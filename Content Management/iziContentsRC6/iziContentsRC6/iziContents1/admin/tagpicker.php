<?php

/***************************************************************************

 tagpicker.php
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

include_once ("rootdatapath.php");

includeLanguageFiles('admin');


force_page_refresh();
frmTags();


function frmTags()
{
	global $_GET, $EzAdmin_Style;

	admhdr();
	if ($_GET["WYSIWYG"] != 'Y') {
		?>
		<script language="JavaScript" type="text/javascript">
			<!-- Begin
				function ReturnModule(sTagName) {
					var input=window.opener.document.forms["MaintForm"].<?php echo $_GET["control"]; ?>;
					input.value=input.value+sTagName;
					window.close();
				}
			//  End -->
		</script>
		<?php
	}
	?>
	<title>TagPicker</title>
	</head>
	<body leftmargin=0 topmargin=0 marginwidth="0" marginheight="0" class="mainback">
	<table border="0" width="100%" cellspacing="3" cellpadding="3"><?php

	// Generate image tags for the different images that appear on the page
	adminbuttons('','','','');
	$iSelectImage = imagehtmltag($GLOBALS["theme_home"],$EzAdmin_Style["SelectIcon"],'',0,'');

	$nCurrentPage = 0;
	if ($_GET["page"] != "") { $nCurrentPage = $_GET["page"]; }

	$strQuery = "SELECT tagid FROM ".$GLOBALS["eztbTags"];
	$result = dbRetrieve($strQuery,true,0,0);
	$rs			= dbFetch($result);
	$lRecCount = dbRowsReturned($result);
	dbFreeResult($result);

	$nPages = intval(($lRecCount - 0.5) / $GLOBALS["RECORDS_PER_PAGE"]) + 1;
	$lStartRec = $nCurrentPage * $GLOBALS["RECORDS_PER_PAGE"];

	frmTagsHdFt($nCurrentPage,$nPages);

	$sqlQuery = "SELECT * FROM ".$GLOBALS["eztbTags"]." ORDER BY cat,tagid";
	$result = dbRetrieve($sqlQuery,true,$lStartRec,$GLOBALS["RECORDS_PER_PAGE"]);
	while ($rs = dbFetch($result)) {
		?>
		<tr class="teasercontent">
			<td width="15%" valign="top"><?php
			if ($_GET["WYSIWYG"] != 'Y') {
				?><a class="menulink" href="javascript:ReturnModule('<?php echo $GLOBALS["tqBlock1"].$rs["tag"].$GLOBALS["tqBlock2"]; ?>')"><?php
			}
			echo $GLOBALS["tqBlock1"].$rs["tag"].$GLOBALS["tqBlock2"];
			if ($_GET["WYSIWYG"] != 'Y') { echo '</a>'; }
			?></td>
			<td width="20%" valign="top"><?php echo GetCat($rs["cat"]); ?></td>
			<td valign="top"><?php echo htmlspecialchars($rs["translation"]); ?></td>
		</tr>
		<?php
	}
	dbFreeResult($result);

	frmTagsHdFt($nCurrentPage,$nPages);

	?>
	<tr class="headercontent">
		<td colspan="3" align="<?php echo $GLOBALS["right"]; ?>"><a href="javascript:window.close();"><?php echo $GLOBALS["tCloseHelp"]; ?></a></td>
	</tr>
	</table>
	</body>
	</html>
	<?php
} // function frmTags()


function frmTagsHdFt($nCurrentPage,$nPages)
{
	global $_GET;
	?>
	<tr class="topmenuback">
		<td colspan="3" align="<?php echo $GLOBALS["left"]; ?>">
			<table height="100%" width="100%" cellspacing="0" cellpadding="0">
				<tr><td align="<?php echo $GLOBALS["right"]; ?>" valign="bottom">
						<a href="<?php echo BuildLink('tagpicker.php'); ?>&control=<?php echo $_GET["control"]; ?>&WYSIWYG=<?php echo $_GET["WYSIWYG"]; ?>&page=0"><?php echo $GLOBALS["iFirst"]; ?></a>&nbsp;<?php
						if ($nCurrentPage != 0) { ?><a href="<?php echo BuildLink('tagpicker.php'); ?>&control=<?php echo $_GET["control"]; ?>&WYSIWYG=<?php echo $_GET["WYSIWYG"]; ?>&page=<?php echo $nCurrentPage - 1; ?>"><?php echo $GLOBALS["iPrev"]; ?></a><?php } else { echo $GLOBALS["iPrev"]; }
						$nCPage = $nCurrentPage + 1;
						echo '&nbsp;&nbsp;'.$GLOBALS["tPage"].' '.$nCPage.' '.$GLOBALS["tOf"].' '.$nPages.'&nbsp;&nbsp;';
						if ($nCurrentPage + 1 != $nPages) { ?><a href="<?php echo BuildLink('tagpicker.php'); ?>&control=<?php echo $_GET["control"]; ?>&WYSIWYG=<?php echo $_GET["WYSIWYG"]; ?>&page=<?php echo $nCurrentPage + 1; ?>"><?php echo $GLOBALS["iNext"]; ?></a><?php } else { echo $GLOBALS["iNext"]; } ?>
						<a href="<?php echo BuildLink('tagpicker.php'); ?>&control=<?php echo $_GET["control"]; ?>&WYSIWYG=<?php echo $_GET["WYSIWYG"]; ?>&page=<?php echo $nPages - 1; ?>"><?php echo $GLOBALS["iLast"]; ?></a>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<?php
} // function frmTagsHdFt()


function GetCat($catcode)
{
   $strQuery = "SELECT catdesc FROM ".$GLOBALS["eztbTagCategories"]." WHERE catname='".$catcode."' AND language='".$GLOBALS["gsLanguage"]."'";
   $result = dbRetrieve($strQuery,true,0,0);
   $rsc = dbFetch($result);
   $catname = $rsc["catdesc"];
   return $catname;
}

?>
