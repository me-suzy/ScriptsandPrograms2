<?php

// The variable below is the only one you should change.
// Create copies of this file for each different reward ie. check2.php for £5, check3.php for £4 etc

$payment = "5.00";

// Do not edit anything below this line



include "config.php"; 

$ref = $HTTP_COOKIE_VARS["ref"];
 
	if (!$ref)	

	{
		$ref = $HTTP_SESSION_VARS["ref"]; 
	}


	if (!$ref)
		{
		exit;
		}

	else  
{
	
	{
	mysql_connect($server, $db_user, $db_pass) or die ("Database CONNECT Error (line 32)"); 
	
	mysql_db_query($database, "INSERT INTO sales VALUES ('$ref', '$clientdate', '$clienttime', '$clientbrowser', '$clientip', '$payment')") or die("Database INSERT Error (line 34)"); 
	}
 
}	
?>