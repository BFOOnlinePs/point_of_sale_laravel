<?php

use App\Http\Controllers\api\auth\AuthController;
use Illuminate\Support\Facades\Route;

// Route::post('/login', [AuthController::class, 'login']);


Route::middleware('api')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
});


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'getUserInfo']);
});
