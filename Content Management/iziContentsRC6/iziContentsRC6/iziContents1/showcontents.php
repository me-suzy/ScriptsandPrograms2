<?php

/***************************************************************************

 showcontents.php
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

$GLOBALS["rootdp"] = './';
require_once ($GLOBALS["rootdp"]."include/config.php");
require_once ($GLOBALS["rootdp"]."include/db.php");
require_once ($GLOBALS["rootdp"]."include/session.php");


// $GLOBALS["gsUseFrames"] is empty when framed.
//		settings.php will decide if it should be set or not
// The session variable noframesbrowser is set when viewing
//		a frame-configured site in a non-frames browser.
if (($GLOBALS["gsUseFrames"] == '') && ($EZ_SESSION_VARS["noframesbrowser"] != True)) {
	include_once ($GLOBALS["rootdp"]."include/settings.php");
	include_once ($GLOBALS["rootdp"]."include/functions.php");
	include_once ($GLOBALS["rootdp"]."include/banners.php");
	include_once ($GLOBALS["rootdp"]."include/content.php");
	includeLanguageFiles('admin','main');
}

if (($GLOBALS["gsUseFrames"] == 'Y') && ($EZ_SESSION_VARS["noframesbrowser"] != True)) {
	Start_Timer();
	Start_Gzip();
	force_page_refresh();
	HTMLHeader('content');
	StyleSheet();

	if ($GLOBALS["gsShowTopMenu"] == 'Y') {
		if (!isset($_GET["groupname"]) || $_GET["groupname"] == '') {
			if (!isset($_GET["topgroupname"]) || $_GET["topgroupname"] == '') {
				$_GET["topgroupname"] = $GLOBALS["gsHomepageTopGroup"];
			}
			$_GET["groupname"] = cGetGroupName($_GET["topgroupname"]);
		} elseif (!isset($_GET["topgroupname"]) || $_GET["topgroupname"] == '') {
			$_GET["topgroupname"] = cGetTopGroupName($_GET["groupname"]);
		}
	} elseif (!isset($_GET["groupname"]) || $_GET["groupname"] == '') {
		$_GET["groupname"] = cGetGroupName($_GET["topgroupname"]);
	}
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
	<body marginwidth="0" marginheight="0" leftmargin="5" rightmargin="5" topmargin="10" class="mainback"> <?php
}

?>
<table border="0" cellspacing="5" cellpadding="0" width="100%" height="100%">
<tr><td width="100%" height="100%" align="center" valign="top">
<?php

ContentPageHeader();

GetOrderByText($_GET["groupname"],$_GET["subgroupname"]);

$isodate = sprintf ("%04d-%02d-%02d", strftime("%Y"), strftime("%m"), strftime("%d"));

// If left/right content frame is set to automatic, see if there's anything to display in the right frame
if (($GLOBALS["gsLRContentFrame"] == 'Y') || ($GLOBALS["gsLRContentFrame"] == 'A')) {
	$rightcontent = false;
	if ($_GET["subgroupname"] == '') {
		if ($GLOBALS["gsLanguage"] == $GLOBALS["gsDefault_language"]) {
			$strQuery = "SELECT * FROM ".$GLOBALS["eztbContents"]." WHERE (groupname='".$_GET["groupname"]."'  OR groupname='999999999') AND subgroupname='' AND language='".$GLOBALS["gsLanguage"]."' AND publishdate<='".$isodate."' AND expiredate>='".$isodate."' AND contentactive='1' AND leftright='R' ".$GLOBALS["orderText"];
		} else {
			$strQuery = "SELECT * FROM ".$GLOBALS["eztbContents"]." WHERE (groupname='".$_GET["groupname"]."'  OR groupname='999999999') AND subgroupname='' AND (language='".$GLOBALS["gsLanguage"]."' OR language='".$GLOBALS["gsDefault_language"]."') AND publishdate<='".$isodate."' AND expiredate>='".$isodate."' AND contentactive='1' AND leftright='R' ".$GLOBALS["orderText"];
		}
	} else {
		if ($GLOBALS["gsLanguage"] == $GLOBALS["gsDefault_language"]) {
			$strQuery = "SELECT * FROM ".$GLOBALS["eztbContents"]." WHERE ((groupname='".$_GET["groupname"]."' AND subgroupname='".$_GET["subgroupname"]."') OR (groupname='999999999' AND (subgroupname='' OR subgroupname='0'))) AND language='".$GLOBALS["gsLanguage"]."' AND publishdate<='".$isodate."' AND expiredate>='".$isodate."' AND contentactive='1' AND leftright='R' ".$GLOBALS["orderText"];
		} else {
			$strQuery = "SELECT * FROM ".$GLOBALS["eztbContents"]." WHERE ((groupname='".$_GET["groupname"]."' AND subgroupname='".$_GET["subgroupname"]."') OR (groupname='999999999' AND (subgroupname='' OR subgroupname='0'))) AND (language='".$GLOBALS["gsLanguage"]."' OR language='".$GLOBALS["gsDefault_language"]."') AND publishdate<='".$isodate."' AND expiredate>='".$isodate."' AND contentactive='1' AND leftright='R' ".$GLOBALS["orderText"];
		}
	}
	$rightresult = dbRetrieve($strQuery,true,0,0);
	if (dbRowsReturned($rightresult) > 0) { $rightcontent = true; }
}


if (($GLOBALS["gsLRContentFrame"] == 'Y') || (($GLOBALS["gsLRContentFrame"] == 'A') && ($rightcontent))) {
	?>
	<table border="0" cellpadding="0" width="100%">
	<tr><td valign="top" width="100%">
	<?php
	if ($_GET["subgroupname"] == '') {
		if ($GLOBALS["gsLanguage"] == $GLOBALS["gsDefault_language"]) {
			$strQuery = "SELECT * FROM ".$GLOBALS["eztbContents"]." WHERE (groupname='".$_GET["groupname"]."'  OR groupname='999999999') AND (subgroupname='' OR subgroupname='0') AND language='".$GLOBALS["gsLanguage"]."' AND publishdate<='".$isodate."' AND expiredate>='".$isodate."' AND contentactive='1' AND leftright!='R' ".$GLOBALS["orderText"];
		} else {
			$strQuery = "SELECT * FROM ".$GLOBALS["eztbContents"]." WHERE (groupname='".$_GET["groupname"]."'  OR groupname='999999999') AND (subgroupname='' OR subgroupname='0') AND (language='".$GLOBALS["gsLanguage"]."' OR language='".$GLOBALS["gsDefault_language"]."') AND publishdate<='".$isodate."' AND expiredate>='".$isodate."' AND contentactive='1' AND leftright!='R' ".$GLOBALS["orderText"];
		}
	} else {
		if ($GLOBALS["gsLanguage"] == $GLOBALS["gsDefault_language"]) {
			$strQuery = "SELECT * FROM ".$GLOBALS["eztbContents"]." WHERE ((groupname='".$_GET["groupname"]."' AND subgroupname='".$_GET["subgroupname"]."') OR (groupname='999999999' AND (subgroupname='' OR subgroupname='0'))) AND language='".$GLOBALS["gsLanguage"]."' AND publishdate<='".$isodate."' AND expiredate>='".$isodate."' AND contentactive='1' AND leftright!='R' ".$GLOBALS["orderText"];
		} else {
			$strQuery = "SELECT * FROM ".$GLOBALS["eztbContents"]." WHERE ((groupname='".$_GET["groupname"]."' AND subgroupname='".$_GET["subgroupname"]."') OR (groupname='999999999' AND (subgroupname='' OR subgroupname='0'))) AND (language='".$GLOBALS["gsLanguage"]."' OR language='".$GLOBALS["gsDefault_language"]."') AND publishdate<='".$isodate."' AND expiredate>='".$isodate."' AND contentactive='1' AND leftright!='R' ".$GLOBALS["orderText"];
		}
	}
} else {
	if ($_GET["subgroupname"] == '') {
		if ($GLOBALS["gsLanguage"] == $GLOBALS["gsDefault_language"]) {
			$strQuery = "SELECT * FROM ".$GLOBALS["eztbContents"]." WHERE (groupname='".$_GET["groupname"]."' OR groupname='999999999') AND (subgroupname='' OR subgroupname='0') AND language='".$GLOBALS["gsLanguage"]."' AND publishdate<='".$isodate."' AND expiredate>='".$isodate."' AND contentactive='1' ".$GLOBALS["orderText"];
		} else {
			$strQuery = "SELECT * FROM ".$GLOBALS["eztbContents"]." WHERE (groupname='".$_GET["groupname"]."' OR groupname='999999999') AND (subgroupname='' OR subgroupname='0') AND (language='".$GLOBALS["gsLanguage"]."' OR language='".$GLOBALS["gsDefault_language"]."') AND publishdate<='".$isodate."' AND expiredate>='".$isodate."' AND contentactive='1' ".$GLOBALS["orderText"];
		}
	} else {
		if ($GLOBALS["gsLanguage"] == $GLOBALS["gsDefault_language"]) {
			$strQuery = "SELECT * FROM ".$GLOBALS["eztbContents"]." WHERE ((groupname='".$_GET["groupname"]."' AND subgroupname='".$_GET["subgroupname"]."') OR (groupname='999999999' AND (subgroupname='' OR subgroupname='0'))) AND language='".$GLOBALS["gsLanguage"]."' AND publishdate<='".$isodate."' AND expiredate>='".$isodate."' AND contentactive='1' ".$GLOBALS["orderText"];
		} else {
			$strQuery = "SELECT * FROM ".$GLOBALS["eztbContents"]." WHERE ((groupname='".$_GET["groupname"]."' AND subgroupname='".$_GET["subgroupname"]."') OR (groupname='999999999' AND (subgroupname='' OR subgroupname='0'))) AND (language='".$GLOBALS["gsLanguage"]."' OR language='".$GLOBALS["gsDefault_language"]."') AND publishdate<='".$isodate."' AND expiredate>='".$isodate."' AND contentactive='1' ".$GLOBALS["orderText"];
		}
	}
}

$leftresult = dbRetrieve($strQuery,true,0,0);
$nContentName = '';
while ($rsContent = dbFetch($leftresult)) {
	if ($rsContent["contentname"] != $nContentName) {
		$nContentName = $rsContent["contentname"];
		ShowArticle($rsContent);
	}
}
dbFreeResult($leftresult);


if (($GLOBALS["gsLRContentFrame"] == 'Y') || (($GLOBALS["gsLRContentFrame"] == 'A') && ($rightcontent))) {
	if ($GLOBALS["gnImageColumnBreak"] != '') {
		if (file_exists($GLOBALS["image_home"].$GLOBALS["gnImageColumnBreak"]) == true) {
			$imageInfo = getimagesize($GLOBALS["image_home"].$GLOBALS["gnImageColumnBreak"]);
			$size = $imageInfo[0];
		}
		?>
		</td>
		<td width=<?php echo $size; ?> height="100%" valign="bottom" class="sep_column">
		<img src="<?php echo './'.$GLOBALS["icon_home"].'blank.gif'; ?>" width=<?php echo $size; ?> height="100%">
		<?php
	}
	?>
	</td>
	<td width="<?php echo $GLOBALS["gnRightColumnWidth"]; ?>" height="100%" valign="top">
	<?php

	$nContentName = '';
	while ($rsContent = dbFetch($rightresult)) {
		if ($rsContent["contentname"] != $nContentName) {
			$nContentName = $rsContent["contentname"];
			ShowArticleRCol($rsContent);
		}
	}
	dbFreeResult($rightresult);
	?>
	<img src="<?php echo $GLOBALS["icon_home"]; ?>blank.gif" width="<?php echo $GLOBALS["gnRightColumnWidth"]; ?>" height="1" border="0"><br />
	</td></tr>
	</table>
	<?php
}

ShowFooterBanner();


function cGetGroupName($topgroupname)
{
	$gname = $GLOBALS["gsHomepageGroup"];
	$strQuery = "SELECT * FROM ".$GLOBALS["eztbGroups"]." WHERE topgroupname='".$topgroupname."' AND language='".$GLOBALS["gsDefault_language"]."' ORDER BY grouporderid";
	$result = dbRetrieve($strQuery,true,0,1);

	if ($rs = dbFetch($result)) { $gname = $rs["groupname"]; }
	dbFreeResult($result);
	return $gname;
} // function cGetGroupName()


function cGetTopGroupName($groupname)
{
	$gname = $GLOBALS["gsHomepageTopGroup"];
	$strQuery = "SELECT * FROM ".$GLOBALS["eztbGroups"]." WHERE groupname='".$groupname."' AND language='".$GLOBALS["gsDefault_language"]."' ORDER BY grouporderid";
	$result = dbRetrieve($strQuery,true,0,1);

	if ($rs = dbFetch($result)) { $gname = $rs["topgroupname"]; }
	dbFreeResult($result);
	return $gname;
} // function cGetTopGroupName()


?>
</td></tr>
</table>
<?php

// If we're in frames mode, output the page footer data
// In non-frames mode, this is handled by control.php
if (($GLOBALS["gsUseFrames"] == 'Y') && ($EZ_SESSION_VARS["noframesbrowser"] != True)) {
	End_Timer();
	?>

	</body>
	</html>
	<?php
	End_Gzip();
}

?>
