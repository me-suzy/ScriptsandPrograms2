<?php

/***************************************************************************

 modfunctions.php
 -----------------
 copyright : (C) 2002-2004 The ezContents Development Team

 ***************************************************************************/

/***************************************************************************
 The ezContents Development Team offers no warranties on this script.
 The owner/licensee of the script is solely responsible for any problems
 caused by installation of the script or use of the script.

 All copyright notices regarding ezContents must remain intact on the
 scripts and in the HTML for the scripts.

 For more info on ezContents,
 visit http://www.ezcontents.org/

/***************************************************************************

/***************************************************************************
 *
 *	This program is free software; you can redistribute it and/or modify
 *	it under the terms of the License which can be found within the
 *	zipped package.
 *
 ***************************************************************************/


function GetModuleData($ModuleName)
{
	$GLOBALS["scTable"] = $GLOBALS["scTitle"] = $GLOBALS["subTextDisplay"] = $GLOBALS["subText"] = $GLOBALS["subGraphicDisplay"] = $GLOBALS["subGraphic"] = "";

	$strQuery = "SELECT * FROM ".$GLOBALS["eztbSpecialcontents"]." WHERE scname='".$ModuleName."'";
	$result = dbRetrieve($strQuery,true,0,0,True);
	while ($rs = dbFetch($result))
	{
		$GLOBALS["scTable"] = $rs["scdb"];
		if ($rs["scuseprefix"] == 'Y') { $GLOBALS["scTable"] = $GLOBALS["eztbPrefix"].$GLOBALS["scTable"]; }
		$GLOBALS["scTitle"]				= $rs["sctitle"];
		$GLOBALS["scValidate"]			= $rs["scvalid"];
		$GLOBALS["scLoginRequired"]		= $rs["screg"];
		$GLOBALS["scUsergroups"]		= $rs["usergroups"];
		$GLOBALS["subTextDisplay"]		= $rs["stextdisplay"];
		$GLOBALS["subText"]				= $rs["stext"];
		$GLOBALS["subGraphicDisplay"]	= $rs["sgraphicdisplay"];
		$GLOBALS["subGraphic"]			= $rs["sgraphic"];
		$GLOBALS["scUseCategories"]		= $rs["scusecategories"];
		$GLOBALS["scOrderBy"]			= $rs["orderby"];
		$GLOBALS["scPostedBy"]			= $rs["showpostedby"];
		$GLOBALS["scPostedDate"]		= $rs["showposteddate"];
		$GLOBALS["scPerPage"]			= $rs["perpage"];
		$GLOBALS["scModuleComments"]	= $rs["sccomments"];
		if ($GLOBALS["scUseCategories"] == 'Y') {
			$GLOBALS["scCatTable"] = $GLOBALS["scTable"].'categories';
		}
	}
	dbFreeResult($result);
	GetModuleSettings($ModuleName);
} // function GetModuleData()


function GetModuleSettings($ModuleName)
{
	$strQuery = "SELECT * FROM ".$GLOBALS["eztbModuleSettings"]." WHERE modulename='".$ModuleName."'";
	$result = dbRetrieve($strQuery,true,0,0,True);
	while ($rs = dbFetch($result)) {
		$Settingname  = $rs["settingname"];
		$Settingvalue = $rs["settingvalue"];

		$GLOBALS[$ModuleName][$Settingname] = $Settingvalue;
	}
	dbFreeResult($result);
} // function GetModuleSettings()


function adminreleasecheck($linkref,$varname,$value)
{
	global $_GET;

	if ($GLOBALS["canedit"] == False) {
		// No privilege
		echo $GLOBALS["iBlank"];
	} else {
		// Edit privilege
		if (isset($_GET["filtergroupname"])) {
			?><a href="javascript:<?php echo $linkref; ?>('<?php echo $varname; ?>=<?php echo $value; ?>&page=<?php echo $_GET["page"]; ?>&filtergroupname=<?php echo $_GET["filtergroupname"]; ?>');" <?php echo BuildLinkMouseOver($GLOBALS["tRelease"]); ?>><?php echo $GLOBALS["iRelease"]; ?></a><?php
		} else {
			?><a href="javascript:<?php echo $linkref; ?>('<?php echo $varname; ?>=<?php echo $value; ?>&page=<?php echo $_GET["page"]; ?>');" <?php echo BuildLinkMouseOver($GLOBALS["tRelease"]); ?>><?php echo $GLOBALS["iRelease"]; ?></a><?php
		}
	}
} // function adminreleasecheck()


function frmModuleHdFt($colspan,$nCurrentPage,$nPages)
{
	global $_GET;

	$pLink = BuildLink('m_'.$GLOBALS["ModuleName"].'.php').'&sort='.$_GET["sort"];
	$fLink = BuildLink('m_'.$GLOBALS["ModuleName"].'form.php').'&sort='.$_GET["sort"];
	$hlink = '<a href="'.$fLink.'&page='.$nCurrentPage.'" title="'.$GLOBALS["tAddNew"].'" '.BuildLinkMouseOver($GLOBALS["tAddNew"]).'>';
	echo '<form name="PagingForm" action="'.$pLink.'" method="GET">';
	?><tr class="topmenuback">
		<td colspan="<?php echo $colspan; ?>" align="<?php echo $GLOBALS["left"]; ?>">
			<table height="100%" width="100%" cellspacing="0" cellpadding="0">
				<tr><td align="<?php echo $GLOBALS["left"]; ?>" valign="bottom"><?php
						echo displaybutton('addbutton',$GLOBALS["ModuleName"],$GLOBALS["tAddNew"].'...',$hlink); ?>
					</td>
					<td align="<?php echo $GLOBALS["right"]; ?>" valign="bottom">
						<a href="<?php echo $pLink; ?>&page=0"><?php echo $GLOBALS["iFirst"]; ?></a> <?php
						if ($nCurrentPage != 0) { ?><a href="<?php echo $pLink; ?>&page=<?php echo $nCurrentPage - 1; ?>"><?php echo $GLOBALS["iPrev"]; ?></a><?php } else { echo $GLOBALS["iPrev"]; }
						$nCPage = $nCurrentPage + 1;
						echo RenderPageList($nCPage,$nPages,'m_'.$GLOBALS["ModuleName"].'.php');
						if ($nCurrentPage + 1 != $nPages) { ?><a href="<?php echo $pLink; ?>&page=<?php echo $nCurrentPage + 1; ?>"><?php echo $GLOBALS["iNext"]; ?></a><?php } else { echo $GLOBALS["iNext"]; } ?>
						<a href="<?php echo $pLink; ?>&page=<?php echo $nPages - 1; ?>"><?php echo $GLOBALS["iLast"]; ?></a>
					</td>
				</tr>
			</table>
		</td>
	</tr><?php
	echo '</form>';
} // function frmModuleHdFt()


function frmModuleReturn($colspan)
{
	?><tr class="teasercontent">
		<td colspan="<?php echo $colspan; ?>" align="<?php echo $GLOBALS["left"]; ?>">
			<a href="<?php echo BuildLink($GLOBALS["rootdp"].$GLOBALS["admin_home"].'m_subcontent.php'); ?>" <?php echo BuildLinkMouseOver($GLOBALS["tRet_SubContent"]); ?>><?php echo $GLOBALS["tRet_SubContent"]; ?></a>
		</td>
	</tr><?php
} // function frmModuleReturn()


function lGetAuthorName($lAuthorID)
{
	$strQuery = "select * from ".$GLOBALS["eztbAuthors"]." where authorid='".$lAuthorID."'";
	$aresult = dbRetrieve($strQuery,true,0,0,True);
	$ars = dbFetch($aresult);
	$lAuthorName = trim($ars["authorname"]);
	dbFreeResult($aresult);
	if ($lAuthorName == "") { $lAuthorName = "---"; }
	return $lAuthorName;
} // function lGetAuthorName()


function lGetAuthorID()
{
	global $EZ_SESSION_VARS;

	$authorid = 0;
	$strQuery = "SELECT authorid FROM ".$GLOBALS["eztbAuthors"]." WHERE login='".$EZ_SESSION_VARS["LoginCookie"]."'";
	$result = dbRetrieve($strQuery,true,0,0);
	$rs = dbFetch($result);
	if ($rs["authorid"] == $EZ_SESSION_VARS["UserID"]) { $authorid = $rs["authorid"]; }
	dbFreeResult($result);
	return $authorid;
} // function lGetAuthorID()


function DeleteEntry($field,$value)
{
	$strQuery = "DELETE FROM ".$GLOBALS["scTable"]." WHERE ".$field."='".$value."'";
	$result = dbExecute($strQuery,true);
	dbCommit();
} // function DeleteEntry()


function ReleaseEntry($field,$value)
{
	$strQuery = "UPDATE ".$GLOBALS["scTable"]." SET activeentry = 1-activeentry WHERE ".$field."='".$value."'";
	$result = dbExecute($strQuery,true);
	dbCommit();
} // function ReleaseEntry()


function frmModuleJs()
{
	?>
	<script language="Javascript" type="text/javascript">
		<!-- Begin

		function DelEntry(sParams) {
			if (window.confirm('<?php echo $GLOBALS["tConfirmDeletion"]; ?>')) {
				location.href='<?php echo BuildLink('m_'.$GLOBALS["ModuleName"].'del.php'); ?>&' + sParams;
			}
		}

		function RelEntry(sParams) {
			if (window.confirm('<?php echo $GLOBALS["tToggle"]; ?>')) {
				location.href='<?php echo BuildLink('m_'.$GLOBALS["ModuleName"].'rel.php'); ?>&' + sParams;
			}
		}
		//  End -->
	</script>
	<?php
} // function frmModuleJs()


function modformclose($tSubmitMessage)
{
	global $EZ_SESSION_VARS, $_POST;

	?>
	<input type="hidden" name="ezSID" value="<?php echo $GLOBALS["ezSID"]; ?>">
	<input type="hidden" name="submitted" value="yes">
	<input type="hidden" name="page" value="<?php echo $_POST["page"]; ?>">
	<input type="submit" value="<?php echo $tSubmitMessage; ?>" name="submit">&nbsp;
	<input type="reset" value="<?php echo $GLOBALS["tReset"]; ?>" name="reset">
	<?php
} // function adminformclose()


function VerifySubmoduleLogin($tSubmitText)
{
	global $EZ_SESSION_VARS, $_POST;

	$strQuery = "SELECT login FROM ".$GLOBALS["eztbAuthors"]." WHERE login='".$EZ_SESSION_VARS["LoginCookie"]."' AND userpassword='".$EZ_SESSION_VARS["PasswordCookie"]."'";
	$result = dbRetrieve($strQuery,true,0,0);
	$lRecCount = dbRowsReturned($result);
	dbFreeResult($result);
	if ($lRecCount > 0) {
		// User is logged in, so test against the list of valid user groups for submission
		if ($GLOBALS["scUsergroups"] == '') { return true; }
		$Submission_Usergroups = explode(',',$GLOBALS["scUsergroups"]);
		if (in_array($EZ_SESSION_VARS["UserGroup"],$Submission_Usergroups)) { return true; }
	}
	SubModuleHeader('',$tSubmitText);
	?><br />
	<table border="1" cellpadding="1" cellspacing="0" align="center" valign="top" width="100%" class="headercontent">
		<tr><td class="tablecontent">&nbsp;<br /><?php echo $GLOBALS["tMustLogin"]; ?>
			<span style="cursor:hand"><a onClick="javascript:window.open('<?php echo BuildLink('login.php'); ?>&topgroupname=<?php echo $_POST["topgroupname"]; ?>&groupname=<?php echo $_POST["groupname"]; ?>&subgroupname=<?php echo $_POST["subgroupname"]; ?>', 'Login', 'width=340,height=180,status=no,resizable=yes,scrollbars=no'); return(false);" <?php echo BuildLinkMouseOver($GLOBALS["tLogin"]); ?> target="popup"><?php
			echo $GLOBALS["tLogin"]; ?></a></span> <?php echo $GLOBALS["tToSubmit"]; ?>.<br />&nbsp;</td>
		</tr>
	</table><?php
	return false;
} // function VerifySubmoduleLogin()


function SubModuleHdFt($nCurrentPage,$nPages)
{
	global $EZ_SESSION_VARS, $_SERVER, $_POST;

	// We don't display paging information/icons if we're an inline.
	// Test to see whether we came from module.php or showcontents.php
	$request_uri = urldecode($_SERVER["REQUEST_URI"]);

	if (strpos($request_uri,'showcontents.php') === false) {
		// We came here from module.php
		if (!(strpos($request_uri,'link='.$GLOBALS["modules_home"].$GLOBALS["ModuleRef"].'/show'.$GLOBALS["ModuleName"].'.php') === false)) {
			if (($GLOBALS["gsUseFrames"] == 'Y') && ($EZ_SESSION_VARS["noframesbrowser"] != True)) {
				$iref = BuildLink('module.php');
			} else { $iref = BuildLink('control.php'); }
			$iref .= '&topgroupname='.$_POST["topgroupname"].'&groupname='.$_POST["groupname"].'&subgroupname='.$_POST["subgroupname"].'&';
			if ($_POST["catcode"] != '') { $iref .= 'catcode='.$_POST["catcode"].'&';}
			?><table width="100%" cellspacing="2" cellpadding="2" class="mod_<?php echo $GLOBALS["ModuleRef"]; ?>_header">
				<tr><td align="<?php echo $GLOBALS["right"]; ?>" valign="bottom">
					<a href="<?php echo $iref; ?>link=<?php echo $GLOBALS["modules_home"].$GLOBALS["ModuleRef"]; ?>/show<?php echo $GLOBALS["ModuleName"]; ?>.php&page=0" <?php echo BuildLinkMouseOver($GLOBALS["tFirstPage"]); ?>><?php echo $GLOBALS["iFirst"]; ?></a> <?php
					if ($nCurrentPage != 0) {
						?><a href="<?php echo $iref; ?>link=<?php echo $GLOBALS["modules_home"].$GLOBALS["ModuleRef"]; ?>/show<?php echo $GLOBALS["ModuleName"]; ?>.php&page=<?php echo $nCurrentPage - 1; ?>" <?php echo BuildLinkMouseOver($GLOBALS["tPrevPage"]); ?>><?php echo $GLOBALS["iPrev"]; ?></a><?php
					} else { echo $GLOBALS["iPrev"]; }
					$nCPage = $nCurrentPage + 1;
					echo '&nbsp;&nbsp;'.$GLOBALS["tPage"].' '.$nCPage.' '.$GLOBALS["tOf"].' '.$nPages.'&nbsp;&nbsp;';
					if ($nCurrentPage + 1 != $nPages) {
						?><a href="<?php echo $iref; ?>link=<?php echo $GLOBALS["modules_home"].$GLOBALS["ModuleRef"]; ?>/show<?php echo $GLOBALS["ModuleName"]; ?>.php&page=<?php echo $nCurrentPage + 1; ?>" <?php echo BuildLinkMouseOver($GLOBALS["tNextPage"]); ?>><?php echo $GLOBALS["iNext"]; ?></a><?php
					} else { echo $GLOBALS["iNext"]; }
					?><a href="<?php echo $iref; ?>link=<?php echo $GLOBALS["modules_home"].$GLOBALS["ModuleRef"]; ?>/show<?php echo $GLOBALS["ModuleName"]; ?>.php&page=<?php echo $nPages - 1; ?>" <?php echo BuildLinkMouseOver($GLOBALS["tLastPage"]); ?>><?php echo $GLOBALS["iLast"]; ?></a>
				</td></tr>
			</table><br /><?php
		}
	}
} // function SubModuleHdFt()


function ModArticleSecurity($nContentName,$rsContent)
{
	GLOBAL $EZ_SESSION_VARS;

	if ($rsContent["contentname"] != $nContentName) {
		$nContentName = $rsContent["contentname"];
		$displayit = true;
	} else { $displayit = false; }

	// If the content is not attached to a menu, then it is hidden
	if ($rsContent["groupname"] == '') { $displayit = false; }
	$_GET["subgroupname"] = $rsContent["subgroupname"];
	$_GET["groupname"] = $rsContent["groupname"];
	if (($displayit == true) && ($rsContent["subgroupname"] != '')) {
		$strQuery = "SELECT loginreq,usergroups from ".$GLOBALS["eztbSubgroups"]." WHERE subgroupname='".$rsContent["subgroupname"]."' AND language='".$rsContent["language"]."'";
		$smresult = dbRetrieve($strQuery,true,0,0,True);
		while ($rs = dbFetch($smresult)) {
			if ($rs["loginreq"] == 'Y') {
				if ($EZ_SESSION_VARS["PasswordCookie"] == '') { $displayit = false;
				} else {
					if ($rs["usergroups"] != '') {
						$Menu_Usergroups = explode(',',$rs["usergroups"]);
						if (!in_array($EZ_SESSION_VARS["UserGroup"],$Menu_Usergroups)) { $displayit = false; }
					}
				}
			}
		}
		dbFreeResult($smresult);
	}
	if ($displayit == true) {
		$strQuery = "SELECT topgroupname,loginreq,usergroups from ".$GLOBALS["eztbGroups"]." WHERE groupname='".$rsContent["groupname"]."' AND language='".$rsContent["language"]."'";
		$mresult = dbRetrieve($strQuery,true,0,0,True);
		while ($rs = dbFetch($mresult)) {
			if ($rs["loginreq"] == 'Y') {
				if ($EZ_SESSION_VARS["PasswordCookie"] == '') { $displayit = false;
				} else {
					if ($rs["usergroups"] != '') {
						$Menu_Usergroups = explode(',',$rs["usergroups"]);
						if (!in_array($EZ_SESSION_VARS["UserGroup"],$Menu_Usergroups)) { $displayit = false; }
					}
				}
			}
			$_GET["topgroupname"] = $rs["topgroupname"];
		}
		dbFreeResult($mresult);
	}
	if (($displayit == true) && ($GLOBALS["gsShowTopMenu"] == 'Y')) {
		$strQuery = "SELECT loginreq,usergroups from ".$GLOBALS["eztbTopgroups"]." WHERE topgroupname='".$_GET["topgroupname"]."' AND language='".$rsContent["language"]."'";
		$tmresult = dbRetrieve($strQuery,true,0,0,True);
		while ($rs = dbFetch($tmresult)) {
			if ($rs["loginreq"] == 'Y') {
				if ($EZ_SESSION_VARS["PasswordCookie"] == '') { $displayit = false;
				} else {
					if ($rs["usergroups"] != '') {
						$Menu_Usergroups = explode(',',$rs["usergroups"]);
						if (!in_array($EZ_SESSION_VARS["UserGroup"],$Menu_Usergroups)) { $displayit = false; }
					}
				}
			} // if ($rs["loginreq"] == 'Y')
		} // end while
		dbFreeResult($tmresult);
	}
	return $displayit;
} // function ModArticleSecurity()


function submitFormCategories($CatCode)
{
	if ($GLOBALS["scUseCategories"] == 'Y') {
		?><tr class="tablecontent">
			<?php FieldHeading("Category","catcode"); ?>
			<td valign="top" class="content">
				<select name="catcode" size="1">
					<option value="0">
					<?php RenderCategories($CatCode); ?>
				</select>
			</td>
		</tr><?php
	} else { ?><input type="hidden" name="catcode" value="0"><?php }
} // function submitFormCategories()


function RenderCategories($EventCat)
{
	$GLOBALS["scModCategoryComments"] = '';

	$sqlQuery = "SELECT * FROM ".$GLOBALS["scCatTable"]." WHERE hiddencat != '1' ORDER BY catref,catname";
	$result = dbRetrieve($sqlQuery,true,0,0,True);
	while ($rs = dbFetch($result)) {
		echo '<option ';
		if ($EventCat == $rs["catref"]) {
			echo 'selected ';
			$GLOBALS["scModCategoryComments"]	= $rs["sccomments"];
		}
		echo 'value="'.$rs["catref"].'">';
		$catparents = explode('.',$rs["catref"]);
		$catlevel = count($catparents) - 1;
		echo str_repeat('-->&nbsp;',$catlevel);
		echo $rs["catname"];
	}
	dbFreeResult($result);
} // function RenderCategories()


function RenderAllCategories($EventCat)
{
	$sqlQuery = "SELECT * FROM ".$GLOBALS["scCatTable"]." ORDER BY catref,catname";
	$result = dbRetrieve($sqlQuery,true,0,0,True);
	while ($rs = dbFetch($result)) {
		echo '<option ';
		if ($EventCat == $rs["catref"]) { echo 'selected '; }
		echo 'value="'.$rs["catref"].'">';
		$catparents = explode('.',$rs["catref"]);
		$catlevel = count($catparents) - 1;
		echo str_repeat('-->&nbsp;',$catlevel);
		echo $rs["catname"];
	}
	dbFreeResult($result);
} // function RenderAllCategories()


function ModuleDataQuery($catcode='',$query='')
{
	$isodate = sprintf ("%04d-%02d-%02d", strftime("%Y"), strftime("%m"), strftime("%d"));

	$strQuery = "SELECT * FROM ".$GLOBALS["scTable"]." WHERE activeentry='1' AND publishdate<='".$isodate." 23:59:59' ";
	if (($catcode != '0') && ($catcode != '')) {
		$strQuery .= "AND (catid='".$catcode."' OR catid LIKE '".$catcode.".%') ";
	}
	if ($query != '') { $strQuery .= 'AND '.$query.' '; }
	if ($GLOBALS["scOrderBy"] == 'C') { $strQuery .= 'ORDER BY catid ASC,publishdate DESC';
	} else { $strQuery .= 'ORDER BY publishdate DESC'; }
	return $strQuery;
}

function ModuleExpiredQuery($catcode='',$query='')
{
	$isodate = sprintf ("%04d-%02d-%02d", strftime("%Y"), strftime("%m"), strftime("%d"));

	$strQuery = "SELECT * FROM ".$GLOBALS["scTable"]." WHERE activeentry='1' AND expiredate<'".$isodate."' ";
	if (($catcode != '0') && ($catcode != '')) {
		$strQuery .= "AND (catid='".$catcode."' OR catid LIKE '".$catcode.".%') ";
	}
	if ($query != '') { $strQuery .= 'AND '.$query.' '; }
	if ($GLOBALS["scOrderBy"] == 'C') { $strQuery .= 'ORDER BY catid ASC,publishdate DESC';
	} else { $strQuery .= 'ORDER BY publishdate DESC'; }
	return $strQuery;
}


function ModuleNoEntries()
{
	?><table border="0" width="100%" cellspacing="1" cellpadding="3" class="headercontent"> 
		<tr><td class="tablecontent"> 
			<?php echo $GLOBALS["tNoEntries"] ?> 
			</td> 
		</tr> 
	</table><?php
}

function ModuleJavaFunctions()
{
	?>
	<script language="javascript" type="text/javascript">
		<!-- Begin
		var winArray = new Array();

		 var gsControlName = "";

		function DatePicker(sControl,sMM,sYYYY) {
			gsControlName = sControl;
			newWin = window.open("<?php echo BuildLink($GLOBALS["rootdp"].$GLOBALS["admin_home"].'datepicker.php'); ?>&control=" + sControl + "&month=" + sMM+ "&year=" + sYYYY, "DatePicker", "width=240,height=225,status=no,resizable=yes,scrollbars=no,dependent=yes");
			winArray[winArray.length] = newWin;
		}

		function TagPicker(sControl,sWYSIWYG)
		{
			gsControlName = sControl;
			newWin = window.open("<?php echo BuildLink($GLOBALS["rootdp"].$GLOBALS["admin_home"].'tagpicker.php'); ?>&control=" + sControl + "&WYSIWYG=" + sWYSIWYG, "TagPicker", "width=520,height=400,status=no,resizable=yes,scrollbars=no,dependent=yes");
			winArray[winArray.length] = newWin;
		}

		function TagPicker2(sControl,sWYSIWYG)
		{
			gsControlName = sControl;
			<?php
			if (isset($GLOBALS["PermittedTags"])) {
				?>newWin = window.open("<?php echo BuildLink($GLOBALS["rootdp"].$GLOBALS["admin_home"].'tagpicker2.php'); ?>&control=" + sControl + "&WYSIWYG=" + sWYSIWYG + "&secure=<?php echo $GLOBALS["RestrictTags"]; ?>&restricted=<?php echo urlencode(implode($GLOBALS["tqSeparator"],$GLOBALS["PermittedTags"])); ?>", "TagPicker", "width=520,height=400,status=no,resizable=yes,scrollbars=no,dependent=yes");<?php
			} else {
				?>newWin = window.open("<?php echo BuildLink($GLOBALS["rootdp"].$GLOBALS["admin_home"].'tagpicker2.php'); ?>&control=" + sControl + "&WYSIWYG=" + sWYSIWYG + "&secure=<?php echo $GLOBALS["RestrictTags"]; ?>&restricted=", "TagPicker", "width=520,height=400,status=no,resizable=yes,scrollbars=no,dependent=yes");<?php
			}
			?>
			winArray[winArray.length] = newWin;
		}

		 function ShowImage(sImageName)
		 {
			newWin = window.open(sImageName, "Image", "width=500,height=400,status=no,resizable=yes,scrollbars=yes,dependent=yes");
			winArray[winArray.length] = newWin;
		 }

		 function ImagePicker(sControl)
		 {
			gsControlName = sControl;
			newWin = window.open("<?php echo BuildLink($GLOBALS["rootdp"].$GLOBALS["admin_home"].'imagepicker.php'); ?>&control=" + sControl, "ImagePicker", "width=490,height=430,status=no,resizable=yes,scrollbars=yes,dependent=yes");
			winArray[winArray.length] = newWin;
		 }


		function closeChildWindows() {
			for(i=0;i<winArray.length;i++) {
				if (!winArray[i].closed) {
					winArray[i].close();
				}
			}
			winArray.length = 0;
		}
		//  End -->
	</script>
	<?php
}

?>
