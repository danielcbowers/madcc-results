<?php

if (!defined('ABSPATH')) {
    exit;
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
        href="<?php echo admin_url('admin.php?page=mcc-delete-course&id=' . $course->id); ?>"
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