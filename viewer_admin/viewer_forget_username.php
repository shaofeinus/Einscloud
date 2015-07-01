<?php
    if(!isset($_POST['forgetLoginPhone'])){
        header("Location: ../index.html");
    }
    $phoneNumber = $_POST['forgetLoginPhone'];
    //echo $nric;
    require_once 'php/DB_connect/db_utility.php';
    $sendUsernameQuery = 'select phone_no, username from RegisteredViewer where phone_no ="' . $phoneNumber . '"' ;
    $response = make_query($sendUsernameQuery);

    if($response === FALSE) {
        echo "response is erroneous";
        die(mysql_error());
    }

//todo send username to phone number