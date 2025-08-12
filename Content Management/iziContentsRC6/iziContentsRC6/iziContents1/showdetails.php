<?php

/***************************************************************************

 showdetails.php
 ----------------
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
	HTMLHeader('detail');
	StyleSheet();
	?>
	<script language="JavaScript" type="text/javascript">
		<!-- Begin
		function ChangeFrames(TopGroupName) {
			parent.left.location.href="<?php echo BuildLink('menu.php'); ?>&topgroupname=" + TopGroupName;
			parent.contents.location.href="<?php echo BuildLink('showcontents.php'); ?>&topgroupname=" + TopGroupName;
		}
		//  End -->
	</script>
	</head>
	<body marginwidth="0" marginheight="0" leftmargin="5" rightmargin="5" topmargin="10" class="mainback">
	<?php
}
?>
<table border="0" cellspacing="5" cellpadding="0" width="100%" height="100%">
<tr><td width="100%" align="center" valign="top">
<?php

ContentPageHeader();

$isodate = sprintf ("%04d-%02d-%02d", strftime("%Y"), strftime("%m"), strftime("%d"));

if ($GLOBALS["gsLanguage"] == $GLOBALS["gsDefault_language"]) {
	$strQuery = "SELECT * FROM ".$GLOBALS["eztbContents"]." WHERE contentname ='".$_GET["contentname"]."' AND language='".$GLOBALS["gsLanguage"]."'";
} else {
	$lOrder = '';
	if ($GLOBALS["gsLanguage"] > $GLOBALS["gsDefault_language"]) { $lOrder = ' DESC'; }
	$strQuery = "SELECT * FROM ".$GLOBALS["eztbContents"]." WHERE contentname ='".$_GET["contentname"]."' AND (language='".$GLOBALS["gsLanguage"]."' OR language='".$GLOBALS["gsDefault_language"]."') ORDER BY language".$lOrder;
}
$result = dbRetrieve($strQuery,true,0,0);

$nContentName = '';
while ($rsContent = dbFetch($result)) {
	if ($rsContent["contentname"] != $nContentName) {
		$nContentName = $rsContent["contentname"];
		ShowContent($rsContent,0);
	}
}

dbFreeResult($result);

?>
</td></tr>
<tr><td align=left valign="top"><?php DetailReturnLink($GLOBALS["tReturn"]); ?></td></tr>
<?php

ShowFooterBanner();

?>
</table>
<?php

if (($GLOBALS["gsUseFrames"] == 'Y')  && ($EZ_SESSION_VARS["noframesbrowser"] != True)) {
	End_Timer();
	?>
	</body>
	</html>
	<?php
	End_Gzip();
}

?>
