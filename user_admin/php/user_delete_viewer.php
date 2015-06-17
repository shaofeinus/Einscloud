<?php
// import sql utility functions.
require_once 'DB_connect/db_utility.php';

//Code to delete selected Viewers (This script process its own POST submission.)
if ( $_SERVER["REQUEST_METHOD"] == "POST") {
	if (! empty ( $_POST ['reg_viewer'] )) {
		foreach ( $_POST ['reg_viewer'] as $viewer_id ) {
			make_query ( "delete from Caregive where rv_id='$viewer_id' and user_id='$user_id'" );
		}
	}

	if (! empty ( $_POST ['unreg_viewer'] )) {
		foreach ( $_POST ['unreg_viewer'] as $vr_code ) {
			make_query ( "delete from UnregisteredViewer where verification_code='$vr_code'" );
		}
	}

	if (! empty ( $_POST ['landline'] )) {
		foreach ( $_POST ['landline'] as $landline_id ) {
			make_query ( "delete from CallLandline where landline_id=$landline_id AND user_id=$user_id" );
		}
	}
}

header("Location: ../user_admin_index.php");