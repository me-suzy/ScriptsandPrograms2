<?php

/***************************************************************************

 imagepicker.php
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

include_once ("rootdatapath.php");

includeLanguageFiles('admin','images');

if (isset($_GET["subdir"])) {
	$_GET["subdir"] = str_replace($GLOBALS["rootdp"], '', $_GET["subdir"]);
} else {
	$_GET["subdir"] = '';
}
$ImageFileTypes = array('gif', 'jpg', 'jpeg', 'png');

force_page_refresh();
frmImages();


function frmImages()
{
	global $ImageFileTypes, $_GET, $EzAdmin_Style;

	admhdr();
	?>
	<script language="JavaScript" type="text/javascript">
		<!-- Begin
			function ReturnImage(sImageName) {
				window.opener.document.MaintForm.<?php echo $_GET["control"]; ?>.value=sImageName;
				window.close();
			}
		//  End -->
	</script>
	<title>ImagePicker</title>
	</head>
	<body leftmargin=0 topmargin=0 marginwidth="0" marginheight="0" class="mainback">
	<table border="0" width="100%" cellspacing="3" cellpadding="3"><?php

	// Generate image tags for the different images that appear on the page
	adminbuttons('','','','');
	$iOpenFolder = imagehtmltag($GLOBALS["theme_home"],$EzAdmin_Style["FolderIcon"],'',0,'');
	if ($iOpenFolder == '') $iOpenFolder = imagehtmltag($GLOBALS["style_home"],$EzAdmin_Style["FolderIcon"],'',0,'');
	$iSelectImage = imagehtmltag($GLOBALS["theme_home"],$EzAdmin_Style["SelectIcon"],'',0,'');
	if ($iSelectImage == '') $iSelectImage = imagehtmltag($GLOBALS["style_home"],$EzAdmin_Style["SelectIcon"],'',0,'');

	$nCurrentPage = 0;
	if($_GET["page"] != "") { $nCurrentPage = $_GET["page"]; }

	$lRecCount = lCountImages();
	$nPages = intval(($lRecCount - 0.5) / 10) + 1;
	$lStartRec = $nCurrentPage * 10;
	?>
	<tr class="headercontent">
		<td colspan="4" align="<?php echo $GLOBALS["left"]; ?>"><?php echo $GLOBALS["image_home"].$_GET["subdir"] ?></td>
	</tr>
	<?php frmImagesHdFt(4,$nCurrentPage,$nPages); ?>
	<tr class="teaserheadercontent">
		<td width="12%"><b><?php echo $GLOBALS["tShowImage"]; ?></b></td>
		<td width="50%" valign="bottom"><b><?php echo $GLOBALS["tImageName"]; ?></b></td>
		<td width="18%">&nbsp;</td>
		<td width="20%">&nbsp;</td>
	</tr>
	<?php

	$nImageNr = 0;
	$nImageShowed = 0;

	if (isset($GLOBALS["files"])) {
		sort($GLOBALS["files"]);
		while (list($i,$val) = each($GLOBALS["files"])) {
			if ($GLOBALS["files"][$i]["filetype"] == 'image') {
				if ($nImageNr >= $lStartRec && $nImageShowed < 10) {
					?>
					<tr class="teasercontent">
						<td align="center" valign="middle">
							<a href="javascript:ShowImage('<?php echo $GLOBALS["rootdp"].$GLOBALS["image_home"].$_GET["subdir"].$GLOBALS["files"][$i]["filename"]; ?>')">
							<?php echo $iSelectImage; ?></a>
						</td>
						<td valign="top">
							<a href="javascript:ReturnImage('<?php echo $_GET["subdir"].$GLOBALS["files"][$i]["filename"]; ?>')">
							<?php echo $GLOBALS["files"][$i]["filename"]; ?></a>
						</td>
						<td align="<?php echo $GLOBALS["right"]; ?>" valign="top">
							<?php echo $GLOBALS["files"][$i]["filesize"]; ?>
						</td>
						<td align="center" valign="top" class="content">
							<?php echo $GLOBALS["files"][$i]["filedims"]; ?>
						</td>
					</tr>
					<?php
					$nImageShowed++;
				}
				$nImageNr++;
			} else {
				if ((($_GET["subdir"] != '') && ($GLOBALS["files"][$i]["filename"] != '.')) || (($_GET["subdir"] == '') && ($GLOBALS["files"][$i]["filename"] != '.') && ($GLOBALS["files"][$i]["filename"] != '..'))) {
					if ($nImageNr >= $lStartRec && $nImageShowed < 10) {
						if ($GLOBALS["files"][$i]["filename"] != '..') {
							$newsubdir = $_GET["subdir"].$GLOBALS["files"][$i]["filename"].'/';
						} else {
							$dirbreakdown = explode('/',$_GET["subdir"]);
							array_pop($dirbreakdown); array_pop($dirbreakdown);
							$newsubdir = implode('/',$dirbreakdown).'/';
							if ($newsubdir == '/') { $newsubdir = ''; }
						}
						?>
						<tr class="teasercontent">
							<td align="center" valign="middle">
								<?php
								if ($GLOBALS["files"][$i]["filename"] != '..') {
									?><a href="<?php echo BuildLink('imagepicker.php'); ?>&control=<?php echo $_GET["control"]; ?>&subdir=<?php echo $newsubdir; ?>"><?php echo $iOpenFolder; ?></a><?php
								}
								?>
							</td>
							<td valign="top">
								<a href="<?php echo BuildLink('imagepicker.php'); ?>&control=<?php echo $_GET["control"]; ?>&subdir=<?php echo $newsubdir; ?>"><?php echo $GLOBALS["files"][$i]["filename"]; ?></a>
							</td>
							<td align="center" valign="middle">
								&nbsp;
							</td>
							<td align="center" valign="middle">
								&nbsp;
							</td>
						</tr>
						<?php
						$nImageShowed++;
					}
					$nImageNr++;
				}
			}
		}
	}

	frmImagesHdFt(4,$nCurrentPage,$nPages);
	?>
	<tr class="headercontent">
		<td colspan="4" align="<?php echo $GLOBALS["right"]; ?>"><a href="javascript:window.close();"><?php echo $GLOBALS["tCloseWindow"]; ?></a></td>
	</tr>
	</table>
	</body>
	</html>
	<?php
} // function frmImages()


function lCountImages()
{
	global $ImageFileTypes, $_GET;

	$nImageCount = 0;
	$savedir = getcwd();
	chdir($GLOBALS["rootdp"].$GLOBALS["image_home"].$_GET["subdir"]);
	if ($handle = @opendir('.')) {
		while ($file = readdir($handle)) {
			$filename = $file;
			if (is_file($filename)) {
				$fileparts = pathinfo($filename);
				$file_ext = strtolower($fileparts["extension"]);
				if (in_array($file_ext,$ImageFileTypes)) {
					$GLOBALS["files"][$nImageCount]["filename"] = $filename;
					$GLOBALS["files"][$nImageCount]["filetype"] = 'image';
					$GLOBALS["files"][$nImageCount]["filesize"] = display_size(filesize($filename));
					$size = GetImageSize($filename);
					$GLOBALS["files"][$nImageCount]["filedims"] = $size["0"].' x '.$size["1"];
					$nImageCount++;
				}
			} elseif (is_dir($filename)) {
				if ((!(($filename == '..') && ($_GET["subdir"] == ''))) && ($filename != '.')) {
					$GLOBALS["files"][$nImageCount]["filename"] = $filename;
					$GLOBALS["files"][$nImageCount]["filetype"] = 'dir';
					$nImageCount++;
				}
			}
		}
		closedir($handle);
	}
	chdir($savedir);
	return $nImageCount;
} // function lCountImages()


function frmImagesHdFt($colspan,$nCurrentPage,$nPages)
{
	global $_GET;
	?>
	<tr class="topmenuback">
		<td colspan="<?php echo $colspan; ?>" align="<?php echo $GLOBALS["left"]; ?>">
			<table height="100%" width="100%" cellspacing="0" cellpadding="0">
				<tr><td align="<?php echo $GLOBALS["right"]; ?>" valign="bottom">
						<a href="<?php echo BuildLink('imagepicker.php'); ?>&control=<?php echo $_GET["control"]; ?>&subdir=<?php echo $_GET["subdir"]; ?>&page=0"><?php echo $GLOBALS["iFirst"]; ?></a> <?php
						if ($nCurrentPage != 0) { ?><a href="<?php echo BuildLink('imagepicker.php'); ?>&control=<?php echo $_GET["control"]; ?>&subdir=<?php echo $_GET["subdir"]; ?>&page=<?php echo $nCurrentPage - 1; ?>"><?php echo $GLOBALS["iPrev"]; ?></a><?php } else { echo $GLOBALS["iPrev"]; }
						$nCPage = $nCurrentPage + 1;
						echo '&nbsp;&nbsp;'.$GLOBALS["tPage"].' '.$nCPage.' '.$GLOBALS["tOf"].' '.$nPages.'&nbsp;&nbsp;';
						if ($nCurrentPage + 1 != $nPages) { ?><a href="<?php echo BuildLink('imagepicker.php'); ?>&control=<?php echo $_GET["control"]; ?>&subdir=<?php echo $_GET["subdir"]; ?>&page=<?php echo $nCurrentPage + 1; ?>"><?php echo $GLOBALS["iNext"]; ?></a><?php } else { echo $GLOBALS["iNext"]; } ?>
						<a href="<?php echo BuildLink('imagepicker.php'); ?>&control=<?php echo $_GET["control"]; ?>&subdir=<?php echo $_GET["subdir"]; ?>&page=<?php echo $nPages - 1; ?>"><?php echo $GLOBALS["iLast"]; ?></a>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<?php
} // function frmImagesHdFt()


include($GLOBALS["rootdp"]."include/javafuncs.php");

?>

