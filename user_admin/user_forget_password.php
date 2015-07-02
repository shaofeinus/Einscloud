<?php
    if(!isset($_POST['forgetPassword'])){
        header("Location: ../index.html");
    }
    $username = $_POST['forgetPassword'];
    //echo $username;
    require_once 'php/DB_connect/db_utility.php';
    $sendUsernameQuery = 'select phone_no, id from User where username ="' . $username . '"' ;
    $response = make_query($sendUsernameQuery);

    if($response === FALSE) {
        echo "response is erroneous";
        die(mysql_error());
    }

    $resetCode = getVerificationCode();
    if(mysqli_num_rows($response) > 0){
        while ($row = mysqli_fetch_assoc($response)){
            $id = $row['id'];
            $phone_no = $row['phone_no'];
        }
    }

    $selectIfExistsQuery = 'select * from ResetPassword where user_id = ' . $id . ' and user_type = "user"';
    $selectResponse = make_query($selectIfExistsQuery);
    if(mysqli_num_rows($selectResponse) ==  0){
        $insertResetQuery = 'insert into ResetPassword values("' . $resetCode . '", "user",' . $id . ',"1")';
        $insertResponse = make_query($insertResetQuery);
        if($insertResponse === FALSE) {
            die(mysql_error());
        }
        require_once '../burstsms/burstsms_send_function.php';
        $smsText = 'Dear user your reset key is ' . $resetCode . '. Follow this URL to reset your password: "http://192.168.1.59/einscloud/user_admin/user_reset_password.php" You are only allowed to use this once by today.';
        sendSMS($smsText, $phone_no);
        echo "<script> alert('SMS sent'); window.location.assign('../index.html')</script>";
    }
    else if(mysqli_num_rows($selectResponse) >  0){
        echo "<script> alert('You already have one reset key!'); window.location.assign('../index.html')</script>";
    }
    else{
        echo "<script> alert('query failed!'); window.location.assign('../index.html')</script>";
    }



<<<<<<< HEAD
    require_once '../burstsms/burstsms_send_function.php';
    $smsText = 'Dear user your reset key is ' . $resetCode . '. Follow this URL to reset your password: "http://192.168.1.59/einshub/user_admin/user_reset_password.php" You are only allowed to use this once by today.';
    sendSMS($smsText, $phone_no);
    echo "<script> alert('SMS sent'); window.location.assign('../index.html')</script>";
=======
>>>>>>> 3b74a08f7d0ad86415321996c2cd75c46e13a189

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