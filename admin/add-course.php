<?php

if (!defined('ABSPATH')) {
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    check_admin_referer('mcc_add_course');

    mcc_add_course(

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

    mcc_success_notice('Course saved successfully.');
}

?>

<div class="wrap">

<h1>Add Course</h1>

<form method="post">

<?php wp_nonce_field('mcc_add_course'); ?>

<h2>General</h2>

<table class="form-table">

<tr>

<th>Course Code</th>

<td>

<input
    type="text"
    name="course_code"
    class="regular-text"
    required>

</td>

</tr>

<tr>

<th>Course Name</th>

<td>

<input
    type="text"
    name="course_name"
    class="regular-text"
    required>

</td>

</tr>

<tr>

<th>Course Type</th>

<td>

<select name="course_type">

    <option value="TT">Time Trial</option>
    <option value="Training">Training Ride</option>
    <option value="Reliability Ride">Reliability Ride</option>
    <option value="Open 10">Open 10</option>
    <option value="Open 25">Open 25</option>
    <option value="Open 50">Open 50</option>
    <option value="Hilly">Hilly</option>

</select>

</td>

</tr>

<tr>

<th>Distance (Miles)</th>

<td>

<input
    type="number"
    step="0.1"
    name="distance">

</td>

</tr>

<tr>

<th>Description</th>

<td>

<textarea
    name="description"
    rows="5"
    class="large-text"></textarea>

</td>

</tr>

<tr>

<th>Active</th>

<td>

<label>

<input
    type="checkbox"
    name="active"
    checked>

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
    class="regular-text">

</td>

</tr>

<tr>

<th>Latitude</th>

<td>

<input
    type="number"
    step="0.00000001"
    name="start_latitude">

</td>

</tr>

<tr>

<th>Longitude</th>

<td>

<input
    type="number"
    step="0.00000001"
    name="start_longitude">

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
    class="regular-text">

</td>

</tr>

<tr>

<th>Latitude</th>

<td>

<input
    type="number"
    step="0.00000001"
    name="finish_latitude">

</td>

</tr>

<tr>

<th>Longitude</th>

<td>

<input
    type="number"
    step="0.00000001"
    name="finish_longitude">

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
    class="large-text"></textarea>

</td>

</tr>

</table>

<?php submit_button('Save Course'); ?>

</form>

</div>