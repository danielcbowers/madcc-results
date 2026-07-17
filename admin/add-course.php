<?php

if (!defined('ABSPATH')) {
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    check_admin_referer('mcc_add_course');

    mcc_add_course(
        sanitize_text_field($_POST['course_code']),
        sanitize_text_field($_POST['course_name']),
        floatval($_POST['distance']),
        isset($_POST['active'])
    );

    echo '<div class="notice notice-success"><p>Course saved successfully.</p></div>';
}

?>

<div class="wrap">

<h1>Add Course</h1>

<form method="post">

<?php wp_nonce_field('mcc_add_course'); ?>

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

<th>Distance</th>

<td>

<input
type="number"
step="0.1"
name="distance"
required>

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

<?php submit_button('Save Course'); ?>

</form>

</div>