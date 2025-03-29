<?php

use App\Http\Controllers\Auth\OAuthController;
use App\Http\Controllers\ZohoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/auth/redirect', [OAuthController::class, 'redirectToProvider'])->name('auth.redirect');

// Handle Zoho callback
Route::get('/auth/callback', [OAuthController::class, 'handleProviderCallback'])->name('auth.callback');
Route::get('/admin/login', function (){
    return view('vendor/filament/pages/auth/login');

})->name('auth.redirect');
Route::get('/zoho/connect', [ZohoController::class, 'redirect'])->name('zoho.connect');
