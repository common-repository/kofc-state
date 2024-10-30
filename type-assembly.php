<?php
/** Assembly Post Type
 * @Package			Knights of Columbus State WP Plugin
 * @File				type-assembly.php
 * @Author			Chris Hood (http://chrishood.me)
 * @Link				https://onthegridwebdesign.com
 * @copyright		(c) 2016-2022, On the Grid Web Design LLC
 * @created			03/24/2020
 */
class otgkofcs_Assembly_Type {

	function __construct () {
		add_action('init', [&$this, 'create_post_type']);
		add_action('add_meta_boxes_assembly', [&$this, 'add_meta_boxes']);
		add_action('save_post_assembly', [&$this, 'save_meta']);
		add_action('load-edit.php', [&$this, 'columns_sorting_load']);
	}

	/**
	 * 
	 */
	function create_post_type () {
		$labels = [
			'name' => 'Assemblies',
			'singular_name' => 'Assembly',
			'menu_name' => 'Assemblies',
			'all_items' => 'All Assemblies',
			'add_new' => 'Add Assembly',
			'new_item' => 'New Assembly',
			'edit_item' => 'Edit Assembly',
			'update_item' => 'Update Assembly',
			'view_item' => 'View Assembly',
			'view_items' => 'View Assemblies',
			'not_found' => 'Not found',
			'not_found_in_trash' => 'Not found in Trash',
			'featured_image' => 'Assembly Image',
			'set_featured_image' => 'Set assembly image',
			'remove_featured_image' => 'Remove assembly image',
			'use_featured_image' => 'Use as assembly image',
			'insert_into_item' => 'Insert into item',
			'uploaded_to_this_item' => 'Uploaded to this assembly',
		];
		$rewrite = [
			'slug' => 'assembly',
			'with_front' => true,
			'pages' => true,
			'feeds' => true,
		];
		$args = [
			'label' => 'Assembly',
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
		register_post_type('assembly', $args);
	}

	/**
	 * @param type $post
	 */
	function add_meta_boxes ($post) {
		add_meta_box('assembly_meta_box', __('Details'), [&$this, 'meta_box'], 'assembly', 'advanced', 'high');
	}

	/** Build custom field meta box
	 * @param post $post The post object
	 */
	function meta_box ($post) {
		//require_once(OTGKOFCS_ROOT_PATH . 'helpers/view_helper.php');
		
		// ***** Retrieve the Meta Fields *****
		$name = get_post_meta($post->ID, 'name', true);
		$location = get_post_meta($post->ID, 'location', true);
		$charter_date = get_post_meta($post->ID, 'charter_date', true);
		$faithful_navigator = get_post_meta($post->ID, 'faithful_navigator', true);
		$faithful_comptroller = get_post_meta($post->ID, 'faithful_comptroller', true);
		$color_corps_commander = get_post_meta($post->ID, 'color_corps_commander', true);
		$website = get_post_meta($post->ID, 'website', true);
		$contact_email = get_post_meta($post->ID, 'contact_email', true);
		$facebook = get_post_meta($post->ID, 'facebook', true);
		$twitter = get_post_meta($post->ID, 'twitter', true);
		$instagram = get_post_meta($post->ID, 'instagram', true);
		
		wp_nonce_field(basename(__FILE__), 'assembly_nonce');
		?>

		<div class='inside otgkofcs_form1'>
			<p>
				<label>Name</label>
				<input type="text" name="otgkofcs_name" value="<?= $name ?>" maxlength="200">
			</p>

			<p>
				<label>Location</label>
				<input type="text" name="otgkofcs_location" value="<?= $location ?>" maxlength="200">
			</p>
			
			<p>
				<label>Charter Date</label>
				<input type="text" name="otgkofcs_charter_date" value="<?= $charter_date ?>" maxlength="50">
			</p>

			<p>
				<label>Faithful Navigator</label>
				<input type="text" name="otgkofcs_faithful_navigator" value="<?= $faithful_navigator ?>" maxlength="200">
			</p>
			
			<p>
				<label>Faithful Comptroller</label>
				<input type="text" name="otgkofcs_faithful_comptroller" value="<?= $faithful_comptroller ?>" maxlength="200">
			</p>
			
			<p>
				<label>Color Corps Commander</label>
				<input type="text" name="otgkofcs_color_corps_commander" value="<?= $color_corps_commander ?>" maxlength="200">
			</p>

			<p>
				<label>Website</label>
				<input type="text" name="otgkofcs_website" value="<?= $website ?>" maxlength="200">
			</p>

			<p>
				<label>Contact Email</label>
				<input type="email" name="otgkofcs_contact_email" value="<?= $contact_email ?>" maxlength="200">
			</p>

			<p>
				<label>Facebook Page (Full Link)</label>
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

			<p clas="otgkofcs_full">*If there's no assembly website, you can use the main social media page.</p>
		</div>
		<?php
	}

	/** Store custom field meta box data
	 * @param int $post_id The post ID.
	 * @link https://codex.wordpress.org/Plugin_API/Action_Reference/save_post
	 */
	function save_meta ($post_id) {
		// verify taxonomies meta box nonce
		if (!isset($_POST['assembly_nonce']) || !wp_verify_nonce($_POST['assembly_nonce'], basename(__FILE__))) {
			return;
		}

		// Check the user's permissions.
		if (!current_user_can('edit_post', $post_id)) {
			return;
		}

		require_once(OTGKOFCS_ROOT_PATH . 'helpers/filter_helper.php');

		$text_field_list = ['name', 'location', 'charter_date', 'faithful_navigator', 'faithful_comptroller', 'color_corps_commander', 'twitter', 'instagram'];
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
		if (isset($_REQUEST['otgkofcs_contact_email'])) {
			update_post_meta($post_id, 'contact_email', otgkofcs_get_request_email('otgkofcs_contact_email'));
		}
	}

	/** Loads the Functions for the Assembly List Page
	 */
	function columns_sorting_load () {
		add_filter('manage_assembly_posts_columns', [&$this, 'columns_head']);
		add_filter('manage_edit-assembly_sortable_columns', [&$this, 'columns_sortable']);
		add_filter('request', [&$this, 'columns_sorting']);
		add_action('manage_assembly_posts_custom_column', [&$this, 'columns_content'], 10, 2);
	}

	/** Adds to Column Array
	 * @param array $defaults
	 * @return array
	 */
	function columns_head ($defaults) {
		unset($defaults['date']);
		$defaults['name'] = 'Name';
		$defaults['location'] = 'Location';
		$defaults['faithful_navigator'] = 'Faithful Navigator';
		$defaults['website'] = 'Website';
		return $defaults;
	}

	/** Adds Columns to List of Sortable Ones
	 * @param array $columns
	 * @return array
	 */
	function columns_sortable ($columns) {
		$columns['name'] = 'name';
		$columns['faithful_navigator'] = 'faithful_navigator';
		$columns['location'] = 'location';

		return $columns;
	}

	/** Adds the Meta Key to the Sorting
	 * @param array $vars
	 * @return array
	 */
	function columns_sorting ($vars) {
		if (isset($vars['post_type']) && 'assembly' == $vars['post_type'])
			if (isset($vars['orderby']) && in_array($vars['orderby'], ['name', 'location', 'faithful_navigator'])) {
				$vars = array_merge($vars, [
					'meta_key' => $vars['orderby'],
					'orderby' => 'meta_value'
				]);
			} else {
				add_filter('posts_orderby', [&$this, 'columns_sorting_to_numeric']);
			}

		return $vars;
	}

	/** Set the Sorting for Title to Numeric
	 * @param type $orderby
	 * @return type
	 */
	function columns_sorting_to_numeric ($orderby) {
		return str_replace('post_title', 'post_title+0', $orderby); 
	}

	/** Prints the Values for the Columns
	 * @param string $column_name
	 * @param int $post_id
	 */
	function columns_content ($column_name, $post_id) {
		switch ($column_name) {
			case 'website':
				$website = get_post_meta($post_id, 'website', true);
				if (!empty($website)) {
					echo '<a href="' . $website . '" target="_blank" rel="noopener">' . $website . '</a>';
				}
				break;
			case 'name':
				$name = get_post_meta($post_id, 'name', true);
				echo $name;
				break;
			case 'location':
				$location = get_post_meta($post_id, 'location', true);
				echo $location;
				break;
			case 'faithful_navigator':
				echo get_post_meta($post_id, 'faithful_navigator', true);
				break;
		}
	}

	/** Gets the list of all assembly with their names and websites
	 * Key is the assembly number
	 * @return array
	 */
	function get_list () {
		$raw_list = get_posts(['post_type' => 'assembly', 'numberposts' => 1000]);
		$out = array();
		foreach ($raw_list as $assembly) {
			$out[$assembly->post_title] = [
				'name' => get_post_meta($assembly->ID, 'name', true),
				'faithful_navigator' => get_post_meta($assembly->ID, 'faithful_navigator', true),
				'faithful_comptroller' => get_post_meta($assembly->ID, 'faithful_comptroller', true),
				'website' => get_post_meta($assembly->ID, 'website', true),
				'location' => get_post_meta($assembly->ID, 'location', true),
				'featured_img_id' => get_post_thumbnail_id($assembly->ID)
			];
		}
		ksort($out);
		return $out;
	}

	/** Gets the List of All Councils in the Assembly
	 * @param int $assembly_number
	 * @return array
	 */
	function get_councils ($assembly_number) {
		$raw_list = get_posts(['post_type' => 'council', 'meta_key'  => 'assembly', 'meta_value' => $assembly_number, 'numberposts' => 1000]);
		$out = array();
		foreach ($raw_list as $council) {
			$out[] = $council->post_title;
		}
		natsort($out);
		return $out;
	}

	/** Sends the Email from the Send Email Form
	 * Gets the data from the post array
	 * @return boolean
	 */
	function email_assembly_form () {
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
		$to = get_post_meta($post_id, 'contact_email', true);
		if (empty($to)) {
			error_log('kofc-state->type-assembly->email_assembly Cannot send email due to assembly email address being empty.');	
			return false;
		}
		
		$email_sender_name = get_option('otgkofcs_email_sender_name');
		$email_sender_address = get_option('otgkofcs_email_sender_address');
		if (empty($email_sender_address)) {
			error_log('kofc-state->type-assembly->email_assembly Cannot send email due to otgkofcs_email_sender_address option being empty');	
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
			error_log("kofc-state->type-assembly->email_assembly wp_mail error Email - To: $to" . PHP_EOL . "Subject: $subject" . PHP_EOL
					. "Message: $message" . PHP_EOL . 'Headers: ' . print_r($headers, true));
		}
		//error_log("kofc-state->type-assembly->email_assembly wp_mail Debug - To: $to" . PHP_EOL . "Subject: $subject" . PHP_EOL . "Message: $message" . PHP_EOL . 'Headers: ' . print_r($headers, true));
		return $result_send;
	}

}