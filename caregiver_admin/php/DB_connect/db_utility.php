<?php
/**
 * @date-of-doc: 2015-07-06
 * @project-version: v0.2
 * @called-by:
 * /einshub/user_admin/php/user_delete_viewer.php
 * /einshub/user_admin/index.php
 * /einshub/user_admin/user_forget_username.php
 * /einshub/user_admin/php/user_add_viewer_functions.php
 * /einshub/user_admin/php/DB_connect/check_session_validity.php
 * /einshub/user_admin/php/user_reset_password_script.php
 * /einshub/user_admin/php/check_username_exists.php
 * /einshub/burstsms/burstsms_send_function.php
 * /einshub/burstsms/burstsms_handleDailyAlert.php
 * /einshub/user_admin/php/user_edit_profile_functions.php
 * /einshub/user_admin/user_update_unreg_viewer.php
 * /einshub/user_admin/php/user_add_new_emerg.php
 * /einshub/clear_reset_keys.php
 * /einshub/user_admin/php/user_registration_create.php
 * /einshub/user_admin/php/user_add_emerg_functions.php
 * /einshub/user_admin/php/user_add_new_viewer.php
 * /einshub/user_admin/php/unreg_daily_clear.php
 * /einshub/user_admin/user_forget_password.php
 * /einshub/user_admin/php/check_nric_exists.php
 * /einshub/user_admin/php/user_login.php
 * /einshub/user_admin/php/check_password_matches.php
 * @calls:
 * db_connect.php
 * @description:
 * This script include all the utility functions for database connection.
 */
function make_query($query) {
    require_once 'db_connect.php'; // TODO prone to path change. Need refactor.
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