<?php

namespace App\Providers;

use Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\Services\Auth\CustomUserProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Auth::provider('custom', function($app, array $config) {
            return new CustomUserProvider($app['hash'], $config['model']);
        });
    }
}


