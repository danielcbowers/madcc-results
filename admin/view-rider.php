<?php

if (!defined('ABSPATH')) {
    exit;
}

$id = intval($_GET['id'] ?? 0);

$rider = mcc_get_rider($id);

if (!$rider) {
    mcc_error_notice('Rider not found.');
    return;
}

$stats = mcc_get_rider_statistics($id);

$results = mcc_get_rider_results($id);

?>

<div class="wrap">

    <h1>
        <?php echo esc_html($rider->first_name . ' ' . $rider->last_name); ?>
    </h1>

    <table class="widefat striped" style="max-width:700px;">

        <tbody>

            <tr>
                <th>Bib Number</th>
                <td><?php echo esc_html($rider->bib_number); ?></td>
            </tr>

            <tr>
                <th>Club</th>
                <td><?php echo esc_html($rider->club); ?></td>
            </tr>

            <tr>
                <th>Category</th>
                <td><?php echo esc_html($rider->category); ?></td>
            </tr>

            <tr>
                <th>Events</th>
                <td><?php echo esc_html($stats['events']); ?></td>
            </tr>

            <tr>
                <th>Wins</th>
                <td><?php echo esc_html($stats['wins']); ?></td>
            </tr>

            <tr>
                <th>Podiums</th>
                <td><?php echo esc_html($stats['podiums']); ?></td>
            </tr>

        </tbody>

    </table>

    <h2>Recent Results</h2>

<table class="widefat striped">

    <thead>

        <tr>
            <th>Date</th>
            <th>Event</th>
            <th>Distance</th>
            <th>Time</th>
            <th>Status</th>
        </tr>

    </thead>

    <tbody>

    <?php foreach ($results as $result) : ?>

        <tr>

            <td>
                <?php echo esc_html(
                    date('d M Y', strtotime($result->event_date))
                ); ?>
            </td>

            <td>
                <?php echo esc_html($result->event_name); ?>
            </td>

            <td>
                <?php echo esc_html($result->distance); ?> miles
            </td>

            <td>
                <?php echo esc_html($result->finish_time); ?>
            </td>

            <td>
                <?php echo esc_html($result->status); ?>
            </td>

        </tr>

    <?php endforeach; ?>

    </tbody>

</table>

</div>