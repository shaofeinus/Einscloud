<?php session_start ();
if(!isset($_SESSION ["login_id"]))
    header("Location: logged_out.html");
?>
<!DOCTYPE html>
<html>
	<head lang="en">
		<link rel="stylesheet" type="text/css"
			href="style/user_resgistration_style.css">
		<meta charset="UTF-8">
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<title>User Admin Console</title>
	</head>
	
	<body>

	<?php
	$user_id = $_SESSION ["login_id"];
	
	// import sql utility functions.
	require_once __DIR__.'/php/DB_connect/db_utility.php';
	
	if ( $_SERVER["REQUEST_METHOD"] == "POST") {
		if (! empty ( $_POST ['reg_viewer'] )) {
			// Loop to store and display values of individual checked checkbox.
			foreach ( $_POST ['reg_viewer'] as $viewer_id ) {
				make_query ( "delete from Caregive where rv_id='$viewer_id' and user_id='$user_id'" );
			}
		}
		
		if (! empty ( $_POST ['unreg_viewer'] )) {
			// Loop to store and display values of individual checked checkbox.
			foreach ( $_POST ['unreg_viewer'] as $vr_code ) {
				make_query ( "delete from UnregisteredViewer where verification_code='$vr_code'" );
			}
		}

        if (! empty ( $_POST ['landline'] )) {
            // Loop to store and display values of individual checked checkbox.
            foreach ( $_POST ['landline'] as $landline_id ) {
                make_query ( "delete from CallLandline where landline_id=$landline_id AND user_id=$user_id" );
            }
        }
	}
	
	function generate_viewer_table($viewer_sql_resp)
    {
        echo "<table border=1><tr>
			<th>Name</th>
			<th>Phone No.</th>
			<th>Email</th>
			<th>Delete</th>
		</tr>";
        /* Read viewers and fillin the table */
        $isNoRecords = false;
        if (mysqli_num_rows($viewer_sql_resp) > 0) {
            // output data of each row
            while ($row = mysqli_fetch_assoc($viewer_sql_resp)) {
                echo "<tr>";
                if (isset ($row ["id"]))
                    echo "<td>" . $row ["firstname"] . " " . $row ["lastname"] . "</td>";
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
        } else {
            $isNoRecords = true;
        }

        echo "</table>";
        if ($isNoRecords)
            echo 'No records found.<br>';
    }

    function generate_landline_table($landline_sql_resp) {
        echo "<table border=1><tr>
			<th>Name</th>
			<th>Phone No.</th>
			<th>Delete</th>
		</tr>";
        /* Read viewers and fillin the table */
        $isNoRecords = false;
        if (mysqli_num_rows ( $landline_sql_resp ) > 0) {
            // output data of each row
            while ( $row = mysqli_fetch_assoc ( $landline_sql_resp ) ) {

                $name = $row['name'];
                $landline_id = $row['landline_id'];
                require_once __DIR__.'/php/DB_connect/db_utility.php';
                $query = "SELECT phone_no FROM LandlineContact WHERE id=$landline_id";
                $response = make_query($query);
                $phone_no = "";
                if($response)  {
                    $row = mysqli_fetch_assoc($response);
                    $phone_no = $row['phone_no'];
                } else {
                    $phone_no = "error fetching phone number";
                }

                echo "<tr>";
                echo "<td>$name</td>";
                echo "<td>$phone_no</td>";
                echo "<td><input type='checkbox' name='landline[]' value=$landline_id></td>";
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

	<h1> Hello <?php echo $_SESSION['login_firstname'].' '.$_SESSION['login_lastname']; ?></h1>

	<?php
	/* Fetch viewers data */
	// Fetch Unregistered Viewers of this User
	$unreg_viewers_sql_resp = make_query ( "select * from UnregisteredViewer where user_id='$user_id'" );
	
	// Fetch Registered Viewers of this User
	$reg_viewers_sql_resp = make_query ( "select * from RegisteredViewer where id=(select rv_id from Caregive where user_id='$user_id')" );

    $landline_sql_resp = make_query ( "select * from CallLandline where user_id='$user_id'");

    ?>


	<form id="viewer_management_form" method='post'>
		<h2>List of registered viewers</h2>
		<?php generate_viewer_table($reg_viewers_sql_resp)?>
	
		<h2>List of unregistered viewers</h2>
		<input type="submit" name="Update Unreg" value="Modify Unregistered Viewer Information" 
			formmethod="get" formaction="./php/user_update_unreg_viewer.php"/>
		<?php generate_viewer_table($unreg_viewers_sql_resp)?>
		
		<p>
		<input type="submit" name="Delete Selected" value="Delete Selected"
			onclick="return confirm('Are you sure that you would like to delete these contacts?')" />
		</p>
	</form>

    <form id="landline_management_form" method='post'>
        <h2>List of Emergency landline contacts viewers</h2>
        <?php generate_landline_table($landline_sql_resp);?>
        <p>
            <input type="submit" name="Delete Selected" value="Delete Selected"
                   onclick="return confirm('Are you sure that you would like to delete these contacts?')" />
        </p>
    </form>

	<p>
	<form id="goto_add_viewer_form" action='user_add_viewer.html'>
		<input type='submit' value='Add Viewer'>
	</form>
	</p>

    <p>
    <form id="goto_add_emerg_form" action='user_add_emerg.html'>
        <input type='submit' value='Add Emergency landline contact'>
    </form>
    </p>
	
	<p>
	<form id="log_out_form" action='./php/logout.php'>
		<input type='submit' value='Log out'>
	</form>
	</p>
	

</body>
</html>