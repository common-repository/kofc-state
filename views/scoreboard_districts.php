<?php
/** List View
 * @Package			Knights of Columbus State WP Plugin
 * @File				views/scoreboard_districts.php
 * @Author			Chris Hood (http://chrishood.me)
 * @Link				http://onthegridwebdesign.com
 * @copyright		(c) 2016-2022, On the Grid Web Design LLC
 * @created			12/6/16
 */
if (!empty($districts_list)) {
	?>
<table class="wp-list-table widefat fixed striped posts">
	<thead><tr>
		<th>District</th>
		<th>Deputy</th>
		<th>Recruits</th>
		</tr></thead>
	<?php foreach ($districts_list as $district_id => $district) {
	if ($district_id > $num_districts) continue; ?>
	<tr>
		<td><?= $district_id ?></td>
		<td><?= $district['deputy'] ?></td>
		<td><?= $district['score'] ?></td>
	</tr>
	<?php	} ?>
</table>
<?php }