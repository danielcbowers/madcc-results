<?php

global $wpdb;

$table = $wpdb->prefix . 'mcc_riders';

$count = $wpdb->get_var("SELECT COUNT(*) FROM $table");

?>

<div class="wrap">

<h1>Riders</h1>

<p>Database connection successful ✅</p>

<p>Table:
<strong><?php echo esc_html($table); ?></strong></p>

<p>Rider count:
<strong><?php echo esc_html($count); ?></strong></p>

</div>