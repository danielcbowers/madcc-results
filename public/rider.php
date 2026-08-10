<?php

if (!defined('ABSPATH')) {
    exit;
}

$riderId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$riderId) {
    echo '<p>No rider selected.</p>';
    return;
}

$rider = mcc_get_rider($riderId);

if (!$rider) {
    echo '<p>Rider not found.</p>';
    return;
}

$results = mcc_get_rider_results($riderId);
$stats   = mcc_get_rider_statistics($riderId);

?>

<div class="mcc-results">

    <h2>
        <?php echo esc_html($rider->first_name . ' ' . $rider->last_name); ?>
    </h2>

    <p class="mcc-event-date">
        <?php echo esc_html($rider->club); ?>
    </p>

    <div class="mcc-event-details">

        <div>
            <strong>Category</strong><br>
            <?php echo esc_html($rider->category); ?>
        </div>

        <div>
            <strong>Status</strong><br>
            <?php echo $rider->active ? 'Active' : 'Inactive'; ?>
        </div>

    </div>

    <div class="mcc-event-summary">

        <div>
            <strong>Events</strong><br>
            <?php echo esc_html($stats['events']); ?>
        </div>

        <div>
            <strong>Wins</strong><br>
            <?php echo esc_html($stats['wins']); ?>
        </div>

        <div>
            <strong>Podiums</strong><br>
            <?php echo esc_html($stats['podiums']); ?>
        </div>

        <div>
            <strong>Best Time</strong><br>
            <?php echo esc_html($stats['best_time'] ?: '—'); ?>
        </div>

    </div>

    <?php if (empty($results)) : ?>

        <p>This rider has no recorded results.</p>

    <?php else : ?>

        <table class="mcc-results-table">

            <thead>
                <tr>
                    <th>Pos</th>
                    <th>Date</th>
                    <th>Event</th>
                    <th>Actual Time</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>

                <?php foreach ($results as $result) : ?>

                    <?php
                    $position = mcc_get_position_for_rider(
                        $result->event_id,
                        $riderId
                    );
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

                            } else {

                                echo '-';

                            }

                            ?>

                        </td>

                        <td>
                            <?php echo esc_html(date('j M Y', strtotime($result->event_date))); ?>
                        </td>

                        <td>
                            <a href="<?php echo esc_url(home_url('/event-results/?event=' . $result->event_id)); ?>">
                                <?php echo esc_html($result->event_name); ?>
                            </a>
                        </td>

                        <td>

                            <?php

                            echo $result->status === 'Finished'
                                ? esc_html($result->actual_time)
                                : '-';

                            ?>

                        </td>

                        <td>
                            <?php echo esc_html($result->status); ?>
                        </td>

                    </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

    <?php endif; ?>

</div>