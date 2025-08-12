<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
echo "<style type=\"text/css\">\n";
echo "<!--\n";
echo "body {\n";
echo "  font-family : $FontFace;\n";
echo "  font-size : $FontSize1;\n";
if(($colorscrollbars==1) && is_msie())
{
	if($sbfacecolor)
		echo "  scrollbar-face-color: $sbfacecolor;\n";
	if($sbhighlightcolor)
		echo "  scrollbar-highlight-color: $sbhighlightcolor;\n";
	if($sbshadowcolor)
		echo "  scrollbar-shadow-color: $sbshadowcolor;\n";
	if($sb3dlightcolor)
		echo "  scrollbar-3dlight-color: $sb3dlightcolor;\n";
	if($sbarrowcolor)
		echo "  scrollbar-arrow-color:  $sbarrowcolor;\n";
	if($sbtrackcolor)
		echo "  scrollbar-track-color: $sbtrackcolor;\n";
	if($sbdarkshadowcolor)
		echo "  scrollbar-darkshadow-color: $sbdarkshadowcolor;\n";
}
if($pagebgpic)
{
	echo "  background-image: url($pagebgpic);\n";
	if($pagebgrepeat)
		echo "  background-repeat: $pagebgrepeat;\n";
	if($pagebgposition)
		echo "  background-position: $pagebgposition;\n";
	if($pagebgattach)
		echo "  background-attachement: $pagebgattach;\n";
}
echo "}\n";
echo "a.subheadinglink:link{\n";
echo "  color: $SubheadingFontColor;\n";
echo "}\n";
echo "a.subheadinglink:visited{\n";
echo "  color: $SubheadingFontColor;\n";
echo "}\n";
echo "a.subheadinglink:active{\n";
echo "  color: $SubheadingFontColor;\n";
echo "}\n";
echo "a.groupheadinglink:link{\n";
echo "  color: $GroupFontColor;\n";
echo "}\n";
echo "a.groupheadinglink:visited{\n";
echo "  color: $GroupFontColor;\n";
echo "}\n";
echo "a.groupheadinglink:active{\n";
echo "  color: $GroupFontColor;\n";
echo "}\n";
echo "a.subcatlink:link{\n";
echo "  color: $subcatfontcolor;\n";
echo "}\n";
echo "a.subcatlink:visited{\n";
echo "  color: $subcatfontcolor;\n";
echo "}\n";
echo "a.subcatlink:active{\n";
echo "  color: $subcatfontcolor;\n";
echo "}\n";
echo "a.clistlink:link{\n";
echo "  color: $clist_linkcolor;\n";
echo "}\n";
echo "a.clistlink:visited{\n";
echo "  color: $clist_vlinkcolor;\n";
echo "}\n";
echo "a.clistlink:active{\n";
echo "  color: $clist_alinkcolor;\n";
echo "}\n";
echo ".contentcopy{\n";
if($cc_fontsize)
	echo "  font-size : $cc_fontsize;\n";
if($cc_font)
	echo "  font-family : $cc_font;\n";
if($cc_fontcolor)
	echo "  color : $cc_fontcolor;\n";
echo "}\n";
echo "select.jumpbox{\n";
echo "  font-size: $jumpboxfontsize;\n";
echo "}\n";
echo "a.pagenav{\n";
echo "  font-size: $pagenavfontsize;\n";
echo "}\n";
echo "-->\n";
echo "</style>\n";
?>