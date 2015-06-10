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


</body>
</html>