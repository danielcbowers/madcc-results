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

    $results = $wpdb->get_results(
        $wpdb->prepare(
            "
            SELECT
                r.*,
                rd.first_name,
                rd.last_name
            FROM {$wpdb->prefix}mcc_results r
            INNER JOIN {$wpdb->prefix}mcc_riders rd
                ON r.rider_id = rd.id
            WHERE r.event_id = %d
            ",
            $eventId
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

    usort($results, function ($a, $b) {

        // Finished riders always come first
        if ($a->status === 'Finished' && $b->status !== 'Finished') {
            return -1;
        }

        if ($a->status !== 'Finished' && $b->status === 'Finished') {
            return 1;
        }

        // Non-finished riders retain their order
        if ($a->status !== 'Finished' && $b->status !== 'Finished') {
            return 0;
        }

        // Finished riders are sorted by actual time
        return $a->actual_seconds <=> $b->actual_seconds;
    });

    return $results;
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
function mcc_add_result($eventId, $riderId, $bibNumber, $bikeType, $finishTime, $status, $comments)
{
    global $wpdb;

    $table = $wpdb->prefix . 'mcc_results';

    return $wpdb->insert(
        $table,
        [
            'event_id'    => $eventId,
            'rider_id'    => $riderId,
            'bib_number'  => $bibNumber,
            'bike_type'   => $bikeType,
            'finish_time' => $finishTime,
            'status'      => $status,
            'comments'    => $comments
        ],
        [
            '%d',
            '%d',
            '%d',
            '%s',
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
    $bibNumber,
    $bikeType,
    $finishTime,
    $status,
    $comments
) {
    global $wpdb;

    return $wpdb->update(
        $wpdb->prefix . 'mcc_results',
        [
            'rider_id'    => $riderId,
            'bib_number'  => $bibNumber,
            'bike_type'   => $bikeType,
            'finish_time' => $finishTime,
            'status'      => $status,
            'comments'    => $comments
        ],
        [
            'id' => $id
        ],
        [
            '%d',
            '%d',
            '%s',
            '%s',
            '%s',
            '%s'
        ],
        [
            '%d'
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

/**
 * Get the position of a rider in an event
 */
function mcc_get_position_for_rider($eventId, $riderId)
{
    $results = mcc_get_results_by_event($eventId);

    $position = 1;

    foreach ($results as $result) {

        if ($result->status !== 'Finished') {
            continue;
        }

        if ($result->rider_id == $riderId) {
            return $position;
        }

        $position++;
    }

    return null;
}

function mcc_calculate_actual_seconds($finishTime, $bibNumber)
{
    return strtotime('1970-01-01 ' . $finishTime)
        - ((int) $bibNumber * 60);
}

function mcc_format_actual_time($seconds)
{
    return gmdate('H:i:s', $seconds);
}