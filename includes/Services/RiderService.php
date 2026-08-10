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
        "SELECT * FROM {$table} ORDER BY first_name, last_name"
    );
}

/**
 * Add a rider
 */
function mcc_add_rider($firstName, $lastName, $email, $club, $category, $active = true)
{
    global $wpdb;

    $table = $wpdb->prefix . 'mcc_riders';

    return $wpdb->insert(
        $table,
        [
            'first_name' => $firstName,
            'last_name'  => $lastName,
            'email'      => $email,
            'club'       => $club,
            'category'   => $category,
            'active'     => $active ? 1 : 0
        ],
        [
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%d'
        ]
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
 * Update a rider
 */
function mcc_update_rider(
    $id,
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

    $results = $wpdb->get_results(
        $wpdb->prepare(
            "
            SELECT
                r.*,
                e.event_name,
                e.event_date,
                c.distance_miles AS distance
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

    foreach ($results as &$result) {

        if ($result->status === 'Finished') {

            $result->actual_seconds = mcc_calculate_actual_seconds(
                $result->finish_time,
                $result->bib_number
            );

            $result->actual_time = mcc_format_actual_time(
                $result->actual_seconds
            );

        } else {

            $result->actual_seconds = PHP_INT_MAX;
            $result->actual_time = '-';
        }
    }

    unset($result);

    return $results;
}

function mcc_sync_spond_rider(array $member)
{
    global $wpdb;

    $table = $wpdb->prefix . 'mcc_riders';

    // Already linked by Spond member ID
    $rider = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT *
            FROM {$table}
            WHERE spond_member_id = %s",
            $member['id']
        )
    );

    if ($rider) {

        $wpdb->update(
            $table,
            [
                'first_name'      => mcc_format_name($member['firstName']),
                'last_name'       => mcc_format_name($member['lastName']),
                'active'          => 1,
                'spond_member_id' => $member['id']
            ],
            [
                'id' => $rider->id
            ],
            [
                '%s',
                '%s',
                '%d',
                '%s'
            ],
            [
                '%d'
            ]
        );

        return $rider->id;
    }

    // Create rider
    $wpdb->insert(
        $table,
        [
            'first_name'      => mcc_format_name($member['firstName']),
            'last_name'       => mcc_format_name($member['lastName']),
            'club'            => 'Maldon CC',
            'active'          => 1,
            'spond_member_id' => $member['id']
        ],
        [
            '%s',
            '%s',
            '%s',
            '%d',
            '%s'
        ]
    );

    return $wpdb->insert_id;
}

function mcc_find_spond_member(array $members, string $memberId): ?array
{
    foreach ($members as $member) {
        if ($member['id'] === $memberId) {
            return $member;
        }
    }

    return null;
}

function mcc_get_rider_by_spond_member_id($spondMemberId)
{
    global $wpdb;

    $table = $wpdb->prefix . 'mcc_riders';

    return $wpdb->get_row(
        $wpdb->prepare(
            "SELECT * FROM {$table} WHERE spond_member_id = %s",
            $spondMemberId
        )
    );
}

