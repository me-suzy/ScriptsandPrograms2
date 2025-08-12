<?php
	header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
	header ("Cache-Control: no-cache, must-revalidate");
	header ("Pragma: no-cache");	
	
	/*////////////////////////////////////////////////////////////
	
	iWare Professional 4.0.0
	Copyright (C) 2002,2003 David N. Simmons 
	http://www.dsiware.com

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

	A COPY OF THE GPL LICENSE FOR THIS PROGRAM CAN BE FOUND WITHIN THE
	docs/ DIRECTORY OF THE INSTALLATION PACKAGE.

	/////////////////////////////////////////////////////////////*/

	
	@import_request_variables('cgps');
	$PHP_SELF=$_SERVER['PHP_SELF'];
	include "iware.php";
	$IW= new IWARE ();	
	$currentUser=$IW->Users_GetUserName($UID);
	$currentGroup=$IW->Users_GetUserGroup($UID);
	$permissions=$IW->Group_GetGroupAuth($currentGroup);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<link rel="stylesheet" href="iware.css"></link>
<script language="JavaScript">
	function modLaunch ()
		{
		mod=document.controlForm.control.options[document.controlForm.control.selectedIndex].value;
		parent.main.location=(mod);
		return false;
		}
</script>
</head>
<body background="images/widget_titlebar.jpg" topmargin=0>
<center>
<table border=0 cellpadding=3 cellspacing=0 width=600>
<tr>
<td width=200  background="images/widget_titlebar.jpg"><b>iWare Professional</b></td>
<form method=post name="controlForm">
<td width=200 background="images/widget_titlebar.jpg">
<select size=1 name=control class="guiListBox" onChange="return modLaunch ()">
<option value="main.php?"><?php echo CONTROL_1; ?></option>
<?php
	if($permissions['allow_users']==1)
	{echo "<option value=\"users.php\">".CONTROL_2."</option>\n";}
	if($permissions['allow_groups']==1)
	{echo "<option value=\"groups.php\">".CONTROL_3."</option>\n";}
	if($permissions['allow_header']==1)
	{echo "<option value=\"header.php\">".CONTROL_4."</option>\n";}
	if($permissions['allow_footer']==1)
	{echo "<option value=\"footer.php\">".CONTROL_5."</option>\n";}
	if($permissions['allow_skin']==1)
	{echo "<option value=\"skin.php\">".CONTROL_6."</option>\n";}
	if($permissions['allow_nav']==1)
	{echo "<option value=\"navbar.php\">".CONTROL_7."</option>\n";}
	if($permissions['allow_order']==1)
	{echo "<option value=\"order.php\">".CONTROL_8."</option>\n";}
	if($permissions['allow_docs']==1)
	{echo "<option value=\"docs.php\">".CONTROL_9."</option>\n";}
	if($permissions['allow_files']==1)
	{echo "<option value=\"files.php\">".CONTROL_10."</option>\n";}
	if($permissions['allow_mods']==1)
	{echo "<option value=\"mods.php\">".CONTROL_11."</option>\n";}
?>
<option value="http://www.dsiware.com">dsiware.com</option>
</select>
<input type=button value=" > " class="guiButton"  onClick="return modLaunch ()">
</td>
</form>
<form method=post target="main" action="../index.php?">
<td width=150 background="images/widget_titlebar.jpg"><input type=submit value="<?php echo CONTROL_12; ?>" class="guiButton"></td>
</form>
<form method=post target=_top action="index.php?logout=1">
<td width=150 background="images/widget_titlebar.jpg"><input type=submit value="<?php echo CONTROL_13; ?> <?php echo $currentUser; ?>" class="guiButton"></td>
</form>
</tr>
</table>
</center>
</p>
</body>
</html>