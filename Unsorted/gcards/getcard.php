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
include('inc/adodb/adodb.inc.php');	   # load code common to ADOdb
include('config.php');
include('inc/UIfunctions.php');

$cardid = (int)$_GET['cardid'];

$page = new pagebuilder;
include_once('inc/setLang.php');
$page->langargs = "&cardid=$cardid";
$page->showHeader();

if (!$cardid)
{
	echo '<span class="error">'.$getcard01.'</span>';
	$page->showFooter();
	exit;
}

	$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
	$conn = &ADONewConnection('mysql');	# create a connection
	$conn->Connect($dbhost,$dbuser,$dbpass,$dbdatabase);
	$sqlstmt = "select ".$tablePrefix."sentcards.imageid, ".$tablePrefix."sentcards.from_name, ".$tablePrefix."sentcards.from_email, ".$tablePrefix."sentcards.to_name, ".$tablePrefix."sentcards.to_email, ".$tablePrefix."sentcards.cardtext, ".$tablePrefix."cardinfo.imagepath, ".$tablePrefix."sentcards.sendonpickup, ".$tablePrefix."sentcards.music from ".$tablePrefix."sentcards, ".$tablePrefix."cardinfo where ".$tablePrefix."sentcards.imageid=".$tablePrefix."cardinfo.imageid and ".$tablePrefix."sentcards.cardid=$cardid";
	
	$recordSet = &$conn->Execute($sqlstmt);
	if (!$recordSet) 
		print $conn->ErrorMsg();
	else
		{
			while (!$recordSet->EOF) 
			{
				$imageid = $recordSet->fields['imageid'];
				$from_name = $recordSet->fields['from_name'];
				$from_email = $recordSet->fields['from_email'];
				$to_name = $recordSet->fields['to_name'];
				$to_email = $recordSet->fields['to_email'];
				$cardtext = $recordSet->fields['cardtext'];
				$imagepath = $recordSet->fields['imagepath'];
				$sendOnPickup = $recordSet->fields['sendonpickup'];
				$music = $recordSet->fields['music'];
				$recordSet->MoveNext();
			}
			$recordSet->Close();
		}
?>

<?  
eval ("\$getcard02 = \"$getcard02\";");
eval ("\$getcard03 = \"$getcard03\";");
if ($sendOnPickup == 'send')
{
	include_once('config_email.php');
	$emailer = new emailer();
	$emailer->From     = $siteEmail;
	$emailer->FromName = $siteName;
	$emailer->Subject = $getcard02; 
	$emailer->Body    = $getcard02;
	$emailer->AddAddress($from_email);
	
	if($emailer->Send())
	{
		$updateSendOnPickupSQL = "UPDATE ".$tablePrefix."sentcards set sendonpickup='sent' where cardid=$cardid";
		$conn->Execute($updateSendOnPickupSQL);
	}
}
include('showcard.php');
?>
<br>
<div align="center">
	<a href="index.php?reply=<? echo $from_email;?>"><? echo $getcard03;?></a>
</div>


<?

if ($stats_pickupcard_hits_enabled = true)
{
		$sql = "UPDATE ".$tablePrefix."statistics set statval=(statval +1) WHERE stat='pickuphits'";
		$conn->Execute($sql);
		unset($sql);
}

$page->showFooter();
?>




