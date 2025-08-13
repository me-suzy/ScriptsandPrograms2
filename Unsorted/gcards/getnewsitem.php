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
include_once('inc/adodb/adodb.inc.php');	   # load code common to ADOdb
include_once('config.php');
include_once('inc/UIfunctions.php');
include_once('inc/newsclass.php');

$page = new pagebuilder;
include_once('inc/setLang.php');
$page->showHeader();

$newsid = $_GET['newsid'];

if ($newsid)
{
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
$conn = &ADONewConnection('mysql');	# create a connection
$conn->Connect($dbhost,$dbuser,$dbpass,$dbdatabase);

$getNewsSQL = "SELECT * from ".$tablePrefix."news WHERE newsid=$newsid";
$newsRecordSet = &$conn->Execute($getNewsSQL);

$news = new news;
echo '<div align="center"><table width="60%">';
$news->getNews($newsRecordSet);
?>
	<tr>
		<td><a href="javascript:history.go(-1);"><? echo $nav01;?></a></td>
	</tr>
</table>
</div>
<?

$conn->Close(); 
}
$page->showFooter();
?>