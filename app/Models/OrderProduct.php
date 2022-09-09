<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = "order_product";


    const STATUS_SELECT = 1;
    const STATUS_NUMBER = 2;
    const STATUS_BASKET = 3;


    public function product()
    {
        return $this->belongsTo(Product::class);
    }


}
