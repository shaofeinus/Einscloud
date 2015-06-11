<?php
/**
 * Created by PhpStorm.
 * User: CK
 * Date: 11/6/2015
 * Time: 11:49 AM
 */
session_start();
?>
<html>
<?php
$viewer_id = $_SESSION['viewer_id'];
$oldPassword = md5($_POST['oldPassword']);
$newPassword = md5($_POST['newPassword']);
//echo $viewer_id;
require_once __DIR__.'/DB_connect/db_utility.php';

$selectQuery = "select * from RegisteredViewer where id = '$viewer_id' and password = '$oldPassword'";
$selectResponse = make_query($selectQuery);
if(mysqli_num_rows($selectResponse) > 0) {
    $query = "update RegisteredViewer set password = '$newPassword' where id = '$viewer_id' and password = '$oldPassword'";
    //print $query;
    $updateResponse = make_query($query);

    if ($updateResponse === FALSE) {
        echo "response is erroneous";
        die(mysql_error());
    }
    echo "Password Change Successful";

}
else {
    echo "Wrong password";
}
    ?>
    <form id="edit_form" action='../viewer_admin_index.php'>
        <input type='submit' value='Go back to Viewer Admin Page'>
    </form>
</html>