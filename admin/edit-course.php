<?php

if (!defined('ABSPATH')) {
    exit;
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$course = mcc_get_course($id);

if (!$course) {
    echo '<div class="notice notice-error"><p>Course not found.</p></div>';
    return;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    check_admin_referer('mcc_edit_course');

    mcc_update_course(

        $id,

        sanitize_text_field($_POST['course_code']),
        sanitize_text_field($_POST['course_name']),
        sanitize_text_field($_POST['course_type']),

        floatval($_POST['distance']),

        sanitize_textarea_field($_POST['description']),

        sanitize_text_field($_POST['start_location']),
        floatval($_POST['start_latitude']),
        floatval($_POST['start_longitude']),

        sanitize_text_field($_POST['finish_location']),
        floatval($_POST['finish_latitude']),
        floatval($_POST['finish_longitude']),

        sanitize_textarea_field($_POST['notes']),

        isset($_POST['active'])
    );

    mcc_success_notice('Course updated successfully.');

    $course = mcc_get_course($id);
}

?>

<div class="wrap">

<h1>Edit Course</h1>

<?php
mcc_back_link(
    'mcc-courses',
    'Back to Courses'
);
?>

<form method="post">

<?php wp_nonce_field('mcc_edit_course'); ?>

<h2>General</h2>

<table class="form-table">

<tr>

<th>Course Code</th>

<td>

<input
    type="text"
    name="course_code"
    class="regular-text"
    required
    value="<?php echo esc_attr($course->course_code); ?>">

</td>

</tr>

<tr>

<th>Course Name</th>

<td>

<input
    type="text"
    name="course_name"
    class="regular-text"
    required
    value="<?php echo esc_attr($course->course_name); ?>">

</td>

</tr>

<tr>

<th>Course Type</th>

<td>

<select name="course_type">

<option value="TT" <?php selected($course->course_type, 'TT'); ?>>Time Trial</option>

<option value="Training" <?php selected($course->course_type, 'Training'); ?>>Training Ride</option>

<option value="Road Race" <?php selected($course->course_type, 'Road Race'); ?>>Road Race</option>

<option value="Reliability Ride" <?php selected($course->course_type, 'Reliability Ride'); ?>>Reliability Ride</option>

</select>

</td>

</tr>

<tr>

<th>Distance (Miles)</th>

<td>

<input
    type="number"
    step="0.1"
    name="distance"
    value="<?php echo esc_attr($course->distance); ?>">

</td>

</tr>

<tr>

<th>Description</th>

<td>

<textarea
    name="description"
    rows="5"
    class="large-text"><?php echo esc_textarea($course->description); ?></textarea>

</td>

</tr>

<tr>

<th>Active</th>

<td>

<label>

<input
    type="checkbox"
    name="active"
    <?php checked($course->active); ?>>

Active

</label>

</td>

</tr>

</table>

<hr>

<h2>Start</h2>

<table class="form-table">

<tr>

<th>Start Location</th>

<td>

<input
    type="text"
    name="start_location"
    class="regular-text"
    value="<?php echo esc_attr($course->start_location); ?>">

</td>

</tr>

<tr>

<th>Latitude</th>

<td>

<input
    type="number"
    step="0.00000001"
    name="start_latitude"
    value="<?php echo esc_attr($course->start_latitude); ?>">

</td>

</tr>

<tr>

<th>Longitude</th>

<td>

<input
    type="number"
    step="0.00000001"
    name="start_longitude"
    value="<?php echo esc_attr($course->start_longitude); ?>">

</td>

</tr>

</table>

<hr>

<h2>Finish</h2>

<table class="form-table">

<tr>

<th>Finish Location</th>

<td>

<input
    type="text"
    name="finish_location"
    class="regular-text"
    value="<?php echo esc_attr($course->finish_location); ?>">

</td>

</tr>

<tr>

<th>Latitude</th>

<td>

<input
    type="number"
    step="0.00000001"
    name="finish_latitude"
    value="<?php echo esc_attr($course->finish_latitude); ?>">

</td>

</tr>

<tr>

<th>Longitude</th>

<td>

<input
    type="number"
    step="0.00000001"
    name="finish_longitude"
    value="<?php echo esc_attr($course->finish_longitude); ?>">

</td>

</tr>

</table>

<hr>

<h2>Notes</h2>

<table class="form-table">

<tr>

<th>Notes</th>

<td>

<textarea
    name="notes"
    rows="8"
    class="large-text"><?php echo esc_textarea($course->notes); ?></textarea>

</td>

</tr>

</table>

<?php submit_button('Update Course'); ?>

</form>

</div>