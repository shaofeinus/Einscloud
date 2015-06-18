<?php

require_once __DIR__.'/../user_admin/php/DB_connect/db_utility.php';
require_once 'burstsms_send_function.php';

$viewer_result = make_query("select * from UnregisteredViewer");

if ($viewer_result->num_rows > 0) {
	while($row = $viewer_result->fetch_assoc()) {
		//Fetch user's name from User table, for the construction of the notification text content.
		$user_result = make_query("select * from User where id={$row['user_id']}");
		$user_row = $user_result->fetch_assoc();
		$username = $user_row['firstname'] . ' ' . $user_row['lastname'];
		
		//Construct the content to be sent out.
		$text = generateText($username, $row['verification_code']);
		sendSMS($text, $row['verification_code']);
		
		//Decrement the "notifications_left" column, for the 3-day auto stop.
		$nots_left = $row['notifications_left'] - 1;
		make_query("update UnregisteredViewer set notifications_left={$nots_left} where
			verification_code={$row['verification_code']} and user_id={$row['user_id']}");
	}
} else {
	echo "UnregisteredViewer is empty.";
}

function generateText($username, $verification_code){
	return "Dear Sir/Mdm, einscloud user " . $username .
	" would like to invite you to be his/her caregiver." .
	" please goto the link http://192.168.1.59/einscloud/viewer_admin/index.php".
	" or download the app at http://192.168.1.59/einsviewer.apk" .
	" and registered with the verification code: " . $verification_code;
}
