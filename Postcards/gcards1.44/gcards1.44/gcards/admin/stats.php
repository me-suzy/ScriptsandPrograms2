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
include_once('../config.php');
include_once('loginfunction.php');
include_once('../inc/UIfunctions.php');
$page = new pagebuilder('../', 'admin.php');
include_once('../inc/setLang.php');
checkUser();
$page->headervalues = '<script language="JavaScript" src="../css/imagepopup.js"></script>';
$page->showHeader($nav05);

include_once('../inc/adodb/adodb.inc.php');

$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
$conn = &ADONewConnection('mysql');
$conn->Connect($dbhost,$dbuser,$dbpass,$dbdatabase);

?>
<div align="center">
<!-- General Statistics -->
<table width="70%">
	<tr>
		<td colspan="2" class="subtitle"><? echo $stat01;?></th>
	</tr>
<!-- Total Cards Sent -->	
	<tr>
		<td width="170"><? echo $stat04;?></td>
		<td valign="top">
<?
	$sql = 'SELECT sum(senttimes) from '.$tablePrefix.'cardinfo';
	echo "\t\t\t".getStat($conn, $sql);
?>		
		</td>
	</tr>
<!-- Cards Sent this Week -->	
	<tr>
		<td width="170"><? echo $stat05;?></td>
		<td valign="top">
<?
	$sql = 'SELECT count(*) from '.$tablePrefix.'sentcards where cardid >'.(time() - (7*24*60*60));
	echo "\t\t\t".getStat($conn, $sql);
?>		
		</td>
	</tr>
<!-- Unique Hits - Index.php -->
	<tr>
		<td><? echo $stat06;?></td>
		<td valign="top">
<?
	echo "\t\t\t";
	if ($stats_unique_index_hits_enabled)
	{
		$sql = "SELECT statval from ".$tablePrefix."statistics where stat='userhits'";
		echo getStat($conn, $sql);
	}
	else echo $stat10;

?>			
		</td>
	</tr>
<!-- Hits - getcard.php -->
	<tr>
		<td><? echo $stat07;?></td>
		<td valign="top">
<?
	echo "\t\t\t";
	if ($stats_pickupcard_hits_enabled)
	{
		$sql = "SELECT statval from ".$tablePrefix."statistics where stat='pickuphits'";
		echo getStat($conn, $sql);
	}
	else echo $stat10;

?>			
		</td>
	</tr>	
<!-- Number of Cards -->	
	<tr>
		<td><? echo $stat08;?></td>
		<td valign="top">
<?
	$sql = 'SELECT count(*) from '.$tablePrefix.'cardinfo';
	echo "\t\t\t".getStat($conn, $sql);
?>		
		</td>
	</tr>
<!-- Number of Categories -->	
	<tr>
		<td><? echo $stat09;?></td>
		<td valign="top">
<?
	$sql = 'SELECT count(*) from '.$tablePrefix.'categories';
	echo "\t\t\t".getStat($conn, $sql);
?>		
		</td>
	</tr>	

	
</table>

<br>

<table width="70%">
	<tr>
		<td valign="top" width="50%">
			<!-- Most Popular Cards -->
			<table>
				<tr>
					<td colspan="2" class="subtitle"><? echo $stat02;?></th>
				</tr>
			<?
				$i=1;
				$sql = 'SELECT imageid, cardname, senttimes, imagepath FROM '.$tablePrefix.'cardinfo order by senttimes DESC';
				$recordSet = $conn->SelectLimit($sql,10,0);
				while (!$recordSet->EOF) 
				{
			?>
				<tr>
					<td><? echo $i;?>.</td>
					<td width="100%"><a href="javascript:CaricaFoto('../images/<? echo $recordSet->fields['imagepath'];?>')" ><? echo $recordSet->fields['cardname'];?></a> (<? echo $recordSet->fields['senttimes'];?>)</td>
				</tr>
			<?
					$i++;
					$recordSet->MoveNext();
				}
				$recordSet->Close();
				unset($sql, $recordSet);
			?>
			</table>
		</td>
		<td valign="top"  width="50%">
			<!-- Most Popular Categories by Cards Sent -->
			<table>
				<tr>
					<td colspan="2" class="subtitle"><? echo $stat03;?></th>
				</tr>
			<?
				$i=1;
				$sql = 'SELECT category, sum(senttimes) as total FROM '.$tablePrefix.'cardinfo, '.$tablePrefix.'categories WHERE '.$tablePrefix.'cardinfo.catid = '.$tablePrefix.'categories.catid group by '.$tablePrefix.'categories.catid order by total DESC';
				$recordSet = $conn->SelectLimit($sql,10,0);
				while (!$recordSet->EOF) 
				{
			?>
				<tr>
					<td><? echo $i;?>.</td>
					<td width="100%"><? echo $recordSet->fields['category'];?> (<? echo $recordSet->fields['total'];?>)</td>
				</tr>
			<?
					$i++;
					$recordSet->MoveNext();
				}
				$recordSet->Close();
				unset($sql, $recordSet);
			?>
			</table>
		</td>
	</tr>
</table>

</div>

<?
$page->showFooter();

function getStat(&$conn, $sql)
{
	$answer = $conn->GetOne($sql);
	return $answer;
}

?>