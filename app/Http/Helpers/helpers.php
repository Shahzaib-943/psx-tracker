<?php

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
        return $value >= 0 ? 'mdi mdi-trending-up primary-green' : 'mdi mdi-trending-down primary-red';
    }
}