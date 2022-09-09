<?php


namespace App\Http\Services;


use App\Models\Category;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Support\Facades\Storage;

class CategoryService
{


    public function store($data)
    {
        $preview = $data['image'];
        $data['image'] = Storage::put('/images1', $preview);
        $data['slug'] = SlugService::createSlug(Category::class,'slug', $data['name']);
        return Category::firstOrCreate($data);
    }

    public function update($category, $data)
    {

        if (isset($data['image']))
        {
            $preview = $data['image'];
            $data['image'] = Storage::put('/images1', $preview);
        }
        $data['slug'] = SlugService::createSlug(Category::class,'slug', $data['name']);


        $category->update($data);

        return $category;

    }



}
