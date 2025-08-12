<?php
/***************************************************************************
 * (c)2002-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
if(($usecustomfooter==1) && ($copyrightpos==1))
{
	echo "<div style=\"clear:both\">";
	if(($footerfile) && ($footerfilepos==0))
	{
		if(is_phpfile($footerfile))
			include_once($footerfile);
		else
			file_output($footerfile);
		if(($customfooter) && ($cfootnobr==0))
			echo "<br>\n";
	}
	if($customfooter)
		echo $customfooter;
	if(($footerfile) && ($footerfilepos==1))
	{
		if(($customfooter) && ($cfootnobr==0))
			echo "<br>\n";
		if(is_phpfile($footerfile))
			include_once($footerfile);
		else
			file_output($footerfile);
	}
	echo "</div>";
}
?>
<div style=\"clear:both\" align="<?php echo $tblalign?>">
<table width="<?php echo $TableWidth?>" border="0" CELLPADDING="1" CELLSPACING="0" class="sntable" align="<?php echo $tblalign?>">
<tr><TD BGCOLOR="<?php echo $bordercolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<TR BGCOLOR="<?php echo $copyrightbgcolor?>" ALIGN="CENTER">
<TD ALIGN="CENTER" VALIGN="MIDDLE" colspan="2"><font face="<?php echo $copyrightfont?>" size="<?php echo $copyrightfontsize?>" color="<?php echo $copyrightfontcolor?>">
<?php
if($showcurrtime==1)
{
	$acttime=transposetime(time(),$servertimezone,$displaytimezone);
	$displaytime=date("H:i",$acttime);
	echo "$l_timeonserver: $displaytime";
	echo "<br><span class=\"timezone\">$l_timezone_note ".timezonename($displaytimezone);
	$gmtoffset=tzgmtoffset($displaytimezone);
	if($gmtoffset)
		echo " (".$gmtoffset.")";
	echo "</span><br>";
}
if($contentcopy)
{
	echo $l_content." ".undo_htmlspecialchars(do_htmlentities(stripslashes($contentcopy)))."<br>";
}
else
{
	echo $l_content." ".$simpnewssitename."<br>";
}
echo "$l_generated_with ".strip_tags($copyright_url)." $copyright_note";
if($l_translationnote)
	echo "<br>$l_translationnote";
echo "</font></td></tr></table></td></tr></table></div>";
if(($usecustomfooter==1) && ($copyrightpos==0))
{
	echo "<div style=\"clear:both\">";
	if(($footerfile) && ($footerfilepos==0))
	{
		if(is_phpfile($footerfile))
			include_once($footerfile);
		else
			file_output($footerfile);
		if($customfooter)
			echo "<br>\n";
	}
	if($customfooter)
		echo $customfooter;
	if(($footerfile) && ($footerfilepos==1))
	{
		if($customfooter)
			echo "<br>\n";
		if(is_phpfile($footerfile))
			include_once($footerfile);
		else
			file_output($footerfile);
	}
	echo "</div>";
}
?>
</body></html>