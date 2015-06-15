<?php
$text = $argv[1];
$number = $argv[2];

include 'burstsms_send_function.php';

sendSMS($text, $number);