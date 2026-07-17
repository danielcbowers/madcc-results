<?php
/**
 * Plugin Name: MCC Results
 * Description: Time Trial Results Management for Maldon Cycle Club
 * Version: 1.0.0
 * Author: Daniel Bowers
 */

if (!defined('ABSPATH')) {
    exit;
}

define('MCC_RESULTS_VERSION', '1.0.0');
define('MCC_RESULTS_PATH', plugin_dir_path(__FILE__));
define('MCC_RESULTS_URL', plugin_dir_url(__FILE__));

require_once MCC_RESULTS_PATH . 'includes/admin-menu.php';
require_once MCC_RESULTS_PATH . 'includes/enqueue.php';
require_once MCC_RESULTS_PATH . 'includes/database.php';
require_once MCC_RESULTS_PATH . 'includes/helpers.php';
register_activation_hook(__FILE__, 'mcc_results_install');
require_once MCC_RESULTS_PATH . 'includes/Services/RiderService.php';
require_once MCC_RESULTS_PATH . 'includes/Services/EventService.php';
require_once MCC_RESULTS_PATH . 'includes/Services/CourseService.php';