<?php
/**
 * Created by PhpStorm.
 * User: Shao Fei
 * Date: 9/6/2015
 * Time: 6:29 PM
 */
session_start();

$error_msg = "";

if(!empty($_POST["username"]) && !empty($_POST["password"])) {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];
    $response = make_sql_query($username, $password);

    if(mysqli_num_rows($response) == 1) {
        $row = mysqli_fetch_assoc($response);
    	$_SESSION["login_id"] =  $row ["id"];
        $_SESSION["login_user"] = $username;
        $_SESSION['login_fullname'] =  $row ["fullname"];
        $_SESSION['login_email'] = $row ["email"];
        $_SESSION['login_nric'] = $row ["nric"];
        $_SESSION['login_phone_no'] = $row ["phone_no"];
    } else {
        echo "<script> alert('Password does not match username'); window.location.assign('../index.php')</script>";
    }
} else {
    $error_msg = "Empty field(s)";
}

//This section sets a custom session id and update it into database.
require_once "DB_connect/db_utility.php";
$user_session_id = md5('user'.time().mt_rand());
setcookie('user_session_id', $user_session_id, 0, '/einscloud/user_admin/');

session_id($user_session_id);

make_query("update User set session_id='{$user_session_id}' where id={$_SESSION["login_id"]};");
$_SESSION['last_activity'] = time();

header("Location: ../user_admin_index.php");



function make_sql_query($username, $password) {
    require_once 'DB_connect/db_connect.php';
    $connector = new DB_CONNECT();
    $connector->connect();

    $passwordHashed = md5($password);
    $query = "SELECT * FROM User WHERE username='$username' AND password='$passwordHashed'";

    $response = mysqli_query($connector->conn, $query);
    $connector->close();
    return $response;
}

?>