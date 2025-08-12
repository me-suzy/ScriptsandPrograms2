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

include_once ("rootdatapath.php");

HTMLHeader("Control","Frameset");

if ($GLOBALS["gsAdminStyle"] != '') {
	?>
	<LINK HREF="<?php echo $GLOBALS["rootdp"].$GLOBALS["style_home"].$GLOBALS["gsAdminStyle"]; ?>/vs.css" REL=STYLESHEET TYPE="text/css">
	<?php
} else {
	include ($GLOBALS["rootdp"]."include/style.php");
}
?>
<title>ezContents - Manager</title>
</head>


<frameset framespacing="0" border="0" rows="58,*" frameborder="0">
	<frame name="topframe" src="<?php echo BuildLink('./top.php'); ?>" scrolling="auto" noresize TITLE="Header Frame">
	<frame name="mainbody" src="<?php echo BuildLink('./control2.php'); ?>" scrolling="auto" noresize TITLE="Control Frame">
</frameset>

<body>
	<noframes>
		<p>This page uses frames, but your browser doesn't support them.</p>
	</noframes>
</body>

</html>
