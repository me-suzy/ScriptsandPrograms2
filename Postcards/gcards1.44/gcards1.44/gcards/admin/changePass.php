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

$auth_user = $_SESSION['auth_user'];
if (isset($_POST['oldPass'])) $oldPass = $_POST['oldPass']; else $oldPass = false;
if (isset($_POST['newPass1'])) $newPass1 = $_POST['newPass1']; else $newPass1 = false;
if (isset($_POST['newPass2'])) $newPass2 = $_POST['newPass2']; else $newPass2 = false;

$page->showHeader($nav05);

if ($oldPass && $newPass1 && $newPass2)
{
	if (!($newPass1 == $newPass2))
	{
		echo "<span class='error'>$user09</span> $nav11";
		$page->showFooter();
		exit;
	}
	include_once('../inc/adodb/adodb.inc.php');	   # load code common to ADOdb
	$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
	$conn = &ADONewConnection('mysql');	# create a connection
	$conn->Connect($dbhost,$dbuser,$dbpass,$dbdatabase);
	$checkOldPassSQL = "SELECT * FROM ".$tablePrefix."cardusers WHERE username='$auth_user' AND userpass=password('$oldPass')";
	$checkOldRecordSet = &$conn->Execute($checkOldPassSQL);
	$numResults = $checkOldRecordSet->RecordCount();
	if (!($numResults > 0))
	{
		echo "<span class='error'>$user11</span> $nav11";
		$page->showFooter();
		exit;
	}
	$updatePassSQL = "UPDATE ".$tablePrefix."cardusers SET userpass=password('$newPass1') where username='$auth_user'";
	if (!($conn->Execute($updatePassSQL)))
	{
		$page->showFooter();
		exit;
	}
	echo $user12;
	$page->showFooter();
	exit;
}
else
{
?>
<table>
	<form action="<? echo $_SERVER['PHP_SELF'];?>" method="POST">
	<tr>
		<td colspan="2" class="bold"><? echo "$action06 $user02";?></td>
	</tr>
	<tr>
		<td><? echo $user13;?>:</td>
		<td><input type="Password" name="oldPass"></td>
	</tr>
	<tr>
		<td><? echo $user14;?>:</td>
		<td><input type="Password" name="newPass1"></td>		
	</tr>
	<tr>
		<td><? echo $user05;?>:</td>
		<td><input type="Password" name="newPass2"></td>		
	</tr>
	<tr>
		<td colspan="2" align="right"><input type="submit" value="<? echo $action06;?>"></td>
	</tr>
	</form>	
</table>


<?
}


$page->showFooter();

?>
