<?php
/*
 * This script is called when the user is trying to verify the connection to a user with verification code.
 * It checks the validity of the code, removes the record from UnregisteredViewer table and add in tuple 
 * for the relationship in Cargive table.
 */
	//start session and check for session validity
	require_once 'DB_connect/check_session_validity.php';
	
    $verification_code = $_POST['verification_code'];
    $user_id = $_POST['user_id'];
    $viewer_id = $_SESSION['viewer_id'];
    //echo $viewer_id;
    //echo $verification_code;
    //echo $_SESSION['login_viewer'] . "<br>";

    require_once 'DB_connect/db_connect.php';
    $connector = new DB_CONNECT();
    $connector->connect();

    $verifyQuery = "select * from UnregisteredViewer where '$verification_code' = verification_code";
    $verifyResponse =  mysqli_query($connector->conn, $verifyQuery);

    if(mysqli_num_rows($verifyResponse) > 0) {
        $row = mysqli_fetch_assoc($verifyResponse);
        if($user_id === $row['user_id']){
            // do insert and delete
            $insertQuery = "insert into Caregive values ('$user_id', '$viewer_id', null)";
            //echo $insertQuery;
            $deleteQuery = "delete from UnregisteredViewer where verification_code = '$verification_code'";
            if(mysqli_query($connector->conn, $insertQuery)) {
                if(mysqli_query($connector->conn, $deleteQuery)){

                    echo "<script> alert('Verification successful!'); window.location.assign('../viewer_admin_index.php')</script>";

                }
            }
            else{
                echo "<script> alert('Insert query failed'); window.location.assign('../viewer_admin_index.php')</script>";
            }
        }


    }

    else{
        echo "<script> alert('Sorry, you have keyed in the wrong verification code.'); window.location.assign('../viewer_admin_index.php')</script>";
    }
    $connector->close();

?>