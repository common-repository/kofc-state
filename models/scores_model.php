<?php
/** Database Functions for Scores
 * @Package			Knights of Columbus State WP Plugin
 * @File				models/scores_model.php
 * @Author			Chris Hood (http://chrishood.me)
 * @Link				http://onthegridwebdesign.com
 * @copyright		(c) 2016-2022, On the Grid Web Design LLC
 * @created			7/24/2016
*/

class otgkofcs_Scores_Model {

	/** Query for the Frontend Scores
	 * @global wpdb $wpdb
	 * @global array $otgkofcs_tables
	 * @return array
	 */
	function knight_scores_for_front () {
		global $wpdb, $otgkofcs_tables;

		// ***** Query *****
		$sql = "SELECT knight_id, name_first, name_last, council_id, score FROM {$otgkofcs_tables['knights']}
			WHERE score > 0 ORDER BY score DESC, name_last ASC";
		return stripslashes_deep($wpdb->get_results($sql, ARRAY_A));
	}	
	
	/** Updates (Creates if new) a Knight's Score
	 * @global wpdb $wpdb
	 * @global array $otgkofcs_tables
	 * @param int $knight_id
	 * @param int $score
	 * @return int/boolean
	 */
	function knight_set_score ($knight_id, $score) {
		global $wpdb, $otgkofcs_tables;
		$result = $wpdb->update(
				$otgkofcs_tables['knights'],
				['score' => $score],
				['knight_id' =>  $knight_id],
				['%d'],
				['%d']
				);
		
		// *** DB Error Handling ***
		if (!$result) {
			error_log('kofc-state->Scores_Model->knights_set_score. Knight_id: ' . $knight_id . '  ' . $wpdb->last_error);
		}
		return $result;
	}
	
	/** Gets a List of all Councils Scores
	 * @global wpdb $wpdb
	 * @global array $otgkofcs_tables
	 * @param string $order_by
	 * @param string $order_direction
	 * @return array
	 */
	function council_scores ($order_by='council_id', $order_direction = 'ASC') {
		global $wpdb, $otgkofcs_tables;

		// ***** Query Security *****
		if ('ASC' != $order_direction) $order_direction = 'DESC';
		if (!in_array($order_by, array('council_id', 'score', 'percentage'))) $order_by = 'council_id';
		if ('score' == $order_by) {
			$order = "score $order_direction, council_id ASC";
		} elseif ('percentage' == $order_by) {
			$order = "percentage $order_direction, council_id ASC";
		} else {
			$order = "$order_by $order_direction";
		}
		
		// ***** Query *****
		$sql = "SELECT council_id, score, membership, (score/membership)*100 AS percentage
			FROM {$otgkofcs_tables['council_scores']} ORDER BY $order";
		$result = stripslashes_deep($wpdb->get_results($sql, ARRAY_A));
		$return = array();
		foreach ($result as $row) {
			$return[$row['council_id']] = $row;
		}
		return $return;
	}
	
	/** Updates (creates if new) a council's Score
	 * @global wpdb $wpdb
	 * @global array $otgkofcs_tables
	 * @param int $council_id
	 * @param int $score
	 * @param int $membership
	 * @return int/boolean
	 */
	function council_set_score ($council_id, $score, $membership) {
		global $wpdb, $otgkofcs_tables;
		$result = $wpdb->replace(
				$otgkofcs_tables['council_scores'], 
				['council_id' => $council_id, 'score' => $score, 'membership' => $membership],
				['%d', '%d', '%d']
				);
		
		// *** DB Error Handling ***
		if (!$result) {
			$return['error'] = 'Could not add score for ' . $council_id;
			error_log('kofc-state->Scores_Model->councils_set_score. Knight_id: ' . $council_id . '  ' . $wpdb->last_error);
		}
		return $result;
	}

	/** Creates a list of all fields (score and deputy name)
	 * @global wpdb $wpdb
	 * @global array $otgkofcs_tables
	 * @return array
	 */
	function district_scores ($order_by='district_id', $order_direction = 'ASC') {
		global $wpdb, $otgkofcs_tables;
		
		// ***** Query Security *****
		if ('ASC' != $order_direction) $order_direction = 'DESC';
		if (!in_array($order_by, array('district_id', 'score', 'deputy'))) $order_by = 'district_id';
		if ('score' == $order_by) {
			$order = "score $order_direction, district_id ASC";
		} else {
			$order = "$order_by $order_direction";
		}

		$sql = "SELECT district_id, score, deputy FROM {$otgkofcs_tables['district_scores']} ORDER BY $order";
		$result = stripslashes_deep($wpdb->get_results($sql, ARRAY_A));
		$return = array();
		foreach ($result as $row) {
			$return[$row['district_id']]['score'] = $row['score'];
			$return[$row['district_id']]['deputy'] = $row['deputy'];
		}
		return $return;
	}
	
	/** Updates (creates if new) a district's score and deputy
	 * @global wpdb $wpdb
	 * @global array $otgkofcs_tables
	 * @param int $district_id
	 * @param int $score
	 * @param string $deputy
	 * @return int/boolean
	 */
	function district_set_score ($district_id, $score, $deputy) {
		global $wpdb, $otgkofcs_tables;
		$result = $wpdb->replace(
				$otgkofcs_tables['district_scores'], 
				['district_id' => $district_id, 'score' => $score, 'deputy' => $deputy],
				['%d', '%d', '%s']
				);				
	
		// *** DB Error Handling ***
		if (!$result) {
			$return['error'] = 'Could not add score for ' . $district_id;
			error_log('kofc-state->Scores_Model->districts_set_score. Knight_id: ' . $district_id . '  ' . $wpdb->last_error);
		}
		return $result;
	}	

	/** Removes unnecessary DB entries
	 * @global wpdb $wpdb
	 * @global array $otgkofcs_tables
	 */
	function cleanup () {
		global $wpdb, $otgkofcs_tables;
		$wpdb->delete($otgkofcs_tables['district_scores'], array('score' => 0, 'deputy' => ''));
	}
	
}
