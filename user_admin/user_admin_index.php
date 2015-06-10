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
/* Fetch viewers data */

// Fetch Unregistered Viewers of this User
$user_id = $_SESSION ["login_id"];
$unreg_viewers_sql_resp = make_query ( "select * from UnregisteredViewer where user_id='$user_id'" );

// Fetch Registered Viewers of this User
$reg_viewers_sql_resp = make_query ( "select * from RegisteredViewer where id=(select rv_id from Caregive where user_id='$user_id')" );
function make_query($query) {
	require_once 'php/DB_connect/db_connect.php'; // TODO prone to path change. Need refactor.
	$connector = new DB_CONNECT ();
	$connector->connect ();
	$response = mysqli_query ( $connector->conn, $query );
	$connector->close ();
	return $response;
}

?>
	<h2>List of registered viewers</h2>
	<table border=1>
		<tr>
			<th>Name</th>
			<th>Phone No.</th>
			<th>Email</th>
		</tr>
		<?php
		/* Read registered viewers and fillin the table */
		$isRegNoRecords = false;
		if (mysqli_num_rows ( $reg_viewers_sql_resp ) > 0) {
			
			// output data of each row
			while ( $row = mysqli_fetch_assoc ( $reg_viewers_sql_resp ) ) {
				echo "<tr>";
				echo "<th>" . $row ["firstname"] . " " . $row ["lastname"] . "</th>";
				echo "<th>" . $row ["phone_no"] . "</th>";
				echo "<th>" . $row ["email"] . "</th>";
				echo "</tr>";
			}
		} else {
			$isRegNoRecords = true;
		}
		?>
	</table>
	<?php if($isRegNoRecords) echo 'No records found.<br>'?>

	<h2>List of unregistered viewers</h2>
	<table border=1>
		<tr>
			<th>Name</th>
			<th>Phone No.</th>
			<th>Email</th>
		</tr>
		<?php
		/* Read registered viewers and fillin the table */
		$isUnregNoRecords = false;
		if (mysqli_num_rows ( $unreg_viewers_sql_resp ) > 0) {
			
			// output data of each row
			while ( $row = mysqli_fetch_assoc ( $unreg_viewers_sql_resp ) ) {
				echo "<tr>";
				echo "<th>" . $row ["firstname"] . " " . $row ["lastname"] . "</th>";
				echo "<th>" . $row ["phone_no"] . "</th>";
				echo "<th>" . $row ["email"] . "</th>";
				echo "</tr>";
			}
		} else {
			$isUnregNoRecords = true;
		}
		?>
	</table>
	<?php if($isUnregNoRecords) echo 'No records found.<br>'?>


</body>
</html>