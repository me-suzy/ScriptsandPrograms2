<?php

/***************************************************************************

 topmenu.php
 ------------
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

// The global gsUseFrames is empty when framed.
//		settings.php will decide if it should be set or not
// The session variable noframesbrowser is set when viewing
//		a frame-configured site in a non-frames browser.

if (($GLOBALS["gsUseFrames"] == '') && ($EZ_SESSION_VARS["noframesbrowser"] != True)) {
	include ($GLOBALS["rootdp"]."include/settings.php");
	include ($GLOBALS["rootdp"]."include/functions.php");
	includeLanguageFiles('main');
	force_page_refresh();
}


$GLOBALS["bTopMenuExists"] = ReadTopMenuStructure();
if ($_GET["topgroupname"] != "") { $menuref = $_GET["topgroupname"];
} else { $menuref = $GLOBALS["gsHomepageTopGroup"]; }
frmTopMenu($menuref);



function jsTopMenuImage($prefix,$group,$image,$postfix)
{
	echo $prefix.$group.$postfix." = new Image(0,0) ;\n";
	echo $prefix.$group.$postfix.".src = './".$GLOBALS["image_home"].$image."' ;\n";
} // jsMenuImage()



function frmTopMenu($menuref)
{
	global $EZ_SESSION_VARS;

	// If we're in frames mode, output the page header data
	// In non-frames mode, this is handled by control.php
	if (($GLOBALS["gsUseFrames"] == 'Y') && ($EZ_SESSION_VARS["noframesbrowser"] != True)) {
		HTMLHeader('topmenu');
		StyleSheet();
	}

if ($GLOBALS["gsShowMouseover"] != "Y") { ?>
	<script language="JavaScript" type="text/javascript">
		<!-- Begin
		var currentTopMenu = '<?php echo $menuref; ?>' ;

		function inTopMenuArray( testArray, testElement ) {
			var r = 0 ;
			for ( var i = 0; i < testArray.length; i++ ) {
				if ( testArray[i] == testElement ) {
					r = 1 ;
				}
			}
			return r ;
		}

		function ChangeFrames( imgDocID, TopGroupName ) {
			parent.left.location.href="<?php echo BuildLink('menu.php'); ?>&topgroupname=" + TopGroupName ;
			parent.contents.location.href="<?php echo BuildLink('showcontents.php'); ?>&topgroupname=" + TopGroupName ;
			if ( TopMenuImageObj ) {
				var iTest = inTopMenuArray( topmenuImageArray, currentTopMenu );
				if ( iTest ) {
					document.images[imgDocID].src = eval( 'gt' + currentTopMenu + '.src' ) ;
				}
			}
			currentTopMenu = TopGroupName ;
			if ( TopMenuImageObj ) {
				var iTest = inTopMenuArray( topmenuImageArray, currentTopMenu );
				if ( iTest ) {
					var iref = eval('topmenu' + currentTopMenu) ;
					iref.src = eval( 'gt' + currentTopMenu + 'c.src' ) ;
				}
			}
		}

		function changeTopMenuImageOver( imgDocID, imgObjName ) {
			if ( TopMenuImageObj ) {
				var iTest = inTopMenuArray( topmenuImageArray, imgObjName );
				if ( iTest ) {
					if ( imgObjName == currentTopMenu ) {
						document.images[imgDocID].src = eval( 'gt' + imgObjName + 'c.src' ) ;
					}
					else {
						document.images[imgDocID].src = eval( 'gt' + imgObjName + 'a.src' ) ;
					}
				}
			}
		}

		function changeTopMenuImageOut( imgDocID, imgObjName ) {
			if ( TopMenuImageObj ) {
				var iTest = inTopMenuArray( topmenuImageArray, imgObjName );
				if ( iTest ) {
					if ( imgObjName == currentTopMenu ) {
						document.images[imgDocID].src = eval( 'gt' + imgObjName + 'b.src' ) ;
					}
					else {
						document.images[imgDocID].src = eval( 'gt' + imgObjName + '.src' ) ;
					}
				}
			}
		}

		var TopMenuImageObj = 0 ;
		if ( document.images ) {
			TopMenuImageObj = 1 ;
			var topmenuImageArray = new Array ;
			<?php
			if ($GLOBALS["bTopMenuExists"] > 0) {
				$i = 0;
				reset($GLOBALS["topgroups"]);
				while (list($topi,$val) = each($GLOBALS["topgroups"])) {
					if ($GLOBALS["topgroups"][$topi]["topmenuimage1"] != "") {
						echo "topmenuImageArray[".$i."] = '".$GLOBALS["topgroups"][$topi]["topgroupname"]."' ;\n";
						jsTopMenuImage('gt',$GLOBALS["topgroups"][$topi]["topgroupname"],$GLOBALS["topgroups"][$topi]["topmenuimage1"],'');
						if ($GLOBALS["topgroups"][$topi]["topmenuimage2"] != "")
						{
							jsTopMenuImage('gt',$GLOBALS["topgroups"][$topi]["topgroupname"],$GLOBALS["topgroups"][$topi]["topmenuimage2"],'a');
						} else {
							jsTopMenuImage('gt',$GLOBALS["topgroups"][$topi]["topgroupname"],$GLOBALS["topgroups"][$topi]["topmenuimage1"],'a');
						}
						if ($GLOBALS["topgroups"][$topi]["topmenuimage3"] != "") {
							jsTopMenuImage('gt',$GLOBALS["topgroups"][$topi]["topgroupname"],$GLOBALS["topgroups"][$topi]["topmenuimage3"],'b');
							if ($GLOBALS["topgroups"][$topi]["topmenuimage4"] != "") {
								jsTopMenuImage('gt',$GLOBALS["topgroups"][$topi]["topgroupname"],$GLOBALS["topgroups"][$topi]["topmenuimage4"],'c');
							} else {
								jsTopMenuImage('gt',$GLOBALS["topgroups"][$topi]["topgroupname"],$GLOBALS["topgroups"][$topi]["topmenuimage3"],'c');
							}
						} else {
							jsTopMenuImage('gt',$GLOBALS["topgroups"][$topi]["topgroupname"],$GLOBALS["topgroups"][$topi]["topmenuimage1"],'b');
							if ($GLOBALS["topgroups"][$topi]["topmenuimage2"] != "") {
								jsTopMenuImage('gt',$GLOBALS["topgroups"][$topi]["topgroupname"],$GLOBALS["topgroups"][$topi]["topmenuimage2"],'c');
							} else {
								jsTopMenuImage('gt',$GLOBALS["topgroups"][$topi]["topgroupname"],$GLOBALS["topgroups"][$topi]["topmenuimage1"],'c');
							}
						}
						$i++;
					}
				}
			}
			?>
		}
		//  End -->
	</script>
<? } 

	if (($GLOBALS["gsUseFrames"] == 'Y') && ($EZ_SESSION_VARS["noframesbrowser"] != True)) {
		?>
		</head>
		<body marginwidth="0" marginheight="0" leftmargin="5" rightmargin="5" topmargin="0" class="topmenuback">
	<?php
		   if ($GLOBALS["gsShowMouseover"] == "Y") {
	?>
	<script language="JavaScript1.2">dqm__codebase = "scripts/"</script><script language="JavaScript1.2" src="scripts/sample_settings.js"></script><script language="JavaScript1.2">function alert() {}</script>
	<? } 
	} else {
		?>
		<table border="0" cellspacing="0" cellpadding="0" width="100%" height="100%">
		<tr><td valign="top" align="<?php echo $GLOBALS["left"]; ?>" class="topmenuback" width="100%" height="100%">
		<?php
	}

	$twidth = '';
	if ($GLOBALS["gsTopMenuAlign"] == 'C') { $talign = 'center';
	} elseif ($GLOBALS["gsTopMenuAlign"] == 'J') {
		$talign = 'center';
		$twidth = '100%';
	} elseif ($GLOBALS["gsTopMenuAlign"] == 'R') { $talign = 'right';
	} else { $talign='left'; }
	?>

	<table border="0" cellspacing="0" cellpadding="0" width="100%" height="100%">
	<tr><td align="<?php echo $talign; ?>">
	<?php
	if ($GLOBALS["bTopMenuExists"] > 0) {

       if (($GLOBALS["gsShowMouseover"] == "Y") && ($GLOBALS["gsUseFrames"] !== 'Y') && ($EZ_SESSION_VARS["noframesbrowser"] = True)) {

		// ### Ausgabe des DHTML Menues
		echo '<table border=0 height=100% cellspacing=0 cellpadding=0>
				<tr class="topmenu"><td>';
        $menu_javascript = "";
        // Hauptmenuepunkte
        $menu_col_width = 120;
		// Dynamische Hauptmenübreite
        //$menu_col_width = ceil ($GLOBALS["gsSiteWidth"] / $GLOBALS["bTopMenuExists"]);
        $menu_margin_top = ceil ($GLOBALS["gnTopMenuFrameHeight"] / 2 - $GLOBALS["gnTopMenuFrameHeight"] / 4);
        $menu_javascript .= 'dqm__main_width = '.$menu_col_width."\n";
        $menu_javascript .= 'dqm__main_margin_top = '.$menu_margin_top."\n";
		$menu_javascript .= 'dqm__main_height = '.$GLOBALS["gnTopMenuFrameHeight"]."\n";
		$menu_javascript .= 'dqm__main_horizontal = true'."\n";
		$menu_javascript .= 'dqm__main_bgcolor = "'.$GLOBALS["bgcolor_topmenu"].'"'."\n";
		$menu_javascript .= 'dqm__main_textcolor = "'.$GLOBALS["topmenu_color_ahref"].'"'."\n";
		$menu_javascript .= 'dqm__main_hl_textcolor = "'.$GLOBALS["topmenu_color_ahref_hover"].'"'."\n";
		$menu_javascript .= 'dqm__main_fontfamily = "'.$GLOBALS["gsFont1"].'"'."\n";
		$menu_javascript .= 'dqm__main_fontsize = "'.substr($GLOBALS["gsTopMenuFontSize"], 0, -2).'"'."\n";
		$menu_javascript .= 'dqm__main_textdecoration = "'.$GLOBALS["gsTopMenuFontStyle"].'"'."\n";
		$menu_javascript .= 'dqm__main_hl_textdecoration = "'.$GLOBALS["gsTopMenuFontStyle"].'"'."\n";
		// Untermenüpunkte
		$menu_javascript .= 'dqm__sub_xy = "-'.$menu_col_width.','.$GLOBALS["gnTopMenuFrameHeight"].'"'."\n";
		$menu_javascript .= 'dqm__border_color = "'.$GLOBALS["bgcolor_topmenu"].'"'."\n";
		$menu_javascript .= 'dqm__menu_bgcolor = "'.$GLOBALS["bgcolor_topmenu"].'"'."\n";
		$menu_javascript .= 'dqm__textcolor = "'.$GLOBALS["menu_color_ahref"].'"'."\n";
		$menu_javascript .= 'dqm__fontfamily = "'.$GLOBALS["gsFont1"].'"'."\n";
		$menu_javascript .= 'dqm__fontsize = "'.substr($GLOBALS["gsFontSize3"], 0, -2).'"'."\n";
		$menu_javascript .= 'dqm__textdecoration = "'.$GLOBALS["gsFontStyle3"].'"'."\n";
		$menu_javascript .= 'dqm__textcolor = "'.$GLOBALS["menu_color_ahref"].'"'."\n";
		$menu_javascript .= 'dqm__hl_textcolor = "'.$GLOBALS["menu_color_ahref_hover"].'"'."\n";   
		$menu_javascript .= 'dqm__hl_textdecoration = "'.$GLOBALS["gsFontStyle3"].'"'."\n";

        // Hauptmenuepunkte (topgroups)

        $top_num = 0;
		$setflag = 0;
        foreach ($GLOBALS["topgroups"] AS $topgroup_id => $topgroup_data) { 
				if ($topgroup_data["topmenuimage1"] <> "") {  }
					else { $menu_javascript .= 'dqm__maindesc'.$top_num.' = "'.$topgroup_data["topgroupdesc"].'"'."\n"; 
					$setflag++;
				}
	            if ($topgroup_data["topgrouplink"] != "") { $modul_link = $topgroup_data["topgrouplink"]; }
				if ($topgroup_data["topopeninpage"] == "Y") {
					$menu_javascript .= 'dqm__urltarget'.$top_num.' = "_self"'."\n";
				} else {
					$menu_javascript .= 'dqm__urltarget'.$top_num.' = "_new"'."\n";
				}
				if (stristr($modul_link, "http")) {
	            	$menu_javascript .= 'dqm__url'.$top_num.' = "'.$modul_link.'"'."\n";
	            } else {
	            	$menu_javascript .= 'dqm__url'.$top_num.' = "control.php?&topgroupname='.$topgroup_data["topgroupname"].'&link='.$modul_link.'"'."\n";
				}
				if ($topgroup_data["topmenuimage2"] != "") { 
					if (file_exists($GLOBALS["image_home"].$topgroup_data["topmenuimage2"]) == true) {
						$imageInfo = getimagesize($GLOBALS["image_home"].$topgroup_data["topmenuimage2"]);
						$sizeW = $imageInfo[0];
						$sizeH = $imageInfo[1];
					}
					$menu_javascript .= 'dqm__rollover_image'.$top_num.' = "'.$GLOBALS["image_home"].$topgroup_data["topmenuimage2"].'"'."\n";
					$menu_javascript .= 'dqm__rollover_wh'.$top_num.' = "'.$sizeW.','.$sizeH.'"'."\n";
					$menu_javascript .= 'dqm__sub_xy'.$top_num.' = "-'.$sizeW.','.$sizeH.'"'."\n"; 
				}         

            // Menue (groups)
            $strQuery = "SELECT * FROM ".$GLOBALS["eztbGroups"]." WHERE menuvisible='Y' AND language='".$GLOBALS["gsLanguage"]."' AND topgroupname='".$topgroup_data["topgroupname"]."' OR topgroupname='999999999' ORDER BY grouporderid";
    	    $result = dbRetrieve($strQuery,true,0,0);
    	    $sub_num = 0;
    	    while ($rs = dbFetch($result)) {
                $menu_javascript .= 'dqm__subdesc'.$top_num.'_'.$sub_num.' = "'.$rs["groupdesc"].'"'."\n";
                if ($rs["grouplink"] != "") { $modul_link = $rs["grouplink"]; }
				if ($rs["openinpage"] == "Y") {
					$menu_javascript .= 'dqm__urltarget'.$top_num.'_'.$sub_num.' = "_self"'."\n";
				} else {
					$menu_javascript .= 'dqm__urltarget'.$top_num.'_'.$sub_num.' = "_new"'."\n";
				}
				if (stristr($modul_link, "http")) {
            		$menu_javascript .= 'dqm__url'.$top_num.'_'.$sub_num.' = "'.$modul_link.'"'."\n";
            	} else {
                	$menu_javascript .= 'dqm__url'.$top_num.'_'.$sub_num.' = "control.php?&topgroupname='.$rs["topgroupname"].'&groupname='.$rs["groupname"].'&action=expand&link='.$modul_link.'"'."\n";
				}
				$modul_link = '';
            
                // Untermenue (subgroups)
        	    $sub_sub_num = 0;
        	    $strQuery = "SELECT * FROM ".$GLOBALS["eztbSubgroups"]." WHERE submenuvisible='Y' AND language='".$GLOBALS["gsLanguage"]."' AND groupname='".$rs["groupname"]."' ORDER BY subgrouporderid";
        	    $result_sub = dbRetrieve($strQuery,true,0,0);
        	    while ($rs_sub = dbFetch($result_sub)) {
                    $menu_javascript .= 'dqm__subdesc'.$top_num.'_'.$sub_num.'_'.$sub_sub_num.' = "'.$rs_sub["subgroupdesc"].'"'."\n";
	                if ($rs_sub["subgrouplink"] != "") { $modul_link = $rs_sub["subgrouplink"]; }
					if ($rs_sub["openinpage"] == "Y") {
						$menu_javascript .= 'dqm__urltarget'.$top_num.'_'.$sub_num.'_'.$sub_sub_num.' = "_self"'."\n";
					} else {
						$menu_javascript .= 'dqm__urltarget'.$top_num.'_'.$sub_num.'_'.$sub_sub_num.' = "_new"'."\n";
					}
					if (stristr($modul_link, "http")) {
            			$menu_javascript .= 'dqm__url'.$top_num.'_'.$sub_num.'_'.$sub_sub_num.' = "'.$modul_link.'"'."\n";
            		} else {
	                    $menu_javascript .= 'dqm__url'.$top_num.'_'.$sub_num.'_'.$sub_sub_num.' = "control.php?&topgroupname='.$rs["topgroupname"].'&groupname='.$rs["groupname"].'&subgroupname='.$rs_sub["subgroupname"].'&action=expand&link='.$modul_link.'"'."\n";
					}
                    $menu_javascript .= 'dqm__sub_xy'.$top_num.'_'.$sub_num.' = "0,0"'."\n";
                    $menu_javascript .= 'dqm__2nd_icon_index'.$top_num.'_'.$sub_num.' = 0'."\n";
				$modul_link = '';
        	    ++$sub_sub_num;
        	    }    	        
    	    ++$sub_num;
    	    }          
        ++$top_num;
		}

		if ($setflag == $top_num) { $pre= "t"; }
		echo '
		<script language="JavaScript1.2">'.$menu_javascript.'</script>
        <script language="JavaScript1.2" src="scripts/'.$pre.'dqm_loader.js"></script>';
		if ($pre == "t") { echo '<script language="JavaScript1.2">generate_mainitems()</script>'; }
        RenderTopGroupsDTHML();
		echo '</td></tr></table></td>';
	} else {

	?><table border="<?php echo $GLOBALS["gsTopMenuBorder"]; ?>" height="100%" align="<?php echo $talign; ?>" cellspacing="0" cellpadding="4" <?php if ($twidth != "") { echo 'width="'.$twidth.'"'; } ?>>
	<tr class="topmenu">
	<?
		RenderTopGroups($menuref,$talign);
	}
	}
	?>
	</tr>
	</table>
	</td></tr>
	</table>
	<?php

	// If we're in frames mode, output the page footer data
	// In non-frames mode, this is handled by control.php
	if (($GLOBALS["gsUseFrames"] == 'Y') && ($EZ_SESSION_VARS["noframesbrowser"] != True)) {
		?>
		</body>
		</html>
		<?php
	} else {
		?>
		</td></tr></table>
		<?php
	}
} // function frmTopMenu()


function RenderTopGroupsDTHML()
{
	global $EZ_SESSION_VARS;
	if ($GLOBALS["topgroups"]) { reset($GLOBALS["topgroups"]); }
	$menucount=0;
	while (list($topi,$val) = each($GLOBALS["topgroups"])) {
			if ($GLOBALS["topgroups"][$topi]["topmenuimage1"] == '') { 
			} else {
			echo '<img src="'.$GLOBALS["image_home"].$GLOBALS["topgroups"][$topi]["topmenuimage1"].'" border="0" name="menu'.$menucount.'" id="menu'.$menucount.'" onmouseover="showMenu(event)" onmouseout="hideMenu(event)">';	
			$menucount++;
			}
	}

} // function RenderTopGroupsDTHML()

function RenderTopGroups($menuref,$talign)
{
	global $EZ_SESSION_VARS;

	$lines = ceil($GLOBALS["bTopMenuExists"] / $GLOBALS["gsTopMenuRows"]);
	$cols = $GLOBALS["bTopMenuExists"] % $GLOBALS["gsTopMenuRows"];
	$BlankColumns = $GLOBALS["gsTopMenuRows"] - $cols;
	if ($BlankColumns == $GLOBALS["gsTopMenuRows"]) { $BlankColumns = 0; }

	$separator_bar = explode('?',$GLOBALS["gsTopMenuSeparator"]);
	if ($GLOBALS["topgroups"]) { reset($GLOBALS["topgroups"]); }

	$linecount = 1;
	$rowcount = 1;
	$barcount = 0;
	$menucount=0;
	if (($talign == 'right') && ($linecount == $lines)) {
		for ($i = 1; $i < $BlankColumns; $i++) {
			echo '<td class="topmenu"></td>';
			if (trim($separator_bar[2]) != '') { echo '<td class="topmenu"></td>'; }
		}
	}
	if (trim($separator_bar[0]) != '') { echo '<td align="center">'.trim($separator_bar[0]).'</td>'; }
	while (list($topi,$val) = each($GLOBALS["topgroups"])) {
		if (($GLOBALS["gsPrivateMenus"] == 'L') || ($GLOBALS["topgroups"][$topi]["loginreq"] != 'Y') || ($EZ_SESSION_VARS["PasswordCookie"] != '')) {
			if ($rowcount > $GLOBALS["gsTopMenuRows"]) {
				if (trim($separator_bar[2]) != '') { echo '<td align="center">'.trim($separator_bar[2]).'</td>'; }
				$rowcount = 1;
				$barcount = 0;
				?>
				</tr><tr class="topmenu">
				<?php
				$linecount++;
				if (($linecount == $lines) && ($talign == 'right')) {
					for ($i = 0; $i < $BlankColumns; $i++) {
						echo '<td class="topmenu"></td>';
						if (trim($separator_bar[2]) != '') { echo '<td class="topmenu"></td>'; }
						if ((trim($separator_bar[1]) != '') && (trim($separator_bar[2]) == '')) { echo '<td class="topmenu"></td>'; }
					}
				}
				if (trim($separator_bar[0]) != '') { echo '<td align="center" valign="top">'.trim($separator_bar[0]).'</td>'; }
			}
			if (($barcount != 0) && (trim($separator_bar[1]) != '')) { echo '<td align="center">'.trim($separator_bar[1]).'</td>'; }
			?>
			<td align="<?php echo $talign; ?>">
			<?php
			$GLOBALS["topgroups"][$topi]["topgrouplink"] = privatemenu($GLOBALS["topgroups"][$topi]["loginreq"],$GLOBALS["topgroups"][$topi]["usergroups"],$GLOBALS["topgroups"][$topi]["topgrouplink"]);
			$menustring = '<a class="topmenulink" ';
			if ($GLOBALS["gsMenuHover"] == "Y") {
				if ($GLOBALS["topgroups"][$topi]["tophovertitle"] != '') { $hovertext= $GLOBALS["topgroups"][$topi]["tophovertitle"];
				} else { $hovertext= $GLOBALS["topgroups"][$topi]["topgroupdesc"]; }
				$menustring .= 'title="'.$hovertext.'" ';
			}
                        $menustring .= 'href="';
                        if ($GLOBALS["topgroups"][$topi]["topgrouplink"] == '') {
                                if (($GLOBALS["gsUseFrames"] == 'Y') && ($EZ_SESSION_VARS["noframesbrowser"] != True)) {
                                        //$menustring .= 'javascript:ChangeFrames(\'topmenu\' + currentTopMenu,\''.$GLOBALS["topgroups"][$topi]["topgroupname"].'\');"';
                                        $menustring .= BuildLink('module.php').'&topgroupname='.$GLOBALS["topgroups"][$topi]["topgroupname"].'" target="_top"';
                                } else {
                                        $menustring .= BuildLink('control.php').'&topgroupname='.$GLOBALS["topgroups"][$topi]["topgroupname"].'" target="_top"';
                                }
                        } else {
                                if (($GLOBALS["gsUseFrames"] == 'Y') && ($EZ_SESSION_VARS["noframesbrowser"] != True)) {
                                        if (($GLOBALS["topgroups"][$topi]["topopeninpage"] == 'Y') || ($GLOBALS["topgroups"][$topi]["topgrouplink"] == 'loginreq.php') || ($GLOBALS["topgroups"][$topi]["topgrouplink"] == 'loginreq2.php')) {
                                                if ((substr($GLOBALS["topgroups"][$topi]["topgrouplink"],0,7) == 'http://') || (substr($GLOBALS["topgroups"][$topi]["topgrouplink"],0,8) == 'https://')) {
                                                        $menustring .= $GLOBALS["topgroups"][$topi]["topgrouplink"].'" target="contents"';
                                                } else {
                                                        $menustring .= BuildLink('module.php').'&link='.$GLOBALS["topgroups"][$topi]["topgrouplink"].'&topgroupname='.$GLOBALS["topgroups"][$topi]["topgroupname"].'" target="contents"';
                                                }
                                        } else {
                                                $menustring .= $GLOBALS["topgroups"][$topi]["topgrouplink"].'" target="_blank"';
                                        }
                                } else {
                                        if (($GLOBALS["topgroups"][$topi]["topopeninpage"] == 'Y') || ($GLOBALS["topgroups"][$topi]["topgrouplink"] == 'loginreq.php') || ($GLOBALS["topgroups"][$topi]["topgrouplink"] == 'loginreq2.php')) {
                                                if ((substr($GLOBALS["topgroups"][$topi]["topgrouplink"],0,7) == 'http://') || (substr($GLOBALS["topgroups"][$topi]["topgrouplink"],0,8) == 'https://')) {
                                                        $menustring .= $GLOBALS["topgroups"][$topi]["topgrouplink"].'"';
                                                } else {
                                                        $menustring .= BuildLink('control.php').'&link='.$GLOBALS["topgroups"][$topi]["topgrouplink"].'&topgroupname='.$GLOBALS["topgroups"][$topi]["topgroupname"].'"';
                                                }
                                        } else {
                                                $menustring .= $GLOBALS["topgroups"][$topi]["topgrouplink"].'" target="_blank"';
                                        }
                                }
                        }
			if ($GLOBALS["topgroups"][$topi]["topmenuimage1"] == '') {
				$menustring .= BuildLinkMouseOver($GLOBALS["topgroups"][$topi]["topgroupdesc"]).'>';
				$menustring .= $GLOBALS["topgroups"][$topi]["topgroupdesc"].'</a>';
			} else {
					$menustring .= ' onMouseOver="changeTopMenuImageOver(\'topmenu'.$GLOBALS["topgroups"][$topi]["topgroupname"].'\',\''.$GLOBALS["topgroups"][$topi]["topgroupname"].'\'); window.status=\''.str_replace("'","\'",$GLOBALS["topgroups"][$topi]["topgroupdesc"]).'\'; return true;"';
					$menustring .= ' onMouseOut="changeTopMenuImageOut(\'topmenu'.$GLOBALS["topgroups"][$topi]["topgroupname"].'\',\''.$GLOBALS["topgroups"][$topi]["topgroupname"].'\'); window.status=\'\'; return true;">';
					if (($GLOBALS["topgroups"][$topi]["topgroupname"] == $menuref) && ($GLOBALS["topgroups"][$topi]["topmenuimage3"] != '')) {
						$menustring .= '<img src="./'.$GLOBALS["image_home"].$GLOBALS["topgroups"][$topi]["topmenuimage3"].'" border="0" name="topmenu'.$GLOBALS["topgroups"][$topi]["topgroupname"].'"></a>';
					} else {
						$menustring .= '<img src="./'.$GLOBALS["image_home"].$GLOBALS["topgroups"][$topi]["topmenuimage1"].'" border="0" name="topmenu'.$GLOBALS["topgroups"][$topi]["topgroupname"].'"></a>';
					}
			}
			if (($GLOBALS["topgroups"][$topi]["loginreq"] == 'Y') && ($GLOBALS["gsSecureIcon"] != '')) {
				$menustring .= '&nbsp;&nbsp;'.imagehtmltag($GLOBALS["image_home"],$GLOBALS["gsSecureIcon"],$GLOBALS["tPrivateOption"],0,'');
			}

			echo $menustring;
			$barcount++;
			$rowcount++;
			?>
			</td>
			<?php
		}
	}
	$menucount++;
	if (trim($separator_bar[2]) != '') { echo '<td align="center">'.trim($separator_bar[2]).'</td>'; }
	if ($talign != 'right') {
		for ($i = 0; $i < $BlankColumns; $i++) {
			echo '<td class="topmenu"></td>';
			if (trim($separator_bar[2]) != '') { echo '<td class="topmenu"></td>'; }
			if ((trim($separator_bar[1]) != '') && (trim($separator_bar[2]) == '')) { echo '<td class="topmenu"></td>'; }
		}
	}
} // function RenderTopGroups()


function ReadTopMenuStructure()
{
	$topi = 0;
	// We always list all menu items in the default site language; but if the user language is different we
	//		include any menu items in that language as well, sorted so that the user language items will be
	//		processed first.... then we filter out the default site language items when we transfer the
	//		retrieved list to the $GLOBALS array to avoid duplication.
	if ($GLOBALS["gsLanguage"] == $GLOBALS["gsDefault_language"]) {
		$strQuery = "SELECT * FROM ".$GLOBALS["eztbTopgroups"]." WHERE topmenuvisible='Y' AND language='".$GLOBALS["gsLanguage"]."' ORDER BY topgrouporderid";
	} else {
		$lOrder = '';
		if ($GLOBALS["gsLanguage"] > $GLOBALS["gsDefault_language"]) { $lOrder = ' DESC'; }
		$strQuery = "SELECT * FROM ".$GLOBALS["eztbTopgroups"]." WHERE topmenuvisible='Y' AND (language='".$GLOBALS["gsLanguage"]."' OR language='".$GLOBALS["gsDefault_language"]."') ORDER BY topgrouporderid,language".$lOrder;
	}
	$result = dbRetrieve($strQuery,true,0,0);

	$nTopGroupName = '';
	while ($rs = dbFetch($result)) {
		//  Suppress duplicates (ensuring we only get the appropriate language versions)
		if ($rs["topgroupname"] != $nTopGroupName) {
			$nTopGroupName = $rs["topgroupname"];
			//  Suppress hidden entries if the user doesn't have privilege to see them.
			//  Locked entries are filtered by the RenderTopGroups() function
			if (!hiddenmenu($rs["loginreq"],$rs["usergroups"])) {
				$GLOBALS["topgroups"][$topi]["topgroupname"]	= $rs["topgroupname"];
				$GLOBALS["topgroups"][$topi]["topgroupdesc"]	= $rs["topgroupdesc"];
				$GLOBALS["topgroups"][$topi]["topgrouplink"]	= $rs["topgrouplink"];
				$GLOBALS["topgroups"][$topi]["topgrouporderid"] = $rs["topgrouporderid"];
				$GLOBALS["topgroups"][$topi]["topmenuimage1"]	= $rs["topmenuimage1"];
				$GLOBALS["topgroups"][$topi]["topmenuimage2"]	= $rs["topmenuimage2"];
				$GLOBALS["topgroups"][$topi]["topmenuimage3"]	= $rs["topmenuimage3"];
				$GLOBALS["topgroups"][$topi]["topmenuimage4"]	= $rs["topmenuimage4"];
				$GLOBALS["topgroups"][$topi]["topmenuvisible"]  = $rs["topmenuvisible"];
				$GLOBALS["topgroups"][$topi]["topmenuorderby"]  = $rs["topmenuorderby"];
				$GLOBALS["topgroups"][$topi]["topmenuorderdir"] = $rs["topmenuorderdir"];
				$GLOBALS["topgroups"][$topi]["tophovertitle"]	= $rs["tophovertitle"];
				$GLOBALS["topgroups"][$topi]["topopeninpage"]	= $rs["topopeninpage"];
				$GLOBALS["topgroups"][$topi]["loginreq"]		= $rs["loginreq"];
				$GLOBALS["topgroups"][$topi]["usergroups"]		= $rs["usergroups"];
				$topi++;
			} // hidden filter
		} // duplicate filter
	}
	dbFreeResult($result);
	return $topi;
} // function ReadTopMenuStructure()

?>
