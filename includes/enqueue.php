<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enqueue public plugin assets.
 */
function mcc_enqueue_public_assets()
{
    wp_enqueue_style(
        'mcc-results-public',
        MCC_RESULTS_URL . 'assets/css/public.css',
        [],
        MCC_RESULTS_VERSION
    );
}

add_action('wp_enqueue_scripts', 'mcc_enqueue_public_assets');

add_action('admin_enqueue_scripts', 'mcc_admin_styles');

function mcc_admin_styles()
{
    wp_enqueue_style(
        'mcc-admin',
        MCC_RESULTS_URL . 'assets/css/admin.css',
        [],
        MCC_RESULTS_VERSION
    );
}

function mcc_enqueue_public_calendar_assets()
{
    wp_enqueue_style(
        'fullcalendar',
        'https://cdn.jsdelivr.net/npm/fullcalendar@6.1.19/index.global.min.css'
    );

    wp_enqueue_script(
        'fullcalendar',
        'https://cdn.jsdelivr.net/npm/fullcalendar@6.1.19/index.global.min.js',
        [],
        null,
        true
    );

    wp_enqueue_style(
        'mcc-calendar',
        MCC_RESULTS_URL . 'assets/css/calendar.css'
    );

    wp_enqueue_script(
        'mcc-calendar',
        MCC_RESULTS_URL . 'assets/js/calendar.js',
        ['fullcalendar'],
        MCC_RESULTS_VERSION,
        true
    );
}