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
    
    Route::get('/test', function (Request $request) {
        return response()->json([
            'message' => 'API is working!',
            'server_time' => now()->toDateTimeString(),
            'environment' => app()->environment(),
        ]);
    });
    
    // Test route to print current time (IST)
    Route::get('/test-time', function () {
        return response()->json([
            'current_time_ist'     => now()->toDateTimeString(),
            'timezone_config'      => config('app.timezone'),
            'carbon_now'           => Carbon::now()->toDateTimeString(),
            'carbon_now_explicit'  => Carbon::now('Asia/Kolkata')->toDateTimeString(),
            'server_timestamp'     => time(),
            'human_readable'       => now()->format('d M Y, h:i A'),
        ]);
    });

});

Route::prefix('user')->middleware('auth:sanctum')->group(function () {
    // Get authenticated user details
    Route::get('/', [AuthController::class, 'user']);
    Route::get('/profile', [AuthController::class, 'user']); // Alias
    
    // Update profile
    Route::put('/profile', [AuthController::class, 'updateProfile']);
    Route::patch('/profile', [AuthController::class, 'updateProfile']);
    
    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/logout-all', [AuthController::class, 'logoutAll']);
    
    // Devices and history
    Route::get('/devices', [AuthController::class, 'devices']);
    Route::get('/history', [AuthController::class, 'loginHistory']);
    
    // Password (if implemented)
    Route::post('/change-password', [AuthController::class, 'changePassword']);
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
    Route::get('/', [PublishController::class, 'index']);
    Route::get('/analytics', [PublishController::class, 'analytics']);
    Route::get('/{id}', [PublishController::class, 'show']);
    Route::post('/', [PublishController::class, 'store']);
    Route::put('/{id}/status', [PublishController::class, 'updateStatus']);
    Route::post('/{id}/views', [PublishController::class, 'incrementViews']);
});

// Auto Blog routes (protected)
Route::prefix('blog')->middleware('auth:sanctum')->group(function () {
    Route::get('/next-pending', [AutoBlogController::class, 'nextPending']);
    Route::get('/history', [AutoBlogController::class, 'userHistory']);
    Route::get('/{id}', [AutoBlogController::class, 'show']);
    Route::put('/{id}/status', [AutoBlogController::class, 'updateStatus']);
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


Route::get('/sanctum-test', function () {
    $user = \App\Models\User::first();

    if (!$user) {
        return response()->json(['error' => 'No user found in tbl_user']);
    }

    $token = $user->createToken('test-token')->plainTextToken;

    return response()->json([
        'message' => 'Sanctum is now working!',
        'token'   => $token
    ]);
});

Route::get('/sanctum-quick-test', function () {
    $user = \App\Models\User::first();

    if (!$user) {
        return response()->json(['error' => 'No user found in tbl_user table']);
    }

    $token = $user->createToken('quick-test')->plainTextToken;

    return response()->json([
        'message' => 'Sanctum works now!',
        'token'   => $token
    ]);
});


