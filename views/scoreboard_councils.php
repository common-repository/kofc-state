<?php
/** List Page
 * @Package			Knights of Columbus State WP Plugin
 * @File				views/scoreboard_councils.php
 * @Author			Chris Hood (http://chrishood.me)
 * @Link				http://onthegridwebdesign.com
 * @copyright		(c) 2016-2018, On the Grid Web Design LLC
 * @created			7/28/16
 */
?>
<table class="wp-list-table widefat fixed striped posts">
	<thead><tr>
		<th>Council</th>
		<th>Score</th>
	</tr></thead>
<?php if (!empty($council_scores)) foreach ($council_scores as $council) {
	if (!isset($councils_list[$council['council_id']])) continue;
	$council_name = $councils_list[$council['council_id']]['name'];
	$percentage_str = number_format($council['percentage'], 2);
	?>
	<tr>
		<td><a href='/council/{$council['council_id']}'><?= $council['council_id'] ?> - <?= $council_name ?></a></td>
		<td><?= $percentage_str ?>%</td>
	</tr>
<?php } ?>
</table>
