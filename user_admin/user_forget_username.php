<?php
    if(!isset($_POST['forgetLoginNric'])){
        header("Location: ../index.html");
    }
    $nric = $_POST['forgetLoginNric'];
    //echo $nric;
    require_once 'php/DB_connect/db_utility.php';
    $sendUsernameQuery = 'select phone_no, username from User where nric ="' . $nric . '"' ;
    $response = make_query($sendUsernameQuery);

    if($response === FALSE) {
        echo "response is erroneous";
        die(mysql_error());
    }

    if(mysqli_num_rows($response)>0){
        while($row = mysqli_fetch_assoc($response)){
            $username = $row['username'];
            $phone_no = $row['phone_no'];
        }
    }
    //require_once '../burstsms/burstsms_send_function.php';
    $smsText = 'Dear User, your username is: '. $username . '.';
    $insertQuery = 'insert into LogInLieuOfSMS values ("' . $smsText . '", "' . $phone_no . '")';
    make_query($insertQuery);
    echo "<script> alert('SMS sent'); window.location.assign('index.php')</script>";