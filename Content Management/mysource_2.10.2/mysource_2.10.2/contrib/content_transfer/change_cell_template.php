<?
// This script changes the type of cell (rich text, raw html etc)
// Initialise
include_once("../init.php");
include_once("../../include/webobject.inc");
include_once("../../squizlib/bodycopy/bodycopy.inc");

$web = &get_web_system();
$page = new Page();
$files = array();
$start_pageid = -1;
$testing = true;
$new_type = "wysiwyg";

if ($testing == true) { 
	echo "<b>this rockin' cell template changer is running in test mode. <br><br></b>"; 
	echo "Changing cell templates to <b>$new_type</b><br><br>";
	echo "<b>Since we're in TEST mode, NOTHING ACTUALLY HAPPENS!</b><br>Change testing to 'false' to actually do something!";
}


$db = &$web->get_db();
$sites = $db->single_column("Select siteid from site");

$i=0;


// pre_echo ($page_index);

foreach($sites as $siteid=>$sitecontents) {
		
	$site = &$web->get_site($sitecontents);
	echo "<p>Site <b>$sitecontents</b> found.<p>";
	$page_index = &$site->get_page_index();

	foreach($page_index as $pageid=>$contents){
		// stopping weird bug 
		if ($pageid == 0) { continue; }

		echo "now on page $pageid<br>";

		if ($testing == true) {
			$pageid = 12;
			echo "cheating in place... testing with $pageid<p>";
		}
		
		
		$page = &$web->get_page($pageid);

		if ($testing == true) {
			echo ("testing $pageid, looping through bodycopy ... <br>");
		}

		// get template for $page (std vs form)
		$template = &$page->get_template();
		// get bodycopy
		$bodycopy_string = &$template->bodycopy;
		// creates a new bodycopy 
		$bodycopy = new BodyCopy($bodycopy_string,'bodycopy');

		// go into each bodycopy and change the cell template
		foreach($bodycopy->tables as $tableid=>$table){
			foreach($table->rows as $rowid=>$row) {
				foreach($row->cells as $cellid=>$cell) {
					$type="bodycopy_table_cell_type_" . $new_type;
					if ($testing == true) { echo "$type<br>"; }
					$bodycopy->tables[$tableid]->rows[$rowid]->cells[$cellid]->set_type("$type");
				}
			}
		}


		if ($testing == true) {
			echo ("Here's the bodycopy, in case you're curious<p>");
			pre_echo($bodycopy);
			break;
		}
		else {// save the bodycopy 
			$template->set_bodycopy($bodycopy->pack());
		}

		
		print "$i pages counted<br>";
		$i++;
	} 
}
?>