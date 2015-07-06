/**
 * @date-of-doc: 2015-07-06
 * @project-version: v0.2
 * @called-by:
 *  ../caregiver_profile.php
 * @calls: php/check_username_exists.php
 * @description:
 *  This file takes in data from caregiver_profile.php and provides client-side validation to the data
 *  The file ensures that the fields give correct data before returning 'true' to the calling html form.
 */

var fieldIsValid = [false, false,false, false, false, false];



function validatePhoneNo() {
    var phoneNoInput = document.forms["viewer_edit_phone"]["phoneNo"].value;

    if(phoneNoInput.trim() === "") {
        document.getElementById("phone_no_feedback").innerHTML = "";
        fieldIsValid[2] = false;
    } else if (!/^[8|9][0-9][0-9][0-9][0-9][0-9][0-9][0-9]$/.test(phoneNoInput)) {
        document.getElementById("phone_no_feedback").innerHTML = "Invalid phone number";
        fieldIsValid[2] = false;
    } else {
        document.getElementById("phone_no_feedback").innerHTML = "";
        fieldIsValid[2] = true;
    }

    console.log(fieldIsValid);
}

function validateUsername() {
    var usernameInput = document.forms["viewer_edit_username"]["username"].value;

    if(usernameInput === "") {
        document.getElementById("username_feedback").innerHTML = "";
        fieldIsValid[3] = false;
    } else if(/[\s]/.test(usernameInput)) {
        document.getElementById("username_feedback").innerHTML = "Username should not contain blank space";
        fieldIsValid[3] = false;
    } else if(usernameInput.length < 4) {
        document.getElementById("username_feedback").innerHTML = "Username below 4 characters";
        fieldIsValid[3] = false;
    } else {
        var container = document.getElementById("username_feedback");
        checkUsernameExists(usernameInput, container);
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
                fieldIsValid[3] = false;
            } else {
                container.innerHTML = "";
                fieldIsValid[3] = true;
            }
        } /*else {
         container.innerHTML = "";
         fieldIsValid[4] = true;
         }*/
    }

    xmlhttp.open("GET", "php/check_username_exists.php?username=" + usernameInput, true);
    xmlhttp.send();
}

//check database
function validateOldPassword() {
    var viewerPasswordInput = document.forms["viewer_edit_password"]["oldPassword"].value;

    if(viewerPasswordInput === "") {
        document.getElementById("old_password_feedback").innerHTML = "";
        fieldIsValid[6] = false;
    } else if (viewerPasswordInput.length < 8) {
       // document.getElementById("old_password_feedback").innerHTML = "Password too short";
        fieldIsValid[6] = false;
    } else {
        document.getElementById("old_password_feedback").innerHTML = "";
        fieldIsValid[6] = true;
    }

    console.log(fieldIsValid);
}

function validatePassword() {
    var viewerPasswordInput = document.forms["viewer_edit_password"]["newPassword"].value;

    if(viewerPasswordInput === "") {
        document.getElementById("password_feedback").innerHTML = "";
        fieldIsValid[4] = false;
    } else if (viewerPasswordInput.length < 8) {
        document.getElementById("password_feedback").innerHTML = "Password too short";
        fieldIsValid[4] = false;
    } else {
        document.getElementById("password_feedback").innerHTML = "";
        fieldIsValid[4] = true;
    }

    console.log(fieldIsValid);
}

function validateConfirmPassword() {
    var viewerPasswordInput = document.forms["viewer_edit_password"]["newPassword"].value;
    var userCfmPasswordInput = document.forms["viewer_edit_password"]["confirmPassword"].value;

    if(userCfmPasswordInput === "") {
        document.getElementById("confirm_password_feedback").innerHTML = "";
        fieldIsValid[5] = false;
    } else if(viewerPasswordInput === "") {
        document.getElementById("confirm_password_feedback").innerHTML = "Please enter a password first"
        fieldIsValid[5] = false;
    } else if(viewerPasswordInput !== userCfmPasswordInput) {
        document.getElementById("confirm_password_feedback").innerHTML = "Passwords do not match";
        fieldIsValid[5] = false;
    } else {
        document.getElementById("confirm_password_feedback").innerHTML = "";
        fieldIsValid[5] = true;
    }

    console.log(fieldIsValid);
}
function isPasswordValid(oldPWIndex, newPWIndex, confirmPWIndex) {

    var formIsValid = true;

    if(!fieldIsValid[oldPWIndex] || !fieldIsValid[newPWIndex] || !fieldIsValid[confirmPWIndex])
        formIsValid = false;

    if(!formIsValid) {
        alert("Form is incomplete/contains invalid fields");
        return false;
    } else {
        return true;
    }
}

function isFormValid(theIndex) {

    var formIsValid = true;

    if(!fieldIsValid[theIndex])
        formIsValid = false;

    if(!formIsValid) {
        alert("Form is incomplete/contains invalid fields");
        return false;
    } else {
        return true;
    }
}

