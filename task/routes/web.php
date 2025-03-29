<?php

use App\Http\Controllers\Auth\ZohoAuthController;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

Route::redirect('/', '/admin', 301);

Route::get('/auth/google', function () {
    return Socialite::driver('google')->redirect();
})->name('google.login');

Route::get('/auth/zoho', [ZohoAuthController::class, 'redirectToZoho'])
    ->name('zoho.login');

Route::get('/admin/auth/zoho/callback', [ZohoAuthController::class, 'handleZohoCallback']);

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/sync-inbox', [ZohoAuthController::class, 'fetchAndStoreInbox'])
        ->name('zoho.sync-inbox');
});

