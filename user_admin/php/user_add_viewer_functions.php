<?php
/**
 * Created by PhpStorm.
 * User: Shao Fei
 * Date: 11/6/2015
 * Time: 4:15 PM
 */

/**
 * @date-of-doc: 2015-07-06
 *
 * @project-version: v0.2
 *
 * @called-by:
 * ../script/user_add_viewer_script.js
 *
 * @calls:
 * ../logged_out.html
 * DB_connect/db_utility.php
 * ../user_admin_index.php
 *
 * @description:
 * Script that contains functions to display validate the User add mobile Caregiver form
 *
 */

decideFunction();

function decideFunction() {

    if(isset($_POST['func']) && isset($_POST['params'])) {
        $function_name = $_POST['func'];
        $param_1 = $_POST['params'];

        switch($function_name) {
            case 'checkPhoneExists':
                return checkPhoneExists($param_1);
            case 'emailAndPhoneMatch':
                return emailAndPhoneMatch($param_1);
            case 'displayDropMenu':
                return displayDropMenu();
            case 'verifyLogin':
                return verifyLogin();
            case 'displayAddViewerForm':
                $form_data = $_POST['params_2'];
                return displayAddViewerForm($param_1, $form_data);
            case 'getName':
                return getName();
            case 'logout':
                return logout();
            default:
                return NULL;
        }
    } else {
        return NULL;
    }
}

function displayDropMenu() {
    session_start();
    $user_id = $_SESSION['login_id'];

    require_once __DIR__.'/DB_connect/db_utility.php';
    $link = get_conn();
    $selectStmt = mysqli_prepare($link, "SELECT * FROM Caregive WHERE user_id=?");
    $selectStmt->bind_param("i", $user_id);

    if($selectStmt->execute()) {
        $selectStmt->store_result();
        $num_rows = $selectStmt->num_rows;
        $link->close();

        if($num_rows < 5){
            echo "<h3 class='page-header'>How many mobile Caregivers do you want to add?</h3>";
            echo "<select id='add_num_viewers' onchange='displayAddViewerForm()' class='selectpicker'>";
            $num_options = 5 - $num_rows;
            $index = 1;
            echo "<option value='0'></option>";
            while($num_options) {
                echo "<option value=$index>$index</option>";
                $index++;
                $num_options--;
            }
            echo "</select>";
        } else{
            echo "You already have 5 mobile Caregivers. Click <a href='user_admin_index.php'>here</a> to manage Viewers";
        }

    } else {
        echo "Error retrieving data";
    }
}

function verifyLogin() {
    session_start();
    if(empty($_SESSION['login_user'])) {
        echo true;
    } else {
        echo false;
    }
}

function getName() {
    session_start();
    $fullName = $_SESSION['login_fullname'];


    echo $fullName;
}

function displayAddViewerForm($num_forms, $form_data) {

    // echo $form_data;

    $filled_form_data = json_decode($form_data);

    $i = 0;
    $output = "";

    while ($num_forms != 0) {

        $nickname = "";
        $phone_no = "";
        $email = "";

        if($filled_form_data[$i] != null){
            $nickname = $filled_form_data[$i]->nickname;
            $phone_no = $filled_form_data[$i]->phone_no;
            $email = $filled_form_data[$i]->email;
        }

        $output = $output .
            "<table class='form_table' id='viewer_" . $i . "_table'>" .
            "<tr class='spaceUnder'><td></td></tr>" .
            "<tr><th class='form_th'><h4>Caregiver " . ($i + 1) . "</h4></th><tr>" .
            "<tr><td class='form_td'>Give your Caregiver a name</td></tr>" .
            "<tr class='spaceUnder'>" .
            "<td class='form_td'><input type='text' value='$nickname' name='nickname_" . $i . "' required></td></tr>" .
            "<tr><td class='form_td'>Phone number</td></tr>" .
            "<tr class='spaceUnder'>" .
            "<td class='form_td'><input type='text' value='$phone_no' oninput='validatePhoneNo(" . $i . ")' name='viewerPhone_" . $i . "' required></td>" .
            "<td class='form_td'><div class='alert-info' id='phone_no_feedback_" . $i . "'></div></td></tr>" .
            "<tr><td class='form_td'>Email</td></tr>" .
            "<tr class='spaceUnder'>" .
            "<td class='form_td'><input type='email' value='$email' oninput='validateEmail(" . $i . ")' name='viewerEmail_".$i."'></td>" .
            "<td class='form_td'><div class='alert-info' id='email_feedback_" . $i . "'></div></td>".
            "</tr>" .
            "</table>";

        $i++;
        $num_forms--;

        if ($num_forms == 0) {
            $output = $output . "<input type='submit' name='addViewersSubmit' value='Add Caregivers' class='btn btn-success'>";
        }
    }

    echo $output;
}

function checkPhoneExists($phone_no) {
    session_start();
    $user_id = $_SESSION['login_id'];

    $unregisteredPhoneExists = checkUnregisteredViewers($phone_no, $user_id);
    $registeredPhoneExists = checkRegisteredViewers($phone_no, $user_id);

    $output = array();

    if($registeredPhoneExists || $unregisteredPhoneExists){
        $output['exists'] = true;
        if($registeredPhoneExists) {
            $output['message'] = "Phone number is already one of your care giver";
        }

        if($unregisteredPhoneExists) {
            $output['message'] = "Phone number already is already requested and pending response";
        }
    } else {
        $output['exists'] = false;
        $output['message'] = "";
    }

    echo json_encode($output);
}

function checkUnregisteredViewers($phone_no, $user_id) {
    require_once __DIR__.'/DB_connect/db_utility.php';
    $link = get_conn();
    $selectStmt = mysqli_prepare($link, "SELECT * FROM UnregisteredViewer WHERE phone_no=? AND user_id=?");
    $selectStmt->bind_param("si", $phone_no, $user_id);

    if($selectStmt->execute()) {
        $selectStmt->store_result();
        if($selectStmt->num_rows > 0) {
            $link->close();
            return true;
        } else {
            $link->close();
            return false;
        }
    } else {
        return "error";
    }
}

function checkRegisteredViewers($phone_no, $user_id) {
    require_once __DIR__.'/DB_connect/db_utility.php';
    $link = get_conn();
    $selectStmt = mysqli_prepare($link, "SELECT rv_id FROM Caregive WHERE user_id=?");
    $selectStmt->store_result();
    $selectStmt->bind_param("i", $user_id);

    if($selectStmt->execute()) {
        $selectStmt->store_result();
        if($selectStmt->num_rows > 0) {
            $selectStmt->bind_result($rv_id);
            while($selectStmt->fetch()) {
                $link2 = get_conn();
                $selectStmt2 = mysqli_prepare($link2, "SELECT phone_no FROM RegisteredViewer WHERE id=?");
                $selectStmt2->bind_param("i", $rv_id);
                if($selectStmt2->execute()) {

                    $selectStmt2->bind_result($phone_no_each);
                    $selectStmt2->fetch();
                    if($phone_no_each === $phone_no) {
                        $link->close();
                        $link2->close();
                        return true;
                    }
                }
                $selectStmt2->close();
                $link2->close();
            }
            $link->close();
        }
        return false;
    } else {
        return "error";
    }
}


function logout() {
    session_destroy();
    header("Location: ../logged_out.html");
    echo "logged out";
}

?>