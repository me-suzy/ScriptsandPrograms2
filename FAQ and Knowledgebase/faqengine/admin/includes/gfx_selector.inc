<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
function gfx_selector($fieldname, $fieldvalue, $formname="inputform", $mode=0, $canbeempty=false)
{
	global $upload_avail, $act_lang, $langvar, $l_gfxupload, $l_blank_gfx_info;
	
	$text="<td align=\"left\">";
	$text.="<input class=\"faqeinput\" type=\"text\" name=\"".$fieldname."\" value=\"".$fieldvalue."\" size=\"40\" maxlength=\"240\"> ";
	$text.="<input class=\"faqebutton\" type=\"button\" value=\"";
	if($upload_avail)
		$text.=$l_gfxupload;
	else
		$text.=$l_choose;
	$link="gfx_upload2.php?$langvar=$act_lang&inputfield=$fieldname&inputform=$formname&mode=$mode";
	$text.="\" onClick=\"openWindow('".do_url_session($link)."')\">";
	if($canbeempty)
		$text.=" <img src=\"gfx\blpic.gif\" alt=\"$l_blank_gfx_info\" title=\"$l_blank_gfx_info\" border=\"0\">";
	$text.="</td></tr>\n";
	return $text;
}
?>