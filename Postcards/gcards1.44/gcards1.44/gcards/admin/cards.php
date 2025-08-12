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
checkUser();

$page->showHeader($nav05);

include_once('../inc/adodb/adodb.inc.php');	   # load code common to ADOdb

$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
$conn = &ADONewConnection('mysql');	# create a connection
$conn->Connect($dbhost,$dbuser,$dbpass,$dbdatabase);

if (isset($_POST['action']))	$action = $_POST['action'];
if (isset($_POST['imageid']))	$imageid = (int)$_POST['imageid'];
if (isset($_GET['imageid']))	$imageid = (int)$_GET['imageid'];
if (isset($_POST['catid']))		$catid = (int)$_POST['catid'];
if (isset($_GET['catid']))		$catid = (int)$_GET['catid'];
if (isset($_GET['limit']))		$limit = (int)$_GET['limit'];
if (isset($_POST['limit']))		$limit = (int)$_POST['limit'];
if (isset($_GET['row']))		$row = (int)$_GET['row'];
if (isset($_GET['catSearch']))	$catSearch = (int)$_GET['catSearch']; else $catSearch = false;
if (isset($_POST['cardname']))	$cardname = checkAddSlashes($_POST['cardname']);
if (!isset($row)) $row = 0;
if (!isset($limit)) $limit = 20;

if (isset($_GET['action']) && $_GET['action'] == 'delete')
{
	$cardInfoSQL = "SELECT imagepath, thumbpath FROM ".$tablePrefix."cardinfo WHERE imageid=$imageid";
	$deleteInfo = $conn->GetRow($cardInfoSQL);
	$deleteimagepath = "../images/".$deleteInfo['imagepath'];
	$deletethumbpath = "../images/".$deleteInfo['thumbpath'];
	$deletesql = "DELETE from ".$tablePrefix."cardinfo WHERE imageid=$imageid";
	if ($conn->Execute($deletesql))
		{
			echo "$cards04 $admin09<br>";
			if (unlink($deleteimagepath))
				echo "$cards01 $admin10<br>";
			else
				echo "<span class='error'>$cards01 $admin11 $admin10</span><br>";
			if (unlink($deletethumbpath))
				echo "$cards02 $admin10<br>";
			else
				echo "<span class='error'>$cards02 $admin11 $admin10</span><br>";
				
		}
	else
		echo "<span class='error'>$cards04 $admin11 $admin09</span><br>"; 
}

if (isset($_POST['action']) && $_POST['action'] == 'edit')
{
	$editsql = "UPDATE ".$tablePrefix."cardinfo set cardname='$cardname', catid=$catid WHERE imageid=$imageid";
	if ($conn->Execute($editsql))
		echo "$cards04 $admin12<br>";
	else
		echo "<span class='error'>$cards04 $admin11 $admin12</span><br>";
}

$sqlstmt = 'select category, catid from '.$tablePrefix.'categories'; // for getMenu2 function below
$recordSet = &$conn->Execute("$sqlstmt" );
?>

<table width="100%">
	<tr>
		<td align="left" valign="top" width="200">
			<? include('../inc/getcategories.php');  // show the eCard Categories ?>
		</td>
		<td>
		
<table>
	<tr>
		<td colspan="2" class="subtitle"><? echo "$admin15 $cards04";?></th>
	</tr>
	<form enctype="multipart/form-data" action="upload.php" method="post">
	<input type="hidden" name="MAX_FILE_SIZE" value="<? echo ($maxFileSize * 1000);?>">
	<tr>
		<td><? echo $cards05;?>:</td>
		<td><input type="text" name="cardname"></td>
	</tr>
	<tr>
		<td><? echo $cat01;?>:</td>
		<td><? print $recordSet->GetMenu2('catid', "$catSearch"); ?></td>
	</tr>
	<tr>
		<td><? echo "$admin16 $cards01";?>:</td>
		<td><input type="file" name="userfile"></td>
	</tr>
	<tr>
		<td><? echo "$admin16 $cards02";?>:<br><span class="smalltext"><? echo $cards06;?></span></td>
		<td><input type="file" name="userthumb"></td>
	</tr>
	<tr>
		<td colspan="2" align="right"><input type="submit" value="<? echo $admin16;?>"><br><br></td>
	</tr>
	</form>
</table>

<?
$page->drawLine();
$rowsInTableSQL = "SELECT COUNT(*) from ".$tablePrefix."cardinfo";
if ($catSearch) $rowsInTableSQL .= " where catid = '$catSearch'";
if (!$catSearch) $cardsql = "SELECT ".$tablePrefix."cardinfo.imageid, ".$tablePrefix."cardinfo.cardname, ".$tablePrefix."categories.category, ".$tablePrefix."cardinfo.imagepath, ".$tablePrefix."cardinfo.thumbpath, ".$tablePrefix."cardinfo.senttimes from ".$tablePrefix."cardinfo LEFT JOIN ".$tablePrefix."categories ON ".$tablePrefix."cardinfo.catid=".$tablePrefix."categories.catid order by ".$tablePrefix."cardinfo.imageid";
else $cardsql = "SELECT ".$tablePrefix."cardinfo.imageid, ".$tablePrefix."cardinfo.cardname, ".$tablePrefix."categories.category, ".$tablePrefix."cardinfo.imagepath, ".$tablePrefix."cardinfo.thumbpath, ".$tablePrefix."cardinfo.senttimes from ".$tablePrefix."cardinfo, ".$tablePrefix."categories WHERE ".$tablePrefix."cardinfo.catid=".$tablePrefix."categories.catid AND ".$tablePrefix."cardinfo.catid=$catSearch order by ".$tablePrefix."cardinfo.imageid";

$pager = new pager($conn, $row, $limit, $rowsInTableSQL);
$cardRecordSet = $pager->getrecords($conn, $cardsql);	

if (!$cardRecordSet)
		echo "No eCards in Database";
else
{
	?>
<br>
<table cellspacing="2" cellpadding="2">
	<tr>
		<th><? echo $admin08;?></th><th><? echo $cards05;?></th><th><? echo $cat01;?></th><th><? echo $cards01;?></th><th><? echo $cards02;?></th><th><? echo $cards03;?></th><th colspan="2"><? echo $action01;?></th>
	</tr>
	<?
while (!$cardRecordSet->EOF) 
			{
				$imageid = $cardRecordSet->fields['imageid'];
				$cardname = $cardRecordSet->fields['cardname'];
				$category = $cardRecordSet->fields['category'];
				$imagepath = $cardRecordSet->fields['imagepath'];
				$thumbpath = $cardRecordSet->fields['thumbpath'];
				$senttimes = $cardRecordSet->fields['senttimes'];
				echo "\n\t<tr>\n\t\t<td>$imageid</td><td>$cardname</td><td>$category</td><td>$imagepath</td><td>$thumbpath</td><td>$senttimes</td><td><a href=\"editCard.php?imageid=$imageid\">$action03</a></td><td><a href=\"".$_SERVER['PHP_SELF']."?imageid=$imageid&action=delete&catSearch=$catSearch&limit=$limit&row=$row\">$action04</a></td>\n\t</tr>";
				$cardRecordSet->MoveNext();
			}




?>

</table>
<table>
<tr><td>&nbsp;</td></tr><tr><td>
<?	$pager->showpagernav($nav01, $nav02, "&catSearch=$catSearch&limit=$limit"); ?>
</td></tr>
	<tr>
		<form action="<? echo $_SERVER['PHP_SELF']; ?>" method="POST">
		<input type="hidden" name="catSearch" value="<? echo $catSearch;?>">
		<td>
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
</td>
	</tr>
</table>
<?
}
$page->showFooter();
?>
