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

include_once('../inc/adodb/adodb.inc.php');	   # load code common to ADOdb
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
$conn = &ADONewConnection('mysql');	# create a connection
$conn->Connect($dbhost,$dbuser,$dbpass,$dbdatabase);

$userid = (int)$_GET['userid'];

$selectUserSQL = "SELECT * from ".$tablePrefix."cardusers where userid=$userid";
$userinfo = &$conn->Execute($selectUserSQL);
$username = $userinfo->fields['username'];
$email = $userinfo->fields['email'];
$role = $userinfo->fields['role'];

$page->showHeader($nav05);

if (!$userid)
	echo " ";
else
{
?>
<table cellspacing="2" cellpadding="2">
	<form action="users.php" method="POST">
	<input type="hidden" name="action" value="edit">
	<input type="hidden" name="userid" value="<? echo $userid;?>">
	<tr>
		<td colspan="2" class="bold"><? echo "$action03 $user08";?></td>
	</tr>
	<tr>
		<td><? echo $user01;?>:</td>
		<td><input type="text" name="username" size="20" value="<? echo $username;?>"></td>
	</tr>
	<tr>
		<td><? echo $user02;?>:</td>
		<td><input type="Password" name="userpass" size="20"> <? echo $user10;?></td>
	</tr>
	<tr>
		<td><? echo $user05;?>:</td>
		<td><input type="Password" name="userpassconfirm" size="20"></td>
	</tr>
	<tr>
		<td><? echo $user06;?>:</td>
		<td><input type="text" name="email" size="30" value="<? echo $email;?>"></td>
	</tr>
	<tr>
		<td><? echo $user07;?>:</td>
		<td>
			<select name="role">
				<option value="standard" <? if ($role == 'standard') echo 'selected';?>>standard</option>
				<option value="admin" <? if ($role == 'admin') echo 'selected';?>>admin</option>
			</select>
		</td>
	</tr>
	<tr>
		<td><input type="submit" value="<? echo $action06;?>"></td></form>
		<form action="users.php" method="POST">
		<td><input type="submit" value="<? echo $action07;?>"></td>
		</form>
	</tr>
	
</table>

<?
}


$page->showFooter();
?>