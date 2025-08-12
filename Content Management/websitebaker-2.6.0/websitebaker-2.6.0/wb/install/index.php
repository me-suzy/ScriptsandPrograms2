<?php

// $Id: index.php 240 2005-11-23 15:17:50Z stefan $

/*

 Website Baker Project <http://www.websitebaker.org/>
 Copyright (C) 2004-2005, Ryan Djurovich

 Website Baker is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 Website Baker is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with Website Baker; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/

// Start a session
if(!defined('SESSION_STARTED')) {
	session_name('wb_session_id');
	session_start();
	define('SESSION_STARTED', true);
}

// Check if the page has been reloaded
if(!isset($_GET['sessions_checked']) OR $_GET['sessions_checked'] != 'true') {
   // Set session variable
   $_SESSION['session_support'] = '<font class="good">Enabled</font>';
   // Reload page
   header('Location: index.php?sessions_checked=true');
} else {
   // Check if session variable has been saved after reload
   if(isset($_SESSION['session_support'])) {
      $session_support = $_SESSION['session_support'];
   } else {   
      $session_support = '<font class="bad">Disabled</font>';
   }
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>Website Baker Installation Wizard</title>
<link href="stylesheet.css" rel="stylesheet" type="text/css">
<script language="javascript" type="text/javascript">

function confirm_link(message, url) {
	if(confirm(message)) location.href = url;
}
function change_os(type) {
	if(type == 'linux') {
		document.getElementById('operating_system_linux').checked = true;
		document.getElementById('operating_system_windows').checked = false;
		document.getElementById('file_perms_box').style.display = 'block';
	} else if(type == 'windows') {
		document.getElementById('operating_system_linux').checked = false;
		document.getElementById('operating_system_windows').checked = true;
		document.getElementById('file_perms_box').style.display = 'none';
	}
}

</script>
</head>
<body>

<table cellpadding="0" cellspacing="0" border="0" width="750" align="center">
<tr>
	<td width="60" valign="top">
		<img src="../admin/interface/logo.png" width="60" height="60" alt="Logo" />
	</td>
	<td width="5">&nbsp;</td>
	<td style="font-size: 20px;">
		<font style="color: #FFFFFF;">Website Baker</font> 
		<font style="color: #DDDDDD;">Installation Wizard</font>
	</td>
</tr>
</table>

<form name="website_baker_installation_wizard" action="save.php" method="post">
<input type="hidden" name="url" value="" />
<input type="hidden" name="username_fieldname" value="admin_username" />
<input type="hidden" name="password_fieldname" value="admin_password" />
<input type="hidden" name="remember" id="remember" value="true" />

<table cellpadding="0" cellspacing="0" border="0" width="750" align="center" style="margin-top: 10px;">
<tr>
	<td class="content">
	
		<center style="padding: 5px;">
			Welcome to the Website Baker Installation Wizard.
		</center>
		
		<?php
		if(isset($_SESSION['message']) AND $_SESSION['message'] != '') {
			?><div style="width: 700px; padding: 10px; margin-bottom: 5px; border: 1px solid #FF0000; background-color: #FFDBDB;"><b>Error:</b> <?php echo $_SESSION['message']; ?></div><?php
		}
		?>
		<table cellpadding="3" cellspacing="0" width="100%" align="center">
		<tr>
			<td colspan="8"><h1>Step 1</h1>Please check the following requirements are met before continuing...</td>
		</tr>
		<tr>
			<td width="140" style="color: #666666;">PHP Version > 4.1.0</td>
			<td width="35">
				<?php
				$phpversion = substr(PHP_VERSION, 0, 6);
				if($phpversion > 4.1) {
					?><font class="good">Yes</font><?php
				} else {
					?><font class="bad">No</font><?php
				}
				?>
			</td>
			<td width="140" style="color: #666666;">PHP Session Support</td>
			<td width="115"><?php echo $session_support; ?></td>
			<td width="105" style="color: #666666;">PHP Safe Mode</td>
			<td>
				<?php
				if(ini_get('safe_mode')) {
					?><font class="bad">Enabled</font><?php
				} else {
					?><font class="good">Disabled</font><?php
				}	
				?>
			</td>
		</tr>
		</table>
		<table cellpadding="3" cellspacing="0" width="100%" align="center">
		<tr>
			<td colspan="8"><h1>Step 2</h1>Please check the following files/folders are writeable before continuing...</td>
		</tr>
		<tr>
			<td style="color: #666666;">wb/config.php</td>
			<td><?php if(is_writable('../config.php')) { echo '<font class="good">Writeable</font>'; } elseif(!file_exists('../config.php')) { echo '<font class="bad">File Not Found</font>'; } else { echo '<font class="bad">Unwriteable</font>'; } ?></td>
			<td style="color: #666666;">wb/pages/</td>
			<td><?php if(is_writable('../pages/')) { echo '<font class="good">Writeable</font>'; } elseif(!file_exists('../pages/')) { echo '<font class="bad">Directory Not Found</font>'; } else { echo '<font class="bad">Unwriteable</font>'; } ?></td>
			<td style="color: #666666;">wb/media/</td>
			<td><?php if(is_writable('../media/')) { echo '<font class="good">Writeable</font>'; } elseif(!file_exists('../media/')) { echo '<font class="bad">Directory Not Found</font>'; } else { echo '<font class="bad">Unwriteable</font>'; } ?></td>
			<td style="color: #666666;">wb/templates/</td>
			<td><?php if(is_writable('../templates/')) { echo '<font class="good">Writeable</font>'; } elseif(!file_exists('../templates/')) { echo '<font class="bad">Directory Not Found</font>'; } else { echo '<font class="bad">Unwriteable</font>'; } ?></td>
		</tr>
		<tr>
			<td style="color: #666666;">wb/modules/</td>
			<td><?php if(is_writable('../modules/')) { echo '<font class="good">Writeable</font>'; } elseif(!file_exists('../modules/')) { echo '<font class="bad">Directory Not Found</font>'; } else { echo '<font class="bad">Unwriteable</font>'; } ?></td>
			<td style="color: #666666;">wb/languages/</td>
			<td><?php if(is_writable('../languages/')) { echo '<font class="good">Writeable</font>'; } elseif(!file_exists('../languages/')) { echo '<font class="bad">Directory Not Found</font>'; } else { echo '<font class="bad">Unwriteable</font>'; } ?></td>
			<td style="color: #666666;">wb/temp/</td>
			<td><?php if(is_writable('../temp/')) { echo '<font class="good">Writeable</font>'; } elseif(!file_exists('../temp/')) { echo '<font class="bad">Directory Not Found</font>'; } else { echo '<font class="bad">Unwriteable</font>'; } ?></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		</table>
		<table cellpadding="3" cellspacing="0" width="100%" align="center">
		<tr>
			<td colspan="2"><h1>Step 3</h1>Please check your path settings, and select your default timezone...</td>
		</tr>
		<tr>
			<td width="125" style="color: #666666;">
				Absolute URL:
			</td>
			<td>
				<?php
				// Try to guess installation URL
				$guessed_url = 'http://'.$_SERVER["SERVER_NAME"].$_SERVER["SCRIPT_NAME"];
				$guessed_url = rtrim(dirname($guessed_url), 'install');
				?>
				<input type="text" tabindex="1" name="wb_url" style="width: 99%;" value="<?php if(isset($_SESSION['wb_url'])) { echo $_SESSION['wb_url']; } else { echo $guessed_url; } ?>" />
			</td>
		</tr>
		<tr>
			<td style="color: #666666;">
				Default Timezone:
			</td>
			<td>
				<select tabindex="3" name="default_timezone" style="width: 100%;">
					<?php
					$TIMEZONES['-12'] = 'GMT - 12 Hours';
					$TIMEZONES['-11'] = 'GMT -11 Hours';
					$TIMEZONES['-10'] = 'GMT -10 Hours';
					$TIMEZONES['-9'] = 'GMT -9 Hours';
					$TIMEZONES['-8'] = 'GMT -8 Hours';
					$TIMEZONES['-7'] = 'GMT -7 Hours';
					$TIMEZONES['-6'] = 'GMT -6 Hours';
					$TIMEZONES['-5'] = 'GMT -5 Hours';
					$TIMEZONES['-4'] = 'GMT -4 Hours';
					$TIMEZONES['-3.5'] = 'GMT -3.5 Hours';
					$TIMEZONES['-3'] = 'GMT -3 Hours';
					$TIMEZONES['-2'] = 'GMT -2 Hours';
					$TIMEZONES['-1'] = 'GMT -1 Hour';
					$TIMEZONES['0'] = 'GMT';
					$TIMEZONES['1'] = 'GMT +1 Hour';
					$TIMEZONES['2'] = 'GMT +2 Hours';
					$TIMEZONES['3'] = 'GMT +3 Hours';
					$TIMEZONES['3.5'] = 'GMT +3.5 Hours';
					$TIMEZONES['4'] = 'GMT +4 Hours';
					$TIMEZONES['4.5'] = 'GMT +4.5 Hours';
					$TIMEZONES['5'] = 'GMT +5 Hours';
					$TIMEZONES['5.5'] = 'GMT +5.5 Hours';
					$TIMEZONES['6'] = 'GMT +6 Hours';
					$TIMEZONES['6.5'] = 'GMT +6.5 Hours';
					$TIMEZONES['7'] = 'GMT +7 Hours';
					$TIMEZONES['8'] = 'GMT +8 Hours';
					$TIMEZONES['9'] = 'GMT +9 Hours';
					$TIMEZONES['9.5'] = 'GMT +9.5 Hours';
					$TIMEZONES['10'] = 'GMT +10 Hours';
					$TIMEZONES['11'] = 'GMT +11 Hours';
					$TIMEZONES['12'] = 'GMT +12 Hours';
					$TIMEZONES['13'] = 'GMT +13 Hours';
					foreach($TIMEZONES AS $hour_offset => $title) {
						?>
							<option value="<?php echo $hour_offset; ?>"<?php if(isset($_SESSION['default_timezone']) AND $_SESSION['default_timezone'] == $hour_offset) { echo ' selected'; } elseif(!isset($_SESSION['default_timezone']) AND $hour_offset == 0) { echo 'selected'; } ?>><?php echo $title; ?></option>
						<?php
					}
					?>
				</select>
			</td>
		</tr>
		</table>
		<table cellpadding="5" cellspacing="0" width="100%" align="center">
		<tr>
			<td colspan="3"><h1>Step 4</h1>Please specify your operating system information below...</td>
		</tr>
		<tr height="50">
			<td width="170">
				Server Operating System:
			</td>
			<td width="180">
				<input type="radio" tabindex="4" name="operating_system" id="operating_system_linux" onclick="document.getElementById('file_perms_box').style.display = 'block';" value="linux"<?php if(!isset($_SESSION['operating_system']) OR $_SESSION['operating_system'] == 'linux') { echo ' checked'; } ?> />
				<font style="cursor: pointer;" onclick="javascript: change_os('linux');">Linux/Unix based</font>
				<br />
				<input type="radio" tabindex="5" name="operating_system" id="operating_system_windows" onclick="document.getElementById('file_perms_box').style.display = 'none';" value="windows"<?php if(isset($_SESSION['operating_system']) AND $_SESSION['operating_system'] == 'windows') { echo ' checked'; } ?> />
				<font style="cursor: pointer;" onclick="javascript: change_os('windows');">Windows</font>
			</td>
			<td>
				<div name="file_perms_box" id="file_perms_box" style="margin: 0; padding: 0; display: <?php if(isset($_SESSION['operating_system']) AND $_SESSION['operating_system'] == 'windows') { echo 'none'; } else { echo 'block'; } ?>;">
					<input type="checkbox" tabindex="6" name="world_writeable" id="world_writeable" value="true"<?php if(isset($_SESSION['world_writeable']) AND $_SESSION['world_writeable'] == true) { echo 'checked'; } ?> />
					<label for="world_writeable">
						World-writeable file permissions (777)
					</label>
					<br />
					<font class="note">(Please note: this is only recommended for testing environments)</font>
				</div>
			</td>
		</tr>
		</table>
		<table cellpadding="5" cellspacing="0" width="100%" align="center">
		<tr>
			<td colspan="5">Please enter your MySQL database server details below...</td>
		</tr>
		<tr>
			<td width="120" style="color: #666666;">Host Name:</td>
			<td width="230">
				<input type="text" tabindex="7" name="database_host" style="width: 98%;" value="<?php if(isset($_SESSION['database_host'])) { echo $_SESSION['database_host']; } else { echo 'localhost'; } ?>" />
			</td>
			<td width="7">&nbsp;</td>
			<td width="70" style="color: #666666;">Username:</td>
			<td>
				<input type="text" tabindex="9" name="database_username" style="width: 98%;" value="<?php if(isset($_SESSION['database_username'])) { echo $_SESSION['database_username']; } else { echo 'root'; } ?>" />
			</td>
		</tr>
		<tr>
			<td style="color: #666666;">Database Name:</td>
			<td>
				<input type="text" tabindex="8" name="database_name" style="width: 98%;" value="<?php if(isset($_SESSION['database_name'])) { echo $_SESSION['database_name']; } else { echo 'wb'; } ?>" />
			</td>
			<td>&nbsp;</td>
			<td style="color: #666666;">Password:</td>
			<td>
				<input type="password" tabindex="10" name="database_password" style="width: 98%;"<?php if(isset($_SESSION['database_password'])) { echo ' value = "'.$_SESSION['database_password'].'"'; } ?> />
			</td>
		</tr>
		<tr>
			<td style="color: #666666;">Table Prefix:</td>
			<td>
				<input type="text" tabindex="11" name="table_prefix" style="width: 250px;"<?php if(isset($_SESSION['table_prefix'])) { echo ' value = "'.$_SESSION['table_prefix'].'"'; } ?> />
			</td>
			<td>&nbsp;</td>
			<td colspan="2">
				<input type="checkbox" tabindex="12" name="install_tables" id="install_tables" value="true"<?php if(!isset($_SESSION['install_tables'])) { echo ' checked'; } elseif($_SESSION['install_tables'] == 'true') { echo ' checked'; } ?> />
				<label for="install_tables" style="color: #666666;">Install Tables</label>
				<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<span style="font-size: 10px; color: #666666;">(Please note: May remove existing tables and data)</span></td>		
			</td>
		</tr>
		<tr>
			<td colspan="5"><h1>Step 5</h1>Please enter your website title below...</td>
		</tr>
		<tr>
			<td style="color: #666666;" colspan="1">Website Title:</td>
			<td colspan="4">
				<input type="text" tabindex="13" name="website_title" style="width: 99%;" value="<?php if(isset($_SESSION['website_title'])) { echo $_SESSION['website_title']; } ?>" />
			</td>
		</tr>
		<tr>
			<td colspan="5"><h1>Step 6</h1>Please enter your Administrator account details below...</td>
		</tr>
		<tr>
			<td style="color: #666666;">Username:</td>
			<td>
				<input type="text" tabindex="14" name="admin_username" style="width: 98%;" value="<?php if(isset($_SESSION['admin_username'])) { echo $_SESSION['admin_username']; } else { echo 'admin'; } ?>" />
			</td>
			<td>&nbsp;</td>
			<td style="color: #666666;">Password:</td>
			<td>
				<input type="password" tabindex="16" name="admin_password" style="width: 98%;"<?php if(isset($_SESSION['admin_password'])) { echo ' value = "'.$_SESSION['admin_password'].'"'; } ?> />
			</td>
		</tr>
		<tr>
			<td style="color: #666666;">Email:</td>
			<td>
				<input type="text" tabindex="15" name="admin_email" style="width: 98%;"<?php if(isset($_SESSION['admin_email'])) { echo ' value = "'.$_SESSION['admin_email'].'"'; } ?> />
			</td>
			<td>&nbsp;</td>
			<td style="color: #666666;">Re-Password:</td>
			<td>
				<input type="password" tabindex="17" name="admin_repassword" style="width: 98%;"<?php if(isset($_SESSION['admin_password'])) { echo ' value = "'.$_SESSION['admin_password'].'"'; } ?> />
			</td>
		</tr>
		<tr>
			<td colspan="5" style="padding: 10px; padding-bottom: 0;"><h1 style="font-size: 0px;">&nbsp;</h1></td>
		</tr>
		<tr>
			<td colspan="4">
				<table cellpadding="0" cellspacing="0" width="100%" border="0">
				<tr valign="top">
					<td>Please note: &nbsp;</td>
					<td>
						Website Baker is released under the 
						<a href="http://www.gnu.org/licenses/gpl.html" target="_blank" tabindex="19">GNU General Public License</a>
						<br />
						By clicking install, you are accepting the license.
					</td>
				</tr>
				</table>
			</td>
			<td colspan="1" align="right">
				<input type="submit" tabindex="20" name="submit" value="Install Website Baker" class="submit" />
			</td>
		</tr>
		</table>
	
	</td>
</tr>
</table>

</form>

<table cellpadding="0" cellspacing="0" border="0" width="100%" style="padding: 10px 0px 10px 0px;">
<tr>
	<td align="center" style="font-size: 10px;">
		<!-- Please note: the following copyright/license notice must not be removed/modified -->
		<a href="http://www.websitebaker.com/" style="color: #000000;" target="_blank">Website Baker</a>
		is	released under the
		<a href="http://www.gnu.org/licenses/gpl.html" style="color: #000000;" target="_blank">GNU General Public License</a>
		<!-- Please note: the above copyright/license notice must not be removed/modified -->
	</td>
</tr>
</table>

</body>
</html>
