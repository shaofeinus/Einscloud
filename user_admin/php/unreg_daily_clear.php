<?php
require_once 'DB_connect/db_utility.php';
$link = get_conn();
$selectStmt = mysqli_prepare($link, "select  verification_code, user_id, notificationsLeft from UnregisteredViewer");
$selectStmt->execute();
$selectStmt->bind_result($verificationCode, $userId, $notificationsLeft);
$link->close();

if ($selectStmt->num_rows > 0) {

	$count_total = 0;
	$count_deleted = 0;

	while($selectStmt->fetch()) {

		if($notificationsLeft <= 0){

            $link = get_conn();
            $deleteStmt = mysqli_prepare($link, "delete from UnregisteredViewer where
					verification_code=? and user_id=?");
            $deleteStmt->bind_param("si", $verificationCode, $userId);
            $deleteStmt->execute();
            $link->close();

			$count_deleted++;
		}
		$count_total++;
	}
	echo "There were {$count_total} unreg viewers in table; {$count_deleted} were deleted";
} else {
	echo "The UnregisteredViewer table is emtpy";
}