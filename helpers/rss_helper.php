<?php
/** Helper Functions for RSS Feeds
 * @Package			Knights of Columbus State WP Plugin
 * @File				helpers/rss_helper.php
 * @Author			Chris Hood (http://chrishood.me)
 * @Link				http://onthegridwebdesign.com
 * @copyright		(c) 2016-2022, On the Grid Web Design LLC
 * @created			1/15/2022
*/

/** Gets the RSS Feed and Puts Entries Less Than One Year Old in an Array Up to the Limit
 * @param string $url
 * @param int $limit
 * @return array|boolean
 */
function otgkofcs_get_rss_feed ($url, $limit) {
	$response = otgkofcs_curl_get($url, null, 'otgkofcs_get_rss_feed');
	if ($response['query_error'])
		return false;
	$rss_data = simplexml_load_string($response['raw_response']);
	//echo '<pre style="color: black">'; print_r($rss_data); echo '</pre>';

	$i = 1;
	$year_ago = strtotime('-1 year');
	foreach ($rss_data->channel->item as $item) {
		$date = strtotime($item->pubDate);
		if ($year_ago > $date) continue;
		
		$entry = [
			'title' => $item->title,
			'date' => date('m/d/Y', $date),
			'link' => $item->link,
			'description' => $item->description
		];
		$return[] = $entry;
		if ($limit-1 < $i)
			break;
		$i++;
	}

	return $return;
}

/** Common Get Using Curl
 * @param string $url
 * @param array $get_params
 * @param string $function_name
 * @return array
 */
function otgkofcs_curl_get ($url, $get_params, $function_name) {
	// ***** Add Params to URL *****
	if (!empty($get_params)) {
		foreach ($get_params as $key => $value) {
			$get_params_combined[] = $key . '=' . $value;
		}
		$url .= '?' . implode('&', $get_params_combined);
	}

	// ***** Build and Execute Query *****
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	//curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // For bypassing certifcate errors
	curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13'); // For WordPress rejecting queries without it
	$response = curl_exec($curl);

	return otgkofcs_response_processing ($function_name, $curl, $response, $get_params);
}

	/** Checks the Curl Query and Extracts the Response Into an Array
 * If no error, returns the JSON decoded response with 'query_error'=false added.
 * @param string $function_name
 * @param object $curl
 * @param array $response
 * @param array $submitted_data
 * @return array
 */
function otgkofcs_response_processing ($function_name, $curl, $response = null, $submitted_data = null) {
	$debug = false;
	//$curl_error = curl_error($curl);
	$curl_info = curl_getinfo($curl);
	$return = [
			'http_code' => $curl_info['http_code'],
			'query_error' => false,
	];

	// ***** Check for Timeout *****
	if (0 == $curl_info['http_code']) {
		otgkofcs_log_curl($function_name, $curl, $response, $submitted_data, true);
		$return['query_error'] = true;
		return $return;
	}

	// ***** Check & Decode Response *****
	if (!empty($response)) {
		$response_array = json_decode($response, true);
		if (!is_array($response_array)) {
			$return['raw_response'] = $response;
		} else {
			$return = array_merge($response_array, $return);
		}
	}

	// ***** Check for HTTP Error Codes *****
	if (!in_array($curl_info['http_code'], ['200', '201', '202'])) {
		$return['query_error'] = true;

		otgkofcs_log_curl($function_name, $curl, $response, $submitted_data, true);
	}

	// ***** Debug Logging*****
	if (!$return['query_error'] && $debug)
		otgkofcs_log_curl($function_name, $curl, $response, $submitted_data);

	return $return;
}

/** Debugging Log
 * @param string $function
 * @param object $curl
 * @param array $response
 * @param array $submitted_data
 * @param boolean $error
 */
function otgkofcs_log_curl ($function, $curl, $response, $submitted_data=null, $error=false) {
	$curl_error = curl_error($curl);
	$curl_info = curl_getinfo($curl);

	// ***** Assemble Log Message *****
	$log_text = PHP_EOL;
	if (!empty($submitted_data))
		$log_text .= 'Submitted Data: ' . print_r($submitted_data, true);
	if (!empty($curl_error))
		$log_text .= "Curl Error: $curl_error" . PHP_EOL;
	$log_text .= 'Curl Info: ' . print_r($curl_info, true);

	if ('404' == $curl_info['http_code']) {
	} elseif (false === $response) {
		$log_text .= 'Response: Boolean False';
	} else {
		$response_array = json_decode($response, true);
		if (is_array($response_array))
			$response = print_r($response_array, true);
		else
			$response .= PHP_EOL;
		$log_text .= 'Response: ' . $response;
	}

	// ***** Limit Log Entry Size (Prevents Some Memory Overloads) *****
	if (250000 < strlen($log_text)) {
		$log_text = substr($log_text, 0, 250000);
	}
	if (!$error) {
		// ***** Write to Custom Log *****
		error_log(date('Y-m-d H:i:s') . " $function $log_text" . PHP_EOL . PHP_EOL, 3, WP_CONTENT_DIR . '/otgkofcs-debug.log');
	} else {
		// ***** Add HTTP Code & Write to Error Log *****
		if (!in_array($curl_info['http_code'], ['200', '201', '202'])) {
			$log_text = ' HTTP Error ' . $curl_info['http_code'] . $log_text;
		}
		error_log(date('Y-m-d H:i:s') . " $function $log_text" . PHP_EOL . PHP_EOL, 3, WP_CONTENT_DIR . '/otgkofcs-error.log');
//		error_log("$function Error $log_text");
	}
}