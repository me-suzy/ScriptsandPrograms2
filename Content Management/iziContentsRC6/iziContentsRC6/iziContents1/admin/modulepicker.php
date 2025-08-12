<?php

/***************************************************************************

 modulepicker.php
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
include_once ("rootdatapath.php");

includeLanguageFiles('admin');


force_page_refresh();
frmModules($_GET["extin"]);


function frmModules($extin)
{
	global $_GET, $EzAdmin_Style;

	admhdr();
	?>
	<script language="JavaScript" type="text/javascript">
		<!-- Begin
			function ReturnModule(sModuleName) {
				window.opener.document.MaintForm.<?php echo $_GET["control"]; ?>.value=sModuleName;
				window.close();
			}
		//  End -->
	</script>
	<title>ModulePicker</title>
	</head>
	<body leftmargin=0 topmargin=0 marginwidth="0" marginheight="0" class="mainback">
	<table border="0" width="100%" cellspacing="3" cellpadding="3"><?php

	// Generate image tags for the different images that appear on the page
	adminbuttons('','','','');
	$iSelectImage = imagehtmltag($GLOBALS["theme_home"],$EzAdmin_Style["SelectIcon"],'',0,'');

	$nCurrentPage = 0;
	if ($_GET["page"] != "") { $nCurrentPage = $_GET["page"]; }

	$strQuery = "SELECT moduleid FROM ".$GLOBALS["eztbModules"]." WHERE extin='".$extin."'";
	$result = dbRetrieve($strQuery,true,0,0);
	$rs			= dbFetch($result);
	$lRecCount = dbRowsReturned($result);
	dbFreeResult($result);

	$nPages = intval(($lRecCount - 0.5) / $GLOBALS["RECORDS_PER_PAGE"]) + 1;
	$lStartRec = $nCurrentPage * $GLOBALS["RECORDS_PER_PAGE"];

	frmModulesHdFt($nCurrentPage,$nPages,$extin);

	$sqlQuery = "SELECT * FROM ".$GLOBALS["eztbModules"]." WHERE extin='".$extin."' ORDER BY modulename";
	$result = dbRetrieve($sqlQuery,true,$lStartRec,$GLOBALS["RECORDS_PER_PAGE"]);
	while ($rs = dbFetch($result)) {
		?>
		<tr class="teasercontent">
			<td><a href="javascript:ReturnModule('<?php echo $GLOBALS["modules_home"].$rs["moduledirectory"].'/'.$rs["modulescript"]; ?>')"><?php echo $rs["modulename"]; ?></a></td>
		</tr>
		<?php
	}
	dbFreeResult($result);

	frmModulesHdFt($nCurrentPage,$nPages,$extin);

	?>
	<tr class="headercontent">
		<td align="<?php echo $GLOBALS["right"]; ?>"><a href="javascript:window.close();"><?php echo $GLOBALS["tCloseHelp"]; ?></a></td>
	</tr>
	</table>
	</body>
	</html>
	<?php
} // function frmModules()


function frmModulesHdFt($nCurrentPage,$nPages,$extin)
{
	global $_GET;
	?>
	<tr class="topmenuback">
		<td align="<?php echo $GLOBALS["left"]; ?>">
			<table height="100%" width="100%" cellspacing="0" cellpadding="0">
				<tr><td align="<?php echo $GLOBALS["right"]; ?>" valign="bottom">
						<a href="<?php echo BuildLink('modulepicker.php'); ?>&control=<?php echo $_GET["control"]; ?>&extin=<?php echo $extin; ?>&page=0"><?php echo $GLOBALS["iFirst"]; ?></a>&nbsp;<?php
						if ($nCurrentPage != 0) { ?><a href="<?php echo BuildLink('modulepicker.php'); ?>&control=<?php echo $_GET["control"]; ?>&extin=<?php echo $extin; ?>&page=<?php echo $nCurrentPage - 1; ?>"><?php echo $GLOBALS["iPrev"]; ?></a><?php } else { echo $GLOBALS["iPrev"]; }
						$nCPage = $nCurrentPage + 1;
						echo '&nbsp;&nbsp;'.$GLOBALS["tPage"].' '.$nCPage.' '.$GLOBALS["tOf"].' '.$nPages.'&nbsp;&nbsp;';
						if ($nCurrentPage + 1 != $nPages) { ?><a href="<?php echo BuildLink('modulepicker.php'); ?>&control=<?php echo $_GET["control"]; ?>&extin=<?php echo $extin; ?>&page=<?php echo $nCurrentPage + 1; ?>"><?php echo $GLOBALS["iNext"]; ?></a><?php } else { echo $GLOBALS["iNext"]; } ?>
						<a href="<?php echo BuildLink('modulepicker.php'); ?>&control=<?php echo $_GET["control"]; ?>&extin=<?php echo $extin; ?>&page=<?php echo $nPages - 1; ?>"><?php echo $GLOBALS["iLast"]; ?></a>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<?php
} // function frmModulesHdFt()

?>
