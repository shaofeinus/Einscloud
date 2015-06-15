<?php
require "burstsms_send_function.php";

if(isset($_POST['text']) && isset($_POST['number'])) {
	$text = $_POST['text'];
	$number = $_POST['number'];
}

sendSMS($text, $number);