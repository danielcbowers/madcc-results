<?php
if (!defined('ABSPATH')) exit;
global $wpdb;
$dbConnected = $wpdb->check_connection(false);
$spond = new SpondService();
$spondStatus = $spond->testConnection();
$riders=$wpdb->prefix.'mcc_riders';
$events=$wpdb->prefix.'mcc_events';
$results=$wpdb->prefix.'mcc_results';
$rc=(int)$wpdb->get_var("SELECT COUNT(*) FROM {$riders}");
$ec=(int)$wpdb->get_var("SELECT COUNT(*) FROM {$events}");
$resc=(int)$wpdb->get_var("SELECT COUNT(*) FROM {$results}");
$user=wp_get_current_user();
$upcoming=$wpdb->get_results("SELECT event_name,event_date FROM {$events} WHERE event_date>=CURDATE() ORDER BY event_date LIMIT 5");
$recent=$wpdb->get_results("SELECT first_name, last_name FROM {$riders} ORDER BY id DESC LIMIT 5");
?>
<div class="wrap mcc-dashboard-wrap">
<div class="mcc-hero">
<div><h1>🏁 MCC Results</h1><p>Welcome back <strong><?php echo esc_html($user->display_name); ?></strong><br><?php echo esc_html(date_i18n('l j F Y')); ?></p></div>
<div class="mcc-version">Version 1.0.0</div>
</div>
<div class="mcc-stats">
<div class="stat blue"><span>👥 Riders</span><strong><?php echo $rc; ?></strong></div>
<div class="stat green"><span>🏁 Events</span><strong><?php echo $ec; ?></strong></div>
<div class="stat orange"><span>📊 Results</span><strong><?php echo $resc; ?></strong></div>
<div class="stat purple"><span>🏆 Records</span><strong>0</strong></div>
</div>
<div class="mcc-grid">
<div class="card">
<h2>⚡ Quick Actions</h2>
<div class="actions">
<a class="action" href="<?php echo admin_url('admin.php?page=mcc-events&action=add');?>">➕ Add Event</a>
<a class="action" href="<?php echo admin_url('admin.php?page=mcc-riders&action=add');?>">➕ Add Rider</a>
<a class="action" href="<?php echo admin_url('admin.php?page=mcc-results&action=add');?>">➕ Enter Results</a>
<a class="action" href="<?php echo admin_url('admin.php?page=mcc-events');?>">📅 View Events</a>
<a class="action" href="<?php echo admin_url('admin.php?page=mcc-riders');?>">👥 View Riders</a>
<a class="action" href="<?php echo admin_url('admin.php?page=mcc-results');?>">📊 View Results</a>
</div>
</div>
<div class="card">
<h2>✅ System Status</h2>
<ul class="status">

    <?php if ($dbConnected): ?>
        <li>🟢 Database Connected</li>
    <?php else: ?>
        <li>🔴 Database Connection Failed</li>
    <?php endif; ?>

    <?php if (is_wp_error($spondStatus)): ?>
        <li>🔴 Spond: <?php echo esc_html($spondStatus->get_error_message()); ?></li>
    <?php else: ?>
        <li>🟢 Spond Connected</li>
    <?php endif; ?>

    <li>
        👥 <?php echo (int) $wpdb->get_var("SELECT COUNT(*) FROM {$riders} WHERE active = 1"); ?>
        Active Riders
    </li>
</div>
<div class="card">
<h2>📅 Upcoming Events</h2>
<ul><?php foreach($upcoming as $e):?><li><?php echo esc_html(date_i18n('j M Y',strtotime($e->event_date)).' - '.$e->event_name);?></li><?php endforeach;?></ul>
</div>
<div class="card">
<h2>👥 Recent Riders</h2>
<ul><?php foreach($recent as $r):?><li><?php echo esc_html($r->first_name . ' ' . $r->last_name); ?></li><?php endforeach;?></ul>
</div>
</div>
</div>