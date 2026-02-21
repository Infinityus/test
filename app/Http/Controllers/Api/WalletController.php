<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class WalletController extends Controller
{
    /**
     * Get wallet balance and overview
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $wallet = Wallet::getOrCreateForUser($user->id);
        $currentMonthYear  = now()->format('M Y'); // e.g., "Feb 2026"
        
        return response()->json([
            'success' => true,
            'data' => [
                'life_time_earning' => $wallet->life_time_earing, // Changed
                'available_balance' => $wallet->available_balance,
                'pending_withdrawal' => $wallet->pending_withdrawal,
                'currency' => $wallet->currency,
                'total_earned' => $wallet->total_earned,
                'total_withdrawn' => $wallet->total_withdrawn,
                'current_month_year' => $currentMonthYear
            ]
        ]);
    }

    /**
     * Get wallet balance only
     */
    public function balance(Request $request): JsonResponse
    {
        $user = $request->user();
        $wallet = Wallet::getOrCreateForUser($user->id);
        $currentMonthYear  = now()->format('M Y'); // e.g., "Feb 2026"
        
        return response()->json([
            'success' => true,
            'data' => [
                'life_time_earning' => $wallet->life_time_earing, // Changed
                'available' => $wallet->available_balance,
                'currency' => $wallet->currency,
                'current_month_year' => $currentMonthYear
            ]
        ]);
    }
}