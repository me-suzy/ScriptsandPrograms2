<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
?>
<div style="clear:both" align="<?php echo $tblalign?>">
<table align="<?php echo $tblalign?>" bgcolor="<?php echo $copyrightbgcolor?>" width="<?php echo $TableWidth?>" border="0">
<tr><td class="copyright" align="center">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize4?>; color: <?php echo $FontColor?>">
<?php
if($showcurrtime)
{
	$displaytime=date("H:i");
	echo "$l_currtime $displaytime<br>";
}
if($showtimezone==1)
{
	echo "<span class=\"timezone\">$l_timezone_note ".timezonename($server_timezone);
	$gmtoffset=tzgmtoffset($server_timezone);
	if($gmtoffset)
		echo " (".$gmtoffset.")";
	echo "</span><br>";
}
if($contentcopy)
	echo "<br><span class=\"contentcopy\">$l_content ".display_encoded($contentcopy)."</span><br>";
else
	echo "<br><span class=\"contentcopy\">$l_content ".$faqsitename."</span><br>";
echo "$l_generated_with $copyright_url $copyright_note";
if($l_translationnote)
	echo "<br>$l_translationnote";
?>
</span>
</td></tr></table>
</div>
</body></html>
