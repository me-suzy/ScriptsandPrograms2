<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
function display_bbcode_buttons($labels,$inputfield, $codeenable=true, $quoteenable=true, $phpenable=true)
{
	global $bbccolorbar, $l_bbcode_helps, $l_bbcode_labels, $act_lang, $l_alignments, $minfontsize, $maxfontsize, $l_bbcodehelp;
	
	include('./includes/bbcode_defs.inc');
		
	echo "<script type=\"text/javascript\" language=\"JavaScript\">\n";
	echo "<!--\n";
	echo "bbc_imgpreload();\n";
	echo "// -->\n";
	echo "</SCRIPT>\n";
	echo "<table class=\"bbcbuttons\" width=\"99%\" align=\"center\">";
	echo "<tr><td>";
	echo "<select onmouseout=\"helpline('','$inputfield')\" onmouseover=\"helpline('".$l_bbcode_helps[17]."','$inputfield')\" class=\"bbcbuttons\" name=\"".$inputfield."font\" onchange=\"changeFont(this.options[this.selectedIndex].value,'$inputfield');\">";
	echo "<option value=\"\">".$labels["font"]."</option>";
	for($i=0;$i<count($bbc_availfonts);$i++)
		echo "<option value=\"".$bbc_availfonts[$i]."\">".$bbc_availfonts[$i]."</option>";
	echo "</select> ";
	echo "<select onmouseout=\"helpline('','$inputfield')\" onmouseover=\"helpline('".$l_bbcode_helps[18]."','$inputfield')\" class=\"bbcbuttons\" name=\"".$inputfield."fontsize\" onChange=\"fontSize(this.options[this.selectedIndex].value,'$inputfield');\">";
	echo "<option value=\"\">".$labels["fontsize"]."</option>";
	for($i=$minfontsize;$i<$maxfontsize+1;$i++)
		echo "<option value=\"$i\">$i</option>";
	echo "</select> ";
	echo "<select onmouseout=\"helpline('','$inputfield')\" onmouseover=\"helpline('".$l_bbcode_helps[19]."','$inputfield')\" class=\"bbcbuttons\" name=\"".$inputfield."alignment\" onChange=\"setAlignment(this.options[this.selectedIndex].value,'$inputfield')\">";
	echo "<option value=\"\">".$labels["alignment"]."</option>";
	for($i=0;$i<count($l_alignments);$i++)
		echo "<option value=\"".$l_alignments[$i]."\">".$l_alignments[$i]."</option>";
	echo "</select> ";
	if(is_opera() || is_ns4() || ($bbccolorbar==0))
	{
		echo "<select onmouseout=\"helpline('','$inputfield')\" onmouseover=\"helpline('".$l_bbcode_helps[20]."','$inputfield')\" class=\"bbcbuttons\" name=\"".$inputfield."fontcolor\" onChange=\"colorThis(this.options[this.selectedIndex].value,'$inputfield')\">";
		echo "<option value=\"\">".$labels["fontcolor"]."</option>";
		for($i=0;$i<count($bbc_availcolors);$i++)
			echo "<option value=\"".$bbc_availcolors[$i]."\" style=\"color:".$bbc_availcolors[$i].";\">".$bbc_availcolors[$i]."</option>";
		echo "</select>";
	}
	else
	{
		echo "</td></tr><tr><td>";
		bbcode_colorbar($inputfield);
	}
	echo "</td></tr><tr><td>";
	for($i=0;$i<count($bbcode_functions);$i++)
		bbcode_button($bbcode_pics[$i], $l_bbcode_helps[$i], $l_bbcode_labels[$i], $bbcode_functions[$i]."('".$inputfield."')", $inputfield, $i);
	if($codeenable)
		bbcode_button($bbcode_pics[15], $l_bbcode_helps[15], $l_bbcode_labels[15], "code('".$inputfield."')", $inputfield, 15);
	if($phpenable)
		bbcode_button($bbcode_pics[16], $l_bbcode_helps[16], $l_bbcode_labels[16], "phpcode('".$inputfield."')", $inputfield, 16);
	if($quoteenable)
		bbcode_button($bbcode_pics[17], $l_bbcode_helps[17], $l_bbcode_labels[17], "quote('".$inputfield."')", $inputfield, 17);
	echo "<a href=\"help/".$act_lang."/bbcode.html\" target=\"_blank\">";
	echo "<img name=\"bbchelp_$inputfield\" onmouseover=\"highlightbutton('".$l_bbcodehelp."', '$inputfield','bbchelp_$inputfield','bbcode_help.gif')\" onmouseout=\"unhighlightbutton('', '$inputfield','bbchelp_$inputfield','bbcode_help.gif')\" src=\"gfx/bbcodes/normal/bbcode_help.gif\" alt=\"$l_bbcodehelp\" title=\"$l_bbcodehelp\" border=\"0\" align=\"absmiddle\"></a>";
	echo "</td></tr>\n";
	echo "<tr><td>\n";
	echo "<select onmouseout=\"helpline('','$inputfield')\" onmouseover=\"helpline('".$l_bbcode_helps[24]."','$inputfield')\" class=\"bbcbuttons\" name=\"".$inputfield."specchar\" onChange=\"addspecialchar(this.options[this.selectedIndex].value,'$inputfield')\">\n";
	echo "<option value=\"\">".$l_bbcode_labels[18]."</option>";
	for($i=0;$i<count($specialchars);$i++)
		echo "<option value=\"".htmlspecialchars($specialchars[$i])."\">".$specialchars[$i]."</option>";
	echo "</select></td></tr>";
	if(!is_ns4())
	{
		echo "<tr><td>";
		echo "<input type=\"text\" name=\"".$inputfield."_helpbox\" size=\"45\" maxlength=\"100\" style=\"width:450px;\" class=\"helpline\">";
		echo "</td></tr>";
	}
	echo "</table>";
}

function bbcode_colorbar($inputfield)
{
	global $l_fontcolor, $l_bbccolhelp;
	
	$colbar_numcolors= 28;
	$colbar_height=10;
	$baritem_width=floor(-2/15*$colbar_numcolors+6);
	
	echo "<table class=\"colorbar\" align=\"left\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">";
	echo"<tr class=\"colorbar\">";
	echo "<td class=\"colorbar\">$l_fontcolor:&nbsp;</td>";
        echo "<TD class=\"colorbar\" id=\"".$inputfield."ColorUsed\" onmouseover=\"helpline('$l_bbccolhelp','$inputfield')\" onclick=\"if(this.bgColor.length > 0) colorThis(this.bgColor)\" ";
	echo "vAlign=\"center\" align=\"middle\" BORDER-RIGHT: BORDER-TOP: BORDER-LEFT: ridge; CURSOR: default; BORDER-BOTTOM: 2px ridge>\n";
	echo "<img class=\"colorbar\" name=\"color1\" align=\"absmiddle\" width=\"10\" height=\"$colbar_height\" src=\"gfx/space.gif\" border=\"1\"></td>";
	echo "<td class=\"colorbar\" width=\"5\">";
	echo "<img class=\"colorbar\" width=\"5\" height=\"$colbar_height\" src=\"gfx/space.gif\" border=\"0\"></td>";
	echo "<TD class=\"colorbar\" id=\"".$inputfield."ColorUsed1\" onmouseover=\"helpline('$l_bbccolhelp','$inputfield')\" onclick=\"if(this.bgColor.length > 0) colorThis(this.bgColor)\" ";
	echo "vAlign=\"center\" align=\"middle\" BORDER-RIGHT: BORDER-TOP: BORDER-LEFT: ridge; CURSOR: default; BORDER-BOTTOM: 2px ridge>";
	echo "<img class=\"colorbar\" name=\"color2\" align=\"absmiddle\" width=\"10\" height=\"$colbar_height\" src=\"gfx/space.gif\" border=\"1\"></td>";
	echo "<td class=\"colorbar\" width=\"5\">";
	echo "<img class=\"colorbar\" width=\"5\" height=\"$colbar_height\" src=\"gfx/space.gif\" border=\"0\"></td>\n";
	include_once('./includes/colorbar.inc');
	show_colorbar($colbar_numcolors, floor(-2/15*$colbar_numcolors+6), $colbar_height, $l_bbccolhelp, $inputfield);
	echo "</td></tr></table>";                
}

function bbcode_button($pic, $help, $label, $jsfunction, $inputfield, $buttonnr)
{
	global $path_faqe;
	
	$imagesize = GetImageSize ($path_faqe."/admin/gfx/bbcodes/normal/".$pic);
	echo "<a href=\"javascript:$jsfunction;\"><img name=\"bbcb".$buttonnr."_".$inputfield."\" onmouseover=\"highlightbutton('".$help."','$inputfield','bbcb".$buttonnr."_".$inputfield."','".$pic."')\" onmouseout=\"unhighlightbutton('','$inputfield','bbcb".$buttonnr."_".$inputfield."','".$pic."')\" src=\"gfx/bbcodes/normal/".$pic."\" border=\"0\" align=\"middle\" title=\"".$label."\" alt=\"".$label."\" width=\"".$imagesize[0]."\" height=\"".$imagesize[1]."\"></a> ";
}
?>