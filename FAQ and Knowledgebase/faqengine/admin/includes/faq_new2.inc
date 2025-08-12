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
		else
			$category=-1;
		if($subcategory>0)
		{
			$sql="select * from ".$tableprefix."_subcategory where catnr=$subcategory";
			if(!$result = faqe_db_query($sql, $db))
				db_die("<tr class=\"errorrow\"><td align=\"center\">Unable to subcategries from DB.");
			if($myrow=faqe_db_fetch_array($result))
				$category=$myrow["category"];
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
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
		if($errors==0)
		{
			if(!$heading)
			{
				echo "<tr class=\"errorrow\"><td align=\"center\">";
				echo "$l_noheading</td></tr>";
				$errors=1;
			}
			else
				$heading=stripslashes($heading);
			if(($category<0) && ($subcategory<=0))
			{
				echo "<tr class=\"errorrow\"><td align=\"center\">";
				echo "$l_nocategory</td></tr>";
				$errors=1;
			}
		}
		if($errors==0)
		{
			$displaykeywords="";
			if($keywords)
			{
				$displaykeywords="<ul>";
				$enteredkeywords = split("[|]",$keywords);
				foreach($enteredkeywords as $keyword)
				{
					$keyword=trim($keyword);
					$displaykeywords.="<li>$keyword";
				}
				$displaykeywords.="</ul>";
			}
			$displayheading=stripslashes($heading);
			$displayheading=strip_tags($displayheading);
			$displayheading=do_htmlentities($displayheading);
			$displayheading=undo_html_ampersand($displayheading);
			$sql = "select prog.*, cat.categoryname from ".$tableprefix."_programm prog, ".$tableprefix."_category cat where prog.prognr=cat.programm and cat.catnr=$category";
			if(!$result = faqe_db_query($sql, $db))
				db_die("<tr class=\"errorrow\"><td>Unable to get programs from DB.");
			if(!$myrow=faqe_db_fetch_array($result))
			{
				$prognr=0;
				$progid="";
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
			echo "<form name=\"inputform\"";
			if($upload_avail)
				echo " ENCTYPE=\"multipart/form-data\"";
			echo " method=\"post\" action=\"$act_script_url\">";
			echo "<tr><td class=\"headingrow\" align=\"center\" colspan=\"2\"><b>$l_newfaq</b> (2/2)</td></tr>";
			echo "<tr class=\"inforow\"><td align=\"center\" colspan=\"2\">";
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
			echo "<td align=\"left\" width=\"80%\"><textarea class=\"faqeinput\" name=\"answer\"  cols=\"$textareascols\" rows=\"$textareasrows\">".undo_htmlspecialchars(do_htmlentities(stripslashes($answer)))."</textarea></td></tr>";
			echo "<tr class=\"optionrow\"><td>&nbsp;</td><td>";
			echo "<input type=\"checkbox\" name=\"ans_no_br\" value=\"1\"";
			if(isset($ans_no_br))
				echo " checked";
			echo ">$l_donttranslatelinebreaks</td></tr>";
			echo "<tr class=\"displayrow\"><td align=\"right\" width=\"20%\" valign=\"top\">";
			echo "$l_keywords:</td>";
			echo "<td align=\"left\" width=\"80%\">$displaykeywords</td></tr>\n";
			echo "<tr class=\"inputrow\"><td align=\"right\" valign=\"top\">$l_relatedfaq:</td><td>";
			$sql = "SELECT dat.*, cat.categoryname, prog.programmname, prog.language from ".$tableprefix."_category cat, ".$tableprefix."_data dat, ".$tableprefix."_programm prog WHERE dat.category=cat.catnr and prog.prognr=cat.programm ";
			if($faqlimitrelated==1)
				$sql.= "and cat.programm=$prognr ";
			$sql.="order by prog.language, prog.displaypos, cat.displaypos, dat.displaypos";
		    	if(!$result = faqe_db_query($sql, $db))
				db_die("<tr class=\"errorrow\"><td>Unable to get related FAQ from DB.");
		    	if($faqrow = faqe_db_fetch_array($result))
		    	{
				echo "<SELECT NAME=\"relfaqs[]\" size=\"5\" multiple>";
				do{
					echo "<OPTION VALUE=\"".$faqrow["faqnr"]."\" >".undo_html_ampersand(stripslashes($faqrow["heading"]))." (".display_encoded($faqrow["categoryname"]);
					if($faqlimitrelated==0)
						echo " | ".display_encoded($faqrow["programmname"])." [".stripslashes($faqrow["language"])."]";
					echo ")</OPTION>\n";
				} while($faqrow = faqe_db_fetch_array($result));
				echo "</select>";
			}
			else {
				echo "$l_none_avail\n";
			}

?>
</td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_referenced_faq?>:</td>
<td>
<?php
			$sql = "SELECT dat.*, prog.language from ".$tableprefix."_category cat, ".$tableprefix."_programm prog, ".$tableprefix."_data dat WHERE prog.progid='$progid' and prog.language!='$proglang' and cat.programm=prog.prognr and dat.category=cat.catnr order by dat.displaypos";
		    	if(!$result = faqe_db_query($sql, $db))
				db_die("<tr class=\"errorrow\"><td>Unable to get referenced FAQ from DB.");
		    	if($faqrow = faqe_db_fetch_array($result))
		    	{
				echo "<SELECT NAME=\"faqs[]\" size=\"5\" multiple>";
				do{
					echo "<OPTION VALUE=\"".$faqrow["faqnr"]."|".$faqrow["language"]."\" >".undo_html_ampersand(stripslashes($faqrow["heading"]))." [".$faqrow["language"]."]</OPTION>\n";
				} while($faqrow = faqe_db_fetch_array($result));
				echo "</select>";
			}
			else
				echo "$l_none_avail\n";

?>
</td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_affected_versions?>:</td>
<td>
<?php
			$versionsql = "SELECT * from ".$tableprefix."_programm_version where programm=$prognr ";
    			$versionsql .="order by version desc";
    			if(!$versionresult = faqe_db_query($versionsql, $db))
				db_die("<tr class=\"errorrow\"><td>Unable to get program versions from DB.");
    			if($versionrow = faqe_db_fetch_array($versionresult))
    			{
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
			$ossql = "SELECT os.* from ".$tableprefix."_os os, ".$tableprefix."_prog_os pos  where pos.prognr=$prognr and os.osnr=pos.osnr ";
    			$ossql .="order by osname";
    			if(!$osresult = faqe_db_query($ossql, $db))
				db_die("<tr class=\"errorrow\"><td>Unable to get OS from DB.");
    			if($osrow = faqe_db_fetch_array($osresult)) {
				echo "<SELECT NAME=\"oss[]\" size=\"5\" multiple>";
				do {
					echo "<OPTION VALUE=\"".$osrow["osnr"]."\" >".display_encoded($osrow["osname"])."</OPTION>\n";
				} while($osrow = faqe_db_fetch_array($osresult));
				echo "</select>";
			}
?>
</td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_attachfile?>:</td><td>
<?php
			echo "<select id=\"new_files\" name=\"new_files[]\" size=\"5\" multiple>";
			echo "</select><br>";
			echo "<a class=\"listlink\" href=\"javascript:openWindow2('".do_url_session("dbfiles.php?$langvar=$lang&mode=1")."',20,20,620,300);\">";
			echo "$l_select_file_from_db</a>";
?>
</td></tr>
<tr class="actionrow" align="center"><td colspan="2">
<input type="hidden" name="srclang" value="<?php echo $proglang?>">
<input type="hidden" name="keywords" value="<?php echo $keywords?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="mode" value="add">
<?php
if(isset($disablehtml))
	echo "<input type=\"hidden\" name=\"disablehtml\" value=\"1\">";
if($sessid_url)
	echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
if(isset($local_urlautoencode))
	echo "<input type=\"hidden\" name=\"local_urlautoencode\" value=\"1\">";
if(isset($local_enablespcode))
	echo "<input type=\"hidden\" name=\"local_enablespcode\" value=\"1\">";
?>
<input type="hidden" name="heading" value="<?php echo do_htmlentities($heading)?>">
<input type="hidden" name="category" value="<?php echo $category?>">
<input type="hidden" name="subcategory" value="<?php echo $subcategory?>">
<input class="faqebutton" type="submit" value="<?php echo $l_enter?>">&nbsp;&nbsp;
<input class="faqebutton" type="button" value="<?php echo $l_preview?>" onclick="document.inputform.mode.value='new2'; document.inputform.submit();">&nbsp;&nbsp;
<input class="faqebutton" type="button" value="<?php echo $l_back ?>" onclick="self.history.back();">
</td></form></tr></table></td></tr></table>
<?php
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_faqlist</a></div>";
		}
		else
		{
			echo "<tr class=\"actionrow\" align=\"center\"><td>";
			echo "<a href=\"javascript:history.back()\">$l_back</a>";
			echo "</td></tr></table></td></tr></table>";
		}
?>