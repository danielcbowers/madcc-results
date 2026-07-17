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

    add_submenu_page(
        null,
        'Delete Course',
        'Delete Course',
        'manage_options',
        'mcc-delete-course',
        'mcc_delete_course_page'
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
        'Delete Event',
        'Delete Event',
        'manage_options',
        'mcc-delete-event',
        'mcc_delete_event_page'
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

function mcc_add_rider_page()
{
    require MCC_RESULTS_PATH . 'admin/add-rider.php';
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

function mcc_delete_course_page()
{
    require MCC_RESULTS_PATH . 'admin/delete-course.php';
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

function mcc_delete_event_page()
{
    require MCC_RESULTS_PATH . 'admin/delete-event.php';
}

function mcc_results_page()
{
    require MCC_RESULTS_PATH . 'admin/results.php';
}

function mcc_settings_page()
{
    echo '<div class="wrap"><h1>Settings</h1></div>';
}


