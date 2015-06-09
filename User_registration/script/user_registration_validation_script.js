/**
 * Created by Shao Fei on 8/6/2015.
 */

var fieldIsValid = [];

function validateForm() {
    var firstNameInput = document.forms["user_registration_form"]["firstName"].value;
    var lastNameInput = document.forms["user_registration_form"]["lastName"].value;

    if(firstNameInput.trim() === "" && lastNameInput.trim() === "") {
        fieldIsValid[0] = false;
    } else {
        fieldIsValid[0] = true;
    }

    validateNric();
    validatePhoneNo();
    validateUsername();
    validatePassword();
    validateConfirmPassword();
    console.log(fieldIsValid);
}

function validateNric() {
    var userNricInput = document.forms["user_registration_form"]["nric"].value;

    if(userNricInput.trim() === "") {
        document.getElementById("nric_feedback").innerHTML = "";
        fieldIsValid[1] = false;
    } else if(!/^(s|t)[0-9][0-9][0-9][0-9][0-9][0-9][0-9][a-z]$/i.test(userNricInput)){
        document.getElementById("nric_feedback").innerHTML = "NRIC invalid";
        fieldIsValid[1] = false;
    }  else {
        document.getElementById("nric_feedback").innerHTML = "";
        fieldIsValid[1] = true;
    }

    console.log(fieldIsValid);
}


function validatePhoneNo() {
    var phoneNoInput = document.forms["user_registration_form"]["phoneNo"].value;

    if(phoneNoInput.trim() === "") {
        document.getElementById("phone_no_feedback").innerHTML = "";
        fieldIsValid[2] = false;
    } else if (phoneNoInput.length < 8) {
        document.getElementById("phone_no_feedback").innerHTML = "Phone number too short";
        fieldIsValid[2] = false;
    } else if (phoneNoInput.length > 8) {
        document.getElementById("phone_no_feedback").innerHTML = "Phone number too long";
        fieldIsValid[2] = false;
    } else if (!/^\d+$/.test(phoneNoInput)) {
        document.getElementById("phone_no_feedback").innerHTML = "Phone number should be numbers only";
        fieldIsValid[2] = false;
    } else {
        document.getElementById("phone_no_feedback").innerHTML = "";
        fieldIsValid[2] = true;
    }

    console.log(fieldIsValid);
}

function validateUsername() {
    var usernameInput = document.forms["user_registration_form"]["username"].value;

    if(usernameInput.trim() === "") {
        document.getElementById("username_feedback").innerHTML = "";
        fieldIsValid[4] = false;
    } else if(usernameInput.length < 4) {
        document.getElementById("username_feedback").innerHTML = "Username below 4 characters";
        fieldIsValid[4] = false;
    } else {
        var container = document.getElementById("username_feedback");
        checkUsernameExists(usernameInput, container);
    }

    console.log(fieldIsValid);
}

function validatePassword() {
    var userPasswordInput = document.forms["user_registration_form"]["password"].value;

    if(userPasswordInput === "") {
        document.getElementById("password_feedback").innerHTML = "";
        fieldIsValid[5] = false;
    } else if (userPasswordInput.length < 6) {
        document.getElementById("password_feedback").innerHTML = "Password too short";
        fieldIsValid[5] = false;
    } else if (userPasswordInput.length > 6) {
        document.getElementById("password_feedback").innerHTML = "Password too long";
        fieldIsValid[5] = false;
    } else if (!/^\d+$/.test(userPasswordInput)) {
        document.getElementById("password_feedback").innerHTML = "Password should be numbers only";
        fieldIsValid[5] = false;
    } else {
        document.getElementById("password_feedback").innerHTML = "";
        fieldIsValid[5] = true;
    }

    console.log(fieldIsValid);
}

function validateConfirmPassword() {
    var userPasswordInput = document.forms["user_registration_form"]["password"].value;
    var userCfmPasswordInput = document.forms["user_registration_form"]["confirmPassword"].value;

    if(userCfmPasswordInput === "") {
        document.getElementById("confirm_password_feedback").innerHTML = "";
        fieldIsValid[6] = false;
    } else if(userPasswordInput === "") {
        document.getElementById("confirm_password_feedback").innerHTML = "Please enter a password first"
        fieldIsValid[6] = false;
    } else if(userPasswordInput !== userCfmPasswordInput) {
        document.getElementById("confirm_password_feedback").innerHTML = "Passwords do not match";
        fieldIsValid[6] = false;
    } else {
        document.getElementById("confirm_password_feedback").innerHTML = "";
        fieldIsValid[6] = true;
    }

    console.log(fieldIsValid);
}


function checkUsernameExists(usernameInput, container) {

    var xmlhttp;

    if(window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }

    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var response = parseInt(xmlhttp.responseText);
            if(response == 1) {
                container.innerHTML = "Username already exists";
                fieldIsValid[4] = false;
            } else {
                container.innerHTML = "";
                fieldIsValid[4] = true;
            }
        } /*else {
            container.innerHTML = "";
            fieldIsValid[4] = true;
        }*/
    }
    xmlhttp.open("GET", "php/check_username_exists.php?username=" + usernameInput, true);
    xmlhttp.send();
}

function isFormValid() {

    validateForm();

    var formIsValid = true;

    for(i = 0; i < fieldIsValid.length; i++) {
        console.log(fieldIsValid[i]);
        if(!fieldIsValid[i]) {
            formIsValid = false;
        }
    }

    if(!formIsValid) {
        alert("Form is incomplete/contains invalid fields");
        return false;
    } else {
        return true;
    }
}

