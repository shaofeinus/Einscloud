/**
 * Created by Shao Fei on 12/6/2015.
 */
/**
 * Created by Shao Fei on 11/6/2015.
 */
var num_emerg;

function verifyLogin() {
    $.post('php/user_add_viewer_functions.php',
        {
            func: 'verifyLogin',
            params: ''
        },
        function(data, status) {
            if(data == true) {
                window.location.replace("logged_out.html");
            }
            console.log(data);
        });
}

function getName() {
    $.post('php/user_add_viewer_functions.php',
        {
            func: 'getName',
            params: ''
        },
        function(data, status) {
            console.log(data);
            document.getElementById('user_real_name').innerHTML = data;
        });
}

function displayDropMenu() {
    $.post('php/user_add_emerg_functions.php',
        {
            func: 'displayDropMenu',
            params: ''
        },
        function(data, status) {
            console.log(data);
            document.getElementById('drop_menu').innerHTML = data;
        });
}

function displayAddEmergForm() {
    var output = "";
    var dropdownMenu = document.getElementById("add_num_emerg");
    num_emerg = parseInt(dropdownMenu.options[dropdownMenu.selectedIndex].value);

    $.post('php/user_add_emerg_functions.php',
        {
            func: 'displayAddEmergForm',
            params: num_emerg
        },
        function(data, status) {
            console.log(data);
            document.getElementById("add_emerg_form").innerHTML = data;
        });
}

function validatePhoneNo(i) {
    var currPhoneNoValid = new Boolean();
    var phoneNoInput = document.forms["add_emerg_form"]["landPhone_" + i].value;

    if(phoneNoInput === "") {
        document.getElementById("phone_no_feedback_" + i).innerHTML = "";
        currPhoneNoValid = false;
    } else if(/[\s]/.test(phoneNoInput)) {
        document.getElementById("phone_no_feedback_" + i).innerHTML = "Phone number should not contain blank space";
        currPhoneNoValid = false;
    } else if (!(/^[6][0-9][0-9][0-9][0-9][0-9][0-9][0-9]$/.test(phoneNoInput) || /^[0-9][0-9][0-9]$/.test(phoneNoInput))){
        document.getElementById("phone_no_feedback_" + i).innerHTML = "Invalid phone number";
        currPhoneNoValid = false;
    } else if(phoneNoInput==="") {
        document.getElementById("phone_no_feedback_" + i).innerHTML = "";
        currPhoneNoValid = true
    } else {
        var container = document.getElementById("phone_no_feedback_" + i);
        checkPhoneExists(phoneNoInput, currPhoneNoValid, container);
    }

    console.log("phone no " + i + " valid " + currPhoneNoValid);
    return currPhoneNoValid;
}

function checkPhoneExists(phoneNoInput, currPhoneNoValid, container) {
    $.post('php/user_add_emerg_functions.php',
        {
            func: "checkPhoneExists",
            params: phoneNoInput
        },
        function(data, status) {
            console.log(data);
            if(data == "true") {
                container.innerHTML = "You already have this number as an Emergency landline contact";
                console.log("number exists");
                //currPhoneNoValid = false;
            } else if(data == "false") {
                container.innerHTML = "";
                console.log("number does not exist");
                //currPhoneNoValid = true;
            } else {
                container.innerHTML = "aaa";
            }
        });
}


function validateForm() {
    var formIsValid = true;
    var i;
    for(i = 0; i < num_emerg; i++) {
        console.log("loop " + i);
        var phoneNoValid = validatePhoneNo(i);
        console.log(i + " " + phoneNoValid + " " + phoneNoValid);
        if(!phoneNoValid){
            formIsValid = false;
            break;
        }
    }
    if(formIsValid === false) {
        alert("Form is incomplete/contains invalid fields");
        return false;
    } else {
        return true;
    }
}

function logout() {
    $.post('php/logout.php', "",
        function(data, status) {
            window.location.replace("php/logout.php");
            console.log(data);
        });
}