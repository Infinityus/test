<?php

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use App\Models\WhatsAppApi;

// ============================================
// EXISTING SMS FUNCTION - NOT CHANGED
// ============================================
if (!function_exists('sendOtpSms')) {
    function sendOtpSms(string $mobile, string $otpCode): array
    {
        $message = "Dear Customer, Your OTP for verification is $otpCode. Please enter this code to complete the process. TEXT2";
        $encodedMessage = urlencode($message);

        $smsUrl = "http://sms1.powerstext.in/http-tokenkeyapi.php?"
            . "authentic-key=3237726d73736f6c7574696f6e3130301741782806"
            . "&senderid=TETXTO"
            . "&route=1"
            . "&number=" . $mobile
            . "&message=" . $encodedMessage
            . "&templateid=1607100000000313572";

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
            Log::error('Guzzle request failed', ['mobile' => $mobile, 'error' => $e->getMessage()]);
        } catch (\Exception $e) {
            Log::error('SMS sending exception', ['mobile' => $mobile, 'error' => $e->getMessage()]);
        }

        return [
            'success' => $smsSuccess,
            'response' => $responseData,
            'raw' => $smsResponseRaw
        ];
    }
}

// ============================================
// NEW WHATSAPP FUNCTION - ADDED
// ============================================
if (!function_exists('sendOtpWhatsApp')) {
    /**
     * Send OTP via WhatsApp API
     * 
     * @param string $mobile
     * @param string $otpCode
     * @return array ['success' => bool, 'response' => mixed, 'raw' => mixed]
     */
    function sendOtpWhatsApp(string $mobile, string $otpCode): array
    {
        // Get WhatsApp API configuration from database
        $whatsappConfig = WhatsAppApi::getActiveOtpConfig();
        
        if (!$whatsappConfig) {
            Log::error('WhatsApp API configuration not found', ['mobile' => $mobile]);
            return [
                'success' => false,
                'response' => null,
                'raw' => 'WhatsApp API configuration not found'
            ];
        }

        $apiUrl = "http://bhashsms.com/api/sendmsgutil.php";
        
        $params = [
            'user' => $whatsappConfig->user,
            'pass' => $whatsappConfig->pass,
            'sender' => $whatsappConfig->sender,
            'phone' => $mobile,
            'text' => $whatsappConfig->text,
            'priority' => $whatsappConfig->priority,
            'stype' => $whatsappConfig->stype,
            'Params' => $otpCode
        ];

        $client = new Client([
            'timeout' => 15,
            'connect_timeout' => 10,
        ]);

        $whatsappSuccess = false;
        $responseRaw = null;
        $responseData = null;

        try {
            $response = $client->get($apiUrl, ['query' => $params]);
            $statusCode = $response->getStatusCode();
            $responseRaw = $response->getBody()->getContents();

            if ($statusCode !== 200) {
                throw new \Exception("HTTP status {$statusCode}");
            }

            // Try to parse JSON response, but handle non-JSON responses too
            $responseData = json_decode($responseRaw, true);
            
            // Check success based on response (adjust based on actual API response format)
            if (is_array($responseData)) {
                $whatsappSuccess = isset($responseData['status']) && $responseData['status'] === 'success';
            } else {
                // If response is not JSON, assume success if no exception was thrown
                $whatsappSuccess = true;
            }

        } catch (RequestException $e) {
            Log::error('WhatsApp API request failed', [
                'mobile' => $mobile, 
                'error' => $e->getMessage()
            ]);
            $responseRaw = $e->getMessage();
        } catch (\Exception $e) {
            Log::error('WhatsApp API exception', [
                'mobile' => $mobile, 
                'error' => $e->getMessage()
            ]);
            $responseRaw = $e->getMessage();
        }

        return [
            'success' => $whatsappSuccess,
            'response' => $responseData,
            'raw' => $responseRaw
        ];
    }
}

// ============================================
// COMBINED FUNCTION - SENDS BOTH SMS AND WHATSAPP
// ============================================
if (!function_exists('sendOtpBoth')) {
    /**
     * Send OTP via both SMS and WhatsApp
     * 
     * @param string $mobile
     * @param string $otpCode
     * @return array ['sms' => array, 'whatsapp' => array]
     */
    function sendOtpBoth(string $mobile, string $otpCode): array
    {
        $results = [
            'sms' => null,
            'whatsapp' => null
        ];

        // Send SMS (your existing function)
        try {
            $results['sms'] = sendOtpSms($mobile, $otpCode);
        } catch (\Exception $e) {
            Log::error('Failed to send SMS in both function', [
                'mobile' => $mobile, 
                'error' => $e->getMessage()
            ]);
            $results['sms'] = [
                'success' => false,
                'response' => null,
                'raw' => $e->getMessage()
            ];
        }

        // Send WhatsApp (new function)
        try {
            $results['whatsapp'] = sendOtpWhatsApp($mobile, $otpCode);
        } catch (\Exception $e) {
            Log::error('Failed to send WhatsApp in both function', [
                'mobile' => $mobile, 
                'error' => $e->getMessage()
            ]);
            $results['whatsapp'] = [
                'success' => false,
                'response' => null,
                'raw' => $e->getMessage()
            ];
        }

        return $results;
    }
}

// ============================================
// HELPER FUNCTION TO CHECK IF ANY METHOD SUCCEEDED
// ============================================
if (!function_exists('isAnyOtpSent')) {
    /**
     * Check if OTP was sent via any method
     * 
     * @param array $results
     * @return bool
     */
    function isAnyOtpSent(array $results): bool
    {
        return (isset($results['sms']['success']) && $results['sms']['success'] === true) ||
               (isset($results['whatsapp']['success']) && $results['whatsapp']['success'] === true);
    }
}