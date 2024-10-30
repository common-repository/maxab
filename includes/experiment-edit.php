<?php
global $wpdb;

$id = $_POST['id'];
$name = $_POST['name'];
$status = $_POST['status'];
$end_criteria = $_POST['end_criteria'];
$traffic_threshold = $_POST['traffic_threshold'];
$page_threshold = $_POST['page_threshold'];

if ($end_criteria == 'manual') {
	$traffic_threshold = 0;
	$page_threshold = 0;
}

if ($end_criteria == 'traffic_threshold') {
	$page_threshold = 0;
}

if ($end_criteria == 'page_threshold') {
	$traffic_threshold = 0;
}

$data = array('name' => $wpdb->escape($name), 'status' => $status, 'end_criteria' => $end_criteria, 'traffic_threshold' => $traffic_threshold, 'page_threshold' => $page_threshold);
$where = array('id' => $id);
$data_format = array('%s', '%s', '%s', '%d', '%d');
$where_format = array('%d');
$wpdb->update(maxab_get_table_name(), $data, $where, $data_format, $where_format);
?>
<script type="text/javascript">
	window.location = "<?php bloginfo('wpurl') ?>/wp-admin/admin.php?page=maxab-experiment&action=details&id=<?php echo $id ?>";
</script>
