<?php

use Illuminate\Support\Facades\Route;
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

Route::prefix('v1')->group(function () {
    Route::post('user/register', [UserController::class,'store']);
    Route::post('user/login', [UserController::class,'login']);
    Route::middleware('auth:sanctum')->group( function () {
        Route::get('user', [UserController::class,'show']);
    });
});
