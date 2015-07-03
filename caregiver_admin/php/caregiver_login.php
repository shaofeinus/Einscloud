<?php
/**
 * Created by PhpStorm.
 * User: Shao Fei
 * Date: 9/6/2015
 * Time: 6:29 PM
 */

session_start();

require_once 'clean_up_input.php';

$error_msg = "";

require_once 'DB_connect/db_utility.php';

if(!empty($_POST["username"]) && !empty($_POST["password"])) {
    $username = cleanUpInput($_POST["username"]);
    $password = $_POST["password"];

    $passwordHashed = md5($password);


    $link = get_conn();
    $loginSelectStmt = mysqli_prepare($link, "select * from  RegisteredViewer where username = ? and password = ?");
    $loginSelectStmt->bind_param("ss", $username, $passwordHashed);
    $loginSelectStmt->execute();
    $loginSelectStmt->store_result();
    $loginSelectStmt->bind_result($row['id'], $row['username'], $row['password'], $row['fullname'], $row['email'], $row['phone_no'], $row['rv_type'], $row['date_registered'], $row['session_id']);
    $link->close();



    if($loginSelectStmt->num_rows == 1) {
        $loginSelectStmt->fetch();
        $_SESSION["login_viewer"] = $username;
        $_SESSION["viewer_id"] = $row['id'];
        $_SESSION["viewer_phone"] = $row['phone_no'];
        $_SESSION["viewer_name"] = $row['fullname'];
    } else {
        echo "<script> alert('Password does not match username'); window.location.assign('../index.php')</script>";
    }
} else {
    $error_msg = "Empty field(s)";
}

//This section sets a custom session id and update it into database.
//require_once "DB_connect/db_utility.php";
$viewer_session_id = md5('viewer'.time().mt_rand());
setcookie('viewer_session_id', $viewer_session_id, 0, '/einshub/caregiver_admin/');

session_id($viewer_session_id);

$link = get_conn();
$setSessionStmt = mysqli_prepare($link, "update RegisteredViewer set session_id = ? where id = ?");
$setSessionStmt->bind_param("ss", $viewer_session_id, $_SESSION["viewer_id"]);
$setSessionStmt->execute();
$link->close();


$_SESSION['last_activity'] = time();

header("Location: ../caregiver_admin_index.php");

