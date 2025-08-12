<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
		if($admin_rights < 2)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
		echo "<table align=\"center\" width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">";
		$errors=0;
		if($programm<0)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_noprogramm</td></tr>";
			$errors=1;
		}
		if($new_global_handling)
			$tmp_file=$_FILES['kbfile']['tmp_name'];
		else
			$tmp_file=$HTTP_POST_FILES['kbfile']['tmp_name'];
		if($upload_avail && is_uploaded_file($tmp_file))
		{
			$filedata = " ".fread(fopen($tmp_file,"r"), filesize($tmp_file));
			$offset=0;
			$fidstart=strpos(strtolower($filedata),"{".md5($copyright_note)."}");
			if($fidstart)
			{
				$kbmarker=strpos(strtolower($filedata),"{kbfile}");
				if($kbmarker<1)
				{
					echo "<tr class=\"errorrow\"><td align=\"center\">";
					echo "$l_wrongkbfile</td></tr>";
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
								if(($optionname=="nobrtrans") && ($optionvalue))
									$no_br=1;
								if(($optionname=="disablehtml") && ($optionvalue))
									$disablehtml=1;
							}
							$offset=$optionstop;
						}
					}
					$keywordstart=strpos(strtolower($filedata),"{keywords}");
					if($keywordstart)
					{
						$offset=$keywordstart+10;
						if($keywordstop=strpos($filedata,"{/keywords}",$keywordstart+10))
						{
							$keywords=trim(substr($filedata,$keywordstart+10,$keywordstop-($keywordstart+10)));
							$offset=$keywordstop;
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
					$textstart=strpos(strtolower($filedata),"{text}",$offset);
					if($textstart)
					{
						$offset=$textstart+6;
						if($textstop=strpos($filedata,"{/text}",$textstart+6))
						{
							$article=trim(substr($filedata,$textstart+6,$textstop-($textstart+6)));
							$offset=$textstop;
						}
					}
				}
			}
			else
			{
				$errors=1;
				echo "<tr class=\"errorrow\" align=\"center\"><td>";
				echo "$l_wrongkbfile</td></tr>";
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
			{
				$heading=strip_tags($heading);
				$heading=stripslashes($heading);
				$heading=do_htmlentities($heading);
			}
			if(!$article)
			{
				echo "<tr class=\"errorrow\"><td align=\"center\">";
				echo "$l_noarticletext</td></tr>";
				$errors=1;
			}
			if(!$keywords)
			{
				echo "<tr class=\"errorrow\"><td align=\"center\">";
				echo "$l_nokeywords</td></tr>";
				$errors=1;
			}
		}
		if($errors==0)
		{
			$sql = "select * from ".$tableprefix."_kb_articles where (articlenr=$input_articlenr)";
			if(!$result = faqe_db_query($sql, $db))
				db_die("<tr class=\"errrorrow\"><td>Unable to get article from DB.");
			if (!$myrow = faqe_db_fetch_array($result))
				die("<tr class=\"errorrow\"><td>no such entry");

			if($admin_rights <3)
			{
				$sql2="select * from ".$tableprefix."_programm_admins where (prognr=".$myrow["programm"].") and (usernr=".$userdata["usernr"].")";
				if(!$result2 = faqe_db_query($sql2, $db))
					db_die("<tr class=\"errorrow\"><td>Unable to get program admins from DB.");
				if(!$tmprow = faqe_db_fetch_array($result2))
				{
					echo "<tr class=\"errorrow\"><td align=\"center\">";
					die("$l_functionnotallowed");
				}
			}
?>
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_editkbarticle?> (2/2)</b></td></tr>
<form onSubmit="return checkform2();" name="inputform" method="post" action="<?$act_script_url?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<?php
			if($sessid_url)
				echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
			if(isset($local_urlautoencode))
				echo "<input type=\"hidden\" name=\"local_urlautoencode\" value=\"1\">";
			if(isset($local_enablespcode))
				echo "<input type=\"hidden\" name=\"local_enablespcode\" value=\"1\">";
			if(isset($keepratings))
				echo "<input type=\"hidden\" name=\"keepratings\" value=\"1\">";
			if(isset($keepviews))
				echo "<input type=\"hidden\" name=\"keepviews\" value=\"1\">";
			if(isset($keepeditdate))
				echo "<input type=\"hidden\" name=\"keepeditdate\" value=\"1\">";
			if(isset($oldeditdate))
				echo "<input type=\"hidden\" name=\"oldeditdate\" value=\"$oldeditdate\">";
			if(isset($oldrating))
				echo "<input type=\"hidden\" name=\"oldrating\" value=\"$oldrating\">";
			if(isset($oldratingcount))
				echo "<input type=\"hidden\" name=\"oldratingcount\" value=\"$oldratingcount\">";
			if(isset($oldviews))
				echo "<input type=\"hidden\" name=\"oldviews\" value=\"$oldviews\">";
?>
<input type="hidden" name="input_editdate" value="<?php echo $input_editdate?>">
<input type="hidden" name="programm" value="<?php echo $programm?>">
<input type="hidden" name="input_articlenr" value="<?php echo $myrow["articlenr"]?>">
<input type="hidden" name="heading" value="<?php echo $heading?>">
<input type="hidden" name="keywords" value="<?php echo $keywords?>">
<?php
			if(is_konqueror())
				echo "<tr><td></td></tr>";
?>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_heading?>:</td><td><?php echo undo_html_ampersand(stripslashes($heading))?></td></tr>
<?php
			if(isset($disablehtml))
				echo "<input type=\"hidden\" name=\"disablehtml\" value=\"1\">";
			$sql = "select prog.* from ".$tableprefix."_programm prog where prog.prognr=$programm";
			if(!$tempresult = faqe_db_query($sql, $db))
				db_die("<tr class=\"errorrow\"><td>Unable to get programs from DB.");
			if(!$temprow=faqe_db_fetch_array($tempresult))
			{
				$progname=$l_unknown;
				$proglang=$l_unknown;
			}
			else
			{
				$proglang=$temprow["language"];
				$progname=$temprow["programmname"];
			}
			if(!isset($local_urlautoencode))
				$urlautoencode=0;
			else
				$urlautoencode=1;
			if(!isset($local_enablespcode))
				$enablespcode=0;
			else
				$enablespcode=1;
			$displayarticle="";
			$displaykeywords="";
			if($article)
			{
				$displayarticle=$article;
				if(isset($disablehtml))
				{
					$displayarticle = htmlspecialchars($displayarticle);
					$displayarticle = undo_html_ampersand($displayarticle);
				}
				if($urlautoencode==1)
					$displayarticle = make_clickable($displayarticle);
				if($enablespcode==1)
					$displayarticle = bbencode($displayarticle);
				$displayarticle = stripslashes($displayarticle);
				$displayarticle = do_htmlentities($displayarticle);
				if(!isset($no_br))
				{
					$displayarticle = str_replace("\n", "<BR>", $displayarticle);
					$displayarticle = str_replace("\r", "", $displayarticle);
				}
				$displayarticle = undo_htmlspecialchars($displayarticle);
				$displayarticle = str_replace("{lang}","$langvar=$act_lang",$displayarticle);
				$displayarticle = str_replace("{url_faqengine}",$url_faqengine,$displayarticle);
				$displayarticle = str_replace("{bbc_code}",$l_bbccode,$displayarticle);
				$displayarticle = str_replace("{bbc_quote}",$l_bbcquote,$displayarticle);
			}
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
			echo "<tr class=\"displayrow\"><td align=\"right\" width=\"20%\" valign=\"top\">";
			echo "$l_keywords:</td>";
			echo "<td align=\"left\" width=\"80%\">$displaykeywords</td></tr>";
			echo "<tr class=\"displayrow\"><td align=\"right\" width=\"20%\" valign=\"top\">";
			echo "$l_articletext:</td>";
			echo "<td align=\"left\" width=\"80%\">$displayarticle</td></tr>";
			echo "<tr class=\"inputrow\"><td>&nbsp;</td>";
			echo "<td align=\"left\" width=\"80%\"><textarea class=\"faqeinput\" name=\"article\"  cols=\"$textareascols\" rows=\"$textareasrows\">".undo_htmlspecialchars(do_htmlentities(stripslashes($article)))."</textarea></td></tr>";
			echo "<tr class=\"optionrow\"><td>&nbsp;</td><td>";
			echo "<input type=\"checkbox\" name=\"no_br\" value=\"1\"";
			if(isset($no_br))
				echo " checked";
			echo ">$l_donttranslatelinebreaks</td></tr>";
?>
<tr class="inputrow"><td align="right"><?php echo $l_category?>:</td>
<td>
<?php
			$sql = "select * from ".$tableprefix."_kb_cat where programm=$programm ";
			if(bittst($admedoptions,BIT_2))
				$sql.="order by catname asc";
			else
				$sql.="order by displaypos asc";
			if(!$result = faqe_db_query($sql, $db))
				db_die("<tr class=\"errorrow\"><td>Unable to get categories from DB.");
?>
<select name="category">
<?php
			echo "<option value=\"0\"";
			if($myrow["category"]==0)
				echo " selected";
			echo ">$l_none</option>";
			if ($temprow = faqe_db_fetch_array($result))
			{
				do {
					echo "<option value=\"".$temprow["catnr"]."\"";
					if($myrow["category"]==$temprow["catnr"])
						echo " selected";
					echo ">";
					echo display_encoded($temprow["catname"]);
					echo "</option>";
				} while($temprow = faqe_db_fetch_array($result));
			}
?>
</select></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_or?> <?php echo $l_subcategory?>:</td>
<td>
<?php
			$sql = "select subcat.*, cat.catname as maincat from ".$tableprefix."_kb_cat cat, ".$tableprefix."_kb_subcat subcat where subcat.category=cat.catnr and cat.programm=$programm ";
			if(bittst($admedoptions,BIT_2))
				$sql.="order by cat.catname asc, subcat.catname asc";
			else
				$sql.="order by cat.displaypos asc, subcat.displaypos asc";
			if(!$result = faqe_db_query($sql, $db))
				db_die("<tr class=\"errorrow\"><td>Unable to get subcategories from DB.");
?>
<select name="subcategory">
<?php
			echo "<option value=\"0\">$l_none</option>";
			if ($temprow = faqe_db_fetch_array($result))
			{
				do {
					echo "<option value=\"".$temprow["catnr"]."\"";
					if($myrow["subcategory"]==$temprow["catnr"])
						echo " selected";
					echo ">".$temprow["catname"]." (".$temprow["maincat"].")";
					echo "</option>";
				} while($temprow = faqe_db_fetch_array($result));
			}
?>
</select></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_affectedos?>:</td>
<td>
<?php
			$sql = "SELECT o.osname, o.osnr FROM ".$tableprefix."_os o, ".$tableprefix."_kb_os kbo WHERE kbo.articlenr = $input_articlenr AND o.osnr = kbo.osnr order by o.osname";
			if(!$r = faqe_db_query($sql, $db))
				db_die("<tr class=\"errorrow\"><td>Unable to get affected OS from DB.");
			if ($row = faqe_db_fetch_array($r))
			{
				 do {
				    echo $row["osname"]." (<input type=\"checkbox\" name=\"rem_os[]\" value=\"".$row["osnr"]."\"> $l_remove)<BR>";
				    $current_os[] = $row["osnr"];
				 } while($row = faqe_db_fetch_array($r));
				 echo "<br>";
			}
			else
				echo "$l_noos<br><br>";
			$sql = "SELECT os.* FROM ".$tableprefix."_os os, ".$tableprefix."_prog_os pos where pos.prognr=$programm and os.osnr=pos.osnr";
			if(isset($current_os))
			{
    				while(list($null, $curros) = each($current_os)) {
					$sql .= " AND os.osnr != $curros";
    				}
    			}
    			$sql .= " ORDER BY os.osname";
    			if(!$r = faqe_db_query($sql, $db))
				db_die("<tr class=\"errorrow\"><td>Unable to get not affected OS from DB.");
    			if($row = faqe_db_fetch_array($r)) {
				echo "<span class=\"inlineheading1\">$l_add:</span><br>";
				echo "<SELECT NAME=\"os[]\" size=\"5\" multiple>";
				do {
					echo "<OPTION VALUE=\"$row[osnr]\" >$row[osname]</OPTION>\n";
				} while($row = faqe_db_fetch_array($r));
				echo"</select>";
			}
?>
</td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_affected_versions?>:</td>
<td>
<?php
			$versionsql = "select progver.* from ".$tableprefix."_programm_version progver, ".$tableprefix."_kb_prog_version kbpv where kbpv.articlenr=$input_articlenr and progver.entrynr=kbpv.progversion order by progver.version"; 
			if(!$versionresult = faqe_db_query($versionsql, $db))
				db_die("<tr class=\"errorrow\"><td>Unable to get affected versions from DB.");
			if ($versionrow = faqe_db_fetch_array($versionresult))
			{
				 do {
				    echo stripslashes($versionrow["version"])." (<input type=\"checkbox\" name=\"rem_kbpvs[]\" value=\"".$versionrow["entrynr"]."\"> $l_remove)<BR>";
				    $current_kbpvs[] = $versionrow["entrynr"];
				 } while($versionrow = faqe_db_fetch_array($versionresult));
				 echo "<br>";
			}
			else
				echo "$l_nodefversions<br><br>";
			$versionsql = "SELECT * from ".$tableprefix."_programm_version where programm=$programm ";
			if(isset($current_kbpvs))
			{
    				while(list($null, $currkbpv) = each($current_kbpvs)) {
					$versionsql .= "and entrynr != $currkbpv ";
    				}
    			}
    			$versionsql .="order by version";
    			if(!$versionresult = faqe_db_query($versionsql, $db))
				db_die("<tr class=\"errorrow\"><td>Unable to get not affected versions from DB.");
    			if($versionrow = faqe_db_fetch_array($versionresult)) {
				echo "<span class=\"inlineheading1\">$l_add:</span><br>";
				echo "<SELECT NAME=\"kbpvs[]\" size=\"5\" multiple>";
				do {
					echo "<OPTION VALUE=\"".$versionrow["entrynr"]."\" >".stripslashes($versionrow["version"])."</OPTION>\n";
				} while($versionrow = faqe_db_fetch_array($versionresult));
				echo "</select>";
			}
?>
</td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_attachfile?>:</td>
<td>
<?php
			$attachsql="select f.filename, f.filesize, kba.entrynr from ".$tableprefix."_files f, ".$tableprefix."_kb_attachs kba where f.entrynr=kba.attachnr and kba.articlenr=$input_articlenr";
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
			echo "<a class=\"listlink\" href=\"javascript:openWindow2('".do_url_session("dbfiles.php?$langvar=$lang&articlenr=$input_articlenr&mode=2")."',20,20,620,300);\">";
			echo "$l_select_file_from_db</a>";
?>
</td></tr>
<tr class="actionrow"><td align="center" colspan="2"><input type="hidden" name="mode" value="update">
<input class="faqebutton" type="submit" value="<?php echo $l_update?>">&nbsp;&nbsp;
<input class="faqebutton" type="button" value="<?php echo $l_back ?>" onclick="self.history.back();">
</td></tr>
</form>
</table></tr></td></table>
<?php
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_articlelist</a></div>";
		}
		else
		{
			echo "<tr class=\"actionrow\" align=\"center\"><td>";
			echo "<a href=\"javascript:history.back()\">$l_back</a>";
			echo "</td></tr></table></td></tr></table>";
		}
?>