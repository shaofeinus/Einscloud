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
    $newUsername = cleanUpInput($_POST['username']);
    //echo $viewer_id;
    require_once 'DB_connect/db_utility.php';

    $query = "update RegisteredViewer set username = '$newUsername' where id = '$viewer_id'";
    $updateResponse = make_query($query);
    if($updateResponse === FALSE) {
        echo "response is erroneous";
        die(mysql_error());
    }
    
    $_SESSION['login_viewer'] = $_POST['username'];

    header("Location: ../caregiver_profile.php");
?>