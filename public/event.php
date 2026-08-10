<?php

if (!defined('ABSPATH')) {
    exit;
}

$event_id = isset($_GET['event']) ? intval($_GET['event']) : 0;

if (!$event_id) {
    echo '<p>Event not found.</p>';
    return;
}

$event = mcc_get_event($event_id);

if (!$event) {
    echo '<p>Event not found.</p>';
    return;
}

$course = null;

if (!empty($event->course_id)) {
    $course = mcc_get_course($event->course_id);
}

$riders = [];

if (!empty($event->accepted_riders)) {
    $riders = json_decode($event->accepted_riders, true);
}

?>

<div class="mcc-event-page">

    <header class="mcc-event-header">

        <h1><?php echo esc_html($event->event_name); ?></h1>

        <p class="mcc-event-date">
            📅 <?php echo esc_html(date('l j F Y', strtotime($event->event_date))); ?>
        </p>

        <?php if (!empty($event->start_time)) : ?>

            <p class="mcc-event-time">
                🕒 <?php echo esc_html(date('H:i', strtotime($event->start_time))); ?>

                <?php if (!empty($event->end_time)) : ?>
                    - <?php echo esc_html(date('H:i', strtotime($event->end_time))); ?>
                <?php endif; ?>

            </p>

        <?php endif; ?>

    </header>

    <div class="mcc-event-grid">

        <div class="mcc-card">

            <h2>Event Information</h2>

            <table class="mcc-event-table">

                <tr>
                    <th>Status</th>
                    <td><?php echo esc_html($event->status); ?></td>
                </tr>

                <tr>
                    <th>Course</th>
                    <td>
                        <?php
                        echo $course
                            ? esc_html($course->course_name)
                            : 'Unknown';
                        ?>
                    </td>
                </tr>

                <?php if (!empty($event->location)) : ?>

                    <tr>
                        <th>Location</th>
                        <td><?php echo esc_html($event->location); ?></td>
                    </tr>

                <?php endif; ?>

                <tr>
                    <th>Entries</th>
                    <td><?php echo intval($event->accepted_count); ?></td>
                </tr>

            </table>

            <?php if (!empty($event->description)) : ?>

                <h3>Description</h3>

                <p>
                    <?php echo nl2br(esc_html($event->description)); ?>
                </p>

            <?php endif; ?>

        </div>

        <div class="mcc-card">

            <h2>
                🚴 Riders Entered
                (<?php echo intval($event->accepted_count); ?>)
            </h2>

            <?php if (!empty($riders)) : ?>

                <ul class="mcc-rider-list">

                    <?php foreach ($riders as $rider) : ?>

                        <li>

                            <a href="<?php echo esc_url(home_url('/rider/?id=' . $rider['rider_id'])); ?>">

                                <?php
                                echo esc_html(
                                    $rider['first_name'] . ' ' . $rider['last_name']
                                );
                                ?>

                            </a>

                        </li>

                    <?php endforeach; ?>

                </ul>

            <?php else : ?>

                <p>No riders have entered yet.</p>

            <?php endif; ?>

        </div>

    </div>

    <div class="mcc-event-actions">

        <a
            class="mcc-button"
            href="<?php echo esc_url(home_url('/event-results/?event=' . $event->id)); ?>">

            View Results →

        </a>

    </div>

</div>