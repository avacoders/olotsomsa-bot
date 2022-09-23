<?php


namespace App\Helpers;


use App\Models\Category;
use App\Models\Chat;
use App\Models\Location;
use App\Models\Message;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\Status;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Napa\R19\Sms;

class Telegram
{


    protected $http;
    protected $bot;
    const url = "https://api.telegram.org/bot";

    public function __construct($bot)
    {
        $this->http = new Http();
        $this->bot = $bot;
    }

    public function settings($user)
    {
        $text = "🔧 ".lang($user->language_code,"settings")." \n\n";
        $text .= "👤 ".lang($user->language_code, "name").": " . $user->name . "\n";
        $text .= "📞 ".lang($user->language_code, "telefon").": " . $user->phone_number . "\n" ?? "No'malum" . "\n";
        $text .= "🔄 ".lang($user->language_code, "til").": " . $user->lang . "\n";
        $text .= "🆔 ID: " . $user->telegram_id . "\n";
        $user->status_id = Status::GET[Status::NORMAL];
        $user->save();

        $buttons = [
            "inline_keyboard" => [
                [
                    [
                        "text" => "📝 ".lang($user->language_code, "change_til"),
                        "callback_data" => "change_lang"
                    ]
                ],
                [
                    [
                        "text" => "📝 ".lang($user->language_code, "change_phone"),
                        "callback_data" => "phone"
                    ]
                ],
                [
                    [
                        "text" => "📝 ".lang($user->language_code, "change_name"),
                        "callback_data" => "name"
                    ]
                ],
                [
                    [
                        "text" => "🗒 ".lang($user->language_code, "history"),
                        "callback_data" => "history"
                    ]
                ]

            ]
        ];
        $this->sendButtons($user->telegram_id, $text, json_encode($buttons));
    }


    public function lang($user)
    {
        $text = "TILNI TANLANG    //   ВЫБЕРИТЕ ЯЗЫК\n\n";
        $buttons = [
            "remove_keyboard" => true,
            "inline_keyboard" => [
                [
                    [
                        "text" => "🇺🇿 O'zbekcha",
                        "callback_data" => "lang|uz"
                    ],
                    [
                        "text" => "🇷🇺 Русский",
                        "callback_data" => "lang|ru"
                    ]
                ]
            ]
        ];
        $this->sendButtons($user->telegram_id, $text, json_encode($buttons));
    }


    public function changeLang($user, $id, $message_id)
    {
        $this->deleteMessage($user->telegram_id, $message_id);
        $user->language_code = $id;
        $user->save();
        $this->sendMenu($user);
    }


    public function sendConfirmation(Request $request)
    {
        DB::beginTransaction();

        $user = User::where('telegram_id', $request->user['id'])->first();
        try {
            $order = Order::create([
                'user_id' => $user->id,
                'status_id' => Order::STATUS_NEW,
                'type' => 0
            ]);
            foreach ($request->orders as $key => $item) {
                if (isset($item['quantity']) && $item['quantity']) {
                    Log::debug(Product::find($key));
                    $order_product = [
                        'product_id' => (int)$key,
                        'order_id' => $order->id,
                        'status_id' => OrderProduct::STATUS_BASKET,
                        'quantity' => $item['quantity'],
                    ];
                    OrderProduct::create($order_product);
                }

            }


            $order_products = $order->order_products()->where('status_id', OrderProduct::STATUS_BASKET)->get();

            if (count($order_products)) {
                $text = "";

                $sum = 0;
                $price = 1;
                foreach ($order_products as $product) {
                    $price = $product->product->price * $product->quantity;
                    $sum += $price;
                    $text .= "\t <b>" . $product->product->name . "</b>  $product->quantity x " . $product->product->price . " = " . $price . " so'm \n";
                }

                $text .= "\n $sum so'm \n";

                $buttons = [
                    'inline_keyboard' => []
                ];
                $buttons['inline_keyboard'][] = $this->makeButton(lang('uz', 'confirm'), 'confirm|' . $order->id);
                $this->sendButtons($user->telegram_id, $text, json_encode($buttons));
            } else {
                $this->sendMessage($user->telegram_id, lang('uz', 'empty'));
            }

            DB::commit();
            return response()->json(['ok' => true]);
        } catch (\Exception $exception) {
//            $this->sendMenu($user);
            Log::debug($exception);
            DB::rollBack();
            return response()->json(['ok' => false]);

        }


    }

    public function checkChatStatus($user, $status)
    {
        return $user->status_id == Status::GET[$status];
    }

    public function saveChatName($user, $text)
    {
        $user->name = $text;
        $user->status_id = Status::GET[Status::PHONE_NUMBER];
        $user->save();
        $this->sendContactRequest($user);
    }

    public function checkVerification($user, $text)
    {
        if ($user->verification_code == $text) {
            $user->status_id = Status::GET[Status::NORMAL];
            $user->save();
            $this->sendMenu($user);
        } else {
            $this->sendMessage($user->telegram_id, lang("uz", 'error'));
        }

    }

    public function checkVerification1($user, $text)
    {
        if ($user->verification_code == $text) {
            $user->status_id = Status::GET[Status::LOCATION_SELECT];
            $user->save();
            $this->location($user, 0, 1);
        } else {
            $this->sendMessage($user->telegram_id, lang("uz", 'error'));
        }

    }

    public function sendSelect($user, $message_id, $product, $quantity)
    {
        $text = lang("uz", 'select') . ": <b>$product->name</b>";
        $text .= "\nNarxi: $product->price so'm";
        $text .= "\n<a href='$product->image'>___</a>";
        $text .= "\n" . lang("uz", 'select_no');
        $buttons = [
            'inline_keyboard' => []
        ];
        $row = [];
        $row[] = [
            "text" => "Menyudan tanlang: $quantity",
            'callback_data' => "nothing|0|$product->id"
        ];
        $buttons['inline_keyboard'][] = $row;
        $row = [];
        for ($i = 1; $i < 10; $i++) {
            $row[] = [
                "text" => $i,
                'callback_data' => "number|$i|$product->id"
            ];

            if ($i % 3 == 0 || $i == 10) {
                $buttons['inline_keyboard'][] = $row;
                $row = [];
            }
        }

        $row = [
            [
                "text" => "0",
                "callback_data" => "number|0|$product->id"
            ],
            [
                "text" => "🗑",
                "callback_data" => "trash|$product->id"
            ],
        ];

        $buttons['inline_keyboard'][] = $row;
        $buttons['inline_keyboard'][] = $this->makeButton(lang("uz", 'addToCart'), "addToCart|$product->id");
        $buttons['inline_keyboard'][] = $this->makeButton("🔙 Ortga", "category|2");

        $this->editMessage($user->telegram_id, $message_id, $text, json_encode($buttons));
    }

    public function selectProduct($user, $message_id, $product)
    {
        try {
            $product = Product::find($product);
            $order1 = [
                'user_id' => $user->telegram_id,
                'status_id' => Order::STATUS_NEW,
                'type' => 0
            ];
            $order = $user->orders()->where('status_id', Order::STATUS_NEW)->latest()->first();
            if (!$order)
                $order = Order::create($order1);


            $order_product = OrderProduct::query()->where('order_id', $order->id)->where('product_id', $product->id)
                ->first();
            if (!$order_product)
                $order_product = OrderProduct::create([
                    "order_id" => $order->id,
                    "product_id" => $product->id,
                    "quantity" => 0,
                    "status_id" => OrderProduct::STATUS_SELECT
                ]);

            $quantity = $order_product ? $order_product->quantity : 0;

            $this->sendSelect($user, $message_id, $product, $quantity);
            DB::commit();
        } catch (\Exception $exception) {
            $this->sendMenu($user);
            DB::rollBack();
        }

    }

    public function selectNumber($user, $message_id, $number, $product)
    {
        DB::beginTransaction();
        try {
            $product = Product::findOrFail($product);

            $order = $user->orders()->where('status_id', Order::STATUS_NEW)->latest()->first();
            $order_product = OrderProduct::query()->where('order_id', $order->id)->where('product_id', $product->id)->first();
            if ($order_product) {
                $order_product->quantity = $order_product->quantity == 0 ? $number : $order_product->quantity . $number;
                $order_product->save();
            } else
                $order_product = OrderProduct::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'status_id' => OrderProduct::STATUS_SELECT,
                    'quantity' => $number
                ]);

            $this->sendSelect($user, $message_id, $product, $order_product->quantity);
            DB::commit();
        } catch (\Exception $exception) {
            $this->sendMenu($user->telegram_id);
            DB::rollBack();
        }
    }

    public function eraseNumber($user, $message_id, $product)
    {
        DB::beginTransaction();

        try {
            $product = Product::findOrFail($product);

            $order = $user->orders()->where('status_id', Order::STATUS_NEW)->latest()->first();
            $order_product = OrderProduct::query()->where('order_id', $order->id)->where('product_id', $product->id)->first();
            if ($order_product) {
                $order_product->quantity = strlen((string)$order_product->quantity) == 1 ? 0 : substr((string)$order_product->quantity, 0, -1);
                $order_product->save();
            } else
                $order_product = OrderProduct::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'status_id' => OrderProduct::STATUS_SELECT,
                    'quantity' => 0
                ]);

            $this->sendSelect($user, $message_id, $product, $order_product->quantity);
            DB::commit();
        } catch (\Exception $exception) {
            $this->sendMenu($user);
            DB::rollBack();
        }


    }

    public function addToCart($user, $message_id, $product, $callback_query_id)
    {
        DB::beginTransaction();
        try {
            $product = Product::findOrFail($product);

            $order = $user->orders()->where('status_id', Order::STATUS_NEW)->latest()->first();
            $order_product = OrderProduct::query()->where('order_id', $order->id)->where('product_id', $product->id)
                ->first();
            if ($order_product && $order_product->quantity != 0) {
                $order_product->status_id = OrderProduct::STATUS_BASKET;
                $order_product->save();
                $this->deleteMessage($user->telegram_id, $message_id);
                $this->sendMenu($user);
            } else {
                if ($order_product)
                    $order_product->delete();
                $this->answerCallbackQuery($callback_query_id, lang("uz", 'select_no'));
                return 0;
            }
            DB::commit();
        } catch (\Exception $exception) {
            $this->sendMenu($user);
            DB::rollBack();
        }

    }

    public function showCart($user, $message_id, $callback_query_id)
    {
        DB::beginTransaction();
        try {
            $order = $user->orders()->where("status_id", Order::STATUS_NEW)->latest()->first();
            if (!$order) {
                $this->answerCallbackQuery($callback_query_id, lang("uz", 'empty'));
                return 1;
            }


            $order_product = OrderProduct::query()
                ->where('order_id', $order->id)
                ->where('status_id', OrderProduct::STATUS_BASKET)->get();

            if (count($order_product)) {
                $text = "";

                $sum = 0;
                $price = 1;
                foreach ($order_product as $product) {
                    $price = $product->product->price * $product->quantity;
                    $sum += $price;
                    $text .= "\t <b>" . $product->product->name . "</b>  $product->quantity x " . $product->product->price . " = " . $price . " so'm \n";
                }

                $text .= "\n $sum so'm \n";

                $buttons = [
                    'inline_keyboard' => []
                ];
                foreach ($order_product as $key => $product) {
                    $row = [
                        [
                            'text' => "-",
                            'callback_data' => 'minus|' . $product->id
                        ],
                        [
                            'text' => $product->product->name,
                            'callback_data' => 'nothing|' . $product->id
                        ],
                        [
                            'text' => "+",
                            'callback_data' => 'plus|' . $product->id
                        ]
                    ];
                    $buttons['inline_k  eyboard'][] = $row;
                }
                $buttons['inline_keyboard'][] = $this->makeButton(lang("uz", 'confirm'), 'confirm|1');
                $buttons['inline_keyboard'][] = $this->makeButton(lang("uz", 'yana'), 'menu|1');
                $this->editMessage($user->telegram_id, $message_id, $text, json_encode($buttons));
            } else {
                $this->answerCallbackQuery($callback_query_id, lang("uz", 'empty'));
            }
            DB::commit();
        } catch (\Exception $exception) {
            $this->sendMenu($user);
            DB::rollBack();
        }

    }


    public function history($user, $message_id, $callback_query_id)
    {

        DB::beginTransaction();

        try {

            $orders = $user->orders()->where("status_id", Order::STATUS_COMPLETE)->get();

            if (!count($orders)) {
                $this->answerCallbackQuery($callback_query_id, lang("uz", 'no_orders'));
                return 1;
            }
            $this->deleteMessage($user->telegram_id, $message_id);
            foreach ($orders as $order) {

                $order_product = OrderProduct::query()
                    ->where('order_id', $order->id)
                    ->where('status_id', OrderProduct::STATUS_BASKET)->get();
                $text = "Buyurtma № $order->id \n";
                $text .= "Buyurtma vaqti: $order->created_at \n";

                $sum = 0;
                $price = 1;
                $i = 0;
                foreach ($order_product as $product) {
                    $i++;
                    $price = $product->product->price * $product->quantity;
                    $sum += $price;
                    $text .= "$i. <b>" . $product->product->name . "</b>  $product->quantity x " . $product->product->price . " so'm = " . $price . " so'm \n";
                }
                $text .= "\n" . lang("uz", 'general') . ": $sum so'm \n";
                $this->sendMessage($user->telegram_id, $text);
            }


            DB::commit();
        } catch (\Exception $exception) {
            Log::debug($exception);
            $this->sendMenu($user);
            DB::rollBack();
        }


        return 1;
    }


    public function confirm($user, $message_id)
    {
        try {

            $user->status_id = Status::GET[Status::ENTER_NAME];
            $user->save();
            if ($user->phone_number)
                $this->location($user, $message_id, 1);
            else
                $this->askPhone($user, $message_id, Status::GET[Status::ASK_PHONE]);
            $this->deleteMessage($user->telegram_id, $message_id);
        } catch (\Exception $exception) {
            Log::debug($exception);
        }


    }

    public function reLocation($user)
    {
        DB::beginTransaction();
        try {
            if ($user->status_id == Status::GET[Status::LOCATION_VERIFY]) {
                $location = [
                    'text' => lang("uz", 'geolocation'),
                    'request_location' => true
                ];
                $buttons = [
                    'keyboard' => [],
                    'resize_keyboard' => true,
                ];
                $buttons['keyboard'][] = [$location];
                $user->status_id = Status::GET[Status::LOCATION_REQUEST];
                $user->save();
                $this->sendButtons($user->telegram_id, lang("uz", 'location_text'), json_encode($buttons));
            } else {
                $this->sendMenu($user);
            }
            DB::beginTransaction();
        } catch (\Exception $exception) {
            $this->sendMenu($user);
            Log::debug($exception);
            DB::rollBack();
        }


    }

    public function back($user)
    {
        $this->sendMenu($user);

    }


    public function location($user, $message_id, $id)
    {
        DB::beginTransaction();
        try {
            if ($user->status_id == Status::GET[Status::LOCATION_SELECT] || $user->status_id == Status::GET[Status::PAYMENT]) {
                $location = [
                    'text' => lang("uz", 'geolocation'),
                    'request_location' => true
                ];
                $buttons = [
                    'keyboard' => [],
                    'resize_keyboard' => true,
                ];
                $buttons['keyboard'][] = [$location];
                $order = $user->orders()->where('status_id', Order::STATUS_NEW)->latest()->first();
                $order->type = $id;
                $order->save();
                $user->status_id = Status::GET[Status::LOCATION_REQUEST];
                $user->save();
                if ($message_id)
                    $this->deleteMessage($user->telegram_id, $message_id);
                $this->sendButtons($user->telegram_id, lang("uz", 'location_text'), json_encode($buttons));
            } else {
                $this->sendMenu($user);
            }
            DB::commit();
        } catch (\Exception $exception) {
            Log::debug($exception);
            $this->sendMenu($user);
            DB::rollBack();

        }
    }

    public function verifyLocation($user, $location)
    {
        DB::beginTransaction();
        try {
            $geolocation = new Geolocation();
            $address = $geolocation->Get_Address_From_Google_Maps($location['latitude'], $location['longitude']);
            // Log::debug($address);
            $order = $user->orders()->where("status_id", Order::STATUS_NEW)->latest()->first();
            if ($order) {
                $location1 = Location::create([
                    'user_id' => $user->telegram_id,
                    'longitude' => $location['longitude'],
                    'latitude' => $location['latitude'],
                    'text' => $address,
                ]);

                $l['location_id'] = (int)$location1->id;
                $order->update($l);


                $contact = [
                    [['text' => lang("uz", 'back')]],
                ];
                $buttons = [
                    'keyboard' => $contact,
                    'resize_keyboard' => true,
                ];


                $user->status_id = Status::GET[Status::COMMENT];
                $user->save();
                $text = lang("uz", 'your_address') . ": $location1->text \n\n";
                $text .= lang("uz", 'correct_address');

                $this->sendButtons($user->telegram_id, $text, json_encode($buttons));
            } else {
                $this->sendMenu($user);
            }
            DB::commit();
        } catch (\Exception $exception) {
            $this->sendMenu($user);
            DB::rollBack();
            Log::debug($exception);

        }


    }

    public function comment($user)
    {
        DB::beginTransaction();
        try {
            $location = [
                [
                    'text' => lang("uz", "next")
                ],
                [
                    'text' => lang("uz", 'back')
                ]
            ];
            $buttons = [
                'keyboard' => [],
                'resize_keyboard' => true,
            ];
            $user->status_id = Status::GET[Status::COMMENT];
            $user->save();
            $buttons['keyboard'][] = $location;
            $this->sendButtons($user->telegram_id, "BIROR GAPINGIZ BO'LSA YOZING", json_encode($buttons));
            DB::commit();
        } catch (\Exception $exception) {
            $this->sendMenu($user);
            DB::rollBack();
            Log::debug($exception);
        }

    }




    public function base($user, $data)
    {
        DB::beginTransaction();
        try {
            Log::debug($user);
            if ($user) {
                if ($user->status_id == Status::GET[Status::COMMENT]) {
                    $order = $user->orders()->where('status_id', Order::STATUS_NEW)->latest()->first();

                    $order->comment = $data["message"]['text'];
                    $order->save();
                    $this->checkOrder($user);
                    return 1;
                }
                if ($this->checkChatStatus($user, Status::NAME)) {
                    $this->saveChatName($user, $data["message"]['text']);
                    return 1;
                }
                if ($user->status_id == Status::GET[Status::PHONE_NUMBER]) {
                    $this->sendVerification($user, $data["message"]['text']);
                    return 1;
                }
                if ($user->status_id == Status::GET[Status::ASK_NAME]) {
                    $this->setName($user, $data["message"]['text'], Status::GET[Status::ASK_NAME]);
                    return 1;
                }
                if ($user->status_id == Status::GET[Status::ENTER_NAME]) {
                    $this->setName($user, $data["message"]['text'], Status::GET[Status::ENTER_NAME]);
                    return 1;
                }
                if ($user->status_id == Status::GET[Status::ASK_PHONE]) {
                    $this->setName($user, $data["message"]['text'], Status::GET[Status::ASK_PHONE]);
                    return 1;
                }
                if ($data["message"]['text'] == "/restore") {
                    $orders = $user->orders()->where('status_id', Order::STATUS_IN_PROGRESS)->whereHas('location')->latest()->get();
                    foreach ($orders as $order) {
                        $text = $this->makeText($order);
                        $buttons = $this->buttons($order);
                        $this->sendButtons(env("TELEGRAM_ORDER_ID"), $text, json_encode($buttons));

                    }
                }

                if ($this->checkChatStatus($user, Status::VERIFICATION)) {
                    $this->checkVerification($user, $data["message"]['text']);
                    return 1;
                }

                if ($this->checkChatStatus($user, Status::VERIFICATION1)) {
                    $this->checkVerification1($user, $data["message"]['text']);
                    return 1;
                }
            }

            DB::commit();
        } catch (\Exception $exception) {
            Log::debug($exception);

            DB::rollBack();
        }
        return 0;

    }


    public function payment($user, $message_id, $id)
    {
        DB::beginTransaction();

        try {
            $order = $user->orders()->where('status_id', Order::STATUS_NEW)->latest()->first();

            if ($order) {
                $order->status_id = Order::STATUS_IN_PROGRESS;
                $order->delivery_minute = 5;
                $order->delivery_price = 5000;
                $order->posuda = 0;
                $order->type = $id;
                $order->save();
                $text = "$order->id  raqamli buyurtmangiz qabul qilindi! Iltimos, operator javobini kuting. Buyurtmangizni yetkazib berish vaqti va pulini tez orada ma'lum qilamiz!  ";
                $this->deleteMessage($user->telegram_id, $message_id);
                $this->sendMessage($user->telegram_id, $text);
                $text = $this->makeText($order);
                $buttons = $this->buttons($order);

                $this->sendButtons(env("TELEGRAM_ORDER_ID"), $text, json_encode($buttons));


            } else {
                $this->sendMenu($user);
            }

            DB::commit();

        } catch (\Exception $exception) {
            DB::rollBack();
            Log::debug($exception);
        }
    }

    public function receive($message_id, $id)
    {
        $order = Order::find($id);
        $user = $order->user;

        if ($order->status_id != Order::STATUS_COMPLETE) {
            $text = "Buyurtmangiz tayyorlanish jarayonida. Sizga $order->delivery_minute daqiqada yetkazib beramiz.";
            $text .= "\nDOSTAVKA <b>$order->delivery_price 💵</b>\n";
            $text .= "\nPOSUDA <b><i>$order->posuda</i></b> 💵 \n";
            $order_product = OrderProduct::query()
                ->where('order_id', $order->id)
                ->where('status_id', OrderProduct::STATUS_BASKET)->get();
            if (count($order_product)) {

                $sum = 0;
                $price = 1;
                foreach ($order_product as $product) {
                    $price = $product->product->price * $product->quantity;
                    $sum += $price;

                    $text .= "<b>" . $product->product->name . "</b>  $product->quantity x " . $product->product->price . " = " . $price . " so'm \n";
                }

                $sum += $order->delivery_price;
                $sum += $order->posuda;
                $text .= "\n<b>Umumiy</b>: $sum 💵";
                $text .= "\n<b>☎️</b>: $user->phone_number";
                $text .= "\n<b>IZOH</b>: $order->comment";

                $this->sendMessage($user->telegram_id, $text);
                $this->changeOrderStatus($message_id, $id, Order::STATUS_COMPLETE);
            }
        }


    }

    public function cancel($message_id, $id)
    {
        $order = Order::find($id);
        $user = $order->user;

        $text = "Buyurtmangiz bekor qilindi!\nSabab: Biz ish vaqtida emasmiz. Iltimos keyinroq urinib ko'ring";

        $this->sendMessage($user->telegram_id, $text);
        $this->changeOrderStatus($message_id, $id, Order::STATUS_CANCEL);
    }

    public function cancelSite($message_id, $id, $needle)
    {
        $order = Order::find($id);
        $user = $order->user;
        if ($needle == "cancel") {
            $text = "Buyurtmangiz bekor qilindi!\nSabab: Biz ish vaqtida emasmiz. Iltimos keyinroq urinib ko'ring";
            $status = Order::STATUS_CANCEL;
        } else {
            $text = "Buyurtmangiz tayyorlanish jarayonida. Sizga $order->delivery_minute daqiqada yetkazib beramiz.";
            $status = Order::STATUS_COMPLETE;
        }


        Sms::send($order->phone_number, $text);
        $this->changeOrderStatusSite($message_id, $id, $status);
    }

    public function minute($message_id, $id, $expression, $quantity)
    {
        $order = Order::find($id);

        $order->delivery_minute = $quantity;
        $order->save();

        $text = $this->makeText($order);
        $buttons = $this->buttons($order);
        $this->editMessage(env("TELEGRAM_ORDER_ID"), $message_id, $text, json_encode($buttons));

    }

    public function getLocation($message_id, $id)
    {
        $order = Order::find($id);

        $result = $this->http::post(self::url . $this->bot . '/sendLocation', [
            'chat_id' => env("TELEGRAM_ORDER_ID"),
            'latitude' => $order->location->latitude,
            'longitude' => $order->location->longitude,
        ]);
    }

    public function changeOrderStatusSite($message_id, $id, $status)
    {
        $order = Order::find($id);

        $order->status_id = $status;
        $order->save();
        $text = $this->makeTextFromSite($order);
        $this->deleteMessage(env("TELEGRAM_ORDER_ID"), $message_id);
        $this->sendMessage(env("TELEGRAM_ORDER_ID"), $text);
    }

    public function changeOrderStatus($message_id, $id, $status)
    {
        $order = Order::find($id);

        $order->status_id = $status;
        $order->save();
        $text = $this->makeText($order);

        $buttons['inline_keyboard'][] = [
            [
                'text' => "Lokatsiya olish",
                'callback_data' => "location|$order->id|10000",
            ],
        ];
        $this->deleteMessage(env("TELEGRAM_ORDER_ID"), $message_id);
        $this->sendMessageWithButtons(env("TELEGRAM_ORDER_ID"), $text, json_encode($buttons));
    }

    public function price($message_id, $id, $expression, $quantity)
    {
        $order = Order::find($id);
        if ($expression == "minus")
            $order->delivery_price = $quantity ? $order->delivery_price + 1000 : $order->delivery_price - 1000;
        else
            $order->delivery_price = $quantity;
        $order->save();
        $text = $this->makeText($order);
        $buttons = $this->buttons($order);
        $this->editMessage(env("TELEGRAM_ORDER_ID"), $message_id, $text, json_encode($buttons));
    }

    public function posuda($message_id, $id, $expression, $quantity)
    {
        $order = Order::find($id);

        switch ($expression) {
            case "set":
                $order->posuda = $quantity;
                break;
            case "add":
                $order->posuda = $order->posuda + 1000;
                break;
            case "sub":
                $order->posuda = $order->posuda > 1000 ? $order->posuda - 1000 : $order->posuda;
                break;
        }
        $order->save();

        $text = $this->makeText($order);
        $buttons = $this->buttons($order);
        $this->editMessage(env("TELEGRAM_ORDER_ID"), $message_id, $text, json_encode($buttons));

    }

    public function buttons($order)
    {

        $buttons['inline_keyboard'][] = [
            [
                'text' => "30 min",
                'callback_data' => "addMinute|$order->id|30",
            ],
            [
                'text' => "60 min",
                'callback_data' => "addMinute|$order->id|60",
            ],
            [
                'text' => "90 min",
                'callback_data' => "addMinute|$order->id|90",
            ]
        ];
        $buttons['inline_keyboard'][] = [
            [
                'text' => "DOSTAVKA SUMMASI",
                'callback_data' => "1|2|1",
            ],
        ];
        $buttons['inline_keyboard'][] = [
            [
                'text' => "10000",
                'callback_data' => "addPrice|$order->id|10000",
            ],
            [
                'text' => "20000",
                'callback_data' => "addPrice|$order->id|20000",
            ],
            [
                'text' => "+1000",
                'callback_data' => "subPrice|$order->id|20000",
            ],
            [
                'text' => "-1000",
                'callback_data' => "subPrice|$order->id",
            ]
        ];
        $buttons['inline_keyboard'][] = [

        ];
        $buttons['inline_keyboard'][] = [
            [
                'text' => "POSUDA QO'SHISH",
                'callback_data' => "1|2|1",
            ],
        ];
        $buttons['inline_keyboard'][] = [
            [
                'text' => "2000",
                'callback_data' => "posuda|$order->id|2000",
            ],
            [
                'text' => "6000",
                'callback_data' => "posuda|$order->id|6000",
            ],
            [
                'text' => "+1000 ",
                'callback_data' => "addPosuda|$order->id|1000",
            ],
            [
                'text' => "-1000",
                'callback_data' => "subPosuda|$order->id|1000",
            ]
        ];
        $buttons['inline_keyboard'][] = [
            [
                'text' => lang('uz', "confirm"),
                'callback_data' => "receive|$order->id",
            ],
            [
                'text' => "BEKOR QIL ❎",
                'callback_data' => "cancel|$order->id",
            ]
        ];
        $buttons['inline_keyboard'][] = [
            [
                'text' => "Lokatsiya olish",
                'callback_data' => "location|$order->id|10000",
            ],
        ];
        return $buttons;
    }

    public function buttonsFromSite($order)
    {

        $buttons['inline_keyboard'][] = [
            [
                'text' => "30 min",
                'callback_data' => "addMinute|$order->id|30",
            ],
            [
                'text' => "60 min",
                'callback_data' => "addMinute|$order->id|60",
            ],
            [
                'text' => "90 min",
                'callback_data' => "addMinute|$order->id|90",
            ]
        ];
        $buttons['inline_keyboard'][] = [
            [
                'text' => "DOSTAVKA SUMMASI",
                'callback_data' => "1|2|1",
            ],
        ];
        $buttons['inline_keyboard'][] = [
            [
                'text' => "10000",
                'callback_data' => "addPrice|$order->id|10000",
            ],
            [
                'text' => "20000",
                'callback_data' => "addPrice|$order->id|20000",
            ],
            [
                'text' => "+1000",
                'callback_data' => "subPrice|$order->id|20000",
            ],
            [
                'text' => "-1000",
                'callback_data' => "subPrice|$order->id",
            ]
        ];
        $buttons['inline_keyboard'][] = [

        ];
        $buttons['inline_keyboard'][] = [
            [
                'text' => "POSUDA QO'SHISH",
                'callback_data' => "1|2|1",
            ],
        ];
        $buttons['inline_keyboard'][] = [
            [
                'text' => "2000",
                'callback_data' => "posuda|$order->id|2000",
            ],
            [
                'text' => "6000",
                'callback_data' => "posuda|$order->id|6000",
            ],
            [
                'text' => "+1000 ",
                'callback_data' => "addPosuda|$order->id|1000",
            ],
            [
                'text' => "-1000",
                'callback_data' => "subPosuda|$order->id|1000",
            ]
        ];
        $buttons['inline_keyboard'][] = [
            [
                'text' => "Lokatsiya olish",
                'callback_data' => "location|$order->id|10000",
            ],
        ];
        $buttons['inline_keyboard'][] = [
            [
                'text' => lang('uz', "confirm"),
                'callback_data' => "receiveSite|$order->id",
            ],
            [
                'text' => "BEKOR QIL ❎",
                'callback_data' => "cancelSite|$order->id",
            ]
        ];
        return $buttons;
    }

    public function makeTextFromSite($order)
    {

        $user = $order->user;

        $order_product = OrderProduct::query()
            ->where('order_id', $order->id)
            ->where('status_id', OrderProduct::STATUS_BASKET)->get();

        $text = "НОМЕР ЗАКАЗА: $order->id  " . $order->user->name . "\n\n";
        if (count($order_product)) {

            $sum = $order->delivery_price;
            $sum += $order->posuda;

            $price = 1;
            foreach ($order_product as $product) {
                $price = $product->product->price * $product->quantity;
                $sum += $price;

                $text .= "<b>" . $product->product->name . "  $product->quantity x " . $product->product->price . " = " . $price . " so'm </b>\n";
            }

            $text .= "<b>\nМИНУТ: <i>$order->delivery_minute </i>  🚕  ДОСТАВКА";
            $text .= "\nДОСТАВКА 🚀 <i>: $order->delivery_price</i> СЎМ";
            $text .= "\nПОСУДА  <i>$order->posuda</i>  СЎМ";
            $text .= "\n\n☎️️:  +" . $order->user->phone_number;
            $text .= "\n\nАДРЕС 📝:   $order->comment";
            $status = Order::GET[$order->status_id];
            $text .= "\n\nСТАТУС: $status";
            $type = Order::GET_TYPE[$order->type];
            $text .= "\nТЎЛОВ ТУРИ:  $type";
            $text .= "\n\nUMUMIY SUMMA: $sum  СЎМ </b>";
        }
        return $text;
    }

    public function makeText($order)
    {

        $user = $order->user;

        $order_product = OrderProduct::query()
            ->where('order_id', $order->id)
            ->where('status_id', OrderProduct::STATUS_BASKET)->get();

        $location = $order->location->text;
        $text = "ЗАКАЗ: $order->id    $user->name   \n";
        if (count($order_product)) {

            $sum = $order->delivery_price;
            $sum += $order->posuda;

            $price = 1;
            foreach ($order_product as $product) {
                $price = $product->product->price * $product->quantity;
                $sum += $price;

                $text .= "<b>" . $product->product->name . "  $product->quantity x " . $product->product->price . " = " . $price . "</b>\n";
            }

            $link = "https://yandex.uz/maps/?ll=" . $order->location->latitude . "%2C" . $order->location->longitude . "&mode=routes&rtext=~" . $order->location->latitude . "%2C" . $order->location->longitude . "&rtt=pd&ruri=~&z=13.78";
            $text .= "\n<b>🏘 📍: $location <a href='$link'>link</a>";
            $text .= "\n\nМИНУТ: <i>$order->delivery_minute </i>  🚕  ДОСТАВКА";
            $text .= "\nДОСТАВКА 🚀 <i>: $order->delivery_price</i> СЎМ";
            $text .= "\nПОСУДА  <i>$order->posuda</i>  СЎМ";
            $text .= "\n\n☎️️:  +$user->phone_number";
            $text .= "\n\nКОМЕНТАРИ 📝:   $order->comment";
            $status = Order::GET[$order->status_id];
            $text .= "\n\nСТАТУС: $status";
            $type = Order::GET_TYPE[$order->type];
            $text .= "\nТЎЛОВ ТУРИ:  $type";
            $text .= "\n\nЖАМИ СУММА: $sum</b>";
        }
        return $text;
    }


    public function answerCallbackQuery($callback_query_id, $text)
    {
        $result = $this->http::post(self::url . $this->bot . '/answerCallbackQuery',
            [
                'callback_query_id' => $callback_query_id,
                'text' => $text,
                'show_alert' => true
            ]
        );
        return $result;
    }


    public function validatePhoneNumber($user, $phone_number)
    {
        $contact = preg_replace('/[^0-9.]+/', '', $phone_number);
        $code5 =  substr($contact, 0, 5);
        $code5 = preg_replace('/[^0-9.]+/', '', $code5);
        Log::debug($contact);

        if (strlen($contact) != 12 || !in_array($code5, [99890, 99891, 99893, 99894, 99895, 99897, 99899]) || $code5 == 99898) {
            Log::debug(strlen($contact));

            $this->sendMessage($user->telegram_id, "Iltimos, telefon raqamni to'g'ri kiriting! Masalan: 9989012345678");
            $this->sendContactRequest($user);
            return false;
        } else {
            return true;
        }
    }

    public function sendVerification($user, $contact)
    {
        $code = rand(10000, 99999);
        $contact = preg_replace('/[^0-9.]+/', '', $contact);
        $buttons = [
            "remove_keyboard" => true
        ];
        $this->sendButtons($user->telegram_id, lang("uz", "code1") . " +$contact " . lang("uz", "code2"), json_encode($buttons));
        $user->phone_number = $contact;
        $user->verification_code = $code;
        $user->verification_expires_at = now()->addMinutes(5);
        $user->save();
        $text = "SIZNING OLOTSOMSA ORIGINAL BOT UCHUN KODINGIZ: $code";
        Sms::send($contact, $text);

    }


    public function sendMessage($chat_id, $message)
    {

        $result = $this->http::post(self::url . $this->bot . '/sendMessage',
            [
                'chat_id' => $chat_id,
                'text' => $message,
                'parse_mode' => 'html',
                'disable_web_page_preview' => true
            ]
        );
        return $result;
    }


    public function sendPhoto($chat_id, $message)
    {
        $result = $this->http->attach('photo', Storage::get('/public/1.jpg'), 'document.jpg')
            ->post(self::url . $this->bot . '/sendPhoto', [
                'chat_id' => $chat_id,
                'caption' => $message,
            ]);
        return $result;
    }

    public function sendButtons($chat_id, $message, $button)
    {
        $result = $this->http::post(self::url . $this->bot . '/sendMessage', [
            'chat_id' => (int)$chat_id,
            'text' => $message,
            'reply_markup' => $button,
            'parse_mode' => 'html',
            'disable_web_page_preview' => true
        ]);
        return $result;
    }

    public function checkNewUser($user_id)
    {
        $user = User::where('telegram_id', $user_id)->first();


        return $user && $user->phone_number;
    }


    public function sendCategoryProducts($user, $category, $message_id)
    {
        $chat_id = $user->telegram_id;
        $category = Category::find($category);

        $buttons = [
            'inline_keyboard' => []
        ];

        $products = Cache::remember("category-$category", (int)env('APP_TTL'), function () use ($category) {
            return $category->products;
        });


        $row = [];
        foreach ($products as $key => $product) {
            $row[] = [
                'text' => "$product->name",
                'callback_data' => 'product|' . $product->id
            ];
            if ($key % 2 == 1 || $key == $products->count() - 1) {
                $buttons['inline_keyboard'][] = $row;
                $row = [];
            }
        }


        $buttons['inline_keyboard'][] = $this->makeButton(lang("uz", "back"), "menu|1");
        $this->editMessage($chat_id, $message_id, lang("uz", 'section') . " <i>$category->name</i> <a href='$category->image'>$category->emoji</a>", json_encode($buttons));

    }


    public function sendMessageWithPhoto($chat_id, $message, $photo, $buttons)
    {
        $result = $this->http->attach('photo', Storage::get($photo), 'document.jpg')
            ->post(self::url . $this->bot . '/sendPhoto', [
                'chat_id' => $chat_id,
                'caption' => $message,
                'reply_markup' => $buttons
            ]);
        return $result;
    }


    public function makeButton($text, $callback_data)
    {
        $back = [
            [
                'text' => $text,
                'callback_data' => $callback_data
            ]
        ];
        return $back;
    }

    public function makeTwoButtons($user)
    {
        $back = [
            [
                'text' => lang("uz", "history"),
                'callback_data' => "history|1"
            ],
            [
                'text' => lang("uz", "lang"),
                'callback_data' => 'lang|1'
            ],
        ];

        return $back;

    }

    public function backToMenu($user, $message_id)
    {
        $chat_id = $user->telegram_id;
        $buttons = [
            'inline_keyboard' => []
        ];

        $categories = \cache()->remember('categories', (int)env('APP_TTL'), function () {
            return \App\Models\Category::all();
        });

        $row = [];
        foreach ($categories as $key => $category) {
            $row[] = [
                'text' => "$category->emoji $category->name",
                'callback_data' => 'category|' . $category->id
            ];
            if ($key % 2 == 1 || $key == $categories->count() - 1) {
                $buttons['inline_keyboard'][] = $row;
                $row = [];
            }
        }

        $buttons['inline_keyboard'][] = $this->makeButton(lang("uz", 'cart'), 'basket|1');
        $buttons['inline_keyboard'][] = $this->makeTwoButtons($user);
        $this->editMessage($chat_id, $message_id, lang("uz", 'menu'), json_encode($buttons));
    }

    public function deleteMessage($chat_id, $message_id)
    {
        $result = $this->http::post(self::url . $this->bot . '/deleteMessage', [
            'chat_id' => $chat_id,
            'message_id' => $message_id,
        ]);
        return $result;
    }


    public function sendMessageWithButtons($chat_id, $message, $buttons)
    {
        $result = $this->http::post(self::url . $this->bot . '/sendMessage', [
            'chat_id' => $chat_id,
            'text' => $message,
            'reply_markup' => $buttons,
            'parse_mode' => "html",
            'disable_web_page_preview' => true
        ]);
        return $result;
    }

    public function editMessage($chat_id, $message_id, $message, $buttons)
    {
        $result = $this->http::post(self::url . $this->bot . '/editMessageText', [
            'chat_id' => $chat_id,
            'message_id' => $message_id,
            'text' => $message,
            'reply_markup' => $buttons,
            'parse_mode' => "html",
            'disable_web_page_preview' => true
        ]);
        return $result;
    }

    public function editMessageByInlineId($chat_id, $message_id, $message, $buttons)
    {
        $result = $this->http::post(self::url . $this->bot . '/editMessageText', [
            'chat_id' => $chat_id,
            'inline_message_id' => $message_id,
            'text' => $message,
            'reply_markup' => $buttons,
            'parse_mode' => "html",
            'disable_web_page_preview' => true
        ]);
        return $result;
    }

    public function editMessageText($chat_id, $message, $message_id)
    {
        $result = $this->http::post(self::url . $this->bot . '/editMessageText', [
            'chat_id' => $chat_id,
            'message_id' => $message_id,
            'text' => $message,
            "parse_mode" => "html"
        ]);
        return $result;
    }

    public function editMessageReplyMarkup($chat_id, $buttons, $message_id)
    {
        $result = $this->http::post(self::url . $this->bot . '/editMessageReplyMarkup', [
            'chat_id' => $chat_id,
            'message_id' => $message_id,
            'reply_markup' => $buttons,
            "disable_web_page_preview" => true

        ]);
        return $result;
    }


    public function sendContactRequest($user)
    {
        $chat_id = $user->telegram_id;
        $contact = [
            'text' => lang("uz", 'phone'),
            'request_contact' => true
        ];
        $buttons = [
            'keyboard' => [],
            'resize_keyboard' => true,
        ];
        $buttons['keyboard'][] = [$contact];
        $user->status_id = Status::GET[Status::PHONE_NUMBER];
        $user->save();

        $this->sendMessage($chat_id, lang("uz", 'ask_phone1'));
        $this->sendButtons($chat_id, lang("uz", 'ask_phone2') . ": 998901234567", json_encode($buttons));
    }

    public function setStatus($chat_id, $status)
    {
        $user = User::where('telegram_id', $chat_id)->first();;
        $user->status_id = Status::GET[$status];
        $user->save();
    }


    public function sendNameRequest($user)
    {
        $this->setStatus((int)$user->telegram_id, Status::NAME);
        $this->sendMessage($user->telegram_id, lang('uz', 'hi'));
    }


    public function saveData($data)
    {
        try {
            DB::beginTransaction();
            $data = isset($data['message']) ? $data['message'] : $data['callback_query'];

            $user = $data['from'];
            $user1['name'] = $user["first_name"];
            $user1['no_name'] = 0;
            $user1['telegram_id'] = $user["id"];
            $user1['is_bot'] = false;
            $user1['role_id'] = false;
            $user1['password'] = bcrypt('secret');

            $existing_user = User::where('telegram_id', $user1['telegram_id'])->first();
            if (!$existing_user) {
                $existing_user = User::create($user1);
            }
            DB::commit();
            return $existing_user;

        } catch
        (\Exception $exception) {
            Log::debug($exception);
            DB::rollBack();
            return $exception;

        }


    }


}
