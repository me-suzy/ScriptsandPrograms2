<?php

/***************************************************************************

 menu.php
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

includeLanguageFiles('admin','adminmenu');

force_page_refresh();
frmMenu();


function frmMenu()
{
	global $EZ_SESSION_VARS, $_GET;

	adminheader();
	?>
	<body marginwidth="5" marginheight="10" leftmargin="5" rightmargin="5" topmargin="10" class="menuback">
	<table border="0" cellspacing="0" cellpadding="0" width="100%" height="100%">
		<tr><td valign="top">
				<table class="menuback" cellspacing="0" cellpadding="0" width="100%">
					<?php RenderMenu(); ?>
					<tr><td>
							<table border="0" cellspacing="0" cellpadding="4">
								<tr><td>
										<br /><a class="menulink" title="<?php echo str_replace("'","\'",$GLOBALS["tmLogout"]); ?>" href="<?php echo BuildLink($GLOBALS["rootdp"].'logout.php'); ?>&ref=<?php echo $GLOBALS["admin_home"]; ?>control2.php" <?php echo BuildLinkMouseOver($GLOBALS["tmLogout"]); ?> target="mainbody">
										<img src="styles/shutdown.gif" border=0 hspace=5 align=absmiddle><?php echo $GLOBALS["tmLogout"]; ?></a>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
        <?php
        if($EZ_SESSION_VARS["UserGroup"]=="administrator"){
        ?>        
                <tr><td align="<?= $GLOBALS["left"]; ?>" valign="bottom">
                <B>CSS Editor</B><br>
                <A href="../include/eledicss/eledicss.php" target="content">Eledicss</a>
                </td></tr>
		<?php
		}
		
		if (($EZ_SESSION_VARS["WYSIWYG"] == 'D') || ($EZ_SESSION_VARS["WYSIWYG"] == 'Y')) {
			?><tr><td align="<?php echo $GLOBALS["left"]; ?>" valign="bottom"><?php
			echo '<B>WYSIWYG:</B>';
			//echo '</td></tr>';
			?><br>
			<a href="<?php echo BuildLink('toggleWYSIWYG.php'); ?>&activegroup=<?php echo $_GET["activegroup"]?>"><?php
			if ($EZ_SESSION_VARS["WYSIWYG"] == 'Y') {
				echo $GLOBALS["tEnabled"];
			} else { echo $GLOBALS["tDisabled"]; }
			echo '</a></td></tr>';
		}
		if ($GLOBALS["gsMultiSite"] == 'Y') {
			?><tr><td align="<?php echo $GLOBALS["left"]; ?>" valign="bottom"><?php
			echo '<B>'.$GLOBALS["tCurrentSite"].':</B>';
			//echo '</td></tr>';
			?><br><?php
			echo getsitename($EZ_SESSION_VARS["Site"]);
			echo '</td></tr>';
		}
		if ($GLOBALS["gsMultiTheme"] == 'Y') {
			?><tr><td align="<?php echo $GLOBALS["left"]; ?>" valign="bottom"><?php
			echo '<B>'.$GLOBALS["tCurrentTheme"].':</B>';
			//echo '</td></tr>';
			?><br><?php
			echo getthemename($EZ_SESSION_VARS["Theme"]);
			echo '</td></tr>';
		}
		?>
		<tr><td align="center" valign="bottom">
				<a href="<?php echo BuildLink($GLOBALS["rootdp"].'about.php'); ?>" <?php echo BuildLinkMouseOver('Powered by iziContents'); ?> target="content">
				<?php echo imagehtmltag($GLOBALS["icon_home"],'logo_small.gif','Powered by iziContents',0,''); ?></a>
				<table border="0" cellspacing="0" cellpadding="2" width="100%">
					<tr><td align="center"><?php echo $GLOBALS["Version"]; ?></td></tr>
				</table>
			</td>
		</tr>

	</table>
	</body>
	</html>
	<?php
} // function frmMenu()


function RenderMenu()
{
	global $EZ_SESSION_VARS, $_GET, $EzAdmin_Style;

	$cgroupname = '';
	$strQuery = "SELECT g.groupname as groupname,g.controlvar as groupvar,g.controltype as grouptype,g.controlvalue as groupvalue,f.functionname as functionname,f.controlvar as functionvar,f.controltype as functiontype,f.controlvalue as functionvalue FROM ".$GLOBALS["eztbFunctions"]." f, ".$GLOBALS["eztbFunctiongroups"]." g, ".$GLOBALS["eztbPrivileges"]." p WHERE g.groupname=f.groupname AND p.functionname=f.functionname AND p.usergroupname='".$EZ_SESSION_VARS["UserGroup"]."' AND (p.accessview='Y' OR p.accessedit='Y' OR p.accessadd='Y' OR p.accessdelete='Y' OR p.accesstranslate='Y') ORDER BY g.grouporderid,f.functionorderid";
	$result = dbRetrieve($strQuery,true,0,0);
        while ($rs = dbFetch($result)) {
                if ($rs["groupname"] != $cgroupname) {
			if (!isset($_GET["activegroup"])) {
				$_GET["activegroup"] = $rs["groupname"];
			}
			$cgroupname		= $rs["groupname"];
			$groupvar		= $rs["groupvar"];
			$grouptype		= $rs["grouptype"];
			$groupvalue		= $rs["groupvalue"];
			$groupdisplay	= True;
			if (($groupvar != '') && ($grouptype != '')) {
				$groupdisplay = False;
				switch ($grouptype) {
					case '!='	: if ($GLOBALS[$groupvar] != $groupvalue) { $groupdisplay = True; }
								  break;
					case '=='	:
					default		: if ($GLOBALS[$groupvar] == $groupvalue) { $groupdisplay = True; }
								  break;
				}
			}
			if ($groupdisplay) {
				$GLOBALS["ExtraMenuImage"] = '';
				?>
				<tr><td>
						<?php
						$fileref = '';
						if ($GLOBALS["gsAdminStyle"] != '') {
							$fileref = $GLOBALS["rootdp"].$GLOBALS["style_home"].$GLOBALS["gsAdminStyle"].'/images/'.$cgroupname.'_menu.gif';
							if (file_exists($fileref) != true) {
								$fileref = $GLOBALS["rootdp"].$GLOBALS["style_home"].$GLOBALS["gsAdminStyle"].'/images/'.$cgroupname.'_menu.jpg';
								if (file_exists($fileref) != true) {
									$fileref = $GLOBALS["rootdp"].$GLOBALS["style_home"].$GLOBALS["gsAdminStyle"].'/images/'.$cgroupname.'_menu.png';
									if (file_exists($fileref) != true) { $fileref = ''; }
								}
							}
							if ($fileref != '') { $GLOBALS["ExtraMenuImage"] = '<IMG SRC="'.$fileref.'" BORDER="0">'; }
						}
						echo menubutton($rs,$cgroupname);
						?>
					</td>
				</tr>
				<?php
			}
		}
		if ($rs["groupname"] == $_GET["activegroup"]) {
			$fname		= $rs["functionname"];
			$functionvar	= $rs["functionvar"];
			$functiontype	= $rs["functiontype"];
			$functionvalue	= $rs["functionvalue"];
			$functiondisplay = True;
			if (($functionvar != '') && ($functiontype != '')) {
				$functiondisplay = False;
				switch ($functiontype) {
					case '!='	: if ($GLOBALS[$functionvar] != $functionvalue) { $functiondisplay = True; }
								  break;
					case '=='	:
					default		: if ($GLOBALS[$functionvar] == $functionvalue) { $functiondisplay = True; }
								  break;
				}
			}
			if ($groupdisplay && $functiondisplay) {
				?>
				<tr><td class="navi_bg"><table border="0" width="100%" cellspacing="0" cellpadding="3">
							<tr><?php
								if (($EzAdmin_Style["adminsubmenuoffset"] != '') && ($EzAdmin_Style["adminsubmenuoffset"] > 0)) {
									?><td width="10"><img src="<?php echo $GLOBALS["rootdp"].$GLOBALS["icon_home"]; ?>blank.gif" height="1" width="<?php echo $EzAdmin_Style["adminsubmenuoffset"]; ?>">
									</td>
									<?
								}
								?>
								<td>
									<a class="menulink" title="<?php echo $GLOBALS["tf".$fname]; ?>" href="<?php echo BuildLink('m_'.$fname.'.php'); ?>" <?php echo BuildLinkMouseOver($GLOBALS["tf".$fname]); ?> target="content">
									<?php echo $GLOBALS["tf".$fname]; ?></a><br />
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<?php
			}
		}
	}
	dbFreeResult($result);
} // function RenderMenu()


function getsitename($Site)
{
	if ($Site != '') {
		$strQuery="SELECT sitename FROM ".$GLOBALS["eztbSites"]." WHERE sitecode='".$Site."'";
		$sresult = dbRetrieve($strQuery,true,0,0);
		$rs = dbFetch($sresult);
		$sitename = $rs["sitename"];
		dbFreeResult($sresult);
	} else { $sitename = $GLOBALS["tMasterSite"]; }
	return $sitename;
} // function getsitename()


function getthemename($Theme)
{
	if ($Theme != '') {
		$strQuery="SELECT themename FROM ".$GLOBALS["eztbThemes"]." WHERE themecode='".$Theme."'";
		$tresult = dbRetrieve($strQuery,true,0,0);
		$rs = dbFetch($tresult);
		$themename = $rs["themename"];
		dbFreeResult($tresult);
	} else { $themename = $GLOBALS["tDefaultTheme"]; }
	return $themename;
} // function getthemename()

?>
