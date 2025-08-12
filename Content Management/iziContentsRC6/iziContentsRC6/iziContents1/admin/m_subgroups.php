<?php

/***************************************************************************

 m_subgroups.php
 ----------------
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

$GLOBALS["form"] = 'subgroups';
$GLOBALS["validaccess"] = VerifyAdminLogin();

includeLanguageFiles('admin','subgroups');


//  Set the default filter language to the user's language, unless it's been set
//      by the filter already.
if ((!isset($_GET["filterlangname"])) || ($_GET["filterlangname"] == "")) {
	$_GET["filterlangname"] = $GLOBALS["gsLanguage"];
}

force_page_refresh();
frmSubGroups();


function frmSubGroups()
{
	global $_SERVER, $_GET, $EzAdmin_Style;

	adminheader();
	admintitle(7,$GLOBALS["tFormTitle"]);
	adminbuttons($GLOBALS["tViewSubmenu"],$GLOBALS["tAddNewSubmenu"],$GLOBALS["tEditSubmenu"],$GLOBALS["tDeleteSubmenu"]);
	$GLOBALS["iTranslate"]	= adminbuttontest($GLOBALS["theme_home"],$EzAdmin_Style["EditIcon"],$GLOBALS["gsLanguage"],$GLOBALS["tTranslate"],0,'edit_button.gif');
	$GLOBALS["iTick"]		= lsimagehtmltag($GLOBALS["icon_home"],'tick.gif',$GLOBALS["gsLanguage"],$GLOBALS["tTranslated"],0);
	$GLOBALS["iCross"]		= lsimagehtmltag($GLOBALS["icon_home"],'cross.gif',$GLOBALS["gsLanguage"],$GLOBALS["tNotTranslated"],0);
	$iVisible	= lsimagehtmltag($GLOBALS["icon_home"],'green_dot.gif',$GLOBALS["gsLanguage"],$GLOBALS["tVisible"],0);
	$iHidden	= lsimagehtmltag($GLOBALS["icon_home"],'red_dot.gif',$GLOBALS["gsLanguage"],$GLOBALS["tHidden"],0);

	// We want the count of all subgroup items, not just those in the current language
	//    so we use the site default language for this check.
	if ($_GET["filtergroupname"] == "") {
		$strQuery = "SELECT subgroupname FROM ".$GLOBALS["eztbSubgroups"]." WHERE language='".$GLOBALS["gsDefault_language"]."'";
	} else {
		$strQuery = "SELECT subgroupname FROM ".$GLOBALS["eztbSubgroups"]." WHERE groupname = '".$_GET["filtergroupname"]."' AND language='".$GLOBALS["gsDefault_language"]."'";
	}
	$result = dbRetrieve($strQuery,true,0,0);
	$rs     = dbFetch($result);
	$lRecCount = dbRowsReturned($result);
	dbFreeResult($result);

	$nCurrentPage = 0;
	if ($_GET["sort"] == '') { $_GET["sort"] = 1; }
	if ($_GET["page"] != "") { $nCurrentPage = $_GET["page"]; }
	$nPages = intval(($lRecCount - 0.5) / $GLOBALS["RECORDS_PER_PAGE"]) + 1;
	if ($nCurrentPage >= $nPages) { $nCurrentPage = 0; }
	$lStartRec = $nCurrentPage * $GLOBALS["RECORDS_PER_PAGE"];

	?>
	<form action="<?php echo $GLOBALS["REQUEST_URI"]; ?>" method="GET" enctype="multipart/form-data">
	<tr class="teaserheadercontent">
		<td colspan="7" align="<?php echo $GLOBALS["left"]; ?>" nowrap>
			<b><?php echo $GLOBALS["tMenuFilter"]; ?>:</b>&nbsp;
			<select name="filtergroupname" size="1" onChange="submit();">
				<?php RenderGroups($_GET["filtergroupname"]); ?>
			</select>
			<?php
			if ($GLOBALS["gsMultiLanguage"] == 'Y') {
				?>
				&nbsp;<b><?php echo $GLOBALS["tLangFilter"]; ?>:</b>&nbsp;
				<select name="filterlangname" size="1" onChange="submit();">
					<?php RenderLanguages($_GET["filterlangname"]); ?>
				</select>&nbsp;
				<?php
			}
			?>
			<input type="image" name="submit" src="<?php echo $GLOBALS["rootdp"].$GLOBALS["icon_home"]; ?>go.gif" alt="Go" value="Go">
			<input type="hidden" name="ezSID" value="<?php echo $GLOBALS["ezSID"]; ?>">
			<input type="hidden" name="page" value="<?php echo $_GET["page"]; ?>">
			<input type="hidden" name="sort" value="<?php echo $_GET["sort"]; ?>">
		</td>
	</tr>
	</form>
	<?php

	frmSubGroupsHdFt(7,$nCurrentPage,$nPages);
	?>
	<tr class="teaserheadercontent">
		<?php
		adminlistitem(10,$GLOBALS["tEditDel"],'c');
		adminlistitem(30,$GLOBALS["tSubmenuTitle"],'',3);
		adminlistitem(25,$GLOBALS["tParentMenu"],'',6);
		adminlistitem(20,$GLOBALS["tMenuRef"],'',2);
		adminlistitem(10,$GLOBALS["tMLoginReq"],'c',4);
		adminlistitem(10,$GLOBALS["tVisible"],'c',5);
		adminlistitem(5,$GLOBALS["toOrderID"],'',1);
	?>
	</tr>
	<?php

	if ($_GET["filterlangname"] == $GLOBALS["gsDefault_language"]) {
		//	If we're working in the site default language, it's a simple sql statement to
		//		create the paged list.
		if ($_GET["filtergroupname"] == "") {
			$sqlQuery = "SELECT subgroupname,subgroupdesc,s.groupname as groupname,s.loginreq as loginreq,s.authorid as authorid,s.language as language,s.submenuvisible as menuvisible,g.grouporderid as grouporderid,s.subgrouporderid as subgrouporderid FROM ".$GLOBALS["eztbSubgroups"]." s LEFT JOIN ".$GLOBALS["eztbGroups"]." g ON g.groupname=s.groupname AND g.language=s.language LEFT JOIN ".$GLOBALS["eztbTopgroups"]." t ON t.topgroupname=g.topgroupname AND t.language=g.language WHERE s.language='".$GLOBALS["gsDefault_language"]."' ORDER BY t.topgrouporderid,g.grouporderid,s.subgrouporderid";
		} else {
			$sqlQuery = "SELECT subgroupname,subgroupdesc,s.groupname as groupname,s.loginreq as loginreq,s.authorid as authorid,s.language as language,s.submenuvisible as menuvisible,g.grouporderid as grouporderid,s.subgrouporderid as subgrouporderid FROM ".$GLOBALS["eztbSubgroups"]." s LEFT JOIN ".$GLOBALS["eztbGroups"]." g ON g.groupname=s.groupname AND g.language=s.language WHERE s.groupname = '".$_GET["filtergroupname"]."' AND s.language='".$GLOBALS["gsDefault_language"]."' ORDER BY grouporderid,subgrouporderid";
		}
		$result = dbRetrieve($sqlQuery,true,$lStartRec,0,0);
	} else {
		//	Things get slightly more complex if we want to display entries in the filter
		//		language where they're available, but in the base language where they're not.
		//		We build the list using a select in the base language first for the paging
		//		counts, and generate an array containing all the subgroupnames to be
		//		displayed on this page.
		if ($_GET["filtergroupname"] == "") {
			$sqlQuery = "SELECT subgroupname FROM ".$GLOBALS["eztbSubgroups"]." s LEFT JOIN ".$GLOBALS["eztbGroups"]." g ON g.groupname=s.groupname AND g.language=s.language LEFT JOIN ".$GLOBALS["eztbTopgroups"]." t ON t.topgroupname=g.topgroupname AND t.language=g.language WHERE s.language='".$GLOBALS["gsDefault_language"]."' ORDER BY t.topgrouporderid,g.grouporderid,s.subgrouporderid";
		} else {
			$sqlQuery = "SELECT subgroupname FROM ".$GLOBALS["eztbSubgroups"]." s LEFT JOIN ".$GLOBALS["eztbGroups"]." g ON g.groupname=s.groupname AND g.language=s.language LEFT JOIN ".$GLOBALS["eztbTopgroups"]." t ON t.topgroupname=g.topgroupname AND t.language=g.language WHERE s.groupname = '".$_GET["filtergroupname"]."' AND s.language='".$GLOBALS["gsDefault_language"]."' ORDER BY grouporderid,s.subgrouporderid";
		}
		$inlist = "";
		$result = dbRetrieve($sqlQuery,true,0,0);
		while ($rs = dbFetch($result)) { $inlistelements[] = "'".$rs["subgroupname"]."'"; }
		dbFreeResult($result);
		if (isset($inlistelements)) { $inlist = "subgroupname IN (". implode(',',$inlistelements).") AND"; }
		$lOrder = '';
		if ($_GET["filterlangname"] > $GLOBALS["gsDefault_language"]) { $lOrder = ' DESC'; }
		if ($_GET["filtergroupname"] == "") {
			$sqlQuery = "SELECT subgroupname,subgroupdesc,s.groupname as groupname,s.loginreq as loginreq,s.authorid as authorid,s.language as language,s.submenuvisible as menuvisible,g.grouporderid as grouporderid,s.subgrouporderid as subgrouporderid FROM ".$GLOBALS["eztbSubgroups"]." s LEFT JOIN ".$GLOBALS["eztbGroups"]." g ON g.groupname=s.groupname AND g.language='".$GLOBALS["gsDefault_language"]."' LEFT JOIN ".$GLOBALS["eztbTopgroups"]." t ON t.topgroupname=g.topgroupname AND t.language='".$GLOBALS["gsDefault_language"]."' WHERE ".$inlist." (s.language='".$_GET["filterlangname"]."' OR s.language='".$GLOBALS["gsDefault_language"]."') ORDER BY t.topgrouporderid,g.grouporderid,subgrouporderid,s.language".$lOrder;
		} else {
			$sqlQuery = "SELECT subgroupname,subgroupdesc,s.groupname as groupname,s.loginreq as loginreq,s.authorid as authorid,s.language as language,s.submenuvisible as menuvisible,g.grouporderid as grouporderid,s.subgrouporderid as subgrouporderid FROM ".$GLOBALS["eztbSubgroups"]." s LEFT JOIN ".$GLOBALS["eztbGroups"]." g ON g.groupname=s.groupname AND g.language='".$GLOBALS["gsDefault_language"]."' LEFT JOIN ".$GLOBALS["eztbTopgroups"]." t ON t.topgroupname=g.topgroupname AND t.language='".$GLOBALS["gsDefault_language"]."' WHERE ".$inlist." s.groupname = '".$_GET["filtergroupname"]."' AND (s.language='".$_GET["filterlangname"]."' OR s.language='".$GLOBALS["gsDefault_language"]."') ORDER BY t.topgrouporderid,g.grouporderid,subgrouporderid,s.language".$lOrder;
		}
		$result = dbRetrieve($sqlQuery,true,0,0);
	}

	//	Transfer our SQL query results to another array ($Menus) filtering out the duplicates as we do so
	//		This is the array we'll use to handle the actual sorting and then reduce it to a single page of
	//		entries for display
	$m = 0;
	$nMenuName = '';
	while ($rs = dbFetch($result)) {
		//	Filter out default language entries where we have duplicates.
		//		This will only apply if we're filtering on a language other than the default.
		//	We also filter out previous versions of the same article through this routine.
		if ($rs["subgroupname"] != $nMenuName) {
			$nMenuName = $rs["subgroupname"];

			$Menus[$m]["groupname"]			= $rs["groupname"];
			$Menus[$m]["subgroupname"]		= $rs["subgroupname"];
			$Menus[$m]["language"]			= $rs["language"];
			$Menus[$m]["authorid"]			= $rs["authorid"];
			$Menus[$m]["subgroupdesc"]		= $rs["subgroupdesc"];
			$Menus[$m]["loginreq"]			= $rs["loginreq"];
			$Menus[$m]["menuvisible"]		= $rs["menuvisible"];
			$Menus[$m]["versionref"]		= $rs["versionref"];
			$Menus[$m]["subgrouporderid"]	= $rs["subgrouporderid"];
			$m++;
		}
	}
	dbFreeResult($result);

	if (isset($Menus)) {
		switch ($_GET["sort"]) {
			case '1' : $Menus = array_csort($Menus,'groupname','subgrouporderid','subgroupname');
					   break;
			case '2' : $Menus = array_csort($Menus,'subgroupname');
					   break;
			case '3' : $Menus = array_csort($Menus,'subgroupdesc','subgroupname');
					   break;
			case '4' : $Menus = array_csort($Menus,'loginreq','subgroupname');
					   break;
			case '5' : $Menus = array_csort($Menus,'menuvisible','subgroupname');
					   break;
			case '6' : $Menus = array_csort($Menus,'groupname','subgroupname');
					   break;
			default  : $Menus = array_csort($Menus,'groupname','subgrouporderid','subgroupname');
		}
	}

	$i = $lStartRec;
	$j = $lStartRec + $GLOBALS["RECORDS_PER_PAGE"];
	if ($j > $m) { $j = $m; }
	for ($c=$i; $c<$j; $c++) {
		?>
		<tr class="teasercontent">
			<td align="center" valign="top" class="content">
				<?php
				if ($_GET["filterlangname"] != $GLOBALS["gsDefault_language"]) {
					admintranslatecheck('tsubgroupsform','SubGroupName',$Menus[$c]["subgroupname"],'LanguageCode',$_GET["filterlangname"]);
				} else {
					admineditcheck('subgroupsform','SubGroupName',$Menus[$c]["subgroupname"],$Menus[$c]["authorid"]);
				}
				admindeletecheck('DelSubGroup','SubGroupName',$Menus[$c]["subgroupname"]);
				?>
			</td>
			<td valign="top" class="content">
				<?php
				if ($_GET["filterlangname"] != $GLOBALS["gsDefault_language"]) {
					if ($Menus[$c]["language"] != $_GET["filterlangname"]) { echo $GLOBALS["iCross"].'&nbsp;'; } else { echo $GLOBALS["iTick"].'&nbsp;'; }
				}
				echo $Menus[$c]["subgroupdesc"];
				?>
			</td>
			<td valign="top" class="content">
				<?php echo sGetGroupName($Menus[$c]["groupname"]); ?>
			</td>			
			<td valign="top" class="content">
				<?php echo $Menus[$c]["subgroupname"]; ?>
			</td>
			<td align="center" valign="top" class="content">
				<?php if ($Menus[$c]["loginreq"] == 'Y') { echo $GLOBALS["tYes"]; } else { echo '&nbsp;'; } ?>
			</td>
			<td valign="top" align="center" class="content">
				<?php if ($Menus[$c]["menuvisible"] == 'Y') { echo $iVisible; } else { echo $iHidden; } ?>
			</td>
			<td align="center" valign="top" class="content">
				<?php
				if ($_GET["sort"] == 1) {
					adminmovecheck('up','SubGroupMove','SubGroupName',$Menus[$c]["subgroupname"]);
					adminmovecheck('down','SubGroupMove','SubGroupName',$Menus[$c]["subgroupname"]);
				}
				?>
			</td>
		</tr>
		<?php
	}

	frmSubGroupsHdFt(7,$nCurrentPage,$nPages);
	?>
	</table>
	</form>
	</body>
	</html>
	<?php
} // function frmSubGroups()


function sGetGroupName($GroupName)
{
	$strQuery = "select * from ".$GLOBALS["eztbGroups"]." where groupname='".$GroupName."' AND language='".$GLOBALS["gsLanguage"]."'";
	$result	= dbRetrieve($strQuery,true,0,0);
	$rs		= dbFetch($result);
	$groupname = $rs["groupdesc"];

	dbFreeResult($result);
	return $groupname;
} // function sGetGroupName()


function RenderGroups($SubGroupName)
{
	if ($GLOBALS["gsShowTopMenu"] == 'Y') {
		$sqlQuery = "SELECT g.groupname AS groupname,g.groupdesc AS groupdesc,t.topgroupdesc AS topgroupdesc FROM ".$GLOBALS["eztbGroups"]." g LEFT JOIN ".$GLOBALS["eztbTopgroups"]." t ON t.topgroupname=g.topgroupname AND t.language=g.language WHERE g.language='".$GLOBALS["gsLanguage"]."' AND g.grouplink='' ORDER BY t.topgrouporderid,g.grouporderid";
	} else {
		$sqlQuery = "SELECT groupname,groupdesc FROM ".$GLOBALS["eztbGroups"]." WHERE language='".$GLOBALS["gsLanguage"]."' AND grouplink='' ORDER BY grouporderid";
	}
	$result = dbRetrieve($sqlQuery,true,0,0);
	echo '<option value="">-- '.$GLOBALS["tShowAll"].' --</option>';
	while ($rs = dbFetch($result)) {
		echo '<option ';
		if ($SubGroupName == $rs["groupname"]) { echo 'selected '; }
		echo 'value="'.$rs["groupname"].'">';
		if ($GLOBALS["gsShowTopMenu"] == 'Y') { echo $rs["topgroupdesc"].' - '; } 
		echo $rs["groupdesc"];
	}
	dbFreeResult($result);
} // function RenderGroups()


function RenderLanguages($LanguageCode)
{
	$sqlQuery = "SELECT * FROM ".$GLOBALS["eztbLanguages"]." WHERE enabled='Y' ORDER BY languagename";
	$result = dbRetrieve($sqlQuery,true,0,0);
	while ($rs = dbFetch($result)) {
		echo '<option ';
		if ($LanguageCode == $rs["languagecode"]) { echo 'selected '; }
		echo 'value="'.$rs["languagecode"].'">'.$rs["languagename"];
	}
	dbFreeResult($result);
} // function RenderLanguages()

?>
<script language="Javascript" type="text/javascript">
	<!-- Begin
	function DelSubGroup(sParams) {
		if (window.confirm('<?php echo $GLOBALS["tConfirmDeletion"]; ?>')) {
			location.href='<?php echo BuildLink('m_subgroupsdel.php'); ?>&' + sParams;
		}
	}

	function SubGroupMove(sParams) {
		location.href='<?php echo BuildLink('m_subgroupsmove.php'); ?>&' + sParams;
	}
	//  End -->
</script>

<?php


function frmSubGroupsHdFt($colspan,$nCurrentPage,$nPages)
{
	global $_GET;

	$pLink = BuildLink('m_subgroups.php');
	$fLink = BuildLink('m_subgroupsform.php');
	$linkmod = '&filterlangname='.$_GET["filterlangname"].'&filtergroupname='.$_GET["filtergroupname"];
	$hlink = '<a href="'.$fLink.$linkmod.'&page='.$nCurrentPage.'&sort='.$_GET["sort"].'" title="'.$GLOBALS["tAddNew"].'" '.BuildLinkMouseOver($GLOBALS["tAddNew"]).'>';
	echo '<form name="PagingForm" action="'.$pLink.'" method="GET">';
	?>
	<tr class="topmenuback">
		<td colspan="<?php echo $colspan; ?>" align="<?php echo $GLOBALS["left"]; ?>">
			<table height="100%" width="100%" cellspacing="0" cellpadding="0">
				<tr><?php
					//  Add new is only permitted in the site default language
					if ($_GET["filterlangname"] == $GLOBALS["gsDefault_language"]) {
						if ($GLOBALS["canadd"] === True) {
							?><td align="<?php echo $GLOBALS["left"]; ?>" valign="bottom"><?php
							echo displaybutton('addbutton','subgroups',$GLOBALS["tAddNew"].'...',$hlink);
							?></td><?php
						}
					}
					?>
					<td align="<?php echo $GLOBALS["right"]; ?>" valign="bottom"><?php
						if ($nCurrentPage != 0) { ?><a href="<?php echo $pLink.$linkmod; ?>&page=0&sort=<?php echo $_GET["sort"]; ?>" <?php echo BuildLinkMouseOver($GLOBALS["tFirstPage"]); ?>><?php echo $GLOBALS["iFirst"]; ?></a><?php } else { echo $GLOBALS["iFirst"]; }
						echo '&nbsp;';
						if ($nCurrentPage != 0) { ?><a href="<?php echo $pLink.$linkmod; ?>&page=<?php echo $nCurrentPage - 1; ?>&sort=<?php echo $_GET["sort"]; ?>" <?php echo BuildLinkMouseOver($GLOBALS["tPrevPage"]); ?>><?php echo $GLOBALS["iPrev"]; ?></a><?php } else { echo $GLOBALS["iPrev"]; }
						$nCPage = $nCurrentPage + 1;
						echo RenderPageList($nCPage,$nPages,'m_subgroups.php',$linkmod);
						if ($nCurrentPage + 1 != $nPages) { ?><a href="<?php echo $pLink.$linkmod; ?>&page=<?php echo $nCurrentPage + 1; ?>&sort=<?php echo $_GET["sort"]; ?>" <?php echo BuildLinkMouseOver($GLOBALS["tNextPage"]); ?>><?php echo $GLOBALS["iNext"]; ?></a><?php } else { echo $GLOBALS["iNext"]; }
						echo '&nbsp;';
						if ($nCurrentPage + 1 != $nPages) { ?><a href="<?php echo $pLink.$linkmod; ?>&page=<?php echo $nPages - 1; ?>&sort=<?php echo $_GET["sort"]; ?>" <?php echo BuildLinkMouseOver($GLOBALS["tLastPage"]); ?>><?php echo $GLOBALS["iLast"]; ?></a><?php } else { echo $GLOBALS["iLast"]; } ?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<?php
	echo '</form>';
} // function frmSubGroupsHdFt()

?>
