<?php

use Illuminate\Support\Facades\Route;
use LarAPI\Modules\Auth\Controllers\AuthController;
use LarAPI\Modules\Auth\Controllers\UserController;

// PUBLIC ROUTES
Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('refresh', [AuthController::class, 'refresh']);
});

// PROTECTED ROUTES
Route::middleware('auth')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('logout', [AuthController::class,'logout']);

        Route::prefix('me')->group(function () {
            Route::get('/', [AuthController::class,'loggedUser']);
            Route::get('roles', [AuthController::class,'loggedUserRoles']);
        });
    });

    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class,'index']);
        Route::post('/', [UserController::class,'create'])->middleware('block_viewer');

        Route::prefix('{uuid}')->group(function () {
            Route::get('/', [UserController::class,'show']);
            Route::put('/', [UserController::class,'update'])->middleware('block_viewer');
            Route::delete('/', [UserController::class,'delete'])->middleware('check_manager');
        });
    });
});
