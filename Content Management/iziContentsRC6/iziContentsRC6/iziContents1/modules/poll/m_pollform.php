<?php

/***************************************************************************

 m_pollform.php
 ---------------
 copyright : (C) 2005 - The iziContents Development Team

 iziContents version : 1.0
 fileversion : 1.0.1
 change date : 23 - 04 - 2005
 ***************************************************************************/

/***************************************************************************
 The iziContents Development Team offers no warranties on this script.
 The owner/licensee of the script is solely responsible for any problems
 caused by installation of the script or use of the script.

 All copyright notices regarding iziContents and ezContents must remain intact on the scripts and in the HTML for the scripts.

 For more info on iziContents,
 visit http://www.izicontents.com*/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the License which can be found within the
 *   zipped package. Under the licence of GPL/GNU.
 *
 ***************************************************************************/

// Localisation variables (used for default values)
// Change these to suit your site preferences
//
$expiryperiod = 'm';			// Time period to calculate the banner expiry date (based on today's date)
$expirynumber = 1;


$GLOBALS["ModuleName"] = 'poll';
include("moduleref.php");

$GLOBALS["rootdp"] = '../../';
require_once ($GLOBALS["rootdp"]."include/config.php");
require_once ($GLOBALS["rootdp"]."include/db.php");
require_once ($GLOBALS["rootdp"]."include/session.php");

include ($GLOBALS["rootdp"]."include/access.php");

include ($GLOBALS["rootdp"]."include/settings.php");
include ($GLOBALS["rootdp"]."include/functions.php");
include ($GLOBALS["rootdp"].$GLOBALS["admin_home"]."adminfunctions.php");
include ($GLOBALS["rootdp"].$GLOBALS["modules_home"]."modfunctions.php");
include ($GLOBALS["rootdp"].$GLOBALS["language_home"].$GLOBALS["gsLanguage"]."/lang_admin.php");
include ($GLOBALS["rootdp"].$GLOBALS["language_home"].$GLOBALS["gsLanguage"]."/lang_main.php");
include_languagefile ($GLOBALS["modules_home"].$GLOBALS["ModuleRef"].'/',$GLOBALS["gsLanguage"],'lang_poll.php');


// Security vet.
// Start by setting all security access to false.
// Do it in the code to overwrite any spurious values that a hacker may
//	have tried to pass in the url
$GLOBALS["specialedit"] = $GLOBALS["canedit"] = $GLOBALS["canadd"] = $GLOBALS["canview"] = False;
$GLOBALS["fieldstatus"] = '';

// Validate the user's level of access for this form.
$GLOBALS["form"] = 'subcontent';
$GLOBALS["validaccess"] = VerifyAdminLogin();
$GLOBALS["specialedit"] = True;


GetModuleData($GLOBALS["ModuleRef"]);

// If we've been passed the request from the banner list, then we
//	read banner data from the database for an edit request, or skip
//	if this is an 'add new' request
if ($_GET["PollID"] != "") {
	$_POST["PollID"] = $_GET["PollID"];
	$_POST["page"] = $_GET["page"];
	GetGlobalData();
} else {
	$timenow = time();			// Calculate the default expiry date
	$GLOBALS["DefExpDate"] = date('Y-m-d H:i:s',DateAdd($expiryperiod,$expirynumber,$timenow));
	GetFormData;
}

if ($_POST["submitted"] == "yes") {
	AddPoll();
	Header("Location: ".BuildLink('m_'.$GLOBALS["ModuleName"].'.php')."&page=".$_POST["page"]);
}

frmPollForm();


function frmPollForm()
{
	global $_POST;

	adminformheader();
	adminformopen('PublishDay');
	adminformtitle(2,$GLOBALS["tFormTitle"]);

	if ($GLOBALS["scUseCategories"] == 'Y') {
		?>
		<tr class="tablecontent">
			<?php FieldHeading("Category","catcode"); ?>
			<td valign="top" class="content">
				<select name="catcode" size="1">
					<option value="0">
					<?php RenderAllCategories($GLOBALS["gsCatCode"]); ?>
				</select>
			</td>
		</tr>
		<?php
	} else {
		?><input type="hidden" name="catcode" value="0"><?php
	}
	?>
	<tr class="tablecontent">
		<?php FieldHeading("PublishDate","PublishDay"); ?>
		<td valign="top" class="content">
			<?php admindatedisplay('Publish',$GLOBALS["gsPublishDate"]); ?>
		</td>
	</tr>
	<tr class="tablecontent">
		<?php FieldHeading("ExpiryDate","ExpiryDay"); ?>
		<td valign="top" class="content">
			<?php admindatedisplay('Expire',$GLOBALS["gsExpireDate"],$GLOBALS["DefExpDate"]); ?>
		</td>
	</tr>
	<tr class="tablecontent">
		<?php FieldHeading("Question","question"); ?>
		<td valign="top" class="content">
			<?php EditButtons("Question","question"); ?>
			<textarea rows="4" name="question" cols="66"<?php echo $GLOBALS["fieldstatus"]; ?>><?php echo htmlspecialchars($GLOBALS["gsQuestion"]); ?></textarea>
		</td>
	</tr>
	<tr class="tablecontent">
		<?php FieldHeading("PollType",8); ?>
		<td valign="top" class="content">
			<input type="radio" value="S" name="polltype" <?php if($GLOBALS["gsPollType"] == "S" || $GLOBALS["gsPollType"] == "") echo "checked" ?><?php echo $GLOBALS["fieldstatus"]; ?>><?php echo $GLOBALS["tSingleVote"]; ?><br />
			<input type="radio" value="M" name="polltype" <?php If($GLOBALS["gsPollType"] == "M") echo "checked" ?><?php echo $GLOBALS["fieldstatus"]; ?>><?php echo $GLOBALS["tMultiVotes"]; ?>
	</td>
   </tr>
	<?php
	adminformsavebar(2,'m_poll.php');
	if ($GLOBALS["specialedit"] == True)
	{
//		adminhelpmsg(2);
		?><input type="hidden" name="PollID" value="<?php echo $_POST["PollID"]; ?>"><?php
	}
	adminformclose();
} // function frmPollForm()


function AddPoll()
{
	global $_POST;

	$publishisodate = dbDateTime(sprintf("%04d-%02d-%02d 00:00:00", $_POST["PublishYear"], $_POST["PublishMonth"], $_POST["PublishDay"]));
	$expireisodate  = dbDateTime(sprintf("%04d-%02d-%02d 00:00:00", $_POST["ExpireYear"], $_POST["ExpireMonth"], $_POST["ExpireDay"]));
	$authorid = lGetAuthorID();

	$sQuestion = dbString($_POST["question"]);

	if ($GLOBALS["scValidate"] == 'Y') { $scValid = 0; }
	else { $scValid = 1; }

	if ($_POST["PollID"] != "") {
		$strQuery = "UPDATE ".$GLOBALS["scTable"]." SET question='".$sQuestion."', publishdate='".$publishisodate."', expiredate='".$expireisodate."', authorid='".$authorid."', catid='".$_POST["catcode"]."' WHERE pollid='".$_POST["PollID"]."'";
	} else {
		$strQuery = "INSERT INTO ".$GLOBALS["scTable"]." VALUES('', '".$publishisodate."', '".$expireisodate."', '".$sQuestion."', ".$scValid.", '".$authorid."', '".$_POST["catcode"]."', '".$_POST["polltype"]."', 0)";
	}
	$result = dbExecute($strQuery,true);
	dbCommit();
}


function GetGlobalData()
{
	global $EZ_SESSION_VARS, $_GET, $_POST;

	$strQuery="SELECT * FROM ".$GLOBALS["scTable"]." WHERE pollid='".$_GET["PollID"]."'";
	$result = dbRetrieve($strQuery,true,0,0);
	$rs	= dbFetch($result);

	$GLOBALS["gsTitle"]			= $rs["title"];
	$GLOBALS["gsPublishDate"]	= $rs["publishdate"];
	$GLOBALS["gsExpireDate"]	= $rs["expiredate"];
	$GLOBALS["gsQuestion"]		= $rs["question"];
	$GLOBALS["gsCatCode"]		= $rs["catid"];
	$GLOBALS["gsPollType"]		= $rs["polltype"];

	$_POST["authorid"] = $rs["authorid"];
	if ($rs["authorid"] == $EZ_SESSION_VARS["UserID"]) {
		$GLOBALS["specialedit"] = True;
		$GLOBALS["fieldstatus"] = '';
	}
} // function GetGlobalData()


function GetFormData()
{
	global $EZ_SESSION_VARS, $_POST;

	$publishisodate = sprintf("%04d-%02d-%02d", $_POST["PublishYear"], $_POST["PublishMonth"], $_POST["PublishDay"]);
	$expireisodate = sprintf("%04d-%02d-%02d", $_POST["ExpireYear"], $_POST["ExpireMonth"], $_POST["ExpireDay"]);

	$GLOBALS["gsTitle"]			= $_POST["title"];
	$GLOBALS["gsPublishDate"]	= $publishisodate;
	$GLOBALS["gsExpireDate"]	= $expireisodate;
	$GLOBALS["gsQuestion"]		= $_POST["question"];
	$GLOBALS["gsCatCode"]		= $_POST["catcode"];
	$GLOBALS["gsPollType"]		= $_POST["polltype"];

	if ($_POST["authorid"] == $EZ_SESSION_VARS["UserID"]) {
		$GLOBALS["specialedit"] = True;
		$GLOBALS["fieldstatus"] = '';
	}
} // function GetFormData()

ModuleJavaFunctions();

?>
