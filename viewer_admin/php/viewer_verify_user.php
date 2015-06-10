<?php
    session_start();

    $verification_code = $_POST['verification_code'];
    $user_id = $_POST['user_id'];
    $viewer_id = $_SESSION['viewer_id'];
    //echo $viewer_id;
    //echo $verification_code;
    //echo $_SESSION['login_viewer'] . "<br>";

    require_once __DIR__.'/DB_connect/db_connect.php';
    $connector = new DB_CONNECT();
    $connector->connect();

    $verifyQuery = "select * from UnregisteredViewer where '$verification_code' = verification_code";
    $verifyResponse =  mysqli_query($connector->conn, $verifyQuery);

    if(mysqli_num_rows($verifyResponse) > 0) {
        $row = mysqli_fetch_assoc($verifyResponse);
        if($user_id === $row['user_id']){
            // do insert and delete
            $insertQuery = "insert into Caregive values ('$user_id', '$viewer_id', null)";
            echo $insertQuery;
            $deleteQuery = "delete from UnregisteredViewer where verification_code = '$verification_code'";
            if(mysqli_query($connector->conn, $insertQuery)) {
                if(mysqli_query($connector->conn, $deleteQuery)){
                    header("Location: ../viewer_admin_index.php");
                    echo "insert delete successful!";
                }
            }
            else{
                echo "insert query failed";
            }
        }


    }

    else{
        echo "your verification key is wrong";
    }

    $connector->close();

?>