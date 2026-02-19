<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Otp;
use App\Models\User;
use App\Models\Device;
use App\Models\DeviceHistory;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class OtpController extends Controller
{
    private function now(): Carbon
    {
        return Carbon::now('Asia/Kolkata');
    }

    private function checkOtpCooldown($otpRecord): ?JsonResponse
    {
        if (!$otpRecord) {
            return null;
        }

        $secondsSinceLastRequest = $otpRecord->updated_at->diffInSeconds($this->now());

        if ($secondsSinceLastRequest < 60) {
            $secondsLeft = 60 - $secondsSinceLastRequest;
            return response()->json([
                'success'      => false,
                'message'      => 'Please Wait Before Requesting A New OTP.',
                'wait_seconds' => max(0, $secondsLeft),
            ], 429);
        }

        return null;
    }

    private function checkOtpRateLimit(string $mobile): array
    {
        $otpRecord = Otp::getTodayOtpRecord($mobile);
        $currentRateLimit = $otpRecord ? $otpRecord->rate_limit : 0;
        $setLimit = $otpRecord ? $otpRecord->set_limit : 5;

        if ($currentRateLimit >= $setLimit) {
            return [
                'allowed' => false,
                'response' => response()->json([
                    'success' => false,
                    'message' => 'Too Many OTP Requests Today. Please Try Again Tomorrow.',
                ], 429)
            ];
        }

        return [
            'allowed' => true,
            'record' => $otpRecord
        ];
    }

    private function handleOtpRecord($user, string $mobile, string $otpCode, Carbon $expiry, string $ip, $otpRecord = null): void
    {
        if ($otpRecord) {
            $otpRecord->incrementRateLimit($otpCode, $expiry, $ip);
        } else {
            Otp::createNewOtp($user->id, $mobile, $otpCode, $expiry, $ip);
        }
    }

    private function getExpiryMinutes(int $attempt): int
    {
        return match ($attempt) {
            1 => 5,
            2 => 10,
            3 => 15,
            4 => 20,
            5 => 30,
            default => 5,
        };
    }

    private function prepareDeviceData(Request $request, string $token): array
    {
        $deviceName = $request->input('device_name') ??
            $request->header('X-Device-Name') ??
            $request->header('User-Agent', 'Unknown Device');

        $deviceDetails = getDeviceDetailsFromUserAgent($request->header('User-Agent', ''));

        return [
            'name' => $deviceName,
            'type' => $deviceDetails['device_type'] !== 'Unknown' ? $deviceDetails['device_type'] : $request->header('X-Device-Type', 'Unknown'),
            'os' => $deviceDetails['device_os'] !== 'Unknown' ? $deviceDetails['device_os'] : $request->header('X-Device-OS', 'Unknown'),
            'version' => $deviceDetails['app_version'] ?? $request->header('X-App-Version', null),
            'ip' => $request->ip(),
            'token' => $token
        ];
    }

    private function checkWaitingTime(int $userId): ?array
    {
        $activeWaitingDevice = Device::findActiveWaitingDevice($userId);

        if (!$activeWaitingDevice) {
            return null;
        }

        $waitingEndTime = Carbon::parse($activeWaitingDevice->waiting_time);
        $remainingMinutes = $this->now()->diffInMinutes($waitingEndTime, false);
        $remainingHours = floor($remainingMinutes / 60);
        $remainingMins = $remainingMinutes % 60;

        $timeString = $remainingHours > 0
            ? "{$remainingHours} hours " . ($remainingMins > 0 ? "{$remainingMins} minutes" : "")
            : "{$remainingMinutes} minutes";

        return [
            'success' => false,
            'message' => "Login restricted due to recent device change. Please wait for {$timeString}.",
            'waiting_time_remaining' => [
                'minutes' => $remainingMinutes,
                'hours' => round($remainingMinutes / 60, 1),
                'human_readable' => $timeString
            ],
            'waiting_until' => $waitingEndTime->toDateTimeString(),
            'device' => [
                'name' => $activeWaitingDevice->device_name,
                'type' => $activeWaitingDevice->device_type
            ]
        ];
    }

    public function sendOtp(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'mobile' => 'required|string|min:10|max:15|regex:/^[0-9+]+$/',
        ]);

        $mobile = trim($validated['mobile']);

        $otpCheck = $this->checkOtpRateLimit($mobile);
        if (!$otpCheck['allowed']) {
            return $otpCheck['response'];
        }
        $otpRecord = $otpCheck['record'];

        $cooldownResponse = $this->checkOtpCooldown($otpRecord);
        if ($cooldownResponse) {
            return $cooldownResponse;
        }

        $otpCode = rand(1000, 9999);
        $expiry = $this->now()->addMinutes(5);

        // ===== REPLACE THIS SECTION =====
        // Send OTP via both SMS and WhatsApp
        $otpResults = sendOtpBoth($mobile, $otpCode);

        // Check if at least one method succeeded
        if (!isAnyOtpSent($otpResults)) {
            $errorMessage = 'Failed To Send OTP. ';
            $debugInfo = [];

            if (!$otpResults['sms']['success']) {
                $errorMessage .= 'SMS failed. ';
                $debugInfo['sms_error'] = $otpResults['sms']['raw'];
            }

            if (!$otpResults['whatsapp']['success']) {
                $errorMessage .= 'WhatsApp failed. ';
                $debugInfo['whatsapp_error'] = $otpResults['whatsapp']['raw'];
            }

            return response()->json([
                'success' => false,
                'message' => $errorMessage . 'Please Try Again Later.',
                'debug'   => app()->environment('local') ? $debugInfo : null,
            ], 503);
        }

        // Log which methods succeeded (optional)
        Log::info('OTP sent results', [
            'mobile' => $mobile,
            'sms_success' => $otpResults['sms']['success'] ?? false,
            'whatsapp_success' => $otpResults['whatsapp']['success'] ?? false
        ]);
        // ===== END REPLACEMENT =====

        $user = User::findOrCreateUser($mobile, $request->header('User-Agent', 'Unknown Device'));
        $this->handleOtpRecord($user, $mobile, $otpCode, $expiry, $request->ip(), $otpRecord);

        return response()->json([
            'success' => true,
            'message' => 'OTP Sent Successfully',
            'mobile'  => $mobile,
        ], 200);
    }

    public function resendOtp(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'mobile' => 'required|string|min:10|max:15|regex:/^[0-9+]+$/',
        ]);

        $mobile = trim($validated['mobile']);

        $user = User::where('mobile', $mobile)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'No Account Found With This Mobile Number.',
            ], 404);
        }

        $otpRecord = Otp::getTodayOtpRecord($mobile);
        $currentRateLimit = $otpRecord ? $otpRecord->rate_limit : 0;

        if ($currentRateLimit >= 100) {
            return response()->json([
                'success' => false,
                'message' => 'Too Many OTP Requests Today. Please Try Again Tomorrow.',
            ], 429);
        }

        $cooldownResponse = $this->checkOtpCooldown($otpRecord);
        if ($cooldownResponse) {
            return $cooldownResponse;
        }

        $otpCode = rand(1000, 9999);
        $nextRateLimit = $currentRateLimit + 1;
        $expiry = $this->now()->addMinutes($this->getExpiryMinutes($nextRateLimit));

        // Send OTP via both SMS and WhatsApp
        $otpResults = sendOtpBoth($mobile, $otpCode);

        // Check if at least one method succeeded
        if (!isAnyOtpSent($otpResults)) {
            $errorMessage = 'Failed To Resend OTP. ';
            $debugInfo = [];

            if (!$otpResults['sms']['success']) {
                $errorMessage .= 'SMS failed. ';
                $debugInfo['sms_error'] = $otpResults['sms']['raw'];
            }

            if (!$otpResults['whatsapp']['success']) {
                $errorMessage .= 'WhatsApp failed. ';
                $debugInfo['whatsapp_error'] = $otpResults['whatsapp']['raw'];
            }

            return response()->json([
                'success' => false,
                'message' => $errorMessage . 'Please Try Again Later.',
                'debug'   => app()->environment('local') ? $debugInfo : null,
            ], 503);
        }

        // Log which methods succeeded (optional)
        Log::info('OTP resent results', [
            'mobile' => $mobile,
            'sms_success' => $otpResults['sms']['success'] ?? false,
            'whatsapp_success' => $otpResults['whatsapp']['success'] ?? false
        ]);

        /* $smsResult = sendOtpSms($mobile, $otpCode);

        if (!$smsResult['success']) {
            return response()->json([
                'success' => false,
                'message' => 'Failed To Resend OTP. Please Try Again Later.',
                'debug'   => app()->environment('local') ? ($smsResult['response']['Description'] ?? $smsResult['raw']) : null,
            ], 503);
        } */

        if ($otpRecord) {
            $otpRecord->incrementRateLimit($otpCode, $expiry, $request->ip());
        } else {
            Otp::createNewOtp($user->id, $mobile, $otpCode, $expiry, $request->ip());
        }

        return response()->json([
            'success' => true,
            'message' => 'OTP Resent Successfully',
            'mobile'  => $mobile,
        ], 200);
    }

    public function verifyOtp(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'mobile' => 'required|string|min:10|max:15|regex:/^[0-9+]+$/',
            'otp'    => 'required|string|size:4',
            'device_name' => 'required|string',
            'device_os' => 'required|string',
            'device_type' => 'required|string',
            'app_version' => 'required|string',
        ]);

        $mobile = trim($validated['mobile']);
        $otpInput = $validated['otp'];

        $otpRecord = Otp::findValidOtp($mobile, $otpInput);

        if (!$otpRecord) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Or Expired OTP.',
            ], 422);
        }

        $user = User::where('mobile', $mobile)->first();

        if (!$user) {
            $user = User::create([
                'mobile'      => $mobile,
                'name'        => 'User_' . substr($mobile, -6),
                'status'      => 1
            ]);
        }

        Device::cleanupExpiredWaitingTime($user->id);

        // Update user device info with data from frontend
        $user->updateDeviceInfo($validated['device_name'], $validated['device_name']);

        $waitingTimeCheck = $this->checkWaitingTime($user->id);
        if ($waitingTimeCheck) {
            return response()->json($waitingTimeCheck, 403);
        }

        $token = $user->createToken(
            name: 'mobile-app-' . $user->id,
            abilities: ['*'],
            expiresAt: $this->now()->addDays(30)
        )->plainTextToken;

        $deviceCheck = $this->handleDeviceManagement($user, $validated, $token);

        if (isset($deviceCheck['allowed']) && !$deviceCheck['allowed']) {
            return response()->json([
                'success' => false,
                'message' => $deviceCheck['message'],
                'remaining_hours' => $deviceCheck['remaining_hours'] ?? null,
                'remaining_minutes' => $deviceCheck['remaining_minutes'] ?? null,
                'waiting_until' => $deviceCheck['waiting_until'] ?? null
            ], 403);
        }

        $otpRecord->delete();
        $user->updateToken($token);


        $wallet = $this->createUserWallet($user);

        // Optional: Add welcome bonus for new users
        // $this->addWelcomeBonusIfNewUser($user, $wallet);

        return response()->json([
            'success' => true,
            'message' => 'OTP Verified Successfully. Login Successful.',
            'token'   => $token,
            'user'    => [
                'id'        => $user->id,
                'name'      => $user->name,
                'mobile'    => $user->mobile,
                'status'    => $user->status,
            ],
            'device' => [
                'id' => $deviceCheck['device_info']->id ?? null,
                'name' => $deviceCheck['device_info']->device_name ?? null,
                'type' => $deviceCheck['device_info']->device_type ?? null,
                'os' => $deviceCheck['device_info']->device_os ?? null,
                'is_new_device' => $deviceCheck['is_new_device'] ?? false,
                'waiting_time' => $deviceCheck['device_info']->waiting_time ?? null,
                'waiting_time_human' => $deviceCheck['waiting_time_human'] ?? null
            ]
        ], 200);
    }

    private function handleDeviceManagement($user, array $deviceData, string $token): array
    {
        $ipAddress = request()->ip();

        $existingDevices = Device::getUserDevices($user->id);

        // CASE 1: First device for user
        if ($existingDevices->isEmpty()) {
            $device = Device::createFirstDevice($user, [
                'device_name' => $deviceData['device_name'],
                'device_type' => $deviceData['device_type'],
                'device_os' => $deviceData['device_os'],
                'app_version' => $deviceData['app_version'],
                'ip_address' => $ipAddress
            ], $token);

            // Log to device history
            if (!DeviceHistory::hasFirstDeviceHistory($user, $deviceData)) {
                DeviceHistory::logDeviceHistory($user, $deviceData, 'first_device');
            }

            return [
                'allowed' => true,
                'message' => 'New device registered successfully',
                'device_info' => $device,
                'is_new_device' => true,
                'waiting_time_human' => 'No waiting period'
            ];
        }

        // CASE 2: Check if current device exists
        $currentDevice = Device::findDeviceByName($user->id, $deviceData['device_name']);

        if ($currentDevice) {
            $currentDevice->updateDeviceInfo([
                'ip_address' => $ipAddress,
                'device_type' => $deviceData['device_type'],
                'device_os' => $deviceData['device_os'],
                'app_version' => $deviceData['app_version']
            ], $token);

            // Log to device history (existing device login)
            DeviceHistory::logDeviceHistory($user, $deviceData, 'existing_device');

            return [
                'allowed' => true,
                'message' => 'Existing device updated successfully',
                'device_info' => $currentDevice,
                'is_new_device' => false,
                'waiting_time_human' => 'No waiting period'
            ];
        }

        // CASE 3: New device detected
        // Check if any existing device is in waiting period
        foreach ($existingDevices as $existingDevice) {
            if (isInWaitingPeriod($existingDevice->waiting_time)) {
                $waitingEndTime = Carbon::parse($existingDevice->waiting_time);
                $remainingMinutes = getRemainingWaitingMinutes($existingDevice->waiting_time);

                return [
                    'allowed' => false,
                    'message' => "Device change restricted. You changed device recently. Please wait for " .
                        formatWaitingTime($remainingMinutes) . ".",
                    'device_info' => $existingDevice,
                    'remaining_hours' => round($remainingMinutes / 60, 1),
                    'remaining_minutes' => $remainingMinutes,
                    'waiting_until' => $waitingEndTime->toDateTimeString()
                ];
            }
        }

        // No waiting period active, create or update device with 24-hour waiting
        $device = Device::where('user_id', $user->id)->first();

        if ($device) {
            $device->update([
                'device_name' => $deviceData['device_name'],
                'mobile' => $user->mobile,
                'token' => $token,
                'ip_address' => $ipAddress,
                'device_type' => $deviceData['device_type'],
                'device_os' => $deviceData['device_os'],
                'app_version' => $deviceData['app_version'],
                'last_used_at' => $this->now(),
                'updated_at' => Carbon::now('Asia/Kolkata'),
                'waiting_time' => $this->now()->addHours(24),
            ]);
        } else {
            $device = Device::create([
                'user_id' => $user->id,
                'device_name' => $deviceData['device_name'],
                'mobile' => $user->mobile,
                'token' => $token,
                'ip_address' => $ipAddress,
                'device_type' => $deviceData['device_type'],
                'device_os' => $deviceData['device_os'],
                'app_version' => $deviceData['app_version'],
                'last_used_at' => $this->now(),
                'waiting_time' => $this->now()->addHours(24),
            ]);
        }

        // Log device change to history
        DeviceHistory::logDeviceHistory($user, $deviceData, 'device_change');

        $device->refresh();

        // Check if waiting time was set and is in future
        if ($device->waiting_time && Carbon::parse($device->waiting_time)->isFuture()) {
            $waitingEndTime = Carbon::parse($device->waiting_time);
            $remainingMinutes = $this->now()->diffInMinutes($waitingEndTime, false);
            $remainingHours = floor($remainingMinutes / 60);
            $remainingMins = $remainingMinutes % 60;

            $timeString = $remainingHours > 0
                ? "{$remainingHours} hours " . ($remainingMins > 0 ? "{$remainingMins} minutes" : "")
                : "{$remainingMinutes} minutes";

            return [
                'allowed' => false,
                'message' => "Login restricted due to recent device change. Please wait for {$timeString}.",
                'device_info' => $device,
                'is_new_device' => true,
                'remaining_hours' => round($remainingMinutes / 60, 1),
                'remaining_minutes' => $remainingMinutes,
                'waiting_until' => $waitingEndTime->toDateTimeString(),
                'waiting_time_human' => $timeString
            ];
        }

        return [
            'allowed' => true,
            'message' => 'New device registered with 24-hour waiting period',
            'device_info' => $device,
            'is_new_device' => true,
            'waiting_time_human' => '24 hours from now',
            'waiting_until' => $device->waiting_time ? Carbon::parse($device->waiting_time)->toDateTimeString() : null
        ];
    }

    /**
     * Create wallet for user if not exists
     */
    private function createUserWallet($user)
    {
        $wallet = Wallet::firstOrCreate(
            ['user_id' => $user->id],
            [
                'life_time_earing' => 0.00, // Changed from 'balance'
                'total_earned' => 0.00,
                'total_withdrawn' => 0.00,
                'pending_withdrawal' => 0.00,
                'available_balance' => 0.00,
                'currency' => 'INR' // Changed to INR
            ]
        );

        if ($wallet->wasRecentlyCreated) {
            Log::info('New wallet created', ['user_id' => $user->id, 'wallet_id' => $wallet->id]);
        }

        return $wallet;
    }

    /**
     * Add welcome bonus for new users
     */
    private function addWelcomeBonusIfNewUser($user, $wallet)
    {
        $isNewUser = $user->created_at->diffInMinutes($this->now()) < 5;
        $bonusAmount = 10.00; // Amount in INR

        if ($isNewUser && $wallet->life_time_earing == 0) { // Changed from 'balance'
            // Update wallet
            $wallet->increment('life_time_earing', $bonusAmount); // Changed
            $wallet->increment('total_earned', $bonusAmount);
            $wallet->increment('available_balance', $bonusAmount);

            // Create transaction record
            WalletTransaction::create([
                'wallet_id' => $wallet->id,
                'user_id' => $user->id,
                'transaction_id' => WalletTransaction::generateTransactionId(),
                'type' => 'credit',
                'amount' => $bonusAmount,
                'balance_before' => 0,
                'balance_after' => $bonusAmount,
                'status' => 'completed',
                'description' => 'Welcome Bonus',
                'reference_type' => 'welcome_bonus',
                'processed_at' => $this->now()
            ]);

            Log::info('Welcome bonus added', ['user_id' => $user->id, 'amount' => $bonusAmount]);
        }
    }
}
