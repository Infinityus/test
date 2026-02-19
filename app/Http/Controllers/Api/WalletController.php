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
        
        return response()->json([
            'success' => true,
            'data' => [
                'life_time_earning' => number_format($wallet->life_time_earing, 2), // Changed
                'available_balance' => number_format($wallet->available_balance, 2),
                'pending_withdrawal' => number_format($wallet->pending_withdrawal, 2),
                'currency' => $wallet->currency,
                'total_earned' => number_format($wallet->total_earned, 2),
                'total_withdrawn' => number_format($wallet->total_withdrawn, 2)
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
        
        return response()->json([
            'success' => true,
            'data' => [
                'life_time_earning' => number_format($wallet->life_time_earing, 2), // Changed
                'available' => number_format($wallet->available_balance, 2),
                'currency' => $wallet->currency
            ]
        ]);
    }
}