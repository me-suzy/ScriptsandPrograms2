<?
parse_str("$QUERY_STRING");

$db = mysql_connect("localhost", "chipmunk_chipmun", "chip1549") or die("Could not connect.");
if(!$db) 
	die("no db");
if(!mysql_select_db("chipmunk_ctest",$db))
 	die("No database selected.");
?>