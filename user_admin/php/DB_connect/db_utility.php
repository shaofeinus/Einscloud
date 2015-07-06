<?php
/**
 *
 * @date-of-doc: 2015-07-06
 * @project-version: v0.2
 * @called-by:
 * /user_admin/php/user_delete_viewer.php
 * /user_admin/index.php
 * /user_admin/user_forget_username.php
 * /user_admin/php/user_add_viewer_functions.php
 * /user_admin/php/DB_connect/check_session_validity.php
 * /user_admin/php/user_reset_password_script.php
 * /user_admin/php/check_username_exists.php
 * /burstsms/burstsms_send_function.php
 * /burstsms/burstsms_handleDailyAlert.php
 * /user_admin/php/user_edit_profile_functions.php
 * /user_admin/user_update_unreg_viewer.php
 * /user_admin/php/user_add_new_emerg.php
 * /clear_reset_keys.php
 * /user_admin/php/user_registration_create.php
 * /user_admin/php/user_add_emerg_functions.php
 * /user_admin/php/user_add_new_viewer.php
 * /user_admin/php/unreg_daily_clear.php
 * /user_admin/user_forget_password.php
 * /user_admin/php/check_nric_exists.php
 * /user_admin/php/user_login.php
 * /user_admin/php/check_password_matches.php
 * @calls:
 * db_connect.php
 * @description:
 * This script include all the utility functions for database connection.
 */

function make_query($query) {
	require_once 'db_connect.php';
	$connector = new DB_CONNECT ();
	$connector->connect ();
	$response = mysqli_query ( $connector->conn, $query );
	$connector->close ();
	return $response;
}

function get_conn(){
    require_once 'db_connect.php';
    $connector = new DB_CONNECT();
    return $connector->connect();
}

function close_conn($conn){
    require_once 'db_connect.php';
    $conn.close();
}
?>