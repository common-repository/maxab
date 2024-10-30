<?php
global $wpdb;
$experiments = $wpdb->get_results("SELECT * FROM " . maxab_get_table_name());
$experiments_count = $wpdb->get_var("SELECT COUNT(*) FROM " . maxab_get_table_name());

function maxab_color_variation1_conversion_rate($experiment) {
	if (maxab_display_variation1_conversion_rate($experiment) != 'N/A') {	
		$original_rate = maxab_calculate_original_conversion_rate($experiment);
		$variation1_rate = maxab_calculate_variation1_conversion_rate($experiment);
		return maxab_display_red_or_green_conversion_rate($original_rate, $variation1_rate);
	}
}

function maxab_color_variation2_conversion_rate($experiment) {
	if (maxab_display_variation2_conversion_rate($experiment) != 'N/A') {	
		$original_rate = maxab_calculate_original_conversion_rate($experiment);
		$variation2_rate = maxab_calculate_variation2_conversion_rate($experiment);
		return maxab_display_red_or_green_conversion_rate($original_rate, $variation2_rate);
	}
}

function maxab_color_variation3_conversion_rate($experiment) {
	if (maxab_display_variation3_conversion_rate($experiment) != 'N/A') {	
		$original_rate = maxab_calculate_original_conversion_rate($experiment);
		$variation3_rate = maxab_calculate_variation3_conversion_rate($experiment);
		return maxab_display_red_or_green_conversion_rate($original_rate, $variation3_rate);
	}
}

function maxab_display_red_or_green_conversion_rate($original_rate, $variation_rate) {
	if ($variation_rate < $original_rate) {
		return 'red';
	}
	
	if ($variation_rate > $original_rate) {
		return 'green';
	}
}

function maxab_alt_background($count) {
	if ($count % 2 == 0) {
		return 'alt-background';
	}
}
?>
<div id="maxab">
	<div class="logo">
		<a href="http://maxfoundry.com" target="_blank"><img src="<?php echo MAXAB_PLUGIN_URL ?>/images/max-foundry-logo.png" alt="Max Foundry Logo" /></a>
	</div>

	<h2 class="tabs">
		<span class="spacer"></span>
		<a class="nav-tab nav-tab-active" href="<?php bloginfo('wpurl') ?>/wp-admin/admin.php?page=maxab-experiment&action=list">Experiments</a>
		<a class="nav-tab" href="<?php bloginfo('wpurl') ?>/wp-admin/admin.php?page=maxab-faq">FAQ</a>
	</h2>

	<div class="actions">
		<a class="button-primary" href="<?php bloginfo('wpurl') ?>/wp-admin/admin.php?page=maxab-experiment&action=new">Add New</a>
	</div>
	
	<table class="experiments-table" cellpadding="0" cellspacing="0">
		<tr>
			<td class="column-header-left" valign="bottom"><br />Name</td>
			<td class="column-header" align="center" valign="bottom">Original<br />Conv. Rate</td>
			<td class="column-header" align="center" valign="bottom">Variation 1<br />Conv. Rate</td>
			<td class="column-header" align="center" valign="bottom">Variation 2<br />Conv. Rate</td>
			<td class="column-header" align="center" valign="bottom">Variation 3<br />Conv. Rate</td>
			<td class="column-header" align="center" valign="bottom">Total<br />Visitors</td>
			<td class="column-header-right" align="center" valign="bottom"><br />Status</td>
		</tr>
		<?php if ($experiments_count < 1) { ?>
			<tr>
				<td colspan="7" class="column-data-none-found">No A/B testing experiments found</td>
			</tr>
		<?php } else { ?>
			<?php $count = 1; ?>
			<?php foreach ($experiments as $e) { ?>
				<?php
					$original_conv_rate = ($e->original_visitors == 0) ? 0.0 : ($e->conversion_visitors_from_original / $e->original_visitors) * 100;
				?>
				<tr>
					<td class="column-data-left <?php echo maxab_alt_background($count) ?>"><a href="<?php bloginfo('wpurl') ?>/wp-admin/admin.php?page=maxab-experiment&action=details&id=<?php echo $e->id ?>"><?php echo $e->name ?></a></td>
					<td class="column-data <?php echo maxab_alt_background($count) ?>" align="center"><?php echo maxab_display_original_conversion_rate($e) ?></td>
					<td class="column-data <?php echo maxab_alt_background($count) ?> <?php echo maxab_color_variation1_conversion_rate($e) ?>" align="center"><?php echo maxab_display_variation1_conversion_rate($e) ?></td>
					<td class="column-data <?php echo maxab_alt_background($count) ?> <?php echo maxab_color_variation2_conversion_rate($e) ?>" align="center"><?php echo maxab_display_variation2_conversion_rate($e) ?></td>
					<td class="column-data <?php echo maxab_alt_background($count) ?> <?php echo maxab_color_variation3_conversion_rate($e) ?>" align="center"><?php echo maxab_display_variation3_conversion_rate($e) ?></td>
					<td class="column-data <?php echo maxab_alt_background($count) ?>" align="center"><?php echo $e->total_visitors ?></td>
					<td class="column-data-right status <?php echo maxab_alt_background($count) ?>" align="center"><?php echo $e->status ?></td>
				</tr>
				<?php $count++; ?>
			<?php } ?>
		<?php } ?>
	</table>
</div>
