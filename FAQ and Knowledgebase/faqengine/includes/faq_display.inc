<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
	if($display=="faq")
	{
?>
<tr><TD BGCOLOR="<?php echo $table_bgcolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<?php
		if(isset($chfaqnr))
		{
			$sql = "select ref.destfaqnr, dat.category from ".$tableprefix."_faq_ref ref, ".$tableprefix."_data dat where ref.srcfaqnr='$chfaqnr' and ref.language='$lang' and dat.faqnr=ref.destfaqnr";
			if(!$result = faqe_db_query($sql, $db))
			{
				echo "<tr><td bgcolor=\"$heading_bgcolor\">";
		    		die("Could not connect to the database.".faqe_db_error());
		    	}
		    	if(!$myrow=faqe_db_fetch_array($result))
			{
				echo "<tr><td bgcolor=\"$heading_bgcolor\">";
				die("Calling error.");
			}
			$faqnr=$myrow["destfaqnr"];
			$catnr=$myrow["category"];
		}
		if(isset($nr))
			$faqnr=$nr;
		if(!isset($faqnr))
		{
			echo "<tr><td bgcolor=\"$heading_bgcolor\">";
			die("Calling error. No faqnr found");
		}
		if(!isset($catnr))
		{
			echo "<tr><td bgcolor=\"$heading_bgcolor\">";
			die("Calling error. No catnr found");
		}
		$sql = "select * from ".$tableprefix."_category where (catnr='$catnr')";
		if(!$result = faqe_db_query($sql, $db))
		{
			echo "<tr><td bgcolor=\"$heading_bgcolor\">";
	    		die("Could not connect to the database.");
	    	}
		if (!$myrow = faqe_db_fetch_array($result))
		{
			echo "<tr><td bgcolor=\"$heading_bgcolor\">";
			die("Parametererror");
		}
		$catname=display_encoded($myrow["categoryname"]);
		$sql = "select * from ".$tableprefix."_programm where (progid='$prog') and (language='$act_lang')";
		if(!$result = faqe_db_query($sql, $db))
		{
			echo "<tr><td bgcolor=\"$heading_bgcolor\">";
	    		die("Could not connect to the database.");
	    	}
		if (!$myrow = faqe_db_fetch_array($result))
		{
			echo "<tr><td bgcolor=\"$heading_bgcolor\">";
			die("Parametererror");
		}
		$prognr=$myrow["prognr"];
		$progname=display_encoded($myrow["programmname"]);
		$sql = "select * from ".$tableprefix."_data where (faqnr='$faqnr')";
		if(!$result = faqe_db_query($sql, $db))
		{
			echo "<tr><td bgcolor=\"$heading_bgcolor\">";
	    		die("Could not connect to the database.");
	    	}
		if (!$myrow = faqe_db_fetch_array($result))
		{
			echo "<tr><td bgcolor=\"$heading_bgcolor\">";
			die("unkown FAQ-NR");
		}
		if($myrow["linkedfaq"]==0)
			$sql = "UPDATE ".$tableprefix."_data SET views = views + 1 WHERE (faqnr = $faqnr)";
		else
			$sql = "UPDATE ".$tableprefix."_data SET views = views + 1 WHERE (faqnr = ".$myrow["linkedfaq"].")";
		@faqe_db_query($sql, $db);
?>
<tr BGCOLOR="<?php echo $subheadingbgcolor?>" ALIGN="CENTER">
<TD ALIGN="LEFT" VALIGN="MIDDLE" width="98%" class="subheading">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize2?>; color: <?php echo $SubheadingFontColor?>; font-weight: bold;">
<?php
if(bittst($linkoptions,BIT_1))
{
	if($altlinkmethod==0)
	{
		if($navframe==1)
			$proglink=$url_faqengine."/faqframe.php";
		else
			$proglink=$act_script_url;
		if(bittst($linkoptions,BIT_2))
		{
			$proglink.="?list=all&prog=$prog&$langvar=$act_lang";
			$proglinkinfo=$l_listall;
		}
		else
		{
			$proglink.="?list=categories&prog=$prog&$langvar=$act_lang";
			$proglinkinfo=$l_listcats;
		}
		if($navframe==1)
			$catlink=$url_faqengine."/faqframe.php";
		else
			$catlink=$act_script_url;
		$catlink.="?list=category&catnr=$catnr&$langvar=$act_lang&prog=$prog";
		if(isset($limitprog))
		{
			$proglink.="&limitprog=$limitprog";
			$catlink.="&limitprog=$limitprog";
		}
		if(isset($onlynewfaq))
		{
			$proglink.="&onlynewfaq=$onlynewfaq";
			$catlink.="&onlynewfaq=$onlynewfaq";
		}
		if(isset($layout))
		{
			$proglink.="&layout=$layout";
			$catlink.="&layout=$layout";
		}
	}
	else
	{
		if(bittst($linkoptions,BIT_2))
		{
			$proglink="listallfaq.php?id=$prog|$act_lang|$layout";
			$proglinkinfo=$l_listall;
		}
		else
		{
			$proglink="listcats.php?id=$prog|$act_lang|$layout";
			$proglinkinfo=$l_listcats;
		}
		$catlink="listcat.php?id=$catnr|$act_lang|$layout";
	}
	echo "<a title=\"$proglinkinfo\" class=\"subheadinglink\" href=\"$proglink\"";
	if($navframe==1)
		echo " target=\"_parent\"";
	echo ">$progname</a> : ";
	echo "<a title=\"$l_listthiscat\" class=\"subheadinglink\" href=\"$catlink\"";
	if($navframe==1)
		echo " target=\"_parent\"";
	echo ">$catname</a> : ";
	if(($myrow["subcategory"]!=0) && bittst($displayoptions,BIT_1))
	{
		$subcatsql="select * from ".$tableprefix."_subcategory where catnr=".$myrow["subcategory"];
		if(!$subcatresult = faqe_db_query($subcatsql, $db))
		{
			echo "<tr><td bgcolor=\"$heading_bgcolor\">";
			die("Could not connect to the database.");
		}
		if ($subcatrow = faqe_db_fetch_array($subcatresult))
		{
			if($altlinkmethod==0)
			{
				if($navframe==1)
					$subcatlink=$url_faqengine."/faqframe.php";
				else
					$subcatlink=$act_script_url;
				$subcatlink.="?list=subcategory&$langvar=$act_lang&prog=$prog&catnr=$catnr&subcatnr=".$subcatrow["catnr"];
				if(isset($onlynewfaq))
					$subcatlink.="&onlynewfaq=$onlynewfaq";
				if(isset($limitprog))
					$subcatlink.="&limitprog=$limitprog";
				if(isset($layout))
					$subcatlink.="&layout=$layout";
			}
			else
			{
				$subcatlink="listsubcat.php?id=".$subcatrow["catnr"]."|$act_lang|$layout";
			}
			echo "<a title=\"$l_listthissubcat\" class=\"subheadinglink\" href=\"$subcatlink\"";
			if($navframe==1)
				echo " target=\"_parent\"";
			echo ">";
			echo display_encoded($subcatrow["categoryname"])."</a> : ";
		}
	}
}
else
{
	echo "$progname : $catname : ";
	if(($myrow["subcategory"]!=0) && bittst($displayoptions,BIT_1))
	{
		$subcatsql="select * from ".$tableprefix."_subcategory where catnr=".$myrow["subcategory"];
		if(!$subcatresult = faqe_db_query($subcatsql, $db))
		{
			echo "<tr><td bgcolor=\"$heading_bgcolor\">";
			die("Could not connect to the database.");
		}
		if ($subcatrow = faqe_db_fetch_array($subcatresult))
			echo display_encoded($subcatrow["categoryname"])." : ";
	}
}
$displayheading=undo_html_ampersand(stripslashes($myrow["heading"]));
if(isset($highlight) && ($highlight))
	$displayheading = highlight_words($displayheading,$highlight);
echo $displayheading;
$printurl=$url_faqengine."/print.php?faqnr=$faqnr&amp;catnr=$catnr&amp;prog=$prog&amp;$langvar=$act_lang";
if(isset($onlynewfaq))
	$printurl.="&amp;onlynewfaq=$onlynewfaq";
if($navframe==1)
	$printurl.="&amp;nobacklink=1";
if(isset($layout))
	$printurl.="&amp;layout=$layout";
?>
</span></td><td align="right" valign="middle" width="2%" nowrap>
<a class="mainaction" href="<?php echo $printurl?>" <?php if($navframe==1) echo "target=\"faqprint\""?>>
<?php
		if($printpic)
			echo "<img src=\"$printpic\" border=\"0\" align=\"middle\" title=\"$l_print\" alt=\"$l_print\"></a> ";
		else
		{
			echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $HeadingFontColor;\">";
			echo "[$l_print]</span></a> ";
		}
		if($allowemail==1)
		{
			echo "<a class=\"mainaction\" href=\"email.php?nr=$faqnr&amp;catnr=$catnr&amp;prog=$prog&amp;$langvar=$act_lang";
			if(isset($onlynewfaq))
				echo "&amp;onlynewfaq=$onlynewfaq";
			if($navframe==1)
				echo "&amp;navframe=1";
			if(isset($limitprog))
				echo "&amp;limitprog=$limitprog";
			if(isset($layout))
				echo "&amp;layout=$layout";
			echo "\">";
			if($emailpic)
				echo "<img class=\"mainaction\" src=\"$emailpic\" border=\"0\" align=\"middle\" title=\"$l_emailfaq\" alt=\"$l_emailfaq\"></a>";
			else
			{
				echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $HeadingFontColor;\">";
				echo "[$l_emailfaq]</span></a> ";
			}
		}
		if($allowquestions==1)
		{
			echo "<a class=\"mainaction\" href=\"question.php?faqnr=$faqnr&amp;prog=$prog&amp;catnr=$catnr&amp;type=new&amp;$langvar=$act_lang";
			if(isset($onlynewfaq))
				echo "&amp;onlynewfaq=$onlynewfaq";
			if($navframe==1)
				echo "&amp;navframe=1";
			if(isset($limitprog))
				echo "&amp;limitprog=$limitprog";
			if(isset($layout))
				echo "&amp;layout=$layout";
			echo "\">";
			if($questionpic)
				echo "<img class=\"mainaction\" src=\"$questionpic\" border=\"0\" align=\"middle\" title=\"$l_askquestion\" alt=\"$l_askquestion\"></a>";
			else
			{
				echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $HeadingFontColor;\">";
				echo "[$l_askquestion]</span></a> ";
			}
		}
		if($allowusercomments==1)
		{
			echo "<a class=\"mainaction\" href=\"comment.php?prog=$prog&amp;catnr=$catnr&amp;faqnr=$faqnr&amp;mode=write&amp;$langvar=$act_lang";
			if(isset($onlynewfaq))
				echo "&amp;onlynewfaq=$onlynewfaq";
			if($navframe==1)
				echo "&amp;navframe=1";
			if(isset($limitprog))
				echo "&amp;limitprog=$limitprog";
			if(isset($layout))
				echo "&amp;layout=$layout";
			echo "\">";
			if($usercommentpic)
				echo "<img class=\"mainaction\" src=\"$usercommentpic\" border=\"0\" align=\"middle\" title=\"$l_writecomment\" alt=\"$l_writecomment\"></a>";
			else
			{
				echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $HeadingFontColor;\">";
				echo "[$l_writecomment]</span></a>";
			}
		}
?>
</td>
</tr></table></td></tr>
<tr><TD BGCOLOR="<?php echo $table_bgcolor?>">
<TABLE BORDER="0" CELLPADDING="<?php echo $tablepadding?>" CELLSPACING="<?php echo $tablespacing?>" WIDTH="100%">
<?php
		if(isset($onlynewfaq))
			$onlynew=$onlynewfaq;
		else
			$onlynew=0;
		if($myrow["linkedfaq"]!=0)
		{
			$faqsql="select * from ".$tableprefix."_data where faqnr=".$myrow["linkedfaq"];
			if(!$faqresult = faqe_db_query($faqsql, $db))
			{
				echo "<tr><td bgcolor=\"$heading_bgcolor\">";
		    		die("Could not connect to the database.");
		    	}
			if (!$faqrow = faqe_db_fetch_array($faqresult))
			{
				echo "<tr><td bgcolor=\"$heading_bgcolor\">";
				die("dead FAQ link");
			}
		}
		else
			$faqrow=$myrow;
		$questiontext=stripslashes($faqrow["questiontext"]);
		$questiontext=str_replace("{lang}","$langvar=$act_lang",$questiontext);
		$questiontext=str_replace("{url_faqengine}",$url_faqengine,$questiontext);
		$questiontext=str_replace("{onlynewfaq}",$onlynew,$questiontext);
		$questiontext = undo_htmlspecialchars($questiontext);
		$questiontext = str_replace("{bbc_code}",$l_bbccode,$questiontext);
		$questiontext = str_replace("{bbc_quote}",$l_bbcquote,$questiontext);
		if(isset($highlight) && ($highlight))
			$questiontext = highlight_words($questiontext,$highlight);
		$answertext=stripslashes($faqrow["answertext"]);
		$answertext=str_replace("{lang}","$langvar=$act_lang",$answertext);
		$answertext=str_replace("{url_faqengine}",$url_faqengine,$answertext);
		$answertext=str_replace("{onlynewfaq}",$onlynew,$answertext);
		$answertext = undo_htmlspecialchars($answertext);
		$answertext = str_replace("{bbc_code}",$l_bbccode,$answertext);
		$answertext = str_replace("{bbc_quote}",$l_bbcquote,$answertext);
		if(isset($highlight) && ($highlight))
			$answertext = highlight_words($answertext,$highlight);
		echo "<TR BGCOLOR=\"$row_bgcolor\" ALIGN=\"LEFT\">";
		echo "<td class=\"question\" bgcolor=\"$group_bgcolor\" width=\"20%\" valign=\"top\">";
		echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $GroupFontColor; font-weight: bold;\">";
		echo "$l_question:</span></td>";
		echo "<td class=\"question\" width=\"80%\">";
		echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
		echo $questiontext."</span></td></tr>";
		echo "<TR BGCOLOR=\"$row_bgcolor\" ALIGN=\"LEFT\">";
		echo "<td class=\"answer\" bgcolor=\"$group_bgcolor\" width=\"20%\" valign=\"top\">";
		echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $GroupFontColor; font-weight: bold;\">";
		echo "$l_answer:</span></td>";
		echo "<td width=\"80%\" class=\"answer\">";
		echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
		echo $answertext."</span></td></tr>";
		$tempsql="select ver.* from ".$tableprefix."_programm_version ver, ".$tableprefix."_faq_prog_version fpv where fpv.faqnr=".$faqrow["faqnr"]." and ver.entrynr=fpv.progversion";
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
			echo "<td width=\"20%\" class=\"progversion\" valign=\"top\" bgcolor=\"$group_bgcolor\">";
			echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $GroupFontColor; font-weight: bold;\">";
			echo "$l_affectedversions:</span></td>";
			echo "<td width=\"80%\" class=\"progversion\">";
			echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
			echo $displayversions;
			echo "</span></td></tr>";
		}
		$tempsql="select os.* from ".$tableprefix."_os os, ".$tableprefix."_faq_os fos where fos.faqnr=".$faqrow["faqnr"]." and os.osnr=fos.osnr";
		if(!$tempresult=faqe_db_query($tempsql,$db))
		{
			echo "<tr><td bgcolor=\"$heading_bgcolor\">";
	    		die("Could not connect to the database.");
		}
		if($temprow=faqe_db_fetch_array($tempresult))
		{
			$displayos=stripslashes($temprow["osname"]);
			while($temprow=faqe_db_fetch_array($tempresult))
			{
				$displayos.=", ".display_encoded($temprow["osname"]);
			}
			echo "<TR BGCOLOR=\"$row_bgcolor\" ALIGN=\"LEFT\">";
			echo "<td width=\"20%\" class=\"os\" valign=\"top\" bgcolor=\"$group_bgcolor\">";
			echo "<span style=\"font-face: $FontFace; font-size:$FontSize1; color: $GroupFontColor; font-weight: bold;\">";
			echo "$l_affectedos:</span></td>";
			echo "<td width=\"80%\" class=\"os\">";
			echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
			echo $displayos;
			echo "</span></td></tr>";
		}
		$attachsql="select f.filename, f.filesize, f.mimetype, f.description, fa.* from ".$tableprefix."_faq_attachs fa, ".$tableprefix."_files f where f.entrynr=fa.attachnr and fa.faqnr=".$faqrow["faqnr"];
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
			echo "$l_attachement</a>";
			if($displayattachinfo==1)
				echo " [".$fileinfo."]";
			echo "</span></td></tr>";
		}
		echo "<TR BGCOLOR=\"$group_bgcolor\" ALIGN=\"LEFT\">";
		list($year, $month, $day) = explode("-", $faqrow["editdate"]);
		if($month>0)
			$displaydate=date($dateformat,mktime(0,0,0,$month,$day,$year));
		else
			$displaydate="";
		echo "<td class=\"editdate\" colspan=\"2\">";
		echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
		echo "$l_lastupdated $displaydate</span></td></tr>";
	}
	if($allowusercomments==1)
	{
		$tempsql="select * from ".$tableprefix."_comments where faqnr='$faqnr'";
		if(!$tempresult = faqe_db_query($tempsql, $db))
		{
			echo "<tr><td bgcolor=\"$heading_bgcolor\">";
	    		die("Could not connect to the database (1).");
	    	}
		if ($temprow = faqe_db_fetch_array($tempresult))
		{
			echo "<tr bgcolor=\"$row_bgcolor\"><td class=\"comments\" colspan=\"2\">";
			echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
			echo faqe_db_num_rows($tempresult)." $l_usercomments";
			echo "&nbsp;&nbsp;&nbsp;(<a class=\"comments\" href=\"comment.php?prog=$prog&amp;catnr=$catnr&amp;faqnr=$faqnr&amp;mode=display&amp;$langvar=$act_lang\">$l_display</a>)</span></td></tr>";
		}
	}
	if($allowquestions==1)
	{
		$tempsql="select * from ".$tableprefix."_questions where faqref=$faqnr and publish=1 and questionref=0";
		if(!$tempresult = faqe_db_query($tempsql, $db))
		{
			echo "<tr><td bgcolor=\"$heading_bgcolor\">";
	    		die("Could not connect to the database (2).".faqe_db_error());
	    	}
		if ($temprow = faqe_db_fetch_array($tempresult))
		{
			echo "<tr bgcolor=\"$row_bgcolor\"><td class=\"userquestions\" colspan=\"2\">";
			echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
			echo faqe_db_num_rows($tempresult)." $l_userquestions";
			echo "&nbsp;&nbsp;&nbsp;(<a class=\"userquestions\" href=\"question.php?prog=$prog&amp;catnr=$catnr&amp;faqnr=$faqnr&amp;mode=display&amp;$langvar=$act_lang\">$l_display</a>)</span></td></tr>";
		}
	}
	if(isset($rate))
	{
		$sql="UPDATE ".$tableprefix."_data set rating=rating+$rating, ratingcount=ratingcount+1 where (faqnr='$faqnr')";
		if(!$result = faqe_db_query($sql, $db))
		{
			echo "<tr><td bgcolor=\"$heading_bgcolor\">";
		    	die("Could not connect to the database.");
	    	}
	    	if(($ratingcomment==1) && isset($ratingcommenttext) && ($ratingcommenttext))
	    	{
	    		$sql="INSERT into ".$tableprefix."_ratings (rating, faqnr, comment) values($rating, $faqnr, '$ratingcommenttext')";
			if(!$result = faqe_db_query($sql, $db))
			{
				echo "<tr><td bgcolor=\"$heading_bgcolor\">";
			    	die("Could not connect to the database.");
		    	}
	    	}
	    	$ratesql="select rating, ratingcount from ".$tableprefix."_data where faqnr='$faqnr'";
		if(!$rateresult = faqe_db_query($ratesql, $db))
		{
			echo "<tr><td bgcolor=\"$heading_bgcolor\">";
		    	die("Could not connect to the database.");
	    	}
	    	if($raterow=faqe_db_fetch_array($rateresult))
	    	{
	    		$faqrating=$raterow["rating"];
	    		$faqratingcount=$raterow["ratingcount"];
	    	}
	    	else
	    	{
	    		$faqrating=0;
	    		$faqratingcount=0;
	    	}
	}
	else
	{
		$faqrating=$myrow["rating"];
		$faqratingcount=$myrow["ratingcount"];
	}
	if($ratingspublic==1)
	{
		echo "<TR BGCOLOR=\"$group_bgcolor\" ALIGN=\"RIGHT\" valign=\"middle\">";
		echo "<td align=\"right\" colspan=\"2\" class=\"rating\">";
		echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
		echo display_ratings($faqrating, $faqratingcount);
		if($ratingcommentpublic==1)
			echo display_faq_ratingcomments($faqnr,$layoutid);
		echo "</span></td></tr>";
	}
	if(isset($rate))
	{
		echo "<TR BGCOLOR=\"$group_bgcolor\" ALIGN=\"LEFT\" valign=\"middle\">";
		echo "<td align=\"center\" colspan=\"2\" class=\"rating\">";
		echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
		echo "$l_ratingdone</span></td></tr>";
	}
	if(($displayrating==1) && (!isset($rate)))
	{
		echo "<TR BGCOLOR=\"$row_bgcolor\" ALIGN=\"LEFT\" valign=\"middle\">";
		echo "<form action=\"$act_script_url\" method=\"post\">";
		echo "<td class=\"rateform\" colspan=\"2\" valign=\"middle\">";
		if($navframe==1)
		{
			echo "<input type=\"hidden\" name=\"navframe\" value=\"1\">";
			if(isset($limitprog))
				echo "<input type=\"hidden\" name=\"limitprog\" value=\"$limitprog\">";
		}
		if(isset($layout))
			echo "<input type=\"hidden\" name=\"layout\" value=\"$layout\">";
		echo "<input type=\"hidden\" name=\"display\" value=\"faq\">";
		echo "<input type=\"hidden\" name=\"rate\" value=\"1\">";
		echo "<input type=\"hidden\" name=\"lang\" value=\"$act_lang\">";
		echo "<input type=\"hidden\" name=\"prog\" value=\"$prog\">";
		if(isset($onlynewfaq))
			echo "<input type=\"hidden\" name=\"onlynewfaq\" value=\"$onlynewfaq\">";
		echo "<input type=\"hidden\" name=\"faqnr\" value=\"$faqnr\">";
		echo "<input type=\"hidden\" name=\"catnr\" value=\"$catnr\">";
		echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
		echo "&nbsp;$l_ratingprelude&nbsp;";
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
			echo "<table width=\"100%\"><tr><td class=\"rateform\" align=\"right\" width=\"6%\" valign=\"top\">";
			echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
			echo "$l_reason:</span></td><td width=\"94%\" class=\"rateform\">";
			echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
			echo "<textarea class=\"faqeinput\" name=\"ratingcommenttext\" rows=\"5\" cols=\"50\"></textarea></span>";
			echo "</td></tr></table><br>&nbsp;";
		}
		else
			echo "&nbsp;&nbsp;&nbsp;";
		echo"<input class=\"faqebutton2\" type=\"submit\" value=\"$l_rate\"></td></form></tr>";
	}
	echo "</table></td></tr>";
?>
<tr><TD BGCOLOR="<?php echo $table_bgcolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<?php
	if($shownextprev==1)
	{
		echo "<TR BGCOLOR=\"$actionbgcolor\" ALIGN=\"LEFT\">";
		echo "<td align=\"center\" colspan=\"2\">";
		echo "<span style=\"font-face: $FontFace; font-size: $actionlinefontsize; color: FontColor;\">";
		$tmpsql="select * from ".$tableprefix."_data where category=".$myrow["category"]." and displaypos=".($myrow["displaypos"]-1);
		if(!$tmpresult = faqe_db_query($tmpsql, $db))
			die("Could not connect to the database.");
		if($tmprow=faqe_db_fetch_array($tmpresult))
		{
			if($navframe==1)
				$linkurl=$url_faqengine."/faqframe.php";
			else
				$linkurl=$act_script_url;
			$linkurl.="?$langvar=$act_lang&amp;display=faq&amp;catnr=$catnr&amp;prog=$prog&amp;faqnr=".$tmprow["faqnr"];
			if(isset($onlynewfaq))
				$linkurl.="&amp;onlynewfaq=$onlynewfaq";
			if(isset($limitprog))
				$linkurl.="&amp;limitprog=$limitprog";
			if(isset($layout))
				$linkurl.="&amp;layout=$layout";
			echo "<a class=\"actionline\" href=\"$linkurl\"";
			if($navframe==1)
				echo " target=\"_parent\"";
			echo "<a class=\"actionline\" href=\"$linkurl\"";
			echo ">[$l_prev_faq]</a>&nbsp;&nbsp; ";
		}
		else if($nextprevmode==1)
		{
			$tmpsql="select * from ".$tableprefix."_category where catnr=".$myrow["category"];
			if(!$tmpresult = faqe_db_query($tmpsql, $db))
				die("Could not connect to the database.");
			if($tmprow=faqe_db_fetch_array($tmpresult))
				$catdisplaypos=$tmprow["displaypos"];
			else
				$catdisplaypos=0;
			if($catdisplaypos>0)
			{
				$tmpsql="select cat.* from ".$tableprefix."_category cat, ".$tableprefix."_programm prog where prog.progid='$prog' and prog.language='$act_lang' and cat.programm=prog.prognr and cat.displaypos=".($catdisplaypos-1);
				if(!$tmpresult = faqe_db_query($tmpsql, $db))
					die("Could not connect to the database.");
				if($tmprow=faqe_db_fetch_array($tmpresult))
				{
					$newcat=$tmprow["catnr"];
					$tmpsql="select * from ".$tableprefix."_data where category=$newcat order by displaypos desc";
					if(!$tmpresult = faqe_db_query($tmpsql, $db))
						die("Could not connect to the database.");
					if($tmprow=faqe_db_fetch_array($tmpresult))
					{
						if($navframe==1)
							$linkurl=$url_faqengine."/faqframe.php";
						else
							$linkurl=$act_script_url;
						$linkurl.="?$langvar=$act_lang&amp;display=faq&amp;catnr=$catnr&amp;prog=$prog&amp;faqnr=".$tmprow["faqnr"];
						if(isset($onlynewfaq))
							$linkurl.="&amp;onlynewfaq=$onlynewfaq";
						if(isset($limitprog))
							$linkurl.="&amp;limitprog=$limitprog";
						if(isset($layout))
							$linkurl.="&amp;layout=$layout";
						echo "<a class=\"actionline\" href=\"$linkurl\"";
						if($navframe==1)
							echo " target=\"_parent\"";
						echo ">[$l_prev_faq]</a>&nbsp;&nbsp; ";
					}
				}
			}
		}
		$tmpsql="select * from ".$tableprefix."_data where category=".$myrow["category"]." and displaypos=".($myrow["displaypos"]+1);
		if(!$tmpresult = faqe_db_query($tmpsql, $db))
			die("Could not connect to the database.");
		if($tmprow=faqe_db_fetch_array($tmpresult))
		{
			if($navframe==1)
				$linkurl=$url_faqengine."/faqframe.php";
			else
				$linkurl=$act_script_url;
			$linkurl.="?$langvar=$act_lang&amp;display=faq&amp;catnr=$catnr&amp;prog=$prog&amp;faqnr=".$tmprow["faqnr"];
			if(isset($onlynewfaq))
				$linkurl.="&amp;onlynewfaq=$onlynewfaq";
			if(isset($limitprog))
				$linkurl.="&amp;limitprog=$limitprog";
			if(isset($layout))
				$linkurl.="&amp;layout=$layout";
			echo "<a class=\"actionline\" href=\"$linkurl\"";
			if($navframe==1)
				echo " target=\"_parent\"";
			echo ">[$l_next_faq]</a>";
		}
		else if($nextprevmode==1)
		{
			$tmpsql="select * from ".$tableprefix."_category where catnr=".$myrow["category"];
			if(!$tmpresult = faqe_db_query($tmpsql, $db))
				die("Could not connect to the database.");
			if($tmprow=faqe_db_fetch_array($tmpresult))
				$catdisplaypos=$tmprow["displaypos"];
			else
				$catdisplaypos=0;
			if($catdisplaypos>0)
			{
				$tmpsql="select cat.* from ".$tableprefix."_category cat, ".$tableprefix."_programm prog where prog.progid='$prog' and prog.language='$act_lang' and cat.programm=prog.prognr and cat.displaypos=".($catdisplaypos+1);
				if(!$tmpresult = faqe_db_query($tmpsql, $db))
					die("Could not connect to the database.");
				if($tmprow=faqe_db_fetch_array($tmpresult))
				{
					$newcat=$tmprow["catnr"];
					$tmpsql="select * from ".$tableprefix."_data where category=$newcat order by displaypos asc";
					if(!$tmpresult = faqe_db_query($tmpsql, $db))
						die("Could not connect to the database.");
					if($tmprow=faqe_db_fetch_array($tmpresult))
					{
						if($navframe==1)
							$linkurl=$url_faqengine."/faqframe.php";
						else
							$linkurl=$act_script_url;
						$linkurl.="?$langvar=$act_lang&amp;display=faq&amp;catnr=$catnr&amp;prog=$prog&amp;faqnr=".$tmprow["faqnr"];
						if(isset($onlynewfaq))
							$linkurl.="&amp;onlynewfaq=$onlynewfaq";
						if(isset($limitprog))
							$linkurl.="&amp;limitprog=$limitprog";
						if(isset($layout))
							$linkurl.="&amp;layout=$layout";
						echo "<a class=\"actionline\" href=\"$linkurl\"";
						if($navframe==1)
							echo " target=\"_parent\"";
						echo ">[$l_next_faq]</a>&nbsp;&nbsp; ";
					}
				}
			}
		}
		echo "</span></td></tr>";
	
	}
?>
<TR BGCOLOR="<?php echo $actionbgcolor?>" ALIGN="LEFT">
<td class="actionline" align="center" width="99%">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $actionlinefontsize?>; color: <?php echo $FontColor?>;">
<?php
	if($altlinkmethod==0)
	{
		if($navframe==1)
			$linkurl=$url_faqengine."/faqframe.php";
		else
			$linkurl=$act_script_url;
		$linkurl.="?list=category&amp;catnr=$catnr&amp;$langvar=$act_lang";
		if(isset($onlynewfaq))
			$linkurl.="&amp;onlynewfaq=$onlynewfaq";
		if(isset($limitprog))
			$linkurl.="&amp;limitprog=$limitprog";
		if(isset($layout))
			$linkurl.="&amp;layout=$layout";
	}
	else
	{
		$linkurl="listcat.php?id=$catnr|$act_lang|$layout";
	}
	echo "<a class=\"actionline\" href=\"$linkurl\"";
	if($navframe==1)
		echo " target=\"_parent\"";
	echo ">$l_listthiscat</a>";
	if($altlinkmethod==0)
	{
		if($navframe==1)
			$linkurl=$url_faqengine."/faqframe.php";
		else
			$linkurl=$act_script_url;
		$linkurl.="?list=categories&amp;prog=$prog&amp;$langvar=$act_lang";
		if(isset($onlynewfaq))
			$linkurl.="&amp;onlynewfaq=$onlynewfaq";
		if(isset($limitprog))
			$linkurl.="&amp;limitprog=$limitprog";
		if(isset($layout))
			$linkurl.="&amp;layout=$layout";
	}
	else
	{
		$linkurl="listcats.php?id=$prog|$act_lang|$layout";
	}
	echo " | <a class=\"actionline\" href=\"$linkurl\"";
	if($navframe==1)
		echo " target=\"_parent\"";
	echo ">$l_listcats</a>";
	if($altlinkmethod==0)
	{
		if($navframe==1)
			$linkurl=$url_faqengine."/faqframe.php";
		else
			$linkurl=$act_script_url;
		$linkurl.="?list=all&amp;prog=$prog&amp;$langvar=$act_lang";
		if(isset($onlynewfaq))
			$linkurl.="&amp;onlynewfaq=$onlynewfaq";
		if(isset($limitprog))
			$linkurl.="&amp;limitprog=$limitprog";
		if(isset($layout))
			$linkurl.="&amp;layout=$layout";
	}
	else
	{
		$linkurl="listallfaq.php?id=$prog|$act_lang|$layout";
	}
	echo " | <a class=\"actionline\" href=\"$linkurl\"";
	if($navframe==1)
		echo " target=\"_parent\"";
	echo ">$l_listall</a>";
	if($show_proglist==1)
	{
		if($altlinkmethod==0)
		{
			if($navframe==1)
				$linkurl=$url_faqengine."/faqframe.php";
			else
				$linkurl=$act_script_url;
			$linkurl.="?list=progs&amp;$langvar=$act_lang";
			if(isset($onlynewfaq))
				$linkurl.="&amp;onlynewfaq=$onlynewfaq";
			if(isset($limitprog))
				$linkurl.="&amp;limitprog=$limitprog";
			if(isset($layout))
				$linkurl.="&amp;layout=$layout";
		}
		else
		{
			$linkurl="listprogs.php?id=$act_lang|$layout";
		}
		echo " | <a class=\"actionline\" href=\"$linkurl\"";
		if($navframe==1)
			echo " target=\"_parent\"";
		echo ">$l_listprogs</a>";
	}
?>
</span></td>
</td>
<td class="gototop" align="right" width="1%">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $actionlinefontsize?>;">
<a href="#top"><img class="gototop" src="<?php echo $pagetoppic?>" border="0" align="middle" title="<?php echo $l_top?>" alt="<?php echo $l_top?>"></a>
</span></td>
</tr></table></td></tr>
<?php
		if($enablejumpboxes==1)
		{
?>
<tr><TD BGCOLOR="<?php echo $table_bgcolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<?php
			echo "<tr BGCOLOR=\"$actionbgcolor\">";
			if($show_proglist==1)
			{
				$sql = "select * from ".$tableprefix."_programm where language='$act_lang' ";
				switch($jumpboxsorting)
				{
					case 1:
						$sql.="order by programmname asc";
						break;
					case 2:
						$sql.="order by displaypos asc";
						break;
					default:
						$sql.="order by prognr asc";
				}
				if(!$result = faqe_db_query($sql, $db))
				{
					echo "<tr><td bgcolor=\"$group_bgcolor\">";
		    			die("Could not connect to the database.");
		    		}
				if($myrow=faqe_db_fetch_array($result))
				{
					if($navframe==1)
						$action_url=$url_faqengine."/faqframe.php";
					else
						$action_url=$act_script_url;
					echo "<form method=\"post\" action=\"$action_url\"";
					if($navframe==1)
						echo " target=\"_parent\"";
					echo ">";
					echo "<td class=\"jumpbox\" align=\"left\" width=\"50%\">";
					echo "<span style=\"font-face: $FontFace; font-size: $jumpboxfontsize; color: $FontColor; font-weight: bold;\">";
					echo "$l_program: ";
					echo "<input type=\"hidden\" name=\"$langvar\" value=\"$act_lang\">";
					if(($navframe==1) && isset($limitprog))
						echo "<input type=\"hidden\" name=\"limitprog\" value=\"$limitprog\">";
					if(isset($onlynewfaq))
						echo "<input type=\"hidden\" name=\"onlynewfaq\" value=\"$onlynewfaq\">";
					if(isset($layout))
						echo "<input type=\"hidden\" name=\"layout\" value=\"$layout\">";
					echo "<input type=\"hidden\" name=\"list\" value=\"all\">";
					echo "<select class=\"jumpbox\" name=\"prog\">";
					do{
						echo "<option value=\"".$myrow["progid"]."\"";
						if($myrow["progid"]==$prog)
							echo " selected";
						echo ">".display_encoded($myrow["programmname"])."</option>";
					}while($myrow=faqe_db_fetch_array($result));
					echo "</select>&nbsp;&nbsp;";
					echo "<input class=\"jumpbox\" type=\"submit\" value=\"$l_change\">";
					echo "</span></td></form>";
				}
			}
			else
				echo "<td width=\"50%\">&nbsp;</td>";
			if($navframe==1)
				$action_url=$url_faqengine."/faqframe.php";
			else
				$action_url=$act_script_url;
			echo "<form method=\"post\" action=\"$action_url\"";
			if($navframe==1)
				echo " target=\"_parent\"";
			echo ">";
			echo "<td class=\"jumpbox\" align=\"right\">";
			echo "<span style=\"font-face: $FontFace; font-size: $jumpboxfontsize; color: $FontColor; font-weight:bold;\">";
			echo "<input type=\"hidden\" name=\"$langvar\" value=\"$act_lang\">";
			if(isset($limitprog))
				echo "<input type=\"hidden\" name=\"limitprog\" value=\"$limitprog\">";
			if(isset($onlynewfaq))
				echo "<input type=\"hidden\" name=\"onlynewfaq\" value=\"$onlynewfaq\">";
			if(isset($layout))
				echo "<input type=\"hidden\" name=\"layout\" value=\"$layout\">";
			echo "<input type=\"hidden\" name=\"list\" value=\"category\">";
			echo "<input type=\"hidden\" name=\"prog\" value=\"$prog\">";
			$sql = "select cat.* from ".$tableprefix."_category cat, ".$tableprefix."_programm prog where prog.progid='$prog' and prog.language='$act_lang' and cat.programm=prog.prognr ";
			switch($jumpboxsorting)
			{
				case 1:
					$sql.="order by cat.categoryname asc";
					break;
				case 2:
					$sql.="order by cat.displaypos asc";
					break;
				default:
					$sql.="order by cat.catnr asc";
			}
			if(!$result = faqe_db_query($sql, $db))
			{
		    		die("Could not connect to the database.");
		    	}
			if($myrow=faqe_db_fetch_array($result))
			{
				echo "$l_category: ";
				echo "<select class=\"jumpbox\" name=\"catnr\">";
				do{
					echo "<option value=\"".$myrow["catnr"]."\"";
					if($myrow["catnr"]==$catnr)
						echo " selected";
					echo ">".display_encoded($myrow["categoryname"])."</option>";
				}while($myrow=faqe_db_fetch_array($result));
				echo "</select>&nbsp;&nbsp;";
			}
			echo "<input class=\"jumpbox\" type=\"submit\" value=\"$l_change\">";
			echo "</span></td></form></tr>";
			echo "</table></td></tr>";
		}
		if($displayrelated==1)
		{
			$sql = "select dat.* from ".$tableprefix."_data dat, ".$tableprefix."_related_faq rf where rf.srcfaq=$faqnr and dat.faqnr=rf.destfaq ";
			switch($jumpboxsorting)
			{
				case 1:
					$sql.="order by dat.heading asc";
					break;
				case 2:
					$sql.="order by dat.displaypos asc";
					break;
				default:
					$sql.="order by dat.faqnr asc";
			}
			if(!$result = faqe_db_query($sql, $db))
			{
				echo "<tr><td bgcolor=\"$group_bgcolor\">";
			    	die("Could not connect to the database.".faqe_db_error());
			}
			if($myrow=faqe_db_fetch_array($result))
			{
				echo "<tr><TD BGCOLOR=\"$table_bgcolor\">";
				echo "<TABLE BORDER=\"0\" CELLPADDING=\"1\" CELLSPACING=\"1\" WIDTH=\"100%\">";
				echo "<tr BGCOLOR=\"$actionbgcolor\">";
				if($navframe==1)
					$action_url=$url_faqengine."/faqframe.php";
				else
					$action_url=$act_script_url;
				echo "<form method=\"post\" action=\"$action_url\"";
				if($navframe==1)
					echo " target=\"_parent\"";
				echo ">";
				echo "<td class=\"jumpbox\" align=\"right\">";
				echo "<span style=\"font-face: $FontFace; font-size: $jumpboxfontsize; color: $FontColor; font-weight: bold;\">";
				if(($navframe==1) && isset($limitprog))
					echo "<input type=\"hidden\" name=\"limitprog\" value=\"$limitprog\">";
				echo "<input type=\"hidden\" name=\"$langvar\" value=\"$act_lang\">";
				if(isset($onlynewfaq))
					echo "<input type=\"hidden\" name=\"onlynewfaq\" value=\"$onlynewfaq\">";
				if(isset($layout))
					echo "<input type=\"hidden\" name=\"layout\" value=\"$layout\">";
				echo "<input type=\"hidden\" name=\"display\" value=\"faq\">";
				echo "<input type=\"hidden\" name=\"prog\" value=\"$prog\">";
				echo "<input type=\"hidden\" name=\"catnr\" value=\"$catnr\">";
				echo "$l_relatedfaqs: ";
				echo "<select class=\"related\" name=\"faqnr\">";
				do{
					$displayheading=stripslashes($myrow["heading"]);
					$displayheading=undo_html_ampersand($displayheading);
					echo "<option value=\"".$myrow["faqnr"]."\"";
					echo ">$displayheading</option>";
				}while($myrow=faqe_db_fetch_array($result));
				echo "</select>&nbsp;&nbsp;";
				echo "<input class=\"jumpbox\" type=\"submit\" value=\"$l_change\">";
				echo "</span></td></form></tr>";
				echo "</table></td></tr>";
			}
		}
		echo "</table>";
		if($enablelanguageselector==1)
		{
			$availlangs=language_list();
			if(count($availlangs)>0)
			{
				$sql = "select * from ".$tableprefix."_faq_ref where srcfaqnr='$faqnr' and language in (";
				$first=true;
				for($i=0;$i<count($availlangs);$i++)
				{
					if($first)
						$first=false;
					else
						$sql.=", ";
					$sql.="'".$availlangs[$i]."'";
				}
				$sql.=")";
				if(!$result = faqe_db_query($sql, $db))
				{
					echo "<tr><td bgcolor=\"$group_bgcolor\">";
			    		die("Could not connect to the database.".faqe_db_error());
			    	}
				if($myrow=faqe_db_fetch_array($result))
				{
					echo "<table style=\"clear:both\" width=\"$TableWidth\" align=\"$tblalign\" border=\"0\">";
					if($navframe==1)
						$action_url=$url_faqengine."/faqframe.php";
					else
						$action_url=$act_script_url;
					echo "<form method=\"post\" action=\"$action_url\"";
					if($navframe==1)
						echo " target=\"_parent\"";
					echo ">";
					echo "<tr bgcolor=\"$group_bgcolor\"><td class=\"langselect\" align=\"center\">";
					if(isset($onlynewfaq))
						echo "<input type=\"hidden\" name=\"onlynewfaq\" value=\"$onlynewfaq\">";
					if(isset($limitprog))
						echo "<input type=\"hidden\" name=\"limitprog\" value=\"$limitprog\">";
					if(isset($layout))
						echo "<input type=\"hidden\" name=\"layout\" value=\"$layout\">";
					echo "<input type=\"hidden\" name=\"display\" value=\"faq\">";
					echo "<input type=\"hidden\" name=\"prog\" value=\"$prog\">";
					echo "<input type=\"hidden\" name=\"chfaqnr\" value=\"$faqnr\">";
					echo "<span style=\"font-face: $FontFace; font-size: $langselectfontsize; color: $FontColor;\">";
					echo "$l_changelang: ";
					echo "<select class=\"langselect\" name=\"lang\">";
					do{
						echo "<option value=\"".$myrow["language"]."\"";
						if($myrow["language"]==$act_lang)
							echo " selected";
						echo ">".$myrow["language"]."</option>";
					}while($myrow=faqe_db_fetch_array($result));
					echo "</select>";
					echo "&nbsp;&nbsp;&nbsp;<input class=\"langselect\" type=\"submit\" value=\"$l_ok\">";
					echo "</span></td></tr></form></table>\n";
				}
			}
		}
?>