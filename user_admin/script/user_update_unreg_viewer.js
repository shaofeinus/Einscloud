/**
 * Modified from user_registration_validation_script.js created by Shao Fei on 8/6/2015.
 */

var fieldIsValid = true;

function validatePhoneNo() {
    var phoneNoInput = document.forms["user_registration_form"]["phoneNo"].value;

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

function validateForm() {
    var firstNameInput = document.forms["user_registration_form"]["firstName"].value;
    var lastNameInput = document.forms["user_registration_form"]["lastName"].value;

    if(firstNameInput.trim() === "" || lastNameInput.trim() === "") {
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