<?php

/*---------------------------------------------------------------------+
| User Support System, Integrated With Modernbill                      |
+----------------------------------------------------------------------+
| (C) Copyright 2002 Mark Carruth                                      |
+----------------------------------------------------------------------+
|                                                                      |
| viewtickets.php :: Allows the user to view his support tickets any   |
|                    any replies relevant to thos tickets              | 
|                                                                      |
+----------------------------------------------------------------------+
| Author: Mark Carruth (mcarruth@totalfreelance.com)                   |
+---------------------------------------------------------------------*/

/*   $Id: viewtickets.php,v 1.00.0.1 04/11/2002 19:25:38 mark Exp $   */

# Get Includes

require_once "./includes/functions.php";        # Functions Library
require_once "./includes/conf.global.php";      # Configuration Settings

# Check the user is authorised

auth_user();

# Create a new template object

$template = new Template;

# Database connection

$db = new Database;

$db->Connect($CONF['dbname']);

//----------------------------------

output("<div class=heading>View Support Tickets</div><br>Here is a listing
	of your support tickets and any replies you may have received.<br><br>");

//----------------------------------

$result = $db->Query("SELECT * FROM `$CONF[table_prefix]tickets`
		    WHERE user_id = '".$HTTP_COOKIE_VARS["user_data"]["2"]."'
		    AND is_reply = '0'
		    AND parent_id = '0'
		    ORDER BY `datestarted` DESC");

//----------------------------------

if($db->num_rows($result) == 0) {
	
	output("<ul><li>You have no support tickets on the system.</li></ul>");
	
}

//----------------------------------
# Print out all support tickets

output("<table cellpadding=0 cellspacing=0 style=\"border: 1px solid #495760\" width=100% bgcolor=#CFD4D6>");

while($row_info = $db->fetch_row($result)) {
	
	output("<tr height=20><td>&nbsp;&nbsp;<b><a href='viewticket.php?id=$row_info[id]'>$row_info[subject]</a></b> :: "
	        .date("m/d/y G:i:s",$row_info['datestarted'])."</td></tr>");
	
	//----------------------------------
	# Now we check for replies for this ticket
	
	$replies = $db->Query("SELECT * FROM `$CONF[table_prefix]tickets`
		    	     WHERE user_id = '".$HTTP_COOKIE_VARS["user_data"]["2"]."'
			     AND parent_id = '$row_info[id]'
		    	     ORDER BY `datestarted` ASC");
	
	if($db->num_rows($replies) == 0) {
		
		output("<tr height=20><td>&nbsp;&nbsp;&nbsp;Currently No Replies</td></tr>");
		
	}
	
	while($reply_info = $db->fetch_row($replies)) {
		
		output("<tr height=20><td>&nbsp;&nbsp;&nbsp;<img src=i/tree-arrow.gif> <a href='viewticket.php?id=$reply_info[id]'>$reply_info[subject]</a>
		:: ".date("m/d/y G:i:s",$reply_info['datestarted'])."</td></tr>");
		
	}
	
	//----------------------------------
	
}

output("</table><br><a href='index.php'>&raquo; Return</a>");

//----------------------------------

$template->createPage();

?>