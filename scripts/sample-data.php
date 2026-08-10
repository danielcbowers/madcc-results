<?php

if (!defined('ABSPATH')) {
    exit;
}

global $wpdb;

echo "Resetting MCC database...\n";

/*
|--------------------------------------------------------------------------
| Drop existing tables
|--------------------------------------------------------------------------
*/

$tables = [
    $wpdb->prefix . 'mcc_results',
    $wpdb->prefix . 'mcc_events',
    $wpdb->prefix . 'mcc_courses',
    $wpdb->prefix . 'mcc_riders',
];

foreach ($tables as $table) {
    $wpdb->query("DROP TABLE IF EXISTS {$table}");
}

/*
|--------------------------------------------------------------------------
| Recreate tables
|--------------------------------------------------------------------------
*/

mcc_results_install();

echo "Tables created.\n";

/*
|--------------------------------------------------------------------------
| Create Course
|--------------------------------------------------------------------------
*/

mcc_add_course(
    'J85/10',
    'Maldon 10',
    'TT',
    10,
    '',
    '',
    null,
    null,
    '',
    null,
    null,
    '',
    true
);

$courseId = $wpdb->insert_id;

echo "Course created.\n";

/*
|--------------------------------------------------------------------------
| Riders
|--------------------------------------------------------------------------
*/

$names = [
    ['Daniel', 'Bowers'],
    ['James', 'Smith'],
    ['Ben', 'Turner'],
    ['Luke', 'Harris'],
    ['Tom', 'Brown'],
    ['Chris', 'Green'],
    ['Ryan', 'Clark'],
    ['Matt', 'Wilson'],
    ['Joe', 'King'],
    ['Jack', 'White']
];

$riders = [];

foreach ($names as $person) {

    mcc_add_rider(
        $person[0],
        $person[1],
        strtolower($person[0]) . '@example.com',
        'Maldon CC',
        'Senior',
        true
    );

    $riders[] = $wpdb->insert_id;
}

echo count($riders) . " riders created.\n";

/*
|--------------------------------------------------------------------------
| Events + Results
|--------------------------------------------------------------------------
*/

for ($event = 1; $event <= 10; $event++) {

    $date = date('Y-m-d', strtotime("+{$event} week"));

    mcc_add_event(
        "Club 10 #{$event}",
        $date,
        $courseId,
        'Time Trial',
        'Completed'
    );

    $eventId = $wpdb->insert_id;

    $bib = 1;

    foreach ($riders as $riderId) {

        // Random DNF/DNS
        $status = 'Finished';

        if (rand(1,100) <= 5) {
            $status = 'DNF';
        } elseif (rand(1,100) <= 3) {
            $status = 'DNS';
        }

        $finishTime = null;

        if ($status === 'Finished') {

            // Random actual time (24:00 - 30:30)
            $actualSeconds = rand(24*60, 30*60+30);

            // Add bib minutes
            $finishSeconds = $actualSeconds + ($bib * 60);

            $finishTime = gmdate('H:i:s', $finishSeconds);
        }

        mcc_add_result(
            $eventId,
            $riderId,
            $bib,
            'Time Trial',
            $finishTime,
            $status,
            ''
        );

        $bib++;
    }

    echo "Created Event {$event}\n";
}

echo "\nDone!\n";