<?php
/**
 * Created by PhpStorm.
 * User: Shao Fei
 * Date: 12/6/2015
 * Time: 3:49 PM
 */

/**
 * @date-of-doc: 2015-07-06
 *
 * @project-version: v0.2
 *
 * @called-by:
 * ../user_add_viewer.php
 *
 * @calls:
 * DB_connect/check_session_validity.php
 * clean_up_input.php
 * DB_connect/db_utility.php
 * ../user_admin_index.php
 *
 * @description:
 * Script that adds new landline Caregiver into database for current User
 *
 */

//start session and check for session validity
require_once 'DB_connect/check_session_validity.php';

require_once 'clean_up_input.php';

processAddEmerg();

function processAddEmerg() {
    if(isset($_POST['addEmergSubmit'])) {
        $i = 0;
        $successEmerg = array();

        while(isset($_POST['nickname_'.$i]) && isset($_POST['landPhone_'.$i])) {
            $name = cleanUpInput($_POST['nickname_'.$i]);
            $phone_no = cleanUpInput($_POST['landPhone_'.$i]);

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
    $link = get_conn();
    $insertStmt = mysqli_prepare($link,
        "INSERT INTO CallLandline (landline_id, user_id, name) VALUES (?, ?, ?)");
    $insertStmt->bind_param("iis", $landline_id, $user_id, $name);

    $instance = array();
    array_push($instance, $name);

    if($insertStmt->execute()) {
        array_push($instance, true);
        $link->close();
    } else {
        array_push($instance, false);
    }

    return $instance;
}

function addLandline($phone_no) {

    require_once __DIR__.'/DB_connect/db_utility.php';
    $link = get_conn();
    $insertStmt = mysqli_prepare($link,
        "INSERT INTO LandlineContact (phone_no) VALUES (?)");
    $insertStmt->bind_param("s", $phone_no);

    if($insertStmt->execute()) {
       $link->close();
    } else {
        echo "<script>alert('Error query: addLandline');</script>";
    }

}

function landlineExists($phone_no) {

    require_once __DIR__.'/DB_connect/db_utility.php';
    $link = get_conn();
    $selectStmt = mysqli_prepare($link, "SELECT id FROM LandlineContact WHERE phone_no=?");
    $selectStmt->bind_param("s", $phone_no);

    if($selectStmt->execute()) {
        $selectStmt->bind_result($id);
        if($selectStmt->fetch()) {
            return $id;
        } else {
            return 0;
        }
    } else {
        echo "<script>alert('Error query: landlineExists');</script>";
    }

}

?>