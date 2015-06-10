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
    $username = $_POST["username"];
    $password = $_POST["password"];
    $response = make_sql_query($username, $password);

    if(mysqli_num_rows($response) == 1) {
        $row = mysqli_fetch_assoc($response);
    	$_SESSION["login_id"] =  $row ["id"];
        $_SESSION["login_user"] = $username;
        $_SESSION['login_firstname'] =  $row ["firstname"];
        $_SESSION['login_lastname'] = $row ["lastname"];
        $_SESSION['login_email'] = $row ["email"];
        $_SESSION['login_nric'] = $row ["nric"];
        $_SESSION['login_phone_no'] = $row ["phone_no"];
        header("Location: ../user_admin_index.php");
    } else {
        echo "<script> alert('Password does not match username'); window.location.assign('../index.html')</script>";
    }
} else {
    $error_msg = "Empty field(s)";
}

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