<?php 
	//start session and check for session validity
	require_once 'php/DB_connect/check_session_validity.php';
	
	function generate_viewer_table($table)
    {
        $user_id = $_SESSION ["login_id"];

        $link = get_conn();
        if($table === "registered") {
            $selectStmt = mysqli_prepare($link,
                "select fullname, phone_no, email, id from RegisteredViewer R, Caregive where user_id=? and rv_id=R.id");
        }

        if($table === "unregistered") {
            $selectStmt = mysqli_prepare($link,
                "select viewername, phone_no, email, verification_code from UnregisteredViewer where user_id=?");
        }


        $selectStmt->bind_param("i", $user_id);
        $selectStmt->execute();
        $selectStmt->store_result();


    	if ($selectStmt->num_rows > 0) {
	        echo "<table class='table table-hover'><tr>
				<th>Name</th>
				<th>Phone No.</th>
				<th>Email</th>
				<th>Delete</th></tr>";
	        /* Read viewers and filling the table */
            // output data of each row
            $row = array();
            if($table === "registered") {
                $selectStmt->bind_result($row ["fullname"], $row ["phone_no"], $row ["email"], $row ["id"]);
                while($selectStmt->fetch()) {
                    echo "<tr>";
                    echo "<td>" . $row ["fullname"] . "</td>";
                    echo "<td>" . $row ["phone_no"] . "</td>";
                    echo "<td>" . $row ["email"] . "</td>";
                    echo "<td>" . "<input type='checkbox' name='reg_viewer[]' value='" . $row ["id"] . "'>" . "</td>";
                    echo "</tr>";
                }
            }

            if($table === "unregistered") {
                $selectStmt->bind_result($row ["viewername"], $row ["phone_no"], $row ["email"],
                    $row ["verification_code"]);
                while($selectStmt->fetch()) {
                    echo "<tr>";
                    echo "<td>" . $row ["viewername"] . "</td>";
                    echo "<td>" . $row ["phone_no"] . "</td>";
                    echo "<td>" . $row ["email"] . "</td>";
                    echo "<td>" . "<input type='checkbox' name='unreg_viewer[]' value='" . $row ["verification_code"] . "'>" . "</td>";
                    echo "</tr>";
                }
            }

            $selectStmt->close();
            $link->close();

            if($table === "unregistered") {
                echo "<tr><td>";
                echo "<input type='submit' value='Modify Information' formmethod='get' formaction='user_update_unreg_viewer.php' class='btn btn-primary'/>";
                echo "</td><td></td><td></td><td></td></tr>";
            } else {
                echo "<tr><td></td><td></td><td></td><td></td></tr>";
            }

            echo "</table>";
    	} 
    	else{
    		echo '<table class="table-condensed"><tr><td>No records found.</td></tr></table>';
    	}
    }

    function generate_landline_table() {

        $user_id = $_SESSION ["login_id"];

        $link = get_conn();
        $selectStmt = mysqli_prepare($link, "select name, landline_id from CallLandline where user_id=?");
        $selectStmt->bind_param("i", $user_id);
        $selectStmt->execute();
        $selectStmt->store_result();

    	if ($selectStmt->num_rows > 0) {
	    	echo "<table class='table table-hover'><tr>
				<th>Name</th>
				<th>Phone No.</th>
				<th>Delete</th>
				</tr>";
			/* Read viewers and fillin the table */
			// output data of each row
            $row = array();
            $selectStmt->bind_result($row ['name'], $row ['landline_id']);
			while ( $selectStmt->fetch() ) {

                $link2 = get_conn();
                $selectStmt2 = mysqli_prepare($link2, "SELECT phone_no FROM LandlineContact WHERE id=?");
                $selectStmt2->bind_param("i", $row ['landline_id'] );
				if ($selectStmt2->execute()) {
                    $selectStmt2->bind_result($row['phone_no']);
					$selectStmt2->fetch();
				} else {
                    $row['phone_no'] = "error fetching phone number";
				}
                $selectStmt2->close();
                $link2->close();
				
				echo "<tr>";
				echo "<td>" . $row['name'] . "</td>";
				echo "<td>" . $row['phone_no'] . "</td>";
				echo "<td><input type='checkbox' name='landline[]' value='" . $row ['landline_id'] . "'></td>";
				echo "</tr>";
			}

            echo "<tr><td></td><td></td><td></td></tr>";

			echo "</table>";
		}
		else{
			echo '<table class="table-condensed"><tr><td>No records found.</td></tr></table>';
		}

        $selectStmt->close();
        $link->close();
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
		<div class="jumbotron well">
			<h1> Hello, <?php echo $_SESSION['login_fullname']; ?></h1>
		</div>

		<form id="viewer_management_form" method='post' action='php/user_delete_viewer.php'>

            <h2 class="page-header well well-sm">Current Caregivers</h2>

            <h3>Mobile phones</h3>
			<?php generate_viewer_table("registered")?>

			<h3>Landlines</h3>
	    
	        <?php generate_landline_table();?>

            <h2 class="page-header well well-sm">Pending Caregivers</h2>

            <?php generate_viewer_table("unregistered")?>

            <table class="table-condensed">
                <tr>
                    <td>
                        <input type="submit" name="Delete Selected" value="Delete Selected"
                               onclick="return confirm('Are you sure that you would like to delete these contacts?')"
                               class="btn btn-warning" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type='submit' value='Add Caregiver (mobile phone)' class="btn btn-primary" formmethod="get" formaction='user_add_viewer.php'/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type='submit' value='Add Caregiver (landline)' class="btn btn-primary" formmethod="get" formaction='user_add_emerg.php'/>
                    </td>
                </tr>
            </table>

            <br>

            <table class="table-condensed">
                <tr>
                    <td>
                        <input type='submit' value='Edit profile' class="btn btn-success" formmethod="get" formaction='user_edit_profile.php'/>
                    </td>
                </tr>
                <tr>
                    <td>
                       <input type='submit' value='Log out' class="btn btn-danger" formmethod="get" formaction='php/logout.php'/>
                    </td>
                </tr>
            </table>
		</form>
	</div>
</body>
</html>