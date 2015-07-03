<?php
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