<?php
/** Edit Listing Page
 * @Package			Knights of Columbus State WP Plugin
 * @File				views/admin/knight_edit.php
 * @Author			Chris Hood (http://chrishood.me)
 * @Link				http://onthegridwebdesign.com
 * @copyright		(c) 2016-2022, On the Grid Web Design LLC
 * @created			7/23/16
*/
?>

<div class="wrap">
	<h2>KofC Scoreboards: <?php if ($record['knight_id']) echo 'Edit'; else echo 'Add'; ?> Knight</h2>
	<?php otgkofcs_display_messages($message_list); ?>

	<form name="form1" method="post" class="otgkofcs_form1" style="display: inline-block; max-width: 550px;">
		<?php if ($record['knight_id']) wp_nonce_field('knight_edit_' . $record['knight_id']); else wp_nonce_field('knight_add'); ?>
		<input type="hidden" name="knight_id" value="<?= $record['knight_id']; ?>">
		
		<p>
			<label>*First Name:</label>
			<input type="text" name="name_first" maxlength="50" value="<?= $record['name_first'] ?>" required="required">
		</p>
		<p>
			<label>*Last Name:</label>
			<input type="text" name="name_last" maxlength="50" value="<?= $record['name_last'] ?>" required="required">
		</p>
		<p>
			<label for="council_id">*Council:</label>
			<select name="council_id" required="required">
				<option>Select Council</option>
				<?php foreach ($council_list as $council_id => $council) { ?>
				<option value="<?= $council_id ?>"<?php if ($council_id == $record['council_id']) echo 'selected="selected"';?>>
					<?= $council_id ?>
				</option>
				<?php } ?>
			</select>
		</p>		
		<p>
			<label for="score">Score:</label>
			<input type="number" name="score" value="<?= $record['score'] ?>">
		</p
		
		<p style="text-align: center;">
			<input type="submit" class="button-primary" value="Save">
			<a href="admin.php?page=kofc-state" class="button-primary" style="margin-left: 17px;">Back to List</a>
		</p>
	</form>	

</div>