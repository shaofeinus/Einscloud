/**
 * Created by Shao Fei on 22/6/2015.
 */

var formValid = {username:false, dob:false, email:false, oldPassword:false, newPassword:false, confirmPassword:false, oldPasswordCorrect:false};

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

function loadProfile() {
    $.post("php/user_edit_profile_functions.php",
        {
            func: "loadProfile",
            params: ""

        },
        function(data, status) {
            console.log(data);
            data = JSON.parse(data);
            var name = data.name;
            document.getElementById('user_real_name').innerHTML = name;
            document.getElementById('name').innerHTML = name;
            var nric = data.nric;
            document.getElementById('nric').innerHTML = nric;
            var gender = data.gender;
            if(gender==="m" || gender==="M") {
                gender = "Male";
            } else if(gender==="f" || gender==="F") {
                gender = "Female";
            } else {
                gender = "";
            }
            document.getElementById('gender').innerHTML = gender;
            var phone_no = data.phone_no;
            document.getElementById('phone_no').innerHTML = phone_no;
            var dob = data.dob;
            document.getElementById('dob').innerHTML = dob;
            var username = data.username;
            document.getElementById('username').innerHTML = username;
            var email = data.email;
            document.getElementById('email').innerHTML = email;
        });
}

function showDeleteEmailButton() {
    $.post("php/user_edit_profile_functions.php",
        {
            func: "hasEmail",
            params: ""

        },
        function(data, status) {
            console.log(data);
            var hasEmail = JSON.parse(data);
            if(!hasEmail) {
                document.getElementById('delete_email_button').style.display = 'none';
            }
        });
}

function validateDOB() {
    var dobInput = document.getElementById('new_dob').value;
    var dobDateObject = new Date(dobInput);
    var todayDateObject = new Date();
    if (dobInput === "") {
        document.getElementById('dob_feedback').innerHTML = "";
        formValid.dob = false;
    } else if(dobDateObject >= todayDateObject) {
        document.getElementById('dob_feedback').innerHTML = "Invalid birthday";
        formValid.dob = false;
    } else {
        document.getElementById('dob_feedback').innerHTML = "";
        formValid.dob = true;
    }
}

function editDOB() {

    var dobInput = document.getElementById('new_dob').value;

    validateDOB();

    if(formValid.dob) {
        $.post("php/user_edit_profile_functions.php",
            {
                func: "editDOB",
                params: dobInput

            },
            function(data, status) {
                console.log(data);
                var success = JSON.parse(data);
                if(success) {
                    alert("Date of birth changed");
                    location.reload();
                } else {
                    alert("Error occurred when changing date of birth")
                }
            });
    } else {
        alert("Invalid date of birth entered");
    }
}

function editUsername() {
    var username = document.getElementById('new_username').value;

    validateUsername();

    if(formValid.username) {
        $.post("php/user_edit_profile_functions.php",
            {
                func: "editUsername",
                params: username

            },
            function(data, status) {
                console.log(data);
                var success = JSON.parse(data);
                if(success) {
                    alert("Username changed");
                    location.reload();
                } else {
                    alert("Error occurred when changing username")
                }
            });
    } else {
        alert("Invalid username entered");
    }
}

function editEmail() {
    var email = document.getElementById('new_email').value;

    validateEmail();
    validateNewPassword();
    validateConfirmPassword();

    if(formValid.oldPassword && formValid.newPassword && formValid.confirmPassword) {
        if(isOldPasswordCorrect()) {

        }
    }

    if(formValid.email) {
        $.post("php/user_edit_profile_functions.php",
            {
                func: "editEmail",
                params: email

            },
            function(data, status) {
                console.log(data);
                var success = JSON.parse(data);
                if(success) {
                    alert("Email changed");
                    location.reload();
                } else {
                    alert("Error occurred when changing email")
                }
            });
    } else {
        alert("Invalid email entered");
    }
}

function deleteEmail() {

    $.post("php/user_edit_profile_functions.php",
        {
            func: "deleteEmail",
            params: ""

        },
        function(data, status) {
            console.log(data);
            var success = JSON.parse(data);
            if(success) {
                alert("Email deleted");
                location.reload();
            } else {
                alert("Error occurred when deleting email")
            }
        });
}

function changePassword() {
    validateOldPassword();
    validateNewPassword();
    validateConfirmPassword();

    if(formValid.oldPassword && formValid.newPassword && formValid.confirmPassword) {
        if(formValid.oldPasswordCorrect) {
            var newPassword = document.getElementById("new_password").value;
            $.post("php/user_edit_profile_functions.php",
                {
                    func: "changePassword",
                    params: newPassword

                },
                function(data, status) {
                    console.log(data);
                    var success = JSON.parse(data);
                    if(success) {
                        alert("Password changed");
                        location.reload();
                    } else {
                        alert("Error occurred when changing password")
                    }
                });
        } else {
            alert("Old password is wrong!");
            document.getElementById("old_password").value = "";
            document.getElementById("new_password").value = "";
            document.getElementById("confirm_password").value = "";
        }
    } else {
        alert("Invalid password(s) entered");
        document.getElementById("old_password").value = "";
        document.getElementById("new_password").value = "";
        document.getElementById("confirm_password").value = "";
    }

}

function validateUsername() {
    var username = document.getElementById('new_username').value;
    if(username === "") {
        document.getElementById("username_feedback").innerHTML = "";
        formValid.username=false;
    } else if(/[\s]/.test(username)) {
        document.getElementById("username_feedback").innerHTML = "Username should not contain blank space";
        formValid.username=false;
    } else if(username.length < 4) {
        document.getElementById("username_feedback").innerHTML = "Username below 4 characters";
        formValid.username=false;
    }  else {
        checkUsernameExists(username);
    }
}

function checkUsernameExists(username) {
    $.get("php/check_username_exists.php",
        {
            username: username
        },
        function(data) {
            console.log(data);
            if(data === '1') {
                document.getElementById("username_feedback").innerHTML = "Username already exists";
                formValid.username = false;
            } else {
                document.getElementById("username_feedback").innerHTML = "";
                formValid.username = true;
            }
        });
}

function validateEmail() {
    var emailField = document.getElementById("new_email");
    if(!emailField.checkValidity()) {
        document.getElementById("email_feedback").innerHTML = "Invalid email";
        formValid.email = false;
    } else {
        document.getElementById("email_feedback").innerHTML = "";
        formValid.email = true;
    }
}

function validateOldPassword() {
    var oldPassword = document.getElementById("old_password").value;

    if(oldPassword === "") {
        document.getElementById("old_password_feedback").innerHTML = "";
        formValid.oldPassword = false;
    } else if(!/^[0-9][0-9][0-9][0-9][0-9][0-9]$/.test(oldPassword)) {
        document.getElementById("old_password_feedback").innerHTML = "Password not a 6 digit number";
        formValid.oldPassword = false;
    } else {
        document.getElementById("old_password_feedback").innerHTML = "";
        formValid.oldPassword = true;
        checkOldPassword(oldPassword);
    }
}

function checkOldPassword(oldPassword) {
    $.post("php/user_edit_profile_functions.php",
        {
            func: "checkOldPassword",
            params: oldPassword

        },
        function(data, status) {
            console.log(data);
            var isValid = JSON.parse(data);
            if(isValid) {
                formValid.oldPasswordCorrect = true;
            } else {
                formValid.oldPasswordCorrect = false;
            }
        });
}

function validateNewPassword() {
    var newPassword = document.getElementById("new_password").value;

    if(newPassword === "") {
        document.getElementById("new_password_feedback").innerHTML = "";
        formValid.newPassword = false;
    } else if(!/^[0-9][0-9][0-9][0-9][0-9][0-9]$/.test(newPassword)) {
        document.getElementById("new_password_feedback").innerHTML = "Password not a 6 digit number";
        formValid.newPassword = false;
    } else {
        document.getElementById("new_password_feedback").innerHTML = "";
        formValid.newPassword = true;
        validateConfirmPassword();
    }
}

function validateConfirmPassword() {
    var newPassword = document.getElementById("new_password").value;
    var confirmPassword = document.getElementById("confirm_password").value;

    if(confirmPassword === "") {
        document.getElementById("confirm_password_feedback").innerHTML = "";
        formValid.confirmPassword = false;
    } else if(!formValid.newPassword) {
        document.getElementById("confirm_password_feedback").innerHTML = "Enter a valid new password first";
        formValid.confirmPassword = false;
    } else if(!/^[0-9][0-9][0-9][0-9][0-9][0-9]$/.test(confirmPassword)) {
        document.getElementById("confirm_password_feedback").innerHTML = "Password not a 6 digit number";
        formValid.confirmPassword = false;
    } else {
        document.getElementById("confirm_password_feedback").innerHTML = "";
        formValid.confirmPassword = true;
    }
}




