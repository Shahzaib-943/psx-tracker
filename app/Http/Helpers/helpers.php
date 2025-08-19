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