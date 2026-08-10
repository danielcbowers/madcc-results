<?php

if (!defined('ABSPATH')) {
    exit;
}

$id = intval($_GET['id'] ?? 0);

$event = mcc_get_event($id);

$course = mcc_get_course($event->course_id);

if (!$event) {
    mcc_error_notice('Event not found.');
    return;
}

if (
    isset($_GET['action']) &&
    $_GET['action'] === 'delete_result' &&
    isset($_GET['result_id'])
) {

    $resultId = intval($_GET['result_id']);

    check_admin_referer('delete_result_' . $resultId);

    if (mcc_delete_result($resultId)) {
        mcc_success_notice('Result deleted successfully.');
    } else {
        mcc_error_notice('Unable to delete result.');
    }
}

$results = mcc_get_results_by_event($id);
$stats = mcc_get_event_statistics($id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    check_admin_referer('mcc_save_results');

    // Delete existing results for this event
    global $wpdb;

    $wpdb->delete(
        $wpdb->prefix . 'mcc_results',
        [
            'event_id' => $id
        ],
        [
            '%d'
        ]
    );

    // Save each row
    foreach ($_POST['rider_id'] as $index => $riderId) {

        if (empty($riderId)) {
            continue;
        }

        $result = mcc_add_result(
            $id,
            intval($riderId),
            intval($_POST['bib_number'][$index]),
            sanitize_text_field($_POST['bike_type'][$index]),
            sanitize_text_field($_POST['finish_time'][$index]),
            sanitize_text_field($_POST['status'][$index]),
            sanitize_textarea_field($_POST['comments'][$index])
        );

        global $wpdb;

        if ($result === false) {

            echo '<h2>Database Error</h2>';

            echo '<pre>';
            echo $wpdb->last_error;
            echo '</pre>';

            echo '<pre>';
            print_r($wpdb->last_query);
            echo '</pre>';

            die();
        }
    }

    mcc_success_notice('Results saved successfully.');

    // Reload results and stats
    $results = mcc_get_results_by_event($id);
    $stats = mcc_get_event_statistics($id);
}

$position = 1;

?>

<div class="wrap">

    <h1><?php echo esc_html($event->event_name); ?></h1>

    <table class="form-table">

        <tr>
            <th>Date</th>
            <td><?php echo esc_html($event->event_date); ?></td>
        </tr>

        <tr>
            <th>Course</th>
            <td><?php echo esc_html(mcc_get_course_display_name($event->course_id)); ?></td>
        </tr>

        <tr>
            <th>Type</th>
            <td><?php echo esc_html($event->event_type); ?></td>
        </tr>

        <tr>
            <th>Status</th>
            <td><?php echo esc_html($event->status); ?></td>
        </tr>

    </table>

    <h2>Statistics</h2>

    <table class="widefat striped" style="max-width:500px;margin-bottom:30px;">

        <tbody>

            <tr>
                <th>Total Results</th>
                <td><?php echo esc_html($stats['total']); ?></td>
            </tr>

            <tr>
                <th>Finished</th>
                <td><?php echo esc_html($stats['finished']); ?></td>
            </tr>

            <tr>
                <th>DNF</th>
                <td><?php echo esc_html($stats['dnf']); ?></td>
            </tr>

            <tr>
                <th>DNS</th>
                <td><?php echo esc_html($stats['dns']); ?></td>
            </tr>

            <tr>
                <th>DSQ</th>
                <td><?php echo esc_html($stats['dsq']); ?></td>
            </tr>

            <tr>
                <?php
                $fastest = '-';

                foreach ($results as $result) {
                    if ($result->status === 'Finished') {
                        $fastest = $result->actual_time;
                        break;
                    }
                }
                ?>

                <tr>
                    <th>Fastest Time</th>
                    <td><?php echo esc_html($fastest); ?></td>
                </tr>
            </tr>

        </tbody>

    </table>

    <h2 class="wp-heading-inline">

        Results (<?php echo count($results); ?>)

    </h2>

    <?php $riders = mcc_get_all_riders(); ?>

    <form method="post">

    <?php wp_nonce_field('mcc_save_results'); ?>

    <table class="widefat striped">

        <thead>

            <tr>

                <th style="width:80px;">Bib</th>
                <th style="width:160px;">Bike</th>
                <th>Rider</th>
                <th style="width:120px;">Time</th>
                <th style="width:120px;">Status</th>
                <th>Comments</th>
                <th style="width:80px;"></th>

            </tr>

        </thead>

        <tbody id="results-body">

            <?php if (!empty($results)) : ?>

                <?php foreach ($results as $result) : ?>

                    <tr>

                        <td>
                            <input
                                type="number"
                                name="bib_number[]"
                                class="small-text"
                                value="<?php echo esc_attr($result->bib_number); ?>">
                        </td>

                        <td>

                            <select name="bike_type[]">

                                <option value="Time Trial" <?php selected($result->bike_type, 'Time Trial'); ?>>
                                    Time Trial
                                </option>

                                <option value="Road Bike" <?php selected($result->bike_type, 'Road Bike'); ?>>
                                    Road Bike
                                </option>

                                <option value="Retro Bike" <?php selected($result->bike_type, 'Retro Bike'); ?>>
                                    Retro Bike
                                </option>

                            </select>

                        </td>

                        <td>

                            <select name="rider_id[]">

                                <option value="">Select rider...</option>

                                <?php foreach ($riders as $rider) : ?>

                                    <option
                                        value="<?php echo $rider->id; ?>"
                                        <?php selected($result->rider_id, $rider->id); ?>>

                                        <?php echo esc_html($rider->first_name . ' ' . $rider->last_name); ?>

                                    </option>

                                <?php endforeach; ?>

                            </select>

                        </td>

                        <td>

                            <input
                                type="text"
                                name="finish_time[]"
                                value="<?php echo esc_attr($result->finish_time); ?>">

                        </td>

                        <td>

                            <select name="status[]">

                                <option value="Finished" <?php selected($result->status, 'Finished'); ?>>Finished</option>
                                <option value="DNF" <?php selected($result->status, 'DNF'); ?>>DNF</option>
                                <option value="DNS" <?php selected($result->status, 'DNS'); ?>>DNS</option>
                                <option value="DSQ" <?php selected($result->status, 'DSQ'); ?>>DSQ</option>

                            </select>

                        </td>

                        <td>

                            <input
                                type="text"
                                name="comments[]"
                                value="<?php echo esc_attr($result->comments); ?>">

                        </td>

                        <td>

                            <button
                                type="button"
                                class="button remove-row">

                                Remove

                            </button>

                        </td>

                    </tr>

                <?php endforeach; ?>

            <?php else : ?>

                <tr>

                    <td>

                        <input
                            type="number"
                            name="bib_number[]"
                            class="small-text">

                    </td>

                    <td>

                        <select name="bike_type[]">

                            <option value="Time Trial">Time Trial</option>
                            <option value="Road Bike">Road Bike</option>
                            <option value="Retro Bike">Retro Bike</option>

                        </select>

                    </td>

                    <td>

                        <select name="rider_id[]">

                            <option value="">Select rider...</option>

                            <?php foreach ($riders as $rider) : ?>

                                <option value="<?php echo $rider->id; ?>">

                                    <?php echo esc_html($rider->first_name . ' ' . $rider->last_name); ?>

                                </option>

                            <?php endforeach; ?>

                        </select>

                    </td>

                    <td>

                        <input
                            type="text"
                            name="finish_time[]"
                            placeholder="00:23:14">

                    </td>

                    <td>

                        <select name="status[]">

                            <option value="Finished">Finished</option>
                            <option value="DNF">DNF</option>
                            <option value="DNS">DNS</option>
                            <option value="DSQ">DSQ</option>

                        </select>

                    </td>

                    <td>

                        <input
                            type="text"
                            name="comments[]">

                    </td>

                    <td>

                        <button
                            type="button"
                            class="button remove-row">

                            Remove

                        </button>

                    </td>

                </tr>

            <?php endif; ?>

        </tbody>

    </table>

    <p>

    <button
        type="button"
        class="button"
        id="add-row">

        + Add Rider

    </button>

    </p>

    <?php submit_button('Save Results'); ?>

    </form>

    <script>

        document.addEventListener("DOMContentLoaded", function () {

            const tbody = document.getElementById("results-body");

            document.getElementById("add-row").onclick = function () {

                const row = tbody.rows[0].cloneNode(true);

                row.querySelectorAll("input").forEach(i => i.value = "");

                row.querySelectorAll("select").forEach(s => s.selectedIndex = 0);

                tbody.appendChild(row);

                wireButtons();

            };

            function wireButtons() {

                document.querySelectorAll(".remove-row").forEach(button => {

                    button.onclick = function () {

                        if (tbody.rows.length > 1) {

                            this.closest("tr").remove();

                        }

                    };

                });

            }

            wireButtons();

        });

    </script>

</div>