<?php
#####################################################################
# NAME/ PURPOSE - this file processes user attempts at logging into
#      the system. It sets up the session if login successful or
#      redirects them if login has failed
#
# STATUS - Done
#
# LAST MODIFIED - 02/11/2005
#
# TO DO - nothing. done.
#
# NOTE: Due to the nature of this program being an open-source project,
#       refer to the project website https://sourceforge.net/projects/gssdms/
#		for the most current status on this project and all files within it
#
#####################################################################


#####################################################################
#
# KLG - added $e variable at the end of query string to represent
# the error which will be sending the user back to the login screen
# if they've entered an incorrect username or password
#
#####################################################################

require('lib/config.inc');

@mysql_connect("$cfg[server]", "$cfg[user]", "$cfg[pass]") or die("Unable to connect to SQL Server.");
@mysql_select_db("$cfg[db]") or die("Unable to select database '$cfg[db]'");

$result = @mysql_query("SELECT id FROM users WHERE user='$login'");

// if no match in the database for the username they've entered, send them back to login again
if( mysql_num_rows($result) != 1 ) {
	header("Location: index.php?e=1"); 
	exit;
}

$result = @mysql_query("SELECT pass != PASSWORD('$pass') FROM users WHERE user='$login'");
$row = @mysql_fetch_array($result);

// if the password supplied doesn't match the password for this username, send them back to login again
if( $row[0] != 0 ) {
	header("Location: index.php?e=2");
	exit;
}

// otherwise, let's move on and start the session
$result = @mysql_query("SELECT id,name FROM users WHERE user='$login'");
$row = @mysql_fetch_array($result);
$id = $row[id];
$name = $row[name];

require('lib/session.inc');
session_register("id");
session_register("login");

// direct them into the system
header("Location: main.php");

?>