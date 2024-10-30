<?php
/** Knight Post Type
 * @Package			Knights of Columbus State WP Plugin
 * @File				type-knight.php
 * @Author			Chris Hood (http://chrishood.me)
 * @Link				http://onthegridwebdesign.com
 * @copyright		(c) 2016-2022, On the Grid Web Design LLC
 * @created			4/30/2018
 */
class otgkofcs_Knight_Type {

	function __construct () {
		add_action('init', [&$this, 'create_post_type']);
		add_action('add_meta_boxes_knight', [&$this, 'add_meta_boxes']);
		add_action('save_post_knight', [&$this, 'save_meta']);
		add_action('load-edit.php', [&$this, 'columns_sorting_load']);
	}

	/**
	 */
	function create_post_type () {
		$labels = [
			'name' => 'Knights',
			'singular_name' => 'Knight',
			'menu_name' => 'Knights',
			'all_items' => 'All Knights',
			'add_new' => 'Add Knight',
			'new_item' => 'New Knight',
			'edit_item' => 'Edit Knight',
			'update_item' => 'Update Knight',
			'view_item' => 'View Knight',
			'view_items' => 'View Knights',
			'not_found' => 'Not found',
			'not_found_in_trash' => 'Not found in Trash',
			'featured_image' => 'Knight Image',
			'set_featured_image' => 'Set knight image',
			'remove_featured_image' => 'Remove knight image',
			'use_featured_image' => 'Use as knight image',
			'insert_into_item' => 'Insert into item',
			'uploaded_to_this_item' => 'Uploaded to this knight',
		];
		$rewrite = [
			'slug' => 'knight',
			'with_front' => true,
			'pages' => true,
			'feeds' => true,
		];
		$args = [
			'label' => 'Knight',
			'description' => '',
			'labels' => $labels,
			'supports' => ['title', 'editor', 'thumbnail'],
			'hierarchical' => false,
			'public' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'menu_position' => 5,
			'show_in_admin_bar' => false,
			'show_in_nav_menus' => true,
			'can_export' => true,
			'has_archive' => true,
			'exclude_from_search' => false,
			'publicly_queryable' => true,
			'rewrite' => $rewrite,
			'capability_type' => 'post',
		];
		register_post_type('knight', $args);
	}

	/**
	 * @param type $post
	 */
	function add_meta_boxes ($post) {
		add_meta_box('knight_meta_box', __('Details'), [&$this, 'meta_box'], 'knight', 'advanced', 'high');
	}

	/** Build Custom Fields Meta Box
	 * @param post $post The post object
	 */
	function meta_box ($post) {
		require_once(OTGKOFCS_ROOT_PATH . 'helpers/view_helper.php');
		
		// ***** Retrieve the Meta Fields *****
		$name_first = get_post_meta($post->ID, 'name_first', true);
		$name_last = get_post_meta($post->ID, 'name_last', true);
		$website = get_post_meta($post->ID, 'website', true);
		$email_address = get_post_meta($post->ID, 'email_address', true);
		$council = get_post_meta($post->ID, 'council', true);
		$facebook = get_post_meta($post->ID, 'facebook', true);
		$twitter = get_post_meta($post->ID, 'twitter', true);
		$instagram = get_post_meta($post->ID, 'instagram', true);
		
		wp_nonce_field(basename(__FILE__), 'knight_nonce');
		?>

		<div class='inside otgkofcs_form1'>

			<p>
				<label>First Name</label>
				<input type="text" name="otgkofcs_name_first" value="<?= $name_first ?>" maxlength="100">
			</p>

			<p>
				<label>Last Name</label>
				<input type="text" name="otgkofcs_name_last" value="<?= $name_last ?>" maxlength="100">
			</p>

			<p>
				<label>Website</label>
				<input type="text" name="otgkofcs_website" value="<?= $website ?>" maxlength="200">
			</p>

			<p>
				<label>Email Address</label>
				<input type="email" name="otgkofcs_email_address" value="<?= $email_address ?>" maxlength="200">
			</p>

			<p>
				<label>Council</label>
				<input type="text" name="otgkofcs_council" value="<?= $council ?>" maxlength="6">
			</p>
			
			<p>
				<label>Facebook Page Link</label>
				<input type="text" name="otgkofcs_facebook" value="<?= $facebook ?>" maxlength="200">
			</p>

			<p>
				<label>Twitter Handle</label>
				<input type="text" name="otgkofcs_twitter" value="<?= $twitter ?>" maxlength="50">
			</p>

			<p>
				<label>Instagram Handle</label>
				<input type="text" name="otgkofcs_instagram" value="<?= $instagram ?>" maxlength="50">
			</p>

		</div>
		<?php
	}

	/** Store custom field meta box data
	 * @param int $post_id The post ID.
	 * @link https://codex.wordpress.org/Plugin_API/Action_Reference/save_post
	 */
	function save_meta ($post_id) {
		// verify taxonomies meta box nonce
		if (!isset($_POST['knight_nonce']) || !wp_verify_nonce($_POST['knight_nonce'], basename(__FILE__))) {
			return;
		}

		// Check the user's permissions.
		if (!current_user_can('edit_post', $post_id)) {
			return;
		}
		
		require_once(OTGKOFCS_ROOT_PATH . 'helpers/filter_helper.php');

		$text_field_list = ['name_first', 'name_last',  'council', 'twitter', 'instagram'];
		foreach ($text_field_list as $field_name) {
			if (isset($_REQUEST['otgkofcs_' . $field_name])) {
				update_post_meta($post_id, $field_name, otgkofcs_get_request_string('otgkofcs_' . $field_name));
			}
		}
		$url_field_list = ['website', 'facebook'];
		foreach ($url_field_list as $field_name) {
			if (isset($_REQUEST['otgkofcs_' . $field_name])) {
				update_post_meta($post_id, $field_name, otgkofcs_get_request_link('otgkofcs_' . $field_name));
			}
		}
		if (isset($_REQUEST['otgkofcs_email_address'])) {
			update_post_meta($post_id, 'email_address', otgkofcs_get_request_email('otgkofcs_email_address'));
		}
	}

	/** Loads the Functions for the Knight List Page
	 */
	function columns_sorting_load () {
		add_filter('manage_knight_posts_columns', [&$this, 'columns_head']);
		add_filter('manage_edit-knight_sortable_columns', [&$this, 'columns_sortable']);
		add_filter('request', [&$this, 'columns_sorting']);
		add_action('manage_knight_posts_custom_column', [&$this, 'columns_content'], 10, 2);
	}

	/** Adds to Column Array
	 * @param array $defaults
	 * @return array
	 */
	function columns_head ($defaults) {
		unset($defaults['date']);
		$defaults['name_first'] = 'First Name';
		$defaults['name_last'] = 'Last Name';
		$defaults['council'] = 'Council';
		$defaults['email_address'] = 'Email Address';
		return $defaults;
	}
	

	/** Adds Columns to List of Sortable Ones
	 * @param array $columns
	 * @return array
	 */
	function columns_sortable ($columns) {
		$columns['name_first'] = 'name_first';
		$columns['name_last'] = 'name_last';
		$columns['council'] = 'council';
		$columns['email_address'] = 'email_address';

		return $columns;
	}

	/** Adds the Meta Key to the Sorting
	 * @param array $vars
	 * @return array
	 */
	function columns_sorting ($vars) {
		if (isset($vars['post_type']) && isset($vars['orderby']) && 'knight' == $vars['post_type'])
			if (in_array($vars['orderby'], ['name_first', 'name_last', 'email_address'])) {
				$vars = array_merge(
						$vars, array(
						'meta_key' => $vars['orderby'],
						'orderby' => 'meta_value'
						)
				);
			} elseif ('council' == $vars['orderby']) {
				$vars = array_merge(
						$vars, array(
						'meta_key' => $vars['orderby'],
						'orderby' => 'meta_value_num'
						)
				);
			}

		return $vars;
	}

	/** Prints the Values for the Columns
	 * @param string $column_name
	 * @param int $post_id
	 */
	function columns_content ($column_name, $post_id) {
		switch ($column_name) {
			case 'email_address':
				$value = get_post_meta($post_id, 'email_address', true);
				if (!empty($value)) {
					echo '<a href="mailto:' . $value . '">' . $value . '</a>';
				}
				break;
			case 'name_first':
				echo get_post_meta($post_id, 'name_first', true);
				break;
			case 'name_last':
				echo get_post_meta($post_id, 'name_last', true);
				break;
			case 'council':
				echo get_post_meta($post_id, 'council', true);
				break;
		}
	}

	/** Gets the list of all knights with their names and councils
	 * Key is the knight number
	 * @return array
	 */
	function get_list () {
		$raw_list = get_posts(['post_type' => 'knight', 'numberposts' => 1000]);
		$out = array();
		foreach ($raw_list as $knight) {
			$name_first = get_post_meta($knight->ID, 'name_first', true);
			$name_last = get_post_meta($knight->ID, 'name_last', true);
			$council = get_post_meta($knight->ID, 'council', true);
			$out[$knight->post_title] = ['name_first' => $name_first, 'name_last' => $name_last, 'council' => $council];
		}
		ksort($out);
		return $out;
	}
	
	/** Sends the Email from the Email Form
	 * Gets the data from the post array
	 * @return boolean
	 */
	function email_knight_form () {
		require_once('models/messages_model.php');
		$otgkofcs_Messages_Model = new otgkofcs_Messages_Model();
		require_once(OTGKOFCS_ROOT_PATH . 'helpers/filter_helper.php');
		require_once(OTGKOFCS_ROOT_PATH . 'helpers/validation_helper.php');
		
		// ***** Form Valdiation *****
		if (!otgkofcs_verify_hcaptcha())
			return false;
		
		$post_id = otgkofcs_get_request_int('post_id');
		$name = otgkofcs_get_request_string('name', null, true);
		$subject = otgkofcs_get_request_string('subject', null, true);
		$email = otgkofcs_get_request_email('email');
		$message = otgkofcs_get_request_texarea('message');
		$ip = $_SERVER['REMOTE_ADDR'];
		
		// ***** Save to Database *****
		$otgkofcs_Messages_Model->add(['to_id' => $post_id, 'name' => $name, 'email' => $email, 'subject' => $subject, 'message' => $message, 'ip' => $ip]);
				
		// ***** Assemble Email *****
		$to = get_post_meta($post_id, 'email_address', true);
		if (empty($to)) {
			error_log('kofc-state->type-knight->email_knight Cannot send email due to knight email address being empty.');	
			return false;
		}
		
		$email_sender_name = get_option('otgkofcs_email_sender_name');
		$email_sender_address = get_option('otgkofcs_email_sender_address');
		if (empty($email_sender_address)) {
			error_log('kofc-state->type-knight->email_knight Cannot send email due to otgkofcs_email_sender_address option being empty');	
			return false;
		}
		if (empty($email_sender_name)) {
			$from = $name . ' <' . $email_sender_address. '>';
		} else {
			$from = $name . ' via ' . $email_sender_name . ' <' . $email_sender_address . '>';
		}
		
		$reply_to = "$name <$email>";

		$headers = array('Content-Type: text/html; charset=UTF-8','From: ' . $from, 'Reply-to: ' . $reply_to);
		
		$message = '<p>Contact form message from ' . htmlentities($reply_to) . ' (Hit reply to respond.)<br>------------------------------------------------------</p>' 
				. '<p>' . nl2br($message) . '</p>'
				. '<p>------------------------------------------------------<br>Email sent from a form on the ' . get_bloginfo('name') . ' website.</p>';
		
		// ***** Send Email *****
		$result_send = wp_mail($to, '[' . get_bloginfo('name') . '] ' . $subject, $message, $headers); 
		if (!$result_send) {
			error_log("kofc-state->type-knight->email_knight wp_mail error Email - To: $to" . PHP_EOL . "Subject: $subject" . PHP_EOL
					. "Message: $message" . PHP_EOL . 'Headers: ' . print_r($headers, true));
		}
		//error_log("kofc-state->type-knight->email_knight wp_mail Debug - To: $to" . PHP_EOL . "Subject: $subject" . PHP_EOL . "message: $message" . PHP_EOL . 'Headers: ' . print_r($headers, true));
		return $result_send;
	}

}