<?php

if (!defined('ABSPATH')) {
    exit;
}

$events = mcc_get_latest_events();

?>

<div class="mcc-results">

    <h2>Latest Results</h2>

    <?php if (empty($events)) : ?>

        <p>No completed events found.</p>

    <?php else : ?>

        <?php foreach ($events as $event) : ?>

            <?php

            $results = mcc_get_results_by_event($event->id);

            $winner = null;

            foreach ($results as $result) {

                if ($result->status === 'Finished') {
                    $winner = $result;
                    break;
                }
            }

            ?>

            <div class="mcc-event">

                <h3><?php echo esc_html($event->event_name); ?></h3>

                <p class="mcc-event-date">
                    <?php echo esc_html(date('j F Y', strtotime($event->event_date))); ?>
                </p>

                <div class="mcc-summary">

                    <p>
                        <strong>Winner:</strong><br>

                        <?php

                        if ($winner) {

                            echo esc_html(
                                $winner->first_name . ' ' . $winner->last_name
                            );

                        } else {

                            echo 'No Results';

                        }

                        ?>

                    </p>

                    <p>
                        <strong>Winning Time:</strong><br>

                        <?php

                        if ($winner) {

                            echo esc_html($winner->finish_time);

                        } else {

                            echo '—';

                        }

                        ?>

                    </p>

                    <p>
                        <strong>Riders:</strong><br>

                        <?php echo count($results); ?>

                    </p>

                </div>

                <p>

                    <a
                        class="mcc-button"
                        href="/event-results/?event=<?php echo $event->id; ?>"
                    >
                        View Full Results →
                    </a>

                </p>

            </div>

        <?php endforeach; ?>

    <?php endif; ?>

</div>