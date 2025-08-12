<?php

/***************************************************************************

 top.php
 --------
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

includeLanguageFiles('admin');


adminheader();
?>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" class="topback">
<table border=0 width="100%">
	<tr><td align="<?php echo $GLOBALS["left"]; ?>" valign="top">
			<a href="<?php echo BuildLink('start.php'); ?>" <?php echo BuildLinkMouseOver($GLOBALS["tsTitle"]); ?> target="content">
			<?php
			if ($GLOBALS["gsAdminStyle"] != '') {
				if (file_exists($GLOBALS["rootdp"].$GLOBALS["style_home"].$GLOBALS["gsAdminStyle"].'/images/logo_maint.gif') == true) {
					$homefile = imagehtmltag($GLOBALS["style_home"].$GLOBALS["gsAdminStyle"],'/images/logo_maint.gif','ezContents Maintenance','',0);
				} else {
					$homefile = imagehtmltag($GLOBALS["icon_home"],'logo_maint.gif','ezContents Maintenance','',0);
				}
			} else {
				$homefile = imagehtmltag($GLOBALS["icon_home"],'logo_maint.gif','ezContents Maintenance','',0);
			}
			if ($homefile == '') { echo '<H1>ezContents</H1>'; } else { echo $homefile; }
			?>
			</a>
		</td>
		<td align="<?php echo $GLOBALS["right"]; ?>" valign="top">
			<a class="menulink" href="<?php echo BuildLink('about.php'); ?>" <?php echo BuildLinkMouseOver($GLOBALS["tAbout"]); ?> target="content">
			<?php echo $GLOBALS["tAbout"]; ?></a><br />
			<a class="menulink" href="<?php echo BuildLink($GLOBALS["rootdp"].'index.php'); ?>" <?php echo BuildLinkMouseOver($GLOBALS["tViewEzContents"]); ?> target="_blank">
			<?php echo $GLOBALS["tViewEzContents"]; ?></a>
		</td>
	</tr>
</table>
</body>
</html>
