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

/**
 * Get the latest completed events
 */
function mcc_get_latest_events($limit = 5)
{
    global $wpdb;

    $table = $wpdb->prefix . 'mcc_events';

    return $wpdb->get_results(
        $wpdb->prepare(
            "
            SELECT *
            FROM {$table}
            WHERE status = %s
            ORDER BY event_date DESC
            LIMIT %d
            ",
            'Completed',
            $limit
        )
    );
}

/**
 * Sync all Spond events.
 *
 * @return true|WP_Error
 */
function mcc_sync_spond_events()
{
    $spond = new SpondService();

    $events = $spond->getEvents();

    if (!$events) {
        return 0;
    }

    $count = 0;

    foreach ($events as $event) {

        // Skip events before today
        $eventDate = strtotime($event['startTimestamp']);

        if ($eventDate < strtotime('today')) {
            continue;
        }

        mcc_sync_spond_event($event);
        $count++;
    }

    return $count;
}

function mcc_sync_spond_event(array $event)
{
    global $wpdb;

    $table = $wpdb->prefix . 'mcc_events';

    $existing = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT id
             FROM {$table}
             WHERE spond_event_id = %s",
            $event['id']
        )
    );

    $acceptedIds = $event['responses']['acceptedIds'] ?? [];
    $members     = $event['recipients']['group']['members'] ?? [];

    $acceptedRiders = [];

    foreach ($acceptedIds as $memberId) {

        foreach ($members as $member) {

            if ($member['id'] !== $memberId) {
                continue;
            }

            $riderId = mcc_sync_spond_rider($member);

            $acceptedRiders[] = [
                'rider_id'   => $riderId,
                'first_name' => mcc_format_name($member['firstName']),
                'last_name'  => mcc_format_name($member['lastName']),
            ];

            break;
        }
    }

    $data = [
        'spond_event_id'   => $event['id'],
        'event_name'       => $event['heading'],
        'description'      => $event['description'] ?? '',
        'event_date'       => gmdate('Y-m-d', strtotime($event['startTimestamp'])),
        'start_time'       => gmdate('H:i:s', strtotime($event['startTimestamp'])),
        'end_time'         => !empty($event['endTimestamp'])
            ? gmdate('H:i:s', strtotime($event['endTimestamp']))
            : null,
        'location'         => $event['location']['name'] ?? '',
        'event_type'       => 'Time Trial',
        'status'           => 'Planned',
        'accepted_count'   => count($acceptedRiders),
        'accepted_riders'  => wp_json_encode($acceptedRiders),
        'updated_at'       => current_time('mysql'),
    ];

    if ($existing) {

        $wpdb->update(
            $table,
            $data,
            ['id' => $existing->id]
        );

    } else {

        $wpdb->insert(
            $table,
            $data
        );

    }
}

function mcc_get_upcoming_events()
{
    global $wpdb;

    $table = $wpdb->prefix . 'mcc_events';

    return $wpdb->get_results(
        $wpdb->prepare(
            "
            SELECT *
            FROM {$table}
            WHERE event_date >= %s
            ORDER BY event_date ASC
            ",
            current_time('Y-m-d')
        )
    );
}