<?php 
session_start ();
$user_id = $_SESSION ["login_id"];

// import sql utility functions.
require_once __DIR__.'/DB_connect/db_utility.php';

// Fetch Unregistered Viewers of this User
$viewers_sql_resp = make_query ( "select * from UnregisteredViewer where user_id='$user_id'" );

if ( $_SERVER["REQUEST_METHOD"] == "POST") {
	compare_and_update("viewername", $viewers_sql_resp);
	compare_and_update("phone_no", $viewers_sql_resp);
	compare_and_update("email", $viewers_sql_resp);

	header('Location: ../user_admin_index.php');
}

function compare_and_update($column_name, $sql_result){
	global $user_id;
	if (! empty ( $_POST [$column_name] )){
		foreach ( $_POST [$column_name] as $value ) {
			$sql_row = mysqli_fetch_assoc( $sql_result );
			if(!($sql_row[$column_name] == $value))
				if(gettype($value) == "string")
					make_query ( "update UnregisteredViewer set ".$column_name."='".$value.
							"' where verification_code=".$sql_row["verification_code"]. " and user_id=".$user_id );
				else
					make_query ( "update UnregisteredViewer set ".$column_name."=".$value. 
						" where verification_code=".$sql_row["verification_code"]. " and user_id=".$user_id );
		}
	}
	mysqli_data_seek($sql_result, 0); //reset the sql result object to first row
}

function generate_unrge_viewer_update_table($viewer_sql_resp) {
	echo "<table><tr>
		<th align='left'>Name</th>
		<th align='left'>Phone No.</th>
		<th align='left'>Email</th>
	</tr>";
	/* Read unregistered viewers and fillin the table */
	$isNoRecords = false;
	if (mysqli_num_rows ( $viewer_sql_resp ) > 0) {
		// output data of each row
		//TODO validate these data
		while ( $row = mysqli_fetch_assoc ( $viewer_sql_resp ) ) {
			echo "<tr>";
			echo "<td>" . "<input type='text' name='viewername[]' value='" . $row ["viewername"] . "'>" . "</td>";
			echo "<td>" . "<input type='text' name='phone_no[]' value='" . $row ["phone_no"] . "'>" . "</td>";
			echo "<td>" . "<input type='text' name='email[]' value='" . $row ["email"] . "'>" . "</td>";
			echo "</tr>";
		}
	} else {
		$isNoRecords = true;
	}

	echo "</table>";
	if ($isNoRecords)
		echo 'No records found.<br>';
}
?>
<!DOCTYPE html>
<html>
	<head lang="en">
		<title>Modify Unregistered Viewers</title>
	</head>

	<body>
	
	
	<h1> Hello <?php echo $_SESSION['login_firstname'].' '.$_SESSION['login_lastname']; ?></h1>
	<p>listed below are your unregistered viewers</p>
	
	<form id="unreg_viewer_update_form" method='post'>
		<?php generate_unrge_viewer_update_table($viewers_sql_resp)?>
		<input type="submit" name="Back" value="Go Back to Admin Console" formmethod="get" formaction="../user_admin_index.php"
			onclick="return confirm('Any modification made will be discarded. Confirm?')" />
		<input type="submit" name="Update" value="Update Contacts"/>
	
	</form>
	
	</body>
</html>