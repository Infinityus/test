<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Otp;
use App\Models\User;
use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
//use App\Helpers\DeviceHelper;

class OtpController extends Controller
{
    public function sendOtp(Request $request): JsonResponse
    {
        // 1. Validate input
        $validated = $request->validate([
            'mobile' => 'required|string|min:10|max:15|regex:/^[0-9+]+$/',
        ]);

        $mobile = trim($validated['mobile']);
        // Log::info('OTP send requested', ['mobile' => $mobile, 'ip' => $request->ip()]);

        // 2. Check rate_limit from DB (today's attempts)
        // We consider only records from today to reset daily limit
        $todayStart = Carbon::today('Asia/Kolkata')->startOfDay();

        $otpRecord = Otp::where('mobile', $mobile)
            ->where('created_at', '>=', $todayStart)
            ->orderBy('created_at', 'desc')   // latest first
            ->first();

        $currentRateLimit = $otpRecord ? $otpRecord->rate_limit : 0;

        if ($currentRateLimit >= 100) {
            /* Log::warning('Daily rate limit exceeded', [
                'mobile'     => $mobile,
                'rate_limit' => $currentRateLimit
            ]); */

            return response()->json([
                'success' => false,
                'message' => 'Too Many OTP Requests Today. Please Try Again Tomorrow.',
            ], 429);
        }

        /* Log::debug('Current rate limit for mobile', [
            'mobile'     => $mobile,
            'rate_limit' => $currentRateLimit,
        ]); */

        // 3. Generate OTP
        $otpCode = rand(1000, 9999);
        $expiry  = Carbon::now('Asia/Kolkata')->addMinutes(5);

        if (app()->environment('local')) {
            /* Log::debug('Generated OTP (debug only)', [
                'mobile'     => $mobile,
                'otp'        => $otpCode,
                'expires_at' => $expiry,
            ]); */
        }

        // 4. Prepare SMS message
        $message = "Dear Customer, Your OTP for verification is $otpCode. Please enter this code to complete the process. TEXT2";
        $encodedMessage = urlencode($message);

        $smsUrl = "http://sms1.powerstext.in/http-tokenkeyapi.php?"
            . "authentic-key=3237726d73736f6c7574696f6e3130301741782806"
            . "&senderid=TETXTO"
            . "&route=1"
            . "&number=" . $mobile
            . "&message=" . $encodedMessage
            . "&templateid=1607100000000313572";

        /* Log::info('Preparing to send SMS', [
            'mobile'         => $mobile,
            'url'            => $smsUrl,
            'message_length' => strlen($message),
        ]); */

        // 5. Send SMS using Guzzle (your existing code)
        $client = new Client([
            'timeout'         => 15,
            'connect_timeout' => 10,
        ]);

        $smsSuccess = false;
        $smsResponseRaw = null;
        $responseData = null;

        try {
            $response = $client->get($smsUrl);
            $statusCode = $response->getStatusCode();
            $smsResponseRaw = $response->getBody()->getContents();

            /* Log::info('SMS HTTP response', [
                'mobile'      => $mobile,
                'status_code' => $statusCode,
                'raw'         => $smsResponseRaw,
            ]); */

            if ($statusCode !== 200) {
                throw new \Exception("HTTP status {$statusCode}");
            }

            $responseData = json_decode($smsResponseRaw, true);

            $smsSuccess = is_array($responseData) &&
                isset($responseData['Status']) &&
                $responseData['Status'] === 'Success';
        } catch (RequestException $e) {
            // Log::error('Guzzle request failed', ['mobile' => $mobile, 'error' => $e->getMessage()]);
        } catch (\Exception $e) {
            // Log::error('SMS sending exception', ['mobile' => $mobile, 'error' => $e->getMessage()]);
        }

        if (!$smsSuccess) {
            return response()->json([
                'success' => false,
                'message' => 'Failed To Send OTP. Please Try Again Later.',
                'debug'   => app()->environment('local') ? ($responseData['Description'] ?? $smsResponseRaw) : null,
            ], 503);
        }

        // 6. SMS success → create/update user
        $user = User::firstOrCreate(
            ['mobile' => $mobile],
            [
                'name'        => 'User_' . substr($mobile, -6),
                'status'      => 1,
                'device_name' => $request->header('User-Agent', 'Unknown Device'),
            ]
        );

        // 7. Handle OTP record with rate_limit increment
        if ($otpRecord) {
            // Update existing record (increase rate_limit and update timestamp)
            $otpRecord->update([
                'rate_limit'  => $otpRecord->rate_limit + 1,
                'updated_at'  => Carbon::now('Asia/Kolkata'),
                // You can keep old OTP or overwrite – usually overwrite
                'otp'         => $otpCode,
                'expiry_time' => $expiry,
                'ip_address'  => $request->ip(),
            ]);

            /* Log::info('Existing OTP updated with new rate_limit', [
                'user_id'    => $user->id,
                'mobile'     => $mobile,
                'new_limit'  => $otpRecord->fresh()->rate_limit,
            ]); */
        } else {
            // Create new record
            Otp::create([
                'user_id'     => $user->id,
                'mobile'      => $mobile,
                'otp'         => $otpCode,
                'expiry_time' => $expiry,
                'ip_address'  => $request->ip(),
                'rate_limit'  => 1,  // start from 1
            ]);

            /* Log::info('New OTP record created', [
                'user_id' => $user->id,
                'mobile'  => $mobile,
            ]); */
        }

        // 8. Success response
        return response()->json([
            'success' => true,
            'message' => 'OTP Sent Successfully',
            'mobile'  => $mobile,
        ], 200);
    }

    public function resendOtp(Request $request): JsonResponse
    {
        // 1. Validate input
        $validated = $request->validate([
            'mobile' => 'required|string|min:10|max:15|regex:/^[0-9+]+$/',
        ]);

        $mobile = trim($validated['mobile']);

        // 2. Check if user exists
        $user = User::where('mobile', $mobile)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'No Account Found With This Mobile Number.',
            ], 404);
        }

        // 3. Check daily rate_limit
        $todayStart = Carbon::today('Asia/Kolkata')->startOfDay();

        $otpRecord = Otp::where('mobile', $mobile)
            ->where('created_at', '>=', $todayStart)
            ->orderBy('created_at', 'desc')
            ->first();

        $currentRateLimit = $otpRecord ? $otpRecord->rate_limit : 0;

        if ($currentRateLimit >= 100) {
            return response()->json([
                'success' => false,
                'message' => 'Too Many OTP Requests Today. Please Try Again Tomorrow.',
            ], 429);
        }

        // 4. Extra cooldown check (60 seconds since last resend/update)
        if ($otpRecord && $otpRecord->updated_at->diffInSeconds(Carbon::now('Asia/Kolkata')) < 60) {
            $secondsLeft = 60 - $otpRecord->updated_at->diffInSeconds(Carbon::now('Asia/Kolkata'));
            return response()->json([
                'success'      => false,
                'message'      => 'Please Wait Before Requesting A New OTP.',
                'wait_seconds' => max(0, $secondsLeft),
            ], 429);
        }

        // 5. Generate new OTP
        $otpCode = rand(1000, 9999);

        // 6. Calculate new expiry based on upcoming rate_limit value
        $nextRateLimit = $currentRateLimit + 1;

        $expiryMinutes = match ($nextRateLimit) {
            1 => 5,
            2 => 10,
            3 => 15,
            4 => 20,
            5 => 30,
            default => 5, // fallback
        };

        $expiry = Carbon::now('Asia/Kolkata')->addMinutes($expiryMinutes);

        // 7. Prepare SMS message
        $message = "Dear Customer, Your OTP for verification is $otpCode. Please enter this code to complete the process. TEXT2";
        $encodedMessage = urlencode($message);

        $smsUrl = "http://sms1.powerstext.in/http-tokenkeyapi.php?"
            . "authentic-key=3237726d73736f6c7574696f6e3130301741782806"
            . "&senderid=TETXTO"
            . "&route=1"
            . "&number=" . $mobile
            . "&message=" . $encodedMessage
            . "&templateid=1607100000000313572";

        // 8. Send SMS using Guzzle
        $client = new Client([
            'timeout'         => 15,
            'connect_timeout' => 10,
        ]);

        $smsSuccess = false;
        $smsResponseRaw = null;
        $responseData = null;

        try {
            $response = $client->get($smsUrl);
            $statusCode = $response->getStatusCode();
            $smsResponseRaw = $response->getBody()->getContents();

            if ($statusCode !== 200) {
                throw new \Exception("HTTP status {$statusCode}");
            }

            $responseData = json_decode($smsResponseRaw, true);

            $smsSuccess = is_array($responseData) &&
                isset($responseData['Status']) &&
                $responseData['Status'] === 'Success';
        } catch (RequestException $e) {
            // silent fail
        } catch (\Exception $e) {
            // silent fail
        }

        if (!$smsSuccess) {
            return response()->json([
                'success' => false,
                'message' => 'Failed To Resend OTP. Please Try Again Later.',
                'debug'   => app()->environment('local') ? ($responseData['Description'] ?? $smsResponseRaw) : null,
            ], 503);
        }

        // 9. Update or create OTP record
        if ($otpRecord) {
            $otpRecord->update([
                'rate_limit'  => $nextRateLimit,
                'updated_at'  => Carbon::now('Asia/Kolkata'),
                'otp'         => $otpCode,
                'expiry_time' => $expiry,
                'ip_address'  => $request->ip(),
            ]);
        } else {
            Otp::create([
                'user_id'     => $user->id,
                'mobile'      => $mobile,
                'otp'         => $otpCode,
                'expiry_time' => $expiry,
                'ip_address'  => $request->ip(),
                'rate_limit'  => 1,
            ]);
        }

        // 10. Success response
        return response()->json([
            'success' => true,
            'message' => 'OTP Resent Successfully',
            'mobile'  => $mobile,
        ], 200);
    }

    public function verifyOtp(Request $request): JsonResponse
    {
        // 1. Validate input
        $validated = $request->validate([
            'mobile' => 'required|string|min:10|max:15|regex:/^[0-9+]+$/',
            'otp'    => 'required|string|size:4', // assuming 4-digit OTP
            'device_name' => 'sometimes|string',
            // 'fcm_token' => 'sometimes|string',
        ]);

        $mobile = trim($validated['mobile']);
        $otpInput = $validated['otp'];

        // 2. Find the latest valid OTP for this mobile
        $otpRecord = Otp::where('mobile', $mobile)
            ->where('otp', $otpInput)
            ->where('expiry_time', '>', Carbon::now('Asia/Kolkata'))
            ->latest()
            ->first();

        if (!$otpRecord) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Or Expired OTP.',
            ], 422);
        }

        // 3. OTP is valid → get or create user
        $user = User::where('mobile', $mobile)->first();

        if (!$user) {
            // Create user if not exists (for first-time login)
            $user = User::create([
                'mobile'      => $mobile,
                'name'        => 'User_' . substr($mobile, -6),
                'status'      => 1
            ]);
        }

        // 4. Update device name if sent
        if ($request->filled('device_name')) {
            $user->update(['device_name' => $request->device_name]);
        } else {
            $user->update(['device_name' => $request->header('User-Agent', 'Unknown Device')]);
        }
        $user->update(['updated_at' => now('Asia/Kolkata')]);

        //CHECK WAITING TIME - If any device has active waiting period, block login
        $activeWaitingDevice = Device::where('user_id', $user->id)
            ->where('waiting_time', '>', now('Asia/Kolkata'))
            ->orderBy('waiting_time', 'desc')
            ->first();

        if ($activeWaitingDevice) {
            $waitingEndTime = Carbon::parse($activeWaitingDevice->waiting_time);
            $remainingMinutes = now('Asia/Kolkata')->diffInMinutes($waitingEndTime, false);
            $remainingHours = floor($remainingMinutes / 60);
            $remainingMins = $remainingMinutes % 60;

            $timeString = $remainingHours > 0
                ? "{$remainingHours} hours " . ($remainingMins > 0 ? "{$remainingMins} minutes" : "")
                : "{$remainingMinutes} minutes";

            return response()->json([
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
            ], 403);
        }

        // 5. Create Sanctum token
        $token = $user->createToken(
            name: 'mobile-app-' . $user->id,
            abilities: ['*'],
            expiresAt: now('Asia/Kolkata')->addDays(30)
        )->plainTextToken;

        // 6. Handle device management (check device change and waiting period)
        $deviceCheck = $this->handleDeviceManagement($user, $request, $token);

        // If device is in waiting period, return error
        /* if (!$deviceCheck['allowed']) {
            return response()->json([
                'success' => false,
                'message' => $deviceCheck['message'],
                'remaining_hours' => $deviceCheck['remaining_hours'] ?? null,
                'remaining_minutes' => $deviceCheck['remaining_minutes'] ?? null,
                'waiting_until' => $deviceCheck['waiting_until'] ?? null
            ], 403);
        } */
        if (isset($deviceCheck['allowed']) && !$deviceCheck['allowed']) {
            return response()->json([
                'success' => false,
                'message' => $deviceCheck['message'],
                'remaining_hours' => $deviceCheck['remaining_hours'] ?? null,
                'remaining_minutes' => $deviceCheck['remaining_minutes'] ?? null,
                'waiting_until' => $deviceCheck['waiting_until'] ?? null
            ], 403);
        }

        // 7. Delete used OTP (security best practice)
        // $otpRecord->delete();

        // 8. Update FCM token if provided
        // if ($request->filled('fcm_token')) {
        //     $user->update(['fcm_token' => $request->fcm_token]);
        // }
        $user->update(['token' => $token]);

        // 9. Success response with token and device info
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

    /**
     * Handle device management logic
     * 
     * @param User $user
     * @param Request $request
     * @param string $token
     * @return array
     */
    private function handleDeviceManagement($user, $request, $token): array
    {
        // Get device information from request headers or input
        $deviceName = $request->input('device_name') ??
            $request->header('X-Device-Name') ??
            $request->header('User-Agent', 'Unknown Device');

        // Get device details from User-Agent using helper
        $deviceDetails = getDeviceDetailsFromUserAgent($request->header('User-Agent', ''));

        $deviceType = $deviceDetails['device_type'];
        $deviceOs = $deviceDetails['device_os'];
        $appVersion = $deviceDetails['app_version'];

        // Fallback to headers if User-Agent parsing fails
        if ($deviceType === 'Unknown') {
            $deviceType = $request->header('X-Device-Type', 'Unknown');
        }
        if ($deviceOs === 'Unknown') {
            $deviceOs = $request->header('X-Device-OS', 'Unknown');
        }
        if (!$appVersion) {
            $appVersion = $request->header('X-App-Version', null);
        }

        $ipAddress = $request->ip();

        // Check if user already has devices
        $existingDevices = Device::where('user_id', $user->id)->get();

        // CASE 1: User has no devices (first login)
        if ($existingDevices->isEmpty()) {
            // Insert new device (no waiting time for first device)
            $device = Device::create([
                'user_id' => $user->id,
                'device_name' => $deviceName,
                'mobile' => $user->mobile,
                'token' => $token,
                'ip_address' => $ipAddress,
                'device_type' => $deviceType,
                'device_os' => $deviceOs,
                'app_version' => $appVersion,
                'last_used_at' => now('Asia/Kolkata'),
                'waiting_time' => null, // No waiting time for first device
            ]);

            return [
                'allowed' => true,
                'message' => 'New device registered successfully',
                'device_info' => $device,
                'is_new_device' => true,
                'waiting_time_human' => 'No waiting period'
            ];
        }

        // CASE 2: User has existing devices, check if current device exists
        $currentDevice = $existingDevices->where('device_name', $deviceName)->first();

        if ($currentDevice) {
            // Same device - just update token and info
            $currentDevice->update([
                'token' => $token,
                'ip_address' => $ipAddress,
                'device_type' => $deviceType,
                'device_os' => $deviceOs,
                'app_version' => $appVersion,
                'last_used_at' => now('Asia/Kolkata'),
                'waiting_time' => null, // Clear waiting time if any
            ]);

            return [
                'allowed' => true,
                'message' => 'Existing device updated successfully',
                'device_info' => $currentDevice,
                'is_new_device' => false,
                'waiting_time_human' => 'No waiting period'
            ];
        } else {
            // CASE 3: New device detected
            // Check if any existing device is in waiting period
            /* foreach ($existingDevices as $existingDevice) {
                if (isInWaitingPeriod($existingDevice->waiting_time)) {
                    // There's an active waiting period
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
            } */

            // No waiting period active, create new device and set 24-hour waiting period
            /* $newDevice = Device::update([
                'user_id' => $user->id,
                'device_name' => $deviceName,
                'mobile' => $user->mobile,
                'token' => $token,
                'ip_address' => $ipAddress,
                'device_type' => $deviceType,
                'device_os' => $deviceOs,
                'app_version' => $appVersion,
                'last_used_at' => now('Asia/Kolkata'),
                'updated_at' => now('Asia/Kolkata'),
                'waiting_time' => now('Asia/Kolkata')->addHours(24), // 24 hour restriction
            ]); */
            $newDevice = Device::where('user_id', $user->id)->first();
            if ($newDevice) {
                $newDevice->update([
                    'device_name' => $deviceName,
                    'mobile' => $user->mobile,
                    'token' => $token,
                    'ip_address' => $ipAddress,
                    'device_type' => $deviceType,
                    'device_os' => $deviceOs,
                    'app_version' => $appVersion,
                    'last_used_at' => now('Asia/Kolkata'),
                    'waiting_time' => now('Asia/Kolkata')->addHours(24),
                ]);
            }


            /* // Also update other devices to have waiting period (optional - to track that a change occurred)
            foreach ($existingDevices as $existingDevice) {
                if (!$existingDevice->waiting_time) {
                    $existingDevice->update([
                        'waiting_time' => now('Asia/Kolkata')->addHours(24)
                    ]);
                }
            } */

            /* $newDevice->refresh();
            if ($newDevice->waiting_time) {
                $waitingEndTime = Carbon::parse($newDevice->waiting_time);
                $remainingMinutes = now('Asia/Kolkata')->diffInMinutes($waitingEndTime, false);
                $remainingHours = floor($remainingMinutes / 60);
                $remainingMins = $remainingMinutes % 60;

                $timeString = $remainingHours > 0
                    ? "{$remainingHours} hours " . ($remainingMins > 0 ? "{$remainingMins} minutes" : "")
                    : "{$remainingMinutes} minutes";

                return response()->json([
                    'success' => false,
                    'message' => "Login restricted due to recent device change. Please wait for {$timeString}.",
                    'waiting_time_remaining' => [
                        'minutes' => $remainingMinutes,
                        'hours' => round($remainingMinutes / 60, 1),
                        'human_readable' => $timeString
                    ],
                    'waiting_until' => $waitingEndTime->toDateTimeString(),
                    'device' => [
                        'name' => $deviceName,
                        'type' => $deviceType
                    ]
                ], 403);
            } */
            $newDevice->refresh();

            // Check if waiting time was set
            if ($newDevice->waiting_time && Carbon::parse($newDevice->waiting_time)->isFuture()) {
                $waitingEndTime = Carbon::parse($newDevice->waiting_time);
                $remainingMinutes = now('Asia/Kolkata')->diffInMinutes($waitingEndTime, false);
                $remainingHours = floor($remainingMinutes / 60);
                $remainingMins = $remainingMinutes % 60;

                $timeString = $remainingHours > 0
                    ? "{$remainingHours} hours " . ($remainingMins > 0 ? "{$remainingMins} minutes" : "")
                    : "{$remainingMinutes} minutes";

                return [
                    'allowed' => false,
                    'message' => "Login restricted due to recent device change. Please wait for {$timeString}.",
                    'device_info' => $newDevice,
                    'is_new_device' => true,
                    'remaining_hours' => round($remainingMinutes / 60, 1),
                    'remaining_minutes' => $remainingMinutes,
                    'waiting_until' => $waitingEndTime->toDateTimeString(),
                    'waiting_time_human' => $timeString
                ];
            }

            $waitingEndTime = Carbon::parse($newDevice->waiting_time);

            return [
                'allowed' => true,
                'message' => 'New device registered with 24-hour waiting period',
                'device_info' => $newDevice,
                'is_new_device' => true,
                'waiting_time_human' => '24 hours from now',
                'waiting_until' => $waitingEndTime->toDateTimeString()
            ];
        }
    }
}
