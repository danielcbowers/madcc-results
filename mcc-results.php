<?php
/**
 * Plugin Name: MCC Results
 * Description: Time Trial Results Management for Maldon Cycle Club
 * Version: 1.0.3
 * Author: Daniel Bowers
 */

if (!defined('ABSPATH')) {
    exit;
}

define('MCC_RESULTS_VERSION', '1.0.3');
define('MCC_RESULTS_PATH', plugin_dir_path(__FILE__));
define('MCC_RESULTS_URL', plugin_dir_url(__FILE__));

/**
 * Load the admin plugin assets and pages.
 */
require_once MCC_RESULTS_PATH . 'includes/admin-menu.php';
require_once MCC_RESULTS_PATH . 'includes/enqueue.php';
require_once MCC_RESULTS_PATH . 'includes/database.php';
require_once MCC_RESULTS_PATH . 'includes/helpers.php';
register_activation_hook(__FILE__, 'mcc_results_install');
require_once MCC_RESULTS_PATH . 'includes/Services/RiderService.php';
require_once MCC_RESULTS_PATH . 'includes/Services/EventService.php';
require_once MCC_RESULTS_PATH . 'includes/Services/CourseService.php';
require_once MCC_RESULTS_PATH . 'includes/Services/ResultsService.php';
require_once MCC_RESULTS_PATH . 'includes/Services/SpondService.php';
require_once MCC_RESULTS_PATH . 'includes/Helpers/TimeHelper.php';
require_once MCC_RESULTS_PATH . 'includes/Helpers/ResultsHelper.php';
require_once MCC_RESULTS_PATH . 'includes/Helpers/GeneralHelper.php';

/**
 * Load the public plugin assets and shortcodes.
 */
require_once MCC_RESULTS_PATH . 'public/shortcodes.php';

/**
 * Schedule the Spond sync event on plugin activation.
 */
//register_activation_hook(__FILE__, 'mcc_activate');
//register_deactivation_hook(__FILE__, 'mcc_deactivate');
function mcc_activate()
{
    if (!wp_next_scheduled('mcc_spond_sync')) {
        wp_schedule_event(time(), 'hourly', 'mcc_spond_sync');
    }
}
function mcc_deactivate()
{
    wp_clear_scheduled_hook('mcc_spond_sync');
}
//add_action('mcc_spond_sync', 'mcc_run_spond_sync');
function mcc_run_spond_sync()
{
    error_log('Starting Spond sync');

    $count = mcc_sync_spond_events();

    error_log("Spond sync completed. {$count} events processed.");
}
