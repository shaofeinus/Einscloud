<!DOCTYPE html>
<html>
<head lang="en">
<link rel="stylesheet" type="text/css"
	href="style/user_resgistration_style.css">
<meta charset="UTF-8">
<title>User Admin Console</title>
</head>
<body>
	<h1> Hello <?php session_start(); echo $_SESSION['login_firstname'].' '.$_SESSION['login_lastname']; ?></h1>

	<?php
	//import sql utility functions.
	require_once $_SERVER['DOCUMENT_ROOT'].'/php/DB_connect/db_utility.php';
	
	/* Fetch viewers data */
	// Fetch Unregistered Viewers of this User
	$user_id = $_SESSION ["login_id"];
	$unreg_viewers_sql_resp = make_query ( "select * from UnregisteredViewer where user_id='$user_id'" );
	
	// Fetch Registered Viewers of this User
	$reg_viewers_sql_resp = make_query ( "select * from RegisteredViewer where id=(select rv_id from Caregive where user_id='$user_id')" );
	?>



	<h2>List of registered viewers</h2>
	<?php generate_viewer_table($reg_viewers_sql_resp)?>

	<h2>List of unregistered viewers</h2>
	<?php generate_viewer_table($unreg_viewers_sql_resp)?>
	
	<p>
		<form id="goto_register_form" action='user_add_viewer.php'>
			<input type='submit' value='Add Viewer'>
		</form>
	</p>

<?php 
function generate_viewer_table($viewer_sql_resp){
	echo "<table border=1><tr>
			<th>Name</th>
			<th>Phone No.</th>
			<th>Email</th>
			<th></th>
		</tr>";
	/* Read registered viewers and fillin the table */
	$isNoRecords = false;
	if (mysqli_num_rows ( $viewer_sql_resp ) > 0) {
			
		// output data of each row
		while ( $row = mysqli_fetch_assoc ( $viewer_sql_resp ) ) {
			echo "<tr>";
			echo "<th>" . $row ["firstname"] . " " . $row ["lastname"] . "</th>";
			echo "<th>" . $row ["phone_no"] . "</th>";
			echo "<th>" . $row ["email"] . "</th>";
			echo "</tr>";
		}
	} else {
		$isNoRecords = true;
	}
	
	echo "</table>";
	if($isNoRecords) echo 'No records found.<br>';
}
?>
</body>
</html>