<?
/*
 * gCards - a web-based eCard application
 * Copyright (C) 2003 Greg Neustaetter
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or (at
 * your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */
session_start();
if (isset($_SESSION['auth_user']))
{
	header("Location: admin/admin.php");
	header("HTTP/1.1 302 Redirection");
}

include_once('config.php');
include_once('inc/UIfunctions.php');


$page = new pagebuilder;
include_once('inc/setLang.php');
$page->bodyargs = 'OnLoad="document.loginform.username.focus();"';
$page->showHeader();
?>
<table>
<form name="loginform" action="admin/admin.php" method="POST">
	<tr>
		<td><? echo $user01;?>:</td>
		<td><input type="text" name="username"></td>
	</tr>
	<tr>
		<td><? echo $user02;?>:</td>
		<td><input type="password" name="userpass"></td>
	</tr>
	<tr>
		<td colspan="2" align="right"><input type="submit" value="<? echo $nav08;?>"></td>
	</tr>	
</form>
</table>

<?
$page->showFooter();
?>
