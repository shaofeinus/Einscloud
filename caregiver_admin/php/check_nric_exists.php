<?php
/**
 * @date-of-doc: 2015-07-06
 * @project-version: v0.2
 * @called-by: ../script/caregiver_registration_validation.js
 * @calls: DB_connect/db_utility.php
 * @description:
 *  This file checks the database for existing NRIC. It returns 1 or 0 to the .js file that calls it.
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