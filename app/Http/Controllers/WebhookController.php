<?php

namespace App\Http\Controllers;

use App\Helpers\Telegram;
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

    public function __construct()
    {
        $this->faker = new Generator();
        $this->telegram = new Telegram(config('bots.bot'));
    }


    public function index(Request $request)
    {
        $data = $request->all();
        $call_id = isset($data['callback_query']) ? $data['callback_query']['message']['chat']['id']: '';

        $chat_id = isset($data['message']) ? $data["message"]['chat']['id'] : $call_id;

        $user = User::where('telegram_id',$chat_id)->first();

        if (isset($data['message']) && isset($data['message']['text'])) {

            if ($data['message']['text'] == "/start") {
                if ($user)
                    $this->telegram->sendMenu($user);
                else
                    $this->telegram->setLang($data);
                return 1;
            }

            if ($data['message']['text'] == lang("uz","confirm")) {
                $this->telegram->comment($user);
                return 1;
            }

            if ($data['message']['text'] == lang("uz",'back')) {
                $this->telegram->back($user);
                return 1;
            }
            if ($data['message']['text'] == lang("uz",'next')) {
                $this->telegram->checkOrder($user);
                return 1;
            }
            if ($data['message']['text'] == '/settings') {
                $this->telegram->settings($user);
                return 1;
            }


            $this->telegram->base($user, $data);

        }


        try {
            if (isset($data['callback_query'])){
                $message_id = $data['callback_query']['message']['message_id'];
                $callback_data = $data['callback_query']['data']? explode('|', $data['callback_query']['data']):['',''];
                $product = isset($callback_data[2]) ? $callback_data[2] : '';
                $type = $callback_data[0];
                $id = isset($callback_data[1])? $callback_data[1]:'';
                if ($type == "addMinute") {
                    $this->telegram->minute($message_id, $id, "plus", $product);
                    return 1;
                }

                if ($type == "addPrice") {
                    $this->telegram->price($message_id, $id, "plus", $product);
                    return 1;
                }
                if ($type == "subPrice") {
                    $this->telegram->price($message_id, $id, "minus", $product);
                    return 1;
                }
                if ($type == "receive") {
                    $this->telegram->receive( $message_id, $id);
                    return 1;
                }
                if ($type == "cancel") {
                    $this->telegram->cancel( $message_id, $id);
                    return 1;
                }
                if ($type == "posuda") {
                    $this->telegram->posuda( $message_id, $id, 'set', $product);
                    return 1;
                }
                if ($type == "addPosuda") {
                    $this->telegram->posuda( $message_id, $id, 'add', $product);
                    return 1;
                }
                if ($type == "subPosuda") {
                    $this->telegram->posuda( $message_id, $id, 'sub', $product);
                    return 1;
                }
                if ($type == "location") {
                    $this->telegram->getLocation( $message_id, $id,);
                    return 1;
                }
                if ($type == "cancelSite") {
                    $this->telegram->cancelSite( $message_id, $id,"cancel");
                    return 1;
                }
                if ($type == "receiveSite") {
                    $this->telegram->cancelSite( $message_id, $id,"receive");
                    return 1;
                }
                if ($type == "change_lang") {
                    $this->telegram->deleteMessage($user->telegram_id,$message_id);
                    $this->telegram->lang(null);
                    return 1;
                }
                if($type == "lang")
                {
                    $this->telegram->changeLang($user, $id, $message_id);
                    return 1;
                }
            }

        }catch (\Exception $exception)
        {
            Log::debug($exception);
        }
        if ($user) {

            if ($this->telegram->checkChatStatus($user, Status::PHONE_NUMBER) && isset($data["message"]['contact'])) {
                $this->telegram->sendVerification($user, $data["message"]['contact']['phone_number']);
                return 1;
            }

            if (isset($data['message']['location'])) {
                $this->telegram->verifyLocation($user, $data['message']['location']);
                return 1;
            }


            if (isset($data['callback_query'])) {
                $message_id = $data['callback_query']['message']['message_id'];
                $callback_data =$data['callback_query']['data']? explode('|', $data['callback_query']['data']):['',''];
                $type = $callback_data[0];
                $id = isset($callback_data[1])? $callback_data[1]:'';
                $product = isset($callback_data[2]) ? $callback_data[2] : '';

                if ($type == 'category') {
                    $this->telegram->sendCategoryProducts($user, $id, $message_id);
                    return 1;
                }
                if ($type == "menu") {
                    $this->telegram->backToMenu($user, $message_id);
                    return 1;
                }

                if ($type == "product") {
                    $this->telegram->selectProduct($user, $message_id, $id);
                    return 1;
                }
                if ($type == "number") {
                    $this->telegram->selectNumber($user, $message_id, $id, $product);
                    return 1;
                }
                if ($type == "trash") {
                    $this->telegram->eraseNumber($user, $message_id, $id);
                    return 1;
                }
                if ($type == "addToCart") {
                    $this->telegram->addToCart($user, $message_id, $id, $data['callback_query']['id']);
                    return 1;
                }
                if ($type == "basket" && $id == 1) {
                    $this->telegram->showCart($user, $message_id, $data['callback_query']['id']);
                    return 1;
                }
                if ($type == "history") {
                    $this->telegram->history($user, $message_id, $data['callback_query']['id'], $id);
                    return 1;
                }
                if ($type == "confirm") {
                    $this->telegram->confirm($user, $message_id);
                    return 1;
                }

                if ($type == "location") {
                    $this->telegram->location($user, $message_id, $id);
                    return 1;
                }

                if ($type == "payment") {
                    $this->telegram->payment($user, $message_id, $id);
                    return 1;
                }

                if ($type == "phone") {
                    $this->telegram->askPhone($user, $message_id);
                    return 1;
                }




                return 0;
            }

        }



    }
}
