<?php
/**
 * @date-of-doc: 2015-07-06
 * @project-version: v0.2
 * @called-by: caregiver_admin_index.php
 * @calls:
 *  php/DB_connect/check_session_validity.php
 *  php/DB_connect/db_utility.php
 *  php/caregiver_edit_username.php
 *  php/caregiver_edit_phone.php
 *  php/caregiver_delete_email.php
 *  php/caregiver_edit_email.php
 *  php/caregiver_edit_password.php
 *  script/caregiver_edit.js
 * @description:
 *  This file retrieves the caregiver's information from the database and displays it to the caregiver
 *  This file allows the caregiver to edit some of his personal information
 */
//start session and check for session validity
require_once 'php/DB_connect/check_session_validity.php';
?>
<html>
<head lang="en">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Profile</title>
</head>

<body>
<div class="container">
    <div class="jumbotron well">
        <h1><?php echo $_SESSION['login_viewer'] . "'s Profile"; ?></h1>
    </div>
</div>
<script src="script/caregiver_edit.js?v=1.1"></script>
<div class="container">
<?php
    $viewer_id = $_SESSION['viewer_id'];
    require_once 'php/DB_connect/db_utility.php';

    $link = get_conn();
    $profileStmt = mysqli_prepare($link, "select * from RegisteredViewer where id = ?");
    $profileStmt->bind_param("i", $viewer_id);
    $profileStmt->execute();
    $profileStmt->store_result();
    $profileStmt->bind_result($row['id'], $row['username'], $row['password'], $row['fullname'], $row['email'], $row['phone_no'], $row['rv_type'], $row['date_registered'], $row['session_id']);
    $link->close();

    //$viewerProfileResponse = make_query("select * from RegisteredViewer where id = '$viewer_id'");
    //if($viewerProfileResponse === FALSE) {
    //    echo "response is erroneous";
    //    die(mysql_error());
    //}
    if($profileStmt->num_rows > 0) {
        while($profileStmt->fetch()) {
            echo '<table class="table">
            <tr>
                <th>Name: </th>';
            echo "<td>" . $row["fullname"] . "</td></tr>";

            //echo '<tr><th>NRIC: </th>';
            //echo "<td>" . $row["nric"] . "</td></tr>";

            echo '<tr>
                <th>Username: </th>';
            echo "<td>" . $row["username"];
            ?>

                <form name="viewer_edit_username" action="php/caregiver_edit_username.php" method="post" onsubmit="return isFormValid(3)">
                <small><em><input type="text" name="username"  placeholder="Your desired username" oninput="validateUsername()" required></em></small>
                <input type="submit" class="btn btn-primary" value="Edit">
                <div class='alert-info' id='username_feedback'></div></td>
                </tr>

                </form>
            <?php

            echo '<tr>
                <th>Phone Number: </th>';
            echo "<td>" . $row["phone_no"];
            ?>

                <form name='viewer_edit_phone' action='php/caregiver_edit_phone.php' method="post" onsubmit="return isFormValid(2)">
                <small><em><input type='text' name='phoneNo' placeholder="Input phone number" oninput="validatePhoneNo()" required></em></small>
                <input type='submit' class="btn btn-primary" value='Edit'>
                <div class='alert-info' id='phone_no_feedback'></div>
                </td>
                </tr></form>
            <?php

            echo '<tr>
                <th>Email: </th>';


            if($row["email"] === NULL)
                echo "<td>" . "No email";
            else {
                echo '<form name="viewer_delete_email" action="php/caregiver_delete_email.php"  method="post">';
                echo '<td>' . $row["email"] . '<input type="submit" value="Delete"></form>';
            }
            ?>

            <form name='viewer_edit_email' action='php/caregiver_edit_email.php' method="post">
                <small><em><input type='email' name='email' placeholder="example@example.com" required></em></small>
                <input type='submit' class="btn btn-primary" value='Edit' ></form>

                </td></tr>
            <?php

            //echo '<tr><th>Caregiver Type: </th>';
            //echo "<td>" . $row["rvtype"];
            ?>

            <!--
            <form name='viewer_edit_rvtype' action='php/caregiver_edit_rvtype.php' method="post">

                <select  name='rvtype' required><option value="Family">Family</option>
                    <option value="Others">Others</option>
                </select>
                <input type='submit' class="btn btn-primary" value='Edit'></form></td>
            -->

            <?php
           //here
        }

    } else {
        echo "query failed";
    }
    echo "</table>";
?></div>
    <br><br>
    <div class="container">
        <div class="page header">
            <h3>Change My Password:</h3>
        </div>
        <table class="table">
    <form id="viewer_edit_password" action='php/caregiver_edit_password.php'onsubmit="return isPasswordValid(6,4,5)" method="post">
        <tr><th>Old Password: </th><td><small><em><input type="password" name="oldPassword" placeholder= "Input Old Password" oninput="validateOldPassword()" required></em></small></td></tr>
        <div class='alert-info' id='old_password_feedback'></div>
        <tr><th>New Password: </th><td><small><em><input type="password" name="newPassword" placeholder= "Input New Password" oninput="validatePassword()"required></em></small></td></tr>
        <div class='alert-info' id='password_feedback'></div>
        <tr><th>Confirm Password: </th><td><small><em><input type="password" name="confirmPassword" placeholder= "Confirm Password" oninput="validateConfirmPassword()"required></em></small></td></tr>
        <div class='alert-info' id='confirm_password_feedback'></div>
        <tr><th></th><td><small><input type='submit' class="btn btn-warning" value='Change My Password'></small></td></tr>
        </table>
    </form>

    <tr><td><form id="back_to_index" action='caregiver_admin_index.php'>
                <input type='submit' class="btn btn-primary" value='Go back to Caregiver Admin'></td></tr>
    </form>

    </div>
</body>

</html>