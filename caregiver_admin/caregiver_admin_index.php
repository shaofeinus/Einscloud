<?php 
//start session and check for session validity
require_once 'php/DB_connect/check_session_validity.php';
?>
<!DOCTYPE html>
<html>
<head lang="en">
    <!-- <link rel="stylesheet" type="text/css" href="style/viewer_admin_style.css"> -->
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Caregiver Admin</title>
</head>
<body>

    <script src="script/caregiver_verify_script.js?v=1.0"></script>

    <div class="container">
        <div class="jumbotron well">
        <h1>Hello <?php echo $_SESSION['viewer_name'] . ','; ?></h1>
        </div>
    </div>

    <div class="container well">
        <div class="page-header">
            <h3>You are currently viewing:</h3>
        </div>


        <?php
        require_once 'php/DB_connect/db_utility.php';
        $username = $_SESSION['login_viewer'];
        $viewer_id = $_SESSION['viewer_id'];
        $viewer_phone = $_SESSION['viewer_phone'];


        $caregiveQuery = "SELECT fullname, birthday, gender, nric, phone_no FROM User, Caregive WHERE rv_id = '$viewer_id' AND user_id = User.id";
        $caregiveResponse = make_query($caregiveQuery);

        if($caregiveResponse === FALSE) {
            echo "response is erroneous";
            die(mysql_error());
        }
        if(mysqli_num_rows($caregiveResponse) > 0) {
            ?>

            <div class="row">
            <?php
            while ($row = mysqli_fetch_assoc($caregiveResponse)) {

                ?>

                        <div class="col-sm-4">
                            <h4><?php echo $row["fullname"]?></h4>
                            <table>
                                <tr>
                                    <th>Age: </th>
                                    <td><?php echo date_diff(date_create($row["birthday"]), date_create('today'))->y ?></td>
                                </tr>
                                <tr>
                                    <th>Gender: </th>
                                    <td><?php echo $row["gender"]?></td>
                                </tr>
                                <tr>
                                    <th>NRIC: </th>
                                    <td><?php echo $row["nric"]?></td>
                                </tr>
                                <tr>
                                    <th>Phone Number: </th>
                                    <td><?php echo ' ' . $row["phone_no"]?></td>
                                </tr>
                            </table>
                        </div>

            <?php
            }
            ?>
            </div>
            <?php
        }
        else {
            ?>
                <div class ='page-header'>
                    <h4><?php echo "You are not a caregiver of anyone";?></h4>
                </div>

        <?php
        }

        ?>
    </div>

    <div class="container well">
        <div class="page-header">
            <h3>Unverified Users Requiring Your Confirmation to View: </h3>
        </div>

        <?php
        $unregisteredQuery = "select id, fullname, U.phone_no, gender, birthday from UnregisteredViewer, User U where '$viewer_phone' = UnregisteredViewer.phone_no AND user_id = U.id";

        $unregisteredResponse = make_query($unregisteredQuery);

        if(mysqli_num_rows($unregisteredResponse) > 0) {
            ?>
            <div class="row">
                <?php
                while ($row = mysqli_fetch_assoc($unregisteredResponse)) {
                ?>
                <div class="col-sm-4">
                    <form name='viewer_verify_form' action='php/caregiver_verify_user.php' method="post">

                        <h3><?php echo $row["fullname"]?></h3>
                        <table>
                            <tr>
                                <th>Age: </th>
                                <td><?php echo date_diff(date_create($row["birthday"]), date_create('today'))->y ?></td>
                            </tr>
                            <tr>
                                <th>Gender: </th>
                                <td><?php echo $row["gender"]?></td>
                            </tr>
                            <tr>
                                <th>Phone Number: </th>
                                <td><?php echo ' ' . $row["phone_no"]?></td>
                            </tr>
                            <tr>
                                <th>Verification Code:</th>
                                <td><?php echo "<input type='text' name='verification_code'  required>" . " ";?></td>
                            </tr>
                            <tr>
                                <td>
                                <?php
                                    echo "<input type='submit' value='Verify'>";
                                    echo '<input type="hidden" name="user_id" value=' . $row["id"] . '>';
                                ?>
                                </td>
                            </tr>
                        </table>

                    </form>
                </div>
                <?php
                }
                ?>
            </div>
        <?php
        }
        else{
            ?>
            <div class = 'page-header'>
                <h4><?php echo "You have no users waiting for your verification";?></h4>
            </div>

            <?php
        }

        ?>
    </div>

    <div class = 'container'>

    <form id="edit_form" action='caregiver_profile.php'>
        <input type='submit' class="btn btn-primary" value='Edit my profile'>
    </form>
    <br>
    <form id="log_out_form" action='./php/logout.php'>
        <input type='submit' class="btn btn-danger" value='Log out'>
    </form>
    </div>


</body>
</html>