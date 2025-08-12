<?php

// << -------------------------------------------------------------------- >>
// >> EXO Helpdesk Upgrade
// >>
// >> UPGRADE4 . PHP File - HelpDesk UPGRADE FILE
// >> Started : January 26, 2004
// >> Edited  : February 23, 2004
// << -------------------------------------------------------------------- >>

ob_start();
include('conf.php');

// CREATE/ALTER TABLES
$query[] = "ALTER TABLE `phpdesk_tickets` ADD `attach` VARCHAR(255) NOT NULL";
$query[] = "ALTER TABLE `phpdesk_tickets` ADD `replies` INT(100) DEFAULT '0' NOT NULL";
$query[] = "ALTER TABLE `phpdesk_troubles` ADD `view` VARCHAR(10) NOT NULL";
$query[] = "UPDATE `phpdesk_troubles` SET `view` = 'All'";
$query[] = "ALTER TABLE `phpdesk_configs` ADD `st_announce` INT(1) DEFAULT '1' NOT NULL AFTER `mem_serv`, 
				ADD `at_allow` INT(1) DEFAULT '1' NOT NULL AFTER `st_announce`, 
				ADD `at_dir` VARCHAR(255) NOT NULL AFTER `at_allow`, 
				ADD `at_size` INT(100) NOT NULL AFTER `at_dir`, 
				ADD `at_ext` MEDIUMTEXT NOT NULL AFTER `at_size`, 
				ADD `at_prefix` VARCHAR(255) NOT NULL AFTER `at_ext`
		   ";
$query[] = "ALTER TABLE `phpdesk_servers` CHANGE `down` `down` LONGTEXT NOT NULL";

$query[] = "DROP TABLE IF EXISTS `phpdesk_announce`";
$query[] = "CREATE TABLE `phpdesk_announce` (
  `id` int(255) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `text` longtext NOT NULL,
  `access` varchar(60) NOT NULL default '',
  `expire` int(30) NOT NULL default '0',
  `added` int(30) NOT NULL default '0',
  PRIMARY KEY  (`id`)
)";

$query[] = "DROP TABLE IF EXISTS `phpdesk_diary`";
$query[] = "CREATE TABLE `phpdesk_diary` (
  `id` int(255) NOT NULL auto_increment,
  `admin_user` varchar(255) NOT NULL default '',
  `text` longtext NOT NULL,
  PRIMARY KEY  (`id`)
)";

$query[] = "DROP TABLE IF EXISTS `phpdesk_sessions`";
$query[] = "CREATE TABLE `phpdesk_sessions` (
  `sid` varchar(32) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `pass` varchar(32) NOT NULL default '',
  `ip` varchar(100) NOT NULL default '',
  `timeout` int(15) NOT NULL default '0',
  `type` varchar(100) NOT NULL default ''
)";

$query[] = "UPDATE phpdesk_configs SET 
			st_announce = '1', at_allow = '1', at_dir = 'attachments/', 
			at_size = '512000', at_ext = '.gif, .jpg, .jpeg, .txt', at_prefix = '_'
			";

foreach($query as $sql)
{
	if($db->query($sql))
	{
		echo "Query Execution :: [ <font color='green'>Success</font> ]<br />";
	}
	else
	{
		echo "Query Execution :: [ <font color='red'>Failed</font> ] <br/ >";
	}
}

/* Do the Table Replies Records */
echo "Tickets replies being counted...<br />";

$query = $db->query( "SELECT id FROM phpdesk_tickets" );
while( $fetch = $db->fetch( $query ))
{
	$Post_Count = 0;
	
	$rQ = $db->query( "SELECT id FROM phpdesk_responses WHERE `tid` = '{$fetch['id']}'" );
	while( $rf = $db->fetch( $rQ ))
	{
		$Post_Count++;		
	}
	
	$db->query( "UPDATE phpdesk_tickets SET replies = '{$Post_Count}' WHERE id = '{$fetch['id']}'" );
}

echo "Upgrade Done.. Please delete this file now.<br />";

ob_end_flush();

?>