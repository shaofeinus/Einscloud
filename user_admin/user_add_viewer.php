<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head lang="en">
    <link rel="stylesheet" type="text/css" href="style/user_resgistration_style.css">
    <meta charset="UTF-8">
    <title>Einswatch add Viewers</title>
</head>

<body>
<script>
    var num_viewer;

    function displayAddViewerForm() {
        var output = "";
        var dropdownMenu = document.getElementById("add_num_viewers");
        num_viewer = parseInt(dropdownMenu.options[dropdownMenu.selectedIndex].value);
        var num_viewer_forms_left = num_viewer;
        var i = 0;

        while(num_viewer_forms_left !== 0) {
            output += "<table>"
                + "<tr><th>Viewer " + (i + 1) + "</th><tr>"
                + "<tr><td>Give your Viewer a name</td></tr>"
                + "<tr><td><input type='text' name='nickname_" + i + "' required></td></tr>"
                + "<tr><td>Phone number</td></tr>"
                + "<tr>"
                + "<td><input type='text' oninput='validatePhoneNo(" + i + ")' name='viewerPhone_" + i + "' required></td>"
                + "<td><div class='feedback' id='phone_no_feedback_" + i + "'></div></td>"
                + "</tr>"
                + "<tr><td>Email</td></tr>"
                + "<td><input type='text' oninput='validateEmail(" + i + ")' name='viewerEmail_" + i + "'></td>"
                + "<td><div class='feedback' id='email_feedback_" + i + "'></div></td>"
                + "</table>";

            i++;
            num_viewer_forms_left--;

            if(num_viewer_forms_left === 0) {
                output += "<input type='submit' name='addViewers' value='Add Viewers'>";
            }
        }

        document.getElementById("add_viewers").innerHTML = output;
    }

    function validatePhoneNo(i) {
        var currPhoneNoValid;
        var phoneNoInput = document.forms["add_viewers_form"]["viewerPhone_" + i].value;

        if(phoneNoInput === "") {
            document.getElementById("phone_no_feedback_" + i).innerHTML = "";
            currPhoneNoValid = false;
        } else if(/[\s]/.test(phoneNoInput)) {
            document.getElementById("phone_no_feedback_" + i).innerHTML = "Phone number should not contain blank space";
            currPhoneNoValid = false;
        } else if (!/^[8|9][0-9][0-9][0-9][0-9][0-9][0-9][0-9]$/.test(phoneNoInput)) {
            document.getElementById("phone_no_feedback_" + i).innerHTML = "Invalid phone number";
            currPhoneNoValid = false;
        } else {
            document.getElementById("phone_no_feedback_" + i).innerHTML = "";
            currPhoneNoValid = true
        }

        console.log("phone no " + i + " valid " + currPhoneNoValid);
        return currPhoneNoValid;
    }

    function validateEmail(i) {
        var currEmailValid;
        var emailInput = document.forms["add_viewers_form"]["viewerEmail_" + i].value;

        if(/[\s]/.test(emailInput)) {
            document.getElementById("email_feedback_" + i).innerHTML = "Email should not contain blank space";
            currEmailValid = false;
        } else {
            document.getElementById("email_feedback_" + i).innerHTML = "";
            currEmailValid = true;
        }

        console.log("email " + i + " valid " + currEmailValid);
        return currEmailValid
    }

    function validateForm() {
        var formIsValid = true;
        var i;
        for(i = 0; i < num_viewer; i++) {
            console.log("loop " + i);
            var phoneNoValid = validatePhoneNo(i);
            var emailValid = validateEmail(i);
            console.log(i + " " + phoneNoValid + " " + emailValid);
            if(!(phoneNoValid && emailValid)){
                formIsValid = false;
                break;
            }
        }
        if(formIsValid === false) {
            alert("Form is incomplete/contains invalid fields");
            return false;
        } else {
            return true;
        }
    }

</script>
<?php verifyLogin(); ?>
<h1>Welcome <?php echo $_SESSION['login_user']; ?> </h1>

<form name="select_no_of_viewers">
    <?php displayDropMenu(); ?><br>
</form>

<form id="add_viewers" name="add_viewers_form" action="php/user_add_viewer.php" method="post" onsubmit="return validateForm()"></form>

</body>
</html>

<?php
/**
 * Created by PhpStorm.
 * User: Shao Fei
 * Date: 10/6/2015
 * Time: 12:23 PM
 */
//header('Content-Type: application/json');

decideFunction();

function decideFunction() {
    if(isset($_POST['func']) && isset($_POST['params'])) {
        switch($_POST['func']) {
            case 'displayForm':
                $num_viewers = $_POST['params'][0];
                displayForm($num_viewers);
                break;
            default:
                break;
        }
    }
}


function verifyLogin() {
    if(empty($_SESSION['login_user'])) {
        header("Location: logged_out.html");
    }
}

function displayDropMenu() {
    $user_id = 1;
    //$_SESSION['id'];
    $response = make_sql_query($user_id);
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

function make_sql_query($user_id) {
    require_once 'php/DB_connect/db_connect.php';
    $connector = new DB_CONNECT();
    $connector->connect();

    $query = "SELECT * FROM Caregive WHERE user_id='$user_id'";

    $response = mysqli_query($connector->conn, $query);
    $connector->close();
    return $response;
}
?>