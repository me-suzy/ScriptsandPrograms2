<?php

/***************************************************************************

 m_setgraphics.php
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
$GLOBALS["form"] = 'setgraphics';
$validaccess = VerifyAdminLogin2();

includeLanguageFiles('admin','setgraphics');


$ImageFileTypes = array('gif', 'jpg', 'jpeg', 'png');


$GLOBALS["tabindex"] = 1024;

if ($_POST["submitted"] == "yes") {
   // User has submitted the data
   UpdateSiteImages();
   Header("Location: ".BuildLink('start.php')."&RefreshMenu=styles");
}
GetGlobalData();
frmSetGraphics();


function frmSetGraphics()
{
   adminformheader();
   adminformopen('tvhomepagelogo');
   adminformtitle(4,$GLOBALS["tFormTitle"]);
   adminsubheader(4,$GLOBALS["thSiteLogo"]);
   ?>
   <tr class="tablecontent">
       <?php FieldHeading("HomepageLogo","tvhomepagelogo"); ?>
       <td valign="top" colspan="3" class="content" width="75%">
           <input type="text" name="tvhomepagelogo" size="64" value="<?php echo $GLOBALS["fsHomepageLogo"]; ?>" maxlength="255"<?php echo $GLOBALS["fieldstatus"]; ?>>
           <?php adminimagedisplay('tvhomepagelogo',$GLOBALS["fsHomepageLogo"],$GLOBALS["tShowImage"]); ?>
       </td>
   </tr>
   <?php adminsubheader(4,$GLOBALS["thBackgrounds"]); ?>
   <tr class="tablecontent">
       <?php FieldHeading("MainBackground","tvmainbg"); ?>
       <td valign="top" colspan="3" class="content">
           <input type="text" name="tvmainbg" size="64" value="<?php echo $GLOBALS["fsMainBg"]; ?>" maxlength="255"<?php echo $GLOBALS["fieldstatus"]; ?>>
           <?php adminimagedisplay('tvmainbg',$GLOBALS["fsMainBg"],$GLOBALS["tShowImage"]); ?>
       </td>
   </tr>
   <tr class="tablecontent">
	   <td class=content></td>
       <td valign="top" class="content" nowrap colspan=2>
           <input type="checkbox" name="tvmainbgrep" value="Y" <?php if($GLOBALS["fbMainBgRep"] == 'Y') echo "checked"; ?><?php echo $GLOBALS["fieldstatus"]; ?>>&nbsp;<?php FieldHeading2("MainBackgroundRepeat","tvmainbgrep"); ?><br>
           <input type="checkbox" name="tvmainbgfix" value="Y" <?php if($GLOBALS["fbMainBgFix"] == 'Y') echo "checked"; ?><?php echo $GLOBALS["fieldstatus"]; ?>>&nbsp;<?php FieldHeading2("MainBackgroundFixed","tvmainbgfix"); ?><br>
			<select size=1 name="tvmainbgpos"><? RenderPositions($GLOBALS["fbMainBgPos"]); ?></select>&nbsp;<?php FieldHeading2("MainBackgroundPosition","tvmainbgpos"); ?>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("ContentBackground","tvheaderbg"); ?>
       <td valign="top" colspan="3" class="content">
           <input type="text" name="tvheaderbg" size="64" value="<?php echo $GLOBALS["fsHeaderBg"]; ?>" maxlength="255"<?php echo $GLOBALS["fieldstatus"]; ?>>
           <?php adminimagedisplay('tvheaderbg',$GLOBALS["fsHeaderBg"],$GLOBALS["tShowImage"]); ?>
       </td>
   </tr>
   <tr class="tablecontent">
		<td class=content></td>
       	<td valign="top" class="content" colspan=2>
           <input type="checkbox" name="tvheaderbgrep" value="Y" <?php if($GLOBALS["fbHeaderBgRep"] == 'Y') echo "checked"; ?><?php echo $GLOBALS["fieldstatus"]; ?>>&nbsp;<?php FieldHeading2("ContentBackgroundRepeat","tvheaderbgrep"); ?><br>
			<select size=1 name="tvheaderbgpos"><? RenderPositions($GLOBALS["fbHeaderBgPos"]); ?></select>&nbsp;<?php FieldHeading2("ContentBackgroundPosition","tvheaderbgpos"); ?>
		</td>
   </tr>
   <?php
   if ($GLOBALS["gsShowTopMenu"] == 'Y') {
      ?>
      <tr class="tablecontent">
          <?php FieldHeading("TopMenuBackground","tvtopmenubg"); ?>
          <td valign="top" colspan="3" class="content">
              <input type="text" name="tvtopmenubg" size="64" value="<?php echo $GLOBALS["fsTopMenuBg"]; ?>" maxlength="255"<?php echo $GLOBALS["fieldstatus"]; ?>>
              <?php adminimagedisplay('tvtopmenubg',$GLOBALS["fsTopMenuBg"],$GLOBALS["tShowImage"]); ?>
          </td>
      </tr>
      <tr class="tablecontent">
          <td class=content></td>
          <td valign="top" class="content" colspan=2>
              <input type="checkbox" name="tvtopmenubgrep" value="Y" <?php if ($GLOBALS["fbTopMenuBgRep"] == 'Y') echo "checked"; ?><?php echo $GLOBALS["fieldstatus"]; ?>>&nbsp;<?php FieldHeading2("TopMenuBackgroundRepeat","tvtopmenubgrep"); ?><br>
              <input type="checkbox" name="tvtopmenubgfix" value="Y" <?php if ($GLOBALS["fbTopMenuBgFix"] == 'Y') echo "checked"; ?><?php echo $GLOBALS["fieldstatus"]; ?>>&nbsp;<?php FieldHeading2("TopMenuBackgroundFixed","tvtopmenubgfix"); ?><br>
				<select size=1 name="tvtopmenubgpos"><? RenderPositions($GLOBALS["fbTopMenuBgPos"]); ?></select>&nbsp;<?php FieldHeading2("TopMenuBackgroundPosition","tvtopmenubgpos"); ?>
          </td>
      </tr>
      <?php
   } else {
      ?>
      <input type="hidden" name="tvtopmenubg" value="<?php echo $GLOBALS["fsTopMenuBg"]; ?>">
      <input type="hidden" name="tvtopmenubgrep" value="<?php echo $GLOBALS["fbTopMenuBgRep"]; ?>">
      <input type="hidden" name="tvtopmenubgfix" value="<?php echo $GLOBALS["fbTopMenuBgFix"]; ?>">
      <input type="hidden" name="tvtopmenubgpos" value="<?php echo $GLOBALS["fbTopMenuBgPos"]; ?>">
      <?php
   }
   ?>
   <tr class="tablecontent">
       <?php FieldHeading("MenuBackground","tvmenubg"); ?>
       <td valign="top" colspan="3" class="content">
           <input type="text" name="tvmenubg" size="64" value="<?php echo $GLOBALS["fsMenuBg"]; ?>" maxlength="255"<?php echo $GLOBALS["fieldstatus"]; ?>>
           <?php adminimagedisplay('tvmenubg',$GLOBALS["fsMenuBg"],$GLOBALS["tShowImage"]); ?>
       </td>
   </tr>
   <tr class="tablecontent">
		<td class=content></td>
		<td valign="top" class="content" colspan=2>
           <input type="checkbox" name="tvmenubgrep" value="Y" <?php if($GLOBALS["fbMenuBgRep"] == 'Y') echo "checked"; ?><?php echo $GLOBALS["fieldstatus"]; ?>>&nbsp;<?php FieldHeading2("MenuBackgroundRepeat","tvmenubgrep"); ?><br>
           <input type="checkbox" name="tvmenubgfix" value="Y" <?php if($GLOBALS["fbMenuBgFix"] == 'Y') echo "checked"; ?><?php echo $GLOBALS["fieldstatus"]; ?>>&nbsp;<?php FieldHeading2("MenuBackgroundFixed","tvmenubgfix"); ?><br>
				<select size=1 name="tvmenubgpos"><? RenderPositions($GLOBALS["fbMenuBgPos"]); ?></select>&nbsp;<?php FieldHeading2("MenuBackgroundPosition","tvmenubgpos"); ?>
		</td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("TopBackground","tvtopbg"); ?>
       <td valign="top" colspan="3" class="content">
           <input type="text" name="tvtopbg" size="64" value="<?php echo $GLOBALS["fsTopBg"]; ?>" maxlength="255"<?php echo $GLOBALS["fieldstatus"]; ?>>
           <?php adminimagedisplay('tvtopbg',$GLOBALS["fsTopBg"],$GLOBALS["tShowImage"]); ?>
       </td>
   </tr>
   <tr class="tablecontent">
		<td class=content></td>
       <td valign="top" class="content" colspan=2>
           <input type="checkbox" name="tvtopbgrep" value="Y" <?php if($GLOBALS["fbTopBgRep"] == 'Y') echo "checked"; ?><?php echo $GLOBALS["fieldstatus"]; ?>>&nbsp;<?php FieldHeading2("TopBackgroundRepeat","tvtopbgrep"); ?><br>
           <input type="checkbox" name="tvtopbgfix" value="Y" <?php if($GLOBALS["fbTopBgFix"] == 'Y') echo "checked"; ?><?php echo $GLOBALS["fieldstatus"]; ?>>&nbsp;<?php FieldHeading2("TopBackgroundFixed","tvtopbgfix"); ?><br>
				<select size=1 name="tvtopbgpos"><? RenderPositions($GLOBALS["fbTopBgPos"]); ?></select>&nbsp;<?php FieldHeading2("TopBackgroundPosition","tvtopbgpos"); ?>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("BorderBackground","tvborderbg"); ?>
       <td valign="top" colspan="3" class="content">
           <input type="text" name="tvborderbg" size="64" value="<?php echo $GLOBALS["fsBorderBg"]; ?>" maxlength="255"<?php echo $GLOBALS["fieldstatus"]; ?>>
           <?php adminimagedisplay('tvborderbg',$GLOBALS["fsBorderBg"],$GLOBALS["tShowImage"]); ?>
       </td>
   </tr>
   <tr class="tablecontent">
       <td class=content></td>
       <td valign="top" class="content" colspan=2>
           <input type="checkbox" name="tvborderbgrep" value="Y" <?php if($GLOBALS["fbBorderBgRep"] == 'Y') echo "checked"; ?><?php echo $GLOBALS["fieldstatus"]; ?>>&nbsp;<?php FieldHeading2("BorderBackgroundRepeat","tvborderbgrep"); ?><br>
           <input type="checkbox" name="tvborderbgfix" value="Y" <?php if($GLOBALS["fbBorderBgFix"] == 'Y') echo "checked"; ?><?php echo $GLOBALS["fieldstatus"]; ?>>&nbsp;<?php FieldHeading2("BorderBackgroundFixed","tvborderbgfix"); ?><br>
				<select size=1 name="tvborderbgpos"><? RenderPositions($GLOBALS["fbBorderBgPos"]); ?></select>&nbsp;<?php FieldHeading2("BorderBackgroundPosition","tvborderbgpos"); ?>
       </td>
   </tr>
   <?php
   if ($GLOBALS["gnBottomFrame"] == 'Y') {
      ?>
      <tr class="tablecontent">
          <?php FieldHeading("FooterBackground","tvfooterbg"); ?>
          <td valign="top" colspan="3" class="content">
              <input type="text" name="tvfooterbg" size="64" value="<?php echo $GLOBALS["fsFooterBg"]; ?>" maxlength="255"<?php echo $GLOBALS["fieldstatus"]; ?>>
              <?php adminimagedisplay('tvfooterbg',$GLOBALS["fsFooterBg"],$GLOBALS["tShowImage"]); ?>
          </td>
      </tr>
      <tr class="tablecontent">
          <td class=content></td>
          <td valign="top" class="content" colspan=2>
              <input type="checkbox" name="tvfooterbgrep" value="Y" <?php if ($GLOBALS["fbFooterBgRep"] == 'Y') echo "checked"; ?><?php echo $GLOBALS["fieldstatus"]; ?>>&nbsp;<?php FieldHeading2("FooterBackgroundRepeat","tvfooterbgrep"); ?><br>
              <input type="checkbox" name="tvfooterbgfix" value="Y" <?php if ($GLOBALS["fbFooterBgFix"] == 'Y') echo "checked"; ?><?php echo $GLOBALS["fieldstatus"]; ?>>&nbsp;<?php FieldHeading2("FooterBackgroundFixed","tvfooterbgfix"); ?><br>
				<select size=1 name="tvfooterbgpos"><? RenderPositions($GLOBALS["fbFooterBgPos"]); ?></select>&nbsp;<?php FieldHeading2("FooterBackgroundPosition","tvfooterbgpos"); ?>
          </td>
      </tr>
      <?php
   } else {
      ?>
      <input type="hidden" name="tvfooterbg" value="<?php echo $GLOBALS["fsFooterBg"]; ?>">
      <input type="hidden" name="tvfooterbgrep" value="<?php echo $GLOBALS["fbFooterBgRep"]; ?>">
      <input type="hidden" name="tvfooterbgfix" value="<?php echo $GLOBALS["fbFooterBgFix"]; ?>">
      <input type="hidden" name="tvfooterbgpos" value="<?php echo $GLOBALS["fbFooterBgPos"]; ?>">
      <?php
   }
   ?>
   <?php adminsubheader(4,$GLOBALS["thPagingIcons"]); ?>
   <tr class="tablecontent">
       <?php FieldHeading("FirstPageIcon","tvfirstpageicon"); ?>
       <td valign="top" colspan="3" class="content">
           <input type="text" name="tvfirstpageicon" size="64" value="<?php echo $GLOBALS["fsFirstPageIcon"]; ?>" maxlength="255"<?php echo $GLOBALS["fieldstatus"]; ?>>
           <?php adminimagedisplay('tvfirstpageicon',$GLOBALS["fsFirstPageIcon"],$GLOBALS["tShowImage"]); ?>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("PrevPageIcon","tvprevpageicon"); ?>
       <td valign="top" colspan="3" class="content">
           <input type="text" name="tvprevpageicon" size="64" value="<?php echo $GLOBALS["fsPrevPageIcon"]; ?>" maxlength="255"<?php echo $GLOBALS["fieldstatus"]; ?>>
           <?php adminimagedisplay('tvprevpageicon',$GLOBALS["fsPrevPageIcon"],$GLOBALS["tShowImage"]); ?>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("NextPageIcon","tvnextpageicon"); ?>
       <td valign="top" colspan="3" class="content">
           <input type="text" name="tvnextpageicon" size="64" value="<?php echo $GLOBALS["fsNextPageIcon"]; ?>" maxlength="255"<?php echo $GLOBALS["fieldstatus"]; ?>>
           <?php adminimagedisplay('tvnextpageicon',$GLOBALS["fsNextPageIcon"],$GLOBALS["tShowImage"]); ?>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("LastPageIcon","tvlastpageicon"); ?>
       <td valign="top" colspan="3" class="content">
           <input type="text" name="tvlastpageicon" size="64" value="<?php echo $GLOBALS["fsLastPageIcon"]; ?>" maxlength="255"<?php echo $GLOBALS["fieldstatus"]; ?>>
           <?php adminimagedisplay('tvlastpageicon',$GLOBALS["fsLastPageIcon"],$GLOBALS["tShowImage"]); ?>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("ListStyleIcon","tvliststyleicon"); ?>
       <td valign="top" colspan="3" class="content">
           <input type="text" name="tvliststyleicon" size="64" value="<?php echo $GLOBALS["fsListStyleIcon"]; ?>" maxlength="255"<?php echo $GLOBALS["fieldstatus"]; ?>>
           <?php adminimagedisplay('tvliststyleicon',$GLOBALS["fsListStyleIcon"],$GLOBALS["tShowImage"]); ?>
       </td>
   </tr>
   <?php adminsubheader(4,$GLOBALS["thMenuIcons"]); ?>
   <tr class="tablecontent">
       <?php FieldHeading("ExpandIcon","tvexpandicon"); ?>
       <td valign="top" colspan="3" class="content">
           <input type="text" name="tvexpandicon" size="64" value="<?php echo $GLOBALS["fsExpandIcon"]; ?>" maxlength="255"<?php echo $GLOBALS["fieldstatus"]; ?>>
           <?php adminimagedisplay('tvexpandicon',$GLOBALS["fsExpandIcon"],$GLOBALS["tShowImage"]); ?>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("CollapseIcon","tvcollapseicon"); ?>
       <td valign="top" colspan="3" class="content">
           <input type="text" name="tvcollapseicon" size="64" value="<?php echo $GLOBALS["fsCollapseIcon"]; ?>" maxlength="255"<?php echo $GLOBALS["fieldstatus"]; ?>>
           <?php adminimagedisplay('tvcollapseicon',$GLOBALS["fsCollapseIcon"],$GLOBALS["tShowImage"]); ?>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("NoExpandIcon","tvnoexpandicon"); ?>
       <td valign="top" colspan="3" class="content">
           <input type="text" name="tvnoexpandicon" size="64" value="<?php echo $GLOBALS["fsNoExpandIcon"]; ?>" maxlength="255"<?php echo $GLOBALS["fieldstatus"]; ?>>
           <?php adminimagedisplay('tvnoexpandicon',$GLOBALS["fsNoExpandIcon"],$GLOBALS["tShowImage"]); ?>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("SecureIcon","tvsecureicon"); ?>
       <td valign="top" colspan="3" class="content">
           <input type="text" name="tvsecureicon" size="64" value="<?php echo $GLOBALS["fsSecureIcon"]; ?>" maxlength="255"<?php echo $GLOBALS["fieldstatus"]; ?>>
           <?php adminimagedisplay('tvsecureicon',$GLOBALS["fsSecureIcon"],$GLOBALS["tShowImage"]); ?>
       </td>
   </tr>
   <?php adminsubheader(4,$GLOBALS["thSpecialIcons"]); ?>
   <tr class="tablecontent">
       <?php FieldHeading("PrintIcon","tvprinticon"); ?>
       <td valign="top" colspan="3" class="content">
           <input type="text" name="tvprinticon" size="64" value="<?php echo $GLOBALS["fsPrintIcon"]; ?>" maxlength="255"<?php echo $GLOBALS["fieldstatus"]; ?>>
           <?php adminimagedisplay('tvprinticon',$GLOBALS["fsPrintIcon"],$GLOBALS["tShowImage"]); ?>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("PDFIcon","tvpdficon"); ?>
       <td valign="top" colspan="3" class="content">
           <input type="text" name="tvpdficon" size="64" value="<?php echo $GLOBALS["fsPDFIcon"]; ?>" maxlength="255"<?php echo $GLOBALS["fieldstatus"]; ?>>
           <?php adminimagedisplay('tvpdficon',$GLOBALS["fsPDFIcon"],$GLOBALS["tShowImage"]); ?>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("TellFriendIcon","tvtellfriendicon"); ?>
       <td valign="top" colspan="3" class="content">
           <input type="text" name="tvtellfriendicon" size="64" value="<?php echo $GLOBALS["fsTellFriendIcon"]; ?>" maxlength="255"<?php echo $GLOBALS["fieldstatus"]; ?>>
           <?php adminimagedisplay('tvtellfriendicon',$GLOBALS["fsTellFriendIcon"],$GLOBALS["tShowImage"]); ?>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("RatingIcon","tvratingicon"); ?>
       <td valign="top" colspan="3" class="content">
           <input type="text" name="tvratingicon" size="64" value="<?php echo $GLOBALS["fsRatingIcon"]; ?>" maxlength="255"<?php echo $GLOBALS["fieldstatus"]; ?>>
           <?php adminimagedisplay('tvratingicon',$GLOBALS["fsRatingIcon"],$GLOBALS["tShowImage"]); ?>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("CommentIcon","tvcommenticon"); ?>
       <td valign="top" colspan="3" class="content">
           <input type="text" name="tvcommenticon" size="64" value="<?php echo $GLOBALS["fsCommentIcon"]; ?>" maxlength="255"<?php echo $GLOBALS["fieldstatus"]; ?>>
           <?php adminimagedisplay('tvcommenticon',$GLOBALS["fsCommentIcon"],$GLOBALS["tShowImage"]); ?>
       </td>
   </tr>
   <?php
   adminformsavebar(4,'start.php');
   if ($GLOBALS["specialedit"] == True) { adminhelpmsg(4); }
   adminformclose();
} // function frmSetGraphics()

function GetGlobalData()
{
   $GLOBALS["fsHomepageLogo"]	= $GLOBALS["gsHomepageLogo"];
   $GLOBALS["fsMainBg"]		= $GLOBALS["gsMainBg"];
   $GLOBALS["fbMainBgRep"]	= $GLOBALS["gbMainBgRep"];
   $GLOBALS["fbMainBgFix"]	= $GLOBALS["gbMainBgFix"];
   $GLOBALS["fbMainBgPos"]	= $GLOBALS["gbMainBgPos"];
   $GLOBALS["fsHeaderBg"]	= $GLOBALS["gsHeaderBg"];
   $GLOBALS["fbHeaderBgRep"]	= $GLOBALS["gbHeaderBgRep"];
   $GLOBALS["fbHeaderBgPos"]	= $GLOBALS["gbHeaderBgPos"];
   $GLOBALS["fsTopBg"]		= $GLOBALS["gsTopBg"];
   $GLOBALS["fbTopBgRep"]	= $GLOBALS["gbTopBgRep"];
   $GLOBALS["fbTopBgFix"]	= $GLOBALS["gbTopBgFix"];
   $GLOBALS["fbTopBgPos"]	= $GLOBALS["gbTopBgPos"];
   $GLOBALS["fsTopMenuBg"]	= $GLOBALS["gsTopMenuBg"];
   $GLOBALS["fbTopMenuBgRep"]	= $GLOBALS["gbTopMenuBgRep"];
   $GLOBALS["fbTopMenuBgFix"]	= $GLOBALS["gbTopMenuBgFix"];
   $GLOBALS["fbTopMenuBgPos"]	= $GLOBALS["gbTopMenuBgPos"];
   $GLOBALS["fsMenuBg"]		= $GLOBALS["gsMenuBg"];
   $GLOBALS["fbMenuBgRep"]	= $GLOBALS["gbMenuBgRep"];
   $GLOBALS["fbMenuBgFix"]	= $GLOBALS["gbMenuBgFix"];
   $GLOBALS["fbMenuBgPos"]	= $GLOBALS["gbMenuBgPos"];
   $GLOBALS["fsBorderBg"]		= $GLOBALS["gsBorderBg"];
   $GLOBALS["fbBorderBgRep"]	= $GLOBALS["gbBorderBgRep"];
   $GLOBALS["fbBorderBgFix"]	= $GLOBALS["gbBorderBgFix"];
   $GLOBALS["fbBorderBgPos"]	= $GLOBALS["gbBorderBgPos"];
   $GLOBALS["fsFooterBg"]	= $GLOBALS["gsFooterBg"];
   $GLOBALS["fbFooterBgRep"]	= $GLOBALS["gbFooterBgRep"];
   $GLOBALS["fbFooterBgFix"]	= $GLOBALS["gbFooterBgFix"];
   $GLOBALS["fbFooterBgPos"]	= $GLOBALS["gbFooterBgPos"];
   $GLOBALS["fsFirstPageIcon"]	= $GLOBALS["gsFirstPageIcon"];
   $GLOBALS["fsPrevPageIcon"]	= $GLOBALS["gsPrevPageIcon"];
   $GLOBALS["fsNextPageIcon"]	= $GLOBALS["gsNextPageIcon"];
   $GLOBALS["fsLastPageIcon"]	= $GLOBALS["gsLastPageIcon"];
   $GLOBALS["fsListStyleIcon"]	= $GLOBALS["gsListStyleIcon"];
   $GLOBALS["fsExpandIcon"]	= $GLOBALS["gsExpandIcon"];
   $GLOBALS["fsCollapseIcon"]	= $GLOBALS["gsCollapseIcon"];
   $GLOBALS["fsNoExpandIcon"]	= $GLOBALS["gsNoExpandIcon"];
   $GLOBALS["fsSecureIcon"]	= $GLOBALS["gsSecureIcon"];
   $GLOBALS["fsPrintIcon"]	= $GLOBALS["gsPrintIcon"];
   $GLOBALS["fsPDFIcon"]        = $GLOBALS["gsPDFIcon"];
   $GLOBALS["fsTellFriendIcon"]	= $GLOBALS["gsTellFriendIcon"];   
   $GLOBALS["fsRatingIcon"]	= $GLOBALS["gsRatingIcon"];
   $GLOBALS["fsCommentIcon"]	= $GLOBALS["gsCommentIcon"];
} // function GetGlobalData()


function UpdateSiteImages()
{
   global $_POST;

   $cssSettingsModified = False;
   if (UpdateSetting($_POST["tvhomepagelogo"],'homepagelogo'))		{ $cssSettingsModified = True; }
   if (UpdateSetting($_POST["tvmainbg"],'mainbg'))			{ $cssSettingsModified = True; }
   if (UpdateSetting($_POST["tvmainbgrep"],'mainbgrep'))		{ $cssSettingsModified = True; }
   if (UpdateSetting($_POST["tvmainbgfix"],'mainbgfix'))		{ $cssSettingsModified = True; }
   if (UpdateSetting($_POST["tvmainbgpos"],'mainbgpos'))		{ $cssSettingsModified = True; }
   if (UpdateSetting($_POST["tvheaderbg"],'headerbg'))			{ $cssSettingsModified = True; }
   if (UpdateSetting($_POST["tvheaderbgrep"],'headerbgrep'))		{ $cssSettingsModified = True; }
   if (UpdateSetting($_POST["tvheaderbgpos"],'headerbgpos'))		{ $cssSettingsModified = True; }
   if (UpdateSetting($_POST["tvtopmenubg"],'topmenubg'))		{ $cssSettingsModified = True; }
   if (UpdateSetting($_POST["tvtopmenubgrep"],'topmenubgrep'))		{ $cssSettingsModified = True; }
   if (UpdateSetting($_POST["tvtopmenubgfix"],'topmenubgfix'))		{ $cssSettingsModified = True; }
   if (UpdateSetting($_POST["tvtopmenubgpos"],'topmenubgpos'))		{ $cssSettingsModified = True; }
   if (UpdateSetting($_POST["tvmenubg"],'menubg'))			{ $cssSettingsModified = True; }
   if (UpdateSetting($_POST["tvmenubgrep"],'menubgrep'))		{ $cssSettingsModified = True; }
   if (UpdateSetting($_POST["tvmenubgfix"],'menubgfix'))		{ $cssSettingsModified = True; }
   if (UpdateSetting($_POST["tvmenubgpos"],'menubgpos'))		{ $cssSettingsModified = True; }
   if (UpdateSetting($_POST["tvtopbg"],'topbg'))			{ $cssSettingsModified = True; }
   if (UpdateSetting($_POST["tvtopbgrep"],'topbgrep'))			{ $cssSettingsModified = True; }
   if (UpdateSetting($_POST["tvtopbgfix"],'topbgfix'))			{ $cssSettingsModified = True; }
   if (UpdateSetting($_POST["tvtopbgpos"],'topbgpos'))			{ $cssSettingsModified = True; }
   if (UpdateSetting($_POST["tvfooterbg"],'footerbg'))			{ $cssSettingsModified = True; }
   if (UpdateSetting($_POST["tvfooterbgrep"],'footerbgrep'))		{ $cssSettingsModified = True; }
   if (UpdateSetting($_POST["tvfooterbgfix"],'footerbgfix'))		{ $cssSettingsModified = True; }
   if (UpdateSetting($_POST["tvfooterbgpos"],'footerbgpos'))		{ $cssSettingsModified = True; }
   if (UpdateSetting($_POST["tvborderbg"],'borderbg'))			{ $cssSettingsModified = True; }
   if (UpdateSetting($_POST["tvborderbgrep"],'borderbgrep'))		{ $cssSettingsModified = True; }
   if (UpdateSetting($_POST["tvborderbgfix"],'borderbgfix'))		{ $cssSettingsModified = True; }
   if (UpdateSetting($_POST["tvborderbgpos"],'borderbgpos'))		{ $cssSettingsModified = True; }
   if (UpdateSetting($_POST["tvfirstpageicon"],'firstpageicon'))	{ $cssSettingsModified = True; }
   if (UpdateSetting($_POST["tvprevpageicon"],'prevpageicon'))		{ $cssSettingsModified = True; }
   if (UpdateSetting($_POST["tvnextpageicon"],'nextpageicon'))		{ $cssSettingsModified = True; }
   if (UpdateSetting($_POST["tvlastpageicon"],'lastpageicon'))		{ $cssSettingsModified = True; }
   if (UpdateSetting($_POST["tvliststyleicon"],'liststyleicon'))		{ $cssSettingsModified = True; }
   if (UpdateSetting($_POST["tvexpandicon"],'expandicon'))		{ $cssSettingsModified = True; }
   if (UpdateSetting($_POST["tvcollapseicon"],'collapseicon'))		{ $cssSettingsModified = True; }
   if (UpdateSetting($_POST["tvnoexpandicon"],'noexpandicon'))		{ $cssSettingsModified = True; }
   if (UpdateSetting($_POST["tvsecureicon"],'secureicon'))		{ $cssSettingsModified = True; }
   if (UpdateSetting($_POST["tvprinticon"],'printicon'))		{ $cssSettingsModified = True; }
   if (UpdateSetting($_POST["tvpdficon"],'PDFicon'))    		{ $cssSettingsModified = True; } 
   if (UpdateSetting($_POST["tvtellfriendicon"],'tellfriendicon'))    		{ $cssSettingsModified = True; } 
   if (UpdateSetting($_POST["tvratingicon"],'ratingicon'))		{ $cssSettingsModified = True; }
   if (UpdateSetting($_POST["tvcommenticon"],'commenticon'))		{ $cssSettingsModified = True; }
   dbCommit();

   if ($cssSettingsModified) { RebuildStyleSheet(); }
} // function UpdateSiteImages()

function RenderPositions($sBgPosition) {
   $positions = array('top left', 'top center', 'top right', 'bottom left', 'bottom center', 'bottom right', 'center left', 'center center', 'center right');

   while($positionname = each($positions)) {
      echo "<option ";
      if($sBgPosition == $positionname[1]) { echo "selected"; }
      echo ">".$positionname[1]."\n";
   }
} // function RenderStyles()

?>


<?php include($GLOBALS["rootdp"]."include/javafuncs.php"); ?>
