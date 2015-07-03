<?php
require_once 'DB_connect/db_utility.php';
$resetKey = $_POST['resetKey'];
$newPassword = $_POST['newPassword'];
$confirmPassword = $_POST['confirmPassword'];
$resetKey = trim($resetKey);


$link = get_conn();
$findUserStmt = mysqli_prepare($link, "select user_type, user_id from ResetPassword where reset_key = ?");
$findUserStmt->bind_param("s", $resetKey);
$findUserStmt->execute();
$findUserStmt->store_result();
$findUserStmt->bind_result($row['user_type'], $row['user_id']);
$link->close();

//$findIDQuery = 'select * from ResetPassword where reset_key = "' . $resetKey . '"';
//$idResponse = make_query($findIDQuery);
//echo $findIDQuery;

if($findUserStmt->num_rows>0){
    while($findUserStmt->fetch()){
        $userType = $row['user_type'];
        $userID = $row['user_id'];
    }
}

if(isset($userType) && $userType == 'caregiver') {
    if ($newPassword == $confirmPassword) {
        $hashedPassword = md5($newPassword);


        $link = get_conn();
        $updateStmt = mysqli_prepare($link, "update RegisteredViewer set password = ? where id = ?");
        $updateStmt->bind_param("si", $hashedPassword, $userID);
        $updateStmt->execute();



    }

    $deleteStmt = mysqli_prepare($link, "delete from ResetPassword where reset_key = ?");
    $deleteStmt->bind_param("s", $resetKey);
    $deleteStmt->execute();

    $link->close();
    echo "<script> alert('Password changed!'); window.location.assign('../index.php')</script>";
}
else{
    echo "<script> alert('Failed query.'); window.location.assign('../index.php')</script>";
}