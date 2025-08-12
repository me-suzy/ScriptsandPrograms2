<?
// daphne@squiz.net
// This script can replace any number of strings in html of bodycopy 
// (takes regular expressions as the search parameters)

// Initialise
include_once("../init.php");
include_once("../../include/webobject.inc");
include_once("../../squizlib/bodycopy/bodycopy.inc");

$web = &get_web_system();
$page = new Page();
$files = array();
$start_pageid = -1;
$testing = false;	// set to false to actually make changes specified in $search and $replace
					// set to true to just view html changes in browser
$table_output = true; // do you want pretty output for testing?



// each element in $search array is replaced by the corresponding element in $replace array

$search = array( 0 => "/<[\/]?SMALL>/i",	// take out any <small> or </small> tags (case insensitive)
				 1 => "/<[\/]?FONT[^<>]*>/i", // take out any <font xxx> or </font> tags (case insensitive)
				 2 => "/<em>/i",  // replace <em>
				 3 => "/<\/em>/", // replace </em>
				 4 => "/<strong>/i", // replace <strong>
				 5 => "/<\/strong>/i", // replace </strong>
				 6 => "/<!--webbot[^<>]*-->[^!]*![^<>]*>/" // replace front page img maps
				);
$replace = array( 0 => "",  
				  1 => "",
				  2 => "<i>",
				  3 => "</i>",
				  4 => "<b>",
				  5 => "</b>",
				  6 => "<div align=\"center\"><a href=\"#top\"><img src=\"./?f=38\" width=\"338\" height=\"12\" border=\"0\"></a></div>"
				 );


if ($testing == true) { echo "<b>this rockin' content replacer is running in test mode. <br><br></b>"; }

$site = &$web->get_site(1);
$page_index = &$site->get_page_index();
$i=0;

// pre_echo ($page_index);


foreach($page_index as $pageid=>$contents){
	// stopping weird bug 
	if ($pageid == 0) { continue; }

	echo "Changing page $pageid<br>";

	if ($testing == true) {
		$pageid = 103; // engineering library guide
		echo "cheating in place... testing with $pageid<p>";
	}
	
	
	$page = &$web->get_page($pageid);

	if ($testing == true) {
		echo ("testing $pageid, looping through bodycopy ... <br>");
		if ($table_output == true) { echo ("<table width=800 border=1>"); }
	}

	// get template for $page (std vs form)
	$template = &$page->get_template();
	// get bodycopy
	$bodycopy_string = &$template->bodycopy;
	// creates a new bodycopy 
	$bodycopy = new BodyCopy($bodycopy_string,'bodycopy');

	// go into each bodycopy and retrieve html in cells.
	foreach($bodycopy->tables as $tableid=>$table){
		foreach($table->rows as $rowid=>$row) {
			foreach($row->cells as $cellid=>$cell) {
				// get html
				$html = $cell->type->html;

				if ($testing == true) {
					if ($table_output == true) { echo(" <tr><td width='50%'>"); }
					echo ("<b>html before:</b> <br>");
					pre_echo ("$html");
					echo("<p>&nbsp;<p>");
					if ($table_output == true) { echo ("</td><td width='50%'>"); }
				}

				// replace html with changes
				$html = preg_replace($search, $replace, $html);

				if ($testing == true) { // print out changes
					echo ("<b>html after:</b> <br>");
					pre_echo ("$html");
					echo ("<p>&nbsp;<p>");
					if ($table_output == true) { echo ("</td></tr>"); }
				}
				else {	// save html
					$bodycopy->tables[$tableid]->rows[$rowid]->cells[$cellid]->type->set_html($html);
				}
			}
		}
	}


	if ($testing == true) {
		echo ("</table><P><hr><p>");
		echo ("Here's the bodycopy, in case you're curious<p>");
		pre_echo($bodycopy);
		break;
	}
	else {// save the bodycopy
		$template->set_bodycopy($bodycopy->pack());
	}

	$i++;
} 

echo "<b>$i pages counted.</b><br>"

?>