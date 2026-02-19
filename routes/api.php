<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CertificateController;
use App\Http\Controllers\Api\OtpController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/otp', [OtpController::class, 'index']);
Route::post('/otp/send', [OtpController::class, 'sendOtp']);
Route::post('/otp/verify', [OtpController::class, 'verifyOtp']);

// Protected route example (requires token)
/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
}); */
