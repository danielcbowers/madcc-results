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

    $charset_collate = $wpdb->get_charset_collate();

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';

    /*
     * Riders table
     */
    $riders_table = $wpdb->prefix . 'mcc_riders';

    $sql = "CREATE TABLE $riders_table (

        id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,

        bib_number INT NOT NULL,

        first_name VARCHAR(100) NOT NULL,

        last_name VARCHAR(100) NOT NULL,

        email VARCHAR(255) NULL,

        club VARCHAR(100) DEFAULT 'Maldon CC',

        category VARCHAR(50) NULL,

        active TINYINT(1) NOT NULL DEFAULT 1,

        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

        PRIMARY KEY (id)

    ) $charset_collate;";

    dbDelta($sql);

    /*
     * Events table
     */
    $events_table = $wpdb->prefix . 'mcc_events';

    $sql = "CREATE TABLE $events_table (

        id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,

        event_name VARCHAR(255) NOT NULL,

        event_date DATE NOT NULL,

        course_id BIGINT UNSIGNED NULL,

        event_type VARCHAR(50) NOT NULL,

        status VARCHAR(20) NOT NULL DEFAULT 'Planned',

        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

        PRIMARY KEY (id)

    ) $charset_collate;";

    dbDelta($sql);

    /*
     * Course table
     */
    $courses_table = $wpdb->prefix . 'mcc_courses';

    $sql = "CREATE TABLE $courses_table (

        id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,

        course_code VARCHAR(50) NOT NULL,

        course_name VARCHAR(255) NOT NULL,

        distance DECIMAL(5,2) NULL,

        active TINYINT(1) NOT NULL DEFAULT 1,

        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

        PRIMARY KEY (id)

    ) $charset_collate;";

    dbDelta($sql);

    /*
    * Results table
    */
    $results_table = $wpdb->prefix . 'mcc_results';

    $sql = "CREATE TABLE $results_table (

        id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,

        event_id BIGINT UNSIGNED NOT NULL,

        rider_id BIGINT UNSIGNED NOT NULL,

        finish_time TIME NULL,

        status VARCHAR(20) NOT NULL DEFAULT 'Finished',

        comments TEXT NULL,

        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

        PRIMARY KEY (id),

        KEY event_id (event_id),
        KEY rider_id (rider_id)

    ) $charset_collate;";

    dbDelta($sql);
}