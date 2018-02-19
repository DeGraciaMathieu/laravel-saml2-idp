<?php

namespace App\Providers;

use App\Services\Saml\SamlService;
use Illuminate\Support\ServiceProvider;
use App\Services\Saml\Managers\ManageClient;
use App\Services\Saml\Managers\ManageRequest;
use App\Services\Saml\Managers\ManageMessage;
use App\Services\Saml\Managers\ManageResponse;
use App\Services\Saml\Managers\ManageSignature;

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
        $this->app->bind(SamlService::class, function ($app) {
            return new SamlService(
                new ManageRequest, 
                new ManageResponse, 
                new ManageMessage, 
                new ManageClient, 
                new ManageSignature
            );
        });
    }
}
