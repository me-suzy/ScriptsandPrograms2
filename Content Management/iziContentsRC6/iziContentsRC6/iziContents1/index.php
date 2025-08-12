<?php

/***************************************************************************

 index.php
 ----------
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
// get uri of file and redirect to index
if (!file_exists('include/config.php')){
	Header("Location: izi_install/index.php");
}


$GLOBALS["rootdp"] = './';
// index.php now generates the unique SID (in /include/session.php) and redirects to control.php
//	which does all the real grunt work.
// This is a frigaround, so a full screen refresh is of control.php rather than index.php, and
//	this won't generate a new SID.
//
require_once ($GLOBALS["rootdp"]."include/config.php");
require_once ($GLOBALS["rootdp"]."include/db.php");
require_once ($GLOBALS["rootdp"]."include/session.php");

require_once ($GLOBALS["rootdp"]."include/settings.php");
require_once ($GLOBALS["rootdp"]."include/functions.php");
include_once ($GLOBALS["rootdp"]."include/banners.php");
include_once ($GLOBALS["rootdp"]."include/content.php");
include_once ($GLOBALS["rootdp"]."include/visitorstats.php");
includeLanguageFiles('admin','main');


if (($stats_firstvisit) && ($GLOBALS["gsVisitorStats"] == 'Y')) {
	visitor_stats();
}

if (isset($_GET["noframesbrowser"])) {
	$EZ_SESSION_VARS["noframesbrowser"] = True;
	db_session_write();
}

// Theme laut Topgroup ermitteln
$EZ_SESSION_VARS["Theme"] = "";
if ($_GET["topgroupname"] != "") {
    if ($EZ_SESSION_VARS["Language"] != "") { $lang = $EZ_SESSION_VARS["Language"]; }
    else { $lang = "de"; }
    $strQuery = "SELECT topgroupname,language,toptheme FROM ".$GLOBALS["eztbTopgroups"]." WHERE topgroupname = '".$_GET["topgroupname"]."' AND language = '".$lang."'";
    $themeresult = dbRetrieve($strQuery,true,0,0);
    while ($themedata = dbFetch($themeresult)) {
        $EZ_SESSION_VARS["Theme"] = $themedata["toptheme"];
    }
    dbFreeResult($themeresult);
}
db_session_write();
// #### End Theme

if (isset($_GET["topgroupname"]))	{ $topgroupname = $_GET["topgroupname"]; }	else { $topgroupname = ''; }
if (isset($_GET["groupname"]))		{ $groupname = $_GET["groupname"]; }		else { $groupname = ''; }
if (isset($_GET["subgroupname"]))	{ $subgroupname = $_GET["subgroupname"]; }	else { $subgroupname = ''; }

if (($GLOBALS["gsUseFrames"] == 'Y') && ($EZ_SESSION_VARS["noframesbrowser"] != True)) {
	$SiteWidth  = $GLOBALS["gsSiteWidth"];
	$SiteHeight = $GLOBALS["gsSiteHeight"];
	if (($SiteWidth == '') or ($SiteWidth == 0)) { $SiteWidth = "100%"; }
	if (($SiteHeight == '') or ($SiteHeight == 0)) { $SiteHeight = "100%"; }

	HTMLHeader($GLOBALS["gsSitetitle"],"Frameset");
	?>
	<meta name="description" content="<?php echo $GLOBALS["gsSitedesc"]; ?>">
	<meta name="keywords" content="<?php echo $GLOBALS["gsSitekeywords"]; ?>">
	<?php if ($GLOBALS["favicon"] != '') { echo '<link rel="SHORTCUT ICON" href="'.$GLOBALS["image_home"].$GLOBALS["favicon"].'">'; } ?>

	</head>

	<frameset rows="*,<?php echo $SiteHeight; ?>,*" border="0" framespacing="0" frameborder="no">
		<frameset cols="*,<?php echo $SiteWidth; ?>,*" border="0" framespacing="0" frameborder="no">
			<frame src="<?php echo $GLOBALS["rootdp"]; ?>border.php?edge=topleft" name="v_topleft" noresize scrolling="no" TITLE="Top Left">
			<frame src="<?php echo $GLOBALS["rootdp"]; ?>border.php?edge=top" name="v_top" noresize scrolling="no" TITLE="Top">
			<frame src="<?php echo $GLOBALS["rootdp"]; ?>border.php?edge=topright" name="v_topright" noresize scrolling="no" TITLE="Top Right">
		</frameset>
		<frameset cols="*,<?php echo $SiteWidth; ?>,*" border="0" framespacing="0" frameborder="no" TITLE="Left">
			<frame src="<?php echo $GLOBALS["rootdp"]; ?>border.php?edge=left" name="v_left" noresize scrolling="no">
			<frame src="<?php echo BuildLink($GLOBALS["rootdp"].'control.php'); ?>&topgroupname=<?php echo $topgroupname; ?>&groupname=<?php echo $groupname; ?>&subgroupname=<?php echo $subgroupname; ?>" name="ezc" noresize scrolling="no" TITLE="Main ezContents Frame">
			<frame src="<?php echo $GLOBALS["rootdp"]; ?>border.php?edge=right" name="v_right" noresize scrolling="no" TITLE="Right">
		</frameset>
		<frameset cols="*,<?php echo $SiteWidth; ?>,*" border="0" framespacing="0" frameborder="no">
			<frame src="<?php echo $GLOBALS["rootdp"]; ?>border.php?edge=bottomleft" name="v_bottomleft" noresize scrolling="no" TITLE="Bottom Left">
			<frame src="<?php echo $GLOBALS["rootdp"]; ?>border.php?edge=bottom" name="v_bottom" noresize scrolling="no" TITLE="Bottom">
			<frame src="<?php echo $GLOBALS["rootdp"]; ?>border.php?edge=bottomright" name="v_bottomright" noresize scrolling="no" TITLE="Bottom Right">
		</frameset>
	</frameset>

	<body>
		<noframes>
			<p><b><?php echo $GLOBALS["tFramesUsed"]; ?>
			<a href="<?php echo BuildLink($_SERVER["REQUEST_URI"]); ?>&noframesbrowser=1&topgroupname=<?php echo $topgroupname; ?>&groupname=<?php echo $groupname; ?>&subgroupname=<?php echo $subgroupname; ?>"><?php echo $GLOBALS["tNonFramesClick"]; ?></a>
			</b><br /><br /><?php echo $GLOBALS["tNonFramesWarning"]; ?></p>
		</noframes>
	</body>
	</html>
<?php
} else {
	Header("Location: ".BuildLink($GLOBALS["rootdp"].'control.php').'&topgroupname='.$topgroupname.'&groupname='.$groupname.'&subgroupname='.$subgroupname);
}

?>
