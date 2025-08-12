<?php

/***************************************************************************

 style.php
 ----------
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

?>
<style type="text/css">

A
{
	FONT-SIZE: <?php echo $GLOBALS["gsFontSize1"]; ?>;<?php
	if ($GLOBALS["color_ahref"] != "") { ?>
		COLOR: <?php if (is_numeric($GLOBALS["color_ahref"])) { echo '#'.$GLOBALS["color_ahref"]; } else { echo $GLOBALS["color_ahref"]; } ?>;<?php
	} ?>
	FONT-FAMILY: <?php echo $GLOBALS["gsFont1"]; ?>;
	TEXT-DECORATION: <?php echo $GLOBALS["gsFontStyle1"]; ?>;
}
A:visited
{
	FONT-SIZE: <?php echo $GLOBALS["gsFontSize1"]; ?>;<?php
	if ($GLOBALS["color_ahref_visited"] != "") { ?>
		COLOR: <?php if (is_numeric($GLOBALS["color_ahref_visited"])) { echo '#'.$GLOBALS["color_ahref_visited"]; } else { echo $GLOBALS["color_ahref_visited"]; } ?>;<?php
	} ?>
	FONT-FAMILY: <?php echo $GLOBALS["gsFont1"]; ?>;
	TEXT-DECORATION: <?php echo $GLOBALS["gsFontStyle1"]; ?>;
}
A:hover
{
	FONT-SIZE: <?php echo $GLOBALS["gsFontSize1"]; ?>;<?php
	if ($GLOBALS["color_ahref_hover"] != "") { ?>
		COLOR: <?php if (is_numeric($GLOBALS["color_ahref_hover"])) { echo '#'.$GLOBALS["color_ahref_hover"]; } else { echo $GLOBALS["color_ahref_hover"]; } ?>;<?php
	} ?>
	FONT-FAMILY: <?php echo $GLOBALS["gsFont1"]; ?>;
	TEXT-DECORATION: <?php echo $GLOBALS["gsFontStyle1"]; ?>;
}

A.small
{	<?php
	if ($GLOBALS["gsSmallFontSize"] != "") { ?>
		FONT-SIZE: <?php echo $GLOBALS["gsSmallFontSize"]; ?>; <?php
	} else { ?>
		FONT-SIZE: 10px;<?php
	}
	if ($GLOBALS["color_ahref_small"] != "") { ?>
		COLOR: <?php if (is_numeric($GLOBALS["color_ahref_small"])) { echo '#'.$GLOBALS["color_ahref_small"]; } else { echo $GLOBALS["color_ahref_small"]; } ?>;<?php
	} ?>
	FONT-FAMILY: <?php echo $GLOBALS["gsFont1"]; ?>;
	TEXT-DECORATION: <?php echo $GLOBALS["gsFontStyle1"]; ?>;
}
A.small:visited
{	<?php
	if ($GLOBALS["gsSmallFontSize"] != "") { ?>
		FONT-SIZE: <?php echo $GLOBALS["gsSmallFontSize"]; ?>;<?php
	} else { ?>
		FONT-SIZE: 10px;<?php
	}
	if ($GLOBALS["color_ahref_small_visited"] != "") { ?>
		COLOR: <?php if (is_numeric($GLOBALS["color_ahref_small_visited"])) { echo '#'.$GLOBALS["color_ahref_small_visited"]; } else { echo $GLOBALS["color_ahref_small_visited"]; } ?>;<?php
	} ?>
	FONT-FAMILY: <?php echo $GLOBALS["gsFont1"]; ?>;
	TEXT-DECORATION: <?php echo $GLOBALS["gsFontStyle1"]; ?>;
}
A.small:hover
{	<?php
	if ($GLOBALS["gsSmallFontSize"] != "") { ?>
		FONT-SIZE: <?php echo $GLOBALS["gsSmallFontSize"]; ?>;<?php
	} else { ?>
		FONT-SIZE: 10px;<?php
	}
	if ($GLOBALS["color_ahref_small_hover"] != "") { ?>
		COLOR: <?php if (is_numeric($GLOBALS["color_ahref_small_hover"])) { echo '#'.$GLOBALS["color_ahref_small_hover"]; } else { echo $GLOBALS["color_ahref_small_hover"]; } ?>;<?php
	} ?>
	FONT-FAMILY: <?php echo $GLOBALS["gsFont1"]; ?>;
	TEXT-DECORATION: <?php echo $GLOBALS["gsFontStyle1"]; ?>;
}

A.menulink
{
	FONT-SIZE: <?php echo $GLOBALS["gsFontSize3"]; ?>;<?php
	if ($GLOBALS["menu_color_ahref"] != "") { ?>
		COLOR: <?php if (is_numeric($GLOBALS["menu_color_ahref"])) { echo '#'.$GLOBALS["menu_color_ahref"]; } else { echo $GLOBALS["menu_color_ahref"]; } ?>;<?php
	} ?>
	FONT-FAMILY: <?php echo $GLOBALS["gsFont1"]; ?>;
	TEXT-DECORATION: <?php echo $GLOBALS["gsFontStyle3"]; ?>;
}
A.menulink:visited
{
	FONT-SIZE: <?php echo $GLOBALS["gsFontSize3"]; ?>;<?php
	if ($GLOBALS["menu_color_ahref_visited"] != "") { ?>
		COLOR: <?php if (is_numeric($GLOBALS["menu_color_ahref_visited"])) { echo '#'.$GLOBALS["menu_color_ahref_visited"]; } else { echo $GLOBALS["menu_color_ahref_visited"]; } ?>;<?php
	} ?>
	FONT-FAMILY: <?php echo $GLOBALS["gsFont1"]; ?>;
	TEXT-DECORATION: <?php echo $GLOBALS["gsFontStyle3"]; ?>;
}
A.menulink:hover
{
	FONT-SIZE: <?php echo $GLOBALS["gsFontSize3"]; ?>;<?php
	if ($GLOBALS["menu_color_ahref_hover"] != "") { ?>
		COLOR: <?php if (is_numeric($GLOBALS["menu_color_ahref_hover"])) { echo '#'.$GLOBALS["menu_color_ahref_hover"]; } else { echo $GLOBALS["menu_color_ahref_hover"]; } ?>;<?php
	} ?>
	FONT-FAMILY: <?php echo $GLOBALS["gsFont1"]; ?>;
	TEXT-DECORATION: <?php echo $GLOBALS["gsFontStyle3"]; ?>;
}

A.topmenulink
{
	FONT-SIZE: <?php echo $GLOBALS["gsTopMenuFontSize"]; ?>;<?php
	if ($GLOBALS["topmenu_color_ahref"] != "") { ?>
		COLOR: <?php if (is_numeric($GLOBALS["topmenu_color_ahref"])) { echo '#'.$GLOBALS["topmenu_color_ahref"]; } else { echo $GLOBALS["topmenu_color_ahref"]; } ?>;<?php
	} ?>
	FONT-FAMILY: <?php echo $GLOBALS["gsFont1"]; ?>;
	TEXT-DECORATION: <?php echo $GLOBALS["gsTopMenuFontStyle"]; ?>;
}
A.topmenulink:visited
{
	FONT-SIZE: <?php echo $GLOBALS["gsTopMenuFontSize"]; ?>;<?php
	if ($GLOBALS["topmenu_color_ahref_visited"] != "") { ?>
		COLOR: <?php if (is_numeric($GLOBALS["topmenu_color_ahref_visited"])) { echo '#'.$GLOBALS["topmenu_color_ahref_visited"]; } else { echo $GLOBALS["topmenu_color_ahref_visited"]; } ?>;<?php
	} ?>
	FONT-FAMILY: <?php echo $GLOBALS["gsFont1"]; ?>;
	TEXT-DECORATION: <?php echo $GLOBALS["gsTopMenuFontStyle"]; ?>;
}
A.topmenulink:hover
{
	FONT-SIZE: <?php echo $GLOBALS["gsTopMenuFontSize"]; ?>;<?php
	if ($GLOBALS["topmenu_color_ahref_hover"] != "") { ?>
		COLOR: <?php if (is_numeric($GLOBALS["topmenu_color_ahref_hover"])) { echo '#'.$GLOBALS["topmenu_color_ahref_hover"]; } else { echo $GLOBALS["topmenu_color_ahref_hover"]; } ?>;<?php
	} ?>
	FONT-FAMILY: <?php echo $GLOBALS["gsFont1"]; ?>;
	TEXT-DECORATION: <?php echo $GLOBALS["gsTopMenuFontStyle"]; ?>;
}

A.rightcol
{
	FONT-SIZE: <?php echo $GLOBALS["gsRColFontSize"]; ?>;<?php
	if ($GLOBALS["rcol_color_ahref"] != "") { ?>
		COLOR: <?php if (is_numeric($GLOBALS["rcol_color_ahref"])) { echo '#'.$GLOBALS["rcol_color_ahref"]; } else { echo $GLOBALS["rcol_color_ahref"]; } ?>;<?php
	} ?>
	FONT-FAMILY: <?php echo $GLOBALS["gsFont1"]; ?>;
	TEXT-DECORATION: <?php echo $GLOBALS["gsRColFontStyle"]; ?>;
}
A.rightcol:visited
{
	FONT-SIZE: <?php echo $GLOBALS["gsRColFontSize"]; ?>;<?php
	if ($GLOBALS["rcol_color_ahref_visited"] != "") { ?>
		COLOR: <?php if (is_numeric($GLOBALS["rcol_color_ahref_visited"])) { echo '#'.$GLOBALS["rcol_color_ahref_visited"]; } else { echo $GLOBALS["rcol_color_ahref_visited"]; } ?>;<?php
	} ?>
	FONT-FAMILY: <?php echo $GLOBALS["gsFont1"]; ?>;
	TEXT-DECORATION: <?php echo $GLOBALS["gsRColFontStyle"]; ?>;
}
A.rightcol:hover
{
	FONT-SIZE: <?php echo $GLOBALS["gsRColFontSize"]; ?>;<?php
	if ($GLOBALS["rcol_color_ahref_hover"] != "") { ?>
		COLOR: <?php if (is_numeric($GLOBALS["rcol_color_ahref_hover"])) { echo '#'.$GLOBALS["rcol_color_ahref_hover"]; } else { echo $GLOBALS["rcol_color_ahref_hover"]; } ?>;<?php
	} ?>
	FONT-FAMILY: <?php echo $GLOBALS["gsFont1"]; ?>;
	TEXT-DECORATION: <?php echo $GLOBALS["gsRColFontStyle"]; ?>;
}

A.heading
{
	FONT-SIZE: <?php echo $GLOBALS["gsFontSize2"]; ?>;<?php
	if ($GLOBALS["color_header"] != "") { ?>
		COLOR: <?php if (is_numeric($GLOBALS["color_header"])) { echo '#'.$GLOBALS["color_header"]; } else { echo $GLOBALS["color_header"]; } ?>;<?php
	} ?>
	FONT-FAMILY: <?php echo $GLOBALS["gsFont1"]; ?>;
}
A.heading:visited
{
	FONT-SIZE: <?php echo $GLOBALS["gsFontSize2"]; ?>;<?php
	if ($GLOBALS["color_header"] != "") { ?>
		COLOR: <?php if (is_numeric($GLOBALS["color_header"])) { echo '#'.$GLOBALS["color_header"]; } else { echo $GLOBALS["color_header"]; } ?>;<?php
	} ?>
	FONT-FAMILY: <?php echo $GLOBALS["gsFont1"]; ?>;
}
A.heading:hover
{
	FONT-SIZE: <?php echo $GLOBALS["gsFontSize2"]; ?>;<?php
	if ($GLOBALS["color_ahref_hover"] != "") { ?>
		COLOR: <?php if (is_numeric($GLOBALS["color_ahref_hover"])) { echo '#'.$GLOBALS["color_ahref_hover"]; } else { echo $GLOBALS["color_ahref_hover"]; } ?>;<?php
	} ?>
	FONT-FAMILY: <?php echo $GLOBALS["gsFont1"]; ?>;
}

TD
{
	BACKGROUND-IMAGE: NONE;
	FONT-SIZE: <?php echo $GLOBALS["gsFontSize1"]; ?>;<?php
	if ($GLOBALS["color_td"] != "") { ?>
		COLOR: <?php if (is_numeric($GLOBALS["color_td"])) { echo '#'.$GLOBALS["color_td"]; } else { echo $GLOBALS["color_td"]; } ?>;<?php
	} ?>
	FONT-FAMILY: <?php echo $GLOBALS["gsFont1"]; ?>;
}

.header
{
	FONT-SIZE: <?php echo $GLOBALS["gsFontSize2"]; ?>;<?php
	if ($GLOBALS["color_header"] != "") { ?>
		COLOR: <?php if (is_numeric($GLOBALS["color_header"])) { echo '#'.$GLOBALS["color_header"]; } else { echo $GLOBALS["color_header"]; } ?>;<?php
	}
	if ($GLOBALS["gsHeaderBg"] != "") { ?>
		BACKGROUND-IMAGE: URL(<?php echo $GLOBALS["rootdp"].$GLOBALS["image_home"].$GLOBALS["gsHeaderBg"]; ?>);
		BACKGROUND-REPEAT: <?php if($GLOBALS["gbHeaderBgRep"] != "Y") echo "NO-" ?>REPEAT;<?php
	} ?>
	FONT-FAMILY: <?php echo $GLOBALS["gsFont1"]; ?>;
}
.rcolheader
{
	FONT-SIZE: <?php echo $GLOBALS["gsRColHeaderFontSize"]; ?>;<?php
	if ($GLOBALS["rcol_color_header"] != "") { ?>
		COLOR: <?php if (is_numeric($GLOBALS["rcol_color_header"])) { echo '#'.$GLOBALS["rcol_color_header"]; } else { echo $GLOBALS["rcol_color_header"]; } ?>;<?php
	}
	if ($GLOBALS["gsHeaderBg"] != "") { ?>
		BACKGROUND-IMAGE: URL(<?php echo $GLOBALS["rootdp"].$GLOBALS["image_home"].$GLOBALS["gsHeaderBg"]; ?>);
		BACKGROUND-REPEAT: <?php if($GLOBALS["gbHeaderBgRep"] != "Y") echo "NO-" ?>REPEAT;<?php
	} ?>
	FONT-FAMILY: <?php echo $GLOBALS["gsFont1"]; ?>;
}
.teaserheader
{
	FONT-SIZE: <?php echo $GLOBALS["gsFontSize2"]; ?>;<?php
	if ($GLOBALS["gsColor_tsrheader"] != "") { ?>
		COLOR: <?php if (is_numeric($GLOBALS["gsColor_tsrheader"])) { echo '#'.$GLOBALS["gsColor_tsrheader"]; } else { echo $GLOBALS["gsColor_tsrheader"]; } ?>;<?php
	}
	if ($GLOBALS["gsHeaderBg"] != "") { ?>
		BACKGROUND-IMAGE: URL(<?php echo $GLOBALS["rootdp"].$GLOBALS["image_home"].$GLOBALS["gsHeaderBg"]; ?>);
		BACKGROUND-REPEAT: <?php if($GLOBALS["gbHeaderBgRep"] != "Y") echo "NO-" ?>REPEAT;<?php
	} ?>
	FONT-FAMILY: <?php echo $GLOBALS["gsFont1"]; ?>;
}
.tablecontent
{
	FONT-SIZE: <?php echo $GLOBALS["gsFontSize1"]; ?>;<?php
	if ($GLOBALS["color_td"] != "") { ?>
		COLOR: <?php if (is_numeric($GLOBALS["color_td"])) { echo '#'.$GLOBALS["color_td"]; } else { echo $GLOBALS["color_td"]; } ?>;<?php
	}
	if ($GLOBALS["bgcolor_cnttbl"] != "") { ?>
		BACKGROUND-COLOR: <?php if (is_numeric($GLOBALS["bgcolor_cnttbl"])) { echo '#'.$GLOBALS["bgcolor_cnttbl"]; } else { echo $GLOBALS["bgcolor_cnttbl"]; } ?>;<?php
	} ?>
	FONT-FAMILY: <?php echo $GLOBALS["gsFont1"]; ?>;
}
.tablecontentfooter
{ <?php
	if ($GLOBALS["gsSmallFontSize"] != "") { ?>
		FONT-SIZE: <?php echo $GLOBALS["gsSmallFontSize"]; ?>; <?php
	} else { ?>
		FONT-SIZE: 10px;<?php
	}
	if ($GLOBALS["color_td"] != "") { ?>
		COLOR: <?php if (is_numeric($GLOBALS["color_td"])) { echo '#'.$GLOBALS["color_td"]; } else { echo $GLOBALS["color_td"]; } ?>;<?php
	}
	if ($GLOBALS["bgcolor_cnttbl"] != "") { ?>
		BACKGROUND-COLOR: <?php if (is_numeric($GLOBALS["bgcolor_cnttbl"])) { echo '#'.$GLOBALS["bgcolor_cnttbl"]; } else { echo $GLOBALS["bgcolor_cnttbl"]; } ?>;<?php
	} ?>
	FONT-FAMILY: <?php echo $GLOBALS["gsFont1"]; ?>;
}
.teasercontent
{
	FONT-SIZE: <?php echo $GLOBALS["gsFontSize1"]; ?>;<?php
	if ($GLOBALS["gsColor_tsrtd"] != "") { ?>
		COLOR: <?php if (is_numeric($GLOBALS["gsColor_tsrtd"])) { echo '#'.$GLOBALS["gsColor_tsrtd"]; } else { echo $GLOBALS["gsColor_tsrtd"]; } ?>;<?php
	}
	if ($GLOBALS["gsBgcolor_tsrtbl"] != "") { ?>
		BACKGROUND-COLOR: <?php if (is_numeric($GLOBALS["gsBgcolor_tsrtbl"])) { echo '#'.$GLOBALS["gsBgcolor_tsrtbl"]; } else { echo $GLOBALS["gsBgcolor_tsrtbl"]; } ?>;<?php
	} ?>
	FONT-FAMILY: <?php echo $GLOBALS["gsFont1"]; ?>;
}
.rcolcontent
{
	FONT-SIZE: <?php echo $GLOBALS["gsRColFontSize"]; ?>;<?php
	if ($GLOBALS["rcol_color_td"] != "") { ?>
		COLOR: <?php if (is_numeric($GLOBALS["rcol_color_td"])) { echo '#'.$GLOBALS["rcol_color_td"]; } else { echo $GLOBALS["rcol_color_td"]; } ?>;<?php
	}
	if ($GLOBALS["rcol_bgcolor_cnttbl"] != "") { ?>
		BACKGROUND-COLOR: <?php if (is_numeric($GLOBALS["rcol_bgcolor_cnttbl"])) { echo '#'.$GLOBALS["rcol_bgcolor_cnttbl"]; } else { echo $GLOBALS["rcol_bgcolor_cnttbl"]; } ?>;<?php
	} ?>
	FONT-FAMILY: <?php echo $GLOBALS["gsFont1"]; ?>;
}
.teasercontentfooter
{ <?php
	if ($GLOBALS["gsSmallFontSize"] != "") { ?>
		FONT-SIZE: <?php echo $GLOBALS["gsSmallFontSize"]; ?>; <?php
	} else { ?>
		FONT-SIZE: 10px;<?php
	}
	if ($GLOBALS["gsColor_tsrtd"] != "") { ?>
		COLOR: <?php if (is_numeric($GLOBALS["gsColor_tsrtd"])) { echo '#'.$GLOBALS["gsColor_tsrtd"]; } else { echo $GLOBALS["gsColor_tsrtd"]; } ?>;<?php
	}
	if ($GLOBALS["gsBgcolor_tsrtbl"] != "") { ?>
		BACKGROUND-COLOR: <?php if (is_numeric($GLOBALS["gsBgcolor_tsrtbl"])) { echo '#'.$GLOBALS["gsBgcolor_tsrtbl"]; } else { echo $GLOBALS["gsBgcolor_tsrtbl"]; } ?>;<?php
	} ?>
	FONT-FAMILY: <?php echo $GLOBALS["gsFont1"]; ?>;
}
.menu
{
	BACKGROUND-IMAGE: NONE;
	FONT-SIZE: <?php echo $GLOBALS["gsFontSize1"]; ?>;
	COLOR: #FFFFFF;<?php
	if ($GLOBALS["bgcolor_menu"] != "") { ?>
		BACKGROUND-COLOR: <?php if (is_numeric($GLOBALS["bgcolor_menu"])) { echo '#'.$GLOBALS["bgcolor_menu"]; } else { echo $GLOBALS["bgcolor_menu"]; } ?>;<?php
	} ?>
	FONT-FAMILY: <?php echo $GLOBALS["gsFont1"]; ?>;
}
.topmenu
{
	BACKGROUND-IMAGE: NONE;
	FONT-SIZE: <?php echo $GLOBALS["gsFontSize1"]; ?>;
	COLOR: #FFFFFF;<?php
	if ($GLOBALS["bgcolor_topmenu"] != "") { ?>
		BACKGROUND-COLOR: <?php if (is_numeric($GLOBALS["bgcolor_topmenu"])) { echo '#'.$GLOBALS["bgcolor_topmenu"]; } else { echo $GLOBALS["bgcolor_topmenu"]; } ?>;<?php
	} ?>
	FONT-FAMILY: <?php echo $GLOBALS["gsFont1"]; ?>;
}
.headercontent
{
	FONT-SIZE: <?php echo $GLOBALS["gsFontSize1"]; ?>;
	COLOR: #FFFFFF;<?php
	if ($GLOBALS["bgcolor_headercnt"] != "") { ?>
		BACKGROUND-COLOR: <?php if (is_numeric($GLOBALS["bgcolor_headercnt"])) { echo '#'.$GLOBALS["bgcolor_headercnt"]; } else { echo $GLOBALS["bgcolor_headercnt"]; } ?>;<?php
	} ?>
	FONT-FAMILY: <?php echo $GLOBALS["gsFont1"]; ?>;
}
.rcolheadercontent
{
	FONT-SIZE: <?php echo $GLOBALS["gsRColFontSize"]; ?>;
	COLOR: #FFFFFF;<?php
	if ($GLOBALS["rcol_bgcolor_headercnt"] != "") { ?>
		BACKGROUND-COLOR: <?php if (is_numeric($GLOBALS["rcol_bgcolor_headercnt"])) { echo '#'.$GLOBALS["rcol_bgcolor_headercnt"]; } else { echo $GLOBALS["rcol_bgcolor_headercnt"]; } ?>;<?php
	} ?>
	FONT-FAMILY: <?php echo $GLOBALS["gsFont1"]; ?>;
}
.teaserheadercontent
{
	FONT-SIZE: <?php echo $GLOBALS["gsFontSize1"]; ?>;
	COLOR: #FFFFFF;<?php
	if ($GLOBALS["gsBgcolor_headertsr"] != "") { ?>
		BACKGROUND-COLOR: <?php if (is_numeric($GLOBALS["gsBgcolor_headertsr"])) { echo '#'.$GLOBALS["gsBgcolor_headertsr"]; } else { echo $GLOBALS["gsBgcolor_headertsr"]; } ?>;<?php
	} ?>
	FONT-FAMILY: <?php echo $GLOBALS["gsFont1"]; ?>;
}
.topback
{
	MARGIN: 0;
	FONT-SIZE: <?php echo $GLOBALS["gsFontSize1"]; ?>;
	COLOR: #FFFFFF;
	FONT-FAMILY: <?php echo $GLOBALS["gsFont1"]; ?>;
	MARGIN: 0;<?php
	if ($GLOBALS["gsTopBg"] != "") { ?>
		BACKGROUND-IMAGE: URL(<?php echo $GLOBALS["rootdp"].$GLOBALS["image_home"].$GLOBALS["gsTopBg"]; ?>);
		BACKGROUND-REPEAT: <?php if($GLOBALS["gbTopBgRep"] != "Y") echo "NO-" ?>REPEAT;<?php
		if($GLOBALS["gbTopBgFix"] == "Y") { echo 'BACKGROUND-ATTACHMENT: FIXED;'; }
	}
	if ($GLOBALS["bgcolor_main"] != "") { ?>
		BACKGROUND-COLOR: <?php if (is_numeric($GLOBALS["bgcolor_main"])) { echo '#'.$GLOBALS["bgcolor_main"]; } else { echo $GLOBALS["bgcolor_main"]; } ?>;<?php
	} ?>
}
.topmenuback
{
	MARGIN: 0;
	FONT-SIZE: <?php echo $GLOBALS["gsFontSize1"]; ?>;
	COLOR: #FFFFFF;
	FONT-FAMILY: <?php echo $GLOBALS["gsFont1"]; ?>;<?php
	if ($GLOBALS["gsTopMenuBg"] != "") { ?>
		BACKGROUND-IMAGE: URL(<?php echo $GLOBALS["rootdp"].$GLOBALS["image_home"].$GLOBALS["gsTopMenuBg"]; ?>);
		BACKGROUND-REPEAT: <?php if($GLOBALS["gbTopMenuBgRep"] != "Y") echo "NO-" ?>REPEAT;<?php
		if($GLOBALS["gbTopMenuBgFix"] == "Y") { echo 'BACKGROUND-ATTACHMENT: FIXED;'; }
	}
	if ($GLOBALS["bgcolor_topmenu"] != "") { ?>
		BACKGROUND-COLOR: <?php if (is_numeric($GLOBALS["bgcolor_topmenu"])) { echo '#'.$GLOBALS["bgcolor_topmenu"]; } else { echo $GLOBALS["bgcolor_topmenu"]; } ?>;<?php
	} ?>
}
.menuback
{
	MARGIN: 0;
	FONT-SIZE: <?php echo $GLOBALS["gsFontSize1"]; ?>;
	COLOR: #FFFFFF;
	FONT-FAMILY: <?php echo $GLOBALS["gsFont1"]; ?>;<?php
	if ($GLOBALS["gsMenuBg"] != "") { ?>
		BACKGROUND-IMAGE: URL(<?php echo $GLOBALS["rootdp"].$GLOBALS["image_home"].$GLOBALS["gsMenuBg"]; ?>);
		BACKGROUND-REPEAT: <?php if($GLOBALS["gbMenuBgRep"] != "Y") echo "NO-" ?>REPEAT;<?php
		if($GLOBALS["gbMenuBgFix"] == "Y") { echo 'BACKGROUND-ATTACHMENT: FIXED;'; }
	}
	if ($GLOBALS["bgcolor_menu"] != "") { ?>
		BACKGROUND-COLOR: <?php if (is_numeric($GLOBALS["bgcolor_menu"])) { echo '#'.$GLOBALS["bgcolor_menu"]; } else { echo $GLOBALS["bgcolor_menu"]; } ?>;<?php
	} ?>
}
.mainback
{
	MARGIN: 0;
	FONT-SIZE: <?php echo $GLOBALS["gsFontSize1"]; ?>;
	COLOR: #FFFFFF;
	FONT-FAMILY: <?php echo $GLOBALS["gsFont1"]; ?>;<?php
	if ($GLOBALS["gsMainBg"] != "") { ?>
		BACKGROUND-IMAGE: URL(<?php echo $GLOBALS["rootdp"].$GLOBALS["image_home"].$GLOBALS["gsMainBg"]; ?>);
		BACKGROUND-REPEAT: <?php if($GLOBALS["gbMainBgRep"] != "Y") echo "NO-" ?>REPEAT;<?php
		if($GLOBALS["gbMainBgFix"] == "Y") { echo 'BACKGROUND-ATTACHMENT: FIXED;'; }
	}
	if ($GLOBALS["bgcolor_main"] != "") { ?>
			BACKGROUND-COLOR: <?php if (is_numeric($GLOBALS["bgcolor_main"])) { echo '#'.$GLOBALS["bgcolor_main"]; } else { echo $GLOBALS["bgcolor_main"]; } ?>;<?php
	} ?>
}
.bottomback
{
	MARGIN: 0;
	FONT-SIZE: <?php echo $GLOBALS["gsFontSize1"]; ?>;
	COLOR: #FFFFFF;
	FONT-FAMILY: <?php echo $GLOBALS["gsFont1"]; ?>;
	MARGIN: 0;<?php
	if ($GLOBALS["gsFooterBg"] != "") { ?>
		BACKGROUND-IMAGE: URL(<?php echo $GLOBALS["rootdp"].$GLOBALS["image_home"].$GLOBALS["gsFooterBg"]; ?>);
		BACKGROUND-REPEAT: <?php if($GLOBALS["gbFooterBgRep"] != "Y") echo "NO-" ?>REPEAT;<?php
		if($GLOBALS["gbFooterBgFix"] == "Y") { echo 'BACKGROUND-ATTACHMENT: FIXED;'; }
	}
	if ($GLOBALS["bgcolor_footer"] != "") { ?>
		BACKGROUND-COLOR: <?php if (is_numeric($GLOBALS["bgcolor_footer"])) { echo '#'.$GLOBALS["bgcolor_footer"]; } else { echo $GLOBALS["bgcolor_footer"]; } ?>;<?php
	} ?>
}
.helptext
{
	FONT-SIZE: <?php echo $GLOBALS["gsHelptextFontSize"]; ?>;<?php
	if ($GLOBALS["gsHelptextColor"] != "") { ?>
		COLOR: <?php if (is_numeric($GLOBALS["gsHelptextColor"])) { echo '#'.$GLOBALS["gsHelptextColor"]; } else { echo $GLOBALS["gsHelptextColor"]; } ?>;<?php
	}
	?>
	FONT-FAMILY: <?php echo $GLOBALS["gsFont1"]; ?>;
}

.smallinput
{	<?php
	if ($GLOBALS["gsSmallFontSize"] != "") { ?>
		FONT-SIZE: <?php echo $GLOBALS["gsSmallFontSize"]; ?>;<?php
	} else { ?>
		FONT-SIZE: 9px;<?php
	}
	if ($GLOBALS["gsHelptextColor"] != "") { ?>
		COLOR: <?php if (is_numeric($GLOBALS["gsHelptextColor"])) { echo '#'.$GLOBALS["gsHelptextColor"]; } else { echo $GLOBALS["gsHelptextColor"]; } ?>;<?php
	} ?>
	FONT-FAMILY: <?php echo $GLOBALS["gsFont1"]; ?>;
	TEXT-DECORATION: None;
}

<?php if ($GLOBALS["gnImageColumnBreak"] != '') { ?>
.sep_column
{
	background-repeat : repeat-y;
	background : url(./<?php echo $GLOBALS["image_home"].$GLOBALS["gnImageColumnBreak"]; ?>);
}
<?php } ?>

-->
</style>
