function sendVerificationCodeSMS(username, verification_code, numberToSend) {
    var textToSend = "Dear Sir/Mdm, einscloud user " + username + 
    	" would like to invite you to be his/her caregiver. please goto the link http://192.168.1.59/einscloud/viewer_admin/index.php " +
    	"or download the android app at http://192.168.1.59/einsviewer.apk" +
    	" and registered with the verification code: " + verification_code;
	
	$.post('../../burstsms/burstsms_handleJSCall.php',
        {
            text : textToSend,
            number : numberToSend
        });
	console.log("sms php script called.");
}