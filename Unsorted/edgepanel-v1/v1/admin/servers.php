<?php

/*---------------------------------------------------------------------+
| User Support System, Integrated With Modernbill                      |
+----------------------------------------------------------------------+
| (C) Copyright 2002 Mark Carruth                                      |
+----------------------------------------------------------------------+
|                                                                      |
| servers.php :: Admin server management page                          |
|                                                                      |
+----------------------------------------------------------------------+
| Author: Mark Carruth (mcarruth@totalfreelance.com)                   |
+---------------------------------------------------------------------*/

/*    $Id: servers.php,v 1.00.0.1 08/10/2002 19:07:25 mark Exp $      */

# Get Includes

require_once "../includes/functions.php";       # Functions Library
require_once "../includes/conf.global.php";     # Configuration Settings

# Authorise the administrator

authadmin(2);

# New template

$template = new Template;

$template->template = "../includes/admin.inc";

# Check a form has been submitted, and if so process it

if($_SUBMIT['add'] == 1) {
	
	# Add the server to the database
	
	$db = new Database;
	
	$db->Connect($CONF['dbname']);
	
	$query = "INSERT INTO `$CONF[table_prefix]servers`
	          VALUES ('',
		 '".addslashes($_SUBMIT['title'])."',
	          '$_SUBMIT[ip]',
		 '$_SUBMIT[type]',
		 '$_SUBMIT[mbuserid]',
		 '$_SUBMIT[web_port]',
		 '$_SUBMIT[ssh_port]',
		 '$_SUBMIT[telnet_port]',
		 '$_SUBMIT[ftp_port]',	 
		 '$_SUBMIT[smtp_port]',
		 '$_SUBMIT[pop3_port]',
		 '$_SUBMIT[mysql_port]')";
	
	if($db->Query($query)) {
		
		# The query was successful, so redirect
		
		header("Location: servers.php");
		
		exit();
		
	}
	else {
		
		# Query unsuccessful, print an error message
		
		output("<div class=heading>&raquo; Create a New Server</div>
		<font color=#990000><b>Error:</b></font> Your server could not be created. MySQL Said:
		".mysql_error().". Please try again shortly.");
		
		$template->createPage();
		
		exit();
		
	}
	
}

output("<div class=heading>Server Management</div>Please select a server
       that you would like to edit/remove, or refer to the bottom of the
       page to add a new server.");

if(isset($_SUBMIT['es'])) {
	
	# Success message needs printing
	
	if($_SUBMIT['es'] == 1) {
		
		output("<br><br><font color=#006633><b>Success:</b> (1) server(s) updated");
		
	}
	
}

output("<br><br><table width=100% cellpadding=0 cellspacing=0><tr>
        <td colspan=2 style=\"border-bottom: 1px solid $_TEMPLATE[border_color]\">
        <table width=115 cellspacing=0 cellpadding=0>
        <tr><td bgcolor=$_TEMPLATE[border_color]>
        <font color=white><B><center>Servers</center></b></font></td></tr>
        </table></td></tr>");

# Create a new database object/connection, so we can print a listing of the
# servers in the database

$db = new Database;

$db->Connect($CONF['dbname']);

$result = $db->Query("SELECT * FROM `$CONF[table_prefix]servers` ORDER BY `title` ASC");

while($row_info = $db->fetch_row($result)) {
	
	# Print all servers
	
	$indicator == 0 ? $color=$_TEMPLATE['light_background'] : $color = $_TEMPLATE['dark_background'];
	$indicator == 0 ? $indicator = 1 : $indicator = 0;
	
	# Is this server being edited ?? If so print only the editing form
	
	if($_SUBMIT['edit'] == $row_info['id']) {
	
		# Show the editing form
		
		output("<tr bgcolor=$color><td height=20 width=50% style=\"border-left: 1px solid $_TEMPLATE[border_color]\">
		&nbsp;&nbsp;<img src=../i/news-item.jpg align=bottom>&nbsp;$row_info[title]</td>
		<td style=\"border-right: 1px solid $_TEMPLATE[border_color]\">
		[ <a href='servers.php'>Close</a> | <a href='deleteserver.php?id=$row_info[id]'>Remove This Server</a> ]</td>
		</tr>");
		
		# This is elaborate code for what it is achieving, but it is
		# adding scope for development
		
		$types = array("Virtual","Dedicated");
		
		foreach($types as $type) {
			
			# For each server type, see if it is selected so we get the right
			# value in our jump menu
			
			if($row_info['type'] == $type) {
				
				$string = $type . "_selected";
				
				$$string = "selected";
				
			}
			
		}
	
		output("<script language=Javascript>
        			function validateForm1() {

                 			if(document.editForm.title.value == \"\") {

                          			alert(\"You must enter a value in the title field.\");
                          			document.editForm.title.focus();
                          			return false;

                 			}

        			}
        			</script>		
		<tr bgcolor=$color>
		<form action='editserver.php' method='post' name=editForm onSubmit='return validateForm1()'>
		<input type=hidden name=id value='$_SUBMIT[edit]'>
		<td colspan=2 style=\"border-right: 1px solid $_TEMPLATE[border_color];
		border-left: 1px solid $_TEMPLATE[border_color];
		border-bottom: 1px solid $_TEMPLATE[border_color]\"><table width=100% cellpadding=0 cellspacing=0>");
			
		output("
		<tr bgcolor=$color><td width=50%>&nbsp;&nbsp;<b>Server Title:</b> (*)<br>&nbsp;&nbsp;The name of this server (e.g. AMD4)</td><td><input type=text name=title value=\"".ereg_replace("\"","&quot;",$row_info['title'])."\"></td></tr>
		<tr bgcolor=$color><td width=50%>&nbsp;&nbsp;<b>Server IP Address:</b><br>&nbsp;&nbsp;(Only required if you have set the option to<br>&nbsp;&nbsp;query servers to check their status)</td><td><input type=text name=ip value=\"$row_info[ip]\"></td></tr>
                  <tr bgcolor=$color><td width=50%>&nbsp;&nbsp;<b>Server Type:</b> (*)<br>&nbsp;&nbsp;The type of server you are adding</td><td><select name=type><option value='Virtual' $Virtual_selected>Virtual Server</option><option value='Dedicated' $Dedicated_selected>Dedicated Server&nbsp;&nbsp;</option></select></td></tr>
		<tr bgcolor=$color><td width=50%>&nbsp;&nbsp;<b>Modern Bill UserID:</b><br>&nbsp;&nbsp;(Only required if this server is dedicated)</td><td><input type=text name=mbuserid value='$row_info[mbuserid]'></td></tr>								
		<tr bgcolor=$color><td width=50%>&nbsp;&nbsp;<b>Web (Apache) Port:</b><br>&nbsp;&nbsp;(Enter '0' to skip a port check)</td><td><input type=text name=web_port value='$row_info[web_port]'></td></tr>
		<tr bgcolor=$color><td width=50%>&nbsp;&nbsp;<b>SSH Port:</b><br>&nbsp;&nbsp;(Default: 22)</td><td><input type=text name=ssh_port value='$row_info[ssh_port]'></td></tr>
		<tr bgcolor=$color><td width=50%>&nbsp;&nbsp;<b>Telnet Port:</b><br>&nbsp;&nbsp;(Default: 23)</td><td><input type=text name=telnet_port value='$row_info[telnet_port]'></td></tr>
		<tr bgcolor=$color><td width=50%>&nbsp;&nbsp;<b>FTP Port:</b><br>&nbsp;&nbsp;(Default: 21)</td><td><input type=text name=ftp_port value='$row_info[ftp_port]'></td></tr>
		<tr bgcolor=$color><td width=50%>&nbsp;&nbsp;<b>SMTP Port:</b><br>&nbsp;&nbsp;(Default: 25)</td><td><input type=text name=smtp_port value='$row_info[smtp_port]'></td></tr>
		<tr bgcolor=$color><td width=50%>&nbsp;&nbsp;<b>POP3 Port:</b><br>&nbsp;&nbsp;(Default: 110)</td><td><input type=text name=pop3_port value='$row_info[pop3_port]'></td></tr>
		<tr bgcolor=$color><td width=50%>&nbsp;&nbsp;<b>MySQL Port:</b><br>&nbsp;&nbsp;(Default: 3306)</td><td><input type=text name=mysql_port value='$row_info[mysql_port]'></td></tr>

		
        		</table><br>&nbsp;&nbsp;<input type=submit value='Save Changes'><br></form></td></tr>");
		
		break;
	
	}
	
	# End server editing code
	
	# Print the relevant row style, depending on whether this row
	# has been selected for editing or not
	
	# NOTE: If a row is being edited, and this isn't it, we print nothing
	
	if(!isset($_SUBMIT['edit']) && ($row_info['id'] != $_SUBMIT['id'])) {
		
		# Row not selected
	
		output("<tr bgcolor=$color><td height=20 width=50% style=\"border-bottom: 1px solid $_TEMPLATE[border_color];
		border-left: 1px solid $_TEMPLATE[border_color]\">
		&nbsp;&nbsp;<img src=../i/news-item.jpg align=bottom>&nbsp;$row_info[title]</td>
		<td style=\"border-bottom: 1px solid $_TEMPLATE[border_color];
		border-right: 1px solid $_TEMPLATE[border_color]\">[ <a href='servers.php?edit=$row_info[id]'>Edit</a>
		 | <a href='deleteserver.php?id=$row_info[id]'>Remove</a> ]</td>
		</tr>");
		
	}
	
}

if($db->num_rows($result) == 0) {
	
	# No servers added, so display a message saying so
	
	output("<tr bgcolor=#F5F5F5><td colspan=2 style=\"border-left: 1px solid $_TEMPLATE[border_color];
	border-bottom: 1px solid $_TEMPLATE[border_color];
	border-right: 1px solid $_TEMPLATE[border_color]\">
	&nbsp;&nbsp;There are currently no servers on the system.
	</td></tr>");
	
}

output("</table><br>");	
	
output("<script language=Javascript>
        function validateForm() {

                 if(document.theForm.title.value == \"\") {

                          alert(\"You must enter a value in the title field.\");
                          document.theForm.title.focus();
                          return false;

                 }

        }
        </script>
        <table width=100% cellpadding=0 cellspacing=0>
        <form action='$PHP_SELF' method='post' onSubmit='return validateForm()' name=theForm>
        <input type=hidden name=add value=1>
         <tr><td style=\"border-bottom: 1px solid #999999\" colspan=2>
          <table width=115 cellpadding=0 cellspacing=0>
           <tr><Td bgcolor=$_TEMPLATE[border_color]><font color=white><b>
               <center>Add A Server</center></b></font>
               </td>
           </tr>
          </table>
         </td></tr>

<tr bgcolor=$_TEMPLATE[light_background]><td width=50% style=\"border-left: 1px solid $_TEMPLATE[border_color];border-bottom: 1px solid $_TEMPLATE[border_color]\">&nbsp;&nbsp;<b>Server Title:</b> (*)<br>&nbsp;&nbsp;The name of this server (e.g. AMD4)</td><td style=\"border-right: 1px solid $_TEMPLATE[border_color];border-bottom: 1px solid $_TEMPLATE[border_color]\"><input type=text name=title></td></tr>
<tr bgcolor=$_TEMPLATE[dark_background]><td width=50% style=\"border-left: 1px solid $_TEMPLATE[border_color];border-bottom: 1px solid $_TEMPLATE[border_color]\">&nbsp;&nbsp;<b>Server IP Address:</b><br>&nbsp;&nbsp;(Only required if you have set the option to<br>&nbsp;&nbsp;query servers to check their status)</td><td style=\"border-right: 1px solid $_TEMPLATE[border_color];border-bottom: 1px solid $_TEMPLATE[border_color]\"><input type=text name=ip></td></tr>
<tr bgcolor=$_TEMPLATE[light_background]><td width=50% style=\"border-left: 1px solid $_TEMPLATE[border_color];border-bottom: 1px solid $_TEMPLATE[border_color]\">&nbsp;&nbsp;<b>Server Type:</b> (*)<br>&nbsp;&nbsp;The type of server you are adding</td><td style=\"border-right: 1px solid $_TEMPLATE[border_color];border-bottom: 1px solid $_TEMPLATE[border_color]\"><select name=type><option value='Virtual'>Virtual Server</option><option value='Dedicated'>Dedicated Server&nbsp;&nbsp;</option></select></td></tr>
<tr bgcolor=$_TEMPLATE[dark_background]><td width=50% style=\"border-left: 1px solid $_TEMPLATE[border_color];border-bottom: 1px solid $_TEMPLATE[border_color]\">&nbsp;&nbsp;<b>Modern Bill UserID:</b><br>&nbsp;&nbsp;(Only required if this server is dedicated)</td><td style=\"border-right: 1px solid $_TEMPLATE[border_color];border-bottom: 1px solid $_TEMPLATE[border_color]\"><input type=text name=mbuserid></td></tr>

<tr bgcolor=$_TEMPLATE[border_color]><td height=25 colspan=2 style=\"border-right: 1px solid $_TEMPLATE[border_color];border-left: 1px solid $_TEMPLATE[border_color]\"><font color=white><b>&nbsp;&nbsp;Port Details</font></td></tr>

<tr bgcolor=$_TEMPLATE[light_background]><td width=50% style=\"border-left: 1px solid $_TEMPLATE[border_color];border-bottom: 1px solid $_TEMPLATE[border_color]\">&nbsp;&nbsp;<b>Web (Apache) Port:</b><br>&nbsp;&nbsp;(Enter '0' to skip the check on this service)</td><td style=\"border-right: 1px solid $_TEMPLATE[border_color];border-bottom: 1px solid $_TEMPLATE[border_color]\"><input type=text name=web_port value='80'></td></tr>
<tr bgcolor=$_TEMPLATE[dark_background]><td width=50% style=\"border-left: 1px solid $_TEMPLATE[border_color];border-bottom: 1px solid $_TEMPLATE[border_color]\">&nbsp;&nbsp;<b>SSH Port:</b><br>&nbsp;&nbsp;(Enter '0' to skip the check on this service)</td><td style=\"border-right: 1px solid $_TEMPLATE[border_color];border-bottom: 1px solid $_TEMPLATE[border_color]\"><input type=text name=ssh_port value='22'></td></tr>
<tr bgcolor=$_TEMPLATE[light_background]><td width=50% style=\"border-left: 1px solid $_TEMPLATE[border_color];border-bottom: 1px solid $_TEMPLATE[border_color]\">&nbsp;&nbsp;<b>Telnet Port:</b><br>&nbsp;&nbsp;(Enter '0' to skip the check on this service)</td><td style=\"border-right: 1px solid $_TEMPLATE[border_color];border-bottom: 1px solid $_TEMPLATE[border_color]\"><input type=text name=telnet_port value='23'></td></tr>
<tr bgcolor=$_TEMPLATE[dark_background]><td width=50% style=\"border-left: 1px solid $_TEMPLATE[border_color];border-bottom: 1px solid $_TEMPLATE[border_color]\">&nbsp;&nbsp;<b>FTP Port:</b><br>&nbsp;&nbsp;(Enter '0' to skip the check on this service)</td><td style=\"border-right: 1px solid $_TEMPLATE[border_color];border-bottom: 1px solid $_TEMPLATE[border_color]\"><input type=text name=ftp_port value='21'></td></tr>
<tr bgcolor=$_TEMPLATE[light_background]><td width=50% style=\"border-left: 1px solid $_TEMPLATE[border_color];border-bottom: 1px solid $_TEMPLATE[border_color]\">&nbsp;&nbsp;<b>SMTP Port:</b><br>&nbsp;&nbsp;(Enter '0' to skip the check on this service)</td><td style=\"border-right: 1px solid $_TEMPLATE[border_color];border-bottom: 1px solid $_TEMPLATE[border_color]\"><input type=text name=smtp_port value='25'></td></tr>
<tr bgcolor=$_TEMPLATE[dark_background]><td width=50% style=\"border-left: 1px solid $_TEMPLATE[border_color];border-bottom: 1px solid $_TEMPLATE[border_color]\">&nbsp;&nbsp;<b>POP3 Port:</b><br>&nbsp;&nbsp;(Enter '0' to skip the check on this service)</td><td style=\"border-right: 1px solid $_TEMPLATE[border_color];border-bottom: 1px solid $_TEMPLATE[border_color]\"><input type=text name=pop3_port value='110'></td></tr>
<tr bgcolor=$_TEMPLATE[light_background]><td width=50% style=\"border-left: 1px solid $_TEMPLATE[border_color];border-bottom: 1px solid $_TEMPLATE[border_color]\">&nbsp;&nbsp;<b>MySQL Port:</b><br>&nbsp;&nbsp;(Enter '0' to skip the check on this service)</td><td style=\"border-right: 1px solid $_TEMPLATE[border_color];border-bottom: 1px solid $_TEMPLATE[border_color]\"><input type=text name=mysql_port value='3306'></td></tr>
        </table><br><input type=submit value='Add Server'></form>");

$template->createPage();

?>