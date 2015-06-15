<?php
define('DB_USER', "eins");
define('DB_PASSWORD', "eins");
define('DB_DATABASE', "einscloud");
define('DB_SERVER', "192.168.1.59");

class DB_CONNECT {

	public $conn;

	function connect() {
		$this->conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
		if(!$this->conn) {
			die("Connection failed: " . mysqli_connect_error());
		}
	}

	function close() {
		if($this->conn) {
			mysqli_close($this->conn);
		}
	}
}

function make_query($query) {
	$connector = new DB_CONNECT ();
	$connector->connect ();
	$response = mysqli_query ( $connector->conn, $query );
	$connector->close ();
	return $response;
}

function sendSMS($text, $number){
	//Test code (to avoid sms charge). To be deleted upon actual deployment.
	make_query("insert into LogInLieuOfSMS values('{$text}', {$number})");

	/* Not employing since money costing.
	 $api_key = 'e56a6a069e9b7066794bcbe264546a95';
	 $api_secret = '123456';

	 $CallerID = 'Einscloud';


	 include'APIClient2.php';
	 $api=new transmitsmsAPI($api_key,  $api_secret);

	 // sending to a set of numbers
	 $result=$api->sendSms($text, $number, $CallerID);

	 // sending to a list
	 //$result=$api->sendSms('test', null, 'callerid', null, 6151);

	 if($result->error->code=='SUCCESS')
	 {
	 echo"Message to {$result->recipients} recipients sent with ID
	 {$result->message_id}, cost {$result->cost}\n";
	 }
	 else
	 {
	 echo"Error: {$result->error->description}";
	 }
	*/
}

$viewer_result = make_query("select * from UnregisteredViewer");



if ($viewer_result->num_rows > 0) {
	while($row = $viewer_result->fetch_assoc()) {
		$user_result = make_query("select * from User where id={$row['user_id']}");
		$user_row = $user_result->fetch_assoc();
		$username = $user_row['firstname'] . ' ' . $user_row['lastname'];
		$text = generateText($username, $row['verification_code']);
		sendSMS($text, $row['verification_code']);
	}
} else {
	echo "UnregisteredViewer is empty.";
}

function generateText($username, $verification_code){
	return "Dear Sir/Mdm, einscloud user " . $username .
	" would like to invite you to be his/her caregiver. please download the app at http://xxx" .
	" and registered with the verification code: " . $verification_code;
}
