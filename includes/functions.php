<?php

if (!defined('ABSPATH')) {
    exit;
}

add_action('admin_menu', 'mcc_results_admin_menu');

function mcc_results_admin_menu()
{
    add_menu_page(
        'MCC Results',
        'MCC Results',
        'manage_options',
        'mcc-results',
        'mcc_results_dashboard',
        'dashicons-awards',
        30
    );
}

function mcc_results_dashboard()
{
    require_once MCC_RESULTS_PATH . 'admin/dashboard.php';
}