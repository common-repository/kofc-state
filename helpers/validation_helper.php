<?php
/** Submitted Data Validation Helper, OTG WP Plugins Common File
 * @Package			com.onthegridwebdesign.wpp-kofc-state
 * @File				helpers/validation_helper.php
 * @Author			Chris Hood (https://onthegridwebdesign.com)
 * @Link				https://onthegridwebdesign.com/software
 * @copyright		(c) 2018-2024, On the Grid Web Design LLC
 * @created			9/10/2024
*/

/** Cleans and Checks an Email Address
 * @param string $in
 * @return array
 */
function otgkofcs_validate_email ($in) {
	if (empty($in))
		return ['email' => '', 'valid' => false, 'message' => 'Email address needs to be submitted.'];

	$valid = true;
	$message = '';

	if (!$email_domain = stristr($in, '@')) {
		$message = 'Email address needs a "@"!';
		$valid = false;
	} elseif (!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-]).*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $in)) {
		$message = 'Email address is missing something!';
		$valid = false;
	} elseif (!stristr($email_domain, '.')) {
		$message = 'Email address domain needs to be valid!';
		$valid = false;
	} elseif (!filter_var($in, FILTER_VALIDATE_EMAIL)) {
		$message = 'Email address needs to be valid!';
		$valid = false;
	}

	return ['valid' => $valid, 'message' => $message];
}

/** Verifies a hCaptcha
 * @return boolean
 */
function otgkofcs_verify_hcaptcha () {
	$post_data['secret'] = get_option('otgkofcs_hcaptcha_secret_key');
	if (empty($post_data['secret'])) return true; // hcaptcha is disabled
	$post_data['response'] = otgkofcs_get_request_string('h-captcha-response');

	$curl = curl_init('https://hcaptcha.com/siteverify');
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_POST, 1);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
	$response = curl_exec($curl);
	$response_data = json_decode($response, true);

	/* ***** For Debugging *****
	$curl_error = curl_error($curl);
	if (!empty($curl_error)) {
		error_log('otgkofcs_verify_hcaptcha Curl Error: ' . print_r($curl_error, true));
	}
	$curl_info = curl_getinfo($curl);
	error_log('otgkofcs_verify_hcaptcha Curl Info: ' . print_r($curl_info, true));
	error_log('otgkofcs_verify_hcaptcha Response: ' . $response);
	error_log('otgkofcs_verify_hcaptcha Response Array: ' . print_r($response_data, true));
	/**/

	if ('true' == $response_data['success']) {
		return true;
	} else {
		return false;
	}
}