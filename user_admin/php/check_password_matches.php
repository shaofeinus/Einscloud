<?php
/**
 * Created by PhpStorm.
 * User: Shao Fei
 * Date: 1/7/2015
 * Time: 2:33 PM
 */

if(!empty($_POST['username']) && !empty($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    checkPassword($username, $password);
} else {
    echo json_encode("Insufficient info");
}



function checkPassword($username, $password) {
    $encryptPassword = md5($password);
    require_once __DIR__.'/DB_connect/db_utility.php';
    $query = "SELECT * FROM User WHERE username='$username' AND password='$encryptPassword'";
    $response = make_query($query);
    if($response) {
        if(mysqli_num_rows($response) == 1) {
            echo json_encode(true);
        } else {
            echo json_encode(false);
        }
    } else {
        echo json_encode("error");
    }
}

?>