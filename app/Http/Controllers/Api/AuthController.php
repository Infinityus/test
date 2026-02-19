<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    
    /**
     * Verify if the current session/token is valid
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function verifySession(Request $request): JsonResponse
    {
        // The auth:sanctum middleware already verifies the token
        // If we reach here, the token is valid
        $user = $request->user();
        return response()->json([
            'success' => true,
            'message' => 'Session is valid',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'mobile' => $user->mobile,
                'status' => (bool) $user->status
            ]
        ]);
    }
        
    /**
     * Get authenticated user details
     */
    public function user(Request $request): JsonResponse
    {
        $user = $request->user();
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'mobile' => $user->mobile,
                'status' => $user->status,
                'device_name' => $user->device_name,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at
            ]
        ]);
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'device_name' => 'sometimes|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();
        
        if ($request->has('name')) {
            $user->name = $request->name;
        }
        
        if ($request->has('device_name')) {
            $user->device_name = $request->device_name;
        }
        
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => $user
        ]);
    }

    /**
     * Logout (revoke token)
     */
    public function logout(Request $request): JsonResponse
    {
        // Revoke current token
        $request->user()->currentAccessToken()->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
    }

    /**
     * Logout from all devices (revoke all tokens)
     */
    public function logoutAll(Request $request): JsonResponse
    {
        // Revoke all tokens for this user
        $request->user()->tokens()->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Logged out from all devices successfully'
        ]);
    }

    /**
     * Get user devices history
     */
    public function devices(Request $request): JsonResponse
    {
        $devices = $request->user()->devices()
            ->orderBy('last_used_at', 'desc')
            ->get(['id', 'device_name', 'device_type', 'device_os', 'last_used_at', 'waiting_time']);
        
        return response()->json([
            'success' => true,
            'data' => $devices
        ]);
    }

    /**
     * Get user login history
     */
    public function loginHistory(Request $request): JsonResponse
    {
        $history = \App\Models\DeviceHistory::where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $history
        ]);
    }

    /**
     * Change password (if you add password field later)
     */
    public function changePassword(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();
        
        // Check if user has password (if you add this feature)
        if (!$user->password) {
            return response()->json([
                'success' => false,
                'message' => 'Password not set for this account'
            ], 400);
        }

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect'
            ], 401);
        }

        // Update password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Password changed successfully'
        ]);
    }
}