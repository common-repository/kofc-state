<?php
/** Database Functions for Knights on the Recruiting Scoreboard
 * @Package			Knights of Columbus State WP Plugin
 * @File				models/recruiting_model.php
 * @Author			Chris Hood (http://chrishood.me)
 * @Link				http://onthegridwebdesign.com
 * @copyright		(c) 2016-2022, On the Grid Web Design LLC
 * @created			7/21/2016
*/

class otgkofcs_Recruiting_Model {
	
	/** Gets a List of Knights for the Scores
	 * @global type $wpdb
	 * @global array $otgkofcs_tables
	 * @return array
	 */
	function knights_list () {
		global $wpdb, $otgkofcs_tables;

		$sql = "SELECT * FROM {$otgkofcs_tables['knights']}";
		return stripslashes_deep($wpdb->get_results($sql, ARRAY_A));
	}
	
	/** Returns All Knight Records Marked As Active
	 * Designed to populate a select element
	 * @global type $wpdb
	 * @global array $otgkofcs_tables
	 * @return array
	 */
	function knights_list_select () {
		global $wpdb, $otgkofcs_tables;

		$sql = "SELECT knight_id, name_first, name_last, council_id
			FROM {$otgkofcs_tables['knights']} WHERE active = 1 ORDER BY name_last ASC";
		return stripslashes_deep($wpdb->get_results($sql, ARRAY_A));
	}
	
	/** Returns All Fields in a Knight Record
	 * @global type $wpdb
	 * @global array $otgkofcs_tables
	 * @param int $knight_id
	 * @return array
	 */
	function knight_get ($knight_id) {
		global $wpdb, $otgkofcs_tables;
		$sql = $wpdb->prepare("SELECT * FROM {$otgkofcs_tables['knights']} WHERE knight_id = %d", $knight_id);
		return stripslashes_deep($wpdb->get_row($sql, ARRAY_A));
	}

	/** Add a Knight Record
	 * @global type $wpdb
	 * @global array $otgkofcs_tables
	 * @param array $data
	 * @return array
	 */
	function knight_add ($data) {
		global $wpdb, $otgkofcs_tables;
		if (empty($data['score'])) $data['score'] = 0;
		$result = $wpdb->insert(
				$otgkofcs_tables['knights'],
				['name_first' => $data['name_first'], 'name_last' => $data['name_last'],
				'council_id' => $data['council_id'], 'score' => $data['score']],
				['%s', '%s', '%d', '%d']
		);
		if (!$result) {
			$return['error'] = 'Could not add knight.';
			error_log('kofc-state->Recruiting_Model->knight_add. ' . $wpdb->last_error);
		} else {
			$return['message'] = 'Knight added.';
			$return['knight_id'] = $wpdb->insert_id;
		}
		return $return;
	}	

	/** Update a Knight's Record
	 * @global type $wpdb
	 * @global array $otgkofcs_tables
	 * @param array $data
	 * @return array
	 */
	function knight_update ($data) {
		global $wpdb, $otgkofcs_tables;
		$result = $wpdb->update(
				$otgkofcs_tables['knights'],
				['name_first' => $data['name_first'], 'name_last' => $data['name_last'],
				'council_id' => $data['council_id'], 'score' => $data['score']],
				['knight_id' =>  $data['knight_id']],
				['%s', '%s', '%d', '%d'],
				['%d']
		);
		if (!$result) {
			$error = $wpdb->last_error;
			if (empty($error))
				$return['message'] = 'No change.';
			else {
				$return['error'] = 'Could not update knight record.';
				error_log('kofc-state->Recruiting_Model->knight_update. ' . $error);
			}
		} else {
			$return['message'] = 'Record updated.';
		}
		return $return;
	}
	
	/** Sets all Knights Scores to 0
	 * @global type $wpdb
	 * @global array $otgkofcs_tables
	 * @return int/boolean
	 */
	function knights_reset_score () {
		global $wpdb, $otgkofcs_tables;
		return $wpdb->query("UPDATE {$otgkofcs_tables['knights']} SET score = 0");
	}	
		
	/** Delete a Knight's Entry and All Associated Scores
	 * @global type $wpdb
	 * @global array $otgkofcs_tables
	 * @param int $id
	 */
	function knight_delete ($id) {
		global $wpdb, $otgkofcs_tables;
		return $wpdb->delete($otgkofcs_tables['knights'], ['knight_id' => $id], ['%d']);
	}
	
}
