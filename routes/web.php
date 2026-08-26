<?php

use Azuriom\Plugin\ReachUs\Controllers\ContactController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ContactController::class, 'index'])->name('index');
Route::post('/', [ContactController::class, 'store'])
    ->middleware(['throttle:reachus.contact', 'captcha'])
    ->name('store');
