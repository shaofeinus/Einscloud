function sendVerificationCodeSMS(username, verification_code, numberToSend) {
    var textToSend = "Dear Sir/Mdm, einscloud user " + username + 
    	" would like to invite you to be his/her caregiver. please download the app at http://xxx" +
    	" and registered with the verification code: " + verification_code;
	
	$.post('../../burstsms/burstsms_handleJSCall.php',
        {
            text : textToSend,
            number : numberToSend
        });
	console.log("sms php script called.");
}