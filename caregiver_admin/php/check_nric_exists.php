<?php
/**
 * Created by PhpStorm.
 * User: Shao Fei
 * Date: 9/6/2015
 * Time: 4:48 PM
 */

if(!empty($_GET["nric"])) {
    $nric = $_GET["nric"];
    $stmt = make_sql_query($nric);

    if($stmt->num_rows > 0) {
        echo 1;
    } else {
        echo 0;
    }
}

function make_sql_query($data) {
    require_once 'DB_connect/db_utility.php';


    $link = get_conn();
    $findNricStmt = mysqli_prepare($link, "SELECT * FROM RegisteredViewer WHERE nric=?");
    $findNricStmt->bind_param("s", $data);
    $result = $findNricStmt->execute();
    $findNricStmt->store_result();
    $link->close();

    //$query = "SELECT * FROM RegisteredViewer WHERE nric='$data'";

    //$response = mysqli_query($connector->conn, $query);

    return $findNricStmt;
}

?>