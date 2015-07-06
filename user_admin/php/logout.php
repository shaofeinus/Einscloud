<?php
/**
 * @date-of-doc: 2015-07-06
 * @project-version: v0.2
 * @called-by:
 * /user_admin/user_admin_index.php
 * /user_admin/user_add_viewer.php
 * /user_admin/user_add_emerg.php
 * /user_admin/user_edit_profile.php
 * @calls: /user_admin/logged_out.html
 * @description: This script cease the session, clears the cookie and redirect to logged_out page.
 */
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
