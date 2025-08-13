<?
include "inc/config.php";
	$message = "This message is to confirm receipt of your payment of invoice #$invoiceid. \n Many thanks, \n\n $yourtitle";
	$extra = "From: $youremail\r\n";
	$recipient = "$clientemail";
	$subject = "From $yourtitle - confirmation of paid invoice.";
	
	mail ($recipient, $subject, $message, $extra);
	
?>