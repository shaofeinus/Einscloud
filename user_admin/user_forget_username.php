<?php
    if(!isset($_POST['forgetLoginNric'])){
        header("Location: ../index.html");
    }
    $nric = $_POST['forgetLoginNric'];
    //echo $nric;
    require_once 'php/DB_connect/db_utility.php';
    $sendUsernameQuery = 'select phone_no, username from User where nric ="' . $nric . '"' ;
    $response = make_query($sendUsernameQuery);

    if($response === FALSE) {
        echo "response is erroneous";
        die(mysql_error());
    }

    //todo send username to phone number