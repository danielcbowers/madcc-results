<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Course Service
 *
 * Handles all course database operations.
 */

/**
 * Get all courses
 */
function mcc_get_all_courses()
{
    global $wpdb;

    $table = $wpdb->prefix . 'mcc_courses';

    return $wpdb->get_results(
        "SELECT * FROM {$table} ORDER BY course_code ASC"
    );
}

/**
 * Get a single course
 */
function mcc_get_course($id)
{
    global $wpdb;

    $table = $wpdb->prefix . 'mcc_courses';

    return $wpdb->get_row(
        $wpdb->prepare(
            "SELECT * FROM {$table} WHERE id = %d",
            $id
        )
    );
}

/**
 * Add a course
 */
function mcc_add_course(
    $courseCode,
    $courseName,
    $courseType,
    $distance,
    $description,
    $startLocation,
    $startLatitude,
    $startLongitude,
    $finishLocation,
    $finishLatitude,
    $finishLongitude,
    $notes,
    $active = true
)
{
    global $wpdb;

    $table = $wpdb->prefix . 'mcc_courses';

    return $wpdb->insert(
        $table,
        [
            'course_code'      => $courseCode,
            'course_name'      => $courseName,
            'course_type'      => $courseType,
            'distance'         => $distance,
            'description'      => $description,

            'start_location'   => $startLocation,
            'start_latitude'   => $startLatitude,
            'start_longitude'  => $startLongitude,

            'finish_location'  => $finishLocation,
            'finish_latitude'  => $finishLatitude,
            'finish_longitude' => $finishLongitude,

            'notes'            => $notes,

            'active'           => $active ? 1 : 0
        ],
        [
            '%s',
            '%s',
            '%s',
            '%f',
            '%s',

            '%s',
            '%f',
            '%f',

            '%s',
            '%f',
            '%f',

            '%s',

            '%d'
        ]
    );
}

/**
 * Update a course
 */
function mcc_update_course(
    $id,
    $courseCode,
    $courseName,
    $courseType,
    $distance,
    $description,
    $startLocation,
    $startLatitude,
    $startLongitude,
    $finishLocation,
    $finishLatitude,
    $finishLongitude,
    $notes,
    $active
)
{
    global $wpdb;

    $table = $wpdb->prefix . 'mcc_courses';

    return $wpdb->update(
        $table,
        [
            'course_code'      => $courseCode,
            'course_name'      => $courseName,
            'course_type'      => $courseType,
            'distance'         => $distance,
            'description'      => $description,

            'start_location'   => $startLocation,
            'start_latitude'   => $startLatitude,
            'start_longitude'  => $startLongitude,

            'finish_location'  => $finishLocation,
            'finish_latitude'  => $finishLatitude,
            'finish_longitude' => $finishLongitude,

            'notes'            => $notes,

            'active'           => $active ? 1 : 0
        ],
        [
            'id' => $id
        ],
        [
            '%s',
            '%s',
            '%s',
            '%f',
            '%s',

            '%s',
            '%f',
            '%f',

            '%s',
            '%f',
            '%f',

            '%s',

            '%d'
        ],
        [
            '%d'
        ]
    );
}

/**
 * Delete a course
 */
function mcc_delete_course($id)
{
    global $wpdb;

    $table = $wpdb->prefix . 'mcc_courses';

    return $wpdb->delete(
        $table,
        ['id' => $id],
        ['%d']
    );
}

/**
 * Get a formatted course name
 */
function mcc_get_course_display_name($id)
{
    $course = mcc_get_course($id);

    if (!$course) {
        return 'Unknown Course';
    }

    return $course->course_code . ' - ' . $course->course_name;
}