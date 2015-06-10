<?php
/**
 * Created by PhpStorm.
 * User: Shao Fei
 * Date: 9/6/2015
 * Time: 4:48 PM
 */

if(!empty($_GET["nric"])) {
    $nric = $_GET["nric"];
    $response = make_sql_query($nric);

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

    $query = "SELECT * FROM RegisteredViewer WHERE nric='$data'";

    $response = mysqli_query($connector->conn, $query);
    $connector->close();
    return $response;
}

?>