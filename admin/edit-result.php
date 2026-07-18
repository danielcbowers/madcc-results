<?php

if (!defined('ABSPATH')) {
    exit;
}

$id = intval($_GET['id'] ?? 0);

$result = mcc_get_result($id);

if (!$result) {
    mcc_error_notice('Result not found.');
    return;
}

$event = mcc_get_event($result->event_id);

$currentRider = mcc_get_rider($result->rider_id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    check_admin_referer('mcc_edit_result');

    $bibNumber = intval($_POST['bib_number']);

    $rider = mcc_get_rider_by_bib_number($bibNumber);

    if (!$rider) {

        mcc_error_notice('No rider found with that bib number.');

    } else {

        $updated = mcc_update_result(
            $id,
            $rider->id,
            sanitize_text_field($_POST['finish_time']),
            sanitize_text_field($_POST['status']),
            sanitize_textarea_field($_POST['comments'])
        );

        if ($updated !== false) {

            mcc_success_notice('Result updated successfully.');

            // Refresh the result so the form shows the updated values
            $result = mcc_get_result($id);
            $currentRider = mcc_get_rider($result->rider_id);

        } else {

            mcc_error_notice('Unable to update result.');

        }

    }

}

?>

<div class="wrap">

    <h1>Edit Result</h1>

    <p>

        <strong>Event:</strong>

        <?php echo esc_html($event->event_name); ?>

    </p>

    <form method="post">

        <?php wp_nonce_field('mcc_edit_result'); ?>

        <table class="form-table">

            <tr>

                <th>Bib Number</th>

                <td>

                    <input
                        type="number"
                        id="bib_number"
                        name="bib_number"
                        value="<?php echo esc_attr($currentRider->bib_number); ?>"
                        required>

                </td>

            </tr>

            <tr>

                <th>Rider</th>

                <td>

                    <span id="rider_name">

                        <strong>

                            <?php
                            echo esc_html(
                                $currentRider->first_name . ' ' .
                                $currentRider->last_name
                            );
                            ?>

                        </strong>

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
                        value="<?php echo esc_attr($result->finish_time); ?>"
                        required>

                </td>

            </tr>

            <tr>

                <th>Status</th>

                <td>

                    <select name="status">

                        <option value="Finished" <?php selected($result->status, 'Finished'); ?>>
                            Finished
                        </option>

                        <option value="DNF" <?php selected($result->status, 'DNF'); ?>>
                            DNF
                        </option>

                        <option value="DNS" <?php selected($result->status, 'DNS'); ?>>
                            DNS
                        </option>

                        <option value="DSQ" <?php selected($result->status, 'DSQ'); ?>>
                            DSQ
                        </option>

                    </select>

                </td>

            </tr>

            <tr>

                <th>Comments</th>

                <td>

                    <textarea
                        name="comments"
                        rows="4"
                        cols="50"><?php echo esc_textarea($result->comments); ?></textarea>

                </td>

            </tr>

        </table>

        <?php submit_button('Update Result'); ?>

    </form>

</div>