<?php
/************************************************************************/
// SAXON: Simple Accessible XHTML Online News system
// Module: news.php
// Display News for a web page
// Version 4.6
// Developed by Black Widow
// Copyright (c) 2004 by Black Widow
// Support: www.forum.quirm.net
// Commercial Site: www.blackwidows.co.uk
/************************************************************************/
include("functions.php");
include "config.php";
include $template;

$fake = "templates/fake-cron.php";
$errormsg = "";

$error = DBConnect ($mhost,$muser,$mpass,$mdb);
if (trim($error)=="")
{
	$tblname = QuoteSmart($prefix."saxon");
	$today = QuoteSmart(date("Y-m-d"));
//	$today = "2005-10-06";

	// Check to see if we need to run fake-cron
	include $fake;
	if (isset($lastrun)) {
		if ($lastrun != $today && $lastrun != "never") {
			$result = mysql_query ("SELECT * FROM $tblname WHERE DATE = '$today' ORDER BY 'DATE' ASC");
			if ($result) {
				$num_rows = mysql_num_rows($result);
				// if 'yes', get $lastrun from fake-cron.php
				if($num_rows != 0) {
					// if last run != today, run rss-auto.php
					$rss = $_SERVER['DOCUMENT_ROOT']."/".$path."rss-auto.php";
					if (file_exists($rss)) include $rss;
					else {
						$errormsg = "Fake cron error!\n\n";
						$errormsg .= "Could not run rss-auto.php\n\n";
						$errormsg .= "Path: ".$rss."\n\n";
					}
				}
			}
			else {
				$errormsg = "Fake cron error!\n\n";
				$errormsg .= "Invalid query: " . mysql_error();
			}
		}
		if ($lastrun != "never") {
			// update fake cron file
			$content = "<?php\n\n";
			$content .= "// Set \$lastrun = \"never\" to stop fake cron completely\n\n";
			$content .="\$lastrun = \"".$today."\";\n\n?>";
			$filename =$_SERVER['DOCUMENT_ROOT']."/".$path.$fake;
			if (is_writable($filename)) {
				$handle = fopen($filename, "w");
				if (fwrite($handle, $content) === FALSE) {
					$errormsg = "Fake cron error!\n\n";
					$errormsg .= "Could not write to ".$filename.".\n";
					exit;
				}
				fclose($handle);
			}
			else {
				$errormsg = "Fake cron error!\n\n";
				$errormsg .= $filename." is not writeable. Chmod it to 777.\n";
			}
		}
	}
			
	// END FAKE CRON

	if (isset($user)) {
		$user = QuoteSmart($user);
		$result = mysql_query ("SELECT * FROM $tblname WHERE POSTER='$user' AND DATE <= '$today' ORDER BY 'DATE' DESC");
	}
	else $result = mysql_query ("SELECT * FROM $tblname WHERE DATE <= '$today' ORDER BY 'DATE' DESC");
	if (!$result) die('Invalid query: ' . mysql_error());
	$num_rows = mysql_num_rows($result);
	if($num_rows == 0)
	{
		echo "<p class=\"error\">No news to display</p>";
		exit;
	}
	// We want to list all items
	if ($max_items == 0) {
		while($row = mysql_fetch_array($result))
		{
			$item = $row['NEWS'];
			if($newslength > 0) $item = LimitWords($item, $newslength);
			$item = PrepText($item, $html);
			if($newslength > 0) {
				$row['NEWS'] .="<p class=\"item-link\">";
				$row['NEWS'] .="<a href=\"".$uri.$path."display-item.php?newsid=".$row['NEWSID']."\">More about ".$row['TITLE']."</a></p>";
			}

			// Let's have a nicely formatted posting date for display only
			$displaydate = DisplayDate(($row['DATE']));
			$news_display = Template($displaydate, $row['TITLE'], $item, $row['POSTER'], $row['NEWSID']);
			echo $news_display;
		}
	}
	else {
		$item_count=1;
		// We only want to list $max_items
		while(($row = mysql_fetch_array($result)) && ($item_count <= $max_items))
		{
			$item = $row['NEWS'];
			if($newslength > 0) $item = LimitWords($item, $newslength);
			$item = PrepText($item, $html);
			if($newslength > 0) {
				$item .="<p class=\"item-link\">";
				$item .="<a href=\"".$uri.$path."display-item.php?newsid=".$row['NEWSID']."\">More about ".$row['TITLE']."</a></p>";
			}

			$item_count++;

			// Let's have a nicely formatted posting date for display only
			$displaydate = date("l, d F Y",strtotime($row['DATE']));
			$news_display = Template($displaydate, $row['TITLE'], $item, $row['POSTER']);
			echo $news_display;
		}
	}
}
else {
	$errormsg = "Fake cron error!\n\n";
	$errormsg = trim($error);
}

if ($errormsg !="") EmailError($errormsg);

?>