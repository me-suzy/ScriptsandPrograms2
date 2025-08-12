<?php

// << -------------------------------------------------------------------- >>
// >> EXO Helpdesk Installation File
// >>
// >> INSTALL . PHP File - HelpDesk Installation File
// >> Started : November 18, 2003
// >> Version : 0.2
// << -------------------------------------------------------------------- >>

ob_start();
include('conf.php');

$query[] = "CREATE TABLE IF NOT EXISTS `phpdesk_fields` (  `field` varchar(255) NOT NULL default '',  `mandatory` varchar(255) NOT NULL default '',  `type` varchar(100) NOT NULL default '');";
$query[] = "CREATE TABLE IF NOT EXISTS `phpdesk_kb` (  `id` int(255) NOT NULL auto_increment,  `title` varchar(255) NOT NULL default '',  `message` mediumtext NOT NULL,  `posted` int(32) NOT NULL default '0',  `view` varchar(10) NOT NULL default '',  `owner` varchar(255) NOT NULL default '',  `group` varchar(255) NOT NULL default '',  PRIMARY KEY  (`id`));";
$query[] = "ALTER TABLE `phpdesk_tickets` ADD `fields` VARCHAR(255) NOT NULL, ADD `values` MEDIUMTEXT NOT NULL;";
$query[] = "INSERT INTO `phpdesk_fields` VALUES ('', '', 'Ticket');";

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

echo "Done.. Please delete this file now.<br />";
ob_end_flush();
?>