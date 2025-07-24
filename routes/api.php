<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\CustomerAuth\CustomerAuthController;
use App\Http\Controllers\Api\v1\ToDOController;

Route::prefix('v1')->group(function(){
    
    Route::post('/register',[CustomerAuthController::class,'register']);
    Route::post('/login',[CustomerAuthController::class,'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/getuser',[CustomerAuthController::class,'getUser']);
        Route::get('/logout',[CustomerAuthController::class,'logout']);
        Route::post('/gettodo',[ToDOController::class,'getToDo']);
        Route::post('/settodo',[ToDOController::class,'store']);
        Route::post('/updatestatus',[ToDOController::class,'updateStatus']);
        Route::post('/deletetodo',[ToDOController::class,'deleteTodo']);
    });

});


