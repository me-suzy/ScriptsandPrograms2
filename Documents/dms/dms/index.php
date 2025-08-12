<?php
#####################################################################
# NAME/ PURPOSE - this is the index page for the system. it displays
#      a login form for 
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
// require('lib/auth.inc'); (commented out, because authentication not required for  this page)
require('lib/classes.inc');
require('lib/functions.inc');

// $user = new user($login);(commented out, because authentication not required for  this page)

print_login_header("Login");

print("<div>\n");

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// KLG - Original version had this image on here with no info on how to get in, and/ or what to do here
//echo "<img src=\"pix/home.gif\" height=\"250\" width=\"500\" alt=\"[ $cfg[site_name] Document Management ]\">\n";
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

print("<h1>$cfg[site_name] Document Management System</h1>");

if(!isset($xo)){
	print("<p>Please Login To Continue</p>");
}
if ($xo==1){
	print("<p class=\"alert\">You have logged out of the system.</p>");
}

if(isset($_REQUEST['e'])){
	print("<div id=\"err_mess\">");
	print("Login details missing or incorrect. Please correct the following:<ul id=\"err_list\">");	
	
	if($_REQUEST['e']==1){
		print ("<li>No match in the database for the username you've entered</li>");
	}
	if($_REQUEST['e']==2){
		print ("<li>Password entered does not match username</li>");
	}
	print("</ul></div>");
}

print("<div id=\"login_form\">");
print("<form action=\"login.php\" method=\"post\">\n");
print("<div class=\"form_row\"><label for=\"login_username\" class=\"label\">Username:</label>\n");
print("<input type=\"text\" class=\"login_input\" id=\"login_username\" name=\"login\" /></div>\n");
print("<div class=\"form_row\"><label for=\"login_password\"  class=\"label\">Password:</label>\n");
print("<input type=\"password\" class=\"login_input\" id=\"login_password\" name=\"pass\" /></div>\n");
print("<div class=\"form_row\"><input type=\"Submit\" class=\"form_button\" value=\"Login\" /></div>\n");
print("</form>\n");
print("</div>\n");
print("</div>\n");

print_login_footer();
?>