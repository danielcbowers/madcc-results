<?php

if (!defined('ABSPATH')) {
    exit;
}

$id = intval($_GET['id'] ?? 0);

$event = mcc_get_event($id);

if (!$event) {
    mcc_error_notice('Event not found.');
    return;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    check_admin_referer('mcc_edit_event');

    mcc_update_event(
        $id,
        sanitize_text_field($_POST['event_name']),
        sanitize_text_field($_POST['event_date']),
        intval($_POST['course_id']),
        sanitize_text_field($_POST['event_type']),
        sanitize_text_field($_POST['status'])
    );

    mcc_success_notice('Event updated successfully.');

    $event = mcc_get_event($id);
}

$courses = mcc_get_all_courses();

?>

<div class="wrap">

<h1>Edit Event</h1>

<form method="post">

<?php wp_nonce_field('mcc_edit_event'); ?>

<table class="form-table">

<tr>

<th>Event Name</th>

<td>

<input
type="text"
name="event_name"
class="regular-text"
value="<?php echo esc_attr($event->event_name); ?>"
required>

</td>

</tr>

<tr>

<th>Date</th>

<td>

<input
type="date"
name="event_date"
value="<?php echo esc_attr($event->event_date); ?>"
required>

</td>

</tr>

<tr>

<th>Course</th>

<td>

<select name="course_id">

<?php foreach ($courses as $course) : ?>

<option
value="<?php echo esc_attr($course->id); ?>"
<?php selected($event->course_id, $course->id); ?>>

<?php echo esc_html($course->course_code . ' - ' . $course->course_name); ?>

</option>

<?php endforeach; ?>

</select>

</td>

</tr>

<tr>

<th>Event Type</th>

<td>

<select name="event_type">

<?php

$types = [
    'Club 10',
    'Club 25',
    'Hill Climb',
    'Open Event'
];

foreach ($types as $type) :

?>

<option
value="<?php echo esc_attr($type); ?>"
<?php selected($event->event_type, $type); ?>>

<?php echo esc_html($type); ?>

</option>

<?php endforeach; ?>

</select>

</td>

</tr>

<tr>

<th>Status</th>

<td>

<select name="status">

<?php

$statuses = [
    'Planned',
    'Completed',
    'Cancelled'
];

foreach ($statuses as $status) :

?>

<option
value="<?php echo esc_attr($status); ?>"
<?php selected($event->status, $status); ?>>

<?php echo esc_html($status); ?>

</option>

<?php endforeach; ?>

</select>

</td>

</tr>

</table>

<?php submit_button('Update Event'); ?>

</form>

</div>