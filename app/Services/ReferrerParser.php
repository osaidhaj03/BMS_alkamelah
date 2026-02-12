<?php

namespace App\Services;

class ReferrerParser
{
    protected static array $sources = [
        'google'    => ['google.com', 'google.co', 'googleapis.com'],
        'bing'      => ['bing.com'],
        'yahoo'     => ['yahoo.com'],
        'facebook'  => ['facebook.com', 'fb.com', 'fbcdn.net'],
        'twitter'   => ['twitter.com', 't.co', 'x.com'],
        'youtube'   => ['youtube.com', 'youtu.be'],
        'linkedin'  => ['linkedin.com'],
        'whatsapp'  => ['whatsapp.com', 'wa.me'],
        'telegram'  => ['telegram.org', 't.me'],
    ];

    public static function parse(?string $referer, ?string $siteHost = null): string
    {
        if (empty($referer)) return 'direct';

        $host = strtolower(parse_url($referer, PHP_URL_HOST) ?? '');

        // رابط داخلي
        if ($siteHost && str_contains($host, strtolower($siteHost))) {
            return 'internal';
        }

        foreach (static::$sources as $name => $domains) {
            foreach ($domains as $domain) {
                if (str_contains($host, $domain)) return $name;
            }
        }

        return 'other';
    }
}
