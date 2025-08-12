<?php
/***************************************\
|					|
|	    100janCMS v1.01		|
|    ------------------------------	|
|    Supplied & Nulled by GTT '2004	|
|					|
\***************************************/
//database connection
$db_username="100jan";
$db_password="mamara";
$db_database="100jancms";
$db_host="localhost";
$db_table_prefix="100jancms_";

	mysql_connect($db_host,$db_username,$db_password);
	@mysql_select_db($db_database) or die( "Unable to connect to database.");

?>