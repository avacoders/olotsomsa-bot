<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [];


    const STATUS_NEW = 1;
    const STATUS_IN_PROGRESS = 2;
    const STATUS_RECEIVED = 3;
    const STATUS_COMPLETE = 4;
    const STATUS_CANCEL = 5;

    const TYPE_CASH = 1;
    const TYPE_CARD = 2;
    const TYPE_SITE = 3;

    const GET = [
        self::STATUS_IN_PROGRESS => "Bajarilmoqda",
        self::STATUS_NEW => "Yangi",
        self::STATUS_RECEIVED => "Kelib tushgan",
        self::STATUS_COMPLETE => "✅ ТАСДИҚЛАНДИ",
        self::STATUS_CANCEL => "🛑 БЕКОР ҚИЛИНДИ"
    ];
    const GET_TYPE = [
        self::TYPE_CASH => "NAQD",
        self::TYPE_CARD => "KARTA",
        self::TYPE_SITE => "SAYT ORQALI",
    ];


    public function location()
    {
        return $this->belongsTo(Location::class);
    }


    public function order_products()
    {
        return $this->hasMany(OrderProduct::class);
    }


    public function products()
    {
        return $this->belongsToMany(Product::class,'order_product','order_id','product_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }





}
