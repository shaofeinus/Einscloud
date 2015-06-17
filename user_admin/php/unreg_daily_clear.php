<?php
require_once 'DB_connect/db_utility.php';

$sql_resp = make_query ( "select * from UnregisteredViewer" );

if ($sql_resp->num_rows > 0) {
	while($row = $sql_resp->fetch_assoc()) {
		if($row['notifications_left'] <= 0)
			make_query ( "delete from UnregisteredViewer where 
					verification_code={$row['verification_code']} and user_id={$row['user_id']}" );
	}
} else {
	echo "The UnregisteredViewer table is emtpy";
}