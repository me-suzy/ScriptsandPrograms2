<?php

/***************************************************************************

 colorpicker.php
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

includeLanguageFiles('admin');


frmColours();


function frmColours()
{
	global $_GET;

	admhdr();
	?>
		<title>Colour picker</title>
		<script language="javascript">
		<!-- Begin
			function ReturnColour(sColourName) {
				window.opener.document.MaintForm.<?php echo $_GET["control"]; ?>.value=sColourName;
				window.opener.document.MaintForm.<?php echo $_GET["control"]; ?>.style.backgroundColor=sColourName;
				window.close();
			}

			function ShowMouseOver(sColourName) {
				document.PickForm.SelColor.value=sColourName;
				return true;
			}

			function ShowMouseOut() {
				document.PickForm.SelColor.value='';
				return true;
			}
			//  End -->
		</script>

</head>
<body leftmargin=0 topmargin=0 marginwidth="0" marginheight="0" class="mainback">

<table border="0" width="100%" cellpadding="2" cellspacing="2">
	<tr><td align="center" valign="top">
			<table border="1" cellspacing="0" cellpadding="0">
				<?php
				for ($r=0; $r<=15; $r=$r+5) {
					for ($g=0; $g<=15; $g=$g+5) {
						echo ("<tr>\n");
						for ($b=0; $b<=15; $b++) {
							echo ("<td width=12 height=12 title=\"#".Hex($r).Hex($g).Hex($b)."\" bgcolor=#".Hex($r).Hex($g).Hex($b)."><a href=\"javascript:ReturnColour('#".Hex($r).Hex($g).Hex($b)."');\" onMouseOver=\"ShowMouseOver('#".Hex($r).Hex($g).Hex($b)."');\" onMouseOut=\"ShowMouseOut();\"><img src=\"".$GLOBALS["rootdp"].$GLOBALS["icon_home"]."blank.gif\" height=12 width=12 border=0></a></td>\n");
						}
					echo ("</tr>\n");
					}
				}
				?>
			</table>
		</td>
		<td align="center" valign="top">
			<table border="1" cellspacing="0" cellpadding="0">
				<?php
				$colArray = array ( "AliceBlue",		"AntiqueWhite",		"Aqua",					"Aquamarine",
									"Azure",			"Beige",			"Bisque",				"Black",
									"BlanchedAlmond",	"Blue",				"BlueViolet",			"Brown",
									"Burlywood",		"CadetBlue",		"Chartreuse",			"Chocolate",
									"Coral",			"Cornflower",		"Cornsilk",				"Crimson",
									"Cyan",				"DarkBlue",			"DarkCyan",				"DarkGoldenrod",
									"DarkGray",			"DarkGreen",		"DarkKhaki",			"DarkMagenta",
									"DarkOliveGreen",	"DarkOrange",		"DarkOrchid",			"DarkRed",
									"DarkSalmon",		"DarkSeaGreen",		"DarkSlateBlue",		"DarkSlateGray",
									"DarkTurquoise",	"DarkViolet",		"DeepPink",				"DeepSkyBlue",
									"DimGray",			"DodgerBlue",		"Firebrick",			"FloralWhite",
									"ForestGreen",		"Fuchia",			"Gainsboro",			"GhostWhite",
									"Gold",				"Goldenrod",		"Gray",					"Green",
									"GreenYellow",		"Honeydew",			"HotPink",				"IndianRed",
									"Indigo",			"Ivory",			"Khaki",				"Lavender",
									"LavenderBlush",	"LawnGreen",		"LemonChiffon",			"LightBlue",
									"LightCoral",		"LightCyan",		"LightGoldenrodYellow",	"LightGreen",
									"LightGrey",		"LightPink",		"LightSalmon",			"LightSeaGreen",
									"LightSkyBlue",		"LightSlateGray",	"LightSteelBlue",		"LightYellow",
									"Lime",				"LimeGreen",		"Linen",				"Magenta",
									"Maroon",			"MediumAquamarine",	"MediumBlue",			"MediumOrchid",
									"MediumPurple",		"MediumSeaGreen",	"MediumSlateBlue",		"MediumSpringGreen",
									"MediumTurquoise",	"MediumVioletRed",	"MidnightBlue",			"MintCream",
									"MistyRose",		"Moccasin",			"NavajoWhite",			"Navy",
									"OldLace",			"Olive",			"OliveDrab",			"Orange",
									"OrangeRed",		"Orchid",			"PaleGoldenrod",		"PaleGreen",
									"PaleTurquoise",	"PaleVioletRed",	"PapayaWhip",			"PeachPuff",
									"Peru",				"Pink",				"Plum",					"PowderBlue",
									"Purple",			"Red",				"RosyBrown",			"RoyalBlue",
									"SaddleBrown",		"Salmon",			"SandyBrown",			"SeaGreen",
									"Seashell",			"Sienna",			"Silver",				"SkyBlue",
									"SlateBlue",		"SlateGray",		"Snow",					"SpringGreen",
									"SteelBlue",		"Tan",				"Teal",					"Thistle",
									"Tomato",			"Turquoise",		"Violet",				"Wheat",
									"White",			"WhiteSmoke",		"Yellow",				"YellowGreen" );

				for ($l=0; $l<=8; $l++) {
					echo ("<tr>\n");
					for ($c=0; $c<=15; $c++) {
						if (isset($colArray[$l*16+$c])) {
							echo "<td width=12 height=12 title=\"".$colArray[$l*16+$c]."\" bgcolor=".$colArray[$l*16+$c]."><a onMouseOver=\"javascript:ShowMouseOver('".$colArray[$l*16+$c]."'); \" onMouseOut=\"javascript:ShowMouseOut();\" href=\"javascript:ReturnColour('".$colArray[$l*16+$c]."');\"><img src=\"".$GLOBALS["rootdp"].$GLOBALS["icon_home"]."blank.gif\" height=12 width=12 border=0></a></td>\n";
						} else {
							echo "<td><img src=\"".$GLOBALS["rootdp"].$GLOBALS["icon_home"]."blank.gif\" height=12 width=12 border=0></td>\n";
						}			
					}
					echo ("</tr>\n");
				}
				?>
			</table>
		</td>
	</tr>
	<tr><td colspan="2" valign="top">
			<form name="PickForm" enctype="multipart/form-data">
				<input id="SelColor" name="SelColor" type="text" size="20">
			</form>
		</td>
	</tr>
</table>

</body>
</html>

<?php
} // function frmColours()


function Hex($nDecimal)
{
	switch ($nDecimal) {
		case 10 : return "A0";
		case 11 : return "B0";
		case 12 : return "C0";
		case 13 : return "D0";
		case 14 : return "E0";
		case 15 : return "FF";
		default : return $nDecimal."0";
	}
} // function Hex()

?>
