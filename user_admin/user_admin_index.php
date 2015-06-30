<?php 
	//start session and check for session validity
	require_once 'php/DB_connect/check_session_validity.php';

	$user_id = $_SESSION ["login_id"];
	
	/* Fetch viewers data */
	// Fetch Registered Viewers of this User
	$reg_viewers_sql_resp = make_query ( "select * from RegisteredViewer R, Caregive where user_id={$user_id} and rv_id=R.id");
	
	// Fetch Unregistered Viewers of this User
	$unreg_viewers_sql_resp = make_query ( "select * from UnregisteredViewer where user_id='$user_id'" );
	
	// Fetch Emergency Viewers of this User
	$landline_sql_resp = make_query ( "select * from CallLandline where user_id='$user_id'");
	
	
	function generate_viewer_table($viewer_sql_resp)
    {
    	if (mysqli_num_rows($viewer_sql_resp) > 0) {
	        echo "<table class='table table-hover'><tr>
				<th>Name</th>
				<th>Phone No.</th>
				<th>Email</th>
				<th>Delete</th>
			</tr>";
	        /* Read viewers and fillin the table */
            // output data of each row
            while ($row = mysqli_fetch_assoc($viewer_sql_resp)) {
                echo "<tr>";
                if (isset ($row ["id"]))
                    echo "<td>" . $row ["fullname"] . "</td>";
                else
                    echo "<td>" . $row ["viewername"] . "</td>";
                echo "<td>" . $row ["phone_no"] . "</td>";
                echo "<td>" . $row ["email"] . "</td>";

                if (isset ($row ["id"]))
                    echo "<td>" . "<input type='checkbox' name='reg_viewer[]' value='" . $row ["id"] . "'>" . "</td>";
                else
                    echo "<td>" . "<input type='checkbox' name='unreg_viewer[]' value='" . $row ["verification_code"] . "'>" . "</td>";

                echo "</tr>";
            }
            echo "</table>";
    	} 
    	else{
    		echo 'No records found.<br>';
    	}
    }

    function generate_landline_table($landline_sql_resp) {
    	if (mysqli_num_rows ( $landline_sql_resp ) > 0) {
	    	echo "<table class='table table-hover'><tr>
				<th>Name</th>
				<th>Phone No.</th>
				<th>Delete</th>
				</tr>";
			/* Read viewers and fillin the table */
			// output data of each row
			while ( $row = mysqli_fetch_assoc ( $landline_sql_resp ) ) {
				$name = $row ['name'];
				$landline_id = $row ['landline_id'];

				$query = "SELECT phone_no FROM LandlineContact WHERE id=$landline_id";
				$response = make_query ( $query );
				$phone_no = "";
				if ($response) {
					$row = mysqli_fetch_assoc ( $response );
					$phone_no = $row ['phone_no'];
				} else {
					$phone_no = "error fetching phone number";
				}
				
				echo "<tr>";
				echo "<td>$name</td>";
				echo "<td>$phone_no</td>";
				echo "<td><input type='checkbox' name='landline[]' value=$landline_id></td>";
				echo "</tr>";
			}
			echo "</table>";
		}
		else{
			echo 'No records found.<br>';
		}
	}
?>

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
<title>User Admin Console</title>
</head>

<body>
	<div class="container">
		<div class="jumbotron  well">
			<h1> Hello, <?php echo $_SESSION['login_fullname']; ?></h1>
		</div>

		<form id="viewer_management_form" method='post'
			action='php/user_delete_viewer.php'>
			<div class="page-header">
				<h2>List of registered viewers</h2>
			</div>

			<?php generate_viewer_table($reg_viewers_sql_resp)?>
		
			<div class="page-header">
				<h2>List of unregistered viewers</h2>
			</div>

			<?php generate_viewer_table($unreg_viewers_sql_resp)?>
			
			<input type="submit"
				value="Modify Unregistered Viewer Information" formmethod="get"
				formaction="user_update_unreg_viewer.php" class="btn btn-primary"/>
				
			<div class="page-header">
				<h2>List of Emergency landline contacts viewers</h2>
			</div>
	    
	        <?php generate_landline_table($landline_sql_resp);?>
	        
	        <br><br><br>
	        <div class=row><div class="col-xs-12">
			<input type="submit" name="Delete Selected" value="Delete Selected"
					onclick="return confirm('Are you sure that you would like to delete these contacts?')" class="btn btn-warning" />
			</div></div>
			<br>
			<div class=row><div class="col-xs-12"><input type='submit' value='Add Viewer' class="btn btn-primary" formmethod="get" formaction='user_add_viewer.php'/></div></div>
			<br>
			<div class=row><div class="col-xs-12"><input type='submit' value='Add Emergency landline contact' class="btn btn-primary" formmethod="get" formaction='user_add_emerg.php'/></div></div>
			<br>
            <div class=row><div class="col-xs-12"><input type='submit' value='Edit profile' class="btn btn-primary" formmethod="get" formaction='user_edit_profile.php'/></div></div>
            <br>
			<div class=row><div class="col-xs-12"><input type='submit' value='Log out' class="btn btn-danger" formmethod="get" formaction='php/logout.php'/></div></div>
		</form>
		
		

	</div>
</body>
</html>