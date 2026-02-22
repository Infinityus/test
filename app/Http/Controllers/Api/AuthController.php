<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Log;



class AuthController extends Controller
{

    private function now(): Carbon
    {
        return Carbon::now('Asia/Kolkata');
    }

    public function verifySession(Request $request): JsonResponse
    {
        $user = $request->user();
        $oldToken = $this->extractTokenFromRequest($request);
        Log::info('verifySession', [
            'user' => $user->mobile,
            'old_token' => $oldToken,
            'time' => now()->format('Y-m-d H:i:s')
        ]);
        $currentToken = $request->bearerToken();

        $tokenModel = $user->currentAccessToken();

        if (!$tokenModel) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid token'
            ], 401);
        }
        Log::info('Check-tokens', [
                'db_token' => $user->token,
                'received_token' => $currentToken,
                'time' => now()->format('Y-m-d H:i:s')
            ]);
        
        if ($user->token !== $currentToken) {
            Log::info('Session expired', [
                'db_token' => $user->token,
                'received_token' => $currentToken,
                'time' => now()->format('Y-m-d H:i:s')
            ]);
            // Token mismatch - delete this token as it's not the active one
            $tokenModel->delete();
            return response()->json([
                'success' => false,
                'message' => 'Session expired. Please login again.'
            ], 401);
        }

        $latestToken = $user->tokens()->where('name', 'like', 'mobile-app-%')->orderBy('created_at', 'desc')->first();

        if ($tokenModel->expires_at && $tokenModel->expires_at->isPast()) {
            $tokenModel->delete();
            return response()->json([
                'success' => false,
                'message' => 'Token expired'
            ], 401);
        }

        // Check if this is the latest token (single device check)
        if (!$latestToken || $latestToken->id !== $tokenModel->id) {
            $tokenModel->delete();
            return response()->json([
                'success' => false,
                'message' => 'You have been logged out because you logged in from another device.'
            ], 401);
        }

        $tokenModel->update(['last_used_at' => now()]);
        $device = Device::where('user_id', $user->id)->where('token', $currentToken)->first();
        return response()->json([
            'success' => true,
            'message' => 'Session is valid',
            'user' => [
                //'id' => $user->id,
                'name' => $user->name,
                'mobile' => $user->mobile,
                'status' => (bool) $user->status,
            ],
            'device' => $device ? [
                'name' => $device->device_name,
                'type' => $device->device_type,
                'os' => $device->device_os,
                'last_used' => $device->last_used_at,
            ] : null,
            'token' => $currentToken
        ]);
    }

    public function refreshToken(Request $request): JsonResponse
    {
        // Get token from request
        $oldToken = $this->extractTokenFromRequest($request);
        
        if (!$oldToken) {
            return $this->errorResponse('Token is required', 400);
        }
        
        // Extract token ID from the token string (format: id|token)
        $tokenParts = explode('|', $oldToken);
        if (count($tokenParts) !== 2) {
            return $this->errorResponse('Invalid token format', 400);
        }
        
        $tokenId = $tokenParts[0];
        
        // Find token in database
        $tokenModel = PersonalAccessToken::find($tokenId);
        if (!$tokenModel) {
            return $this->errorResponse('Token not found', 401);
        }
        
        // Get user
        $user = $tokenModel->tokenable;
        if (!$user) {
            return $this->errorResponse('User not found', 401);
        }
        
        $oldToken = $this->extractTokenFromRequest($request);
        /*Log::info('refreshToken', [
            'user' => $user,
            'time' => now()->format('Y-m-d H:i:s')
        ]);*/
        
        // 🔴 NEW VALIDATION: Check if old token matches tbl_user.token
        if ($user->token !== $oldToken) {
            return $this->errorResponse('Token mismatch. Please login again.', 401);
        }
        
        // Check if token is expired
        if ($tokenModel->expires_at && Carbon::parse($tokenModel->expires_at)->isPast()) {
            $tokenModel->delete();
            $user->update(['token' => null]);
            return $this->errorResponse('Token has expired. Please login again.', 401);
        }
        
        // Generate new token
        $newToken = $user->createToken(
            name: 'mobile-app-' . $user->id,
            abilities: ['*'],
            expiresAt: $this->now()->addDays(30)
        )->plainTextToken;
        
        // Update records
        $user->update(['token' => $newToken]);
        Device::where('user_id', $user->id)
            ->where('token', $oldToken)
            ->update(['token' => $newToken]);
        
        // Delete old token
        $tokenModel->delete();
        
        Log::info('refreshToken', [
            'old_token from refresh' => $oldToken,
            'new-token' => $newToken,
            'time' => now()->format('Y-m-d H:i:s')
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Token refreshed successfully',
            'token' => $newToken,
            'expires_in' => 30 * 24 * 60 * 60, // 30 days in seconds
            'user' => [
                'name' => $user->name,
                'mobile' => $user->mobile,
                'status' => (bool) $user->status
            ]
        ]);
    }
    
    /**
     * Extract token from request header or body
     */
    private function extractTokenFromRequest(Request $request): ?string
    {
        // Check Authorization header
        if ($request->hasHeader('Authorization')) {
            $authHeader = $request->header('Authorization');
            if (str_starts_with($authHeader, 'Bearer ')) {
                return substr($authHeader, 7);
            }
        }
        
        // Check request body
        if ($request->has('token')) {
            return $request->input('token');
        }
        
        return null;
    }
    
    /**
     * Return error response
     */
    private function errorResponse(string $message, int $statusCode): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message
        ], $statusCode);
    }

    /**
     * Get authenticated user details
     */
    public function user(Request $request): JsonResponse
    {
        $user = $request->user();
        
        // Trim the name: take first word only and limit to 24 characters
        $nameParts = explode(' ', trim($user->name));
        $firstName = $nameParts[0]; // Get first word
        
        // Limit to 24 characters
        $trimmedName = substr($firstName, 0, 24);

        return response()->json([
            'success' => true,
            'data' => [
                //'id' => $user->id,
                'name' => $trimmedName,
                'mobile' => $user->mobile,
                'status' => $user->status,
                'device_name' => $user->device_name,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at
            ]
        ]);
    }

}
