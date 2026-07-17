<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Display a success notice.
 */
function mcc_success_notice($message)
{
    ?>
    <div class="notice notice-success is-dismissible">
        <p><?php echo esc_html($message); ?></p>
    </div>
    <?php
}

/**
 * Display an error notice.
 */
function mcc_error_notice($message)
{
    ?>
    <div class="notice notice-error">
        <p><?php echo esc_html($message); ?></p>
    </div>
    <?php
}

/**
 * Display a standard back link.
 */
function mcc_back_link($page, $text = 'Back')
{
    ?>
    <p>
        <a href="<?php echo esc_url(admin_url('admin.php?page=' . $page)); ?>">
            ← <?php echo esc_html($text); ?>
        </a>
    </p>
    <?php
}