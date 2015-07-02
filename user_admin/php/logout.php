<?php 
if(isset($_COOKIE['user_session_id'])){
	session_id($_COOKIE['user_session_id']);
	session_start ();
	
	// remove all session variables
	session_unset();
	
	// destroy the session
	session_destroy();
	
	setcookie('user_session_id', '', -1, '/einshub/user_admin/');
}
header("Location: /einshub/user_admin/logged_out.html");

?>
