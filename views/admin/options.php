<?php
/** Edit Listing Page
 * @Package			Knights of Columbus State WP Plugin
 * @File				views/admin/options.php
 * @Author			Chris Hood (http://chrishood.me)
 * @Link				http://onthegridwebdesign.com
 * @copyright		(c) 2016-2021, On the Grid Web Design LLC
 * @created			7/23/16
*/
?>

<div class="wrap">
	<h2>KofC State: Options</h2>
	<?php otgkofcs_display_messages($message_list); ?>

	<form name="form1" method="post" class="otgkofcs_form1">
		<?php wp_nonce_field('options'); ?>
		
		<h4>General</h4>
		<p class="otgkofcs_full">
			<label for="otgkofcs_number_of_districts">Number of Districts:</label>
			<input name="otgkofcs_number_of_districts" value="<?= $options['otgkofcs_number_of_districts'] ?>" type="number" maxlength="20">
		</p>
		<p class="otgkofcs_full">
			<label for="otgkofcs_star_table_top_offset">Top Offset for Star Table Header:</label>
			<input name="otgkofcs_star_table_top_offset" value="<?= $options['otgkofcs_star_table_top_offset'] ?>" type="number" maxlength="10"> Pixels
		</p>
		<p class="otgkofcs_full">
			<label for="otgkofcs_star_table_top_offset">Email Sender Name:</label>
			<input name="otgkofcs_email_sender_name" value="<?= $options['otgkofcs_email_sender_name'] ?>" type="text" maxlength="100">
		</p>
		<p class="otgkofcs_full">
			<label for="otgkofcs_star_table_top_offset">Email Sender Address:</label>
			<input name="otgkofcs_email_sender_address" value="<?= $options['otgkofcs_email_sender_address'] ?>" type="email" maxlength="200">
		</p>
		<p class="otgkofcs_full">
			<label for="otgkofcs_hcaptcha_site_key">hCaptcha Site Key:</label>
			<input name="otgkofcs_hcaptcha_site_key" value="<?= $options['otgkofcs_hcaptcha_site_key'] ?>" type="text" maxlength="200">
		</p>
		<p class="otgkofcs_full">
			<label for="otgkofcs_hcaptcha_secret_key">hCaptcha Secret Key:</label>
			<input name="otgkofcs_hcaptcha_secret_key" value="<?= $options['otgkofcs_hcaptcha_secret_key'] ?>" type="text" maxlength="200">
		</p>
		
		<p class="otgkofcs_full"><a href="https://www.hcaptcha.com/" rel="noopener norefferer" target="_blank">Get the hCaptcha Keys</a></p>
		<p class="otgkofcs_full">Leave both hCaptcha fields blank to disable it.</p>	

		<p style="text-align: center;">
			<input type="submit" class="button-primary" value="Save">
		</p>
	</form>	

</div>