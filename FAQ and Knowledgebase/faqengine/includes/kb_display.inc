<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
	$sql = "select * from ".$tableprefix."_kb_articles where (articlenr='$kbnr')";
	if(!$result = faqe_db_query($sql, $db))
		db_die("<tr class=\"errorrow\"><td>Could not connect to the database.");
	if (!$myrow = faqe_db_fetch_array($result))
		die("<tr class=\"errorrow\"><td>no such entry");
	$sql2 = "UPDATE ".$tableprefix."_kb_articles SET views = views + 1 WHERE (articlenr='$kbnr')";
	@faqe_db_query($sql2, $db);
?>
<tr bgcolor="<?php echo $subheadingbgcolor?>"><td class="subheading" align="center" width="98%">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize2?>; color: <?php echo $SubheadingFontColor?>; font-weight: bold;">
<?php echo $l_kbarticle?> #<?php echo $myrow["articlenr"]?></span></td>
<?php
$printurl="kb_print.php?kbnr=$kbnr&amp;$langvar=$act_lang&amp;prog=$prog";
if($navframe==1)
	$printurl.="&amp;nobacklink=1";
if(isset($layout))
	$printurl.="&amp;layout=$layout";
?>
<td align="right" valign="middle" width="2%" nowrap><a class=\"mainaction\" href="<?php echo $printurl?>" <?php if($navframe==1) echo "target=\"kbprint\""?>>
<?php
	if($printpic)
		echo "<img src=\"$printpic\" border=\"0\" align=\"middle\" title=\"$l_print\" alt=\"$l_print\"></a>";
	else
	{
		echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $HeadingFontColor;\">";
		echo "[$l_print]</span></a> ";
	}
	if($allowemail==1)
	{
		$emailurl=$url_faqengine."/kb_email.php?kbnr=$kbnr&amp;$langvar=$act_lang&amp;prog=$prog";
		if($navframe==1)
			$emailurl.="&amp;nobacklink=1";
		if(isset($layout))
			$emailurl.="&amp;layout=$layout";
		echo "<a class=\"mainaction\" href=\"$emailurl\"";
		if($navframe==1)
			echo " target=\"kbemail\"";
		echo ">";
		if($emailpic)
			echo "<img class=\"mainaction\" src=\"$emailpic\" border=\"0\" align=\"middle\" title=\"$l_emailkb\" alt=\"$l_emailkb\"></a>";
		else
		{
			echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $HeadingFontColor;\">";
			echo "[$l_emailkb]</span></a> ";
		}
	}
?>
</td></tr></table></td></tr>
<tr><TD BGCOLOR="<?php echo $table_bgcolor?>">
<TABLE BORDER="0" CELLPADDING="<?php echo $tablepadding?>" CELLSPACING="<?php echo $tablespacing?>" WIDTH="100%">
<tr bgcolor="<?php echo $group_bgcolor?>"><td align="center" colspan="2">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize5?>; color: <?php echo $FontColor?>; font-weight: bold;">
<?php echo undo_html_ampersand(stripslashes($myrow["heading"]))?></span></td></tr>
<tr bgcolor="<?php echo $row_bgcolor?>"><td align="right" width="15%">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>; color: <?php echo $FontColor?>;">
<?php echo $l_program?>:</font></td>
<td width="85%"><span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>; color: <?php echo $FontColor?>;">
<?php
	$sql = "select * from ".$tableprefix."_programm where (prognr=".$myrow["programm"].")";
	if(!$result = faqe_db_query($sql, $db)) {
		die("<tr bgcolor=\"#cccccc\"><td align=\"center\">Could not connect to the database (3).");
	}
	if ($temprow = faqe_db_fetch_array($result))
	{
		$progname=display_encoded($temprow["programmname"]);
		$proglang=$temprow["language"];
	}
	else
	{
		$progname=$l_undefined;
		$proglang=$l_none;
	}
	echo "$progname [$proglang]</font></td></tr>";
	$sql = "select os.* from ".$tableprefix."_os os, ".$tableprefix."_kb_os kbos where kbos.articlenr=".$myrow["articlenr"]." and kbos.osnr=os.osnr";
	if(!$result = faqe_db_query($sql, $db)) {
		die("<tr bgcolor=\"#cccccc\"><td align=\"center\">Could not connect to the database (3).");
	}
	if($temprow=faqe_db_fetch_array($result))
	{
		echo "<tr bgcolor=\"$row_bgcolor\"><td class=\"os\" align=\"right\" valign=\"top\" width=\"15%\">";
		echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
		echo "$l_affectedos:</span></td>";
		$oslist="<ul>";
		do{
			$oslist.="<li>".display_encoded($temprow["osname"]);
		}while($temprow=faqe_db_fetch_array($result));
		$oslist.="</ul>";
		echo "<td width=\"85%\"><span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
		echo "$oslist</span></td></tr>";
	}
	$tempsql="select ver.* from ".$tableprefix."_programm_version ver, ".$tableprefix."_kb_prog_version kbpv where kbpv.articlenr=".$myrow["articlenr"]." and ver.entrynr=kbpv.progversion";
	if(!$tempresult=faqe_db_query($tempsql,$db))
	{
		echo "<tr><td bgcolor=\"$heading_bgcolor\">";
		die("Could not connect to the database.");
	}
	if($temprow=faqe_db_fetch_array($tempresult))
	{
		$displayversions=stripslashes($temprow["version"]);
		while($temprow=faqe_db_fetch_array($tempresult))
		{
			$displayversions.=", ".stripslashes($temprow["version"]);
		}
		echo "<TR BGCOLOR=\"$row_bgcolor\" ALIGN=\"LEFT\">";
		echo "<td valign=\"top\" align=\"right\" width=\"15%\">";
		echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
		echo "$l_affectedversions:</span></td>";
		echo "<td class=\"progversion\" width=\"85%\"><span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
		echo $displayversions;
		echo "</span></td></tr>";
	}
	$articletext=stripslashes($myrow["article"]);
	$articletext = undo_htmlspecialchars($articletext);
	$articletext = str_replace("{lang}","$langvar=$act_lang",$articletext);
	$articletext = str_replace("{url_faqengine}",$url_faqengine,$articletext);
	$articletext = str_replace("{bbc_code}",$l_bbccode,$articletext);
	$articletext = str_replace("{bbc_quote}",$l_bbcquote,$articletext);
?>
<tr bgcolor="<?php echo $row_bgcolor?>"><td class="article" align="left" colspan="2">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>; color: <?php echo $FontColor?>;">
<?php echo $articletext?></span></td></tr>
<?php
	$attachsql="select f.filename, f.filesize, f.mimetype, f.description, kba.* from ".$tableprefix."_kb_attachs kba, ".$tableprefix."_files f where f.entrynr=kba.attachnr and kba.articlenr=".$myrow["articlenr"];
	if(!$attachresult = faqe_db_query($attachsql, $db))
		die("Could not connect to the database.");
	while($attachrow=faqe_db_fetch_array($attachresult))
	{
		echo "<TR BGCOLOR=\"$group_bgcolor\" ALIGN=\"LEFT\">";
		echo "<td colspan=\"2\" class=\"attach\">";
		echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
		echo "<a class=\"attach\" href=\"download.php?attachnr=".$attachrow["attachnr"]."\">";
		$fileinfo=$attachrow["filename"]." (".format_bytes($attachrow["filesize"]).")";
		if($attachrow["description"])
			$fileinfo.="\n".$attachrow["description"];
		$mimesql="select * from ".$tableprefix."_mimetypes where mimetype='".$attachrow["mimetype"]."'";
		if(!$mimeresult = faqe_db_query($mimesql, $db))
			die("Could not connect to the database.");
		if($mimerow=faqe_db_fetch_array($mimeresult))
		{
			$fdsql="select * from ".$tableprefix."_filetypedescription where language='$act_lang' and mimetype=".$mimerow["entrynr"];
			if(!$fdresult = faqe_db_query($fdsql, $db))
				die("Could not connect to the database.");
			if($fdrow=faqe_db_fetch_array($fdresult))
				$dfileinfo=$fdrow["description"].": ".$fileinfo;
			else
				$dfileinfo=$fileinfo;
			if($mimerow["icon"])
				echo "<img class=\"attach\" src=\"$url_faqengine/gfx/".$mimerow["icon"]."\" border=\"0\" align=\"absmiddle\" title=\"$dfileinfo\" alt=\"$dfileinfo\"> ";
			else
				echo "<img class=\"attach\" src=\"$attachpic\" border=\"0\" align=\"absmiddle\" title=\"$dfileinfo\" alt=\"$dfileinfo\"> ";
		}
		else if($attachpic)
			echo "<img class=\"attach\" src=\"$attachpic\" border=\"0\" align=\"absmiddle\" title=\"$fileinfo\" alt=\"$fileinfo\"> ";
		else
			echo "&nbsp;";
		echo "$l_attachemen</a>";
		if($displayattachinfo==1)
			echo " [".$fileinfo."]";
		echo "</span></td></tr>";
	}
	if(isset($rate))
	{
		$sql="UPDATE ".$tableprefix."_kb_articles set rating=rating+$rating, ratingcount=ratingcount+1 where (articlenr='$kbnr')";
		if(!$result = faqe_db_query($sql, $db))
		{
			echo "<tr><td bgcolor=\"$heading_bgcolor\" colspan=\"2\">";
		    	die("Could not connect to the database.".faqe_db_error());
		}
	    	if(($ratingcomment==1) && isset($ratingcommenttext) && ($ratingcommenttext))
	    	{
	    		$sql="INSERT into ".$tableprefix."_kb_ratings (rating, articlenr, comment) values($rating, $kbnr, '$ratingcommenttext')";
			if(!$result = faqe_db_query($sql, $db))
			{
				echo "<tr><td bgcolor=\"$heading_bgcolor\">";
			    	die("Could not connect to the database.");
		    	}
	    	}
	    	$ratesql="select rating, ratingcount from ".$tableprefix."_kb_articles where articlenr='$kbnr'";
		if(!$rateresult = faqe_db_query($ratesql, $db))
		{
			echo "<tr><td bgcolor=\"$heading_bgcolor\">";
		    	die("Could not connect to the database.");
	    	}
	    	if($raterow=faqe_db_fetch_array($rateresult))
	    	{
	    		$kbrating=$raterow["rating"];
	    		$kbratingcount=$raterow["ratingcount"];
	    	}
	    	else
	    	{
	    		$kbrating=0;
	    		$kbratingcount=0;
	    	}
	}
	else
	{
		$kbrating=$myrow["rating"];
		$kbratingcount=$myrow["ratingcount"];
	}
	if($ratingspublic==1)
	{
		echo "<TR BGCOLOR=\"$group_bgcolor\" ALIGN=\"RIGHT\" valign=\"middle\">";
		echo "<td align=\"right\" colspan=\"2\">";
		echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
		echo display_ratings($kbrating, $kbratingcount);
		if($ratingcommentpublic==1)
			echo display_kb_ratingcomments($kbnr,$layoutid);
		echo "</span></td></tr>";
	}
	if(($enablekbrating==1) && (!isset($rate)))
	{
		echo "<TR BGCOLOR=\"$group_bgcolor\" ALIGN=\"LEFT\" valign=\"middle\">";
		echo "<form action=\"$act_script_url\" method=\"post\">";
		echo "<td class=\"rateform\" colspan=\"2\" valign=\"middle\">";
		if(isset($limitprog))
			echo "<input type=\"hidden\" name=\"limitprog\" value=\"$limitprog\">";
		if($navframe==1)
			echo "<input type=\"hidden\" name=\"navframe\" value=\"1\">";
		if(isset($layout))
			echo "<input type=\"hidden\" name=\"layout\" value=\"$layout\">";
		echo "<input type=\"hidden\" name=\"prog\" value=\"$prog\">";
		echo "<input type=\"hidden\" name=\"rate\" value=\"1\" alt=\"rate\">";
		echo "<input type=\"hidden\" name=\"$langvar\" value=\"$act_lang\" alt=\"$langvar\">";
		echo "<input type=\"hidden\" name=\"kbnr\" value=\"$kbnr\">";
		echo "<input type=\"hidden\" name=\"mode\" value=\"display\">";
		echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
		echo "$l_ratingprelude ";
		echo "<select class=\"faqeselect2\" name=\"rating\">";
		for($i = 0; $i< count($l_ratings); $i++)
		{
			echo "<option value=\"$i\"";
			if($i==(count($l_ratings)-1))
				echo " selected";
			echo ">".$l_ratings[$i]."</option>";
		}
		echo "</select></span> ";
		if($ratingcomment==1)
		{
			echo "<br>";
			echo "<table width=\"100%\"><tr><td class=\"rateform\" align=\"right\" width=\"5%\" valign=\"top\">";
			echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
			echo "$l_reason:</span></td><td width=\"95%\" class=\"rateform\">";
			echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
			echo "<textarea class=\"faqeinput\" name=\"ratingcommenttext\" rows=\"5\" cols=\"50\"></textarea></span></td></tr></table><br>";
			echo "&nbsp;";
		}
		else
			echo "&nbsp;&nbsp;&nbsp;";
		echo "<input class=\"faqebutton2\" type=\"submit\" value=\"$l_rate\" alt=\"$l_rate\"></font></td></form></tr>";
	}
	if(isset($rate))
	{
		echo "<TR BGCOLOR=\"$group_bgcolor\" ALIGN=\"LEFT\" valign=\"middle\">";
		echo "<td class=\"rating\" align=\"center\" colspan=\"2\">";
		echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
		echo "$l_ratingdone</span></td></tr>";
	}
	echo "<tr bgcolor=\"$actionbgcolor\"><td class=\"actionline\" align=\"center\" colspan=\"2\">";
	echo "<span style=\"font-face: $FontFace; font-size: $actionlinefontsize; color: $FontColor;\">";
	if(isset($backurl))
	{
		echo "<a class=\"actionline\" href=\"$backurl\"";
		if($navframe==1)
			echo " target=\"_parent\"";
		echo ">$l_back</a>";
		echo "&nbsp;|&nbsp;";
	}
	if($navframe==1)
		$link=$url_faqengine."/kbframe.php";
	else
		$link=$act_script_url;
	$link.="?$langvar=$act_lang";
	if(isset($limitprog))
		$link.="&amp;limitprog=$limitprog";
	if(isset($layout))
		$link.="&amp;layout=$layout";
	echo "<a class=\"actionline\" href=\"$link\"";
	if($navframe==1)
		echo " target=\"_parent\"";
	echo ">$l_kb_main</a>";
	echo "</span></td></tr>";

?>
</table></td></tr></table>
