<?php

/***************************************************************************

 gzip.php
 ---------
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

includeLanguageFiles('admin','main','serversettings');


if (strpos($_SERVER["HTTP_ACCEPT_ENCODING"], 'x-gzip') !== FALSE) {
	$GLOBALS["gzip_encoding"] = 'x-gzip';
} elseif (strpos($_SERVER["HTTP_ACCEPT_ENCODING"],'gzip') !== FALSE) {
	$GLOBALS["gzip_encoding"] = 'gzip';
}


admhdr();
?>
<TITLE><?php echo $GLOBALS["tOnlineHelp"].' - '.$GLOBALS["tGzipTest"]; ?></TITLE>
</head>
<body leftmargin=0 topmargin=0 marginwidth="0" marginheight="0" class="mainback">
<table border="0" width="100%" cellspacing="3" cellpadding="3">
	<tr class="headercontent">
		<td class="header"><b><?php echo $GLOBALS["tGzipTest"]; ?></b></td>
	</tr>
	<tr class="tablecontent"><td>
		<?php
		if (isset($GLOBALS["gzip_encoding"])) {
			echo $GLOBALS["tGzipSupported"];
		} else {
			echo $GLOBALS["tGzipUnsupported"];
		}
		?>
	</td></tr>
	<tr class="headercontent">
		<td align="<?php echo $GLOBALS["right"]; ?>"><a href="javascript:window.close();"><?php echo $GLOBALS["tCloseHelp"]; ?></a></td>
	</tr>
</table>
</body>
</html>
