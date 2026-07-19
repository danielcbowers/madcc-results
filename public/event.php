<?php

if (!defined('ABSPATH')) {
    exit;
}

$eventId = isset($_GET['event']) ? intval($_GET['event']) : 0;

if (!$eventId) {
    echo '<p>No event selected.</p>';
    return;
}

$event = mcc_get_event($eventId);

$course = null;

if (!empty($event->course_id)) {
    $course = mcc_get_course($event->course_id);
}

if (!$event) {
    echo '<p>Event not found.</p>';
    return;
}

$results = mcc_get_results_by_event($eventId);

$stats = mcc_get_event_statistics($eventId);

$winner = null;

foreach ($results as $result) {

    if ($result->status === 'Finished') {
        $winner = $result;
        break;
    }
}
?>

<div class="mcc-results">

    <h2><?php echo esc_html($event->event_name); ?></h2>

    <p class="mcc-event-date">
        <?php echo esc_html(date('j F Y', strtotime($event->event_date))); ?>
    </p>

    <?php if (empty($results)) : ?>

        <p>No results have been entered for this event.</p>

    <?php else : ?>
    
        <div class="mcc-event-details">

            <div>
                <strong>Event Type</strong><br>
                <?php echo esc_html($event->event_type); ?>
            </div>

            <?php if ($course) : ?>

                <div>
                    <strong>Course</strong><br>
                    <?php echo esc_html($course->course_name); ?>
                </div>

                <div>
                    <strong>Distance</strong><br>
                    <?php echo esc_html($course->distance); ?> miles
                </div>

            <?php endif; ?>

            <div>
                <strong>Status</strong><br>
                <?php echo esc_html($event->status); ?>
            </div>

        </div>

        <div class="mcc-event-summary">

            <div>
                <strong>Winner</strong><br>

                <?php

                if ($winner) {

                    echo esc_html(
                        $winner->first_name . ' ' . $winner->last_name
                    );

                } else {

                    echo 'No Results';

                }

                ?>

            </div>

            <div>

                <strong>Winning Time</strong><br>

                <?php echo esc_html($stats['fastest'] ?: '—'); ?>

            </div>

            <div>

                <strong>Riders</strong><br>

                <?php echo $stats['total']; ?>

            </div>

            <div>

                <strong>Finished</strong><br>

                <?php echo $stats['finished']; ?>

            </div>

            <div>

                <strong>DNF</strong><br>

                <?php echo $stats['dnf']; ?>

            </div>

        </div>

        <table class="mcc-results-table">

            <thead>
                <tr>
                    <th>Pos</th>
                    <th>Bib</th>
                    <th>Rider</th>
                    <th>Time</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>

                <?php

                $position = 1;

                foreach ($results as $result) :

                ?>

                    <tr>

                        <td>

                            <?php

                            if ($result->status === 'Finished') {

                                switch ($position) {

                                    case 1:
                                        echo '🥇 1';
                                        break;

                                    case 2:
                                        echo '🥈 2';
                                        break;

                                    case 3:
                                        echo '🥉 3';
                                        break;

                                    default:
                                        echo $position;
                                }

                                $position++;

                            } else {

                                echo '-';

                            }

                            ?>

                        </td>

                        <td><?php echo esc_html($result->bib_number); ?></td>

                        <td>
                            <a href="<?php echo esc_url(home_url('/rider-profile/?id=' . $result->rider_id)); ?>">
                                <?php
                                echo esc_html(
                                    $result->first_name . ' ' . $result->last_name
                                );
                                ?>
                            </a>
                        </td>

                        <td>

                            <?php
                            echo $result->status === 'Finished'
                                ? esc_html($result->finish_time)
                                : '-';
                            ?>

                        </td>

                        <td><?php echo esc_html($result->status); ?></td>

                    </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

    <?php endif; ?>

</div>