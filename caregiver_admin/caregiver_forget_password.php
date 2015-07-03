<?php

    if(!isset($_POST['forgetPassword'])){
        header("Location: ../index.html");
    }
    $username = $_POST['forgetPassword'];
    //echo $username;
    require_once 'php/DB_connect/db_utility.php';

    $link = get_conn();
    $findUserStmt = mysqli_prepare($link, "select phone_no, id from RegisteredViewer where username = ?");
    $findUserStmt->bind_param("s", $username);
    $findUserStmt->execute();
    $findUserStmt->store_result();
    $findUserStmt->bind_result($row['phone_no'], $row['id']);
    $link->close();

    //$sendUsernameQuery = 'select phone_no, id from RegisteredViewer where username ="' . $username . '"' ;
    //$response = make_query($sendUsernameQuery);

    $resetCode = getVerificationCode();
    if($findUserStmt->num_rows>0){
        while ($findUserStmt->fetch()){
            $id = $row['id'];
            $phone_no = $row['phone_no'];
        }
    }

    $link = get_conn();
    $selectIfExistStmt = mysqli_prepare($link, "select * from ResetPassword where user_id = ? and user_type = 'caregiver'");
    $selectIfExistStmt->bind_param("i", $id);
    $selectIfExistStmt->execute();
    $selectIfExistStmt->store_result();


    if($selectIfExistStmt->num_rows == 0) {
        //$insertResetQuery = 'insert into ResetPassword values("' . $resetCode . '", "caregiver",' . $id . ',"1")';
        //$insertResponse = make_query($insertResetQuery);
        $insertSMSStmt = mysqli_prepare($link, "insert into ResetPassword values(?, 'caregiver',?,'1')");
        $insertSMSStmt->bind_param("si", $resetCode, $id);
        $insertSMSStmt->execute();

        require_once '../burstsms/burstsms_send_function.php';
        $smsText = 'Dear user your reset key is ' . $resetCode . '. Follow this URL to reset your password: "http://192.168.1.59/einshub/caregiver_admin/caregiver_reset_password.php" You are only allowed to use this once by today.';
        //sendSMS($smsText, $phone_no);
        make_query("insert into LogInLieuOfSMS values('{$smsText}', {$phone_no})");
        echo "<script> alert('SMS sent'); window.location.assign('../index.html')</script>";
    }
    else if($selectIfExistStmt->num_rows >  0){
        echo "<script> alert('You already have one reset key!'); window.location.assign('../index.html')</script>";
    }
    else{
        echo "<script> alert('query failed!'); window.location.assign('../index.html')</script>";
    }
    $link->close();




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

    $link = get_conn();
    $createKeyStmt = mysqli_prepare($link, "SELECT * FROM ResetPassword WHERE reset_key=?");
    $createKeyStmt->bind_param("s", $code);
    $createKeyStmt->execute();
    $createKeyStmt->store_result();
    $link->close();

    //$query = "SELECT * FROM ResetPassword WHERE reset_key='$code'";
    //$response = make_query($query);
    if(!$createKeyStmt->num_rows == 0) {
        return getVerificationCode();
    } else {
        return $code;
    }
}