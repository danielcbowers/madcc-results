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

    $table_name = $wpdb->prefix . 'mcc_riders';

    $charset_collate = $wpdb->get_charset_collate();

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';

    $sql = "CREATE TABLE $table_name (

        id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,

        first_name VARCHAR(100) NOT NULL,

        last_name VARCHAR(100) NOT NULL,

        email VARCHAR(255) NULL,

        active TINYINT(1) NOT NULL DEFAULT 1,

        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

        PRIMARY KEY (id)

    ) $charset_collate;";

    dbDelta($sql);
}