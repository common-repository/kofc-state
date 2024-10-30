<?php
/** Database Functions
 * @Package			Knights of Columbus State WP Plugin
 * @File				models/star_model.php
 * @Author			Chris Hood (http://chrishood.me)
 * @Link				http://onthegridwebdesign.com
 * @copyright		(c) 2016-2018, On the Grid Web Design LLC
 * @created			12/18/2016
*/

class otgkofcs_Star_Model {

	/** Get List of All Star Council Requirements
	 * @global wpdb $wpdb
	 * @global array $otgkofcs_tables
	 * @return array
	 */
	function star_reqs_list () {
		global $wpdb, $otgkofcs_tables;
		$sql = "SELECT * FROM {$otgkofcs_tables['star_requirements']} ORDER BY display_order ASC";
		return stripslashes_deep($wpdb->get_results($sql, ARRAY_A));
	}	
	
	/** Add a star council requirement
	 * @global wpdb $wpdb
	 * @global array $otgkofcs_tables
	 * @param array $form_data
	 * @return array
	 */
	function add ($new_name) {
		global $wpdb, $otgkofcs_tables;
		$result = $wpdb->insert(
				$otgkofcs_tables['star_requirements'],
				['name' => $new_name, 'display_order' => 100],
				['%s', '%d']
				);
		if (!$result) {
			$return['message'] = ['Could not add requirement.', 3, 2];
			error_log('kofc-state->Star_Model->add: ' . $wpdb->last_error);
		} else {
			$return['message'] = ['Requirement added.', 1, 3];
			$return['star_req_id'] = $wpdb->insert_id;
		}
		return $return;
	}		

	/** Updates a requirements name and order
	 * @global wpdb $wpdb
	 * @global array $otgkofcs_tables
	 * @param array $form_data
	 * @return string
	 */
	function update_req ($data) {
		global $wpdb, $otgkofcs_tables;
		$result = $wpdb->update(
				$otgkofcs_tables['star_requirements'],
				['name' => $data['name'], 'display_order' => $data['display_order']],
				['star_req_id' =>  $data['star_req_id']],
				['%s', '%d'],
				['%d']
		);
		if (!$result) {
			$error = $wpdb->last_error;
			if (empty($error))
				$return['message'] = 'No change.';
			else {
				$return['error'] = 'Could not update requirment record.';
				error_log('kofc-state->Star_Model->update: ' . $error);
			}
		} else {
			$return['message'] = 'Record updated.';
		}
		return $return;
	}	
		
	/** Delete a Requirement's Entry and All Associated Records
	 * @global type $wpdb
	 * @global array $otgkofcs_tables
	 * @param int $id
	 */
	function delete_req ($id) {
		global $wpdb, $otgkofcs_tables;
		return $wpdb->delete($otgkofcs_tables['star_requirements'], ['star_req_id' => $id], ['%d']);
	}
	
	/** Returns an array of the councils
	 * @global wpdb $wpdb
	 * @global array $otgkofcs_tables
	 * @return type
	 */
	function star_reqs_met_list () {
		global $wpdb, $otgkofcs_tables;
		$return = [];
		$sql = "SELECT * FROM {$otgkofcs_tables['star_reqs_met']} ORDER BY council_id ASC";
		$result = stripslashes_deep($wpdb->get_results($sql, ARRAY_A));
		foreach ($result as $row) {
			$star_reqs_met = explode(',', $row['star_reqs_met']);
			$return[$row['council_id']] = $star_reqs_met;
		}
		return $return;
	}	

	/** Clears out the star_reqs_met table
	 * @global wpdb $wpdb
	 * @global array $otgkofcs_tables
	 */
	function star_reqs_met_truncate () {
		global $wpdb, $otgkofcs_tables;
		$sql = "TRUNCATE {$otgkofcs_tables['star_reqs_met']}";
//		$result = stripslashes_deep($wpdb->get_results($sql, ARRAY_A));
	}	

	/** Updates a council's star requirements met record
	 * @global wpdb $wpdb
	 * @global array $otgkofcs_tables
	 * @param int $council_id
	 * @param array $star_reqs_met
	 * @return string
	 */
	function update_reqs_met ($council_id, $star_reqs_met) {
		global $wpdb, $otgkofcs_tables;
		$result = $wpdb->replace(
				$otgkofcs_tables['star_reqs_met'],
				['star_reqs_met' =>  implode(',', $star_reqs_met),	'council_id' =>  $council_id],
				['%s', '%d']
				);
		if (!$result) {
			$error = $wpdb->last_error;
			if (empty($error))
				$return['message'] = 'No change.';
			else {
				$return['error'] = 'Could not update council`s requirements met record.';
				error_log('kofc-state->Star_Model->update_reqs_met: ' . $error);
			}
		} else {
			$return['message'] = 'Record updated.';
		}
		return $return;
	}		
}