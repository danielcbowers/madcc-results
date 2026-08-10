<?php

if (!defined('ABSPATH')) {
    exit;
}

$events = mcc_get_upcoming_events();

?>

<div class="mcc-results">

    <h2>Upcoming Events</h2>

    <?php if (empty($events)) : ?>

        <p>No upcoming events found.</p>

    <?php else : ?>

        <?php foreach ($events as $event) : ?>

            <div class="mcc-event">

                <h3><?php echo esc_html($event->event_name); ?></h3>

                <p class="mcc-event-date">
                    <?php echo esc_html(date('j F Y', strtotime($event->event_date))); ?>
                </p>

                <p>

                    <a
                        class="mcc-button"
                        href="/event/?event=<?php echo $event->id; ?>"
                    >
                        View Event →
                    </a>

                </p>

            </div>

        <?php endforeach; ?>

    <?php endif; ?>

</div>