<?php
/***************************************************************************
 * (c)2002 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
function color_chooser($fieldname,$fieldvalue)
{
	global $l_choose;
	$text="<td align=\"left\"><table bgcolor=\"#000000\" cellspacing=\"2\" width=\"100%\"><tr><td class=\"inputrow\" width=\"90%\">\n";
	$text.="<input class=\"faqecolorinput\" id=\"$fieldname\" type=\"text\" name=\"";
	$text.=$fieldname;
	$text.="\" value=\"";
	$text.=$fieldvalue;
	$text.="\" size=\"8\" maxlength=\"7\"";
	if(!is_opera() && !is_ns4())
	{
		$text.="onChange=\"color_prev('";
		$text.=$fieldname."')\"";
	}
	$text.=">\n";
	if(!is_ns4())
	{
		if(is_msie())
		{
			$text.="<input class=\"faqecolorbutton\" type=\"button\" onclick=\"color_picker('";
			$text.=$fieldname;
			$text.="');\" value=\"$l_choose\">\n";
		}
		else
		{
			$text.="<input class=\"faqecolorbutton\" type=\"button\" onclick=\"color_chooser('";
			$text.=$fieldname;
			$text.="');\" value=\"$l_choose\">\n";
		}
		$text.="</td>";
		if(!is_opera())
		{
			$text.="<td width=\"10%\" id=\"";
			$text.=$fieldname;
			$text.="prev\" bgcolor=\"";
			$text.=$fieldvalue;
			$text.="\">&nbsp;</td>\n";
		}
	}
	else
	{
		$text.="</td>";
	}
	$text.="</tr></table></td></tr>\n";
	return $text;
}
?>