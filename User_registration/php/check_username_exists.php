<?php
/**
 * Created by PhpStorm.
 * User: Shao Fei
 * Date: 8/6/20
15
* Time: 4:46 PM
*/

if(!empty($_GET["username"])) {
    $username = $_GET["username"];
    $response = make_sql_query($username);

    if(mysqli_num_rows($response) > 0) {
        echo json_encode(true);
    } else {
        echo json_encode(false);
    }
}

function make_sql_query($username) {
    require_once 'DB_connect/db_connect.php';
    $connector = new DB_CONNECT();
    $connector->connect();

    $query = "SELECT * FROM User WHERE username='$username'";

    $response = mysqli_query($connector->conn, $query);
    $connector->close();
    return $response;
}
?>
