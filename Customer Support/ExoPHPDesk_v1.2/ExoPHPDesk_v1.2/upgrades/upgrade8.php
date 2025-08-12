<?php

// << -------------------------------------------------------------------- >>
// >> EXO Helpdesk Upgrade
// >>
// >> UPGRADE8 . PHP File - HelpDesk UPGRADE FILE
// >> Started : July 12, 2004
// >> Version : 1.2
// << -------------------------------------------------------------------- >>

ob_start();
include('conf.php');

// Queries!
$query[] = "ALTER TABLE `phpdesk_staff` ADD `edit_ticket` INT(1) DEFAULT '1' NOT NULL";
$query[] = "ALTER TABLE `phpdesk_staff` ADD `edit_response` INT(1) DEFAULT '1' NOT NULL";
$query[] = "ALTER TABLE `phpdesk_configs` ADD `desk_offline` INT(1) DEFAULT '0' NOT NULL AFTER `at_prefix`, ADD `off_reason` MEDIUMTEXT NOT NULL AFTER `desk_offline`";
$query[] = "CREATE TABLE `phpdesk_events` (  `id` int(255) NOT NULL auto_increment,  `title` varchar(255) NOT NULL default '',  `message` mediumtext NOT NULL,  `day` int(10) NOT NULL default '0',  `month` int(10) NOT NULL default '0',  `year` int(10) NOT NULL default '0',  `owner` varchar(255) NOT NULL default '',  `type` varchar(255) NOT NULL default '',  PRIMARY KEY  (`id`))";

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