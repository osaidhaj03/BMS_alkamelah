<?php

namespace App\Services;

class UserAgentParser
{
    /**
     * تحليل User Agent وإرجاع: device_type, browser, os
     */
    public static function parse(?string $userAgent): array
    {
        $ua = strtolower($userAgent ?? '');

        return [
            'device_type' => static::detectDevice($ua),
            'browser'     => static::detectBrowser($userAgent ?? ''),
            'os'          => static::detectOS($userAgent ?? ''),
        ];
    }

    protected static function detectDevice(string $ua): string
    {
        if (str_contains($ua, 'ipad') || str_contains($ua, 'tablet')
            || (str_contains($ua, 'android') && !str_contains($ua, 'mobile'))) {
            return 'tablet';
        }
        if (str_contains($ua, 'mobile') || str_contains($ua, 'iphone')
            || str_contains($ua, 'ipod') || str_contains($ua, 'android')) {
            return 'mobile';
        }
        return 'desktop';
    }

    protected static function detectBrowser(string $ua): string
    {
        $patterns = [
            '/Edg[e\/]?([\d.]+)/'    => 'Edge',
            '/OPR\/([\d.]+)/'        => 'Opera',
            '/Chrome\/([\d.]+)/'      => 'Chrome',
            '/Firefox\/([\d.]+)/'     => 'Firefox',
            '/Safari\/([\d.]+)/'      => 'Safari',
            '/MSIE ([\d.]+)/'         => 'IE',
            '/Trident.*rv:([\d.]+)/'  => 'IE',
        ];

        foreach ($patterns as $pattern => $name) {
            if (preg_match($pattern, $ua, $matches)) {
                $version = explode('.', $matches[1])[0]; // major version فقط
                return "$name $version";
            }
        }
        return 'Other';
    }

    protected static function detectOS(string $ua): string
    {
        $patterns = [
            '/Windows NT 10/'   => 'Windows 10/11',
            '/Windows NT 6.3/'  => 'Windows 8.1',
            '/Windows NT 6.1/'  => 'Windows 7',
            '/Mac OS X ([\d_]+)/' => 'macOS',
            '/Android ([\d.]+)/'  => 'Android',
            '/iPhone OS ([\d_]+)/' => 'iOS',
            '/iPad.*OS ([\d_]+)/' => 'iPadOS',
            '/Linux/'             => 'Linux',
        ];

        foreach ($patterns as $pattern => $name) {
            if (preg_match($pattern, $ua, $matches)) {
                if (isset($matches[1]) && in_array($name, ['Android', 'iOS', 'iPadOS'])) {
                    $ver = str_replace('_', '.', explode('.', $matches[1])[0]);
                    return "$name $ver";
                }
                return $name;
            }
        }
        return 'Other';
    }
}
