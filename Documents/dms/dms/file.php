<?php
#####################################################################
# NAME/ PURPOSE - this file is used to download the file the user
#      has selected.
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

require('lib/config.inc');
require('lib/auth.inc');
require('lib/classes.inc');
require('lib/functions.inc');

global $cfg;

$tmp = explode("/", $REQUEST_URI);
$doc_id = $tmp[sizeof($tmp)-2];

@mysql_connect($cfg[server], $cfg[user], $cfg[pass]) or die("Unable to connect to MySQL server");
@mysql_select_db($cfg[db]) or die("Unable to select $cfg[db] database");

$user = new user($login);

if( may_read($user->id,$doc_id) ) {
	$res = @mysql_query("SELECT d.id AS id,d.name AS name,d.type AS type,d.size AS size,c.content AS content FROM documents AS d LEFT JOIN documents_content AS c ON d.id=c.id WHERE d.id=$doc_id");
	$row = @mysql_fetch_array($res);
	header("Content-Type: $row[type]");
	print("". base64_decode($row[content]) ."");
	exit;
}

print_header("Permission Denied");
print("<h1>Permission denied</h1>\n");
print("<p>You are not permitted to access this file.</p>");
print_footer();
?>