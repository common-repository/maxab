<?php

?>

<div id="maxab">
	<div class="logo">
		<a href="http://maxfoundry.com" target="_blank"><img src="<?php echo MAXAB_PLUGIN_URL ?>/images/max-foundry-logo.png" alt="Max Foundry Logo" /></a>
	</div>

	<h2 class="tabs">
		<span class="spacer"></span>
		<a class="nav-tab" href="<?php bloginfo('wpurl') ?>/wp-admin/admin.php?page=maxab-experiment&action=list">Experiments</a>
		<a class="nav-tab nav-tab-active" href="<?php bloginfo('wpurl') ?>/wp-admin/admin.php?page=maxab-faq">FAQ</a>
	</h2>
	
	<h3>When should I stop my experiment?</h3>
	<p>You should wait until the experiment has reached at least a 95% confidence level with statistically significant results, and ideally you should wait until the experiment reaches 99% confidence.</p>
	<p>The higher the statistical confidence, the more likely the results will be statistically significant. For sites with high traffic, the time it takes for experiments to reach statistical significance will be much shorter than sites with lower traffic. Making decisions about your experiment results before the proper thresholds have been reached may result is false-positives that lead to lower-than-expected conversion rates.</p>
	
	<h3>How are the metrics defined?</h3>
	<div class="faq-metrics">
		<table border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td class="metric-name" valign="top">Total Visitors</td>
				<td class="metric-definition" valign="top">
					Total number of visitors who have taken part in the experiment. To count as part of the experiment, a visitor must first arrive on the original page of the experiment, at which point they will remain on that page or will be redirected to one of the variation pages.
				</td>
			</tr>
			<tr>
				<td class="metric-name" valign="top">Original Visitors</td>
				<td class="metric-definition" valign="top">
					Total number of visitors who were shown the original page of the experiment.
				</td>
			</tr>
			<tr>
				<td class="metric-name" valign="top">Variation Visitors</td>
				<td class="metric-definition" valign="top">
					Total number of visitors who were shown the variation page of the experiment.
				</td>
			</tr>
			<tr>
				<td class="metric-name" valign="top">Original Conversions</td>
				<td class="metric-definition" valign="top">
					Total number of visitors who reached the conversion page after starting the experiment from the original page.
				</td>
			</tr>
			<tr>
				<td class="metric-name" valign="top">Variation Conversions</td>
				<td class="metric-definition" valign="top">
					Total number of visitors who reached the conversion page after starting the experiment from the variation page.
				</td>
			</tr>
			<tr>
				<td class="metric-name" valign="top">Original Conversion Rate</td>
				<td class="metric-definition" valign="top">
					Percentage of visitors who reached the conversion page after starting the experiment from the original page.
				</td>
			</tr>
			<tr>
				<td class="metric-name" valign="top">Variation Conversion Rate</td>
				<td class="metric-definition" valign="top">
					Percentage of visitors who reached the conversion page after starting the experiment from the variation page.
				</td>
			</tr>
			<tr>
				<td class="metric-name" valign="top">Variation Improvement</td>
				<td class="metric-definition" valign="top">
					Percentage of improvement of the variation conversion rate compared to the original conversion rate. Can be positive or negative.
				</td>
			</tr>
			<tr>
				<td class="metric-name" valign="top">Statistical Confidence</td>
				<td class="metric-definition" valign="top">
					Confidence level of the variation results. For example, a 96% statistical confidence means that the experiment is 96% sure that the metrics are statistically significant for the variation.
				</td>
			</tr>
			<tr>
				<td class="metric-name" valign="top">95% Confidence</td>
				<td class="metric-definition" valign="top">
					Indicates whether or not the variation results have at least a 95% confidence level.
				</td>
			</tr>
			<tr>
				<td class="metric-name" valign="top">99% Confidence</td>
				<td class="metric-definition" valign="top">
					Indicates whether or not the variation results have at least a 99% confidence level.
				</td>
			</tr>
			<tr>
				<td class="metric-name" valign="top">Statistically Significant</td>
				<td class="metric-definition" valign="top">
					Indicates whether or not the variation results are statistically significant.
				</td>
			</tr>
		</table>
	</div>
</div>
