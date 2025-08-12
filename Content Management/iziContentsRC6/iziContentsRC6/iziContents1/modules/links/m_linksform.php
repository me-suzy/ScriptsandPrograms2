<?php

/***************************************************************************

 m_linksform.php
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


$GLOBALS["ModuleName"] = 'links';
include("moduleref.php");


$GLOBALS["rootdp"] = '../../';
include_once ($GLOBALS["rootdp"]."include/config.php");
require_once ($GLOBALS["rootdp"]."include/db.php");
require_once ($GLOBALS["rootdp"]."include/session.php");

include ($GLOBALS["rootdp"]."include/access.php");

include ($GLOBALS["rootdp"]."include/settings.php");
include ($GLOBALS["rootdp"]."include/functions.php");
include ($GLOBALS["rootdp"].$GLOBALS["admin_home"]."adminfunctions.php");
include ($GLOBALS["rootdp"].$GLOBALS["admin_home"]."compile.php");
include ($GLOBALS["rootdp"].$GLOBALS["modules_home"]."modfunctions.php");
include ($GLOBALS["rootdp"].$GLOBALS["language_home"].$GLOBALS["gsLanguage"]."/lang_admin.php");
include ($GLOBALS["rootdp"].$GLOBALS["language_home"].$GLOBALS["gsLanguage"]."/lang_main.php");
include_languagefile ($GLOBALS["modules_home"].$GLOBALS["ModuleRef"].'/',$GLOBALS["gsLanguage"],'lang_links.php');


// Security vet.
// Start by setting all security access to false.
// Do it in the code to overwrite any spurious values that a hacker may
//    have tried to pass in the url
$GLOBALS["specialedit"] = $GLOBALS["canedit"] = $GLOBALS["canadd"] = $GLOBALS["canview"] = False;
$GLOBALS["fieldstatus"] = '';

// Validate the user's level of access for this form.
$GLOBALS["form"] = 'subcontent';
$GLOBALS["validaccess"] = VerifyAdminLogin();
$GLOBALS["specialedit"] = True;


//	Set list of textareas in an array for HTMLArea integration
$GLOBALS["textareas"]	= array('descr');
$GLOBALS["base_url"] = SiteBaseUrl($EZ_SESSION_VARS["Site"]);



GetModuleData($GLOBALS["ModuleRef"]);

// If we've been passed the request from the banner list, then we
//    read banner data from the database for an edit request, or skip
//    if this is an 'add new' request
if ($_GET["LinkID"] != "") {
   $_POST["LinkID"] = $_GET["LinkID"];
   $_POST["page"] = $_GET["page"];
   GetGlobalData();
} else {
   $timenow = time();			// Calculate the default expiry date
   $GLOBALS["DefExpDate"] = DateAdd($expiryperiod,$expirynumber,$timenow);
   GetFormData;
}

if ($_POST["submitted"] == "yes") {
   AddLinks();
   Header("Location: ".BuildLink('m_'.$GLOBALS["ModuleName"].'.php')."&page=".$_POST["page"]);
}

frmLinksForm();


function frmLinksForm()
{
   global $EZ_SESSION_VARS, $_POST;

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
       <?php FieldHeading("WebURL","webURL"); ?>
       <td valign="top" class="content">
           <input type="text" name="webURL" size="50" value="<?php echo htmlspecialchars($GLOBALS["gsWeb_Page"]); ?>" maxlength=255<?php echo $GLOBALS["fieldstatus"]; ?>>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("Description","descr"); ?>
       <td valign="top" class="content">
			<?php
			if ((isset($GLOBALS["textareas"])) && ($EZ_SESSION_VARS["WYSIWYG"] == 'Y')) {
				?>
				<textarea id="descr" name="descr" style="width:540; height:240"<?php echo $GLOBALS["fieldstatus"]; ?>><?php echo $GLOBALS["gsDescr"]; ?></textarea>
				<?php
			} else {
	           EditButtons("Description","descr");
				?>
				<textarea rows="6" id="descr" name="descr" cols="64"<?php echo $GLOBALS["fieldstatus"]; ?>><?php echo htmlspecialchars($GLOBALS["gsDescr"]); ?></textarea>
				<?php
			}
			admintagdisplay('descr');
			admintagdisplay2('descr');
			?>
       </td>
   </tr>
   <?php
   adminformsavebar(2,'m_links.php');
   if ($GLOBALS["specialedit"] == True) {
//      adminhelpmsg(2);
      ?><input type="hidden" name="LinkID" value="<?php echo $_POST["LinkID"]; ?>"><?php
   }
   adminformclose();
} // function frmLinksForm()


function AddLinks()
{
   global $_POST, $EZ_SESSION_VARS;

   $publishisodate = dbDateTime(sprintf("%04d-%02d-%02d 00:00:00", $_POST["PublishYear"], $_POST["PublishMonth"], $_POST["PublishDay"]));
   $updateisodate  = dbDateTime(sprintf("%04d-%02d-%02d %02d:%02d:%02d", strftime("%Y"), strftime("%m"), strftime("%d"), strftime("%H"), strftime("%M"), strftime("%S")));
   $authorid = lGetAuthorID();

   $sWebURL  = trim($_POST["webURL"]);
   $sDescr = trim(dbString($_POST["descr"]));
	if ($sDescr == '<br />') { $sDescr = '';
	} else {
		//	Adjust any absolute URLs to relative URLs for images and downloads
		$sDescr = str_replace($GLOBALS["tqBlock1"].'./',$GLOBALS["tqBlock1"].'/',$sDescr);
		$sDescr = str_replace($GLOBALS["base_url"],'./',$sDescr); 
		$sDescr = str_replace('<./','</',$sDescr);
		$sDescr = str_replace('../','',$sDescr);
		//	Compile pre-compiled tags
		$sDescr = trim(compile($GLOBALS["tqBlock1"].'html'.$GLOBALS["tqBlock2"].$sDescr.$GLOBALS["tqBlock1"].$GLOBALS["tqCloseBlock"].'html'.$GLOBALS["tqBlock2"], $EZ_SESSION_VARS["WYSIWYG"], $GLOBALS["RestrictTags"]));
	}

   if ($GLOBALS["scValidate"] == 'Y') { $scValid = 0; }
   else { $scValid = 1; }

   if ($_POST["LinkID"] != "") {
      $strQuery = "UPDATE ".$GLOBALS["scTable"]." SET linkurl='".$sWebURL."', publishdate='".$publishisodate."', linkdescr='".$sDescr."', updatedate='".$updateisodate."', authorid='".$authorid."', catid='".$_POST["catcode"]."' WHERE linksid='".$_POST["LinkID"]."'";
   } else {
      $strQuery = "INSERT INTO ".$GLOBALS["scTable"]." VALUES('', '".$publishisodate."', '".$sWebURL."', '".$sDescr."', '".$scValid."', '".$authorid."', '".$updateisodate."', '".$_POST["catcode"]."')";
   }
   $result = dbExecute($strQuery,true);
   dbCommit();
}


function GetGlobalData()
{
   global $EZ_SESSION_VARS, $_GET, $_POST;

   $strQuery="SELECT * FROM ".$GLOBALS["scTable"]." WHERE linksid='".$_GET["LinkID"]."'";
   $result = dbRetrieve($strQuery,true,0,0);
   $rs     = dbFetch($result);

   $GLOBALS["gsWeb_Page"]		= $rs["linkurl"];
   $GLOBALS["gsPublishDate"]	= $rs["publishdate"];
   $GLOBALS["gsCatCode"]		= $rs["catid"];

	if ($EZ_SESSION_VARS["WYSIWYG"] == 'Y') {
		$GLOBALS["gsDescr"]	= formatWYSIWYG($rs["linkdescr"]);
	} else {
		$GLOBALS["gsDescr"]	= $rs["linkdescr"];
	}

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

   $GLOBALS["gsPublishDate"]	= $publishisodate;
   $GLOBALS["gsWeb_Page"]		= $_POST["webURL"];
   $GLOBALS["gsDescr"]			= $_POST["descr"];
   $GLOBALS["gsCatCode"]		= $_POST["catcode"];

   if ($_POST["authorid"] == $EZ_SESSION_VARS["UserID"]) {
      $GLOBALS["specialedit"] = True;
      $GLOBALS["fieldstatus"] = '';
   }
} // function GetFormData()

ModuleJavaFunctions();

?>
