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
function mcc_add_rider($bibNumber, $firstName, $lastName, $email, $club, $category, $active = true)
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
            'category'   => $category,
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
function mcc_bib_number_exists($bibNumber, $excludeId = 0)
{
    global $wpdb;

    $table = $wpdb->prefix . 'mcc_riders';

    return (bool) $wpdb->get_var(
        $wpdb->prepare(
            "SELECT COUNT(*)
             FROM {$table}
             WHERE bib_number = %d
             AND id != %d",
            $bibNumber,
            $excludeId
        )
    );
}

/**
 * Get a single rider
 */
function mcc_get_rider($id)
{
    global $wpdb;

    $table = $wpdb->prefix . 'mcc_riders';

    return $wpdb->get_row(
        $wpdb->prepare(
            "SELECT * FROM {$table} WHERE id = %d",
            $id
        )
    );
}

/**
 * Get a rider by bib number
 */
function mcc_get_rider_by_bib_number($bibNumber)
{
    global $wpdb;

    $table = $wpdb->prefix . 'mcc_riders';

    return $wpdb->get_row(
        $wpdb->prepare(
            "SELECT * FROM {$table}
             WHERE bib_number = %d",
            $bibNumber
        )
    );
}

/**
 * Update a rider
 */
function mcc_update_rider(
    $id,
    $bibNumber,
    $firstName,
    $lastName,
    $email,
    $club,
    $category,
    $active = true
)
{
    global $wpdb;

    $table = $wpdb->prefix . 'mcc_riders';

    return $wpdb->update(
        $table,
        [
            'bib_number' => $bibNumber,
            'first_name' => $firstName,
            'last_name'  => $lastName,
            'email'      => $email,
            'club'       => $club,
            'category'   => $category,
            'active'     => $active ? 1 : 0
        ],
        [
            'id' => $id
        ],
        [
            '%d',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%d'
        ],
        [
            '%d'
        ]
    );
}

/**
 * Delete a rider
 */
function mcc_delete_rider($id)
{
    global $wpdb;

    $table = $wpdb->prefix . 'mcc_riders';

    return $wpdb->delete(
        $table,
        [
            'id' => $id
        ],
        [
            '%d'
        ]
    );
}

/**
 * Get rider statistics
 */
function mcc_get_rider_statistics($riderId)
{
    global $wpdb;

    $resultsTable = $wpdb->prefix . 'mcc_results';

    $results = $wpdb->get_results(
        $wpdb->prepare(
            "
            SELECT *
            FROM {$resultsTable}
            WHERE rider_id = %d
            AND status = 'Finished'
            ORDER BY finish_time ASC
            ",
            $riderId
        )
    );

    $stats = [
        'events' => count($results),
        'wins' => 0,
        'podiums' => 0,
        'best_time' => null
    ];

    if (!empty($results)) {
        $stats['best_time'] = $results[0]->finish_time;
    }

    return $stats;
}

/**
 * Get recent results for a rider
 */
function mcc_get_rider_results($riderId)
{
    global $wpdb;

    return $wpdb->get_results(
        $wpdb->prepare(
            "
            SELECT
                r.*,
                e.event_name,
                e.event_date,
                c.distance
            FROM {$wpdb->prefix}mcc_results r
            INNER JOIN {$wpdb->prefix}mcc_events e
                ON r.event_id = e.id
            INNER JOIN {$wpdb->prefix}mcc_courses c
                ON e.course_id = c.id
            WHERE r.rider_id = %d
            ORDER BY e.event_date DESC
            ",
            $riderId
        )
    );
}