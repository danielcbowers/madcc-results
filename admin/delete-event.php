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

    check_admin_referer('mcc_delete_event');

    mcc_delete_event($id);

    mcc_success_notice('Event deleted successfully.');

    echo '<p><a href="' . esc_url(admin_url('admin.php?page=mcc-events')) . '">Return to Events</a></p>';

    return;
}

?>

<div class="wrap">

<h1>Delete Event</h1>

<p>Are you sure you want to delete this event?</p>

<table class="widefat striped" style="max-width:700px; margin-bottom:20px;">

<tr>
    <th style="width:200px;">Event</th>
    <td><?php echo esc_html($event->event_name); ?></td>
</tr>

<tr>
    <th>Date</th>
    <td><?php echo esc_html($event->event_date); ?></td>
</tr>

<tr>
    <th>Status</th>
    <td><?php echo esc_html($event->status); ?></td>
</tr>

</table>

<form method="post">

    <?php wp_nonce_field('mcc_delete_event'); ?>

    <?php submit_button('Delete Event', 'delete'); ?>

</form>

<p>

<a href="<?php echo esc_url(admin_url('admin.php?page=mcc-events')); ?>">

Cancel

</a>

</p>

</div>