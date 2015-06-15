<?php

emailToViewer();

function emailToViewer()
{
    //$user_name =$firstName . ' ' . $lastName;
    //$to = $email;

    $to = $_POST['email'];
    $user_name = $_POST['firstName'] . ' ' . $_POST['lastName'];
    echo $user_name;

    $subject = $user_name . ' needs you to be his Einswatch viewer';
    $message = 'Dear Sir/Mdm, ' . $user_name . ' has recently purchased the Einswatch and wants you to download the app.';
    $headers = 'From: admin@einscloud.com' . "\r\n" .
        'Reply-To: admin@einscloud.com' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    $result = mail($to, $subject, $message, $headers);


    //header("Location: http://reddit.com");
}
?>
