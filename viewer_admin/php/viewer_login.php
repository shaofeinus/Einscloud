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
    // fetch id

    if(mysqli_num_rows($response) == 1) {
        $row = mysqli_fetch_assoc($response);
        $_SESSION["login_viewer"] = $username;
        $_SESSION["viewer_id"] = $row['id'];
        $_SESSION["viewer_phone"] = $row['phone_no'];
        //echo $_SESSION['viewer_phone'];
        //echo $_SESSION["viewerid"];
        header("Location: ../viewer_admin_index.php");
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
    $query = "SELECT * FROM RegisteredViewer WHERE username='$username' AND password='$passwordHashed'";

    $response = mysqli_query($connector->conn, $query);
    $connector->close();
    return $response;
}

?>