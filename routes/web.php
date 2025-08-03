<?php

use CleaniqueCoders\SocialiteRecall\Http\Controllers\SocialiteController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::get('{provider}/redirect', [SocialiteController::class, 'redirect'])
        ->name('socialite.redirect');

    Route::get('{provider}/callback', [SocialiteController::class, 'callback'])
        ->name('socialite.callback');

    Route::post('{provider}/logout', [SocialiteController::class, 'logout'])
        ->middleware('auth')
        ->name('socialite.logout');
});
