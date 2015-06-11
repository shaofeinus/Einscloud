<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head lang="en">
    <link rel="stylesheet" type="text/css" href="style/user_resgistration_style.css">
    <meta charset="UTF-8">
    <title>Einswatch add Viewers</title>
</head>

<body>
<?php require "php/user_add_new_viewer.php"?>
<script src="script/user_add_viewer_script.js"></script>
<?php verifyLogin(); ?>
<h1>Welcome <?php echo $_SESSION['login_user']; ?> </h1>

<form name="select_no_of_viewers_form">
    <?php displayDropMenu(); ?><br>
</form>

<form id="add_viewers_form" name="add_viewers_form" action="" method="post" onsubmit="return validateForm()"></form>

</body>
</html>

<?php
/**
 * Created by PhpStorm.
 * User: Shao Fei
 * Date: 10/6/2015
 * Time: 12:23 PM
 */
//header('Content-Type: application/json');

function verifyLogin() {
    if(empty($_SESSION['login_user'])) {
        header("Location: logged_out.html");
    }
}

function displayDropMenu() {
    $user_id = $_SESSION['login_id'];
    require_once __DIR__.'/php/DB_connect/db_utility.php';
    $query = "SELECT * FROM Caregive WHERE user_id='$user_id'";
    $response = make_query($query);
    if($response) {
        $num_rows = mysqli_num_rows($response);

        if($num_rows < 5){
            echo "How may viewers do you want to add?<br>";
            echo "<select id='add_num_viewers' onchange='displayAddViewerForm()'>";
            $num_options = 5 - $num_rows;
            $index = 1;
            echo "<option value='0'></option>";
            while($num_options) {
                echo "<option value=$index>$index</option>";
                $index++;
                $num_options--;
            }
            echo "</select>";
        } else{
            echo "You already have 5 Viewer. Click <a href='user_admin_index.php'>here</a> to manage Viewers";
        }

    } else {
        echo "Error retrieving data";
    }
}
?>