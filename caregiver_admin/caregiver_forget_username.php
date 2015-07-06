<?php

/**
 * @date-of-doc: 2015-07-06
 * @project-version: v0.2
 * @called-by: ../forget_login_details.php
 * @calls:
 *  php/DB_connect/db_utility.php
 * @description:
 *  This file verifies a caregiver's phone number and sends an SMS to the caregiver with his username.
 */

    if(!isset($_POST['forgetLoginPhone'])){
        header("Location: ../index.html");
    }
    $phoneNumber = $_POST['forgetLoginPhone'];

    require_once 'php/DB_connect/db_utility.php';

    $link = get_conn();
    $caregiverForgetStmt = mysqli_prepare($link, "Select phone_no, username FROM RegisteredViewer WHERE phone_no = ?");
    $caregiverForgetStmt->bind_param("s", $phoneNumber);
    $caregiverForgetStmt->execute();
    $caregiverForgetStmt->store_result();
    $caregiverForgetStmt->bind_result($row['phone_no'], $row['username']);
    $link->close();


    if($caregiverForgetStmt->num_rows>0){
        while($caregiverForgetStmt->fetch()){
            $username = $row['username'];
            $phone_no = $row['phone_no'];
        }
        //require_once '../burstsms/burstsms_send_function.php';
        $smsText = 'Dear User, your username is: '. $username . '.';
        $link = get_conn();
        $insertSMSStmt = mysqli_prepare($link, "insert into LogInLieuOfSMS values(?, ?)");
        $insertSMSStmt->bind_param("ss", $smsText, $phoneNumber);
        $insertSMSStmt->execute();
        $link->close();

        echo "<script> alert('SMS sent'); window.location.assign('index.php')</script>";
    }
    else{
        echo "<script> alert('This number is not registered at EinsHub'); window.location.assign('index.php')</script>";
    }
