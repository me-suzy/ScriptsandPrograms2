<?php
/*
** sCssBoard, an extremely fast and flexible CSS-based message board system
** Copyright (CC) 2005 Elton Muuga
**
** This work is licensed under the Creative Commons Attribution-NonCommercial-ShareAlike License. 
** To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-sa/2.0/ or send 
** a letter to Creative Commons, 559 Nathan Abbott Way, Stanford, California 94305, USA.
*/
?>
<?php

if ($current_user[users_level] < 3) {
	die("Insufficient access level.");
}

if ($board_name) {
	@mysql_query("update $_CON[prefix]settings set setting_value = '$_POST[board_name]' where setting_name = 'board_name'");
	@mysql_query("update $_CON[prefix]settings set setting_value = '$_POST[allow_signups]' where setting_name = 'allow_signups'");
	@mysql_query("update $_CON[prefix]settings set setting_value = '$_POST[cookie_url]' where setting_name = 'cookie_url'");
	@mysql_query("update $_CON[prefix]settings set setting_value = '$_POST[cookie_path]' where setting_name = 'cookie_path'");
	@mysql_query("update $_CON[prefix]settings set setting_value = '$_POST[redir_method]' where setting_name = 'redir_method'");
	@mysql_query("update $_CON[prefix]settings set setting_value = '$_POST[sig_bbcode]' where setting_name = 'sig_bbcode'");
	@mysql_query("update $_CON[prefix]settings set setting_value = '$_POST[debug_level]' where setting_name = 'debug_level'");
	@mysql_query("update $_CON[prefix]settings set setting_value = '$_POST[date_format]' where setting_name = 'date_format'");
	@mysql_query("update $_CON[prefix]settings set setting_value = '$_POST[default_style]' where setting_name = 'default_style'");
	@mysql_query("update $_CON[prefix]settings set setting_value = '$_POST[use_relative_dates]' where setting_name = 'use_relative_dates'");
	echo "<center><b>Settings saved.</b></center><br /><br />";
	echo redirect("?act=admin-general", 1);


} else {


include("navbar.php");
echo "
<br />
<form action='?act=admin-general' method='post'>
<table width='400' border='0' cellpadding='2' cellspacing='2' align='center'>
<tr>
	<td colspan='3' class='catheader' align='center' style='font-size:14px;'>
		<strong>General Settings</strong>
	</td>
</tr>
<tr>
	<td class='forum_stat_hd' width='200'>Board Name</td>";
$setting_buffer = @mysql_fetch_array(@mysql_query("select * from $_CON[prefix]settings where setting_name = 'board_name'"));

	echo "<td class='forum_name' width='200'><input type='text' class='input' size='30' name='board_name' value='$setting_buffer[setting_value]'></td>
</tr>
<tr>
	<td class='forum_stat_hd' width='200'>Allow New Registrations</td>";
$setting_buffer = @mysql_fetch_array(@mysql_query("select * from $_CON[prefix]settings where setting_name = 'allow_signups'"));
	echo "<td class='forum_name' width='200'>
	<select name='allow_signups'>
		<option value='yes'";
		if ($setting_buffer[setting_value] == "yes") { echo " selected='selected'"; }
	echo ">Yes</option>";
	echo "<option value='no'";
		if ($setting_buffer[setting_value] == "no") { echo " selected='selected'"; }
	echo ">No</option>";
	echo "</select>
	</td>
</tr>
<tr>
	<td colspan='3' class='catheader' align='center' style='font-size:14px;'>
		<strong>Profile Settings</strong>
	</td>
</tr>
<tr>
	<td class='forum_stat_hd' width='200'>Default Stylesheet</td>
	<td class='forum_name' width='200'>
		<select name='default_style'>"; 
			$setting_buffer = @mysql_fetch_array(@mysql_query("select * from $_CON[prefix]settings where setting_name = 'default_style'"));
			$dir = opendir('./styles');  
			$css_files = array();  
			while(($file = readdir($dir)) !== false)  {  
				if(strlen($file) > 4 && substr(strtolower($file), strlen($file) - 4) === '.css' && !is_dir($file)) {  
					$css_files[] = $file;  
				}  
			}  
			closedir($dir); 
			foreach ($css_files as $filename) {
				echo "<option value='$filename'";
				if ($setting_buffer[setting_value] == $filename) {
					echo " selected='selected'";
				}
				echo ">$filename</option>";
			}
		echo "</select>
	</td>
</tr>
<tr>
	<td class='forum_stat_hd' width='200'>Allow BBCode In Signatures</td>";
$setting_buffer = @mysql_fetch_array(@mysql_query("select * from $_CON[prefix]settings where setting_name = 'sig_bbcode'"));
	echo "<td class='forum_name' width='200'>
	<select name='sig_bbcode'>
		<option value='yes'";
		if ($setting_buffer[setting_value] == "yes") { echo " selected='true'"; }
	echo ">Yes</option>";
	echo "<option value='no'";
		if ($setting_buffer[setting_value] == "no") { echo " selected='true'"; }
	echo ">No</option>";
	echo "</select>
	</td>
</tr>
<tr>
	<td colspan='3' class='catheader' align='center' style='font-size:14px;'>
		<strong>Date Settings</strong>
	</td>
</tr>
<tr>
	<td class='forum_stat_hd' width='200'>Date Format [<a href='http://www.php.net/manual/en/function.date.php'>PHP date()</a>]</td>";
$setting_buffer = @mysql_fetch_array(@mysql_query("select * from $_CON[prefix]settings where setting_name = 'date_format'"));
	echo "<td class='forum_name' width='200'><input type='text' class='input' size='30' name='date_format' value='$setting_buffer[setting_value]'></td>
</tr>
<tr>
	<td class='forum_stat_hd' width='200'>Use Relative Dating [<a href=\"javascript:alert('If set to Yes, dates younger than two days will be shown in an easy-to-read form, i.e. \'8 Minutes Ago\'.');\">?</a>]</td>";
$setting_buffer = @mysql_fetch_array(@mysql_query("select * from $_CON[prefix]settings where setting_name = 'use_relative_dates'"));
	echo "<td class='forum_name' width='200'>
	<select name='use_relative_dates'>
		<option value='yes'";
		if ($setting_buffer[setting_value] == "yes") { echo " selected='true'"; }
	echo ">Yes</option>";
	echo "<option value='no'";
		if ($setting_buffer[setting_value] == "no") { echo " selected='true'"; }
	echo ">No</option>";
	echo "</select>
	</td>
</tr>
<tr>
	<td colspan='3' class='catheader' align='center' style='font-size:14px;'>
		<strong>Advanced Settings</strong>
	</td>
</tr>
<tr>
	<td class='forum_stat_hd' width='200'>Cookie Domain [<a href=\"javascript:alert('The domain that the cookie is available on. For example, if you want it to be available on all subdomains of example.com, set this to .example.com . You can also use subdomain.example.com to set it only to that subdomain. Usually, you can leave this blank.');\">?</a>]</td>";
$setting_buffer = @mysql_fetch_array(@mysql_query("select * from $_CON[prefix]settings where setting_name = 'cookie_url'"));
	echo "<td class='forum_name' width='200'><input type='text' class='input' size='30' name='cookie_url' value='$setting_buffer[setting_value]'></td>
</tr>
<tr>
	<td class='forum_stat_hd' width='200'>Cookie Path [<a href=\"javascript:alert('The path within the server that the cookie is available on. Use this setting if you have more than one forum on a domain. If not, this should be set to /. If using this setting, be sure to add a trailing slash.');\">?</a>]</td>";
$setting_buffer = @mysql_fetch_array(@mysql_query("select * from $_CON[prefix]settings where setting_name = 'cookie_path'"));
	echo "<td class='forum_name' width='200'><input type='text' class='input' size='30' name='cookie_path' value='$setting_buffer[setting_value]'></td>
</tr>
<tr>
	<td class='forum_stat_hd' width='200'>Redirect Method [<a href=\"javascript:alert('JavaScript is usually faster than meta, however, it will not function if a user disables it in his/her browser.');\">?</a>]</td>";
$setting_buffer = @mysql_fetch_array(@mysql_query("select * from $_CON[prefix]settings where setting_name = 'redir_method'"));
	echo "<td class='forum_name' width='200'><select name='redir_method'>";
	echo "<option value='meta'";
	if ($setting_buffer[setting_value] == "meta") { echo " selected='yes'"; }
	echo ">META Redirect</option>";
	echo "<option value='javascript'";
	if ($setting_buffer[setting_value] == "javascript") { echo " selected='yes'"; }
	echo ">Javascript Location</option>";
	echo "</select></td>
</tr>
<tr>
	<td class='forum_stat_hd' width='200'>Additional Footer Info</td>";
$setting_buffer = @mysql_fetch_array(@mysql_query("select * from $_CON[prefix]settings where setting_name = 'debug_level'"));
	echo "<td class='forum_name' width='200'>
	<select name='debug_level'>
		<option value='0'";
		if ($setting_buffer[setting_value] == "0") { echo " selected='true'"; }
	echo ">None</option>";
	echo "<option value='1'";
		if ($setting_buffer[setting_value] == "1") { echo " selected='true'"; }
	echo ">Page Generation Time</option>";
	echo "</select>
	</td>
</tr>
<tr>
	<td class='forum_stat_hd' colspan='2' align='center'><input type='submit' name='submit' value='Save'></form></td>
</tr>
</table>

<br /><br />";
}
?>