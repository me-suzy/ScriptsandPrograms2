<?php
/************************************************************************/
// SAXON: Simple Accessible XHTML Online News system
// Module: rss-auto.php
// Version 4.6
// Automatically create an XML news feed. Called from add.php, update.php and confirm-delete.php
// Developed by Black Widow
// Copyright (c) 2004 by Black Widow
// Support: www.forum.quirm.net
// Commercial Site: www.blackwidows.co.uk
/************************************************************************/
include 'config.php';

$today = date("Y-m-d");
$xml_file =$_SERVER['DOCUMENT_ROOT']."/".$path.$xml_filename;
$xml_link = $uri.$path."display-item.php?newsid=";

$xml_header="<?xml version=\"1.0\"?>\n<rss version=\"2.0\">\n<channel>\n<title>".$xml_title."</title>\n<link>".$news_url."</link>\n<description>".$xml_desc."</description>\n<language>".$xml_lang."</language>\n\n";
$xml_footer = "</channel>\n</rss>";
$xml_chr = $xml_chr - 4;

$error = DBConnect ($mhost,$muser,$mpass,$mdb);
if (trim($error)=="")
{
	$tblname = QuoteSmart($prefix."saxon");
	$today = QuoteSmart($today);
	$result = mysql_query ("SELECT * FROM $tblname WHERE DATE <= '$today' ORDER BY 'DATE' DESC");
	if (!$result) die(' in rss-auto');
}
xmlOpen($xml_file, $xml_header);

// We want to list all news items
while($row = mysql_fetch_array($result))
{
	$title=$row['TITLE'];
	// remove any slashes inserted by magic_quotes_gpc and all HTML tags
	$row['NEWS'] = htmlspecialchars(stripslashes (strip_tags($row['NEWS'])), ENT_QUOTES);
	$extract = explode(' ', $row['NEWS']);
	if ((count($extract) > $xml_chr) && ($xml_chr != 0)) $extract = implode(' ', array_slice($extract, 0, $xml_chr)) . "...";
	else $extract = implode(' ',$extract);

	// Convert common Word chrs to ANSI equivalents
	$extract = EncodeChrs ($extract);

	$string="<item>\n<title>".$title."</title>\n";
	$string .= "<link>".$xml_link.$row['NEWSID']."</link>\n";
	$string .="<description>".$extract."</description>\n</item>\n\n";
	ignore_user_abort(true);
	xmlWrite($xml_file, $string);
	ignore_user_abort(false);
}
xmlClose($xml_file,$xml_footer);
// script ran without error
$rss_auto = 1;

// FUNCTIONS

function xmlOpen($file, $header) {
	ignore_user_abort(true);
	$fp=fopen($file,'w');
	$write = fputs($fp, $header);
	fclose($fp);
	ignore_user_abort(false);
}

function xmlWrite($file, $content) {
	$fp=fopen($file,'a');
	$write = fputs($fp, $content);
	fclose($fp);
}

function xmlClose ($file, $footer) {
	ignore_user_abort(true);
	$fp=fopen($file,'a');
	$write = fputs($fp, $footer);
	fclose($fp);
	ignore_user_abort(false);
}

?>