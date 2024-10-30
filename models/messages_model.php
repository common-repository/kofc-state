<?php
/** Messages Database Functions
 * @Package			Knights of Columbus State WP Plugin
 * @File				models/messages_model.php
 * @Author			Chris Hood (http://chrishood.me)
 * @Link				http://onthegridwebdesign.com
 * @copyright		(c) 2016-2022, On the Grid Web Design LLC
 * @created			4/11/2018
*/
class otgkofcs_Messages_Model {

	/** Gets a List of the Messages	 *
	 * @global wpdb $wpdb
	 * @global array $otgkofcs_tables
	 * @return array
	 */
	function get_list () {
		global $wpdb, $otgkofcs_tables;
		$posts_table = $wpdb->prefix . 'posts';
		
		$sql = "SELECT {$otgkofcs_tables['messages']}.message_id,  $posts_table.post_title AS `to`, {$otgkofcs_tables['messages']}.name, {$otgkofcs_tables['messages']}.email, {$otgkofcs_tables['messages']}.date, {$otgkofcs_tables['messages']}.subject"
		. " FROM {$otgkofcs_tables['messages']} JOIN $posts_table ON $posts_table.ID = {$otgkofcs_tables['messages']}.to_id";
		return stripslashes_deep($wpdb->get_results($sql, ARRAY_A));
	}
	
	/** Returns All Fields in a Messages Record
	 * @global wpdb $wpdb
	 * @global array $otgkofcs_tables
	 * @param int $message_id
	 * @return array
	 */
	function get ($message_id) {
		global $wpdb, $otgkofcs_tables;
		$sql = $wpdb->prepare("SELECT * FROM {$otgkofcs_tables['messages']} WHERE message_id = %d", $message_id);
		return stripslashes_deep($wpdb->get_row($sql, ARRAY_A));
	}

	/** Store a Message
	 * @global type $wpdb
	 * @global array $otgkofcs_tables
	 * @param array $data
	 * @return boolean
	 */
	function add ($data) {
		global $wpdb, $otgkofcs_tables;
		$result = $wpdb->insert(
				$otgkofcs_tables['messages'],
				['to_id' => $data['to_id'], 'name' => $data['name'], 'email' => $data['email'], 'subject' => $data['subject'], 'message' => $data['message'], 'ip' => $data['ip']],
				['%d', '%s', '%s', '%s', '%s', '%s']
		);
		if (!$result) {
			error_log('kofc-state->Messages_Model->add. ' . $wpdb->last_error);
			return false;
		}
		return true;
	}	

	/** Delete a Message
	 * @global type $wpdb
	 * @param int $id
	 */
	function delete ($id) {
		global $wpdb, $otgkofcs_tables;
		$result = $wpdb->delete($otgkofcs_tables['messages'], ['message_id' => $id], ['%d']);
		if (empty($result))
			return false;
		else
			return true;
	}

}
