<?php
#####################################################################
# NAME/ PURPOSE - this page is used to set up access control for files
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
$document = new document($doc_id);

if( !may_god($user->id, $document->id) ) {
	print_header("Permission Denied");
	print("<h2>Permission denied</h2>\n");
	print("<p>You are not allowed to access this resource.</p>");
	print_footer();
	exit;
}

print_header("Edit Access Control List");

print("<h1>Access control</h1>");

print("<h2>Edit Access Control Level for $document->name</h2>\n");

if(isset($button)) {
	if( $level == "X" ) {
		@mysql_query("DELETE FROM ACL WHERE document_id=$document->id AND user_id=$user_id");
	}
	else {
		@mysql_query("INSERT INTO ACL(document_id,user_id,level) VALUES($document->id,$user_id,'$level')");
		if(mysql_errno() == 1062){
			@mysql_query("UPDATE  ACL SET level='$level' WHERE user_id=$user_id AND document_id=$document->id");
		}
	}
	if(mysql_errno()) {
		print("<h3>Update failed<br>". mysql_error() ."</h3>\n");
	}
	else {
		print("<h3>Update succeeded; new level active</h3>\n");
	}
}

print("<div class=\"a_form\"><form action=\"acl.php\" method=\"post\">\n");
print("<div><input type=\"hidden\" name=\"doc_id\" value=\"$document->id\" /></div>\n");
print("<div style=\"margin-bottom: 10px;\"><strong>User:</strong>\n");
print("<select name=\"user_id\">\n");

$res = @mysql_query("SELECT id,name FROM users ORDER BY name ASC");
while($row = @mysql_fetch_array($res)){
	printf("<option value=\"%d\"%s>%s (%s)</option>\n"
          ,$row[id]
          ,($row[id] == $user_id) ? "selected" : "" 
          ,$row[name]
          ,access_string(get_access($row[id],$document->id))
    );
}

print("</select></div>\n");
print("<div><fieldset><legend>New level:</legend>\n");
print("<div><img src=\"pix/X.gif\" height=\"15\" width=\"15\" alt=\"[ ". access_string("X") ."]\"><input type=\"radio\" name=\"level\" value=\"X\">No Access</div>\n");
print("<div><img src=\"pix/R.gif\" height=\"15\" width=\"15\" alt=\"[ ". access_string("R") ."]\"><input type=\"radio\" name=\"level\" value=\"R\">Read-Only</div>\n");
print("<div><img src=\"pix/W.gif\" height=\"15\" width=\"15\" alt=\"[ ". access_string("W") ."]\"><input type=\"radio\" name=\"level\" value=\"W\">Read/Write</div>\n");
print("<div><img src=\"pix/G.gif\" height=\"15\" width=\"15\" alt=\"[ ". access_string("G") ."]\"><input type=\"radio\" name=\"level\" value=\"G\">God Mode</div>\n");
print("</fieldset></div>\n");

print("<div><input type=\"submit\" class=\"form_button\" name=\"button\" value=\"Update Access Level\"></div>\n");
print("</form></div>\n");

print_footer();
?>