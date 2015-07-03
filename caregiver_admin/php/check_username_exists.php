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
    $stmt = make_sql_query($username);

    if($stmt->num_rows > 0) {
        echo 1;
    } else {
        echo 0;
    }
}

function make_sql_query($data) {
    require_once 'DB_connect/db_utility.php';


    $link = get_conn();
    $findUsernameStmt = mysqli_prepare($link, "SELECT * FROM RegisteredViewer WHERE username=?");
    $findUsernameStmt->bind_param("s", $data);
    $result = $findUsernameStmt->execute();
    $findUsernameStmt->store_result();
    $link->close();

    //$query = "SELECT * FROM RegisteredViewer WHERE username='$data'";

    //$response = mysqli_query($connector->conn, $query);
   // $connector->close();
    return $findUsernameStmt;
}
?>
