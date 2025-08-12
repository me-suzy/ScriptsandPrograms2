<?php

// $Id: rss.php 66 2005-09-11 10:19:10Z stefan $

/*

 Website Baker Project <http://www.websitebaker.org/>
 Copyright (C) 2004-2005, Ryan Djurovich

 Website Baker is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 Website Baker is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with Website Baker; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/

// Include WB files
require_once('../../config.php');
require_once(WB_PATH.'/framework/class.frontend.php');
$database = new database();
$wb = new frontend();
$wb->get_page_details();
$wb->get_website_settings();

// Check that GET values have been supplied
if(isset($_GET['page_id']) AND is_numeric($_GET['page_id'])) {
	$page_id = $_GET['page_id'];
} else {
	header('Location: '.WB_URL);
}
if(isset($_GET['group_id']) AND is_numeric($_GET['group_id'])) {
	$group_id = $_GET['group_id'];
}

// Sending XML header
header("Content-type: text/xml");

// Header info
// Required by CSS 2.0

echo "<rss version='2.0'>";
echo "<channel>";
echo "<title>".PAGE_TITLE."</title>";
echo "<link>".WB_URL."</link>";
echo "<description>".PAGE_DESCRIPTION."</description>";

// Optional header info
echo "<language>".DEFAULT_LANGUAGE."</language>";
echo "<copyright>".WB_URL."</copyright>";
echo "<managingEditor>".SERVER_EMAIL."</managingEditor>";
echo "<webMaster>".SERVER_EMAIL."</webMaster>";
echo "<category>".WEBSITE_TITLE."</category>";
echo "<generator>Website Baker Content Management System</generator>";

// Get news items from database

//Query
if(isset($group_id)) {
	$query = "SELECT * FROM ".TABLE_PREFIX."mod_news_posts WHERE group_id=".$group_id." AND page_id = ".$page_id." AND active=1 ORDER BY posted_when DESC";
} else {
	$query = "SELECT * FROM ".TABLE_PREFIX."mod_news_posts WHERE page_id=".$page_id." AND active=1 ORDER BY posted_when DESC";	
}
$result = $database->query($query);

//Generating the news items
while($item = $result->fetchRow($result)){

    echo "<item>";
    echo "<title>".$item["title"]."</title>";
    // Stripping HTML Tags for text-only visibility
    echo "<description>".strip_tags($item["content_short"])."</description>";
    echo "<link>".WB_URL."/pages".$item["link"].PAGE_EXTENSION."</link>";
    /* Add further (non required) information here like ie.
    echo "<author>".$item["posted_by"]."</author>");
    etc.
    */
    echo "</item>";

}

// Writing footer information
echo "</channel>";
echo "</rss>";

?>