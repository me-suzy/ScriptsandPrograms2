<?php
/*********************************************************
 * Name: stats.php
 * Author: Dave Conley
 * Contact: realworld@blazefans.com
 * Description: Allows you to display a number of stats on your website
 * Version: 4.0
 * Last edited: 20th February, 2005
 *
 * Usage Instructions:
 * Type the following into a .php file:
 * <?php 
 * 		readfile ("http://www.yourwebsite.com/download/stats.php?ACT=XXX&limit=Y");
 * ?>
 * Just replace yourwebsite.com with your own website url
 * Replace XXX with one of the following:
 * topdl - displays the most downloaded files
 * toprate - displays the higest rated files
 * latest - displays the latest added files
 * Replace Y with the number of items you wish to display
 * You may also change $ROOT_PATH to the full path to your downloads 
 * folder if you have problems running this script
 *********************************************************/

define( 'ROOT_PATH', "./" );
// Do not edit anything below this point

// Load config
$CONFIG = array();
require_once ROOT_PATH."/globalvars.php";

// Load required libraries
require_once (ROOT_PATH."/functions/mysql.php");

// Load the database
$dbinfo = array("sqlhost" => $CONFIG["sqlhost"],
		"sqlusername" => $CONFIG["sqlusername"],
		"sqlpassword" => $CONFIG["sqlpassword"],
		"sqldatabase" => $CONFIG["sqldatabase"],
		"sql_tbl_prefix" => $CONFIG["sqlprefix"]);

$DB = new mysql($dbinfo);

$path = $CONFIG["sitepath"];
$home_url = $CONFIG["siteurl"];

$act = $HTTP_POST_VARS['ACT'] ? $HTTP_POST_VARS['ACT'] : $HTTP_GET_VARS['ACT'];

switch($act)
{
	case 'topdl':
		topDownloads($_GET["limit"]);
		break;
	case 'toprate':
		topRatedDownloads($_GET["limit"]);
		break;
	case 'latest':
		latestDownloads($_GET["limit"]);
		break;
}

function topDownloads($limit = "0,5")
{
	global $DB, $home_url;
	$DB->query("SELECT * FROM `dl_links` ORDER BY `downloads` DESC limit $limit");
	if ($myrow = $DB->fetch_row())
	{
		do
		{
			echo "- <a href=\"".$home_url."/index.php?dlid=".$myrow["did"]."\">".$myrow["name"]."</a> (".$myrow["downloads"].")<br>";
		} while ($myrow = $DB->fetch_row());
	}
	
}

function topRatedDownloads($limit = "0,5")
{
	global $DB, $home_url;
	$DB->query("SELECT * FROM dl_links ORDER BY userrating DESC limit $limit");
	if ($myrow = $DB->fetch_row())
	{
		do
		{
			echo "- <a href=\"".$home_url."/index.php?dlid=".$myrow["did"]."\">".$myrow["name"]."</a> (".$myrow["userrating"].")<br>";
		} while ($myrow = $DB->fetch_row());
	}
	
}

function latestDownloads($limit = "0,5")
{
	global $DB, $home_url;
	$DB->query("SELECT * FROM dl_links ORDER BY date DESC limit $limit");
	if ($myrow = $DB->fetch_row())
	{
		do
		{
			echo "- <a href=\"".$home_url."/index.php?dlid=".$myrow["did"]."\">".$myrow["name"]."</a><br>";
		} while ($myrow = $DB->fetch_row());
	}
}
?>