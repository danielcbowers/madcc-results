<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Rider Service
 *
 * Handles all rider database operations.
 */

/**
 * Get all riders
 */
function mcc_get_all_riders()
{
    global $wpdb;

    $table = $wpdb->prefix . 'mcc_riders';

    return $wpdb->get_results(
        "SELECT * FROM {$table} ORDER BY last_name, first_name"
    );
}

/**
 * Add a rider
 */
function mcc_add_rider($firstName, $lastName, $email, $active = true)
{
    global $wpdb;

    $table = $wpdb->prefix . 'mcc_riders';

    return $wpdb->insert(
        $table,
        [
            'first_name' => $firstName,
            'last_name'  => $lastName,
            'email'      => $email,
            'active'     => $active ? 1 : 0
        ],
        [
            '%s',
            '%s',
            '%s',
            '%d'
        ]
    );
}