<?php
/**
 * Created by PhpStorm.
 * User: Shao Fei
 * Date: 11/6/2015
 * Time: 2:12 PM
 */

processAddViewer();

function processAddViewer() {
    if(isset($_POST['addViewersSubmit'])) {
        $i = 0;
        $successViewer = array();

        while(isset($_POST['nickname_'.$i]) && isset($_POST['viewerPhone_'.$i]) && isset($_POST['viewerEmail_'.$i])) {
            $viewername = $_POST['nickname_'.$i];
            $phone_no = $_POST['viewerPhone_'.$i];
            $email = $_POST['viewerEmail_'.$i];
            $user_id = $_SESSION['login_id'];
            $verification_code = getVerificationCode();

            if(!empty($email)) {
                $query = "INSERT INTO UnregisteredViewer (verification_code, phone_no, email, user_id, viewername)".
                    "VALUES ('$verification_code', '$phone_no', '$email', '$user_id', '$viewername')";
            } else {
                $query = "INSERT INTO UnregisteredViewer (verification_code, phone_no, email, user_id, viewername)".
                    "VALUES ('$verification_code', '$phone_no', NULL, '$user_id', '$viewername')";
            }

            require_once __DIR__.'/DB_connect/db_utility.php';
            $response = make_query($query);
            $instance = array();
            array_push($instance, $viewername);
            if($response) {
                array_push($instance, true);
            } else {
                array_push($instance, false);
            }
            array_push($successViewer, $instance);
            $i++;
        }

        $alert_feedback = "";
        foreach($successViewer as $viewer) {
            if($viewer[1]) {
                $alert_feedback = $alert_feedback."Successfully added ".$viewer[0]."\n";
            } else {
                $alert_feedback = $alert_feedback."Failed to add ".$viewer[0]."\n";
            }
        }
        $s = json_encode($alert_feedback);
        echo "<script>alert($s)</script>";
    }
}

function getVerificationCode() {
    $code_digit1 = rand(0,9);
    $code_digit2 = rand(0,9);
    $code_digit3 = rand(0,9);
    $code_digit4 = rand(0,9);
    $code_digit5 = rand(0,9);
    $code_digit6 = rand(0,9);

    $code = $code_digit1.$code_digit2.$code_digit3.$code_digit4.$code_digit5.$code_digit6.'';

    require_once __DIR__.'/DB_connect/db_utility.php';
    $query = "SELECT * FROM UnregisteredViewer WHERE verification_code='$code'";
    $response = make_query($query);
    if(!mysqli_num_rows($response) == 0) {
        return getVerificationCode();
    } else {
        return $code;
    }
}
?>