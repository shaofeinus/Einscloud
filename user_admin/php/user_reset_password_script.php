<?php

require_once 'DB_connect/db_utility.php';
$resetKey = $_POST['resetKey'];
$newPassword = $_POST['newPassword'];
$confirmPassword = $_POST['confirmPassword'];

$link = get_conn();
$selectStmt = mysqli_prepare($link, "select user_type, user_id from ResetPassword where reset_key=?");
$selectStmt->bind_param("s", $resetKey);
if($selectStmt->execute()) {
    $selectStmt->store_result();
    if($selectStmt->num_rows>0) {
        $selectStmt->bind_result($userType, $userID);
        $selectStmt->fetch();
    }
    $selectStmt->close();
    $link->close();
} else {
    die("error with sql query");
}

/*
$findIDQuery = 'select * from ResetPassword where reset_key = "' . $resetKey . '"';
$idResponse = make_query($findIDQuery);

if(mysqli_num_rows($idResponse)>0){
    while($row = mysqli_fetch_assoc($idResponse)){
        $userType = $row['user_type'];
        $userID = $row['user_id'];
    }
}
*/

if(isset($userType) && $userType == 'user') {

    if ($newPassword == $confirmPassword) {

        $hashedPassword = md5($newPassword);

        $link = get_conn();
        $updateStmt = mysqli_prepare($link, "update User set password=? where id=?");
        $updateStmt->bind_param("si", $hashedPassword, $userID);
        if($updateStmt->execute()) {
            $updateStmt->close();
            $link->close();
        } else {
            die("error updating password");
        }
    }

    $link = get_conn();
    $deleteStmt = mysqli_prepare($link, "delete from ResetPassword where reset_key =?");
    $deleteStmt->bind_param("s", $resetKey);

    if($deleteStmt->execute()) {
        $deleteStmt->close();
        $link->close();
    } else {
        die("error deleting reset key");
    }

} else{
    echo "<script> alert('Failed query.'); window.location.assign('../index.php')</script>";
}

?>