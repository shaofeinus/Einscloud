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
	$newRvtype = $_POST['rvtype'];
	//echo $viewer_id;
	require_once 'DB_connect/db_utility.php';

    $link = get_conn();
    $updateRvStmt = mysqli_prepare($link, "update RegisteredViewer set rvtype = ? where id = ?");
    $updateRvStmt->bind_param("si", $newRvtype, $viewer_id);
    $updateRvStmt->execute();

    $link->close();

	
	header("Location: ../caregiver_profile.php");
?>