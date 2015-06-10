<!DOCTYPE html>
<?php session_start(); ?>
<html>
<head lang="en">
    <link rel="stylesheet" type="text/css" href="style/viewer_admin_style.css">
    <meta charset="UTF-8">
    <title>Viewer Admin</title>
</head>
<body>
<script src="script/viewer_verify_script.js"></script>

<h1> Hello <?php echo $_SESSION['login_viewer'] . ','; ?></h1>
<?php
    $username = $_SESSION['login_viewer'];
    $viewer_id = $_SESSION['viewer_id'];
    $viewer_phone = $_SESSION['viewer_phone'];
    //echo "viewer id is " . $viewer_id;

    echo "<h3> You are currently viewing: </h3>";

    queryCaregive($viewer_id);

    echo "<br><br><h3> Unverified Users Requiring Your Confirmation to View</h3>";

    queryUnregistered($viewer_phone);

    //echo "<br><br><input type='submit' value='Edit my profile'>";


function queryCaregive($viewer_id) {
    require_once __DIR__.'/php/DB_connect/db_connect.php';
    $connector = new DB_CONNECT();
    $connector->connect();

    $caregiveQuery = "SELECT firstname, lastname, birthday, gender, nric, phone_no FROM User, Caregive WHERE rv_id = '$viewer_id' AND user_id = User.id";
    $caregiveResponse = mysqli_query($connector->conn, $caregiveQuery);


    if($caregiveResponse === FALSE) {
        echo "response is erroneous";
        die(mysql_error());
    }




    if(mysqli_num_rows($caregiveResponse) > 0) {
        echo "<table>
        <tr>
        <th>Name</th>
        <th>Age</th>
        <th>Gender</th>
        <th>NRIC</th>
        <th>Phone Number</th>
        </tr>";

        while($row = mysqli_fetch_assoc($caregiveResponse)) {
            echo "<tr>";
            echo "<td>" . $row["firstname"] . " " . $row["lastname"] . "<br></td>";
            echo "<td>" . date_diff(date_create($row["birthday"]), date_create('today'))->y . "</td>";
            echo "<td>" . $row["gender"] . "</td>";
            echo "<td>" . $row["nric"] . "</td>";
            echo "<td>" . $row["phone_no"] . "</td>";
            echo "</tr>";

        }
    } else {
        echo "You are viewing 0 users";
    }
    echo "</table>";
    $connector->close();

    return $caregiveResponse;
}

function queryUnregistered($viewer_phone) {
    require_once __DIR__.'/php/DB_connect/db_connect.php';
    $connector = new DB_CONNECT();
    $connector->connect();

    $unregisteredQuery = "select id, firstname, lastname, U.phone_no, gender, birthday from UnregisteredViewer, User U where '$viewer_phone' = UnregisteredViewer.phone_no AND user_id = U.id";
    $unregisteredResponse = mysqli_query($connector->conn, $unregisteredQuery);

    if($unregisteredResponse === FALSE) {
        echo "response is erroneous";
        die(mysql_error());
    }
    if(mysqli_num_rows($unregisteredResponse) > 0) {

        echo "<table>
        <tr>
        <th>Name</th>
        <th>Age</th>
        <th>Gender</th>
        <th>Phone Number</th>
        <th>Enter Verification</th>
        </tr>";
        ?>

            <form name='viewer_verify_form' action='php/viewer_verify_user.php' method="post"
        <?php
        while($row = mysqli_fetch_assoc($unregisteredResponse)) {
            echo "<tr>";
            echo "<td>" . $row["firstname"] . " " . $row["lastname"] . "<br></td>";
            echo "<td>" . date_diff(date_create($row["birthday"]), date_create('today'))->y . "</td>";
            echo "<td>" . $row["gender"] . "</td>";
            echo "<td>" . $row["phone_no"] . "</td>";
            echo "<td>" . "<input type='text' name='verification_code'  required>" . " ";
            echo "<input type='submit' value='Verify'>";
            echo '<input type="hidden" name="user_id" value=' . $row["id"] . '>';
            echo "</td>";

            echo "</tr>";

        }
        ?>

            </form>
        <?php
    } else {
        echo "No user awaiting your confirmation";
    }

    $connector->close();
}
?>


</body>
</html>