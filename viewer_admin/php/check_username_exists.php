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
        echo 1;
    } else {
        echo 0;
    }
}

function make_sql_query($data) {
    require_once __DIR__.'/DB_connect/db_connect.php';
    $connector = new DB_CONNECT();
    $connector->connect();

    $query = "SELECT * FROM RegisteredViewer WHERE username='$data'";

    $response = mysqli_query($connector->conn, $query);
    $connector->close();
    return $response;
}
?>
