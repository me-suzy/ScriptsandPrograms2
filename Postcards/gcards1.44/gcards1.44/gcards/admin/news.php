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
include_once('../inc/formFunctions.php');
include_once('../inc/pager.php');

$page = new pagebuilder('../', 'admin.php');
include_once('../inc/setLang.php');
checkUser('admin');

$page->showHeader($nav05);

include_once('../inc/adodb/adodb.inc.php');	   # load code common to ADOdb
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
$conn = &ADONewConnection('mysql');	# create a connection
$conn->Connect($dbhost,$dbuser,$dbpass,$dbdatabase);

$time = time();
if (isset($_GET['limit'])) $limit = $_GET['limit']; else $limit = 10;
if (isset($_GET['row'])) $row = $_GET['row']; else $row = 0;

if (isset($_POST['action']) && $_POST['action'] == 'add')
{
	$newssubject = checkAddSlashes($_POST['newssubject']);
	$body = checkAddSlashes($_POST['body']);
	$author = checkAddSlashes($_SESSION['auth_user']);
	$addsql = "INSERT INTO ".$tablePrefix."news (username, subject, body, postdate) VALUES ('$author', '$newssubject', '$body', $time)";
	if ($conn->Execute($addsql))
		echo "<br>$news05 $admin14";
	else
		echo "<br><span class='error'>$news05 $admin11 $admin14</span>";
}

if (isset($_POST['action']) && $_POST['action'] == 'edit')
{
	$newsid = $_POST['newsid'];
	$newssubject = checkAddSlashes($_POST['newssubject']);
	$body = checkAddSlashes($_POST['body']);
	$editsql = "UPDATE ".$tablePrefix."news set subject='$newssubject', body='$body' where newsid=$newsid";
	if ($conn->Execute($editsql))
		echo "<br>$news05 $admin12";
	else
		echo "<br><span class='error'>$news05 $admin11 $admin12</span>";
}

if (isset($_GET['action']) && $_GET['action'] == 'delete')
{
	$newsid = $_GET['newsid'];
	$deletesql = "DELETE from ".$tablePrefix."news WHERE newsid=$newsid";
	if ($conn->Execute($deletesql))
		echo "$news05 $admin10<br>";
	else
		echo "<span class='error'>$news05 $admin11 $admin10</span><br>";
}

$summaryLength = 150;

$newsInTableSQL = "SELECT COUNT(*) from ".$tablePrefix."news";
$getNewsSQL = "SELECT * from ".$tablePrefix."news ORDER BY newsid DESC";

$pager = new pager($conn, $row, $limit, $newsInTableSQL);
$newsRecordSet = $pager->getrecords($conn, $getNewsSQL);	

if (!$newsRecordSet) print $conn->ErrorMsg();
else
{
	?>
		
		<table cellspacing="2" cellpadding="2">
			<tr>
				<td><? $page->showLink('addnews.php?action=new',"$action02 $news05");?></td>
			</tr>
		</table>
		<br>
		<table cellspacing="2" cellpadding="2">
			<tr>
				<th><? echo "$news05 $admin08";?></th><th><? echo $news06;?></th><th><? echo $news07;?></th><th><? echo $news08;?></th><th><? echo $news09;?></th><th colspan="3"><? echo $action01;?></th>
			</tr>
	<?
	while (!$newsRecordSet->EOF)
	{
		$newsid = $newsRecordSet->fields['newsid'];
		$username = $newsRecordSet->fields['username'];
		$newssubject = $newsRecordSet->fields['subject'];
		$body = strip_tags(substr($newsRecordSet->fields['body'],0,50)."...");
		$postdate = date("F j, Y g:i a", $newsRecordSet->fields['postdate']);
		?>
		<tr>
			<td><? echo $newsid;?></td>
			<td><? echo $newssubject;?></td>
			<td><? echo $body;?></td>
			<td><? echo $postdate;?></td>
			<td><? echo $username;?></td>
			<td><? $page->showLink("../getnewsitem.php?newsid=$newsid",$action05,'_blank');?></td>
			<td><? $page->showLink("addnews.php?newsid=$newsid&action=old",$action03);?></td>
			<td><? $page->showLink($_SERVER['PHP_SELF']."?newsid=$newsid&limit=$limit&row=$row&action=delete",$action04);?></td>
		</tr>
		<?
		$newsRecordSet->MoveNext();
	}
		echo "<tr><td colspan='8'>";
		$pager->showpagernav($nav01, $nav02, "&limit=$limit");
		echo "</td></tr>";
		?>
		<tr>
			<form action="<? echo $_SERVER['PHP_SELF']; ?>" method="GET">
			<td colspan="8">
				<? echo $nav04;?>
				<select name="limit" id="limit" onChange="submit()">
					<option value="5" <? if ($limit == 5) echo 'selected'?>>5</option>
					<option value="10" <? if ($limit == 10) echo 'selected'?>>10</option>
					<option value="15" <? if ($limit == 15) echo 'selected'?>>15</option>
					<option value="20" <? if ($limit == 20) echo 'selected'?>>20</option>
					<option value="25" <? if ($limit == 25) echo 'selected'?>>25</option>
					<option value="50" <? if ($limit == 50) echo 'selected'?>>50</option>
				</select>
				<? echo $nav10;?>
			</td>
			</form>
		</tr>
		</table>
	<?
}
$conn->Close(); # optional
$page->showFooter();
?>
