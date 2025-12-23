<?php

use App\Traits\HasPublicId;

function formatNumber($number)
{
    if ($number >= 1000000000) {
        return round($number / 1000000000, 2) . 'B';
    } elseif ($number >= 1000000) {
        return round($number / 1000000, 2) . 'M';
    } elseif ($number >= 1000) {
        return round($number / 1000, 2) . 'K';
    }
    return $number;
}

if (!function_exists('getProfitLossClass')) {
    function getProfitLossClass($value)
    {
        if ($value > 0) {
            return 'mdi mdi-trending-up primary-green';
        } elseif ($value < 0) {
            return 'mdi mdi-trending-down primary-red';
        } else {
            return 'mdi mdi-trending-flat primary-gray';
        }
    }
}
if (!function_exists('formatPercentageClass')) {
    function formatPercentageClass($value)
    {
        if ($value > 0) {
            return 'badge bg-success';
        } elseif ($value < 0) {
            return 'badge bg-danger';
        } else {
            return 'badge bg-secondary';
        }
    }
}

if(!function_exists('generatePublicId')) {
    function generatePublicId($model) {
        
        return HasPublicId::generateUniquePublicId($model);
    }
}

if (!function_exists('getPsxApiHeaders')) {
    /**
     * Get browser-like headers for PSX API requests to avoid blocking
     * Matches actual browser headers exactly
     */
    function getPsxApiHeaders(): array
    {
        return [
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36',
            'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8',
            'Accept-Language' => 'en-US,en;q=0.9',
            'Accept-Encoding' => 'gzip, deflate, br, zstd',
            'Connection' => 'keep-alive',
            'Host' => 'dps.psx.com.pk',
            'sec-ch-ua' => '"Brave";v="143", "Chromium";v="143", "Not A(Brand";v="24"',
            'sec-ch-ua-mobile' => '?0',
            'sec-ch-ua-platform' => '"Windows"',
            'sec-fetch-dest' => 'document',
            'sec-fetch-mode' => 'navigate',
            'sec-fetch-site' => 'none',
            'sec-fetch-user' => '?1',
            'sec-gpc' => '1',
            'upgrade-insecure-requests' => '1',
            'Referer' => 'https://dps.psx.com.pk/',
            'Origin' => 'https://dps.psx.com.pk',
        ];
    }
}