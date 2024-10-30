<?php
/** Recruiting Scores Form for Councils and Districts Page
 * @Package			Knights of Columbus State WP Plugin
 * @File				views/admin/scores_coun_dist.php
 * @Author			Chris Hood (http://chrishood.me)
 * @Link				http://onthegridwebdesign.com
 * @copyright		(c) 2016-2022, On the Grid Web Design LLC
 * @created			7/23/16
*/
?>

<div class="wrap">
	<h2>KofC Scoreboards: District & Council Scores</h2>
	<?php otgkofcs_display_messages($message_list); ?>

	<form method="post" class="otgkofcs_form1">
		<?php wp_nonce_field('scores_coun_dist'); ?>

		<h3 id="councils">Councils <input type="submit" class="button-primary" value="Save"></h3>
		<div class="otgkofcs_score_input_header">
			<div>
				<label>Council</label>
				<span class="otgkofcs_column_header1">Recruits</span>
				<span class="otgkofcs_column_header1">Membership</span>
			</div>
		</div>
		
		<section id="otgkofcs_councils_sections" class="otgkofcs_score_input">
			<?php foreach ($council_list as $council_id => $council) {
				if (empty($council_scores[$council_id]))
					$council_scores[$council_id] = ['percentage' => 0, 'score' => 0, 'membership' => ''];
				?>
			<p>
				<label for="c_score_<?= $council_id ?>">
						<?= $council_id ?> (<?= number_format($council_scores[$council_id]['percentage'], 1) ?>%)
				</label>
				<input type="number" name="c_score[<?= $council_id ?>]" min="0" value="<?= $council_scores[$council_id]['score'] ?>" class="otgkofcs_input_small">
				<input type="number" name="membership[<?= $council_id ?>]" min="0" value="<?= $council_scores[$council_id]['membership'] ?>" class="otgkofcs_input_small">
			</p>
			<?php } ?>
		</section>	

		<hr>

		<h3 id="districts">Districts <input type="submit" class="button-primary" value="Save"></h3>
		<div>
			<span class="otgkofcs_column_header1">District</span>
			<span class="otgkofcs_column_header1">Recruits</span>
			<span class="otgkofcs_column_header1">District Deputy</span>
		</div>
		
		<section id="otgkofcs_districts_sections">
		<?php for ($i=1; $i<get_option('otgkofcs_number_of_districts')+1; $i++) { 
			if (empty($district_list[$i]['score'])) $district_list[$i]['score'] = 0;
			if (empty($district_list[$i]['deputy'])) $district_list[$i]['deputy'] = '';
			?>
			<p class="otgkofcs_full">
				<span class="otgkofcs_column_header1"><?= $i ?></span>
				<input type="number" name="d_score[<?= $i ?>]" min="0" value="<?= $district_list[$i]['score'] ?>" class="otgkofcs_input_small">
				<input type="text" name="deputy[<?= $i ?>]" value="<?= $district_list[$i]['deputy'] ?>">
			</p>
		<?php } ?>
		</section>	

		<p>
			<input type="submit" class="button-primary" value="Save">
		</p>
			
	</form>
	
</div>