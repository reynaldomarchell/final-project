<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\JWTMiddleware; 
use App\Http\Middleware\AdminMiddleware;

Route::get('/', function () {
    return csrf_token();
});

Route::prefix('auth')->group(function(){
    Route::post('register',[AuthController::class,'register']);
    Route::post('login',[AuthController::class,'login']);
});

Route::prefix('event')->group(function(){
    Route::get('/',[EventController::class,'index']);
    Route::get('/{id}',[EventController::class,'show']);
    
    Route::middleware([JWTMiddleware::class, AdminMiddleware::class])->group(function () {
        Route::post('/', [EventController::class, 'store']); 
        Route::put('/{id}', [EventController::class, 'edit']); 
        Route::delete('/{id}', [EventController::class, 'destroy']); 
    });
});

Route::prefix('user')->group(function(){
    Route::middleware([JWTMiddleware::class])->group(function () {
        Route::get('/', [UserController::class, 'index']); 
        Route::put('/', [UserController::class, 'edit']);
        Route::put('/change-password', [UserController::class, 'changePassword']); 
    });
});