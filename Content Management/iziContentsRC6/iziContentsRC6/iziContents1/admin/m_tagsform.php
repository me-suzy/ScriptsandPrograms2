<?php

/***************************************************************************

 m_tagsform.php
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

include_once ("rootdatapath.php");

// Security vet.
// Start by setting all security access to false.
// Do it in the code to overwrite any spurious values that a hacker may
//    have tried to pass in the url
$GLOBALS["specialedit"] = $GLOBALS["canedit"] = $GLOBALS["canadd"] = $GLOBALS["canview"] = False;
$GLOBALS["fieldstatus"] = '';

// Validate the user's level of access for this form.
$GLOBALS["form"] = 'tags';
$validaccess = VerifyAdminLogin3("TagID");

includeLanguageFiles('admin','tags');


//	Set list of textareas in an array for HTMLArea integration
$GLOBALS["textareas"]	= array('translation');
$GLOBALS["base_url"] = SiteBaseUrl($EZ_SESSION_VARS["Site"]);



// If we've been passed the request from the tags list, then we
//    read the tag data from the database for an edit request, or skip
//    if this is an 'add new' request
if ($_GET["TagID"] != '') {
	$_POST["TagID"] = $_GET["TagID"];
	$_POST["page"] = $_GET["page"];
	$_POST["sort"] = $_GET["sort"];
	GetGlobalData();
}

$GLOBALS["tabindex"] = 1024;

if ($_POST["submitted"] == "yes") {
	// User has submitted the data
	if (bCheckForm()) {
		AddTag();
		Header("Location: ".BuildLink('m_tags.php')."&page=".$_POST["page"]."&sort=".$_POST["sort"]);
	} else {
		// Invalid data has been submitted
		GetFormData();
	}
}
frmTagForm();


function frmTagForm()
{
	global $EZ_SESSION_VARS, $_POST;

	adminformheader();
	adminformopen('tagname');
	adminformtitle(2,$GLOBALS["tFormTitle"]);
	if (isset($GLOBALS["strErrors"])) { formError(2); }
	adminsubheader(2,$GLOBALS["thTagGeneral"]);
	?>
	<tr class="tablecontent">
		<?php FieldHeading("Tag","tagname"); ?>
		<td valign="top" class="content">
			<input type="text" name="tagname" size="32" value="<?php echo $GLOBALS["gsTagName"]; ?>" maxlength="32"<?php echo $GLOBALS["fieldstatus"]; ?>>
		</td>
	</tr>
	<tr class="tablecontent">
		<?php FieldHeading("Category","cat"); ?>
		<td valign="top" class="content">
			<select name="cat" size="1"<?php echo $GLOBALS["fieldstatus"]; ?>><?php RenderCats($GLOBALS["gsCat"]); ?></select>
		</td>
	</tr>
	<tr class="tablecontent">
			<?php
			if ((isset($GLOBALS["textareas"])) && ($EZ_SESSION_VARS["WYSIWYG"] == 'Y')) {
				FieldHeading("Translation","cat"); ?>
				<td valign="top" class="content">
				<textarea id="translation" name="translation" style="width:540; height:360"<?php echo $GLOBALS["fieldstatus"]; ?>><?php echo $GLOBALS["gsTranslation"]; ?></textarea>
				<?php
			} else {
				FieldHeading("Translation","translation"); ?>
				<td valign="top" class="content">
				<?php EditButtons("Teaser","teaser"); ?>
				<textarea name="translation" rows="6" cols="64"<?php echo $GLOBALS["fieldstatus"]; ?>><?php echo htmlspecialchars($GLOBALS["gsTranslation"]); ?></textarea>
				<?php
			}
			?>
		</td>
	</tr>
	<?php
	adminformsavebar(2,'m_tags.php');
	if ($GLOBALS["specialedit"] == True) {
		adminhelpmsg(2);
		?><input type="hidden" name="TagID" value="<?php echo $_POST["TagID"]; ?>"><?php
	}
	adminformclose();
} // function frmTagForm()


function AddTag()
{
	global $_POST, $EZ_SESSION_VARS;

	if ($EZ_SESSION_VARS["UserGroup"] == $GLOBALS["gsAdminPrivGroup"]) { $scriptsAllowed = 'Y'; } else { $scriptsAllowed = 'N'; }

	$sTranslation = trim(dbString($_POST["translation"]));
	if ($sTranslation == '<br />') { $sTranslation = '';
	} else {
		//	Adjust any absolute URLs to relative URLs for images and downloads
		$sTranslation	= str_replace($GLOBALS["tqBlock1"].'./',$GLOBALS["tqBlock1"].'/',$sTranslation);
		$sTranslation	= str_replace($GLOBALS["base_url"],'./',$sTranslation);
		$sTranslation	= str_replace('<./','</',$sTranslation);
		//	Compile pre-compiled tags
		$sTranslation	= trim(compile($GLOBALS["tqBlock1"].'html'.$GLOBALS["tqBlock2"].$sTranslation.$GLOBALS["tqBlock1"].$GLOBALS["tqCloseBlock"].'html'.$GLOBALS["tqBlock2"], $EZ_SESSION_VARS["WYSIWYG"], 'Y', 'L', $scriptsAllowed ));
	}

	if ($_POST["TagID"] != '') {
		$strQuery = "UPDATE ".$GLOBALS["eztbTags"]." SET tag='".$_POST["tagname"]."', cat='".$_POST["cat"]."', translation='".$sTranslation."' WHERE tagid='".$_POST["TagID"]."'";
	} else {
		$strQuery = "INSERT INTO ".$GLOBALS["eztbTags"]."(tag,canedit,candelete,translation,authorid,cat) VALUES('".$_POST["tagname"]."', 'Y', 'Y', '".$sTranslation."', ".$EZ_SESSION_VARS["UserID"].", '".$_POST["cat"]."')";
	}
	$result = dbExecute($strQuery,true);

	if ($_POST["TagID"] != '') {
		$strQuery = "SELECT contentname,language,body,teaser,leftright FROM ".$GLOBALS["eztbContents"]." WHERE body LIKE '%[".$_POST["tagname"]."]%' OR teaser LIKE '%[".$_POST["tagname"]."]%'";
		$sresult = dbRetrieve($strQuery,true,0,0);
		$results_found = dbRowsReturned($sresult);
		if ($results_found != 0) {
			while ($rsContent = dbFetch($sresult)) {
				$nContentName = $rsContent["contentname"];
				$nContentLang = $rsContent["language"];

				$sBody   = trim(compile($GLOBALS["tqBlock1"].'html'.$GLOBALS["tqBlock2"].$rsContent["body"].$GLOBALS["tqBlock1"].$GLOBALS["tqCloseBlock"].'html'.$GLOBALS["tqBlock2"], $EZ_SESSION_VARS["WYSIWYG"], 'Y', $rsContent["leftright"], $scriptsAllowed));
				$sTeaser = trim(compile($GLOBALS["tqBlock1"].'html'.$GLOBALS["tqBlock2"].$rsContent["teaser"].$GLOBALS["tqBlock1"].$GLOBALS["tqCloseBlock"].'html'.$GLOBALS["tqBlock2"], $EZ_SESSION_VARS["WYSIWYG"], 'Y', $rsContent["leftright"], $scriptsAllowed));
				$strQuery = "UPDATE ".$GLOBALS["eztbContents"]." SET cbody='".str_replace("'","\'", $sBody)."',cteaser='".str_replace("'","\'", $sTeaser)."' WHERE contentname='".$nContentName."' AND language='".$nContentLang."'";
				$cresult = dbExecute($strQuery,true);
			}
		}
		dbFreeResult($sresult);
	}
	dbCommit();
} // function AddTag()


function bCheckForm()
{
	global $_POST;

	$bFormOK = true;
	if ($_POST["tagname"] == "")	{ $GLOBALS["strErrors"][] = $GLOBALS["eNoTag"]; }
	if ((trim($_POST["translation"]) == "") || (trim($_POST["translation"]) == "<br />"))	{ $GLOBALS["strErrors"][] = $GLOBALS["eNoTranslation"]; }

	if (isset($GLOBALS["strErrors"])) { $bFormOK = false; }
	return $bFormOK;
} // function bCheckForm()


function GetGlobalData()
{
	global $EZ_SESSION_VARS, $_GET, $_POST;

	$strQuery="SELECT * FROM ".$GLOBALS["eztbTags"]." WHERE tagid='".$_GET["TagID"]."'";
	$result = dbRetrieve($strQuery,true,0,0);
	$rs     = dbFetch($result);

	$GLOBALS["gsTagName"]     = $rs["tag"];
	$GLOBALS["gsCat"]         = $rs["cat"];

	if ($GLOBALS["WYSIWYG"] == 'Y') {
		$GLOBALS["gsTranslation"]		= formatWYSIWYG($rs["translation"]);
	} else {
		$GLOBALS["gsTranslation"]		= $rs["translation"];
	}

	$_POST["authorid"] = $rs["authorid"];
	if ($rs["authorid"] == $EZ_SESSION_VARS["UserID"]) {
		$GLOBALS["specialedit"] = True;
		$GLOBALS["fieldstatus"] = '';
	}
	dbFreeResult($result);
} // function GetGlobalData()


function GetFormData()
{
	global $_POST, $EZ_SESSION_VARS;

	$GLOBALS["gsTagName"]     = $_POST["tagname"];
	$GLOBALS["gsCat"]         = $_POST["cat"];
	$GLOBALS["gsTranslation"] = $_POST["translation"];

	if ($_POST["authorid"] == $EZ_SESSION_VARS["UserID"]) {
		$GLOBALS["specialedit"] = True;
		$GLOBALS["fieldstatus"] = '';
	}
} // function GetFormData()


function RenderCats($cat)
{
   $sqlQuery = "SELECT * FROM ".$GLOBALS["eztbTagCategories"]." WHERE language='".$GLOBALS["gsLanguage"]."'";
   $result = dbRetrieve($sqlQuery,true,0,0);
   while ($rs = dbFetch($result)) {
      echo '<option ';
      if ($cat == $rs["catname"]) { echo 'selected '; }
      echo 'value="'.$rs["catname"].'">'.$rs["catdesc"];
   }
   dbFreeResult($result);
} // function RenderCats()


include($GLOBALS["rootdp"]."include/javafuncs.php");

?>
