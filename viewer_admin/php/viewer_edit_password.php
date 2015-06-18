<?php
session_start();

$viewer_id = $_SESSION['viewer_id'];
$oldPassword = md5($_POST['oldPassword']);
$newPassword = md5($_POST['newPassword']);
//echo $viewer_id;
require_once 'DB_connect/db_utility.php';

$selectQuery = "select * from RegisteredViewer where id = '$viewer_id' and password = '$oldPassword'";
$selectResponse = make_query($selectQuery);
if(mysqli_num_rows($selectResponse) > 0) {
    $query = "update RegisteredViewer set password = '$newPassword' where id = '$viewer_id' and password = '$oldPassword'";
    //print $query;
    $updateResponse = make_query($query);

    if ($updateResponse === FALSE) {
        echo "response is erroneous";
        die(mysql_error());
    }
    echo "<script> alert('You have successfully changed your password!'); window.location.assign('../viewer_profile.php')</script>";

}
else {
    echo "<script> alert('You have failed to change your password! Please try again.'); window.location.assign('../viewer_profile.php')</script>";
}
?>

