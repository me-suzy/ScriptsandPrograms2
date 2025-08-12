<?php // conf.php

//!!!edit below here!!!

$dbhost = "localhost"; //mysql host, usually localhost.
$dbuser = "anyone"; //mysql username
$dbpass = "secretpass"; //mysql password
$dbname = "myforum"; //the database your forum uses.
$forumurl = "http://www.mysite.com/forum/"; //remember the trailing slash! Enter in the format Http://www.mysite.com/forum/ only.
$allowedgroup = "Members"; //the name of the usergroup which is allowed. The admin group is allowed by default, so do not enter that here.

//!!!stop editing here!!!

$forumurl = $forumurl."index.php?act=Reg&CODE=10";

function dbConnect($db="") {
    global $dbhost, $dbuser, $dbpass;
	
	$dbcnx = @mysql_connect($dbhost, $dbuser, $dbpass)
		or die("The site database appears to be down.");
    
	if ($db!="" and !@mysql_select_db($db))        
		die("The site database is unavailable.");
		
	return $dbcnx;
}
?>