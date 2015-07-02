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
    <script
        src="js/forget_login.js"></script>
    <title>Forget Login Information</title>
</head>

<?php
    if(isset($_GET['forgetLogin'])){
        $userType = $_GET['forgetLogin'];
        if($userType == "Forget User Information"){
            $userType = 'user';
            //echo "hi";
        }
        else if($userType == "Forget Caregiver Information") {
            $userType = 'caregiver';
            //echo "bye";
        }
    }
    else{
        header("Location: index.html");
    }
?>



<body>
<div class="container">
    <div class="jumbotron  well">
        <h1> Reset Login Information</h1>
    </div>

    <div class="container">
        <table>

            <tr><div class="page-header"><h3>Select what you have forgotten</h3></div></tr>
            <tr>
                <td>
                    <select id='loginInfo' name="select_info" onchange='displayLoginInfo()' class='selectpicker'>
                        <option id='-' value='-'>-</option>
                        <option id='username' value='username'>Username</option>
                        <option id = 'password' value='password'>Password</option>
                    </select>
                </td>
            </tr>
            <?php
                if($userType == 'user') {
                    ?>
                    <form name='forget_username_form' action='user_admin/user_forget_username.php' method="post" onsubmit="return isNricValid()">
                        <tr>
                            <td><p>
                                <div id="forgetUsername" style="display:none">
                                    Please key in your NRIC <input type='text' name='forgetLoginNric' oninput="validateNric()">
                                <div class='alert-info' id='nric_feedback'></div></div>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input class="btn btn-primary" type='submit' style="display:none" id='forgetUserButton'
                                       value='Send Username to Phone Number'>
                            </td>
                        </tr>
                    </form>
                <?php
                }
                else if($userType == 'caregiver'){
                    ?>
                    <form name='forget_username_form' action='viewer_admin/viewer_forget_username.php' method="post" onsubmit="return isPhoneValid()">
                        <tr>
                            <td><p>
                                <div id="forgetUsername" style="display:none">
                        Please key in your registered phone number <input type='text' name='forgetLoginPhone' oninput="validatePhoneNo()">
                                <div class='alert-info' id='phone_no_feedback'></div></div>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input class="btn btn-primary" type='submit' style="display:none" id='forgetUserButton'
                                       value='Send Username to Phone Number'>
                            </td>
                        </tr>
                    </form>
            <?php
                }

            //----------------------------------------PASSWORD-------------------------------------------------------

            if($userType == 'user'){
            ?>
            <form name='forget_password_form' action='user_admin/user_forget_password.php' method="post" onsubmit="return isUsernameValid()">
                <tr><td><p>
                        <div id="forgetPassword" style="display:none">
                            Type in your Username <input type='text' name='forgetPassword' oninput="userValidateUsername()">
                        <div class='alert-info' id='password_feedback'></div></div>
                        </p>
                    </td></tr>
                <tr><td>
                        <p><input class="btn btn-primary" type='submit' style="display:none" id='forgetPasswordButton' value='Send Reset Key to Phone Number'>
                        </p></td></tr>
            </form>
            <?php }

            else if($userType == 'caregiver') {
            ?>
                <form name='forget_password_form' action='viewer_admin/viewer_forget_password.php' method="post" onsubmit="return isUsernameValid()">
                    <tr><td><p>
                            <div id="forgetPassword" style="display:none">
                                Type in your Username <input type='text' name='forgetPassword' oninput="caregiverValidateUsername()">
                            <div class='alert-info' id='password_feedback'></div></div>
                            </p>
                        </td></tr>
                    <tr><td>
                            <p><input class="btn btn-primary" type='submit' style="display:none" id='forgetPasswordButton' value='Send Reset Key to Phone Number'>
                            </p></td></tr>
                </form>
            <?php
            }

            if($userType == 'user'){
            ?>
            <tr><td><form action="user_admin/index.php">
                    <input class="btn btn-warning" type='submit' id='forgetPasswordButton' value='Go back to home'>
                    </form></td></tr>
            <?php
            } else if($userType == 'caregiver'){
            ?>
            <tr><td><form action="viewer_admin/index.php">
                        <input class="btn btn-warning" type='submit' id='forgetPasswordButton' value='Go back to home'>
                    </form></td></tr>
            <?php
            }
            ?>
        </table>

    </div>

</body>