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
function loginuser()
{
	global $nav11;
	global $auth01;
	global $auth02;
	global $nav05;
	global $nav03;
	global $nav09;
	$username = $_POST['username'];
	$userpass = $_POST['userpass'];
	if ($username && $userpass)
	{
		include('../inc/adodb/adodb.inc.php');	   # load code common to ADOdb
		include('../config.php');
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$conn = &ADONewConnection('mysql');	# create a connection
		$conn->Connect($dbhost,$dbuser,$dbpass,$dbdatabase);
		$sqlstmt = "SELECT role FROM ".$tablePrefix."cardusers WHERE username='$username' AND userpass=password('$userpass')";
		$recordSet = &$conn->Execute("$sqlstmt" );
		$numResults = $recordSet->RecordCount();
		$userRole = $recordSet->fields['role'];
		if ($numResults > 0)
			{
				$_SESSION['auth_user'] = $username;
				$_SESSION['auth_role'] = $userRole;
			}
		else
			{
				$page = new pagebuilder('../');
				$page->showHeader($nav05);
				echo "<span class='error'>$auth01: $username. $nav11</span>";
				$page->showFooter();
				exit;
			}
	}
	else 
	{
		$page = new pagebuilder('../');
		$page->showHeader($nav05);
		echo "<span class='error'>$auth02 $nav11</span>";
		$page->showFooter();
		exit;
	}
}

function checkUser($check='')
{
	global $nav11;
	global $auth03;
	global $auth04;
	global $nav05;
	global $nav03;
	global $nav09;
	if (!isset($_SESSION['auth_user']))
	{
		$page = new pagebuilder('../');
		$page->showHeader($nav05);
		echo "<span class='error'>$auth03<br>";
		echo "$nav11</span><br>";
		$page->showFooter();
		exit;
	}
	if ($check)
	{
		if ($_SESSION['auth_role'] != $check)
		{
			$page = new pagebuilder('../');
			$page->showHeader($nav05);
			echo "<span class='error'>$auth04<br></span>";
			$page->showFooter();
			exit;	
		}
	}
}
?>
