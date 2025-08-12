<?php

/***************************************************************************

 control.php
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

$GLOBALS["rootdp"] = './';
require_once ($GLOBALS["rootdp"]."include/config.php");
require_once ($GLOBALS["rootdp"]."include/db.php");
require_once ($GLOBALS["rootdp"]."include/session.php");

require_once ($GLOBALS["rootdp"]."include/settings.php");

require_once ($GLOBALS["rootdp"]."include/functions.php");
include_once ($GLOBALS["rootdp"]."include/banners.php");
include_once ($GLOBALS["rootdp"]."include/content.php");
includeLanguageFiles('admin','main');

if (isset($_POST["ezSID"])) { $_GET["ezSID"] = $_POST["ezSID"]; }
if (isset($_POST["topgroupname"])) { $_GET["topgroupname"] = $_POST["topgroupname"]; }
if (isset($_POST["groupname"])) { $_GET["groupname"] = $_POST["groupname"]; }
if (isset($_POST["subgroupname"])) { $_GET["subgroupname"] = $_POST["subgroupname"]; }
if (isset($_POST["contentname"])) { $_GET["contentname"] = $_POST["contentname"]; }
if (isset($_POST["page"])) { $_GET["page"] = $_POST["page"]; }
if (isset($_POST["link"])) { $_GET["link"] = $_POST["link"]; }
if (isset($_POST["ref"])) { $_GET["ref"] = $_POST["ref"]; }
if (isset($_POST["noframesbrowser"])) { $_GET["noframesbrowser"] = $_POST["noframesbrowser"]; }


if (isset($_GET["noframesbrowser"])) {
	$EZ_SESSION_VARS["noframesbrowser"] = $_GET["noframesbrowser"];
	db_session_write();
}

// Theme laut Topgroup ermitteln
$EZ_SESSION_VARS["Theme"] = "";
if ($_GET["topgroupname"] != "") {
    if ($EZ_SESSION_VARS["Language"] != "") { $lang = $EZ_SESSION_VARS["Language"]; }
    else { $lang = "de"; }
    $strQuery = "SELECT topgroupname,language,toptheme FROM ".$GLOBALS["eztbTopgroups"]." WHERE topgroupname = '".$_GET["topgroupname"]."'";
    $themeresult = dbRetrieve($strQuery,true,0,0);
    while ($themedata = dbFetch($themeresult)) {
        $EZ_SESSION_VARS["Theme"] = $themedata["toptheme"];
    }
    dbFreeResult($themeresult);
}
db_session_write();
// #### End Theme


Start_Timer();
Start_Gzip();


if (($GLOBALS["gsShowTopMenu"] == 'Y') && ((!isset($_GET["topgroupname"])) || ($_GET["topgroupname"] == ''))) {
	$_GET["topgroupname"] = iGetTopGroupName();
	if ((!isset($_GET["groupname"])) || ($_GET["groupname"] == '')) { $_GET["groupname"] = iGetGroupName(); }
} else {
	if ((!isset($_GET["groupname"])) || ($_GET["groupname"] == '')) {
		if ($GLOBALS["gsShowTopMenu"] == 'Y') { $_GET["groupname"] = iGetGroupName(); }
		else { $_GET["groupname"] = $GLOBALS["gsHomepageGroup"]; }
	}
}


$topgroupname = $_GET["topgroupname"];
$groupname = $_GET["groupname"];
$subgroupname = $_GET["subgroupname"];


HTMLHeader($GLOBALS["gsSitetitle"]);
?>
<meta name="description" content="<?php echo $GLOBALS["gsSitedesc"]; ?>">
<meta name="keywords" content="<?php echo $GLOBALS["gsSitekeywords"]; ?>"><?php


if (($GLOBALS["gsUseFrames"] != 'Y') || ($EZ_SESSION_VARS["noframesbrowser"] == True)) { StyleSheet(); }

?>
</head>
<?php


if (($GLOBALS["gsUseFrames"] == 'Y') && ($EZ_SESSION_VARS["noframesbrowser"] != True)) {
	if (($GLOBALS["gsShowTopMenu"] == 'Y') && ($GLOBALS["gnTopFrameHeight"] != '') && ($GLOBALS["gnTopFrameHeight"] > 0)) {
		if ($GLOBALS["gnBottomFrame"] == 'Y') {
			?>
			<frameset framespacing="0" border="0" rows="<?php echo $GLOBALS["gnTopFrameHeight"]; ?>,<?php echo $GLOBALS["gnTopMenuFrameHeight"]; ?>,*,<?php echo $GLOBALS["gnBottomFrameHeight"]; ?>" frameborder="0">
			<?php
		} else {
			?>
			<frameset framespacing="0" border="0" rows="<?php echo $GLOBALS["gnTopFrameHeight"]; ?>,<?php echo $GLOBALS["gnTopMenuFrameHeight"]; ?>,*" frameborder="0">
			<?php
		}
	} else {
		if ($GLOBALS["gnBottomFrame"] == 'Y') {
			?>
			<frameset framespacing="0" border="0" rows="<?php echo $GLOBALS["gnTopFrameHeight"]; ?>,*,<?php echo $GLOBALS["gnBottomFrameHeight"]; ?>" frameborder="0">
			<?php
		} else {
			?>
			<frameset framespacing="0" border="0" rows="<?php echo $GLOBALS["gnTopFrameHeight"]; ?>,*" frameborder="0">
			<?php
		}
	}
	if ($GLOBALS["gsUserdataFrame"] == 'T') {
		if ($GLOBALS["gsDirection"] == 'rtl') {
			?>
			<frameset cols="<?php echo $GLOBALS["gnUserdataFrameWidth"]; ?>,*">
				<frame name="userdata" scrolling="no" noresize src="<?php echo BuildLink('headeruserdata.php'); ?>&topgroupname=<?php echo $topgroupname; ?>&groupname=<?php echo $groupname; ?>&subgroupname=<?php echo $subgroupname; ?>" TITLE="Userdata Frame">
				<frame name="top" scrolling="no" noresize src="<?php echo BuildLink('top.php'); ?>" TITLE="Header Frame">
			</frameset>
			<?php
		} else {
			?>
			<frameset cols="*,<?php echo $GLOBALS["gnUserdataFrameWidth"]; ?>">
				<frame name="top" scrolling="no" noresize src="<?php echo BuildLink('top.php'); ?>" TITLE="Header Frame">
				<frame name="userdata" scrolling="no" noresize src="<?php echo BuildLink('headeruserdata.php'); ?>&topgroupname=<?php echo $topgroupname; ?>&groupname=<?php echo $groupname; ?>&subgroupname=<?php echo $subgroupname; ?>" TITLE="Userdata Frame">
			</frameset>
			<?php
		}
	} else {
		?>
		<frame name="top" scrolling="no" noresize src="<?php echo BuildLink('top.php'); ?>" TITLE="Header Frame">
		<?php
	}
	if (($GLOBALS["gsShowTopMenu"] == 'Y') && ($GLOBALS["gnTopMenuFrameHeight"] != '') && ($GLOBALS["gnTopMenuFrameHeight"] > 0)) {
		?>
		<frame name="topmenu" scrolling="no" noresize src="<?php echo BuildLink('topmenu.php'); ?>&topgroupname=<?php echo $topgroupname; ?>" TITLE="Top Menu Frame">
		<?php
	}
	if (($GLOBALS["gsMenuFrameAlign"] == 'R') || (($GLOBALS["gsMenuFrameAlign"] == 'A') && ($GLOBALS["gsDirection"] == 'rtl'))) {
		?>
		<frameset cols="*,<?php echo $GLOBALS["gnLeftFrameWidth"]; ?>">
			<frame name="contents" <?php if ($GLOBALS["gsUserdataFrame"] != 'T') { echo 'src="'.sHomeLink($GLOBALS["gsHomepageGroup"]).'"'; } ?> TITLE="Content Frame">
			<frame name="left" <?php if ($GLOBALS["gsUserdataFrame"] != 'T') { echo 'src="'.BuildLink('menu.php').'&topgroupname='.$topgroupname.'&groupname='.$groupname.'&subgroupname='.$subgroupname.'"'; } ?> scrolling="auto" noresize TITLE="Menu Frame">
		</frameset>
		<?php
	} else {
		?>
		<frameset cols="<?php echo $GLOBALS["gnLeftFrameWidth"]; ?>,*">
			<frame name="left" <?php if ($GLOBALS["gsUserdataFrame"] != 'T') { echo 'src="'.BuildLink('menu.php').'&topgroupname='.$topgroupname.'&groupname='.$groupname.'&subgroupname='.$subgroupname.'"'; } ?> scrolling="auto" noresize TITLE="Menu Frame">
			<frame name="contents" <?php if ($GLOBALS["gsUserdataFrame"] != 'T') { echo 'src="'.sHomeLink($GLOBALS["gsHomepageGroup"]).'"'; } ?> TITLE="Content Frame">
		</frameset>
		<?php
	}
	if ($GLOBALS["gnBottomFrame"] == 'Y') {
		?>
		<frame name="bottom" scrolling="no" noresize src="<?php echo BuildLink('bottom.php'); ?>" TITLE="Footer Frame">
		<?php
	}
	?>
	<noframes>
		<body>
			<p><b><?php echo $GLOBALS["tFramesUsed"]; ?>
			<a href="<?php echo BuildLink('index.php'); ?>&noframesbrowser=1><?php echo $GLOBALS["tNonFramesClick"]; ?></a>
			</b><br /><br /><?php echo $GLOBALS["tNonFramesWarning"]; ?></p>
		</body>
	</noframes>
	</frameset>
	<?php
} else {
	if ($GLOBALS["gsSiteWidth"] == '') { $GLOBALS["gsSiteWidth"] = '100%'; }
	if ($GLOBALS["favicon"] != '') { echo '<link rel="SHORTCUT ICON" href="'.$GLOBALS["image_home"].$GLOBALS["favicon"].'">'; }
	?>
	<body marginwidth="0" marginheight="0" leftmargin="0" topmargin="0" class=borderback onLoad="putFocus(0,0);">
	<?
    if ($GLOBALS["gsShowMouseover"] == "Y") {
	?>
	<script language="JavaScript1.2">dqm__codebase = "scripts/"</script><script language="JavaScript1.2" src="scripts/sample_settings.js"></script><script language="JavaScript1.2">function alert() {}</script>
	<? } ?>
	<center>
	<table border="0" cellspacing="0" cellpadding="0" width="<?php echo $GLOBALS["gsSiteWidth"]; ?>" height="<?php echo $GLOBALS["gnTopFrameHeight"]; ?>">
		<tr>
			<?php
			if ($GLOBALS["gsUserdataFrame"] == 'T') {
				$twidth = $GLOBALS["gsSiteWidth"] - $GLOBALS["gnUserdataFrameWidth"];
			} else {
				$twidth = $GLOBALS["gsSiteWidth"];
			}
			if ($GLOBALS["gsDirection"] == 'ltr') {
				?>
				<td width="<?php echo $twidth; ?>" valign="top" align="<?php echo $GLOBALS["left"]; ?>" class="topback">
				<?php
				include ($GLOBALS["rootdp"]."top.php");
				echo '</td>';
			}
			if ($GLOBALS["gsUserdataFrame"] == 'T') {
				?>
				<td width="<?php echo $GLOBALS["gnUserdataFrameWidth"]; ?>" valign="top" align="<?php echo $GLOBALS["left"]; ?>" class="topback">
					<?php
					if ($_GET["ulink"] != "") {
						if (strrpos ($_GET["ulink"], "?") === false) {
							$ulinkfile = $_GET["ulink"];
							$ulinkparms = "";
						} else {
							$ulinkfile = trim(substr($_GET["ulink"], 0, strpos($_GET["ulink"], "?")));
							$ulinkparms = trim(substr(strstr($_GET["ulink"], "?"), 1));
						}
						include ($GLOBALS["rootdp"].$ulinkfile);
					} else {
						include ($GLOBALS["rootdp"]."headeruserdata.php");
					}
				echo '</td>';
			} elseif ($_GET["ulink"] != "") {
				$_GET["link"] = $_GET["ulink"];
			}
			if ($GLOBALS["gsDirection"] == 'rtl') {
				?>
				<td width="<?php echo $twidth; ?>" valign="top" align="<?php echo $GLOBALS["left"]; ?>" class="topback">
					<?php
					include ($GLOBALS["rootdp"]."top.php");
				echo '</td>';
			}
			?>
		</tr>
	</table>
	<?php

	if (($GLOBALS["gsShowTopMenu"] == 'Y') && ($GLOBALS["gnTopMenuFrameHeight"] != '') && ($GLOBALS["gnTopMenuFrameHeight"] > 0)) {
		?>
		<table border="0" cellspacing="0" cellpadding="0" width="<?php echo $GLOBALS["gsSiteWidth"]; ?>" height="<?php echo $GLOBALS["gnTopMenuFrameHeight"]; ?>">
			<tr>
				<td width="100%" valign="top" align="center" class="topmenuback">
					<?php include ($GLOBALS["rootdp"]."topmenu.php"); ?>
				</td>
			</tr>
		</table>
		<?php
	}
	?>
	<table border="0" cellspacing="0" cellpadding="0" width="<?php echo $GLOBALS["gsSiteWidth"]; ?>">
		<tr>
			<?php
			if (($GLOBALS["gsMenuFrameAlign"] == 'L') || (($GLOBALS["gsMenuFrameAlign"] == 'A') && ($GLOBALS["gsDirection"] == 'ltr'))) { displaymenu(); } ?>
			<td height="100%" width="100%" valign="top" align="<?php echo $GLOBALS["left"]; ?>" class="mainback">
				<img src="<?php echo $GLOBALS["icon_home"]; ?>blank.gif" width="<?php echo $GLOBALS["gsSiteWidth"] - $GLOBALS["gnLeftFrameWidth"]; ?>" height="1" border="0"><br />
				<?php
				if ($_GET["link"] != "") {
					if (strrpos ($_GET["link"], "?") === false) {
						$linkfile = $_GET["link"];
						$linkparms = "";
					} else {
						$linkfile = trim(substr($_GET["link"], 0, strpos($_GET["link"], "?")));
						$linkparms = trim(substr(strstr($_GET["link"], "?"), 1));
					}
					include_once ($GLOBALS["rootdp"].$GLOBALS["modules_home"]."modfunctions.php");
					include_once ($GLOBALS["rootdp"].$GLOBALS["admin_home"]."adminfunctions.php");
					include ($GLOBALS["rootdp"]."module.php");
				} else {
					if ($_GET["groupname"] == "") {
						$_GET["groupname"] = $GLOBALS["gsHomepageGroup"];
					}
					$_GET["link"] = sHomeLinkNoFrames($GLOBALS["groupname"]);
					if ($_GET["link"] != "") {
						if (strrpos ($_GET["link"], "?") === false) {
							$linkfile = $_GET["link"];
							$linkparms = "";
						} else {
							$linkfile = trim(substr($_GET["link"], 0, strpos($_GET["link"], "?")));
							$linkparms = trim(substr(strstr($_GET["link"], "?"), 1));
						}
						include_once ($GLOBALS["rootdp"].$GLOBALS["modules_home"]."modfunctions.php");
						include_once ($GLOBALS["rootdp"].$GLOBALS["admin_home"]."adminfunctions.php");
						include ($GLOBALS["rootdp"]."module.php");
					} else {
						if ($_GET["contentname"] == "") {
							include ($GLOBALS["rootdp"]."showcontents.php");
						} else {
							include ($GLOBALS["rootdp"]."showdetails.php");
						}
					}
				}
				?>
			</td>
			<?php
			if (($GLOBALS["gsMenuFrameAlign"] == 'R') || (($GLOBALS["gsMenuFrameAlign"] == 'A') && ($GLOBALS["gsDirection"] == 'rtl'))) { displaymenu(); } ?>
		</tr>
	</table>
	<?php


	End_Timer();

	if ($GLOBALS["gnBottomFrame"] == 'Y') {
		?>
		<table border="0" cellspacing="0" cellpadding="0" width="<?php echo $GLOBALS["gsSiteWidth"]; ?>" height="<?php echo $GLOBALS["gnBottomFrameHeight"]; ?>">
			<tr><td width="100%" valign="top" align="center" class="bottomback">
					<?php include ($GLOBALS["rootdp"]."bottom.php"); ?>
			</td></tr>
		</table>
		<?php
	}

	?>
	</center>

	</body>
	<?php
}
?>
</html>
<?php

End_Gzip();


function displaymenu()
{
	global $_GET, $_POST;
	?>
	<td width="<?php echo $GLOBALS["gnLeftFrameWidth"]; ?>" height="100%" valign="top" align="<?php echo $GLOBALS["left"]; ?>" class="menuback">
	<img src="<?php echo $GLOBALS["icon_home"]; ?>blank.gif" width="<?php echo $GLOBALS["gnLeftFrameWidth"]; ?>" height="1" border="0"><br />
	<?php include ($GLOBALS["rootdp"]."menu.php"); ?>
	</td>
	<?php
}


function sHomeLink($GroupName)
{
	global $EZ_SESSION_VARS, $groupname, $subgroupname;

	if ($subgroupname != '') {
		$strQuery = "SELECT loginreq,groupname,subgroupname,subgrouplink FROM ".$GLOBALS["eztbSubgroups"]." WHERE groupname='".$groupname."' AND subgroupname='".$subgroupname."' AND language='".$GLOBALS["gsLanguage"]."'";
		$result = dbRetrieve($strQuery,true,0,0);
		$rs	= dbFetch($result);
		if (($rs["loginreq"] == 'Y') && ($EZ_SESSION_VARS["PasswordCookie"] == '')) {
			$homepagelink = BuildLink('module.php')."&link=loginreq.php&groupname=".$rs["groupname"]."&subgroupname=".$rs["subgroupname"];
		} else {
			if ($rs["subgrouplink"] != '') {
				if (isExternalLink($rs["subgrouplink"])) { $homepagelink = $rs["subgrouplink"];
				} else { $homepagelink = BuildLink('module.php')."&link=".$rs["grouplink"]."&groupname=".$rs["groupname"]; }
			} else { $homepagelink = BuildLink('showcontents.php')."&groupname=".$rs["groupname"]."&subgroupname=".$rs["subgroupname"]; }
		}
	} else {
		if ($groupname != '') {
			$strQuery = "SELECT loginreq,groupname,grouplink FROM ".$GLOBALS["eztbGroups"]." WHERE groupname='".$groupname."' AND language='".$GLOBALS["gsLanguage"]."'";
		} else {
			$strQuery = "SELECT loginreq,groupname,grouplink FROM ".$GLOBALS["eztbGroups"]." WHERE groupname='".$GroupName."' AND language='".$GLOBALS["gsLanguage"]."'";
		}
		$result = dbRetrieve($strQuery,true,0,0);
		$rs	= dbFetch($result);
		if (($rs["loginreq"] == 'Y') && ($EZ_SESSION_VARS["PasswordCookie"] == '')) {
			$homepagelink = BuildLink('module.php')."&link=loginreq.php&groupname=".$rs["groupname"];
		} else {
			if ($rs["grouplink"] != '') {
				if (isExternalLink($rs["grouplink"])) { $homepagelink = $rs["grouplink"];
				} else { $homepagelink = BuildLink('module.php')."&link=".$rs["grouplink"]."&groupname=".$rs["groupname"]; }
			} else { $homepagelink = BuildLink('showcontents.php')."&groupname=".$rs["groupname"]; }
		}
	}
	dbFreeResult($result);
	return $homepagelink;
} // function sHomeLink()


function sHomeLinkNoFrames($GroupName)
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
} // function sHomeLinkNoFrames()


function iGetTopGroupName()
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
} // function iGetTopGroupName()


function iGetGroupName()
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
} // function iGetGroupName()

?>
