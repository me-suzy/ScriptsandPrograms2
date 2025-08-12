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


// index.php now generates the unique SID (in /include/session.php) and redirects to control.php
//		which does all the real grunt work.
// Note that if you have two windows open, one for admin, the other on the main page, you _may_ have
//		two SIDs, one for each (and two entries in the visitor stats tables if this feature is enabled).

include_once ("rootdatapath.php");

includeLanguageFiles('admin','main');

HTMLHeader($GLOBALS["gsSitetitle"],"Frameset");
	?>
	<meta name="description" content="<?php echo $GLOBALS["gsSitedesc"]; ?>">
	<meta name="keywords" content="<?php echo $GLOBALS["gsSitekeywords"]; ?>">
	<?php if ($GLOBALS["favicon"] != '') { echo '<link rel="SHORTCUT ICON" href="'.$GLOBALS["rootdp"].$GLOBALS["image_home"].$GLOBALS["favicon"].'">'; } ?>
</head>

<frameset rows="*,100%,*" border="0" framespacing="0" frameborder="no">
	<frameset cols="*,100%,*" border="0" framespacing="0" frameborder="no">
		<frame src="<?php echo $GLOBALS["rootdp"]; ?>border.php?edge=topleft" name="v_topleft" noresize scrolling="no">
		<frame src="<?php echo $GLOBALS["rootdp"]; ?>border.php?edge=top" name="v_top" noresize scrolling="no">
		<frame src="<?php echo $GLOBALS["rootdp"]; ?>border.php?edge=topright" name="v_topright" noresize scrolling="no">
	</frameset>
	<frameset cols="*,100%,*" border="0" framespacing="0" frameborder="no">
		<frame src="<?php echo $GLOBALS["rootdp"]; ?>border.php?edge=left" name="v_left" noresize scrolling="no">
		<frame src="
		<?php echo BuildLink('./control.php'); ?>" name="ezc" noresize scrolling="no">
		<frame src="<?php echo $GLOBALS["rootdp"]; ?>border.php?edge=right" name="v_right" noresize scrolling="no">
	</frameset>
	<frameset cols="*,100%,*" border="0" framespacing="0" frameborder="no">
		<frame src="<?php echo $GLOBALS["rootdp"]; ?>border.php?edge=bottomleft" name="v_bottomleft" noresize scrolling="no">
		<frame src="<?php echo $GLOBALS["rootdp"]; ?>border.php?edge=bottom" name="v_bottom" noresize scrolling="no">
		<frame src="<?php echo $GLOBALS["rootdp"]; ?>border.php?edge=bottomright" name="v_bottomright" noresize scrolling="no">
	</frameset>
</frameset>

<body>
	<noframes>
		<p><b><?php echo $GLOBALS["tFramesUsed"]; ?></b></p>
	</noframes>
</body>

</html>

