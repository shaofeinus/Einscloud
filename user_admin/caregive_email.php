<?php
session_start();
emailToViewer();

function emailToViewer()
{
    //$user_name =$firstName . ' ' . $lastName;
    //$to = $email;

    $to = $_POST['email'];
    $user_name = $_POST['firstName'] . ' ' . $_POST['lastName'];
    echo $user_name;
    $verificationCode = $_SESSION['verificationCode'];
    $subject = $user_name . ' needs you to be his Einswatch viewer';
    //$message = 'Dear Sir/Mdm,\n ' . $user_name . ' has recently purchased the Einswatch and wants you to download the app.';
    //$headers = 'From: admin@einscloud.com' . "\r\n" . 'Reply-To: admin@einscloud.com' . "\r\n" . 'X-Mailer: PHP/' . phpversion();



    $message = '
        <html>
        <body>
        Dear Sir/Mdm,<br>
		<p>' . $user_name . ' has recently purchased the Einswatch and has requested that you download
		the mobile app to be a personal caregiver. Your one-time verification code is "' . $verificationCode .
		'". Follow this <a href= "http://einstech.com">link</a> to download the app.</p><br>
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
?>
