<?php
/** Admin Pages Controller
 * @Package			Knights of Columbus State WP Plugin
 * @File				admin.php
 * @Author			Chris Hood (http://chrishood.me)
 * @Link				http://onthegridwebdesign.com
 * @copyright		(c) 2016-2024, On the Grid Web Design LLC
 * @created			7/21/2016
 */

/** Page to View and Edit District Scores and Names of Deputies
 */
function otgkofcs_scores_coun_dist_page () {
	// ***** Security Check *****
	if (!current_user_can('publish_posts')) {
		wp_die(__('You do not have sufficient permissions to access this page.'));
	}

	// ***** Load Models, Helpers and Libraries *****
	global $otgkofcs_Council;
	require_once(OTGKOFCS_ROOT_PATH . 'models/scores_model.php');
	$otgkofcs_Scores_Model = new otgkofcs_Scores_Model();
	require_once(OTGKOFCS_ROOT_PATH . 'helpers/view_helper.php');
	require_once(OTGKOFCS_ROOT_PATH . 'helpers/filter_helper.php');

	$message_list = array();
	
	if (isset($_POST['_wpnonce'])) {
		// *** Council Scores form Submitted ***
		check_admin_referer('scores_coun_dist');
	
		// *** Council Scores ***
		$membership_list = otgkofcs_get_request_str_array('membership');
		foreach (otgkofcs_get_request_int_array('c_score') as $council_id => $score) {
			// ***** Check Input *****
			if (!ctype_digit($council_id) && !is_int($council_id))
				continue;
			if (0 > $score || (!ctype_digit($score) && !is_int($score)))
				$score = 0;
			if (0 > $membership_list || (!ctype_digit($membership_list[$council_id]) && !is_int($membership_list[$council_id])))
				$membership_list[$council_id] = 0;
			
			$result = $otgkofcs_Scores_Model->council_set_score($council_id, $score, $membership_list[$council_id]);
			if (!empty($result['error']))
				$message_list[] = [$result['error'], 3 , 2];
		}
		
		$deputy_list = otgkofcs_get_request_str_array('deputy');
		foreach (otgkofcs_get_request_str_array('d_score') as $key => $value) {
			if (0 > $value)
				$value = 0;
			$result = $otgkofcs_Scores_Model->district_set_score($key, $value, $deputy_list[$key]);
			if (!empty($result['error']))
				$message_list[] = [$result['error'], 3, 2];
		}
		$message_list[] = ['Scores Updated', 1, 3];
		$otgkofcs_Scores_Model->cleanup();
	}

	// ***** Get Data *****
	$council_list = $otgkofcs_Council->get_list();
	$council_scores = $otgkofcs_Scores_Model->council_scores();
	$district_list = $otgkofcs_Scores_Model->district_scores();

	// ***** Call View *****
	include(OTGKOFCS_ROOT_PATH . 'views/admin/scores_coun_dist.php');
}

/** Shows the page and handles bulk actions
 */
function otgkofcs_knights_list_page () {
	// ***** Security Check *****
	if (!current_user_can('publish_posts')) {
		wp_die(__('You do not have sufficient permissions to access this page.'));
	}

	// ***** Load Models, Helpers and Libraries *****
	global $otgkofcs_Council;
	require_once(OTGKOFCS_ROOT_PATH . 'models/recruiting_model.php');
	$Recruiting_Model = new otgkofcs_Recruiting_Model();
	require_once(OTGKOFCS_ROOT_PATH . 'models/scores_model.php');
	$Score_Model = new otgkofcs_Scores_Model();
	require_once(OTGKOFCS_ROOT_PATH . 'helpers/view_helper.php');
	require_once(OTGKOFCS_ROOT_PATH . 'helpers/filter_helper.php');

	$message_list = array();

	// ***** Run Bulk Actions if Submitted *****
	if (isset($_POST['action'])) {
		$action = otgkofcs_get_request_string('action');
		check_admin_referer('knights_list');

		if ('score' == $action) {
			$score_list = otgkofcs_get_request_int_array('score');
			$count = 0;
			foreach ($score_list as $knight_id => $score) {
				$result = $Score_Model->knight_set_score($knight_id, $score);
				if (!empty($result))
					$count++;
			}
			if (1 == $count)
				$message_list[] = ['1 Score Updated', 1, 3];
			else
				$message_list[] = ["$count Scores Updated", 1, 3];
		}
		
		if ('delete' == $action) {
			$bulk_action_list = otgkofcs_get_request_int_array();
			$delete_count = 0;
			foreach ($bulk_action_list as $listing_id) {
				$result = $Recruiting_Model->knight_delete($listing_id);
				if (!empty($result))
					$delete_count++;
			}
			if (1 == $delete_count)
				$message_list[] = ['1 Knight Deleted', 1, 3];
			else
				$message_list[] = ["$delete_count Knights Deleted", 1, 3];
		}
	}

	// ***** Add Knight Record if Submitted *****
	if (!empty($_POST['name_first'])) {
		check_admin_referer('knights_quick_add');
		$result = $Recruiting_Model->knight_add([
				'name_first' => otgkofcs_get_request_string('name_first'),
				'name_last' => otgkofcs_get_request_string('name_last'),
				'council_id' => otgkofcs_get_request_string('council_id'),
				'score' => otgkofcs_get_request_string('score')
		]);
		if (empty($result['error'])) {
			$message_list[] = [$result['message'], 1, 3];
		} else {
			$message_list[] = [$result['error'], 3, 1];
		}
	}
	
	// ***** Reset Knight Scores if Submitted *****
	if (!empty($_POST['reset_scores'])) {
		check_admin_referer('knights_reset_scores');
		$result = $Recruiting_Model->knights_reset_score();
		if (false === $result) {
			$message_list[] = [$result['message'], 3, 1];
		} else {
			$message_list[] = ['Knight Scores Reset', 1, 3];
		}
	}

	// ***** Get Data *****
	$knights_list = $Recruiting_Model->knights_list();
	$council_list = $otgkofcs_Council->get_list();
	
	// ***** Call View *****
	include(OTGKOFCS_ROOT_PATH . 'views/admin/knights_list.php');
}

/** Handle showing and processing the knight record edit form
 */
function otgkofcs_knight_edit () {
	// ***** Security Check *****
	if (!current_user_can('publish_posts')) {
		wp_die(__('You do not have sufficient permissions to access this page.'));
	}

	// ***** Load Models, Helpers and Libraries *****
	global $otgkofcs_Council;
	require_once(OTGKOFCS_ROOT_PATH . 'models/recruiting_model.php');
	$Recruiting_Model = new otgkofcs_Recruiting_Model();
	require_once(OTGKOFCS_ROOT_PATH . 'helpers/view_helper.php');
	require_once(OTGKOFCS_ROOT_PATH . 'helpers/filter_helper.php');
	
	$message_list = array();

	$knight_id = otgkofcs_get_request_int('knight_id');
	if (!empty($_POST['name_first'])) {
		// ***** Assemble Data Form Submitted *****
		$data = [
			'knight_id' => $knight_id,
			'name_first' => otgkofcs_get_request_string('name_first'),
			'name_last' => otgkofcs_get_request_string('name_last'),
			'council_id' => otgkofcs_get_request_int('council_id'),
			'score' => otgkofcs_get_request_int('score')
		];

		// ***** Check Nonce and Add/Update *****
		if (empty($knight_id)) {
			check_admin_referer('knight_add');
			$result = $Recruiting_Model->knight_add($data);
		} else {
			check_admin_referer('knight_edit_' . $knight_id);
			$result = $Recruiting_Model->knight_update($data);
		}
		if (!empty($result['error'])) {
			$message_list[] = [$result['error'], 3, 2];
		} else {
			$message_list[] = [$result['message'], 1, 3];
			if (empty($knight_id))
				$knight_id = $result['knight_id'];
		}
	}

	if (!empty($knight_id)) {
		$record = $Recruiting_Model->knight_get($knight_id);
		if (!$record)
			wp_die(__('Invalid id.'));
	} elseif (!empty($data)) {
		$record = $data;
	} else {
		$record = ['knight_id' => null, 'name_first' => null, 'name_last' => null, 'council_id' => null, 'score' => null];
	}
	$council_list = $otgkofcs_Council->get_list();
	include(OTGKOFCS_ROOT_PATH . 'views/admin/knight_edit.php');
}

/** Shows and handles the options page
 */
function otgkofcs_options_page () {
	// ***** Security Check *****
	if (!current_user_can('add_users')) {
		wp_die(__('You do not have sufficient permissions to access this page.'));
	}

	// ***** Load Models, Helpers and Libraries *****
	require_once(OTGKOFCS_ROOT_PATH . 'helpers/view_helper.php');
	require_once(OTGKOFCS_ROOT_PATH . 'helpers/filter_helper.php');
	
	$message_list = array();
	$option_list = ['otgkofcs_number_of_districts', 'otgkofcs_star_table_top_offset', 'otgkofcs_email_sender_name', 'otgkofcs_email_sender_address',
			'otgkofcs_hcaptcha_site_key', 'otgkofcs_hcaptcha_secret_key'];

	if (isset($_POST['_wpnonce'])) {
		// ***** Save Options *****
		$options_updated = 0;
		foreach ($option_list as $option) {
			check_admin_referer('options');
			if (update_option($option, otgkofcs_get_request_string($option)))
				$options_updated++;
		}
		if (1 > $options_updated)
			$message_list[] = ['No Options Updated', 2, 3];
		elseif (1 == $options_updated)
			$message_list[] = ['One Option Updated', 1, 3];
		else
			$message_list[] = [$options_updated . ' Options Updated', 1, 3];
	}

	// ***** Get Options for View *****
	foreach ($option_list as $option) {
		$options[$option] = get_option($option);
	}

	// ***** Call View *****
	include(OTGKOFCS_ROOT_PATH . 'views/admin/options.php');
}

/** Add, Delete, Update and Reorder Star Council Requirement List
 */
function otgkofcs_star_reqs_list_page () {
	// ***** Security Check *****
	if (!current_user_can('publish_posts')) {
		wp_die(__('You do not have sufficient permissions to access this page.'));
	}

	// ***** Load Models, Helpers and Libraries *****
	require_once("models/star_model.php");
	$otgkofcs_Star_Model = new otgkofcs_Star_Model();
	require_once(OTGKOFCS_ROOT_PATH . 'helpers/view_helper.php');
	require_once(OTGKOFCS_ROOT_PATH . 'helpers/filter_helper.php');

	$message_list = array();

	// ***** Update Requirement Records if Submitted *****
	if (!empty($_POST['name'])) {
		check_admin_referer('star_reqs');

		$i=1;
		$req_list = otgkofcs_get_request_str_array('name');
		foreach ($req_list as $star_req_id => $name) {
			$data = array('star_req_id'=>$star_req_id, 'name'=>$name, 'display_order'=>$i);
			$result = $otgkofcs_Star_Model->update_req($data);
			if (!empty($result['error'])) $message_list[] = [$result['error'], 3, 2];
			$i++;
		}
		if (empty($message_list))
			$message_list[] = ['Requirements updated.', 1, 3];
	}
	
	// ***** Add Requirement Record if Submitted *****
	$new_name = otgkofcs_get_request_string('new_name');
	if (!empty($new_name)) {
		check_admin_referer('star_reqs_add');
		$result = $otgkofcs_Star_Model->add($new_name);
		$message_list[] = $result['message'];
	}
	
	// ***** Delete Requirement Record if Submitted *****
	$star_req_id = otgkofcs_get_request_int('delete');
	if (!empty($star_req_id)) {
		$result = $otgkofcs_Star_Model->delete_req($star_req_id);
		if (false === $result)
			$message_list[] = ['Unable to delete requirement #' . $star_req_id, 3, 2];
		elseif (0 == $result)
			$message_list[] = ['Unable to find requirement #' . $star_req_id, 2, 2];
		else
			$message_list[] = ['Requirement deleted.', 1, 3];
	}	

	// ***** Get Data *****
	$star_reqs_list = $otgkofcs_Star_Model->star_reqs_list();
	
	// ***** Call View *****
	include(OTGKOFCS_ROOT_PATH . 'views/admin/star_reqs.php');
}

/** Shows the page and handles bulk actions
 */
function otgkofcs_messages_list_page () {
	// ***** Security Check *****
	if (!current_user_can('publish_posts')) {
		wp_die(__('You do not have sufficient permissions to access this page.'));
	}

	// ***** Load Models, Helpers and Libraries *****
	require_once(OTGKOFCS_ROOT_PATH . 'models/messages_model.php');
	$Messages_Model = new otgkofcs_Messages_Model();
	require_once(OTGKOFCS_ROOT_PATH . 'helpers/view_helper.php');
	require_once(OTGKOFCS_ROOT_PATH . 'helpers/filter_helper.php');

	$message_list = array();

	// ***** Run Bulk Actions if Submitted *****
	if (isset($_POST['action'])) {
		check_admin_referer('messages_list');
		$action = otgkofcs_get_request_string('action');
		$bulk_action_list = otgkofcs_get_request_int_array();

		if ('delete' == $action) {
			$count = 0;
			foreach ($bulk_action_list as $message_id) {
				$result = $Messages_Model->delete($message_id);
				if (!empty($result))
					$count++;
			}
			if (1 == $count)
				$message_list[] = ['Message Deleted', 1, 3];
			else
				$message_list[] = [$count . ' Messages Deleted', 1, 3];
		}
	}

	// ***** Get Data *****
	$email_list = $Messages_Model->get_list();

	// ***** Call View *****
	include(OTGKOFCS_ROOT_PATH . 'views/admin/messages_list.php');
}

/** Show the Page to View a Message
 */
function otgkofcs_message_page () {
	// ***** Security Check *****
	if (!current_user_can('publish_posts')) {
		wp_die(__('You do not have sufficient permissions to access this page.'));
	}

	// ***** Load Models, Helpers and Libraries *****
	require_once(OTGKOFCS_ROOT_PATH . 'models/messages_model.php');
	$otgkofcs_Messages_Model = new otgkofcs_Messages_Model();
	require_once(OTGKOFCS_ROOT_PATH . 'helpers/filter_helper.php');

	// ***** Check for Get Variables *****
	$message_id = otgkofcs_get_request_int('message_id');

	// ***** Get Data *****
	$message = $otgkofcs_Messages_Model->get($message_id);
	$message['to_name'] = get_the_title($message['to_id']);

	// ***** Call View *****
	include(OTGKOFCS_ROOT_PATH . 'views/admin/message_view.php');
}

/** Add, Delete, Update and Reorder Star Council Requirement List
 */
function otgkofcs_scores_star_council_page () {
	// ***** Security Check *****
	if (!current_user_can('publish_posts')) {
		wp_die(__('You do not have sufficient permissions to access this page.'));
	}

	// ***** Load Models, Helpers and Libraries *****
	global $otgkofcs_Council;
	require_once(OTGKOFCS_ROOT_PATH . 'models/star_model.php');
	$otgkofcs_Star_Model = new otgkofcs_Star_Model();
	require_once(OTGKOFCS_ROOT_PATH . 'helpers/view_helper.php');
	require_once(OTGKOFCS_ROOT_PATH . 'helpers/filter_helper.php');

	$message_list = array();
	$councils_list = $otgkofcs_Council->get_list();

	// ***** Update Star Council Checklist if Submitted *****
	if (isset($_POST['_wpnonce'])) {
		check_admin_referer('star_scores');
		$otgkofcs_Star_Model->star_reqs_met_truncate();
		foreach ($councils_list as $council_id => $value) {
			if (!empty($_POST["starreq_$council_id"])) {
				$reqs_met = array_keys(otgkofcs_get_request_str_array("starreq_$council_id"));
				$result = $otgkofcs_Star_Model->update_reqs_met($council_id, $reqs_met);
				if (!empty($result['error']))
					$message_list = [$result['error'], 3, 2];
			}
		}
		if (empty($message_list))
			$message_list[] = ["All councils' requirements met updated.", 1, 3];
	}

	// ***** Get Data *****
	$star_reqs_list = $otgkofcs_Star_Model->star_reqs_list();
	$star_reqs_met_list = $otgkofcs_Star_Model->star_reqs_met_list();

	// ***** Call View *****
	include(OTGKOFCS_ROOT_PATH . 'views/admin/star_scores.php');
}

/** Register Admin Pages
 */
function otgkofcs_admin () {
	add_options_page('KofC State Plugin', 'KofC State Plugin', 'manage_options', 'kofc-state-options', 'otgkofcs_options_page');

	add_menu_page('Scoreboard', 'Scoreboard', 'publish_posts', 'kofc-state', 'otgkofcs_knights_list_page', '', 8);
	add_submenu_page('kofc-state', 'Recruiting Scores - Knights', 'Knights', 'publish_posts', 'kofc-state', 'otgkofcs_knights_list_page');
	add_submenu_page(null, 'Edit Knight', 'Knights', 'publish_posts', 'kofc-state-knight-edit', 'otgkofcs_knight_edit');
	add_submenu_page('kofc-state', 'Recruiting Scores - Council & District', 'Councils & Districts', 'publish_posts', 'kofc-state-scores-councils-districts', 'otgkofcs_scores_coun_dist_page');
	add_submenu_page('kofc-state', 'Star Council', 'Star Council', 'publish_posts', 'kofc-state-scores-star-council', 'otgkofcs_scores_star_council_page');
	add_submenu_page('kofc-state', 'Scoreboard - Star Council Requirements', 'Star Council Requirements', 'publish_posts', 'kofc-state-starreq', 'otgkofcs_star_reqs_list_page');

	add_menu_page('Inbound Messages', 'Inbound Messages', 'publish_posts', 'kofc-state-messages', 'otgkofcs_messages_list_page', '', 9);
	add_submenu_page('kofc-state-messages', '', '', 'publish_posts', 'kofc-state-message-view', 'otgkofcs_message_page');
	
	add_filter('plugin_action_links_kofc-state/kofc-state.php', 'otgkofcs_add_settings_link');	
}

/** Add the Settings Link to Plugins Page
 * @param array $links
 * @return array
 */
function otgkofcs_add_settings_link ($links) {
	$settings_link = '<a href="options-general.php?page=kofc-state-options">' . __( 'Settings' ) . '</a>';
	array_unshift($links, $settings_link);
	return $links;
}

/** Load Styles and Javascript Files Used on Admin Pages
 */
function otgkofcs_admin_styles_and_scripts () {
	wp_register_style('otgcalgs_datatables_css', plugins_url('datatables.min.css', __FILE__));
	wp_enqueue_style('otgcalgs_datatables_css');
	wp_enqueue_script('otgcalgs_datatables', plugins_url('datatables.min.js', __FILE__));
	wp_register_style('otgkofcs_jquery-ui-smoothness', plugins_url('jquery-ui-smoothness-1.11.4.min.css', __FILE__));
	wp_enqueue_style('otgkofcs_jquery-ui-smoothness');
	wp_enqueue_script('jquery-ui-sortable');
}
