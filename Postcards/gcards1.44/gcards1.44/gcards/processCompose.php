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
include_once('inc/formFunctions.php');
include_once('inc/UIfunctions.php');
include_once('config.php');

$page = new pagebuilder;
include_once('inc/setLang.php');
$page->showHeader();

if (isset($_GET['ds'])) $ds = $_GET['ds']; else $ds = 1;

// Set session vars if preview or direct send
if ($ds == 1)
{
	$_SESSION['from_name'] = checkAddSlashes($_POST['from_name']);
	$_SESSION['to_email'] = $_POST['to_email'];
	$_SESSION['from_email'] = $_POST['from_email'];
	$_SESSION['cardtext'] = checkAddSlashes($_POST['cardtext']);
	if (isset($_POST['music'])) $_SESSION['music'] = $_POST['music']; else $_SESSION['music'] = 'none';
	if (isset($_POST['sendOnPickup'])) $_SESSION['sendOnPickup'] = $_POST['sendOnPickup']; else $_SESSION['sendOnPickup'] = 'no';
	$emailError = 0;
}

// Create Local Variables for verification, preview, and send
createLocalFromSession('imageid');
createLocalFromSession('from_name');
createLocalFromSession('from_email');
createLocalFromSession('to_email');
createLocalFromSession('cardtext');
createLocalFromSession('sendOnPickup');
createLocalFromSession('music');

// Form validation when coming from compose.php
if ($ds == 1)
{
	// Make sure user entered data for the following fields
	if (!filledForm('from_name, from_email, to_email, cardtext', $_POST))
	{
		echo $preview01;
		$page->showFooter();
		exit;
	}
	
	// Check size of post data and throw errors if it is too big
	if (strlen($cardtext) > 10000)
	{
		echo $preview05;
		$page->showFooter();
		exit;
	}
	$fields = array(
				0 => array(
					"field" => "from_name",
					"maxlength" => 60),
				1 => array(
					"field" => "from_email",
					"maxlength" => 60),
				2 => array(
					"field" => "to_email",
					"maxlength" => 600)
					);
	foreach($fields as $field)
	{
		if (strlen($_POST[$field['field']]) > $field['maxlength'])
		{
			echo "Error - \"".$_POST[$field['field']]."\" > ".$field['maxlength']."!  $nav11";
			$page->showFooter();
			exit;
		}
	}
	
	// Check to_email for valid email addresses
	$emails = explode(",",$to_email);
	foreach($emails as $email)
	{
		$email = trim($email);
		if (validEmail($email))
		{
			$cleanedValid[] = $email;
		}
		else
		{
			$emailErrorMsgs[] = "<span class=\"error\">'".$email."' $preview02 </span><br>";
			$emailError = 1;
		}
	}
	
	// Check from_email for valid email address
	if (!validEmail($from_email))
	{
		$emailErrorMsgs[] = "<span class=\"error\">'".$from_email."' $preview02 </span><br>";
		$emailError = 1;
	}
	
	// throw email errors if there are any 
	if ($emailError == 1)
	{
		echo $preview03."<br><br>";
		foreach($emailErrorMsgs as $errorMsg)
		{
			echo $errorMsg;
		}
		$page->showFooter();
		exit;
	}
	
	$_SESSION['to_email'] = implode(",",$cleanedValid);
}


// Switch to either send or preview action
switch ($_REQUEST['action'])
{

// preview code
	case $action08:
		include('inc/adodb/adodb.inc.php');
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$conn = &ADONewConnection('mysql');
		$conn->Connect($dbhost,$dbuser,$dbpass,$dbdatabase);
		$sqlstmt = "select imagepath from ".$tablePrefix."cardinfo where imageid='$imageid'";
		$recordSet = &$conn->Execute($sqlstmt);
		if (!$recordSet) print $conn->ErrorMsg();
		else	$imagepath = $recordSet->fields['imagepath'];
		include('showcard.php'); 
		?>
		<br><br>
		<div align="center">
		<a href="compose.php"><? echo $nav01;?></a>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<a href="processCompose.php?ds=0&action=<? echo $preview04;?>"><? echo $preview04;?></a>
		</div>
		<?
		$page->showFooter();
		exit;
	break;


// sendcard code
	case $preview04:
		$cardid = time();
		$to_name = 'null';
		deleteFromSession('reply');
		eval ("\$subject = \"$subject\";");
		eval ("\$message = \"$message\";");
		if (!$imageid || !$cardtext || !$from_email || !$from_name || !$to_name || !$to_email)
		{
			echo $sendcard01;
			$page->showFooter();
			exit;
		}
		include_once('inc/adodb/adodb.inc.php');
		include_once('config_email.php');
		$emailer = new emailer();
		$emailer->From     = $from_email;
		$emailer->FromName = $from_name;
		$emailer->Subject = $subject; 
		$emailer->Body    = $message;
		$emailAddresses = explode(',', $to_email);
		if (count($emailAddresses) == 1) {
			$emailer->AddAddress(trim($emailAddresses[0]));
		}
		else
		{
			$emailer->AddAddress(trim($from_email));
			foreach($emailAddresses as $emailAddress)
			{
				$emailAddress = trim($emailAddress);
				$emailer->AddBCC($emailAddress);
			}
		}
		if(!$emailer->Send())
		{
			echo "There has been a mail error sending to $to_email <br>";
			echo $sendcard04;
		}
		else
		{
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$conn = &ADONewConnection('mysql');	# create a connection
			$conn->Connect($dbhost,$dbuser,$dbpass,$dbdatabase);
			$sqlstmt = "INSERT into ".$tablePrefix."sentcards (cardid,imageid,to_name,to_email,from_name,from_email,cardtext,sendonpickup, music) values ($cardid,$imageid,'$to_name','$to_email','$from_name','$from_email','$cardtext','$sendOnPickup', '$music')";
			if ($conn->Execute($sqlstmt) === false) 
			{
				echo $sendcard02.$conn->ErrorMsg().'<BR>';
			}
			$counterSQL = "UPDATE ".$tablePrefix."cardinfo set senttimes=(senttimes + 1) WHERE imageid=$imageid";
			$conn->Execute($counterSQL);
			echo $sendcard03."<br><br>";
			$page->showLink('index.php', "$siteName $nav03");
		}
		$page->showFooter();
		exit;
	break;

// No Action (it should never get this far, but if it does, show error)
	default:
		?>You really shouldn't come to this page directly<?
		$page->showFooter();
		exit;
	break;
}

?>