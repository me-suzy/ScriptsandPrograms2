<?php
#####################################################################
# NAME/ PURPOSE - this page contains the form(s) and scriptint used to
#      add, edit, and delete users.
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

print_header("Edit Users");

print("<h1>Maintain Users</h1>");

switch($button) {
	
	case "Yes, add user":
		print("<h3>Action: Add user $victim: \n");
		@mysql_query("INSERT INTO users(user,pass,name,email) VALUES('$victim',PASSWORD('$pass'),'". addslashes($name) ."','$email')");
		
		if(mysql_errno()){
			print("Error<br />". mysql_error() ."</h3>");
		}
		else{
			print("OK</h3>");
		}
	break;
	
	case "Add User":
		print("<h2>Add user $victim?</h2>\n");
		print("<div class=\"a_form\">\n");
		print("<form action=\"users.php\" method=\"post\">\n");
		print("<div><input type=\"hidden\" name=\"victim\" value=\"$victim\" /></div>\n");
		print("<div><input type=\"hidden\" name=\"pass\" value=\"$pass\" /></div>\n");
		print("<div><input type=\"hidden\" name=\"name\" value=\"$name\" /></div>\n");
		print("<div><input type=\"hidden\" name=\"email\" value=\"$email\" /></div>\n");
		print("<div><input type=\"submit\" name=\"button\" class=\"form_button\" value=\"Yes, add user\" />\n");
		print("<input type=\"submit\" name=\"button\" class=\"form_button\" value=\"Cancel\" /></div>\n");
		print("</form>\n");
		print("</div>\n");
		print_footer();
		exit;
	break;
	
	case "Delete User":
		$tmp = explode(",", $victim);
		print("<h2>Delete user $tmp[1]?</h2>\n");
		print("<div class=\"a_form\">\n");
		print("<form action=\"users.php\" method=\"post\">\n");
		print("<div><input type=\"hidden\" name=\"victim\" value=\"$victim\" /></div>\n");
		print("<div><input type=\"submit\" class=\"form_button\" name=\"button\" value=\"Yes, I am sure\" />\n");
		print("<input type=\"submit\" class=\"form_button\" name=\"button\" value=\"Cancel\" /></div>\n");
		print("</form>\n");
		print("</div>\n");
		print_footer();
		exit;
	break;
	
	case "Yes, I am sure":
		print("<h3>Action: Delete user $victim: \n");
		$tmp = explode(",", $victim);
		@mysql_query("DELETE FROM users WHERE id=$tmp[0]");
		if(mysql_errno()){
			print("Error<br />". mysql_error() ."</h3>");
		}
		else{
			print("OK</h3>");
		}
	break;
		
	default:
	break;

}

print("<div class=\"a_form\"><form action=\"users.php\" method=\"post\">\n");
print("<fieldset>");
print("<legend>Add a user</legend>\n");
print("<div class=\"form_row\"><label for=\"new_user_login\">Login:</label>\n");
print("<input type=\"text\" class=\"input_text\" name=\"victim\" id=\"new_user_login\" maxlength=\"16\" /></div>\n");
print("<div class=\"form_row\"><label for=\"new_user_pw\">Password:\n");
print("<input type=\"text\" class=\"input_text\" name=\"pass\" id=\"new_user_pw\" maxlength=\"8\" /></div>\n");
print("<div class=\"form_row\"><label for=\"new_user_name\">Name:</label>\n");
print("<input type=\"text\" class=\"input_text\" name=\"name\" id=\"new_user_name\" maxlength=\"64\" /></div>\n");
print("<div class=\"form_row\"><label for=\"new_user_email\">Email:</label>\n");
print("<input type=\"text\" class=\"input_text\" name=\"email\" id=\"new_user_email\" maxlength=\"64\" /></div>\n");
print("<div class=\"form_row\"><input type=\"submit\" class=\"form_button\" name=\"button\" value=\"Add User\" /></div>\n");
print("</form></fieldset></div>\n");

print("<div class=\"a_form\"><form action=\"users.php\" method=\"post\">\n");
print("<fieldset>");
print("<legend>Delete a user</legend>\n");
print("<div class=\"form_row\"><label for=\"del_user_name\">User:</label>\n");
print("<select name=\"victim\" id=\"del_user_name\">\n");
$res = @mysql_query("SELECT id,user,name FROM users ORDER BY name ASC");
while( $row = @mysql_fetch_array($res) ) {
	$tmp = new user($row[user]);
	if(!$tmp->god){
		printf("<option value=\"%d,%s\">%s</option>\n", $tmp->id, $tmp->name, $tmp->name );
	}
}

print("</select></div>\n");
print("<div class=\"form_row\"><input type=\"submit\" class=\"form_button\" name=\"button\" value=\"Delete User\" /></div>\n");
print("</form></fieldset></div>\n");

print_footer();

?>