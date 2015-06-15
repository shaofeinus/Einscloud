<?php
function sendSMS($text, $number){
	//Test code (to avoid sms charge). To be deleted upon actual deployment.
	$myfile = fopen("OutputInLieuOfSMS.log", "w") or die("Unable to open file!");
	$txt = "This log: " . $text . " " . $number . "\n";
	fwrite($myfile, $txt);
	fclose($myfile);
	
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