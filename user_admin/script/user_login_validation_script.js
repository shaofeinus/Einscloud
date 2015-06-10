/**
 * Created by Shao Fei on 9/6/2015.
 */
var fieldIsValid = [false, false];
var passwordIsCorrect = false;

function validateForm() {
    validateUsername();
    validatePassword();
    console.log(fieldIsValid);
}

function validateUsername() {
    var usernameInput = document.forms["user_login_form"]["username"].value;

    if(usernameInput === "") {
        document.getElementById("username_feedback").innerHTML = "";
        fieldIsValid[0] = false;
    } else if(/[\s]/.test(usernameInput)) {
        document.getElementById("username_feedback").innerHTML = "Username should not contain blank space";
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
    var userPasswordInput = document.forms["user_login_form"]["password"].value;

    if(userPasswordInput === "") {
        document.getElementById("password_feedback").innerHTML = "";
        fieldIsValid[1] = false;
    } else if (userPasswordInput.length < 6) {
        document.getElementById("password_feedback").innerHTML = "Password too short";
        fieldIsValid[1] = false;
    } else if (userPasswordInput.length > 6) {
        document.getElementById("password_feedback").innerHTML = "Password too long";
        fieldIsValid[1] = false;
    } else if (!/^\d+$/.test(userPasswordInput)) {
        document.getElementById("password_feedback").innerHTML = "Password should be numbers only";
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
    } else{
        return true;
    }
}