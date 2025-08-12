<?php 
///////////////////////////////
//Modify the MySql access info:


$so_location = "localhost";       //Database host (usually localhost) 
$so_username = "DatabaseUsername";        //Database username
$so_password = "DatabasePassword";        //Database password
$so_database = "DatabaseName";       //Database name
$so_prefix = "sayop";        //Prefix of the SayOp MySql tables (useful for multiple installations)


///////////////////////
//Do not modify below:
$conn = mysql_connect("$so_location","$so_username","$so_password"); 
if (!$conn) die("Could not connect >>> " . mysql_error());
mysql_select_db($so_database,$conn) or die("Could not open database >>> " . mysql_error()); 
?>