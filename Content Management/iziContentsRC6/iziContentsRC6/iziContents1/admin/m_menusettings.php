<?php

/***************************************************************************

 m_menusettings.php
 -------------------
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
$GLOBALS["form"] = 'menusettings';
$validaccess = VerifyAdminLogin2();

includeLanguageFiles('admin','menusettings');


if ($_POST["submitted"] == "yes") {
   // User has submitted the data
   if (bCheckForm()) {
      AdjustSettings();
      Header("Location: ".BuildLink('start.php'));
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
   adminformheader();
   if ($GLOBALS["gsShowTopMenu"] == 'Y') {
      adminformopen('topmenuborder');
   } else {
      adminformopen('menuborder');
   }
   adminformtitle(2,$GLOBALS["tFormTitle"]);
   if (isset($GLOBALS["strErrors"])) { formError(2); }
   if ($GLOBALS["gsShowTopMenu"] == 'Y') {
      adminsubheader(2,$GLOBALS["thTopMenu"]);
      ?>
      <tr class="tablecontent">
          <?php FieldHeading("TopMenuBorder","topmenuborder"); ?>
          <td valign="top" class="content">
              <input type="text" name="topmenuborder" size="3" value="<?php echo $GLOBALS["fsTopMenuBorder"]; ?>" maxlength="3"<?php echo $GLOBALS["fieldstatus"]; ?>>
          </td>
      </tr>
      <tr class="tablecontent">
          <?php FieldHeading("TopMenuAlign",1); ?>
          <td valign="top" class="content">
              <input type="radio" value="L" name="topmenualign" <?php if($GLOBALS["fsTopMenuAlign"] == "L" || $GLOBALS["fsTopMenuAlign"] == "") echo "checked" ?><?php echo $GLOBALS["fieldstatus"]; ?>><?php echo $GLOBALS["tLeft"]; ?><br />
              <input type="radio" value="C" name="topmenualign" <?php if($GLOBALS["fsTopMenuAlign"] == "C") echo "checked" ?><?php echo $GLOBALS["fieldstatus"]; ?>><?php echo $GLOBALS["tCentre"]; ?><br />
              <input type="radio" value="R" name="topmenualign" <?php if($GLOBALS["fsTopMenuAlign"] == "R") echo "checked" ?><?php echo $GLOBALS["fieldstatus"]; ?>><?php echo $GLOBALS["tRight"]; ?><br />
              <input type="radio" value="J" name="topmenualign" <?php if($GLOBALS["fsTopMenuAlign"] == "J") echo "checked" ?><?php echo $GLOBALS["fieldstatus"]; ?>><?php echo $GLOBALS["tJustify"]; ?>
          </td>
      </tr>
      <tr class="tablecontent">
          <?php FieldHeading("TopMenuRows","topmenurows"); ?>
          <td valign="top" class="content">
              <input type="text" name="topmenurows" size="3" value="<?php echo $GLOBALS["fsTopMenuRows"]; ?>" maxlength="3"<?php echo $GLOBALS["fieldstatus"]; ?>>
          </td>
      </tr>
      <tr class="tablecontent">
          <?php FieldHeading("TopMenuSeparator","topmenuseparator"); ?>
          <td valign="top" class="content">
              <select name="topmenuseparator" size="1"<?php echo $GLOBALS["fieldstatus"]; ?>><?php RenderSeparators($GLOBALS["fsTopMenuSeparator"]); ?>
              </select>
          </td>
      </tr>
      <tr class="tablecontent">
          <?php FieldHeading("ShowDHTML","showdhtml"); ?>
          <td valign="top" class="content">
              <input type="checkbox" value="Y" name="showdhtml" <?php if($GLOBALS["fsShowDHTML"] == "Y") echo "checked" ?><?php echo $GLOBALS["fieldstatus"]; ?>>
          </td>
      </tr>
      <?php
   } else {
      ?>
      <input type="hidden" name="topmenuborder" value="<?php echo $GLOBALS["fsTopMenuBorder"]; ?>">
      <input type="hidden" name="topmenualign" value="<?php echo $GLOBALS["fsTopMenuAlign"]; ?>">
      <input type="hidden" name="topmenurows" value="<?php echo $GLOBALS["fsTopMenuRows"]; ?>">
      <input type="hidden" name="topmenuseparator" value="<?php echo $GLOBALS["fsTopMenuSeparator"]; ?>">
      <?php
   }
   adminsubheader(2,$GLOBALS["thSideMenu"]);
   ?>
   <tr class="tablecontent">
       <?php FieldHeading("MenuBorder","menuborder"); ?>
       <td valign="top" class="content">
           <input type="text" name="menuborder" size="3" value="<?php echo $GLOBALS["fsMenuBorder"]; ?>" maxlength="3"<?php echo $GLOBALS["fieldstatus"]; ?>>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("TopDistance","menudistance1"); ?>
       <td valign="top" class="content">
           <input type="text" name="menudistance1" size="3" value="<?php echo $GLOBALS["fsMenuDistance1"]; ?>" maxlength="7"<?php echo $GLOBALS["fieldstatus"]; ?>>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("BetweenDistance1","menudistance2"); ?>
       <td valign="top" class="content">
           <input type="text" name="menudistance2" size="3" value="<?php echo $GLOBALS["fsMenuDistance2"]; ?>" maxlength="7"<?php echo $GLOBALS["fieldstatus"]; ?>>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("BetweenDistance2","menudistance3"); ?>
       <td valign="top" class="content">
           <input type="text" name="menudistance3" size="3" value="<?php echo $GLOBALS["fsMenuDistance3"]; ?>" maxlength="7"<?php echo $GLOBALS["fieldstatus"]; ?>>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("Indent","menudistance4"); ?>
       <td valign="top" class="content">
           <input type="text" name="menudistance4" size="3" value="<?php echo $GLOBALS["fsMenuDistance4"]; ?>" maxlength="7"<?php echo $GLOBALS["fieldstatus"]; ?>>
       </td>
   </tr>
   <?php adminsubheader(2,$GLOBALS["thAccess"]); ?>
   <tr class="tablecontent">
       <?php FieldHeading("PrivateMenus",1); ?>
       <td valign="top" class="content">
           <input type="radio" value="H" name="privatemenus" <?php if($GLOBALS["fsPrivateMenus"] == "H" || $GLOBALS["fsPrivateMenus"] == "") echo "checked" ?><?php echo $GLOBALS["fieldstatus"]; ?>><?php echo $GLOBALS["tHidden"]; ?><br />
           <input type="radio" value="L" name="privatemenus" <?php if($GLOBALS["fsPrivateMenus"] == "L") echo "checked" ?><?php echo $GLOBALS["fieldstatus"]; ?>><?php echo $GLOBALS["tLocked"]; ?>
       </td>
   </tr>
   <?php
   adminformsavebar(2,'start.php');
   if ($GLOBALS["specialedit"] == True) { adminhelpmsg(2); }
   adminformclose();
} // function frmSettingsForm()


function GetFormData()
{
   global $_POST;

   $GLOBALS["fsTopMenuBorder"]    = $_POST["topmenuborder"];
   $GLOBALS["fsTopMenuAlign"]     = $_POST["topmenualign"];
   $GLOBALS["fsTopMenuRows"]      = $_POST["topmenurows"];
   $GLOBALS["fsTopMenuSeparator"] = $_POST["topmenuseparator"];
   $GLOBALS["fsMenuBorder"]       = $_POST["menuborder"];
   $GLOBALS["fsMenuDistance1"]    = $_POST["menudistance1"];
   $GLOBALS["fsMenuDistance2"]    = $_POST["menudistance2"];
   $GLOBALS["fsMenuDistance3"]    = $_POST["menudistance3"];
   $GLOBALS["fsMenuDistance4"]    = $_POST["menudistance4"];
   $GLOBALS["fsPrivateMenus"]     = $_POST["privatemenus"];
   $GLOBALS["fsShowDHTML"]      = $_POST["showdhtml"];
} // function GetFormData()


function GetGlobalData()
{
   $GLOBALS["fsTopMenuBorder"]    = $GLOBALS["gsTopMenuBorder"];
   $GLOBALS["fsTopMenuAlign"]     = $GLOBALS["gsTopMenuAlign"];
   $GLOBALS["fsTopMenuRows"]      = $GLOBALS["gsTopMenuRows"];
   $GLOBALS["fsTopMenuSeparator"] = $GLOBALS["gsTopMenuSeparator"];
   $GLOBALS["fsMenuBorder"]       = $GLOBALS["gsMenuBorder"];
   $GLOBALS["fsMenuDistance1"]    = $GLOBALS["gsMenuDistance1"];
   $GLOBALS["fsMenuDistance2"]    = $GLOBALS["gsMenuDistance2"];
   $GLOBALS["fsMenuDistance3"]    = $GLOBALS["gsMenuDistance3"];
   $GLOBALS["fsMenuDistance4"]    = $GLOBALS["gsMenuDistance4"];
   $GLOBALS["fsPrivateMenus"]     = $GLOBALS["gsPrivateMenus"];
   $GLOBALS["fsShowDHTML"]      = $GLOBALS["gsShowMouseover"];
} // function GetGlobalData()


function AdjustSettings()
{
   global $_POST;

   $cssSettingsModified = False;
   if (UpdateSetting($_POST["topmenuborder"],'topmenuborder'))		{ $cssSettingsModified = True; }
   if (UpdateSetting($_POST["topmenualign"],'topmenualign'))		{ $cssSettingsModified = True; }
   if (UpdateSetting($_POST["topmenurows"],'topmenurows'))		{ $cssSettingsModified = True; }
   if (UpdateSetting($_POST["topmenuseparator"],'topmenuseparator'))	{ $cssSettingsModified = True; }
   if (UpdateSetting($_POST["menuborder"],'menuborder'))		{ $cssSettingsModified = True; }
   if (UpdateSetting($_POST["menudistance1"],'menudistance1'))		{ $cssSettingsModified = True; }
   if (UpdateSetting($_POST["menudistance2"],'menudistance2'))		{ $cssSettingsModified = True; }
   if (UpdateSetting($_POST["menudistance3"],'menudistance3'))		{ $cssSettingsModified = True; }
   if (UpdateSetting($_POST["menudistance4"],'menudistance4'))		{ $cssSettingsModified = True; }
   if (UpdateSetting($_POST["privatemenus"],'privatemenus'))		{ $cssSettingsModified = True; }
   if (UpdateSetting($_POST["showdhtml"],'showdhtml'))		{ $cssSettingsModified = True; }
   dbCommit();

   if ($cssSettingsModified) { RebuildStyleSheet(); }
} // function AdjustSettings()


function bCheckForm()
{
   global $_POST;

   $bFormOK = true;
   if ($GLOBALS["gsShowTopMenu"] == 'Y') {
      if (!is_numeric($_POST["topmenuborder"]))	{ $GLOBALS["strErrors"][] = $GLOBALS["eBorder1"]; }
      if (!is_numeric($_POST["topmenurows"]))		{ $GLOBALS["strErrors"][] = $GLOBALS["eRows1"]; }
   }
   if (!is_numeric($_POST["menuborder"]))		{ $GLOBALS["strErrors"][] = $GLOBALS["eBorder2"]; }
   if (!is_numeric($_POST["menudistance1"]))		{ $GLOBALS["strErrors"][] = $GLOBALS["eDistance1"]; }
   if (!is_numeric($_POST["menudistance2"]))		{ $GLOBALS["strErrors"][] = $GLOBALS["eDistance2"]; }
   if (!is_numeric($_POST["menudistance3"]))		{ $GLOBALS["strErrors"][] = $GLOBALS["eDistance3"]; }
   if (!is_numeric($_POST["menudistance4"]))		{ $GLOBALS["strErrors"][] = $GLOBALS["eDistance4"]; }

   if (isset($GLOBALS["strErrors"])) { $bFormOK = false; }
   return $bFormOK;
} // function bCheckForm()


function RenderSeparators($sSeparator)
{
   $separators = array('', '[ ? ] [ ? ]', '[ ? | ? ]', '[ ? - ? ]', '{ ? } { ? }', '{ ? | ? }', '{ ? - ? }', '? | ?', '- ? - ? -', '? - ?', '? : ?');

   while($separator = each($separators)) {
      echo '<option value="'.$separator[1].'"';
      if($sSeparator == $separator[1]) { echo " selected"; }
      $dseparator = str_replace("?", $GLOBALS["tiMenuTitle"], $separator[1]);
      echo ">".$dseparator."</option>\n";
   }
} // function RenderFonts()


include($GLOBALS["rootdp"]."include/javafuncs.php");

?>
