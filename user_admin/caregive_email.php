<?php
session_start();

    $user_name = $_SESSION['login_firstname'] . $_SESSION['login_lastname'];

    $to = $_SESSION['viewer_email'];
    $subject = $user_name . ' needs you to be his Einswatch viewer';
    $message = 'Dear Sir/Mdm, ' . $user_name . ' has recently purchased the Einswatch and wants you to download the app.';
    $headers = 'From: admin@einscloud.com' . "\r\n" .
        'Reply-To: admin@einscloud.com' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

echo 'trying';
$result = mail($to, $subject, $message, $headers);
header("Location: /php/user_add_new_viewer.php");
?>
