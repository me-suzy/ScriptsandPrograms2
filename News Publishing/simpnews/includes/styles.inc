<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
echo "<style type=\"text/css\">\r\n";
echo "<!--\r\n";
if(($colorscrollbars==1) || ($pagebgpic))
{
	echo "body {\r\n";
	if(($colorscrollbars==1) && is_msie())
	{
		if($sbfacecolor)
			echo "  scrollbar-face-color: $sbfacecolor;\r\n";
		if($sbhighlightcolor)
			echo "  scrollbar-highlight-color: $sbhighlightcolor;\r\n";
		if($sbshadowcolor)
			echo "  scrollbar-shadow-color: $sbshadowcolor;\r\n";
		if($sb3dlightcolor)
			echo "  scrollbar-3dlight-color: $sb3dlightcolor;\r\n";
		if($sbarrowcolor)
			echo "  scrollbar-arrow-color:  $sbarrowcolor;\r\n";
		if($sbtrackcolor)
			echo "  scrollbar-track-color: $sbtrackcolor;\r\n";
		if($sbdarkshadowcolor)
			echo "  scrollbar-darkshadow-color: $sbdarkshadowcolor;\r\n";
	}
	if($pagebgpic)
		echo "  background-image: url($url_gfx/$pagebgpic);\r\n";
	echo "}\r\n";
}
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
if($morelinkfontsize)
{
	echo "a.morelink {\r\n";
	echo "  font-size : $morelinkfontsize;\r\n";
	echo "}\r\n";
}
if($morelinkcolor)
{
	echo "a.morelink:link {\r\n";
	echo "  color : $morelinkcolor;\r\n";
	echo "}\r\n";
}
if($morevlinkcolor)
{
	echo "a.morelink:visited {\r\n";
	echo "  color : $morevlinkcolor;\r\n";
	echo "}\r\n";
}
if($morealinkcolor)
{
	echo "a.morelink:activ {\r\n";
	echo "  color : $morealinkcolor;\r\n";
	echo "}\r\n";
}
if($catlinkcolor)
{
	echo "a.catlink:link {\r\n";
	echo "  color : $catlinkcolor;\r\n";
	echo "}\r\n";
}
if($catvlinkcolor)
{
	echo "a.catlink:visited {\r\n";
	echo "  color : $catvlinkcolor;\r\n";
	echo "}\r\n";
}
if($catalinkcolor)
{
	echo "a.catlink:activ {\r\n";
	echo "  color : $catalinkcolor;\r\n";
	echo "}\r\n";
}
if($linkcolor)
{
	echo "a:link {\r\n";
	echo "  color : $linkcolor;\r\n";
	echo "}\r\n";
}
if($vlinkcolor)
{
	echo "a:visited {\r\n";
	echo "  color : $vlinkcolor;\r\n";
	echo "}\r\n";
}
if($alinkcolor)
{
	echo "a:activ {\r\n";
	echo "  color : $alinkcolor;\r\n";
	echo "}\r\n";
}
if($commentlinkcolor)
{
	echo "a.commentlink:link {\r\n";
	echo "  color : $commentlinkcolor;\r\n";
	echo "}\r\n";
}
if($commentvlinkcolor)
{
	echo "a.commentlink:visited {\r\n";
	echo "  color : $commentvlinkcolor;\r\n";
	echo "}\r\n";
}
if($commentalinkcolor)
{
	echo "a.commentlink:activ {\r\n";
	echo "  color : $commentalinkcolor;\r\n";
	echo "}\r\n";
}
if($pagenavlinkcolor)
{
	echo "a.pagenav:link {\r\n";
	echo "  color : $pagenavlinkcolor;\r\n";
	echo "}\r\n";
}
if($pagenavvlinkcolor)
{
	echo "a.pagenav:visited {\r\n";
	echo "  color : $pagenavvlinkcolor;\r\n";
	echo "}\r\n";
}
if($pagenavalinkcolor)
{
	echo "a.pagenav:activ {\r\n";
	echo "  color : $pagenavalinkcolor;\r\n";
	echo "}\r\n";
}
if($attachlinkcolor)
{
	echo "a.attach:link {\r\n";
	echo "  color : $attachlinkcolor;\r\n";
	echo "}\r\n";
}
if($attachvlinkcolor)
{
	echo "a.attach:visited {\r\n";
	echo "  color : $attachvlinkcolor;\r\n";
	echo "}\r\n";
}
if($attachalinkcolor)
{
	echo "a.attach:activ {\r\n";
	echo "  color : $attachalinkcolor;\r\n";
	echo "}\r\n";
}
echo "input.snewsinput {\r\n";
if($sninput_bgcolor)
	echo "  background : $sninput_bgcolor;\r\n";
if($sninput_fontcolor)
	echo "  color : $sninput_fontcolor;\r\n";
if(!is_ns4())
{
	if($sninput_borderstyle)
		echo "  border-style : $sninput_borderstyle;\r\n";
	if($sninput_borderwidth)
		echo "  border-width : $sninput_borderwidth;\r\n";
	if($sninput_bordercolor)
		echo "  border-color : $sninput_bordercolor;\r\n";
}
if($sninput_fontsize)
	echo "  font-size : $sninput_fontsize;\r\n";
if($sninput_font)
	echo "  font-family : $sninput_font;\r\n";
if($sninput_fontstyle)
	echo "  font-style : $sninput_fontstyle;\r\n";
if($sninput_fontweight)
	echo "  font-weight : $sninput_fontweight;\r\n";
echo "}\r\n";
echo "textarea.snewsinput {\r\n";
if($sninput_bgcolor)
	echo "  background : $sninput_bgcolor;\r\n";
if($sninput_fontcolor)
	echo "  color : $sninput_fontcolor;\r\n";
if(!is_ns4())
{
	if($sninput_borderstyle)
		echo "  border-style : $sninput_borderstyle;\r\n";
	if($sninput_borderwidth)
		echo "  border-width : $sninput_borderwidth;\r\n";
	if($sninput_bordercolor)
		echo "  border-color : $sninput_bordercolor;\r\n";
}
if($sninput_fontsize)
	echo "  font-size : $sninput_fontsize;\r\n";
if($sninput_font)
	echo "  font-family : $sninput_font;\r\n";
if($sninput_fontstyle)
	echo "  font-style : $sninput_fontstyle;\r\n";
if($sninput_fontweight)
	echo "  font-weight : $sninput_fontweight;\r\n";
if(($textareanoscroll==0) && ($colorscrollbars==1))
{
	if($snisb_facecolor)
		echo "  scrollbar-face-color: $snisb_facecolor;\r\n";
	if($snisb_highlightcolor)
		echo "  scrollbar-highlight-color: $snisb_highlightcolor;\r\n";
	if($snisb_shadowcolor)
		echo "  scrollbar-shadow-color: $snisb_shadowcolor;\r\n";
	if($snisb_3dlightcolor)
		echo "  scrollbar-3dlight-color: $snisb_3dlightcolor;\r\n";
	if($snisb_arrowcolor)
		echo "  scrollbar-arrow-color:  $snisb_arrowcolor;\r\n";
	if($snisb_trackcolor)
		echo "  scrollbar-track-color: $snisb_trackcolor;\r\n";
	if($snisb_darkshadowcolor)
		echo "  scrollbar-darkshadow-color: $snisb_darkshadowcolor;\r\n";
}
echo "}\r\n";
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
if($eventlinkfontsize)
{
	echo "a.eventlink {\r\n";
	echo "  font-size : $eventlinkfontsize;\r\n";
	echo "}\r\n";
	echo "a.eventshorts {\r\n";
	echo "  font-size : $eventlinkfontsize;\r\n";
	echo "}\r\n";
	echo ".eventshorts {\r\n";
	echo "  font-size : $eventlinkfontsize;\r\n";
	echo "}\r\n";
}
if($eventlinkcolor)
{
	echo "a.eventlink:link {\r\n";
	echo "  color : $eventlinkcolor;\r\n";
	echo "}\r\n";
	echo "a.eventshorts:link {\r\n";
	echo "  color : $eventlinkcolor;\r\n";
	echo "}\r\n";
}
if($eventvlinkcolor)
{
	echo "a.eventlink:visited {\r\n";
	echo "  color : $eventvlinkcolor;\r\n";
	echo "}\r\n";
	echo "a.eventshorts:visited {\r\n";
	echo "  color : $eventvlinkcolor;\r\n";
	echo "}\r\n";
}
if($eventalinkcolor)
{
	echo "a.eventlink:activ {\r\n";
	echo "  color : $eventalinkcolor;\r\n";
	echo "}\r\n";
	echo "a.eventshorts:activ {\r\n";
	echo "  color : $eventalinkcolor;\r\n";
	echo "}\r\n";
}
if($actionlinkcolor)
{
	echo "a.actionlink:link {\r\n";
	echo "  color : $actionlinkcolor;\r\n";
	echo "}\r\n";
}
if($actionvlinkcolor)
{
	echo "a.actionlink:visited {\r\n";
	echo "  color : $actionvlinkcolor;\r\n";
	echo "}\r\n";
}
if($actionalinkcolor)
{
	echo "a.actionlink:activ {\r\n";
	echo "  color : $actionalinkcolor;\r\n";
	echo "}\r\n";
}
if($tablealign<2)
{
	echo "table.sntable {\r\n";
	echo "  float: $tblalign;\r\n";
	echo "	clear:both;\r\n";
	echo "}\r\n";
}
echo ".helpline {\r\n";
echo "  border-style : none;\r\n";
if($bbchelp_bgcolor)
	echo "  background : $bbchelp_bgcolor;\r\n";
if($bbchelp_fontcolor)
	echo "  color : $bbchelp_fontcolor;\r\n";	
if($bbchelp_fontsize)
	echo "  font-size : $bbchelp_fontsize;\r\n";
if($bbchelp_font)
	echo "  font-family : $bbchelp_font;\r\n";
if($bbchelp_fontstyle)
	echo "  font-style : $bbchelp_fontstyle;\r\n";
if($bbchelp_fontweight)
	echo "font-weight : $bbchelp_fontweight;\r\n";
echo "}\r\n";
echo "a.emoticonlink {\r\n";
if($el_font)
	echo "  font-family : $el_font;\r\n";
if($el_fontweight)
	echo "	font-weight : $el_fontweight;\r\n";
if($el_fontstyle)
	echo "	font-style : $el_fontstyle;\r\n";
if($el_fontsize)
	echo "  font-size : $el_fontsize;\r\n";
echo "}\r\n";
echo "a.emoticonlink:hover {\r\n";
if($el_fontsize)
	echo "  font-size : $el_fontsize;\r\n";
if($el_fontweight)
	echo "	font-weight : $el_fontweight;\r\n";
if($el_hovercolor)
	echo "  background-color : $el_hovercolor;\r\n";
echo "}\r\n";
echo "table.smiliebox {\r\n";
if($sb_bgcolor)
	echo "  background-color : $sb_bgcolor;\r\n";
echo "  border : thin solid";
if($sb_bordercolor)
	echo " $sb_bordercolor";
echo ";\r\n";
echo "}\r\n";
echo "table.bbcbuttons {\r\n";
if($bbc_bgcolor)
	echo "  background-color : $bbc_bgcolor;\r\n";
echo "  border : thin solid";
if($bbc_bordercolor)
	echo " $bbc_bordercolor";
echo ";\r\n";
echo "}\r\n";
echo "select.bbcsel {\r\n";
if($bbcsel_fontcolor)
	echo "  color: $bbcsel_fontcolor;\r\n";
if($bbcsel_bgcolor)
	echo "  background: $bbcsel_bgcolor;\r\n";
if($bbcsel_font)
	echo "  font-family : $bbcsel_font;\r\n";
if($bbcsel_fontsize)
	echo "  font-size : $bbcsel_fontsize;\r\n";
if(!is_ns4())
{
	if($bbcsel_borderstyle)
		echo "  border-style : $bbcsel_borderstyle;\r\n";
	if($bbcsel_borderwidth)
		echo "  border-width : $bbcsel_borderwidth;\r\n";
	if($bbcsel_bordercolor)
		echo "  border-color : $bbcsel_bordercolor;\r\n";
}
if($bbcsel_fontstyle)
	echo "  font-style : $bbcsel_fontstyle;\r\n";
if($bbcsel_fontweight)
	echo "  font-weight : $bbcsel_fontweight;\r\n";
echo "}\r\n";
echo ".optionrow{\r\n";
if($or_bgcolor)
	echo "  background-color : $or_bgcolor;\r\n";
if($or_fontcolor)
	echo "  color: $or_fontcolor;\r\n";
if($or_font)
	echo "  font-family: $or_font;\r\n";
if($or_fontsize)
	echo "  font-size: $or_fontsize;\r\n";
echo "}\r\n";
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
echo "td.catinfo{\r\n";
echo "  padding-left : $catinfoindent;\r\n";
echo "}\r\n";
echo ".errorrow{\r\n";
echo "	background-color: #FF6347;\r\n";
echo "}\r\n";
echo "td.n4entry{\r\n";
if($n4tbmargin)
{
	echo "	padding-top: $n4tbmargin;\r\n";
	echo "	padding-bottom: $n4tbmargin;\r\n";
}
if($n4leftmargin)
	echo "	padding-left: $n4leftmargin;\r\n";
echo "}\r\n";
echo "-->\r\n";
echo "</style>\r\n";
?>