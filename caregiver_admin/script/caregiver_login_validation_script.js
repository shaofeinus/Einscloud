/**
 * @date-of-doc: 2015-07-06
 * @project-version: v0.2
 * @called-by:
 *  ../index.php
 * @calls: php/check_username_exists.php
 * @description:
 *  This file takes in data from index.php and provides client-side validation to the data
 *  The file ensures that the fields give correct data before returning 'true' to the calling html form.
 */
var fieldIsValid = [false, false];

function validateForm() {
    validateUsername();
    validatePassword();
    console.log(fieldIsValid);
}

function validateUsername() {
    var usernameInput = document.forms["viewer_login_form"]["username"].value.trim();

    if(usernameInput.trim() === "") {
        document.getElementById("username_feedback").innerHTML = "";
        fieldIsValid[0] = false;
    } else if(usernameInput.length < 4) {
        document.getElementById("username_feedback").innerHTML = "Username below 4 characters";
        fieldIsValid[0] = false;
    } else {
        var container = document.getElementById("username_feedback");
        checkUsernameExists(usernameInput, container);
    }

    console.log(fieldIsValid);
}

function validatePassword() {
    var userPasswordInput = document.forms["viewer_login_form"]["password"].value;

    if(userPasswordInput === "") {
        document.getElementById("password_feedback").innerHTML = "";
        fieldIsValid[1] = false;
    } else if (userPasswordInput.length < 8) {
        document.getElementById("password_feedback").innerHTML = "Password too short";
        fieldIsValid[1] = false;
    } else {
        document.getElementById("password_feedback").innerHTML = "";
        fieldIsValid[1] = true;
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
                container.innerHTML = "We recognise you!";
                fieldIsValid[0] = true;
            } else {
                container.innerHTML = "We don't seem to know you";
                fieldIsValid[0] = false;
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
        alert("Log in information is incomplete/contain invalid fields");
        return false;
    } else {
        return true;
    }
}