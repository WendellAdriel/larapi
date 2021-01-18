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

        Route::prefix('me')->group(function () {
            Route::get('/', 'AuthController@loggedUser');
            Route::get('roles', 'AuthController@loggedUserRoles');
        });
    });
});
