/**
 * @date-of-doc: 2015-07-06
 * @project-version: v0.2
 * @called-by: /user_admin/php/user_add_new_viewer.php
 * @calls: burstsms_handleJSCall.php
 * @description:
 * This function is called when a new intended caregiver is added by a user to send out SMS notification.
 */
function sendVerificationCodeSMS(username, verification_code, numberToSend) {
    var textToSend = "Dear Sir/Mdm, einshub user " + username +
    	" would like to invite you to be his/her caregiver. please goto the link http://192.168.1.59/einshub/caregiver_admin/index.php " +
    	"or download the android app at http://192.168.1.59/einsviewer.apk" +
    	" and registered with the verification code: " + verification_code;
	
	$.post('../../burstsms/burstsms_handleJSCall.php',
        {
            text : textToSend,
            number : numberToSend
        });
	console.log("sms php script called.");
}