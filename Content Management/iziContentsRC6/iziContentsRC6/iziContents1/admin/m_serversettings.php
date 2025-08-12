<?php

/***************************************************************************

 m_serversettings.php
 ---------------------
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
$GLOBALS["form"] = 'serversettings';
$validaccess = VerifyAdminLogin2();

includeLanguageFiles('admin','serversettings');


$ImageFileTypes[] = array('gif', 'jpg', 'jpeg', 'png');

$GLOBALS["tabindex"] = 1024;
if ($_POST["submitted"] == "yes") {
   // User has submitted the data
   if (bCheckForm()) {
      AdjustSettings();
      Header("Location: ".BuildLink('start.php')."&RefreshMenu=styles");
   } else {
      // Invalid data has been submitted
      GetFormData();
   }
} else {
   // First visit to the form
   GetGlobalData();
}
frmSettingsForm();


function frmSettingsForm()
{
   global $EZ_SESSION_VARS;

   adminformheader();
   adminformopen('tvuse_compression');
   adminformtitle(4,$GLOBALS["tFormTitle"]);
   if (isset($GLOBALS["strErrors"])) { formError(4); }
   adminsubheader(4,$GLOBALS["thServerOptions"]);
   ?>
   <tr class="tablecontent">
       <?php FieldHeading("GzipSetting","tvuse_compression"); ?>
       <td valign="top" class="content">
           <select name="tvuse_compression" size="1"<?php echo $GLOBALS["fieldstatus"]; ?>>
               <option value="Y" <?php if($GLOBALS["fsUse_compression"] == "Y") echo "selected"; ?>><?php echo $GLOBALS["tGzipCompression"]; ?>
               <option value="N" <?php if($GLOBALS["fsUse_compression"] != "Y") echo "selected"; ?>><?php echo $GLOBALS["tGzipNoCompression"]; ?>
           </select>
           <?php
           if ($GLOBALS["canedit"] == True) {
               ?>
               <br /><span style="cursor:help"><a OnClick='GZipHelp();' class="small"><?php echo $GLOBALS["tGzipTest"]; ?></a></span>
               <?php
           }
           ?>
       </td>
       <?php FieldHeading("SecureServer","tvsecureserver"); ?>
       <td valign="top" class="content">
           <input type="checkbox" name="tvsecureserver" value="Y" <?php if ($GLOBALS["fsSecureServer"] == 'Y') echo "checked"; ?><?php echo $GLOBALS["fieldstatus"]; ?>>
       </td>
   </tr>
   <?php
   if ($EZ_SESSION_VARS["Site"] == '') {
      ?>
      <tr class="tablecontent">
          <?php FieldHeading("MultiSite","tvmultisite"); ?>
          <td valign="top" class="content">
              <select name="tvmultisite" size="1"<?php echo $GLOBALS["fieldstatus"]; ?>>
                  <option value="Y" <?php if ($GLOBALS["fsMultiSite"] == "Y") echo "selected"; ?>><?php echo $GLOBALS["tYes"]; ?>
                  <option value="N" <?php if ($GLOBALS["fsMultiSite"] != "Y") echo "selected"; ?>><?php echo $GLOBALS["tNo"]; ?>
              </select>
          </td>
          <?php FieldHeading("MultiSiteAuthors","tvmultisiteauthors"); ?>
          <td valign="top" class="content">
              <select name="tvmultisiteauthors" size="1"<?php echo $GLOBALS["fieldstatus"]; ?>>
                  <option value="Y" <?php if ($GLOBALS["fsMultiSiteAuthors"] == "Y") echo "selected"; ?>><?php echo $GLOBALS["tYes"]; ?>
                  <option value="N" <?php if ($GLOBALS["fsMultiSiteAuthors"] != "Y") echo "selected"; ?>><?php echo $GLOBALS["tNo"]; ?>
              </select>
          </td>
      </tr>
      <?php
   } else {
      ?><input type="hidden" name="tvmultisite" value="<?php echo $GLOBALS["fsMultiSite"]; ?>"><?php
      ?><input type="hidden" name="tvmultisiteauthors" value="<?php echo $GLOBALS["fsMultiSiteAuthors"]; ?>"><?php
   }
   ?>
   <tr class="tablecontent">
       <?php FieldHeading("MultiLanguage","tvmultilanguage"); ?>
       <td colspan="3" valign="top" class="content">
           <select name="tvmultilanguage" size="1"<?php echo $GLOBALS["fieldstatus"]; ?>>
               <option value="Y" <?php if($GLOBALS["fsMultiLanguage"] == "Y") echo "selected"; ?>><?php echo $GLOBALS["tYes"]; ?>
               <option value="N" <?php if($GLOBALS["fsMultiLanguage"] != "Y") echo "selected"; ?>><?php echo $GLOBALS["tNo"]; ?>
           </select>
       </td>
   </tr>
   <?php
   if ($EZ_SESSION_VARS["Theme"] == '') {
      ?>
      <tr class="tablecontent">
          <?php FieldHeading("MultiTheme","tvmultitheme"); ?>
          <td colspan="3" valign="top" class="content">
              <select name="tvmultitheme" size="1"<?php echo $GLOBALS["fieldstatus"]; ?>>
                  <option value="Y" <?php if($GLOBALS["fsMultiTheme"] == "Y") echo "selected"; ?>><?php echo $GLOBALS["tYes"]; ?>
                  <option value="N" <?php if($GLOBALS["fsMultiTheme"] != "Y") echo "selected"; ?>><?php echo $GLOBALS["tNo"]; ?>
              </select>
          </td>
      </tr>
      <?php
   } else {
      ?><input type="hidden" name="tvmultitheme" value="<?php echo $GLOBALS["fsMultiTheme"]; ?>"><?php
   }
   ?>
   <tr class="tablecontent">
       <?php FieldHeading("PageTimer","tvtimegen_display"); ?>
       <td colspan="3" valign="top" class="content">
           <select name="tvtimegen_display" size="1"<?php echo $GLOBALS["fieldstatus"]; ?>>
               <option value="Y" <?php if($GLOBALS["fsTimegen_display"] == "Y") echo "selected"; ?>><?php echo $GLOBALS["tTimerDisplay"]; ?>
               <option value="N" <?php if($GLOBALS["fsTimegen_display"] != "Y") echo "selected"; ?>><?php echo $GLOBALS["tTimerNoDisplay"]; ?>
           </select>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("DateFormat","tvdateformat"); ?>
       <td valign="top" class="content">
           <input type="text" name="tvdateformat" size="16" value="<?php echo $GLOBALS["fsDateFormat"]; ?>" maxlength="32"<?php echo $GLOBALS["fieldstatus"]; ?>>
       </td>
       <?php FieldHeading("Timezone","tvtimezone"); ?>
       <td valign="top" class="content">
           <input type="text" name="tvtimezone" size="6" value="<?php echo $GLOBALS["fsTimezone"]; ?>" maxlength="6"<?php echo $GLOBALS["fieldstatus"]; ?>>
       </td>
   </tr>
   <?php adminsubheader(4,$GLOBALS["thezContentsOptions"]); ?>
   <tr class="tablecontent">
       <?php FieldHeading("DefaultLanguage","tvdefault_language"); ?>
       <td colspan="3" valign="top" class="content">
           <select name="tvdefault_language"<?php echo $GLOBALS["fieldstatus"]; ?>>
               <?php RenderLanguages($GLOBALS["fsDefault_language"]) ?>
           </select>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("FrameSetting","tvuseframes"); ?></td>
       <td colspan="3" valign="top" class="content">
           <select name="tvuseframes" size="1"<?php echo $GLOBALS["fieldstatus"]; ?>>
               <option value="Y" <?php if($GLOBALS["fsFrames"] == "Y") echo "selected"; ?>><?php echo $GLOBALS["tFrames"]; ?>
               <option value="N" <?php if($GLOBALS["fsFrames"] != "Y") echo "selected"; ?>><?php echo $GLOBALS["tNoFrames"]; ?>
           </select>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("VisitorStats","tvvisitorstats"); ?>
       <td colspan="3" valign="top" class="content">
           <input type="checkbox" name="tvvisitorstats" value="Y" <?php if ($GLOBALS["fsVisitorStats"] == 'Y') echo "checked"; ?><?php echo $GLOBALS["fieldstatus"]; ?>>
       </td>
   </tr>
   <?php
   adminformsavebar(4,'start.php');
   if ($GLOBALS["specialedit"] == True) {
      adminhelpmsg(4);
      ?><input type="hidden" name="prevtopmenu" value="<?php echo $GLOBALS["fsShowTopMenu"]; ?>"><?php
   }
   adminformclose();
} // function frmSettingsForm()


function AdjustSettings()
{
   global $_POST;

   $cssSettingsModified = False;
   if (UpdateSetting($_POST["tvuse_compression"],'use_compression'))	{ $cssSettingsModified = True; }
   if (UpdateSetting($_POST["tvsecureserver"],'secureserver'))		{ $cssSettingsModified = True; }
   if (UpdateSetting($_POST["tvmultisite"],'multisite'))		{ $cssSettingsModified = True; }
   if (UpdateSetting($_POST["tvmultisiteauthors"],'multisiteauthors'))	{ $cssSettingsModified = True; }
   if (UpdateSetting($_POST["tvmultilanguage"],'multilanguage'))	{ $cssSettingsModified = True; }
   if (UpdateSetting($_POST["tvmultitheme"],'multitheme'))		{ $cssSettingsModified = True; }
   if (UpdateSetting($_POST["tvtimegen_display"],'timegen_display'))	{ $cssSettingsModified = True; }
   if (UpdateSetting($_POST["tvdefault_language"],'default_language'))	{ $cssSettingsModified = True; }
   if (UpdateSetting($_POST["tvdateformat"],'dateformat'))		{ $cssSettingsModified = True; }
   if (UpdateSetting($_POST["tvtimezone"],'timezone'))			{ $cssSettingsModified = True; }
   if (UpdateSetting($_POST["tvuseframes"],'useframes'))		{ $cssSettingsModified = True; }
   if (UpdateSetting($_POST["tvvisitorstats"],'visitorstats'))		{ $cssSettingsModified = True; }
   dbCommit();

   if ($cssSettingsModified) { RebuildStyleSheet(); }
} // function AdjustSettings()


function GetFormData()
{
   global $_POST;

   $GLOBALS["fsUse_compression"]  = $_POST["tvuse_compression"];
   $GLOBALS["fsSecureServer"]     = $_POST["tvsecureserver"];
   $GLOBALS["fsMultiSite"]        = $_POST["tvmultisite"];
   $GLOBALS["fsMultiSiteAuthors"] = $_POST["tvmultisiteauthors"];
   $GLOBALS["fsMultiLanguage"]    = $_POST["tvmultilanguage"];
   $GLOBALS["fsMultiTheme"]       = $_POST["tvmultitheme"];
   $GLOBALS["fsTimegen_display"]  = $_POST["tvtimegen_display"];
   $GLOBALS["fsDefault_language"] = $_POST["tvdefault_language"];
   $GLOBALS["fsDateFormat"]       = $_POST["tvdateformat"];
   $GLOBALS["fsTimezone"]         = $_POST["tvtimezone"];
   $GLOBALS["fsFrames"]           = $_POST["tvuseframes"];
   $GLOBALS["fsVisitorStats"]     = $_POST["tvvisitorstats"];
} // function GetFormData()


function GetGlobalData()
{
   $GLOBALS["fsUse_compression"]  = $GLOBALS["gsUse_compression"];
   $GLOBALS["fsSecureServer"]     = $GLOBALS["gsSecureServer"];
   $GLOBALS["fsMultiSite"]        = $GLOBALS["gsMultiSite"];
   $GLOBALS["fsMultiSiteAuthors"] = $GLOBALS["gsMultiSiteAuthors"];
   $GLOBALS["fsMultiLanguage"]    = $GLOBALS["gsMultiLanguage"];
   $GLOBALS["fsMultiTheme"]       = $GLOBALS["gsMultiTheme"];
   $GLOBALS["fsTimegen_display"]  = $GLOBALS["gsTimegen_display"];
   $GLOBALS["fsDefault_language"] = $GLOBALS["gsDefault_language"];
   $GLOBALS["fsDateFormat"]       = $GLOBALS["gsDateFormat"];
   $GLOBALS["fsTimezone"]         = $GLOBALS["gsTimezone"];
   $GLOBALS["fsFrames"]           = $GLOBALS["gsUseFrames"];
   $GLOBALS["fsVisitorStats"]     = $GLOBALS["gsVisitorStats"];
} // function GetGlobalData()


function bCheckForm()
{
   $bFormOK = true;

   if (isset($GLOBALS["strErrors"])) { $bFormOK = false; }
   return $bFormOK;
} // function bCheckForm()


function RenderLanguages($languagecode)
{
   $sqlQuery = "SELECT * FROM ".$GLOBALS["eztbLanguages"]." WHERE enabled='Y' ORDER BY languagename";
   $result = dbRetrieve($sqlQuery,true,0,0);
   while ($rs = dbFetch($result))
   {
      ?><option <?php if($rs["languagecode"] == $languagecode) { echo "selected "; } ?>value="<?php echo $rs["languagecode"]; ?>"><?php echo $rs["languagename"];
   }
   dbFreeResult($result);
} // function RenderLanguages()


include($GLOBALS["rootdp"]."include/javafuncs.php");

?>

<script language="javascript" type="text/javascript">
    <!-- Begin
    function GZipHelp() {
       putFocus('MaintForm','tvuse_compression');
       window.open("<?php echo BuildLink('gzip.php'); ?>", "Help", "width=400,height=250,status=no,resizable=yes,scrollbars=yes");
    }
    //  End -->
</script>
