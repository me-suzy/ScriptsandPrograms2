<?php

// << -------------------------------------------------------------------- >>
// >> EXO Helpdesk Upgrade
// >>
// >> UPGRADE4 . PHP File - HelpDesk UPGRADE FILE
// >> Started : June 05, 2003
// >> Version : 1.1
// << -------------------------------------------------------------------- >>

ob_start();
include('conf.php');

// Queries!
$query[] = "INSERT INTO `phpdesk_fields` VALUES ('', '', 'Profile')";
$query[] = "ALTER TABLE `phpdesk_members` ADD `FIELDS` MEDIUMTEXT NOT NULL, ADD `VALUES` MEDIUMTEXT NOT NULL";
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