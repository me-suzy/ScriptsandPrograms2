<?

////////////////////////////////////////////////////////////
//
// spiderLog v1.1 - a simple guestbook
//
////////////////////////////////////////////////////////////
//
// This script adds entries to a guestbook.
//
// See readme.txt for more information.
//
// Author: Jon Thomas <http://www.fromthedesk.com/code>
// Last Modified: 11/05/2005
//
// You may freely use, modify, and distribute this script.
//
////////////////////////////////////////////////////////////

//
// SET THE VARIABLES
//

// filename of guestbook
$guestbook_filename = "guestbook.html";

// text of error message
$error_msg = "<p><b>Error:</b> Your submission failed validation.</p>";


//
// VALIDATE SUBMISSION
//

// do not accept a submission if:
// (1) there are no comments
// (2) the comments contain a Web address
// (3) the name is longer than 40 characters
// (4) the city is longer than 30 characters
// (5) the city and state are identical
if ($comments == "" || ereg("http://", $comments) || ereg("www.", $comments) || ereg("<a href=", $comments) || strlen($name) > 40 || strlen($city) > 30)) {
	// terminate the script with an error message
	die($error_msg);
}


//
// FORMAT SUBMISSION
//

// strip HTML and PHP tags from submission
$comments = strip_tags($comments);
$name = strip_tags($name);
$city = strip_tags($city);
$state = strip_tags($state);
$country = strip_tags($county);

// include a new "post here" tag
$entry = "<!--POST HERE-->\n";

// include comments
$entry .= "<b>$comments</b><br>\n";

// include name and location
$entry .= "$name - $city, $state $country<br>";

// include the date and time
$entry .= "$datetime<br>\n";

// include horizontal rule
$entry .= "<hr><br>\n";


//
// ADD SUBMISSION TO GUESTBOOK
//

// get current guestbook data as an array of file lines
$guestbook_data = file($guestbook_filename);

// replace "post here" tag with new entry
$guestbook_data = preg_replace("/<!--POST HERE-->/", $entry, $guestbook_data);

// create a single variable from guestbook array
$guestbook_data = join("", $guestbook_data);

// open guestbook file for writing only
$guestbook = fopen($guestbook_filename, "w");

// write new guestbook data to guestbook file
fputs($guestbook, $updatedBook);

// close guestbook file
fclose($guestbook);

// send user back to guestbook
header("Location: $guestbook_filename");

?>