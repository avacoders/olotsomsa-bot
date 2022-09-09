<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\Product\ProductResource;
use App\Http\Resources\Order\CollectionResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function index()
    {
        $products = cache()->remember('products', 60 * 60 * 24 * 7, function () {
            return Product::select('id', 'name', 'price', 'image')->orderBy('order', 'asc')->get();
        });

        return ProductResource::collection($products);

    }

    public function myOrders()
    {
        $user = auth()->user();

        $orders = $user->orders()->select('id', 'delivery_price', 'posuda','updated_at')->with(['order_products' => function ($query) {
            $query->select('id','product_id','order_id','quantity')->with(['product' => function ($query) {
                $query->select('id','name','price');
            }]);
        }])->orderBy('id','desc')->get();

        return new CollectionResource($orders);

    }


}
