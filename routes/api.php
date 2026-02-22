<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\OtpController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\WalletController;
use App\Http\Controllers\Api\CertificateController;
use App\Http\Controllers\Api\PublishController;
use App\Http\Controllers\Api\AdminAuthController;
use App\Http\Controllers\Api\AutoBlogController;
use App\Http\Controllers\Api\EarningController;
use Carbon\Carbon;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| These routes are automatically prefixed with /api
| Example: /api/test
|
*/


// OTP Send Route
Route::prefix('v2')->group(function () {
    Route::post('/send-otp/', [OtpController::class, 'sendOtp']);
    Route::post('/resend-otp', [OtpController::class, 'resendOtp']);
    Route::post('/verify-otp', [OtpController::class, 'verifyOtp']);
    
    Route::post('/verify-session', [AuthController::class, 'verifySession'])->middleware('auth:sanctum');
    Route::post('/refresh-token', [AuthController::class, 'refreshToken']);
});

Route::prefix('user')->middleware('auth:sanctum')->group(function () {
    // Get authenticated user details
    Route::get('/', [AuthController::class, 'user']);
    Route::get('/profile', [AuthController::class, 'user']);
});

Route::prefix('wallet')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [WalletController::class, 'index']);
    Route::get('/balance', [WalletController::class, 'balance']);
    Route::get('/transactions', [WalletController::class, 'transactions']);
});

// Certificate routes (protected)
Route::prefix('certificates')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [CertificateController::class, 'index']);
    Route::get('/{id}', [CertificateController::class, 'show']);
    Route::post('/', [CertificateController::class, 'store']); // Admin only
    Route::put('/{id}/status', [CertificateController::class, 'updateStatus']); // Admin only
});

// Publish routes (protected)
Route::prefix('publishes')->middleware('auth:sanctum')->group(function () {
    Route::get('/analytics', [PublishController::class, 'analytics']);
    Route::get('/recent-activity', [PublishController::class, 'recentActivity']);
});

// Auto Blog routes (protected)
Route::prefix('blog')->middleware('auth:sanctum')->group(function () {
    Route::get('/next-pending', [AutoBlogController::class, 'nextPending']);
    Route::get('/history', [AutoBlogController::class, 'userHistory']);
    Route::get('/{id}', [AutoBlogController::class, 'show']);
    Route::put('/{id}/status', [AutoBlogController::class, 'updateStatus']);
});

// Earning routes (protected)
Route::prefix('earnings')->middleware('auth:sanctum')->group(function () {
    Route::get('/last-7-days', [EarningController::class, 'last7Days']);
    Route::get('/last-15-days', [EarningController::class, 'last15Days']);
    Route::get('/last-30-days', [EarningController::class, 'last30Days']);
    Route::get('/last-60-days', [EarningController::class, 'last60Days']);
});


// Admin routes (no auth required for login)
Route::prefix('admin')->group(function () {
    Route::post('/login', [AdminAuthController::class, 'login']);
    
    // Protected admin routes
    Route::middleware('auth:admin')->group(function () {
        Route::post('/verify-session', [AdminAuthController::class, 'verifySession']);
        Route::post('/logout', [AdminAuthController::class, 'logout']);
        Route::get('/profile', [AdminAuthController::class, 'profile']);
    });
});


