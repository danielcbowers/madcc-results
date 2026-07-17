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
        floatval($_POST['distance']),
        isset($_POST['active'])
    );

    mcc_success_notice('Course updated successfully.');

    $course = mcc_get_course($id);
}

?>

<div class="wrap">

<h1>Edit Course</h1>

mcc_back_link(
    'mcc-courses',
    'Back to Courses'
);

<form method="post">

<?php wp_nonce_field('mcc_edit_course'); ?>

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

<th>Distance</th>

<td>

<input
type="number"
step="0.1"
name="distance"
value="<?php echo esc_attr($course->distance); ?>"
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

<?php checked($course->active); ?>

>

Active

</label>

</td>

</tr>

</table>

<?php submit_button('Update Course'); ?>

</form>

</div>