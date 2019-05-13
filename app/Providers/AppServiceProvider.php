<?php

namespace App\Providers;

use App\Http\Middleware\CheckUserRole;
use App\Role\RoleChecker;
use App\Services\FakeService;
use App\Services\FBService;
use App\Services\InstagramScraperService;
use App\Services\NakrutkaService;
use App\Services\VKService;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Schema;
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

        app()->bind(FakeService::class, function() {
            return new FakeService();
        });

        app()->bind(NakrutkaService::class, function() {
            return new NakrutkaService(config('services.nakrutka'));
        });

        $this->app->singleton(CheckUserRole::class, function(Application $app) {
            return new CheckUserRole(
                $app->make(RoleChecker::class)
            );
        });

        app()->bind(InstagramScraperService::class, function () {
            return new InstagramScraperService();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
    }
}
