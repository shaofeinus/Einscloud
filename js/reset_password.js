//check database
var passwordIsValid = false;
var confirmPasswordIsValid = false;

function validateUserPassword() {
    var passwordInput = document.forms["reset_password"]["newPassword"].value;

    if(passwordInput === "") {
        document.getElementById("password_feedback").innerHTML = "";
        passwordIsValid = false;
    } else if (passwordInput.length < 6) {
        document.getElementById("password_feedback").innerHTML = "Password too short";
        passwordIsValid = false;
    } else if (passwordInput.length > 6) {
        document.getElementById("password_feedback").innerHTML = "Password too long";
        passwordIsValid = false;
    } else if (!/^\d+$/.test(passwordInput)) {
        document.getElementById("password_feedback").innerHTML = "Password should be numbers only";
        passwordIsValid = false;
    } else {
        document.getElementById("password_feedback").innerHTML = "";
        passwordIsValid = true;
    }

}

function validateCaregiverPassword() {
    var passwordInput = document.forms["reset_password"]["newPassword"].value;

    if(passwordInput === "") {
        document.getElementById("password_feedback").innerHTML = "";
        passwordIsValid = false;
    } else if (passwordInput.length < 8) {
        document.getElementById("password_feedback").innerHTML = "Password too short";
        passwordIsValid = false;
    } else {
        document.getElementById("password_feedback").innerHTML = "";
        passwordIsValid = true;
    }

}


function validateConfirmPassword() {
    var passwordInput = document.forms["reset_password"]["newPassword"].value;
    var userCfmPasswordInput = document.forms["reset_password"]["confirmPassword"].value;

    if(userCfmPasswordInput === "") {
        document.getElementById("confirm_password_feedback").innerHTML = "";
        confirmPasswordIsValid = false;
    } else if(passwordInput === "") {
        document.getElementById("confirm_password_feedback").innerHTML = "Please enter a password first"
        confirmPasswordIsValid = false;
    } else if(passwordInput !== userCfmPasswordInput) {
        document.getElementById("confirm_password_feedback").innerHTML = "Passwords do not match";
        confirmPasswordIsValid = false;
    } else {
        document.getElementById("confirm_password_feedback").innerHTML = "";
        confirmPasswordIsValid = true;
    }

    //console.log(fieldIsValid);

}
function isPasswordValid() {

    var resetIsValid = true;

    if(!passwordIsValid || !confirmPasswordIsValid)
        resetIsValid = false;

    if(!resetIsValid) {
        alert("Password input are incorrect");
        return false;
    } else {
        return true;
    }
}