<?php 
/*  Template Name: Knight Page */

// ***** Handle Email Form *****
$otgkofcs_email_sent = false;
if (!empty($_POST['_wpnonce'])) {
	$nonce = $_POST['_wpnonce'];
	if (wp_verify_nonce($nonce, 'email_knight')) {
		$otgkofcs_email_sent = $otgkofcs_Knight->email_knight_form();
	} else {
		error_log('kofc_state->knight page Bad nonce: ' . $nonce);
	}
}

get_header();
?>

<div class="otgkofcs_listing_page">
	<section class="otgkofcs_listing_page_main">
<?php if (have_posts()) {
	while (have_posts()) {
		the_post();
		$meta = get_post_meta(get_queried_object_id());
		?>
	
		<div class="otgkofcs_textbox2">
			<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Link to <?php the_title_attribute(); ?>">Email <?php the_title(); ?></a></h2>
		
		<?php if (!empty($meta['email_address'][0]) && !$otgkofcs_email_sent) { ?>
			<script src="https://js.hcaptcha.com/1/api.js" async defer></script>
			<form method="post" class="otgkofcs_form2">
				<input type="hidden" name="post_id" value="<?= get_the_ID() ?>">
				<?php wp_nonce_field('email_knight'); ?>
				<input type="text" name="name" placeholder="Your Name" required>
				<input type="email" name="email" placeholder="Your Email" required>
				<input type="subject" name="subject" placeholder="Subject" required>
				<textarea name="message" placeholder="Your Message" required></textarea>
				<div class="h-captcha" data-sitekey="<?= get_option('otgkofcs_hcaptcha_site_key') ?>" data-callback="otgkofcs_hcaptcha_good"></div>
				<input id="otgkofcs_submit_button" type="submit" value="Send" disabled>				
			</form>
		<?php } ?>

		<?php if ($otgkofcs_email_sent) { ?>
			<p class="otgkofcs_large_text">Your email was sent. They should respond to you soon.</p>
		<?php } ?>

		</div>
	</section>

<?php //***** Side Bar ***** ?>
	<section class="otgkofcs_listing_page_sidebar">

		<div id="meta-2" class="widget_meta">
			<h4 class="widgettitle">About <?php the_title(); ?></h4>	

			<?php the_post_thumbnail('medium', ['class' => 'otgkofcs_knight_profile_img']); ?>

			<?php the_content(); ?>

		<?php if (!empty($meta['council'][0])) {
			$council = $meta['council'][0] ?>
			<p><a href="/council/<?= $council ?>">Council <?= $council ?></a></p>
		<?php } ?>

		<?php if (!empty($meta['website'][0])) { ?>
			<p><a href="<?= $meta['website'][0] ?>" target="_blank" rel="noopener"><?= $meta['website'][0] ?></a></p>
		<?php } ?>
				
		<?php if (!empty($meta['twitter'][0])) { ?>
			<p>
				<img src="<?= OTGKOFCS_ROOT_URL . 'images/twitter2_64px.png'?>" class="otgkofcs_knight_social_icon">
				<a href="https://twitter.com/<?= $meta['twitter'][0] ?>" target="_blank" rel="noopener" class="otgkofcs_knight_social_link">
					<?= $meta['twitter'][0] ?>
				</a>
			</p>
		<?php }
		if (!empty($meta['instagram'][0])) { ?>
			<p>
				<img src="<?= OTGKOFCS_ROOT_URL . 'images/instagram-64.png'?>" class="otgkofcs_knight_social_icon">
				<a href="https://www.instagram.com/<?= $meta['instagram'][0] ?>" target="_blank" rel="noopener" class="otgkofcs_knight_social_link">
					<?= $meta['instagram'][0] ?>
				</a>
			</p>
		<?php } 
		if (!empty($meta['facebook'][0])) { ?>
			<p>
				<img src="<?= OTGKOFCS_ROOT_URL . 'images/facebook.png'?>" class="otgkofcs_knight_social_icon">
				<a href="<?= $meta['facebook'][0] ?>" target="_blank" rel="noopener" class="otgkofcs_knight_social_link">
					<?= str_replace('https://www.facebook.com/', '', $meta['facebook'][0]) ?>
				</a>
			</p>
		<?php }	?>
			
		</div>
			
<?php if (function_exists('dynamic_sidebar')) dynamic_sidebar('sidebar'); ?>
	</section>
	
	<?php }
		} else { // ***** 404 Message *****?>
	<div class="otgkofcs_textbox2">
		<h2>404</h2>
		<p>Sorry, that knight does not exist. You get a 404.</p>
	</div>
<?php } ?>
	
</div>

<?php get_footer();