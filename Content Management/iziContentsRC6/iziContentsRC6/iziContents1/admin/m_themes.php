<?php

/***************************************************************************

 m_themes.php
 -------------
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

$GLOBALS["form"] = 'themes';
$GLOBALS["validaccess"] = VerifyAdminLogin();

includeLanguageFiles('admin','themes');


TestCurrentTheme($EZ_SESSION_VARS["Theme"]);
force_page_refresh();
frmThemes();


function frmThemes()
{
	global $_GET, $EZ_SESSION_VARS;

	adminheader();
	admintitle(6,$GLOBALS["tFormTitle"]);
	adminbuttons($GLOBALS["tViewTheme"],$GLOBALS["tAddNewTheme"],$GLOBALS["tEditTheme"],$GLOBALS["tDeleteTheme"]);
	$GLOBALS["iRelease"] = lsimagehtmltag($GLOBALS["icon_home"],'rel_button.gif',$GLOBALS["gsLanguage"],$GLOBALS["tReleaseTheme"],0);
	$iVisible	= lsimagehtmltag($GLOBALS["icon_home"],'green_dot.gif',$GLOBALS["gsLanguage"],$GLOBALS["tVisible"],0);
	$iHidden	= lsimagehtmltag($GLOBALS["icon_home"],'red_dot.gif',$GLOBALS["gsLanguage"],$GLOBALS["tHidden"],0);

	$strQuery = "SELECT themecode FROM ".$GLOBALS["eztbThemes"];
	$result = dbRetrieve($strQuery,true,0,0);
	$lRecCount = dbRowsReturned($result);
	dbFreeResult($result);

	$nCurrentPage = 0;
	if ($_GET["page"] != "") { $nCurrentPage = $_GET["page"]; }
	if ($_GET["sort"] == '') { $_GET["sort"] = 4; }
	$nPages = intval(($lRecCount - 0.5) / $GLOBALS["RECORDS_PER_PAGE"]) + 1;
	if ($nCurrentPage >= $nPages) { $nCurrentPage = 0; }
	$lStartRec = $nCurrentPage * $GLOBALS["RECORDS_PER_PAGE"];

	safeModeWarning(6);


	adminHdFt('themes',6,$nCurrentPage,$nPages,'');
	?>
	<tr class="teaserheadercontent">
		<?php
		adminlistitem(10,$GLOBALS["tEditDelRel"],'c');
		adminlistitem(10,$GLOBALS["tThemeCode"],'',1);
		adminlistitem(20,$GLOBALS["tThemeName"],'',2);
		adminlistitem(35,$GLOBALS["tThemeDescription"],'',3);
		adminlistitem(10,$GLOBALS["tThemeEnabled"],'c',4);
		adminlistitem(5,"&nbsp;",'c');
		?>
	</tr>

	<tr class="teasercontent">
		<td align="center" valign="top" class="content">
		</td>
		<td valign="top" class="content">
			<?php echo $GLOBALS["eztbMasterPrefix"]; ?>
		</td>
		<td valign="top" class="content">
			<?php echo $GLOBALS["tDefaultTheme"]; ?>
		<td valign="top" class="content">
			<?php echo $GLOBALS["tDefaultTheme"]; ?>
		</td>
		<td valign="top" align="center" class="content">
			<?php echo $iVisible; ?>
		</td>
		<td valign="top" align="center" class="content">
			 <?php
			 if ($EZ_SESSION_VARS["Theme"] != '') {
				 ?>
				 <a href="<?php echo BuildLink('selecttheme.php'); ?>&ThemeCode=" title="<?php echo $GLOBALS["tSelectTheme"]; ?>" <?php echo BuildLinkMouseOver($GLOBALS["tSelectTheme"]); ?>>
				 <img src="<?php echo $GLOBALS["rootdp"].$GLOBALS["icon_home"]; ?>checkbox_off.gif" border="0" alt="<?php echo $GLOBALS["tMakeAdmin"]; ?>">
				 </a>
				 <?php
			 } else {
				 ?><img src="<?php echo $GLOBALS["rootdp"].$GLOBALS["icon_home"]; ?>checkbox_on.gif" border="0" alt="<?php echo $GLOBALS["tCurrentTheme"]; ?>"><?php
			 }
			 ?>
		</td>
	</tr>
	<?php

	switch ($_GET["sort"])
	{
		case '1' :	$sort = 'themecode';
					 break;
		case '2' :	$sort = 'themename';
					 break;
		case '3' :	$sort = 'themedescription';
					 break;
		case '4' :	$sort = 'themeenabled DESC,themecode';
		default  :	$sort = 'themeenabled DESC,themecode';
	}
	$strQuery = "SELECT * FROM ".$GLOBALS["eztbThemes"]." ORDER BY ".$sort;
	$result = dbRetrieve($strQuery,true,$lStartRec,$GLOBALS["RECORDS_PER_PAGE"]);
	while ($rs = dbFetch($result)) {
		?>
		<tr class="teasercontent">
			<td align="center" valign="top" class="content">
				<?php
				admineditcheck('themesform','ThemeCode',$rs["themecode"],0);
				admindeletecheck('DelTheme','ThemeCode',$rs["themecode"]); ?>&nbsp;<?php
				themereleasecheck($rs["themecode"]); ?>&nbsp;
			</td>
			<td valign="top" class="content">
				<?php echo $rs["themecode"]; ?>
			</td>
			<td valign="top" class="content">
				<?php echo $rs["themename"]; ?>
			</td>
			<td valign="top" class="content">
				<?php echo htmlspecialchars($rs["themedescription"]); ?>
			</td>
			<td valign="top" align="center" class="content">
				<?php
				if ($rs["themeenabled"] == '1') { echo $iVisible; } else { echo $iHidden; }
				?>
			</td>
			<td valign="top" align="center" class="content">
				<?php
				if ($EZ_SESSION_VARS["Theme"] != $rs["themecode"]) {
					?>
					<a href="<?php echo BuildLink('selecttheme.php'); ?>&ThemeCode=<?php echo $rs["themecode"]; ?>" title="<?php echo $GLOBALS["tSelectTheme"]; ?>" <?php echo BuildLinkMouseOver($GLOBALS["tSelectTheme"]); ?>>
					<img src="<?php echo $GLOBALS["rootdp"].$GLOBALS["icon_home"]; ?>checkbox_off.gif" border="0" alt="<?php echo $GLOBALS["tMakeAdmin"]; ?>">
					</a>
					<?php
				} else {
					?><img src="<?php echo $GLOBALS["rootdp"].$GLOBALS["icon_home"]; ?>checkbox_on.gif" border="0" alt="<?php echo $GLOBALS["tCurrentTheme"]; ?>"><?php
				}
				?>
			</td>
		</tr>
		<?php
	}
	dbFreeResult($result);

	adminHdFt('themes',6,$nCurrentPage,$nPages,'');
	?>
	</table>
	</body>
	</html>
	<?php
} // function frmThemes()


function themereleasecheck($ThemeCode)
{
	global $_GET;

	if ($GLOBALS["canedit"] == False) { echo $GLOBALS["iBlank"];
	} else {
		?>
		<a href="javascript:RelTheme('ThemeCode=<?php echo $ThemeCode; ?>&page=<?php echo $_GET["page"]; ?>');" <?php echo BuildLinkMouseOver($GLOBALS["tRelease"]); ?>>
		<?php echo $GLOBALS["iRelease"]; ?></a><?php
	}
} // function themereleasecheck()


function TestCurrentTheme($Theme)
{
	global $EZ_SESSION_VARS;

	if ($Theme != '') {
		//  First see if the currently selected theme is actually a theme, and not a site.
		$strQuery = "SELECT themecode FROM ".$GLOBALS["eztbThemes"]." WHERE themecode='".$Theme."'";
		$sresult = dbRetrieve($strQuery,true,0,0);
		$sRecCount = dbRowsReturned($sresult);
		dbFreeResult($sresult);

		if ($sRecCount == 0) {
			//  Reset the session variable
			$EZ_SESSION_VARS["Theme"] = '';
			db_session_write();
		}
	}
} // function TestCurrentTheme()


?>
<script language="Javascript" type="text/javascript">
	<!-- Begin
	function DelTheme(sParams) {
		if (window.confirm('<?php echo $GLOBALS["tConfirmDeletion"]; ?>')) {
			location.href='<?php echo BuildLink('m_themesdel.php'); ?>&' + sParams;
		}
	}

	function RelTheme(sParams) {
		if (window.confirm('<?php echo $GLOBALS["tToggle"]; ?>')) {
			location.href='<?php echo BuildLink('m_themerel.php'); ?>&' + sParams;
		}
	}
	//  End -->
</script>
