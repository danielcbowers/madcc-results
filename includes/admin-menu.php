<?php

if (!defined('ABSPATH')) {
    exit;
}

function mcc_results_admin_menu()
{
    // Main menu
    add_menu_page(
        'MCC Results',
        'MCC Results',
        'manage_options',
        'mcc-results',
        'mcc_dashboard_page',
        'dashicons-awards',
        30
    );

    // Dashboard
    add_submenu_page(
        'mcc-results',
        'Dashboard',
        'Dashboard',
        'manage_options',
        'mcc-results',
        'mcc_dashboard_page'
    );

    // Riders
    add_submenu_page(
        'mcc-results',
        'Riders',
        'Riders',
        'manage_options',
        'mcc-riders',
        'mcc_riders_page'
    );

    add_submenu_page(
        null,
        'Add Rider',
        'Add Rider',
        'manage_options',
        'mcc-add-rider',
        'mcc_add_rider_page'
    );

    // Events
    add_submenu_page(
        'mcc-results',
        'Events',
        'Events',
        'manage_options',
        'mcc-events',
        'mcc_events_page'
    );

    // Results
    add_submenu_page(
        'mcc-results',
        'Results',
        'Results',
        'manage_options',
        'mcc-results-list',
        'mcc_results_page'
    );

    // Settings
    add_submenu_page(
        'mcc-results',
        'Settings',
        'Settings',
        'manage_options',
        'mcc-settings',
        'mcc_settings_page'
    );
}

add_action('admin_menu', 'mcc_results_admin_menu');

function mcc_dashboard_page()
{
    require MCC_RESULTS_PATH . 'admin/dashboard.php';
}

function mcc_riders_page()
{
    require MCC_RESULTS_PATH . 'admin/riders.php';
}

function mcc_events_page()
{
    require MCC_RESULTS_PATH . 'admin/events.php';
}

function mcc_results_page()
{
    require MCC_RESULTS_PATH . 'admin/results.php';
}

function mcc_settings_page()
{
    echo '<div class="wrap"><h1>Settings</h1></div>';
}

function mcc_add_rider_page()
{
    require MCC_RESULTS_PATH . 'admin/add-rider.php';
}