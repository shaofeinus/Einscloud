/**
 * @date-of-doc: 2015-07-06
 * @project-version: v0.2
 * @called-by:
 *  ../caregiver_registration.html
 * @calls:
 *  php/check_username_exists.php
 *  php/check_nric_exists.php
 * @description:
 *  This file takes in data from caregiver_registration.html and provides client-side validation to the data
 *  The file ensures that the fields give correct data before returning 'true' to the calling html form.
 */

var fieldIsValid = [false, false,false, false, false];
var finalUserName;
var uniqueCondition;

function validateForm() {
    var fullNameInput = document.forms["viewer_registration_form"]["fullName"].value;


    if(fullNameInput.trim() === "") {
        fieldIsValid[0] = false;
    } else {
        fieldIsValid[0] = true;
    }

    //validateNric();
    validatePhoneNo();
    validateUsername();
    validatePassword();
    validateConfirmPassword();
    console.log(fieldIsValid);
}

function validatePhoneNo() {
    var phoneNoInput = document.forms["viewer_registration_form"]["phoneNo"].value;
    var useDefaultRadio = document.forms["viewer_registration_form"]["default_username"].value;

    if (phoneNoInput.trim() === "") {
        document.getElementById("phone_no_feedback").innerHTML = "";
        fieldIsValid[1] = false;
    } else if (!/^[8|9][0-9][0-9][0-9][0-9][0-9][0-9][0-9]$/.test(phoneNoInput)) {
        document.getElementById("phone_no_feedback").innerHTML = "Invalid phone number";
        fieldIsValid[1] = false;
    } else {
        document.getElementById("phone_no_feedback").innerHTML = "";
        fieldIsValid[1] = true;
    }
    if (useDefaultRadio == 'yes') {
        generateDefaultPassword();
    }
    console.log(fieldIsValid);
}

function validateUsername() {
    var usernameInput = document.forms["viewer_registration_form"]["username"].value;

    if(usernameInput === "") {
        document.getElementById("username_feedback").innerHTML = "";
        fieldIsValid[2] = false;
    } else if(/[\s]/.test(usernameInput)) {
        document.getElementById("username_feedback").innerHTML = "Username should not contain blank space";
        fieldIsValid[2] = false;
    } else if(usernameInput.length < 4) {
        document.getElementById("username_feedback").innerHTML = "Username below 4 characters";
        fieldIsValid[2] = false;
    } else {
        document.getElementById("username_feedback").innerHTML = "";
        var container = document.getElementById("username_feedback");
        checkUsernameExists(usernameInput, container);
    }

    console.log(fieldIsValid);
}

function validatePassword() {
    var viewerPasswordInput = document.forms["viewer_registration_form"]["password"].value;

    if(viewerPasswordInput === "") {
        document.getElementById("password_feedback").innerHTML = "";
        fieldIsValid[3] = false;
    } else if (viewerPasswordInput.length < 8) {
        document.getElementById("password_feedback").innerHTML = "Password too short";
        fieldIsValid[3] = false;
    } else {
        document.getElementById("password_feedback").innerHTML = "";
        fieldIsValid[3] = true;
    }

    console.log(fieldIsValid);
}

function validateConfirmPassword() {
    var viewerPasswordInput = document.forms["viewer_registration_form"]["password"].value;
    var userCfmPasswordInput = document.forms["viewer_registration_form"]["confirmPassword"].value;

    if(userCfmPasswordInput === "") {
        document.getElementById("confirm_password_feedback").innerHTML = "";
        fieldIsValid[4] = false;
    } else if(viewerPasswordInput === "") {
        document.getElementById("confirm_password_feedback").innerHTML = "Please enter a password first"
        fieldIsValid[4] = false;
    } else if(viewerPasswordInput !== userCfmPasswordInput) {
        document.getElementById("confirm_password_feedback").innerHTML = "Passwords do not match";
        fieldIsValid[4] = false;
    } else {
        document.getElementById("confirm_password_feedback").innerHTML = "";
        fieldIsValid[4] = true;
    }

    console.log(fieldIsValid);
}


function checkUsernameExists(usernameInput, container) {

    var xmlhttp;
    var useDefaultRadio = document.forms["viewer_registration_form"]["default_username"].value;

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
                fieldIsValid[2] = false;
                uniqueCondition = false;

            } else {
                //container.innerHTML = "";
                fieldIsValid[2] = true;
                uniqueCondition = true;
            }
            if(useDefaultRadio == 'yes') {
                generateDefaultUserName2(usernameInput, container);
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

    if(!formIsValid || !uniqueCondition) {
        alert("Form is incomplete/contains invalid fields");
        return false;
    } else {
        return true;
    }
}


function defaultUserInfo() {


    document.getElementById('username').readOnly = true;
    document.getElementById('password').readOnly = true;
    document.getElementById('confirm_password').readOnly = true;
    document.getElementById('username').style.backgroundColor = '#dddddd';
    document.getElementById('password').style.backgroundColor = '#dddddd';
    document.getElementById('confirm_password').style.backgroundColor = '#dddddd';
    document.getElementById('username').value = '';
    document.getElementById('password').value = '';
    document.getElementById('confirm_password').value = '';

    document.getElementById('username_feedback').innerHTML = "Auto generated";
    document.getElementById('password_feedback').innerHTML = "Auto generated - Your phone number";


    generateDefaultUserName();
    generateDefaultPassword();

}

function customUserInfo() {

    document.getElementById('username').readOnly = false;
    document.getElementById('password').readOnly = false;
    document.getElementById('confirm_password').readOnly = false;
    document.getElementById('username').style.backgroundColor = '#ffffff';
    document.getElementById('password').style.backgroundColor = '#ffffff';
    document.getElementById('confirm_password').style.backgroundColor = '#ffffff';
    document.getElementById('username').value = '';
    document.getElementById('password').value = '';
    document.getElementById('confirm_password').value = '';

    document.getElementById('username_feedback').innerHTML = "";
    document.getElementById('password_feedback').innerHTML = "";
}

function generateDefaultUserName(){

    var useDefaultRadio = document.forms["viewer_registration_form"]["default_username"].value;
    if(useDefaultRadio == 'yes') {
        var username = document.forms["viewer_registration_form"]["fullName"].value;
        document.getElementById('username_feedback').value = '';
        var container = document.getElementById("username_feedback");

        username = username.replace(/\s+/g, '');
        username = username.toLowerCase();
        checkUsernameExists(username, container);
    }

}

function generateDefaultUserName2(username, container){
    if(username.trim() === ""){
        document.getElementById('username').value = "";
    }
    else if(uniqueCondition == false || username.length < 4){
        var appendNum = Math.floor(Math.random()*900) + 100;
        var stringNum = String(appendNum)
        username = username.concat(stringNum);
        //document.getElementById('username').value = username;
        //console.log("this is concat name: ");
        //console.log(username);
        checkUsernameExists(username, container);
    }

    if(uniqueCondition == true){
        finalUserName = username;
        //console.log("unique condition is true now final username is: ");
        //console.log(finalUserName);
        document.getElementById('username').value = finalUserName;
    }
}

function generateDefaultPassword(){

    var phoneNo = document.forms["viewer_registration_form"]["phoneNo"].value;
    document.getElementById('password').value = phoneNo;
    document.getElementById('confirm_password').value = phoneNo;
    //validatePassword();
    //validateConfirmPassword();
    console.log(phoneNo);
}









//----------------------------------UNUSED FUNCTIONS--------------------------------------------------
function validateNric() {
    var viewerNricInput = document.forms["viewer_registration_form"]["nric"].value;

    if(viewerNricInput.trim() === "") {
        document.getElementById("nric_feedback").innerHTML = "blank";
        fieldIsValid[1] = false;
    } else if(!/^(s|t|g|f)[0-9][0-9][0-9][0-9][0-9][0-9][0-9][a-z]$/i.test(viewerNricInput)){
        document.getElementById("nric_feedback").innerHTML = "NRIC invalid";
        fieldIsValid[1] = false;
    }  else {
        var container = document.getElementById("nric_feedback");
        checkNricExists(viewerNricInput, container);
    }

    console.log(fieldIsValid);
}
// uncalled
function checkNricExists(nricInput, container) {

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
                container.innerHTML = "NRIC already exists. Please use current account.";
                fieldIsValid[1] = false;
            } else {
                container.innerHTML = "";
                fieldIsValid[1] = true;
            }
        } /*else {
         container.innerHTML = "";
         fieldIsValid[4] = true;
         }*/
    }
    xmlhttp.open("GET", "php/check_nric_exists.php?nric=" + nricInput, true);
    xmlhttp.send();
}