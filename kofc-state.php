<?php
/* Provides a directory for Knights and Councils along with recruiting scoreboards
  Plugin Name: Knights of Columbus State Council
  Plugin URI: https://onthegridwebdesign.com/software
  Description: Functionality for State Councils Including Recruiting Scoreboards
  Author: Chris Hood, On The Grid Web Design LLC
  Version: 2.5.0
  Author URI: https://onthegridwebdesign.com
  Updated: 9/10/2024 Created: 7/21/2016
 */

// ****** Global Settings *****
global $wpdb;
$otgkofcs_tables = [
	'knights' => $wpdb->prefix . 'otgkofcs_knights',
	'council_scores' => $wpdb->prefix . 'otgkofcs_council_scores',
	'district_scores' => $wpdb->prefix . 'otgkofcs_district_scores',
	'star_requirements' => $wpdb->prefix . 'otgkofcs_star_requirements',
	'star_reqs_met' => $wpdb->prefix . 'otgkofcs_star_reqs_met',
	'messages' => $wpdb->prefix . 'otgkofcs_messages'
];
define('OTGKOFCS_ROOT_URL',  plugins_url(null, __FILE__) . '/');
define('OTGKOFCS_ROOT_PATH',  plugin_dir_path(__FILE__));

// ***** Register Stuff *****
register_activation_hook(__FILE__, 'otgkofcs_install');
add_action('wp_loaded', 'otgkofcs_scripts');

// ***** Custom Post Types *****
require_once(OTGKOFCS_ROOT_PATH . 'type-council.php');
$otgkofcs_Council = new otgkofcs_Council_Type();
require_once(OTGKOFCS_ROOT_PATH . 'type-knight.php');
$otgkofcs_Knight = new otgkofcs_Knight_Type();
require_once(OTGKOFCS_ROOT_PATH . 'type-assembly.php');
$otgkofcs_Assembly = new otgkofcs_Assembly_Type();
add_filter('single_template', 'template');

// ***** Shortcodes *****
include(OTGKOFCS_ROOT_PATH . 'shortcodes.php');
add_shortcode('otgkofcs', 'otgkofcs_scoreboard'); // To be removed
add_shortcode('otgkofcs_scoreboard', 'otgkofcs_scoreboard');
add_shortcode('otgkofcs_star_reqs', 'otgkofcs_star_reqs');
add_shortcode('otgkofcs_council_directory', 'otgkofcs_council_table'); // For those using old shortcode name
add_shortcode('otgkofcs_council_table', 'otgkofcs_council_table');
add_shortcode('otgkofcs_assembly_table', 'otgkofcs_assembly_table');
add_shortcode('otgkofcs_council_box_list', 'otgkofcs_council_box_list');
add_shortcode('otgkofcs_assembly_box_list', 'otgkofcs_assembly_box_list');

// ***** Admin Pages *****
if (is_admin()) {
	include(OTGKOFCS_ROOT_PATH . 'admin.php');
	add_action('admin_menu', 'otgkofcs_admin');
	add_action('admin_enqueue_scripts', 'otgkofcs_admin_styles_and_scripts');
}

/** Load CSS and JS Files
 */	
function otgkofcs_scripts () {
	wp_register_style('otgkofcs_css', plugins_url('kofc-state.min.css', __FILE__));
	wp_enqueue_style('otgkofcs_css');
	wp_enqueue_script('jquery');
	wp_enqueue_script('otgkofcs_scripts', plugins_url('kofc-state.min.js', __FILE__));
}

/** Changes the Single Page to Ones for Post Types
 * @global array $post
 * @param string $single
 * @return string
 */
function template ($single) {
	global $post;
	if ($post->post_type == 'council')
		return plugin_dir_path(__FILE__) . 'views/council.php';
	elseif ($post->post_type == 'knight')
		return plugin_dir_path(__FILE__) . 'views/knight.php';
	elseif ($post->post_type == 'assembly')
		return plugin_dir_path(__FILE__) . 'views/assembly.php';
	return $single;
}

/** Install & Update Function
 * @global type $wpdb
 * @global string $table_knights
 * @global string $table_councils
 * @global string $table_periods
 * @global string $table_knight_scores
 * @global string $table_council_scores
 */
function otgkofcs_install() {
	global $wpdb;
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	$table_knights = $wpdb->prefix . 'otgkofcs_knights';
	$table_council_scores = $wpdb->prefix . 'otgkofcs_council_scores';
	$table_district_scores = $wpdb->prefix . 'otgkofcs_district_scores';
	$table_star_requirements = $wpdb->prefix . 'otgkofcs_star_requirements';
	$table_star_reqs_met = $wpdb->prefix . 'otgkofcs_star_reqs_met';
	$table_messages = $wpdb->prefix . 'otgkofcs_messages';
	$charset_collate = $wpdb->get_charset_collate();
			
	// *** Knights Table ***
	$sql_knights = "CREATE TABLE $table_knights (
		knight_id mediumint(9) NOT NULL AUTO_INCREMENT,
		name_first varchar(50) NOT NULL,
		name_last varchar(50) NOT NULL,
		council_id mediumint(9),
		score mediumint(9),
		PRIMARY KEY knight_id (knight_id)
		) $charset_collate;";
	dbDelta($sql_knights);

	// *** Council Score Table ***
	$sql_council_scores = "CREATE TABLE $table_council_scores (
		council_id mediumint(9) NOT NULL,
		score mediumint(9) NOT NULL,
		membership mediumint(9) NOT NULL,
		PRIMARY KEY (`council_id`)
		) $charset_collate;";
	dbDelta($sql_council_scores);

	// *** District Score Table ***
	$sql_district_scores = "CREATE TABLE $table_district_scores (
		district_id mediumint(9) NOT NULL,
		deputy varchar (100) NOT NULL,
		score mediumint(9) NOT NULL,
		PRIMARY KEY (`district_id`)
		) $charset_collate;";
	dbDelta($sql_district_scores);
	if (!get_option('otgkofcs_number_of_districts')) update_option('otgkofcs_number_of_districts', 12);
	
	// *** Star Council Requiments Table ***
	$sql_star_requirements = "CREATE TABLE $table_star_requirements (
		star_req_id mediumint(9) NOT NULL AUTO_INCREMENT,
		name varchar (250) NOT NULL,
		display_order mediumint(9),
		PRIMARY KEY (`star_req_id`)
		) $charset_collate;";
	dbDelta($sql_star_requirements);
	
	// *** Star Council Requiments Met Table ***
	$sql_star_reqs_met = "CREATE TABLE $table_star_reqs_met (
		council_id mediumint(9) NOT NULL,
		star_reqs_met varchar(500) NOT NULL,
		PRIMARY KEY (`council_id`)
		) $charset_collate;";
	dbDelta($sql_star_reqs_met);
	
	// *** Messages Table ***
	$sql_messages = "CREATE TABLE $table_messages (
		message_id mediumint(9) NOT NULL AUTO_INCREMENT,
		to_id mediumint(9) NOT NULL,
		name varchar(200),
		email varchar(200),
		subject varchar(200),
		date datetime DEFAULT CURRENT_TIMESTAMP,
		message text,
		ip varchar(20),
		PRIMARY KEY (`message_id`)
		) $charset_collate;";
	dbDelta($sql_messages);	
}

/**
 * @param string $message
 * @param array $arr
 */
function otg_log ($message, $arr = null) {
	$out = '';
	if (is_array($arr))
		$out = print_r($out, true);
	error_log('[' . date('Y-m-d H:i:s') . "] $message $out" . PHP_EOL, 3, WP_CONTENT_DIR . '/debug.log');
}
