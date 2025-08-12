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

$page = new pagebuilder('../', 'admin.php');
include_once('../inc/setLang.php');
checkUser();
$page->showHeader($nav05);

include_once('../inc/adodb/adodb.inc.php');	   # load code common to ADOdb

$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
$conn = &ADONewConnection('mysql');	# create a connection
$conn->Connect($dbhost,$dbuser,$dbpass,$dbdatabase);

if (isset($_POST['action']) && $_POST['action'] == 'add')
{
	$category = checkAddSlashes($_POST['category']);
	$action = $_POST['action'];
	$addsql = "INSERT INTO ".$tablePrefix."categories (category) VALUES ('$category')";
	$category = checkStripSlashes($category);
	if ($conn->Execute($addsql))
		echo "\"$category\" $admin14<br>";
	else
		echo "<span class='error'>\"$category\" $admin11 $admin14</span><br>";
}

if (isset($_GET['action']) && $_GET['action'] == 'delete')
{
	$catid = (int)$_GET['catid'];
	$category = $_GET['category'];
	$deletesql = "DELETE from ".$tablePrefix."categories WHERE catid=$catid";
	$category = checkStripSlashes($category);
	if ($conn->Execute($deletesql))
		echo "\"$category\" $admin09<br>";
	else
		echo "<span class='error'>\"$category\" $admin11 $admin09</span><br>";
}

if (isset($_POST['action']) && $_POST['action'] == 'edit')
{
	$catid = (int)$_POST['catid'];
	$category = checkAddSlashes($_POST['category']);
	$editsql = "UPDATE ".$tablePrefix."categories SET category='$category' WHERE catid=$catid";
	if ($conn->Execute($editsql))
		echo "$cat01 $admin12<br>";
	else
		echo "<span class='error'>$cat01 $admin11 $admin12</span><br>";
}
?>
<table cellspacing="2" cellpadding="2">
	<form action="categories.php" method="POST">
	<input type="hidden" name="action" value="add">
	<tr>
		<td><? echo "$action02 $cat01";?>:</td>
		<td><input type="text" name="category" size="20"></td>
		<td><input type="submit" value="Add"></td>
	</tr>
	</form>
</table>


<?
$sqlstmt = 'select category, catid from '.$tablePrefix.'categories';
$recordSet = &$conn->Execute("$sqlstmt" );
if (!$recordSet)
	echo "No Categories in Database";
else
{
	?>
<br>
<table cellspacing="2" cellpadding="2">
	<tr>
		<th><? echo "$cat01 $admin08";?></th><th><? echo $cat02;?></th><th><? echo $cat03;?></th><th colspan="2"><? echo $action01;?></th>
	</tr>
	<?
			while (!$recordSet->EOF) 
				{
					$catid = $recordSet->fields['catid'];
					$category = $recordSet->fields['category'];
					$numCardsInCatSQL = "SELECT COUNT(*) from ".$tablePrefix."cardinfo where catid=$catid";
					$cardsInRow = $conn->GetOne($numCardsInCatSQL);
					echo "\n\t<tr>\n\t\t<td>$catid</td><td>$category</td><td>$cardsInRow</td><td><a href=\"editCategory.php?catid=$catid&category=$category\">$action03</a></td><td><a href=\"".$_SERVER['PHP_SELF']."?action=delete&catid=$catid&category=$category\">$action04</a></td>\n\t</tr>";
					$recordSet->MoveNext();
				}
	?>

</table>
	<?
	$recordSet->Close();
}

$page->showFooter();
?>
