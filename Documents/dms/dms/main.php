<?php
#####################################################################
# NAME/ PURPOSE - this is the 'home' page for users who have logged in
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

print_header("Main");

print("<div>\n");
    
print("<h1>$cgf[site_name] Document Managment System</h1>");
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Original version had only this image displayed, with no mention of what you do once you get into the system
//echo "<img src=\"pix/home.gif\" height=\"250\" width=\"500\" alt=\"[ $cfg[site_name] Document Management ]\">\n";
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
print("<p>Select from the links above to view or maintain documents.</p>");    

print("</div>\n");

print_footer();
?>