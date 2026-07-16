<?php

if (!defined('ABSPATH')) {
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    check_admin_referer('mcc_add_rider');

    mcc_add_rider(
        sanitize_text_field($_POST['first_name']),
        sanitize_text_field($_POST['last_name']),
        sanitize_email($_POST['email']),
        isset($_POST['active'])
    );

    echo '<div class="notice notice-success"><p>Rider saved successfully.</p></div>';
}

?>

<div class="wrap">

<h1>Add Rider</h1>

<form method="post">

<?php wp_nonce_field('mcc_add_rider'); ?>

<table class="form-table">

<tr>

<th>First Name</th>

<td>

<input
type="text"
name="first_name"
class="regular-text"
required>

</td>

</tr>

<tr>

<th>Last Name</th>

<td>

<input
type="text"
name="last_name"
class="regular-text"
required>

</td>

</tr>

<tr>

<th>Email</th>

<td>

<input
type="email"
name="email"
class="regular-text">

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

<?php submit_button('Save Rider'); ?>

</form>

</div>