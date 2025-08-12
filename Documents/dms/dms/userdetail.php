<?php
#####################################################################
# NAME/ PURPOSE - this page displays detailed information for users
#      displayed on users.php
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
$contact = new user($contact);
$contact->load_address();

print_header("Contact Information for $contact->name");

print("<h1>Contact Information for $contact->name</h1>\n");

print("<ul id=\"contact_details\">");

print("<li>Name:\n");
print("$contact->name</li>\n");
print("<li>Email:\n");
print("$contact->email</li>\n");

if($contact->phone) {
	print("<li>Phone:\n");
	print("$contact->phone</li>\n");
}

if($contact->fax) {
	print("<li>Fax:\n");
	print("$contact->fax</li>\n");
}

if($contact->mobile) {
	print("<li>Mobile:\n");
	print("$contact->mobile</li>\n");
}

if($contact->addr_1) {
	print("<li>Address: \n");
	print("$contact->addr_1<br />\n");
	
	if($contact->addr_2){
		print("$contact->addr_2<br />\n");
	}
	
	print("$contact->city, $contact->state $contact->postcode</li>\n");
}

print("</ul>");

print_footer();

?>