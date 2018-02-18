<?php

namespace App\Providers;

use App\Services\Saml\SamlService;
use App\Services\Saml\ManageRequest;
use App\Services\Saml\ManageMessage;
use App\Services\Saml\ManageResponse;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(SamlService::class, function ($app) {
            return new SamlService(new ManageRequest, new ManageResponse, new ManageMessage);
        });
    }
}
