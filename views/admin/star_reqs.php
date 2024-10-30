<?php
/** List Page
 * @Package			Knights of Columbus State WP Plugin
 * @File				views/admin/star_reqs.php
 * @Author			Chris Hood (http://chrishood.me)
 * @Link				http://onthegridwebdesign.com
 * @copyright		(c) 2016-2022, On the Grid Web Design LLC
 * @created			12/18/16
*/
?>

<div class="wrap">
	<h2>KofC Scoreboards: Score Councils Requirements</h2>
	<?php otgkofcs_display_messages($message_list); ?>
	
<?php if (!empty($star_reqs_list)) { ?>
	<form method="post" class="otgkofcs_form1">
		<?php wp_nonce_field('star_reqs'); ?>
		
		<ul id="otgkofcs_sortable">		
			<?php foreach ($star_reqs_list as $star_req) { ?>
				<li class="ui-state-default" id="req_<?= $star_req['star_req_id']; ?>">
					<span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
					<input type="text" name="name[<?= $star_req['star_req_id'] ?>]" length="250" value="<?= $star_req['name'] ?>">
					<span class="otgkofcs_red_x" onclick="otgkofcs_delete_something('admin.php?page=kofc-state-starreq&delete=<?= $star_req['star_req_id'] ?>', 'this requirement')">&times;</span>
				</li>
			<?php } ?>  
		</ul>

		<input type="submit" value="Save Changes">
		<script>
		 jQuery(function($) {
			$("#otgkofcs_sortable").sortable();
			$("#otgkofcs_sortable").disableSelection();
		 } );
		</script>				
	</form>

<?php } else { ?>
	<p class="otgkofcs_error">There are no requirements in the database.<br>You should add some.</p>
	<?php } ?>
	
	<form id="otgkofcs_star_req_add_form" class="edit" method="post">
		<?php wp_nonce_field('star_reqs_add'); ?>
		<h3>Add a Requirement</h3>
		<input type="text" id="otgkofcs_title" name="new_name" placeholder="Requirement Name" required="required" maxlength="250" style="width: 400px;">
		<input type="submit" value="Add">
	</form>
</div>