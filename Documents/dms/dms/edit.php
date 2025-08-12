<?php
#####################################################################
# NAME/ PURPOSE - this page is used to edit details for files on the system
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
$document = new document($doc_id);

if( !may_god($user->id, $document->id) ) {
	print_header("Permission Denied");
	print("<h1>Permission denied</h1>\n");
	print("<p>You are not allowed to access this resource.</p>");
	print_footer();
	exit;
}

$tmpauthor = $document->author;
$tmpmaintainer = $document->maintainer;

if(isset($button)) {
    print_header("Save Document Information");
    if($document->name != $name) {
        if( get_extension($name) != get_extension($document->name))
            $name .= ".". get_extension($document->name);
        @mysql_query("UPDATE documents SET name='$name' WHERE id=$document->id");
    }
    if($document->revision != $revision) @mysql_query("UPDATE documents SET revision=$revision WHERE id=$document->id");
    if($tmpauthor->id != $author) @mysql_query("UPDATE documents SET author=$author WHERE id=$document->id");
    if($tmpmaintainer->id != $maintainer) @mysql_query("UPDATE documents SET maintainer=$maintainer WHERE id=$document->id");
    if($document->info == NULL)
        @mysql_query("INSERT INTO documents_info(id,info) VALUES($document->id,'". addslashes($info) ."')");
    else
        @mysql_query("UPDATE documents_info SET info='". addslashes($info) ."' WHERE id=$document->id");
} 
else{
    print_header("Edit Document Information");
}

// Reload updated information.
$document = new document($doc_id);
$author = $document->author;
$maintainer = $document->maintainer;

if(isset($button)){
	print("<h2>Saved details for $document->name</h2>\n");
}
else{
	print("<h2>Edit details for $document->name</h2>\n");
}

print("<div><form action=\"edit.php\" method=\"post\">\n");
print("<div><input type=\"hidden\" name=\"doc_id\" value=\"$document->id\" /></div>\n");

//print("<div><img src=\"pix/". get_extension($document->name) ."-l.gif\" height=\"32\" width=\"32\" alt=\"[". strtoupper(get_extension($document->name)) ."]\" /></div>\n");

print("<table class=\"form_table\">");

print("<tr class=\"ul_row\"><td><label for=\"file_desc\">Description:</label></td><td><textarea name=\"info\" id=\"file_desc\" rows=\"10\" cols=\"40\">". htmlspecialchars(stripslashes($document->info)) ."</textarea></td></tr>\n");
    
print("<tr class=\"ul_row\"><td>Keywords:</td>\n");
print("<td>");
$document->print_keywords();
print("</td></tr>");


print("<tr><td>File name:</td>\n");
print("<td><input type=\"text\" class=\"input_text\" name=\"name\" value=\"$document->name\" /></td></tr>");
print("<tr class=\"ul_row\"><td colspan=\"2\">Note: The file extension .". strtoupper(get_extension($document->name)) ." will remain in place regardless of changes to it here</td></tr>\n");
 
print("<tr class=\"ul_row\"><td>File size:</td>\n");
$seconds = ($document->size/7000) % 60;

if($seconds<10){
        $seconds = "0$seconds";
}
$minutes = number_format((($document->size/7000) - $seconds)/60, "0", "","");
print("<td>". number_format($document->size, 0, ".", ",") ." bytes (About $minutes:$seconds @ 56K)\n</td></tr>");
 
print("<tr class=\"ul_row\"><td>Mime type:</td>\n");
print("<td> $document->type</td></tr>\n");

print("<tr class=\"ul_row\"><td>Author:</td>\n");
print("<td><select name=\"author\">\n");
$res = @mysql_query("SELECT id,name,email FROM users ORDER BY id ASC");
while($row = @mysql_fetch_array($res)){
	printf("<option value=\"$row[id]\"%s>$row[name] &lt;$row[email]&gt;</option>\n", ($row[id] == $author->id) ? " selected" : "" );
}

print("</select></td></tr>\n");

print("<tr class=\"ul_row\"><td>Maintainer:</td>\n");
print("<td><select name=\"maintainer\">\n");
$res = @mysql_query("SELECT id,name,email FROM users ORDER BY id ASC");
while($row = @mysql_fetch_array($res)){
	printf("<option value=\"$row[id]\"%s>$row[name] &lt;$row[email]&gt;</option>\n", ($row[id] == $maintainer->id) ? " selected" : "" );
}

print("</select></td></tr>\n");
  
print("<tr class=\"ul_row\"><td>Created:</td>\n");
print("<td> $document->created</td></tr>\n");

print("<tr class=\"ul_row\"><td>Revision:</td>\n");
print("<td><input type=\"text\" class=\"input_text\" maxlength=\"2\" name=\"revision\" value=\"$document->revision\" /></td></tr>\n");

print("<tr class=\"ul_row\"><td>Last update:</td>\n");
print("<td> $document->modified</td></tr>\n");
if( $row[uid] == $user->id ){
	print("<tr><td></td><td><form action=\"edit.php\" method=\"post\"><input type=\"hidden\" name=\"doc_id\" value=\"$document->id\" /><input type=\"submit\" class=\"form_button\" value=\"Edit\" /></form></td></tr>\n");
}

print("<tr><td></td><td><input type=\"submit\" class=\"form_button\" name=\"button\" value=\"Save details\" /></td></tr>\n");

print("</table>");

print("</form></div>\n");

print_footer();

?>