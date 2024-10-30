<?php
/** Shortcodes Controller
 * @Package			Knights of Columbus State WP Plugin
 * @File				shortscodes.php
 * @Author			Chris Hood (http://chrishood.me)
 * @Link				https://onthegridwebdesign.com
 * @copyright		(c) 2016-2022, On the Grid Web Design LLC
 * @created			7/21/2016
*/

/** Generates the output for the recruiting scoreboards
 * @param array $attributes
 * @param string $content
 * @return string
 */
function otgkofcs_scoreboard ($attributes, $content = null) {
	global $otgkofcs_Council;
	// ***** Load Models, Helpers and Libraries *****
	if (empty($otgkofcs_Scores_Model)) {
		require_once(OTGKOFCS_ROOT_PATH . 'models/scores_model.php');
		$otgkofcs_Scores_Model = new otgkofcs_Scores_Model();
	}

	// ***** Get Attributes & Data *****
	$attr_defaults = ['type'=>''];
	$atts = shortcode_atts($attr_defaults, $attributes);
	
	ob_start();
	switch ($atts['type']) {
		case 'councils':
			$councils_list = $otgkofcs_Council->get_list();
			$council_scores = $otgkofcs_Scores_Model->council_scores($order_by='percentage', 'DESC');
			include("views/scoreboard_councils.php");
			break;
		case 'knights':
			$knight_list = $otgkofcs_Scores_Model->knight_scores_for_front();
			include("views/scoreboard_knights.php");
			break;
		case 'districts':
			$districts_list = $otgkofcs_Scores_Model->district_scores('score', 'DESC');
			$num_districts = get_option('otgkofcs_number_of_districts');
			include(OTGKOFCS_ROOT_PATH . 'views/scoreboard_districts.php');
			break;
	}
	$output = ob_get_clean();
	return $output;
}

/** Generates the output form the star council requirements met shortcode
 * @param type $attributes
 * @param type $content
 * @return string
 */
function otgkofcs_star_reqs ($attributes, $content = null) {
	global $otgkofcs_Council;
	// ***** Load Models, Helpers and Libraries *****
	require_once(OTGKOFCS_ROOT_PATH . 'models/star_model.php');
	$otgkofcs_Star_Model = new otgkofcs_Star_Model();

	$star_reqs_list = $otgkofcs_Star_Model->star_reqs_list();
	$star_reqs_met_list = $otgkofcs_Star_Model->star_reqs_met_list();
	$councils_list = $otgkofcs_Council->get_list();
	
	ob_start();
	include(OTGKOFCS_ROOT_PATH . 'views/scoreboard_star_reqs.php');
	$output = ob_get_clean();
	
	return $output;
}

/** Creates the Council Directory Table
 * @global otgkofcs_Council_Type $otgkofcs_Council
 * @param array $attributes
 * @param string $content
 * @return string
 */
function otgkofcs_council_table ($attributes, $content = null) {
	// ***** Get Attributes *****
	$attr_defaults = ['class' => ''];
	$attribute_list = shortcode_atts($attr_defaults, $attributes);
	
	// ***** Get Data *****
	global $otgkofcs_Council;
	$councils_list = $otgkofcs_Council->get_list();
	
	// ***** Generate Output *****
	ob_start();
	echo '<table class="' . $attribute_list['class'] . '"><tr><th>Number</th><th>Council Name</th><th>Location</th><th>Grand Knight</th></tr>';
	foreach ($councils_list as $council_num => $council_info) {
		echo '<tr><td><a href="/council/' . $council_num . '">' . $council_num . '</a></td>'
				. '<td><a href="/council/' . $council_num . '">' . $council_info['name'] . '</a></td>'
				. '<td>' . $council_info['location'] . '</td>'
				. '<td>' . $council_info['grand_knight'] . '</td></tr>';
	}
	echo '</table>';

	$output = ob_get_clean();
	return $output;
}

/** Creates a Assembly Directory Table
 * @global otgkofcs_Assembly_Type $otgkofcs_Assembly
 * @param array $attributes
 * @param string $content
 * @return string
 */
function otgkofcs_assembly_table ($attributes, $content = null) {
	// ***** Get Attributes *****
	$attr_defaults = ['class' => ''];
	$attribute_list = shortcode_atts($attr_defaults, $attributes);
	
	// ***** Get Data *****
	global $otgkofcs_Assembly;
	$assemblies_list = $otgkofcs_Assembly->get_list();

	// ***** Generate Output *****
	ob_start();
	echo '<table class="' . $attribute_list['class'] . '"><tr><th>Number</th><th>Name</th><th>Location</th><th>Councils</th><th>Faithful Navigator</th></tr>';
	foreach ($assemblies_list as $assembly_num => $assembly) {
		$council_list = $otgkofcs_Assembly->get_councils($assembly_num);
		echo '<tr><td><a href="/assembly/' . $assembly_num . '">' . $assembly_num . '</a></td>'
				. '<td><a href="/assembly/' . $assembly_num . '">' . $assembly['name'] . '</a></td>'
				. '<td>' . $assembly['location'] . '</td>'
				. '<td>' . implode(', ', $council_list) . '</td>'
				. '<td>' . $assembly['faithful_navigator'] . '</td></tr>';
	}
	echo '</table>';

	$output = ob_get_clean();
	return $output;
}

/** Creates the Council Directory Boxed List
 * @global otgkofcs_Council_Type $otgkofcs_Council
 * @param array $attributes
 * @param string $content
 * @return string
 */
function otgkofcs_council_box_list ($attributes, $content = null) {
	global $otgkofcs_Council;
	$councils_list = $otgkofcs_Council->get_list();
	
	ob_start();
	echo '<div class="otgkofcs_box_list">';
	foreach ($councils_list as $council_num => $council) {
		$featured_img = wp_get_attachment_image_src($council['featured_img_id'], 'large');
		
		?>
		<div style="background-image: url('<?= $featured_img[0] ?>')"><a href="/council/<?= $council_num ?>">
			<p class="otgkofcs_box_list_line1">Council #<?= $council_num ?></p>
			<p class="otgkofcs_box_list_line2"><?= $council['name'] ?></p>
			Location: <?= $council['location'] ?><br>
			Grand Knight: <?= $council['grand_knight'] ?>
		</a></div>
<?php	}
	echo '</div>';

	$output = ob_get_clean();
	return $output;
}

/** Creates the Assembly Boxed List
 * @global otgkofcs_Assembly_Type $otgkofcs_Assembly
 * @param type $attributes
 * @param type $content
 * @return type
 */
function otgkofcs_assembly_box_list ($attributes, $content = null) {
	global $otgkofcs_Assembly;
	$assemblies_list = $otgkofcs_Assembly->get_list();
	
	ob_start();
	echo '<div class="otgkofcs_box_list">';
	foreach ($assemblies_list as $assembly_num => $assembly) {
		$council_list = $otgkofcs_Assembly->get_councils($assembly_num);
		$featured_img = wp_get_attachment_image_src($assembly['featured_img_id'], 'large');
		?>
		<div<?php if ($featured_img) { ?> style="background-image: url('<?= $featured_img[0] ?>')"<?php } ?>><a href="/assembly/<?= $assembly_num ?>">
			<p class="otgkofcs_box_list_line1">Assembly #<?= $assembly_num ?></p>
			<p class="otgkofcs_box_list_line2"><?= $assembly['name'] ?></p>
			Location: <?= $assembly['location'] ?><br>
			Serving Councils: <?= implode(', ', $council_list)  ?><br>
			Faithful Navigator: <?= $assembly['faithful_navigator'] ?><br>
			Faithful Comptroller: <?= $assembly['faithful_comptroller'] ?><br>
		</a></div>
		<?php	}
	echo '</div>';

	$output = ob_get_clean();
	return $output;
}
