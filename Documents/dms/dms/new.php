<?php
#####################################################################
# NAME/ PURPOSE - this is the page used to upload a new document
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

$user = new user($login);

if(! $user->god) {
	print_header("Access Denied!");
	print("<h1>Access Denied</h1>");
	print("<p>You are not allowed to access this resource.</p>");
	print_footer();
	exit;
}

print_header("Upload a new document");

print("<h1>Upload new document (Max 16 Mb)</h1>\n");
print("<div><form action=\"upload.php\" method=\"post\" enctype=\"multipart/form-data\">\n");
print("<div><input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"16777216\"></div>\n");

print("<table class=\"form_table\">");

print("<tr><td>File:</td>\n");
print("<td><input type=\"file\" class=\"input_text\" name=\"userfile\"></td></tr>");

print("<tr class=\"ul_row\"><td colspan=\"2\">Click browse to select a file</td></tr>\n");

print("<tr><td>Access:</td>\n");
print("<td><select name=\"level\">\n");
print("<option value=\"X\">Everybody: No Access</option>\n");
print("<option value=\"R\">Everybody: Read-Only</option>\n");
print("<option value=\"W\">Everybody: Read-Write</option>\n");

if($user->god){
	print("<option value=\"G\">Everybody: God Mode</option>\n");
}

print("</select></td></tr>");

print("<tr class=\"ul_row\"><td colspan=\"2\">Select the default access level for normal users</td></tr>\n");

print("<tr><td>Keywords:</td>\n");
print("<td><input type=\"text\" class=\"input_text\" maxlength=\"512\" name=\"keywords\"></td></tr>");
print("<tr class=\"ul_row\"><td colspan=\"2\">Enter keywords delimited by spaces or commas</td></tr>\n");

print("<tr><td>Info:</td>\n");
print("<td><textarea name=\"info\" rows=\"10\" cols=\"40\"></textarea></td>");
print("<tr class=\"ul_row\"><td colspan=\"2\">Enter a short comment describing this document</td></tr>\n");

print("<tr><td></td><td><input type=\"submit\" class=\"form_button\" value=\"Upload this document\"></td></tr>\n");

print("</table>");

print("</form></div>\n");

?>