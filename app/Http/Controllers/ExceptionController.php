<?php

namespace App\Http\Controllers;

use App\Helpers\Telegram;
use App\Http\Requests\Exception\IndexRequest;
use Illuminate\Http\Request;

class ExceptionController extends Controller
{
    public function index(IndexRequest $request)
    {
        $data = $request->validated();
        $telegram = new Telegram(config('bots.bot'));
        $telegram->sendMessage(env("REPORT_TELEGRAM_ID"), (string)view('report_mobile',$data));
        return response()->json([
            'ok' => true,
            'message' => 'Сообщение отправлено'
        ], 200);
    }
}
