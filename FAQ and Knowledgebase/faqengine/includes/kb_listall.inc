<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
	if(!isset($prog))
		die("<tr bgcolor=\"#cccccc\"><td>$l_callingerror");
	$sql = "select * from ".$tableprefix."_programm where (progid='$prog') and (language='$act_lang')";
	if(!$result = faqe_db_query($sql, $db))
	{
		echo "<tr><td bgcolor=\"$group_bgcolor\">";
		die("Could not connect to the database.");
	}
	if($myrow=faqe_db_fetch_array($result))
	{
		$prognr=$myrow["prognr"];
		$progname=display_encoded($myrow["programmname"]);
		if($myrow["description"])
			$proginfoavail=1;
		else
			$proginfoavail=0;
	}
	else
	{
		echo "<tr><td bgcolor=\"$group_bgcolor\">";
    		die($l_nosuchprog);
	}
?>
<tr BGCOLOR="<?php echo $subheadingbgcolor?>" ALIGN="CENTER">
<?php
	echo "<TD class=\"subheading\" ALIGN=\"CENTER\" VALIGN=\"MIDDLE\"";
	if($proginfoavail==1)
		echo " width=\"98%\"";
	echo ">";
?>
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize2?>; color: <?php echo $SubheadingFontColor?>; font-weight: bold;">
<?php echo $l_listallarticles?>: <?php echo $progname?></span></td>
<?php
	if($proginfoavail==1)
	{
		$proginfolink="proginfo.php?$langvar=$act_lang&prog=$prognr";
		if(isset($layout))
			$proginfolink.="&layout=$layout";
		echo "<td width=\"2%\" align=\"center\" bgcolor=\"$subheadingbgcolor\" class=\"proginfolink\"";
		if($hovercells==1)
			echo " onMouseOver=\"this.style.backgroundColor='$activcellcolor';\" onMouseOut=\"this.style.backgroundColor='$subheadingbgcolor'\" onclick=\"openProginfo('$proginfolink')\"";
		echo "><a class=\"proginfolink\" href=\"javascript:openProginfo('$proginfolink')\"><img class=\"proginfolink\" src=\"$proginfopic\" border=\"0\" title=\"$l_proginfo\" alt=\"$l_proginfo\"></a></td>";
	}
?>
</tr></table></td></tr>
<tr><TD BGCOLOR="<?php echo $table_bgcolor?>">
<?php
		$actfaq=0;
		$displayedfaq=0;
		if($maxentries>0)
		{
			include_once("./includes/faq_pagenav.inc");
			$sql = "select * from ".$tableprefix."_kb_articles where programm=$prognr";
			if(!$result = faqe_db_query($sql, $db))
			{
				echo "<tr><td bgcolor=\"$heading_bgcolor\">";
		    		die("Could not connect to the database.");
		    	}
		    	$totalfaqs=faqe_db_num_rows($result);
		}
		else
			$totalfaqs=0;
		$nocats=0;
		$sql = "select * from ".$tableprefix."_kb_cat where (programm=$prognr) order by displaypos asc";
		if(!$result = faqe_db_query($sql, $db))
		{
			echo "<tr><td bgcolor=\"$heading_bgcolor\">";
	    		die("Could not connect to the database.");
	    	}
		if (!$myrow = faqe_db_fetch_array($result))
		{
			$nocats=1;
		}
		else
		{
			do {
				$entries=0;
				if($maxentries>0)
				{
					$sql2 = "select * from ".$tableprefix."_kb_articles where category=".$myrow["catnr"]." and programm=$prognr";
					if(!$tempresult = faqe_db_query($sql2, $db))
					{
						echo "<tr><td bgcolor=\"$heading_bgcolor\">";
		    				die("Could not connect to the database.".faqe_db_error());
		    			}
		    			$numfaqs=faqe_db_num_rows($tempresult);
		    		}
				if(($maxentries>0) && ($actfaq+1>$start+$maxentries))
					continue;
				if(($maxentries>0) && (($actfaq+$numfaqs<$start) || ($numfaqs<=0)))
				{
					$actfaq+=$numfaqs;
					$displayedfaq+=$numfaqs;
					continue;
				} 			
				$catnr=$myrow["catnr"];
				echo "<TABLE BORDER=\"0\" CELLPADDING=\"$tablepadding\" CELLSPACING=\"$tablespacing\" WIDTH=\"100%\">";
				echo "<TR BGCOLOR=\"$group_bgcolor\" ALIGN=\"LEFT\">";
				if($navframe==1)
					$link=$url_faqengine."/kbframe.php";
				else
					$link=$act_script_url;
				$link.="?mode=category&amp;$langvar=$act_lang&amp;catnr=$catnr&amp;prog=$prog";
				if(isset($limitprog))
					$link.="&amp;limitprog=$limitprog";
				if(isset($layout))
					$link.="&amp;layout=$layout";
				echo "<td align=\"left\" class=\"catlink\"";
				if($hovercells==1)
				{
					echo " onMouseOver=\"this.style.backgroundColor='$activcellcolor';\" onMouseOut=\"this.style.backgroundColor='$group_bgcolor'\"";
					if($navframe==0)
						echo " onclick=\"window.location.href='$link'\"";
				}
				echo ">";
				echo "<span style=\"font-face: $FontFace; font-size: $FontSize5; color: $GroupFontColor; font-weight: bold;\">";
				echo "<a class=\"catlink\" href=\"$link\"";
				if($navframe==1)
					echo " target=\"_parent\"";
				echo ">";
				echo display_encoded($myrow["catname"]);
				echo "</a></span></td>";
				echo "</table>";
				$sql2 = "select * from ".$tableprefix."_kb_articles where category=".$myrow["catnr"]." and programm=$prognr and subcategory=0";
				$sql2.=" order by displaypos asc";
				if(!$tempresult = faqe_db_query($sql2, $db))
				{
					echo "<tr><td bgcolor=\"$heading_bgcolor\">";
	    				die("Could not connect to the database.".faqe_db_error());
	    			}
				if ($temprow = faqe_db_fetch_array($tempresult))
				{
					$entries=1;
					echo "<TABLE BORDER=\"0\" CELLPADDING=\"$tablepadding\" CELLSPACING=\"$tablespacing\" WIDTH=\"100%\">";
					do {
						$actfaq++;
						if(($maxentries>0) && (($actfaq-1 >= ($start+$maxentries)) ))
							continue;
						$displayedfaq++;
						if($actfaq-1<$start)
							continue;
						$act_id=$temprow["articlenr"];
						echo "<TR BGCOLOR=\"$row_bgcolor\" ALIGN=\"LEFT\">";
						if($navframe==1)
							$backurl=$url_faqengine."/kbframe.php?";
						else
							$backurl=$act_script_url."?";
						if(isset($limitprog))
							$backurl.="limitprog=$limitprog&";
						if(isset($layout))
							$backurl.="layout=$layout&";
						$backurl=urlencode($backurl."mode=listall&$langvar=$act_lang&prog=$prog");
						if($navframe==1)
							$link=$url_faqengine."/kbframe.php";
						else
							$link=$act_script_url;
						$link.="?mode=display&amp;kbnr=$act_id&amp;$langvar=$act_lang&amp;prog=$prog";
						if(isset($limitprog))
							$link.="&amp;limitprog=$limitprog";
						if(isset($layout))
							$link.="&amp;layout=$layout";
						$link.="&amp;backurl=$backurl";
						echo "<td align=\"left\" class=\"listlink\"";
						if($hovercells==1)
						{
							echo " onMouseOver=\"this.style.backgroundColor='$activcellcolor';\" onMouseOut=\"this.style.backgroundColor='$row_bgcolor'\"";
							if($navframe==0)
								echo " onclick=\"window.location.href='$link'\"";
						}
						echo ">";
						echo "<span style=\"font-face: $FontFace; font-size: $FontSize1;\">";
						echo "<a class=\"listlink\" href=\"$link\"";
						if($navframe==1)
							echo " target=\"_parent\"";
						echo ">";
						echo undo_html_ampersand(stripslashes($temprow["heading"]));
						echo "</a></span></td></tr>";
		   			} while($temprow = faqe_db_fetch_array($tempresult));
		   			echo "</table>";
				}
				$tempsql = "select * from ".$tableprefix."_kb_subcat where category=".$myrow["catnr"]." order by displaypos asc";
				if(!$tempresult = faqe_db_query($tempsql, $db))
				{
					echo "<tr><td bgcolor=\"$heading_bgcolor\">";
	    				die("Could not connect to the database.".faqe_db_error());
	    			}
	    			if($temprow=faqe_db_fetch_array($tempresult))
	    			{
	    				do{
						$datasql = "select * from ".$tableprefix."_kb_articles where category=".$myrow["catnr"]." and programm=$prognr and subcategory=".$temprow["catnr"];
						$datasql.=" order by displaypos asc";
						if(!$dataresult = faqe_db_query($datasql, $db))
						{
							echo "<tr><td bgcolor=\"$heading_bgcolor\">";
							die("Could not connect to the database.".faqe_db_error());
						}
		    				if($datarow=faqe_db_fetch_array($dataresult))
		    				{
							$entries=1;
							$headerdisplayed=false;
		    					do{
								$actfaq++;
								if(($maxentries>0) && (($actfaq-1 >= ($start+$maxentries)) ))
									continue;
								$displayedfaq++;
								if($actfaq-1<$start)
									continue;
								if(!$headerdisplayed)
								{
									$headerdisplayed=true;
									echo "<TABLE BORDER=\"0\" CELLPADDING=\"$tablepadding\" CELLSPACING=\"$tablespacing\" WIDTH=\"100%\">";
									echo "<TR BGCOLOR=\"$subcatbgcolor\" ALIGN=\"LEFT\">";
									echo "<td class=\"subcat\">";
									switch($subcatfontstyle)
									{
										case 1:
											$addstyle="font-style: italic;";
											break;
										case 2:
											$addstyle="font-weight: bold;";
											break;
										case 3:
											$addstyle="font-weight: bold; font-style: italic;";
											break;
										default:
											$addstyle="";
											break;
									}
									echo "<span style=\"font-face: $FontFace; font-size: $subcatfontsize; color: $subcatfontcolor; $addstyle\">";
									echo display_encoded($temprow["heading"])."</span></td></tr>";
								}
								$act_id=$datarow["articlenr"];
								echo "<TR BGCOLOR=\"$row_bgcolor\" ALIGN=\"LEFT\">";
								if($navframe==1)
									$backurl=$url_faqengine."/kbframe.php?";
								else
									$backurl=$act_script_url."?";
								if(isset($limitprog))
									$backurl.="limitprog=$limitprog&";
								if(isset($layout))
									$backurl.="layout=$layout&";
								$backurl=urlencode($backurl."mode=listall&$langvar=$act_lang&prog=$prog");
								if($navframe==1)
									$link=$url_faqengine."/kbframe.php";
								else
									$link=$act_script_url;
								$link.="?mode=display&amp;kbnr=$act_id&amp;$langvar=$act_lang&amp;prog=$prog";
								if(isset($limitprog))
									$link.="&amp;limitprog=$limitprog";
								if(isset($layout))
									$linkurl.="&amp;layout=$layout";
								$link.="&amp;backurl=$backurl";
								echo "<td align=\"left\" class=\"listlink\"";
								if($hovercells==1)
								{
									echo " onMouseOver=\"this.style.backgroundColor='$activcellcolor';\" onMouseOut=\"this.style.backgroundColor='$row_bgcolor'\"";
									if($navframe==0)
										echo " onclick=\"window.location.href='$link'\"";
								}
								echo ">";
								echo "<span style=\"font-face: $FontFace; font-size: $FontSize1;\">";
								echo "<a class=\"listlink\" href=\"$link\"";
								if($navframe==1)
									echo " target=\"_parent\"";
								echo ">";
								echo undo_html_ampersand(stripslashes($datarow["heading"]));
								echo "</a></span></td></tr>";
		    					}while($datarow=faqe_db_fetch_array($dataresult));
			   				echo "</table>";
			   			}
		   			}while($temprow=faqe_db_fetch_array($tempresult));
    				}
    				if($entries<1)
    				{
					echo "<TABLE BORDER=\"0\" CELLPADDING=\"$tablepadding\" CELLSPACING=\"$tablespacing\" WIDTH=\"100%\">";
					echo "<TR BGCOLOR=\"$row_bgcolor\" ALIGN=\"LEFT\">";
					echo "<td align=\"center\" class=\"listrow\">";
					echo "<span style=\"font-face: $FontFace; font-size: $FontSize1;\">$l_noentries</span>";
					echo "</td></tr></table>";
				}
		   	} while($myrow = faqe_db_fetch_array($result));
		}
		$sql = "select * from ".$tableprefix."_kb_articles where programm=$prognr and category=0 order by displaypos asc";
		if(!$result = faqe_db_query($sql, $db))
		{
			echo "<tr><td bgcolor=\"$heading_bgcolor\">";
		    	die("Could not connect to the database.");
		}
		$numfaqs=faqe_db_num_rows($result);
		if(($maxentries<=0) || (($maxentries>0) && ($actfaq+$numfaqs>$start) && ($actfaq+1<=$start+$maxentries)))
		{
			if(!$myrow = faqe_db_fetch_array($result))
			{
				if($nocats==1)
				{
					echo "<TABLE BORDER=\"0\" CELLPADDING=\"$tablepadding\" CELLSPACING=\"$tablespacing\" WIDTH=\"100%\">";
					echo "<tr><td class=\"listrow\" bgcolor=\"$row_bgcolor\" align=\"center\">";
					echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
					echo "$l_noentries</span>";
					echo "</td></tr></table>";
				}
			}
			else
			{
				echo "<TABLE BORDER=\"0\" CELLPADDING=\"$tablepadding\" CELLSPACING=\"$tablespacing\" WIDTH=\"100%\">";
				echo "<TR BGCOLOR=\"$group_bgcolor\" ALIGN=\"LEFT\">";
				if($navframe==1)
					$link=$url_faqengine."/kbframe.php";
				else
					$link=$act_script_url;
				$link.="?mode=category&amp;$langvar=$act_lang&amp;catnr=0&amp;prog=$prog";
				if(isset($limitprog))
					$link.="&amp;limitprog=$limitprog";
				if(isset($layout))
					$link.="&amp;layout=$layout";
				echo "<td align=\"left\" class=\"catlink\"";
				if($hovercells==1)
				{
					echo " onMouseOver=\"this.style.backgroundColor='$activcellcolor';\" onMouseOut=\"this.style.backgroundColor='$group_bgcolor'\"";
					if($navframe==0)
						echo " onclick=\"window.location.href='$link'\"";
				}
				echo ">";
				echo "<span style=\"font-face: $FontFace; font-size: $FontSize5; color: $GroupFontColor; font-weight: bold;\">";
				echo "<a class=\"catlink\" href=\"$link\"";
				if($navframe==1)
					echo " target=\"_parent\"";
				echo ">";
				echo $l_withoutcategory;
				echo "</a></span></td>";
				echo "</table>";
				echo "<TABLE BORDER=\"0\" CELLPADDING=\"$tablepadding\" CELLSPACING=\"$tablespacing\" WIDTH=\"100%\">";
				do{
					$actfaq++;
					if(($maxentries>0) && (($actfaq-1 >= ($start+$maxentries)) ))
						continue;
					$displayedfaq++;
					if($actfaq-1<$start)
						continue;
					$act_id=$myrow["articlenr"];
					echo "<TR BGCOLOR=\"$row_bgcolor\" ALIGN=\"LEFT\">";
					echo "</td>";
					if($navframe==1)
						$backurl=$url_faqengine."/kbframe.php?";
					else
						$backurl=$act_script_url."?";
					if(isset($limitprog))
						$backurl.="limitprog=$limitprog&";
					if(isset($layout))
						$backurl.="layout=$layout&";
					$backurl=urlencode($backurl."mode=listall&$langvar=$act_lang&prog=$prog");
					if($navframe==1)
						$link=$url_faqengine."/kbframe.php";
					else
						$link=$act_script_url;
					$link.="?mode=display&amp;kbnr=$act_id&amp;$langvar=$act_lang&amp;prog=$prog";
					if(isset($limitprog))
						$link.="&amp;limitprog=$limitprog";
					if(isset($layout))
						$link.="&amp;layout=$layout";
					$link.="&amp;backurl=$backurl";
					echo "<td align=\"left\" class=\"listlink\"";
					if($hovercells==1)
					{
						echo " onMouseOver=\"this.style.backgroundColor='$activcellcolor';\" onMouseOut=\"this.style.backgroundColor='$row_bgcolor'\"";
						if($navframe==0)
							echo " onclick=\"window.location.href='$link'\"";
					}
					echo ">";
					echo "<span style=\"font-face: $FontFace; font-size: $FontSize1;\">";
					echo "<a class=\"listlink\" href=\"$link\"";
					if($navframe==1)
						echo " target=\"_parent\"";
					echo ">";
					echo undo_html_ampersand(stripslashes($myrow["heading"]));
					echo "</a></span></td></tr>";
				}while($myrow=faqe_db_fetch_array($result));
				echo "</table>";
			}
		}
?>
</td></tr>
<tr><TD BGCOLOR="<?php echo $table_bgcolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<?php
		if(($maxentries>0) && ($totalfaqs>$maxentries))
		{
			$url_start="$act_script_url?$langvar=$act_lang&amp;mode=listall&amp;prog=$prog";
			if($navframe==1)
				$url_start.="&amp;navframe=1";
			if(isset($limitprog))
				$url_start.="&amp;limitprog=$limitprog";
			echo "<tr bgcolor=\"$actionbgcolor\"><td class=\"pagenav\" align=\"center\" valign=\"middle\" colspan=\"2\"";
			if($proginfoavail==0)
				echo " colspan=\"2\"";
			echo">";
			faq_pagenav($start, $maxentries, $totalfaqs, $displayedfaq, $url_start);
			echo "</td></tr>";
		}
if($navframe==1)
{
	$linkbase=$url_faqengine."/kbframe.php?";
	$linktarget="_parent";
}
else
{
	$linkbase=$act_script_url."?";
	$linktarget="_self";
}
if(isset($limitprog))
	$linkbase.="limitprog=$limitprog&amp;";
if(isset($layout))
	$linkbase.="layout=$layout&amp;";
?>
<TR BGCOLOR="<?php echo $actionbgcolor?>" ALIGN="LEFT">
<td class="actionline" align="center" width="99%">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $actionlinefontsize?>; color: <?php echo $FontColor?>;">
<a class="actionline" href="<?php echo $linkbase?>mode=catlist&amp;prog=<?php echo $prog?>&amp;<?php echo "$langvar=$act_lang"?>" target="<?php echo $linktarget?>"><?php echo $l_listcats?></a>
<?php
if(!isset($limitprog))
	echo " | <a class=\"actionline\" href=\"".$linkbase."mode=proglist&amp;$langvar=$act_lang\" target=\"$linktarget\">$l_listprogs</a>";
?>
| <a class="actionline" href="<?php echo $linkbase?><?php echo "$langvar=$act_lang"?>&amp;mode=search" target="<?php echo $linktarget?>"><?php echo $l_keywordsearch?></a>
| <a class="actionline" href="<?php echo $linkbase?><?php echo "$langvar=$act_lang"?>&amp;mode=wizard" target="<?php echo $linktarget?>"><?php echo $l_wizard?></a>
</span>
</td>
<td class="gototop" align="right" width="1%"><a class="gototop" href="#top">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $actionlinefontsize?>;"><img class="gototop" src="<?php echo $pagetoppic?>" border="0" align="middle" title="<?php echo $l_top?>" alt="<?php echo $l_top?>"></span></a></td>
</tr>
</table></td></tr></table>
<?php
	if($enablelanguageselector==1)
	{
		echo "<table style=\"clear:both\" width=\"$TableWidth\" align=\"$tblalign\" border=\"0\">\n";
		echo "<form method=\"post\" action=\"$form_url\" target=\"$form_target\">";
		if(isset($limitprog))
			echo "<input type=\"hidden\" name=\"limitprog\" value=\"$limitprog\">";
		if(isset($layout))
			echo "<input type=\"hidden\" name=\"layout\" value=\"$layout\">";
		echo "<input type=\"hidden\" name=\"mode\" value=\"$mode\">";
		echo "<input type=\"hidden\" name=\"prog\" value=\"$prog\">";
		echo "<tr bgcolor=\"$group_bgcolor\"><td align=\"center\" class=\"langselect\">\n";
		echo "<span style=\"color: $FontColor; font-face: $FontFace; font-size: $langselectfontsize;\">";
		echo "$l_changelang: ".language_select($act_lang,$langvar,"./language","langselect");
		echo "&nbsp;&nbsp;&nbsp;<input class=\"langselect\" type=\"submit\" value=\"$l_ok\">";
		echo "</span></td></tr></form></table>\n";
	}
?>