<?php
global $wpdb;
$wpdb->query($wpdb->prepare("DELETE FROM " . maxab_get_table_name() . " WHERE id = %d", $_GET['id']));
?>
<script type="text/javascript">
	window.location = "<?php bloginfo('wpurl') ?>/wp-admin/admin.php?page=maxab-experiment&action=list";
</script>
