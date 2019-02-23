<?php

namespace App\Providers;

use App\Services\FBService;
use App\Services\VKService;
use Illuminate\Support\ServiceProvider;
use App;
use App\Services\SMMAuthService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        App::bind(VKService::class, function() {
            return new VKService(config('services.vk'));
        });

        App::bind(SMMAuthService::class, function() {
            return new SMMAuthService();
        });

        App::bind(FBService::class, function() {
            return new FBService(config('services.fb'));
        });

        app()->singleton(App\Services\VKTransport::class, function() {
            return new App\Services\VKTransport();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
