<?php

// << -------------------------------------------------------------------- >>
// >> EXO Helpdesk Upgrade
// >>
// >> UPGRADE4 . PHP File - HelpDesk UPGRADE FILE
// >> Started : November 18, 2003
// >> Version : 0.6
// << -------------------------------------------------------------------- >>

ob_start();
include('conf.php');

// NEW TABLES
$query[] = "DROP TABLE IF EXISTS `phpdesk_kbgroups`";
$query[] = "CREATE TABLE `phpdesk_kbgroups` (
  `id` int(255) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `staff` int(1) NOT NULL default '1',
  PRIMARY KEY  (`id`)
)";

$query[] = "DROP TABLE IF EXISTS `phpdesk_ratings`";
$query[] = "CREATE TABLE `phpdesk_ratings` (
  `id` int(255) NOT NULL auto_increment,
  `rating` int(1) NOT NULL default '0',
  `type` varchar(100) NOT NULL default '',
  `uid` int(255) NOT NULL default '0',
  `ratedby` varchar(255) NOT NULL default '',
  `ip` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id`)
)";

$query[] = "DROP TABLE IF EXISTS `phpdesk_saved`";
$query[] = "CREATE TABLE `phpdesk_saved` (
  `id` int(255) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `text` mediumtext NOT NULL,
  `type` varchar(60) NOT NULL default '',
  PRIMARY KEY  (`id`)
)";

$query[] = "DROP TABLE IF EXISTS `phpdesk_troubles`";
$query[] = "CREATE TABLE `phpdesk_troubles` (
  `id` int(255) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `text` mediumtext NOT NULL,
  `isparent` int(1) NOT NULL default '1',
  `parent` int(100) NOT NULL default '0',
  PRIMARY KEY  (`id`)
)";

$query[] = "ALTER TABLE `phpdesk_staff` ADD `rating` VARCHAR(255) NOT NULL";

foreach($query as $sql)
{
	if($db->query($sql))
	{
		echo "Success.. Query Executed.<br />";
	}
	else
	{
		echo "Query Failed. <br/ >";
	}
}

echo "Upgrade Done.. Please delete this file now.<br />";
ob_end_flush();
?>