<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\OtpController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\WalletController;
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

/*// In routes/api.php, update your fallback route:
Route::fallback(function (Request $request) {
    // Only handle GET requests to API routes
    if ($request->isMethod('get') && $request->is('api/*')) {
        // Return 403 Forbidden with JSON response
        return response()->json([
            'success' => false,
            'message' => 'Access denied. API endpoints cannot be accessed via browser.'
        ], 403); // Changed from 405 to 403
    }
    
    // Let other requests pass through
    return response()->json([
        'success' => false,
        'message' => 'API route not found'
    ], 404);
});*/

// Add this at the TOP of your routes/api.php (before other routes)
Route::get('/{any}', function() {
    return response()->json([
        'success' => false,
        'message' => 'GET method is not supported for API endpoints. Use POST instead.'
    ], 405);
})->where('any', '.*');

// Or add a catch-all at the BOTTOM (after all routes)
Route::any('{any}', function() {
    return response()->json([
        'success' => false,
        'message' => 'API route not found or method not allowed'
    ], 404);
})->where('any', '.*');

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


//Route::post('/send-otp', [OtpController::class, 'sendOtp'])
    //->middleware('auth:sanctum');
