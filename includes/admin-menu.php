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

    add_submenu_page(
        null,
        'Edit Rider',
        'Edit Rider',
        'manage_options',
        'mcc-edit-rider',
        'mcc_edit_rider_page'
    );

    add_submenu_page(
        null,
        'View Rider',
        'View Rider',
        'manage_options',
        'mcc-view-rider',
        'mcc_view_rider_page'
    );

    // Courses
    add_submenu_page(
        'mcc-results',
        'Courses',
        'Courses',
        'manage_options',
        'mcc-courses',
        'mcc_courses_page'
    );

    add_submenu_page(
        null,
        'Add Course',
        'Add Course',
        'manage_options',
        'mcc-add-course',
        'mcc_add_course_page'
    );

    add_submenu_page(
        null,
        'Edit Course',
        'Edit Course',
        'manage_options',
        'mcc-edit-course',
        'mcc_edit_course_page'
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

    add_submenu_page(
        null,
        'Add Event',
        'Add Event',
        'manage_options',
        'mcc-add-event',
        'mcc_add_event_page'
    );

    add_submenu_page(
        null,
        'Edit Event',
        'Edit Event',
        'manage_options',
        'mcc-edit-event',
        'mcc_edit_event_page'
    );

    add_submenu_page(
        null,
        'View Event',
        'View Event',
        'manage_options',
        'mcc-view-event',
        'mcc_view_event_page'
    );

    // Results
    // add_submenu_page(
    //     'mcc-results',
    //     'Results',
    //     'Results',
    //     'manage_options',
    //     'mcc-results-list',
    //     'mcc_results_page'
    // );

    add_submenu_page(
        null,
        'Add Result',
        'Add Result',
        'manage_options',
        'mcc-add-result',
        'mcc_add_result_page'
    );

    add_submenu_page(
        null,
        'Edit Result',
        'Edit Result',
        'manage_options',
        'mcc-edit-result',
        'mcc_edit_result_page'
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

function mcc_add_rider_page()
{
    require MCC_RESULTS_PATH . 'admin/add-rider.php';
}

function mcc_edit_rider_page()
{
    require MCC_RESULTS_PATH . 'admin/edit-rider.php';
}

function mcc_view_rider_page()
{
    require MCC_RESULTS_PATH . 'admin/view-rider.php';
}

function mcc_courses_page()
{
    require MCC_RESULTS_PATH . 'admin/courses.php';
}

function mcc_add_course_page()
{
    require MCC_RESULTS_PATH . 'admin/add-course.php';
}

function mcc_edit_course_page()
{
    require MCC_RESULTS_PATH . 'admin/edit-course.php';
}

function mcc_events_page()
{
    require MCC_RESULTS_PATH . 'admin/events.php';
}

function mcc_add_event_page()
{
    require MCC_RESULTS_PATH . 'admin/add-event.php';
}

function mcc_edit_event_page()
{
    require MCC_RESULTS_PATH . 'admin/edit-event.php';
}

function mcc_view_event_page()
{
    require MCC_RESULTS_PATH . 'admin/view-event.php';
}

function mcc_results_page()
{
    require MCC_RESULTS_PATH . 'admin/results.php';
}

function mcc_add_result_page()
{
    require MCC_RESULTS_PATH . 'admin/enter-results.php';
}

function mcc_edit_result_page()
{
    require MCC_RESULTS_PATH . 'admin/edit-result.php';
}

function mcc_settings_page()
{
    require MCC_RESULTS_PATH . 'admin/settings.php';
}

add_action('admin_enqueue_scripts', 'mcc_admin_scripts');

function mcc_admin_scripts($hook)
{
    $allowedHooks = [
        'admin_page_mcc-add-result',
        'admin_page_mcc-edit-result'
    ];

    if (!in_array($hook, $allowedHooks, true)) {
        return;
    }

    wp_enqueue_script(
        'mcc-results-admin',
        MCC_RESULTS_URL . 'assets/js/resolve-bib.js',
        [],
        '1.0.0',
        true
    );

    wp_localize_script(
        'mcc-results-admin',
        'mccResults',
        [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce'   => wp_create_nonce('mcc_lookup_rider')
        ]
    );
}

add_action('wp_ajax_mcc_lookup_rider', 'mcc_lookup_rider');

function mcc_lookup_rider()
{
    check_ajax_referer('mcc_lookup_rider', 'nonce');

    $bib = intval($_POST['bib']);

    $rider = mcc_get_rider_by_bib_number($bib);

    if (!$rider) {
        wp_send_json_error();
    }

    wp_send_json_success([
        'name' => $rider->first_name . ' ' . $rider->last_name
    ]);
}

add_action('admin_enqueue_scripts', 'mcc_dashboard_assets');

function mcc_dashboard_assets($hook)
{
    // Only load on the MCC Dashboard
    if ($hook !== 'toplevel_page_mcc-results') {
        return;
    }

    wp_enqueue_style(
        'mcc-dashboard',
        MCC_RESULTS_URL . 'assets/css/dashboard.css',
        [],
        '1.0.0'
    );

    wp_enqueue_script(
        'mcc-dashboard',
        MCC_RESULTS_URL . 'assets/js/dashboard.js',
        [],
        '1.0.0',
        true
    );
}