<?php
/**
 * @date-of-doc: 2015-07-06
 * @project-version: v0.2
 * @called-by: ../forget_login_details.php
 * @calls:
 *  ../js/reset_password.js
 *  php/user_reset_password_script.php
 * @description:
 *  This page provides the interface for the user to reset his password. Forms are provided for the user to
 *  enter the reset key and new password. New passwords are checked on the spot using javascript.
 */
?>
<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet"
          href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
    <script
        src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script
        src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    <script src="../js/reset_password.js?v=1.1"></script>
    <title> User Reset Password </title>
</head>
<body>
    <div class="container">
        <div class="jumbotron well">
            <h1> Reset Login Information</h1>
        </div>
        <div class="page-header">
            <h3>Reset your password</h3>
        </div>
        <table class="table">
            <form id="reset_password" action='php/user_reset_password_script.php' onsubmit="return isPasswordValid()" method="post">
                <tr><th>Reset Key: </th><td><em><input type="text" name="resetKey" placeholder= "Input Reset Key" oninput="" required></em></td></tr>

                <tr><th>New Password: </th><td><em><input type="password" name="newPassword" placeholder= "Input New Password" oninput="validateUserPassword()"required></em></td></tr>
                <div class='alert-info' id='password_feedback'></div>
                <tr><th>Confirm Password: </th><td><em><input type="password" name="confirmPassword" placeholder= "Confirm Password" oninput="validateConfirmPassword()"required></em></td></tr>
                <div class='alert-info' id='confirm_password_feedback'></div>
                <tr><th></th><td><input type='submit' class="btn btn-warning" value='Reset My Password'></td></tr>
        </table>
        </form>

    </div>

</body>
</html>