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
$newPhoneno = $_POST['phoneNo'];
//echo $viewer_id;
require_once 'DB_connect/db_utility.php';

$query = "update RegisteredViewer set phone_no = '$newPhoneno' where id = '$viewer_id'";
$updateResponse = make_query($query);
if($updateResponse === FALSE) {
    echo "Phone number in existence apparently";
    die(mysql_error());
}

header("Location: ../caregiver_profile.php");
?>