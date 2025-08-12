<?php

/***************************************************************************

 m_languagesform.php
 --------------------
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
$GLOBALS["form"] = 'languages';
$validaccess = VerifyAdminLogin3("LanguageCode");

includeLanguageFiles('admin','languages');


// If we've been passed the request from the languages list, then we
//    read the language data from the database for an edit request
if ($_GET["LanguageCode"] != '') {
   $_POST["LanguageCode"] = $_GET["LanguageCode"];
   $_POST["page"] = $_GET["page"];
   $_POST["sort"] = $_GET["sort"];
   GetGlobalData();
}

$GLOBALS["tabindex"] = 1024;

if ($_POST["submitted"] == "yes") {
   // User has submitted the data
   if (bCheckForm()) {
      AddLanguage();
      Header("Location: ".BuildLink('m_languages.php')."&page=".$_POST["page"]);
   } else {
      // Invalid data has been submitted
      GetFormData();
   }
}
frmLanguageForm();


function frmLanguageForm()
{
   global $_POST;

   adminformheader();
   adminformopen('languagename');
   adminformtitle(2,$GLOBALS["tFormTitle"]);
   if (isset($GLOBALS["strErrors"])) { formError(2); }
   adminsubheader(2,$GLOBALS["thLanguageGeneral"]);
   ?>
   <tr class="tablecontent">
       <?php FieldHeading("Language","languagename"); ?>
       <td valign="top" class="content">
           <input type="text" name="languagename" size="24" value="<?php echo $GLOBALS["gslanguagename"]; ?>" maxlength="24"<?php echo $GLOBALS["fieldstatus"]; ?>>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("CharSet","charset"); ?>
       <td valign="top" class="content">
           <input type="text" name="charset" size="32" value="<?php echo $GLOBALS["gscharset"]; ?>" maxlength="32"<?php echo $GLOBALS["fieldstatus"]; ?>>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("Direction",3); ?>
       <td valign="top" class="content">
           <input type="radio" value="ltr" name="direction" <?php if ($GLOBALS["gsdirection"] != "rtl") echo "checked" ?><?php echo $GLOBALS["fieldstatus"]; ?>><?php echo $GLOBALS["tLtR"]; ?><br />
           <input type="radio" value="rtl" name="direction" <?php if ($GLOBALS["gsdirection"] == "rtl") echo "checked" ?><?php echo $GLOBALS["fieldstatus"]; ?>><?php echo $GLOBALS["tRtL"]; ?>
       </td>
   </tr>
   <?php
   adminformsavebar(2,'m_languages.php');
   if ($GLOBALS["specialedit"] == True) {
      adminhelpmsg(2);
      ?><input type="hidden" name="LanguageCode" value="<?php echo $_POST["LanguageCode"]; ?>"><?php
   }
   adminformclose();
} // function frmTagForm()


function AddLanguage()
{
   global $_POST, $EZ_SESSION_VARS;

   $sLanguageName = dbString($_POST["languagename"]);
   $sCharSet =      dbString($_POST["charset"]);

   $strQuery = "UPDATE ".$GLOBALS["eztbLanguages"]." set languagename='".$sLanguageName."', charset='".$sCharSet."', direction='".$_POST["direction"]."' where languagecode='".$_POST["LanguageCode"]."'";
   $result = dbExecute($strQuery,true);

   //  If we're enabling a new language, we need to ensure that all privilege usergroups have an entry
   //     for that language.
   $strQuery = "SELECT usergroupdesc,usergroupname FROM ".$GLOBALS["eztbUsergroups"]." WHERE language='".$GLOBALS["gsDefault_language"]."'";
   $lresult = dbRetrieve($strQuery,true,0,0);
   while ($lrs = dbFetch($lresult)) {
      $strQuery = "INSERT INTO ".$GLOBALS["eztbUsergroups"]." VALUES('', '".$lrs["usergroupdesc"].", '".$lrs["usergroupname"]."', '".$_POST["LanguageCode"]."', '".$EZ_SESSION_VARS["UserID"]."')";
      $result = dbExecute($strQuery,false);
   }
   dbFreeResult($lresult);

   dbCommit();
} // function AddTag()


function bCheckForm()
{
   global $_POST;

   $bFormOK = true;
   if($_POST["languagename"] == "") {
      $GLOBALS["strErrors"][] = $GLOBALS["eNoLanguage"];
   }
   $strQuery = "SELECT languagecode FROM ".$GLOBALS["eztbLanguages"]." WHERE languagename='".$_POST["languagename"]."' AND languagecode != '".$_POST["LanguageCode"]."'";
   $cres = dbRetrieve($strQuery,true,0,0);
   $tagcheck = dbRowsReturned($cres);
   if ($tagcheck != 0) {
      $GLOBALS["strErrors"][] = $GLOBALS["eLanguageExists"];
   }
   dbFreeResult($cres);

   if (isset($GLOBALS["strErrors"])) { $bFormOK = false; }
   return $bFormOK;
} // function bCheckForm()


function GetGlobalData()
{
   global $_GET;

   $strQuery = "SELECT * FROM ".$GLOBALS["eztbLanguages"]." WHERE languagecode='".$_GET["LanguageCode"]."'";
   $result = dbRetrieve($strQuery,true,0,0);
   $rs     = dbFetch($result);

   $GLOBALS["gslanguagename"] = $rs["languagename"];
   $GLOBALS["gscharset"]      = $rs["charset"];
   $GLOBALS["gsenabled"]      = $rs["enabled"];
   $GLOBALS["gsdirection"]    = $rs["direction"];
   dbFreeResult($result);
} // function GetGlobalData()


function GetFormData()
{
   global $_POST;

   $GLOBALS["gslanguagename"] = $_POST["languagename"];
   $GLOBALS["gscharset"]      = $_POST["charset"];
   $GLOBALS["gsenabled"]      = $_POST["enabled"];
   $GLOBALS["gsdirection"]    = $_POST["direction"];
} // function GetFormData()


include($GLOBALS["rootdp"]."include/javafuncs.php");

?>
