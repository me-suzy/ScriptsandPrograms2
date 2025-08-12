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
if ($addnotes) {
	@mysql_query("update $_CON[prefix]settings set setting_value = '$_POST[notes]' where setting_name = 'admin_notes'");
	$update_msg = "<strong>Changes saved.</strong><br />";
} else {
	$update_msg = "";
}
$admin_notes = @mysql_fetch_array(@mysql_query("select * from $_CON[prefix]settings where setting_name = 'admin_notes'"));

include("admin/navbar.php");

echo "
<br />
<table width='800' border='0' cellpadding='2' cellspacing='2' align='center'>
<tr>
	<td colspan='3' class='catheader' align='center' style='font-size:14px;'>
		<strong>Home</strong>
	</td>
</tr>
<tr>
	<td colspan='3' style='border:1px solid #ccc; padding:10px; text-align:center;'>
		<img src='http://scssboard.if-hosting.com/ver_check/$_MAIN[script_version_simp].gif' alt='Version Checker' /><br /><br />
		<a href='http://scssboard.if-hosting.com/forums/'>sCssBoard Website</a><br />
		<a href='http://scssboard.if-hosting.com/wiki/index.php'>sCssBoard Support Forums</a><br />
		<a href='http://scssboard.if-hosting.com/wiki/index.php/Documentation'>sCssBoard Documentation</a>
	</td>
</tr>
<tr>
	<td colspan='3' class='catheader' align='center' style='font-size:14px;'>
		<strong>Notes</strong>
	</td>
</tr>
<tr>
	<td colspan='3' style='border:1px solid #ccc; padding:10px;'>
	$update_msg
	<form action='?act=admin-home&amp;addnotes=yes' method='post'>
	<textarea name='notes' style='width:500px; height:200px;'>$admin_notes[setting_value]</textarea><br />
	<input type='submit' value='Save' class='form_button'>
	</form>
	</td>
</tr>

</table>";
?>