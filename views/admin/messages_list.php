<?php
/** List Page
 * @Package			Knights of Columbus State WP Plugin
 * @File				views/admin/messages_list.php
 * @Author			Chris Hood (http://chrishood.me)
 * @Link				http://onthegridwebdesign.com
 * @copyright		(c) 2016-2022, On the Grid Web Design LLC
 * @created			4/11/18
*/
?>
<script>
jQuery(document).ready(function () {
	var tableData = [
<?php if (!empty($email_list)) foreach ($email_list as $message) {?>
		[
			'<input type="checkbox" name="bulk_action_list[]" value="<?= $message['message_id'] ?>" class="otgkofcs_list_checkbox">',
			'<?= htmlspecialchars($message['to'], ENT_QUOTES) ?>',
			'<?= htmlspecialchars($message['date'], ENT_QUOTES) ?>',
			'<?= htmlspecialchars($message['name'], ENT_QUOTES) ?>',
			'<a href="mailto:<?= $message['email'] ?>"><?= $message['email'] ?></a>',
			'<a href="admin.php?page=kofc-state-message-view&message_id=<?= $message['message_id'] ?>"><?= htmlspecialchars($message['subject'], ENT_QUOTES) ?></a>'
 		],
<?php } ?>
	];
    jQuery('#table').DataTable( {
		data: tableData,
		autoWidth: false,
		pageLength: 25,
		stateSave: true,
		columnDefs: [
			{orderable: false, targets: [0]}
		],
		order: [[ 2, "desc" ]]
	});
});
</script>
<div class="wrap">
	<h2>KofC State: Messages Sent Via Forms</h2>
	<?php otgkofcs_display_messages($message_list); ?>

<?php if (!empty($email_list)) { ?>
	<form method="post">
		<?php wp_nonce_field('messages_list'); ?>
		
		<div class="tablenav">
			<select name="action" id="bulk-action-selector-top">
				<option value="-1" selected="selected">Bulk Actions</option>
				<option value="delete">Delete</option>
			</select>
			<input type="submit" id="doaction" class="button action" value="Apply">
		</div>

		<table id="table" class="otgkofcs_table1">
			<thead><tr>
				<th><input type="checkbox" id="cb-select-all-1"></th>
				<th>To</th>
				<th>Date</th>
				<th>From</th>
				<th>Email</th>
				<th>Subject</th>
			</tr></thead>
		</table>

		<div class="tablenav">
			<select name="action" id="bulk-action-selector-bottom">
				<option value="-1" selected="selected">Bulk Actions</option>
				<option value="delete">Delete</option>
			</select>
			<input type="submit" id="doaction" class="button action" value="Apply">
		</div>
	</form>
	
<?php } else { ?>
	<p class="otgkofcs_error">There are no inbound messages in the database.</p>
<?php } ?>
	
</div>