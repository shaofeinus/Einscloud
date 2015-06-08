/**
 * Created by Shao Fei on 8/6/2015.
 */

var formIsValid = false;

function validateUsername() {
    var usernameInput = document.forms["user_registration_form"]["username"].value;

    if(usernameInput.length < 5) {
        document.getElementById("username_feedback").innerHTML = "Username below 5 characters";
    } else if(isUsernameExists(usernameInput)==true) {
        document.getElementById("username_feedback").innerHTML = "Username already exists";
    }
    else {
        document.getElementById("username_feedback").innerHTML = "";
        formIsValid = true;

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


function isUsernameExists(usernameInput) {
    var xmlhttp = new XMLHttpRequest();

    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var response = parseInt(xmlhttp.responseText);
            if(response == true) {
                return true;
            } else {
                return false
            }
        }
    }
    xmlhttp.open("GET", "../check_username_exists.php?username=" + usernameInput, true);
    xmlhttp.send();
}