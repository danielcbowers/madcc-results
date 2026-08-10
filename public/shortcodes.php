<?php

if (!defined('ABSPATH')) {
    exit;
}



/**
 * Render a public template.
 *
 * @param string $template Template filename located in /public.
 * @return string
 */
function mcc_render_public_template($template)
{
    wp_enqueue_style('mcc-results-public');

    ob_start();

    include MCC_RESULTS_PATH . "public/{$template}";

    return ob_get_clean();
}

/**
 * Latest Results
 */
add_shortcode('mcc_latest_results', function () {
    return mcc_render_public_template('latest-results.php');
});

/**
 * Upcoming Events
 */
add_shortcode('mcc_events', function () {
    return mcc_render_public_template('events.php');
});

/**
 * Event Details
 */
add_shortcode('mcc_event', function () {
    return mcc_render_public_template('event.php');
});

/**
 * Event Results
 */
add_shortcode('mcc_event_results', function () {
    return mcc_render_public_template('event-results.php');
});

/**
 * Rider Profile
 */
add_shortcode('mcc_rider', function () {
    return mcc_render_public_template('rider.php');
});
