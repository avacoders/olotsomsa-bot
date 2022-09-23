<?php

namespace App\Http\Services;

use App\Helpers\Telegram;

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
        if($message == "/start") {
            if($user)
                $this->sendMenu($user);
            else
                $this->askLang($chat_id);
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

    public function setLang($data, $lang)
    {
        $user = $this->telegram->saveData($data);
        $user->language_code = $lang;
        $user->save();
        $this->sendMenu($user);
    }

    public function callback($user,$data)
    {
        $query = $data['callback_query'];
        $commands = explode('|', $query['data']);
        $command = $commands[0];
        $id = $commands[1];
        $product = isset($commands[2]) ? $commands[2] : '';
        $message_id = $query['message']['message_id'];
        $this->telegram->deleteMessage($query['message']['chat']['id'], $message_id);
        switch ($command) {
            case 'lang':
                $this->setLang($data, $id);break;
        }
    }


}
