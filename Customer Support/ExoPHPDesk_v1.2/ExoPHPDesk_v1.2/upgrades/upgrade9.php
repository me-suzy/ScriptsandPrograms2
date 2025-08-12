<?php

// << -------------------------------------------------------------------- >>
// >> EXO Helpdesk Upgrade
// >>
// >> UPGRADE8 . PHP File - HelpDesk UPGRADE FILE
// >> Started : October 22, 2004
// >> Version : 1.2
// << -------------------------------------------------------------------- >>

ob_start();
include('conf.php');

// Queries!
$query[] = 'ALTER TABLE `phpdesk_sessions` ADD UNIQUE ( `name` )';
$query[] = "ALTER TABLE `phpdesk_groups` ADD `high_tickets` INT( 50 ) DEFAULT '0' NOT NULL ,
			ADD `open_tickets` INT( 50 ) DEFAULT '0' NOT NULL ,
			ADD `total_tickets` INT( 50 ) DEFAULT '0' NOT NULL";

// execute the queries.
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

$g = $db->query("SELECT * FROM `phpdesk_groups`");
while($fg = $db->fetch($g))
{
	// unset vars!
	$total_tickets = $open_tickets = $high_tickets = 0;
	
	echo "<br>Found Group `{$fg[name]}`<br>Counting Tickets: ";
	$q = $db->query("SELECT * FROM `phpdesk_tickets` WHERE `group` = '{$fg[name]}'");
	while($f = $db->fetch($q)) 
	{
		// increment total tickets count!
		$total_tickets++;
	
		// add to the open tickets counter..
		if($f['status'] == 'Open') {
			$open_tickets++;
			
			// add to high ticket counter..
			if($f['priority'] == '1') {
				$high_tickets++;
			}
		}
	}
	
	echo "<font color=green><b>Done</b></font><br>";
	
	// update group's ticket counters
	$db->query("UPDATE `phpdesk_groups` SET total_tickets = '$total_tickets', 
					open_tickets = '$open_tickets', high_tickets = '$high_tickets' 
				WHERE `name` = '{$fg[name]}'");
	
}

echo "<br>Upgrade Done.. Please delete this file now.<br />";
ob_end_flush();
?>