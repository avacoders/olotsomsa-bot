<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Login\NameRequest;
use App\Http\Requests\Login\PhoneNumberRequest;
use App\Http\Requests\Login\UpdateRequest;
use App\Models\Status;
use App\Models\User;
use Illuminate\Http\Request;
use Napa\R19\Sms;

class LoginController extends Controller
{


    public function login(PhoneNumberRequest $request)
    {
        $data = $request->validated();
        $user = User::query()->where('phone_number', $data['phone_number'])->first();
        $data['email'] = sha1("a" . time()) . "@avacoder.uz";
        $data['name'] = "No Name";
        $data['is_bot'] = false;
        $data['password'] = bcrypt('secret');
        $data['status_id'] = Status::GET[Status::NAME];
        if (!$user) {
            $user = User::create($data);
        }
        if ($user->phone_number != env("EXCEPTION_PHONE_NUMBER"))
            $this->sendCode($user);


        return response()->json(['ok' => true, 'message' => "Sizga 5 ta raqamli kod SMS tarzida yuborildi! Kodni kiriting"]);
    }

    public function update(UpdateRequest $request)
    {
        $data = $request->validated();
        $user = auth()->user();
        $user->update($data);
        return response()->json(['ok' => true, 'message' => "Muvaffaqiyatli o'zgartirildi", 'user' => auth()->user()]);
    }


    public function name(NameRequest $request, $phone_number)
    {
        $data = $request->validated();
        $user = User::query()->where('phone_number', $phone_number)->first();
        if (!$user) {
            return response()->json(["ok" => false, "message" => "Foydalanuvchi topilmadi"]);
        }
        if ($data['code'] == $user->verification_code) {
            $user->name = $data['name'];
            $user->no_name = 0;
            $user->save();
            auth()->login($user);
            $accessToken = $user->no_name ? null : auth()->user()->createToken('authToken')->accessToken;
            return response()->json(['ok' => true, 'message' => "Muvaffaqiyatli kiritildi!", 'user' => auth()->user(), 'access_token' => $accessToken]);
        } else {
            return response()->json(['ok' => false, 'message' => "Kiritilgan kod noto'g'ri"], 500);

        }
    }

    public function code($phone_number, Request $request)
    {
        $code = $request->code;
        $user = User::query()->where('phone_number', $phone_number)->first();
        if (!$user)
            return response()->json(['ok' => false, 'message' => "Kiritilgan foydalanuvchi mavjud emas"], 500);
        if ($phone_number == env("EXCEPTION_PHONE_NUMBER") && $code == 11111)
            return $this->phone_number_exception($phone_number, $code);

        if ($code == $user->verification_code) {
            auth()->login($user);
            $accessToken = $user->no_name ? null : auth()->user()->createToken('authToken')->accessToken;
            return response()->json(['ok' => true, 'message' => "Muvaffaqiyatli kiritildi!", 'user' => auth()->user(), 'access_token' => $accessToken]);
        } else {
            return response()->json(['ok' => false, 'message' => "Kiritilgan kod noto'g'ri"], 500);

        }
    }

    public
    function phone_number_exception($phone_number, $code)
    {
        $data['phone_number'] = $phone_number;
        $data['email'] = sha1("a" . time()) . "@avacoder.uz";
        $data['name'] = "No Name";
        $data['is_bot'] = false;
        $data['password'] = bcrypt('secret');
        $data['status_id'] = Status::GET[Status::NAME];
        if ($phone_number == env("EXCEPTION_PHONE_NUMBER") && $code == 11111) {

            $user = User::query()->where('phone_number', $phone_number)->first();
            if (!$user)
                $user = User::create($data);
            auth()->login($user);
            $accessToken = auth()->user()->createToken('authToken')->accessToken;
            return response()->json(['ok' => true, 'message' => "Muvaffaqiyatli kiritildi!", 'user' => auth()->user(), 'access_token' => $accessToken]);
        }

    }

    public
    function sendCode($user)
    {
        $code = rand(10000, 99999);
        $user->verification_code = $code;
        $user->verification_expires_at = now()->addMinutes(5);
        $user->status_id = Status::GET[Status::VERIFICATION];
        $user->save();
        $text = "SIZNING OLOTSOMSA ORIGINAL UCHUN KODINGIZ: $code";
        Sms::send($user->phone_number, $text);
        return $code;
    }

    public
    function logout(Request $request)
    {
        $token = auth()->user()->token();
        $token->revoke();
        return response()->json(['ok' => true, "message" => "Success"]);
    }

    public function selfDelete()
    {
        auth()->user()->delete();
        return response()->json(['ok' => true, "message" => "Muvaffaqiyatli o'chiirildi"]);
    }


}
