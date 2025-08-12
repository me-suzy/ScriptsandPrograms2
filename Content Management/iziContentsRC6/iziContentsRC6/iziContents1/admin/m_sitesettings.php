<?php

/***************************************************************************

 m_sitesettings.php
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

include_once ("compile.php");

// Security vet.
// Start by setting all security access to false.
// Do it in the code to overwrite any spurious values that a hacker may
//    have tried to pass in the url
$GLOBALS["specialedit"] = $GLOBALS["canedit"] = $GLOBALS["canadd"] = $GLOBALS["canview"] = False;
$GLOBALS["fieldstatus"] = '';

// Validate the user's level of access for this form.
$GLOBALS["form"] = 'sitesettings';
$validaccess = VerifyAdminLogin2();

includeLanguageFiles('admin','sitesettings');


//	Set list of textareas in an array for HTMLArea integration
$GLOBALS["textareas"]	= array('tvtophtml','tvfooter');
$GLOBALS["base_url"] = SiteBaseUrl($EZ_SESSION_VARS["Site"]);


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
   adminformopen('tvsitetitle');
   adminformtitle(4,$GLOBALS["tFormTitle"]);
   if (isset($GLOBALS["strErrors"])) { formError(4); }
   adminsubheader(4,$GLOBALS["thTitles"]);
   ?>
   <tr class="tablecontent">
       <?php FieldHeading("SiteTitle","tvsitetitle"); ?>
       <td colspan="3" valign="top" class="content">
           <input type="text" name="tvsitetitle" size="70" value="<?php echo $GLOBALS["fsSitetitle"]; ?>" maxlength="250"<?php echo $GLOBALS["fieldstatus"]; ?>>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("SiteDescr","tvsitedesc"); ?>
       <td colspan="3" valign="top" class="content">
           <input type="text" name="tvsitedesc" size="70" value="<?php echo $GLOBALS["fsSitedesc"]; ?>" maxlength="500"<?php echo $GLOBALS["fieldstatus"]; ?>>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("SiteKeywords","tvsitekeywords"); ?>
       <td colspan="3" valign="top" class="content">
           <input type="text" name="tvsitekeywords" size="70" value="<?php echo $GLOBALS["fsSitekeywords"]; ?>" maxlength="250"<?php echo $GLOBALS["fieldstatus"]; ?>>
       </td>
   </tr>
   <?php
   if ($GLOBALS["gsUseFrames"] == 'Y') {
      ?>
      <tr class="tablecontent">
          <?php FieldHeading("SiteWidth","tvsitewidth"); ?>
          <td valign="top" class="content">
              <input type="text" name="tvsitewidth" size="5" value="<?php echo $GLOBALS["fsSiteWidth"]; ?>" maxlength="8"<?php echo $GLOBALS["fieldstatus"]; ?>>
          </td>
          <?php FieldHeading("SiteHeight","tvsiteheight"); ?>
          <td valign="top" class="content">
              <input type="text" name="tvsiteheight" size="5" value="<?php echo $GLOBALS["fsSiteHeight"]; ?>" maxlength="8"<?php echo $GLOBALS["fieldstatus"]; ?>>
          </td>
      </tr>
      <?php
   } else {
      ?>
         <input type="hidden" name="tvsitewidth" value="<?php echo $GLOBALS["fsSiteWidth"]; ?>">
         <input type="hidden" name="tvsiteheight" value="<?php echo $GLOBALS["fsSiteHeight"]; ?>">
      <?php
   }
   ?>
   <?php adminsubheader(4,$GLOBALS["thSecurity"]); ?>
   <tr class="tablecontent">
       <?php FieldHeading("SectionSecurity","tvsectionsecurity"); ?>
       <td colspan="3" valign="top" class="content">
           <select name="tvsectionsecurity" size="1"<?php echo $GLOBALS["fieldstatus"]; ?>>
               <option value="Y" <?php if($GLOBALS["fsSectionSecurity"] == "Y") echo "selected"; ?>><?php echo $GLOBALS["tYes"]; ?>
               <option value="N" <?php if($GLOBALS["fsSectionSecurity"] != "Y") echo "selected"; ?>><?php echo $GLOBALS["tNo"]; ?>
           </select>
       </td>
   </tr>
   <?php adminsubheader(4,$GLOBALS["thTopFrame"]); ?>
   <tr class="tablecontent">
       <?php FieldHeading("TopFrameHeight","tvtopframe_height"); ?>
       <td colspan="3" valign="top" class="content">
           <input type="text" name="tvtopframe_height" size="5" value="<?php echo $GLOBALS["fnTopFrameHeight"]; ?>" maxlength="5"<?php echo $GLOBALS["fieldstatus"]; ?>>
       </td>
   </tr>
   <tr class="tablecontent">
			<?php
			if ((isset($GLOBALS["textareas"])) && ($EZ_SESSION_VARS["WYSIWYG"] == 'Y')) {
				FieldHeading("TopHTML","tvtopframe_height"); ?><td colspan=3></td></tr>
				<tr><td colspan="4" valign="top" class="content">
				<textarea id="tvtophtml" name="tvtophtml" style="width:800; height:180"<?php echo $GLOBALS["fieldstatus"]; ?>><?php echo $GLOBALS["fsTopHtml"]; ?></textarea>
				<?php
			} else {
				FieldHeading("TopHTML","tvtophtml"); ?>
				<td colspan="3" valign="top" class="content">
				<textarea name="tvtophtml" cols="64" rows="5"<?php echo $GLOBALS["fieldstatus"]; ?>><?php echo htmlspecialchars($GLOBALS["fsTopHtml"]); ?></textarea>
				<?php
			}
			?>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("TopMenu","tvshowtopmenu"); ?>
       <td valign="top" class="content">
           <select name="tvshowtopmenu" size="1"<?php echo $GLOBALS["fieldstatus"]; ?>>
               <option value="Y" <?php if($GLOBALS["fsShowTopMenu"] == "Y") echo "selected"; ?>><?php echo $GLOBALS["tYes"]; ?>
               <option value="N" <?php if($GLOBALS["fsShowTopMenu"] != "Y") echo "selected"; ?>><?php echo $GLOBALS["tNo"]; ?>
           </select>
       </td>
       <?php FieldHeading("TopMenuHeight","tvtopmenuframe_height"); ?>
       <td valign="top" class="content">
           <input type="text" name="tvtopmenuframe_height" size="5" value="<?php echo $GLOBALS["fnTopMenuFrameHeight"]; ?>" maxlength="5"<?php echo $GLOBALS["fieldstatus"]; ?>>
       </td>
   </tr>
   <?php adminsubheader(4,$GLOBALS["thMenus"]); ?>
   <tr class="tablecontent">
       <?php FieldHeading("MenuWidth","tvleftframe_width"); ?>
       <td valign="top" class="content">
           <input type="text" name="tvleftframe_width" size="5" value="<?php echo $GLOBALS["fnLeftFrameWidth"]; ?>" maxlength="5"<?php echo $GLOBALS["fieldstatus"]; ?>>
       </td>
       <?php FieldHeading("MenuFrameAlign",10); ?>
       <td valign="top" class="content">
           <input type="radio" value="L" name="tvmenuframealign" <?php if($GLOBALS["fsMenuFrameAlign"] == "L" || $GLOBALS["fsMenuFrameAlign"] == "") echo "checked" ?><?php echo $GLOBALS["fieldstatus"]; ?>><?php echo $GLOBALS["tLeft"]; ?><br />
           <input type="radio" value="R" name="tvmenuframealign" <?php if($GLOBALS["fsMenuFrameAlign"] == "R") echo "checked" ?><?php echo $GLOBALS["fieldstatus"]; ?>><?php echo $GLOBALS["tRight"]; ?><br />
           <input type="radio" value="A" name="tvmenuframealign" <?php if($GLOBALS["fsMenuFrameAlign"] == "A") echo "checked" ?><?php echo $GLOBALS["fieldstatus"]; ?>><?php echo $GLOBALS["tAutomatic"]; ?>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("MenuExpand","tvexpandmenus"); ?>
       <td valign="top" class="content">
           <select name="tvexpandmenus" size="1"<?php echo $GLOBALS["fieldstatus"]; ?>>
               <option value="Y" <?php if($GLOBALS["fsExpandMenus"] == "Y") echo "selected"; ?>><?php echo $GLOBALS["tYes"]; ?>
               <option value="N" <?php if($GLOBALS["fsExpandMenus"] != "Y") echo "selected"; ?>><?php echo $GLOBALS["tNo"]; ?>
           </select>
       </td>
       <?php FieldHeading("MenuCollapse","tvcollapsemenus"); ?>
       <td valign="top" class="content">
           <select name="tvcollapsemenus" size="1"<?php echo $GLOBALS["fieldstatus"]; ?>>
               <option value="Y" <?php if($GLOBALS["fsCollapseMenus"] == "Y") echo "selected"; ?>><?php echo $GLOBALS["tYes"]; ?>
               <option value="N" <?php if($GLOBALS["fsCollapseMenus"] != "Y") echo "selected"; ?>><?php echo $GLOBALS["tNo"]; ?>
           </select>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("MenuHover","tvhoverdisplay"); ?>
       <td colspan="3" valign="top" class="content">
           <select name="tvhoverdisplay" size="1"<?php echo $GLOBALS["fieldstatus"]; ?>>
               <option value="Y" <?php if($GLOBALS["fsMenuHover"] == "Y") echo "selected"; ?>><?php echo $GLOBALS["tYes"]; ?>
               <option value="N" <?php if($GLOBALS["fsMenuHover"] != "Y") echo "selected"; ?>><?php echo $GLOBALS["tNo"]; ?>
           </select>
       </td>
   </tr>
   <?php adminsubheader(4,$GLOBALS["thUserdataFrame"]); ?>
   <tr class="tablecontent">
       <?php FieldHeading("Userdata","tvuserdataframe"); ?>
       <td valign="top" class="content">
           <select name="tvuserdataframe" size="1"<?php echo $GLOBALS["fieldstatus"]; ?>>
               <option value="T" <?php if($GLOBALS["fsUserdataFrame"] == "T") echo "selected"; ?>><?php echo $GLOBALS["tHeaderPanel"]; ?>
               <option value="M" <?php if($GLOBALS["fsUserdataFrame"] == "M") echo "selected"; ?>><?php echo $GLOBALS["tMenuPanel"]; ?>
               <option value="N" <?php if($GLOBALS["fsUserdataFrame"] == "N") echo "selected"; ?>><?php echo $GLOBALS["tNoUserdata"]; ?>
           </select>
       </td>
       <?php FieldHeading("UserdataWidth","tvuserdataframe_width"); ?>
       <td valign="top" class="content">
           <input type="text" name="tvuserdataframe_width" size="5" value="<?php echo $GLOBALS["fnUserdataFrameWidth"]; ?>" maxlength="5"<?php echo $GLOBALS["fieldstatus"]; ?>>
       </td>
   </tr>
   <?php adminsubheader(4,$GLOBALS["thContents"]); ?>
   <tr class="tablecontent">
       <?php FieldHeading("LRContent","tvlrcontentframe"); ?>
       <td valign="top" class="content">
           <select name="tvlrcontentframe" size="1"<?php echo $GLOBALS["fieldstatus"]; ?>>
               <option value="Y" <?php if($GLOBALS["fsLRContentFrame"] == "Y") echo "selected"; ?>><?php echo $GLOBALS["tAlways"]; ?>
               <option value="A" <?php if($GLOBALS["fsLRContentFrame"] == "A") echo "selected"; ?>><?php echo $GLOBALS["tAutomatic"]; ?>
               <option value="N" <?php if($GLOBALS["fsLRContentFrame"] == "N") echo "selected"; ?>><?php echo $GLOBALS["tNever"]; ?>
           </select>
       </td>
       <?php FieldHeading("RightWidth","tvrightcolumn_width"); ?>
       <td valign="top" class="content">
           <input type="text" name="tvrightcolumn_width" size="5" value="<?php echo $GLOBALS["fnRightColumnWidth"]; ?>" maxlength="5"<?php echo $GLOBALS["fieldstatus"]; ?>>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("ColBreak","tvimagecolumnbreak"); ?>
       <td colspan="3" valign="top" class="content">
           <input type="text" name="tvimagecolumnbreak" size="64" value="<?php echo $GLOBALS["fnImageColumnBreak"]; ?>" maxlength="255"<?php echo $GLOBALS["fieldstatus"]; ?>>
           <?php adminimagedisplay('tvimagecolumnbreak',$GLOBALS["fnImageColumnBreak"],$GLOBALS["tShowImage"]); ?>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("Breadcrumb","tvbreadcrumb"); ?>
       <td valign="top" class="content">
           <select name="tvbreadcrumb" size="1"<?php echo $GLOBALS["fieldstatus"]; ?>>
               <option value="Y" <?php if($GLOBALS["fsBreadcrumb"] == "Y") echo "selected"; ?>><?php echo $GLOBALS["tYes"]; ?>
               <option value="N" <?php if($GLOBALS["fsBreadcrumb"] != "Y") echo "selected"; ?>><?php echo $GLOBALS["tNo"]; ?>
           </select>
       </td>
       <?php FieldHeading("BreadcrumbSeparator","tvbreadcrumbseparator"); ?>
       <td valign="top" class="content">
           <input type="text" name="tvbreadcrumbseparator" size="24" value="<?php echo htmlspecialchars($GLOBALS["fnBreadcrumbSeparator"]); ?>" maxlength="64"<?php echo $GLOBALS["fieldstatus"]; ?>>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("Bookmark","tvbookmark"); ?>
       <td colspan="3" valign="top" class="content">
           <select name="tvbookmark" size="1"<?php echo $GLOBALS["fieldstatus"]; ?>>
               <option value="Y" <?php if($GLOBALS["fsBookmark"] == "Y") echo "selected"; ?>><?php echo $GLOBALS["tYes"]; ?>
               <option value="N" <?php if($GLOBALS["fsBookmark"] != "Y") echo "selected"; ?>><?php echo $GLOBALS["tNo"]; ?>
           </select>
       </td>
   </tr>
   <?php adminsubheader(4,$GLOBALS["thBanners"]); ?>
   <tr class="tablecontent">
       <?php FieldHeading("Banners","tvshowbanners"); ?>
       <td colspan="3" valign="top" class="content">
           <select name="tvshowbanners" size="1"<?php echo $GLOBALS["fieldstatus"]; ?>>
               <option value="N" <?php if($GLOBALS["fsShowBanners"] == "N") echo "selected"; ?>><?php echo $GLOBALS["tNoBanners"]; ?>
               <option value="U" <?php if($GLOBALS["fsShowBanners"] == "U") echo "selected"; ?>><?php echo $GLOBALS["tBannersT"]; ?>
               <option value="D" <?php if($GLOBALS["fsShowBanners"] == "D") echo "selected"; ?>><?php echo $GLOBALS["tBannersB"]; ?>
               <option value="2" <?php if($GLOBALS["fsShowBanners"] == "2") echo "selected"; ?>><?php echo $GLOBALS["tBannersTB"]; ?>
           </select>
       </td>
   </tr>
   <?php adminsubheader(4,$GLOBALS["thFooter"]); ?>
   <tr class="tablecontent">
       <?php FieldHeading("Footer","tvbottomframe"); ?>
       <td valign="top" class="content">
           <select name="tvbottomframe" size="1"<?php echo $GLOBALS["fieldstatus"]; ?>>
               <option value="Y" <?php if($GLOBALS["fsBottomFrame"] == "Y") echo "selected"; ?>><?php echo $GLOBALS["tYes"]; ?>
               <option value="N" <?php if($GLOBALS["fsBottomFrame"] != "Y") echo "selected"; ?>><?php echo $GLOBALS["tNo"]; ?>
           </select>
       </td>
       <?php FieldHeading("FooterHeight","tvbottomframe_height"); ?>
       <td valign="top" class="content">
           <input type="text" name="tvbottomframe_height" size="5" value="<?php echo $GLOBALS["fnBottomFrameHeight"]; ?>" maxlength="5"<?php echo $GLOBALS["fieldstatus"]; ?>>
       </td>
   </tr>
   <tr class="tablecontent">
			<?php
			if ((isset($GLOBALS["textareas"])) && ($EZ_SESSION_VARS["WYSIWYG"] == 'Y')) {
				FieldHeading("FooterText","tvbottomframe"); ?><td colspan=3></td></tr>
				<tr><td colspan="4" valign="top" class="content">
				<textarea id="tvfooter" name="tvfooter" style="width:790; height:180"<?php echo $GLOBALS["fieldstatus"]; ?>><?php echo $GLOBALS["fsFooter"]; ?></textarea>
				<?php
			} else {
				FieldHeading("FooterText","tvfooter"); ?>
				<td colspan="3" valign="top" class="content">
				<textarea name="tvfooter" cols="64" rows="4"<?php echo $GLOBALS["fieldstatus"]; ?>><?php echo htmlspecialchars($GLOBALS["fsFooter"]); ?></textarea>
				<?php
			}
			?>
       </td>
   </tr>
   <?php
   adminformsavebar(4,'start.php');
   if ($GLOBALS["specialedit"] == True) {
      adminhelpmsg(4);
      ?><input type="hidden" name="prevtopmenu" value="<?php echo $GLOBALS["prevtopmenu"]; ?>"><?php
   }
   adminformclose();
} // function frmSettingsForm()


function AdjustSettings()
{
	global $_POST, $EZ_SESSION_VARS;

	if ($EZ_SESSION_VARS["UserGroup"] == $GLOBALS["gsAdminPrivGroup"]) { $scriptsAllowed = 'Y'; } else { $scriptsAllowed = 'N'; }

	$sTopHTML		= trim(dbString($_POST["tvtophtml"]));
	if ($sTopHTML == '<br />') { $sTopHTML = '';
	} else {
		//	Adjust any absolute URLs to relative URLs for images and downloads
		$sTopHTML	= str_replace($GLOBALS["tqBlock1"].'./',$GLOBALS["tqBlock1"].'/',$sTopHTML);
		$sTopHTML	= str_replace($GLOBALS["base_url"],'./',$sTopHTML);
		$sTopHTML	= str_replace('<./','</',$sTopHTML);
		$sTopHTML	= str_replace('../','',$sTopHTML);
		//	Compile pre-compiled tags
		$sTopHTML	= trim(compile($GLOBALS["tqBlock1"].'html'.$GLOBALS["tqBlock2"].$sTopHTML.$GLOBALS["tqBlock1"].$GLOBALS["tqCloseBlock"].'html'.$GLOBALS["tqBlock2"], $EZ_SESSION_VARS["WYSIWYG"], 'Y', 'L', $scriptsAllowed));
	}
	$sSiteTitle		= trim(dbString($_POST["tvsitetitle"]));
	$sSiteDesc		= trim(dbString($_POST["tvsitedesc"]));
	$sSiteKeys		= trim(dbString($_POST["tvsitekeywords"]));
	$sFooter		= trim(dbString($_POST["tvfooter"]));
	if ($sFooter == '<br />') { $sFooter = '';
	} else {
		//	Adjust any absolute URLs to relative URLs for images and downloads
		$sFooter	= str_replace($GLOBALS["tqBlock1"].'./',$GLOBALS["tqBlock1"].'/',$sFooter);
		$sFooter	= str_replace($GLOBALS["base_url"],'./',$sFooter);
		$sFooter	= str_replace('<./','</',$sFooter);
		$sFooter	= str_replace('../','',$sFooter);
		//	Compile pre-compiled tags
		$sFooter	= trim(compile($GLOBALS["tqBlock1"].'html'.$GLOBALS["tqBlock2"].$sFooter.$GLOBALS["tqBlock1"].$GLOBALS["tqCloseBlock"].'html'.$GLOBALS["tqBlock2"], $EZ_SESSION_VARS["WYSIWYG"], 'Y', 'L', $scriptsAllowed ));
	}
	$sBreadcrumb	= trim(dbString($_POST["tvbreadcrumbseparator"]));

	$cssSettingsModified = False;
	if (UpdateSetting($sSiteTitle,'sitetitle'))											{ $cssSettingsModified = True; }
	if (UpdateSetting($sSiteDesc,'sitedesc'))											{ $cssSettingsModified = True; }
	if (UpdateSetting($sSiteKeys,'sitekeywords'))										{ $cssSettingsModified = True; }
	if (UpdateSetting($_POST["tvsitewidth"],'sitewidth'))						{ $cssSettingsModified = True; }
	if (UpdateSetting($_POST["tvsiteheight"],'siteheight'))					{ $cssSettingsModified = True; }
	if (UpdateSetting($_POST["tvsectionsecurity"],'sectionsecurity'))			{ $cssSettingsModified = True; }
	if (UpdateSetting($_POST["tvtopframe_height"],'topframe_height'))			{ $cssSettingsModified = True; }
	if (UpdateSetting($sTopHTML,'tophtml'))												{ $cssSettingsModified = True; }
	if (UpdateSetting($_POST["tvshowtopmenu"],'showtopmenu'))					{ $cssSettingsModified = True; }
	if (UpdateSetting($_POST["tvtopmenuframe_height"],'topmenuframe_height'))	{ $cssSettingsModified = True; }
	if (UpdateSetting($_POST["tvuserdataframe"],'userdataframe'))				{ $cssSettingsModified = True; }
	if (UpdateSetting($_POST["tvuserdataframe_width"],'userdataframewidth'))	{ $cssSettingsModified = True; }
	if (UpdateSetting($_POST["tvleftframe_width"],'leftframe_width'))			{ $cssSettingsModified = True; }
	if (UpdateSetting($_POST["tvmenuframealign"],'menuframealign'))			{ $cssSettingsModified = True; }
	if (UpdateSetting($_POST["tvexpandmenus"],'expandmenus'))					{ $cssSettingsModified = True; }
	if (UpdateSetting($_POST["tvcollapsemenus"],'collapsemenus'))				{ $cssSettingsModified = True; }
	if (UpdateSetting($_POST["tvhoverdisplay"],'hoverdisplay'))				{ $cssSettingsModified = True; }
	if (UpdateSetting($_POST["tvlrcontentframe"],'lrcontentframe'))			{ $cssSettingsModified = True; }
	if (UpdateSetting($_POST["tvbreadcrumb"],'breadcrumb'))					{ $cssSettingsModified = True; }
	if (UpdateSetting($sBreadcrumb,'breadcrumbseparator'))								{ $cssSettingsModified = True; }
	if (UpdateSetting($_POST["tvbookmark"],'bookmark'))						{ $cssSettingsModified = True; }
	if (UpdateSetting($_POST["tvrightcolumn_width"],'rightcolumnwidth'))		{ $cssSettingsModified = True; }
	if (UpdateSetting($_POST["tvimagecolumnbreak"],'imagecolumnbreak'))		{ $cssSettingsModified = True; }
	if (UpdateSetting($_POST["tvshowbanners"],'showbanners'))					{ $cssSettingsModified = True; }
	if (UpdateSetting($_POST["tvbottomframe"],'bottomframe'))					{ $cssSettingsModified = True; }
	if (UpdateSetting($_POST["tvbottomframe_height"],'bottomframe_height'))	{ $cssSettingsModified = True; }
	if (UpdateSetting($sFooter,'footer')) 												{ $cssSettingsModified = True; }

	if (($_POST["tvshowtopmenu"] == 'Y') && ($_POST["tvshowtopmenu"] != $_POST["prevtopmenu"])) {
		$strQuery = "UPDATE ".$GLOBALS["eztbGroups"]." SET topgroupname='999999999' WHERE topgroupname='0' OR topgroupname='' OR topgroupname IS NULL";
		$result = dbExecute($strQuery,true);
	}
	dbCommit();

	if ($cssSettingsModified) { RebuildStyleSheet(); }
} // function AdjustSettings()


function GetFormData()
{
	global $_POST;

	$GLOBALS["fsSitetitle"]				= $_POST["tvsitetitle"];
	$GLOBALS["fsSitedesc"]				= $_POST["tvsitedesc"];
	$GLOBALS["fsSitekeywords"]			= $_POST["tvsitekeywords"];
	$GLOBALS["fsSiteWidth"]				= $_POST["tvsitewidth"];
	$GLOBALS["fsSiteHeight"]			= $_POST["tvsiteheight"];
	$GLOBALS["fsSectionSecurity"]		= $_POST["tvsectionsecurity"];
	$GLOBALS["fnTopFrameHeight"]		= $_POST["tvtopframe_height"];
	$GLOBALS["fsTopHtml"]				= $_POST["tvtophtml"];
	$GLOBALS["fsShowTopMenu"]			= $_POST["tvshowtopmenu"];
	$GLOBALS["fnTopMenuFrameHeight"]	= $_POST["tvtopmenuframe_height"];
	$GLOBALS["fsUserdataFrame"]			= $_POST["tvuserdataframe"];
	$GLOBALS["fnUserdataFrameWidth"]	= $_POST["tvuserdataframe_width"];
	$GLOBALS["fnLeftFrameWidth"]		= $_POST["tvleftframe_width"];
	$GLOBALS["fsMenuFrameAlign"]		= $_POST["tvmenuframealign"];
	$GLOBALS["fsExpandMenus"]			= $_POST["tvexpandmenus"];
	$GLOBALS["fsCollapseMenus"]			= $_POST["tvcollapsemenus"];
	$GLOBALS["fsMenuHover"]				= $_POST["tvhoverdisplay"];
	$GLOBALS["fsLRContentFrame"]		= $_POST["tvlrcontentframe"];
	$GLOBALS["fnRightColumnWidth"]		= $_POST["tvrightcolumn_width"];
	$GLOBALS["fnImageColumnBreak"]		= $_POST["tvimagecolumnbreak"];
	$GLOBALS["fsBreadcrumb"]			= $_POST["tvbreadcrumb"];
	$GLOBALS["fnBreadcrumbSeparator"]	= $_POST["tvbreadcrumbseparator"];
	$GLOBALS["fsBookmark"]				= $_POST["tvbookmark"];
	$GLOBALS["fsShowBanners"]			= $_POST["tvshowbanners"];
	$GLOBALS["fsBottomFrame"]			= $_POST["tvbottomframe"];
	$GLOBALS["fnBottomFrameHeight"]		= $_POST["tvbottomframe_height"];
	$GLOBALS["fsFooter"]				= $_POST["tvfooter"];

	$GLOBALS["prevtopmenu"]				= $_POST["prevtopmenu"];
} // function GetFormData()


function GetGlobalData()
{
	global $EZ_SESSION_VARS;

	$GLOBALS["fsSitetitle"]				= $GLOBALS["gsSitetitle"];
	$GLOBALS["fsSitedesc"]				= $GLOBALS["gsSitedesc"];
	$GLOBALS["fsSitekeywords"]			= $GLOBALS["gsSitekeywords"];
	$GLOBALS["fsSiteWidth"]				= $GLOBALS["gsSiteWidth"];
	$GLOBALS["fsSiteHeight"]			= $GLOBALS["gsSiteHeight"];
	$GLOBALS["fsSectionSecurity"]		= $GLOBALS["gsSectionSecurity"];
	$GLOBALS["fnTopFrameHeight"]		= $GLOBALS["gnTopFrameHeight"];
	$GLOBALS["fsShowTopMenu"]			= $GLOBALS["gsShowTopMenu"];
	$GLOBALS["fnTopMenuFrameHeight"]	= $GLOBALS["gnTopMenuFrameHeight"];
	$GLOBALS["fsUserdataFrame"]			= $GLOBALS["gsUserdataFrame"];
	$GLOBALS["fnUserdataFrameWidth"]	= $GLOBALS["gnUserdataFrameWidth"];
	$GLOBALS["fnLeftFrameWidth"]		= $GLOBALS["gnLeftFrameWidth"];
	$GLOBALS["fsMenuFrameAlign"]		= $GLOBALS["gsMenuFrameAlign"];
	$GLOBALS["fsExpandMenus"]			= $GLOBALS["gsExpandMenus"];
	$GLOBALS["fsCollapseMenus"]			= $GLOBALS["gsCollapseMenus"];
	$GLOBALS["fsMenuHover"]				= $GLOBALS["gsMenuHover"];
	$GLOBALS["fsLRContentFrame"]		= $GLOBALS["gsLRContentFrame"];
	$GLOBALS["fnRightColumnWidth"]		= $GLOBALS["gnRightColumnWidth"];
	$GLOBALS["fnImageColumnBreak"]		= $GLOBALS["gnImageColumnBreak"];
	$GLOBALS["fsBreadcrumb"]			= $GLOBALS["gsBreadcrumb"];
	$GLOBALS["fnBreadcrumbSeparator"]	= $GLOBALS["gnBreadcrumbSeparator"];
	$GLOBALS["fsBookmark"]				= $GLOBALS["gsBookmark"];
	$GLOBALS["fsShowBanners"]			= $GLOBALS["gsShowBanners"];
	$GLOBALS["fsBottomFrame"]			= $GLOBALS["gnBottomFrame"];
	$GLOBALS["fnBottomFrameHeight"]		= $GLOBALS["gnBottomFrameHeight"];

	if ($EZ_SESSION_VARS["WYSIWYG"] == 'Y') {
		$GLOBALS["fsTopHtml"]		= formatWYSIWYG($GLOBALS["gsTopHtml"]);
		$GLOBALS["fsFooter"]		= formatWYSIWYG($GLOBALS["gsFooter"]);
	} else {
		$GLOBALS["fsTopHtml"]		= $GLOBALS["gsTopHtml"];
		$GLOBALS["fsFooter"]		= $GLOBALS["gsFooter"];
	}

	$GLOBALS["prevtopmenu"]			= $GLOBALS["gsShowTopMenu"];
} // function GetGlobalData()


function bCheckForm()
{
	global $_POST;

	$bFormOK = true;
	if (!is_numeric($_POST["tvtopframe_height"])) { $GLOBALS["strErrors"][] = $GLOBALS["eTopFrame"]; }
	if (($_POST["tvshowtopmenu"] == 'Y') && (!is_numeric($_POST["tvtopmenuframe_height"]))) {
		$GLOBALS["strErrors"][] = $GLOBALS["eTopmenuFrame"];
	}
	if (($_POST["tvuserdataframe"] == 'T') && (!is_numeric($_POST["tvuserdataframe_width"]))) {
		$GLOBALS["strErrors"][] = $GLOBALS["eUserdataFrame"];
	}
	if (!is_numeric($_POST["tvleftframe_width"])) {
		$GLOBALS["strErrors"][] = $GLOBALS["eMenuFrame"];
	}
	//if ($_POST["tvlrcontentframe"] != 'N')  {
	//	$GLOBALS["strErrors"][] = $GLOBALS["eRightFrame"];
	//}
	if (($_POST["tvbottomframe"] == 'Y') && (!is_numeric($_POST["tvbottomframe_height"]))) {
		$GLOBALS["strErrors"][] = $GLOBALS["eBottomFrame"];
	}

	if (isset($GLOBALS["strErrors"])) { $bFormOK = false; }
	return $bFormOK;
} // function bCheckForm()


include($GLOBALS["rootdp"]."include/javafuncs.php");

?>
