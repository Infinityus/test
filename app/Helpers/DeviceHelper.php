<?php

//namespace App\Helpers;

use Illuminate\Support\Carbon;

if (!function_exists('getDeviceDetailsFromUserAgent')) {
    function getDeviceDetailsFromUserAgent(string $userAgent): array
    {
        $deviceType = 'Unknown';
        $deviceOs = 'Unknown';
        $appVersion = null;
        
        if (empty($userAgent)) {
            return [
                'device_type' => $deviceType,
                'device_os' => $deviceOs,
                'app_version' => $appVersion
            ];
        }
        
        // Detect Device Type
        if (preg_match('/mobile|iphone|ipod|android|blackberry|windows phone/i', $userAgent)) {
            $deviceType = 'Mobile';
        } elseif (preg_match('/tablet|ipad|kindle|playbook|silk/i', $userAgent)) {
            $deviceType = 'Tablet';
        } elseif (preg_match('/windows nt|macintosh|linux x86_64|cros/i', $userAgent)) {
            $deviceType = 'Desktop';
        } elseif (preg_match('/bot|crawler|spider|scraper/i', $userAgent)) {
            $deviceType = 'Bot';
        }
        
        // Detect Operating System
        if (strpos($userAgent, 'Android') !== false) {
            preg_match('/Android\s([0-9\.]+)/i', $userAgent, $matches);
            $version = $matches[1] ?? '';
            $deviceOs = 'Android' . ($version ? ' ' . $version : '');
        }
        elseif (strpos($userAgent, 'iPhone') !== false || strpos($userAgent, 'iPad') !== false || strpos($userAgent, 'iPod') !== false) {
            preg_match('/OS\s([0-9_]+)/i', $userAgent, $matches);
            $version = isset($matches[1]) ? str_replace('_', '.', $matches[1]) : '';
            $deviceOs = 'iOS' . ($version ? ' ' . $version : '');
        }
        elseif (strpos($userAgent, 'Windows NT') !== false) {
            preg_match('/Windows NT\s([0-9\.]+)/i', $userAgent, $matches);
            $version = $matches[1] ?? '';
            $windowsVersions = [
                '10.0' => 'Windows 10',
                '6.3' => 'Windows 8.1',
                '6.2' => 'Windows 8',
                '6.1' => 'Windows 7',
                '6.0' => 'Windows Vista',
                '5.2' => 'Windows XP x64',
                '5.1' => 'Windows XP',
            ];
            $deviceOs = $windowsVersions[$version] ?? ('Windows ' . $version);
        }
        elseif (strpos($userAgent, 'Macintosh') !== false) {
            preg_match('/OS X\s([0-9_]+)/i', $userAgent, $matches);
            $version = isset($matches[1]) ? str_replace('_', '.', $matches[1]) : '';
            $deviceOs = 'macOS' . ($version ? ' ' . $version : '');
        }
        elseif (strpos($userAgent, 'Linux') !== false) {
            $deviceOs = 'Linux';
        }
        elseif (strpos($userAgent, 'CrOS') !== false) {
            $deviceOs = 'Chrome OS';
        }
        
        // Detect App Version
        if (strpos($userAgent, 'Flutter') !== false) {
            preg_match('/Flutter\/([0-9\.]+)/i', $userAgent, $matches);
            $appVersion = $matches[1] ?? 'Flutter App';
        }
        elseif (strpos($userAgent, 'ReactNative') !== false) {
            preg_match('/ReactNative\/([0-9\.]+)/i', $userAgent, $matches);
            $appVersion = $matches[1] ?? 'React Native App';
        }
        else {
            $patterns = [
                '/version[\/\s]([0-9]+\.[0-9]+\.[0-9]+)/i',
                '/v([0-9]+\.[0-9]+\.[0-9]+)/i',
                '/([0-9]+\.[0-9]+\.[0-9]+)/',
                '/app[\/\s]([0-9]+\.[0-9]+\.[0-9]+)/i',
                '/client[\/\s]([0-9]+\.[0-9]+\.[0-9]+)/i'
            ];
            
            foreach ($patterns as $pattern) {
                if (preg_match($pattern, $userAgent, $matches)) {
                    $appVersion = $matches[1];
                    break;
                }
            }
        }
        
        return [
            'device_type' => $deviceType,
            'device_os' => $deviceOs,
            'app_version' => $appVersion
        ];
    }
}

if (!function_exists('formatWaitingTime')) {
    function formatWaitingTime(int $minutes): string
    {
        $hours = floor($minutes / 60);
        $mins = $minutes % 60;
        
        if ($hours > 0 && $mins > 0) {
            return "{$hours} hours {$mins} minutes";
        } elseif ($hours > 0) {
            return "{$hours} hours";
        } else {
            return "{$mins} minutes";
        }
    }
}

if (!function_exists('isInWaitingPeriod')) {
    function isInWaitingPeriod(?string $waitingTime): bool
    {
        if (!$waitingTime) {
            return false;
        }
        return Carbon::parse($waitingTime)->isFuture();
    }
}

if (!function_exists('getRemainingWaitingMinutes')) {
    function getRemainingWaitingMinutes(?string $waitingTime): int
    {
        if (!$waitingTime || !isInWaitingPeriod($waitingTime)) {
            return 0;
        }
        return now('Asia/Kolkata')->diffInMinutes(Carbon::parse($waitingTime), false);
    }
}