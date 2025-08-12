<?php
#####################################################################
# NAME/ PURPOSE - this page displays a list of users in the system
#       as well as a link to more details (on userdetail.php) and a
#       link to their e-mail address.
#
# STATUS - Done
#
# LAST MODIFIED - 02/11/2005
#
# TO DO - nothing. done
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

print_header("Contacts List");

print("<h1>Contact List</h1>\n");

print("<table id=\"contact_list\">");
print("<tr>\n");
print("<th scope=\"col\">Name</th>\n");
print("<th scope=\"col\">Email</th>\n");
print("</tr>\n");

$res = @mysql_query("SELECT user,name,email FROM users ORDER BY name ASC");
while( $row = @mysql_fetch_array($res) ){
	print("<tr><td><a href=\"userdetail.php?contact=$row[user]\">$row[name]</a></td><td>&lt;<a href=\"mailto:$row[email]\">$row[email]</a>&gt;</td>\n</tr>\n");
}

print("</table>");

print("<div><a href=\"users.php\"><button>Add A New User</button></a></div>");

print_footer();
?>