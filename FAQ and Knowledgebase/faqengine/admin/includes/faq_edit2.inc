<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
		if($admin_rights < 2)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
		if(!isset($subcategory))
			$subcategory=0;
		else
		{
			$parts=explode("|",$subcategory);
			$subcategory=$parts[0];
		}
		if(isset($category))
		{
			$parts=explode("|",$category);
			$category=$parts[0];
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		echo "<div align=\"center\">";
		$errors=0;
		if($new_global_handling)
			$tmp_file=$_FILES['faqfile']['tmp_name'];
		else
			$tmp_file=$HTTP_POST_FILES['faqfile']['tmp_name'];
		if($upload_avail && is_uploaded_file($tmp_file))
		{
			$filedata = " ".fread(fopen($tmp_file,"r"), filesize($tmp_file));
			$offset=0;
			$fidstart=strpos(strtolower($filedata),"{".md5($copyright_note)."}");
			if($fidstart)
			{
				$faqfmarker=strpos(strtolower($filedata),"{kbfile}");
				if($faqfmarker<1)
				{
					echo "<tr class=\"errorrow\"><td align=\"center\">";
					echo "$l_faquploadwrongformat/td></tr>";
					$errors=1;
				}
				else
				{
					$optionstart=strpos(strtolower($filedata),"{options}");
					if($optionstart)
					{
						$offset=$optionstart+9;
						if($optionstop=strpos($filedata,"{/options}",$optionstart+9))
						{
							$options=explode("§",strtolower(trim(substr($filedata,$optionstart+9,$optionstop-($optionstart+9)))));
							for($i=0;$i<count($options);$i++)
							{
								list($optionname,$optionvalue)=explode("|",$options[$i]);
								if(($optionname=="urlautoencode") && ($optionvalue))
									$local_urlautoencode=1;
								if(($optionname=="enablebbc") && ($optionvalue))
									$local_enablespcode=1;
								if(($optionname=="nobrtransquestion") && ($optionvalue))
									$ques_no_br=1;
								if(($optionname=="nobrtransanswer") && ($optionvalue))
									$ans_no_br=1;
								if(($optionname=="disablehtml") && ($optionvalue))
									$disablehtml=1;
							}
							$offset=$optionstop;
						}
					}
					$headerstart=strpos(strtolower($filedata),"{heading}");
					if($headerstart)
					{
						$offset=$headerstart+9;
						if($headerstop=strpos($filedata,"{/heading}",$headerstart+9))
						{
							$heading=trim(substr($filedata,$headerstart+9,$headerstop-($headerstart+9)));
							$offset=$headerstop;
						}
					}
					$questionstart=strpos(strtolower($filedata),"{question}",$offset);
					if($questionstart)
					{
						$offset=$questionstart+10;
						if($questionstop=strpos($filedata,"{/question}",$questionstart+10))
						{
							$question=trim(substr($filedata,$questionstart+10,$questionstop-($questionstart+10)));
							$offset=$questionstop;
						}
					}
					$answerstart=strpos(strtolower($filedata),"{answer}",$offset);
					if($answerstart)
					{
						$offset=$answerstart+8;
						if($answerstop=strpos($filedata,"{/answer}",$answerstart+8))
						{
							$answer=trim(substr($filedata,$answerstart+8,$answerstop-($answerstart+8)));
						}
					}
				}
			}
			else
			{
				$errors=1;
				echo "<tr class=\"errorrow\" align=\"center\"><td>";
				echo "$l_faquploadwrongformat</td></tr>";
			}
		}
		if($subcategory>0)
		{
			$sql="select * from ".$tableprefix."_subcategory where catnr=$subcategory";
			if(!$result = faqe_db_query($sql, $db))
			    db_die("<tr class=\"errorrow\" align=\"center\"><td>Unable to get subcategory from DB.");
			if($myrow=faqe_db_fetch_array($result))
				$category=$myrow["category"];
		}
		if(!$heading)
		{
			echo "<tr class=\"errorrow\" align=\"center\"><td>";
			echo "$l_noheading</td></tr>";
			$errors=1;
		}
		else
			$heading=stripslashes($heading);
		if(($category<0) && ($subcategory==0))
		{
			echo "<tr class=\"errorrow\" align=\"center\"><td>";
			echo "$l_nocategory</td></tr>";
			$errors=1;
		}
		if($errors==0)
		{
			$displayheading=stripslashes($heading);
			$displayheading=strip_tags($displayheading);
			$displayheading=do_htmlentities($displayheading);
			$displayheading=undo_html_ampersand($displayheading);
			$sql = "select prog.*, cat.categoryname from ".$tableprefix."_programm prog, ".$tableprefix."_category cat where prog.prognr=cat.programm and cat.catnr=$category";
			if(!$result = faqe_db_query($sql, $db))
				db_die("<tr class=\"errorrow\" align=\"center\"><td>Unable to get program from DB.");
			if(!$myrow=faqe_db_fetch_array($result))
			{
				$progname=$l_unknown;
				$proglang=$l_unknown;
				$catname=$l_unknown;
			}
			else
			{
				$prognr=$myrow["prognr"];
				$progid=$myrow["progid"];
				$proglang=$myrow["language"];
				$progname=display_encoded($myrow["programmname"]);
				$catname=display_encoded($myrow["categoryname"]);
			}
			if($subcategory>0)
			{
				$sql="select * from ".$tableprefix."_subcategory where catnr=$subcategory";
				if(!$result = faqe_db_query($sql, $db))
					db_die("<tr class=\"errorrow\" align=\"center\"><td>Unable to get subcategory from DB.");
				if($myrow=faqe_db_fetch_array($result))
					$subcatname=display_encoded($myrow["categoryname"]);
			}
			else
				$subcatname="";
			if(!isset($local_urlautoencode))
				$urlautoencode=0;
			else
				$urlautoencode=1;
			if(!isset($local_enablespcode))
				$enablespcode=0;
			else
				$enablespcode=1;
			$displayquestion="";
			$displayanswer="";
			if($question)
			{
				$displayquestion=$question;
				if(isset($disablehtml))
				{
					$displayquestion = htmlspecialchars($displayquestion);
					$displayquestion = undo_html_ampersand($displayquestion);
				}
				if($urlautoencode==1)
					$displayquestion = make_clickable($displayquestion);
				if($enablespcode==1)
					$displayquestion = bbencode($displayquestion);
				$displayquestion = stripslashes($displayquestion);
				$displayquestion = do_htmlentities($displayquestion);
				if(!isset($ques_no_br))
				{
					$displayquestion = str_replace("\n", "<BR>", $displayquestion);
					$displayquestion = str_replace("\r", "", $displayquestion);
				}
				$displayquestion = undo_htmlspecialchars($displayquestion);
				$displayquestion = str_replace("{lang}","$langvar=$act_lang",$displayquestion);
				$displayquestion = str_replace("{url_faqengine}",$url_faqengine,$displayquestion);
				$displayquestion = str_replace("{onlynewfaq}",0,$displayquestion);
				$displayquestion = str_replace("{bbc_code}",$l_bbccode,$displayquestion);
				$displayquestion = str_replace("{bbc_quote}",$l_bbcquote,$displayquestion);
			}
			if($answer)
			{
				$displayanswer=$answer;
				if(isset($disablehtml))
				{
					$displayanswer = htmlspecialchars($displayanswer);
					$displayanswer = undo_html_ampersand($displayanswer);
				}
				if($urlautoencode==1)
					$displayanswer = make_clickable($displayanswer);
				if($enablespcode==1)
					$displayanswer = bbencode($displayanswer);
				$displayanswer = stripslashes($displayanswer);
				$displayanswer = do_htmlentities($displayanswer);
				if(!isset($ans_no_br))
				{
					$displayanswer = str_replace("\n", "<BR>", $displayanswer);
					$displayanswer = str_replace("\r", "", $displayanswer);
				}
				$displayanswer = undo_htmlspecialchars($displayanswer);
				$displayanswer = str_replace("{lang}","$langvar=$act_lang",$displayanswer);
				$displayanswer = str_replace("{url_faqengine}",$url_faqengine,$displayanswer);
				$displayanswer = str_replace("{onlynewfaq}",0,$displayanswer);
				$displayanswer = str_replace("{bbc_code}",$l_bbccode,$displayanswer);
				$displayanswer = str_replace("{bbc_quote}",$l_bbcquote,$displayanswer);
			}
			$keywords=trim($keywords);
			if($keywords)
			{
				$displaykeywords="<ul class=\"kwlist\">";
				$enteredkeywords = split("[|]",$keywords);
				foreach($enteredkeywords as $keyword)
				{
					$keyword=trim($keyword);
					$displaykeywords.="<li>$keyword";
				}
				$displaykeywords.="</ul>";
			}
			else
				$displaykeywords="";
			echo "<form name=\"inputform\"";
			echo " method=\"post\" action=\"$act_script_url\">";
			if(is_konqueror())
				echo "<tr><td></td></tr>";
			echo "<tr class=\"headingrow\"><td align=\"center\" colspan=\"2\"><b>$l_editfaq (2/2)</b></td></tr>";
			echo "<tr class=\"inforow\"><td align=\"center\" colspan=\"2\">";
			if($subcatname)
				echo "<b>$progname [$proglang] : $catname : $subcatname : $displayheading</b></td></tr>";
			else
				echo "<b>$progname [$proglang] : $catname : $displayheading</b></td></tr>";
			echo "<tr class=\"displayrow\"><td align=\"right\" width=\"20%\" valign=\"top\">";
			echo "$l_question:</td>";
			echo "<td align=\"left\" width=\"80%\">$displayquestion</td></tr>";
			echo "<tr class=\"inputrow\"><td>&nbsp;</td>";
			echo "<td align=\"left\" width=\"80%\"><textarea class=\"faqeinput\" name=\"question\"  cols=\"$textareascols\" rows=\"$textareasrows\">".undo_htmlspecialchars(do_htmlentities(stripslashes($question)))."</textarea></td></tr>";
			echo "<tr class=\"optionrow\"><td>&nbsp;</td><td>";
			echo "<input type=\"checkbox\" name=\"ques_no_br\" value=\"1\"";
			if(isset($ques_no_br))
				echo " checked";
			echo ">$l_donttranslatelinebreaks</td></tr>";
			echo "<tr class=\"displayrow\"><td align=\"right\" width=\"20%\" valign=\"top\">";
			echo "$l_answer:</td>";
			echo "<td align=\"left\" width=\"80%\">$displayanswer</td></tr>";
			echo "<tr class=\"inputrow\"><td>&nbsp;</td>";
			echo "<td align=\"left\" width=\"80%\"><textarea class=\"faqeinput\" name=\"answer\" cols=\"$textareascols\" rows=\"$textareasrows\">".undo_htmlspecialchars(do_htmlentities(stripslashes($answer)))."</textarea></td></tr>";
			echo "<tr class=\"optionrow\"><td>&nbsp;</td><td>";
			echo "<input type=\"checkbox\" name=\"ans_no_br\" value=\"1\"";
			if(isset($ans_no_br))
				echo " checked";
			echo ">$l_donttranslatelinebreaks</td></tr>";
			echo "<tr class=\"displayrow\"><td align=\"right\" width=\"20%\" valign=\"top\">";
			echo "$l_keywords:</td>";
			echo "<td align=\"left\" width=\"80%\">$displaykeywords</td></tr>";
?>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_relatedfaq?>:</td>
<td>
<?php
			$faqsql = "SELECT dat.*, cat.categoryname, prog.programmname, prog.language from ".$tableprefix."_data dat, ".$tableprefix."_category cat, ".$tableprefix."_related_faq rf, ".$tableprefix."_programm prog where rf.srcfaq=$input_faqnr and dat.faqnr=rf.destfaq and cat.catnr=dat.category and prog.prognr=cat.programm order by dat.displaypos";
			if(!$faqresult = faqe_db_query($faqsql, $db))
				die("<tr class=\"errorrow\"><td>Unable to get related FAQ from DB");
			if ($faqrow = faqe_db_fetch_array($faqresult))
			{
				 do {
				    echo undo_html_ampersand(stripslashes($faqrow["heading"]))." (".display_encoded($faqrow["categoryname"]);
				    if($faqlimitrelated==0)
				    	echo " | ".display_encoded($faqrow["programmname"])." [".stripslashes($faqrow["language"])."]";
				    echo ") (<input type=\"checkbox\" name=\"rem_relfaqs[]\" value=\"".$faqrow["faqnr"]."\"> $l_remove)<BR>";
				    $current_relfaqs[] = $faqrow["faqnr"];
				 } while($faqrow = faqe_db_fetch_array($faqresult));
				 echo "<br>";
			}
			else
				echo "$l_norelatedfaqs<br><br>";
			$faqsql = "SELECT dat.*, cat.categoryname, prog.programmname, prog.language from ".$tableprefix."_data dat, ".$tableprefix."_category cat, ".$tableprefix."_programm prog where dat.category=cat.catnr and dat.faqnr!=$input_faqnr and prog.prognr=cat.programm ";
			if($faqlimitrelated==1)
				$faqsql.= "and cat.programm=$prognr ";
			if(isset($current_relfaqs))
			{
    				while(list($null, $currFaq) = each($current_relfaqs)) {
					$faqsql .= "and dat.faqnr != $currFaq ";
    				}
    			}
    			$faqsql .="order by prog.language, prog.displaypos, cat.displaypos, dat.displaypos";
    			if(!$faqresult = faqe_db_query($faqsql, $db))
				db_die("<tr class=\"errorrow\"><td>Unable to get unrelated FAQ from DB");
    			if($faqrow = faqe_db_fetch_array($faqresult)) {
				echo "<span class=\"inlineheading1\">$l_add:</span><br>";
				echo "<SELECT NAME=\"relfaqs[]\" size=\"5\" multiple>";
				do {
					echo "<OPTION VALUE=\"".$faqrow["faqnr"]."\" >".undo_html_ampersand(stripslashes($faqrow["heading"]))." (".display_encoded($faqrow["categoryname"]);
					if($faqlimitrelated==0)
						echo " | ".display_encoded($faqrow["programmname"])." [".stripslashes($faqrow["language"])."]";
					echo ")</OPTION>\n";
				} while($faqrow = faqe_db_fetch_array($faqresult));
				echo "</select>";
			}
?>
</td></tr><tr class="inputrow"><td align="right" valign="top"><?php echo $l_referenced_faq?>:</td>
<td>
<?php
			$faqsql = "SELECT dat.*, ref.language from ".$tableprefix."_data dat, ".$tableprefix."_faq_ref ref where ref.srcfaqnr=$input_faqnr and dat.faqnr=ref.destfaqnr order by ref.language, dat.displaypos";
			if(!$faqresult = faqe_db_query($faqsql, $db))
				db_die("<tr class=\"errorrow\"><td>Unable to get referenced FAQ from DB.");
			if ($faqrow = faqe_db_fetch_array($faqresult))
			{
				 do {
				    echo undo_html_ampersand(stripslashes($faqrow["heading"]))." [".$faqrow["language"]."] (<input type=\"checkbox\" name=\"rem_faqs[]\" value=\"".$faqrow["faqnr"]."\"> $l_remove)<BR>";
				    $current_faqs[] = $faqrow["faqnr"];
				 } while($faqrow = faqe_db_fetch_array($faqresult));
				 echo "<br>";
			}
			else
				echo "$l_norefs<br><br>";
			$faqsql = "SELECT dat.*, prog.language from ".$tableprefix."_data dat, ".$tableprefix."_programm prog, ".$tableprefix."_category cat where prog.progid='$progid' and prog.language!='$proglang' and cat.programm=prog.prognr and dat.category=cat.catnr and dat.faqnr!= $input_faqnr ";
			if(isset($current_faqs))
			{
    				while(list($null, $currFaq) = each($current_faqs)) {
					$faqsql .= "and dat.faqnr != $currFaq ";
    				}
    			}
    			$faqsql .="order by prog.language, dat.displaypos";
    			if(!$faqresult = faqe_db_query($faqsql, $db))
				db_die("<tr class=\"errorrow\"><td>Unable to get not referenced FAQ from DB.");
    			if($faqrow = faqe_db_fetch_array($faqresult)) {
				echo "<span class=\"inlineheading1\">$l_add:</span><br>";
				echo "<SELECT NAME=\"faqs[]\" size=\"5\" multiple>";
				do {
					echo "<OPTION VALUE=\"".$faqrow["faqnr"]."|".$faqrow["language"]."\" >".undo_html_ampersand(stripslashes($faqrow["heading"]))." [".$faqrow["language"]."]</OPTION>\n";
				} while($faqrow = faqe_db_fetch_array($faqresult));
				echo "</select>";
			}
?>
</td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_affected_versions?>:</td>
<td>
<?php
			$versionsql = "select progver.* from ".$tableprefix."_programm_version progver, ".$tableprefix."_faq_prog_version fpv where fpv.faqnr=$input_faqnr and progver.entrynr=fpv.progversion order by progver.version desc"; 
			if(!$versionresult = faqe_db_query($versionsql, $db))
				db_die("<tr class=\"errorrow\"><td>Unable to get program versions from DB.");
			if ($versionrow = faqe_db_fetch_array($versionresult))
			{
				 do {
				    if($versionrow["programm"]==$prognr)	
				    	echo stripslashes($versionrow["version"])." (<input type=\"checkbox\" name=\"rem_fpvs[]\" value=\"".$versionrow["entrynr"]."\"> $l_remove)<BR>";
				    else
				    	echo "<input type=\"hidden\" name=\"remp_fpvs[]\" value=\"".$versionrow["entrynr"]."\">";
				    $current_fpvs[] = $versionrow["entrynr"];
				 } while($versionrow = faqe_db_fetch_array($versionresult));
				 echo "<br>";
			}
			else
				echo "$l_nodefversions<br><br>";
			$versionsql = "SELECT * from ".$tableprefix."_programm_version where programm=$prognr ";
			if(isset($current_fpvs))
			{
    				while(list($null, $currFpv) = each($current_fpvs)) {
					$versionsql .= "and entrynr != $currFpv ";
    				}
    			}
    			$versionsql .="order by version desc";
    			if(!$versionresult = faqe_db_query($versionsql, $db))
				db_die("<tr class=\"errorrow\"><td>Unable to get program versions from DB.");
    			if($versionrow = faqe_db_fetch_array($versionresult)) {
				echo "<span class=\"inlineheading1\">$l_add:</span><br>";
				echo "<SELECT NAME=\"fpvs[]\" size=\"5\" multiple>";
				do {
					echo "<OPTION VALUE=\"".$versionrow["entrynr"]."\" >".stripslashes($versionrow["version"])."</OPTION>\n";
				} while($versionrow = faqe_db_fetch_array($versionresult));
				echo "</select>";
			}
?>
</td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_affected_os?>:</td>
<td>
<?php
			$ossql = "select os.* from ".$tableprefix."_os os, ".$tableprefix."_faq_os fos where fos.faqnr=$input_faqnr and os.osnr=fos.osnr order by os.osname"; 
			if(!$osresult = faqe_db_query($ossql, $db))
				db_die("<tr class=\"errorrow\"><td>Unable to get defined OS from DB.");
			if ($osrow = faqe_db_fetch_array($osresult))
			{
				do {
					echo display_encoded($osrow["osname"])." (<input type=\"checkbox\" name=\"rem_oss[]\" value=\"".$osrow["osnr"]."\"> $l_remove)<BR>";
					$current_oss[] = $osrow["osnr"];
				} while($osrow = faqe_db_fetch_array($osresult));
				echo "<br>";
			}
			else
				echo "$l_nodefos<br><br>";
			$ossql = "SELECT os.* from ".$tableprefix."_os os, ".$tableprefix."_prog_os pos  where pos.prognr=$prognr and os.osnr=pos.osnr ";
			if(isset($current_oss))
			{
    				while(list($null, $curros) = each($current_oss)) {
					$ossql .= "and os.osnr != $curros ";
    				}
    			}
    			$ossql .="order by osname";
    			if(!$osresult = faqe_db_query($ossql, $db))
				db_die("<tr class=\"errorrow\"><td>Unable to get not defined OS from DB.");
    			if($osrow = faqe_db_fetch_array($osresult)) {
				echo "<span class=\"inlineheading1\">$l_add:</span><br>";
				echo "<SELECT NAME=\"oss[]\" size=\"5\" multiple>";
				do {
					echo "<OPTION VALUE=\"".$osrow["osnr"]."\" >".display_encoded($osrow["osname"])."</OPTION>\n";
				} while($osrow = faqe_db_fetch_array($osresult));
				echo "</select>";
			}
?>
</td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_attachfile?>:</td>
<td>
<?php
			$attachsql="select f.filename, f.filesize, fa.entrynr from ".$tableprefix."_files f, ".$tableprefix."_faq_attachs fa where f.entrynr=fa.attachnr and fa.faqnr=$input_faqnr";
    			if(!$attachresult = faqe_db_query($attachsql, $db))
				db_die("<tr class=\"errorrow\"><td>Unable to get attached files from DB.");
			if($attachrow=faqe_db_fetch_array($attachresult))
			{
				echo "$l_attachedfile:<br>";
				do{
					echo $attachrow["filename"]." (".$attachrow["filesize"]."Bytes)&nbsp;";
					echo "<input type=\"checkbox\" name=\"delattach[]\" value=\"".$attachrow["entrynr"]."\">$l_delete<br>";
				}while($attachrow=faqe_db_fetch_array($attachresult));
				echo "<hr>";
			}
			echo "<span class=\"inlineheading1\">$l_add_file:</span><br>";
			echo "<select id=\"new_files\" name=\"new_files[]\" size=\"5\" multiple>";
			echo "</select><br>";
			echo "<a class=\"listlink\" href=\"javascript:openWindow2('".do_url_session("dbfiles.php?$langvar=$lang&faqnr=$input_faqnr&mode=1")."',20,20,620,300);\">";
			echo "$l_select_file_from_db</a>";
?>
</td></tr>
<tr class="actionrow" align="center"><td colspan="2">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="mode" value="update">
<input type="hidden" name="keywords" value="<?php echo $keywords?>">
<?php
if(isset($disablehtml))
	echo "<input type=\"hidden\" name=\"disablehtml\" value=\"1\">";
if($sessid_url)
	echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
if(isset($local_urlautoencode))
	echo "<input type=\"hidden\" name=\"local_urlautoencode\" value=\"1\">";
if(isset($local_enablespcode))
	echo "<input type=\"hidden\" name=\"local_enablespcode\" value=\"1\">";
if(isset($keepviews))
	echo "<input type=\"hidden\" name=\"keepviews\" value=\"1\">";
if(isset($keepratings))
	echo "<input type=\"hidden\" name=\"keepratings\" value=\"1\">";
if(isset($keepeditdate))
	echo "<input type=\"hidden\" name=\"keepeditdate\" value=\"1\">";
if(isset($oldeditdate))
	echo "<input type=\"hidden\" name=\"oldeditdate\" value=\"$oldeditdate\">";
if(isset($oldviews))
	echo "<input type=\"hidden\" name=\"oldviews\" value=\"$oldviews\">";
if(isset($oldrating))
	echo "<input type=\"hidden\" name=\"oldrating\" value=\"$oldrating\">";
if(isset($oldratingcount))
	echo "<input type=\"hidden\" name=\"oldratingcount\" value=\"$oldratingcount\">";
?>
<input type="hidden" name="subcategory" value="<?php echo $subcategory?>">
<input type="hidden" name="heading" value="<?php echo do_htmlentities(stripslashes($heading))?>">
<input type="hidden" name="category" value="<?php echo $category?>">
<input type="hidden" name="oldcat" value="<?php echo $oldcat?>">
<input type="hidden" name="input_editdate" value="<?php echo $input_editdate?>">
<input type="hidden" name="input_faqnr" value="<?php echo $input_faqnr?>">
<input class="faqebutton" type="submit" value="<?php echo $l_update?>">&nbsp;&nbsp;
<input class="faqebutton" type="button" value="<?php echo $l_preview?>" onclick="document.inputform.mode.value='edit2'; document.inputform.submit();">&nbsp;&nbsp;
<input class="faqebutton" type="button" value="<?php echo $l_back ?>" onclick="self.history.back();">
</td></form></tr></table></td></tr></table>
<?php
		}
		else
		{
			echo "<tr class=\"actionrow\" align=\"center\"><td>";
			echo "<a href=\"javascript:history.back()\">$l_back</a>";
			echo "</td></tr></table></td></tr></table>";
		}
?>