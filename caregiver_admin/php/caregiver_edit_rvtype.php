<?php
/**
 * @date-of-doc: 2015-07-06
 * @project-version: v0.2
 * @called-by: ../caregiver_profile.php
 * @calls:
 *  DB_connect/check_session_validity.php
 *  DB_connect/db_utility.php
 * @description:s
 *  This file changes the caregiver's viewer type. It is currently unused.
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