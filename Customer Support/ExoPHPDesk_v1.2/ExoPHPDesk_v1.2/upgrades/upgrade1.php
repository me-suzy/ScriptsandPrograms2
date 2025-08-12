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

$query[] = "CREATE TABLE IF NOT EXISTS `phpdesk_lostpass` (  `id` int(255) NOT NULL auto_increment,  `key` varchar(255) NOT NULL default '',  `user` varchar(255) NOT NULL default '',  `date` int(32) NOT NULL default '0',  `type` varchar(17) NOT NULL default '',  PRIMARY KEY  (`id`)) TYPE=MyISAM";
$query[] = "ALTER TABLE `phpdesk_configs` ADD `mailtype` VARCHAR(4) NOT NULL, ADD `mailhost` VARCHAR(50) NOT NULL, ADD `mailuser` VARCHAR(100) NOT NULL, ADD `mailpass` VARCHAR(100) NOT NULL;";
$query[] = "ALTER TABLE `phpdesk_tickets` ADD `admin_email` VARCHAR(255) NOT NULL AFTER `admin_user`;";

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