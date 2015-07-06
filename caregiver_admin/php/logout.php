<?php
/**
 * @date-of-doc: 2015-07-06
 * @project-version: v0.2
 * @called-by: /caregiver_admin/cargiver_admin_index.php
 * @calls: /caregiver_admin/logged_out.html
 * @description: This script cease the session, clears the cookie and redirect to logged_out page.
 */
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
