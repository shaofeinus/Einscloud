<?php 
//start session and check for session validity
require_once 'DB_connect/check_session_validity.php';

require_once 'clean_up_input.php';
?>
<head>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
</head>
<?php
processAddViewer();

function processAddViewer() {
    if(isset($_POST['addViewersSubmit'])) {
        $i = 0;
        $successViewer = array();

        while(isset($_POST['nickname_'.$i]) && isset($_POST['viewerPhone_'.$i]) && isset($_POST['viewerEmail_'.$i])) {
            $viewername = cleanUpInput($_POST['nickname_' . $i]);
            $phone_no = cleanUpInput($_POST['viewerPhone_' . $i]);
            $email = cleanUpInput($_POST['viewerEmail_' . $i]);
            $user_id = $_SESSION['login_id'];
            $verification_code = getVerificationCode();

            require_once "DB_connect/db_utility.php";

            if (!empty($email)) {
                $link = get_conn();
                $insertstmt = mysqli_prepare($link, "INSERT INTO UnregisteredViewer (verification_code, phone_no, user_id, viewername)" .
                    "VALUES (?, ?, ?, ?, ?)");
                $insertstmt->bind_param("sssis", $verification_code, $phone_no, $user_id, $viewername);
                $insertSuccess = $insertstmt->execute();
            } else {
                $link = get_conn();
                $insertstmt = mysqli_prepare($link, "INSERT INTO UnregisteredViewer (verification_code, phone_no, user_id, viewername)" .
                    "VALUES (?, ?, ?, ?)");
                $insertstmt->bind_param("ssis", $verification_code, $phone_no, $user_id, $viewername);
                $insertSuccess = $insertstmt->execute();
            }

            if($insertSuccess) {
                $link->close();
            }

            //Send out invitation email asynchronously
            
            if (!empty($email) && $insertSuccess) {
                $_SESSION['verificationCode'] = $verification_code;
                ?>
                    <script language="JavaScript" type="text/javascript" src= "../script/user_email_viewer_script.js"></script>
                <?php
                echo '<script>'
                . 'emailViewer("' . $email . '", "' . $_SESSION['login_fullname'] .'");'
                . '</script>';
                //require_once '../caregive_email.php';
                //emailToViewer($email, $_SESSION['login_firstname'], $_SESSION['login_lastname']);

            }
            
            //Send out invitation SMS asynchronously
            ?><script language="JavaScript" type="text/javascript" 
            	src= "../../burstsms/burstsms_send_verification_code.js"></script>
            <?php
            $username = $_SESSION['login_fullname'];
            echo "<script> sendVerificationCodeSMS('{$username}', {$verification_code}, {$phone_no}); </script>";

            $instance = array();
            array_push($instance, $viewername);
            if($insertSuccess && $verification_code !== NULL) {
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
        echo "<script>alert($s); window.location = '../user_admin_index.php';</script>";
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
    $link = get_conn();
    $selectStmt = mysqli_prepare($link, "SELECT * FROM UnregisteredViewer WHERE verification_code=?");
    $selectStmt->bind_param("s", $code);

    if($selectStmt->execute()) {
        $selectStmt->store_result();
        if ($selectStmt->num_rows !== 0) {
            $link->close();
            return getVerificationCode();
        } else {
            $link->close();
            return $code;
        }
    } else {
        return NULL;
    }
}
?>