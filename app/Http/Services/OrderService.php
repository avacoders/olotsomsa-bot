<?php

namespace App\Http\Services;

use App\Helpers\Telegram;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Status;

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
        switch($user->status_id)
        {
            case Status::GET[Status::NAME]:
                $this->setNameAndContinue($user, $message);
                break;
        }
    }

    public function setNameAndContinue($user, $message)
    {
        $user->name = $message;
        $user->no_name = 1;
        $user->save();
        if(!$user->phone_number)
        {
            $this->askPhone($user);
        }else{
            $this->askLocation($user);
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
        $this->telegram->sendButtons($user->telegram_id, lang($user->language_code, 'menu'), json_encode($buttons));
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
        $this->telegram->sendButtons($user_id, $text, json_encode($buttons));
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
        if(!$user->no_name)
        {
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
}
