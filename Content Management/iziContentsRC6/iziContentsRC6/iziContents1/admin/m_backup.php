<?php

/***************************************************************************

 m_backup.php
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

$GLOBALS["form"] = 'backup';
$GLOBALS["validaccess"] = VerifyAdminLogin();
include_once ($GLOBALS["rootdp"]."include/filefunctions.php");
validatefiletypes('Backup');

includeLanguageFiles('admin','backup');


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
			$delete = DeleteBackup();
		} else {
			$delete = DeleteBackupDir();
		}
	}
}


force_page_refresh();
frmBackup();


function frmBackup()
{
	global $_GET, $EzAdmin_Style;

	adminheader();
	admintitle(6,$GLOBALS["tFormTitle"]);
	adminbuttons('',$GLOBALS["tUploadBackup"],'',$GLOBALS["tDeleteBackup"]);
	$GLOBALS["iOpenFolder"] = imagehtmltag($GLOBALS["theme_home"],$EzAdmin_Style["FolderIcon"],'',0,'');
	if ($GLOBALS["iOpenFolder"] == '') $GLOBALS["iOpenFolder"] = imagehtmltag($GLOBALS["style_home"],$EzAdmin_Style["FolderIcon"],'',0,'');
	$GLOBALS["iBackup"] = imagehtmltag($GLOBALS["icon_home"],'backup_button.gif',$GLOBALS["tCreateBackup"],0,'');
	$GLOBALS["iRestore"] = imagehtmltag($GLOBALS["icon_home"],'restore_button.gif',$GLOBALS["tRestoreBackup"],0,'');

	$nCurrentPage = 0;
	if ($_GET["page"] != "") { $nCurrentPage = $_GET["page"]; }
	$lRecCount = lCountFiles($GLOBALS["backup_home"],'backup');
	$nPages = intval(($lRecCount - 0.5) / $GLOBALS["RECORDS_PER_PAGE"]) + 1;
	if ($nCurrentPage >= $nPages) { $nCurrentPage = 0; }
	$lStartRec = $nCurrentPage * $GLOBALS["RECORDS_PER_PAGE"];

	frmDisplayDir(6,$GLOBALS["backup_home"]);
	safeModeWarning(6);

	switch ($_GET["returncode"]) {
		case '-2' : $strMessage = '<tr bgcolor="#900000"><td colspan="6"><b>'.$GLOBALS["eERROR"].': '.$GLOBALS["eBackupFailed"].'</b></td></tr>';
					break;
		case '-1' : $strMessage = '<tr bgcolor="#900000"><td colspan="6"><b>'.$GLOBALS["eERROR"].': '.$GLOBALS["eRestoreFailed"].'</b></td></tr>';
					break;
		case '1'  : $strMessage = '<tr bgcolor="#009000"><td colspan="6"><b>'.$GLOBALS["mRestoreComplete"].'</b></td></tr>';
					break;
		case '2'  : $strMessage = '<tr bgcolor="#009000"><td colspan="6"><b>'.$GLOBALS["mBackupComplete"].'</b></td></tr>';
					break;
	}
	echo $strMessage;

	frmBackupsHdFt(6,$nCurrentPage,$nPages);
	?>
	<tr class="teaserheadercontent"><?php
		adminlistitem(5,$GLOBALS["tDel"],'c');
		adminlistitem(6,'&nbsp;','');
		adminlistitem(47,$GLOBALS["tBackupFilename"],'');
		adminlistitem(12,$GLOBALS["tFilesize"],'r');
		adminlistitem(25,$GLOBALS["tFiledate"],'r');
		adminlistitem(5,'&nbsp;','');
	?>
	</tr>
	<?php

	$nBackupNr = 0;
	$nBackupShowed = 0;

	if (isset($GLOBALS["files"])) {
		sort($GLOBALS["files"]);
		while (list($i,$val) = each($GLOBALS["files"])) {
			if ($GLOBALS["files"][$i]["filetype"] == 'backup') {
				if ($nBackupNr >= $lStartRec && $nBackupShowed < $GLOBALS["RECORDS_PER_PAGE"]) {
					?>
					<tr class="teasercontent">
						<td align="center" valign="top" class="content">
							<?php
							echo $GLOBALS["iBlank"].'&nbsp;';
							if ($GLOBALS["candelete"] === False) { echo $GLOBALS["iBlank"];
							} else {
								?><a href="javascript:DelBackup('subdir=<?php echo $_GET["subdir"]; ?>&Backup=<?php echo $GLOBALS["files"][$i]["filename"]; ?>&page=<?php echo $GLOBALS["page"]; ?>')" <?php echo BuildLinkMouseOver($GLOBALS["tDeleteBackup"]); ?>><?php echo $GLOBALS["iDelete"]; ?></a><?php
							}
							?>
						</td>
						<td align="center" valign="top" class="content">
							<?php
							if ($GLOBALS["files"][$i]["fileicon"] != '') {
								echo '<img src="'.$GLOBALS["rootdp"].$GLOBALS["image_home"].$GLOBALS["files"][$i]["fileicon"].'">';
							}
							?>
						</td>
						<td valign="top" class="content">
							<a href="javascript:DownloadBackup('<?php echo $GLOBALS["rootdp"].$GLOBALS["backup_home"].$_GET["subdir"].$GLOBALS["files"][$i]["filename"]; ?>')" title="<?php echo $GLOBALS["tDownloadBackup"]; ?>" <?php echo BuildLinkMouseOver($GLOBALS["tDownloadBackup"]); ?>><?php echo $GLOBALS["files"][$i]["filename"]; ?></a>
						</td>
						<td align="<?php echo $GLOBALS["right"]; ?>" valign="top" class="content">
							<?php echo $GLOBALS["files"][$i]["filesize"]; ?>
						</td>
						<td align="<?php echo $GLOBALS["right"]; ?>" valign="top" class="content">
							<?php echo $GLOBALS["files"][$i]["filetime"]; ?>
						</td>
						<td align="<?php echo $GLOBALS["right"]; ?>" valign="top" class="content">
							<?php
							if ($GLOBALS["canadd"] === False) { echo $GLOBALS["iBlank"];
							} else {
								?><a href="javascript:RestoreBackup('<?php echo $GLOBALS["rootdp"].$GLOBALS["backup_home"].$_GET["subdir"].$GLOBALS["files"][$i]["filename"]; ?>')" <?php echo BuildLinkMouseOver($GLOBALS["tRestoreBackup"]); ?>><?php echo $GLOBALS["iRestore"]; ?></a><?php
							}
							?>
						</td>
					</tr>
					<?php
					$nBackupShowed++;
				}
				$nBackupNr++;
			} else {
				if ((($_GET["subdir"] != '') && ($GLOBALS["files"][$i]["filename"] != '.')) || (($_GET["subdir"] == '') && ($GLOBALS["files"][$i]["filename"] != '.') && ($GLOBALS["files"][$i]["filename"] != '..'))) {
					if ($nBackupNr >= $lStartRec && $nBackupShowed < $GLOBALS["RECORDS_PER_PAGE"]) {
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
									?><a href="<?php echo BuildLink('m_backup.php'); ?>&subdir=<?php echo $newsubdir; ?>"><?php echo $GLOBALS["iOpenFolder"]; ?></a>&nbsp;<?php
									if ($GLOBALS["candelete"] === False) {
										echo $GLOBALS["iBlank"];
									} else {
										?><a href="javascript:DelDir('subdir=<?php echo $_GET["subdir"]; ?>&folder=<?php echo $GLOBALS["files"][$i]["filename"]; ?>&page=<?php echo $_GET["page"]; ?>')" <?php echo BuildLinkMouseOver($GLOBALS["tDeleteBackupDir"]); ?>><?php echo $GLOBALS["iDelete"]; ?></a><?php
									}
								}
								?>
							</td>
							<td align="center" valign="middle" class="content">
								&nbsp;
							</td>
							<td valign="top" class="content">
								<a href="<?php echo BuildLink('m_backup.php'); ?>&subdir=<?php echo $newsubdir; ?>">
								<?php echo $GLOBALS["files"][$i]["filename"]; ?></a>
							</td>
							<td align="center" valign="middle" class="content">
								&nbsp;
							</td>
							<td align="center" valign="middle" class="content">
								&nbsp;
							</td>
							<td align="center" valign="middle" class="content">
								&nbsp;
							</td>
						</tr>
						<?php
						$nBackupShowed++;
					}
					$nBackupNr++;
				}
			}
		}
	}

	frmBackupsHdFt(6,$nCurrentPage,$nPages);
	frmFreeSpace(6,$GLOBALS["backup_home"]);
	?>

	</table>
	</body>
	</html>
	<?php
} // function frmBackup()


?>
<script language="Javascript" type="text/javascript">
	<!-- Begin
	function DelBackup(sParams) {
		if (window.confirm('<?php echo $GLOBALS["tConfirmDeletion"]; ?>')) {
			location.href='<?php echo BuildLink('m_backup.php'); ?>&mode=delete&' + sParams;
		}
	}

	function DelDir(sParams) {
		if(window.confirm('<?php echo $GLOBALS["tConfirmDeletion"]; ?>')) {
			location.href='<?php echo BuildLink('m_backup.php'); ?>&mode=deletedir&' + sParams;
		}
	}

	function DownloadBackup(sBackupName) {
		window.open(sBackupName, "Backup", "width=1,height=1,status=no,resizable=yes,scrollbars=yes");
	}

	function UploadBackup() {
		window.open("<?php echo BuildLink('uploadbackup.php'); ?>&subdir=<?php echo $_GET["subdir"]; ?>", "UploadBackup", "width=600,height=310,status=no,resizable=no,scrollbars=no");
	}

	function RestoreBackup(sParams) {
		if (window.confirm('<?php echo $GLOBALS["tConfirmRestore"]; ?>')) {
			location.href='<?php echo BuildLink('restorebackup.php'); ?>&file=' + sParams;
		}
	}
	//  End -->
</script>
<?php


function frmBackupsHdFt($colspan,$nCurrentPage,$nPages)
{
	global $_GET;
	
	$pLink = BuildLink('m_backup.php').'&subdir='.$_GET["subdir"];
	$fLink = BuildLink('createbackup.php');
	$linkmod = '&subdir='.$_GET["subdir"];
	$hlink = '<a href="javascript:UploadBackup();" '.BuildLinkMouseOver($GLOBALS["tUploadBackup"]).'>';
	$dlink = '<a href="'.$fLink.'" '.BuildLinkMouseOver($GLOBALS["tCreateBackup"]).'>';
	echo '<form name="PagingForm" action="'.$pLink.'" method="GET">';
	?>
	<tr class="topmenuback">
		<td colspan="<?php echo $colspan; ?>" align="<?php echo $GLOBALS["left"]; ?>">
			<table height="100%" width="100%" cellspacing="0" cellpadding="0">
				<tr><?php
					if (($GLOBALS["canadd"] === True) && ($GLOBALS["file_uploads"])) {
						?><td align="<?php echo $GLOBALS["left"]; ?>" valign="bottom"><?php
						echo displaybutton('addbutton','backup',$GLOBALS["tUploadBackup"].'...',$hlink);
						?></td><?php
					}
					if ($GLOBALS["canadd"] === True) {
						?><td align="<?php echo $GLOBALS["left"]; ?>" valign="bottom"><?php
						echo displaybutton('addbutton','cbackup',$GLOBALS["tBackupDB"].'...',$dlink);
						?></td><?php
					}
					?><td align="<?php echo $GLOBALS["right"]; ?>" valign="bottom"><?php
					if ($nCurrentPage != 0) {
						?><a href="<?php echo $pLink; ?>&page=0" <?php
						echo BuildLinkMouseOver($GLOBALS["tFirstPage"]); ?>><?php
						echo $GLOBALS["iFirst"]; ?></a>&nbsp;<?php
						?><a href="<?php echo $pLink; ?>&page=<?php echo $nCurrentPage - 1; ?>" <?php
						echo BuildLinkMouseOver($GLOBALS["tPrevPage"]); ?>><?php
						echo $GLOBALS["iPrev"]; ?></a><?php
					} else {
						echo $GLOBALS["iFirst"].'&nbsp;'.$GLOBALS["iPrev"];
					}
					$nCPage = $nCurrentPage + 1;
					echo RenderPageList($nCPage,$nPages,'m_backup.php',$linkmod);
					if ($nCurrentPage + 1 != $nPages) {
						?><a href="<?php echo $pLink; ?>&page=<?php echo $nCurrentPage + 1; ?>" <?php
						echo BuildLinkMouseOver($GLOBALS["tNextPage"]); ?>><?php
						echo $GLOBALS["iNext"]; ?></a>&nbsp;<?php
						?><a href="<?php echo $pLink; ?>&page=<?php echo $nPages - 1; ?>" <?php
						echo BuildLinkMouseOver($GLOBALS["tLastPage"]); ?>><?php
						echo $GLOBALS["iLast"]; ?></a><?php
					} else {
						echo $GLOBALS["iNext"].'&nbsp;'.$GLOBALS["iLast"];
					}
					?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<?php
	echo '</form>';
} // function frmBackupsHdFt()


function DeleteBackup()
{
	global $_GET;

	$path = $GLOBALS["rootdp"].$GLOBALS["backup_home"].$_GET["subdir"];
	$ffile = $_GET["Backup"];
	$delete = DeleteFile($path,$ffile);

	return $delete;
} // function DeleteBackup()


function DeleteBackupDir()
{
	global $_GET;

	$path = $GLOBALS["rootdp"].$GLOBALS["backup_home"].$_GET["subdir"];
	$ffile = $_GET["folder"];
	$delete = DeleteDir($path,$ffile);

	return $delete;
} // function DeleteBackupDir()

?>

