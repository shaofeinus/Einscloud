<?php
//start session and check for session validity
require_once 'DB_connect/check_session_validity.php';

$viewer_id = $_SESSION['viewer_id'];
$oldPassword = md5($_POST['oldPassword']);
$newPassword = md5($_POST['newPassword']);
//echo $viewer_id;
require_once 'DB_connect/db_utility.php';

$link = get_conn();
$selectStmt = mysqli_prepare($link, "select * from RegisteredViewer where id = ? and password = ?");
$selectStmt->bind_param("is", $viewer_id, $oldPassword);
$selectStmt->execute();
$selectStmt->store_result();
$link->close();


if($selectStmt->num_rows > 0) {
    $link = get_conn();
    $updateStmt = mysqli_prepare($link, "update RegisteredViewer set password = ? where id = ? and password = ?");
    $updateStmt->bind_param("sis", $newPassword, $viewer_id, $oldPassword);
    $updateStmt->execute();
    $link->close();


    echo "<script> alert('You have successfully changed your password!'); window.location.assign('../caregiver_profile.php')</script>";

}
else {
    echo "<script> alert('You have failed to change your password! Please try again.'); window.location.assign('../caregiver_profile.php')</script>";
}
?>

