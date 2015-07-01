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
    $query = "SELECT * FROM Caregive WHERE user_id='$user_id'";
    $response = make_query($query);
    if($response) {
        $num_rows = mysqli_num_rows($response);

        if($num_rows < 5){
            echo "How many caregivers do you want to add?<br>";
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
            echo "You already have 5 Caregivers. Click <a href='user_admin_index.php'>here</a> to manage Viewers";
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
            "<tr><th class='form_th'>Caregiver " . ($i + 1) . "</th><tr>" .
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
            $output = $output . "<input type='submit' name='addViewersSubmit' value='Add Caregivers' class='btn btn-primary'>";
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
    $query = "SELECT * FROM UnregisteredViewer WHERE phone_no='$phone_no' AND user_id=$user_id";
    $response = make_query($query);
    if($response) {
        if(mysqli_num_rows($response) > 0) {
            return true;
        } else {
            return false;
        }
    } else {
        return "error";
    }
}

function checkRegisteredViewers($phone_no, $user_id) {
    require_once __DIR__.'/DB_connect/db_utility.php';
    $query = "SELECT rv_id FROM Caregive WHERE user_id=$user_id";
    $response = make_query($query);
    if($response) {
        if(mysqli_num_rows($response)) {
            while($row = mysqli_fetch_assoc($response)) {
                $rv_id = $row['rv_id'];
                $query = "SELECT phone_no FROM RegisteredViewer WHERE id=$rv_id";
                $response_each = make_query($query);
                if(mysqli_num_rows($response_each)) {
                    $row_each = mysqli_fetch_assoc($response_each);
                    $phone_no_each = $row_each['phone_no'];
                    if($phone_no_each == $phone_no) {
                        return true;
                    }
                }
            }
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