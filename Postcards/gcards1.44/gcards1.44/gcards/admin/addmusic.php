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
include_once('loginfunction.php');
include_once('../inc/UIfunctions.php');
include_once('../config.php');
$page = new pagebuilder('../', 'admin.php');
include_once('../inc/setLang.php');
checkUser();
$page->showHeader($nav05);

if (!isset($_POST['action']))
{
	?>
	<table cellspacing="2" cellpadding="2">
		<form enctype="multipart/form-data" action="addmusic.php" method="post">
		<input type="hidden" name="action" value="addMusic">
		<tr>
			<td class="subtitle" colspan="2"><? echo $music02;?></td>
		</tr>
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr>
			<td><? echo $music04;?>:</td>
			<td><input type="text" name="mname" maxlength="40"></td>
		</tr>
		<tr>
			<td><? echo $music06;?></td>
			<td><input type="file" name="userfile"></td>
		</tr>
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr>
			<td colspan="2" align="right"><input type="submit" value="<? echo $admin16;?>"></td>
		</tr>
		</form>
	</table>
	<?
	$page->showFooter();
	exit;
}
elseif ($_POST['action'] == 'addMusic')
{
	if (empty($_POST['mname']) || !is_uploaded_file($_FILES['userfile']['tmp_name']))
	{
		echo $sendcard01;
	}
	$mpath = time().str_replace(" ", "_", $_FILES['userfile']['name']);
	$musicpath = '../sound/'.$mpath;
	$mname = $_POST['mname'];
	
	if (!move_uploaded_file($_FILES['userfile']['tmp_name'], $musicpath)) 
	{
		echo "<span class='error'>$upload02 $nav11</span>";
		$page->showFooter();
		exit;
	}
	
	include_once('../inc/adodb/adodb.inc.php');
	$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
	$conn = &ADONewConnection('mysql');
	$conn->Connect($dbhost,$dbuser,$dbpass,$dbdatabase);
	
	$insertSQL = "INSERT INTO ".$tablePrefix."music (mname, mpath) VALUES ('$mname', '$mpath')";
	if ($conn->Execute($insertSQL))
	{
	?>
		<span class="subtitle"><? echo $music02;?></span>
		<br><br>
		<? echo $music07;?>
		<br><br>
		<a href="music.php">[ <? echo $admin19;?> ]</a>
		<br><br>
	<?
	}
	$page->showFooter();
	exit;
}	
?>
