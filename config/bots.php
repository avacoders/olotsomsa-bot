<?php

return [
    'bot' => env("TELEGRAM_BOT_TOKEN"),
    "report_id" => config('bots.report_id'),
    "order_id" => env("TELEGRAM_ORDER_ID"),
    "exception" => env("EXCEPTION_PHONE_NUMBER"),
];
