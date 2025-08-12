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

$GLOBALS["rootdp"] = './';
require_once ($GLOBALS["rootdp"]."include/config.php");
require_once ($GLOBALS["rootdp"]."include/db.php");
require_once ($GLOBALS["rootdp"]."include/session.php");

if (($GLOBALS["gsUseFrames"] == '') && ($EZ_SESSION_VARS["noframesbrowser"] != True)) {
	include_once ($GLOBALS["rootdp"]."include/settings.php");
	include_once ($GLOBALS["rootdp"]."include/functions.php");
	includeLanguageFiles('main');
	Start_Timer();
	Start_Gzip();
	force_page_refresh();
}


if ($_GET["groupname"] != '') { $GLOBALS["activemenu"] = $_GET["groupname"];
} else { $GLOBALS["activemenu"] = $_GET["groupname"] = $GLOBALS["gsHomepageGroup"]; }

if ($_GET["subgroupname"] != '') { $GLOBALS["activesubmenu"] = $_GET["subgroupname"];
} else { $GLOBALS["activesubmenu"] = ''; }

if ($GLOBALS["gsShowTopMenu"] == 'Y') {
	if ((!isset($_GET["topgroupname"])) || ($_GET["topgroupname"] == '')) {
		$_GET["topgroupname"] = GetTopGroupName($_GET["groupname"]);
	}
}

$GLOBALS["bMenuExists"] = ReadMenuStructure($_GET["topgroupname"]);
frmMenu();



function jsMenuImage($prefix,$group,$image,$postfix)
{
	echo $prefix.$group.$postfix." = new Image(0,0) ;\n";
	echo $prefix.$group.$postfix.".src = './".$GLOBALS["image_home"].$image."' ;\n";
} // jsMenuImage()


function frmMenu()
{
	global $EZ_SESSION_VARS, $_GET;

	if (($GLOBALS["gsUseFrames"] == 'Y') && ($EZ_SESSION_VARS["noframesbrowser"] != True)) {
		HTMLHeader('menu');
		StyleSheet();
	}
	?>
	<script language="JavaScript" type="text/javascript">
		<!-- Begin
		var currentMenu = '<?php echo $_GET["groupname"]; ?>' ;
		var currentSubmenu = '<?php echo $_GET["subgroupname"]; ?>' ;


		function inMenuArray( testArray, testElement ) {
			var r = 0 ;
			for ( var i = 0; i < testArray.length; i++ ) {
				if ( testArray[i] == testElement ) {
					r = 1 ;
				}
			}
			return r ;
		}

		function changeMenuImageOver( imgDocID, imgObjName ) {
			if ( menuImageObj ) {
				var iTest = inMenuArray( menuImageArray, imgObjName );
				if ( iTest ) {
					if ( imgObjName == currentMenu ) {
						document.images[imgDocID].src = eval( 'gm' + imgObjName + 'c.src' ) ;
					} else {
						document.images[imgDocID].src = eval( 'gm' + imgObjName + 'a.src' ) ;
					}
				}
			}
		}

		function changeSubmenuImageOver( imgDocID, imgObjName ) {
			if ( menuImageObj ) {
				var iTest = inMenuArray( submenuImageArray, imgObjName );
				if ( iTest ) {
					if ( imgObjName == currentSubmenu ) {
						document.images[imgDocID].src = eval( 'gs' + imgObjName + 'c.src' ) ;
					} else {
						document.images[imgDocID].src = eval( 'gs' + imgObjName + 'a.src' ) ;
					}
				}
			}
		}

		function changeMenuImageOut( imgDocID, imgObjName ) {
			if ( menuImageObj ) {
				var iTest = inMenuArray( menuImageArray, imgObjName );
				if ( iTest ) {
					if ( imgObjName == currentMenu ) {
						document.images[imgDocID].src = eval( 'gm' + imgObjName + 'b.src' ) ;
					} else {
						document.images[imgDocID].src = eval( 'gm' + imgObjName + '.src' ) ;
					}
				}
			}
		}

		function changeSubmenuImageOut( imgDocID, imgObjName ) {
			if ( menuImageObj ) {
				var iTest = inMenuArray( submenuImageArray, imgObjName );
				if ( iTest ) {
					if ( imgObjName == currentSubmenu ) {
						document.images[imgDocID].src = eval( 'gs' + imgObjName + 'b.src' ) ;
					} else {
						document.images[imgDocID].src = eval( 'gs' + imgObjName + '.src' ) ;
					}
				}
			}
		}

		function changeSubmenu( imgDocID, imgObjName ) {
			if ( menuImageObj ) {
				var iTest = inMenuArray( submenuImageArray, imgObjName );
				if ( iTest ) {
					if ( imgObjName != currentSubmenu ) {
						document.images[imgDocID].src = eval( 'gs' + imgObjName + 'c.src' ) ;
					}
				} else {
					var iTest = inMenuArray( submenuImageArray, currentSubmenu );
					if ( iTest ) {
						var iref = eval('submenu' + currentSubmenu) ;
						iref.src = eval( 'gs' + currentSubmenu + '.src' ) ;
					}
				}
				currentSubmenu = imgObjName ;
			}
		}

		var menuImageObj = 0;
		if (document.images)
		{
			menuImageObj = 1;
			var menuImageArray = new Array ;
			<?php
			if ($GLOBALS["bMenuExists"] > 0) {
				$j = 0;
				reset($GLOBALS["groups"]);
				while (list($i,$val) = each($GLOBALS["groups"])) {
					if ($GLOBALS["groups"][$i]["menuimage1"] != "") {
						echo "menuImageArray[".$j."] = '".$GLOBALS["groups"][$i]["groupname"]."' ;\n";
						jsMenuImage('gm',$GLOBALS["groups"][$i]["groupname"],$GLOBALS["groups"][$i]["menuimage1"],'');
						if ($GLOBALS["groups"][$i]["menuimage2"] != "") {
							jsMenuImage('gm',$GLOBALS["groups"][$i]["groupname"],$GLOBALS["groups"][$i]["menuimage2"],'a');
						} else {
							jsMenuImage('gm',$GLOBALS["groups"][$i]["groupname"],$GLOBALS["groups"][$i]["menuimage1"],'a');
						}
						if ($GLOBALS["groups"][$i]["menuimage3"] != "") {
							jsMenuImage('gm',$GLOBALS["groups"][$i]["groupname"],$GLOBALS["groups"][$i]["menuimage3"],'b');
							if ($GLOBALS["groups"][$i]["menuimage4"] != "") {
								jsMenuImage('gm',$GLOBALS["groups"][$i]["groupname"],$GLOBALS["groups"][$i]["menuimage4"],'c');
							} else {
								jsMenuImage('gm',$GLOBALS["groups"][$i]["groupname"],$GLOBALS["groups"][$i]["menuimage3"],'c');
							}
						} else {
							jsMenuImage('gm',$GLOBALS["groups"][$i]["groupname"],$GLOBALS["groups"][$i]["menuimage1"],'b');
							if ($GLOBALS["groups"][$i]["menuimage2"] != "") {
								jsMenuImage('gm',$GLOBALS["groups"][$i]["groupname"],$GLOBALS["groups"][$i]["menuimage2"],'c');
							} else {
								jsMenuImage('gm',$GLOBALS["groups"][$i]["groupname"],$GLOBALS["groups"][$i]["menuimage1"],'c');
							}
						}
						$j++;
					}
				}
			}
			?>
		}
		//  End -->
	</script>
	<?php
	if (($GLOBALS["gsUseFrames"] == 'Y') && ($EZ_SESSION_VARS["noframesbrowser"] != True)) {
		?>
		</head>
		<body marginwidth="0" marginheight="0" leftmargin="5" rightmargin="5" topmargin="10" class="menuback">
		<?php
	} else {
		?>
		<table border="0" cellspacing="0" cellpadding="0" width="100%" height="100%">
		<tr><td valign="top" align="<?php echo $GLOBALS["left"]; ?>" class="menuback" width="100%">
		<?php
	}
	?>

	<table border="0" cellspacing="1" cellpadding="0" width="100%" height="100%">
	<tr><td height="<?php echo $GLOBALS["gsMenuDistance1"]; ?>"></td></tr>
	<tr><td>
	<table border="<?php echo $GLOBALS["gsMenuBorder"]; ?>" cellspacing="0" cellpadding="4" width="100%" height="100%">
	<?php

	if ($GLOBALS["bMenuExists"] > 0) { RenderGroups(); }

	?>
	</table>
	</td></tr>
	<?php

	if ($GLOBALS["gsUserdataFrame"] == 'M') { include($GLOBALS["rootdp"].'menuuserdata.php'); }

	?>
	<tr><td height="100%" align="center" valign="bottom">
	<?php
	if ($GLOBALS["PoweredByEZC"] == 'M') {
		if (($GLOBALS["gsUseFrames"] == 'Y') && ($EZ_SESSION_VARS["noframesbrowser"] != True)) {
			?><a href="<?php echo BuildLink('about.php'); ?>" target="contents" <?php echo BuildLinkMouseOver('Powered by iziContents'); ?>><?php
		} else {
			?><a href="<?php echo BuildLink('about.php'); ?>" target="_blank" <?php echo BuildLinkMouseOver('Powered by iziContents'); ?>><?php
		}
		echo imagehtmltag($GLOBALS["icon_home"],'logo_small.gif','Powered by iziContents',0,''); ?></a>
		<br /><?php echo $GLOBALS["Version"];
	}
	?>
	</td></tr>
	</table>
	<?php
	if (($GLOBALS["gsUseFrames"] == 'Y') && ($EZ_SESSION_VARS["noframesbrowser"] != True)) {
		End_Timer();
		?>
		</body>
		</html>
		<?php
		End_Gzip();
	} else {
		?>
		</td></tr></table>
		<?php
	}
} // function frmMenu()


function RenderGroups()
{
	global $_GET, $EZ_SESSION_VARS;

	reset($GLOBALS["groups"]);
	while (list($i,$val) = each($GLOBALS["groups"]))	{
		if ($GLOBALS["gsShowTopMenu"] != 'Y') { $GLOBALS["groups"][$i]["topgroupname"] = ''; }
			if (($GLOBALS["gsPrivateMenus"] == 'L') || ($GLOBALS["groups"][$i]["loginreq"] != 'Y') || ($EZ_SESSION_VARS["PasswordCookie"] != '')) {
				?>
				<tr class="menu">
				<td>
				<?php
				$GLOBALS["groups"][$i]["grouplink"] = privatemenu($GLOBALS["groups"][$i]["loginreq"],$GLOBALS["groups"][$i]["usergroups"],$GLOBALS["groups"][$i]["grouplink"]);
				?>
				<a class="menulink" <?php
				if ($GLOBALS["gsMenuHover"] == 'Y') {
					if ($GLOBALS["groups"][$i]["hovertitle"] != '') {
						$hovertext= $GLOBALS["groups"][$i]["hovertitle"];
					} else {
						$hovertext= $GLOBALS["groups"][$i]["groupdesc"];
					}
					?> title="<?php echo $hovertext ?>" <?php
				}
				?> href="<?php
				if ($GLOBALS["groups"][$i]["grouplink"] == '') {
					if (($GLOBALS["gsUseFrames"] == 'Y') && ($EZ_SESSION_VARS["noframesbrowser"] != True)) {
						echo BuildLink('showcontents.php'); ?>&topgroupname=<?php echo $GLOBALS["groups"][$i]["topgroupname"]; ?>&groupname=<?php echo $GLOBALS["groups"][$i]["groupname"]; ?>" target="contents"<?php
					} else {
						if ($GLOBALS["gsExpandMenus"] == "Y") {
							if (($GLOBALS["gsExpandMenus"] == "Y") && ($GLOBALS["groups"][$i]["groupname"] == $GLOBALS["activemenu"]) && ($_GET["action"] != 'collapse')) {
								echo BuildLink('control.php'); ?>&topgroupname=<?php echo $GLOBALS["groups"][$i]["topgroupname"]; ?>&groupname=<?php echo $GLOBALS["groups"][$i]["groupname"]; ?>&action=collapse"<?php
							} else {
								echo BuildLink('control.php'); ?>&topgroupname=<?php echo $GLOBALS["groups"][$i]["topgroupname"]; ?>&groupname=<?php echo $GLOBALS["groups"][$i]["groupname"]; ?>"<?php
							}
						} else {
							echo BuildLink('control.php'); ?>&topgroupname=<?php echo $GLOBALS["groups"][$i]["topgroupname"]; ?>&groupname=<?php echo $GLOBALS["groups"][$i]["groupname"]; ?>"<?php
						}
					}
				} else {
					if (($GLOBALS["gsUseFrames"] == 'Y') && ($EZ_SESSION_VARS["noframesbrowser"] != True)) {
						if (($GLOBALS["groups"][$i]["openinpage"] == 'Y') || ($GLOBALS["groups"][$i]["grouplink"] == 'loginreq.php') || ($GLOBALS["groups"][$i]["grouplink"] == 'loginreq2.php')) {
							if ((substr($GLOBALS["groups"][$i]["grouplink"],0,7) == 'http://') || (substr($GLOBALS["groups"][$i]["grouplink"],0,8) == 'https://')) {
								echo $GLOBALS["groups"][$i]["grouplink"]; ?>" target="contents"<?php
							} else {
								echo BuildLink('module.php'); ?>&link=<?php echo $GLOBALS["groups"][$i]["grouplink"]; ?>&topgroupname=<?php echo $GLOBALS["groups"][$i]["topgroupname"]; ?>&groupname=<?php echo $GLOBALS["groups"][$i]["groupname"]; ?>" target="contents"<?php
							}
						} else {
							echo $GLOBALS["groups"][$i]["grouplink"]; ?>" target="_blank"<?php
						}
					} else {
					if (($GLOBALS["groups"][$i]["openinpage"] == 'Y') || ($GLOBALS["groups"][$i]["grouplink"] == 'loginreq.php') || ($GLOBALS["groups"][$i]["grouplink"] == 'loginreq2.php')) {
						if ((substr($GLOBALS["groups"][$i]["grouplink"],0,7) == 'http://') || (substr($GLOBALS["groups"][$i]["grouplink"],0,8) == 'https://')) {
							echo $GLOBALS["groups"][$i]["grouplink"]; ?>"<?php
						} else {
							echo BuildLink('control.php'); ?>&link=<?php echo $GLOBALS["groups"][$i]["grouplink"]; ?>&topgroupname=<?php echo $GLOBALS["groups"][$i]["topgroupname"]; ?>&groupname=<?php echo $GLOBALS["groups"][$i]["groupname"]; ?>"<?php
						}
					} else {
						echo $GLOBALS["groups"][$i]["grouplink"]; ?>" target="_blank"<?php
					}
				}
			}

			if (($GLOBALS["gsUseFrames"] == 'Y') && ($EZ_SESSION_VARS["noframesbrowser"] != True)) {
				if ($GLOBALS["gsExpandMenus"] == "Y") {
					if (($GLOBALS["gsExpandMenus"] == "Y") && ($GLOBALS["groups"][$i]["groupname"] == $GLOBALS["activemenu"]) && ($_GET["action"] != 'collapse')) {
						?> onClick="javascript:window.location.href='<?php echo BuildLink('menu.php'); ?>&topgroupname=<?php echo $GLOBALS["groups"][$i]["topgroupname"]; ?>&groupname=<?php echo $GLOBALS["groups"][$i]["groupname"]; ?>&action=collapse';" <?php
					} else {
						?> onClick="javascript:window.location.href='<?php echo BuildLink('menu.php'); ?>&topgroupname=<?php echo $GLOBALS["groups"][$i]["topgroupname"]; ?>&groupname=<?php echo $GLOBALS["groups"][$i]["groupname"]; ?>';" <?php
					}
				}
			}

			if ($GLOBALS["groups"][$i]["menuimage1"] == '') {
				echo BuildLinkMouseOver($GLOBALS["groups"][$i]["groupdesc"]).'>';
			} else {
				?> onMouseOver="changeMenuImageOver('menu<?php echo $GLOBALS["groups"][$i]["groupname"]; ?>','<?php echo $GLOBALS["groups"][$i]["groupname"]; ?>'); window.status='<?php echo $GLOBALS["groups"][$i]["groupdesc"]; ?>'; return true;"<?php
				?> onMouseOut="changeMenuImageOut('menu<?php echo $GLOBALS["groups"][$i]["groupname"]; ?>','<?php echo $GLOBALS["groups"][$i]["groupname"]; ?>'); window.status=''; return true;"><?php
			}

			if ($GLOBALS["gsExpandMenus"] == "Y") {
				if ($GLOBALS["groups"][$i]["subgroupcount"] > 0) {
					if (($GLOBALS["groups"][$i]["groupname"] == $GLOBALS["activemenu"]) && ($_GET["action"] != 'collapse')) {
						if ($GLOBALS["gsCollapseIcon"] != '') {
							echo imagehtmltag($GLOBALS["image_home"],$GLOBALS["gsCollapseIcon"],$GLOBALS["tCollapse"],0,''); ?>&nbsp;<?php
						}
					} else {
						if ($GLOBALS["gsExpandIcon"] != '') {
							echo imagehtmltag($GLOBALS["image_home"],$GLOBALS["gsExpandIcon"],$GLOBALS["tExpand"],0,''); ?>&nbsp;<?php
						}
					}
				} else {
					if ($GLOBALS["gsNoExpandIcon"] != '') {
						echo imagehtmltag($GLOBALS["image_home"],$GLOBALS["gsNoExpandIcon"],$GLOBALS["tNoExpand"],0,''); ?>&nbsp;<?php
					}
				}
			}

			if ($GLOBALS["groups"][$i]["menuimage1"] == '') {
				echo $GLOBALS["groups"][$i]["groupdesc"]; ?></a><?php
			} else {
				if (($GLOBALS["groups"][$i]["menuimage3"] != '') && ($GLOBALS["groups"][$i]["groupname"] == $GLOBALS["activemenu"])) {
				?><img src="./<?php echo $GLOBALS["image_home"].$GLOBALS["groups"][$i]["menuimage3"]; ?>" border="0" name="menu<?php echo $GLOBALS["groups"][$i]["groupname"]?>"></a><?php
			} else {
				?><img src="./<?php echo $GLOBALS["image_home"].$GLOBALS["groups"][$i]["menuimage1"]; ?>" border="0" name="menu<?php echo $GLOBALS["groups"][$i]["groupname"]?>"></a><?php
			}
			}

			if ($GLOBALS["groups"][$i]["loginreq"] == 'Y') {
				?>&nbsp;&nbsp;<?php echo imagehtmltag($GLOBALS["image_home"],$GLOBALS["gsSecureIcon"],$GLOBALS["tPrivateOption"],0,''); ?>&nbsp;<?php
			}

			if (($GLOBALS["groups"][$i]["grouplink"] == '') && ($GLOBALS["groups"][$i]["subgroupcount"] > 0)) {
				if (($GLOBALS["gsPrivateMenus"] == 'L') || ($GLOBALS["groups"][$i]["loginreq"] != 'Y') || ($EZ_SESSION_VARS["PasswordCookie"] != '')) {
					if ($GLOBALS["gsExpandMenus"] == "Y") {
						if (($GLOBALS["activemenu"] == $GLOBALS["groups"][$i]["groupname"]) && ($_GET["action"] != 'collapse')) {
							if (ReadSubmenuStructure($GLOBALS["groups"][$i]["groupname"],$GLOBALS["groups"][$i]["loginreq"],$GLOBALS["groups"][$i]["usergroups"]) > 0) {
								RenderJavaSubGroups($GLOBALS["groups"][$i]["groupname"]);
								RenderSubGroups($GLOBALS["groups"][$i]["topgroupname"],$GLOBALS["groups"][$i]["groupname"]);
							}
						}
					} else {
						if (ReadSubmenuStructure($GLOBALS["groups"][$i]["groupname"],$GLOBALS["groups"][$i]["loginreq"],$GLOBALS["groups"][$i]["usergroups"]) > 0) {
							RenderJavaSubGroups($GLOBALS["groups"][$i]["groupname"]);
							RenderSubGroups($GLOBALS["groups"][$i]["topgroupname"],$GLOBALS["groups"][$i]["groupname"]);
						}
					}
				}
			}
			?>
			</td>
			</tr>
			<?php
		}
	}
} // function RenderGroups()


function RenderJavaSubGroups($GroupName) {
	?>
	<script language="JavaScript" type="text/javascript">
		<!-- Begin
		var submenuImageArray = new Array ;
	<?php

	$k = 0;
	reset($GLOBALS["subgroups"]);
	while (list($si,$val) = each($GLOBALS["subgroups"])) {
		if ($GLOBALS["subgroups"][$si]["submenuimage1"] != "") {
			echo "submenuImageArray[".$k."] = '".$GLOBALS["subgroups"][$si]["subgroupname"]."' ;\n";
			jsMenuImage('gs',$GLOBALS["subgroups"][$si]["subgroupname"],$GLOBALS["subgroups"][$si]["submenuimage1"],'');
			if ($GLOBALS["subgroups"][$si]["submenuimage2"] != "") {
				jsMenuImage('gs',$GLOBALS["subgroups"][$si]["subgroupname"],$GLOBALS["subgroups"][$si]["submenuimage2"],'a');
			} else {
				jsMenuImage('gs',$GLOBALS["subgroups"][$si]["subgroupname"],$GLOBALS["subgroups"][$si]["submenuimage1"],'a');
			}
			if ($GLOBALS["subgroups"][$si]["submenuimage3"] != "") {
				jsMenuImage('gs',$GLOBALS["subgroups"][$si]["subgroupname"],$GLOBALS["subgroups"][$si]["submenuimage3"],'b');
				if ($GLOBALS["subgroups"][$si]["submenuimage4"] != "") {
					jsMenuImage('gs',$GLOBALS["subgroups"][$si]["subgroupname"],$GLOBALS["subgroups"][$si]["submenuimage4"],'c');
				} else {
					jsMenuImage('gs',$GLOBALS["subgroups"][$si]["subgroupname"],$GLOBALS["subgroups"][$si]["submenuimage3"],'c');
				}
			} else {
				jsMenuImage('gs',$GLOBALS["subgroups"][$si]["subgroupname"],$GLOBALS["subgroups"][$si]["submenuimage1"],'b');
				if ($GLOBALS["subgroups"][$si]["submenuimage2"] != "") {
					jsMenuImage('gs',$GLOBALS["subgroups"][$si]["subgroupname"],$GLOBALS["subgroups"][$si]["submenuimage2"],'c');
				} else {
					jsMenuImage('gs',$GLOBALS["subgroups"][$si]["subgroupname"],$GLOBALS["subgroups"][$si]["submenuimage1"],'c');
				}
			}
			$k++;
		}
	}
	?>
	//  End -->
	</script>
	<?php
} // function RenderJavaSubGroups()


function RenderSubGroups($topgroupname,$GroupName)
{
	global $EZ_SESSION_VARS;

	?>
	<table border="0" cellpadding="0" cellspacing="0">
	<tr><td height="<?php echo $GLOBALS["gsMenuDistance2"]; ?>"></td><td></td></tr>
	<tr><td width="<?php echo $GLOBALS["gsMenuDistance4"]; ?>"></td><td>
	<?php

	reset($GLOBALS["subgroups"]);
	while (list($si,$val) = each($GLOBALS["subgroups"])) {
		if (($GLOBALS["gsPrivateMenus"] == 'L') || ($GLOBALS["subgroups"][$si]["loginreq"] != 'Y') || ($EZ_SESSION_VARS["PasswordCookie"] != '')) {
			$GLOBALS["subgroups"][$si]["subgrouplink"] = privatemenu($GLOBALS["subgroups"][$si]["loginreq"],$GLOBALS["subgroups"][$si]["usergroups"],$GLOBALS["subgroups"][$si]["subgrouplink"]);
			?>
			<a class="menulink" <?php
			if ($GLOBALS["gsMenuHover"] == 'Y') {
				if ($GLOBALS["subgroups"][$si]["hovertitle"] != '') {
					$hovertext= $GLOBALS["subgroups"][$si]["hovertitle"];
				} else {
					$hovertext= $GLOBALS["subgroups"][$si]["subgroupdesc"];
				}
				?> title="<?php echo $hovertext ?>" <?php
			}
			?> href="<?php
			if ($GLOBALS["subgroups"][$si]["subgrouplink"] == '') {
				if (($GLOBALS["gsUseFrames"] == 'Y') && ($EZ_SESSION_VARS["noframesbrowser"] != True)) {
					echo BuildLink('showcontents.php'); ?>&topgroupname=<?php echo $topgroupname; ?>&groupname=<?php echo $GroupName; ?>&subgroupname=<?php echo $GLOBALS["subgroups"][$si]["subgroupname"]; ?>" target="contents"<?php
				} else {
					echo BuildLink('control.php'); ?>&topgroupname=<?php echo $topgroupname; ?>&groupname=<?php echo $GroupName; ?>&subgroupname=<?php echo $GLOBALS["subgroups"][$si]["subgroupname"]; ?>"<?php
				}
			} else {
				if (($GLOBALS["gsUseFrames"] == 'Y') && ($EZ_SESSION_VARS["noframesbrowser"] != True)) {
					if (($GLOBALS["subgroups"][$si]["openinpage"] == 'Y') || ($GLOBALS["subgroups"][$si]["subgrouplink"] == 'loginreq.php') || ($GLOBALS["subgroups"][$si]["subgrouplink"] == 'loginreq2.php')) {
						if ((substr($GLOBALS["subgroups"][$si]["subgrouplink"],0,7) == 'http://') || (substr($GLOBALS["subgroups"][$si]["subgrouplink"],0,8) == 'https://')) {
							echo $GLOBALS["subgroups"][$si]["subgrouplink"]; ?>" target="contents"<?php
						} else {
							echo BuildLink('module.php'); ?>&link=<?php echo $GLOBALS["subgroups"][$si]["subgrouplink"]; ?>&topgroupname=<?php echo $topgroupname; ?>&groupname=<?php echo $GroupName; ?>&subgroupname=<?php echo $GLOBALS["subgroups"][$si]["subgroupname"]; ?>" target="contents"<?php
						}
					} else {
						echo $GLOBALS["subgroups"][$si]["subgrouplink"]; ?>" target="_blank"<?php
					}
				} else {
					if (($GLOBALS["subgroups"][$si]["openinpage"] == 'Y') || ($GLOBALS["subgroups"][$si]["subgrouplink"] == 'loginreq.php') || ($GLOBALS["subgroups"][$si]["subgrouplink"] == 'loginreq2.php')) {
						if ((substr($GLOBALS["subgroups"][$si]["subgrouplink"],0,7) == 'http://') || (substr($GLOBALS["subgroups"][$si]["subgrouplink"],0,8) == 'https://')) {
							echo $GLOBALS["subgroups"][$si]["subgrouplink"]; ?>"<?php
						} else {
							echo BuildLink('control.php'); ?>&link=<?php echo $GLOBALS["subgroups"][$si]["subgrouplink"]; ?>&topgroupname=<?php echo $topgroupname; ?>&groupname=<?php echo $GroupName; ?>&subgroupname=<?php echo $GLOBALS["subgroups"][$si]["subgroupname"]; ?>"<?php
						}
					} else {
						echo $GLOBALS["subgroups"][$si]["subgrouplink"]; ?>" target="_blank"<?php
					}
				}
			}

			if (($GLOBALS["gsUseFrames"] == 'Y') && ($EZ_SESSION_VARS["noframesbrowser"] != True)) {
				?> onClick="changeSubmenu('<?php echo "submenu".$GLOBALS["subgroups"][$si]["subgroupname"]; ?>','<?php echo $GLOBALS["subgroups"][$si]["subgroupname"]; ?>'); return true;"<?php
			}
			if ($GLOBALS["subgroups"][$si]["submenuimage1"] == '') {
				echo BuildLinkMouseOver($GLOBALS["subgroups"][$si]["subgroupdesc"]).'>';
				echo $GLOBALS["subgroups"][$si]["subgroupdesc"]; ?></a><?php
			} else {
				?> onMouseOver="changeSubmenuImageOver('<?php echo "submenu".$GLOBALS["subgroups"][$si]["subgroupname"]; ?>','<?php echo $GLOBALS["subgroups"][$si]["subgroupname"]; ?>'); window.status=''; return true;"<?php
				?> onMouseOut="changeSubmenuImageOut('<?php echo "submenu".$GLOBALS["subgroups"][$si]["subgroupname"]; ?>','<?php echo $GLOBALS["subgroups"][$si]["subgroupname"]; ?>'); window.status=''; return true;"><?php
				if (($GLOBALS["subgroups"][$si]["submenuimage3"] != '') && ($GLOBALS["subgroups"][$si]["subgroupname"] == $GLOBALS["activesubmenu"])) {
					?><img src="./<?php echo $GLOBALS["image_home"].$GLOBALS["subgroups"][$si]["submenuimage3"]; ?>" border="0" name="submenu<?php echo $GLOBALS["subgroups"][$si]["subgroupname"]?>"></a><?php
				} else {
					?><img src="./<?php echo $GLOBALS["image_home"].$GLOBALS["subgroups"][$si]["submenuimage1"]; ?>" border="0" name="submenu<?php echo $GLOBALS["subgroups"][$si]["subgroupname"]?>"></a><?php
				}
			}
			if ($GLOBALS["subgroups"][$si]["loginreq"] == 'Y') {
				?>&nbsp;&nbsp;<?php echo imagehtmltag($GLOBALS["image_home"],$GLOBALS["gsSecureIcon"],$GLOBALS["tPrivateOption"],0,''); ?>&nbsp;<?php
			}
			?>
			<table border="0" cellspacing="0" cellpadding="0">
			<tr><td height="<?php echo $GLOBALS["gsMenuDistance3"]; ?>"></td></tr></table><?php
		}
	}
	?>
	</td></tr></table>
	<?php
} // function RenderSubGroups()


function GetTopGroupName($groupname)
{
	$topgroupname = '';
	if ((isset($groupname)) && ($groupname != '')) {
		$strQuery = "SELECT * FROM ".$GLOBALS["eztbGroups"]." WHERE menuvisible='Y' AND groupname='".$groupname."' AND language='".$GLOBALS["gsDefault_language"]."'";
		$result = dbRetrieve($strQuery,true,0,1);
		if ($rs = dbFetch($result)) { $topgroupname = $rs["topgroupname"]; }
		dbFreeResult($result);
	}
	return $topgroupname;
} // function GetTopGroupName()


function ReadMenuStructure($topgroupname)
{
	$i = 0;
	if (topmenusecuritycheck($topgroupname)) {
		// We always list all menu items in the default site language; but if the user language is different we
		//		include any menu items in that language as well, sorted so that the user language items will be
		//		processed first.... then we filter out the default site language items when we transfer the
		//		retrieved list to the $GLOBALS array to avoid duplication.
		if ($GLOBALS["gsLanguage"] == $GLOBALS["gsDefault_language"]) {
			if ($GLOBALS["gsShowTopMenu"] == 'Y') {
				if (isset($topgroupname) && $topgroupname != '') {
					$strQuery = "SELECT * FROM ".$GLOBALS["eztbGroups"]." WHERE menuvisible='Y' AND (topgroupname='".$topgroupname."' OR topgroupname='999999999') AND language='".$GLOBALS["gsLanguage"]."' ORDER BY grouporderid";
				} else {
					$strQuery = "SELECT * FROM ".$GLOBALS["eztbGroups"]." WHERE menuvisible='Y' AND language='".$GLOBALS["gsLanguage"]."' ORDER BY grouporderid";
				}
			} else {
				$strQuery = "SELECT * FROM ".$GLOBALS["eztbGroups"]." WHERE menuvisible='Y' AND language='".$GLOBALS["gsLanguage"]."' ORDER BY grouporderid";
			}
		} else {
			$lOrder = '';
			if ($GLOBALS["gsLanguage"] > $GLOBALS["gsDefault_language"]) { $lOrder = ' DESC'; }
			if ($GLOBALS["gsShowTopMenu"] == 'Y') {
				if (isset($topgroupname) && $topgroupname != '') {
					$strQuery = "SELECT * FROM ".$GLOBALS["eztbGroups"]." WHERE menuvisible='Y' AND (topgroupname='".$topgroupname."' OR topgroupname='999999999') AND (language='".$GLOBALS["gsLanguage"]."' OR language='".$GLOBALS["gsDefault_language"]."') ORDER BY grouporderid,language".$lOrder;
				} else {
					$strQuery = "SELECT * FROM ".$GLOBALS["eztbGroups"]." WHERE menuvisible='Y' AND (language='".$GLOBALS["gsLanguage"]."' OR language='".$GLOBALS["gsDefault_language"]."') ORDER BY grouporderid,language".$lOrder;
				}
			} else {
				$strQuery = "SELECT * FROM ".$GLOBALS["eztbGroups"]." WHERE menuvisible='Y' AND (language='".$GLOBALS["gsLanguage"]."' OR language='".$GLOBALS["gsDefault_language"]."') ORDER BY grouporderid,language".$lOrder;
			}
		}
		$result = dbRetrieve($strQuery,true,0,0);

		$activefound = false;
		$nGroupName = '';
		while ($rs = dbFetch($result)) {
			//  Suppress duplicates (ensuring we only get the appropriate language versions)
			if ($rs["groupname"] != $nGroupName) {
				$nGroupName = $rs["groupname"];
				//  Suppress hidden entries if the user doesn't have privilege to see them.
				//  Locked entries are filtered by the RenderGroups() function
				if (!hiddenmenu($rs["loginreq"],$rs["usergroups"])) {
					$GLOBALS["groups"][$i]["groupname"] = $rs["groupname"];
					if ($rs["topgroupname"] == '999999999') {
						$GLOBALS["groups"][$i]["topgroupname"] = $topgroupname;
					} else {
						$GLOBALS["groups"][$i]["topgroupname"] = $rs["topgroupname"];
					}
					$GLOBALS["groups"][$i]["groupdesc"]		= $rs["groupdesc"];
					$GLOBALS["groups"][$i]["grouplink"]		= str_replace('../', '', $rs["grouplink"]);
					$GLOBALS["groups"][$i]["grouporderid"]	= $rs["grouporderid"];
					$GLOBALS["groups"][$i]["menuimage1"]	= $rs["menuimage1"];
					$GLOBALS["groups"][$i]["menuimage2"]	= $rs["menuimage2"];
					$GLOBALS["groups"][$i]["menuimage3"]	= $rs["menuimage3"];
					$GLOBALS["groups"][$i]["menuimage4"]	= $rs["menuimage4"];
					$GLOBALS["groups"][$i]["menuvisible"]	= $rs["menuvisible"];
					$GLOBALS["groups"][$i]["menuorderby"]	= $rs["menuorderby"];
					$GLOBALS["groups"][$i]["menuorderdir"]	= $rs["menuorderdir"];
					$GLOBALS["groups"][$i]["hovertitle"]	= $rs["hovertitle"];
					$GLOBALS["groups"][$i]["openinpage"]	= $rs["openinpage"];
					$GLOBALS["groups"][$i]["loginreq"]		= $rs["loginreq"];
					$GLOBALS["groups"][$i]["usergroups"]	= $rs["usergroups"];
					$GLOBALS["groups"][$i]["subgroupcount"]	= $rs["subgroupcount"];
					$i++;

					if ($rs["groupname"] == $GLOBALS["activemenu"]) { $activefound = true; }
					if (($i == 1) && (!$activefound)) { $newactive = $rs["groupname"]; }
				}  // security filter
			}  // duplicate filter
		}
		dbFreeResult($result);

		if (($i >= 1) && (!$activefound)) {
			$GLOBALS["activemenu"] = $newactive;
			$_GET["groupname"] = $newactive;
		}
	}
	return $i;
} // function ReadMenuStructure()


function ReadSubmenuStructure($GroupName,$loginreq,$usergroups)
{
	$si = 0;

	//	Flush the array from any previous calls to this function so that we don't get any 'phantom'
	//		menu entries.
	if (isset($GLOBALS["subgroups"])) {
		$vCount = count($GLOBALS["subgroups"]);
		for ($i = 0; $i < $vCount; $i++) {
		    $dump = array_pop($GLOBALS["subgroups"]);
		}
	}

	if (menusecuritycheck($GroupName,$loginreq,$usergroups)) {
		// We always list all menu items in the default site language; but if the user language is different we
		//		include any menu items in that language as well, sorted so that the user language items will be
		//		processed first.... then we filter out the default site language items when we transfer the
		//		retrieved list to the $GLOBALS array to avoid duplication.
		if ($GLOBALS["gsLanguage"] == $GLOBALS["gsDefault_language"]) {
			$strQuery = "SELECT * FROM ".$GLOBALS["eztbSubgroups"]." WHERE submenuvisible='Y' AND groupname='".$GroupName."' AND language='".$GLOBALS["gsLanguage"]."' ORDER BY subgrouporderid";
		} else {
			$lOrder = '';
			if ($GLOBALS["gsLanguage"] > $GLOBALS["gsDefault_language"]) { $lOrder = ' DESC'; }
			$strQuery = "SELECT * FROM ".$GLOBALS["eztbSubgroups"]." WHERE submenuvisible='Y' AND groupname='".$GroupName."' AND (language='".$GLOBALS["gsLanguage"]."' OR language='".$GLOBALS["gsDefault_language"]."') ORDER BY subgrouporderid,language".$lOrder;
		}
		$sresult = dbRetrieve($strQuery,true,0,0);

		$nSubGroupName = '';
		while ($rs = dbFetch($sresult)) {
			//  Suppress duplicates (ensuring we only get the appropriate language versions)
			if ($rs["subgroupname"] != $nSubGroupName) {
				$nSubGroupName = $rs["subgroupname"];
				//  Suppress hidden entries if the user doesn't have privilege to see them.
				//  Locked entries are filtered by the RenderSubGroups() function
				if (!hiddenmenu($rs["loginreq"],$rs["usergroups"])) {
					$GLOBALS["subgroups"][$si]["subgroupname"]		= $rs["subgroupname"];
					$GLOBALS["subgroups"][$si]["subgroupdesc"]		= $rs["subgroupdesc"];
					$GLOBALS["subgroups"][$si]["subgrouplink"]		= str_replace('../', '', $rs["subgrouplink"]);
					$GLOBALS["subgroups"][$si]["subgrouporderid"]	= $rs["subgrouporderid"];
					$GLOBALS["subgroups"][$si]["submenuimage1"]		= $rs["submenuimage1"];
					$GLOBALS["subgroups"][$si]["submenuimage2"]		= $rs["submenuimage2"];
					$GLOBALS["subgroups"][$si]["submenuimage3"]		= $rs["submenuimage3"];
					$GLOBALS["subgroups"][$si]["submenuimage4"]		= $rs["submenuimage4"];
					$GLOBALS["subgroups"][$si]["submenuvisible"]	= $rs["submenuvisible"];
					$GLOBALS["subgroups"][$si]["submenuorderby"]	= $rs["submenuorderby"];
					$GLOBALS["subgroups"][$si]["submenuorderdir"]	= $rs["submenuorderdir"];
					$GLOBALS["subgroups"][$si]["hovertitle"]		= $rs["hovertitle"];
					$GLOBALS["subgroups"][$si]["openinpage"]		= $rs["openinpage"];
					$GLOBALS["subgroups"][$si]["loginreq"]			= $rs["loginreq"];
					$GLOBALS["subgroups"][$si]["usergroups"]		= $rs["usergroups"];
					$si++;
				} // hidden filter
			} // duplicate filter
		}
		dbFreeResult($sresult);
	}
	return $si;
} // function ReadSubmenuStructure()

?>
