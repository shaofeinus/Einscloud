<?php
/**
 * Created by PhpStorm.
 * User: Shao Fei
 * Date: 22/6/2015
 * Time: 4:50 PM
 */

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
    $query = "SELECT * FROM User WHERE id='$id'";
    $response = make_query($query);

    if($response) {
        if(mysqli_num_rows($response) == 1) {
            $row = mysqli_fetch_assoc($response);
            $result['username'] = $row['username'];
            $result['name'] = $row['firstname'] . " " . $row['lastname'];
            $result['email'] =  $row['email'];
            $result['nric'] =  $row['nric'];
            $result['phone_no'] =  $row['phone_no'];
            $result['gender'] = $row['gender'];
            $result['dob'] = $row['birthday'];
        }
    }

    echo json_encode($result);
}

function hasEmail() {
    session_start();
    $result = array();
    $id = $_SESSION['login_id'];

    require_once __DIR__.'/DB_connect/db_utility.php';
    $query = "SELECT email FROM User WHERE id='$id'";
    $response = make_query($query);
    if($response) {
        $email = mysqli_fetch_assoc($response)['email'];
        if(!empty($email)) {
            echo json_encode(true);
        } else {
            echo json_encode(false);
        }
    } else {
        echo json_encode(false);
    }
}

function editDOB($dob) {
    require_once __DIR__.'/DB_connect/db_utility.php';
    session_start();
    $id = $_SESSION['login_id'];
    $query = "UPDATE User SET birthday='$dob' WHERE id=$id";
    $response = make_query($query);
    if($response) {
        echo json_encode(true);
    } else {
        echo json_encode(false);
    }
}

function editUsername($username) {
    require_once __DIR__.'/DB_connect/db_utility.php';
    session_start();
    $id = $_SESSION['login_id'];
    $query = "UPDATE User SET username='$username' WHERE id=$id";
    $response = make_query($query);
    if($response) {
        echo json_encode(true);
    } else {
        echo json_encode(false);
    }
}

function editEmail($email) {
    require_once __DIR__.'/DB_connect/db_utility.php';
    session_start();
    $id = $_SESSION['login_id'];
    $query = "UPDATE User SET email='$email' WHERE id=$id";
    $response = make_query($query);
    if($response) {
        echo json_encode(true);
    } else {
        echo json_encode(false);
    }
}

function deleteEmail() {
    require_once __DIR__.'/DB_connect/db_utility.php';
    session_start();
    $id = $_SESSION['login_id'];
    $query = "UPDATE User SET email=NULL WHERE id=$id";
    $response = make_query($query);
    if($response) {
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
    $query = "SELECT * FROM User WHERE id=$id AND password='$encryptPassword'";
    $response = make_query($query);
    if($response) {
        if(mysqli_num_rows($response) == 1) {
            echo json_encode(true);
        } else {
            echo json_encode(false);
        }
    } else {
        echo json_encode(false);
    }
}

function changePassword($newPassword) {
    $encryptPassword = md5($newPassword);
    require_once __DIR__.'/DB_connect/db_utility.php';
    session_start();
    $id = $_SESSION['login_id'];
    $query = "UPDATE User SET password='$encryptPassword' WHERE id=$id";
    $response = make_query($query);
    if($response) {
        echo json_encode(true);
    } else {
        echo json_encode(false);
    }
}


?>