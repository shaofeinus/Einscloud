<?php
function make_query($query) {
	require_once 'db_connect.php';
	$connector = new DB_CONNECT ();
	$connector->connect ();
	$response = mysqli_query ( $connector->conn, $query );
	$connector->close ();
	return $response;
}

function get_conn(){
    require_once 'db_connect.php';
    $connector = new DB_CONNECT();
    return $connector->connect();
}

function close_conn($conn){
    require_once 'db_connect.php';
    $conn.close();
}
?>