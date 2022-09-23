<?php

namespace App\Http\Controllers;

use App\Helpers\Telegram;
use App\Http\Services\OrderService;
use App\Jobs\SaveDataJob;
use App\Models\Category;
use App\Models\Chat;
use App\Models\Message;
use App\Models\Status;
use App\Models\User;
use Faker\Generator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use League\CommonMark\Extension\CommonMark\Node\Block\ThematicBreak;
use Symfony\Component\ErrorHandler\Debug;

class WebhookController extends Controller
{
    protected $faker;
    protected $telegram;
    protected $service;

    public function __construct()
    {
        $this->faker = new Generator();
        $this->telegram = new Telegram(config('bots.bot'));
        $this->service = new OrderService();
    }


    public function index(Request $request)
    {
        $data = $request->all();
        Log::debug($data);
        $call_id = isset($data['callback_query']) ? $data['callback_query']['message']['chat']['id'] : '';
        $chat_id = isset($data['message']) ? $data["message"]['chat']['id'] : $call_id;
        $user = User::where('telegram_id', $chat_id)->first();
        $message = isset($data['message']) && isset($data['message']['text']) ? $data['message']['text'] : '';
        $contact = isset($data['message']) && isset($data['message']['contact']) ? $data['message']['contact'] : '';
        $location = isset($data['message']) && isset($data['message']['location']) ? $data['message']['location'] : '';
        if($message)
            $this->service->answer($user, $message, $data);
        if(isset($data['callback_query']))
            $this->service->callback($user, $data);
        if($contact)
            $this->service->setPhoneNumberAndContinue($user, $contact);
        if($location)
            $this->service->setLocation($user, $location);


    }
}
