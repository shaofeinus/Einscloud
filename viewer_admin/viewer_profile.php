<?php session_start(); ?>
<html>
<head lang="en">
    <link rel="stylesheet" type="text/css" href="style/viewer_admin_style.css">
    <meta charset="UTF-8">
    <title>My Profile</title>
</head>

<body>
<h1><?php echo $_SESSION['login_viewer'] . "'s Profile"; ?></h1>
<script src="script/viewer_edit.js"></script>
<?php
    $viewer_id = $_SESSION['viewer_id'];
    require_once __DIR__.'/php/DB_connect/db_utility.php';

    $viewerProfileResponse = make_query("select * from RegisteredViewer where id = '$viewer_id'");
    if($viewerProfileResponse === FALSE) {
        echo "response is erroneous";
        die(mysql_error());
    }
    if(mysqli_num_rows($viewerProfileResponse) > 0) {
        echo '<table>
            <tr>

            <th>Name</th>
            <th>NRIC</th>
            <th>Username</th>
            <th>Email</th>

            <th>Phone Number</th>
            <th>Viewer Type</th>
            </tr>';

        while($row = mysqli_fetch_assoc($viewerProfileResponse)) {
            echo "<tr>";
            echo "<td>" . $row["firstname"] . " " . $row["lastname"] . "<br><br><br></td>";
            echo "<td>" . $row["nric"] . "<br><br><br></td>";

            echo "<td>" . $row["username"];
            ?>
                <form name="viewer_edit_username" action="php/viewer_edit_username.php" method="post" onsubmit="return isFormValid(3)">
                <input type="text" name="username" oninput="validateUsername()" required>
                <input type="submit" value="Edit">
                <div class='feedback' id='username_feedback'></div>
                </td>

                </form>

            <?php

            if($row["email"] === NULL)
                echo "<td>" . "No email";
            else {
                echo '<form name="viewer_delete_email" action="php/viewer_delete_email.php"  method="post">';
                echo '<td>' . $row["email"] . '<input type="submit" value="Delete"></form>';

            }
            ?>
            <form name='viewer_edit_email' action='php/viewer_edit_email.php' method="post">
                <input type='email' name='email' required>
                <input type='submit' value='Edit' ></form>
                </td>
            <?php


            echo "<td>" . $row["phone_no"];
            ?>
            <form name='viewer_edit_phone' action='php/viewer_edit_phone.php' method="post" onsubmit="return isFormValid(2)">
                <input type='text' name='phoneNo' oninput="validatePhoneNo()" required>
                <input type='submit' value='Edit'>
                <div class='feedback' id='phone_no_feedback'></div>
                </td></form>


            <?php
            echo "<td>" . $row["rvtype"];
            ?>
            <form name='viewer_edit_rvtype' action='php/viewer_edit_rvtype.php' method="post">
                <select name='rvtype' required><option value="Family">Family</option>
                    <option value="Others">Others</option>
                </select>
                <input type='submit' value='Edit'></td></form>
            <?php
            echo "</tr>";
        }

    } else {
        echo "query failed";
    }
    echo "</table>";
?>
    <br><br>
    <form id="viewer_edit_password" action='php/viewer_edit_password.php'onsubmit="return isPasswordValid(6,4,5)" method="post">
        <p>Old Password: <input type="password" name="oldPassword" oninput="validateOldPassword()" required><br>
        <div class='feedback' id='old_password_feedback'></div></p>
        <p>New Password: <input type="password" name="newPassword" oninput="validatePassword()"required><br>
        <div class='feedback' id='password_feedback'></div></p>
        <p>Confirm Password: <input type="password" name="confirmPassword" oninput="validateConfirmPassword()"required>
        <div class='feedback' id='confirm_password_feedback'></div></p>
        <input type='submit' value='Change My Password'>
    </form>

    <form id="back_to_index" action='viewer_admin_index.php'>
        <input type='submit' value='Go back to Viewer Admin'>
    </form>

</body>

</html>