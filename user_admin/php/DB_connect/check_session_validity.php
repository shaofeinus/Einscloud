<?php
/**
 * @date-of-doc: 2015-07-06
 * @project-version: v0.2
 * @called-by:
 * /user_admin/php/user_delete_viewer.php
 * /user_admin/user_add_emerg.php
 * /user_admin/php/user_add_new_viewer.php
 * /user_admin/user_edit_profile.php
 * /user_admin/user_update_unreg_viewer.php
 * /user_admin/php/user_add_new_emerg.php
 * /user_admin/user_add_viewer.php
 * /user_admin/user_admin_index.php
 * @calls:
 * db_utility.php
 * /user_admin/logged_out.html
 * /user_admin/logged_in_somewhere_else.html
 * @description:
 * This file checks for the validity of the session that the current browser is in. If the session is no longer valid,
 * (does not agree with the session id recorded in database) the user is redirected to appropriate pages.
 */
if(!isset($_COOKIE['user_session_id'])){
	header("Location: /einshub/user_admin/logged_out.html");
}
session_id($_COOKIE['user_session_id']);
session_start();

require_once "db_utility.php";

//if session doesn't exist at all, go to logged_out.html
if(!isset($_SESSION['login_id'])) header("Location: /einshub/user_admin/logged_out.html");

$link = get_conn();
$selectStmt = mysqli_prepare($link,"select session_id from User where id=?");
$selectStmt->bind_param("s", $_SESSION['login_id']);
if($selectStmt->execute()) {
    $selectStmt->store_result();
    $selectStmt->bind_result($id_in_database);
    if($selectStmt->num_rows == 1) {
        $selectStmt->fetch();
        $link->close();
    } else {
        $link->close();
        die('A query with a user-id from the User table returned a non-1-row result.');
    }
} else {
    die("Unable to make sql query");
}

if($id_in_database != session_id()){
	header("Location: /einshub/user_admin/logged_in_somewhere_else.html");
    die();
}

//if session has not been active for 30 min, destroy session and go to logged_out.html;
//if still active (within 30min), update recorded activity time
if(isset($_SESSION['last_activity']) && time() - $_SESSION['last_activity'] > 1800){
	session_unset();
	session_destroy();
	header("Location: /einshub/user_admin/logged_out.html");
    die();
}
$_SESSION['last_activity'] =  time();