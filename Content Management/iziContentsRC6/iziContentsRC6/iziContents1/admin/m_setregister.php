<?php

/***************************************************************************

 m_setregister.php
 ------------------
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
$GLOBALS["form"] = 'setregister';
$validaccess = VerifyAdminLogin2();

includeLanguageFiles('admin','setregister');


$GLOBALS["tabindex"] = 1024;

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
   adminformopen('phone_status');
   adminformtitle(2,$GLOBALS["tFormTitle"]);
   if (isset($GLOBALS["strErrors"])) { formError(2); }
   adminsubheader(2,$GLOBALS["thActiveFields"]);
   ?>
   <tr class="tablecontent">
       <?php FieldHeading("PhoneNumber","phone_status"); ?>
       <td valign="top" class="content">
           <select name="phone_status" size="1"<?php echo $GLOBALS["fieldstatus"]; ?>><?php RenderStatus($GLOBALS["fnPhoneStatus"]); ?>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("FaxNumber","fax_status"); ?>
       <td valign="top" class="content">
           <select name="fax_status" size="1"<?php echo $GLOBALS["fieldstatus"]; ?>><?php RenderStatus($GLOBALS["fnFaxStatus"]); ?>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("Address","address_status"); ?>
       <td valign="top" class="content">
           <select name="address_status" size="1"<?php echo $GLOBALS["fieldstatus"]; ?>><?php RenderStatus($GLOBALS["fnAddressStatus"]); ?>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("City","city_status"); ?>
       <td valign="top" class="content">
           <select name="city_status" size="1"<?php echo $GLOBALS["fieldstatus"]; ?>><?php RenderStatus($GLOBALS["fnCityStatus"]); ?>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("State","state_status"); ?>
       <td valign="top" class="content">
           <select name="state_status" size="1"<?php echo $GLOBALS["fieldstatus"]; ?>><?php RenderStatus($GLOBALS["fnStateStatus"]); ?>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("Zip","zip_status"); ?>
       <td valign="top" class="content">
           <select name="zip_status" size="1"<?php echo $GLOBALS["fieldstatus"]; ?>><?php RenderStatus($GLOBALS["fnZipStatus"]); ?>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("Country","country_status"); ?>
       <td valign="top" class="content">
           <select name="country_status" size="1"<?php echo $GLOBALS["fieldstatus"]; ?>><?php RenderStatus($GLOBALS["fnCountryStatus"]); ?>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("Language","language_status"); ?>
       <td valign="top" class="content">
           <select name="language_status" size="1"<?php echo $GLOBALS["fieldstatus"]; ?>><?php RenderStatus($GLOBALS["fnLanguageStatus"]); ?>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("Website","website_status"); ?>
       <td valign="top" class="content">
           <select name="website_status" size="1"<?php echo $GLOBALS["fieldstatus"]; ?>><?php RenderStatus($GLOBALS["fnWebsiteStatus"]); ?>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("Comments","comments_status"); ?>
       <td valign="top" class="content">
           <select name="comments_status" size="1"<?php echo $GLOBALS["fieldstatus"]; ?>><?php RenderStatus($GLOBALS["fnCommentsStatus"]); ?>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("Newsletter","newsletter_status"); ?>
       <td valign="top" class="content">
           <select name="newsletter_status" size="1"<?php echo $GLOBALS["fieldstatus"]; ?>><?php RenderStatus($GLOBALS["fnNewsletterStatus"]); ?>
       </td>
   </tr>
   <?php
   adminsubheader(2,$GLOBALS["thMailSettings"]);
   ?>
   <tr class="tablecontent">
       <?php FieldHeading("SendConfMail","sendconfirmationmail"); ?>
       <td valign="top" class="content">
           <select name="sendconfirmationmail" size="1"<?php echo $GLOBALS["fieldstatus"]; ?>><?php RenderYesNo($GLOBALS["fnSendConfMail"]); ?>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("ServerUserEmail","serveruseremail"); ?>
       <td valign="top" class="content">
           <input type="text" name="serveruseremail" size="30" value="<?php echo $GLOBALS["fsServerUserEmail"]; ?>" maxlength="255"<?php echo $GLOBALS["fieldstatus"]; ?>>
       </td>
   </tr>
   <?php adminsubheader(2,$GLOBALS["thExtraSettings"]); ?>
   <tr class="tablecontent">
       <?php FieldHeading("ErrFormFontColor","errform_font_color"); ?>
       <td valign="top" class="content">
				<?php ColourField('errform_font_color',$GLOBALS["fsErrFormFontColor"]); ?>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("ShowHelptexts","show_helptexts"); ?>
       <td valign="top" class="content">
           <select name="show_helptexts" size="1"<?php echo $GLOBALS["fieldstatus"]; ?>><?php RenderYesNo($GLOBALS["fnShowHelptexts"]); ?>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("HelpTextSize","helptext_fontsize"); ?>
       <td valign="top" class="content">
           <select name="helptext_fontsize" size="1"<?php echo $GLOBALS["fieldstatus"]; ?>><?php RenderSizes($GLOBALS["fsHelptextFontSize"]); ?></select>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("HelpTextColor","helptext_color"); ?>
       <td valign="top" class="content">
				<?php ColourField('helptext_color',$GLOBALS["fsHelptextColor"]); ?>
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

   $GLOBALS["fnPhoneStatus"]    = $_POST["phone_status"];
   $GLOBALS["fnFaxStatus"]     = $_POST["fax_status"];
   $GLOBALS["fnAddressStatus"]      = $_POST["address_status"];
   $GLOBALS["fnCityStatus"] = $_POST["city_status"];
   $GLOBALS["fnStateStatus"]       = $_POST["state_status"];
   $GLOBALS["fnZipStatus"]    = $_POST["zip_status"];
   $GLOBALS["fnCountryStatus"]    = $_POST["country_status"];
   $GLOBALS["fnLanguageStatus"]    = $_POST["language_status"];
   $GLOBALS["fnWebsiteStatus"]    = $_POST["website_status"];
   $GLOBALS["fnCommentsStatus"]    = $_POST["comments_status"];
   $GLOBALS["fnNewsletterStatus"]    = $_POST["newsletter_status"];
   $GLOBALS["fnSendConfMail"]     = $_POST["sendconfirmationmail"];
   $GLOBALS["fsServerUserEmail"]     = $_POST["serveruseremail"];
   $GLOBALS["fsErrFormFontColor"]     = $_POST["errform_font_color"];
   $GLOBALS["fnShowHelptexts"]     = $_POST["show_helptexts"];
   $GLOBALS["fsHelptextFontSize"]     = $_POST["helptext_fontsize"];
   $GLOBALS["fsHelptextColor"]     = $_POST["helptext_color"];
} // function GetFormData()


function GetGlobalData()
{

   $GLOBALS["fnPhoneStatus"]    = $GLOBALS["gsPhoneStatus"];
   $GLOBALS["fnFaxStatus"]     = $GLOBALS["gsFaxStatus"];
   $GLOBALS["fnAddressStatus"]      = $GLOBALS["gsAddressStatus"];
   $GLOBALS["fnCityStatus"] = $GLOBALS["gsCityStatus"];
   $GLOBALS["fnStateStatus"]       = $GLOBALS["gsStateStatus"];
   $GLOBALS["fnZipStatus"]    = $GLOBALS["gsZipStatus"];
   $GLOBALS["fnCountryStatus"]    = $GLOBALS["gsCountryStatus"];
   $GLOBALS["fnLanguageStatus"]    = $GLOBALS["gsLanguageStatus"];
   $GLOBALS["fnWebsiteStatus"]    = $GLOBALS["gsWebsiteStatus"];
   $GLOBALS["fnCommentsStatus"]    = $GLOBALS["gsCommentsStatus"];
   $GLOBALS["fnNewsletterStatus"]    = $GLOBALS["gsNewsletterStatus"];
   $GLOBALS["fnSendConfMail"]     = $GLOBALS["gsSendConfMail"];
   $GLOBALS["fsServerUserEmail"]     = $GLOBALS["gsServerUserEmail"];
   $GLOBALS["fsErrFormFontColor"]     = $GLOBALS["gsErrFormFontColor"];
   $GLOBALS["fnShowHelptexts"]     = $GLOBALS["gsShowHelptexts"];
   $GLOBALS["fsHelptextFontSize"]     = $GLOBALS["gsHelptextFontSize"];
   $GLOBALS["fsHelptextColor"]     = $GLOBALS["gsHelptextColor"];
} // function GetGlobalData()


function AdjustSettings()
{
   global $_POST;

   $cssSettingsModified = False;
   if (UpdateSetting($_POST["phone_status"],'phone_status')) { $cssSettingsModified = True; }
   if (UpdateSetting($_POST["fax_status"],'fax_status')) { $cssSettingsModified = True; }
   if (UpdateSetting($_POST["address_status"],'address_status')) { $cssSettingsModified = True; }
   if (UpdateSetting($_POST["city_status"],'city_status')) { $cssSettingsModified = True; }
   if (UpdateSetting($_POST["state_status"],'state_status')) { $cssSettingsModified = True; }
   if (UpdateSetting($_POST["zip_status"],'zip_status')) { $cssSettingsModified = True; }
   if (UpdateSetting($_POST["country_status"],'country_status')) { $cssSettingsModified = True; }
   if (UpdateSetting($_POST["language_status"],'language_status')) { $cssSettingsModified = True; }
   if (UpdateSetting($_POST["website_status"],'website_status')) { $cssSettingsModified = True; }
   if (UpdateSetting($_POST["comments_status"],'comments_status')) { $cssSettingsModified = True; }
   if (UpdateSetting($_POST["newsletter_status"],'newsletter_status')) { $cssSettingsModified = True; }
   if (UpdateSetting($_POST["sendconfirmationmail"],'sendconfirmationmail')) { $cssSettingsModified = True; }
   if (UpdateSetting($_POST["serveruseremail"],'serveruseremail')) { $cssSettingsModified = True; }
   if (UpdateSetting($_POST["errform_font_color"],'errform_font_color')) { $cssSettingsModified = True; }
   if (UpdateSetting($_POST["show_helptexts"],'show_helptexts')) { $cssSettingsModified = True; }
   if (UpdateSetting($_POST["helptext_fontsize"],'helptext_fontsize')) { $cssSettingsModified = True; }
   if (UpdateSetting($_POST["helptext_color"],'helptext_color')) { $cssSettingsModified = True; }
   dbCommit();

   if ($cssSettingsModified) { RebuildStyleSheet(); }
} // function AdjustSettings()


function bCheckForm()
{
   global $_POST;

   $bFormOK = true;
   if (substr($_POST["errform_font_color"],0,1) == '#' && strlen($_POST["errform_font_color"]) != 7) {
      $GLOBALS["strErrors"][] = $GLOBALS["eErrorColorWrong"];
   }
   if (substr($_POST["helptext_fontsize"],0,1) == '#' && strlen($_POST["helptext_fontsize"]) != 7) {
      $GLOBALS["strErrors"][] = $GLOBALS["eHelptextColorWrong"];
   }

   if (isset($GLOBALS["strErrors"])) { $bFormOK = false; }
   return $bFormOK;
} // function bCheckForm()


function RenderStatus($sStatus)
{
   $statusdesc= array('Don\'t show', 'Optional', 'Mandatory');
   $statusvalues= array('N', 'O', 'M');

   $count=count($statusdesc);

   for ($i=0; $i<$count; $i++) {
      echo '<option value="'.$statusvalues[$i].'"';
      if($sStatus == $statusvalues[$i]) { echo " selected"; }
      echo ">".$statusdesc[$i]."</option>\n";
   }
} // function RenderStatus()


function RenderYesNo($sStatus)
{
      echo '<option value="Y"';
      if($sStatus == 'Y') { echo " selected"; }
      echo ">Yes</option>\n";

      echo '<option value="N"';
      if($sStatus == 'N') { echo " selected"; }
      echo ">No</option>\n";

} // function RenderYesNo()


function RenderSizes($sFontSize)
{
   for($i=6; $i<30; $i++) {
      echo "<option";
      if ($sFontSize == $i) { echo " selected"; }
      echo ">".$i."px\n";
   }
} // function RenderSizes()


include($GLOBALS["rootdp"]."include/javafuncs.php");

?>
