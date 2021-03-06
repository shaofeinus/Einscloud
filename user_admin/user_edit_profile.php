<?php

/**
 * @date-of-doc: 2015-07-06
 *
 * @project-version: v0.2
 *
 * @called-by:
 * user_admin_index.php
 *
 * @calls:
 * script/user_edit_profile_script.js
 * php/DB_connect/check_session_validity.php
 * user_admin_index.php
 * php/logout.php
 *
 * @description:
 * Page for users to edit their profiles.
 *
 * */


//start session and check for session validity
require_once 'php/DB_connect/check_session_validity.php';
?>

<!DOCTYPE html>
<html>

<head lang="en">
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" />

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    <script src="script/user_edit_profile_script.js?v=1"></script>

    <title>EinsWatch edit User profile</title>
</head>

<body>

    <script>
        verifyLogin();
        loadProfile();
        showDeleteEmailButton();
    </script>

    <div class="container">
        <div class="jumbotron well">
            <h1>Hello, <var id="user_real_name"></var></h1>
        </div>

        <table class="table">
            <tr>
                <th>Name:</th><td id="name"></td><td></td>
            </tr>
            <tr>
                <th>NRIC:</th><td id="nric"></td><td></td>
            </tr>
            <tr>
                <th>Gender:</th><td id="gender"></td><td></td>
            </tr>
            <tr>
                <th>Current phone number:</th><td id="phone_no"></td><td></td>
            </tr>
            <tr>
                <th>Date of Birth</th><td id="dob"></td>
                <td>
                    <input type="date" id="new_dob" oninput="validateDOB()">
                    <button class="btn btn-primary" onclick="editDOB()">Edit</button>
                    <div class="alert-info" id="dob_feedback"></div>
                </td>
            </tr>
            <tr>
                <th>Username:</th><td id="username"></td>
                <td>

                    <input type="text" id="new_username" placeholder="New username here" oninput="validateUsername()">
                    <button class="btn btn-primary" onclick="editUsername()">Edit</button>
                    <div class="alert-info" id="username_feedback"></div>
                </td>
            </tr>
            <tr>
                <th>Email</th><td id="email"></td>
                <td>
                    <input type="email" id="new_email" placeholder="New email here" oninput="validateEmail()">
                    <button class="btn btn-primary" onclick="editEmail()">Edit</button>
                    <button class="btn btn-primary" id="delete_email_button" onclick="deleteEmail()">Delete</button>
                    <div class="alert-info" id="email_feedback"></div>
                </td>
            </tr>
        </table>

        <div class="page header">
            <h3>Change My Password:</h3>
        </div>

        <table class="table">
            <tr>
                <th>Old password:</th>
                <td>
                    <input type="password" id="old_password" oninput="validateOldPassword()">
                    <div class="alert-info" id="old_password_feedback"></div>
                </td>
            </tr>
            <tr>
                <th>New password:</th>
                <td>
                    <input type="password" id="new_password" oninput="validateNewPassword()">
                    <div class="alert-info" id="new_password_feedback"></div>
                </td>
            </tr>
            <tr>
                <th>Confirm new password:</th>
                <td>
                    <input type="password" id="confirm_password" oninput="validateConfirmPassword()">
                    <div class="alert-info" id="confirm_password_feedback"></div>
                </td>
            </tr>
            <tr>
                <td></td><td><button class="btn btn-warning" onclick="changePassword()">Change password</button></td>
            </tr>
        </table>

        <form>
            <table class="table-condensed">
                    <td>
                        <input type="submit" class="btn btn-primary" formaction="user_admin_index.php" value="Go back to User admin">
                    </td>
                    <td></td>
                </tr>
                    <td>
                        <input type='submit' value='Log out' class="btn btn-danger" formmethod="get" formaction='php/logout.php'/>
                    </td>
                    <td></td>
                </tr>
            </table>
        </form>

    </div>

</body>
</html>