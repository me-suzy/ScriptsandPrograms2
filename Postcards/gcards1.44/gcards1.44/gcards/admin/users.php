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
checkUser('admin');
$page->showHeader($nav05);

include_once('../inc/adodb/adodb.inc.php');	   # load code common to ADOdb

$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
$conn = &ADONewConnection('mysql');	# create a connection
$conn->Connect($dbhost,$dbuser,$dbpass,$dbdatabase);

if (isset($_POST['action']) && $_POST['action'] == 'add')
{
	$userpass = $_POST['userpass'];
	$userpassconfirm = $_POST['userpassconfirm'];
	$username = $_POST['username'];
	$email = $_POST['email'];
	$role = $_POST['role'];
	if (!($userpass == $userpassconfirm))
		echo "<span class='error'>$user09</span>";
	else
	{
		$addsql = "INSERT INTO ".$tablePrefix."cardusers (username, userpass, email, role) VALUES ('$username',password('$userpass'),'$email','$role')";
		if ($conn->Execute($addsql))
			echo "$user08 $admin14";
		else
			echo "<span class='error'>$user08 $admin11 $admin14</span>";
	}
}

if (isset($_GET['action']) && $_GET['action'] == 'delete')
{
	$userid = (int)$_GET['userid'];
	$deletesql = "DELETE from ".$tablePrefix."cardusers WHERE userid=$userid";
	if ($conn->Execute($deletesql))
		echo "$user08 $admin09";
	else
		echo "<span class='error'>$user08 $admin11 $admin09</span>";
}

if (isset($_POST['action']) && $_POST['action'] == 'edit')
{
	$userpass = $_POST['userpass'];
	$userpassconfirm = $_POST['userpassconfirm'];
	$username = $_POST['username'];
	$email = $_POST['email'];
	$role = $_POST['role'];
	$userid = $_POST['userid'];
	if ($userpass) $passUpdate = "userpass = password('$userpass'),"; else $passUpdate = '';
	if ($userpass != $userpassconfirm)
		echo "<span class='error'>$user09</span>";
	else
	{
		$editsql = "UPDATE ".$tablePrefix."cardusers SET username='$username', $passUpdate email='$email', role='$role' WHERE userid=$userid";
		if ($conn->Execute($editsql))
			echo "$user08 $admin12";
		else
			echo "<span class='error'>$user08 $admin11 $admin12</span>";
	}
}
?>

<table cellspacing="2" cellpadding="2">
	<form action="<? echo $_SERVER['PHP_SELF'];?>" method="POST">
	<input type="hidden" name="action" value="add">
	<tr>
		<td colspan="2" class="bold"><? echo "$action02 $user08";?></td>
	</tr>
	<tr>
		<td><? echo $user01;?>:</td>
		<td><input type="text" name="username" size="20"></td>
	</tr>
	<tr>
		<td><? echo $user02;?>:</td>
		<td><input type="Password" name="userpass" size="20"></td>
	</tr>
	<tr>
		<td><? echo $user05;?>:</td>
		<td><input type="Password" name="userpassconfirm" size="20"></td>
	</tr>
	<tr>
		<td><? echo $user06;?>:</td>
		<td><input type="text" name="email" size="30"></td>
	</tr>
	<tr>
		<td><? echo $user07;?>:</td>
		<td>
			<select name="role">
				<option value="standard" selected>standard</option>
				<option value="admin">admin</option>
			</select>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="right"><input type="submit" value="<? echo $action02;?>"></td>
	</tr>
	</form>
</table>


<?
$sqlstmt = 'select userid, username, email, role from '.$tablePrefix.'cardusers';
$recordSet = &$conn->Execute("$sqlstmt" );
if (!$recordSet)
	echo "No Users in Database";
else
{
	?>
<br><br>
<table cellspacing="2" cellpadding="2">
	<tr>
		<th><? echo "$user08 $admin08";?></th><th><? echo $user01;?></th><th><? echo $user06;?></th><th><? echo $user07;?></th><th colspan="2"><? echo $action01;?></th>
	</tr>
	<?
			while (!$recordSet->EOF) 
				{
					$userid = $recordSet->fields['userid'];
					$username = $recordSet->fields['username'];
					$email = $recordSet->fields['email'];
					$role = $recordSet->fields['role'];					
					
					echo "\n\t<tr>\n\t\t<td>$userid</td><td>$username</td><td>$email</td><td>$role</td><td><a href=\"editUser.php?userid=$userid\">$action03</a></td><td><a href=\"".$_SERVER['PHP_SELF']."?action=delete&userid=$userid\">$action04</a></td>\n\t</tr>";
					$recordSet->MoveNext();
				}
	?>

</table>
	<?
	$recordSet->Close();
}

$page->showFooter();
?>
