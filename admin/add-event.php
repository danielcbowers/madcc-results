<?php

if (!defined('ABSPATH')) {
    exit;
}

$courses = mcc_get_all_courses();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    check_admin_referer('mcc_add_event');

    mcc_add_event(
        sanitize_text_field($_POST['event_name']),
        sanitize_text_field($_POST['event_date']),
        intval($_POST['course_id']),
        sanitize_text_field($_POST['event_type']),
        sanitize_text_field($_POST['status'])
    );

    echo '<div class="notice notice-success"><p>Event saved successfully.</p></div>';
}

?>

<div class="wrap">

    <h1>Add Event</h1>

    <form method="post">

        <?php wp_nonce_field('mcc_add_event'); ?>

        <table class="form-table">

            <tr>
                <th>Event Name</th>
                <td>
                    <input
                        type="text"
                        name="event_name"
                        class="regular-text"
                        required>
                </td>
            </tr>

            <tr>
                <th>Date</th>
                <td>
                    <input
                        type="date"
                        name="event_date"
                        required>
                </td>
            </tr>

            <tr>
                <th>TT Course</th>
                <td>

                    <select name="course_id" required>

                        <option value="">Select a course</option>

                        <?php foreach ($courses as $course): ?>

                            <option value="<?php echo esc_attr($course->id); ?>">

                                <?php
                                echo esc_html(
                                    $course->course_code . ' - ' . $course->course_name
                                );
                                ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </td>
            </tr>

            <tr>
                <th>Event Type</th>
                <td>
                    <select name="event_type">

                        <option value="Club 10">Club 10</option>

                        <option value="Club 25">Club 25</option>

                        <option value="Hill Climb">Hill Climb</option>

                        <option value="Open Event">Open Event</option>

                    </select>
                </td>
            </tr>

            <tr>

                <th>Status</th>

                <td>

                    <select name="status">

                        <option value="Planned">Planned</option>

                        <option value="Completed">Completed</option>

                        <option value="Cancelled">Cancelled</option>

                    </select>

                </td>

            </tr>

        </table>

        <?php submit_button('Add Event'); ?>

    </form>

</div>