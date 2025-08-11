<?php
// ----------------------------------------------------------------------
// Khaled Content Management System
// Copyright (C) 2004 by Khaled Al-Shamaa.
// GSIBC.net stands behind the software with support, training, certification and consulting.
// http://www.al-shamaa.com/
// ----------------------------------------------------------------------
// LICENSE

// This program is open source product; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.

// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------
// Filename: rss.php
// Original  Author(s): Khaled Al-Sham'aa khaled@al-shamaa.com
// Purpose:  Really Simple Syndication (RSS) for the website content
// ----------------------------------------------------------------------

include_once ("config.php");
include_once ("db.php");
include_once ("lang.php");

// Find out base path of the website
$path_arr = explode("/", $_SERVER['SCRIPT_NAME']);
$temp = array_pop($path_arr);
$path = implode("/", $path_arr);
$path = "http://" . $_SERVER['HTTP_HOST'] . $path;

// create an object instance
// configure library for a MySQL connection
$db = NewADOConnection(DBTYPE);

// open connection to database
$db->Connect(HOST, USER, PASS, DB) or die("Unable to connect!");

// get resultset as associative array
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

$strsql = "SELECT * FROM `pages` WHERE `parent_id`=1 AND `lang`='" . $lang . "'";
$rs = $db->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());

$itemNo = 0;
$rss  = '';

while (!$rs->EOF) {
    // get the field contents
    $x_id = @$rs->fields['id'];
    $x_title = @$rs->fields['title'];
    $x_content = @$rs->fields['content'];
    
    // Prepare RSS information
    $headline = strip_tags($x_title);
    $content1 = strip_tags($x_content);
    $content1 = str_replace("&nbsp;", " ", $content1);
    $content  = substr($content1, 0, 250);
    if(strlen($content1) > 250){ $content .= "..."; }

    // Add RSS item
    $rss .= "<item>\n";
    $rss .= "\t<title>$headline</title>\n";
    $rss .= "\t<description>$content</description>\n";
    $rss .= "\t<link>$path/page-$x_id.html</link>\n";
    $rss .= "</item>\n";

    $itemNo++;
    $rs->MoveNext();
}

if($itemNo < 15){
    $limit = 15 - $itemNo;

    $strsql = "SELECT * FROM `pages` WHERE `parent_id`<>1 AND `lang`='" . $lang . "' ORDER BY `modified` DESC LIMIT $limit";
    $rs = $db->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());
    
    while (!$rs->EOF) {
        // get the field contents
        $x_id = @$rs->fields['id'];
        $x_title = @$rs->fields['title'];
        $x_content = @$rs->fields['content'];
        
        // Prepare RSS information
        $headline = strip_tags($x_title);
        $content1 = strip_tags($x_content);
        $content  = substr($content1, 0, 250);
        if(strlen($content1) > 250){ $content .= "..."; }
    
        // Add RSS item
        $rss .= "<item>\n";
        $rss .= "\t<title>$headline</title>\n";
        $rss .= "\t<description>$content</description>\n";
        $rss .= "\t<link>$path/page-$x_id.html</link>\n";
        $rss .= "</item>\n";
    
        $rs->MoveNext();
    }
}
$db->Close();

// Send XML headers
header('Content-type:  application/xhtml+xml');
echo "<?xml version='1.0' ?>\n";
?>
<rss version='2.0'>
<channel>
<title><?php echo SITE_TITLE; ?></title>
<link><?php echo $path; ?></link>
<description>News for programmers, business, and home computer users.</description>
<language><?php echo $lang; ?></language>
<docs><?php echo "$path/rss.php"; ?></docs>
<?php echo $rss; ?>
</channel>
</rss>

