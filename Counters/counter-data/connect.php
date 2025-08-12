<?php
/*********************************************************
			This Free Script was downloaded at			
			Free-php-Scripts.net (HelpPHP.net)			
	This script is produced under the LGPL license		
		Which is included with your download.			
	Not like you are going to read it, but it mostly	
	States that you are free to do whatever you want	
				With this script!						
*********************************************************/

define ('DB_USER', 'root');   // Database User Name
define ('DB_PASSWORD', '');    // Database User Password
define ('DB_HOST', 'localhost');   // Host Name (mostly localhost)
$dbc = mysql_connect (DB_HOST, DB_USER, DB_PASSWORD);  // Establishes connection
mysql_select_db('fps_pass');    // database name to connect to
			
?>