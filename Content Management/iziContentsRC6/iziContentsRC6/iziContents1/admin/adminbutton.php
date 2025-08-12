<?php

/***************************************************************************

 adminbutton.php
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


//	Determine if the php gd graphics extension is loaded on the server
//
$GLOBALS["gd_available"] = extension_loaded('gd');


//	Netscape cannot handle the BACKGROUND qualifier in an HTML TABLE, so we need to determine
//		if the user is browsing with Netscape and make special allowance. (Oh! The delights of
//		the W3C standard.)
//
$agent = $_SERVER["HTTP_USER_AGENT"];
if ((ereg("Nav", $agent)) || (ereg("Gold", $agent)) || (ereg("X11", $agent)) || (ereg("Mozilla", $agent)) || (ereg("Netscape", $agent)) AND (!ereg("MSIE", $agent))) {
	$GLOBALS["browser"] = "Netscape";
}


//  Include the admin theme settings, both the default values, and for the defined theme (if one is set)
$stylesettings = $GLOBALS["rootdp"].$GLOBALS["style_home"]."settings.php";
if (file_exists($stylesettings)) {
	include_once($stylesettings);
}
if ($GLOBALS["gsAdminStyle"] != '') {
	$GLOBALS["theme_home"] = $GLOBALS["style_home"].$GLOBALS["gsAdminStyle"].'/';
} else {
	$GLOBALS["theme_home"] = $GLOBALS["style_home"];
}
$stylesettings = $GLOBALS["rootdp"].$GLOBALS["theme_home"]."settings.php";
if (file_exists($stylesettings)) {
	include_once($stylesettings);
}



//  Determine if a specified graphic image file exists or not
function buttonexists ($subdirectory,$button,$extension)
{
	$buttonimage = $GLOBALS["rootdp"].$GLOBALS["style_home"].$subdirectory.$button.".".$extension;
	if (!file_exists($buttonimage)) return '';
	return $buttonimage;
} // function buttonexists ()


//  Determine the graphic image file for a button
//  This can have an extension of .gif, .jpg, .jpeg or .png (tested in that order of precedence);
//		and if it isn't explicitly set for the admin style, then we use the default.
//
function buttonimageexists($button)
{
	global $EzAdmin_Style;

	$buttonfile = False;
	if ($GLOBALS["gsAdminStyle"] != '') {
		$buttonfile = buttonexists($GLOBALS["gsAdminStyle"].'/',$EzAdmin_Style[$button],'gif');
		if ($buttonfile == '') { $buttonfile = buttonexists($GLOBALS["gsAdminStyle"].'/',$EzAdmin_Style[$button],'jpg'); }
		if ($buttonfile == '') { $buttonfile = buttonexists($GLOBALS["gsAdminStyle"].'/',$EzAdmin_Style[$button],'jpeg'); }
		if ($buttonfile == '') { $buttonfile = buttonexists($GLOBALS["gsAdminStyle"].'/',$EzAdmin_Style[$button],'png'); }
	}
	if ($buttonfile == '') {
		$buttonfile = buttonexists('',$EzAdmin_Style[$button],'gif');
		if ($buttonfile == '') { $buttonfile = buttonexists('',$EzAdmin_Style[$button],'jpg'); }
		if ($buttonfile == '') { $buttonfile = buttonexists('',$EzAdmin_Style[$button],'jpeg'); }
		if ($buttonfile == '') { $buttonfile = buttonexists('',$EzAdmin_Style[$button],'png'); }
	}
	return $buttonfile;
} // function buttonimageexists()


function setbuttons($button)
{
	global $EzAdmin_Style;

	if ($EzAdmin_Style[$button."Border"] != '') { $GLOBALS[$button]["Border"] = $EzAdmin_Style[$button."Border"]; } else { $GLOBALS[$button]["Border"] = '0'; }
	//  Determine the name and location of the button image file
	$GLOBALS[$button]["Template"] = buttonimageexists($button);
	//  Once we've determined if there is a button template or not,
	//			get the image dimensions.
	//
	if ($GLOBALS[$button]["Template"] != '') {
		if (file_exists($GLOBALS[$button]['Template']) == true) {
			$sourceImageSize = getImageSize($GLOBALS[$button]['Template']);
			$GLOBALS[$button]['ImageWidth']  = $sourceImageSize[0] + ($GLOBALS[$button]["Border"] * 2);
			$GLOBALS[$button]['ImageHeight'] = $sourceImageSize[1] + ($GLOBALS[$button]["Border"] * 2);
		} else {
			$GLOBALS[$button]['ImageWidth']  = $EzAdmin_Style["adminmenuwidth"] - ($EzAdmin_Style["adminsubmenuoffset"] * 2);
			$GLOBALS[$button]['ImageHeight'] = '';
		}
	} else {
		$GLOBALS[$button]['ImageWidth']  = $EzAdmin_Style["adminmenuwidth"] - ($EzAdmin_Style["adminsubmenuoffset"] * 2);
		$GLOBALS[$button]['ImageHeight'] = '';
	}
	$GLOBALS[$button]["Colour"] = $EzAdmin_Style[$button."Colour"];
//	if ($EzAdmin_Style[$button."BorderColour"] != '') { $GLOBALS[$button]["BorderColour"] = $EzAdmin_Style[$button."BorderColour"]; } else { $GLOBALS[$button]["BorderColour"] = $GLOBALS[$button."Colour"]; }
	if ($EzAdmin_Style[$button."Align"] != '') { $GLOBALS[$button]["Align"] = $EzAdmin_Style[$button."Align"]; } else { $GLOBALS[$button]["Align"] = 'left'; }
	if ($EzAdmin_Style[$button."Valign"] != '') { $GLOBALS[$button]["Valign"] = $EzAdmin_Style[$button."Valign"]; } else { $GLOBALS[$button]["Valign"] = 'middle'; }

//	echo $button.'<br />';
//	print_r($GLOBALS[$button]);
//	echo '<br /><br />';
//	exit;
} // function setbuttons()


setbuttons('addbutton');
setbuttons('menubutton');



function menubutton($rs,$cgroupname)
{
	global $EzAdmin_Style;

	$buttontype = 'menubutton';

	$title = $GLOBALS["tgd".$cgroupname];
//	$mtext = urlencode($GLOBALS["tg".$cgroupname]);
	$mtext = $GLOBALS["tg".$cgroupname];
	$href  = BuildLink('t_menu.php').'&groupname='.$rs["groupname"];
	$cref  = 'javascript:window.location.href=\''.BuildLink('menu.php').'&activegroup='.$rs["groupname"].'\';"';
	$hover = BuildLinkMouseOver($GLOBALS["tgd".$cgroupname]);
	$hlink = '<a class="menulink" title="'.$title.'" href="'.$href.'" '.$hover.' onClick="'.$cref.' target="content">';

	$newbutton = displaybutton('menubutton',$cgroupname,$mtext,$hlink);

	return $newbutton;
} // function menubutton()


function displaybutton($buttontype,$buttonname,$title,$hlink)
{
	global $EzAdmin_Style;

	$buttoncached = TestCache($buttontype,$buttonname,$GLOBALS["gsLanguage"],'gif');
	if (!$buttoncached) $buttoncached = TestCache($buttontype,$buttonname,$GLOBALS["gsLanguage"],'jpg');
	if (!$buttoncached) $buttoncached = TestCache($buttontype,$buttonname,$GLOBALS["gsLanguage"],'png');
	if ($buttoncached) {
		//  If there is a cached button image, simply display it
		$newbutton = $hlink;
		$newbutton .= '<img border="0" src="'.$GLOBALS["buttonimage"].'">';
		$newbutton .= '</a>';
	} else {
		//  Otherwise we need to create a button image that will be cached for subsequent accesses
		//		but only if the language supports it.
		if (($EzAdmin_Style["menubutton"] == 'cacheable') && ($GLOBALS["gd_available"]) && (buttontestlanguage())) {
			//  If the gd library is enabled, we create the appropriate button, and cache it for future use
			$newbutton = $hlink;
			$newbutton .= '<img border="0" src="'.$GLOBALS["rootdp"].$GLOBALS["admin_home"].'button.php?template='.$EzAdmin_Style[$buttontype].'&text='.$title.'&font='.$EzAdmin_Style[$buttontype."Font"].'&fontsize='.$EzAdmin_Style[$buttontype."FontSize"].'&align='.$EzAdmin_Style[$buttontype."Align"].'&valign='.$EzAdmin_Style[$buttontype."Valign"].'&save='.$GLOBALS["buttonname"].'">';
			$newbutton .= '</a>';
		} else {
			$buttonfontsize = floor($EzAdmin_Style[$buttontype."FontSize"] / 5);
			//  Without gd, we simulate it by setting the template as an HTML table cell background
			//  Unfortunately, Netscape 4 can't handle background images in a cell, so we have to use the absolute fallback of non-graphics
//			if (($GLOBALS[$buttontype."Template"] == '') || ($GLOBALS["browser"] == "Netscape")) {
//				$newbutton = '<table width="100%" border="'.$EzAdmin_Style[$buttontype."Border"].'" bordercolor="'.$EzAdmin_Style[$buttontype."BorderColour"].'" bgcolor="'.$EzAdmin_Style[$buttontype."Colour"].'" cellpadding="2">';
//			} else {
				$newbutton = '<table ';
				if ($GLOBALS[$buttontype]["ImageWidth"] != '') { $newbutton .= 'width="'.$GLOBALS[$buttontype]["ImageWidth"].'" '; }
				if ($GLOBALS[$buttontype]["ImageHeight"] != '') { $newbutton .= 'height="'.$GLOBALS[$buttontype]["ImageHeight"].'" '; }
				if ($GLOBALS[$buttontype]["Template"] != '') { $newbutton .= 'background="'.$GLOBALS[$buttontype]["Template"].'" ';
				} else {
					if ($GLOBALS[$buttontype]["Colour"] != '') { $newbutton .= 'bgcolor="'.$GLOBALS[$buttontype]["Colour"].'" '; }
				}
				if ($GLOBALS[$buttontype]["Border"] != '') { $newbutton .= 'border="'.$GLOBALS[$buttontype]["Border"].'" '; }
//			}
			$newbutton .= 'cellpadding="0" cellspacing="0"><tr height="100%"';
			$newbutton .= '><td nowrap ';
			$newbutton .= 'align="'.$GLOBALS[$buttontype]["Align"].'" valign="'.$GLOBALS[$buttontype]["Valign"].'">';
			$newbutton .= $hlink;
			$newbutton .= $GLOBALS["ExtraMenuImage"];
			$newbutton .= '<b><font color="'.$EzAdmin_Style[$buttontype."TextColour"].'" face="'.$EzAdmin_Style[$buttontype."Font"].'" size="'.$buttonfontsize.'px">'.$title.'</font></b>';
			$newbutton .= '</a>';
			$newbutton .= '</td></tr></table>';
		}
	}
	return $newbutton;
} // function displaybutton()


function buttontestlanguage()
{
	//	Ugly but quick and efficient
	$charset = $GLOBALS["gsCharset"];
	$iso = 'iso-8859-1';
	$pos = strpos($charset, $iso);
//	if ($pos === False) { return false;
//	} else {
//		$pos += strlen($iso);
//		if ($pos >= strlen($charset)) { return True;
//		} else {
//			$poschar = $charset{$pos};
//			if (is_integer($poschar)) { return False;
//			} else { return True; }
//		}
//	}	
	return True;
} // function buttontestlanguage()


function TestCache($type,$name,$lang,$extension)
{
	global $EzAdmin_Style;

	$GLOBALS["buttonname"] = $GLOBALS["rootdp"].$GLOBALS["style_home"].'icache/';
	if ($GLOBALS["gsAdminStyle"] != '') { $GLOBALS["buttonname"] .= $GLOBALS["gsAdminStyle"]."_"; }
	$GLOBALS["buttonname"] .= $type.'_';
	if ($name != '') { $GLOBALS["buttonname"] .= $name."_"; }
	$GLOBALS["buttonname"] .= $lang;
	$GLOBALS["buttonimage"] = $GLOBALS["buttonname"].".".$extension;
	if (file_exists($GLOBALS["buttonimage"])) return True;
	return False;
}

?>
