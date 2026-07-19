<?php

if (!defined('ABSPATH')) {
    exit;
}

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

add_shortcode('mcc_latest_results', 'mcc_latest_results_shortcode');

function mcc_latest_results_shortcode()
{
    wp_enqueue_style('mcc-results-public');

    ob_start();

    include MCC_RESULTS_PATH . 'public/latest-results.php';

    return ob_get_clean();
}

add_shortcode('mcc_event', 'mcc_event_shortcode');

function mcc_event_shortcode()
{
    wp_enqueue_style('mcc-results-public');

    ob_start();

    include MCC_RESULTS_PATH . 'public/event.php';

    return ob_get_clean();
}

add_shortcode('mcc_rider', 'mcc_rider_shortcode');

function mcc_rider_shortcode()
{
    ob_start();

    include MCC_RESULTS_PATH . 'public/rider.php';

    return ob_get_clean();
}