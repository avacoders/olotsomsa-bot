<?php

use App\Helpers\Telegram;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\GitController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WebhookController;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Napa\R19\Sms;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function (){
    Route::view('/','index');

    Route::prefix('products')->group(function (){
        Route::get('/', [ProductController::class, 'index'])->name('product.index');
        Route::get('/create', [ProductController::class, 'create'])->name('product.create');
        Route::get('/edit/{product}', [ProductController::class, 'edit'])->name('product.edit');
        Route::put('/edit/{product}', [ProductController::class, 'update'])->name('product.update');
        Route::post('/create', [ProductController::class, 'store'])->name('product.store');
        Route::delete('/destroy/{product}', [ProductController::class, 'destroy'])->name('product.destroy');
    });
    Route::prefix('categories')->group(function (){
        Route::get('/', [CategoryController::class, 'index'])->name('category.index');
        Route::get('/create', [CategoryController::class, 'create'])->name('category.create');
        Route::get('/edit/{category}', [CategoryController::class, 'edit'])->name('category.edit');
        Route::put('/edit/{category}', [CategoryController::class, 'update'])->name('category.update');
        Route::post('/create', [CategoryController::class, 'store'])->name('category.store');
        Route::delete('/destroy/{category}', [CategoryController::class, 'destroy'])->name('category.destroy');
    });

    Route::group(['prefix' => 'branches', 'as' => 'branch.'], function (){
        Route::get('/', [BranchController::class, 'index'])->name('index');
        Route::get('/create', [BranchController::class, 'create'])->name('create');
        Route::post('/create', [BranchController::class, 'store'])->name('store');
        Route::get('/edit/{branch}', [BranchController::class, 'edit'])->name('edit');
        Route::put('/edit/{branch}', [BranchController::class, 'update'])->name('update');
        Route::delete('/destroy/{branch}', [BranchController::class, 'destroy'])->name('destroy');
    });
    Route::group(['prefix' => 'users', 'as' => 'user.'], function (){
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/create', [UserController::class, 'store'])->name('store');
//        Route::get('/edit/{user}', [\App\Http\Controllers\UserController::class, 'edit'])->name('edit');
//        Route::put('/edit/{user}', [\App\Http\Controllers\UserController::class, 'update'])->name('update');
        Route::delete('/destroy/{user}', [UserController::class, 'destroy'])->name('destroy');
    });
});

Auth::routes(['register' => false]);

    Route::post('/pull', [GitController::class,'index']);


Route::group(['prefix' => 'bot'], function () {

    Route::get("/setWebhook", function (){
        $result = \Illuminate\Support\Facades\Http::get("https://api.telegram.org/bot".config('bots.bot')."/setWebhook?url=https://bot.olotsomsa.com/bot/webhook");
        dd($result->body());
    });
    Route::post('/webhook', [WebhookController::class,'index']);
    Route::view('/app', 'webApp');

});
Route::get('locale/{locale}', function ($locale) {
    LaravelLocalization::setLocale($locale);
    return redirect()->back();
});


//Route::group([
//    'prefix' => LaravelLocalization::setLocale(),
//    'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath' ]
//], function(){
//

    Route::get("/", [\App\Http\Controllers\HomeController::class,'index'])->name('site');
    Route::get("/contact", [\App\Http\Controllers\HomeController::class,'contact'])->name('contact');
    Route::get("/about", [\App\Http\Controllers\HomeController::class,'about'])->name('about');
    Route::get("/branches", [\App\Http\Controllers\HomeController::class,'branches'])->name('branches');
    Route::get("/my-orders", [\App\Http\Controllers\HomeController::class,'myOrders'])->name('myOrders');
    Route::get("/cabinet", [\App\Http\Controllers\HomeController::class,'cabinet'])->name('cabinet');

//});
Route::get("/send",function (){
    $response = Sms::send(998903911755, 2342314);
    $code = substr(998903911755, 0, 5);
    dd($code);
});
