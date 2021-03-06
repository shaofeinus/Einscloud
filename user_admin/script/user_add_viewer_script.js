/**
 * Created by Shao Fei on 11/6/2015.
 */

/**
 * @date-of-doc: 2015-07-06
 *
 * @project-version: v0.2
 *
 * @called-by:
 * ../user_add_viewer.php
 *
 * @calls:
 * ../php/user_add_viewer_functions.php
 * ../php/logout.php
 *
 * @description:
 * Script that contains functions to call php functions to display add mobile Caregiver form and
 * validate add mobile Caregiver form.
 *
 */

var num_viewer;
var num_exists = [];
var curr_form;
var filled_form = [];

function verifyLogin() {
    $.post('php/user_add_viewer_functions.php',
        {
            func: 'verifyLogin',
            params: ''
        },
        function(data, status) {
            if(data == true) {
                window.location.replace("logged_out.html");
            }
            console.log(data);
        });
}

function getName() {
    $.post('php/user_add_viewer_functions.php',
        {
            func: 'getName',
            params: ''
        },
        function(data, status) {
            console.log(data);
            document.getElementById('user_real_name').innerHTML = data;
        });
}

function displayDropMenu() {

    var MAX_VIEWER = 5;

    while(MAX_VIEWER) {
        filled_form.push({nickname:"", phone_no:"", email:""});
        MAX_VIEWER--;
    }

    $.post('php/user_add_viewer_functions.php',
        {
            func: 'displayDropMenu',
            params: ''
        },
        function(data, status) {
            console.log(data);
            document.getElementById('drop_menu').innerHTML = data;
        });
}

function displayAddViewerForm() {
    cacheForm();
    var output = "";
    var dropdownMenu = document.getElementById("add_num_viewers");
    num_viewer = parseInt(dropdownMenu.options[dropdownMenu.selectedIndex].value);

    $.post('php/user_add_viewer_functions.php',
        {
            func: 'displayAddViewerForm',
            params: num_viewer,
            params_2: JSON.stringify(filled_form)
        },

        function(data, status) {
            console.log(data);
            var num_rows = num_viewer;
            while(num_rows) {
                num_exists.push(true);
                num_rows--;
            }
            document.getElementById("add_viewers_form").innerHTML = data;
        });

}

function cacheForm() {
    for(var i = 0; i < num_viewer; i++) {
        if(document.getElementById("viewer_" + i + "_table") !== undefined) {

            var nickname_field = document.forms["add_viewers_form"].elements["nickname_" + i];
            var phone_no_field = document.forms["add_viewers_form"].elements["viewerPhone_" + i];
            var email_field = document.forms["add_viewers_form"].elements["viewerEmail_" + i];

            var nickname = nickname_field===undefined?"":nickname_field.value;
            var phone_no = phone_no_field===undefined?"":phone_no_field.value;
            var email = email_field===undefined?"":email_field.value;

            filled_form[i] = {nickname:nickname, phone_no:phone_no, email:email};

        }
    }
}

function validatePhoneNo(i) {
    curr_form = i;
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
        currPhoneNoValid = true;
        var container = document.getElementById("phone_no_feedback_" + i);
        checkPhoneExists(phoneNoInput, container);
    }

    console.log("phone no " + i + " valid: " + currPhoneNoValid + " number exists: " + num_exists[i]);
    return currPhoneNoValid;
}

function checkPhoneExists(phoneNoInput, container) {
    $.post('php/user_add_viewer_functions.php',
        {
            func: "checkPhoneExists",
            params: phoneNoInput
        },
        function(data, status) {
            if(data) {
                data = JSON.parse(data);
                num_exists[curr_form] = data['exists'];
                container.innerHTML = data['message'];
                console.log(data);
            } else {
                container.innerHTML = "";
            }
        });
}

function validateEmail(i) {
    var currEmailValid;
    var emailInput = document.forms["add_viewers_form"]["viewerEmail_" + i].value;

    if(/[\s]/.test(emailInput)) {
        document.getElementById("email_feedback_" + i).innerHTML = "Email should not contain blank space";
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

function validateForm() {
    var formIsValid = true;
    var i;
    for(i = 0; i < num_viewer; i++) {
        console.log("loop " + i);
        var phoneNoValid = validatePhoneNo(i);
        var emailValid = validateEmail(i);
        console.log(i + " " + phoneNoValid + " " + emailValid + " " + num_exists[i]);
        if(!(phoneNoValid && emailValid)){
            formIsValid = false;
            break;
        } else if(num_exists[i]) {
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

function logout() {
    $.post('php/logout.php', "",
        function(data, status) {
            window.location.replace("php/logout.php");
            console.log(data);
        });
}