<?php
/** District Scores Enter Page
 * @Package			Knights of Columbus State WP Plugin
 * @File				views/admin/star_scores.php
 * @Author			Chris Hood (http://chrishood.me)
 * @Link				http://onthegridwebdesign.com
 * @copyright		(c) 2016-2022, On the Grid Web Design LLC
 * @created			12/15/16
*/
?>

<div class="wrap">
	<h2>KofC Scoreboards: Star Council</h2>
	<?php otgkofcs_display_messages($message_list); ?>
	
	<section class="otgkofcs_admin700">
		<form method="post" class="otgkofcs_form1">
			<?php wp_nonce_field('star_scores'); ?>
			
			<table class="otgkofcs_table_star_scores_header" style="top: 32px;">
				<tr>
					<th></th>
					<?php foreach ($star_reqs_list as $star_req) {
						echo '<th><div><span>' . $star_req['name'] . '</span></div></th>';
					} ?>
				</tr>
			</table>				
			<table class="otgkofcs_table_star_scores">
			<?php foreach ($councils_list as $council_id => $council) { ?>
				<tr>
					<td><?= $council_id ?></td>
			<?php foreach ($star_reqs_list as $star_req) { ?>
					<td><input type="checkbox" name="starreq_<?= $council_id ?>[<?= $star_req['star_req_id'] ?>]"
							<?php if (isset($star_reqs_met_list[$council_id]) && in_array($star_req['star_req_id'], $star_reqs_met_list[$council_id])) echo 'checked="checked"'; ?>
							></td>
			<?php } ?>
				</tr>
			<?php } ?>
			</table>
			
			<p style="text-align: center;">
				<input type="submit" class="button-primary" value="Save">
			</p>

		</form>
	</section>	
	
</div>