<?php
 

  
	if ($ref) 
	
{		
SetCookie ("ref","$ref"); 
	
	
	{
	session_start();
	session_register("ref");
	}

	
	include "config.php";   
{ 
mysql_connect($server, $db_user, $db_pass) or die ("Database CONNECT Error (line 17)"); 
mysql_db_query($database, "INSERT INTO clickthroughs VALUES ('$ref', '$clientdate', '$clienttime', '$clientbrowser', '$clientip', '$clienturl', '')") or die ("Database INSERT Error (line 18)"); 
  
} 
 	
} 

 
?>