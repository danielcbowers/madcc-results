<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Create plugin database tables
 */
function mcc_results_install()
{
    global $wpdb;

    ob_start();

    $charset_collate = $wpdb->get_charset_collate();

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';

    /*
     * Riders table
     */
    $riders_table = $wpdb->prefix . 'mcc_riders';

    $sql = "CREATE TABLE $riders_table (

        id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,

        spond_member_id VARCHAR(32) NULL,

        first_name VARCHAR(100) NOT NULL,

        last_name VARCHAR(100) NOT NULL,

        email VARCHAR(255) NULL,

        club VARCHAR(100) DEFAULT 'Maldon CC',

        category VARCHAR(50) NULL,

        active TINYINT(1) NOT NULL DEFAULT 1,

        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

        PRIMARY KEY (id)

    ) $charset_collate;";

    $wpdb->query($sql);

    /*
     * Events table
     */
    $events_table = $wpdb->prefix . 'mcc_events';

    $sql = "CREATE TABLE $events_table (

        id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,

        spond_event_id VARCHAR(32) DEFAULT NULL,

        event_name VARCHAR(255) NOT NULL,

        description TEXT NULL,

        event_date DATE NOT NULL,

        start_time TIME NULL,

        end_time TIME NULL,

        location VARCHAR(255) NULL,

        course_id BIGINT UNSIGNED NULL,

        event_type VARCHAR(50) NOT NULL,

        status VARCHAR(20) NOT NULL DEFAULT 'Planned',

        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

        updated_at DATETIME NULL,

        accepted_count INT NOT NULL DEFAULT 0,

        accepted_riders LONGTEXT NULL,

        PRIMARY KEY (id),

        KEY spond_event_id (spond_event_id)

    ) $charset_collate;";

    $wpdb->query($sql);

    /*
    * Course table
    */
    $courses_table = $wpdb->prefix . 'mcc_courses';

    $sql = "CREATE TABLE $courses_table (

        id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,

        course_code VARCHAR(50) NOT NULL,

        course_name VARCHAR(255) NOT NULL,

        course_type VARCHAR(50) NOT NULL DEFAULT 'TT',

        distance_miles DECIMAL(5,2) NULL,

        description TEXT NULL,

        start_location VARCHAR(255) NULL,
        start_latitude DECIMAL(10,8) NULL,
        start_longitude DECIMAL(11,8) NULL,

        finish_location VARCHAR(255) NULL,
        finish_latitude DECIMAL(10,8) NULL,
        finish_longitude DECIMAL(11,8) NULL,

        route_geojson LONGTEXT NULL,

        notes TEXT NULL,

        active TINYINT(1) NOT NULL DEFAULT 1,

        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

        PRIMARY KEY (id)

    ) $charset_collate;";

    $wpdb->query($sql);

    /*
    * Results table
    */
    $results_table = $wpdb->prefix . 'mcc_results';

    $sql = "CREATE TABLE $results_table (

        id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,

        event_id BIGINT UNSIGNED NOT NULL,

        rider_id BIGINT UNSIGNED NOT NULL,

        bib_number INT UNSIGNED NOT NULL,

        finish_time TIME NULL,

        bike_type VARCHAR(20) NOT NULL DEFAULT 'Time Trial',

        status VARCHAR(20) NOT NULL DEFAULT 'Finished',

        comments TEXT NULL,

        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

        PRIMARY KEY (id),

        KEY idx_event_id (event_id),
        KEY idx_rider_id (rider_id),
        KEY idx_bib_number (bib_number)

    ) $charset_collate;";

    $wpdb->query($sql);

    $output = ob_get_clean();

    if (!empty($output)) {
        error_log("Plugin activation output:");
        error_log($output);
    }
}