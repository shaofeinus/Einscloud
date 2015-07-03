<?php
/**
 * Created by PhpStorm.
 * User: CK
 * Date: 11/6/2015
 * Time: 11:49 AM
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