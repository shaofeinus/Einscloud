<?php
/**
 * Created by PhpStorm.
 * User: Shao Fei
 * Date: 22/6/2015
 * Time: 4:50 PM
 */

/**
 * @date-of-doc: 2015-07-06
 *
 * @project-version: v0.2
 *
 * @called-by:
 * ../script/user_edit_profile_script.js
 *
 * @calls:
 * clean_up_input.php
 * DB_connect/db_utility.php
 *
 * @description:
 * Script that contains functions to update current User's profile
 *
 * */

require_once 'clean_up_input.php';

decideFunction();

function decideFunction() {

    if(isset($_POST['func']) && isset($_POST['params'])) {
        $function_name = $_POST['func'];
        $param = $_POST['params'];

        switch($function_name) {
            case 'loadProfile':
                return loadProfile();
            case 'hasEmail':
                return hasEmail();
            case 'editDOB':
                return editDOB($param);
            case 'editUsername':
                return editUsername($param);
            case 'editEmail':
                return editEmail($param);
            case 'deleteEmail':
                return deleteEmail();
            case 'checkOldPassword':
                return checkOldPassword($param);
            case 'changePassword':
                return changePassword($param);
            default:
                return NULL;
        }
    } else {
        return NULL;
    }
}

function loadProfile() {
    session_start();
    $result = array();
    $id = $_SESSION['login_id'];

    require_once __DIR__.'/DB_connect/db_utility.php';
    $link = get_conn();
    $selectStmt = mysqli_prepare($link,
        "SELECT username, fullname, email, nric, phone_no, gender, birthday FROM User WHERE id=?");
    $selectStmt->bind_param("i", $id);
    if($selectStmt->execute()) {
        $selectStmt->store_result();
        if ($selectStmt->num_rows === 1) {
            $selectStmt->bind_result($result['username'],
                $result['name'],
                $result['email'],
                $result['nric'],
                $result['phone_no'],
                $result['gender'],
                $result['dob']);
            $selectStmt->fetch();
            echo json_encode($result);
        }
        $link->close();
    }
}

function hasEmail() {
    session_start();
    $id = $_SESSION['login_id'];

    require_once __DIR__.'/DB_connect/db_utility.php';
    $link = get_conn();
    $selectStmt = mysqli_prepare($link, "SELECT email FROM User WHERE id=?");
    $selectStmt->bind_param("i", $id);

    if($selectStmt->execute()) {
        $selectStmt->bind_result($email);
        $selectStmt->fetch();
        if(!empty($email) || $email !== NULL) {
            echo json_encode(true);
        } else {
            echo json_encode(false);
        }
        $link->close();
    } else {
        echo json_encode(false);
    }
}

function editDOB($dob) {
    require_once __DIR__.'/DB_connect/db_utility.php';
    session_start();
    $id = $_SESSION['login_id'];

    $link = get_conn();
    $updateStmt = mysqli_prepare($link, "UPDATE User SET birthday=? WHERE id=?");
    $updateStmt->bind_param("si", $dob, $id);
    if($updateStmt->execute()) {
        $link->close();
        echo json_encode(true);
    } else {
        echo json_encode(false);
    }
}

function editUsername($username) {
    $username=cleanUpInput($username);
    require_once __DIR__.'/DB_connect/db_utility.php';
    session_start();
    $id = $_SESSION['login_id'];

    $link = get_conn();
    $updateStmt = mysqli_prepare($link, "UPDATE User SET username=? WHERE id=?");
    $updateStmt->bind_param("si", $username, $id);
    if($updateStmt->execute()) {
        $link->close();
        echo json_encode(true);
    } else {
        echo json_encode(false);
    }
}

function editEmail($email) {
    $email=cleanUpInput($email);
    require_once __DIR__.'/DB_connect/db_utility.php';
    session_start();
    $id = $_SESSION['login_id'];

    $link = get_conn();
    $updateStmt = mysqli_prepare($link, "UPDATE User SET email=? WHERE id=?");
    $updateStmt->bind_param("si", $email, $id);
    if($updateStmt->execute()) {
        $link->close();
        echo json_encode(true);
    } else {
        echo json_encode(false);
    }
}

function deleteEmail() {
    require_once __DIR__.'/DB_connect/db_utility.php';
    session_start();
    $id = $_SESSION['login_id'];

    $link = get_conn();
    $updateStmt = mysqli_prepare($link, "UPDATE User SET email=NULL WHERE id=?");
    $updateStmt->bind_param("i", $id);
    if($updateStmt->execute()) {
        $link->close();
        echo json_encode(true);
    } else {
        echo json_encode(false);
    }
}

function checkOldPassword($oldPassword) {
    $encryptPassword = md5($oldPassword);
    require_once __DIR__.'/DB_connect/db_utility.php';
    session_start();
    $id = $_SESSION['login_id'];

    $link = get_conn();
    $selectStmt = mysqli_prepare($link, "SELECT * FROM User WHERE id=? AND password=?");
    $selectStmt->bind_param("is", $id, $encryptPassword);
    if($selectStmt->execute()) {
        $selectStmt->store_result();
        if($selectStmt->num_rows === 1) {
            echo json_encode(true);
        } else {
            echo json_encode(false);
        }
        $link->close();
    } else {
        echo json_encode(false);
    }
}

function changePassword($newPassword) {
    $encryptPassword = md5($newPassword);
    require_once __DIR__.'/DB_connect/db_utility.php';
    session_start();
    $id = $_SESSION['login_id'];

    $link = get_conn();
    $updateStmt = mysqli_prepare($link, "UPDATE User SET password=? WHERE id=?");
    $updateStmt->bind_param("si", $encryptPassword, $id);
    if($updateStmt->execute()) {
        $link->close();
        echo json_encode(true);
    } else {
        echo json_encode(false);
    }
}

?>