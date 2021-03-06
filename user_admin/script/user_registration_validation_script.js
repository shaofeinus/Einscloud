/**
 * Created by Shao Fei on 8/6/2015.
 */

/**
 * @date-of-doc: 2015-07-06
 *
 * @project-version: v0.2
 *
 * @called-by:
 * ../user_registration.html
 *
 * @calls:
 * ../php/check_username_exists.php
 * ../php/check_nric_exists.php
 *
 * @description:
 * Script that contains functions to validate forms in user registration page.
 *
 */

var fieldIsValid = [true, false, false, false, false, false, false, false];

function validateForm() {
    validateNric();
    validatePhoneNo();
    validateUsername();
    validatePassword();
    validateConfirmPassword();
    validateEmail();
    validateBirthday();
    validateRace()
    console.log(fieldIsValid);
}

function validateNric() {
    var userNricInput = document.forms["user_registration_form"]["nric"].value;

    if(userNricInput.trim() === "") {
        document.getElementById("nric_feedback").innerHTML = "";
        fieldIsValid[1] = false;
    } else if(!/^(s|t|g|f)[0-9][0-9][0-9][0-9][0-9][0-9][0-9][a-z]$/i.test(userNricInput)){
        document.getElementById("nric_feedback").innerHTML = "NRIC invalid";
        fieldIsValid[1] = false;
    }  else {
        var container = document.getElementById("nric_feedback");
        checkNricExists(userNricInput, container);
    }

    console.log(fieldIsValid);
}

function validatePhoneNo() {
    var phoneNoInput = document.forms["user_registration_form"]["phoneNo"].value;

    if(phoneNoInput === "") {
        document.getElementById("phone_no_feedback").innerHTML = "";
        fieldIsValid[2] = false;
    } else if(/[\s]/.test(phoneNoInput)) {
        document.getElementById("phone_no_feedback").innerHTML = "Phone number should not contain blank space";
        fieldIsValid[2] = false;
    } else if (!/^[8|9][0-9][0-9][0-9][0-9][0-9][0-9][0-9]$/.test(phoneNoInput)) {
        document.getElementById("phone_no_feedback").innerHTML = "Invalid phone number";
        fieldIsValid[2] = false;
    } else {
        document.getElementById("phone_no_feedback").innerHTML = "";
        fieldIsValid[2] = true;
        generateDefaultPassword();
    }

    console.log(fieldIsValid);
}

function validateRace() {
    var otherRaceInput = document.forms["user_registration_form"]["otherRace"].value;
    var presetRaceInput = document.getElementById("race").value;

    if(presetRaceInput !== "otherRaces") {
        fieldIsValid[7] = true;
    } else if(otherRaceInput === "") {
        document.getElementById("race_feedback").innerHTML = "";
        fieldIsValid[7] = false;
    } else if(/\d/.test(otherRaceInput)) {
        document.getElementById("race_feedback").innerHTML = "Race cannot contain numbers";
        fieldIsValid[7] = false;
    } else {
        document.getElementById("race_feedback").innerHTML = "";
        fieldIsValid[7] = true;
    }
    console.log(fieldIsValid);
}

function validateUsername() {
    var usernameInput = document.forms["user_registration_form"]["username"].value;

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
        checkUsernameExists(usernameInput, container, "user");
    }

    console.log(fieldIsValid);
}

function validateEmail() {
    var emailInput = document.forms["user_registration_form"]["email"].value;

    if(/[\s]/.test(emailInput)) {
        document.getElementById("email_feedback").innerHTML = "Email should not contain blank space";
        fieldIsValid[6] = false;
    } else {
        document.getElementById("email_feedback").innerHTML = "";
        fieldIsValid[6]  = true;
    }

    console.log(fieldIsValid);
}

function validatePassword() {
    var userPasswordInput = document.forms["user_registration_form"]["password"].value;

    if(userPasswordInput === "") {
        document.getElementById("password_feedback").innerHTML = "";
        fieldIsValid[4] = false;
    } else if (userPasswordInput.length < 6) {
        document.getElementById("password_feedback").innerHTML = "Password too short";
        fieldIsValid[4] = false;
    } else if (userPasswordInput.length > 6) {
        document.getElementById("password_feedback").innerHTML = "Password too long";
        fieldIsValid[4] = false;
    } else if (!/^\d+$/.test(userPasswordInput)) {
        document.getElementById("password_feedback").innerHTML = "Password should be numbers only";
        fieldIsValid[4] = false;
    } else {
        document.getElementById("password_feedback").innerHTML = "";
        validateConfirmPassword();
        fieldIsValid[4] = true;
    }

    console.log(fieldIsValid);
}

function validateConfirmPassword() {
    var userPasswordInput = document.forms["user_registration_form"]["password"].value;
    var userCfmPasswordInput = document.forms["user_registration_form"]["confirmPassword"].value;

    if(userCfmPasswordInput === "") {
        document.getElementById("confirm_password_feedback").innerHTML = "";
        fieldIsValid[5] = false;
    } else if(userPasswordInput === "") {
        document.getElementById("confirm_password_feedback").innerHTML = "Please enter a password first"
        fieldIsValid[5] = false;
    } else if(userPasswordInput !== userCfmPasswordInput) {
        document.getElementById("confirm_password_feedback").innerHTML = "Passwords do not match";
        fieldIsValid[5] = false;
    } else {
        document.getElementById("confirm_password_feedback").innerHTML = "";
        fieldIsValid[5] = true;
    }

    console.log(fieldIsValid);
}

function validateBirthday() {
    var userBirthday = document.forms["user_registration_form"]["birthday"].value;

    //generate today;s date
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth()+1; //January is 0!
    var yyyy = today.getFullYear();

    if(dd<10) {
        dd='0'+dd
    }

    if(mm<10) {
        mm='0'+mm
    }
    today = yyyy+'-'+mm+'-'+dd;


    if(userBirthday === "") {
        document.getElementById("birthday_feedback").innerHTML = "";
        fieldIsValid[7] = false;
    } else if(userBirthday > today) {
        document.getElementById("birthday_feedback").innerHTML = "Invalid Date of Birth"
        fieldIsValid[7] = false;
    } else {
        document.getElementById("birthday_feedback").innerHTML = "";
        fieldIsValid[7] = true;
    }

    console.log(fieldIsValid);
}


function checkUsernameExists(usernameInput, container, caller) {

    var xmlhttp;

    if(window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }

    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var response = parseInt(xmlhttp.responseText);

            // For form validation
            if (caller === "user") {
                if (response == 1) {
                    container.innerHTML = "Username already exists";
                    fieldIsValid[3] = false;
                } else {
                    container.innerHTML = "";
                    fieldIsValid[3] = true;
                }
            }

            // For default account generation
            else if (caller === "system") {
                if(response === 1) {
                    fieldIsValid[3] = false;
                    generateDefaultUserName2(usernameInput);
                } else {
                    fieldIsValid[3] = true;
                    document.getElementById('username').value = usernameInput;
                }
            }
        } /*else {
            container.innerHTML = "";
            fieldIsValid[4] = true;
        }*/
    }
    xmlhttp.open("GET", "php/check_username_exists.php?username=" + usernameInput, true);
    xmlhttp.send();
}

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
        }
    }
    xmlhttp.open("GET", "php/check_nric_exists.php?nric=" + nricInput, true);
    xmlhttp.send();
}

function isFormValid() {

    validateForm();

    var formIsValid = true;

    for(i = 0; i < fieldIsValid.length; i++) {
        console.log(fieldIsValid[i]);
        if(!fieldIsValid[i]) {
            formIsValid = false;
            break;
        }
    }

    if(!formIsValid) {
        alert("Form is incomplete/contains invalid fields");
        return false;
    } else {
        // Function from process ic image script
        makeIcImgPerm();
        return true;
    }
}


