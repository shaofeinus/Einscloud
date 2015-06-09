/**
 * Created by Shao Fei on 8/6/2015.
 */

var formIsValid = false;

function validateUsername() {
    var usernameInput = document.forms["user_registration_form"]["username"].value;

    if(usernameInput.length < 5) {
        document.getElementById("username_feedback").innerHTML = "Username below 5 characters";
    } else {
        var container = document.getElementById("username_feedback");
        checkUsernameExists(usernameInput, container);
    }
}

function validatePassword() {
    var userPasswordInput = document.forms["user_registration_form"]["password"].value;
    var userCfmPasswordInput = document.forms["user_registration_form"]["confirmPassword"].value;

    if(userPasswordInput === "") {
        document.getElementById("password_feedback").innerHTML = "Please enter a password first"
    } else if(!(userPasswordInput === userCfmPasswordInput)) {
        document.getElementById("password_feedback").innerHTML = "Passwords do not match";
    } else {
        document.getElementById("password_feedback").innerHTML = "";
        formIsValid = true;
    }
}

function validateNric() {
    var userNric = document.forms["user_registration_form"]["nric"].value;

    if(!/^(s|t)[0-9][0-9][0-9][0-9][0-9][0-9][0-9][a-z]$/i.test(userNric)){
        document.getElementById("nric_feedback").innerHTML = "NRIC invalid"
    }  else {
        document.getElementById("nric_feedback").innerHTML = "";
        formIsValid = true;
    }
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
                formIsValid = false;
            } else {
                formIsValid = true;
            }
        } else {
            container.innerHTML = "";
            formIsValid = true;
        }
    }
    xmlhttp.open("GET", "php/check_username_exists.php?username=" + usernameInput, true);
    xmlhttp.send();
}