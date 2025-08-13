<?php

/*---------------------------------------------------------------------+
| User Support System, Integrated With Modernbill                      |
+----------------------------------------------------------------------+
| (C) Copyright 2002 Mark Carruth                                      |
+----------------------------------------------------------------------+
|                                                                      |
| cleanup.php :: Admin database cleanup utility                        |
|                                                                      |
+----------------------------------------------------------------------+
| Author: Mark Carruth (mcarruth@totalfreelance.com)                   |
+---------------------------------------------------------------------*/

/*    $Id: cleanup.php,v 1.00.0.1 05/11/2002 17:32:00 mark Exp $      */

# Get Includes

require_once "../includes/functions.php";       # Functions Library
require_once "../includes/conf.global.php";     # Configuration Settings

# Authorise the administrator

authadmin(1);

# New template

$template = new Template;

$template->template = "../includes/admin.inc";

# Connect to database

$db = new Database;
			
$db->Connect($CONF['dbname']);

//----------------------------------
# Check if a default value is set
# :: If there is a default value
#    for this script we're gonna
#    trick it into performing the default
#    value

if($CONF['db_cleanup'] != "") {
	
	$_SUBMIT['action'] = $CONF['db_cleanup'];
	$_SUBMIT['delete'] = 1;
	
}

//----------------------------------
# Check for a form submitall

if($_SUBMIT['delete'] == 1) {
	
	//----------------------------------
	# Do we need to save the default action
	
	if($_SUBMIT['rem'] == 1) {
		
		# Attempt to CHMOD the config file
		
		if(!is_writeable("../includes/conf.global.php")) {
			
			@chmod("../includes/conf.global.php","0777");
			
		}
		
		# Firstly backup the config file
		
		if(is_file("../includes/conf.global.bak")) {
			
			@unlink("../includes/conf.global.bak");
			
		}
		
		@copy("../includes/conf.global.php","../includes/conf.global.bak");
		
		# We need to save the option, we means we need to open the config file
		
		$contents = file("../includes/conf.global.php");
		
		# Replace the relevant part of the file
		
		$regex = "\$CONF\[\"db_cleanup\"\].+=.+\".+\";";
		
		$replace = "\$CONF[\"db_cleanup\"] = \"$_SUBMIT[action]\";\n";
		
		$i=0;
		
		foreach($contents as $line) {
			
			if(substr($line,0,19) == "\$CONF[\"db_cleanup\"]") {
				
				$contents[$i] = $replace;
				
			}
			
			$i++;
			
		}
								
		# Now put the file back together
		
		@unlink("../includes/conf.global.php");
	
		$fp = fopen("../includes/conf.global.php","w");
		
		fwrite($fp,implode($contents,""));
		
		fclose($fp);
		
	}
	
	//----------------------------------
	# Perform switch of different actions
	
	switch($_SUBMIT['action']) {
		
		case "obsoletes";
		
			//----------------------------------
			# Remove redundant database entries
			# and closed support tickets
			
			$ticket_query = "DELETE FROM `$CONF[table_prefix]tickets`
				        WHERE status = 'closed'";
			
			$news_query   = "DELETE FROM `$CONF[table_prefix]servernews`
				        WHERE servers = ''";
			
			//----------------------------------
			# Perform the queries
			
			$result = $db->Query($ticket_query);
			
			$result = $db->Query($news_query);	
			
			//----------------------------------
			# Print success message
			
			output("<div class=heading>Database Cleanup</div>Please select the database
        				clean up that you would like to perform:<br><br>");
			
			output("<font color=#006633><b>Success:</b> Obselete entries removed successfully.</font>");
			
			$template->createPage();
			
			exit();
			
		break;
		
		case "unique";
		
			//----------------------------------
			# Empty the whole god damned database,
			# except the admin table
			
			$result = $db->Query("DELETE FROM `$CONF[table_prefix]categories`");
			$result = $db->Query("DELETE FROM `$CONF[table_prefix]news`");
			$result = $db->Query("DELETE FROM `$CONF[table_prefix]servernews`");
			$result = $db->Query("DELETE FROM `$CONF[table_prefix]servers`");
			$result = $db->Query("DELETE FROM `$CONF[table_prefix]tickets`");
			$result = $db->Query("DELETE FROM `$CONF[table_prefix]livechats`");
			
			//----------------------------------
			# Print success message
			
			output("<div class=heading>Database Cleanup</div>Please select the database
        				clean up that you would like to perform:<br><br>");
			
			output("<font color=#006633><b>Success:</b> Database emptied successfully.</font>");
			
			$template->createPage();
			
			exit();
		
		break;
		
	}
	
}

output("<div class=heading>Database Cleanup</div>Please select the database
        clean up that you would like to perform:<br><br>".admininfobox("You will 
	be given <b>NO</b> further warning about emptying the database.")."<br>");

output("<table width=100% cellpadding=0 cellspacing=0>
		<form action='$PHP_SELF' method='post'>
		<input type=hidden name=delete value=1>
		<input type=hidden name=id value=$_SUBMIT[id]>
		<input type=hidden name=sid value=$_SUBMIT[sid]>
		<tr><td width=25 height=30><input type=radio name=action value='obsoletes'></td><td>Remove Obsolete
		News entries/Closed support tickets.</td></tr>
		<tr><td width=25  height=30><input type=radio name=action value='unique'></td><td>Empty the database (will
		remove everything except admin/user accounts)</td></tr>
		<tr><td colspan=2><hr color=$_TEMPLATE[border_color] size=1 noshade width=55% align=left></td></tr>
		<tr><td width=25 height=30><input type=checkbox name=rem value=1></td><td> Remember my decision</td></tr>");
		
		output("</table><br><input type=submit value='Perform Cleanup'>");

$template->createPage();

?>