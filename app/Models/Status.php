<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;
    protected $guarded = [];

    use Sluggable;


    const NAME = "name";
    const PHONE_NUMBER = "phone_number";
    const VERIFICATION = "verification";
    const VERIFICATION1 = "verification1";
    const NORMAL = "normal";
    const LOCATION_SELECT = "location_select";
    const LOCATION_REQUEST = "location_request";
    const LOCATION_VERIFY = "location_verify";
    const COMMENT = "comment";
    const PAYMENT = "payment";
    const ASK_NAME = "ask_name";
    const ENTER_NAME = "enter_name";
    const ASK_PHONE = "ask_phone";


    const GET = [
        self::NAME => 1,
        self::PHONE_NUMBER => 2,
        self::VERIFICATION => 3,
        self::VERIFICATION1 => 13,
        self::NORMAL => 4,
        self::LOCATION_SELECT => 5,
        self::LOCATION_REQUEST => 6,
        self::LOCATION_VERIFY => 7,
        self::COMMENT => 8,
        self::PAYMENT => 9,
        self::ASK_NAME => 10,
        self::ENTER_NAME => 11,
        self::ASK_PHONE => 12,
    ];


    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }


}
