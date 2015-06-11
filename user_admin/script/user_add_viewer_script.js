/**
 * Created by Shao Fei on 11/6/2015.
 */
var num_viewer;

function displayAddViewerForm() {
    var output = "";
    var dropdownMenu = document.getElementById("add_num_viewers");
    num_viewer = parseInt(dropdownMenu.options[dropdownMenu.selectedIndex].value);
    var num_viewer_forms_left = num_viewer;
    var i = 0;

    while(num_viewer_forms_left !== 0) {
        output += "<table class='form_table'>"
        + "<tr><th class='form_th'>Viewer " + (i + 1) + "</th><tr>"
        + "<tr><td class='form_td'>Give your Viewer a name</td></tr>"
        + "<tr><td class='form_td'><input type='text' name='nickname_" + i + "' required></td></tr>"
        + "<tr><td class='form_td'>Phone number</td></tr>"
        + "<tr>"
        + "<td class='form_td'><input type='text' oninput='validatePhoneNo(" + i + ")' name='viewerPhone_" + i + "' required></td>"
        + "<td class='form_td'><div class='feedback' id='phone_no_feedback_" + i + "'></div></td>"
        + "</tr>"
        + "<tr><td class='form_td'>Email</td></tr>"
        + "<td class='form_td'><input type='text' oninput='validateEmail(" + i + ")' name='viewerEmail_" + i + "'></td>"
        + "<td class='form_td'><div class='feedback' id='email_feedback_" + i + "'></div></td>"
        + "</table>";

        i++;
        num_viewer_forms_left--;

        if(num_viewer_forms_left === 0) {
            output += "<input type='submit' name='addViewersSubmit' value='Add Viewers'>";
        }
    }

    document.getElementById("add_viewers_form").innerHTML = output;
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
    } else if(!emailAndPhoneMatch(i)) {
        document.getElementById("email_feedback_" + i).innerHTML = "Email does not match the phone number";
        currEmailValid = false;
    } else if(document.forms["add_viewers_form"]["viewerPhone_" + i].value === "") {
        document.getElementById("phone_no_feedback_" + i).innerHTML = "Missing phone number";
        currEmailValid = false;
    } else {
        document.getElementById("email_feedback_" + i).innerHTML = "";
        currEmailValid = true;
    }

    console.log("email " + i + " valid " + currEmailValid);
    return currEmailValid
}

//TODO: check for match IF viewer is registered
function emailAndPhoneMatch(i) {
    var emailInput = document.forms["add_viewers_form"]["viewerEmail_" + i].value;
    var phoneNoInput = document.forms["add_viewers_form"]["viewerPhone_" + i].value;
    return true;
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
