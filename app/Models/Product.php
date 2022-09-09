<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];

    use Sluggable;


    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }


    public function getImageAttribute($value)
    {
        return asset("storage/$value");
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }


    public function orders()
    {
        return $this->belongsToMany(Order::class,'order_product','product_id','order_id');
    }

}
