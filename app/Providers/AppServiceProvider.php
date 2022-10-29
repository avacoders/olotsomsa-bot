<?php

namespace App\Providers;

use App\Helpers\Telegram;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport; // add this

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(Telegram::class, function ($app) {
            return new Telegram(config('bots.bot'));
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
//        URL::forceScheme('https');
        Schema::defaultStringLength(191);
        Paginator::useBootstrap();
        Passport::routes(); // Add this



    }
}
