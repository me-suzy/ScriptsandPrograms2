<?php
/***************************************************************************
 * (c)2002-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
include_once('../includes/bbcode_pics.inc');
function display_bbcode_buttons($labels, $inputfield, $codeenable=true, $quoteenable=true, $formname="inputform", $filelinking=false)
{
	global $langvar, $bbcode_pics, $bbcodepic_help, $l_bbcode_helps, $act_lang, $l_availfonts, $l_availcolors, $l_alignments, $minfontsize, $maxfontsize, $l_bbcodehelp;
	
	echo "<script type=\"text/javascript\" language=\"JavaScript\">\n";
	echo "<!--\n";
	echo "bbc_imgpreload();\n";
	echo "// -->\n";
	echo "</SCRIPT>\n";
	echo "<table class=\"bbcbuttons\" width=\"99%\" align=\"center\">";
	echo "<tr><td>";
	echo "<select name=\"".$inputfield."_font\" onmouseover=\"helpline('".$l_bbcode_helps[16]."','$formname','$inputfield')\" onmouseout=\"helpline('','$formname','$inputfield')\" onchange=\"changeFont(this.options[this.selectedIndex].value,'$inputfield','$formname');\" style=\"background-color:#a0a0a0\">";
	echo "<option value=\"\">".$labels["font"]."</option>";
	for($i=0;$i<count($l_availfonts);$i++)
		echo "<option value=\"".$l_availfonts[$i]."\">".$l_availfonts[$i]."</option>";
	echo "</select> ";
	echo "<select name=\"".$inputfield."_fontsize\"  onmouseover=\"helpline('".$l_bbcode_helps[17]."','$formname','$inputfield')\" onmouseout=\"helpline('','$formname','$inputfield')\" onChange=\"fontSize(this.options[this.selectedIndex].value,'$inputfield','$formname');\" style=\"background-color:#a0a0a0\">";
	echo "<option value=\"\">".$labels["fontsize"]."</option>";
	for($i=$minfontsize;$i<$maxfontsize+1;$i++)
		echo "<option value=\"$i\">$i</option>";
	echo "</select> ";
	echo "<select name=\"".$inputfield."alignment\"  onmouseover=\"helpline('".$l_bbcode_helps[18]."','$formname','$inputfield')\" onmouseout=\"helpline('','$formname','$inputfield')\" onChange=\"setAlignment(this.options[this.selectedIndex].value,'$inputfield','$formname')\" style=\"background-color:#a0a0a0\">";
	echo "<option value=\"\">".$labels["alignment"]."</option>";
	for($i=0;$i<count($l_alignments);$i++)
		echo "<option value=\"".$l_alignments[$i]."\">".$l_alignments[$i]."</option>";
	echo "</select> ";
	if(!is_opera() && !is_ns4())
	{
		echo "<br>";
		bbcode_colorbar($inputfield,$l_bbcode_helps[19],$formname);
	}
	else
	{
		echo "<select name=\"".$inputfield."_fontcolor\"  onmouseover=\"helpline('".$l_bbcode_helps[19]."','$formname','$inputfield')\" onmouseout=\"helpline('','$formname','$inputfield')\" onChange=\"colorThis(this.options[this.selectedIndex].value,'$inputfield','$formname')\" style=\"background-color:#a0a0a0\">";
		echo "<option value=\"\">".$labels["fontcolor"]."</option>";
		for($i=0;$i<count($l_availcolors);$i++)
			echo "<option value=\"".$l_availcolors[$i]."\" style=\"color:".$l_availcolors[$i].";\">".$l_availcolors[$i]."</option>";
		echo "</select> ";
	}
	echo "<br>";
	bbcode_button($bbcode_pics[0], $l_bbcode_helps[0], $labels["bold"], "bold('$inputfield','$formname')", $formname, $inputfield, 0);
	bbcode_button($bbcode_pics[1], $l_bbcode_helps[1], $labels["italic"], "italic('$inputfield','$formname')", $formname, $inputfield,1);
	bbcode_button($bbcode_pics[2], $l_bbcode_helps[2], $labels["typewriter"], "typewriter('$inputfield','$formname')", $formname, $inputfield,2);
	bbcode_button($bbcode_pics[3], $l_bbcode_helps[3], $labels["strike"], "strike('$inputfield','$formname')", $formname, $inputfield,3);
	bbcode_button($bbcode_pics[4], $l_bbcode_helps[4], $labels["center"], "center('$inputfield','$formname')", $formname, $inputfield,4);
	bbcode_button($bbcode_pics[17], $l_bbcode_helps[21], $labels["underline"], "underline('$inputfield','$formname')", $formname, $inputfield,16);
	bbcode_button($bbcode_pics[5], $l_bbcode_helps[5], $labels["sub"], "subscript('$inputfield','$formname')", $formname, $inputfield,5);
	bbcode_button($bbcode_pics[6], $l_bbcode_helps[6], $labels["sup"], "superscript('$inputfield','$formname')", $formname, $inputfield,6);
	if($codeenable)
		bbcode_button($bbcode_pics[7], $l_bbcode_helps[7], $labels["code"], "code('$inputfield','$formname')", $formname, $inputfield,7);
	if($quoteenable)
		bbcode_button($bbcode_pics[8], $l_bbcode_helps[8], $labels["quote"], "quote('$inputfield','$formname')", $formname, $inputfield,8);
	bbcode_button($bbcode_pics[9], $l_bbcode_helps[9], $labels["liststart"], "listStart('$inputfield','$formname')", $formname, $inputfield,9);
	bbcode_button($bbcode_pics[10], $l_bbcode_helps[10], $labels["listitem"], "listItem('$inputfield','$formname')", $formname, $inputfield,10);
	bbcode_button($bbcode_pics[11], $l_bbcode_helps[11], $labels["listend"], "listEnd('$inputfield','$formname')", $formname, $inputfield,11);
	bbcode_button($bbcode_pics[12], $l_bbcode_helps[12], $labels["email"], "email('$inputfield','$formname')", $formname, $inputfield,12);
	bbcode_button($bbcode_pics[13], $l_bbcode_helps[13], $labels["url"], "aHref('$inputfield','$formname')", $formname, $inputfield,13);
	bbcode_button($bbcode_pics[14], $l_bbcode_helps[14], $labels["pic"], "pic('$inputfield','$formname')", $formname, $inputfield,14);
	bbcode_button($bbcode_pics[15], $l_bbcode_helps[15], $labels["pic2"], "openWindow('".do_url_session("gfx_upload.php?$langvar=$act_lang&inputfield=$inputfield&mode=3&inputform=$formname")."')", $formname, $inputfield,15);
	if($filelinking)
		bbcode_button($bbcode_pics[16], $l_bbcode_helps[20], $labels["attach"], "openWindow2('".do_url_session("dbfiles.php?$langvar=$act_lang&inputfield=$inputfield&mode=3&inputform=$formname")."',20,20,620,300)", $formname, $inputfield,16);
	echo "<a href=\"../help/".$act_lang."/bbcode.php\" target=\"_blank\">";
	echo "<img name=\"bbchelp_$inputfield\" onmouseover=\"highlightbutton('".$l_bbcodehelp."', '$formname', '$inputfield','bbchelp_$inputfield','bbcode_help.gif')\" onmouseout=\"unhighlightbutton('', '$formname', '$inputfield','bbchelp_$inputfield','bbcode_help.gif')\" src=\"../gfx/bbcodes/normal/bbcode_help.gif\" alt=\"$l_bbcodehelp\" title=\"$l_bbcodehelp\" border=\"0\" align=\"absmiddle\"></a>";
	echo "</td></tr>";
	if(!is_ns4())
	{
		echo "<tr><td>";
		echo "<input type=\"text\" name=\"".$inputfield."_helpbox\" size=\"45\" maxlength=\"100\" style=\"width:450px;\" class=\"helpline\">";
		echo "</td></tr>";
	}
	echo "</table>";
}

function display_smiliebox($inputfield, $formname="inputform")
{
	global $tableprefix, $db, $url_emoticons, $act_lang, $langvar, $l_emoticonlist;
	
	echo "<table class=\"smiliebox\" width=\"99%\" align=\"center\">";
	echo "<tr><td valign=\"middle\">";
	$tempsql = "select emoticon_url, code, emotion from ".$tableprefix."_emoticons group by emoticon_url";
	if(!$tempresult = mysql_query($tempsql, $db))
	    die("Unable to connect to database.".mysql_error());
	while($temprow=mysql_fetch_array($tempresult))
	{
		echo "<a href=\"javascript:add_emoticon('".stripslashes($temprow["code"])."','$inputfield','$formname')\">";
		echo "<img src=\"$url_emoticons/".$temprow["emoticon_url"]."\" align=\"bottom\" border=\"0\" alt=\"".do_htmlentities($temprow["emotion"])."\" title=\"".do_htmlentities($temprow["emotion"])."\"></a> ";
	}
	echo "&nbsp; <a class=\"emoticonlink\" href=\"javascript:openWindow3('".do_url_session("emoticonlist.php?$langvar=$act_lang&inputfield=$inputfield&formname=$formname")."','emoticonlist',20,20,300,400)\">[$l_emoticonlist]</a>";
	echo "</td></tr>";
}

function bbcode_colorbar($inputfield, $helptext, $formname="inputform")
{
	global $l_fontcolor, $url_simpnews;
	
	$colbar_numcolors= 28;
	$colbar_height=10;
	$baritem_width=floor(-2/15*$colbar_numcolors+6);
	
	echo "<SCRIPT language=JavaScript src=\"./js/colorbar.js\">\n";
	echo "</SCRIPT>\n";
	echo "<script type=\"text/javascript\" language=\"JavaScript\">\n";
	echo "<!--\n";
	echo "var height1 = $colbar_height;//define the height of the color bar\n";
	echo "var pas = $colbar_numcolors;// define the number of color in the color bar\n";
	echo "var width1=Math.floor(-2/15*pas+6);//define the width of the color bar\n";
	echo "var helptext='$helptext';\n";
	echo "var inputfield='$inputfield';\n";
	echo "var formname='$formname';\n";
	echo "var text1=helptext.substring(0,search(helptext,\"=\"));\n";
	echo "var text2=helptext.substring(search(helptext,\"]\"),search(helptext,\"/\"));\n";
	echo "// -->\n";
	echo "</SCRIPT>\n";
	echo "<table class=\"colorbar\" align=\"left\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr class=\"colorbar\">";
	echo "<td class=\"colorbar\">$l_fontcolor:&nbsp;</td>";
        echo "<TD class=\"colorbar\" id=\"ColorUsed\" onmouseover=\"helpline('$helptext','$formname','$inputfield')\" onclick=\"if(this.bgColor.length > 0) colorThis(this.bgColor,'$inputfield','$formname')\" ";
	echo "vAlign=\"center\" align=\"middle\" BORDER-RIGHT: BORDER-TOP: BORDER-LEFT: ridge; CURSOR: default; BORDER-BOTTOM: 2px ridge>";
	echo "<img class=\"colorbar\" name=\"color1\" align=\"absmiddle\" width=\"10\" height=\"$colbar_height\" src=\"gfx/space.gif\" border=\"1\"></td>";
	echo "<td class=\"colorbar\" width=\"5\">";
	echo "<img class=\"colorbar\" width=\"5\" height=\"$colbar_height\" src=\"gfx/space.gif\" border=\"0\"></td>";
	echo "<TD class=\"colorbar\" id=\"ColorUsed1\"  onmouseover=\"helpline('$helptext','$formname','$inputfield')\" onclick=\"if(this.bgColor.length > 0) colorThis(this.bgColor,'$inputfield','$formname')\" ";
	echo "vAlign=\"center\" align=\"middle\" BORDER-RIGHT: BORDER-TOP: BORDER-LEFT: ridge; CURSOR: default; BORDER-BOTTOM: 2px ridge>";
	echo "<img class=\"colorbar\" name=\"color2\" align=\"absmiddle\" width=\"10\" height=\"$colbar_height\" src=\"gfx/space.gif\" border=\"1\"></td>";
	echo "<td class=\"colorbar\" width=\"5\">";
	echo "<img class=\"colorbar\" width=\"5\" height=\"$colbar_height\" src=\"gfx/space.gif\" border=\"0\"></td>\n";
	echo "<script type=\"text/javascript\" language=\"JavaScript\">\n";
	echo "<!--\n";
	echo "rgb(pas,width1,height1,text1,text2,inputfield,formname)\n";
	echo "// -->\n";
	echo "</SCRIPT>\n";
	echo "</td></tr></table>";                
}

function bbcode_button($pic, $help, $label, $jsfunction, $formname, $inputfield, $buttonnr)
{
	global $path_simpnews;
	$imagesize = GetImageSize ($path_simpnews."/gfx/bbcodes/normal/".$pic);
	echo "<a href=\"javascript:$jsfunction;\"><img name=\"bbcb".$buttonnr."_".$inputfield."\" onmouseover=\"highlightbutton('".$help."','$formname','$inputfield','bbcb".$buttonnr."_".$inputfield."','".$pic."')\" onmouseout=\"unhighlightbutton('','$formname','$inputfield','bbcb".$buttonnr."_".$inputfield."','".$pic."')\" src=\"../gfx/bbcodes/normal/".$pic."\" border=\"0\" align=\"middle\" title=\"".$label."\" alt=\"".$label."\" width=\"".$imagesize[0]."\" height=\"".$imagesize[1]."\"></a> ";
}
?>