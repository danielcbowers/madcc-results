<?php

if (!defined('ABSPATH')) {
    exit;
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

mcc_delete_course($id);

?>

<script>

window.location="<?php echo admin_url('admin.php?page=mcc-courses'); ?>";

</script>