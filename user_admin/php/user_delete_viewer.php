<?php
//start session and check for session validity
require_once 'DB_connect/check_session_validity.php';
require_once 'DB_connect/db_utility.php';

$user_id = $_SESSION ["login_id"];


if ( $_SERVER["REQUEST_METHOD"] == "POST") {
	if (! empty ( $_POST ['reg_viewer'] )) {
		foreach ( $_POST ['reg_viewer'] as $viewer_id ) {
            $link = get_conn();
            $deleteStmt = mysqli_prepare($link, "delete from Caregive where rv_id=? and user_id=?");
            $deleteStmt->bind_param("ii", $viewer_id, $user_id);
            $deleteStmt->execute();
            $deleteStmt->close();
            $link->close();

		}
	}

	if (! empty ( $_POST ['unreg_viewer'] )) {
		foreach ( $_POST ['unreg_viewer'] as $vr_code ) {
            $link = get_conn();
            $deleteStmt = mysqli_prepare($link, "delete from UnregisteredViewer where  verification_code=?");
            $deleteStmt->bind_param("s", $vr_code);
            $deleteStmt->execute();
            $deleteStmt->close();
            $link->close();
		}
	}

	if (! empty ( $_POST ['landline'] )) {
		foreach ( $_POST ['landline'] as $landline_id ) {
            $link = get_conn();
            $deleteStmt = mysqli_prepare($link, "delete from CallLandline where landline_id=? AND user_id=?");
            $deleteStmt->bind_param("ii", $landline_id, $user_id);
            $deleteStmt->execute();
            $deleteStmt->close();
            $link->close();
		}
	}
}

header("Location: ../user_admin_index.php");