<?php

use Illuminate\Support\Facades\Route;

// Redirect index to login (or dashboard if authenticated)
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('items', 'items')
    ->middleware(['auth', 'verified'])
    ->name('items');

Route::view('cart', 'cart')
    ->middleware(['auth', 'verified'])
    ->name('cart');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
