<?php

/***************************************************************************

 m_themesform.php
 -----------------
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
$GLOBALS["form"] = 'themes';
$validaccess = VerifyAdminLogin3("ThemeID");

includeLanguageFiles('admin','themes');


// If we've been passed the request from the themes list, then we
//    read the theme data from the database for an edit request, or skip
//    if this is an 'add new' request
if ($_GET["ThemeCode"] != '') {
   $_POST["ThemeCode"] = $_GET["ThemeCode"];
   $_POST["page"] = $_GET["page"];
   $_POST["sort"] = $_GET["sort"];
   GetGlobalData();
}

$GLOBALS["tabindex"] = 1024;

if ($_POST["submitted"] == "yes") {
   // User has submitted the data
   if (bCheckForm()) {
      AddTheme();
      Header("Location: ".BuildLink('m_themes.php')."&page=".$_POST["page"]."&sort=".$_POST["sort"]);
   } else {
      // Invalid data has been submitted
      GetFormData();
   }
}
frmThemeForm();


function frmThemeForm()
{
   global $_POST;

   adminformheader();
   if ($_POST["ThemeCode"] != '') {
      adminformopen('themename');
   } else {
      adminformopen('themecode');
   }
   adminformtitle(2,$GLOBALS["tFormTitle"]);
   if (isset($GLOBALS["strErrors"])) { formError(2); }
   adminsubheader(2,$GLOBALS["thThemeGeneral"]);
   ?>
   <tr class="tablecontent">
       <?php FieldHeading("ThemeCode","themecode"); ?>
       <td valign="top" class="content">
           <?php
           if ($_POST["ThemeCode"] != '') {
              ?><input type="text" name="themecode" size="32" value="<?php echo $GLOBALS["gsThemeCode"]; ?>" maxlength="32" disabled><?php
           } else {
              ?><input type="text" name="themecode" size="32" value="<?php echo $GLOBALS["gsThemeCode"]; ?>" maxlength="32"<?php echo $GLOBALS["fieldstatus"]; ?>><?php
           }
           ?>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("ThemeName","themename"); ?>
       <td valign="top" class="content">
           <input type="text" name="themename" size="32" value="<?php echo $GLOBALS["gsThemeName"]; ?>" maxlength="64"<?php echo $GLOBALS["fieldstatus"]; ?>>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("ThemeDescription","themedescription"); ?>
       <td valign="top" class="content">
           <textarea name="themedescription" rows="6" cols="64"<?php echo $GLOBALS["fieldstatus"]; ?>><?php echo htmlspecialchars($GLOBALS["gsThemeDescription"]); ?></textarea>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("ThemeEnabled","themeenabled"); ?>
       <td valign="top" class="content">
           <select name="themeenabled" size="1"<?php echo $GLOBALS["fieldstatus"]; ?>>
               <option value="1" <?php if($GLOBALS["gsThemeEnabled"] == "1") echo "selected"; ?>><?php echo $GLOBALS["tYes"]; ?>
               <option value="0" <?php if($GLOBALS["gsThemeEnabled"] != "1") echo "selected"; ?>><?php echo $GLOBALS["tNo"]; ?>
           </select>
       </td>
   </tr>
   <?php
   adminformsavebar(2,'m_themes.php');
   adminhelpmsg(2);
   ?><input type="hidden" name="ThemeCode" value="<?php echo $_POST["ThemeCode"]; ?>"><?php
   adminformclose();
} // function frmThemeForm()


function AddTheme()
{
   global $_POST, $EZ_SESSION_VARS;

   $sThemeName        = dbString($_POST["themename"]);
   $sThemeDescription = dbString($_POST["themedescription"]);

   if ($_POST["ThemeCode"] != '') {
      $strQuery = "UPDATE ".$GLOBALS["eztbThemes"]." SET themename='".$sThemeName."', themedescription='".$sThemeDescription."', themeenabled='".$_POST["themeenabled"]."' WHERE themecode='".$_POST["ThemeCode"]."'";
   } else {
      $strQuery = "INSERT INTO ".$GLOBALS["eztbThemes"]."(themecode,themename,themedescription,themeenabled) VALUES('".$_POST["themecode"]."', '".$sThemeName."', '".$sThemeDescription."', '".$_POST["themeenabled"]."')";
   }
   $result = dbExecute($strQuery,true);

   //  If this is a brand new theme, we need to set up the directories, config file,
   //     and new database table for settings
   if ($_POST["ThemeCode"] == '') {
      $savedir = getcwd();

      chdir($GLOBALS["rootdp"].$GLOBALS["themes_home"]);
      //  Create the theme-specific config file
      $fullfilename = 'config.'.$_POST["themecode"].'.php';
      $fp = fopen($fullfilename, "wb");
      fwrite($fp,'<?php'.chr(10).chr(10));
      fwrite($fp,'// ezContents Table Names'.chr(10));
      fwrite($fp,'$GLOBALS["eztbThemePrefix"]'.chr(9).'= "'.$EZ_SESSION_VARS["Site"].$_POST["themecode"].'";'.chr(10));
      fwrite($fp,'$GLOBALS["eztbSettings"]'.chr(9).'= $GLOBALS["eztbThemePrefix"]."settings";'.chr(10));
      fwrite($fp,chr(10).'?>'.chr(10));
      fclose($fp);


      //  Create a new directory under the multi-theme home directory
      //  If a directory doesn't exist for a theme, then we create it
      if ((!file_exists($_POST["themecode"])) || (!is_dir($_POST["themecode"]))) { mkdir ($_POST["themecode"], 0777); }
      chdir($_POST["themecode"]);
      chdir($savedir);


      //  Create the data tables for this theme
      //  For each table, we generate a 'create' definition from the existing table,
      //      then simply modify the table name for the new theme and execute the script.
      //  SETTINGS
      $dbString  = dbTableDef($GLOBALS["eztbSettings"]);
      $sqlString = str_replace ($GLOBALS["eztbSettings"], $EZ_SESSION_VARS["Site"].$_POST["themecode"]."settings", $dbString);
      $result = dbExecute($sqlString,true);

      //  Copy all settings from the current master theme
      $sqlString = "INSERT INTO ".$EZ_SESSION_VARS["Site"].$_POST["themecode"]."settings SELECT * FROM ".$GLOBALS["eztbSettings"];
      $r = dbExecute($sqlString,true);

      $EZ_SESSION_VARS["Theme"] = $_POST["themecode"];
      RebuildStyleSheet();
   }

   dbCommit();
} // function AddTheme()


function bCheckForm()
{
   global $_POST;

   $bFormOK = true;
   if ($_POST["ThemeCode"] == '') {
      if ($_POST["themecode"] == "") {
         $GLOBALS["strErrors"][] = $GLOBALS["eNoCode"];
      } elseif ($_POST["themecode"] <> urlencode($_POST["themecode"])) {
         $GLOBALS["strErrors"][] = $GLOBALS["eInvalidCode"];
      } elseif ($_POST["themecode"] == $GLOBALS["gsThemeCode"]) {
         $GLOBALS["strErrors"][] = $GLOBALS["eMasterCode"];
      } else {
         $strQuery="SELECT * FROM ".$GLOBALS["eztbThemes"]." WHERE themecode='".$_POST["themecode"]."'";
         $sresult = dbRetrieve($strQuery,true,0,0);
         $sRecCount = dbRowsReturned($sresult);
         dbFreeResult($sresult);
         if ($sRecCount <> 0) { $GLOBALS["strErrors"][] = $GLOBALS["eCodeInUse"];
         } else {
	         $strQuery="SELECT * FROM ".$GLOBALS["eztbSites"]." WHERE sitecode='".$_POST["themecode"]."'";
	         $sresult = dbRetrieve($strQuery,true,0,0);
	         $sRecCount = dbRowsReturned($sresult);
	         dbFreeResult($sresult);
	         if ($sRecCount <> 0) { $GLOBALS["strErrors"][] = $GLOBALS["eCodeInUse"]; }
         }
      }
   }
   if ($_POST["themename"] == "")		{ $GLOBALS["strErrors"][] = $GLOBALS["eNoName"]; }
   if ($_POST["themedescription"] == "")	{ $GLOBALS["strErrors"][] = $GLOBALS["eNoDescription"]; }

   if (isset($GLOBALS["strErrors"])) { $bFormOK = false; }
   return $bFormOK;
} // function bCheckForm()


function GetGlobalData()
{
   global $EZ_SESSION_VARS, $_GET, $_POST;

   $strQuery="SELECT * FROM ".$GLOBALS["eztbThemes"]." WHERE themecode='".$_GET["ThemeCode"]."'";
   $result = dbRetrieve($strQuery,true,0,0);
   $rs     = dbFetch($result);

   $GLOBALS["gsThemeCode"]        = $rs["themecode"];
   $GLOBALS["gsThemeName"]        = $rs["themename"];
   $GLOBALS["gsThemeDescription"] = $rs["themedescription"];
   $GLOBALS["gsThemeEnabled"]     = $rs["themeenabled"];
   dbFreeResult($result);
} // function GetGlobalData()


function GetFormData()
{
   global $_POST, $EZ_SESSION_VARS;

   $GLOBALS["gsThemeCode"]        = $_POST["themecode"];
   $GLOBALS["gsThemeName"]        = $_POST["themename"];
   $GLOBALS["gsThemeDescription"] = $_POST["themedescription"];
   $GLOBALS["gsThemeEnabled"]     = $_POST["themeEnabled"];
} // function GetFormData()


include($GLOBALS["rootdp"]."include/javafuncs.php");

?>
