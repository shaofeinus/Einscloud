<?php
/**
 * Created by PhpStorm.
 * User: Shao Fei
 * Date: 9/6/2015
 * Time: 4:48 PM
 */

/**
 * @date-of-doc: 2015-07-06
 *
 * @project-version: v0.2
 *
 * @called-by:
 * ../script/user_registration_validation_script.js
 *
 * @calls:
 * ../php/DB_connect/db_utility.php
 *
 * @description:
 * Script to check whether nric exists.
 *
 */

if(!empty($_GET["nric"])) {
    $nric = $_GET["nric"];
    $response = make_sql_query($nric);

    if($response->fetch()) {
        echo 1;
    } else {
        echo 0;
    }
}

function make_sql_query($data) {

    require_once 'DB_connect/db_utility.php';
    $link = get_conn();
    $query = mysqli_prepare($link, "SELECT id FROM User WHERE nric=?");
    $query->bind_param("s", $data);
    $query->execute();
    $query->store_result();
    $link->close();
    return $query;
}

?>