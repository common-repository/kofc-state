<?php
/** View: Star Councils Frontend
 * @Package			Knights of Columbus State WP Plugin
 * @File				views/scoreboard_star_reqs.php
 * @Author			Chris Hood (http://chrishood.me)
 * @Link				http://onthegridwebdesign.com
 * @copyright		(c) 2016-2018, On the Grid Web Design LLC
 * @created			12/15/16
*/
$offset = get_option('otgkofcs_star_table_top_offset');
if (is_admin_bar_showing()) $offset += 32;
?>

<table class="otgkofcs_table_star_scores_header" style="top: <?= $offset ?>px;">
	<tr style="background: white;">
		<th></th>
	<?php
		$i = 1;
		foreach ($star_reqs_list as $star_req) {
	?>
			<th<?php if (1 == $i) echo ' style="background: white;"';?>><div><span><?= $star_req['name'] ?></span></div></th>
	<?php
		$i++;
		}
	?>
	</tr>
</table>				

<table class="otgkofcs_table_star_scores">
<?php foreach ($councils_list as $council_id => $council) { ?>
	<tr>
		<td><a href="/council/<?= $council_id ?>"><?= $council_id ?></td>
	<?php foreach ($star_reqs_list as $star_req) { ?>
		<td>
		<?php if (isset($star_reqs_met_list[$council_id]) && in_array($star_req['star_req_id'], $star_reqs_met_list[$council_id])) echo '&#x2714;'; ?>
		</td>
	<?php } ?>
	</tr>
<?php } ?>
</table>