<?php
/** List Page
 * @Package			Knights of Columbus State WP Plugin
 * @File				views/admin/knights_list.php
 * @Author			Chris Hood (http://chrishood.me)
 * @Link				http://onthegridwebdesign.com
 * @copyright		(c) 2016-2022, On the Grid Web Design LLC
 * @created			7/21/16
*/
?>
<script>
jQuery(document).ready(function () {
	var tableData = [
<?php if (!empty($knights_list)) foreach ($knights_list as $knight) {?>
		[
			'<input type="checkbox" name="bulk_action_list[]" value="<?= $knight['knight_id'] ?>" class="otgkofcs_list_checkbox">',
			'<a href="admin.php?page=kofc-state-knight-edit&knight_id=<?= $knight['knight_id'] ?>" class="row-title"><?= otgkofcs_filter_quotes($knight['name_first']) ?></a>',
			'<a href="admin.php?page=kofc-state-knight-edit&knight_id=<?= $knight['knight_id'] ?>" class="row-title"><?= otgkofcs_filter_quotes($knight['name_last']) ?></a>',
			'<?= $knight['council_id'] ?>',
			'<?= $knight['score'] ?>',
			'<input type="number" name="score[<?= $knight['knight_id'] ?>]" min="0" class="otgkofcs_input_small">'
		],
<?php } ?>
	];
    jQuery('#table').DataTable( {
		data: tableData,
		autoWidth: false,
		pageLength: 25,
		stateSave: true,
		columnDefs: [
			{orderable: false, targets: [0, 5]}
		]
	});
});
</script>
<div class="wrap">
	<h2>KofC Scoreboards: Knights List &nbsp; <a href="admin.php?page=kofc-state-knight-edit" class="add-new-h2">Add New</a></h2>
	<?php otgkofcs_display_messages($message_list); ?>

	<?php if (!empty($knights_list)) { ?>
	<form method="post">
		<?php wp_nonce_field('knights_list'); ?>
		
		<div class="tablenav">
			<select name="action" id="bulk-action-selector-top">
				<option value="score" selected="selected">Update Scores</option>
				<option value="delete">Delete</option>
			</select>
			<input type="submit" id="doaction" class="button action" value="Apply">
		</div>
		<table id="table" class="otgkofcs_table1">
			<thead><tr>
				<th><input type="checkbox" id="cb-select-all-1"></th>
				<th>First Name</th>
				<th>Last Name</th>
				<th>Council</th>
				<th>Score</th>
				<th>Update Score</th>
			</tr></thead>
		</table>
	</form>
	
	<?php } else { ?>
	<p class="otgkofcs_error">There are no Knights in the Database.<br>You should add some.</p>
	<?php } ?>

	<form class="edit" method="post">
		<?php wp_nonce_field('knights_quick_add'); ?>
		<h3>Quick Add</h3>
		<input type="text" id="name_first" name="name_first" placeholder="First Name" required="required">
		<input type="text" id="name_last" name="name_last" placeholder="Last Name" required="required">
		<select id="council_id" name="council_id" required="required">
			<option>Select Council</option>
			<?php foreach ($council_list as $council_id => $council) { ?>
			<option value="<?= $council_id ?>"><?= $council_id ?></option>
			<?php } ?>
		</select>
		<input type="number" id="score" name="score" placeholder="Score" step="1" min="0" max="999">
		<input type="submit" class="button-primary">
	</form>
	
	<form class="edit" method="post">
		<?php wp_nonce_field('knights_reset_scores'); ?>
		<h3>Reset All Knight Scores to 0</h3>
		<p>
			<label for="reset_scores">Are You Sure?</label>
			<input type="checkbox" id="reset_scores" name="reset_scores" required="required">
		</p>
		<input type="submit" class="button-primary">
	</form>	
	
</div>