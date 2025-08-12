<?php

function send_mail  ($to_address,  $from_address,  $subject,  $message, $type, $on_switch)  {

	if ($type == "html") {

		$headers = "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=us-ascii\r\n";
		$headers .= "Content-Transfer-Encoding: 7bit\r\n";
		$headers .= "From: $from_address\r\n";
		$headers .= "Reply-To: $from_address\r\n";
	} else {
	
		$headers = "From: $from_address\r\n";
		$headers .= "Reply-To: $from_address\r\n";
	
	
	}


	if ($on_switch=='ON') 
		mail($to_address,$subject,$message,$headers);
}


?>