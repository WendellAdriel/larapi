<?php

use Illuminate\Support\Facades\Route;

// PUBLIC ROUTES
Route::prefix('auth')->group(function () {
    Route::post('login', 'AuthController@login');
    Route::post('refresh', 'AuthController@refresh');
});

// PROTECTED ROUTES
Route::middleware('auth')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('logout', 'AuthController@logout');
        Route::get('me', 'AuthController@me');
    });
});
