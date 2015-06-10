<?php
function make_query($query) {
	require_once $_SERVER['DOCUMENT_ROOT'].'/php/DB_connect/db_connect.php'; // TODO prone to path change. Need refactor.
	$connector = new DB_CONNECT ();
	$connector->connect ();
	$response = mysqli_query ( $connector->conn, $query );
	$connector->close ();
	return $response;
}
?>