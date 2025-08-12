<?php

/***************************************************************************

 m_colorsform.php
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
//	have tried to pass in the url
$GLOBALS["specialedit"] = $GLOBALS["canedit"] = $GLOBALS["canadd"] = $GLOBALS["canview"] = False;
$GLOBALS["fieldstatus"] = '';

// Validate the user's level of access for this form.
$GLOBALS["form"] = 'colorsform';
$validaccess = VerifyAdminLogin2();

includeLanguageFiles('admin','colorsform');


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
	adminformheader();
	adminformopen('tvbgcolor_main');
	adminformtitle(4,$GLOBALS["tFormTitle"]);
	if (isset($GLOBALS["strErrors"])) { formError(4); }
	adminsubheader(4,$GLOBALS["thBackground"]);
	?>
	<tr class="tablecontent">
		<?php FieldHeading("MainBackground","tvbgcolor_main"); ?>
		<td valign="top" class="content">
			<?php ColourField('tvbgcolor_main',$GLOBALS["fsBgcolor_main"]); ?>
		</td>
		<?php FieldHeading("BorderBackground","tvbgcolor_border"); ?>
		<td valign="top" class="content">
			<?php ColourField('tvbgcolor_border',$GLOBALS["fsBgcolor_border"]); ?>
		</td>
	</tr>
	<tr class="tablecontent">
		<?php FieldHeading("HeaderBackground","tvbgcolor_header"); ?>
		<?php
		if ($GLOBALS["gnBottomFrame"] == 'Y') {
			?>
			<td valign="top" class="content">
				<?php ColourField('tvbgcolor_header',$GLOBALS["fsBgcolor_header"]); ?>
			</td>
			<?php FieldHeading("FooterBackground","tvbgcolor_footer"); ?>
			<td valign="top" class="content">
				<?php ColourField('tvbgcolor_footer',$GLOBALS["fsBgcolor_footer"]); ?>
			</td>
			<?php
		} else {
			?>
			<td valign="top" class="content" colspan="3">
				<?php ColourField('tvbgcolor_header',$GLOBALS["fsBgcolor_header"]); ?>
			</td>
			<?php
		}
		?>
	</tr>
	<tr class="tablecontent">
		<?php FieldHeading("MenuBackground","tvbgcolor_menu"); ?>
		<?php
		if ($GLOBALS["gsShowTopMenu"] == 'Y') {
			?>
			<td valign="top" class="content">
				<?php ColourField('tvbgcolor_menu',$GLOBALS["fsBgcolor_menu"]); ?>
			</td>
			<?php FieldHeading("TopmenuBackground","tvbgcolor_topmenu"); ?>
			<td valign="top" class="content">
				<?php ColourField('tvbgcolor_topmenu',$GLOBALS["fsBgcolor_topmenu"]); ?>
			</td>
			<?php
		} else {
			?>
			<td valign="top" class="content" colspan="3">
				<?php ColourField('tvbgcolor_menu',$GLOBALS["fsBgcolor_menu"]); ?>
			</td>
			<?php
		}
		?>
	</tr>
	<?php adminsubheader(4,$GLOBALS["thFont"]); ?>
	<tr class="tablecontent">
		<?php FieldHeading("Font","tvfont1"); ?>
		<td colspan="3" valign="top" class="content">
			<select name="tvfont1" size="1"<?php echo $GLOBALS["fieldstatus"]; ?>><?php RenderFonts($GLOBALS["fsFont1"]); ?></select>
		</td>
	</tr>
	<tr class="tablecontent">
		<?php FieldHeading("TMFontSize","tvtopmenu_fontsize"); ?>
		<td valign="top" class="content">
			<select name="tvtopmenu_fontsize" size="1"<?php echo $GLOBALS["fieldstatus"]; ?>><?php RenderSizes($GLOBALS["fsTopMenuFontSize"]); ?></select>
		</td>
		<?php FieldHeading("TMFontStyle","tvtopmenu_fontstyle"); ?>
		<td valign="top" class="content">
			<select name="tvtopmenu_fontstyle" size="1"<?php echo $GLOBALS["fieldstatus"]; ?>><?php RenderStyles($GLOBALS["fsTopMenuFontStyle"]); ?></select>
		</td>
	</tr>
	<tr class="tablecontent">
		<?php FieldHeading("MFontSize","tvfontsize3"); ?>
		<td valign="top" class="content">
			<select name="tvfontsize3" size="1"<?php echo $GLOBALS["fieldstatus"]; ?>><?php RenderSizes($GLOBALS["fsFontSize3"]); ?></select>
		</td>
		<?php FieldHeading("MFontStyle","tvfontstyle3"); ?>
		<td valign="top" class="content">
			<select name="tvfontstyle3" size="1"<?php echo $GLOBALS["fieldstatus"]; ?>><?php RenderStyles($GLOBALS["fsFontStyle3"]); ?></select>
		</td>
	</tr>
	<tr class="tablecontent">
		<?php FieldHeading("SLinkFontSize","tvsmallfontsize"); ?>
		<td valign="top" class="content">
			<select name="tvsmallfontsize" size="1"<?php echo $GLOBALS["fieldstatus"]; ?>><?php RenderSizes($GLOBALS["fsSmallFontSize"]); ?></select>
		</td>
		<?php FieldHeading("HeaderFontSize","tvfontsize2"); ?>
		<td valign="top" class="content">
			<select name="tvfontsize2" size="1"<?php echo $GLOBALS["fieldstatus"]; ?>><?php RenderSizes($GLOBALS["fsFontSize2"]); ?></select>
		</td>
	</tr>
	<tr class="tablecontent">
		<?php FieldHeading("CFontSize","tvfontsize1"); ?>
		<td valign="top" class="content">
			<select name="tvfontsize1" size="1"<?php echo $GLOBALS["fieldstatus"]; ?>><?php RenderSizes($GLOBALS["fsFontSize1"]); ?></select>
		</td>
		<?php FieldHeading("CFontStyle","tvfontstyle1"); ?>
		<td valign="top" class="content">
			<select name="tvfontstyle1" size="1"<?php echo $GLOBALS["fieldstatus"]; ?>><?php RenderStyles($GLOBALS["fsFontStyle1"]); ?></select>
		</td>
	</tr>
	<tr class="tablecontent">
		<?php FieldHeading("RColFontSize","tvrcolfontsize"); ?>
		<td valign="top" class="content">
			<select name="tvrcolfontsize" size="1"<?php echo $GLOBALS["fieldstatus"]; ?>><?php RenderSizes($GLOBALS["fsRColFontSize"]); ?></select>
		</td>
		<?php FieldHeading("RColFontStyle","tvrcol_fontstyle"); ?>
		<td valign="top" class="content">
			<select name="tvrcol_fontstyle" size="1"<?php echo $GLOBALS["fieldstatus"]; ?>><?php RenderStyles($GLOBALS["fsRColFontStyle"]); ?></select>
		</td>
	</tr>
	<tr class="tablecontent">
		<?php FieldHeading("RColHeaderFontSize","tvrcol_headerfontsize"); ?>
		<td colspan="3" valign="top" class="content">
			<select name="tvrcol_headerfontsize" size="1"<?php echo $GLOBALS["fieldstatus"]; ?>><?php RenderSizes($GLOBALS["fsRColHeaderFontSize"]); ?></select>
		</td>
	</tr>
	<?php adminsubheader(4,$GLOBALS["thLinks"]); ?>
	<tr class="tablecontent">
		<?php FieldHeading("LinkColour","tvcolor_ahref"); ?>
		<td valign="top" class="content">
			<?php ColourField('tvcolor_ahref',$GLOBALS["fsColor_ahref"]); ?>
		</td>
		<?php FieldHeading("LinkColourHover","tvcolor_ahref_hover"); ?>
		<td valign="top" class="content">
			<?php ColourField('tvcolor_ahref_hover',$GLOBALS["fsColor_ahref_hover"]); ?>
		</td>
	</tr>
	<tr class="tablecontent">
		<?php FieldHeading("LinkColourHistory","tvcolor_ahref_visited"); ?>
		<td colspan="3" valign="top" class="content">
			<?php ColourField('tvcolor_ahref_visited',$GLOBALS["fsColor_ahref_visited"]); ?>
		</td>
	</tr>
	<tr class="tablecontent">
		<?php FieldHeading("SLinkColour","tvcolor_ahref_small"); ?>
		<td valign="top" class="content">
			<?php ColourField('tvcolor_ahref_small',$GLOBALS["fsColor_ahref_small"]); ?>
		</td>
		<?php FieldHeading("SLinkColourHover","tvcolor_ahref_small_hover"); ?>
		<td valign="top" class="content">
			<?php ColourField('tvcolor_ahref_small_hover',$GLOBALS["fsColor_ahref_small_hover"]); ?>
		</td>
	</tr>
	<tr class="tablecontent">
		<?php FieldHeading("SLinkColourHistory","tvcolor_ahref_small_visited"); ?>
		<td colspan="3" valign="top" class="content">
			<?php ColourField('tvcolor_ahref_small_visited',$GLOBALS["fsColor_ahref_small_visited"]); ?>
		</td>
	</tr>

	<tr class="tablecontent">
		<?php FieldHeading("MenuLinkColour","tvmenu_color_ahref"); ?>
		<td valign="top" class="content">
			<?php ColourField('tvmenu_color_ahref',$GLOBALS["fsMenu_Color_ahref"]); ?>
		</td>
		<?php FieldHeading("MenuLinkColourHover","tvmenu_color_ahref_hover"); ?>
		<td valign="top" class="content">
			<?php ColourField('tvmenu_color_ahref_hover',$GLOBALS["fsMenu_Color_ahref_hover"]); ?>
		</td>
	</tr>
	<tr class="tablecontent">
		<?php FieldHeading("MenuLinkColourHistory","tvmenu_color_ahref_visited"); ?>
		<td colspan="3" valign="top" class="content">
			<?php ColourField('tvmenu_color_ahref_visited',$GLOBALS["fsMenu_Color_ahref_visited"]); ?>
		</td>
	</tr>
	<tr class="tablecontent">
		<?php FieldHeading("TopMenuLinkColour","tvtopmenu_color_ahref"); ?>
		<td valign="top" class="content">
			<?php ColourField('tvtopmenu_color_ahref',$GLOBALS["fsTopMenu_Color_ahref"]); ?>
		</td>
		<?php FieldHeading("TopMenuLinkColourHover","tvtopmenu_color_ahref_hover"); ?>
		<td valign="top" class="content">
			<?php ColourField('tvtopmenu_color_ahref_hover',$GLOBALS["fsTopMenu_Color_ahref_hover"]); ?>
		</td>
	</tr>
	<tr class="tablecontent">
		<?php FieldHeading("TopMenuLinkColourHistory","tvtopmenu_color_ahref_visited"); ?>
		<td colspan="3" valign="top" class="content">
			<?php ColourField('tvtopmenu_color_ahref_visited',$GLOBALS["fsTopMenu_Color_ahref_visited"]); ?>
		</td>
	</tr>
	<tr class="tablecontent">
		<?php FieldHeading("RColLinkColour","tvrcol_color_ahref"); ?>
		<td valign="top" class="content">
			<?php ColourField('tvrcol_color_ahref',$GLOBALS["fsRCol_Color_ahref"]); ?>
		</td>
		<?php FieldHeading("RColLinkColourHover","tvrcol_color_ahref_hover"); ?>
		<td valign="top" class="content">
			<?php ColourField('tvrcol_color_ahref_hover',$GLOBALS["fsRCol_Color_ahref_hover"]); ?>
		</td>
	</tr>
	<tr class="tablecontent">
		<?php FieldHeading("RColLinkColourHistory","tvrcol_color_ahref_visited"); ?>
		<td colspan="3" valign="top" class="content">
			<?php ColourField('tvrcol_color_ahref_visited',$GLOBALS["fsRCol_Color_ahref_visited"]); ?>
		</td>
	</tr>

	<?php adminsubheader(4,$GLOBALS["thContent"]); ?>
	<tr class="tablecontent">
		<?php FieldHeading("CBorder","tvbgcolor_headercnt"); ?>
		<td valign="top" class="content">
			<?php ColourField('tvbgcolor_headercnt',$GLOBALS["fsBgcolor_headercnt"]); ?>
		</td>
		<?php FieldHeading("CHeader","tvcolor_header"); ?>
		<td valign="top" class="content">
			<?php ColourField('tvcolor_header',$GLOBALS["fsColor_header"]); ?>
		</td>
	</tr>
	<tr class="tablecontent">
		<?php FieldHeading("CBackground","tvbgcolor_cnttbl"); ?>
		<td valign="top" class="content">
			<?php ColourField('tvbgcolor_cnttbl',$GLOBALS["fsBgcolor_cnttbl"]); ?>
		</td>
		<?php FieldHeading("CFont","tvcolor_td"); ?>
		<td valign="top" class="content">
			<?php ColourField('tvcolor_td',$GLOBALS["fsColor_td"]); ?>
		</td>
	</tr>
	<?php adminsubheader(4,$GLOBALS["thTeaser"]); ?>
	<tr class="tablecontent">
		<?php FieldHeading("TBorder","tvbgcolor_headertsr"); ?>
		<td valign="top" class="content">
			<?php ColourField('tvbgcolor_headertsr',$GLOBALS["fsBgcolor_headertsr"]); ?>
		</td>
		<?php FieldHeading("THeader","tvcolor_tsrheader"); ?>
		<td valign="top" class="content">
			<?php ColourField('tvcolor_tsrheader',$GLOBALS["fsColor_tsrheader"]); ?>
		</td>
	</tr>
	<tr class="tablecontent">
		<?php FieldHeading("TBackground","tvbgcolor_tsrtbl"); ?>
		<td valign="top" class="content">
			<?php ColourField('tvbgcolor_tsrtbl',$GLOBALS["fsBgcolor_tsrtbl"]); ?>
		</td>
		<?php FieldHeading("TFont","tvcolor_tsrtd"); ?>
		<td valign="top" class="content">
			<?php ColourField('tvcolor_tsrtd',$GLOBALS["fsColor_tsrtd"]); ?>
		</td>
	</tr>


	<?php adminsubheader(4,$GLOBALS["thRColContent"]); ?>
	<tr class="tablecontent">
		<?php FieldHeading("RColCBorder","tvrcol_bgcolor_headercnt"); ?>
		<td valign="top" class="content">
			<?php ColourField('tvrcol_bgcolor_headercnt',$GLOBALS["fsRColBgcolor_headercnt"]); ?>
		</td>
		<?php FieldHeading("RColCHeader","tvrcol_color_header"); ?>
		<td valign="top" class="content">
			<?php ColourField('tvrcol_color_header',$GLOBALS["fsRColColor_header"]); ?>
		</td>
	</tr>
	<tr class="tablecontent">
		<?php FieldHeading("RColCBackground","tvrcol_bgcolor_cnttbl"); ?>
		<td valign="top" class="content">
			<?php ColourField('tvrcol_bgcolor_cnttbl',$GLOBALS["fsRColBgcolor_cnttbl"]); ?>
		</td>
		<?php FieldHeading("RColCFont","tvrcol_color_td"); ?>
		<td valign="top" class="content">
			<?php ColourField('tvrcol_color_td',$GLOBALS["fsRColColor_td"]); ?>
		</td>
	</tr>


	<?php
	adminformsavebar(4,'start.php');
	if ($GLOBALS["specialedit"] == True) { adminhelpmsg(4); }
	adminformclose();
} // function frmSettingsForm()


function GetFormData()
{
	global $_POST;

	$GLOBALS["fsBgcolor_main"]					= $_POST["tvbgcolor_main"];
	$GLOBALS["fsBgcolor_header"]				= $_POST["tvbgcolor_header"];
	$GLOBALS["fsBgcolor_footer"]				= $_POST["tvbgcolor_footer"];
	$GLOBALS["fsBgcolor_menu"]					= $_POST["tvbgcolor_menu"];
	$GLOBALS["fsBgcolor_topmenu"]				= $_POST["tvbgcolor_topmenu"];
	$GLOBALS["fsBgcolor_border"]				= $_POST["tvbgcolor_border"];
	$GLOBALS["fsFont1"]							= $_POST["tvfont1"];
	$GLOBALS["fsFontSize2"]						= $_POST["tvfontsize2"];
	$GLOBALS["fsRColHeaderFontSize"]			= $_POST["tvrcol_headerfontsize"];
	$GLOBALS["fsTopMenuFontSize"]				= $_POST["tvtopmenu_fontsize"];
	$GLOBALS["fsTopMenuFontStyle"]				= $_POST["tvtopmenu_fontstyle"];
	$GLOBALS["fsFontSize3"]						= $_POST["tvfontsize3"];
	$GLOBALS["fsFontStyle3"]					= $_POST["tvfontstyle3"];
	$GLOBALS["fsColor_ahref"]					= $_POST["tvcolor_ahref"];
	$GLOBALS["fsColor_ahref_hover"]				= $_POST["tvcolor_ahref_hover"];
	$GLOBALS["fsColor_ahref_visited"]			= $_POST["tvcolor_ahref_visited"];
	$GLOBALS["fsColor_ahref_small"]				= $_POST["tvcolor_ahref_small"];
	$GLOBALS["fsColor_ahref_small_hover"]		= $_POST["tvcolor_ahref_small_hover"];
	$GLOBALS["fsColor_ahref_small_visited"]		= $_POST["tvcolor_ahref_small_visited"];
	$GLOBALS["fsMenu_Color_ahref"]				= $_POST["tvmenu_color_ahref"];
	$GLOBALS["fsMenu_Color_ahref_hover"]		= $_POST["tvmenu_color_ahref_hover"];
	$GLOBALS["fsMenu_Color_ahref_visited"]		= $_POST["tvmenu_color_ahref_visited"];
	$GLOBALS["fsTopMenu_Color_ahref"]			= $_POST["tvtopmenu_color_ahref"];
	$GLOBALS["fsTopMenu_Color_ahref_hover"]		= $_POST["tvtopmenu_color_ahref_hover"];
	$GLOBALS["fsTopMenu_Color_ahref_visited"]	= $_POST["tvtopmenu_color_ahref_visited"];
	$GLOBALS["fsRCol_Color_ahref"]				= $_POST["tvrcol_color_ahref"];
	$GLOBALS["fsRCol_Color_ahref_hover"]		= $_POST["tvrcol_color_ahref_hover"];
	$GLOBALS["fsRCol_Color_ahref_visited"]		= $_POST["tvrcol_color_ahref_visited"];
	$GLOBALS["fsSmallFontSize"]					= $_POST["tvsmallfontsize"];
	$GLOBALS["fsBgcolor_headercnt"]				= $_POST["tvbgcolor_headercnt"];
	$GLOBALS["fsColor_header"]					= $_POST["tvcolor_header"];
	$GLOBALS["fsBgcolor_cnttbl"]				= $_POST["tvbgcolor_cnttbl"];
	$GLOBALS["fsColor_td"]						= $_POST["tvcolor_td"];
	$GLOBALS["fsBgcolor_headertsr"]				= $_POST["tvbgcolor_headertsr"];
	$GLOBALS["fsColor_tsrheader"]				= $_POST["tvcolor_tsrheader"];
	$GLOBALS["fsBgcolor_tsrtbl"]				= $_POST["tvbgcolor_tsrtbl"];
	$GLOBALS["fsColor_tsrtd"]					= $_POST["tvcolor_tsrtd"];
	$GLOBALS["fsFontSize1"]						= $_POST["tvfontsize1"];
	$GLOBALS["fsRColFontSize"]					= $_POST["tvrcolfontsize"];
	$GLOBALS["fsRColFontStyle"]					= $_POST["tvrcol_fontstyle"];
	$GLOBALS["fsFontStyle1"]					= $_POST["tvfontstyle1"];
	$GLOBALS["fsRColBgcolor_headercnt"]			= $_POST["tvrcol_bgcolor_headercnt"];
	$GLOBALS["fsRColColor_header"]				= $_POST["tvrcol_color_header"];
	$GLOBALS["fsRColBgcolor_cnttbl"]			= $_POST["tvrcol_bgcolor_cnttbl"];
	$GLOBALS["fsRColColor_td"]					= $_POST["tvrcol_color_td"];

} // function GetFormData()


function GetGlobalData()
{
	$GLOBALS["fsBgcolor_main"]					= $GLOBALS["bgcolor_main"];
	$GLOBALS["fsBgcolor_header"]				= $GLOBALS["bgcolor_header"];
	$GLOBALS["fsBgcolor_footer"]				= $GLOBALS["bgcolor_footer"];
	$GLOBALS["fsBgcolor_menu"]					= $GLOBALS["bgcolor_menu"];
	$GLOBALS["fsBgcolor_topmenu"]				= $GLOBALS["bgcolor_topmenu"];
	$GLOBALS["fsBgcolor_border"]				= $GLOBALS["bgcolor_border"];
	$GLOBALS["fsFont1"]							= $GLOBALS["gsFont1"];
	$GLOBALS["fsFontSize2"]						= $GLOBALS["gsFontSize2"];
	$GLOBALS["fsRColHeaderFontSize"]			= $GLOBALS["gsRColHeaderFontSize"];
	$GLOBALS["fsTopMenuFontSize"]				= $GLOBALS["gsTopMenuFontSize"];
	$GLOBALS["fsTopMenuFontStyle"]				= $GLOBALS["gsTopMenuFontStyle"];
	$GLOBALS["fsFontSize3"]						= $GLOBALS["gsFontSize3"];
	$GLOBALS["fsFontStyle3"]					= $GLOBALS["gsFontStyle3"];
	$GLOBALS["fsColor_ahref"]					= $GLOBALS["color_ahref"];
	$GLOBALS["fsColor_ahref_hover"]				= $GLOBALS["color_ahref_hover"];
	$GLOBALS["fsColor_ahref_visited"]			= $GLOBALS["color_ahref_visited"];
	$GLOBALS["fsMenu_Color_ahref"]				= $GLOBALS["menu_color_ahref"];
	$GLOBALS["fsMenu_Color_ahref_hover"]		= $GLOBALS["menu_color_ahref_hover"];
	$GLOBALS["fsMenu_Color_ahref_visited"]		= $GLOBALS["menu_color_ahref_visited"];
	$GLOBALS["fsTopMenu_Color_ahref"]			= $GLOBALS["topmenu_color_ahref"];
	$GLOBALS["fsTopMenu_Color_ahref_hover"]		= $GLOBALS["topmenu_color_ahref_hover"];
	$GLOBALS["fsTopMenu_Color_ahref_visited"]	= $GLOBALS["topmenu_color_ahref_visited"];
	$GLOBALS["fsRCol_Color_ahref"]				= $GLOBALS["rcol_color_ahref"];
	$GLOBALS["fsRCol_Color_ahref_hover"]		= $GLOBALS["rcol_color_ahref_hover"];
	$GLOBALS["fsRCol_Color_ahref_visited"]		= $GLOBALS["rcol_color_ahref_visited"];
	$GLOBALS["fsColor_ahref_small"]				= $GLOBALS["color_ahref_small"];
	$GLOBALS["fsColor_ahref_small_hover"]		= $GLOBALS["color_ahref_small_hover"];
	$GLOBALS["fsColor_ahref_small_visited"]		= $GLOBALS["color_ahref_small_visited"];
	$GLOBALS["fsSmallFontSize"]					= $GLOBALS["gsSmallFontSize"];
	$GLOBALS["fsBgcolor_headercnt"]				= $GLOBALS["bgcolor_headercnt"];
	$GLOBALS["fsColor_header"]					= $GLOBALS["color_header"];
	$GLOBALS["fsBgcolor_cnttbl"]				= $GLOBALS["bgcolor_cnttbl"];
	$GLOBALS["fsColor_td"]						= $GLOBALS["color_td"];
	$GLOBALS["fsBgcolor_headertsr"]				= $GLOBALS["gsBgcolor_headertsr"];
	$GLOBALS["fsColor_tsrheader"]				= $GLOBALS["gsColor_tsrheader"];
	$GLOBALS["fsBgcolor_tsrtbl"]				= $GLOBALS["gsBgcolor_tsrtbl"];
	$GLOBALS["fsColor_tsrtd"]					= $GLOBALS["gsColor_tsrtd"];
	$GLOBALS["fsFontSize1"]						= $GLOBALS["gsFontSize1"];
	$GLOBALS["fsRColFontSize"]					= $GLOBALS["gsRColFontSize"];
	$GLOBALS["fsRColFontStyle"]					= $GLOBALS["gsRColFontStyle"];
	$GLOBALS["fsFontStyle1"]					= $GLOBALS["gsFontStyle1"];
	$GLOBALS["fsRColBgcolor_headercnt"]			= $GLOBALS["rcol_bgcolor_headercnt"];
	$GLOBALS["fsRColColor_header"]				= $GLOBALS["rcol_color_header"];
	$GLOBALS["fsRColBgcolor_cnttbl"]			= $GLOBALS["rcol_bgcolor_cnttbl"];
	$GLOBALS["fsRColColor_td"]					= $GLOBALS["rcol_color_td"];
} // function GetGlobalData()


function AdjustSettings()
{
	global $_POST;

	$cssSettingsModified = False;
	if (UpdateSetting($_POST["tvbgcolor_main"],'bgcolor_main'))								{ $cssSettingsModified = True; }
	if (UpdateSetting($_POST["tvbgcolor_header"],'bgcolor_header'))							{ $cssSettingsModified = True; }
	if (UpdateSetting($_POST["tvbgcolor_footer"],'bgcolor_footer'))							{ $cssSettingsModified = True; }
	if (UpdateSetting($_POST["tvbgcolor_menu"],'bgcolor_menu'))								{ $cssSettingsModified = True; }
	if (UpdateSetting($_POST["tvbgcolor_topmenu"],'bgcolor_topmenu'))							{ $cssSettingsModified = True; }
	if (UpdateSetting($_POST["tvbgcolor_border"],'bgcolor_border'))
	if (UpdateSetting($_POST["tvfont1"],'font1'))												{ $cssSettingsModified = True; }
	if (UpdateSetting($_POST["tvfontsize2"],'fontsize2'))										{ $cssSettingsModified = True; }
	if (UpdateSetting($_POST["tvrcol_headerfontsize"],'rcol_headerfontsize'))					{ $cssSettingsModified = True; }
	if (UpdateSetting($_POST["tvtopmenu_fontsize"],'topmenu_fontsize'))						{ $cssSettingsModified = True; }
	if (UpdateSetting($_POST["tvtopmenu_fontstyle"],'topmenu_fontstyle'))						{ $cssSettingsModified = True; }
	if (UpdateSetting($_POST["tvfontsize3"],'fontsize3'))										{ $cssSettingsModified = True; }
	if (UpdateSetting($_POST["tvfontstyle3"],'fontstyle3'))									{ $cssSettingsModified = True; }
	if (UpdateSetting($_POST["tvcolor_ahref"],'color_ahref'))									{ $cssSettingsModified = True; }
	if (UpdateSetting($_POST["tvcolor_ahref_hover"],'color_ahref_hover'))						{ $cssSettingsModified = True; }
	if (UpdateSetting($_POST["tvcolor_ahref_visited"],'color_ahref_visited'))					{ $cssSettingsModified = True; }
	if (UpdateSetting($_POST["tvmenu_color_ahref"],'menu_color_ahref'))						{ $cssSettingsModified = True; }
	if (UpdateSetting($_POST["tvmenu_color_ahref_hover"],'menu_color_ahref_hover'))			{ $cssSettingsModified = True; }
	if (UpdateSetting($_POST["tvmenu_color_ahref_visited"],'menu_color_ahref_visited')) 		{ $cssSettingsModified = True; }
	if (UpdateSetting($_POST["tvtopmenu_color_ahref"],'topmenu_color_ahref'))					{ $cssSettingsModified = True; }
	if (UpdateSetting($_POST["tvtopmenu_color_ahref_hover"],'topmenu_color_ahref_hover'))		{ $cssSettingsModified = True; }
	if (UpdateSetting($_POST["tvtopmenu_color_ahref_visited"],'topmenu_color_ahref_visited'))	{ $cssSettingsModified = True; }
	if (UpdateSetting($_POST["tvrcol_color_ahref"],'rcol_color_ahref'))						{ $cssSettingsModified = True; }
	if (UpdateSetting($_POST["tvrcol_color_ahref_hover"],'rcol_color_ahref_hover'))			{ $cssSettingsModified = True; }
	if (UpdateSetting($_POST["tvrcol_color_ahref_visited"],'rcol_color_ahref_visited'))		{ $cssSettingsModified = True; }
	if (UpdateSetting($_POST["tvcolor_ahref_small"],'color_ahref_small'))						{ $cssSettingsModified = True; }
	if (UpdateSetting($_POST["tvcolor_ahref_small_hover"],'color_ahref_small_hover'))			{ $cssSettingsModified = True; }
	if (UpdateSetting($_POST["tvcolor_ahref_small_visited"],'color_ahref_small_visited'))		{ $cssSettingsModified = True; }
	if (UpdateSetting($_POST["tvsmallfontsize"],'smallfontsize'))								{ $cssSettingsModified = True; }
	if (UpdateSetting($_POST["tvbgcolor_headercnt"],'bgcolor_headercnt'))						{ $cssSettingsModified = True; }
	if (UpdateSetting($_POST["tvcolor_header"],'color_header'))								{ $cssSettingsModified = True; }
	if (UpdateSetting($_POST["tvbgcolor_cnttbl"],'bgcolor_cnttbl'))							{ $cssSettingsModified = True; }
	if (UpdateSetting($_POST["tvcolor_td"],'color_td'))										{ $cssSettingsModified = True; }
	if (UpdateSetting($_POST["tvbgcolor_headertsr"],'bgcolor_headertsr'))						{ $cssSettingsModified = True; }
	if (UpdateSetting($_POST["tvcolor_tsrheader"],'color_tsrheader'))							{ $cssSettingsModified = True; }
	if (UpdateSetting($_POST["tvbgcolor_tsrtbl"],'bgcolor_tsrtbl'))							{ $cssSettingsModified = True; }
	if (UpdateSetting($_POST["tvcolor_tsrtd"],'color_tsrtd'))									{ $cssSettingsModified = True; }
	if (UpdateSetting($_POST["tvfontsize1"],'fontsize1'))										{ $cssSettingsModified = True; }
	if (UpdateSetting($_POST["tvrcolfontsize"],'rcolfontsize'))								{ $cssSettingsModified = True; }
	if (UpdateSetting($_POST["tvrcol_fontstyle"],'rcol_fontstyle'))							{ $cssSettingsModified = True; }
	if (UpdateSetting($_POST["tvfontstyle1"],'fontstyle1'))									{ $cssSettingsModified = True; }
	if (UpdateSetting($_POST["tvrcol_bgcolor_headercnt"],'rcol_bgcolor_headercnt'))			{ $cssSettingsModified = True; }
	if (UpdateSetting($_POST["tvrcol_color_header"],'rcol_color_header'))						{ $cssSettingsModified = True; }
	if (UpdateSetting($_POST["tvrcol_bgcolor_cnttbl"],'rcol_bgcolor_cnttbl'))					{ $cssSettingsModified = True; }
	if (UpdateSetting($_POST["tvrcol_color_td"],'rcol_color_td'))								{ $cssSettingsModified = True; }
	dbCommit();

	if ($cssSettingsModified) { RebuildStyleSheet(); }
} // function AdjustSettings()


function testColorField($colourval)
{
	$invalidColour = false;
	if (substr($colourval,0,1) == '#') {
		if (strlen($colourval) != 7) { $invalidColour = true; }
	}
	return $invalidColour;
}


function bCheckForm()
{
	global $_POST;

	$bFormOK = true;
	if (testColorField($_POST["tvbgcolor_main"]))					{ $GLOBALS["strErrors"][] = $GLOBALS["eBGWrong"]; }
	if (testColorField($_POST["tvbgcolor_header"]))				{ $GLOBALS["strErrors"][] = $GLOBALS["eHeaderBGWrong"]; }
	if (testColorField($_POST["tvbgcolor_footer"]))				{ $GLOBALS["strErrors"][] = $GLOBALS["eFooterBGWrong"]; }
	if (testColorField($_POST["tvbgcolor_menu"]))					{ $GLOBALS["strErrors"][] = $GLOBALS["eMenuBGWrong"]; }
	if (testColorField($_POST["tvbgcolor_topmenu"]))				{ $GLOBALS["strErrors"][] = $GLOBALS["eTopmenuBGWrong"]; }
	if (testColorField($_POST["tvbgcolor_border"]))					{ $GLOBALS["strErrors"][] = $GLOBALS["eBorderBGWrong"]; }
	if (testColorField($_POST["tvcolor_ahref"]))					{ $GLOBALS["strErrors"][] = $GLOBALS["eLinkWrong"]; }
	if (testColorField($_POST["tvcolor_ahref_hover"]))				{ $GLOBALS["strErrors"][] = $GLOBALS["eLinkHoverWrong"]; }
	if (testColorField($_POST["tvcolor_ahref_visited"]))			{ $GLOBALS["strErrors"][] = $GLOBALS["eLinkVisitedWrong"]; }
	if (testColorField($_POST["tvmenu_color_ahref"]))				{ $GLOBALS["strErrors"][] = $GLOBALS["eMenuLinkWrong"]; }
	if (testColorField($_POST["tvmenu_color_ahref_hover"]))		{ $GLOBALS["strErrors"][] = $GLOBALS["eMenuLinkHoverWrong"]; }
	if (testColorField($_POST["tvmenu_color_ahref_visited"]))		{ $GLOBALS["strErrors"][] = $GLOBALS["eMenuLinkVisitedWrong"]; }
	if (testColorField($_POST["tvtopmenu_color_ahref"]))			{ $GLOBALS["strErrors"][] = $GLOBALS["eTopMenuLinkWrong"]; }
	if (testColorField($_POST["tvtopmenu_color_ahref_hover"]))		{ $GLOBALS["strErrors"][] = $GLOBALS["eTopMenuLinkHoverWrong"]; }
	if (testColorField($_POST["tvtopmenu_color_ahref_visited"]))	{ $GLOBALS["strErrors"][] = $GLOBALS["eTopMenuLinkVisitedWrong"]; }
	if (testColorField($_POST["tvrcol_color_ahref"]))				{ $GLOBALS["strErrors"][] = $GLOBALS["eRColLinkWrong"]; }
	if (testColorField($_POST["tvrcol_color_ahref_hover"]))		{ $GLOBALS["strErrors"][] = $GLOBALS["eRColLinkHoverWrong"]; }
	if (testColorField($_POST["tvrcol_color_ahref_visited"]))		{ $GLOBALS["strErrors"][] = $GLOBALS["eRColLinkVisitedWrong"]; }
	if (testColorField($_POST["tvcolor_ahref_small"]))				{ $GLOBALS["strErrors"][] = $GLOBALS["eSLinkWrong"]; }
	if (testColorField($_POST["tvcolor_ahref_small_hover"]))		{ $GLOBALS["strErrors"][] = $GLOBALS["eSLinkHoverWrong"]; }
	if (testColorField($_POST["tvcolor_ahref_small_visited"]))		{ $GLOBALS["strErrors"][] = $GLOBALS["eSLinkVisitedWrong"]; }
	if (testColorField($_POST["tvbgcolor_headercnt"]))				{ $GLOBALS["strErrors"][] = $GLOBALS["eCBorderWrong"]; }
	if (testColorField($_POST["tvcolor_header"]))					{ $GLOBALS["strErrors"][] = $GLOBALS["eCHeaderWrong"]; }
	if (testColorField($_POST["tvbgcolor_cnttbl"]))				{ $GLOBALS["strErrors"][] = $GLOBALS["eContentBGWrong"]; }
	if (testColorField($_POST["tvcolor_td"]))						{ $GLOBALS["strErrors"][] = $GLOBALS["eCFontWrong"]; }
	if (testColorField($_POST["tvbgcolor_headertsr"]))				{ $GLOBALS["strErrors"][] = $GLOBALS["eTBorderWrong"]; }
	if (testColorField($_POST["tvcolor_tsrheader"]))				{ $GLOBALS["strErrors"][] = $GLOBALS["eTHeaderWrong"]; }
	if (testColorField($_POST["tvbgcolor_tsrtbl"]))				{ $GLOBALS["strErrors"][] = $GLOBALS["eTeaserBGWrong"]; }
	if (testColorField($_POST["tvcolor_tsrtd"]))					{ $GLOBALS["strErrors"][] = $GLOBALS["eTFontWrong"]; }
	if (testColorField($_POST["tvrcol_bgcolor_headercnt"]))		{ $GLOBALS["strErrors"][] = $GLOBALS["eRColBorderWrong"]; }
	if (testColorField($_POST["tvrcol_color_header"]))				{ $GLOBALS["strErrors"][] = $GLOBALS["eRColHeaderWrong"]; }
	if (testColorField($_POST["tvrcol_bgcolor_cnttbl"]))			{ $GLOBALS["strErrors"][] = $GLOBALS["eRColBGWrong"]; }
	if (testColorField($_POST["tvrcol_color_td"]))					{ $GLOBALS["strErrors"][] = $GLOBALS["eRColFontWrong"]; }

	if (isset($GLOBALS["strErrors"])) { $bFormOK = false; }
	return $bFormOK;
} // function bCheckForm()


function RenderFonts($sFont)
{
	$fonts= array('Arial', 'Courier', 'Georgia', 'Helvetica', 'Sans-Serif', 'Times', 'Verdana', 'Tahoma', 'Trebuchet MS', 'Comic Sans MS'); 

	while($fontname = each($fonts)) {
		echo "<option";
		if ($sFont == $fontname[1]) { echo " selected"; }
		echo ">".$fontname[1]."\n";
	}
} // function RenderFonts()


function RenderSizes($sFontSize)
{
	for($i=6; $i<30; $i++) {
		echo "<option";
		if ($sFontSize == $i) { echo " selected"; }
		echo ">".$i."px\n";
	}
} // function RenderSizes()


function RenderStyles($sFontStyle)
{
	$styles= array('None', 'Underline', 'Overline', 'Line-through');

	while($stylename = each($styles)) {
		echo "<option";
		if($sFontStyle == $stylename[1]) { echo " selected"; }
		echo ">".$stylename[1]."\n";
	}
} // function RenderStyles()


include($GLOBALS["rootdp"]."include/javafuncs.php");


?>

<script language="JavaScript" type="text/JavaScript">
	function changeColor(inId, inColor) {
		inId.style.backgroundColor = inColor;
	}
</script>
