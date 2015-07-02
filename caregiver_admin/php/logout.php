<?php 
if(isset($_COOKIE['viewer_session_id'])){
	session_id($_COOKIE['viewer_session_id']);
	session_start ();
	// remove all session variables
	session_unset();
	// destroy the session
	session_destroy();
	
	setcookie('viewer_session_id','', -1, '/einshub/caregiver_admin/');
}

header("Location: /einshub/caregiver_admin/logged_out.html");
?>
