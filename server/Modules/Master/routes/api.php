<?php

use Illuminate\Support\Facades\Route;
use Modules\Master\Http\Controllers\AccessMenuController;
use Modules\Master\Http\Controllers\MasterController;
use Modules\Master\Http\Controllers\MenuController;

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

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::post('menu',[MenuController::class,'store']);
    Route::get('menu',[MenuController::class,'index']);
    Route::get('menu:form',[MenuController::class,'create']);
    Route::post('menu/{id}',[MenuController::class,'update']);

    Route::prefix('menu:access')->group(function(){
        Route::post('',[AccessMenuController::class,'store']);
        Route::get('',[AccessMenuController::class,'index']);
        Route::get('{id}',[AccessMenuController::class,'show']);
        Route::post('{id}',[AccessMenuController::class,'update']);
        Route::delete('{id}',[AccessMenuController::class,'destroy']);
    });
});
