<?php

if (!defined('ABSPATH')) {
    exit;
}

$riders = mcc_get_all_riders();

?>

<div class="wrap">

<h1 class="wp-heading-inline">Riders</h1>

<a href="<?php echo admin_url('admin.php?page=mcc-add-rider'); ?>" class="page-title-action">
    Add Rider
</a>

<hr class="wp-header-end">

<?php if(empty($riders)): ?>

<p>No riders found.</p>

<?php else: ?>

<table class="widefat striped">

<thead>

<tr>

<th>Name</th>
<th>Email</th>
<th>Status</th>

</tr>

</thead>

<tbody>

<?php foreach($riders as $rider): ?>

<tr>

<td>

<?php
echo esc_html(
$rider->first_name . ' ' . $rider->last_name
);
?>

</td>

<td>

<?php echo esc_html($rider->email); ?>

</td>

<td>

<?php
echo $rider->active
? 'Active'
: 'Inactive';
?>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

<?php endif; ?>

</div>