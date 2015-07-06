<?php 
/*
 * This page submit the POST form to itself.
 */

//start session and check for session validity
require_once 'php/DB_connect/check_session_validity.php';

$user_id = $_SESSION ["login_id"];

// import sql utility functions.
require_once 'php/DB_connect/db_utility.php';

// Fetch Unregistered Viewers of this User
$link = get_conn();
$selectStmt = mysqli_prepare($link, "select verification_code from UnregisteredViewer where user_id=?");
$selectStmt->bind_param("i", $user_id);
$selectStmt->execute();
$selectStmt->bind_result($verification_code);
$selectStmt->fetch();
$selectStmt->close();
$link->close();

if ( $_SERVER["REQUEST_METHOD"] == "POST") {
	compare_and_update("viewername", $verification_code);
	compare_and_update("phone_no", $verification_code);
	compare_and_update("email", $verification_code);

	header('Location: user_admin_index.php');
}

function compare_and_update($column_name, $verification_code){
	global $user_id;
	if (! empty ( $_POST [$column_name] )){
		foreach ( $_POST [$column_name] as $value ) {
            $link = get_conn();
            $updateStmt = mysqli_prepare($link,
                "update UnregisteredViewer set $column_name=? where verification_code=? and user_id=?");
            if(gettype($value) == "string") {
                $updateStmt->bind_param("ssi", $value, $verification_code, $user_id);
                //make_query ( "update UnregisteredViewer set $column_name='$value' where verification_code=$verification_code and user_id=$user_id");
            }
            else {
                $updateStmt->bind_param("isi", $value, $verification_code, $user_id);
                //make_query ( "update UnregisteredViewer set $column_name=$value where verification_code=$verification_code and user_id=$user_id" );
            }
            $updateStmt->execute();
            $updateStmt->close();
            $link->close();

		}
	}
}

function generate_unrge_viewer_update_table() {

    $link = get_conn();
    $selectStmt = mysqli_prepare($link, "select viewername, phone_no, email from UnregisteredViewer where user_id=?");
    $user_id = $_SESSION ["login_id"];
    $selectStmt->bind_param("i", $user_id);
    $selectStmt->execute();
    $selectStmt->store_result();

	echo "<table class='table table-hover'><tr>
		<th class='form_th' align='left'>Name</th>
		<th class='form_th' align='left'>Phone No.</th>
		<th class='form_th' align='left'>Email</th>
	</tr>";
	/* Read unregistered viewers and fillin the table */
	$isNoRecords = false;
    if($selectStmt->num_rows > 0) {
        $row = array();
        $selectStmt->bind_result($row ["viewername"], $row ["phone_no"], $row ["email"]);
		// output data of each row
		//TODO validate these data
		while ($selectStmt->fetch()) {
			echo "<tr>";
			echo "<td class='form_td'>" . "<input required class='form-control' type='text' name='viewername[]' value='" . $row ["viewername"] . "'>" . "</td>";
			echo "<td class='form_td'>" . 
				"<input required title='Singapore mobile number' 
					pattern='^[8|9][0-9][0-9][0-9][0-9][0-9][0-9][0-9]$' class='form-control' type='text' name='phone_no[]' value='" . $row ["phone_no"] . "'>" . 
				"</td>";
			echo "<td class='form_td'>" . "<input class='form-control' placeholder='Enter password' type='email' name='email[]' value='" . $row ["email"] . "'>" . "</td>";
			echo "</tr>";
		}
	} else {
		$isNoRecords = true;
	}

    $selectStmt->close();
    $link->close();

	echo "</table>";
	if ($isNoRecords)
		echo 'No records found.<br>';
}
?>
<!DOCTYPE html>
<html>
	<head lang="en">
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
		<title>Modify Unregistered Viewers</title>
	</head>

	<body>
	<div class="container">
		<div class="jumbotron well">
			<h1> Hello, <?php echo $_SESSION['login_fullname']; ?></h1>
			<p>listed below are your unregistered viewers</p>
		</div>
        <form id="unreg_viewer_update_form" method='post' class="form-horizontal" role="form">
            <?php generate_unrge_viewer_update_table()?>
            <div class="row">
                <div class="col-lg-4"/>
                <div class="col-lg-6">
                	<div class="form-group">
                        <input type="submit" name="Update" value="Update Contacts" class="btn btn-primary"/>
                    </div>
                    
                    <div class="form-group">
                        <input type="submit" name="Back" value="Go Back to Admin Console" formmethod="get" formaction="user_admin_index.php"
                        onclick="return confirm('Any modification made will be discarded. Confirm?')" class="btn btn-primary"/>
                    </div>	
                    
                    <div class="form-group">
                        <input type='submit' value='Log out' formaction="php/logout.php" class="btn btn-danger"/>
                    </div>
                </div>
                <div class="col-lg-2"/>
            </div>
        </form>
	</div>
	</body>
</html>