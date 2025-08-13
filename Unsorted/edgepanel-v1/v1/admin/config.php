<?php

/*---------------------------------------------------------------------+
| User Support System, Integrated With Modernbill                      |
+----------------------------------------------------------------------+
| (C) Copyright 2002 Mark Carruth                                      |
+----------------------------------------------------------------------+
|                                                                      |
| config.php :: Admin script configuration                             |
|                                                                      |
+----------------------------------------------------------------------+
| Author: Mark Carruth (mcarruth@totalfreelance.com)                   |
+---------------------------------------------------------------------*/

/*     $Id: config.php,v 1.00.0.1 05/11/2002 17:56:59 mark Exp $      */

# Get Includes

require_once "../includes/functions.php";       # Functions Library
require_once "../includes/conf.global.php";     # Configuration Settings

# Authorise the administrator

authadmin(1);

# New template

$template = new Template;

$template->template = "../includes/admin.inc";

//----------------------------------
# Check if a form has been submitted

if($_SUBMIT['save'] == 1) {
	
	//----------------------------------
	# Do we need to backup the old config
	# file?
	
	if($_SUBMIT['backup'] == 1) {
		
		@copy("../includes/conf.global.php","../includes/conf.global.bak");
		
	}
	
	//----------------------------------
	# Starting building config file string
	
	$config_string = "<?php

/*---------------------------------------------------------------------+
| User Support System, Integrated With Modernbill                      |
+----------------------------------------------------------------------+
| (C) Copyright 2002 Mark Carruth                                      |
+----------------------------------------------------------------------+
|                                                                      |
| conf.global.php :: This file contains the main script configuration  |
| settings                                                             |
|                                                                      |
+----------------------------------------------------------------------+
| Author: Mark Carruth (mcarruth@totalfreelance.com)                   |
+---------------------------------------------------------------------*/

/*   \$Id: conf.global.php,v 0.10.0.1 20/09/2002 21:00:00 ssb Exp $    */

/*---------------------------------------------------------------------+
| NOTE: Due to the way in which PHP handles path information, any      |
| directory or file paths you specify can be either relative or        |
| absolute paths, but it is recommended, for reliability reasons, that |
| you always use absolute paths                                        |
/*--------------------------------------------------------------------*/
	
# Database Information
	
";
	
	$config_string .= "\$CONF[\"dbhost\"] = \"$_SUBMIT[dbhost]\";                  # Database Host

\$CONF[\"dbuser\"] = \"$_SUBMIT[dbuser]\";              # Database Username

\$CONF[\"dbpass\"] = \"$_SUBMIT[dbpass]\";                # Database Password

\$CONF[\"dbname\"] = \"$_SUBMIT[dbname]\";                # Name of Database

\$CONF[\"mbdbname\"] = \"$_SUBMIT[mbdbname]\";       # Modern Bill Database

\$CONF[\"table_prefix\"] = \"$_SUBMIT[table_prefix]\";                      # Table prefix

\$CONF[\"mbtable_prefix\"] = \"$_SUBMIT[mbtable_prefix]\";         # Modernbill Table Prefix";
	
	$config_string .= "

# Script Settings

\$CONF[\"template\"] = \"$_SUBMIT[template]\";  # Template File

\$CONF[\"sitename\"] = \"$_SUBMIT[sitename]\";                   # Site Title

\$CONF[\"numnews\"] = \"$_SUBMIT[numnews]\";         # Number of news items to display
	
\$CONF[\"livechat\"] = \"$_SUBMIT[livechat]\";
	
\$CONF[\"userdriver\"] = \"$_SUBMIT[userdriver]\";

\$CONF[\"script_name\"] = \"$CONF[script_name]\";
	
\$CONF[\"version\"] = \"$CONF[version]\";

# Saved Settings
# ----------------------------------------------------------------------
# The following are setting values that the admin has opted to save so
# they needed go through the same option processes over and over again,
# just like you can do in Windows. NOTE: All values, if left blank, will
# prompt the script to ask the admin

# SERVER NEWS DELETE
# ----------------------------------------------------------------------
# This is the server news delete. Possible values:
#
#    - all (delete news item from all servers)
#    - unique (delete from only this server)

\$CONF[\"sn_delete\"] = \"$CONF[sn_delete]\";

# ----------------------------------------------------------------------
# CLEANUP DATABASE
# ----------------------------------------------------------------------
# This is the cleanup database value. Possible values:
#
#    - obseletes (remove only obselete entries)
#    - unique (empty everything except admin table)

\$CONF[\"db_cleanup\"] = \"$CONF[db_cleanup]\";

?>";
	
	//----------------------------------
	# Now we have the monster string, we 
	# need to write it to the file
	
	$fp = fopen("../includes/conf.global.php","w");
	
	flock($fp,2);
	
	if(fwrite($fp,$config_string)) {
		
		//----------------------------------
		# Output success message and close
		# file pointer
		
		output("<div class=heading>Script Configuration</div>Please make any changes
        			to the script configuration and click 'Save Changes'.<br><br>");
		
		output("<font color=#006633><b>Success:</b> Your configuration changes have been saved.</font>");
		
		$template->createPage();
		
		fclose($fp);
		
		exit();
		
	}
	else {
		
		//----------------------------------
		# Output error message and restore backup
		
		output("<div class=heading>Script Configuration</div>Please make any changes
        			to the script configuration and click 'Save Changes'.<br><br>");
		
		output("<font color=#990000><b>Error:</b> Your configuration could not be saved. We have tried
			to restore the most recent backup of the config file, but if this fails, to restore
			a backup of the config file, rename <i>conf.global.bak</i> in the <i>includes</i> directory
		         to <i>conf.global.php</il>. If there is no backup, you must reinstall.</font>");
		
		$template->createPage();
		
		fclose($fp);
		
		//----------------------------------
		# Attempt to restore backup
		
		if(is_file("../includes/conf.global.bak")) {
			
			@copy("../includes/conf.global.bak","../includes/conf.global.php");
			
		}
		
		exit();
	
	}
	
}

//----------------------------------
# Output page header

output("<div class=heading>Script Configuration</div>Please make any changes
        to the script configuration and click 'Save Changes'.<br><br>".admininfobox("It is <b>strongly</b> recommended
        that you empty the database before changing the User Database Driver, as failure to do so will lead to security
        issues.")."<br>");

tableheading("Database Config");

output("<form action='$PHP_SELF' method='post'>");
output("<input type=hidden name=save value=1>");
output("<tr bgcolor=$_TEMPLATE[light_background]><td width=50% style=\"border-bottom: 1px solid $_TEMPLATE[border_color];border-left: 1px solid $_TEMPLATE[border_color];\">&nbsp;&nbsp;<b>Database Host:</b><br>&nbsp;&nbsp;Your database server</td><td width=50% style=\"border-bottom: 1px solid $_TEMPLATE[border_color];border-right: 1px solid $_TEMPLATE[border_color];\"><input type=text name=dbhost value=\"$CONF[dbhost]\"></td></tr>");
output("<tr bgcolor=$_TEMPLATE[dark_background]><td width=50% style=\"border-bottom: 1px solid $_TEMPLATE[border_color];border-left: 1px solid $_TEMPLATE[border_color];\">&nbsp;&nbsp;<b>Database Username:</b><br>&nbsp;&nbsp;Your login username</td><td width=50% style=\"border-bottom: 1px solid $_TEMPLATE[border_color];border-right: 1px solid $_TEMPLATE[border_color];\"><input type=text name=dbuser value=\"$CONF[dbuser]\"></td></tr>");
output("<tr bgcolor=$_TEMPLATE[light_background]><td width=50% style=\"border-bottom: 1px solid $_TEMPLATE[border_color];border-left: 1px solid $_TEMPLATE[border_color];\">&nbsp;&nbsp;<b>Database Password:</b><br>&nbsp;&nbsp;Your login password</td><td width=50% style=\"border-bottom: 1px solid $_TEMPLATE[border_color];border-right: 1px solid $_TEMPLATE[border_color];\"><input type=password name=dbpass value=\"$CONF[dbpass]\"></td></tr>");
output("<tr bgcolor=$_TEMPLATE[dark_background]><td width=50% style=\"border-bottom: 1px solid $_TEMPLATE[border_color];border-left: 1px solid $_TEMPLATE[border_color];\">&nbsp;&nbsp;<b>Database Name:</b><br>&nbsp;&nbsp;The Name of the Database</td><td width=50% style=\"border-bottom: 1px solid $_TEMPLATE[border_color];border-right: 1px solid $_TEMPLATE[border_color];\"><input type=text name=dbname value=\"$CONF[dbname]\"></td></tr>");
output("<tr bgcolor=$_TEMPLATE[light_background]><td width=50% style=\"border-bottom: 1px solid $_TEMPLATE[border_color];border-left: 1px solid $_TEMPLATE[border_color];\">&nbsp;&nbsp;<b>ModernBill Database:</b><br>&nbsp;&nbsp;The name of the ModernBill database</td><td width=50% style=\"border-bottom: 1px solid $_TEMPLATE[border_color];border-right: 1px solid $_TEMPLATE[border_color];\"><input type=text name=mbdbname value=\"$CONF[mbdbname]\"></td></tr>");
output("<tr bgcolor=$_TEMPLATE[dark_background]><td width=50% style=\"border-bottom: 1px solid $_TEMPLATE[border_color];border-left: 1px solid $_TEMPLATE[border_color];\">&nbsp;&nbsp;<b>Table Prefix:</b><br>&nbsp;&nbsp;Database Table Prefix</td><td width=50% style=\"border-bottom: 1px solid $_TEMPLATE[border_color];border-right: 1px solid $_TEMPLATE[border_color];\"><input type=text name=table_prefix value=\"$CONF[table_prefix]\"></td></tr>");
output("<tr bgcolor=$_TEMPLATE[light_background]><td width=50% style=\"border-bottom: 1px solid $_TEMPLATE[border_color];border-left: 1px solid $_TEMPLATE[border_color];\">&nbsp;&nbsp;<b>ModernBill Table Prefix:</b><br>&nbsp;&nbsp;(Usually not required)</td><td width=50% style=\"border-bottom: 1px solid $_TEMPLATE[border_color];border-right: 1px solid $_TEMPLATE[border_color];\"><input type=text name=mbtable_prefix value=\"$CONF[mbtable_prefix]\"></td></tr>");

output("</table><br>");

tableheading("Script Settings");

output("<tr bgcolor=$_TEMPLATE[light_background]><td width=50% style=\"border-bottom: 1px solid $_TEMPLATE[border_color];border-left: 1px solid $_TEMPLATE[border_color];\">&nbsp;&nbsp;<b>Template:</b><br>&nbsp;&nbsp;The path to the template file</td><td width=50% style=\"border-bottom: 1px solid $_TEMPLATE[border_color];border-right: 1px solid $_TEMPLATE[border_color];\"><input type=text name=template value=\"$CONF[template]\"></td></tr>");
output("<tr bgcolor=$_TEMPLATE[dark_background]><td width=50% style=\"border-bottom: 1px solid $_TEMPLATE[border_color];border-left: 1px solid $_TEMPLATE[border_color];\">&nbsp;&nbsp;<b>Site Name:</b><br>&nbsp;&nbsp;The title of the site</td><td width=50% style=\"border-bottom: 1px solid $_TEMPLATE[border_color];border-right: 1px solid $_TEMPLATE[border_color];\"><input type=text name=sitename value=\"".clear($CONF['sitename'])."\"></td></tr>");
output("<tr bgcolor=$_TEMPLATE[light_background]><td width=50% style=\"border-bottom: 1px solid $_TEMPLATE[border_color];border-left: 1px solid $_TEMPLATE[border_color];\">&nbsp;&nbsp;<b>News Limit:</b><br>&nbsp;&nbsp;Max number of news items to display</td><td width=50% style=\"border-bottom: 1px solid $_TEMPLATE[border_color];border-right: 1px solid $_TEMPLATE[border_color];\"><input type=text name=numnews value=\"$CONF[numnews]\"></td></tr>");

switch($CONF['livechat']) {
	
	case "on";
	
		$ons = "selected";
		
	break;
	
	case "off";
	
		$offs = "selected";
		
	break;
	
}

output("<tr bgcolor=$_TEMPLATE[dark_background]><td width=50% style=\"border-bottom: 1px solid $_TEMPLATE[border_color];border-left: 1px solid $_TEMPLATE[border_color];\">&nbsp;&nbsp;<b>Live Chat:</b><br>&nbsp;&nbsp;Status of the live chat module</td><td width=50% style=\"border-bottom: 1px solid $_TEMPLATE[border_color];border-right: 1px solid $_TEMPLATE[border_color];\"><select name=livechat><option value=on $ons>Enabled</option><option value=off $offs>Disabled</option></select></td></tr>");

switch($CONF['userdriver']) {
	
	case "modernbill";
	
		$mbs = "selected";
		
	break;
	
	case "database";
	
		$dbs = "selected";
		
	break;
	
}

output("<tr bgcolor=$_TEMPLATE[light_background]><td width=50% style=\"border-bottom: 1px solid $_TEMPLATE[border_color];border-left: 1px solid $_TEMPLATE[border_color];\">&nbsp;&nbsp;<b>User Database Driver:</b><br>&nbsp;&nbsp;Which user database is to be used?</td><td width=50% style=\"border-bottom: 1px solid $_TEMPLATE[border_color];border-right: 1px solid $_TEMPLATE[border_color];\"><select name=userdriver><option value=modernbill $mbs>ModernBill Database</option><option value=database $dbs>EdgePanel Database</option></select></td></tr>");


output("<tr><td colspan=2 height=35><input type=checkbox name=backup value=1 checked> Backup Old Configuration File</td></tr>");

output("</table><br><input type=submit value='Save Changes'></form>");

$template->createPage();

?>