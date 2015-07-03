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

    if($response->fetch()) {
        echo 1;
    } else {
        echo 0;
    }
}

function make_sql_query($data) {
    require_once 'DB_connect/db_utility.php';
    $link = get_conn();
    $query = mysqli_prepare($link, "SELECT * FROM User WHERE username=?");
    $query->bind_param("s", $data);
    $query->execute();
    $query->store_result();
    $link->close();
    return $query;
}
?>
