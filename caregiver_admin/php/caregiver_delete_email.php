<?php
/**
 * @date-of-doc: 2015-07-06
 * @project-version: v0.2
 * @called-by: ../caregiver_profile.php
 * @calls:
 *  DB_connect/check_session_validity.php
 *  DB_connect/db_utility.php
 * @description:
 *  This file deletes the caregiver's email from the database
 */
//start session and check for session validity
require_once 'DB_connect/check_session_validity.php';

$viewer_id = $_SESSION['viewer_id'];

//echo $viewer_id;
require_once 'DB_connect/db_utility.php';

$link = get_conn();
$deleteStmt = mysqli_prepare($link, "update RegisteredViewer set email = NULL where id = ?");
$deleteStmt->bind_param("i", $viewer_id);
$deleteStmt->execute();
$link->close();

//$query = "update RegisteredViewer set email = NULL where id = '$viewer_id'";
//$updateResponse = make_query($query);


header("Location: ../caregiver_profile.php");
?>