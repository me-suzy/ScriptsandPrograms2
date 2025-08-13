<?
include "inc/config.php";
	$extra = "From: $youremail\r\n";
	$recipient = "$clientemail";
	$subject = "From $yourtitle - invoice notification.";
	
	mail ($recipient, $subject, $message, $extra);
	
?>