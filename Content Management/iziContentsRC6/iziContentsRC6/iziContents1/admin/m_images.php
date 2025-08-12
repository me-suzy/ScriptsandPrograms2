<?php

/***************************************************************************

 m_images.php
 -------------
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


$GLOBALS["form"] = 'images';
$GLOBALS["validaccess"] = VerifyAdminLogin();
include_once ($GLOBALS["rootdp"]."include/filefunctions.php");
validatefiletypes('Image');

includeLanguageFiles('admin','images');


if (isset($_GET["subdir"])) {
	$_GET["subdir"] = str_replace('../', '', $_GET["subdir"]);
} else {
	$_GET["subdir"] = '';
}

if (($_GET["mode"] == 'delete') || ($_GET["mode"] == 'deletedir')) {
	if ($GLOBALS["candelete"] == False) {
		Header("Location: ".BuildLink('adminlogin.php'));
	} else {
		if ($_GET["mode"] == 'delete') {
			$delete = DeleteImage();
		} else {
			$delete = DeleteImageDir();
		}
	}
}


force_page_refresh();
frmImages();


function frmImages()
{
	global $_GET, $EzAdmin_Style;

	adminheader();
	if ($GLOBALS["ShowFilePermissions"] != 'N') {
		if ($GLOBALS["OS"] != "Windows") { $colcount = 8;
		} else { $colcount = 6; }
	} else { $colcount = 5; }

	admintitle($colcount,$GLOBALS["tFormTitle"]);
	adminbuttons('',$GLOBALS["tAddNewImage"],$GLOBALS["tEditImage"],$GLOBALS["tDeleteImage"]);
	$GLOBALS["iOpenFolder"] = imagehtmltag($GLOBALS["theme_home"],$EzAdmin_Style["FolderIcon"],'',0,'');
	if ($GLOBALS["iOpenFolder"] == '') $GLOBALS["iOpenFolder"] = imagehtmltag($GLOBALS["style_home"],$EzAdmin_Style["FolderIcon"],'',0,'');

	$nCurrentPage = 0;
	if($_GET["page"] != "") { $nCurrentPage = $_GET["page"]; }
	$lRecCount = lCountFiles($GLOBALS["image_home"],'image');
	$nPages = intval(($lRecCount - 0.5) / $GLOBALS["RECORDS_PER_PAGE"]) + 1;
	if ($nCurrentPage >= $nPages) { $nCurrentPage = 0; }
	$lStartRec = $nCurrentPage * $GLOBALS["RECORDS_PER_PAGE"];

	frmDisplayDir($colcount,$GLOBALS["image_home"]);
	safeModeWarning($colcount);


	frmImagesHdFt($colcount,$nCurrentPage,$nPages);
	?>
	<tr class="teaserheadercontent"><?php
		adminlistitem(10,$GLOBALS["tDel"],'');
		adminlistitem(6,'&nbsp;','');
		if ($GLOBALS["ShowFilePermissions"] != 'N') {
			if ($GLOBALS["OS"] != "Windows") { 
				adminlistitem(30,$GLOBALS["tImageName"],'');
				adminlistitem(10,'Permissions','');
				adminlistitem(10,'Owner','');
				adminlistitem(10,'Group','');
			} else {
				adminlistitem(50,$GLOBALS["tImageName"],'');
				adminlistitem(10,'Permissions','');
			}
		} else {
				adminlistitem(60,$GLOBALS["tImageName"],'');
		}
		adminlistitem(12,'&nbsp;','');
		adminlistitem(12,'&nbsp;','');
	?>
	</tr>
	<?php

	$nImageNr = 0;
	$nImageShowed = 0;

	if (isset($GLOBALS["files"])) {
		sort($GLOBALS["files"]);
		while (list($i,$val) = each($GLOBALS["files"])) {
			if ($GLOBALS["files"][$i]["filetype"] == 'image') {
				if ($nImageNr >= $lStartRec && $nImageShowed < $GLOBALS["RECORDS_PER_PAGE"]) {
					?>
					<tr class="teasercontent">
						<td align="center" valign="top" class="content">
							<?php
							echo $GLOBALS["iBlank"].'&nbsp;';
							if ($GLOBALS["candelete"] === False) { echo $GLOBALS["iBlank"];
							} else {
								?><a href="javascript:DelImage('subdir=<?php echo $_GET["subdir"]; ?>&Image=<?php echo $GLOBALS["files"][$i]["filename"]; ?>&page=<?php echo $GLOBALS["page"]; ?>')" <?php echo BuildLinkMouseOver($GLOBALS["tDeleteImage"]); ?>><?php echo $GLOBALS["iDelete"]; ?></a><?php
							}
							?>
						</td>
						<td align="center" valign="top" class="content">
							<a href="javascript:ShowImage('<?php echo $GLOBALS["rootdp"].$GLOBALS["image_home"].$_GET["subdir"].$GLOBALS["files"][$i]["filename"]; ?>')"><img src="<? echo $GLOBALS["rootdp"].$GLOBALS["image_home"].$_GET["subdir"].$GLOBALS["files"][$i]["filename"]; ?>" height=30 border=0 alt="Bild anzeigen"></a>
						</td>
						<td valign="top" class="content">
							<a href="javascript:ShowImage('<?php echo $GLOBALS["rootdp"].$GLOBALS["image_home"].$_GET["subdir"].$GLOBALS["files"][$i]["filename"]; ?>')"><?php echo $GLOBALS["files"][$i]["filename"]; ?></a>
						</td>
						<?php
						if ($GLOBALS["ShowFilePermissions"] != 'N') {
							?>
							<td align="center" valign="top" class="content">
								<?php echo $GLOBALS["files"][$i]["fileperms"]; ?>
							</td>
							<?php
							if ($GLOBALS["OS"] != "Windows") { 
								?>
								<td align="center" valign="top" class="content">
									<?php echo $GLOBALS["files"][$i]["fileowner"]; ?>
								</td>
								<td align="center" valign="top" class="content">
									<?php echo $GLOBALS["files"][$i]["filegroup"]; ?>
								</td>
							<?php
							}
						}
						?>
						<td align="<?php echo $GLOBALS["right"]; ?>" valign="top" class="content">
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
					if ($nImageNr >= $lStartRec && $nImageShowed < $GLOBALS["RECORDS_PER_PAGE"]) {
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
							<td align="center" valign="top" class="content">
								<?php
								if ($GLOBALS["files"][$i]["filename"] != '..') {
									?>
									<a href="<?php echo BuildLink('m_images.php'); ?>&subdir=<?php echo $newsubdir; ?>"><?php echo $GLOBALS["iOpenFolder"]; ?></a>&nbsp;
									<?php
									if ($GLOBALS["candelete"] === False) { echo $GLOBALS["iBlank"];
									} else {
										?><a href="javascript:DelDir('subdir=<?php echo $_GET["subdir"]; ?>&folder=<?php echo $GLOBALS["files"][$i]["filename"]; ?>&page=<?php echo $_GET["page"]; ?>')" <?php echo BuildLinkMouseOver($GLOBALS["tDeleteImageDir"]); ?>><?php echo $GLOBALS["iDelete"]; ?></a><?php
									}
								}
								?>
							</td>
							<td align="center" valign="middle" class="content">
								&nbsp;
							</td>
							<td valign="top" class="content">
								<a href="<?php echo BuildLink('m_images.php'); ?>&subdir=<?php echo $newsubdir; ?>"><?php echo $GLOBALS["files"][$i]["filename"]; ?></a>
							</td>
							<?php
							if ($GLOBALS["ShowFilePermissions"] != 'N') {
								?>
								<td align="center" valign="top" class="content">
									<?php echo $GLOBALS["files"][$i]["fileperms"]; ?>
								</td>
								<?php
								if ($GLOBALS["OS"] != "Windows") { 
									?>
									<td align="center" valign="top" class="content">
										<?php echo $GLOBALS["files"][$i]["fileowner"]; ?>
									</td>
									<td align="center" valign="top" class="content">
										<?php echo $GLOBALS["files"][$i]["filegroup"]; ?>
									</td>
								<?php
								}
							}
							?>
							<td align="center" valign="middle" class="content">
								&nbsp;
							</td>
							<td align="center" valign="middle" class="content">
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

	frmImagesHdFt($colcount,$nCurrentPage,$nPages);
	frmFreeSpace($colcount,$GLOBALS["image_home"]);
	?>
	</table>
	</body>
	</html>
	<?php
} // function frmImages()


function frmImagesHdFt($colspan,$nCurrentPage,$nPages)
{
	global $_GET;

	$pLink = BuildLink('m_images.php');
	$linkmod = '&subdir='.$_GET["subdir"];
	$hlink = '<a href="javascript:UploadImage();" '.BuildLinkMouseOver($GLOBALS["tAddNew"]).'>';
	echo '<form name="PagingForm" action="'.$pLink.'" method="GET">';
	?>
	<tr class="topmenuback">
		<td colspan="<?php echo $colspan; ?>" align="<?php echo $GLOBALS["left"]; ?>">
			<table height="100%" width="100%" cellspacing="0" cellpadding="0">
				<tr><?php
					if (($GLOBALS["canadd"] === True) && ($GLOBALS["file_uploads"])) {
						?><td align="<?php echo $GLOBALS["left"]; ?>" valign="bottom"><?php
						echo displaybutton('addbutton','images',$GLOBALS["tAddNew"].'...',$hlink);
						?></td><?php
					}
					?>
					<td align="<?php echo $GLOBALS["right"]; ?>" valign="bottom"><?php
						if ($nCurrentPage != 0) { ?><a href="<?php echo $pLink.$linkmod; ?>&page=0" <?php echo BuildLinkMouseOver($GLOBALS["tFirstPage"]); ?>><?php echo $GLOBALS["iFirst"]; ?></a><?php } else { echo $GLOBALS["iFirst"]; }
						echo '&nbsp;';
						if ($nCurrentPage != 0) { ?><a href="<?php echo $pLink.$linkmod; ?>&page=<?php echo $nCurrentPage - 1; ?>" <?php echo BuildLinkMouseOver($GLOBALS["tPrevPage"]); ?>><?php echo $GLOBALS["iPrev"]; ?></a><?php } else { echo $GLOBALS["iPrev"]; }
						$nCPage = $nCurrentPage + 1;
						echo RenderPageList($nCPage,$nPages,'m_images.php',$linkmod);
						if ($nCurrentPage + 1 != $nPages) { ?><a href="<?php echo $pLink.$linkmod; ?>&page=<?php echo $nCurrentPage + 1; ?>" <?php echo BuildLinkMouseOver($GLOBALS["tNextPage"]); ?>><?php echo $GLOBALS["iNext"]; ?></a><?php } else { echo $GLOBALS["iNext"]; }
						echo '&nbsp;';
						if ($nCurrentPage + 1 != $nPages) { ?><a href="<?php echo $pLink.$linkmod; ?>&page=<?php echo $nPages - 1; ?>" <?php echo BuildLinkMouseOver($GLOBALS["tLastPage"]); ?>><?php echo $GLOBALS["iLast"]; ?></a><?php } else { echo $GLOBALS["iLast"]; } ?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<?php
	echo '</form>';
} // function frmImagesHdFt()


include($GLOBALS["rootdp"]."include/javafuncs.php");

?>
<script language="Javascript" type="text/javascript">
	<!-- Begin
	function DelImage(sParams) {
		if (window.confirm('<?php echo $GLOBALS["tConfirmDeletion"]; ?>')) {
			location.href='<?php echo BuildLink('m_images.php'); ?>&mode=delete&' + sParams;
		}
	}

	function DelDir(sParams) {
		if (window.confirm('<?php echo $GLOBALS["tConfirmDeletion"]; ?>')) {
			location.href='<?php echo BuildLink('m_images.php'); ?>&mode=deletedir&' + sParams;
		}
	}

	function UploadImage() {
		window.open("<?php echo BuildLink('uploadimage.php'); ?>&subdir=<?php echo $_GET["subdir"]; ?>", "UploadImage", "width=600,height=310,status=no,resizable=no,scrollbars=no");
	}
	//  End -->
</script>
<?php


function DeleteImage()
{
	global $_GET;

	$path = $GLOBALS["rootdp"].$GLOBALS["image_home"].$_GET["subdir"];
	$ffile = $_GET["Image"];
	$delete = DeleteFile($path,$ffile);

	return $delete;
} // function DeleteImage()


function DeleteImageDir()
{
	global $_GET;

	$path = $GLOBALS["rootdp"].$GLOBALS["image_home"].$_GET["subdir"];
	$ffile = $_GET["folder"];
	$delete = DeleteDir($path,$ffile);

	return $delete;
} // function DeleteImageDir()

?>