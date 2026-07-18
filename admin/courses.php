<?php

if (!defined('ABSPATH')) {
    exit;
}

if (
    isset($_GET['action']) &&
    $_GET['action'] === 'delete' &&
    isset($_GET['id'])
) {
    $id = intval($_GET['id']);

    check_admin_referer('delete_course_' . $id);

    if (mcc_delete_course($id)) {
        mcc_success_notice('Course deleted successfully.');
    } else {
        mcc_error_notice('Unable to delete course.');
    }
}

$courses = mcc_get_all_courses();

?>

<div class="wrap">

<h1 class="wp-heading-inline">Courses</h1>

<a href="<?php echo admin_url('admin.php?page=mcc-add-course'); ?>"
class="page-title-action">

Add Course

</a>

<hr class="wp-header-end">

<table class="widefat striped">

<thead>

<tr>

<th>Code</th>
<th>Name</th>
<th>Distance</th>
<th>Active</th>
<th>Actions</th>

</tr>

</thead>

<tbody>

<?php if ($courses) : ?>

<?php foreach ($courses as $course) : ?>

<tr>

<td><?php echo esc_html($course->course_code); ?></td>

<td><?php echo esc_html($course->course_name); ?></td>

<td><?php echo esc_html($course->distance); ?> miles</td>

<td><?php echo $course->active ? 'Yes' : 'No'; ?></td>

<td>

    <a href="<?php echo admin_url('admin.php?page=mcc-edit-course&id=' . $course->id); ?>">
        Edit
    </a>

    |

    <a
        href="<?php echo wp_nonce_url(
            admin_url('admin.php?page=mcc-courses&action=delete&id=' . $course->id),
            'delete_course_' . $course->id
        ); ?>"
        onclick="return confirm('Delete this course?');">

        Delete

    </a>

</td>

</tr>

<?php endforeach; ?>

<?php else : ?>

<tr>

<td colspan="4">

No courses found.

</td>

</tr>

<?php endif; ?>

</tbody>

</table>

</div>