<?php


namespace App\Http\Services;


use App\Models\Product;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Support\Facades\Storage;

class ProductService
{


    public function store($data)
    {
        $preview = $data['image'];
        $data['image'] = Storage::put('/images1', $preview);
        $data['slug'] = SlugService::createSlug(Product::class, 'slug', $data['name']);
        $data['status'] = isset($data['status']) ? 1 : 0;
        return Product::firstOrCreate($data);
    }

    public function update($product, $data)
    {

        if (isset($data['image'])) {
            $preview = $data['image'];
            $data['image'] = Storage::put('/images1', $preview);
        }
        $data['slug'] = SlugService::createSlug(Product::class, 'slug', $data['name']);
        $data['status'] = isset($data['status']) ? 1 : 0;


        $product->update($data);

        return $product;

    }


}
