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

    require_once 'DB_connect/db_utility.php';
    //$connector = new DB_CONNECT();
    //$connector->connect();

    //$verifyQuery = "select * from UnregisteredViewer where '$verification_code' = verification_code";
    //$verifyResponse =  mysqli_query($connector->conn, $verifyQuery);

    $link = get_conn();
    $verifyStmt = mysqli_prepare($link, "select user_id from UnregisteredViewer where verification_code = ?");
    $verifyStmt->bind_param("s", $verification_code);
    $verifyStmt->execute();
    $verifyStmt->store_result();
    $verifyStmt->bind_result($row['user_id']);
    $link->close();

    if($verifyStmt->num_rows > 0) {
        $verifyStmt->fetch();
        if($user_id == $row['user_id']){
            // do insert and delete
            $link = get_conn();
            $insertStmt = mysqli_prepare($link, "insert into Caregive values (?, ?, null)");
            $insertStmt->bind_param("ii", $user_id, $viewer_id);
            $insertResult = $insertStmt->execute();


            $deleteStmt = mysqli_prepare($link, "delete from UnregisteredViewer where verification_code = ?");
            $deleteStmt->bind_param("s", $verification_code);
            $deleteResult = $deleteStmt->execute();

            $link->close();

            //$insertQuery = "insert into Caregive values ('$user_id', '$viewer_id', null)";
            //echo $insertQuery;
            //$deleteQuery = "delete from UnregisteredViewer where verification_code = '$verification_code'";
            if($insertResult == true) {
                if($deleteResult == true){

                    echo "<script> alert('Verification successful!'); window.location.assign('../caregiver_admin_index.php')</script>";

                }
            }
            else{
                echo "<script> alert('Insert query failed'); window.location.assign('../caregiver_admin_index.php')</script>";
            }
        }


    }

    else{
        echo "<script> alert('Sorry, you have keyed in the wrong verification code.'); window.location.assign('../caregiver_admin_index.php')</script>";
    }
    //$connector->close();

?>