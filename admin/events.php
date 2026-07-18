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

    check_admin_referer('delete_event_' . $id);

    if (mcc_delete_event($id)) {
        mcc_success_notice('Event deleted successfully.');
    } else {
        mcc_error_notice('Unable to delete event.');
    }
}

$events = mcc_get_all_events();

?>

<div class="wrap">

<h1 class="wp-heading-inline">Events</h1>

<a href="<?php echo admin_url('admin.php?page=mcc-add-event'); ?>"
   class="page-title-action">
    Add Event
</a>

<hr class="wp-header-end">

<table class="widefat striped">

    <thead>

        <tr>

            <th>Name</th>
            <th>Date</th>
            <th>TT Course</th>
            <th>Type</th>
            <th>Status</th>
            <th>Actions</th>

        </tr>

    </thead>

    <tbody>

<?php if ($events) : ?>

<?php foreach ($events as $event) : ?>

<tr>

<td><?php echo esc_html($event->event_name); ?></td>

<td><?php echo esc_html($event->event_date); ?></td>

<td>

    <?php
    echo esc_html(
        mcc_get_course_display_name($event->course_id)
    );
    ?>

</td>

<td><?php echo esc_html($event->event_type); ?></td>

<td><?php echo esc_html($event->status); ?></td>

<td>

    <a href="<?php echo admin_url('admin.php?page=mcc-view-event&id=' . $event->id); ?>">
        View
    </a>

    |

    <a href="<?php echo admin_url('admin.php?page=mcc-edit-event&id=' . $event->id); ?>">
        Edit
    </a>

    |

    <a
        href="<?php echo wp_nonce_url(
            admin_url('admin.php?page=mcc-events&action=delete&id=' . $event->id),
            'delete_event_' . $event->id
        ); ?>"
        onclick="return confirm('Delete this event?');">

        Delete

    </a>

</td>

<?php endforeach; ?>

<?php else : ?>

<tr>

<td colspan="4">No events found.</td>

</tr>

<?php endif; ?>

    </tbody>

</table>

</div>