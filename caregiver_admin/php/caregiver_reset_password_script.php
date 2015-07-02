<?php
require_once 'DB_connect/db_utility.php';
$resetKey = $_POST['resetKey'];
$newPassword = $_POST['newPassword'];
$confirmPassword = $_POST['confirmPassword'];
$resetKey = trim($resetKey);

$findIDQuery = 'select * from ResetPassword where reset_key = "' . $resetKey . '"';
$idResponse = make_query($findIDQuery);
//echo $findIDQuery;

if(mysqli_num_rows($idResponse)>0){
    while($row = mysqli_fetch_assoc($idResponse)){
        $userType = $row['user_type'];
        $userID = $row['user_id'];
    }
}

if(isset($userType) && $userType == 'caregiver') {
    if ($newPassword == $confirmPassword) {
        $hashedPassword = md5($newPassword);
        $updatePasswordQuery = 'update RegisteredViewer set password = "' . $hashedPassword . '" where id = ' . $userID;
        //echo $updatePasswordQuery;
        make_query($updatePasswordQuery);
    }
    $deleteQuery = 'delete from ResetPassword where reset_key = "' . $resetKey . '"';
    //echo $deleteQuery;
    make_query($deleteQuery);
}
else{
    echo "<script> alert('Failed query.'); window.location.assign('../index.php')</script>";
}