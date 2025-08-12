<?php

/***************************************************************************

 headeruserdata.php
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

$GLOBALS["rootdp"] = './';
require_once ($GLOBALS["rootdp"]."include/config.php");
require_once ($GLOBALS["rootdp"]."include/db.php");
require_once ($GLOBALS["rootdp"]."include/session.php");

if (($GLOBALS["gsUseFrames"] == '') && ($EZ_SESSION_VARS["noframesbrowser"] != True)) {
	include ($GLOBALS["rootdp"]."include/settings.php");
	include ($GLOBALS["rootdp"]."include/functions.php");
	includeLanguageFiles('admin','main');
} // Frames includes
include ($GLOBALS["rootdp"]."include/userdata.php");


if (isset($_POST["topgroupname"])) { $_GET["topgroupname"] = $_POST["topgroupname"]; }
if (isset($_POST["groupname"])) { $_GET["groupname"] = $_POST["groupname"]; }
if (isset($_POST["subgroupname"])) { $_GET["subgroupname"] = $_POST["subgroupname"]; }

if ($_GET["topgroupname"] == '') { $_GET["topgroupname"] = hGetTopGroupName(); }
if ($_GET["groupname"] == '') { $_GET["groupname"] = hGetGroupName(); }


if (($GLOBALS["gsUseFrames"] == 'Y') && ($EZ_SESSION_VARS["noframesbrowser"] != True)) {
	HTMLHeader('userdata');
	StyleSheet();
	?>
	</head>
	<?php
	$_GET["link"] = hHomeLink($_GET["groupname"]);
	if ($GLOBALS["gsShowTopMenu"] == 'Y') {
		$topmenulink = BuildLink('topmenu.php').'&topgroupname='.$_GET["topgroupname"];
		$menulink = BuildLink('menu.php').'&topgroupname='.$_GET["topgroupname"].'&groupname='.$_GET["groupname"].'&subgroupname='.$_GET["subgroupname"];
		if ($_GET["link"] != "") {
			$contentlink = BuildLink('module.php').'&link='.$_GET["link"].'&topgroupname='.$_GET["topgroupname"].'&groupname='.$_GET["groupname"].'&subgroupname='.$_GET["subgroupname"];
		} else {
			$contentlink = BuildLink('showcontents.php').'&topgroupname='.$_GET["topgroupname"].'&groupname='.$_GET["groupname"].'&subgroupname='.$_GET["subgroupname"];
		}
		?>
		<body marginwidth="0" marginheight="0" leftmargin="0" topmargin="0" class="topback" onload="top.ezc.topmenu.location='<?php echo $topmenulink; ?>'; top.ezc.left.location='<?php echo $menulink; ?>'; top.ezc.contents.location='<?php echo $contentlink; ?>';">
		<?php
	} else {
		$menulink = BuildLink('menu.php').'&groupname='.$_GET["groupname"].'&subgroupname='.$_GET["subgroupname"];
		if ($_GET["link"] != "") {
			$contentlink = BuildLink('module.php').'&link='.$_GET["link"].'&groupname='.$_GET["groupname"].'&subgroupname='.$_GET["subgroupname"];
		} else {
			$contentlink = BuildLink('showcontents.php').'&groupname='.$_GET["groupname"].'&subgroupname='.$_GET["subgroupname"];
		}
		?>
		<body marginwidth="0" marginheight="0" leftmargin="0" topmargin="0" class="topback" onload="top.ezc.left.location='<?php echo $menulink; ?>'; top.ezc.contents.location='<?php echo $contentlink; ?>';">
		<?php
	}
} // Frames header



?>
<table border="0" width="100%" height="100%" cellpadding="3" cellspacing="3">
	<tr class="topback">
		<td align="<?php echo $GLOBALS["right"]; ?>" valign="top">
			<table border="0" width="100%" height="100%" cellpadding="1" cellspacing="0">
				<?php userdatamain('header'); ?>
			</table>
	</td></tr>
</table>
<?php


if (($GLOBALS["gsUseFrames"] == 'Y') && ($EZ_SESSION_VARS["noframesbrowser"] != True)) {
	?>
	</body>
	</html>
	<?php
} // Frames footer


function hGetTopGroupName()
{
	global $_GET;

	if ((isset($_GET["groupname"])) && ($_GET["groupname"] != '')) {
		$strQuery = "SELECT topgroupname FROM ".$GLOBALS["eztbGroups"]." WHERE groupname='".$_GET["groupname"]."' AND language='".$GLOBALS["gsLanguage"]."'";
		$result = dbRetrieve($strQuery,true,0,1);
		if ($rs = dbFetch($result)) { $topgroupname = $rs["topgroupname"];
		} else { $topgroupname = $GLOBALS["gsHomepageTopGroup"]; }
		dbFreeResult($result);
		return $topgroupname;
	}
	return $GLOBALS["gsHomepageTopGroup"];
} // function hGetTopGroupName()


function hGetGroupName()
{
	global $_GET;

	if ((isset($_GET["topgroupname"])) && ($_GET["topgroupname"] != '')) {
		if ($_GET["topgroupname"] != $GLOBALS["gsHomepageTopGroup"]) {
			$strQuery = "SELECT groupname FROM ".$GLOBALS["eztbGroups"]." WHERE topgroupname='".$_GET["topgroupname"]."' AND language='".$GLOBALS["gsDefault_language"]."' ORDER BY grouporderid";
			$result = dbRetrieve($strQuery,true,0,1);
			if ($rs = dbFetch($result)) { $groupname = $rs["groupname"];
			} else { $groupname = $GLOBALS["gsHomepageGroup"]; }
			dbFreeResult($result);
			return $groupname;
		}
	}
	return $GLOBALS["gsHomepageGroup"];
} // function hGetGroupName()


function hHomeLink($GroupName)
{
	global $EZ_SESSION_VARS, $groupname, $subgroupname;

	$link = '';
	if ($groupname != '') {
		$strQuery = "SELECT loginreq,groupname,grouplink FROM ".$GLOBALS["eztbGroups"]." WHERE groupname='".$groupname."' AND language='".$GLOBALS["gsLanguage"]."'";
	} else {
		$strQuery = "SELECT loginreq,groupname,grouplink FROM ".$GLOBALS["eztbGroups"]." WHERE groupname='".$GroupName."' AND language='".$GLOBALS["gsLanguage"]."'";
	}
	$result = dbRetrieve($strQuery,true,0,0);
	$rs	= dbFetch($result);
	if (($rs["loginreq"] == 'Y') && ($EZ_SESSION_VARS["PasswordCookie"] == '')) {
		$link = 'loginreq.php';
	} else {
		if ($rs["grouplink"] != '') { $link = $rs["grouplink"]; }
	}
	dbFreeResult($result);
	return $link;
} // function hHomeLink()


?>
