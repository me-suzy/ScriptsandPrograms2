<?php
function head() {
?>

<head>
<title>My DataBook Installation</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
td {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 12px;
	color: #000000;
	text-decoration: none;
}
-->
</style>
</head>

<body bgcolor="#E3E3E3" text="#000000" link="#FF0000" vlink="#FF0000" alink="#FF0000">
<p>&nbsp;</p>
<table width="650" border="0" align="center" cellpadding="1" cellspacing="0">
<tr> 
<td><table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#000000">
<tr> 
<td><table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#FFFFFF">
<tr> 
<td><img src="images/header.gif" width="250" height="50"></td>
</tr>
</table></td>
</tr>
</table></td>
</tr>
<tr>
<td><table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#000000">
<tr> 
<td><table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#FFFFFF">
<tr> 
<td><p><font size="2"><strong>&raquo; My DataBook installation,</strong></font></p>
<table width="75%" border="0" align="center" cellpadding="1" cellspacing="0">
<tr>
<td>

<?php
}


function footer() {
?>

</td>
</tr>
</table>
<p align="center">&nbsp;</p></td>
</tr>
</table></td>
</tr>
</table></td>
</tr>
</table>
</body>
</html>

<?php
}

if(isset($step)) {
	switch($step) {
		case one:
			head();
			?>

				<div align="center">First thing we need to do is create 
				  the connection to the database, Please provide us with 
				  the required information to connect to the MySQL server 
				  and the table names you would like to use for you installation 
				  of My DataBook.</div>
				<form name="form1" method="post" action="">
				<input type="hidden" name="step" value="two">
				  <table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#000000">
					<tr> 
					  <td><table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#FFFFCC">
						  <tr> 
							<td><table width="100%" border="0" cellspacing="1" cellpadding="2">
								<tr valign="top"> 
								  <td width="92%"><div align="center"><strong>MySQL 
									  host name,</strong><br>
									  <font size="1">(normally &quot;localhost&quot;)<br>
									  <input name="host" type="text" value="localhost" size="30">
									  </font></div></td>
								</tr>
								<tr valign="top"> 
								  <td><div align="center"><strong>MySQL 
									  user name,</strong><br>
									  <font size="1">(The name used to 
									  connect to your MySQL server)<br>
									  <input name="user" type="text" size="30">
									  </font></div></td>
								</tr>
								<tr valign="top"> 
								  <td><div align="center"><strong>MySQL 
									  database name,</strong><br>
									  <font size="1">(The name of the 
									  database to install My DataBook 
									  information)<br>
									  <input name="database" type="text" size="30">
									  </font></div></td>
								</tr>
								<tr valign="top"> 
								  <td><div align="center"><strong>MySQL 
									  password,</strong><br>
									  <font size="1">(The password used 
									  to access your database) <br>
									  <input name="password" type="text" size="30">
									  </font></div></td>
								</tr>
								<tr valign="top"> 
								  <td><div align="center"><strong>Your 
									  name,</strong><br>
									  <font size="1">(This is the name 
									  that will appear on all emails sent 
									  from My DataBook)</font><br>
									  <input name="your_name" type="text" size="30">
									</div></td>
								</tr>
								<tr valign="top"> 
								  <td><div align="center"><strong>Your 
									  eMail address,</strong><br>
									  <font size="1">(This is going to 
									  be the &quot;reply to&quot; address 
									  on all emails sent by My DataBook)<br>
									  <input name="email" type="text" size="30">
									  </font></div></td>
								</tr>
								<tr valign="top"> 
								  <td><div align="center"> 
									  <hr size="1">
									</div></td>
								</tr>




								<tr valign="top"> 
								  <td><div align="center"><strong>Notes 
									  table,</strong><br>
									  <font size="1">(This is the table 
									  name that will hold your notes)<br>
									  <input name="notes_table" type="text" value="MDB_notes" size="30">
									  </font></div></td>
								</tr>
								<tr valign="top"> 
								  <td><div align="center"><strong>Contacts 
									  table,</strong><br>
									  <font size="1">(name of the table 
									  that will hold all your contact 
									  information)</font><br>
									  <input name="contacts_table" type="text" value="MDB_contacts" size="30">
									</div></td>
								</tr>
								<tr valign="top"> 
								  <td><div align="center"><strong>Groups 
									  table,</strong><br>
									  <font size="1">(name of the groups 
									  table)</font><br>
									  <input name="groups_table" type="text" value="MDB_groups" size="30">
									</div></td>
								</tr>
								<tr valign="top"> 
								  <td><div align="center"> 
									  <p><strong>Reminders table,</strong><br>
										<font size="1">(Name of the reminders 
										table)<br>
										<input name="reminders_table" type="text" value="MDB_reminders" size="30">
										</font></p>
									</div></td>
								</tr>
								<tr valign="top"> 
								  <td><div align="center"> 
									  <p><strong>Diary table,</strong><br>
										<font size="1">(Name of the diary 
										entry table)<br>
										<input name="diary_table" type="text" value="MDB_diary" size="30">
										</font></p>
									</div></td>
								</tr>
								<tr valign="top"> 
								  <td><div align="center"><strong>Task 
									  table,</strong><br>
									  <font size="1">(name of the tasks 
									  table)</font><br>
									  <input name="task_table" type="text" value="MDB_tasks" size="30">
									</div></td>
								</tr>
								<tr valign="top"> 
								  <td><div align="center"><strong>Task 
									  Update table,</strong><br>
									  <font size="1">(Name of the table 
									  that will hold all task updates)</font><br>
									  <input name="task_update_table" type="text" value="MDB_tasks_update" size="30">
									</div></td>
								</tr>
								<tr valign="top"> 
								  <td><div align="center"><strong>Appointments 
									  table,</strong><br>
									  <font size="1">(Name of the appointments 
									  table)</font><br>
									  <input name="appointments_table" type="text" value="MDB_appointments" size="30">
									</div></td>
								</tr>
								<tr valign="top"> 
								  <td><div align="center"><strong>Calendar 
									  notes,</strong><br>
									  <font size="1">(Name of the scheduled 
									  notes for your calendar)</font><br>
									  <input name="calendar_table" type="text" value="MDB_scheduled_notes" size="30">
									</div></td>
								</tr>
								<tr valign="top"> 
								  <td>&nbsp;</td>
								</tr>
								<tr valign="top"> 
								  <td><div align="center"> 
									  <input name="Submit" type="submit" value="  Submit  ">
									</div></td>
								</tr>
								<tr valign="top"> 
								  <td>&nbsp;</td>
								</tr>
							  </table></td>
						  </tr>
						</table></td>
					</tr>
				  </table>
				</form>

			<?php
			footer();
			break;

			case two:
				$path = realpath("setup.php");
				$path = ereg_replace("setup.php","",$path);
				
				$filename = $path."data.inc.php";
				$file = fopen($filename, "w+");
				fwrite($file, "<?php\n\n\$DB_host=\"$host\";\n\$DB_name=\"$database\";\n\$DB_pass=\"$password\";\n\$DB_user=\"$user\";\n\n\$YOU=\"$your_name\";\n\$YOUR_EMAIL=\"$email\";\n\n\$Table_notes=\"$notes_table\";\n\$Table_contacts=\"$contacts_table\";\n\$Table_groups=\"$groups_table\";\n\$Table_reminders=\"$reminders_table\";\n\$Table_diary=\"$diary_table\";\n\$Table_tasks=\"$task_table\";\n\$Table_task_updates=\"$task_update_table\";\n\$Table_appointments=\"$appointments_table\";\n\$Table_scheduled_notes=\"$calendar_table\";\n\n?>");
				fclose($file);

				$Connect = mysql_connect($host,$user,$password) or die(mysql_error());
				mysql_select_db($database);

				$build_one = mysql_query("CREATE TABLE \"$appointments_table\" (`A_ID` int(11) NOT NULL auto_increment,`date` date NOT NULL default '0000-00-00',`time` varchar(20) NOT NULL default '',`type` varchar(24) NOT NULL default '',`notes` text NOT NULL,PRIMARY KEY  (`A_ID`)) TYPE=MyISAM AUTO_INCREMENT=10 ;") or die(mysql_error());

				$build_two = mysql_query("CREATE TABLE \"$contacts_table\" (`C_ID` int(11) NOT NULL auto_increment,`first_name` varchar(36) NOT NULL default '',`last_name` varchar(36) default NULL,`birthday` varchar(36) default NULL,`title` varchar(36) default NULL,`company` varchar(120) default NULL,`email` varchar(180) default NULL,`home_phone` varchar(24) default NULL,`icq` varchar(24) default NULL,`work_phone` varchar(24) default NULL,`msn` varchar(24) default NULL,`other_phone` varchar(24) default NULL,`yahoo` varchar(24) default NULL,`cell_phone` varchar(24) default NULL,`aim` varchar(24) default NULL,`pager` varchar(24) default NULL,`website` varchar(255) default NULL,`street` varchar(255) default NULL,`city` varchar(120) default NULL,`state` varchar(120) default NULL,`country` varchar(255) default NULL,`zip` varchar(24) default NULL,`notes` text,`group_num` varchar(255) NOT NULL default '0',PRIMARY KEY  (`C_ID`)) TYPE=MyISAM AUTO_INCREMENT=21 ;") or die(mysql_error());

				$build_three = mysql_query("CREATE TABLE \"$diary_table\" (`D_ID` int(11) NOT NULL auto_increment,`date` datetime NOT NULL default '0000-00-00 00:00:00',`entry` text NOT NULL,PRIMARY KEY  (`D_ID`)) TYPE=MyISAM AUTO_INCREMENT=12 ;") or die(mysql_error());

				$build_four = mysql_query("CREATE TABLE \"$groups_table\" (`G_ID` int(11) NOT NULL auto_increment,`name` varchar(36) NOT NULL default '',PRIMARY KEY  (`G_ID`)) TYPE=MyISAM AUTO_INCREMENT=16 ;") or die(mysql_error());

				$build_five = mysql_query("CREATE TABLE \"$notes_table\" (`N_ID` int(11) NOT NULL auto_increment,`date` varchar(120) NOT NULL default '0000-00-00',`color` int(1) NOT NULL default '0',`note` text NOT NULL,PRIMARY KEY  (`N_ID`)) TYPE=MyISAM AUTO_INCREMENT=20 ;") or die(mysql_error());

				$build_six = mysql_query("CREATE TABLE \"$reminders_table\" (`R_ID` int(11) NOT NULL auto_increment,`subject` varchar(48) NOT NULL default '',`message` text NOT NULL,`date` date NOT NULL default '0000-00-00',`status` int(1) NOT NULL default '0',PRIMARY KEY  (`R_ID`)) TYPE=MyISAM AUTO_INCREMENT=16 ;") or die(mysql_error());

				$build_seven = mysql_query("CREATE TABLE \"$calendar_table\" (`SN_ID` int(11) NOT NULL auto_increment,`date` date NOT NULL default '0000-00-00',`note` text NOT NULL,PRIMARY KEY  (`SN_ID`)) TYPE=MyISAM AUTO_INCREMENT=9 ;") or die(mysql_error());

				$build_eight = mysql_query("CREATE TABLE \"$task_update_table\" (`TU_ID` int(11) NOT NULL auto_increment,`date` date NOT NULL default '0000-00-00',`new_update` text NOT NULL,`sub` int(12) NOT NULL default '0',PRIMARY KEY  (`TU_ID`)) TYPE=MyISAM AUTO_INCREMENT=6 ;") or die(mysql_error());

				$build_nine = mysql_query("CREATE TABLE \"$task_table\" (`T_ID` int(11) NOT NULL auto_increment,`due_date` date NOT NULL default '0000-00-00',`inserted` date NOT NULL default '0000-00-00',`priority` int(1) NOT NULL default '0',`title` varchar(48) NOT NULL default '',`task` text NOT NULL,`completed` int(1) NOT NULL default '0',`email` int(1) NOT NULL default '0',PRIMARY KEY  (`T_ID`)) TYPE=MyISAM AUTO_INCREMENT=19 ;") or die(mysql_error());

				mysql_close($Connect);
				head();

					?>

					<div align="center">
                          <p><font size="3"><strong><em>Congratulations!</em></strong></font></p>
                          <p>You have successfully installed My DataBook. You 
                            may now start using My DataBook for your everyday 
                            appointments and scheduling.</p>
                          <p>If you find any problems with the program please 
                            submit a bug report that is located at your home page 
                            of My DataBook.</p><p><a href="index.php">Click here to start using My DataBook</a>
                        </div>

					<?php

				footer();
				break;
	}
}

if(!isset($step)) {

head();
?>

<p align="center">Welcome to My DataBook installation application. Over the next few pages we will select the configuration settings that will create the database tables and get My DataBook ready for your information.</p>
<p align="center">You will be asked some questions and all you have to do is answer the questions and you'll be done in no time.</p>
<p align="right"><a href="<?= $PHP_SELF; ?>?step=one">Start installation</a> &raquo;</p>

<?php
footer();
}
?>