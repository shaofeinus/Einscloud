<?php
/**
 * @date-of-doc: 2015-07-06
 * @project-version: v0.2
 * @called-by: ../forget_login_details.php
 * @calls:
 *  php/DB_connect/db_utility.php
 * @description:
 *  This file generates a reset key for the user to reset his password and
 *  sends an sms to the user with information on how to reset his password
 */
    if(!isset($_POST['forgetPassword'])){
        header("Location: ../index.html");
    }
    $username = $_POST['forgetPassword'];
    //echo $username;
    require_once 'php/DB_connect/db_utility.php';

    $link = get_conn();
    $selectStmt = mysqli_prepare($link, "select phone_no, id from User where username =?");
    $selectStmt->bind_param("s", $username);
    if($selectStmt->execute()) {
        $selectStmt->store_result();
        if ($selectStmt->num_rows > 0) {
            $selectStmt->bind_result($phone_no, $id);
            $selectStmt->fetch();
        } else {
            die(mysql_error());
        }
        $selectStmt->close();
        $link->close();
    } else {
        die(mysql_error());
    }

    $resetCode = getVerificationCode();

    $link = get_conn();
    $selectStmt = mysqli_prepare($link, "select * from ResetPassword where user_id =? and user_type ='user'");
    $selectStmt->bind_param("i", $id);
    if($selectStmt->execute()) {
        $selectStmt->store_result();
        if ($selectStmt->num_rows === 0) {
            $link2 = get_conn();
            $insertStmt = mysqli_prepare($link2, "insert into ResetPassword values(?, 'user', ?, '1')");
            $insertStmt->bind_param("si", $resetCode, $id);
            if(!$insertStmt->execute()) {
                die(mysql_error());
            }
            $link2->close();

            require_once '../burstsms/burstsms_send_function.php';
            $smsText = 'Dear user your reset key is ' . $resetCode . '. Follow this URL to reset your password: "http://192.168.1.59/einscloud/user_admin/user_reset_password.php" You are only allowed to use this once by today.';
            sendSMS($smsText, $phone_no);

            echo "<script> alert('SMS sent'); window.location.assign('../index.html')</script>";
        } else if($selectStmt->num_rows > 0) {
            echo "<script> alert('You already have one reset key!'); window.location.assign('../index.html')</script>";
        } else {
            echo "<script> alert('query failed!'); window.location.assign('../index.html')</script>";
        }

        $selectStmt->close();
        $link->close();
    } else {
        die(mysql_error());
    }

function getVerificationCode() {
    $code_digit1 = rand(0,9);
    $code_digit2 = rand(0,9);
    $code_digit3 = rand(0,9);
    $code_digit4 = rand(0,9);
    $code_digit5 = rand(0,9);
    $code_digit6 = rand(0,9);
    $code_digit7 = rand(0,9);
    $code_digit8 = rand(0,9);

    $code = $code_digit1.$code_digit2.$code_digit3.$code_digit4.$code_digit5.$code_digit6.$code_digit7.$code_digit8.'';

    //require_once __DIR__.'/DB_connect/db_utility.php';
    $query = "SELECT * FROM ResetPassword WHERE reset_key='$code'";
    $response = make_query($query);
    if(!mysqli_num_rows($response) == 0) {
        return getVerificationCode();
    } else {
        return $code;
    }
}