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
include_once('inc/adodb/adodb.inc.php');	   # load code common to ADOdb
include_once('config.php');
include_once('inc/UIfunctions.php');
include_once('inc/pager.php');

$page = new pagebuilder;
include_once('inc/setLang.php');
$page->showHeader();

$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
$conn = &ADONewConnection('mysql');	# create a connection
$conn->Connect($dbhost,$dbuser,$dbpass,$dbdatabase);

if (isset($_GET['row'])) $row = $_GET['row']; else $row = 0;
if (isset($_GET['limit'])) $limit = $_GET['limit']; else $limit = 5;

$newsInTableSQL = "SELECT COUNT(*) from ".$tablePrefix."news";
$getNewsSQL = "SELECT * from ".$tablePrefix."news ORDER BY newsid DESC";

$pager = new pager($conn, $row, $limit, $newsInTableSQL);
$recordSet = $pager->getrecords($conn, $getNewsSQL);

$newsRecordSet = &$conn->SelectLimit($getNewsSQL,$limit,$row );
if (!$newsRecordSet) print $conn->ErrorMsg();
else
{
	?>
		<div align="center">
		<table width="60%">
			<tr>
				<td class="bold"><? echo $news01;?></td>
			</tr>
			<tr>
				<td><? $page->drawLine();?><br></td>
			</tr>
	<?
	include_once('inc/newsclass.php');
	$news = new news;
	$news->getNews($newsRecordSet, 1);


	echo '<tr><td>';
	$pager->showpagernav($nav01, $nav02, "&limit=$limit");
	echo '<br></td></tr>';

	?>
		<tr>
			<form action="<? echo $_SERVER['PHP_SELF']; ?>" method="GET">
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
				<? echo $news04;?>
			</td>
			</form>
		</tr>
		</table>
		</div>
	<?
}
$conn->Close(); # optional
$page->showFooter();
?>