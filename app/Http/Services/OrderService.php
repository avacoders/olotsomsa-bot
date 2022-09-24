<?php

namespace App\Http\Services;

use App\Helpers\Geolocation;
use App\Helpers\Telegram;
use App\Models\Location;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Status;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderService
{
    protected $telegram;
    protected $exception_commands = ["lang", "confirm", "payment", "history", "change_lang", "phone"];

    public function __construct()
    {
        $this->telegram = new Telegram(config('bots.bot'));
    }

    public function answer($user, $message, $data)
    {
        $call_id = isset($data['callback_query']) ? $data['callback_query']['message']['chat']['id'] : '';
        $chat_id = isset($data['message']) ? $data["message"]['chat']['id'] : $call_id;
        $user = $user ?? $this->telegram->saveData($data);
        if ($message == "/start") {
            if ($user)
                $this->sendMenu($user);
            else
                $this->askLang($chat_id);
            return 1;
        }
        if ($message == "/settings")
        {
            $this->telegram->settings($user);
            return 1;
        }
        $this->answerByStatus($user, $message, $data);

    }

    public function callback($user, $data)
    {
        $query = $data['callback_query'];
        $user = $user ?? $this->telegram->saveData($data);
        $commands = explode('|', $query['data']);
        $command = $commands[0];
        $id = isset($commands[1]) ? $commands[1] : null;
        $product = isset($commands[2]) ? $commands[2] : '';
        $message_id = $query['message']['message_id'];
        $callback_id = $query['id'];
        if (in_array($command, $this->exception_commands))
            $this->telegram->deleteMessage($query['message']['chat']['id'], $message_id);
        switch ($command) {
            case 'lang':
                $this->setLang($user, $id);
                break;
            case "confirm":
                $this->confirm($user, $id);
                break;
            case "payment":
                $this->payment($user, $id);
                break;
            case "addMinute":
                $this->telegram->minute($message_id, $id, "plus", $product);
                break;
            case "addPrice":
                $this->telegram->price($message_id, $id, "plus", $product);
                break;
            case "subPrice":
                $this->telegram->price($message_id, $id, "minus", $product);
                break;
            case "receive":
                $this->telegram->receive($message_id, $id);
                break;
            case "cancel":
                $this->telegram->cancel($message_id, $id);
                break;
            case "posuda":
                $this->telegram->posuda($message_id, $id, 'set', $product);
                break;
            case "addPosuda":
                $this->telegram->posuda($message_id, $id, 'add', $product);
                break;
            case "subPosuda":
                $this->telegram->posuda($message_id, $id, 'sub', $product);
                break;
            case "location":
                $this->telegram->getLocation($message_id, $id,);
                break;
            case "cancelSite":
                $this->telegram->cancelSite($message_id, $id, "cancel");
                break;
            case "receiveSite":
                $this->telegram->cancelSite($message_id, $id, $product);
                break;
            case "history":
                $this->telegram->history($user, $message_id, $callback_id);
                break;
            case "name":
                $this->askNameForChange($user);
                break;
            case "phone":
                $this->askPhoneForChange($user);
                break;
            case "change_lang":
                $this->askLang($user->telegram_id);
                break;
        }
    }

    public function answerByStatus($user, $message, $data)
    {
        $user = $user ?? $this->telegram->saveData($data);
        switch ($user->status_id) {
            case Status::GET[Status::NAME]:
                $this->setNameAndContinue($user, $message);
                break;
            case Status::GET[Status::PHONE_NUMBER]:
                $this->setPhoneNumberAndContinue($user, $message);
                break;
            case Status::GET[Status::VERIFICATION]:
                $this->setVerificationAndContinue($user, $message);
                break;
            case Status::GET[Status::COMMENT]:
                $this->setComment($user, $message);
                break;
            case Status::GET[Status::ASK_NAME]:
                $this->setName($user, $message);
                break;
            case Status::GET[Status::VERIFICATION1]:
                $this->setVerification($user, $message);
                break;
            case Status::GET[Status::ASK_PHONE]:
                $this->setPhoneNumber($user, $message);
                break;

        }
    }

    public function setName($user, $message)
    {
        $user->name = $message;
        $user->no_name = 1;
        $user->save();
        $this->telegram->settings($user);
    }

    public function setNameAndContinue($user, $message)
    {
        $user->name = $message;
        $user->no_name = 1;
        $user->save();
        if (!$user->phone_number) {
            $this->askPhone($user);
        } else {
            $this->askLocationAndContinue($user);
        }
    }

    public function setPhoneNumberAndContinue($user, $message)
    {
        if ($this->telegram->validatePhoneNumber($user, $message)) {
            $user->phone_number = $message;
            $user->status_id = Status::GET[Status::VERIFICATION];
            $user->save();
            $this->telegram->sendVerification($user, $message);
        }
    }

    public function setPhoneNumber($user, $message)
    {
        if ($this->telegram->validatePhoneNumber($user,$message)) {
            $user->phone_number = $message;
            $user->status_id = Status::GET[Status::VERIFICATION1];
            $user->save();
            $this->telegram->sendVerification($user, $message);
        }
    }

    public function setVerification($user, $message)
    {
        if ($user->verification_code != $message) {
            $text = lang($user->language_code, "error");
            $this->telegram->sendMessage($user->telegram_id, $text);
            return 1;
        }
        if ($user->verification_expires_at < now()) {
            $text = lang($user->language_code, "expired");
            $this->telegram->sendMessage($user->telegram_id, $text);
            return 1;
        }
        $this->telegram->settings($user);
        return 1;

    }

    public function setVerificationAndContinue($user, $message)
    {
        if ($user->verification_code != $message) {
            $text = lang($user->language_code, "error");
            $this->telegram->sendMessage($user->telegram_id, $text);
            return 1;
        }
        if ($user->verification_expires_at < now()) {
            $text = lang($user->language_code, "expired");
            $this->telegram->sendMessage($user->telegram_id, $text);
            return 1;
        }
        $this->askLocationAndContinue($user);
    }

    public function setLocation($user, $location)
    {
        DB::beginTransaction();
        try {
            $geolocation = new Geolocation();
            $address = $geolocation->Get_Address_From_Google_Maps($location['latitude'], $location['longitude']);
            $order = $user->orders()->where("status_id", Order::STATUS_NEW)->latest()->first();
            if ($order) {
                $location1 = Location::create([
                    'user_id' => $user->id,
                    'longitude' => $location['longitude'],
                    'latitude' => $location['latitude'],
                    'text' => $address,
                ]);

                $l['location_id'] = (int)$location1->id;
                $order->update($l);


                $user->status_id = Status::GET[Status::COMMENT];
                $user->save();
                $text = lang($user->language_code, 'your_address') . ": $location1->text \n\n";
                $text .= lang($user->language_code, 'correct_address');
                $buttons = [
                    "remove_keyboard" => true
                ];
                $this->telegram->sendButtons($user->telegram_id, $text, $buttons);
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

    public function setComment($user, $message)
    {
        $order = $user->orders()->where("status_id", Order::STATUS_NEW)->latest()->first();
        if ($order) {
            $order->comment = $message;
            $order->save();
            $this->checkOrder($user);
        } else {
            $this->sendMenu($user);
        }
    }


    public function sendMenu($user)
    {
        $buttons = [
            'inline_keyboard' => [
                [
                    ["text" => lang($user->language_code??"uz", "menu1"),
                        "web_app" => [
                            "url" => "https://telegram.olotsomsa.com/bot/app"
                        ]
                    ]
                ]
            ]
        ];
        $this->telegram->sendMessageWithButtons($user->telegram_id, lang($user->language_code, 'menu'), json_encode($buttons));
    }

    public function askLang($user_id)
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
        $this->telegram->sendMessageWithButtons($user_id, $text, json_encode($buttons));
    }

    public function setLang($user, $lang)
    {
        $user->language_code = $lang;
        $user->save();
        $this->sendMenu($user);
    }


    public function payment($user, $id)
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
                $text = "$order->id  " . lang($user->language_code, 'order') . " \n\n";
                $this->telegram->sendMessage($user->telegram_id, $text);
                $text = $this->makeText($order, $user);
                $buttons = $this->buttons($order);
                $this->telegram->sendMessageWithButtons(env("TELEGRAM_ORDER_ID"), $text, json_encode($buttons));
            } else {
                $this->sendMenu($user);
            }

            DB::commit();

        } catch (\Exception $exception) {
            DB::rollBack();
            Log::debug($exception);
        }
    }


    public function confirm($user, $id)
    {
        if (!$user->no_name) {
            $this->askName($user);
            return 0;
        }
        if (!$user->phone_number) {
            $this->askPhone($user);
            return 0;
        }
        $this->askLocationAndContinue($user);
    }

    public function askName($user)
    {
        $text = "Ismingizni kiriting";
        $user->status_id = Status::GET[Status::NAME];
        $user->save();
        $this->telegram->sendMessage($user->telegram_id, $text);
    }

    public function askNameForChange($user)
    {
        $text = lang($user->language_code, 'hi');
        $user->status_id = Status::GET[Status::ASK_NAME];
        $user->save();
        $this->telegram->sendMessage($user->telegram_id, $text);
    }

    public function askPhoneForChange($user)
    {
        $text = lang($user->language_code, 'ask_phone1')." \n\n".lang($user->language_code, 'ask_phone2')." \n\n998903911755";
        $user->status_id = Status::GET[Status::ASK_PHONE];
        $user->save();
        $contact = [
            'text' => lang($user->language_code, 'phone'),
            'request_contact' => true
        ];
        $buttons = [
            'keyboard' => [],
            'resize_keyboard' => true,
        ];
        $buttons['keyboard'][] = [$contact];
        $this->telegram->sendButtons($user->telegram_id, $text, json_encode($buttons));
    }

    public function askPhone($user)
    {
        $text = lang($user->language_code, 'ask_phone1')." \n\n".lang($user->language_code, 'ask_phone2')." \n\n998903911755";
        $user->status_id = Status::GET[Status::PHONE_NUMBER];
        $user->save();
        $contact = [
            'text' => lang($user->language_code, 'phone'),
            'request_contact' => true
        ];
        $buttons = [
            'keyboard' => [],
            'resize_keyboard' => true,
        ];
        $buttons['keyboard'][] = [$contact];
        $this->telegram->sendButtons($user->telegram_id, $text, json_encode($buttons));    }

    public function askLocationAndContinue($user)
    {
        $user->status_id = Status::GET[Status::LOCATION_SELECT];
        $user->save();
        $location = [
            'text' => lang($user->language_code, 'geolocation'),
            'request_location' => true
        ];
        $buttons = [
            'keyboard' => [],
            'resize_keyboard' => true,
        ];
        $buttons['keyboard'][] = [$location];
        Log::debug($this->telegram->sendMessageWithButtons($user->telegram_id, lang($user->language_code, 'location_text'), json_encode($buttons)));
    }


    public function makeText($order, $user)
    {

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

    public function checkOrder($user)
    {
        DB::beginTransaction();
        try {
            $buttons = [
                "remove_keyboard" => true
            ];
            $this->telegram->sendMessageWithButtons($user->telegram_id, lang($user->language_code, "check"), json_encode($buttons));

            $order = $user->orders()->where("status_id", Order::STATUS_NEW)->latest()->first();

            $order_product = OrderProduct::query()
                ->where('order_id', $order->id)
                ->where('status_id', OrderProduct::STATUS_BASKET)->get();

            $location = $order->location->text;
            if (count($order_product)) {
                $text = "";

                $sum = 0;
                $price = 1;
                foreach ($order_product as $product) {
                    $price = $product->product->price * $product->quantity;
                    $sum += $price;

                    $text .= "\t <b>" . $product->product->name . "</b>  $product->quantity x " . $product->product->price . " = " . $price . " so'm \n";
                }

                $text .= "\n<b>".lang($user->language_code, "general")."</b>: $sum so'm";
                $text .= "\n<b>".lang($user->language_code, "location")."</b>: $location";
                $text .= "\n\n<b>".lang($user->language_code, "telefon")."</b>: $user->phone_number";
                $text .= "\n\n<b>".lang($user->language_code, "comment")."</b>: $order->comment";
                $text .= "\n\n".lang($user->language_code,"payment")."\n";


                $buttons['inline_keyboard'][] = $this->telegram->makeButton(lang($user->language_code, 'confirm3'), 'payment|' . Order::TYPE_CASH);
                $buttons['inline_keyboard'][] = $this->telegram->makeButton(lang($user->language_code, 'confirm2'), 'payment|' . Order::TYPE_CARD);
                $user->status_id = Status::GET[Status::PAYMENT];
                $user->save();
                $this->telegram->sendMessageWithButtons($user->telegram_id, $text, json_encode($buttons));
            }
            DB::commit();
        } catch (\Exception $exception) {
            Log::debug($exception);
            DB::rollBack();
            $this->sendMenu($user);
        }


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


}
