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
function mcc_add_rider($bibNumber, $firstName, $lastName, $email, $club, $active = true)
{
    global $wpdb;

    $table = $wpdb->prefix . 'mcc_riders';

    return $wpdb->insert(
        $table,
        [
            'bib_number' => $bibNumber,
            'first_name' => $firstName,
            'last_name'  => $lastName,
            'email'      => $email,
            'club'       => $club,
            'active'     => $active ? 1 : 0
        ],
        [
            '%d',
            '%s',
            '%s',
            '%s',
            '%s',
            '%d'
        ]
    );
}

/**
 * Check if a bib number already exists
 */
function mcc_bib_number_exists($bibNumber)
{
    global $wpdb;

    $table = $wpdb->prefix . 'mcc_riders';

    return (bool) $wpdb->get_var(
        $wpdb->prepare(
            "SELECT COUNT(*) FROM {$table} WHERE bib_number = %d",
            $bibNumber
        )
    );
}