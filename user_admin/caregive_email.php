<?php

function emailToViewer($email, $firstName, $lastName)
{


    $user_name =$firstName . ' ' . $lastName;

    $to = $email;
    $subject = $user_name . ' needs you to be his Einswatch viewer';
    $message = 'Dear Sir/Mdm, ' . $user_name . ' has recently purchased the Einswatch and wants you to download the app.';
    $headers = 'From: admin@einscloud.com' . "\r\n" .
        'Reply-To: admin@einscloud.com' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    $result = mail($to, $subject, $message, $headers);


  //  header("Location: user_add_viewer.html");
}
?>
