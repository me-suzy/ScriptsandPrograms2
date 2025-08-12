<?php

/***************************************************************************

 loginreq2.php
 --------------
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
include_once ($GLOBALS["rootdp"]."include/functions.php");
// This global is empty when framed.
if (($GLOBALS["gsUseFrames"] == 'Y') && ($EZ_SESSION_VARS["noframesbrowser"] != True)) {
	includeLanguageFiles('admin');
}

?>
<table border="1" cellpadding="1" cellspacing="0" align="center" valign="top" width="98%" class="headercontent">
	<tr><td class="tablecontent">
			&nbsp;<br />
			<?php
			if ($GLOBALS["LoginCookie"] != '') { echo $GLOBALS["tMustLogin"].' '; }
			else { echo $GLOBALS["tMustRegister"].' '; }
			?><span style="cursor:hand"><a onClick="javascript:window.open('<?php echo BuildLink('login.php'); ?>&topgroupname=<?php echo $_GET["topgroupname"]; ?>&groupname=<?php echo $_GET["groupname"]; ?>&subgroupname=<?php echo $_GET["subgroupname"]; ?>', 'Login', 'width=340,height=180,status=no,resizable=yes,scrollbars=no'); return(false);" <?php echo BuildLinkMouseOver($GLOBALS["tmLogin"]); ?> target="popup"><?php
			echo $GLOBALS["tLogin"]; ?></a></span> <?php echo $GLOBALS["tHigherPriv"].' '.$GLOBALS["tToAccessPrivate"]; ?>.<br />&nbsp;</td>
	</tr>
</table>
