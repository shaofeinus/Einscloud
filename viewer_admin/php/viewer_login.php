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
    // fetch id

    if(mysqli_num_rows($response) == 1) {
        $row = mysqli_fetch_assoc($response);
        $_SESSION["login_viewer"] = $username;
        $_SESSION["viewer_id"] = $row['id'];
        $_SESSION["viewer_phone"] = $row['phone_no'];
    } else {
        echo "<script> alert('Password does not match username'); window.location.assign('../index.php')</script>";
    }
} else {
    $error_msg = "Empty field(s)";
}

//This section sets a custom session id and update it into database.
require_once "DB_connect/db_utility.php";
$viewer_session_id = md5('viewer'.time().mt_rand());
setcookie('viewer_session_id', $viewer_session_id, 0, '/einscloud/viewer_admin/');

session_id($viewer_session_id);

make_query("update RegisteredViewer set session_id='{$viewer_session_id}' where id={$_SESSION["viewer_id"]};");
$_SESSION['last_activity'] = time();

header("Location: ../viewer_admin_index.php");

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