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

if (isset($_GET['imageid']))	$imageid = $_GET['imageid'];
$page = new pagebuilder('../', 'admin.php');
include_once('../inc/setLang.php');
checkUser();

$page->showHeader($nav05);

include_once('../inc/adodb/adodb.inc.php');	   # load code common to ADOdb

$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
$conn = &ADONewConnection('mysql');	# create a connection
$conn->Connect($dbhost,$dbuser,$dbpass,$dbdatabase);
$sqlstmt = 'select category, catid from '.$tablePrefix.'categories';
$recordSet = &$conn->Execute("$sqlstmt" );
$cardsql = "SELECT ".$tablePrefix."cardinfo.imageid, ".$tablePrefix."cardinfo.cardname, ".$tablePrefix."categories.category, ".$tablePrefix."cardinfo.imagepath, ".$tablePrefix."cardinfo.thumbpath from ".$tablePrefix."cardinfo, ".$tablePrefix."categories WHERE ".$tablePrefix."cardinfo.catid=".$tablePrefix."categories.catid AND ".$tablePrefix."cardinfo.imageid=$imageid";
$cardRecordSet = &$conn->Execute("$cardsql" );
while (!$cardRecordSet->EOF) 
			{
				$cardname = $cardRecordSet->fields['cardname'];
				$category = $cardRecordSet->fields['category'];
				$imagepath = $cardRecordSet->fields['imagepath'];
				$thumbpath = $cardRecordSet->fields['thumbpath'];
				$cardRecordSet->MoveNext();
			}
?>
<br><br>
<table>
	<tr>
		<td width="30">&nbsp;</td>
		<td>
			<table>
				<tr>
					<td colspan="2" class="bold"><? echo "$action03 $cards04";?></th>
				</tr>
				<tr>
					<td colspan="2"><img src="../images/<? echo rawurlencode($thumbpath);?>" border="0" alt="Image Thumbnail"></td>
				</tr>
				<form action="cards.php" method="post">
				<input type="hidden" name="action" value="edit">
				<tr>
					<td><? echo $admin08;?>:</td><td><? echo $imageid;?></td>
					<input type="hidden" name="imageid" value="<? echo $imageid;?>">
				</tr>
				<tr>
					<td><? echo $cards05;?>:</td>
					<td><input type="text" name="cardname" value="<? echo $cardname;?>"></td>
				</tr>
				<tr>
					<td><? echo $cat01;?>:</td>
					<td><? print $recordSet->GetMenu('catid', $category); ?></td>
				</tr>
				<tr>
					<td colspan="2" align="right"><input type="submit" value="<? echo $action06;?>"></td>
				</tr>
				</form>
			</table>
		</td>
	</tr>
</table>
<?
$page->showFooter();
?>