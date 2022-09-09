<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $products = cache()->remember('products',60*60*24*7, function (){
            return Product::select('id','name','price','image','unit')->orderBy('order','asc')->get();
        });
        return view('site.index', compact('products'));
    }


    public function contact()
    {

        return view('site.contact');
    }


    public function about()
    {

        return view('site.about');
    }


    public function branches()
    {
        $branches = Branch::all();
        return view('site.branches', compact('branches'));
    }
    public function myOrders()
    {

        return view('site.myOrders');
    }
    public function cabinet()
    {

        return view('site.cabinet');
    }


}
