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
    require_once 'DB_connect/db_utility.php';
    $link = get_conn();
    $query = mysqli_prepare($link, "SELECT * FROM User WHERE username=? AND password=?");
    $query->bind_param("ss", $username, $encryptPassword);
    $query->execute();
    $query->store_result();
    $link->close();

    if($query->fetch()) {
        echo json_encode(true);
    } else {
        echo json_encode(false);
    }
}

?>