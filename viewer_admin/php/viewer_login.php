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
        $_SESSION["login_viewer"] = $username;
        header("Location: ../user_admin_index.php");
    } else {
        header("Location: user_wrong_password.html");
    }
} else {
    $error_msg = "Empty field(s)";
}

function make_sql_query($username, $password) {
    require_once 'DB_connect/db_connect.php';
    $connector = new DB_CONNECT();
    $connector->connect();

    $passwordHashed = md5($password);
    $query = "SELECT * FROM RegisteredViewer WHERE username='$username' AND password='$passwordHashed'";

    $response = mysqli_query($connector->conn, $query);
    $connector->close();
    return $response;
}

?>