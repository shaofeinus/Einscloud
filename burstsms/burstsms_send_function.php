<?php
function sendSMS($text, $number){
	//Test code (to avoid sms charge). To be deleted upon actual deployment.
	require_once __DIR__.'/../user_admin/php/DB_connect/db_utility.php';
	make_query("insert into LogInLieuOfSMS values('{$text}', {$number})");
	/*
	// Not employing since money costing.
	$api_key = '1c4365b5168a69ef4891b73ecd255540';
	$api_secret = 'einstech';

	$CallerID = 'Einscloud';
	$number = '65'.$number;
	

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