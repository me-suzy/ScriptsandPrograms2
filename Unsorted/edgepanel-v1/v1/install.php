<?php

/*---------------------------------------------------------------------+
| User Support System, Integrated With Modernbill                      |
+----------------------------------------------------------------------+
| (C) Copyright 2002 Mark Carruth                                      |
+----------------------------------------------------------------------+
|                                                                      |
| install.php :: Install script (duh)                                  |
|                                                                      |
+----------------------------------------------------------------------+
| Author: Mark Carruth (mcarruth@totalfreelance.com)                   |
+---------------------------------------------------------------------*/

/*    $Id: install.php,v 1.00.0.1 05/11/2002 21:08:34 mark Exp $      */

# Get Includes

require_once "./includes/functions.php";             # Functions Library

# Start new template

$template = new Template;

$template->template = "./includes/install.inc";

$template->bypass_news = 1;

//-----------------------------------
# Startup Cookie Step System

if(empty($HTTP_COOKIE_VARS["step"])) {
	
	setcookie("step","1");
	$step=1;
	
}
else {
	
	$step = $HTTP_COOKIE_VARS["step"];
	
}

//-----------------------------------
# Function To Return the Installation Script Side Nav

function side_nav() {
	
	global $step;
	
	$elements = array("System Check","Database Config","General Config","Admin Account","Install");
	
	$output = "<table width=90% cellpadding=0 cellspacing=0>";
	
	$i=1;
	
	foreach($elements as $element) {
		
		$output .= "<tr><td>&nbsp;&nbsp;&nbsp;";
		
		if($step == $i) {
			
			$output .= "<span class=heading>";
			   
		}
		else {

			$output .= "<span class=sub>";
			
		}

		$output .= "&raquo; $element</span></td></tr>";
		
		$i++;
		
	}
	
	$output .= "</table>";
	
	return $output;
	
}

//-----------------------------------
# Set Side Nav Parameter

$template->setParameter("SIDE_NAV",side_nav());

# Display Step!
# :: Switch for all the steps

switch($step) {
	
	case "1";

		output("<div class=heading>System Check</div>Welcome to the script installation! 
			We will now perform various checks on your system to ensure that the 
		         script can be installed correctly on this system.");
	
		# Step One
		# :: Run Basic checks on the system
		#    and ensure that we can install
		# ---------------------------------
				
		output("<ul><table width=58% cellpadding=0 cellspacing=0>");
						
		#  : Check the presence of conf.global.php
		
			output("<tr><td width=30%><b>&raquo; Checking conf.global.php:</b></td>");

			if(is_file("./includes/conf.global.php")) {
			
				output("<td width=20%><font color=#006633><b>Passed</b></td></tr>");
			
			}
			else {
			
				output("<td width=20%><font color=#990000><b>Failed</b></td></tr>");
				$error=1;
			
			}
			
		#  : Check write permissions of conf.global.php
		
			output("<tr><td width=30%><b>&raquo; Checking file permissions:</b></td>");
			
			if(is_writeable("./includes/conf.global.php")) {
				
				output("<td width=20%><font color=#006633><b>Passed</b></td></tr>");
			
			}
			else {
			
				output("<td width=20%><font color=#990000><b>Failed</b></td></tr>");
				$error=1;
			
			}
			
		#  : Check PHP version
		
			output("<tr><td width=30%><b>&raquo; Checking PHP version:</b></td>");
			
			$version = substr(PHP_VERSION,0,1);
			
			if((integer)$version >= 4) {
				
				output("<td width=20%><font color=#006633><b>Passed</b></td></tr>");
			
			}
			else {
			
				output("<td width=20%><font color=#990000><b>Warning</b></td></tr>");
				$version_error=1;
			
			}
			
		#  : See if we have any errors,
		#    if not, allow them to continue
		
		if(($error != 1)) {
			
			output("</table></ul>");
			
			if($version_error == 1) {

				output("It has been detected that you are using a PHP version earlier
					than PHP 4. The script has not been tested with PHP 3 or earlier,
					so it is not recommended you continue. You do so at your own risk.
					<b><a href='$PHP_SELF'>&raquo; Continue</a></b><br><br>");
				
				setcookie("step","2");
			
				$template->createPage();
			
				exit();

			}
			
			output("No errors were found with your system configuration: 
			        <b><a href='$PHP_SELF'>&raquo; Continue</a></b><br><br>");
			
			setcookie("step","2");
			
			$template->createPage();
			
			exit();
			
		}
		
		#  : There were errors, so lets show them some tips
		
		output("</table></ul><div class=heading>&raquo; Assistance</div>
			The script has encountered one or more errors cannot continue. Please ensure that
		         the conf.global.php can be found in the <i>includes</i> folder on this web server,
		         and that the file has write permissions (CHMOD to 0755 or 0777 should work fine).<br><br>");
		
		if($version_error == 1) {
			
			output("Additionally, it was detected that you are using a PHP version earlier than
			        version 4 (i.e. version 3 or earlier). Whilst we have tried to allow for
			        previous PHP versions, this script has not yet been tested using PHP 3 or earlier,
			        so it is not recommended that you continue with the installation. If you choose
			        to do so, you do so at your own risk, and we cannot be held responsible for any
			        damage that may occur to your server.<br><br>");
			
		} 
		
	break;
	
	
	
	
	
	case "2";
	
		# Database configuration
		# :: See if a form has been submitted
		
		if($_SUBMIT['submit'] == 1) {
			
			# Check we have all required fields
			
			$req = array("dbhost","dbuser","dbname");
			
			foreach($req as $field) {
				
				if(empty($_SUBMIT[$field])) {
					
					$error = 1;
					
				}
				
			}
			
			# Test MySQL configuration
			
			$connection = @mysql_connect($_SUBMIT['dbhost'],$_SUBMIT['dbuser'],$_SUBMIT['dbpass']);
			$database = @mysql_select_db($_SUBMIT['dbname']);
			
			if(!$connection or !$database) {
				
				$cerror = 1;
				
			}
			
			# If we have no errors, continue to step three
			
			//-----------------------------------
			
			if(($error != 1) && ($cerror != 1)) {
				
				# There have been no errors,
				# so set the cookie to step three and redirect
				
				# First, though, we must store the entered data!
				
				$data = array($_SUBMIT['dbhost'],
					     $_SUBMIT['dbuser'],
					     $_SUBMIT['dbpass'],
					     $_SUBMIT['dbname'],
					     $_SUBMIT['mbdbname'],
					     $_SUBMIT['table_prefix'],
					     $_SUBMIT['mbtable_prefix']);
					     
				$data_string = join($data,":::");
					     
				# Set the data cookie
				
				setcookie("data",$data_string);
				
				# Set the step cookie
				
				setcookie("step","3");
				
				# Redirect
				
				header("Location: install.php");
				
				exit();
				
			}
			
			//-----------------------------------
				
					
		}
		
		# Database configuration
		# :: Ask for database details
		
		//-----------------------------------
		
		output("<div class=heading>Database Configuration</div>Please enter your database
			configuration that will be used throughout the script.");
		
		//-----------------------------------
		
		if($error == 1) {
			
			output("<br><br><font color=#990000><b>Error:</b> You did not fill in all required fields.</font>");
			
		}
		
		if($cerror == 1) {
			
			if($error != 1) {
				
				output("<br>");
				
			}
			
			output("<br><font color=#990000><b>SQL Error:</b> A connection could not be made with the specified SQL config.
			        Please ensure that the database name you have entered exists.</font>");
			
		}
		
		//-----------------------------------
		
		output("<br><br>");
		
		tableheading("Database Config");
		
		output("<form action='$PHP_SELF' method='post'>
			<input type=hidden name=submit value=1>");
		
		output("<tr height=30 bgcolor=$_TEMPLATE[light_background]><td $left_border width=50%>
			<b>&nbsp;&nbsp;SQL Host:</b> (*)</td><td $right_border width=50%>
			<input type=text name=dbhost size=25 value='localhost'></td></tr>");
		
		output("<tr height=30 bgcolor=$_TEMPLATE[dark_background]><td $left_border width=50%>
			<b>&nbsp;&nbsp;SQL Username:</b> (*)</td><td $right_border width=50%>
			<input type=text name=dbuser size=25 value='$_SUBMIT[dbuser]'></td></tr>");

		output("<tr height=30 bgcolor=$_TEMPLATE[light_background]><td $left_border width=50%>
			<b>&nbsp;&nbsp;SQL Password:</b></td><td $right_border width=50%>
			<input type=text name=dbpass size=25></td></tr>");
		
		output("<tr height=30 bgcolor=$_TEMPLATE[dark_background]><td $left_border width=50%>
			<b>&nbsp;&nbsp;SQL Database:</b> (*)</td><td $right_border width=50%>
			<input type=text name=dbname size=25 value='$_SUBMIT[dbname]'></td></tr>");

		output("<tr height=30 bgcolor=$_TEMPLATE[light_background]><td $left_border width=50%>
			<b>&nbsp;&nbsp;SQL Modernbill Database:</b></td><td $right_border width=50%>
			<input type=text name=mbdbname size=25 value='$_SUBMIT[mbdbname]'></td></tr>");

		output("<tr height=30 bgcolor=$_TEMPLATE[dark_background]><td $left_border width=50%>
			<b>&nbsp;&nbsp;SQL Table Prefix:</b> (Can be blank)</td><td $right_border width=50%>
			<input type=text name=table_prefix size=25 value='$_SUBMIT[table_prefix]'></td></tr>");

		output("<tr height=30 bgcolor=$_TEMPLATE[light_background]><td $left_border width=50%>
			<b>&nbsp;&nbsp;SQL Modernbill Table Prefix:</b></td><td $right_border width=50%>
			<input type=text name=mb_table_prefix size=25 value='$_SUBMIT[mbtable_prefix]'></td></tr>");			
		
		output("</table><br><input type=submit value='Continue »'></form>");
		
		//-----------------------------------
		
	break;
	
	
	
	case "3";
	
		//-----------------------------------
		# Step Three
		# :: Check for form Submittal
		
		if($_SUBMIT['submit'] == 1) {
			
			# Check we have all required fields
			
			$req = array("sitename","template","numnews");
			
			foreach($req as $field) {
				
				if(empty($_SUBMIT[$field])) {
					
					$error=1;
					
				}
				
			}
			
			//-----------------------------------
			
			# If there's no errors, set the cookie and redirect
			
			if($error != 1) {
				
				# Construct the data cookie
				
				$data = array($_SUBMIT['sitename'],
					     $_SUBMIT['template'],
					     $_SUBMIT['numnews'],
					     $_SUBMIT['livechat'],
					     $_SUBMIT['userdriver']);
					     
				$data_string = join($data,":::");
				
				# Set the data cookie
				
				setcookie("gen_data",$data_string);
				
				# Set the step cookie
				
				setcookie("step","4");
				
				# Redirect
				
				header("Location: install.php");
				
				exit();
				
			}
			
			//-----------------------------------
				
		}
		
		//-----------------------------------
		# Step Three
		# :: General Script Configuration
				
		output("<div class=heading>General Configuration</div>Please enter your desired
		        script configuration.<br><br>");
		
		//-----------------------------------
		
		if($error == 1) {
			
			output("<font color=#990000><b>Error:</b> You did not fill in all
			        required fields.</font><br><br>");
			
		}
		
		//-----------------------------------
		# Step Three
		# :: Print the input form

		tableheading("General Config");
		
		output("<form action='$PHP_SELF' method='post'>
			<input type=hidden name=submit value='1'>");
		
		output("<tr bgcolor=$_TEMPLATE[light_background] height=30><td $left_border width=50%>
		        <b>&nbsp;&nbsp;Site Name:</b> (*)</td><td $right_border width=50%>
		        <input type=text name=sitename value='Your Site Name' size=25></td></tr>");
		
		output("<tr bgcolor=$_TEMPLATE[dark_background] height=30><td $left_border width=50%>
		        <b>&nbsp;&nbsp;Template File:</b> (*)</td><td $right_border width=50%>
		        <input type=text name=template value='./includes/template.inc' size=25></td></tr>");
		
		output("<tr bgcolor=$_TEMPLATE[light_background] height=30><td $left_border width=50%>
		        <b>&nbsp;&nbsp;Number of News Items To Display:</b> (*)</td><td $right_border width=50%>
		        <input type=text name=numnews value='5'></td></tr>");

		output("<tr bgcolor=$_TEMPLATE[dark_background] height=30><td $left_border width=50%>
		        <b>&nbsp;&nbsp;Live Chat Module:</b> (*)</td><td $right_border width=50%>
		        <select name=livechat><option value=on>Enabled</option><option value=off>Disabled</option>
			</select></td></tr>");

		output("<tr bgcolor=$_TEMPLATE[light_background] height=30><td $left_border width=50%>
		        <b>&nbsp;&nbsp;User Database Driver:</b> (*)</td><td $right_border width=50%>
		        <select name=userdriver><option value=modernbill>Modernbill Database</option><option value=database>EdgePanel Database</option>
			</select></td></tr>");		
		
		output("</table><br><input type=submit value='Continue »'></form>");

		//-----------------------------------	
		
	break;
	
	
	case "4";
	
		//-----------------------------------
		# Step Four
		# :: Check for a form submittal
		
		if($_SUBMIT['submit'] == 1) {
			
			//-----------------------------------
			# Check we have all fields
			
			$req = array("username","password");
			
			foreach($req as $field) {
				
				if(empty($_SUBMIT[$field])) {
					
					$error = 1;
					
				}
				
			}
			
			//-----------------------------------
			# If no error, redirect to final step
			
			if($error != 1) {
				
				//-----------------------------------
				# Firstly construct the data for the
				# data cookie
				
				$data = array($_SUBMIT['username'],
					     $_SUBMIT['password']);
					     
				$data_string = join($data,":::");
				
				//-----------------------------------
				# Set the data cookie
				
				setcookie("a_data",$data_string);
				
				//-----------------------------------
				# Set the step cookie
				
				setcookie("step","5");
				
				//-----------------------------------
				# Redirect
				
				header("Location: install.php");
				
				exit();
				
			}
			
			//-----------------------------------
			
		}		
	
		//-----------------------------------
		# Step Four
		# :: Setup Admin Account
		
		output("<div class=heading>Admin Account</div>Please enter the details you would
			like for your master admin account.<br><br>");
		
		//-----------------------------------
		
		if($error == 1) {
			
			output("<font color=#990000><b>Error:</b> You must fill in both fields to continue</font><br><br>");
			
		}
		
		//-----------------------------------
		
		tableheading("Admin Account");
		
		output("<form action='$PHP_SELF' method='post'>
			<input type=hidden name=submit value='1'>");

		output("<tr bgcolor=$_TEMPLATE[light_background] height=30><td $left_border width=50%>
			<b>&nbsp;&nbsp;Username: </b>(*)</td><td width=50% $right_border>
			<input type=text name=username></td></tr>");
		
		output("<tr bgcolor=$_TEMPLATE[dark_background] height=30><td $left_border width=50%>
			<b>&nbsp;&nbsp;Password: </b>(*)</td><td width=50% $right_border>
			<input type=password name=password></td></tr>");
		
		output("</table><br><input type=submit value='Continue »'></form>");
		
		//-----------------------------------	
		
	break;
	
	
	case "5";
	
		//-----------------------------------
		# Step Five
		# :: The Installer! Install the lot
		
		//-----------------------------------
		
		output("<div class=heading>Installation</div>Please wait as we
			attempt to install the script using the settings you
			have provided us with.<br><br>");
		
		//-----------------------------------
		
		output("<ul><table width=58% cellpadding=0 cellspacing=0>");
		
		//-----------------------------------
		# Write config file
		
		output("<tr><td width=30%><b>&raquo; Writing configuration file:</b></td>");
		
		$data = explode(":::",$HTTP_COOKIE_VARS['data']);
		$gdata = explode(":::",$HTTP_COOKIE_VARS['gen_data']);
		$adata = explode(":::",$HTTP_COOKIE_VARS['a_data']);

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

/*   \$Id: conf.global.php,v 1.00.0.1 05/11/2002 20:48:02 ssb Exp $    */

/*---------------------------------------------------------------------+
| NOTE: Due to the way in which PHP handles path information, any      |
| directory or file paths you specify can be either relative or        |
| absolute paths, but it is recommended, for reliability reasons, that |
| you always use absolute paths                                        |
/*--------------------------------------------------------------------*/
	
# Database Information
	
";
	
$config_string .= "\$CONF[\"dbhost\"] = \"$data[0]\";    # Database Host

\$CONF[\"dbuser\"] = \"$data[1]\";                   # Database Username

\$CONF[\"dbpass\"] = \"$data[2]\";                   # Database Password

\$CONF[\"dbname\"] = \"$data[3]\";                    # Name of Database

\$CONF[\"mbdbname\"] = \"$data[4]\";              # Modern Bill Database

\$CONF[\"table_prefix\"] = \"$data[5]\";                  # Table prefix

\$CONF[\"mbtable_prefix\"] = \"$data[6]\";     # Modernbill Table Prefix";
	
	$config_string .= "

# Script Settings

\$CONF[\"template\"] = \"$gdata[1]\";                # Template File

\$CONF[\"sitename\"] = \"$gdata[0]\";                   # Site Title

\$CONF[\"numnews\"] = \"$gdata[2]\";   # Number of news items to display
	
\$CONF[\"livechat\"] = \"$gdata[3]\";

\$CONF[\"userdriver\"] = \"$gdata[4]\";

\$CONF[\"script_name\"] = \"EdgePanel&trade; Version 1.00\";
	
\$CONF[\"version\"] = \"1.00\";

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

\$CONF[\"sn_delete\"] = \"\";

# ----------------------------------------------------------------------
# CLEANUP DATABASE
# ----------------------------------------------------------------------
# This is the cleanup database value. Possible values:
#
#    - obseletes (remove only obselete entries)
#    - unique (empty everything except admin table)

\$CONF[\"db_cleanup\"] = \"\";

?>";
	
	# Now we gotta write the config file
	
	$fp = fopen("./includes/conf.global.php","w");
	
	flock($fp,2);
	
	if(fwrite($fp,$config_string)) {
		
		//----------------------------------
		# Output success message and close
		# file pointer
		
		output("<td width=20%><font color=#006633><b>Complete</b></font></td></tr>");
				
	}
	else {
		
		//----------------------------------
		# Output error message and restore backup
		
		output("<td width=20%><font color=#990000><b>Failed</b></font></td></tr>");
	
	}
		
		# Now we gotta create the database tables
		
		output("<tr><td width=30%><b>&raquo; Creating Database Tables:</b></td>");
		
		mysql_connect("$data[0]","$data[1]","$data[2]");
		
		mysql_select_db("$data[3]");
		
		$result = mysql_query("CREATE TABLE `$data[5]admins` (
  				     id int(11) NOT NULL auto_increment,
  				     username varchar(200) NOT NULL default '',
  				     password varchar(200) NOT NULL default '',
  				     plain_password varchar(200) NOT NULL default '',
  				     level int(3) NOT NULL default '0',
  				     in_chat int(11) NOT NULL default '0',
  				     PRIMARY KEY  (id),
  				     UNIQUE KEY username (username)
				     ) TYPE=MyISAM;");
		
		$result1 = mysql_query("CREATE TABLE `$data[5]categories` (
  				     id int(15) NOT NULL auto_increment,
  				     title varchar(200) NOT NULL default '',
  				     is_scat int(2) NOT NULL default '0',
  				     parent_id int(15) NOT NULL default '0',
  				     PRIMARY KEY  (id)
				     ) TYPE=MyISAM;");
		
		$result2 = mysql_query("CREATE TABLE `$data[5]news` (
  				      id int(10) NOT NULL auto_increment,
  				      title varchar(200) NOT NULL default '',
  				      description longtext NOT NULL,
  				      dateadded int(15) NOT NULL default '0',
  				      addedby varchar(50) NOT NULL default '',
  				      PRIMARY KEY  (id)
				      ) TYPE=MyISAM;");
		
		$result3 = mysql_query("CREATE TABLE `$data[5]servernews` (
  				      id int(15) NOT NULL auto_increment,
  				      subject varchar(200) NOT NULL default '',
  				      message longtext NOT NULL,
  				      dateadded int(15) NOT NULL default '0',
  				      addedby varchar(50) NOT NULL default '',
  				      servers varchar(200) NOT NULL default '',
  				      PRIMARY KEY  (id)
				      ) TYPE=MyISAM;");
		
		$result4 = mysql_query("CREATE TABLE `$data[5]servers` (
  				      id int(15) NOT NULL auto_increment,
  				      title varchar(200) NOT NULL default '',
  				      ip varchar(20) NOT NULL default '',
  				      type varchar(200) NOT NULL default '',
  				      mbuserid varchar(15) NOT NULL default '0',
  				      web_port int(11) NOT NULL default '0',
  				      ssh_port int(11) NOT NULL default '0',
  				      telnet_port int(11) NOT NULL default '0',
  				      ftp_port int(11) NOT NULL default '0',
  				      smtp_port int(11) NOT NULL default '0',
  				      pop3_port int(11) NOT NULL default '0',
  				      mysql_port int(11) NOT NULL default '0',
  				      PRIMARY KEY  (id)
				      ) TYPE=MyISAM;");
		
		$result5 = mysql_query("CREATE TABLE `$data[5]tickets` (
  				      id int(11) NOT NULL auto_increment,
  				      subject varchar(200) NOT NULL default '',
  				      priority varchar(200) NOT NULL default '',
  				      message longtext NOT NULL,
  				      parent_id int(11) NOT NULL default '0',
  				      datestarted int(15) NOT NULL default '0',
  				      `status` varchar(200) NOT NULL default '',
  				      is_reply int(11) NOT NULL default '0',
  				      `category` varchar(200) NOT NULL default '',
  				      user_id int(15) NOT NULL default '0',
  				      admin_id int(15) NOT NULL default '0',
  				      PRIMARY KEY  (id)
				      ) TYPE=MyISAM;");
		
		$result6 = mysql_query("CREATE TABLE `$data[5]livechats` (
  				      id int(15) NOT NULL auto_increment,
  				      script longtext NOT NULL,
  				      admin_id int(15) NOT NULL default '0',
  				      user_id int(15) NOT NULL default '0',
  				      datestarted int(11) NOT NULL default '0',
  				      lastactivity int(11) NOT NULL default '0',
  				      closed int(1) NOT NULL default '0',
  				      PRIMARY KEY  (id)
				      ) TYPE=MyISAM;");
		
		
		$result7 = mysql_query("CREATE TABLE `$data[5]users` (
  				      id int(15) NOT NULL auto_increment,
  				      username varchar(200) NOT NULL default '',
  				      password varchar(200) NOT NULL default '',
  				      plain_password varchar(200) NOT NULL default '',
  				      email varchar(200) NOT NULL default '',
  				      PRIMARY KEY  (id),
				      UNIQUE KEY id (id)
				      ) TYPE=MyISAM;");
		
		if($result and $result1 and $result2 and $result3 and $result4 and $result5 and $result6 and $result7) {
			
			output("<td width=20%><font color=#006633><b>Complete</b></font></td></tr>");
			
		}
		else {
			
			output("<td width=20%><font color=#990000><b>Failed</b></font></td></tr>");
			
		}
		
		# Create admin account
		
		output("<tr><td width=30%><b>&raquo; Creating Admin Account:</b></td>");
		
		$result = mysql_query("INSERT INTO `$data[5]admins` VALUES
				     ('',
				      '$adata[0]',
				      '".crypt($adata[1],"DD")."',
				      '',
				      '1',
				      '0')");
		
		if($result) {
			
			output("<td width=20%><font color=#006633><b>Complete</b></font></td></tr>");
			
		}
		else {
			
			output("<td width=20%><font color=#990000><b>Failed</b></font></td></tr>");
			
		}
		
		//-----------------------------------
				
		output("</table></ul>The install is now complete. If any of the above checks say 'Failed',
		it is recommended that you empty the database and run the installer again. It is also recommended
		that you now delete this script, <i>install.php</i>. <a href='admin/login.php'>&raquo; Admin Login</a>");
		
		//-----------------------------------

		
	break;
	
}		

$template->createPage();

?>