<?php

/***************************************************************************

 control2.php
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

// If user isn't logged in, they need to do so before we let them get any further
if ((!bVerifyLogin()) || ($EZ_SESSION_VARS["UserGroup"] == '')) {
	header("Location: ".BuildLink('adminlogin.php'));
} else {
	force_page_refresh();
	?>
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
	<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $GLOBALS["gsCharset"]; ?>">
	<?php
	if ($GLOBALS["gsAdminStyle"] != '') {
		?>
		<LINK HREF="<?php echo $GLOBALS["rootdp"].$GLOBALS["style_home"].$GLOBALS["gsAdminStyle"]; ?>/vs.css" REL=STYLESHEET TYPE="text/css">
		<?php
	} else {
		include ($GLOBALS["rootdp"]."include/style.php");
	}
	?>
	<title></title>
	</head>

	<?php
	if(is_dir("../izi_install")){
		?>
		<frameset framespacing="0" border="0" cols="<?php echo $EzAdmin_Style["adminmenuwidth"]; ?>,*" frameborder="0">
			<frame name="left" src="<?php echo BuildLink('./menu.php'); ?>" scrolling="auto" noresize TITLE="Menu Frame">
			<frame name="content" src="<?php echo BuildLink('./about.php'); ?>" TITLE="Content Frame">
		</frameset>
		<?php
	}
	elseif ($GLOBALS["gsDirection"] == 'rtl') {
		?>
		<frameset framespacing="0" border="0" cols="*,<?php echo $EzAdmin_Style["adminmenuwidth"]; ?>" frameborder="0">
			<frame name="content" src="<?php echo BuildLink('./start.php'); ?>" TITLE="Content Frame">
			<frame name="left" src="<?php echo BuildLink('./menu.php'); ?>" scrolling="auto" noresize TITLE="Menu Frame">
		</frameset>
		<?php
	} else {
		?>
		<frameset framespacing="0" border="0" cols="<?php echo $EzAdmin_Style["adminmenuwidth"]; ?>,*" frameborder="0">
			<frame name="left" src="<?php echo BuildLink('./menu.php'); ?>" scrolling="auto" noresize TITLE="Menu Frame">
			<frame name="content" src="<?php echo BuildLink('./start.php'); ?>" TITLE="Content Frame">
		</frameset>
		<?php
	}
	?>

	<noframes>
	<body>

	<p><?php echo $GLOBALS["tFramesRequired"]; ?></p>

	</body>
	</noframes>

	</html>
	<?php
}


function bVerifyLogin()
{
	global $EZ_SESSION_VARS;

	if (($EZ_SESSION_VARS["LoginCookie"] != '') && ($EZ_SESSION_VARS["PasswordCookie"] != '')) {
		$strQuery = "SELECT login FROM ".$GLOBALS["eztbAuthors"]." WHERE login='".$EZ_SESSION_VARS["LoginCookie"]."' AND userpassword='".$EZ_SESSION_VARS["PasswordCookie"]."'";
		$result = dbRetrieve($strQuery,true,0,0);
		$rs	= dbFetch($result);
		if ($rs["login"] == $EZ_SESSION_VARS["LoginCookie"]) {
			dbFreeResult($result);
			return true;
		}
		dbFreeResult($result);
	}
	return false;
} // function bVerifyLogin()

?>
