<?php
/**
 * @date-of-doc: 2015-07-06
 * @project-version: v0.2
 * @called-by: ../caregiver_profile.php
 * @calls:
 *  DB_connect/check_session_validity.php
 *  clean_up_input.php
 *  DB_connect/db_utility.php
 * @description:
 *  This file changes the caregiver's phone number in the database
 */
//start session and check for session validity
require_once 'DB_connect/check_session_validity.php';
require_once 'clean_up_input.php';

$viewer_id = $_SESSION['viewer_id'];
$newPhoneno = cleanUpInput($_POST['phoneNo']);
//echo $viewer_id;
require_once 'DB_connect/db_utility.php';


$link = get_conn();
$unregisteredStmt = mysqli_prepare($link, "update RegisteredViewer set phone_no = ? where id = ?");
$unregisteredStmt->bind_param("si", $newPhoneno, $viewer_id);
$unregisteredStmt->execute();

$link->close();


header("Location: ../caregiver_profile.php");
?>