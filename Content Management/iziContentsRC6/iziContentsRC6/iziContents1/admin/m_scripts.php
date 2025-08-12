<?php

/***************************************************************************

 m_scripts.php
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

$GLOBALS["form"] = 'scripts';
$GLOBALS["validaccess"] = VerifyAdminLogin();
include_once ($GLOBALS["rootdp"]."include/filefunctions.php");
validatefiletypes('Script');

includeLanguageFiles('admin','scripts');


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
			$delete = DeleteScript();
		} else {
			$delete = DeleteScriptDir();
		}
	}
}


force_page_refresh();
frmScripts();


function frmScripts()
{
	global $_GET, $EzAdmin_Style;

	adminheader();
	if ($GLOBALS["ShowFilePermissions"] != 'N') {
		if ($GLOBALS["OS"] != "Windows") { $colcount = 7;
		} else { $colcount = 5; }
	} else { $colcount = 4; }

	admintitle($colcount,$GLOBALS["tFormTitle"]);
	adminbuttons('',$GLOBALS["tAddNewScript"],$GLOBALS["tEditScript"],$GLOBALS["tDeleteScript"]);
	$GLOBALS["iOpenFolder"] = imagehtmltag($GLOBALS["theme_home"],$EzAdmin_Style["FolderIcon"],'',0,'');
	if ($GLOBALS["iOpenFolder"] == '') $GLOBALS["iOpenFolder"] = imagehtmltag($GLOBALS["style_home"],$EzAdmin_Style["FolderIcon"],'',0,'');

	$nCurrentPage = 0;
	if ($_GET["page"] != "") { $nCurrentPage = $_GET["page"]; }
	$lRecCount = lCountFiles($GLOBALS["script_home"],'script');
	$nPages = intval(($lRecCount - 0.5) / $GLOBALS["RECORDS_PER_PAGE"]) + 1;
	if ($nCurrentPage >= $nPages) { $nCurrentPage = 0; }
	$lStartRec = $nCurrentPage * $GLOBALS["RECORDS_PER_PAGE"];

	frmDisplayDir($colcount,$GLOBALS["script_home"]);
	safeModeWarning($colcount);


	frmScriptsHdFt($colcount,$nCurrentPage,$nPages);
	?>
	<tr class="teaserheadercontent"><?php
		adminlistitem(10,$GLOBALS["tDel"],'');
		adminlistitem(6,'&nbsp;','');
		if ($GLOBALS["ShowFilePermissions"] != 'N') {
			if ($GLOBALS["OS"] != "Windows") { 
				adminlistitem(42,$GLOBALS["tScriptName"],'');
				adminlistitem(10,'Permissions','');
				adminlistitem(10,'Owner','');
				adminlistitem(10,'Group','');
			} else {
				adminlistitem(62,$GLOBALS["tScriptName"],'');
				adminlistitem(10,'Permissions','');
			}
		} else {
			adminlistitem(72,$GLOBALS["tScriptName"],'');
		}
		adminlistitem(12,'&nbsp;','');
	?>
	</tr>
	<?php

	$nScriptNr = 0;
	$nScriptShowed = 0;

	if (isset($GLOBALS["files"])) {
		sort($GLOBALS["files"]);
		while (list($i,$val) = each($GLOBALS["files"])) {
			if ($GLOBALS["files"][$i]["filetype"] == 'script') {
				if ($nScriptNr >= $lStartRec && $nScriptShowed < $GLOBALS["RECORDS_PER_PAGE"]) {
					?>
					<tr class="teasercontent">
						<td align="center" valign="middle" class="content">
							<?php
							echo $GLOBALS["iBlank"].'&nbsp;';
							if ($GLOBALS["candelete"] === False) { echo $GLOBALS["iBlank"];
							} else {
								?><a href="javascript:DelScript('subdir=<?php echo $_GET["subdir"]; ?>&Script=<?php echo $GLOBALS["files"][$i]["filename"]; ?>&page=<?php echo $GLOBALS["page"]; ?>')" <?php echo BuildLinkMouseOver($GLOBALS["tDeleteScript"]); ?>><?php echo $GLOBALS["iDelete"]; ?></a><?php
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
							<a href="javascript:ShowScript('<?php echo $GLOBALS["rootdp"].$GLOBALS["script_home"].$_GET["subdir"].$GLOBALS["files"][$i]["filename"]; ?>')"><?php echo $GLOBALS["files"][$i]["filename"]; ?></a>
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
					</tr>
					<?php
					$nScriptShowed++;
				}
				$nScriptNr++;
			} else {
				if ((($_GET["subdir"] != '') && ($GLOBALS["files"][$i]["filename"] != '.')) || (($_GET["subdir"] == '') && ($GLOBALS["files"][$i]["filename"] != '.') && ($GLOBALS["files"][$i]["filename"] != '..'))) {
					if ($nScriptNr >= $lStartRec && $nScriptShowed < $GLOBALS["RECORDS_PER_PAGE"]) {
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
							<td align="center" valign="middle" class="content">
								<?php
								if ($GLOBALS["files"][$i]["filename"] != '..') {
									?><a href="<?php echo BuildLink('m_scripts.php'); ?>&subdir=<?php echo $newsubdir; ?>"><?php echo $GLOBALS["iOpenFolder"]; ?></a>&nbsp;<?php
									if ($GLOBALS["candelete"] === False) { echo $GLOBALS["iBlank"];
									} else {
										?><a href="javascript:DelDir('subdir=<?php echo $_GET["subdir"]; ?>&folder=<?php echo $GLOBALS["files"][$i]["filename"]; ?>&page=<?php echo $_GET["page"]; ?>')" <?php echo BuildLinkMouseOver($GLOBALS["tDeleteScriptDir"]); ?>><?php echo $GLOBALS["iDelete"]; ?></a><?php
									}
								}
								?>
							</td>
							<td align="center" valign="middle" class="content">
								&nbsp;
							</td>
							<td valign="top" class="content">
								<a href="<?php echo BuildLink('m_scripts.php'); ?>&subdir=<?php echo $newsubdir; ?>"><?php echo $GLOBALS["files"][$i]["filename"]; ?></a>
							</td>
							<?
							if ($GLOBALS["ShowFilePermissions"] != 'N') {
								?>
								<td align="center" valign="top" class="content">
									<?php echo $GLOBALS["files"][$i]["fileperms"]; ?>
								</td>
								<td align="center" valign="top" class="content">
									<?php echo $GLOBALS["files"][$i]["fileowner"]; ?>
								</td>
								<td align="center" valign="top" class="content">
									<?php echo $GLOBALS["files"][$i]["filegroup"]; ?>
								</td>
								<?php
							}
							?>
							<td align="center" valign="middle" class="content">
								&nbsp;
							</td>
						</tr>
						<?php
						$nScriptShowed++;
					}
					$nScriptNr++;
				}
			}
		}
	}

	frmScriptsHdFt($colcount,$nCurrentPage,$nPages);
	frmFreeSpace($colcount,$GLOBALS["script_home"]);
	?>
	</table>
	</body>
	</html>
	<?php
} // function frmScripts()


function frmScriptsHdFt($colspan,$nCurrentPage,$nPages)
{
	global $_GET;

	$pLink = BuildLink('m_scripts.php');
	$linkmod = '&subdir='.$_GET["subdir"];
	$hlink = '<a href="javascript:UploadScript();" '.BuildLinkMouseOver($GLOBALS["tAddNew"]).'>';
	echo '<form name="PagingForm" action="'.$pLink.'" method="GET">';
	?>
	<tr class="topmenuback">
		<td colspan="<?php echo $colspan; ?>" align="<?php echo $GLOBALS["left"]; ?>">
			<table height="100%" width="100%" cellspacing="0" cellpadding="0">
				<tr><?php
					if (($GLOBALS["canadd"] === True) && ($GLOBALS["file_uploads"])) {
						?><td align="<?php echo $GLOBALS["left"]; ?>" valign="bottom"><?php
						echo displaybutton('addbutton','scripts',$GLOBALS["tAddNew"].'...',$hlink);
						?></td><?php
					}
					?>
					<td align="<?php echo $GLOBALS["right"]; ?>" valign="bottom"><?php
						if ($nCurrentPage != 0) { ?><a href="<?php echo $pLink.$linkmod; ?>&page=0" <?php echo BuildLinkMouseOver($GLOBALS["tFirstPage"]); ?>><?php echo $GLOBALS["iFirst"]; ?></a><?php } else { echo $GLOBALS["iFirst"]; }
						echo '&nbsp;';
						if ($nCurrentPage != 0) { ?><a href="<?php echo $pLink.$linkmod; ?>&page=<?php echo $nCurrentPage - 1; ?>" <?php echo BuildLinkMouseOver($GLOBALS["tPrevPage"]); ?>><?php echo $GLOBALS["iPrev"]; ?></a><?php } else { echo $GLOBALS["iPrev"]; }
						$nCPage = $nCurrentPage + 1;
						echo RenderPageList($nCPage,$nPages,'m_scripts.php',$linkmod);
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
} // function frmScriptsHdFt()


?>
<script language="Javascript" type="text/javascript">
	<!-- Begin
	function DelScript(sParams) {
		if (window.confirm('<?php echo $GLOBALS["tConfirmDeletion"]; ?>')) {
			location.href='<?php echo BuildLink('m_scripts.php'); ?>&mode=delete&' + sParams;
		}
	}

	function DelDir(sParams) {
		if (window.confirm('<?php echo $GLOBALS["tConfirmDeletion"]; ?>')) {
			location.href='<?php echo BuildLink('m_scripts.php'); ?>&mode=deletedir&' + sParams;
		}
	}

	function ShowScript(sScriptName) {
		window.open(sScriptName, "Script", "width=500,height=400,status=no,resizable=yes,scrollbars=yes");
	}

	function UploadScript() {
		window.open("<?php echo BuildLink('uploadscript.php'); ?>&subdir=<?php echo $_GET["subdir"]; ?>", "UploadScript", "width=600,height=310,status=no,resizable=no,scrollbars=no");
	}
	//  End -->
</script>
<?php


function DeleteScript()
{
	global $_GET;

	$path = $GLOBALS["rootdp"].$GLOBALS["script_home"].$_GET["subdir"];
	$ffile = $_GET["Script"];
	$delete = DeleteFile($path,$ffile);

	return $delete;
} // function DeleteScript()


function DeleteScriptDir()
{
	global $_GET;

	$path = $GLOBALS["rootdp"].$GLOBALS["script_home"].$_GET["subdir"];
	$ffile = $_GET["folder"];
	$delete = DeleteDir($path,$ffile);

	return $delete;
} // function DeleteScriptDir()

?>

