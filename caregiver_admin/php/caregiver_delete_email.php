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

//echo $viewer_id;
require_once 'DB_connect/db_utility.php';

$query = "update RegisteredViewer set email = NULL where id = '$viewer_id'";
$updateResponse = make_query($query);
if($updateResponse === FALSE) {
    echo "Phone number in existence apparently";
    die(mysql_error());
}

header("Location: ../caregiver_profile.php");
?>