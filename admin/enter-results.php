<?php

if (!defined('ABSPATH')) {
    exit;
}

$eventId = intval($_GET['event_id'] ?? 0);

$event = mcc_get_event($eventId);

if (!$event) {
    mcc_error_notice('Event not found.');
    return;
}

$rider = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    check_admin_referer('mcc_add_result');

    $bibNumber = intval($_POST['bib_number']);

    $rider = mcc_get_rider_by_bib_number($bibNumber);

    if (!$rider) {

        mcc_error_notice('No rider found with bib number ' . $bibNumber);

    } else {

        if (
            mcc_add_result(
                $eventId,
                $rider->id,
                sanitize_text_field($_POST['finish_time']),
                sanitize_text_field($_POST['status']),
                sanitize_textarea_field($_POST['comments'])
            )
        ) {

            mcc_success_notice('Result added successfully.');

            // Clear the form after saving
            $_POST = [];
            $rider = null;

        } else {

            mcc_error_notice('Unable to save result.');

        }
    }
}

?>

<div class="wrap">

    <h1>Add Result</h1>

    <p>
        <strong>Event:</strong>
        <?php echo esc_html($event->event_name); ?>
    </p>

    <form method="post">

        <?php wp_nonce_field('mcc_add_result'); ?>

        <table class="form-table">

            <tr>

                <th>Bib Number</th>

                <td>

                    <input
                        type="number"
                        id="bib_number"
                        name="bib_number"
                        value="<?php echo esc_attr($_POST['bib_number'] ?? ''); ?>"
                        required>

                </td>

            </tr>

            <tr>

                <th>Rider</th>

                <td>

                    <span id="rider_name">

                        <?php if ($rider) : ?>

                            <strong>
                                <?php
                                echo esc_html(
                                    $rider->first_name . ' ' . $rider->last_name
                                );
                                ?>
                            </strong>

                        <?php else : ?>

                            <em>Enter a bib number</em>

                        <?php endif; ?>

                    </span>

                </td>

            </tr>

            <tr>

                <th>Finish Time</th>

                <td>

                    <input
                        type="time"
                        step="1"
                        name="finish_time"
                        value="<?php echo esc_attr($_POST['finish_time'] ?? ''); ?>"
                        required>

                </td>

            </tr>

            <tr>

                <th>Status</th>

                <td>

                    <?php $selectedStatus = $_POST['status'] ?? 'Finished'; ?>

                    <select name="status">

                        <option value="Finished" <?php selected($selectedStatus, 'Finished'); ?>>Finished</option>
                        <option value="DNF" <?php selected($selectedStatus, 'DNF'); ?>>DNF</option>
                        <option value="DNS" <?php selected($selectedStatus, 'DNS'); ?>>DNS</option>
                        <option value="DSQ" <?php selected($selectedStatus, 'DSQ'); ?>>DSQ</option>

                    </select>

                </td>

            </tr>

            <tr>

                <th>Comments</th>

                <td>

                    <textarea
                        name="comments"
                        rows="4"
                        cols="50"><?php echo esc_textarea($_POST['comments'] ?? ''); ?></textarea>

                </td>

            </tr>

        </table>

        <?php submit_button('Save Result'); ?>

    </form>

</div>

