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

// If we've been passed the request from the poll options list, then we
//	read the option data from the database for an edit request, or skip
//	if this is an 'add new' request
if ($_GET["PollID"] != "") {
	$_POST["PollID"] = $_GET["PollID"];
}
if ($_GET["PollOptionID"] != "") {
	$_POST["PollOptionID"] = $_GET["PollOptionID"];
	GetGlobalData();
} else {
	GetFormData;
}

if ($_POST["submitted"] == "yes") {
	AddPollOption();
	Header("Location: ".BuildLink('m_'.$GLOBALS["ModuleName"].'options.php').'&PollID='.$_POST["PollID"]);
}

frmPollOptionForm();


function frmPollOptionForm()
{
	global $_POST;

	adminformheader();
	adminformopen('option');
	adminformtitle(2,$GLOBALS["tFormTitle"]);

	?>
	<tr class="tablecontent">
		<?php FieldHeading("Option","option"); ?>
		<td valign="top" class="content">
			<?php EditButtons("Option","option"); ?>
			<textarea rows="2" name="option" cols="66"<?php echo $GLOBALS["fieldstatus"]; ?>><?php echo htmlspecialchars($GLOBALS["gsOption"]); ?></textarea>
		</td>
	</tr>
   </tr>
	<?php
	adminformsavebar(2,'m_polloptions.php');
	if ($GLOBALS["specialedit"] == True)
	{
//		adminhelpmsg(2);
		?><input type="hidden" name="PollOptionID" value="<?php echo $_POST["PollOptionID"]; ?>"><?php
		?><input type="hidden" name="PollID" value="<?php echo $_POST["PollID"]; ?>"><?php
	}
	adminformclose();
} // function frmPollForm()


function AddPollOption()
{
	global $_POST;

	$sOption = dbString($_POST["option"]);

	if ($_POST["PollOptionID"] != "") {
		$strQuery = "UPDATE ".$GLOBALS["scTable"]."options SET polloption='".$sOption."' WHERE polloptionid='".$_POST["PollOptionID"]."'";
	} else {
		$strQuery = "INSERT INTO ".$GLOBALS["scTable"]."options VALUES('', '".$_POST["PollID"]."', '".$sOption."', 0)";
	}
	$result = dbExecute($strQuery,true);
	dbCommit();
}


function GetGlobalData()
{
	global $_GET;

	$strQuery="SELECT * FROM ".$GLOBALS["scTable"]."options WHERE polloptionid='".$_GET["PollOptionID"]."'";
	$result = dbRetrieve($strQuery,true,0,0);
	$rs	= dbFetch($result);

	$GLOBALS["gsOption"]		= $rs["polloption"];
} // function GetGlobalData()


function GetFormData()
{
	global $_POST;

	$GLOBALS["gsOption"]	= $_POST["option"];
} // function GetFormData()

?>
