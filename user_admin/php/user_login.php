<?php
/**
 * Created by PhpStorm.
 * User: Shao Fei
 * Date: 9/6/2015
 * Time: 6:29 PM
 */

/**
 * @date-of-doc: 2015-07-07
 *
 * @project-version: v0.2
 *
 * @called-by:
 * ../index.php
 *
 * @calls:
 * clean_up_input.php
 * DB_connect/db_utility.php
 * ../user_admin_index.php
 * ../index.php
 *
 * @description:
 * Script that logs in User to ../user_admin_index.php upon correct username and password,
 * redirect to ../index.html if otherwise.
 *
 */

session_start();

$error_msg = "";
require_once "clean_up_input.php";

if(!empty($_POST["username"]) && !empty($_POST["password"])) {
    $username = cleanUpInput($_POST["username"]);
    $password = $_POST["password"];
    $loginOk = make_sql_query($username, $password);
    if($loginOk) {
        login();
    } else {
        echo "<script> alert('Password does not match username'); window.location.assign('../index.php')</script>";
    }
}

function login() {
    //This section sets a custom session id and update it into database.
    require_once "DB_connect/db_utility.php";
    $user_session_id = md5('user'.time().mt_rand());
    setcookie('user_session_id', $user_session_id, 0, '/einshub/user_admin/');

    session_id($user_session_id);

    $link = get_conn();
    $updateStmt = mysqli_prepare($link, "update User set session_id=? where id=?");
    $updateStmt->bind_param("si", $user_session_id, $_SESSION["login_id"]);
    $updateStmt->execute();
    $link->close();

    $_SESSION['last_activity'] = time();

    header("Location: ../user_admin_index.php");
}

function make_sql_query($username, $password) {
    $passwordHashed = md5($password);

    require_once 'DB_connect/db_utility.php';
    $link = get_conn();
    $selectStmt = mysqli_prepare($link, "SELECT id, fullname, email, nric, phone_no FROM User WHERE username=? AND password=?");
    $selectStmt->bind_param("ss", $username, $passwordHashed);

    if($selectStmt->execute()) {
        $selectStmt->store_result();
        if($selectStmt->num_rows === 1) {
            $selectStmt->bind_result($id, $fullname, $email, $nric, $phone_no);
            $selectStmt->fetch();

            $_SESSION["login_id"] =  $id;
            $_SESSION["login_user"] = $username;
            $_SESSION['login_fullname'] =  $fullname;
            $_SESSION['login_email'] = $email;
            $_SESSION['login_nric'] = $nric;
            $_SESSION['login_phone_no'] = $phone_no;

            $link->close();
            return true;
        } else {
            $link->close();
            return false;
        }
    } else {
        return false;
    }
}

?>