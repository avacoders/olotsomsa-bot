<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\StoreRequest;
use App\Http\Requests\Product\UpdateRequest;
use App\Http\Services\ProductService;
use App\Models\Category;
use App\Models\Product;

class ProductController extends Controller
{

    protected $service;

    public function __construct()
    {
        $this->service = new ProductService();
    }


    public function index()
    {
        $products = Product::select('id','name','price','image', 'unit')->with('category')->paginate();
        return view('products.index', compact('products', ));
    }

    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        $this->service->store($data);
        return to_route('product.index');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return to_route('product.index');
    }


    public function edit(Product $product)
    {
        $categories = Category::all();

        return view('products.edit', compact('product','categories'));
    }

    public function update(UpdateRequest $request,Product $product)
    {
        $data = $request->validated();

        $this->service->update($product, $data);

        return to_route('product.index');
    }

}
