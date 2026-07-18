<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Calculate average speed in km/h
 */
function mcc_calculate_positions(&$results)
{
    usort($results, function ($a, $b) {

        if ($a->status !== 'Finished' && $b->status === 'Finished') {
            return 1;
        }

        if ($a->status === 'Finished' && $b->status !== 'Finished') {
            return -1;
        }

        return strcmp($a->finish_time, $b->finish_time);

    });

    $position = 1;

    foreach ($results as $result) {

        if ($result->status === 'Finished') {
            $result->position = $position++;
        } else {
            $result->position = '-';
        }

    }
}

/**
 * Get status badge HTML
 */
function mcc_get_status_badge($status)
{
    switch ($status) {

        case 'Finished':
            return '<span class="mcc-badge mcc-success">Finished</span>';

        case 'DNF':
            return '<span class="mcc-badge mcc-warning">DNF</span>';

        case 'DNS':
            return '<span class="mcc-badge mcc-info">DNS</span>';

        case 'DSQ':
            return '<span class="mcc-badge mcc-danger">DSQ</span>';

        default:
            return esc_html($status);
    }
}