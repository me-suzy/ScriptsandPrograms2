<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
echo "<style type=\"text/css\">\r\n";
echo "<!--\r\n";
if($tablealign<2)
{
	echo "table.sntable {\r\n";
	echo "  float: $tblalign;\r\n";
	echo "	clear:both;\r\n";
	echo "}\r\n";
}
if($nbindent)
{
	echo "table.newsbox{\r\n";
	echo "  padding-left : $nbindent;\r\n";
	echo "}\r\n";
	echo "td.newsbox{\r\n";
	echo "  padding-left : $nbindent;\r\n";
	echo "}\r\n";
}
if($evbindent)
{
	echo "table.eventbox{\r\n";
	echo "  padding-left : $evbindent;\r\n";
	echo "}\r\n";
	echo "td.eventbox{\r\n";
	echo "  padding-left : $evbindent;\r\n";
	echo "}\r\n";
}
echo "input.snewsbutton {\r\n";
if($snbutton_bgcolor)
	echo "  background : $snbutton_bgcolor;\r\n";
if($snbutton_fontcolor)
	echo "  color : $snbutton_fontcolor;\r\n";
if(!is_ns4())
{
	if($snbutton_borderstyle)
		echo "  border-style : $snbutton_borderstyle;\r\n";
	if($snbutton_borderwidth)
		echo "  border-width : $snbutton_borderwidth;\r\n";
	if($snbutton_bordercolor)
		echo "  border-color : $snbutton_bordercolor;\r\n";
}
if($snbutton_fontsize)
	echo "  font-size : $snbutton_fontsize;\r\n";
if($snbutton_font)
	echo "  font-family : $snbutton_font;\r\n";
if($snbutton_fontstyle)
	echo "  font-style : $snbutton_fontstyle;\r\n";
if($snbutton_fontweight)
	echo "  font-weight : $snbutton_fontweight;\r\n";
echo "}\r\n";
echo "select.snewsdropdown {\r\n";
if($snsel_bgcolor)
	echo "  background : $snsel_bgcolor;\r\n";
if($snsel_fontcolor)
	echo "  color : $snsel_fontcolor;\r\n";
if(!is_ns4())
{
	if($snsel_borderstyle)
		echo "  border-style : $snsel_borderstyle;\r\n";
	if($snsel_borderwidth)
		echo "  border-width : $snsel_borderwidth;\r\n";
	if($snsel_bordercolor)
		echo "  border-color : $snsel_bordercolor;\r\n";
}
if($snsel_fontsize)
	echo "  font-size : $snsel_fontsize;\r\n";
if($snsel_font)
	echo "  font-family : $snsel_font;\r\n";
if($snsel_fontstyle)
	echo "  font-style : $snsel_fontstyle;\r\n";
if($snsel_fontweight)
	echo "  font-weight : $snsel_fontweight;\r\n";
echo "}\r\n";
echo ".errorrow{\r\n";
echo "	background-color: #FF6347;\r\n";
echo "}\r\n";
echo "table.attachbox{\r\n";
echo "	padding-bottom: 2px;\r\n";
echo "	padding-top: 2px;\r\n";
echo "}\r\n";
echo "-->\r\n";
echo "</style>\r\n";
?>