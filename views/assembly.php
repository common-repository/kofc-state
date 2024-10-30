<?php 
/*  Template Name: Assembly Page */

// ***** Handle Email Form *****
$otgkofcs_email_sent = false;
if (!empty($_POST['_wpnonce'])) {
	$nonce = $_POST['_wpnonce'];
	if (wp_verify_nonce($nonce, 'email_assembly')) {
		$otgkofcs_email_sent = $otgkofcs_Assembly->email_assembly_form();
	} else {
		error_log('kofc_state->assembly page Bad nonce: ' . $nonce);
	}
}

get_header();
$assembly_list = $otgkofcs_Assembly->get_list();
?>

<div class="otgkofcs_listing_page">
	<section class="otgkofcs_listing_page_main">
<?php if (have_posts()) {
	while (have_posts()) {
		the_post();
		$meta = get_post_meta(get_queried_object_id());
		?>
	
		<div class="otgkofcs_textbox2">
			<div style="float: right; padding-right: 5px">
		<?php if (!empty($meta['facebook'][0])) { ?>
				<a href="<?= $meta['facebook'][0] ?>" target="_blank" rel="noopener">
					<img src="<?= OTGKOFCS_ROOT_URL . 'images/facebook.png'?>" class="otgkofcs_assembly_page_icon">
				</a>
		<?php } ?>
		<?php if (!empty($meta['twitter'][0])) { ?>
				<a href="https://twitter.com/<?= $meta['twitter'][0] ?>" target="_blank" rel="noopener">
					<img src="<?= OTGKOFCS_ROOT_URL . 'images/twitter2_64px.png'?>" class="otgkofcs_assembly_page_icon">
				</a>
		<?php } ?>
		<?php if (!empty($meta['instagram'][0])) { ?>
				<a href="https://www.instagram.com/<?= $meta['instagram'][0] ?>" target="_blank" rel="noopener">
					<img src="<?= OTGKOFCS_ROOT_URL . 'images/instagram-64.png'?>" class="otgkofcs_assembly_page_icon">
				</a>
		<?php } ?>
			</div>
	
			<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Link to <?php the_title_attribute(); ?>">Assembly #<?php the_title(); ?>&nbsp; <?= $meta['name'][0] ?></a></h2>
		
		<?php the_post_thumbnail('medium', ['class' => 'alignright']); ?>
			
		<?php if (!empty($meta['location'][0])) { ?>
			<p>Location:&nbsp;<?= $meta['location'][0] ?></p>
		<?php } ?>

		<?php if (!empty($meta['charter_date'][0])) { ?>
			<p>Charter Date:&nbsp;<?= $meta['charter_date'][0] ?></p>
		<?php } ?>
			
		<?php $council_list = $otgkofcs_Assembly->get_councils(get_the_title());
		if (!empty($council_list)) {
			$council_html = '';
			foreach ($council_list as $council) $council_html .= '<a href="/council/' . $council . '">' . $council . '</a>, ';
			$council_html = trim($council_html, ', ');
			?>
			<p>Serving Councils:&nbsp;<?= $council_html ?></p>
		<?php } ?>

		<?php if (!empty($meta['faithful_navigator'][0])) { ?>
			<p>Faithful Navigator:&nbsp;<?= $meta['faithful_navigator'][0] ?></p>
		<?php } ?>

		<?php if (!empty($meta['faithful_comptroller'][0])) { ?>
			<p>Faithful Comptroller:&nbsp;<?= $meta['faithful_comptroller'][0] ?></p>
		<?php } ?>

		<?php if (!empty($meta['color_corps_commander'][0])) { ?>
			<p>Color Corps Commander:&nbsp;<?= $meta['color_corps_commander'][0] ?></p>
		<?php } ?>

		<?php if (!empty($meta['website'][0])) { ?>
			<p>Website:&nbsp;<a href="<?= $meta['website'][0] ?>" target="_blank" rel="noopener"><?= $meta['website'][0] ?></a></p>
		<?php } ?>

		<?php the_content(); ?>
		
		</div>
		
		<?php if (!empty($meta['contact_email'][0]) && !$otgkofcs_email_sent) { ?>
		<div class="otgkofcs_textbox2">
			<h3>Email Assembly <?php the_title(); ?></h3>
			<script src="https://js.hcaptcha.com/1/api.js" async defer></script>
			<form method="post" class="otgkofcs_form2">
				<input type="hidden" name="post_id" value="<?= get_the_ID() ?>">
				<?php wp_nonce_field('email_assembly'); ?>
				<input type="text" name="name" placeholder="Your Name" required>
				<input type="email" name="email" placeholder="Your Email" required>
				<input type="subject" name="subject" placeholder="Subject" required>
				<textarea name="message" placeholder="Your Message" required></textarea>
				<div class="h-captcha" data-sitekey="<?= get_option('otgkofcs_hcaptcha_site_key') ?>" data-callback="otgkofcs_hcaptcha_good"></div>
				<input id="otgkofcs_submit_button" type="submit" value="Send" disabled>
			</form>
		</div>
		<?php } ?>

		<?php if ($otgkofcs_email_sent) { ?>
		<div class="otgkofcs_textbox2">
			<h3>Email Assembly <?php the_title(); ?></h3>
			<p class="otgkofcs_large_text">Your email was sent to the assembly. They should respond to you soon.</p>
		</div>
		<?php } ?>
		
	<?php }
		} else { // ***** 404 Message *****?>
		<div class="otgkofcs_textbox2">
			<h2>404</h2>
			<p>Sorry, that assembly does not exist. You get a 404.</p>
		</div>
<?php } ?>
	</section>

<?php //***** Side Bar ***** ?>
	<section class="otgkofcs_listing_page_sidebar">
		<div id="meta-2" class="widget_meta"><h4 class="widgettitle">All Assemblies</h4>	
<?php if (!empty($assembly_list)) foreach ($assembly_list as $assembly_num => $assembly) { ?>
			<a href="/assembly/<?= $assembly_num ?>"><?= $assembly_num ?> - <?= $assembly['name'] ?></a><br>
<?php } ?>
		</div>
<?php if (function_exists('dynamic_sidebar')) dynamic_sidebar('sidebar'); ?>
	</section>
	
</div>

<?php get_footer();