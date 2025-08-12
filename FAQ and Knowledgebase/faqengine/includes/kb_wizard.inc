<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
if(isset($prog) && ($prog))
{
	$sql = "select * from ".$tableprefix."_programm where progid='$prog' and language='$act_lang'";
	if(!$result=faqe_db_query($sql,$db))
		die("Unable to connect to database");
	if(!$myrow=faqe_db_fetch_array($result))
		die("<tr bgcolor=\"$row_bgcolor\"><td align=\"center\" colspan=\"2\">$l_nosuchprog");
?>
<tr bgcolor="<?php echo $subheadingbgcolor?>"><td align="center" colspan="2">
<span style="color: <?php echo $SubheadingFontColor?>; font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize2?>; font-weight: bold;">
<?php echo $l_program?>: <?php echo $myrow["programmname"]?>
</span></td></tr>
<?php
	$programm=$myrow["prognr"];
}
else
{
	if(!isset($programm))
		$actprog=-1;
	else
		$actprog=$programm;
	$sql = "select * from ".$tableprefix."_programm where language='$act_lang'";
	if(!$result=faqe_db_query($sql,$db))
		die("Unable to connect to database");
	if(!$myrow=faqe_db_fetch_array($result))
		die("<tr bgcolor=\"$row_bgcolor\"><td align=\"center\" colspan=\"2\">$l_noprogsdefined");
?>
<form method="post" action="<?php echo $form_url?>" target="<?php echo $form_target?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="mode" value="wizard">
<?php
	if(isset($layout))
		echo "<input type=\"hidden\" name=\"layout\" value=\"$layout\">";
	if(isset($limitprog))
		echo "<input type=\"hidden\" name=\"limitprog\" value=\"$limitprog\">";
	if(is_konqueror())
		echo "<tr><td></td></tr>";
	echo "<tr bgcolor=\"$row_bgcolor\"><td class=\"kbwizard\" align=\"right\" width=\"30%\">";
	echo "<span style=\"color: $FontColor; font-face: $FontFace; font-size: $FontSize1;\">";
	echo "$l_select_prog:</span></td>";
	echo "<td class=\"kbwizard\" align=\"left\" width=\"70%\">";
	echo "<span style=\"color: $FontColor; font-face: $FontFace; font-size: $FontSize1;\">";
	echo "<select class=\"faqeselect\" name=\"programm\">";
	echo "<option value=\"-1\">???</option>";
	do{
		echo "<option value=\"".$myrow["prognr"]."\"";
		if($myrow["prognr"]==$actprog)
			echo " selected";
		echo ">".display_encoded($myrow["programmname"])."</option>";
	}while($myrow=faqe_db_fetch_array($result));
	echo "</select>";
	echo " <input class=\"faqebutton\" type=\"submit\" value=\"$l_ok\">";
	echo "</span></td></tr>";
?>
</form>
<?php
}
if(isset($programm) && ($programm>0))
{
	if(!isset($category))
		$actcat=-1;
	else
		$actcat=$category;
?>
<form method="post" action="<?php echo $form_url?>" target="<?php echo $form_target?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="mode" value="wizard">
<input type="hidden" name="programm" value="<?php echo $programm?>">
<?php
	if(isset($layout))
		echo "<input type=\"hidden\" name=\"layout\" value=\"$layout\">";
	if(isset($limitprog))
		echo "<input type=\"hidden\" name=\"limitprog\" value=\"$limitprog\">";
	if(is_konqueror())
		echo "<tr><td></td></tr>";
	$sql="select * from ".$tableprefix."_prog_os where prognr='$programm'";
	if(!$result=faqe_db_query($sql,$db))
		die("Unable to connect to database");
	if(faqe_db_num_rows($result)==0)
		echo "<input type=\"hidden\" name=\"os\" value=\"0\">";
	$sql="select * from ".$tableprefix."_programm_version where programm=$programm";
	if(!$result=faqe_db_query($sql,$db))
		die("Unable to connect to database");
	if(faqe_db_num_rows($result)==0)
		echo "<input type=\"hidden\" name=\"progversion\" value=\"0\">";	
	echo "<tr bgcolor=\"$row_bgcolor\"><td class=\"kbwizard\" align=\"right\" width=\"30%\">";
	echo "<span style=\"color: $FontColor; font-face: $FontFace; font-size: $FontSize1;\">";
	echo "$l_select_cat:</span></td>";
	echo "<td class=\"kbwizard\" align=\"left\" width=\"70%\">";
	echo "<span style=\"color: $FontColor; font-face: $FontFace; font-size: $FontSize1;\">";
	echo "<select class=\"faqeselect\" name=\"category\">";
	echo "<option value=\"-1\">???</option>";
	echo "<option value=\"0\"";
	if($actcat==0)
		echo " selected";
	echo ">$l_none</option>";
	$sql = "select * from ".$tableprefix."_kb_cat where programm=$programm";
	if(!$result=faqe_db_query($sql,$db))
		die("Unable to connect to database");
	if($myrow=faqe_db_fetch_array($result))
	{
		do{
			$displayheading = display_encoded($myrow["heading"]);
			echo "<option value=\"".$myrow["catnr"]."\"";
			if($myrow["catnr"]==$actcat)
				echo " selected";
			echo ">".$displayheading."</option>";
		}while($myrow=faqe_db_fetch_array($result));
	}
	echo "</select>";
	echo " <input class=\"faqebutton\" type=\"submit\" value=\"$l_ok\">";
	echo "</span></td></tr>";
?>
</form>
<?php
	}
	if(isset($category) && isset($programm) && ($category>=0))
	{
		if($category==0)
			$subcategory=-1;
		else
		{
			if(!isset($subcategory))
				$actsubcat=-2;
			else
				$actsubcat=$subcategory;
?>
<form method="post" action="<?php echo $form_url?>" target="<?php echo $form_target?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="mode" value="wizard">
<input type="hidden" name="programm" value="<?php echo $programm?>">
<input type="hidden" name="category" value="<?php echo $category?>">
<?php
			if(isset($layout))
				echo "<input type=\"hidden\" name=\"layout\" value=\"$layout\">";
			if(isset($limitprog))
				echo "<input type=\"hidden\" name=\"limitprog\" value=\"$limitprog\">";
			$sql="select * from ".$tableprefix."_programm_version where programm=$programm";
			if(!$result=faqe_db_query($sql,$db))
				die("Unable to connect to database");
			if(faqe_db_num_rows($result)==0)
				echo "<input type=\"hidden\" name=\"progversion\" value=\"0\">";	
			$sql="select * from ".$tableprefix."_prog_os where prognr='$programm'";
			if(!$result=faqe_db_query($sql,$db))
				die("Unable to connect to database");
			if(faqe_db_num_rows($result)==0)
				echo "<input type=\"hidden\" name=\"os\" value=\"0\">";
			if(is_konqueror())
				echo "<tr><td></td></tr>";
			echo "<tr bgcolor=\"$row_bgcolor\"><td class=\"kbwizard\" align=\"right\" width=\"30%\">";
			echo "<span style=\"color: $FontColor; font-face: $FontFace; font-size: $FontSize1;\">";
			echo "$l_select_subcat:</span></td>";
			echo "<td class=\"kbwizard\" align=\"left\" width=\"70%\">";
			echo "<span style=\"color: $FontColor; font-face: $FontFace; font-size: $FontSize1;\">";
			echo "<select class=\"faqeselect\" name=\"subcategory\">";
			echo "<option value=\"-2\">???</option>";
			echo "<option value=\"-1\"";
			if($actsubcat==-1)
				echo " selected";
			echo ">$l_all</option>";
			echo "<option value=\"0\"";
			if($actsubcat==0)
				echo " selected";
			echo ">$l_none</option>";
			if($category>0)
				$sql = "select * from ".$tableprefix."_kb_subcat where category=$category";
			else
				$sql = "select subcat.* from ".$tableprefix."_kb_subcat subcat, ".$tableprefix."_kb_cat cat where cat.programm=$programm and subcat.category=cat.catnr";
			if(!$result=faqe_db_query($sql,$db))
				die("Unable to connect to database");
			if($myrow=faqe_db_fetch_array($result))
			{
				do{
					$displayheading = display_encoded($myrow["heading"]);
					echo "<option value=\"".$myrow["catnr"]."\"";
					if($myrow["catnr"]==$actsubcat)
						echo " selected";
					echo ">".$displayheading."</option>";
				}while($myrow=faqe_db_fetch_array($result));
			}
			echo "</select>";
			echo " <input class=\"faqebutton\" type=\"submit\" value=\"$l_ok\">";
			echo "</span></td></tr>";
?>
</form>
<?php
		}
	}
	if(isset($category) && isset($programm) && isset($subcategory) && ($category>=0) && ($subcategory>=-1))
	{
		$sql="select * from ".$tableprefix."_prog_os where prognr='$programm'";
		if(!$result=faqe_db_query($sql,$db))
			die("Unable to connect to database");
		if(faqe_db_num_rows($result)>0)
		{
			if(!isset($os))
				$actos=-1;
			else
				$actos=$os;
?>
<form method="post" action="<?php echo $form_url?>" target="<?php echo $form_target?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="mode" value="wizard">
<input type="hidden" name="programm" value="<?php echo $programm?>">
<input type="hidden" name="category" value="<?php echo $category?>">
<input type="hidden" name="subcategory" value="<?php echo $subcategory?>">
<?php
			if(isset($layout))
				echo "<input type=\"hidden\" name=\"layout\" value=\"$layout\">";
			if(isset($limitprog))
				echo "<input type=\"hidden\" name=\"limitprog\" value=\"$limitprog\">";
			$sql="select * from ".$tableprefix."_programm_version where programm=$programm";
			if(!$result=faqe_db_query($sql,$db))
				die("Unable to connect to database");
			if(faqe_db_num_rows($result)==0)
				echo "<input type=\"hidden\" name=\"progversion\" value=\"0\">";	
			if(is_konqueror())
				echo "<tr><td></td></tr>";
			echo "<tr bgcolor=\"$row_bgcolor\"><td class=\"kbwizard\" align=\"right\" width=\"30%\">";
			echo "<span style=\"color: $FontColor; font-face: $FontFace; font-size: $FontSize1;\">";
			echo "$l_select_os:</span></td>";
			echo "<td class=\"kbwizard\" align=\"left\" width=\"70%\">";
			echo "<span style=\"color: $FontColor; font-face: $FontFace; font-size: $FontSize1;\">";
			echo "<select class=\"faqeselect\" name=\"os\">";
			echo "<option value=\"-1\">???</option>";
			echo "<option value=\"0\"";
			if($actos==0)
				echo " selected";
			echo ">$l_all</option>";
			$sql = "select * from ".$tableprefix."_os";
			if(!$result=faqe_db_query($sql,$db))
				die("Unable to connect to database");
			if($myrow=faqe_db_fetch_array($result))
			{
				do{
					echo "<option value=\"".$myrow["osnr"]."\"";
					if($myrow["osnr"]==$actos)
						echo " selected";
					echo ">".display_encoded($myrow["osname"])."</option>";
				}while($myrow=faqe_db_fetch_array($result));
			}
			echo "</select>";
			echo " <input class=\"faqebutton\" type=\"submit\" value=\"$l_ok\">";
			echo "</span></td></tr>";
?>
</form>
<?php
			}
	}
	if(isset($category) && isset($programm) && isset($subcategory) && ($category>=0) && ($subcategory>=-1) && isset($os) && ($os>=0))
	{
		$sql="select * from ".$tableprefix."_programm_version where programm=$programm";
		if(!$result=faqe_db_query($sql,$db))
			die("Unable to connect to database");
		if(faqe_db_num_rows($result)>0)
		{
			if(!isset($progversion))
				$actprogversion=-1;
			else
				$actprogversion=$progversion;
			$sql = "select * from ".$tableprefix."_programm_version where programm=$programm";
			if(!$result=faqe_db_query($sql,$db))
				die("Unable to connect to database");
?>
<form method="post" action="<?php echo $form_url?>" target="<?php echo $form_target?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="mode" value="wizard">
<input type="hidden" name="programm" value="<?php echo $programm?>">
<input type="hidden" name="category" value="<?php echo $category?>">
<input type="hidden" name="subcategory" value="<?php echo $subcategory?>">
<input type="hidden" name="os" value="<?php echo $os?>">
<?php
			if(isset($layout))
				echo "<input type=\"hidden\" name=\"layout\" value=\"$layout\">";
			if(isset($limitprog))
				echo "<input type=\"hidden\" name=\"limitprog\" value=\"$limitprog\">";
			if(is_konqueror())
				echo "<tr><td></td></tr>";
			echo "<tr bgcolor=\"$row_bgcolor\"><td class=\"kbwizard\" align=\"right\" width=\"30%\">";
			echo "<span style=\"color: $FontColor; font-face: $FontFace; font-size: $FontSize1;\">";
			echo "$l_select_progversion:</span></td>";
			echo "<td class=\"kbwizard\" align=\"left\" width=\"70%\">";
			echo "<span style=\"color: $FontColor; font-face: $FontFace; font-size: $FontSize1;\">";
			echo "<select style=\"width: 150pt\" class=\"faqeselect\" name=\"progversion\">";
			echo "<option value=\"-1\">???</option>";
			echo "<option value=\"0\"";
			if($actprogversion==0)
				echo " selected";
			echo ">$l_all</option>";
			if($myrow=faqe_db_fetch_array($result))
			{
				do{
					echo "<option value=\"".$myrow["entrynr"]."\"";
					if($myrow["entrynr"]==$actprogversion)
						echo " selected";
					echo ">".$myrow["version"]."</option>";
				}while($myrow=faqe_db_fetch_array($result));
			}
			echo "</select>";
			echo " <input class=\"faqebutton\" type=\"submit\" value=\"$l_ok\">";
			echo "</span></td></tr>";
?>
</form>
<?php
			}
	}
	echo "<tr bgcolor=\"$actionbgcolor\"><td class=\"actionline\" align=\"center\" colspan=\"2\">";
	echo "<span style=\"color: $FontColor; font-face: $FontFace; font-size: $actionlinefontsize;\">";
	if($navframe==1)
		$linkurl=$url_faqengine."/kbframe.php?";
	else
		$linkurl=$act_script_url."?";
	if(isset($limitprog))
		$linkurl.="limitprog=$limitprog&";
	if(isset($layout))
		$linkurl.="layout=$layout&";
	if(isset($prog) && ($prog))
	{
		echo "<a class=\"actionline\" href=\"".$linkurl."prog=$prog&amp;$langvar=$act_lang&amp;mode=search\"";
		if($navframe==1)
			echo " target=\"_parent\"";
		echo ">";
	}
	else
	{
		echo "<a class=\"actionline\" href=\"".$linkurl."$langvar=$act_lang&amp;mode=search\"";
		if($navframe==1)
			echo " target=\"_parent\"";
		echo ">";
	}
	echo "$l_keywordsearch</a>";
	echo " | ";
	if(!isset($limitprog))
	{
		echo "<a class=\"actionline\" href=\"".$linkurl."$langvar=$act_lang&amp;mode=proglist\"";
		if($navframe==1)
			echo " target=\"_parent\"";
		echo ">$l_proglist</a>";
	}
	else
	{
		echo "<a class=\"actionline\" href=\"".$linkurl."$langvar=$act_lang&amp;mode=listall&amp;prog=$limitprog\"";
		if($navframe==1)
			echo " target=\"_parent\"";
		echo ">$l_listallarticles</a>";
	}
	echo "</span></td></tr>";
?>
</table></td></tr>
<?php
	if(isset($category) && isset($programm) && isset($subcategory) && isset($os) && ($category>=0) && ($os>=0) && ($subcategory>=-1) && isset($progversion) && ($progversion>-1))
	{
?>
<tr><TD BGCOLOR="<?php echo $table_bgcolor?>">
<TABLE BORDER="0" CELLPADDING="<?php echo $tablepadding?>" CELLSPACING="<?php echo $tablespacing?>" WIDTH="100%">
<?php
		echo "<tr bgcolor=\"$subheadingbgcolor\"><td align=\"center\" colspan=\"2\" class=\"subheading\">";
		echo "<span style=\"font-face: $FontFace; font-size: $FontSize2; color: $SubheadingFontColor; font-weight: bold;\">";
		echo "$l_found_articles</span></td></tr>";
		if($os>0)
		{
			if($progversion>0)
				$sql = "select art.* from ".$tableprefix."_kb_articles art, ".$tableprefix."_kb_os os, ".$tableprefix."_kb_prog_version kbpv where kbpv.progversion='$progversion' and art.articlenr=kbpv.articlenr and art.programm=$programm and art.category=$category and os.osnr='$os' and art.articlenr=os.articlenr";
			else
				$sql = "select art.* from ".$tableprefix."_kb_articles art, ".$tableprefix."_kb_os os where art.programm=$programm and art.category=$category and os.osnr='$os' and art.articlenr=os.articlenr";
		}
		else
		{
			if($progversion>0)			
				$sql = "select art.* from ".$tableprefix."_kb_articles art, ".$tableprefix."_kb_prog_version kbpv where kbpv.progversion=$progversion and art.articlenr=kbpv.articlenr and art.programm=$programm and art.category=$category";
			else
				$sql = "select art.* from ".$tableprefix."_kb_articles art where art.programm=$programm and art.category=$category";
		}
		if($subcategory>-1)
			$sql.= " and art.subcategory=$subcategory";
		switch($kbsortmethod)
		{
			case 0:
				$sql.=" order by art.lastedited desc";
				break;
			case 1:
				$sql.=" order by art.displaypos asc";
				break;
			case 2:
				$sql.=" order by art.heading asc";
				break;
		}
		if(!$result=faqe_db_query($sql,$db))
			die("Unable to connect to database".faqe_db_error());
		if($maxentries>0)
		{
			$totalfaqs=faqe_db_num_rows($result);			
			include_once("./includes/faq_pagenav.inc");
			if($totalfaqs>$maxentries)
			{
				$sql .=" limit $start,$maxentries";
				if(!$result = faqe_db_query($sql, $db))
					die("Could not connect to the database (3).".faqe_db_error());
				$displayedfaq=$start+faqe_db_num_rows($result);
			}
		}
		if(!$myrow=faqe_db_fetch_array($result))
		{
			echo "<tr bgcolor=\"$row_bgcolor\"><td align=\"center\" colspan=\"2\" class=\"listrow\">";
			echo "<span style=\"color: $FontColor; font-face: $FontFace; font-size: $FontSize1;\">";
			echo "$l_nosuchentries</span></td></tr>";
		}
		else
		{
			$tmpsql="select * from ".$tableprefix."_programm where prognr='$programm'";
			if(!$tmpresult=faqe_db_query($tmpsql,$db))
				die("Unable to connect to database".faqe_db_error());
			if(!$tmprow=faqe_db_fetch_array($tmpresult))
				die("Unable to connect to database".faqe_db_error());
			$myprog=$tmprow["progid"];			
			echo "<tr bgcolor=\"$group_bgcolor\"><td align=\"center\" width=\"10%\" class=\"listrow\">";
			echo "<span style=\"color: $FontColor; font-face: $FontFace; font-size: $tabledescfontsize; font-weight: bold;\">";
			echo "#</span></td>";
			echo "<td align=\"center\" class=\"listrow\" width=\"90%\">";
			echo "<span style=\"color: $FontColor; font-face: $FontFace; font-size: $tabledescfontsize; font-weight: bold;\">";
			echo "$l_description</span></td></tr>";
			do{
				echo "<tr bgcolor=\"$row_bgcolor\"><td align=\"center\" width=\"10%\" class=\"listrow\">";
				echo "<span style=\"color: $FontColor; font-face: $FontFace; font-size: $FontSize1;\">";
				echo $myrow["articlenr"]."</span></td>";
				if($navframe==1)
					$backurl=$url_faqengine."/kbframe.php";
				else
					$backurl=$act_script_url;
				$backurl.="?mode=wizard&$langvar=$act_lang";
				if(isset($programm))
					$backurl.="&programm=$programm";
				if(isset($category))
					$backurl.="&category=$category";
				if(isset($subcategory))
					$backurl.="&subcategory=$subcategory";
				if(isset($os))
					$backurl.="&os=$os";
				if(isset($limitprog))
					$backurl.="&limitprog=$limitprog";
				if(isset($layout))
					$backurl.="&layout=$layout";
				$backurl=urlencode($backurl);
				if($navframe==1)
					$link=$url_faqengine."/kbframe.php";
				else
					$link=$act_script_url;
				$link.="?mode=display&amp;kbnr=".$myrow["articlenr"]."&amp;$langvar=$act_lang&amp;prog=$myprog&amp;backurl=$backurl";
				if(isset($layout))
					$link.="&amp;layout=$layout";
				if(isset($limitprog))
					$link.="&amp;limitprog=$limitprog";
				echo "<td align=\"left\" class=\"listlink\"";
				if($hovercells==1)
				{
					echo " onMouseOver=\"this.style.backgroundColor='$activcellcolor';\" onMouseOut=\"this.style.backgroundColor='$row_bgcolor'\"";
					if($navframe==0)
						echo " onclick=\"window.location.href='$link'\"";
				}
				echo ">";
				echo "<span style=\"color: $FontColor; font-face: $FontFace; font-size: $FontSize1;\">";
				echo "<a href=\"$link\"";
				if($navframe==1)
					echo " target=\"_parent\"";
				echo ">".undo_html_ampersand(stripslashes($myrow["heading"]))."</a></span></td></tr>";
			}while($myrow=faqe_db_fetch_array($result));
			if(($maxentries>0) && ($totalfaqs>$maxentries))
			{
				$url_start="$act_script_url?$langvar=$act_lang&amp;mode=wizard&amp;category=$category&amp;programm=$programm&amp;subcategory=$subcategory&os=$os";
				if($navframe==1)
					$url_start.="&navframe=1";
				if(isset($limitprog))
					$url_start.="&limitprog=$limitprog";
				echo "<tr bgcolor=\"$actionbgcolor\"><td class=\"pagenav\" align=\"center\" valign=\"middle\"";
				echo " colspan=\"2\"";
				echo">";
				faq_pagenav($start, $maxentries, $totalfaqs, $displayedfaq, $url_start);
				echo "</td></tr>";
			}
			
		}
?>
</table></td></tr>
<?php
	}
	echo "</table>";
	if(($enablelanguageselector==1) && !isset($category) && !isset($search_keywords))
	{
		echo "<table style=\"clear:both\" width=\"$TableWidth\" align=\"$tblalign\" border=\"0\">";
		echo "<form method=\"post\" action=\"$form_url\" target=\"$form_target\">";
		echo "<input type=\"hidden\" name=\"mode\" value=\"$mode\">";
		echo "<tr bgcolor=\"$group_bgcolor\"><td align=\"center\" class=\"langselect\">";
		if(isset($limitprog))
			echo "<input type=\"hidden\" name=\"limitprog\" value=\"$limitprog\">";
		if(isset($layout))
			echo "<input type=\"hidden\" name=\"layout\" value=\"$layout\">";
		if($navframe==1)
			echo "<input type=\"hidden\" name=\"navframe\" value=\"1\">";
		if(isset($prog) && $prog)
			echo "<input type=\"hidden\" name=\"prog\" value=\"$prog\">";
		echo "<span style=\"color: $FontColor; font-face: $FontFace; font-size: $langselectfontsize;\">";
		echo "$l_changelang: ".language_select($act_lang,$langvar,"./language","langselect");
		echo "&nbsp;&nbsp;&nbsp;<input class=\"langselect\" type=\"submit\" value=\"$l_ok\">";
		echo "</span></td></tr></form></table>";
	}
?>