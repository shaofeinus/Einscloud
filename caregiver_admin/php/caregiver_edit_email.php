<?php
/**
 * Created by PhpStorm.
 * User: CK
 * Date: 11/6/2015
 * Time: 11:49 AM
 */
//start session and check for session validity
require_once 'DB_connect/check_session_validity.php';

$viewer_id = $_SESSION['viewer_id'];
$newEmail = $_POST['email'];
//echo $viewer_id;
require_once 'DB_connect/db_utility.php';

$link = get_conn();
$updateStmt = mysqli_prepare($link, "update RegisteredViewer set email = ? where id = ?");
$updateStmt->bind_param("si", $newEmail, $viewer_id);
$updateStmt->execute();
$link->close();

header("Location: ../caregiver_profile.php");
?>