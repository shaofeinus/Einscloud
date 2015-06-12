<?php
/**
 * Created by PhpStorm.
 * User: Shao Fei
 * Date: 12/6/2015
 * Time: 3:49 PM
 */
processAddEmerg();

function processAddEmerg() {
    session_start();
    if(isset($_POST['addEmergSubmit'])) {
        $i = 0;
        $successEmerg = array();

        while(isset($_POST['nickname_'.$i]) && isset($_POST['landPhone_'.$i])) {
            $name = $_POST['nickname_'.$i];
            $phone_no = $_POST['landPhone_'.$i];

            $landline_id = landlineExists($phone_no);

            if($landline_id != 0) {
                $instance = updateCallLandlineTable($landline_id, $name);
            } else {
                addLandline($phone_no);
                $instance = updateCallLandlineTable(landlineExists($phone_no), $name);
            }

            array_push($successEmerg, $instance);
            $i++;
        }

        $alert_feedback = "";

        foreach($successEmerg as $landline) {
            if($landline[1]) {
                $alert_feedback = $alert_feedback."Successfully added ".$landline[0]."\n";
            } else {
                $alert_feedback = $alert_feedback."Failed to add ".$landline[0]."\n";
            }
        }
        $s = json_encode($alert_feedback);
        echo "<script>alert($s); window.location = '../user_admin_index.php';</script>";
    }
}

function updateCallLandlineTable($landline_id, $name) {
    $user_id = $_SESSION['login_id'];
    require_once __DIR__.'/DB_connect/db_utility.php';
    $query = "INSERT INTO CallLandline (landline_id, user_id, name) VALUES ($landline_id, $user_id, '$name')";
    $response = make_query($query);

    $instance = array();
    array_push($instance, $name);

    if($response) {
        array_push($instance, true);
    } else {
        array_push($instance, false);
    }

    return $instance;
}

function addLandline($phone_no) {

    require_once __DIR__.'/DB_connect/db_utility.php';
    $query = "INSERT INTO LandlineContact (phone_no) VALUES ('$phone_no')";
    $response = make_query($query);

    if(!$response) {
        echo "<script>alert('Error query: addLandline');</script>";
    }

}

function landlineExists($phone_no) {

    require_once __DIR__.'/DB_connect/db_utility.php';
    $query = "SELECT * FROM LandlineContact WHERE phone_no='$phone_no'";
    $response = make_query($query);

    if($response) {
        if(mysqli_num_rows($response) == 1){
            $row = mysqli_fetch_array($response, MYSQL_ASSOC);
            return $row['id'];
        } else {
            return 0;
        }
    } else {
        echo "<script>alert('Error query: landlineExists');</script>";
    }

}

?>