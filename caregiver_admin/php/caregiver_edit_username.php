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
 *  This file changes the caregiver's username in the database
 */
	//start session and check for session validity
	require_once 'DB_connect/check_session_validity.php';
    require_once 'clean_up_input.php';
	
    $viewer_id = $_SESSION['viewer_id'];
    $newUsername = cleanUpInput($_POST['username']);
    //echo $viewer_id;
    require_once 'DB_connect/db_utility.php';

    $link = get_conn();
    $editUsernameStmt = mysqli_prepare($link, "update RegisteredViewer set username = ? where id = ?");
    $editUsernameStmt->bind_param("si", $newUsername, $viewer_id);
    $editUsernameStmt->execute();
    $link->close();

    
    $_SESSION['login_viewer'] = $_POST['username'];

    header("Location: ../caregiver_profile.php");
?>