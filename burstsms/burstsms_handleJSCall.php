<?php
/**
 * @date-of-doc: 2015-07-06
 * @project-version: v0.2
 * @called-by: burstsms_send_verification_code.js
 * @calls: burstsms_send_function.php
 * @description:
 * This file serves as the connection point between the relevant JS file to the functional php scripts.
 * This script receives a POST request from the JS.
 */
require "burstsms_send_function.php";

if (isset($_POST['text']) && isset($_POST['number'])) {
    $text = $_POST['text'];
    $number = $_POST['number'];
}

sendSMS($text, $number);