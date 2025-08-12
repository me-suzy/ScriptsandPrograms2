<?php

// << -------------------------------------------------------------------- >>
// >> EXO Helpdesk Upgrade
// >>
// >> UPGRADE4 . PHP File - HelpDesk UPGRADE FILE
// >> Started : November 18, 2003
// >> Version : 0.5
// << -------------------------------------------------------------------- >>

ob_start();
include('conf.php');

// NEW TABLES
$query[] = "DROP TABLE IF EXISTS `phpdesk_servers`";
$query[] = "CREATE TABLE `phpdesk_servers` (  `id` int(255) NOT NULL auto_increment,  `ip` varchar(20) NOT NULL default '',  `name` varchar(255) NOT NULL default '',  `down` int(255) NOT NULL default '0',  `news` mediumtext NOT NULL,  `web_port` int(6) NOT NULL default '0',  `ssh_port` int(6) NOT NULL default '0',  `telnet_port` int(6) NOT NULL default '0',  `ftp_port` int(6) NOT NULL default '0',  `mysql_port` int(6) NOT NULL default '0',  `smtp_port` int(6) NOT NULL default '0',  `pop3_port` int(6) NOT NULL default '0',  `imap_port` int(6) NOT NULL default '0',  PRIMARY KEY  (`id`))";

// ALTER TABLES
$query[] = 'ALTER TABLE `phpdesk_configs` ADD `mem_serv` INT(1) NOT NULL AFTER `registrations`';
$query[] = 'ALTER TABLE `phpdesk_admin` ADD `signature` MEDIUMTEXT NOT NULL';
$query[] = 'ALTER TABLE `phpdesk_staff` ADD `signature` MEDIUMTEXT NOT NULL';

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

echo "Upgrading Done.. Please delete this file now.<br />";
ob_end_flush();
?>