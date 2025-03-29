<?php

use Illuminate\Support\Facades\Route;



Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/admin', function () {
        return view('filament.pages.dashboard');
    })->name('dashboard');

    Route::get('/', function () {
        return view('welcome');
    })->name('home');
});

