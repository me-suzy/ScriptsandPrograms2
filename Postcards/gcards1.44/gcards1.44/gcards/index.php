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

include_once('inc/adodb/adodb.inc.php');
include_once('config.php');
include_once('inc/UIfunctions.php');
include_once('inc/pager.php');

$row = (isset($_GET['row'])) ? (int)$_GET['row'] : 0;
$catSearch = (isset($_GET['catSearch'])) ? (int)$_GET['catSearch'] : false;
deleteFromSession('to_email, cardtext, music');
if (isset($_GET['reply'])) $_SESSION['reply'] = $_GET['reply'];

$page = new pagebuilder;
include_once('inc/setLang.php');
$page->langargs = "&row=$row&catSearch=$catSearch";
$page->showHeader();


$limit = $rowsPerPage * $cardsPerRow;

$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
$conn = &ADONewConnection('mysql');
if (!$conn->Connect($dbhost,$dbuser,$dbpass,$dbdatabase))
{
	echo "Error: Could not connect to database";
	$page->showFooter();
	exit;
}

if ($deleteDays != 0)
{
	$deletePriorTime = time() - ($deleteDays * 24 * 60 * 60);
	$deleteCardsSQL = 'DELETE FROM '.$tablePrefix.'sentcards WHERE cardid<'.$deletePriorTime;
	$conn->Execute($deleteCardsSQL);
}

if ($stats_unique_index_hits_enabled == true)
{
	if (!isset($_SESSION['stat_userhits']))
	{
		$sql = "UPDATE ".$tablePrefix."statistics set statval=(statval +1) WHERE stat='userhits'";
		$conn->Execute($sql);
		unset($sql);
		$_SESSION['stat_userhits'] = true;
	}
}

$rowsInTableSQL = "SELECT COUNT(*) from ".$tablePrefix."cardinfo";
if ($catSearch) $rowsInTableSQL .= " where catid = '$catSearch'";
if ($orderPop == 'yes') $orderArg = "senttimes DESC,"; else $orderArg = " ";
$sqlstmt = 'select * from '.$tablePrefix.'cardinfo';
$sqlstmt .= ($catSearch) ? " where catid = '$catSearch' order by $orderArg imageid $order" : " order by $orderArg imageid $order";

$pager = new pager($conn, $row, $limit, $rowsInTableSQL);
$recordSet = $pager->getrecords($conn, $sqlstmt);	
if (!$recordSet) print $conn->ErrorMsg();
?>

<table width="100%">
	<tr>
		<td valign="top" width="200">
			<? include('inc/getcategories.php');  // show the eCard Categories ?>
		</td>
		<td valign="top">
			<table cellpadding="5">
				<tr>
					<td class="subtitle">
						<? 
						if (isset($selectedCategory)) echo $selectedCategory;
						else echo $index01;
						?>
					</td>
				</tr>
				<tr>
					<td>
						<? echo $index02;?><br><br>
					</td>
				</tr>
			</table>
<?
if ($recordSet)
	{
		$numCards = $recordSet->RecordCount();
		$cardCount = 0;
		echo "<table cellpadding=\"5\" cellspacing=\"5\">\n\t<tr>";
		while (!$recordSet->EOF) 
			{
				$imageid = $recordSet->fields['imageid'];
				$cardname = $recordSet->fields['cardname'];
				$thumbpath = rawurlencode($recordSet->fields['thumbpath']);
				if ((($cardCount % $cardsPerRow) == 0) && (!($cardCount == 0))) echo "\n\t</tr>\n\t<tr>";
				if ($dropShadow == 'yes')
					{
						?>
						<td align="center">
							<table cellspacing="0" cellpadding="0" border="0">
								<tr>
									<td><a href="compose.php?imageid=<? echo $imageid;?>"><img src="images/<? echo $thumbpath;?>" border="0"></a></td>
									<td valign="top" background="images/siteImages/dropshadow/ds_right.gif"><img src="images/siteImages/dropshadow/ds_topright.gif" alt="" width="7" height="10" border="0"></td>
								</tr>
								<tr>
									<td background="images/siteImages/dropshadow/ds_bottom.gif"><img src="images/siteImages/dropshadow/ds_bottomleft.gif" alt="" width="7" height="7" border="0"></td>
									<td><img src="images/siteImages/dropshadow/ds_corner.gif" alt="" width="7" height="7" border="0"></td>
								</tr>
							</table>
							<? echo $cardname?>
						</td>
						<?
					}
				else
					{
						?>	
						<td align="center" bgcolor="white">
							<a href="compose.php?imageid=<? echo $imageid;?>"><img src="images/<? echo $thumbpath;?>" border="0"></a><br><? echo $cardname?>
						</td>
						<?
					}
				$recordSet->MoveNext();
				$cardCount++;
			}
		$emptyCells = ($cardsPerRow - ($numCards % $cardsPerRow));
		for ($i=0; $i < $emptyCells; $i++) echo "\n\t\t<td>&nbsp;</td>";
		echo "\n\t</tr>\n</table>";
	}
$recordSet->Close();
?>
<table><tr><td>&nbsp;</td><td><br></td></tr>
<tr><td>&nbsp;</td><td>
<?
$pager->showpagernav($nav01, $nav02, "&catSearch=$catSearch");
?>
</td></tr>
</table>
<?
if (($enableNews == 'yes') && ($newsLocation == 'bottom'))
{
	echo '<br><br><table width="100%"><tr><td>';
	include('inc/newssummary.php');
	echo "</td></tr></table>";
}

echo '</td>';

if (($enableNews == 'yes') && ($newsLocation == 'right'))
{
	echo '<td valign="top" width="200">';
	include('inc/newssummary.php');
	echo '</td>';
}
		
echo '</tr></table>';

$conn->Close();
$page->showFooter();
?>                    