<?php
require_once '../user_admin/php/DB_connect/db_utility.php';
$viewer_result = make_query("select * from UnregisteredViewer");

include 'burstsms_send_function.php';

if ($viewer_result->num_rows > 0) {
	while($row = $viewer_result->fetch_assoc()) {
		$user_result = make_query("select * from User where id={$row['user_id']}");
		$user_row = $user_result->fetch_assoc();
		$username = $user_row['firstname'] . ' ' . $user_row['lastname'];
		$text = generateText($username, $row['verification_code']);
		sendSMS($text, $row['verification_code']);
	}
} else {
	echo "UnregisteredViewer is empty.";
}

function generateText($username, $verification_code){
	return "Dear Sir/Mdm, einscloud user " . $username .
	" would like to invite you to be his/her caregiver. please download the app at http://xxx" .
	" and registered with the verification code: " . $verification_code;
}