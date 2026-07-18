<?php

if (!defined('ABSPATH')) {
    exit;
}

function mcc_calculate_average_speed($distance, $finishTime)
{
    if (empty($finishTime)) {
        return '-';
    }

    list($hours, $minutes, $seconds) = array_map('intval', explode(':', $finishTime));

    $totalHours = ($hours * 3600 + $minutes * 60 + $seconds) / 3600;

    if ($totalHours <= 0) {
        return '-';
    }

    return round($distance / $totalHours, 2);
}