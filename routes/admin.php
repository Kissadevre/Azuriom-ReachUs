<?php

use Azuriom\Plugin\ReachUs\Controllers\Admin\ResponseController;
use Azuriom\Plugin\ReachUs\Controllers\Admin\SettingController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/admin/reachus/responses')->name('index');

Route::middleware('can:reachus.responses')->group(function () {
    Route::get('/responses', [ResponseController::class, 'index'])->name('responses.index');
    Route::get('/responses/{message}', [ResponseController::class, 'show'])->name('responses.show');
    Route::patch('/responses/{message}/unread', [ResponseController::class, 'unread'])->name('responses.unread');
    Route::delete('/responses/{message}', [ResponseController::class, 'destroy'])->name('responses.destroy');
});

Route::middleware('can:reachus.settings')->group(function () {
    Route::get('/settings', [SettingController::class, 'show'])->name('settings');
    Route::post('/settings/discord/test', [SettingController::class, 'testDiscordWebhook'])
        ->name('settings.discord.test');
    Route::post('/settings', [SettingController::class, 'save'])->name('settings.save');
});
