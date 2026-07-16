<?php

if (!defined('ABSPATH')) {
    exit;
}

add_action('admin_enqueue_scripts', 'mcc_admin_styles');

function mcc_admin_styles()
{
    wp_enqueue_style(
        'mcc-admin',
        MCC_RESULTS_URL . 'assets/css/admin.css',
        [],
        MCC_RESULTS_VERSION
    );
}