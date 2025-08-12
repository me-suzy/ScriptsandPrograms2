<?php

/***************************************************************************

 m_sites.php
 ------------
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

$GLOBALS["form"] = 'sites';
$GLOBALS["validaccess"] = VerifyAdminLogin();

includeLanguageFiles('admin','sites');


force_page_refresh();
frmSites();


function frmSites()
{
	global $_GET, $EZ_SESSION_VARS;

	adminheader();
	admintitle(6,$GLOBALS["tFormTitle"]);
	adminbuttons($GLOBALS["tViewSite"],$GLOBALS["tAddNewSite"],$GLOBALS["tEditSite"],$GLOBALS["tDeleteSite"]);
	$GLOBALS["iRelease"] = lsimagehtmltag($GLOBALS["icon_home"],'rel_button.gif',$GLOBALS["gsLanguage"],$GLOBALS["tReleaseSite"],0);
	$iVisible	= lsimagehtmltag($GLOBALS["icon_home"],'green_dot.gif',$GLOBALS["gsLanguage"],$GLOBALS["tVisible"],0);
	$iHidden	= lsimagehtmltag($GLOBALS["icon_home"],'red_dot.gif',$GLOBALS["gsLanguage"],$GLOBALS["tHidden"],0);

	$strQuery = "SELECT sitecode FROM ".$GLOBALS["eztbSites"];
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


	adminHdFt('sites',6,$nCurrentPage,$nPages,'');
	?>
	<tr class="teaserheadercontent">
		<?php
		adminlistitem(10,$GLOBALS["tEditDelRel"],'c');
		adminlistitem(10,$GLOBALS["tSiteCode"],'',1);
		adminlistitem(20,$GLOBALS["tSiteName"],'',2);
		adminlistitem(35,$GLOBALS["tSiteDescription"],'',3);
		adminlistitem(10,$GLOBALS["tSiteEnabled"],'c',4);
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
			<?php echo $GLOBALS["tMasterSite"]; ?>
		<td valign="top" class="content">
			<?php echo $GLOBALS["tMasterSite"]; ?>
		</td>
		<td valign="top" align="center" class="content">
			<?php echo $iVisible; ?>
		</td>
		<td valign="top" align="center" class="content">
			<?php
			if ($EZ_SESSION_VARS["Site"] != '') {
				?>
				<a href="<?php echo BuildLink('selectsite.php'); ?>&SiteCode=" title="<?php echo $GLOBALS["tSelectSite"]; ?>" <?php echo BuildLinkMouseOver($GLOBALS["tSelectSite"]); ?>>
				<img src="<?php echo $GLOBALS["rootdp"].$GLOBALS["icon_home"]; ?>checkbox_off.gif" border="0" alt="<?php echo $GLOBALS["tMakeAdmin"]; ?>">
				</a>
				<?php
			} else {
				?><img src="<?php echo $GLOBALS["rootdp"].$GLOBALS["icon_home"]; ?>checkbox_on.gif" border="0" alt="<?php echo $GLOBALS["tCurrentSite"]; ?>"><?php
			}
			?>
		</td>
	</tr>
	<?php

	switch ($_GET["sort"]) {
		case '1' :	$sort = 'sitecode';
					break;
		case '2' :	$sort = 'sitename';
					break;
		case '3' :	$sort = 'sitedescription';
					break;
		case '4' :	$sort = 'siteenabled DESC,sitecode';
		default  :	$sort = 'siteenabled DESC,sitecode';
	}
	$strQuery = "SELECT * FROM ".$GLOBALS["eztbSites"]." ORDER BY ".$sort;
	$result = dbRetrieve($strQuery,true,$lStartRec,$GLOBALS["RECORDS_PER_PAGE"]);
	while ($rs = dbFetch($result)) {
		?>
		<tr class="teasercontent">
			<td align="center" valign="top" class="content">
				<?php
				admineditcheck('sitesform','SiteCode',$rs["sitecode"],0);
				admindeletecheck('DelSite','SiteCode',$rs["sitecode"]); ?>&nbsp;<?php
				sitereleasecheck($rs["sitecode"]); ?>&nbsp;
			</td>
			<td valign="top" class="content">
				<?php echo $rs["sitecode"]; ?>
			</td>
			<td valign="top" class="content">
				<?php echo $rs["sitename"]; ?>
			</td>
			<td valign="top" class="content">
				<?php echo htmlspecialchars($rs["sitedescription"]); ?>
			</td>
			<td valign="top" align="center" class="content">
				<?php if ($rs["siteenabled"] == '1') { echo $iVisible; } else { echo $iHidden; } ?>
			</td>
			<td valign="top" align="center" class="content">
				<?php
				if ($EZ_SESSION_VARS["Site"] != $rs["sitecode"]) {
					if ($GLOBALS["gsMultiSiteAuthors"] != 'Y') {
						$strQuery = "SELECT authorid FROM ".$rs["sitecode"]."authors WHERE authorid='".$EZ_SESSION_VARS["UserID"]."'";
					} else {
						$strQuery = "SELECT authorid FROM ".$GLOBALS["eztbAuthors"]." WHERE authorid='".$EZ_SESSION_VARS["UserID"]."'";
					}
					$sresult = dbRetrieve($strQuery,false,0,0);
					if ($sresult) {
						$sRecCount = dbRowsReturned($sresult);
						dbFreeResult($sresult);
						if ($sRecCount > 0) {
							?>
							<a href="<?php echo BuildLink('selectsite.php'); ?>&SiteCode=<?php echo $rs["sitecode"]; ?>" title="<?php echo $GLOBALS["tSelectSite"]; ?>" <?php echo BuildLinkMouseOver($GLOBALS["tSelectSite"]); ?>>
							<img src="<?php echo $GLOBALS["rootdp"].$GLOBALS["icon_home"]; ?>checkbox_off.gif" border="0" alt="<?php echo $GLOBALS["tSelectSite"]; ?>">
							</a>
							<?php
						} else { echo '&nbsp;'; }
					} else {
						//	MultiSiteAuthors was not enabled when this site was created, although it has since been set
						?>
						<a href="<?php echo BuildLink('selectsite.php'); ?>&SiteCode=<?php echo $rs["sitecode"]; ?>" title="<?php echo $GLOBALS["tSelectSite"]; ?>" <?php echo BuildLinkMouseOver($GLOBALS["tSelectSite"]); ?>>
						<img src="<?php echo $GLOBALS["rootdp"].$GLOBALS["icon_home"]; ?>checkbox_off.gif" border="0" alt="<?php echo $GLOBALS["tSelectSite"]; ?>">
						</a>
						<?php
					}
				} else {
					?><img src="<?php echo $GLOBALS["rootdp"].$GLOBALS["icon_home"]; ?>checkbox_on.gif" border="0" alt="<?php echo $GLOBALS["tCurrentSite"]; ?>"><?php
				}
				?>
			</td>
		</tr>
		<?php
	}
	dbFreeResult($result);

	adminHdFt('sites',6,$nCurrentPage,$nPages,'');
	?>
	</table>
	</body>
	</html>
	<?php
} // function frmSites()


function sitereleasecheck($SiteCode)
{
	global $_GET;

	if ($GLOBALS["canedit"] == False) {
		// No privilege
		echo $GLOBALS["iBlank"];
	} else {
		// Edit privilege
		?>
		<a href="javascript:RelSite('SiteCode=<?php echo $SiteCode; ?>&page=<?php echo $_GET["page"]; ?>');" <?php echo BuildLinkMouseOver($GLOBALS["tRelease"]); ?>>
		<?php echo $GLOBALS["iRelease"]; ?></a><?php
	}
} // function sitereleasecheck()


?>
<script language="Javascript" type="text/javascript">
	<!-- Begin
	function DelSite(sParams) {
		if (window.confirm('<?php echo $GLOBALS["tConfirmDeletion"]; ?>')) {
			location.href='<?php echo BuildLink('m_sitesdel.php'); ?>&' + sParams;
		}
	}

	function RelSite(sParams) {
		if (window.confirm('<?php echo $GLOBALS["tToggle"]; ?>')) {
			location.href='<?php echo BuildLink('m_siterel.php'); ?>&' + sParams;
		}
	}
	//  End -->
</script>
