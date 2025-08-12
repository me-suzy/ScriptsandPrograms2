<?php
$hostname = "localhost";		/* Database Hostname (usually localhost) */
$user = "username";			/* Username for database */
$pass = "password";			/* Password for database */
$database = "database";			/* Database name */
$domain = "domain";		/* Do NOT enter www. or http:// */
$directory = "/news/";			/* this is the directory location of the script ie.. /news/ be sure to add a slash at the beginning and end */



/*------ NO NEED TO EDIT BELOW ---------*/

$newstable = "news12_story";
$newsadmin = "news12_admin";
$newscomments = "news12_comments";
$newsoptions = "news12_options";
$newssmilies = "news12_smilies";
$newsfilter = "news12_filter";
$connection = @mysql_connect($hostname, $user, $pass)
or die(mysql_error());
$dbs = @mysql_select_db($database, $connection) or
die(mysql_error());
?>