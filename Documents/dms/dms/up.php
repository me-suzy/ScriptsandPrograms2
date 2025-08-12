<?php
#####################################################################
# NAME/ PURPOSE - this page is used to update documents
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

print_header("Update a document");

print("<h1>Update a document (Max 16 Mb)</h1>\n");

print("<div><form action=\"update.php\" method=\"post\" enctype=\"multipart/form-data\">\n");

print("<table class=\"form_table\">");

print("<tr><td></td><td><input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"16777216\"></td></tr>\n");

print("<tr class=\"ul_row\"><td>Document:</td>\n");

print("<td><select name=\"doc_id\">\n");

if($user->god){
	$res = @mysql_query("SELECT d.id AS id,d.name AS name,u.name AS user FROM documents AS d LEFT JOIN users AS u on d.author=u.id ORDER BY name ASC");
}
else{
	$res = @mysql_query("SELECT d.id AS id,d.name AS name,a.level AS level FROM documents AS d LEFT JOIN ACL AS a ON a.document_id=d.id WHERE a.user_id=$user->id AND (a.level='W' OR a.level='G') ORDER BY name ASC");
}

if(!mysql_num_rows($res)) {
	print("<option selected>You cannot update any documents</option>\n");
}
else {
	while( $row = @mysql_fetch_array($res)) {
		if($user->god){
			print("<option value=\"$row[id]\">$row[name] &nbsp;&nbsp; [$row[user]]</option>\n");
		}
		else{
			print("<option value=\"$row[id]\">$row[name]</option>\n");
		}
	}
}

print("</select></td></tr>\n");

print("<tr><td>Update With New Document:<br />\n");

print("<td><input type=\"file\" class=\"input_text\" name=\"userfile\"></td></tr>");

print("<tr class=\"ul_row\"><td colspan=\"2\">Click browse to select a file or leave blank to keep the same file.</td></tr>\n");

print("<tr><td>Keywords:</td>\n");
print("<td><input type=\"text\" class=\"input_text\" maxlength=\"512\" name=\"keywords\"></td></tr>");

print("<tr class=\"ul_row\"><td colspan=\"2\">Enter keywords delimited by spaces or commas or leave blank to keep originals</td></tr>\n");

print("<tr><td>Description:</td>\n");
print("<td><textarea name=\"info\" rows=\"15\" cols=\"40\"></textarea></td></tr>");

print("<tr class=\"ul_row\"><td colspan=\"2\">Enter a short comment describing this document or leave blank to keep original</td></tr>\n");

print("<tr><td></td><td><input type=\"submit\" class=\"form_button\" value=\"Update this document\"></td></tr>\n");

print("</table>");

print("</form></div>\n");

print_footer();
?>