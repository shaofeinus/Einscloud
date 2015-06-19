<?php
    require_once 'user_admin/php/DB_connect/db_utility.php';
    $viewer_result = make_query("select * from UnregisteredViewer where email is not null");

    if(mysqli_num_rows($viewer_result) > 0){
        while($row = mysqli_fetch_assoc($viewer_result)){
            $to = $row['email'];
            $rowUserID = $row['user_id'];
            $user_result = make_query("select firstname, lastname from User where id = '$rowUserID'");
            $user_result_row = mysqli_fetch_assoc($user_result);
            $user_name = $user_result_row['firstname'] . ' ' . $user_result_row['lastname'];
            $verificationCode = $row['verification_code'];
            $subject = $user_name . ' still needs you to be his Einswatch viewer';
            $message = '
                <html>
                <body>
                Dear Sir/Mdm,<br>
                <p>We have noticed that you have not verified to be ' . $user_name . '\'s' . ' viewer. ' . $user_name . ' has
                recently purchased the Einswatch and has requested that you download the mobile app to be a personal caregiver.
                Your one-time verification code is "' . $verificationCode . '". Follow this <a href= "192.168.1.59/einscloud/viewer_admin/index.php">link</a> to
                go to viewer registration.</p><br>
                Best Regards,<br>
                The Einswatch Team
                </body>
                </html>
            ';
            //echo $message;
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= 'From: <einscloud@gmail.com>' . "\r\n";
            $result = mail($to, $subject, $message, $headers);
        }
    }
    else {
        echo 'No unregistered viewer with emails';
    }
?>