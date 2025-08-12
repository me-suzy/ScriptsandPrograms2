<?php
#####################################################################
# NAME/ PURPOSE - this is a redirect page that directs users to the
#      file they have selected to download
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
$user = new user($login);
$document = new document($doc_id);

// Log access to this document.
@mysql_query("INSERT INTO documents_log(user,document,revision,date,address) VALUES($user->id,$document->id,$document->revision,NOW(),'". addslashes(gethostbyaddr(getenv("REMOTE_ADDR"))) ."')");

$debug_time_start = microtime();
	
print("<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">");
print("<html xmlns=\"http://www.w3.org/1999/xhtml\">");
print("<head>\n");
print("<title>$cfg[site_name] Document Management: $title</title>\n");
print("<meta http-equiv=\"Expires\" content=\"".gmdate("l, d F Y H:i:s")." GMT\" />\n");
print("<meta http-equiv=\"Last-Modified\" content=\"".gmdate("l, d F Y H:i:s")." GMT\" />\n");
print("<meta http-equiv=\"Cache-Control\" content=\"no-cache, must-revalidate\" />\n");
print("<meta http-equiv=\"Pragma\" content=\"no-cache\" />\n");
print("<script type=\"text/javascript\" src=\"js/confirmlogout.js\"></script>\n");
print("<link rel=\"stylesheet\" href=\"styles.css\" type=\"text/css\" />");

// Start the download in 3 seconds.
if( may_read($user->id,$document->id) )
print("<meta http-equiv=\"refresh\" content=\"3; url=file.php/$document->id/$document->name\">\n");
	
print("</head>\n");
print("<body>\n");
print("<div id=\"logged\">");

print("<strong>Logged in as ");

if($user->god){
	print("(GOD) ");
}

print("$user->name\n");
print("</strong></div>\n");
print("<div id=\"navcontainer\">");
print("<ul id=\"navlist\">");
print("<li><a href=\"main.php\">Home</a></li>\n");
print("<li><a href=\"contacts.php\">Contacts</a></li>\n");
print("<li><a href=\"message.php\">Messages</a></li>\n");
print("<li><a href=\"list.php\">List</a></li>\n");
print("<li><a href=\"up.php\">Update</a></li>\n");
print("<li><a href=\"new.php\">New</a></li>\n");

if($user->god) {
	print("<li><a href=\"users.php\">Users</a></li>\n");
	print("<li><a href=\"logs.php\">Logs</a></li>\n");
}

print("<li><a href=\"logout.php\" onclick=\"return confirm_logout()\">Logout</a></li>\n");
print("</ul></div>\n");
print("<div id=\"content\">");


print("<h1>Download Document</h1>");
if(! may_read($user->id,$document->id) ) {
	print("<h2>Permission denied</h2>\n");
} 
else {
	print("<div style=\"padding-left: 15px; padding-right: 15px;\"><h2>$document->name</h2>\n");
	print("<h3>The document will begin downloading in 3 seconds</h3>\n");
	print("<p>If the download does not start within 3 seconds, you can download the document \n");
	print("by clicking on <strong><a href=\"file.php/$document->id/$document->name\">$document->name</a></strong></p></div>\n");
}

print_footer();

?>