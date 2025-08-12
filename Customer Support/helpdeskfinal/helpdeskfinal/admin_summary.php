<?php
//
// Project: Help Desk support system (Admin panel)
// Description: 
// 1. Summary page - Show some information about the helpdesk

require_once "includes/auth.php";
require_once "includes/db.php";
require_once "includes/tpl.php";
require_once "includes/funcs.php";
require_once "includes/const.php";

$page_title = "Admin Panel - Summary";

// User authentication
if($hduser['user_level'] != RANK_ADMIN)
{
	// If user NOT logged in, show message & redirect to index
	dialog("Your clearance is not high enough to access this section.", $page_title);
}

$tpl_summary = new tpl("tpl/admin_summary.tpl");

// Get stats
$sql_stats = "SELECT COUNT(t1.ticket_id) AS calls_total,
					COUNT(t2.ticket_status) AS calls_open,
					COUNT(t3.ticket_status) AS calls_closed, 
					COUNT(t4.ticket_status) AS calls_resolved,
					COUNT(t5.ticket_status) AS calls_onhold,
					COUNT(t6.ticket_status) AS calls_critical,
					COUNT(t7.ticket_status) AS calls_high,
					COUNT(t8.ticket_status) AS calls_normal,
					COUNT(t9.ticket_status) AS calls_low
			   FROM $TABLE_TICKETS AS t1
			   LEFT JOIN $TABLE_TICKETS AS t2 ON (t2.ticket_id = t1.ticket_id AND t2.ticket_status =" . STATUS_OPEN .")
			   LEFT JOIN $TABLE_TICKETS AS t3 ON (t3.ticket_id = t1.ticket_id AND t3.ticket_status =" . STATUS_CLOSED . ")
			   LEFT JOIN $TABLE_TICKETS AS t4 ON (t4.ticket_id = t1.ticket_id AND t4.ticket_status =" . STATUS_RESOLVED . ")
			   LEFT JOIN $TABLE_TICKETS AS t5 ON (t5.ticket_id = t1.ticket_id AND t5.ticket_status =" . STATUS_ONHOLD . ")
			   LEFT JOIN $TABLE_TICKETS AS t6 ON (t6.ticket_id = t1.ticket_id AND t6.ticket_priority =" . PRIORITY_CRITICAL . ")
			   LEFT JOIN $TABLE_TICKETS AS t7 ON (t7.ticket_id = t1.ticket_id AND t7.ticket_priority =" . PRIORITY_HIGH . ")
			   LEFT JOIN $TABLE_TICKETS AS t8 ON (t8.ticket_id = t1.ticket_id AND t8.ticket_priority =" . PRIORITY_NORMAL . ")
			   LEFT JOIN $TABLE_TICKETS AS t9 ON (t9.ticket_id = t1.ticket_id AND t9.ticket_priority =" . PRIORITY_LOW . ")";

$r_stats = mysql_query($sql_stats) or
				error("Cannot get summary.");
$db_stats = mysql_fetch_object($r_stats);

$tpl_summary_tags = array( "calls_open"		=> $db_stats->calls_open,
						   "calls_closed"	=> $db_stats->calls_closed,
						   "calls_resolved" => $db_stats->calls_resolved,
						   "calls_onhold"	=> $db_stats->calls_onhold,
						   "calls_total"	=> $db_stats->calls_total,
						   "calls_critical" => $db_stats->calls_critical,
						   "calls_high"		=> $db_stats->calls_high,
						   "calls_normal"	=> $db_stats->calls_normal,
						   "calls_low"		=> $db_stats->calls_low );
$tpl_summary->parse($tpl_summary_tags);

echo build_page(content_box($tpl_summary->parsed, $page_title, 1), $page_title);
?>