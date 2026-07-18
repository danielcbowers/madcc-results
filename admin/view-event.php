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

/*
|--------------------------------------------------------------------------
| Sort results
|--------------------------------------------------------------------------
*/

usort($results, function ($a, $b) {

    if ($a->status === 'Finished' && $b->status !== 'Finished') {
        return -1;
    }

    if ($a->status !== 'Finished' && $b->status === 'Finished') {
        return 1;
    }

    return strcmp($a->finish_time, $b->finish_time);
});

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
                <th>Fastest Time</th>
                <td><?php echo esc_html($stats['fastest'] ?: '-'); ?></td>
            </tr>

        </tbody>

    </table>

    <h2 class="wp-heading-inline">

        Results (<?php echo count($results); ?>)

    </h2>

    <a href="<?php echo admin_url('admin.php?page=mcc-add-result&event_id=' . $event->id); ?>"
       class="page-title-action">
        Add Result
    </a>

    <hr class="wp-header-end">

    <?php if ($results) : ?>

        <table class="widefat striped">

            <thead>

                <tr>

                    <th>Pos</th>
                    <th>Bib</th>
                    <th>Rider</th>
                    <th>Time</th>
                    <th>Avg. Speed (mph)</th>
                    <th>Status</th>
                    <th>Actions</th>

                </tr>

            </thead>

            <tbody>

            <?php foreach ($results as $result) : ?>

                <?php

                if ($result->status === 'Finished') {
                    $pos = $position++;
                } else {
                    $pos = '-';
                }

                ?>

                <tr>

                    <td>

                        <?php

                        if ($pos === 1) {
                            echo '🥇';
                        } elseif ($pos === 2) {
                            echo '🥈';
                        } elseif ($pos === 3) {
                            echo '🥉';
                        } else {
                            echo esc_html($pos);
                        }

                        ?>

                    </td>

                    <td><?php echo esc_html($result->bib_number); ?></td>

                    <td>
                        <a href="<?php echo esc_url(
                            admin_url('admin.php?page=mcc-view-rider&id=' . $result->rider_id)
                        ); ?>">
                            <?php echo esc_html($result->first_name . ' ' . $result->last_name); ?>
                        </a>
                    </td>

                    <td><?php echo esc_html($result->finish_time); ?></td>

                    <td>

                    <?php

                    if ($result->status === 'Finished') {

                        echo esc_html(
                            mcc_calculate_average_speed(
                                $course->distance,
                                $result->finish_time
                            )
                        );

                    } else {

                        echo '-';

                    }

                    ?>

                    </td>

                    <td><?php echo esc_html($result->status); ?></td>

                    <td>

                        <a href="<?php echo admin_url('admin.php?page=mcc-edit-result&id=' . $result->id); ?>">
                            Edit
                        </a>

                        |

                        <a
                            href="<?php echo wp_nonce_url(
                                admin_url(
                                    'admin.php?page=mcc-view-event&id=' .
                                    $event->id .
                                    '&action=delete_result&result_id=' .
                                    $result->id
                                ),
                                'delete_result_' . $result->id
                            ); ?>"
                            onclick="return confirm('Delete this result?');">

                            Delete

                        </a>

                    </td>

                </tr>

            <?php endforeach; ?>

            </tbody>

        </table>

    <?php else : ?>

        <p>No results have been entered yet.</p>

    <?php endif; ?>

</div>