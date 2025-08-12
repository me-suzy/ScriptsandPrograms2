<?php

/***************************************************************************

 submit_links.php
 ----------------
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


global $_SERVER;
if ( (substr($_SERVER["PHP_SELF"],-11) == 'control.php') ||
	 (substr($_SERVER["PHP_SELF"],-10) == 'module.php') ||
	 (substr($_SERVER["PHP_SELF"],-16) == 'showcontents.php') ) {
	 require_once('../moduleSec.php');
} else {
	require_once('../moduleSec.php');
}

// Localisation variables (used for default values)
// Change these to suit your site preferences
//
$expiryperiod = 'm';			// Time period to calculate the banner expiry date (based on today's date)
$expirynumber = 1;


$GLOBALS["ModuleName"] = 'links';

include_once ($GLOBALS["language_home"].$GLOBALS["gsLanguage"]."/lang_admin.php");
include_once ($GLOBALS["language_home"].$GLOBALS["gsLanguage"]."/lang_main.php");
include_languagefile ($GLOBALS["modules_home"].$GLOBALS["ModuleName"].'/',$GLOBALS["gsLanguage"],"lang_".$GLOBALS["ModuleName"].".php");

$validentry = True;
if ($GLOBALS["scLoginRequired"] == 'Y') {
	$validentry = VerifySubmoduleLogin($GLOBALS["tSubmitLinks"]);
}


if ($validentry) {
	$timenow = time();			// Calculate the default expiry date
	$GLOBALS["DefExpDate"] = date('Y-m-d H:i:s',DateAdd($expiryperiod,$expirynumber,$timenow));

	if ($_POST["submitted"] == "yes") {
		AddLinks();
		SubModuleReturn('showlinks.php', $GLOBALS["tLinksPage"],'');
	} else {
		frmLinksForm();
	}
}


function frmLinksForm()
{
	global $EZ_SESSION_VARS, $_POST;

	SubModFormHeader();
	adminformtitle(2,$GLOBALS["tSubmitFormTitle"]);
	$GLOBALS["specialedit"] = True;

	if ($GLOBALS["scUseCategories"] == 'Y') {
		?>
		<tr class="tablecontent">
			<?php FieldHeading("Category","catcode"); ?>
			<td valign="top" class="content">
				<select name="catcode" size="1">
					<option value="0">
					<?php RenderCategories($GLOBALS["gsCatCode"]); ?>
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
		<?php FieldHeading("WebURL","webURL"); ?>
		<td valign="top" class="content">
			<input type="text" name="webURL" size="50" value="<?php echo htmlspecialchars($GLOBALS["gsWeb_Page"]); ?>" maxlength=255<?php echo $GLOBALS["fieldstatus"]; ?>>
		</td>
	</tr>
	<tr class="tablecontent">
		<?php FieldHeading("Description","descr"); ?>
		<td valign="top" class="content">
			<?php
			EditButtons("Description","descr");
			?>
			<textarea rows="4" id="descr" name="descr" cols="44"<?php echo $GLOBALS["fieldstatus"]; ?>><?php echo htmlspecialchars($GLOBALS["gsDescr"]); ?></textarea>
			<?php
			admintagdisplay('descr');
			admintagdisplay2('descr');
			?>
		</td>
	</tr>
	<?php SubModFormFooter(2); ?>
	<input type="hidden" name="LinksID" value="<?php echo $_POST["LinksID"]; ?>">
	<?php modformclose($GLOBALS["tSubmitLink"]); ?>
	</td></tr></table>
	</form>
	</td></tr></table>
   <?php
} // function frmLinksForm()


function AddLinks()
{
	global $_POST;

	$publishisodate = dbDateTime(sprintf("%04d-%02d-%02d 00:00:00", $_POST["PublishYear"], $_POST["PublishMonth"], $_POST["PublishDay"]));
	$updateisodate  = dbDateTime(sprintf("%04d-%02d-%02d %02d:%02d:%02d", strftime("%Y"), strftime("%m"), strftime("%d"), strftime("%H"), strftime("%M"), strftime("%S")));
	$authorid = lGetAuthorID();

	$sWebURL  = trim($_POST["webURL"]);
	$sDescr = trim(dbString($_POST["descr"]));

	if ($GLOBALS["scValidate"] == 'Y') { $scValid = 0; } else { $scValid = 1; }
	$sCatId = $_POST["catcode"];

	$strQuery = "INSERT INTO ".$GLOBALS["scTable"]." VALUES('', '".$publishisodate."', '".$sWebURL."', '".$sDescr."', '".$scValid."', '".$authorid."', '".$updateisodate."', '".$_POST["catcode"]."')";
	$result = dbExecute($strQuery,true);
	dbCommit();
} // function AddLinks()

ModuleJavaFunctions();

?>
