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
     */
    function getPsxApiHeaders(): array
    {
        return [
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            'Accept' => 'application/json, text/plain, */*',
            'Accept-Language' => 'en-US,en;q=0.9',
            'Accept-Encoding' => 'gzip, deflate, br',
            'Referer' => 'https://dps.psx.com.pk/',
            'Origin' => 'https://dps.psx.com.pk',
        ];
    }
}