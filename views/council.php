<?php
/*  Template Name: Council Page */

// ***** Handle Email Form *****
$otgkofcs_email_sent = false;
if (!empty($_POST['_wpnonce'])) {
	$nonce = $_POST['_wpnonce'];
	if (wp_verify_nonce($nonce, 'email_council')) {
		$otgkofcs_email_sent = $otgkofcs_Council->email_council_form();
	} else {
		error_log('kofc_state->council page Bad nonce: ' . $nonce);
	}
}

get_header();
$council_list = $otgkofcs_Council->get_list();
$rss_data = array();
?>

<div class="otgkofcs_listing_page">
	<section class="otgkofcs_listing_page_main">
<?php if (have_posts()) {
	while (have_posts()) {
		the_post();
		$meta = get_post_meta(get_queried_object_id());

		if (!empty($meta['rss_url'][0])) {
			require_once(OTGKOFCS_ROOT_PATH . 'helpers/rss_helper.php');
			$rss_data = otgkofcs_get_rss_feed ($meta['rss_url'][0], 5);
		}
		?>

		<div class="otgkofcs_textbox2">
			<div style="float:right; padding-right: 5px">
		<?php if (!empty($meta['facebook'][0])) { ?>
				<a href="<?= $meta['facebook'][0] ?>" target="_blank" rel="noopener">
					<img src="<?= OTGKOFCS_ROOT_URL . 'images/facebook.png' ?>" class="otgkofcs_council_page_icon">
				</a>
		<?php } ?>
		<?php if (!empty($meta['twitter'][0])) { ?>
				<a href="https://twitter.com/<?= $meta['twitter'][0] ?>" target="_blank" rel="noopener">
					<img src="<?= OTGKOFCS_ROOT_URL . 'images/twitter2_64px.png' ?>" class="otgkofcs_council_page_icon">
				</a>
		<?php } ?>
		<?php if (!empty($meta['instagram'][0])) { ?>
				<a href="https://www.instagram.com/<?= $meta['instagram'][0] ?>" target="_blank" rel="noopener">
					<img src="<?= OTGKOFCS_ROOT_URL . 'images/instagram-64.png' ?>" class="otgkofcs_council_page_icon">
				</a>
		<?php } ?>
			</div>

			<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Link to <?php the_title_attribute(); ?>">Council <?php the_title(); ?> <?= $meta['name'][0] ?></a></h2>

		<?php the_post_thumbnail('medium', ['class' => 'alignright']); ?>

		<?php if (!empty($meta['location'][0])) { ?>
			<p>Location:&nbsp;<?= $meta['location'][0] ?></p>
		<?php } ?>

		<?php if (!empty($meta['grand_knight'][0])) { ?>
			<p>Grand Knight:&nbsp;<?= $meta['grand_knight'][0] ?></p>
		<?php } ?>

		<?php if (!empty($meta['charter_date'][0])) { ?>
			<p>Charter Date:&nbsp;<?= $meta['charter_date'][0] ?></p>
		<?php } ?>

		<?php if (!empty($meta['assembly'][0])) { ?>
			<p>Assembly:&nbsp;<a href="/assembly/<?= $meta['assembly'][0] ?>">#<?= $meta['assembly'][0] ?></a></p>
		<?php } ?>

		<?php if (!empty($meta['website'][0])) { ?>
			<p>Website:&nbsp;<a href="<?= $meta['website'][0] ?>" target="_blank" rel="noopener"><?= $meta['website'][0] ?></a></p>
		<?php } ?>

		<?php the_content(); ?>

		</div>

		<?php if (!empty($meta['contact_email'][0]) && !$otgkofcs_email_sent) { ?>
		<div class="otgkofcs_textbox2">
			<h3>Email Council <?php the_title(); ?></h3>
			<script src="https://js.hcaptcha.com/1/api.js" async defer></script>
			<form method="post" class="otgkofcs_form2">
				<input type="hidden" name="post_id" value="<?= get_the_ID() ?>">
				<?php wp_nonce_field('email_council'); ?>
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
			<h3>Email Council <?php the_title(); ?></h3>
			<p class="otgkofcs_large_text">Your email was sent to the council. They should respond to you soon.</p>
		</div>
		<?php } ?>

	<?php }
		} else { // ***** 404 Message *****?>
		<div class="otgkofcs_textbox2">
			<h2>404</h2>
			<p>Sorry, that council does not exist. You get a 404.</p>
		</div>
<?php } ?>
	</section>

<?php //***** Side Bar ***** ?>
	<section class="otgkofcs_listing_page_sidebar">
<?php if (!empty($rss_data)) { ?>
		<div id="meta-2" class="widget_meta"><h4 class="widgettitle">Council News</h4>
<?php foreach ($rss_data as $item) { ?>
			<p style="padding-bottom: 2px; font-size: 1.2em;">
				<a href="<?= $item['link'] ?>" target="_blank" style="color: blue;"><?= $item['date'] ?> - <?= $item['title'] ?></a>
			</p>
			<p><?= $item['description'] ?></p>
<?php } ?>
		</div>
<?php }  ?>

		<div id="meta-2" class="widget_meta"><h4 class="widgettitle">All Councils</h4>
<?php if (!empty($council_list)) foreach ($council_list as $council_num => $council) { ?>
			<a href="/council/<?= $council_num ?>"><?= $council_num ?> - <?= $council['name'] ?></a><br>
<?php } ?>
		</div>

<?php if (function_exists('dynamic_sidebar')) dynamic_sidebar('sidebar'); ?>
	</section>

</div>

<?php get_footer();
