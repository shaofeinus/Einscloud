/**
 * Created by Shao Fei on 1/7/2015.
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
 * user_registration_validation_script.js
 *
 * @description:
 * Script that contains functions to generate default username and password from User registration form inputs.
 *
 */


function generateDefaultUserName() {

    var defaultOption = document.forms["user_registration_form"]["default_username"].value;
    if (defaultOption === "yes") {
        var username = document.forms["user_registration_form"]["fullName"].value;
        username = username.replace(/\s+/g, '');
        username = username.toLowerCase();
        if(username.length < 4 && username.length > 0) {
            var appendNum = Math.floor(Math.random()*900) + 100;
            var stringNum = String(appendNum);
            username = username.concat(stringNum);
        }

        // Function from validation script
        checkUsernameExists(username, null, "system");
    }
}

function generateDefaultUserName2(username){
    if(username.trim() === ""){
        username = "";
        document.getElementById('username').value = username;
    } else {
        var appendNum = Math.floor(Math.random()*900) + 100;
        var stringNum = String(appendNum);
        username = username.concat(stringNum);

        // Function from validation script
        checkUsernameExists(username, null, "system");
    }
}

function generateDefaultPassword(){
    var defaultOption = document.forms["user_registration_form"]["default_username"].value;
    if (defaultOption === "yes") {
        var phoneNo = document.forms["user_registration_form"]["phoneNo"].value;
        document.getElementById('password').value = phoneNo.substring(0, 6);
        document.getElementById('confirm_password').value = phoneNo.substring(0, 6);
    }
}