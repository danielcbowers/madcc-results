<?php

if (!defined('ABSPATH')) {
    exit;
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$rider = mcc_get_rider($id);

if (!$rider) {
    wp_die('Rider not found.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    check_admin_referer('mcc_edit_rider');

        mcc_update_rider(
        sanitize_text_field($_POST['first_name']),
        sanitize_text_field($_POST['last_name']),
        sanitize_email($_POST['email']),
        sanitize_text_field($_POST['club']),
        sanitize_text_field($_POST['category']),
        isset($_POST['active'])
    );

    echo '<div class="notice notice-success"><p>Rider updated successfully.</p></div>';
}

?>

<div class="wrap">

    <h1>Edit Rider</h1>

    <form method="post">

        <?php wp_nonce_field('mcc_edit_rider'); ?>

        <table class="form-table">

            <tr>
                <th>First Name</th>
                <td>
                    <input
                        type="text"
                        name="first_name"
                        class="regular-text"
                        value="<?php echo esc_attr($rider->first_name); ?>"
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
                        value="<?php echo esc_attr($rider->last_name); ?>"
                        required>
                </td>
            </tr>

            <tr>
                <th>Email</th>
                <td>
                    <input
                        type="email"
                        name="email"
                        class="regular-text"
                        value="<?php echo esc_attr($rider->email); ?>">
                </td>
            </tr>

            <tr>
                <th>Club</th>
                <td>
                    <input
                        type="text"
                        name="club"
                        class="regular-text"
                        value="<?php echo esc_attr($rider->club); ?>"
                        required>
                </td>
            </tr>

            <tr>
                <th>Category</th>
                <td>
                    <select name="category">
                        <option value="">Select Category</option>

                        <option value="Senior" <?php selected($rider->category ?? '', 'Senior'); ?>>Senior</option>
                        <option value="Veteran" <?php selected($rider->category ?? '', 'Veteran'); ?>>Veteran</option>
                        <option value="Junior" <?php selected($rider->category ?? '', 'Junior'); ?>>Junior</option>
                        <option value="Juvenile" <?php selected($rider->category ?? '', 'Juvenile'); ?>>Juvenile</option>
                        <option value="Female" <?php selected($rider->category ?? '', 'Female'); ?>>Female</option>
                        <option value="Espoir" <?php selected($rider->category ?? '', 'Espoir'); ?>>Espoir</option>
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
                            <?php checked($rider->active, 1); ?>>
                        Active
                    </label>
                </td>
            </tr>

        </table>

        <?php submit_button('Update Rider'); ?>

    </form>

</div>