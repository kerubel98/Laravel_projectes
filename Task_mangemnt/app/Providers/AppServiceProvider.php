<?php

namespace App\Providers;

use Filament\Forms\Components\View;
use Filament\Support\Facades\FilamentView;
use Illuminate\Foundation;

use Illuminate\Support\ServiceProvider;

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

        FilamentView::registerRenderHook(
            'panels::auth.login.form.before',
            fn (): \Illuminate\Contracts\View\View|Foundation\Application => view('filament.login_extra')
        );
    }
}
