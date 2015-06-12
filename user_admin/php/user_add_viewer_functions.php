<?php
/**
 * Created by PhpStorm.
 * User: Shao Fei
 * Date: 11/6/2015
 * Time: 4:15 PM
 */

decideFunction();

function decideFunction() {

    if(isset($_POST['func']) && isset($_POST['params'])) {
        $function_name = $_POST['func'];
        $function_params = $_POST['params'];

        switch($function_name) {
            case 'checkViewerPhoneExists':
                return checkViewerPhoneExists($function_params);
            case 'emailAndPhoneMatch':
                return emailAndPhoneMatch($function_params);
            case 'displayDropMenu':
                return displayDropMenu();
            case 'verifyLogin':
                return verifyLogin();
            case 'displayAddViewerForm':
                return displayAddViewerForm($function_params);
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
    $query = "SELECT * FROM Caregive WHERE user_id='$user_id'";
    $response = make_query($query);
    if($response) {
        $num_rows = mysqli_num_rows($response);

        if($num_rows < 5){
            echo "How may viewers do you want to add?<br>";
            echo "<select id='add_num_viewers' onchange='displayAddViewerForm()'>";
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
            echo "You already have 5 Viewer. Click <a href='user_admin_index.php'>here</a> to manage Viewers";
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
    $firstName = $_SESSION['login_firstname'];
    $lastName = $_SESSION['login_lastname'];

    echo $firstName." ".$lastName;
}

function displayAddViewerForm($num_forms)
{
    $i = 0;
    $output = "";

    while ($num_forms != 0) {
        $output = $output .
            "<table class='form_table'>" .
            "<tr><th class='form_th'>Viewer " . ($i + 1) . "</th><tr>" .
            "<tr><td class='form_td'>Give your Viewer a name</td></tr>" .
            "<tr><td class='form_td'><input type='text' name='nickname_" . $i . "' required></td></tr>" .
            "<tr><td class='form_td'>Phone number</td></tr>" .
            "<tr>" .
            "<td class='form_td'><input type='text' oninput='validatePhoneNo(" . $i . ")' name='viewerPhone_" . $i . "' required></td>" .
            "<td class='form_td'><div class='feedback' id='phone_no_feedback_" . $i . "'></div></td>" .
            "</tr>" .
            "<tr><td class='form_td'>Email</td></tr>" .
            "<tr>" .
            "<td class='form_td'><input type='email' oninput='validateEmail(" . $i . ")' name='viewerEmail_".$i."'></td>" .
            "<td class='form_td'><div class='feedback' id='email_feedback_" . $i . "'></div></td>".
            "</tr>" .
            "</table>";

        $i++;
        $num_forms--;

        if ($num_forms == 0) {
            $output = $output . "<input type='submit' name='addViewersSubmit' value='Add Viewers'>";
        }
    }

    echo $output;
}

function logout() {
    session_destroy();
    header("Location: ../logged_out.html");
    echo "logged out";
}

/** Unused functions */
function emailAndPhoneMatch($params_json) {
    $params = json_decode($params_json);
    $email = $params[0];
    $phone = $params[1];
    echo $email.$phone;
    $query = "SELECT * FROM RegisteredViewer WHERE phone_no='$phone' AND email='$email'";
    require_once __DIR__.'/DB_connect/db_utility.php';
    $response = make_query($query);
    if($response) {
        if(mysqli_num_rows($response) > 0) {
            echo json_encode(true);
        } else {
            echo json_encode(false);
        }
    } else {
        echo "<script>console.log('mysql error in check email phone match');</script>";
    }
}

function checkViewerPhoneExists($param_json) {
    $phoneNo = json_decode($param_json);
    $query = "SELECT * FROM RegisteredViewer WHERE phone_no='$phoneNo'";
    require_once __DIR__.'/DB_connect/db_utility.php';
    $response = make_query($query);
    if($response) {
        if(mysqli_num_rows($response) > 0) {
            echo json_encode(true);
        } else {
            echo json_encode(false);
        }
    } else {
        echo "<script>console.log('mysql error in check phone exist');</script>";
    }
}

?>