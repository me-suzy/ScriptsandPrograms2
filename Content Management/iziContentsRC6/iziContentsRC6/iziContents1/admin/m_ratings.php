<?php

/***************************************************************************

 m_ratings.php
 --------------
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
$GLOBALS["form"] = 'ratings';
$validaccess = VerifyAdminLogin2();

includeLanguageFiles('admin','ratings');


if ($_POST["submitted"] == "yes") {
   // User has submitted the data
   if (bCheckForm()) {
      AdjustSettings();
      Header("Location: ".BuildLink('start.php'));
   } else {
      GetFormData();
   }
} else {
   GetGlobalData();
}
frmRatingsForm();


function frmRatingsForm()
{
   adminformheader();
   adminformopen('tvallowratings');
   adminformtitle(4,$GLOBALS["tFormTitle"]);
   if (isset($GLOBALS["strErrors"])) { formError(4); }
   adminsubheader(4,$GLOBALS["thContentRatings"]);
   ?>
   <tr class="tablecontent">
       <?php FieldHeading("AllowRatings","tvallowratings"); ?></td>
       <td colspan="3" valign="top" class="content">
           <select name="tvallowratings" size="1"<?php echo $GLOBALS["fieldstatus"]; ?>>
               <option value="Y" <?php if($GLOBALS["fsAllowRatings"] == "Y") echo "selected"; ?>><?php echo $GLOBALS["tYes"]; ?>
               <option value="N" <?php if($GLOBALS["fsAllowRatings"] != "Y") echo "selected"; ?>><?php echo $GLOBALS["tNo"]; ?>
           </select>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("RatingMin","tvratingmin"); ?>
       <td valign="top" class="content">
           <select name="tvratingmin"<?php echo $GLOBALS["fieldstatus"]; ?>><?php RenderRatings(-5,1,$GLOBALS["fsRatingMin"]) ?></select>
       </td>
       <?php FieldHeading("RatingMax","tvratingmax"); ?>
       <td valign="top" class="content">
           <select name="tvratingmax"<?php echo $GLOBALS["fieldstatus"]; ?>><?php RenderRatings(0,10,$GLOBALS["fsRatingMax"]) ?></select>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("RatingImage1","tvratingimage1"); ?>
       <td colspan="3" valign=top>
           <input type="text" name="tvratingimage1" size="64" value="<?php echo $GLOBALS["fsRatingImage1"]; ?>" maxlength="255"<?php echo $GLOBALS["fieldstatus"]; ?>>
           <?php adminimagedisplay('tvratingimage1',$GLOBALS["fsRatingImage1"],$GLOBALS["tShowImage"]); ?>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("RatingImage2","tvratingimage2"); ?>
       <td colspan="3" valign=top>
           <input type="text" name="tvratingimage2" size="64" value="<?php echo $GLOBALS["fsRatingImage2"]; ?>" maxlength="255"<?php echo $GLOBALS["fieldstatus"]; ?>>
           <?php adminimagedisplay('tvratingimage2',$GLOBALS["fsRatingImage2"],$GLOBALS["tShowImage"]); ?>
       </td>
   </tr>
   <?php adminsubheader(4,$GLOBALS["thContentComments"]); ?>
   <tr class="tablecontent">
       <?php FieldHeading("AllowComments","tvallowcomments"); ?></td>
       <td colspan="3" valign="top" class="content">
           <select name="tvallowcomments" size="1"<?php echo $GLOBALS["fieldstatus"]; ?>>
               <option value="Y" <?php if($GLOBALS["fsAllowComments"] == "Y") echo "selected"; ?>><?php echo $GLOBALS["tYes"]; ?>
               <option value="N" <?php if($GLOBALS["fsAllowComments"] != "Y") echo "selected"; ?>><?php echo $GLOBALS["tNo"]; ?>
           </select>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("VetComments","tvvetcomments"); ?>
       <td colspan="3" valign="top" class="content">
           <select name="tvvetcomments" size="1"<?php echo $GLOBALS["fieldstatus"]; ?>>
               <option value="Y" <?php if($GLOBALS["fsVetComments"] == "Y") echo "selected"; ?>><?php echo $GLOBALS["tYes"]; ?>
               <option value="N" <?php if($GLOBALS["fsVetComments"] != "Y") echo "selected"; ?>><?php echo $GLOBALS["tNo"]; ?>
           </select>
       </td>
   </tr>
   <?php adminsubheader(4,$GLOBALS["thPrinterFriendly"]); ?>
   <tr class="tablecontent">
       <?php FieldHeading("PrintFriendly","tvprinterfriendly"); ?>
       <td colspan="3" valign="top" class="content">
           <select name="tvprinterfriendly" size="1"<?php echo $GLOBALS["fieldstatus"]; ?>>
               <option value="Y" <?php if($GLOBALS["fsPrinterFriendly"] == "Y") echo "selected"; ?>><?php echo $GLOBALS["tYes"]; ?>
               <option value="N" <?php if($GLOBALS["fsPrinterFriendly"] != "Y") echo "selected"; ?>><?php echo $GLOBALS["tNo"]; ?>
           </select>
       </td>
   </tr>
   <?php adminsubheader(4,$GLOBALS["thContentSettings"]); ?>
   <tr class="tablecontent">
       <?php FieldHeading("TeaserWithDetails","tvteaserwithdetails"); ?>
       <td colspan="3" valign="top" class="content">
           <select name="tvteaserwithdetails" size="1"<?php echo $GLOBALS["fieldstatus"]; ?>>
               <option value="Y" <?php if($GLOBALS["fsTeaserWithDetails"] == "Y") echo "selected"; ?>><?php echo $GLOBALS["tYes"]; ?>
               <option value="N" <?php if($GLOBALS["fsTeaserWithDetails"] != "Y") echo "selected"; ?>><?php echo $GLOBALS["tNo"]; ?>
           </select>
       </td>
   </tr>
   <?php
   adminformsavebar(4,'start.php');
   if ($GLOBALS["specialedit"] == True) {
      adminhelpmsg(4);
      ?><input type="hidden" name="prevtopmenu" value="<?php echo $GLOBALS["fsShowTopMenu"]; ?>"><?php
   }
   adminformclose();
} // function frmRatingsForm()


function AdjustSettings()
{
   global $_POST;

   $cssSettingsModified = False;
   if (UpdateSetting($_POST["tvallowratings"],'allowratings'))			{ $cssSettingsModified = True; }
   if (UpdateSetting($_POST["tvallowcomments"],'allowcomments'))		{ $cssSettingsModified = True; }
   if (UpdateSetting($_POST["tvratingmin"],'ratingmin'))			{ $cssSettingsModified = True; }
   if (UpdateSetting($_POST["tvratingmax"],'ratingmax'))			{ $cssSettingsModified = True; }
   if (UpdateSetting($_POST["tvratingimage1"],'ratingimage1'))			{ $cssSettingsModified = True; }
   if (UpdateSetting($_POST["tvratingimage2"],'ratingimage2'))			{ $cssSettingsModified = True; }
   if (UpdateSetting($_POST["tvvetcomments"],'vetcomments'))			{ $cssSettingsModified = True; }
   if (UpdateSetting($_POST["tvprinterfriendly"],'printerfriendly'))		{ $cssSettingsModified = True; }
   if (UpdateSetting($_POST["tvteaserwithdetails"],'teaserwithdetails'))	{ $cssSettingsModified = True; }
   dbCommit();

   if ($cssSettingsModified) { RebuildStyleSheet(); }
} // function AdjustSettings()


function GetFormData()
{
   global $_POST;

   $GLOBALS["fsAllowRatings"]      = $_POST["tvallowratings"];
   $GLOBALS["fsAllowComments"]     = $_POST["tvallowcomments"];
   $GLOBALS["fsRatingMin"]         = $_POST["tvratingmin"];
   $GLOBALS["fsRatingMax"]         = $_POST["tvratingmax"];
   $GLOBALS["fsRatingImage1"]      = $_POST["tvratingimage1"];
   $GLOBALS["fsRatingImage2"]      = $_POST["tvratingimage2"];
   $GLOBALS["fsVetComments"]       = $_POST["tvvetcomments"];
   $GLOBALS["fsPrinterFriendly"]   = $_POST["tvprinterfriendly"];
   $GLOBALS["fsTeaserWithDetails"] = $_POST["tvteaserwithdetails"];
} // function GetFormData()


function GetGlobalData()
{
   $GLOBALS["fsAllowRatings"]      = $GLOBALS["gsAllowRatings"];
   $GLOBALS["fsAllowComments"]     = $GLOBALS["gsAllowComments"];
   $GLOBALS["fsRatingMin"]         = $GLOBALS["gsRatingMin"];
   $GLOBALS["fsRatingMax"]         = $GLOBALS["gsRatingMax"];
   $GLOBALS["fsRatingImage1"]      = $GLOBALS["gsRatingImage1"];
   $GLOBALS["fsRatingImage2"]      = $GLOBALS["gsRatingImage2"];
   $GLOBALS["fsVetComments"]       = $GLOBALS["gsVetComments"];
   $GLOBALS["fsPrinterFriendly"]   = $GLOBALS["gsPrinterFriendly"];
   $GLOBALS["fsTeaserWithDetails"] = $GLOBALS["gsTeaserWithDetails"];
} // function GetGlobalData()


function bCheckForm() {
   $bFormOK = true;

   if (isset($GLOBALS["strErrors"])) { $bFormOK = false; }
   return $bFormOK;
} // function bCheckForm()


function RenderRatings($startval,$endval,$ratingval)
{
   for ($i=$startval; $i<=$endval; $i++)
   {
      echo "<option";
      if ($ratingval == $i) { echo " selected"; }
      echo ">".$i."\n";
   }
} // function RenderRatings()

include($GLOBALS["rootdp"]."include/javafuncs.php");
?>


