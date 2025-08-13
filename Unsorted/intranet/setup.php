<html>
<head><title>Setup</title>

<?php
include("config.php");
include("identity.php");
if ($refok == "yes")
	{
	$appheaderstring = "Setup";
	include("header.php");
	if($setting[perm_admin]=='y' and $setting[want_admin]=='y')
		{
		dbconnect($dbusername,$dbuserpasswd);
		$result93=mysql_query("select * from userinfo");
		exec('echo "## SCRIPT TO ADD ALL STAFF TO HTPASSWORD FILE" > /tmp/htpasswd.script');
		exec('echo "## SCRIPT TO SEND ALL STAFF CLEAR UPLOAD WARNING" > /tmp/winpopup.script');
		while($list93=mysql_fetch_array($result93))
			{
                    	$stuff = "/usr/local/apache/bin/htpasswd -b /usr/local/apache/staff.users " . $list93[login] . " " . $list93[password];
			exec("echo $stuff >> /tmp/htpasswd.script");
			//$clearwarning="Warning: Files in http://bhcinfo.tranquility.net/uploads/ will be deleted in 30 minutes.";
			$clearwarning='Automated Warning: Files on http://bhcinfo.tranquility.net/uploads/ will be deleted in 30 minutes.';
			$list93[machinename] = ucfirst($list93[machinename]);
			$stuff2 = "echo 'echo \"$clearwarning\" | /usr/bin/smbclient -M " . $list93[machinename] . " -U Gabrielle' >> /tmp/winpopup.script";	
			// echo $stuff2, "<br>";
			exec($stuff2);
			}
		}
if ($action == 'delete')
	{
	if ($deletetarget != $currentuser)
		{
		dbconnect($dbusername,$dbuserpasswd);
	        mysql_query("delete from userinfo where login='$deletetarget'");
		} else
			{
			echo "<font color='660000' face='Courier' size='4'>ERROR: You aren't allowed to delete yourself. Go have someone else delete you.</font>";
			$targetuser = $deletetarget;
			}
	}
if ($changes == "yes")
	{
	if ($want_news != 'y') { $want_news = 'n'; }
	if ($want_calendar != 'y') { $want_calendar = 'n'; }
	if ($want_rolodex != 'y') { $want_rolodex = 'n'; }
	if ($want_tasklist != 'y') { $want_tasklist = 'n'; }
	if ($want_network != 'y') { $want_network = 'n'; }
	if ($want_admin != 'y') { $want_admin = 'n'; }
	if ($want_survey != 'y') { $want_survey = 'n'; }
	if ($want_admin_news != 'y') { $want_admin_news = 'n'; }
	if ($want_admin_bar != 'y') { $want_admin_bar = 'n'; }
	if ($want_admin_msg != 'y') { $want_admin_msg = 'n'; }
	if ($want_timesheet != 'y') { $want_timesheet = 'n'; }
	if ($want_timesheetcp != 'y') { $want_timesheetcp = 'n'; }
	if ($want_setup != 'y') { $want_setup = 'n'; }
	if ($quotes_dark != 'y') { $quotes_dark = 'n'; }
	if ($quotes_happy != 'y') { $quotes_happy = 'n'; }
	if ($quotes_fortune != 'y') { $quotes_fortune = 'n'; }
	if ($quotes_odd != 'y') { $quotes_odd = 'n'; }
	if ($quotes_crude != 'y') { $quotes_crude = 'n'; }	
	if ($quotes_joke != 'y') { $quotes_joke = 'n'; }
	if ($newuseheader !='y') { $newuseheader ='n'; }
	if ($machinename == '') { $errormsg =$errormsg . "Error: You provided no machine name. Update failed.<br>"; }
	if ($login == '') { $errormsg = $errormsg . "Error: You provided no login name. Update failed.<br>"; }
	if ($newipaddress == '') { $errormsg = $errormsg . "Error: You provided no IP address. Update failed.<br>"; }
	dbconnect($dbusername,$dbuserpasswd);
	$result_c1=mysql_query("select * from userinfo where machinename='$machinename'");
	if($result_c1)
		{
		while($list_c1 = mysql_fetch_array($result_c1))
			{
                        if($list_c1[login] != $targetuser)
				{
				$errormsg = $errormsg . "Error: That machine name is already in use. Update failed.<br>";
				}
			}
                }
	dbconnect($dbusername,$dbuserpasswd);
	$result_c2=mysql_query("select * from userinfo where login='$login'");
	if($result_c2)
		{
		while($list_c2= mysql_fetch_array($result_c2))
			{
			if($list_c2[login] != $targetuser)
				{
				$errormsg = $errormsg . "Error: That login name is already in use. Update failed.<br>";
				}
			}
		}
	dbconnect($dbusername,$dbuserpasswd);
	$result_c3=mysql_query("select * from userinfo where ipaddress='$newipaddress'");
	if($result_c3)
		{
		while($list_c3=mysql_fetch_array($result_c3))
			{
			if($list_c3[login] != $targetuser)
				{
				$errormsg = $errormsg . "Error: That IP address is already in use. Update failed.<br>";
				}
			}
		}
	if($errormsg == '')
		{
		if($perm_sharepw =='') { $perm_sharepw = $setting[perm_sharepw]; }
		dbconnect($dbusername,$dbuserpasswd);
		mysql_query ("update userinfo set
		login='$login', emailaddress='$emailaddress', employeetype='$employeetype', perm_sharepw='$perm_sharepw',
		firstname='$firstname', lastname='$lastname', ipaddress='$newipaddress', machinename='$machinename',
		opsys='$opsys', datajack='$datajack', password='$password', menu_bgcolor='$menu_bgcolor',
		menu_fontcolor='$menu_fontcolor', menu_fontface='$menu_fontface', menu_fontsize='$menu_fontsize',
		menu_columns='$menu_columns', menu_scroll='$menu_scroll', default_fontcolor='$default_fontcolor',
		default_fontface='$default_fontface', default_fontsize='$default_fontsize', default_bgcolor='$default_bgcolor',
		heading_fontcolor='$heading_fontcolor',	heading_fontface='$heading_fontface',
		heading_fontsize='$heading_fontsize', heading_bgcolor='$heading_bgcolor', showquote='$showquote',
		utilitybar='$utilitybar', want_news='$want_news', want_calendar='$want_calendar', want_rolodex='$want_rolodex',
		want_tasklist='$want_tasklist', want_network='$want_network', want_admin='$want_admin',
		want_timesheet='$want_timesheet', want_timesheetcp='$want_timesheetcp', want_setup='$want_setup',
		quotes_dark='$quotes_dark', quotes_happy='$quotes_happy', quotes_fortune='$quotes_fortune',
		quotes_odd='$quotes_odd', quotes_crude='$quotes_crude', quotes_joke='$quotes_joke',
		useheader='$newuseheader', menumode='$newmenumode', want_admin_news='$want_admin_news',
		want_admin_bar='$want_admin_bar', want_admin_msg = '$want_admin_msg', want_survey='$want_survey',
		want_contact='$want_contact', headinghighlight='$headinghighlight', linkbarbg='$linkbarbg', bgwallpaper='$bgwallpaper',
		menuwallpaper='$menuwallpaper', headingwallpaper='$headingwallpaper', adminlbbg='$adminlbbg', lb_fontcolor='$lb_fontcolor',
		ab_fontcolor='$ab_fontcolor'
		where login='$targetuser'");
		} else { echo "<font color='", $setting[default_fontcolor], "'>", $errormsg, "</font>"; }
if ($permchanges == 'y')
	{
        mysql_query ("update userinfo set perm_news='$perm_news', perm_calendar='$perm_calendar',
		perm_rolodex='$perm_rolodex', perm_network='$perm_network', perm_tasklist='$perm_tasklist',
		perm_timesheet='$perm_timesheet', perm_timesheetcp='$perm_timesheetcp', perm_survey='$perm_survey',
		perm_admin='$perm_admin', perm_setup='$perm_setup', perm_contact='$perm_contact' where login='$targetuser'");
	}
	if ($login != $targetuser) { $targetuser = $login; }
	if ($password != $oldpassword)
		{
		$message = "Please change password for " . $login . " to " . $password . ".";
                mysql_query("insert into sysadminmsg set login='$login', message='$message', date_time=now()");
		}
	}

if ($targetuser == 'create_new_user')
	{
	dbconnect($dbusername,$dbuserpasswd);
	mysql_query( "insert into userinfo set login='new_user', firstname='NEW',
			lastname='USER', ipaddress='0.0.0.0', password='8jk3ajsmng82kjc'");
	$targetuser = 'new_user';
	}
if ($setting[perm_admin] == 'y' and $setting[want_admin] == 'y')
	{
        echo "<form method='post' action='setup.php'><table  width='100%' border='0' cellpadding='0' cellspacing='0'>
	<tr><td>Account: <select name='targetuser'><option value='create_new_user'>CREATE NEW USER";
	dbconnect($dbusername,$dbuserpasswd);

	$tresult = mysql_query( "select * from userinfo order by lastname");
	if ($targetuser == '') { $targetuser = $currentuser; }
	while($trow = mysql_fetch_array($tresult))
		{
                echo "<option";
		if ($trow[login] == $targetuser) { echo " selected"; }
		echo " value='", $trow[login], "'>", $trow[firstname], " ", $trow[lastname];
		}
	echo "</select><input type='submit' value='Go'></td></form>";
	if ($currentuser != $targetuser)
		{
	echo "<td align='right'><a href='setup.php?action=delete&deletetarget=", $targetuser, "'";
?>
 onClick="if (confirm('<?php echo "You are about to delete ", $targetuser; ?>.\nThe record will be deleted forever unless you click Cancel right now.') == true) { return true; } else { return false; }"
<?php
	echo "><img src='icons/delete.gif' border='0' alt='Delete!'></a> &nbsp; &nbsp; </td>";
		}
	echo "</tr></table>";
	}
	if ($targetuser == '') { $targetuser = $currentuser; }

	dbconnect($dbusername,$dbuserpasswd);
	$result = mysql_query( "select * from userinfo where login ='$targetuser'");
	$row = mysql_fetch_array($result);

echo "<form name='changestuff' action='setup.php' method='post'><center><table border='0' cellpadding='1' cellspacing='0'>";
echo "<input type='hidden' name='targetuser' value='", $targetuser, "'>";
echo "<tr><td align='left' colspan='2' bgcolor='", $setting[headinghighlight], "'><font size='", $row[default_fontsize], "' face='", $row[default_fontface], "' color='", $setting[default_fontcolor], "'><b><i>Set by System Administrator</i></b></td></tr>";
echo "<tr><td align='right'><font size='", $row[default_fontsize], "' face='", $row[default_fontface], "'><b>Machine Name (required):</b></td><td>";
if ($setting[perm_admin] == 'y' and $setting[want_admin] == 'y')
	{
	echo "<input type='text' name='machinename' value='", $row[machinename], "'>";
	} else
		{
		echo $row[machinename], "<input type='hidden' name='machinename' value='", $row[machinename], "'>";
		}
echo "</td></tr>";

echo "<tr><td align='right'><font size='", $row[default_fontsize], "' face='", $row[default_fontface], "'><b>IP Address (required):</b></td><td>";
if ($setting[perm_admin] == 'y' and $setting[want_admin] == 'y')
	{
	echo "<input type='text' name='newipaddress' value='", $row[ipaddress], "'>";
	} else
		{
		echo $row[ipaddress], "<input type='hidden' name='newipaddress' value='", $row[ipaddress], "'>";
		}
echo "</td></tr>";

echo "<tr><td align='right'><font size='", $row[default_fontsize], "' face='", $row[default_fontface], "'><b>Your Login Name (required):</b></td><td>";
if ($setting[perm_admin] == 'y' and $setting[want_admin] == 'y')
	{
	echo "<input type='text' name='login' value='", $row[login], "'>";
	} else
		{
		echo $row[login], "<input type='hidden' name='login' value='", $row[login], "'>";
		}
echo "</td></tr>";

echo "<tr><td align='right'><font size='", $row[default_fontsize], "' face='", $row[default_fontface], "'><b>Your E-mail Address:</b></td><td>";
if ($setting[perm_admin] == 'y' and $setting[want_admin] == 'y')
	{
	echo "<input type='text' name='emailaddress' value='", $row[emailaddress], "'>";
	} else
		{
		echo $row[emailaddress], "<input type='hidden' name='emailaddress' value='", $row[emailaddress], "'>";
		}
echo "</td></tr>";

echo "<tr><td align='right'><font size='", $row[default_fontsize], "' face='", $row[default_fontface], "'><b>Your First Name:</b></td><td>";
if ($setting[perm_admin] == 'y' and $setting[want_admin] == 'y')
	{
	echo "<input type='text' name='firstname' value='", $row[firstname], "'>";
	} else
		{
		echo $row[firstname], "<input type='hidden' name='firstname' value='", $row[firstname], "'>";
		}
echo "</td></tr>";

echo "<tr><td align='right'><font size='", $row[default_fontsize], "' face='", $row[default_fontface], "'><b>Your Last Name:</b></td><td>";
if ($setting[perm_admin] == 'y' and $setting[want_admin] == 'y')
	{
	echo "<input type='text' name='lastname' value='", $row[lastname], "'>";
	} else
		{
		echo $row[lastname], "<input type='hidden' name='lastname' value='", $row[lastname], "'>";
		}
echo "</td></tr>";

echo "<tr><td align='right'><font size='", $row[default_fontsize], "' face='", $row[default_fontface], "'><b>Operating System:</b></td><td>";
if ($setting[perm_admin] == 'y' and $setting[want_admin] =='y')
	{
	echo "<input type='text' name='opsys' value='", $row[opsys], "'>";
	} else
		{
		echo $row[opsys], "<input type='hidden' name='opsys' value='", $row[opsys], "'>";
		}
echo "</td></tr>";
echo "<tr><td align='right'><font size='", $row[default_fontsize], "' face='", $row[default_fontface], "'><b>Data Jack:</b></td><td>";
if ($setting[perm_admin] == 'y' and $setting[want_admin] == 'y')
	{
	echo "<input type='text' name='datajack' value='", $row[datajack], "'>";
	} else
		{
		echo $row[datajack], "<input type='hidden' name='datajack' value='", $row[datajack], "'>";
		}
echo "</td></tr>";
echo "<tr><td align='right'><font size='", $row[default_fontsize], "' face='", $row[default_fontface], "'><b>Employee Type:</b></td><td>";
if ($setting[perm_admin] == 'y' and $setting[want_admin] == 'y')
	{
	echo "<input type='text' name='employeetype' value='", $row[employeetype], "'>";
	} else
		{
		echo $row[employeetype], "<input type='hidden' name='employeetype' value='", $row[employeetype], "'>";
		}
echo "</td></tr>";

if (($showadmin == 'yes' or $setting[perm_admin] == 'y') and $setting[want_admin] == 'y')
	{
	echo "<tr><td align='left' colspan='2' bgcolor='", $setting[headinghighlight], "'><font size='", $row[default_fontsize], "' face='", $row[default_fontface], "' color='", $setting[default_fontcolor], "'><b><i>Permissions</i></b></td></tr>";
if ($shownews=='restrict')
	{
        echo "<tr><td align='right'><font size='", $row[default_fontsize], "' face='", $row[default_fontface], "'><b>News:</b></td><td><select name='perm_news'><option value='n'>No<option value='y'";
if ($row[perm_news] == 'y') { echo " selected"; }
	echo ">Yes</select></td></tr>";
	} else { echo "<input type='hidden' name='perm_news' value='", $row[perm_news], "'>"; }
if ($showcalendar=='restrict')
	{
	echo "<tr><td align='right'><font size='", $row[default_fontsize], "' face='", $row[default_fontface], "'><b>Calendar:</b></td><td><select name='perm_calendar'><option value='n'>No<option value='y'";
if ($row[perm_calendar] =='y') { echo " selected"; }
	echo ">Yes</select></td></tr>";
	} else { echo "<input type='hidden' name='perm_calendar' value='", $row[perm_calendar], "'>"; }
if ($showrolodex=='restrict')
	{
	echo "<tr><td align='right'><font size='", $row[default_fontsize], "' face='", $row[default_fontface], "'><b>Rolodex:</b></td><td><select name='perm_rolodex'><option value='n'>No<option value='y'";
if ($row[perm_rolodex] =='y') { echo " selected"; }
	echo ">Yes</select></td></tr>";
        } else { echo "<input type='hidden' name='perm_rolodex' value='", $row[perm_rolodex], "'>"; }
if ($showcontact=='restrict')
	{
	echo "<tr><td align='right'><font size='", $row[default_fontsize], "' face='", $row[default_fontface], "'><b>Contacts:</b></td><td><select name='perm_contacts'><option value='n'>No<option value='y'";
if ($row[perm_contact] == 'y') { echo " selected"; }
	echo ">Yes</select></td></tr>";
        } else { echo "<input type='hidden' name='perm_contact' value='", $row[perm_contact], "'>"; }
if ($shownetwork=='restrict')
	{
	echo "<tr><td align='right'><font size='", $row[default_fontsize], "' face='", $row[default_fontface], "'><b>Network:</b></td><td><select name='perm_network'><option value='n'>No<option value='y'";
if ($row[perm_network] == 'y') { echo " selected"; }
	echo ">Yes</select></td></tr>";
        } else { echo "<input type='hidden' name='perm_network' value='", $row[perm_network], "'>"; }
if ($shownetwork=='restrict')
	{
	echo "<tr><td align='right'><font size='", $row[default_fontsize], "' face='", $row[default_fontface], "'><b>Tasklist:</b></td><td><select name='perm_tasklist'><option value='n'>No<option value='y'";
if ($row[perm_tasklist] =='y') { echo " selected"; }
	echo ">Yes</select></td></tr>";
        } else { echo "<input type='hidden' name='perm_tasklist' value='", $row[perm_tasklist], "'>"; }
if ($showtimesheet=='restrict')
	{
	echo "<tr><td align='right'><font size='", $row[default_fontsize], "' face='", $row[default_fontface], "'><b>Timesheet:</b></td><td><select name='perm_timesheet'><option value='n'>No<option value='y'";
if ($row[perm_timesheet] == 'y') { echo " selected"; }
	echo ">Yes</select></td></tr>";
        } else { echo "<input type='hidden' name='perm_timesheet' value='", $row[perm_timesheet], "'>"; }
if ($showtsadmin=='restrict')
	{
	echo "<tr><td align='right'><font size='", $row[default_fontsize], "' face='", $row[default_fontface], "'><b>Timesheet Admin:</b></td><td><select name='perm_timesheetcp'><option value='n'>No<option value='y'";
if ($row[perm_timesheetcp] == 'y') { echo " selected"; }
	echo ">Yes</select></td></tr>";
	} else { echo "<input type='hidden' name='perm_timesheetcp' value='", $row[perm_timesheet], "'>"; }
if ($showsurvey =='restrict')
	{
	echo "<tr><td align='right'><font size='", $row[default_fontsize], "' face='", $row[default_fontface], "'><b>Survey:</b></td><td><select name='perm_survey'><option value='n'>No<option value='y'";
if ($row[perm_survey] == 'y') { echo " selected"; }
	echo ">Yes</select></td></tr>";
	} else { echo "<input type='hidden' name='perm_survey' value='", $row[perm_survey], "'>"; }
if ($showadmin =='restrict')
	{	
	echo "<tr><td align='right'><font size='", $row[default_fontsize], "' face='", $row[default_fontface], "'><b>Admin:</b></td><td><select name='perm_admin'><option value='n'>No<option value='y'";
if ($row[perm_admin] == 'y') { echo " selected"; }
	echo ">Yes</select></td></tr>";
        } else { echo "<input type='hidden' name='perm_admin' value='", $row[perm_admin], "'>"; }
if ($showsetup =='restrict')
	{
	echo "<tr><td align='right'><font size='", $row[default_fontsize], "' face='", $row[default_fontface], "'><b>Setup:</b></td><td><select name='perm_setup'><option value='n'>No<option value='y'";
if ($row[perm_setup] == 'y') { echo " selected"; }
	echo ">Yes</select></td></tr>";
	} else { echo "<input type='hidden' name='perm_setup' value='", $row[perm_setup], "'>"; }
if ($sharepw =='restrict')
	{
	echo "<tr><td align='right'><font size='", $row[default_fontsize], "' face='", $row[default_fontface], "'><b>Password Sharing:</b></td><td><select name='perm_sharepw'><option value='n'>No<option value='y'";
if ($row[perm_sharepw] == 'y') { echo " selected"; }
	echo ">Yes</select></td></tr>";
	} else { echo "<input type='hidden' name='perm_sharepw' value='", $row[perm_sharepw], "'>"; }

	echo "<input type='hidden' name='permchanges' value='y'>";
	}

echo "<tr><td align='left' colspan='2' bgcolor='", $setting[headinghighlight], "'><font size='", $row[default_fontsize], "' face='", $row[default_fontface], "' color='", $setting[default_fontcolor], "'><b><i>Password Change</i></b></td></tr>";
echo "<tr><td align='right'><font size='", $row[default_fontsize], "' face='", $row[default_fontface], "'><b>Password:</b></td><td><input type='text' name='password' value='", $row[password], "'></td></tr>";
echo "<input type='hidden' name='oldpassword' value='", $row[password], "'>";
echo "<tr><td align='left' colspan='2' bgcolor='", $setting[headinghighlight], "'><font size='", $row[default_fontsize], "' face='", $row[default_fontface], "' color='", $setting[default_fontcolor], "'><b><i>Menu Settings</i></b></td></tr>";
echo "<tr><td align='right'><font size='", $row[default_fontsize], "' face='", $row[default_fontface], "'><b>Menu Layout Mode:</b></td><td>";
if ($menumode == 'user')
	{
	echo "<select name='newmenumode'><option value='norm'";
	if ($row[menumode] == 'norm') { echo " selected"; }
 	echo ">Normal (icons + labels)<option value='icon'";
	if ($row[menumode] == 'icon') { echo " selected"; }
 	echo ">Icons Only<option value='list'";
	if ($row[menumode] == 'list') { echo " selected"; }
 	echo ">Bulleted List<option value='text'";
	if ($row[menumode] == 'text') { echo " selected"; }
 	echo ">Text Only";
	echo "</select>";
	} else
		{
		if ($menumode == 'norm') { echo "Normal (icons + labels)"; }
                if ($menumode == 'text') { echo "Text Only"; }
		if ($menumode == 'list') { echo "Bulleted List"; }
		if ($menumode == 'icon') { echo "Icons Only"; }
		}
echo "</td></tr>";
echo "<tr><td align='right'><font size='", $row[default_fontsize], "' face='", $row[default_fontface], "'><b>Menu Background Color:</b></td><td>";
echo "<select name='menu_bgcolor'>";
	dbconnect($dbusername,$dbuserpasswd);
	$cresult=mysql_query("select * from colors order by label");
	while($colorlist=mysql_fetch_array($cresult))
		{
                echo "<option value='", $colorlist[hex], "'";
		if ($colorlist[hex] == $row[menu_bgcolor]) { echo " selected"; }
		echo ">", $colorlist[label];
		}
echo "</select></td></tr>";
echo "<tr><td align='right'><font size='", $row[default_fontsize], "' face='", $row[default_fontface], "'><b>Menu Font Size:</b></td><td><input type='text' size='2' value='", $row[menu_fontsize], "' name='menu_fontsize'></td></tr>";
echo "<tr><td align='right'><font size='", $row[default_fontsize], "' face='", $row[default_fontface], "'><b>Menu Font Face:</b></td><td><input type='text' size='20' value='", $row[menu_fontface], "' name='menu_fontface'></td></tr>";
echo "<tr><td align='right'><font size='", $row[default_fontsize], "' face='", $row[default_fontface], "'><b>Menu Font Color:</b></td><td>";
echo "<select name='menu_fontcolor'>";
	dbconnect($dbusername,$dbuserpasswd);
	$cresult=mysql_query("select * from colors order by label");
	while($colorlist=mysql_fetch_array($cresult))
		{
                echo "<option value='", $colorlist[hex], "'";
		if ($colorlist[hex] == $row[menu_fontcolor]) { echo " selected"; }
		echo ">", $colorlist[label];
		}
echo "</select></td></tr>";
echo "<tr><td align='right'><font size='", $row[default_fontsize], "' face='", $row[default_fontface], "'><b>Menu Wallpaper:</b> <a href='wallpaper/' target='_new'><img src='icons/magnify1a.gif' border='0' align='absmiddle'></a></td><td><input type='text' size='20' value='", $row[menuwallpaper], "' name='menuwallpaper'></td></tr>";

echo "<tr><td align='right'><font size='", $row[default_fontsize], "' face='", $row[default_fontface], "'><b>Number of Columns in Menu:</b></td><td>";
echo "<select name='menu_columns'><option>1<option";
if ($row[menu_columns] != 1) { echo " selected"; }
echo ">2</select></td></tr>";
echo "<tr><td align='right'><font size='", $row[default_fontsize], "' face='", $row[default_fontface], "'><b>Allow Menu to Scroll</b></td><td>";
echo "<select name='menu_scroll'><option>yes<option";
if ($row[menu_scroll] != 'yes') { echo " selected"; }
echo ">no</select></td></tr>";

echo "<tr><td align='right'><font size='", $row[default_fontsize], "' face='", $row[default_fontface], "'><b>Show News:</b></td><td>";
if ($shownews == 'no')  { echo "No<input type='hidden' name='want_news' value='", $row[want_news], "'>"; }
if ($shownews == 'yes') { echo "Yes<input type='hidden' name='want_news' value='", $row[want_news], "'>"; }
if ($shownews == 'user')
	{
	echo "<input type='checkbox' name='want_news' value='y'";
if ($row[want_news] == 'y') { echo " checked"; }
	echo ">";
	}
if ($shownews == 'restrict' and $row[perm_news] == 'y')
	{
	echo "<input type='checkbox' name='want_news' value='y'";
if ($row[want_news] == 'y') { echo " checked"; }
	echo ">";
	}
if ($shownews == 'restrict' and $row[perm_news] == 'n') { echo "No<input type='hidden' name='want_news' value='", $row[want_news], "'>"; }
echo "</td></tr>";

echo "<tr><td align='right'><font size='", $row[default_fontsize], "' face='", $row[default_fontface], "'><b>Show Calendar:</b></td><td>";
if ($showcalendar == 'no')  { echo "No<input type='hidden' name='want_calendar' value='", $row[want_calendar], "'>"; }
if ($showcalendar == 'yes') { echo "Yes<input type='hidden' name='want_calendar' value='", $row[want_calendar], "'>"; }
if ($showcalendar == 'user')
	{
	echo "<input type='checkbox' name='want_calendar' value='y'";
if ($row[want_calendar] == 'y') { echo " checked"; }
	echo ">";
	}
if ($showcalendar == 'restrict' and $row[perm_calendar] == 'y')
	{
	echo "<input type='checkbox' name='want_calendar' value='y'";
if ($row[want_calendar] == 'y') { echo " checked"; }
	echo ">";
	}
if ($showcalendar == 'restrict' and $row[perm_calendar] == 'n') { echo "No<input type='hidden' name='want_calendar' value='", $row[want_calendar], "'>"; }
echo "</td></tr>";

echo "<tr><td align='right'><font size='", $row[default_fontsize], "' face='", $row[default_fontface], "'><b>Show Rolodex:</b></td><td>";
if ($showrolodex == 'no')  { echo "No<input type='hidden' name='want_rolodex' value='", $row[want_rolodex], "'>"; }
if ($showrolodex == 'yes') { echo "Yes<input type='hidden' name='want_rolodex' value='", $row[want_rolodex], "'>"; }
if ($showrolodex == 'user')
	{
	echo "<input type='checkbox' name='want_rolodex' value='y'";
if ($row[want_rolodex] == 'y') { echo " checked"; }
	echo ">";
	}
if ($showrolodex == 'restrict' and $row[perm_rolodex] == 'y')
	{
	echo "<input type='checkbox' name='want_rolodex' value='y'";
if ($row[want_rolodex] == 'y') { echo " checked"; }
  	echo ">";
	}
if ($showrolodex == 'restrict' and $row[perm_rolodex] == 'n') { echo "No<input type='hidden' name='want_rolodex' value='", $row[want_rolodex], "'>"; }
echo "</td></tr>";

echo "<tr><td align='right'><font size='", $row[default_fontsize], "' face='", $row[default_fontface], "'><b>Show Contact Log:</b></td><td>";
if ($showcontact == 'no')  { echo "No<input type='hidden' name='want_contact' value='", $row[want_contact], "'>"; }
if ($showcontact == 'yes') { echo "Yes<input type='hidden' name='want_contact' value='", $row[want_contact], "'>"; }
if ($showcontact == 'user')
	{
	echo "<input type='checkbox' name='want_contact' value='y'";
if ($row[want_contact] == 'y') { echo " checked"; }
	echo ">";
	}
if ($showcontact == 'restrict' and $row[perm_contact] == 'y')
	{
	echo "<input type='checkbox' name='want_contact' value='y'";
if ($row[want_contact] == 'y') { echo " checked"; }
  	echo ">";
	}
if ($showcontact == 'restrict' and $row[perm_contact] == 'n') { echo "No<input type='hidden' name='want_contact' value='", $row[want_contact], "'>"; }
echo "</td></tr>";

echo "<tr><td align='right'><font size='", $row[default_fontsize], "' face='", $row[default_fontface], "'><b>Show Network:</b></td><td>";
if ($shownetwork == 'no')  { echo "No<input type='hidden' name='want_network' value='", $row[want_network], "'>"; }
if ($shownetwork == 'yes') { echo "Yes<input type='hidden' name='want_network' value='", $row[want_network], "'>"; }
if ($shownetwork == 'user')
	{
	echo "<input type='checkbox' name='want_network' value='y'";
if ($row[want_network] == 'y') { echo " checked"; }
	echo ">";
	}
if ($shownetwork == 'restrict' and $row[perm_network] == 'y')
	{
	echo "<input type='checkbox' name='want_network' value='y'";
if ($row[want_network] == 'y') { echo " checked"; }
	echo ">";
	}
if ($shownetwork == 'restrict' and $row[perm_network] == 'n') { echo "No<input type='hidden' name='want_network' value='", $row[want_network], "'>"; }
echo "</td></tr>";

echo "<tr><td align='right'><font size='", $row[default_fontsize], "' face='", $row[default_fontface], "'><b>Show Task List:</b></td><td>";
if ($showtasklist == 'no')  { echo "No<input type='hidden' name='want_tasklist' value='", $row[want_tasklist], "'>"; }
if ($showtasklist == 'yes') { echo "Yes<input type='hidden' name='want_tasklist' value='", $row[want_tasklist], "'>"; }
if ($showtasklist == 'user')
	{
	echo "<input type='checkbox' name='want_tasklist' value='y'";
if ($row[want_tasklist] == 'y') { echo " checked"; }
	echo ">";
	}
if ($showtasklist == 'restrict' and $row[perm_tasklist] == 'y')
	{
	echo "<input type='checkbox' name='want_tasklist' value='y'";
if ($row[want_tasklist] == 'y') { echo " checked"; }
	echo ">";
	}
if ($showtasklist == 'restrict' and $row[perm_tasklist] == 'n') { echo "No<input type='hidden' name='want_tasklist' value='", $row[want_tasklist], "'>"; }
echo "</td></tr>";

echo "<tr><td align='right'><font size='", $row[default_fontsize], "' face='", $row[default_fontface], "'><b>Show Time Sheet:</b></td><td>";
if ($showtimesheet == 'no')  { echo "No<input type='hidden' name='want_timesheet' value='", $row[want_timesheet], "'>"; }
if ($showtimesheet == 'yes') { echo "Yes<input type='hidden' name='want_timesheet' value='", $row[want_timesheet], "'>"; }
if ($showtimesheet == 'user')
	{
	echo "<input type='checkbox' name='want_timesheet' value='y'";
if ($row[want_timesheet] == 'y') { echo " checked"; }
	echo ">";
	}
if ($showtimesheet == 'restrict' and $row[perm_timesheet] == 'y')
	{
	echo "<input type='checkbox' name='want_timesheet' value='y'";
if ($row[want_timesheet] == 'y') { echo " checked"; }
	echo ">";
	}
if ($showtimesheet == 'restrict' and $row[perm_timesheet] == 'n') { echo "No<input type='hidden' name='want_timesheet' value='", $row[want_timesheet], "'>"; }
echo "</td></tr>";

echo "<tr><td align='right'><font size='", $row[default_fontsize], "' face='", $row[default_fontface], "'><b>Show Time Sheet Admin:</b></td><td>";
if ($showtsadmin == 'no')  { echo "No<input type='hidden' name='want_timesheetcp' value='", $row[want_timesheetcp], "'>"; }
if ($showtsadmin == 'yes') { echo "Yes<input type='hidden' name='want_timesheetcp' value='", $row[want_timesheetcp], "'>"; }
if ($showtsadmin == 'user')
	{
	echo "<input type='checkbox' name='want_timesheetcp' value='y'";
if ($row[want_timesheetcp] == 'y') { echo " checked"; }
	echo ">";
	}
if ($showtsadmin == 'restrict' and $row[perm_timesheetcp] == 'y')
	{
	echo "<input type='checkbox' name='want_timesheetcp' value='y'";
if ($row[want_timesheetcp] == 'y') { echo " checked"; }
	echo ">";
	}
if ($showtsadmin == 'restrict' and $row[perm_timesheetcp] == 'n') { echo "No<input type='hidden' name='want_timesheetcp' value='", $row[want_timesheetcp], "'>"; }
echo "</td></tr>";

echo "<tr><td align='right'><font size='", $row[default_fontsize], "' face='", $row[default_fontface], "'><b>Show Surveys:</b></td><td>";
if ($showsurvey == 'no')  { echo "No<input type='hidden' name='want_survey' value='", $row[want_survey], "'>"; }
if ($showsurvey == 'yes') { echo "Yes<input type='hidden' name='want_survey' value='", $row[want_survey], "'>"; }
if ($showsurvey == 'user')
	{
	echo "<input type='checkbox' name='want_survey' value='y'";
if ($row[want_survey] == 'y') { echo " checked"; }
	echo ">";
	}
if ($showsurvey == 'restrict' and $row[perm_survey] == 'y')
	{
	echo "<input type='checkbox' name='want_survey' value='y'";
if ($row[want_survey] == 'y') { echo " checked"; }
	echo ">";
	}
if ($showsurvey == 'restrict' and $row[perm_survey] == 'n') { echo "No<input type='hidden' name='want_survey' value='", $row[want_survey], "'>"; }
echo "</td></tr>";

echo "<tr><td align='right'><font size='", $row[default_fontsize], "' face='", $row[default_fontface], "'><b>Show Admin:</b></td><td>";
if ($showadmin == 'no')  { echo "No<input type='hidden' name='want_admin' value='", $row[want_admin], "'>"; }
if ($showadmin == 'yes') { echo "Yes<input type='hidden' name='want_admin' value='", $row[want_admin], "'>"; }
if ($showadmin == 'user')
	{
	echo "<input type='checkbox' name='want_admin' value='y'";
if ($row[want_admin] == 'y') { echo " checked"; }
	echo ">";
	}
if ($showadmin == 'restrict' and $row[perm_admin] == 'y')
	{
	echo "<input type='checkbox' name='want_admin' value='y'";
if ($row[want_admin] == 'y') { echo " checked"; }
	echo ">";
	}
if ($showadmin == 'restrict' and $row[perm_admin] == 'n') { echo "No<input type='hidden' name='want_admin' value='", $row[want_admin], "'>"; }
echo "</td></tr>";

echo "<tr><td align='right'><font size='", $row[default_fontsize], "' face='", $row[default_fontface], "'><b>Show Setup:</b></td><td>";
if ($showsetup == 'no')  { echo "No<input type='hidden' name='want_setup' value='", $row[want_setup], "'>"; }
if ($showsetup == 'yes') { echo "Yes<input type='hidden' name='want_setup' value='", $row[want_setup], "'>"; }
if ($showsetup == 'user')
	{
	echo "<input type='checkbox' name='want_setup' value='y'";
if ($row[want_setup] == 'y') { echo " checked"; }
	echo ">";
	}
if ($showsetup == 'restrict' and $row[perm_setup] == 'y')
	{
	echo "<input type='checkbox' name='want_setup' value='y'";
if ($row[want_setup] == 'y') { echo " checked"; }
	echo ">";
	}
if ($showsetup == 'restrict' and $row[perm_setup] == 'n') { echo "No<input type='hidden' name='want_setup' value='", $row[want_setup], "'>"; }
echo "</td></tr>";

echo "<tr><td align='left' colspan='2' bgcolor='", $setting[headinghighlight], "'><font size='", $row[default_fontsize], "' face='", $row[default_fontface], "' color='", $setting[default_fontcolor], "'><b><i>Main Window Settings</i></b></td></tr>";
echo "<tr><td align='right'><font size='", $row[default_fontsize], "' face='", $row[default_fontface], "'><b>Main Window Font Color:</b></td><td>";
echo "<select name='default_fontcolor'>";
	dbconnect($dbusername,$dbuserpasswd);
	$cresult=mysql_query("select * from colors order by label");
	while($colorlist=mysql_fetch_array($cresult))
		{
                echo "<option value='", $colorlist[hex], "'";
		if ($colorlist[hex] == $row[default_fontcolor]) { echo " selected"; }
		echo ">", $colorlist[label];
		}
echo "</select></td></tr>";
echo "<tr><td align='right'><font size='", $row[default_fontsize], "' face='", $row[default_fontface], "'><b>Main Window Font Face:</b></td><td><input type='text' size='20' value='", $row[default_fontface], "' name='default_fontface'></td></tr>";
echo "<tr><td align='right'><font size='", $row[default_fontsize], "' face='", $row[default_fontface], "'><b>Main Window Font Size:</b></td><td><input type='text' size='2' value='", $row[default_fontsize], "' name='default_fontsize'></td></tr>";
echo "<tr><td align='right'><font size='", $row[default_fontsize], "' face='", $row[default_fontface], "'><b>Main Window Background Color:</b></td><td>";
echo "<select name='default_bgcolor'>";
	dbconnect($dbusername,$dbuserpasswd);
	$cresult=mysql_query("select * from colors order by label");
	while($colorlist=mysql_fetch_array($cresult))
		{
                echo "<option value='", $colorlist[hex], "'";
		if ($colorlist[hex] == $row[default_bgcolor]) { echo " selected"; }
		echo ">", $colorlist[label];
		}
echo "</select></td></tr>";
echo "<tr><td align='right'><font size='", $row[default_fontsize], "' face='", $row[default_fontface], "'><b>Highlighted Sub-heading Background Color:</b></td><td>";
echo "<select name='headinghighlight'>";
	dbconnect($dbusername,$dbuserpasswd);
	$cresult=mysql_query("select * from colors order by label");
	while($colorlist=mysql_fetch_array($cresult))
		{
                echo "<option value='", $colorlist[hex], "'";
		if ($colorlist[hex] == $row[headinghighlight]) { echo " selected"; }
		echo ">", $colorlist[label];
		}
echo "</select></td></tr>";


echo "<tr><td align='right'><font size='", $row[default_fontsize], "' face='", $row[default_fontface], "'><b>Main Window Wallpaper:</b> <a href='wallpaper/' target='_new'><img src='icons/magnify1a.gif' border='0' align='absmiddle'></a> </td><td><input type='text' size='20' value='", $row[bgwallpaper], "' name='bgwallpaper'></td></tr>";



echo "<tr><td align='left' colspan='2' bgcolor='", $setting[headinghighlight], "'><font size='", $row[default_fontsize], "' face='", $row[default_fontface], "' color='", $setting[default_fontcolor], "'><b><i>Main Window Heading Settings</i></b></td></tr>";
// echo "---", $useheader, "---", $row[useheader];
if ($useheader == 'user')
	{
	echo "<tr><td align='right'><font size='", $row[default_fontsize], "' face='", $row[default_fontface], "'><b>Show Headers:</b></td><td><input type='checkbox' name='newuseheader' value='y'";
	if ($row[useheader] == 'y') { echo " checked"; }
	echo "></td></tr>";
	}
echo "<tr><td align='right'><font size='", $row[default_fontsize], "' face='", $row[default_fontface], "'><b>Main Window Heading Font Color:</b></td><td>";
echo "<select name='heading_fontcolor'>";
	dbconnect($dbusername,$dbuserpasswd);
	$cresult=mysql_query("select * from colors order by label");
	while($colorlist=mysql_fetch_array($cresult))
		{
                echo "<option value='", $colorlist[hex], "'";
		if ($colorlist[hex] == $row[heading_fontcolor]) { echo " selected"; }
		echo ">", $colorlist[label];
		}
echo "</select></td></tr>";
echo "<tr><td align='right'><font size='", $row[default_fontsize], "' face='", $row[default_fontface], "'><b>Main Window Heading Font Face:</b></td><td><input type='text' size='20' value='", $row[heading_fontface], "' name='heading_fontface'></td></tr>";
echo "<tr><td align='right'><font size='", $row[default_fontsize], "' face='", $row[default_fontface], "'><b>Main Window Heading Font Size:</b></td><td><input type='text' size='2' value='", $row[heading_fontsize], "' name='heading_fontsize'></td></tr>";
echo "<tr><td align='right'><font size='", $row[default_fontsize], "' face='", $row[default_fontface], "'><b>Main Window Heading Background Color:</b></td><td>";
echo "<select name='heading_bgcolor'>";
	dbconnect($dbusername,$dbuserpasswd);
	$cresult=mysql_query("select * from colors order by label");
	while($colorlist=mysql_fetch_array($cresult))
		{
                echo "<option value='", $colorlist[hex], "'";
		if ($colorlist[hex] == $row[heading_bgcolor]) { echo " selected"; }
		echo ">", $colorlist[label];
		}
echo "</select></td></tr>";
echo "<tr><td align='right'><font size='", $row[default_fontsize], "' face='", $row[default_fontface], "'><b>Heading Wallpaper:</b> <a href='wallpaper/' target='_new'><img src='icons/magnify1a.gif' border='0' align='absmiddle'></a> </td><td><input type='text' size='20' value='", $row[headingwallpaper], "' name='headingwallpaper'></td></tr>";

echo "<tr><td align='left' colspan='2' bgcolor='", $setting[headinghighlight], "'><font size='", $row[default_fontsize], "' face='", $row[default_fontface], "' color='", $setting[default_fontcolor], "'><b><i>News Page Settings</i></b></td></tr>";
echo "<tr><td align='right'><font size='", $row[default_fontsize], "' face='", $row[default_fontface], "'><b>Show Admin Messages:</b></td><td>";
if($row[perm_admin] == 'y')
	{
	echo "<input type='checkbox' name='want_admin_msg' value='y'";
	if ($row[want_admin_msg] == 'y') { echo " checked"; }
	echo ">";
	} else { echo "No"; }
echo "</td></tr>";
echo "<tr><td align='right'><font size='", $row[default_fontsize], "' face='", $row[default_fontface], "'><b>Show Quote on News Page:</b></td><td> </td></tr>";
echo "</td></tr>";echo "<tr><td align='right'><font size='", $row[default_fontsize], "' face='", $row[default_fontface], "'><b>Dark:</b></td><td><input type='checkbox' name='quotes_dark' value='y'";
if ($row[quotes_dark] == 'y') { echo " checked"; }
echo "></td></tr>";echo "<tr><td align='right'><font size='", $row[default_fontsize], "' face='", $row[default_fontface], "'><b>Happy:</b></td><td><input type='checkbox' name='quotes_happy' value='y'";
if ($row[quotes_happy] == 'y') { echo " checked"; }
echo "></td></tr>";echo "<tr><td align='right'><font size='", $row[default_fontsize], "' face='", $row[default_fontface], "'><b>Odd:</b></td><td><input type='checkbox' name='quotes_odd' value='y'";
if ($row[quotes_odd] == 'y') { echo " checked"; }
echo "></td></tr>";echo "<tr><td align='right'><font size='", $row[default_fontsize], "' face='", $row[default_fontface], "'><b>Fortunes:</b></td><td><input type='checkbox' name='quotes_fortune' value='y'";
if ($row[quotes_fortune] == 'y') { echo " checked"; }
echo "></td></tr>";
echo "<tr><td align='right'><font size='", $row[default_fontsize], "' face='", $row[default_fontface], "'><b>Crude:</b></td><td><input type='checkbox' name='quotes_crude' value='y'";
if ($row[quotes_crude] == 'y') { echo " checked"; }
echo "></td></tr>";
echo "<tr><td align='right'><font size='", $row[default_fontsize], "' face='", $row[default_fontface], "'><b>Joke:</b></td><td><input type='checkbox' name='quotes_joke' value='y'";
if ($row[quotes_joke] == 'y') { echo " checked"; }
echo "></td></tr>";
echo "<tr><td align='right'><font size='", $row[default_fontsize], "' face='", $row[default_fontface], "'><b>News Page Link Bar:</b></td><td> </td></tr>";
echo "<tr><td align='right'><font size='", $row[default_fontsize], "' face='", $row[default_fontface], "'><b>Show Link Bar:</b></td><td><input type='checkbox' name='utilitybar' value='true'";
if ($row[utilitybar] == 'true') { echo " checked"; }
echo "></td></tr>";
echo "<tr><td align='right'><font size='", $row[default_fontsize], "' face='", $row[default_fontface], "'><b>Link Bar Background Color:</b></td><td>";
echo "<select name='linkbarbg'>";
	dbconnect($dbusername,$dbuserpasswd);
	$cresult=mysql_query("select * from colors order by label");
	while($colorlist=mysql_fetch_array($cresult))
		{
                echo "<option value='", $colorlist[hex], "'";
		if ($colorlist[hex] == $row[linkbarbg]) { echo " selected"; }
		echo ">", $colorlist[label];
		}
echo "</select></td></tr>";
echo "<tr><td align='right'><font size='", $row[default_fontsize], "' face='", $row[default_fontface], "'><b>Link Bar Font Color:</b></td><td>";
echo "<select name='lb_fontcolor'>";
	dbconnect($dbusername,$dbuserpasswd);
	$cresult=mysql_query("select * from colors order by label");
	while($colorlist=mysql_fetch_array($cresult))
		{
                echo "<option value='", $colorlist[hex], "'";
		if ($colorlist[hex] == $row[lb_fontcolor]) { echo " selected"; }
		echo ">", $colorlist[label];
		}
echo "</select></td></tr>";
echo "<tr><td align='right'><font size='", $row[default_fontsize], "' face='", $row[default_fontface], "'><b>Include Technical Links:</b></td><td>";
if($row[perm_admin] == 'y')
	{
	echo "<input type='checkbox' name='want_admin_news' value='y'";
	if ($row[want_admin_news] == 'y') { echo " checked"; }
	echo ">";
	} else { echo "No"; }
echo "</td></tr>";
echo "<tr><td align='right'><font size='", $row[default_fontsize], "' face='", $row[default_fontface], "'><b>Show Admin Link Bar:</b></td><td>";
if($row[perm_admin] == 'y')
	{
	echo "<input type='checkbox' name='want_admin_bar' value='y'";
	if ($row[want_admin_bar] == 'y') { echo " checked"; }
	echo ">";
	} else { echo "No"; }
echo "</td></tr>";
echo "<tr><td align='right'><font size='", $row[default_fontsize], "' face='", $row[default_fontface], "'><b>Admin Link Bar Background Color:</b></td><td>";
if($row[perm_admin] == 'y')
	{
echo "<select name='adminlbbg'>";
	dbconnect($dbusername,$dbuserpasswd);
	$cresult=mysql_query("select * from colors order by label");
	while($colorlist=mysql_fetch_array($cresult))
		{
                echo "<option value='", $colorlist[hex], "'";
		if ($colorlist[hex] == $row[adminlbbg]) { echo " selected"; }
		echo ">", $colorlist[label];
		}
echo "</select></td></tr>";
	} else { echo "<input type='hidden' name='adminlbbg' value='", $row[adminlbbg], "'>Not Applicable"; }
echo "<tr><td align='right'><font size='", $row[default_fontsize], "' face='", $row[default_fontface], "'><b>Admin Link Bar Font Color:</b></td><td>";
if($row[perm_admin] == 'y')
	{
echo "<select name='ab_fontcolor'>";
	dbconnect($dbusername,$dbuserpasswd);
	$cresult=mysql_query("select * from colors order by label");
	while($colorlist=mysql_fetch_array($cresult))
		{
                echo "<option value='", $colorlist[hex], "'";
		if ($colorlist[hex] == $row[ab_fontcolor]) { echo " selected"; }
		echo ">", $colorlist[label];
		}
echo "</select></td></tr>";
	} else { echo "<input type='hidden' name='ab_fontcolor' value='", $row[ab_fontcolor], "'>Not Applicable"; }
echo "<tr><td align='right' bgcolor='", $setting[headinghighlight], "' colspan='2'><input type='hidden' name='changes' value='yes'><input type='submit' value='Make Changes'></td></tr></table></form></center>";	
	}
?>
</body></html>

