<?php
/** List Page
 * @Package			Knights of Columbus State WP Plugin
 * @File				views/scoreboard_knights.php
 * @Author			Chris Hood (http://chrishood.me)
 * @Link				http://onthegridwebdesign.com
 * @copyright		(c) 2016-2022, On the Grid Web Design LLC
 * @created			7/28/16
 */
?>
<table class="wp-list-table widefat fixed striped posts">
	<thead><tr>
		<th>Knight</th>
		<th>Council</th>
		<th>Recruits</th>
	</tr></thead>

<?php if (!empty($knight_list)) foreach ($knight_list as $knight) { ?>
	<tr>
		<td><?= $knight['name_first'] ?> <?= $knight['name_last'] ?></td>
		<td><?= $knight['council_id'] ?></td>
		<td><?= $knight['score'] ?></td>
	</tr>
	<?php	} ?>
</table>