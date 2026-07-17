<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Event Service
 *
 * Handles all event database operations.
 */

/**
 * Get all events
 */
function mcc_get_all_events()
{
    global $wpdb;

    $table = $wpdb->prefix . 'mcc_events';

    return $wpdb->get_results(
        "SELECT * FROM {$table} ORDER BY event_date ASC"
    );
}

/**
 * Add an event
 */
function mcc_add_event($eventName, $eventDate, $courseId, $eventType, $status)
{
    global $wpdb;

    $table = $wpdb->prefix . 'mcc_events';

    return $wpdb->insert(
        $table,
        [
            'event_name' => $eventName,
            'event_date' => $eventDate,
            'course_id'  => $courseId,
            'event_type' => $eventType,
            'status'     => $status
        ],
        [
            '%s',
            '%s',
            '%d',
            '%s',
            '%s'
        ]
    );
}

/**
 * Get a single event
 */
function mcc_get_event($id)
{
    global $wpdb;

    $table = $wpdb->prefix . 'mcc_events';

    return $wpdb->get_row(
        $wpdb->prepare(
            "SELECT * FROM {$table} WHERE id = %d",
            $id
        )
    );
}

/**
 * Update an event
 */
function mcc_update_event($id, $eventName, $eventDate, $courseId, $eventType, $status)
{
    global $wpdb;

    $table = $wpdb->prefix . 'mcc_events';

    return $wpdb->update(
        $table,
        [
            'event_name' => $eventName,
            'event_date' => $eventDate,
            'course_id'  => $courseId,
            'event_type' => $eventType,
            'status'     => $status
        ],
        [
            'id' => $id
        ],
        [
            '%s',
            '%s',
            '%d',
            '%s',
            '%s'
        ],
        [
            '%d'
        ]
    );
}


/**
 * Delete an event
 */
function mcc_delete_event($id)
{
    global $wpdb;

    $table = $wpdb->prefix . 'mcc_events';

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