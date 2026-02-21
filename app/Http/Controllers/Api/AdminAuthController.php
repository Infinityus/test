<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AdminS;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    private function now(): Carbon
    {
        return Carbon::now('Asia/Kolkata');
    }

    /**
     * Admin login using email only (no password)
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'gmail' => 'required|email'
        ]);

        $admin = AdminS::where('gmail', $request->gmail)
            ->where('status', 1)
            ->first();

        if (!$admin) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email or account not found'
            ], 401);
        }

        // Generate token using Laravel Sanctum
        $token = $admin->createToken(
            name: 'admin-token-' . $admin->id,
            abilities: ['admin']
        )->plainTextToken;

        // Update admin record
        $admin->update([
            'token' => $token,
            'ip_address' => $request->ip(),
            'last_login' => $this->now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'admin' => [
                    //'id' => $admin->id,
                    'admin_name' => $admin->admin_name,
                    'gmail' => $admin->gmail,
                    'mobile' => $admin->mobile,
                    'role' => $admin->role,
                    'role_name' => $admin->role_name,
                    'status' => (bool) $admin->status
                ],
                'token' => $token,
                'token_type' => 'Bearer'
            ]
        ]);
    }

    /**
     * Verify admin session
     */
    public function verifySession(Request $request): JsonResponse
    {
        $admin = $request->user('admin');
        
        if (!$admin) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid session'
            ], 401);
        }

        return response()->json([
            'success' => true,
            'message' => 'Session is valid',
            'data' => [
                'admin' => [
                    'id' => $admin->id,
                    'admin_name' => $admin->admin_name,
                    'gmail' => $admin->gmail,
                    'mobile' => $admin->mobile,
                    'role' => $admin->role,
                    'role_name' => $admin->role_name,
                    'status' => (bool) $admin->status
                ]
            ]
        ]);
    }

    /**
     * Admin logout
     */
    public function logout(Request $request): JsonResponse
    {
        $admin = $request->user('admin');
        
        if ($admin) {
            // Delete current token
            $admin->currentAccessToken()->delete();
            
            // Clear token from admin record
            $admin->update(['token' => null]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Logout successful'
        ]);
    }

    /**
     * Get admin profile
     */
    public function profile(Request $request): JsonResponse
    {
        $admin = $request->user('admin');
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $admin->id,
                'admin_name' => $admin->admin_name,
                'gmail' => $admin->gmail,
                'mobile' => $admin->mobile,
                'role' => $admin->role,
                'role_name' => $admin->role_name,
                'status' => (bool) $admin->status,
                'last_login' => $admin->last_login,
                'ip_address' => $admin->ip_address
            ]
        ]);
    }
}