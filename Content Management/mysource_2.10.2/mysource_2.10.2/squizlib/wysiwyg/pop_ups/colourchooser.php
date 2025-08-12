<?  ##############################################
   ### SQUIZLIB ------------------------------###
  ##- Bodycopy Editor ---- PHP4 --------------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## This file is subject to version 1.0 of the
## MySource License, that is bundled with
## this package in the file LICENSE, and is
## available at through the world-wide-web at
## http://mysource.squiz.net/
## If you did not receive a copy of the MySource
## license and are unable to obtain it through
## the world-wide-web, please contact us at
## mysource@squiz.net so we can mail you a copy
## immediately.
##
## $Source: /home/cvsroot/squizlib/wysiwyg/pop_ups/colourchooser.php,v $
## $Revision: 1.1 $
## $Author: blair $
## $Date: 2002/03/27 08:42:33 $
#######################################################################


  ####################################################################################
 # NOTE: this is the original licence, but this page has been specialised           #
#        to work in the bodycopy and may not work without modification elsewhere   #
###################################################################################

#################################################################################
##
## HTML Text Editing Component for hosting in Web Pages
## Copyright (C) 2001  Ramesys (Contracting Services) Limited
## 
## This library is free software; you can redistribute it and/or
## modify it under the terms of the GNU Lesser General Public
## License as published by the Free Software Foundation; either
## version 2.1 of the License, or (at your option) any later version.
##
## This library is distributed in the hope that it will be useful,
## but WITHOUT ANY WARRANTY; without even the implied warranty of
## MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
## Lesser General Public License for more details.
##
## You should have received a copy of the GNU LesserGeneral Public License
## along with this program; if not a copy can be obtained from 
##
##    http://www.gnu.org/copyleft/lesser.html
##
## or by writing to:
##
##    Free Software Foundation, Inc.
##    59 Temple Place - Suite 330,
##    Boston,
##    MA  02111-1307,
##    USA.
## 
## Original Developer:
##
##	Austin David France
##	Ramesys (Contracting Services) Limited
##	Mentor House
##	Ainsworth Street
##	Blackburn
##	Lancashire
##	BB1 6AY
##	United Kingdom
##  email: Austin.France@Ramesys.com
##
## Home Page:    http://richtext.sourceforge.net/
## Support:      http://richtext.sourceforge.net/
## 
#################################################################################

$blank_img = '<img src="../images/blank.gif" width="12" height="12">';
?>
<html>
<head>
<meta name=vs_targetSchema content="HTML 4.0">
<meta name="GENERATOR" content="Microsoft Visual Studio 7.0">
<style type="text/css">
	TABLE.colorChooser { 
		background-color: buttonface; 
		border: 0; 
	}
	.colorChooser TD { 
		border: 2px inset buttonface; 
		cursor: hand; 
	}
	.colorChooserLabel TD { 
		width: 100%; 
		border: 0;
		font-family: "MS Sans Serif"; 
		font-size: xx-small; 
		vertical-align: middle;
	}
	TD.colorNone { 
		font-family: "MS Sans Serif"; 
		font-size: xx-small; 
		vertical-align: middle; 
		text-align: center; 
		border: 2px outset buttonface!important; 
	}
</style>
</head>

<body topmargin="0" leftmargin="0" style="border: 0; margin: 0;">
<script language="JavaScript">
var public_description = new ColorMenu();

function ColorMenu() {
}

function hover(on) {
	var el = window.event.srcElement;
	if (el && el.nodeName == "IMG") {
		if (on) {
			el.parentElement.style.border = '2px solid black';
		} else {
			el.parentElement.style.border = '2px inset buttonface';
		}
	}
}
function choose(on) {
	var el = window.event.srcElement;
	if (el && el.nodeName == "IMG") {
		window.external.raiseEvent("onselect", el.parentElement.title);
	}
	if (el && el.nodeName == "TD") {
		window.external.raiseEvent("onselect", null);
	}
}
</script>
<table class="colorChooser" cellpadding="0" cellspacing="2" onmouseover="hover(true)" onmouseout="hover(false)" onclick="choose()">
<tr class="colorChooserLabel"><td colspan="16">Standard Colors</td></tr>
<tr>
<td bgcolor="Green"		title="Green"	><?=$blank_img?></td>
<td bgcolor="Lime"		title="Lime"	><?=$blank_img?></td>
<td bgcolor="Teal"		title="Teal"	><?=$blank_img?></td>
<td bgcolor="Aqua"		title="Aqua"	><?=$blank_img?></td>
<td bgcolor="Navy"		title="Navy"	><?=$blank_img?></td>
<td bgcolor="Blue"		title="Blue"	><?=$blank_img?></td>
<td bgcolor="Purple"	title="Purple"	><?=$blank_img?></td>
<td bgcolor="Fuchsia"	title="Fuchsia"	><?=$blank_img?></td>
<td bgcolor="Maroon"	title="Maroon"	><?=$blank_img?></td>
<td bgcolor="Red"		title="Red"		><?=$blank_img?></td>
<td bgcolor="Olive"		title="Olive"	><?=$blank_img?></td>
<td bgcolor="Yellow"	title="Yellow"	><?=$blank_img?></td>
<td bgcolor="White"		title="White"	><?=$blank_img?></td>
<td bgcolor="Silver"	title="Silver"	><?=$blank_img?></td>
<td bgcolor="Gray"		title="Gray"	><?=$blank_img?></td>
<td bgcolor="Black"		title="Black"	><?=$blank_img?></td>
</tr>
<tr class="colorChooserLabel"><td colspan="16">Gray Scale</td></tr>
<tr>
<td bgcolor="#101010"><?=$blank_img?></td>
<td bgcolor="#202020"><?=$blank_img?></td>
<td bgcolor="#303030"><?=$blank_img?></td>
<td bgcolor="#404040"><?=$blank_img?></td>
<td bgcolor="#505050"><?=$blank_img?></td>
<td bgcolor="#606060"><?=$blank_img?></td>
<td bgcolor="#707070"><?=$blank_img?></td>
<td bgcolor="#808080"><?=$blank_img?></td>
<td bgcolor="#909090"><?=$blank_img?></td>
<td bgcolor="#A0A0A0"><?=$blank_img?></td>
<td bgcolor="#B0B0B0"><?=$blank_img?></td>
<td bgcolor="#C0C0C0"><?=$blank_img?></td>
<td bgcolor="#D0D0D0"><?=$blank_img?></td>
<td bgcolor="#E0E0E0"><?=$blank_img?></td>
<td bgcolor="#F0F0F0"><?=$blank_img?></td>
</tr>
<tr class="colorChooserLabel"><td colspan="16">Additional</td></tr>
<tr>
<td bgcolor="DarkOliveGreen"	title="DarkOliveGreen"	><?=$blank_img?></td>
<td bgcolor="DarkGreen"			title="DarkGreen"		><?=$blank_img?></td>
<td bgcolor="DarkSlateGray"		title="DarkSlateGray"	><?=$blank_img?></td>
<td bgcolor="SlateGray"			title="SlateGray"		><?=$blank_img?></td>
<td bgcolor="DarkBlue"			title="DarkBlue"		><?=$blank_img?></td>
<td bgcolor="MidnightBlue"		title="MidnightBlue"	><?=$blank_img?></td>
<td bgcolor="Indigo"			title="Indigo"			><?=$blank_img?></td>
<td bgcolor="DarkMagenta"		title="DarkMagenta"		><?=$blank_img?></td>
<td bgcolor="Brown"				title="Brown"			><?=$blank_img?></td>
<td bgcolor="DarkRed"			title="DarkRed"			><?=$blank_img?></td>
<td bgcolor="Sienna"			title="Sienna"			><?=$blank_img?></td>
<td bgcolor="SaddleBrown"		title="SaddleBrown"		><?=$blank_img?></td>
<td bgcolor="DarkGoldenrod"		title="DarkGoldenrod"	><?=$blank_img?></td>
<td bgcolor="Beige"				title="Beige"			><?=$blank_img?></td>
<td bgcolor="Honeydew"			title="Honeydew"		><?=$blank_img?></td>
<td bgcolor="DimGray"			title="DimGray"			><?=$blank_img?></td>
</tr>
<tr>
<td bgcolor="OliveDrab"				title="OliveDrab"			><?=$blank_img?></td>
<td bgcolor="ForestGreen"			title="ForestGreen"			><?=$blank_img?></td>
<td bgcolor="DarkCyan"				title="DarkCyan"			><?=$blank_img?></td>
<td bgcolor="LightSlateGray"		title="LightSlateGray"		><?=$blank_img?></td>
<td bgcolor="MediumBlue"			title="MediumBlue"			><?=$blank_img?></td>
<td bgcolor="DarkSlateBlue"			title="DarkSlateBlue"		><?=$blank_img?></td>
<td bgcolor="DarkViolet"			title="DarkViolet"			><?=$blank_img?></td>
<td bgcolor="MediumVioletRed"		title="MediumVioletRed"		><?=$blank_img?></td>
<td bgcolor="IndianRed"				title="IndianRed"			><?=$blank_img?></td>
<td bgcolor="Firebrick"				title="Firebrick"			><?=$blank_img?></td>
<td bgcolor="Chocolate"				title="Chocolate"			><?=$blank_img?></td>
<td bgcolor="Peru"					title="Peru"				><?=$blank_img?></td>
<td bgcolor="Goldenrod"				title="Goldenrod"			><?=$blank_img?></td>
<td bgcolor="LightGoldenrodYellow"	title="LightGoldenrodYellow"><?=$blank_img?></td>
<td bgcolor="MintCream"				title="MintCream"			><?=$blank_img?></td>
<td bgcolor="DarkGray"				title="DarkGray"			><?=$blank_img?></td>
</tr>
<tr>
<td bgcolor="YellowGreen"	title="YellowGreen"	><?=$blank_img?></td>
<td bgcolor="SeaGreen"		title="SeaGreen"	><?=$blank_img?></td>
<td bgcolor="CadetBlue"		title="CadetBlue"	><?=$blank_img?></td>
<td bgcolor="SteelBlue"		title="SteelBlue"	><?=$blank_img?></td>
<td bgcolor="RoyalBlue"		title="RoyalBlue"	><?=$blank_img?></td>
<td bgcolor="BlueViolet"	title="BlueViolet"	><?=$blank_img?></td>
<td bgcolor="DarkOrchid"	title="DarkOrchid"	><?=$blank_img?></td>
<td bgcolor="DeepPink"		title="DeepPink"	><?=$blank_img?></td>
<td bgcolor="RosyBrown"		title="RosyBrown"	><?=$blank_img?></td>
<td bgcolor="Crimson"		title="Crimson"		><?=$blank_img?></td>
<td bgcolor="DarkOrange"	title="DarkOrange"	><?=$blank_img?></td>
<td bgcolor="Burlywood"		title="Burlywood"	><?=$blank_img?></td>
<td bgcolor="DarkKhaki"		title="DarkKhaki"	><?=$blank_img?></td>
<td bgcolor="LightYellow"	title="LightYellow"	><?=$blank_img?></td>
<td bgcolor="Azure"			title="Azure"		><?=$blank_img?></td>
<td bgcolor="LightGrey"		title="LightGrey"	><?=$blank_img?></td>
</tr>
<tr>
<td bgcolor="LawnGreen"			title="LawnGreen"		><?=$blank_img?></td>
<td bgcolor="MediumSeaGreen"	title="MediumSeaGreen"	><?=$blank_img?></td>
<td bgcolor="LightSeaGreen"		title="LightSeaGreen"	><?=$blank_img?></td>
<td bgcolor="DeepSkyBlue"		title="DeepSkyBlue"		><?=$blank_img?></td>
<td bgcolor="DodgerBlue"		title="DodgerBlue"		><?=$blank_img?></td>
<td bgcolor="SlateBlue"			title="SlateBlue"		><?=$blank_img?></td>
<td bgcolor="MediumOrchid"		title="MediumOrchid"	><?=$blank_img?></td>
<td bgcolor="PaleVioletRed"		title="PaleVioletRed"	><?=$blank_img?></td>
<td bgcolor="Salmon"			title="Salmon"			><?=$blank_img?></td>
<td bgcolor="OrangeRed"			title="OrangeRed"		><?=$blank_img?></td>
<td bgcolor="SandyBrown"		title="SandyBrown"		><?=$blank_img?></td>
<td bgcolor="Tan"				title="Tan"				><?=$blank_img?></td>
<td bgcolor="Gold"				title="Gold"			><?=$blank_img?></td>
<td bgcolor="Ivory"				title="Ivory"			><?=$blank_img?></td>
<td bgcolor="GhostWhite"		title="GhostWhite"		><?=$blank_img?></td>
<td bgcolor="Gainsboro"			title="Gainsboro"		><?=$blank_img?></td>
</tr>
<tr>
<td bgcolor="Chartreuse"		title="Chartreuse"		><?=$blank_img?></td>
<td bgcolor="LimeGreen"			title="LimeGreen"		><?=$blank_img?></td>
<td bgcolor="MediumAquamarine"	title="MediumAquamarine"><?=$blank_img?></td>
<td bgcolor="DarkTurquoise"		title="DarkTurquoise"	><?=$blank_img?></td>
<td bgcolor="CornflowerBlue"	title="CornflowerBlue"	><?=$blank_img?></td>
<td bgcolor="MediumSlateBlue"	title="MediumSlateBlue"	><?=$blank_img?></td>
<td bgcolor="Orchid"			title="Orchid"			><?=$blank_img?></td>
<td bgcolor="HotPink"			title="HotPink"			><?=$blank_img?></td>
<td bgcolor="LightCoral"		title="LightCoral"		><?=$blank_img?></td>
<td bgcolor="Tomato"			title="Tomato"			><?=$blank_img?></td>
<td bgcolor="Orange"			title="Orange"			><?=$blank_img?></td>
<td bgcolor="Bisque"			title="Bisque"			><?=$blank_img?></td>
<td bgcolor="Khaki"				title="Khaki"			><?=$blank_img?></td>
<td bgcolor="Cornsilk"			title="Cornsilk"		><?=$blank_img?></td>
<td bgcolor="Linen"				title="Linen"			><?=$blank_img?></td>
<td bgcolor="WhiteSmoke"		title="WhiteSmoke"		><?=$blank_img?></td>
</tr>
<tr>
<td bgcolor="GreenYellow"		title="GreenYellow"		><?=$blank_img?></td>
<td bgcolor="DarkSeaGreen"		title="DarkSeaGreen"	><?=$blank_img?></td>
<td bgcolor="Turquoise"			title="Turquoise"		><?=$blank_img?></td>
<td bgcolor="MediumTurquoise"	title="MediumTurquoise"	><?=$blank_img?></td>
<td bgcolor="SkyBlue"			title="SkyBlue"			><?=$blank_img?></td>
<td bgcolor="MediumPurple"		title="MediumPurple"	><?=$blank_img?></td>
<td bgcolor="Violet"			title="Violet"			><?=$blank_img?></td>
<td bgcolor="LightPink"			title="LightPink"		><?=$blank_img?></td>
<td bgcolor="DarkSalmon"		title="DarkSalmon"		><?=$blank_img?></td>
<td bgcolor="Coral"				title="Coral"			><?=$blank_img?></td>
<td bgcolor="NavajoWhite"		title="NavajoWhite"		><?=$blank_img?></td>
<td bgcolor="BlanchedAlmond"	title="BlanchedAlmond"	><?=$blank_img?></td>
<td bgcolor="PaleGoldenrod"		title="PaleGoldenrod"	><?=$blank_img?></td>
<td bgcolor="Oldlace"			title="Oldlace"			><?=$blank_img?></td>
<td bgcolor="Seashell"			title="Seashell"		><?=$blank_img?></td>
<td bgcolor="GhostWhite"		title="GhostWhite"		><?=$blank_img?></td>
</tr>
<tr>
<td bgcolor="PaleGreen"			title="PaleGreen"		><?=$blank_img?></td>
<td bgcolor="SpringGreen"		title="SpringGreen"		><?=$blank_img?></td>
<td bgcolor="Aquamarine"		title="Aquamarine"		><?=$blank_img?></td>
<td bgcolor="PowderBlue"		title="PowderBlue"		><?=$blank_img?></td>
<td bgcolor="LightSkyBlue"		title="LightSkyBlue"	><?=$blank_img?></td>
<td bgcolor="LightSteelBlue"	title="LightSteelBlue"	><?=$blank_img?></td>
<td bgcolor="Plum"				title="Plum"			><?=$blank_img?></td>
<td bgcolor="Pink"				title="Pink"			><?=$blank_img?></td>
<td bgcolor="LightSalmon"		title="LightSalmon"		><?=$blank_img?></td>
<td bgcolor="Wheat"				title="Wheat"			><?=$blank_img?></td>
<td bgcolor="Moccasin"			title="Moccasin"		><?=$blank_img?></td>
<td bgcolor="AntiqueWhite"		title="AntiqueWhite"	><?=$blank_img?></td>
<td bgcolor="LemonChiffon"		title="LemonChiffon"	><?=$blank_img?></td>
<td bgcolor="FloralWhite"		title="FloralWhite"		><?=$blank_img?></td>
<td bgcolor="Snow"				title="Snow"			><?=$blank_img?></td>
<td bgcolor="AliceBlue"			title="AliceBlue"		><?=$blank_img?></td>
</tr>
<tr>
<td bgcolor="LightGreen"		title="LightGreen"			><?=$blank_img?></td>
<td bgcolor="MediumSpringGreen"	title="MediumSpringGreen"	><?=$blank_img?></td>
<td bgcolor="PaleTurquoise"		title="PaleTurquoise"		><?=$blank_img?></td>
<td bgcolor="LightCyan"			title="LightCyan"			><?=$blank_img?></td>
<td bgcolor="LightBlue"			title="LightBlue"			><?=$blank_img?></td>
<td bgcolor="Lavender"			title="Lavender"			><?=$blank_img?></td>
<td bgcolor="Thistle"			title="Thistle"				><?=$blank_img?></td>
<td bgcolor="MistyRose"			title="MistyRose"			><?=$blank_img?></td>
<td bgcolor="Peachpuff"			title="Peachpuff"			><?=$blank_img?></td>
<td bgcolor="PapayaWhip"		title="PapayaWhip"			><?=$blank_img?></td>
<td colspan="6" class="colorNone" title="None">None</td>
</tr>
</table>
</body>
</html>
