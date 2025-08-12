<?php
#####################################################################
# NAME/ PURPOSE - this page lists the details for the selected file
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

function hilite($text) {
	global $query;
	$uquery = strtoupper($query);
	if($query){
		return ereg_replace("$query", "<strong>$query</strong>", ereg_replace("$uquery", "<strong>$uquery</strong>", $text));
	}
	else{
		return $text;
	}
}

$user = new user($login);
$document = new document($doc_id);
$document->get_access($user->id);
$author = $document->author;
$maintainer = $document->maintainer;

print_header("Document Information");

print("<h1>Document Details</h1>");

print("<h2><img src=\"pix/". get_extension($document->name) ."-l.gif\" class=\"document_type_pic\" alt=\"[". strtoupper(get_extension($document->name)) ."]\" />\n");

print(" $document->name</h2>\n");
print("<div class=\"a_form\"><form action=\"download.php\" method=\"post\"><input type=\"hidden\" name=\"doc_id\" value=\"$document->id\"><input type=\"submit\" class=\"form_button\" value=\"Download\"></form></div>\n");

printf("<p>%s\n", ($document->info == NULL) ? "No information" : hilite(htmlspecialchars(stripslashes($document->info))) );
printf("%s\n", (get_extension($document->name) != "exe") ? "" : "<p class=\"descb\">Note: This application has not been scanned for viruses!" );
print("<h3>Keywords:</h3>\n");
print("<ul>\n");
$kw = current($document->keywords);
do {
	print("<li>". hilite($kw) ."</li>\n");
} 
while( $kw = next($document->keywords) );

print("</ul>\n");

print("<h3>Details</h3>");
print("<ul>");
print("<li>File name:\n");
printf("<img src=\"pix/%s.gif\" height=\"15\" width=\"15\" alt=\"[ Access: %s ]\" />%s</li>\n"
, $document->level
, access_string( ($document->level == NULL) ? "" : $document->level )
, hilite($document->name) );

print("<li>File size:\n");
print(" ". number_format($document->size, 0, ".", ",") ." bytes</li>\n");

print("<li>Mime type:\n");
print("$document->type</li>\n");

print("<li>Download time:\n");

$seconds = ($document->size/5500) % 60;
if($seconds<10){
	$seconds = "0$seconds";
}
$minutes = number_format((($document->size/5500) - $seconds)/60, "0", "","");

print("About $minutes:$seconds at 56K\n<br>No time at all at 10 Mbps</li>");

print("<li>Author:\n");
print("$author->name &lt;<a href=\"mailto:$author->email\">$author->email</a>&gt;</li>\n");

print("<li>Maintainer:\n");
print(" $maintainer->name &lt;<a href=\"mailto:$maintainer->email\">$maintainer->email</a>&gt;</li>\n");

print("<li>Created:\n");
print(" $document->created</li>\n");

print("<li>Revision:\n");
print(" $document->revision</li>\n");

print("<li>Last update:\n");
printf(" %s</li>\n", ($document->modified == NULL ) ? "-" : $document->modified );

print("</ul>");

if( may_god($user->id, $document->id) ) {
	print("<table><tr>");
	print("<td><form action=\"edit.php\" method=\"post\"><input type=\"hidden\" name=\"doc_id\" value=\"$document->id\"><input type=\"submit\" class=\"form_button\" value=\"Edit Details\"></form></td>\n");
	print("<td><form action=\"acl.php\" method=\"post\"><input type=\"hidden\" name=\"doc_id\" value=\"$document->id\"><input type=\"submit\" class=\"form_button\" value=\"Edit Access\"></form></td>\n");
	print("</tr></table>");
}

print_footer();
?>