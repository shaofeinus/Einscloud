<?php
/**
 * Created by PhpStorm.
 * User: CK
 * Date: 11/6/2015
 * Time: 11:49 AM
 */
session_start();
$viewer_id = $_SESSION['viewer_id'];
$newRvtype = $_POST['rvtype'];
//echo $viewer_id;
require_once __DIR__.'/DB_connect/db_utility.php';

$query = "update RegisteredViewer set rvtype = '$newRvtype' where id = '$viewer_id'";
$updateResponse = make_query($query);
if($updateResponse === FALSE) {
    echo "response is erroneous";
    die(mysql_error());
}

header("Location: ../viewer_profile.php");
?>