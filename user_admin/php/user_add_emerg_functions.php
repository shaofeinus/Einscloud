<?php
/**
 * Created by PhpStorm.
 * User: Shao Fei
 * Date: 12/6/2015
 * Time: 3:01 PM
 */

decideFunction();

function decideFunction() {

    if(isset($_POST['func']) && isset($_POST['params'])) {
        $function_name = $_POST['func'];
        $function_params = $_POST['params'];

        switch($function_name) {
            case 'displayDropMenu':
                return displayDropMenu();
            case 'displayAddEmergForm':
                return displayAddEmergForm($function_params);
            case 'checkPhoneExists':
                return checkPhoneExists($function_params);
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
    $query = "SELECT * FROM CallLandline WHERE user_id='$user_id'";
    $response = make_query($query);
    if($response) {
        $num_rows = mysqli_num_rows($response);

        if($num_rows < 5){
            echo "How may emergency landline contacts do you want to add?<br>";
            echo "<select id='add_num_emerg' onchange='displayAddEmergForm()'>";
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
            echo "You already have 5 emergency landline contacts. Click <a href='user_admin_index.php'>here</a> to manage emergency landline contacts";
        }

    } else {
        echo "Error retrieving data";
    }
}

function displayAddEmergForm($num_forms) {
    $i = 0;
    $output = "";

    while ($num_forms != 0) {
        $output = $output .
            "<table class='form_table'>" .
            "<tr><th class='form_th'>Emergency landline contact " . ($i + 1) . "</th><tr>" .
            "<tr><td class='form_td'>Give your landline contact a name</td></tr>" .
            "<tr><td class='form_td'><input type='text' name='nickname_" . $i . "' required></td></tr>" .
            "<tr><td class='form_td'>Phone number</td></tr>" .
            "<tr>" .
            "<td class='form_td'><input type='text' oninput='validatePhoneNo(" . $i . ")' name='landPhone_" . $i . "' required></td>" .
            "<td class='form_td'><div class='feedback' id='phone_no_feedback_" . $i . "'></div></td>" .
            "</tr>" .
            "</table>";

        $i++;
        $num_forms--;

        if ($num_forms == 0) {
            $output = $output . "<input type='submit' name='addEmergSubmit' value='Add Viewers'>";
        }
    }

    echo $output;
}

function checkPhoneExists($phone_no) {
    require_once __DIR__.'/DB_connect/db_utility.php';
    $phone_no = json_decode($phone_no);
    $query = "SELECT * FROM LandlineContact WHERE phone_no='$phone_no'";
    $response = make_query($query);

    if($response) {
        if(mysqli_num_rows($response) > 0) {
            echo json_encode(true);
        } else {
            echo json_encode(false);
        }
    } else {
        echo json_encode("error");
    }
}

?>