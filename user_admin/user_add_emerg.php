<?php 
//start session and check for session validity
require_once 'php/DB_connect/check_session_validity.php';
?>

<!DOCTYPE html>
<html>
<head lang="en">
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="stylesheet"
		href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" />
	<script
		src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script
		src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>

	<script src="script/user_add_emerg_script.js?v=1"></script>

    <style>
        tr.spaceUnder > td{
            padding-bottom: 1em;
        }
    </style>

	<title>Einswatch add Emergency landline contacts</title>
</head>

<body><div class="container">

	<script>
		verifyLogin();
		displayDropMenu();
		getName();
	</script>

	<div class="jumbotron well"><h1>
		Welcome,
		<var id="user_real_name"></var>
	</h1></div>

	<table class="form_table">
		<tr>
			<td class="form_td">
				<form id="drop_menu" name="select_no_of_emerg_form"></form>
			</td>
		</tr>

		<tr>
			<td class="form_td">
				<form id="add_emerg_form" name="add_emerg_form"
					action="php/user_add_new_emerg.php" method="post"
					onsubmit="return validateForm()"></form>
			</td>
		</tr>

        <tr class='spaceUnder'><td></td></tr>

		<tr class='spaceUnder'>
			<td class="form_td">
				<form action="user_admin_index.php">
					<input type="submit" value="Back to User admin console" class="btn btn-primary"/>
				</form>
			</td>
		</tr>

		<tr>
			<td class="form_td">
				<form action='php/logout.php'>
					<input type="submit" value="Log out" class="btn btn-danger"/>
				</form>
			</td>
		</tr>
	</table>
</div></body>
</html>