<?php

use Azuriom\Plugin\ReachUs\Controllers\ContactController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ContactController::class, 'index'])->name('index');
