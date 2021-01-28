<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Geolocation\GeolocationService;
use App\Services\Geolocation\LocalGeolocationService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(GeolocationService::class, LocalGeolocationService::class);
    }
}
