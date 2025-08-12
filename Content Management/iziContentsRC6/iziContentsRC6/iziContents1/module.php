<?php

/***************************************************************************

 module.php
 -----------
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

$GLOBALS["rootdp"] = './';
require_once ($GLOBALS["rootdp"]."include/config.php");
require_once ($GLOBALS["rootdp"]."include/db.php");
require_once ($GLOBALS["rootdp"]."include/session.php");
include_once ($GLOBALS["rootdp"].$GLOBALS["modules_home"]."modfunctions.php");


if ((!isset($_GET["ezSID"])) && (isset($_POST["ezSID"]))) $_GET["ezSID"] = $_POST["ezSID"];
if ((!isset($_GET["link"])) && (isset($_POST["link"])))  $_GET["link"] = $_POST["link"];

$_GET["link"] = str_replace('../', '', $_GET["link"]);

$linkref = $_GET["link"];
$chainlink = explode('/',$linkref);
$modfilename = array_pop($chainlink);
$GLOBALS["modfiledir"] = implode('/',$chainlink);

if ($GLOBALS["modfiledir"] != '') {
	include($GLOBALS["rootdp"].$GLOBALS["modfiledir"]."/moduleref.php");
} else {
	$GLOBALS["moduleref"] = '';
}

if (($GLOBALS["gsUseFrames"] == '') && ($EZ_SESSION_VARS["noframesbrowser"] != True)) {
	include_once ($GLOBALS["rootdp"]."include/settings.php");
	include_once ($GLOBALS["rootdp"]."include/functions.php");
	include_once ($GLOBALS["rootdp"].$GLOBALS["admin_home"]."adminfunctions.php");
	include_once ($GLOBALS["rootdp"]."include/banners.php");
	include_once ($GLOBALS["rootdp"]."include/content.php");

	includeLanguageFiles('admin','main');
}

if (!isset($_POST["ezSID"])) $_POST["ezSID"] = $_GET["ezSID"];
if (!isset($_POST["link"])) $_POST["link"] = $_GET["link"];
if (!isset($_POST["topgroupname"])) $_POST["topgroupname"] = $_GET["topgroupname"];
if (!isset($_POST["groupname"])) $_POST["groupname"] = $_GET["groupname"];
if (!isset($_POST["subgroupname"])) $_POST["subgroupname"] = $_GET["subgroupname"];
if (!isset($_POST["page"])) $_POST["page"] = $_GET["page"];
if (!isset($_POST["catcode"])) $_POST["catcode"] = $_GET["catcode"];


if ($GLOBALS["gsShowTopMenu"] == 'Y') {
	if (!isset($_GET["groupname"])) {
		if (!isset($_GET["topgroupname"])) {
			$_GET["topgroupname"] = $GLOBALS["gsHomepageTopGroup"];
		}
		$_GET["groupname"] = mGetGroupName($_GET["topgroupname"]);
	} elseif (!isset($_GET["topgroupname"])) {
		$_GET["topgroupname"] = mGetTopGroupName($_GET["groupname"]);
	}
} elseif (!isset($_GET["groupname"])) {
	$_GET["groupname"] = mGetGroupName($_GET["topgroupname"]);
}

GetModuleData($GLOBALS["ModuleRef"]);


if ($GLOBALS["gsDirection"] == 'rtl') {
	$GLOBALS["iFirst"] = lsimagehtmltag($GLOBALS["image_home"],$GLOBALS["gsLastPageIcon"],$GLOBALS["gsLanguage"],$GLOBALS["tFirstPage"],0);
	$GLOBALS["iPrev"]  = lsimagehtmltag($GLOBALS["image_home"],$GLOBALS["gsNextPageIcon"],$GLOBALS["gsLanguage"],$GLOBALS["tPrevPage"],0);
	$GLOBALS["iNext"]  = lsimagehtmltag($GLOBALS["image_home"],$GLOBALS["gsPrevPageIcon"],$GLOBALS["gsLanguage"],$GLOBALS["tNextPage"],0);
	$GLOBALS["iLast"]  = lsimagehtmltag($GLOBALS["image_home"],$GLOBALS["gsFirstPageIcon"],$GLOBALS["gsLanguage"],$GLOBALS["tLastPage"],0);
} else {
	$GLOBALS["iFirst"] = lsimagehtmltag($GLOBALS["image_home"],$GLOBALS["gsFirstPageIcon"],$GLOBALS["gsLanguage"],$GLOBALS["tFirstPage"],0);
	$GLOBALS["iPrev"]  = lsimagehtmltag($GLOBALS["image_home"],$GLOBALS["gsPrevPageIcon"],$GLOBALS["gsLanguage"],$GLOBALS["tPrevPage"],0);
	$GLOBALS["iNext"]  = lsimagehtmltag($GLOBALS["image_home"],$GLOBALS["gsNextPageIcon"],$GLOBALS["gsLanguage"],$GLOBALS["tNextPage"],0);
	$GLOBALS["iLast"]  = lsimagehtmltag($GLOBALS["image_home"],$GLOBALS["gsLastPageIcon"],$GLOBALS["gsLanguage"],$GLOBALS["tLastPage"],0);
}


if (($GLOBALS["gsUseFrames"] == 'Y') && ($EZ_SESSION_VARS["noframesbrowser"] != True)) {
	Start_Timer();
	Start_Gzip();
	force_page_refresh();
	HTMLHeader('module');
	StyleSheet();
	?>
	<script language="JavaScript" type="text/javascript">
		<!-- Begin
		function ChangeFrames(TopGroupName) {
			parent.left.location.href="<?php echo BuildLink('menu.php'); ?>&topgroupname=" + TopGroupName;
			parent.contents.location.href="<?php echo BuildLink('showcontents.php'); ?>&topgroupname=" + TopGroupName;
		}
		if (parent.location.href == self.location.href){
			window.location.href='<?php echo BuildLink('index.php').'&topgroupname='.$_GET["topgroupname"].'&groupname='.$_GET["groupname"].'&subgroupname='.$_GET["subgroupname"]; ?>';
		}
		//  End -->
	</script>
	</head>
	<body marginwidth="0" marginheight="0" leftmargin="5" rightmargin="5" topmargin="10" class="mainback" onLoad="putFocus(0,0);">
	<?php
}

?>
<table border="0" cellspacing="5" cellpadding="0" width="100%" height="100%">
<tr><td width="100%" height="100%" align="center" valign="top">

<?php

ContentPageHeader();

if ($GLOBALS["gsLRContentFrame"] == 'Y') {
	?>
	<table border="0" cellpadding="0" width="100%">
	<tr><td valign="top" width="100%">
	<?php
}

if (isExternalLink ($_GET["link"])) {
	ECHO 'Remote Code Execution Patch Installed on this implementation of ezContents';
} else {
	include($GLOBALS["rootdp"].$_GET["link"]);
}

if ($GLOBALS["gsLRContentFrame"] == 'Y') {
	if ($GLOBALS["gnImageColumnBreak"] != '') {
		if (file_exists($GLOBALS["image_home"].$GLOBALS["gnImageColumnBreak"]) == true) {
			$imageInfo = getimagesize($GLOBALS["image_home"].$GLOBALS["gnImageColumnBreak"]);
			$size = $imageInfo[0];
		}
		?>
		</td><td width=<?php echo $size; ?> height="100%" valign="bottom" class="sep_column">
					<img src="<?php echo './'.$GLOBALS["icon_home"].'blank.gif'; ?>" width=<?php echo $size; ?> height="100%">
		<?php
	}
	?></td><td width="<?php echo $GLOBALS["gnRightColumnWidth"]; ?>" valign="top"><?php

	GetOrderByText($_GET["groupname"],$_GET["subgroupname"]);
	$isodate = sprintf ("%04d-%02d-%02d", strftime("%Y"), strftime("%m"), strftime("%d"));
	if ($GLOBALS["gsLanguage"] == $GLOBALS["gsDefault_language"]) {
		$strQuery = "SELECT * FROM ".$GLOBALS["eztbContents"]." WHERE (groupname='999999999' AND (subgroupname='' OR subgroupname='0')) AND language='".$GLOBALS["gsLanguage"]."' AND publishdate<='".$isodate."' AND expiredate>='".$isodate."' AND leftright='R' ".$GLOBALS["orderText"];
	} else {
		$strQuery = "SELECT * FROM ".$GLOBALS["eztbContents"]." WHERE (groupname='999999999' AND (subgroupname='' OR subgroupname='0')) AND (language='".$GLOBALS["gsLanguage"]."' OR language='".$GLOBALS["gsDefault_language"]."') AND publishdate<='".$isodate."' AND expiredate>='".$isodate."' AND leftright='R' ".$GLOBALS["orderText"];
	}
	$result = dbRetrieve($strQuery,true,0,0);
	$nContentName = '';
	while ($rsContent = dbFetch($result)) {
		if ($rsContent["contentname"] != $nContentName) {
			$nContentName = $rsContent["contentname"];
			ShowArticleRCol($rsContent);
		}
	}
	dbFreeResult($result);

	?><img src="<?php echo $GLOBALS["icon_home"]; ?>blank.gif" width="<?php echo $GLOBALS["gnRightColumnWidth"]; ?>" height="1" border="0"><br />
	</td></tr></table>
	<?php
}

ShowFooterBanner();

?>
</td></tr>
</table>
<?php

if (($GLOBALS["gsUseFrames"] == 'Y') && ($EZ_SESSION_VARS["noframesbrowser"] != True)) {
	End_Timer();
	?>
	</body>
	</html>
	<?php
	End_Gzip();
}



function SubModuleHeader($ModuleTitle,$ModuleSubmitText)
{
	global $EZ_SESSION_VARS, $_POST;

	if ($ModuleTitle == '') { $ModuleTitle = $GLOBALS["scTitle"]; }
	if (($GLOBALS["subTextDisplay"] == 'Y') && ($ModuleSubmitText == '')) { $ModuleSubmitText = $GLOBALS["scText"]; }
	?>
	<table border="0" width="100%" class="headercontent">
		<tr>
			<td valign="bottom" align="<?php echo $GLOBALS["left"]; ?>" class="header">
				<?php echo $ModuleTitle; ?>
			</td>
			<?php
			if (($GLOBALS["subGraphicDisplay"] == 'Y') || ($GLOBALS["subTextDisplay"] == 'Y')) {
				?>
				<td align="<?php echo $GLOBALS["right"]; ?>" class="header">
					<?php
					if (($GLOBALS["gsUseFrames"] == 'Y') && ($EZ_SESSION_VARS["noframesbrowser"] != True)) {
						?><a href="<?php echo BuildLink('module.php'); ?>&link=<?php echo $GLOBALS["modules_home"].$GLOBALS["ModuleRef"]; ?>/submit_<?php echo $GLOBALS["ModuleName"]; ?>.php&topgroupname=<?php echo $_POST["topgroupname"]; ?>&groupname=<?php echo $_POST["groupname"]; ?>&subgroupname=<?php echo $_POST["subgroupname"]; ?>&page=<?php echo $_POST["page"]; ?>"<?php
					} else {
						?><a href="<?php echo BuildLink('control.php'); ?>&topgroupname=<?php echo $_POST["topgroupname"]; ?>&groupname=<?php echo $_POST["groupname"]; ?>&subgroupname=<?php echo $_POST["subgroupname"]; ?>&page=<?php echo $_POST["page"]; ?>&link=<?php echo $GLOBALS["modules_home"].$GLOBALS["ModuleRef"]; ?>/submit_<?php echo $GLOBALS["ModuleName"]; ?>.php"<?php
					}
					echo ' title="'.$ModuleSubmitText.'" '.BuildLinkMouseOver($ModuleSubmitText).'">';
					if ($GLOBALS["subGraphicDisplay"] != '') {
						if ($GLOBALS["subGraphic"] != '') {
							echo lsimagehtmltag($GLOBALS["image_home"],$GLOBALS["subGraphic"],$GLOBALS["gsLanguage"],$ModuleSubmitText,0);
						} else {
							echo lsimagehtmltag($GLOBALS["modules_home"].$GLOBALS["ModuleRef"].'/','submit_'.$GLOBALS["ModuleName"].'.gif',$GLOBALS["gsLanguage"],$ModuleSubmitText,0);
						}
					}
					if ($GLOBALS["subTextDisplay"] == 'Y') {
						?><br /><b><?php echo $ModuleSubmitText; ?></b><?php
					}
					?></a>
				</td>
				<?php
			}
			?>
		</tr>
		<?php
		if ($GLOBALS["scUseCategories"] == 'Y') {
			echo '<tr>';
			if (($GLOBALS["subGraphicDisplay"] == 'Y') || ($GLOBALS["subTextDisplay"] != '')) {
				?><td colspan="2" valign="bottom" class="teaserheader"><?php
			} else {
				?><td valign="bottom" class="teaserheader"><?php
			}
			ModHdrCategories($_POST["catcode"]);
			echo '</td></tr>';
		}
		?>
	</table>
	<?php
} // function SubModuleHeader()


function ModHdrCategories($Cat)
{
	global $EZ_SESSION_VARS, $_POST, $_SERVER;

	if (($GLOBALS["gsUseFrames"] == 'Y') && ($EZ_SESSION_VARS["noframesbrowser"] != True)) {
		echo '<form name="MaintForm" action="module.php" method="GET" enctype="multipart/form-data">';
	} else {
		?><form name="MaintForm" action="<?php echo BuildLink('control.php'); ?>&link=<?php echo $GLOBALS["modules_home"].$GLOBALS["ModuleRef"]; ?>/show<?php echo $GLOBALS["ModuleName"]; ?>.php" method="POST" enctype="multipart/form-data"><?php
	}
	echo '<select name="catcode" size="1" onChange="submit();">';
	echo '<option value="0">'.$GLOBALS["tAllCategories"];
	RenderCategories($Cat);
	echo '</select>';
	if (ereg("Opera", $_SERVER["HTTP_USER_AGENT"])) {
		?><input type="image" name="submit" src="<?php echo $GLOBALS["icon_home"]; ?>go.gif" alt="Go" value="Go"><?php
	}
	?>
	<input type="hidden" name="topgroupname" value="<?php echo $_POST["topgroupname"]; ?>">
	<input type="hidden" name="groupname" value="<?php echo $_POST["groupname"]; ?>">
	<input type="hidden" name="subgroupname" value="<?php echo $_POST["subgroupname"]; ?>">
	<input type="Hidden" name="link" value="<?php echo $_POST["link"]; ?>">
	<input type="Hidden" name="ezSID" value="<?php echo $_POST["ezSID"]; ?>">
	</form>
	<?php
} // function ModHdrCategories()


function SubFormHeader($formname)
{
	global $_SERVER;

	?>
	<form name="<?php echo $formname; ?>" action="<?php echo $_SERVER["PHP_SELF"]; if ($_SERVER["QUERY_STRING"] != '') { echo '?'.$_SERVER["QUERY_STRING"]; } ?>" method="post" enctype="multipart/form-data">
	<?php
} // function SubFormHeader()


function SubModFormHeader()
{
	global $EZ_SESSION_VARS;
	?>
	<table border="0" width="100%" cellspacing="0" cellpadding="0" class="mainback">
		<tr class="header">
			<td align="center" class="header">
				<?php
				if (($GLOBALS["gsUseFrames"] == 'Y') && ($EZ_SESSION_VARS["noframesbrowser"] != True)) {
					?><form name="MaintForm" action="<?php echo BuildLink('module.php'); ?>&link=<?php echo $GLOBALS["modules_home"].$GLOBALS["ModuleRef"]; ?>/submit_<?php echo $GLOBALS["ModuleName"]; ?>.php" method="POST" enctype="multipart/form-data"><?php
				} else {
					?><form name="MaintForm" action="<?php echo BuildLink('control.php'); ?>&link=<?php echo $GLOBALS["modules_home"].$GLOBALS["ModuleRef"]; ?>/submit_<?php echo $GLOBALS["ModuleName"]; ?>.php" method="POST" enctype="multipart/form-data"><?php
				}
				?>
				<table border="0" cellspacing="3" cellpadding="3">
					<?php
} // function SubModFormHeader()


function SubModFormFooter($colspan)
{
	global $EZ_SESSION_VARS, $_POST;
	?>
	<tr class="topmenuback">
		<td colspan="<?php echo $colspan; ?>">
			<?php
			if (($GLOBALS["gsUseFrames"] == 'N') || ($EZ_SESSION_VARS["noframesbrowser"] === True)) {
				?>
				<input type="hidden" name="topgroupname" value="<?php echo $_POST["topgroupname"]; ?>">
				<input type="hidden" name="groupname" value="<?php echo $_POST["groupname"]; ?>">
				<input type="hidden" name="subgroupname" value="<?php echo $_POST["subgroupname"]; ?>">
				<?php
			}
} // function SubModFormFooter()


function SubFormFooter()
{
	global $EZ_SESSION_VARS, $_POST;
	?>
	<input type="hidden" name="topgroupname" value="<?php echo $_POST["topgroupname"]; ?>">
	<input type="hidden" name="groupname" value="<?php echo $_POST["groupname"]; ?>">
	<input type="hidden" name="subgroupname" value="<?php echo $_POST["subgroupname"]; ?>">
	<input type="Hidden" name="link" value="<?php echo $_POST["link"]; ?>">
	<input type="Hidden" name="catcode" value="<?php echo $_POST["catcode"]; ?>">
	<input type="Hidden" name="ezSID" value="<?php echo $_POST["ezSID"]; ?>">
	</form>
	<?php
} // function SubModFormFooter()


function SubModuleReturn($rScript, $rMessage, $rQueryString)
{
	global $EZ_SESSION_VARS, $_POST;
	?>
	<table border="0" width="100%" cellspacing="0" cellpadding="0" class="headercontent">
		<tr><td class="tablecontent">&nbsp;<br /><?php echo $GLOBALS["tThankYou"]; ?><br /><?php
				if ($GLOBALS["scValidate"] == 'Y') {
					?><br /><?php echo $GLOBALS["tReleaseRequired"]; ?><br /><?php
				}
				?>
				<br /><?php echo $GLOBALS["tClickToReturn"]; ?> <?php
				if (($GLOBALS["gsUseFrames"] == 'Y') && ($EZ_SESSION_VARS["noframesbrowser"] != True)) {
					?><a href="<?php echo BuildLink('module.php')?>&link=<?php echo $GLOBALS["modules_home"].$GLOBALS["ModuleRef"]; ?>/<?php echo $rScript; ?>&page=<?php echo $_POST["page"]; ?><?php echo $rQueryString; ?>"> <?php
				} else {
					?><a href="<?php echo BuildLink('control.php')?>&topgroupname=<?php echo $_POST["topgroupname"]; ?>&groupname=<?php echo $_POST["groupname"]; ?>&subgroupname=<?php echo $_POST["subgroupname"]; ?>&link=<?php echo $GLOBALS["modules_home"].$GLOBALS["ModuleRef"]; ?>/<?php echo $rScript; ?>&page=<?php echo $_POST["page"]; ?><?php echo $rQueryString; ?>"> <?php
				}
				echo $rMessage; ?></a>.<br />&nbsp;</td>
		</tr>
	</table>
	<?php
} // function SubModuleReturn()


function mGetGroupName($topgroupname)
{
	$gname = $GLOBALS["gsHomepageGroup"];
	$strQuery = "SELECT * FROM ".$GLOBALS["eztbGroups"]." WHERE topgroupname='".$topgroupname."' AND language='".$GLOBALS["gsDefault_language"]."' ORDER BY grouporderid";
	$result = dbRetrieve($strQuery,true,0,1);

	if ($rs = dbFetch($result)) { $gname = $rs["groupname"]; }
	dbFreeResult($result);
	return $gname;
} // function mGetGroupName()


function mGetTopGroupName($groupname)
{
	$gname = $GLOBALS["gsHomepageTopGroup"];
	$strQuery = "SELECT * FROM ".$GLOBALS["eztbGroups"]." WHERE groupname='".$groupname."' AND language='".$GLOBALS["gsDefault_language"]."' ORDER BY grouporderid";
	$result = dbRetrieve($strQuery,true,0,1);

	if ($rs = dbFetch($result)) { $gname = $rs["topgroupname"]; }
	dbFreeResult($result);
	return $gname;
} // function mGetGroupName()

?>
