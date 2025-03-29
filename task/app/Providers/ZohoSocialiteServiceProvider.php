<?php

namespace App\Providers;


use App\Socialite\ZohoProvider;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\Facades\Socialite;

class ZohoSocialiteServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        $this->app->bind(ZohoProvider::class, function ($app) {
            $config = $app['config']['services.zoho'];

            return new ZohoProvider(
                $app->make(Request::class),
                $config['client_id'],
                $config['client_secret'],
                $config['redirect'],
                [
                    'accounts_url' => $config['accounts_url'],
                    'guzzle' => $config['guzzle'] ?? []
                ]
            );
        });
    }
    public function boot()
    {
        Socialite::extend('zoho', function ($app) {
            return $app->make(ZohoProvider::class);
        });
    }
}
