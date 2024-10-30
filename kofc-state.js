/** Javascripts
 * @Package			Knights of Columbus State WP Plugin
 * @File				recruiting-scoreboard.js
 * @Author			Chris Hood (http://chrishood.me)
 * @Link				http://onthegridwebdesign.com
 * @copyright		(c) 2016-2021, On the Grid Web Design LLC
 * @created			7/21/2016
*/

jQuery(document).ready(function ($) {
	// ***** Listeners *****

	// *** Keep the Bulk Action Selects Synced ***
	$('#bulk-action-selector-top').change(function (e) {
		$("#bulk-action-selector-bottom").val($('#bulk-action-selector-top').val());
	});
	$('#bulk-action-selector-bottom').change(function (e) {
		$("#bulk-action-selector-top").val($('#bulk-action-selector-bottom').val());
	});

	// *** Check All Box ***
	$('#cb-select-all-1').change(function (e) {
		if ($('#cb-select-all-1').prop('checked')) {
			$('.otgkofcs_list_checkbox').prop('checked', true);
		} else {
			$('.otgkofcs_list_checkbox').prop('checked', false);
		}
	});
	$('#cb-select-all-2').change(function (e) {
		if ($('#cb-select-all-2').prop('checked')) {
			$('.otgkofcs_list_checkbox').prop('checked', true);
		} else {
			$('.otgkofcs_list_checkbox').prop('checked', false);
		}
	});

});

/** Asks if a user is sure about deleting something before calling the URL
 * @param {string} url
 * @param {string} what
 * @returns {undefined}
 */
function otgkofcs_delete_something (url, what) {
	var sure = confirm("Delete " + what + " and all associated info? Are you sure? (this cannot be undone)");
	if (sure) {
		window.location.href = url;
	}
}

/** Turns On Submit Button if the hCaptcha is Good
 */
function otgkofcs_hcaptcha_good () {
	jQuery('#otgkofcs_submit_button').prop('disabled', false);
}