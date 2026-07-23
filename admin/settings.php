<?php

if (!defined('ABSPATH')) {
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    check_admin_referer('mcc_save_settings');

    update_option(
        'mcc_spond_email',
        sanitize_email($_POST['spond_email'])
    );

    update_option(
        'mcc_spond_password',
        sanitize_text_field($_POST['spond_password'])
    );

    echo '<div class="notice notice-success"><p>Settings saved successfully.</p></div>';
}

if (isset($_POST['test_spond'])) {

    check_admin_referer('mcc_save_settings');

    $spond = new MCC_SpondService();

    $result = $spond->login();

    if (is_wp_error($result)) {

        echo '<div class="notice notice-error"><p>';
        echo esc_html($result->get_error_message());
        echo '</p></div>';

    } else {

        echo '<div class="notice notice-success"><p>';
        echo 'Successfully connected to Spond!';
        echo '</p></div>';

    }
}

?>

<div class="wrap">

    <h1>MCC Settings</h1>

    <form method="post">

        <?php wp_nonce_field('mcc_save_settings'); ?>

        <table class="form-table">

            <tr>
                <th scope="row">
                    <label for="spond_email">Spond Email</label>
                </th>
                <td>
                    <input
                        id="spond_email"
                        type="email"
                        name="spond_email"
                        class="regular-text"
                        value="<?php echo esc_attr(get_option('mcc_spond_email', '')); ?>">
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="spond_password">Spond Password</label>
                </th>
                <td>
                    <input
                        id="spond_password"
                        type="password"
                        name="spond_password"
                        class="regular-text"
                        value="<?php echo esc_attr(get_option('mcc_spond_password', '')); ?>">
                </td>
            </tr>

        </table>

        <?php submit_button('Save Settings'); ?>

        <p>
            <input
                type="submit"
                name="test_spond"
                class="button button-secondary"
                value="Test Spond Connection">
        </p>

    </form>

</div>