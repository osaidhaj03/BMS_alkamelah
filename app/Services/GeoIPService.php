<?php

namespace App\Services;

use GeoIp2\Database\Reader;

class GeoIPService
{
    protected ?Reader $reader = null;

    public function __construct()
    {
        $dbPath = storage_path('app/geoip/GeoLite2-City.mmdb');
        if (file_exists($dbPath)) {
            $this->reader = new Reader($dbPath);
        }
    }

    /**
     * البحث عن موقع IP
     * @return array{country: ?string, city: ?string, country_code: ?string}
     */
    public function lookup(string $ip): array
    {
        if (!$this->reader || $this->isPrivateIP($ip)) {
            return ['country' => null, 'city' => null, 'country_code' => null];
        }

        try {
            $record = $this->reader->city($ip);
            return [
                'country'      => $record->country->name ?? null,
                'city'         => $record->city->name ?? null,
                'country_code' => $record->country->isoCode ?? null,
            ];
        } catch (\Exception $e) {
            return ['country' => null, 'city' => null, 'country_code' => null];
        }
    }

    protected function isPrivateIP(string $ip): bool
    {
        return !filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE);
    }
}
