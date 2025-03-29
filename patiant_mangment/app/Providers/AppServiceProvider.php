<?php

namespace App\Providers;

use App\SocialiteProviders\ZohoProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\Facades\Socialite;
use SocialiteProviders\Manager\SocialiteWasCalled;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */

    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::unguard();

        Socialite::extend('zoho', function ($app) {
            $config = $app['config']['services.zoho'];
            return Socialite::buildProvider(ZohoProvider::class, $config);
        });

    }
}
