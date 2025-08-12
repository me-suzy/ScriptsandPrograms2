<?php 

	//************************************************************************************
	// This include goes in the /PHPJK/SendECard/index.php page
	//************************************************************************************
	
	// ECard email
	// 		the 1: is replaced with the senders name
	//		the 2: is replaced with the current domain name (eg: "www.phpjk.com")
	//		the 3: is replaced with the card id number (CardUnq)
	//		the \n should appear as a line break in the email
	$CONF_ECardEmail = "1: has sent you an E-Card!\n\n";
	$CONF_ECardEmail .= "Either click this <a href='2:/EC.php?iCardUnq=3:' target='_blank'>link</a> to view your card, ";
	$CONF_ECardEmail .= "or copy and paste this URL into your browser: 2:/EC.php?iCardUnq=3:\n\n";
?>