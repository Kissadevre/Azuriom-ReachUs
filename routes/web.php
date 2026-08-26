<?php

use Azuriom\Plugin\ReachUs\Controllers\ContactController;
use Azuriom\Plugin\ReachUs\Middleware\EnsureContactFormAvailable;
use Azuriom\Plugin\ReachUs\Middleware\RedirectAuthenticatedUsers;
use Illuminate\Support\Facades\Route;

Route::get('/', [ContactController::class, 'index'])
    ->middleware(RedirectAuthenticatedUsers::class)
    ->name('index');
Route::post('/', [ContactController::class, 'store'])
    ->middleware([
        RedirectAuthenticatedUsers::class,
        EnsureContactFormAvailable::class,
        'throttle:reachus.contact',
        'captcha',
    ])
    ->name('store');
