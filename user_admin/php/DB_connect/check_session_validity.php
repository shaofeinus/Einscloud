<?php
if(!isset($_COOKIE['user_session_id'])){
	header("Location: /einshub/user_admin/logged_out.html");
}
session_id($_COOKIE['user_session_id']);
session_start();

require_once "db_utility.php";

//if session doesn't exist at all, go to logged_out.html
if(!isset($_SESSION['login_id'])) header("Location: /einshub/user_admin/logged_out.html");

//check whether the session_id agrees with that in the database.
$resp = make_query("select * from User where id={$_SESSION['login_id']}");
$id_in_database;
if (mysqli_num_rows($resp) == 1) {
	$row = mysqli_fetch_assoc($resp);
	$id_in_database = $row['session_id'];
} else {
	//if the sql query does not return exactly 1 row of result, something's wrong. 
	//TODO: Maybe should change this to writing to a console log file or something.
	die('A query with a user-id from the User table returned a non-1-row result.');
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