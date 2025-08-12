<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
function gfx_selector($fieldname,$fieldvalue,$mode="1",$formname="layoutform")
{
	global $upload_avail, $act_lang, $langvar, $l_gfxupload, $l_choose;
	
	$text="<td align=\"left\">";
	$text.="<input class=\"sninput\" type=\"text\" name=\"".$fieldname."\" value=\"".$fieldvalue."\" size=\"40\" maxlength=\"240\"> ";
	$text.="<input class=\"sncolorbutton\" type=\"button\" value=\"";
	if($upload_avail)
		$text.=$l_gfxupload;
	else
		$text.=$l_choose;
	$text.="\" onClick=\"openWindow('".do_url_session("gfx_upload.php?$langvar=$act_lang&inputfield=$fieldname&inputform=$formname&mode=$mode")."')\">";
	$text.="</td></tr>\n";
	return $text;
}
?>