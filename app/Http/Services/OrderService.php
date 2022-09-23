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

    public function __construct()
    {
        $this->telegram = new Telegram(config('bots.bot'));
    }

    public function answer($user, $message, $data)
    {
        $call_id = isset($data['callback_query']) ? $data['callback_query']['message']['chat']['id'] : '';
        $chat_id = isset($data['message']) ? $data["message"]['chat']['id'] : $call_id;
        if ($message == "/start") {
            if ($user)
                $this->sendMenu($user);
            else
                $this->askLang($chat_id);
        }
        $this->answerByStatus($user, $message);

    }

    public function answerByStatus($user, $message)
    {
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

        }
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

    public function setVerificationAndContinue($user, $message)
    {
        if ($user->verification_code != $message) {
            $text = "Kiritilgan kod noto'g'ri. Iltimos qayta kiriting";
            $this->telegram->sendMessage($user, $text);
        }
        if ($user->verification_expires_at < now()) {
            $text = "Kod muddati tugagan. Iltimos qayta kiriting";
            $this->telegram->sendMessage($user, $text);
        }
        Log::debug(123);
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

                $this->telegram->sendMessageWithButtons($user->telegram_id, $text, json_encode($buttons));
            } else {
                $this->sendMenu($user->id);
            }
            DB::commit();
        } catch (\Exception $exception) {
            $this->sendMenu($user->id);
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
        }else{
            $this->sendMenu($user);
        }
    }


    public function sendMenu($user)
    {
        $buttons = [
            'inline_keyboard' => [
                [
                    ["text" => "Buyurtma berish",
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

    public function callback($user, $data)
    {
        $query = $data['callback_query'];
        $user = $user ?? $this->telegram->saveData($data);
        $commands = explode('|', $query['data']);
        $command = $commands[0];
        $id = $commands[1];
        $product = isset($commands[2]) ? $commands[2] : '';
        $message_id = $query['message']['message_id'];
        $this->telegram->deleteMessage($query['message']['chat']['id'], $message_id);
        switch ($command) {
            case 'lang':
                $this->setLang($user, $id);
                break;
            case "confirm":
                $this->confirm($user, $id);
                break;
        }
    }

    public function confirm($user, $id)
    {
        if (!$user->no_name) {
            $this->askName($user);
            return 0;
        }
    }

    public function askName($user)
    {
        $text = "Ismingizni kiriting";
        $user->status_id = Status::GET[Status::NAME];
        $user->save();
        $this->telegram->sendMessage($user->telegram_id, $text);
    }

    public function askPhone($user)
    {
        $text = "Telefon raqamingizni kiriting";
        $user->status_id = Status::GET[Status::PHONE_NUMBER];
        $user->save();
        $this->telegram->sendMessage($user->telegram_id, $text);
    }

    public function askLocationAndContinue($user)
    {
        $user->status_id = Status::GET[Status::LOCATION_SELECT];
        $user->save();
        $location = [
            'text' => lang("uz", 'geolocation'),
            'request_location' => true
        ];
        $buttons = [
            'keyboard' => [],
            'resize_keyboard' => true,
        ];
        $buttons['keyboard'][] = [$location];
        Log::debug($this->telegram->sendMessageWithButtons($user->telegram_id, lang("uz", 'location_text'), json_encode($buttons)));
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
            $this->telegram->sendMessageWithButtons($user->telegram_id, "ILTIMOS BUYURTMANGIZNI YANA BIR BOR KO'ZDAN KECHIRING", json_encode($buttons));

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

                $text .= "\n<b>Umumiy</b>: $sum so'm";
                $text .= "\n<b>Geolokatsiya</b>: $location";
                $text .= "\n\n<b>Telefon raqam</b>: $user->phone_number";
                $text .= "\n\n<b>IZOH</b>: $order->comment";
                $text .= "\n\nTo'lov usulini tanlang\n";


                $buttons['inline_keyboard'][] = $this->telegram->makeButton(lang("uz", 'confirm3'), 'payment|' . Order::TYPE_CASH);
                $buttons['inline_keyboard'][] = $this->telegram->makeButton(lang("uz", 'confirm2'), 'payment|' . Order::TYPE_CARD);
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

}
