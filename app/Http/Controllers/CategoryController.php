<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\StoreRequest;
use App\Http\Requests\Category\UpdateRequest;
use App\Http\Services\CategoryService;
use App\Http\Services\ProductService;
use App\Models\Category;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{

    protected $service;

    public function __construct()
    {
        $this->service = new CategoryService();
    }


    public function index()
    {
        $categories = Category::paginate();

        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        $this->service->store($data);
        return to_route('category.index');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return to_route('category.index');
    }


    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(UpdateRequest $request,Category $category)
    {
        $data = $request->validated();

        $this->service->update($category, $data);

        return to_route('category.index');
    }


}
