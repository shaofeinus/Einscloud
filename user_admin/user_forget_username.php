<?php
/**
 * @date-of-doc: 2015-07-06
 * @project-version: v0.2
 * @called-by: ../forget_login_details.php
 * @calls:
 *  php/DB_connect/db_utility.php
 * @description:
 *  This file verifies a user's NRIC and sends an SMS to the user with his username.
 */

    if(!isset($_POST['forgetLoginNric'])){
        header("Location: ../index.html");
    }
    $nric = $_POST['forgetLoginNric'];
    //echo $nric;
    require_once 'php/DB_connect/db_utility.php';

    $link = get_conn();
    $selectStmt = mysqli_prepare($link, "select phone_no, username from User where nric =?");
    $selectStmt->bind_param("s", $nric);

    if(!$selectStmt->execute()) {
        echo "response is erroneous";
        die(mysql_error());
    }

    $selectStmt->store_result();

    if($selectStmt->num_rows>0){
        $selectStmt->bind_result($phone_no, $username);
        $selectStmt->fetch();
        //require_once '../burstsms/burstsms_send_function.php';
        $smsText = 'Dear User, your username is: '. $username . '.';
        $selectStmt->close();
        $link->close();

        $link = get_conn();
        $insertStmt = mysqli_prepare($link, 'insert into LogInLieuOfSMS values (?, ?)');
        $insertStmt->bind_param("ss", $smsText, $phone_no);
        $insertStmt->execute();
        $insertStmt->close();
        $link->close();
        echo "<script> alert('SMS sent'); window.location.assign('index.php')</script>";
    }
    else{
        $selectStmt->close();
        $link->close();
        echo "<script> alert('This number is not registered at EinsHub'); window.location.assign('index.php')</script>";
    }

