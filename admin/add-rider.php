<?php

if (!defined('ABSPATH')) {
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    check_admin_referer('mcc_add_rider');

    $bibNumber = intval($_POST['bib_number']);

    if (mcc_bib_number_exists($bibNumber)) {

        mcc_error_notice('That bib number is already assigned.');

    } else {

        mcc_add_rider(
            intval($_POST['bib_number']),
            sanitize_text_field($_POST['first_name']),
            sanitize_text_field($_POST['last_name']),
            sanitize_email($_POST['email']),
            sanitize_text_field($_POST['club']),
            sanitize_text_field($_POST['category']),
            isset($_POST['active'])
        );

        echo '<div class="notice notice-success"><p>Rider saved successfully.</p></div>';

    }
}

?>

<div class="wrap">

<h1>Add Rider</h1>

<form method="post">

<?php wp_nonce_field('mcc_add_rider'); ?>

<table class="form-table">

<tr>

    <th>Bib Number</th>

    <td>

        <input
            type="number"
            name="bib_number"
            min="1"
            required>

    </td>

</tr>

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

    <th>Club</th>

    <td>

        <input
            type="text"
            name="club"
            class="regular-text"
            value="Maldon CC"
            required>

    </td>

</tr>

<tr>

    <th>Category</th>

    <td>

        <select name="category">

            <option value="">Select Category</option>
            <option value="Senior">Senior</option>
            <option value="Veteran">Veteran</option>
            <option value="Junior">Junior</option>
            <option value="Juvenile">Juvenile</option>
            <option value="Female">Female</option>
            <option value="Espoir">Espoir</option>

        </select>

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