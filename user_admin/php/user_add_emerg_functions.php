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
    $link = get_conn();
    $selectStmt = mysqli_prepare($link, "SELECT * FROM CallLandline WHERE user_id=?");
    $selectStmt->bind_param("i", $user_id);

    if($selectStmt->execute()) {
        $selectStmt->store_result();
        $num_rows = $selectStmt->num_rows;
        if($num_rows < 5){
            $html_output = "<h3 class='page-header'>How may landline Caregivers do you want to add?</h3>".
                "<select id='add_num_emerg' class='selectpicker' onchange='displayAddEmergForm()'>".
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
            $html_output = "You already have 5 landline Caregivers. Click <a href='user_admin_index.php'>here</a> to manage emergency landline contacts";
        }

        echo $html_output;

    } else {
        echo "error retrieving data";
    }

    $link->close();
}

function displayAddEmergForm($json_object) {

    $data = json_decode($json_object);
    $num_forms = $data->num_emerg;
    $filled_form_data = $data->filled_form;

    $i = 0;
    $output = "";

    while ($num_forms != 0) {

        $nickname = "";
        $phone_no = "";

        if($filled_form_data[$i] != null){
            $nickname = $filled_form_data[$i]->nickname;
            $phone_no = $filled_form_data[$i]->phone_no;
        }

        $output = $output .
            "<table class='form_table' name='landline_" . $i . "_table'>" .
            "<tr class='spaceUnder'><td></td></tr>" .
            "<tr><th class='form_th'><h4>Caregiver contact " . ($i + 1) . "</h4></th><tr>" .
            "<tr><td class='form_td'>Give your landline contact a name</td></tr>" .
            "<tr class='spaceUnder'><td class='form_td'><input type='text' value='$nickname' name='nickname_" . $i . "' required></td></tr>" .
            "<tr><td class='form_td'>Phone number</td></tr>" .
            "<tr class='spaceUnder'>" .
            "<td class='form_td'><input type='text' value='$phone_no' oninput='validatePhoneNo(" . $i . ")' name='landPhone_" . $i . "' required></td>" .
            "<td class='form_td'><div class='feedback' id='phone_no_feedback_" . $i . "'></div></td>" .
            "</tr>" .
            "</table>";

        $i++;
        $num_forms--;

        if ($num_forms == 0) {
            $output = $output . "<input type='submit' name='addEmergSubmit' value='Add Caregivers' class='btn btn-success'>";
        }
    }

    echo $output;
}

function checkPhoneExists($phone_no) {
    session_start();
    $user_id = $_SESSION['login_id'];

    require_once __DIR__.'/DB_connect/db_utility.php';
    $link = get_conn();
    $selectStmt1 = mysqli_prepare($link, "SELECT id FROM LandlineContact WHERE phone_no=?");
    $selectStmt1->bind_param("s", $phone_no);

    if($selectStmt1->execute()) {
        $selectStmt1->bind_result($landline_id);
        $selectStmt1->fetch();
        $link->close();
    } else {
        echo "error";
    }

    $link = get_conn();
    $selectStmt2 = mysqli_prepare($link, "SELECT * FROM CallLandline WHERE landline_id=? AND user_id=?");
    $selectStmt2->bind_param("ii", $landline_id, $user_id);

    if($selectStmt2->execute()) {
        $selectStmt2->store_result();
        if($selectStmt2->num_rows > 0) {
            echo true;
        } else {
            echo false;
        }
        $link->close();
    } else {
        echo "error";
    }
}

?>