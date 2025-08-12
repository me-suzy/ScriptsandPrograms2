<?php

/***************************************************************************

 bottom.php
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
//	  settings.php will decide if it should be set or not
// The session variable noframesbrowser is set when viewing
//	  a frame-configured site in a non-frames browser.
if (($GLOBALS["gsUseFrames"] == '') && ($EZ_SESSION_VARS["noframesbrowser"] != True)) {
	include_once ($GLOBALS["rootdp"]."include/settings.php");
	include_once ($GLOBALS["rootdp"]."include/functions.php");
	HTMLHeader('bottom');
	StyleSheet();
	?>
	<base target="contents">
	</head>
	<body marginwidth="0" marginheight="0" leftmargin="0" topmargin="0" class="bottomback">
	<?php
}  // if Frames mode




// Display the actual content for the bottom frame
?>
<table border="0" width="100%" height="100%" cellspacing="0" cellpadding="0" class="bottomback">
	<tr width="100%">
		<td width="100%">
			<?php echo $GLOBALS["gsFooter"]; ?>
		</td>
		<?php
		if ($GLOBALS["PoweredByEZC"] == 'F') {
			echo '<td align="right" valign="middle" nowrap>';
			if (($GLOBALS["gsUseFrames"] == 'Y') && ($EZ_SESSION_VARS["noframesbrowser"] != True)) {
				?><a href="<?php echo BuildLink('about.php'); ?>" target="contents" <?php echo BuildLinkMouseOver('Powered by iziContents'); ?>><?php
			} else {
				?><a href="<?php echo BuildLink('about.php'); ?>" target="_blank" <?php echo BuildLinkMouseOver('Powered by iziContents'); ?>><?php
			}
			echo 'Powered by iziContents'; ?></a>
			&nbsp;<?php echo $GLOBALS["Version"];
			echo '</td>';
		}
		?>
	</tr>
</table>
<?php




// If we're in frames mode, output the page footer data
// In non-frames mode, this is handled by control.php
if (($GLOBALS["gsUseFrames"] == 'Y') && ($EZ_SESSION_VARS["noframesbrowser"] != True)) {
	?>
	</body>
	</html>
	<?php
}  // if Frames mode

?>
