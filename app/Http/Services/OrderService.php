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
        if($message == "/start") {
            if($user)
                $this->sendMenu($user);
            else
                $this->askLang($user, $data);
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

    public function askLang($user, $data)
    {
        if (!$user)
            $user = $this->telegram->saveData($data);
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
        $this->telegram->sendButtons($user->telegram_id, $text, json_encode($buttons));
    }




}
