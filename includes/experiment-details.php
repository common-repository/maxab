<?php
global $wpdb;
$experiment = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . maxab_get_table_name() . " WHERE id = %d", $_GET['id']));

function maxab_display_end_criteria($end_criteria, $traffic_threshold, $page_threshold) {
	switch ($end_criteria) {
		case 'manual':
			return 'It is manually stopped';
			break;
		case 'traffic_threshold':
			return 'Total traffic reaches ' . $traffic_threshold . ' visitors';
			break;
		case 'page_threshold':
			return 'Each page reaches at least ' . $page_threshold . ' visitors';
			break;
	}
}

function maxab_color_variation1_improvement($experiment) {
	// No need to check variation1_url because we should always have at least 1 variation
	$improvement = maxab_calculate_variation1_improvement($experiment);
	return maxab_display_red_or_green_improvement($improvement);
}

function maxab_color_variation2_improvement($experiment) {
	if ($experiment->variation2_url != '') {
		$improvement = maxab_calculate_variation2_improvement($experiment);
		return maxab_display_red_or_green_improvement($improvement);
	}
}

function maxab_color_variation3_improvement($experiment) {
	if ($experiment->variation3_url != '') {
		$improvement = maxab_calculate_variation3_improvement($experiment);
		return maxab_display_red_or_green_improvement($improvement);
	}
}

function maxab_display_red_or_green_improvement($improvement) {
	if ($improvement < 0) {
		return 'red';
	}
	
	if ($improvement > 0) {
		return 'green';
	}
}

$variation1_z_score = maxab_calculate_variation1_z_score($experiment);
$variation2_z_score = maxab_calculate_variation2_z_score($experiment);
$variation3_z_score = maxab_calculate_variation3_z_score($experiment);

$variation1_p_value = maxab_calculate_normal_distribution($variation1_z_score, 0, 1, true);
$variation2_p_value = maxab_calculate_normal_distribution($variation2_z_score, 0, 1, true);
$variation3_p_value = maxab_calculate_normal_distribution($variation3_z_score, 0, 1, true);

function maxab_display_95_percent_confidence_icon($p_value) {
	$is_95_pct_confident = maxab_has_p_value_reached_95_percent_confidence($p_value);
	return maxab_display_confidence_icon($is_95_pct_confident);
}

function maxab_display_99_percent_confidence_icon($p_value) {
	$is_99_pct_confident = maxab_has_p_value_reached_99_percent_confidence($p_value);
	return maxab_display_confidence_icon($is_99_pct_confident);
}

function maxab_display_confidence_icon($is_confident) {
	if ($is_confident) {
		return '<img src="' . MAXAB_PLUGIN_URL . '/images/green-check.png" alt="" />';
	}
	else {
		return '<img src="' . MAXAB_PLUGIN_URL . '/images/red-minus.png" alt="" />';
	}
}

function maxab_display_statistically_significant_icon($p_value) {	
	$is_significant = maxab_is_p_value_statistically_significant($p_value);
	
	if ($is_significant) {
		return '<img src="' . MAXAB_PLUGIN_URL . '/images/green-check.png" alt="" />';
	}
	else {
		return '<img src="' . MAXAB_PLUGIN_URL . '/images/red-minus.png" alt="" />';
	}
}
?>
<div id="maxab">
	<div class="logo">
		<a href="http://maxfoundry.com" target="_blank"><img src="<?php echo MAXAB_PLUGIN_URL ?>/images/max-foundry-logo.png" alt="Max Foundry Logo" /></a>
	</div>

	<h2 class="tabs">
		<span class="spacer"></span>
		<a class="nav-tab nav-tab-active" href="<?php bloginfo('wpurl') ?>/wp-admin/admin.php?page=maxab-experiment&action=details&id=<?php echo $experiment->id ?>">Experiment Details</a>
	</h2>
	
	<?php if (!isset($experiment->id)) { ?>
		<p>There is no experiment with that ID.</p>
		<p><a href="<?php bloginfo('wpurl') ?>/wp-admin/admin.php?page=maxab-experiment&action=list">Back to List</a></p>
	<?php } else { ?>
		<script type="text/javascript">
			jQuery(document).ready(function() {			
				jQuery('#edit-button').click(function() {
					jQuery('#edit-experiment-modal').modal();
					jQuery('#simplemodal-container').css('height', '360px');
					jQuery('#simplemodal-container').css('width', '450px');
					return false;
				});
				
				jQuery('#delete-button').click(function() {
					jQuery('#delete-experiment-modal').modal();
					jQuery('#simplemodal-container').css('height', '160px');
					jQuery('#simplemodal-container').css('width', '450px');
					return false;
				});
				
				jQuery('#tracking-script-button').click(function() {
					jQuery('#tracking-script-modal').modal();
					jQuery('#simplemodal-container').css('height', '240px');
					jQuery('#simplemodal-container').css('width', '520px');
					return false;
				});
				
				jQuery('#save-cancel-button').click(function() {
					jQuery.modal.close();
				});
				
				jQuery('#delete-no-button').click(function() {
					jQuery.modal.close();
				});
				
				jQuery('#tracking-script-close-button').click(function() {
					jQuery.modal.close();
				});
				
				jQuery("#edit-experiment-form").validate({
					messages: {
						name: "",
						status: "",
						traffic_threshold: "",
						page_threshold: ""
					},
					rules: {
						traffic_threshold: {
							required: function() {
								return jQuery("input:radio[name=end_criteria]:checked").val() == 'traffic_threshold';
							},
							digits: function() {
								return jQuery("input:radio[name=end_criteria]:checked").val() == 'traffic_threshold';
							}
						},
						page_threshold: {
							required: function() {
								return jQuery("input:radio[name=end_criteria]:checked").val() == 'page_threshold';
							},
							digits: function() {
								return jQuery("input:radio[name=end_criteria]:checked").val() == 'page_threshold';
							}
						}
					}
				});
				
				jQuery("#save-experiment-button").click(function() {					
					if (jQuery("#edit-experiment-form").valid()) {
						jQuery("#edit-experiment-form").submit();
					}
					
					return false;
				});
			});
			
			function selectTrafficThreshold() {
				jQuery("#traffic-threshold-end-radio").attr("checked", "checked");
				jQuery("#page-threshold").val("");
				return false;
			}
			
			function selectPageThreshold() {
				jQuery("#page-threshold-end-radio").attr("checked", "checked");
				jQuery("#traffic-threshold").val("");
				return false;
			}
		</script>
		
		<div class="actions">
			<a class="button" href="<?php bloginfo('wpurl') ?>/wp-admin/admin.php?page=maxab-experiment&action=list">&laquo; Back to List</a>
			<a id="edit-button" class="button-primary" href="#">Edit</a>
			<a id="delete-button" class="button-primary" href="#">Delete</a>
			
			<?php if ($experiment->conversion_id == -1) { ?>
				<a id="tracking-script-button" class="button-primary" href="#">Tracking Script</a>
			<?php } ?>
		</div>
		
		<div class="details">
			<h3><?php echo $experiment->name ?></h3>
			<div class="info">
				<div class="label">Status:</div>
				<div class="value status"><?php echo $experiment->status ?></div>
				<div class="clear"></div>
				<div class="label">Ends when:</div>
				<div class="value"><?php echo maxab_display_end_criteria($experiment->end_criteria, $experiment->traffic_threshold, $experiment->page_threshold) ?></div>
				<div class="clear"></div>
				<div class="label">Visitor distribution:</div>
				<div class="value">Equal across all pages</div>
				<div class="clear"></div>
				<div class="label">Created:</div>
				<div class="value"><?php echo date('M j, Y', strtotime($experiment->date_created)) ?></div>
				<div class="clear"></div>
			</div>
			<div class="info">
				<div class="label">Original page:</div>
				<div class="value"><a href="<?php echo $experiment->original_url ?>" target="_blank"><?php echo $experiment->original_url ?></a></div>
				<div class="clear"></div>
				<div class="label">Variation page 1:</div>
				<div class="value"><a href="<?php echo $experiment->variation1_url ?>" target="_blank"><?php echo $experiment->variation1_url ?></a></div>
				<div class="clear"></div>
				<div class="label">Variation page 2:</div>
				<div class="value"><?php echo ($experiment->variation2_url != '') ? '<a href="' . $experiment->variation2_url . '" target="_blank">' . $experiment->variation2_url . '</a>' : 'N/A' ?></div>
				<div class="clear"></div>
				<div class="label">Variation page 3:</div>
				<div class="value"><?php echo ($experiment->variation3_url != '') ? '<a href="' . $experiment->variation3_url . '" target="_blank">' . $experiment->variation3_url . '</a>' : 'N/A' ?></div>
				<div class="clear"></div>
				<div class="label">Conversion page:</div>
				<div class="value"><a href="<?php echo $experiment->conversion_url ?>" target="_blank"><?php echo $experiment->conversion_url ?></a></div>
				<div class="clear"></div>
			</div>
			<div class="info">
				<div class="label">Total visitors:</div>
				<div class="value-total-visitors"><?php echo $experiment->total_visitors ?></div>
				<div class="clear"></div>
			</div>
			
			<div class="compare">
				<table cellpadding="0" cellspacing="0">
					<tr>
						<td align="center" class="details-header">&nbsp;</td>
						<td align="center" class="details-header">Original</td>
						<td align="center" class="details-header">Variation 1</td>
						<td align="center" class="details-header">Variation 2</td>
						<td align="center" class="details-header">Variation 3</td>
						</td>
					</tr>
					<tr>
						<td colspan="5" class="section-header">Conversion Metrics</td>
					<tr>
					<tr>
						<td class="details-label">Visitors</td>
						<td align="center" class="details-value"><?php echo $experiment->original_visitors ?></td>
						<td align="center" class="details-value"><?php echo $experiment->variation1_visitors ?></td>
						<td align="center" class="details-value"><?php echo ($experiment->variation2_url != '') ? $experiment->variation2_visitors : 'N/A' ?></td>
						<td align="center" class="details-value"><?php echo ($experiment->variation3_url != '') ? $experiment->variation3_visitors : 'N/A' ?></td>
					</tr>
					<tr>
						<td class="details-label">Conversions</td>
						<td align="center" class="details-value"><?php echo $experiment->conversion_visitors_from_original ?></td>
						<td align="center" class="details-value"><?php echo $experiment->conversion_visitors_from_variation1 ?></td>
						<td align="center" class="details-value"><?php echo ($experiment->variation2_url != '') ? $experiment->conversion_visitors_from_variation2 : 'N/A' ?></td>
						<td align="center" class="details-value"><?php echo ($experiment->variation3_url != '') ? $experiment->conversion_visitors_from_variation3 : 'N/A' ?></td>
					</tr>
					<tr>
						<td class="details-label">Conversion Rate</td>
						<td align="center" class="details-value"><?php echo maxab_display_original_conversion_rate($experiment) ?></td>
						<td align="center" class="details-value"><?php echo maxab_display_variation1_conversion_rate($experiment) ?></td>
						<td align="center" class="details-value"><?php echo maxab_display_variation2_conversion_rate($experiment) ?></td>
						<td align="center" class="details-value"><?php echo maxab_display_variation3_conversion_rate($experiment) ?></td>
					</tr>
					<tr>
						<td class="details-label">Improvement</td>
						<td align="center" class="details-value">&nbsp;</td>
						<td align="center" class="details-value <?php echo maxab_color_variation1_improvement($experiment) ?>"><?php echo maxab_display_variation1_improvement($experiment) ?></td>
						<td align="center" class="details-value <?php echo maxab_color_variation2_improvement($experiment) ?>"><?php echo maxab_display_variation2_improvement($experiment) ?></td>
						<td align="center" class="details-value <?php echo maxab_color_variation3_improvement($experiment) ?>"><?php echo maxab_display_variation3_improvement($experiment) ?></td>
					</tr>
					<tr>
						<td colspan="5" class="section-header">Statistical Significance</td>
					<tr>
					<tr>
						<td class="details-label">Statistical Confidence</td>
						<td align="center" class="details-value">&nbsp;</td>
						<td align="center" class="details-value"><?php echo maxab_display_statistical_confidence($variation1_p_value) ?></td>
						<td align="center" class="details-value"><?php echo maxab_display_statistical_confidence($variation2_p_value) ?></td>
						<td align="center" class="details-value"><?php echo maxab_display_statistical_confidence($variation3_p_value) ?></td>
					</tr>
					<tr>
						<td class="details-label">95% Confidence</td>
						<td align="center" class="details-value">&nbsp;</td>
						<td align="center" class="details-value"><?php echo maxab_display_95_percent_confidence_icon($variation1_p_value) ?></td>
						<td align="center" class="details-value"><?php echo maxab_display_95_percent_confidence_icon($variation2_p_value) ?></td>
						<td align="center" class="details-value"><?php echo maxab_display_95_percent_confidence_icon($variation3_p_value) ?></td>
					</tr>
					<tr>
						<td class="details-label">99% Confidence</td>
						<td align="center" class="details-value">&nbsp;</td>
						<td align="center" class="details-value"><?php echo maxab_display_99_percent_confidence_icon($variation1_p_value) ?></td>
						<td align="center" class="details-value"><?php echo maxab_display_99_percent_confidence_icon($variation2_p_value) ?></td>
						<td align="center" class="details-value"><?php echo maxab_display_99_percent_confidence_icon($variation3_p_value) ?></td>
					</tr>
					<tr>
						<td class="details-label">Statistically Significant</td>
						<td align="center" class="details-value">&nbsp;</td>
						<td align="center" class="details-value"><?php echo maxab_display_statistically_significant_icon($variation1_p_value) ?></td>
						<td align="center" class="details-value"><?php echo maxab_display_statistically_significant_icon($variation2_p_value) ?></td>
						<td align="center" class="details-value"><?php echo maxab_display_statistically_significant_icon($variation3_p_value) ?></td>
					</tr>
				</table>
			</div>
		</div>
		
		<div id="delete-experiment-modal">
			<div class="title">Delete Experiment</div>
			<p>Are you sure you want to delete this experiment?</p>
			<p>Doing so deletes all data for this experiment from the database.</p>
			<div class="button-group">
				<span><a href="<?php bloginfo('wpurl') ?>/wp-admin/admin.php?page=maxab-experiment&action=delete&id=<?php echo $experiment->id ?>" class="button-primary">Yes</a></span>
				<a id="delete-no-button" href="#" class="button">No</a>
			</div>
		</div>
		
		<div id="edit-experiment-modal">
			<div class="title">Edit Experiment</div>
			<form id="edit-experiment-form" action="<?php bloginfo('wpurl') ?>/wp-admin/admin.php?page=maxab-experiment&action=edit" method="post">
				<div class="input-group">
					<div class="label">Name</div>
					<div><input type="text" name="name" maxlength="250" class="required" value="<?php echo $experiment->name ?>" /></div>
				</div>
				<div class="input-group">
					<div class="label">Status</div>
					<div>
						<select name="status">
							<option value='running' <?php echo ($experiment->status == 'running') ? 'selected' : '' ?>>Running</option>
							<option value='paused' <?php echo ($experiment->status == 'paused') ? 'selected' : '' ?>>Paused</option>
						</select>
					</div>
				</div>
				<div class="radio-group">
					<div class="label">Experiment ends when</div>
					<div>
						<input type="radio" name="end_criteria" id="manual-end-radio" value="manual" <?php echo ($experiment->end_criteria == 'manual') ? 'checked="checked"' : '' ?> /><label for="manual-end-radio">It is manually stopped</label><br />
						<input type="radio" name="end_criteria" id="traffic-threshold-end-radio" value="traffic_threshold" <?php echo ($experiment->end_criteria == 'traffic_threshold') ? 'checked="checked"' : '' ?> /><label for="traffic-threshold-end-radio">Total traffic reaches</label> <input class="short" type="text" id="traffic-threshold" name="traffic_threshold" maxlength="5" onfocus="selectTrafficThreshold();" value="<?php echo ($experiment->end_criteria == 'traffic_threshold') ? $experiment->traffic_threshold : '' ?>" /> <label>visitors</label><br />
						<input type="radio" name="end_criteria" id="page-threshold-end-radio" value="page_threshold" <?php echo ($experiment->end_criteria == 'page_threshold') ? 'checked="checked"' : '' ?> /><label for="page-threshold-end-radio">Each page reaches at least</label> <input class="short" type="text" id="page-threshold" name="page_threshold" maxlength="5" onfocus="selectPageThreshold();" value="<?php echo ($experiment->end_criteria == 'page_threshold') ? $experiment->page_threshold : '' ?>" /> <label>visitors</label><br />
					</div>
				</div>
				<div class="button-group">
					<span><a id="save-experiment-button" class="button-primary">Save</a></span>
					<a id="save-cancel-button" href="#" class="button">Cancel</a>
				</div>
				<input type="hidden" name="id" value="<?php echo $experiment->id ?>" />
			</form>
		</div>
		
		<?php if ($experiment->conversion_id == -1) { ?>
			<div id="tracking-script-modal">
				<div class="title">Tracking Script</div>
				<p>The conversion page must contain the following tracking script.</p>
				<p>It should be placed just before the closing &lt;/head&gt; tag.</p>
				<textarea rows="3" wrap="off"><?php echo maxab_build_conversion_tracking_script($experiment->conversion_url) ?></textarea>
				<div class="button-group">
					<a id="tracking-script-close-button" href="#" class="button">Close</a>
				</div>
			</div>
		<?php } ?>
	<?php } ?>
</div>
