<?php
if(isset($_COOKIE['user_session_id'])){
	session_id($_COOKIE['user_session_id']);
}
session_start();

//check if the user has already logged in and the session is still valid,
//direct to admin page.
if(isset($_SESSION['login_id'])){
	require_once "php/DB_connect/db_utility.php";
	$resp = make_query("select * from User where id={$_SESSION['login_id']}");
	$id_in_database;
	if (mysqli_num_rows($resp) == 1) {
		$row = mysqli_fetch_assoc($resp);
		$id_in_database = $row['session_id'];
	} else {
		//if the sql query does not return exactly 1 row of result, something's wrong.
		//TODO: Maybe should change this to writing to a console log file or something.
		die('A query with a user-id from the User table returned a non-1-row result.');
	}
	
	if($id_in_database == session_id()){
		if(isset($_SESSION['last_activity']) && time() - $_SESSION['last_activity'] <= 1800){
			header("Location: user_admin_index.php");
		}
	}
}
?>
<!DOCTYPE html>
<html>
<head lang="en">
    <script src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.11.3.min.js"></script>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Einswatch User admin</title>
</head>

<body>
<script src="script/user_login_validation_script.js?v=1"></script>
<div class="container">
    <div class="jumbotron well">
        <h1>Welcome Einswatch User</h1>
    </div>
    <div class="page-header">
        <h3>Existing Einswatch Users</h3>
    </div>
</div>

<div class="container">
    <table class="">
        <form id="user_login_form" action='php/user_login.php' method='post' onsubmit="return isFormValid()">

            <tr>
                <td class="form_td">Username:</td>
            </tr>

            <tr>
                <td class="form_td"><input type='text' name='username' oninput="validateUsername()" required></td>
                <td class="form_td"><div class='alert-info' id='username_feedback'></div></td>
            </tr>

            <tr>
                <td class="form_td">6 digit password:</td>
            </tr>
            <tr>
                <td class="form_td"><p><input type='password' name='password' oninput="validatePassword()" required></p></td>
                <td class="form_td"><div class='alert-info' id='password_feedback'></div></td>
            </tr>

            <tr>
                <td><input type='submit' class="btn btn-success" name='loginUser' value='Log in'></td>
            </tr>
        </form>
    </table>
</div>
<div class="container">
    <div class = page-header>
        <h3>New Einswatch Users</h3>

        <table>
            <?php
                $str = 'user_registration.html?' . time();

            echo '<tr><td><p><form id="goto_register_form" action="' . $str . '">
                        <input type="submit" class="btn btn-primary" value="Register">
                    </form></p></td></tr>';

			//if the user appears to be a mobile user (assumed to be accessing from an app), the "go back to home page“ button is not shown.
            $useragent=$_SERVER['HTTP_USER_AGENT'];
            if(!preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))){
                ?>
                <tr><td><form id="" action='../index.html'>
                            <input type='submit' class="btn btn-primary" value='Go back to Home Page'>
                        </form></td></tr>

            <?php
            }
            ?>
        </table>
    </div></div>
</body>
</html>
