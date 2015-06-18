<?php
/**
 * Created by PhpStorm.
 * User: Shao Fei
 * Date: 12/6/2015
 * Time: 3:01 PM
 * 
 * This script contains functions used to generate the page content for user_add_emerg.html
 * Functions in this script is called by a relevant JS script.
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
        $html_output = "";
        $num_rows = mysqli_num_rows($response);
        if($num_rows < 5){
            $html_output = "How may emergency landline contacts do you want to add?<br>".
                "<select id='add_num_emerg' onchange='displayAddEmergForm()'>".
                "<option value='0'></option>";
            $num_options = 5 - $num_rows;
            $index = 1;
            while($num_options) {
                $html_output = $html_output . "<option value=$index>$index</option>";
                $index++;
                $num_options--;
            }
            $html_output = $html_output . "</select>";
        } else{
            $html_output = "You already have 5 emergency landline contacts. Click <a href='user_admin_index.php'>here</a> to manage emergency landline contacts";
        }

        echo $html_output;

    } else {
        echo "error retrieving data";
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
            $output = $output . "<input type='submit' name='addEmergSubmit' value='Add Emergency landlines' class='btn btn-primary'>";
        }
    }

    echo $output;
}

function checkPhoneExists($phone_no) {
    session_start();
    $user_id = $_SESSION['login_id'];

    require_once __DIR__.'/DB_connect/db_utility.php';

    $query = "SELECT id FROM LandlineContact WHERE phone_no=$phone_no";

    $response = make_query($query);

    $landline_id = 0;

    if($response) {
        $row = mysqli_fetch_assoc($response);

        if($row) {
            $landline_id = $row['id'];
        }
    } else {
        echo "error";
    }

    $query = "SELECT * FROM CallLandline WHERE landline_id=$landline_id AND user_id=$user_id";

    $response = make_query($query);

    if($response) {
        if(mysqli_num_rows($response) > 0) {
            echo true;
        } else {
            echo false;
        }
    } else {
        echo "error";
    }
}

?>