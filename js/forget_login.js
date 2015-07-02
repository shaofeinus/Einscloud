var nricIsValid = false;
var phoneIsValid = false;
var usernameIsValid = false;

function displayLoginInfo() {

    if(document.getElementById("loginInfo").value === "username") {
        document.getElementById("forgetUsername").setAttribute("style", "display:inline");
        document.getElementById("forgetUserButton").setAttribute("style", "display:inline");
        document.getElementById("forgetPassword").setAttribute("style", "display:none");
        document.getElementById("forgetPasswordButton").setAttribute("style", "display:none");



    } else if (document.getElementById("loginInfo").value === "password"){
        document.getElementById("forgetPassword").setAttribute("style", "display:inline");
        document.getElementById("forgetPasswordButton").setAttribute("style", "display:inline");
        document.getElementById("forgetUsername").setAttribute("style", "display:none");
        document.getElementById("forgetUserButton").setAttribute("style", "display:none");


    }
    else if (document.getElementById("loginInfo").value === "-"){
        document.getElementById("forgetUsername").setAttribute("style", "display:none");
        document.getElementById("forgetPassword").setAttribute("style", "display:none");
        document.getElementById("forgetUserButton").setAttribute("style", "display:none");
        document.getElementById("forgetPasswordButton").setAttribute("style", "display:none");
    }
}

function validateNric() {
    var userNricInput = document.forms["forget_username_form"]["forgetLoginNric"].value;

    if(userNricInput.trim() === "") {
        document.getElementById("nric_feedback").innerHTML = "";
        nricIsValid = false;
    } else if(!/^(s|t|g|f)[0-9][0-9][0-9][0-9][0-9][0-9][0-9][a-z]$/i.test(userNricInput)){
        document.getElementById("nric_feedback").innerHTML = "NRIC invalid";
        nricIsValid = false;
    }  else {
        var container = document.getElementById("nric_feedback");
        checkNricExists(userNricInput, container);
    }
    console.log(userNricInput);

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
                container.innerHTML = "Nric in database";
                nricIsValid = true;
            } else {
                container.innerHTML = "This NRIC is not in our database";
                nricIsValid = false;
            }
        }
    }
    xmlhttp.open("GET", "user_admin/php/check_nric_exists.php?nric=" + nricInput, true);
    xmlhttp.send();
}

function validatePhoneNo() {
    var phoneNoInput = document.forms["forget_username_form"]["forgetLoginPhone"].value;

    if(phoneNoInput === "") {
        document.getElementById("phone_no_feedback").innerHTML = "";
        phoneIsValid = false;
    } else if(/[\s]/.test(phoneNoInput)) {
        document.getElementById("phone_no_feedback").innerHTML = "Phone number should not contain blank space";
        phoneIsValid = false;
    } else if (!/^[8|9][0-9][0-9][0-9][0-9][0-9][0-9][0-9]$/.test(phoneNoInput)) {
        document.getElementById("phone_no_feedback").innerHTML = "Invalid phone number";
        phoneIsValid = false;
    } else {
        document.getElementById("phone_no_feedback").innerHTML = "";
        phoneIsValid = true;

    }

}

function userValidateUsername() {
    var usernameInput = document.forms["forget_password_form"]["forgetPassword"].value;

    if(usernameInput === "") {
        document.getElementById("password_feedback").innerHTML = "";
        usernameIsValid = false;
    } else if(/[\s]/.test(usernameInput)) {
        document.getElementById("password_feedback").innerHTML = "";
        usernameIsValid = false;
    } else if(usernameInput.length < 4) {
        document.getElementById("password_feedback").innerHTML = "";
        usernameIsValid = false;
    } else {
        var container = document.getElementById("password_feedback");
        userCheckUsernameExists(usernameInput, container);
    }

}

function userCheckUsernameExists(usernameInput, container) {

    var xmlhttp;

    if(window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }

    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var response = parseInt(xmlhttp.responseText);

            if (response == 1) {
                container.innerHTML = "Username exists in database";
                usernameIsValid = true;
            } else {
                container.innerHTML = "Username does not exist in database";
                usernameIsValid = false;
            }

        }
    }
    xmlhttp.open("GET", "user_admin/php/check_username_exists.php?username=" + usernameInput, true);
    xmlhttp.send();
}

function caregiverValidateUsername() {
    var usernameInput = document.forms["forget_password_form"]["forgetPassword"].value;

    if(usernameInput === "") {
        document.getElementById("password_feedback").innerHTML = "";
        usernameIsValid = false;
    } else if(/[\s]/.test(usernameInput)) {
        document.getElementById("password_feedback").innerHTML = "";
        usernameIsValid = false;
    } else if(usernameInput.length < 4) {
        document.getElementById("password_feedback").innerHTML = "";
        usernameIsValid = false;
    } else {
        var container = document.getElementById("password_feedback");
        caregiverCheckUsernameExists(usernameInput, container);
    }

}

function caregiverCheckUsernameExists(usernameInput, container) {

    var xmlhttp;

    if(window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }

    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var response = parseInt(xmlhttp.responseText);

            if (response == 1) {
                container.innerHTML = "Username exists in database";
                usernameIsValid = true;
            } else {
                container.innerHTML = "Username does not exist in database";
                usernameIsValid = false;
            }

        }
    }
    xmlhttp.open("GET", "caregiver_admin/php/check_username_exists.php?username=" + usernameInput, true);
    xmlhttp.send();
}

function isNricValid() {
    if(!nricIsValid) {
        alert("NRIC erroneous");
        return false;
    } else {
        return true;
    }
}

function isPhoneValid() {
    if(!phoneIsValid) {
        alert("Phone number erroneous");
        return false;
    } else {
        return true;
    }
}

function isUsernameValid() {
    if(!usernameIsValid) {
        alert("username erroneous");
        return false;
    } else {
        return true;
    }
}