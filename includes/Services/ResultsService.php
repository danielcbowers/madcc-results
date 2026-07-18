<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Results Service
 *
 * Handles all result database operations.
 */

/**
 * Get all results for an event
 */
function mcc_get_results_by_event($eventId)
{
    global $wpdb;

    return $wpdb->get_results(
        $wpdb->prepare(
            "
            SELECT
                r.*,
                rd.first_name,
                rd.last_name,
                rd.bib_number
            FROM {$wpdb->prefix}mcc_results r
            INNER JOIN {$wpdb->prefix}mcc_riders rd
                ON r.rider_id = rd.id
            WHERE r.event_id = %d
            ORDER BY
                CASE
                    WHEN r.status = 'Finished' THEN 0
                    ELSE 1
                END,
                r.finish_time ASC
            ",
            $eventId
        )
    );
}

/**
 * Get a single result
 */
function mcc_get_result($id)
{
    global $wpdb;

    $table = $wpdb->prefix . 'mcc_results';

    return $wpdb->get_row(
        $wpdb->prepare(
            "SELECT * FROM {$table}
             WHERE id = %d",
            $id
        )
    );
}

/**
 * Add a result
 */
function mcc_add_result($eventId, $riderId, $finishTime, $status, $comments)
{
    global $wpdb;

    $table = $wpdb->prefix . 'mcc_results';

    return $wpdb->insert(
        $table,
        [
            'event_id'    => $eventId,
            'rider_id'    => $riderId,
            'finish_time' => $finishTime,
            'status'      => $status,
            'comments'    => $comments
        ],
        [
            '%d',
            '%d',
            '%s',
            '%s',
            '%s'
        ]
    );
}

/**
 * Update a result
 */
function mcc_update_result(
    $id,
    $riderId,
    $finishTime,
    $status,
    $comments
) {
    global $wpdb;

    return $wpdb->update(
        $wpdb->prefix . 'mcc_results',
        [
            'rider_id'    => $riderId,
            'finish_time' => $finishTime,
            'status'      => $status,
            'comments'    => $comments
        ],
        [
            'id' => $id
        ]
    );
}

/**
 * Delete a result
 */
function mcc_delete_result($id)
{
    global $wpdb;

    $table = $wpdb->prefix . 'mcc_results';

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
 * Get event statistics
 */
function mcc_get_event_statistics($eventId)
{
    $results = mcc_get_results_by_event($eventId);

    $stats = [
        'total'      => 0,
        'finished'   => 0,
        'dnf'        => 0,
        'dns'        => 0,
        'dsq'        => 0,
        'fastest'    => null
    ];

    foreach ($results as $result) {

        $stats['total']++;

        switch ($result->status) {

            case 'Finished':
                $stats['finished']++;

                if (
                    $stats['fastest'] === null ||
                    $result->finish_time < $stats['fastest']
                ) {
                    $stats['fastest'] = $result->finish_time;
                }

                break;

            case 'DNF':
                $stats['dnf']++;
                break;

            case 'DNS':
                $stats['dns']++;
                break;

            case 'DSQ':
                $stats['dsq']++;
                break;
        }
    }

    return $stats;
}