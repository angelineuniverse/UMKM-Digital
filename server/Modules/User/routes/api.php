<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\AccessMenuUserController;
use Modules\User\Http\Controllers\UserController;

/*
 *--------------------------------------------------------------------------
 * API Routes
 *--------------------------------------------------------------------------
 *
 * Here is where you can register API routes for your application. These
 * routes are loaded by the RouteServiceProvider within a group which
 * is assigned the "api" middleware group. Enjoy building your API!
 *
*/

Route::prefix('v1/user')->group(function () {
    Route::post('register', [UserController::class,'store']);
    Route::post('login', [UserController::class,'login']);
    Route::middleware('auth:sanctum')->group( function () {
        Route::get('logout', [UserController::class,'logout']);
        Route::get('show', [UserController::class,'show']);
        Route::get('index', [UserController::class,'index']);
        Route::post('update/{id}', [UserController::class,'update']);
        Route::delete('delete/{id}', [UserController::class,'destroy']);

        Route::prefix('menu')->group(function () {
            Route::get('', [AccessMenuUserController::class,'index']);
            Route::post('', [AccessMenuUserController::class,'store']);
            Route::get('{id}', [AccessMenuUserController::class,'show']);
            Route::post('{id}', [AccessMenuUserController::class,'update']);
            Route::delete('{id}', [AccessMenuUserController::class,'destroy']);
        });
    });
});
