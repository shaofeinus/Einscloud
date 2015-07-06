<?php
/**
 * @date-of-doc: 2015-07-06
 * @project-version: v0.2
 * @called-by:
 * /caregiver_admin/caregiver_profile.php
 * /caregiver_admin/php/caregiver_edit_phone.php
 * /caregiver_admin/php/caregiver_delete_email.php
 * /caregiver_admin/caregiver_forget_username.php
 * /caregiver_admin/php/caregiver_edit_username.php
 * /caregiver_admin/php/caregiver_verify_user.php
 * /caregiver_admin/caregiver_forget_password.php
 * /caregiver_admin/php/caregiver_reset_password_script.php
 * /caregiver_admin/index.php
 * /caregiver_admin/php/caregiver_edit_password.php
 * /caregiver_admin/php/caregiver_edit_rvtype.php
 * /caregiver_admin/caregiver_admin_index.php
 * /caregiver_admin/php/caregiver_edit_email.php
 * /caregiver_admin/php/caregiver_login.php
 * @calls:
 * db_utility.php
 * /caregiver_admin/logged_out.html
 * /caregiver_admin/logged_in_somewhere_else.html
 * @description:
 * This file checks for the validity of the session that the current browser is in. If the session is no longer valid,
 * (does not agree with the session id recorded in database) the user is redirected to appropriate pages.
 */
if(!isset($_COOKIE['viewer_session_id'])){
	header("Location: /einshub/caregiver_admin/logged_out.html");
}
session_id($_COOKIE['viewer_session_id']);
session_start();

require_once "db_utility.php";
//if session doesn't exist at all, go to logged_out.html
if(!isset($_SESSION['viewer_id'])) header("Location: /einshub/caregiver_admin/logged_out.html");

//check whether the session_id agrees with that in the database.
$resp = make_query("select * from RegisteredViewer where id={$_SESSION['viewer_id']}");
$id_in_database;
if (mysqli_num_rows($resp) == 1) {
	$row = mysqli_fetch_assoc($resp);
	$id_in_database = $row['session_id'];
} else {
	//if the sql query does not return exactly 1 row of result, something's wrong. 
	//TODO: Maybe should change this to writing to a console log file or something.
	die('A query with a viewer-id from the RegisteredViewer table returned a non-1-row result.');
}

if($id_in_database != session_id()){
	header("Location: /einshub/caregiver_admin/logged_in_somewhere_else.html");
}

//if session has not been active for 30 min, destroy session and go to logged_out.html;
//if still active (within 30min), update recorded activity time
if(isset($_SESSION['last_activity']) && time() - $_SESSION['last_activity'] > 1800){
	session_unset();
	session_destroy();
	header("Location: /einshub/caregiver_admin/logged_out.html");
}
$_SESSION['last_activity'] =  time();