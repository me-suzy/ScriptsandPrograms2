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

$query[] = "CREATE TABLE `phpdesk_notes` (  `id` int(255) NOT NULL auto_increment,  `tid` int(255) NOT NULL default '0',  `sname` varchar(255) NOT NULL default '',  `note` mediumtext NOT NULL,  `posted` int(32) NOT NULL default '0',  PRIMARY KEY  (`id`));";

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