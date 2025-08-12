<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
if($show_proglist==0)
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
	echo "$l_disallowed</td></tr>";
	echo "</table></div>";
	include_once("./includes/bottom.inc");
	exit;
}
?>
<tr><TD BGCOLOR="<?php echo $table_bgcolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<tr BGCOLOR="<?php echo $subheadingbgcolor?>" ALIGN="CENTER">
<TD ALIGN="CENTER" VALIGN="MIDDLE">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize2?>; color: <?php echo $SubheadingFontColor?>; font-weight: bold">
<?php echo $l_proglist?></span>
</td></tr></table></td></tr>
<tr><TD BGCOLOR="<?php echo $table_bgcolor?>">
<TABLE BORDER="0" CELLPADDING="<?php echo $tablepadding?>" CELLSPACING="<?php echo $tablespacing?>" WIDTH="100%">
<?php
		$actprog=0;
		$displayedprog=0;
		$actdate = date("Y-m-d");
		if(isset($onlynewfaq) && ($onlynewfaq>0) && ($faqnewdisplaymethod==0))
			$sql = "select prog.* from ".$tableprefix."_programm prog, ".$tableprefix."_category cat, ".$tableprefix."_data dat where dat.editdate >= DATE_SUB('$actdate', INTERVAL $onlynewfaq DAY) and cat.catnr=dat.category and prog.prognr=cat.programm and prog.language='$act_lang' group by prog.prognr order by prog.displaypos asc";
		else
			$sql = "select * from ".$tableprefix."_programm where (language='$act_lang') order by displaypos asc";
		if(!$result = faqe_db_query($sql, $db))
		{
			echo "<tr><td bgcolor=\"$heading_bgcolor\">";
	    		die("Could not connect to the database.");
	    	}
	    	if($maxentries>0)
	    	{
			include_once("./includes/faq_pagenav.inc");
	    		$totalprogs=faqe_db_num_rows($result);
	    	}
	    	else
	    		$totalprogs=0;
		if (!$myrow = faqe_db_fetch_array($result))
		{
			echo "<TR BGCOLOR=\"$row_bgcolor\" ALIGN=\"LEFT\">";
			echo "<td align=\"center\">";
			echo "<span style=\"font-face: $FontFace; font-size: $FontSize2;\">";
			if(isset($onlynewfaq) && ($onlynewfaq>0) && ($faqnewdisplaymethod==0))
				echo $l_nonewentries;
			else
				echo $l_noentries;
			echo "</span></td></tr>";
		}
		else
		{
?>
<tr bgcolor="<?php echo $group_bgcolor?>">
<td class="tabledesc" colspan="2" align="center">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $tabledescfontsize?>; color: <?php echo $TableDescFontColor?>; font-weight: bold">
<?php echo $l_progname?></span></td>
<td class="tabledesc" align="center" width="10%">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $tabledescfontsize?>; color: <?php echo $TableDescFontColor?>; font-weight: bold">
<?php echo $l_numcats?></span></td>
<td class="tabledesc" align="center" width="10%">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $tabledescfontsize?>; color: <?php echo $TableDescFontColor?>; font-weight: bold">
<?php echo $l_faqs?></span></td>
<td width="15%">&nbsp;</td><td width="15%">&nbsp;</td></tr>
<?php
			do {
				$actprog++;
				if(($maxentries>0) && (($actprog-1 >= ($start+$maxentries)) ))
					continue;
				$displayedprog++;
				if($actprog-1<$start)
					continue;
 				$progbgcolor=$group_bgcolor;
 				$progfontcolor=$GroupFontColor;
 				$act_id=$myrow["progid"];
 				$prognr=$myrow["prognr"];
				$countsql = "select count(dat.faqnr) as faqcount from ".$tableprefix."_programm prog, ".$tableprefix."_category cat, ".$tableprefix."_data dat where prog.prognr='$prognr' and cat.catnr=dat.category and prog.prognr=cat.programm and prog.language='$act_lang' group by prog.prognr order by prog.displaypos asc";
				if(!$countresult = faqe_db_query($countsql, $db))
				{
					echo "<tr><td bgcolor=\"$heading_bgcolor\">";
    					die("Could not connect to the database.");
    				}
    				if($countrow=faqe_db_fetch_array($countresult))
	    				$faqcount=$countrow["faqcount"];
	    			else
	    				$faqcount=0;
	    			$newfaqs=0;
				if(isset($onlynewfaq) && ($onlynewfaq>0) && ($faqnewdisplaymethod==1))
				{
					$newsql = "select count(dat.faqnr) as newcount from ".$tableprefix."_programm prog, ".$tableprefix."_category cat, ".$tableprefix."_data dat where prog.prognr='$prognr' and dat.editdate >= DATE_SUB('$actdate', INTERVAL $onlynewfaq DAY) and cat.catnr=dat.category and prog.prognr=cat.programm and prog.language='$act_lang' group by prog.prognr order by prog.displaypos asc";
					if(!$newresult = faqe_db_query($newsql, $db))
					{
						echo "<tr><td bgcolor=\"$heading_bgcolor\">";
	    				die("Could not connect to the database.");
	    			}
	    			if($newrow=faqe_db_fetch_array($newresult))
	    			{
	    				$newfaqs=$newrow["newcount"];
	    				if($newrow["newcount"]>0)
	    				{
	    					$progbgcolor=$faqnewdisplaybgcolor;
	    					$progfontcolor=$faqnewdisplayfontcolor;
	    				}
	    			}
				}
				echo "<TR BGCOLOR=\"$row_bgcolor\" ALIGN=\"LEFT\">";
				echo "<td class=\"listrow\" bgcolor=\"$progbgcolor\" width=\"38%\">";
				echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $progfontcolor;\">";
				echo display_encoded($myrow["programmname"])."</span></td>";
				if(!$myrow["description"])
				{
					echo "<td class=\"proginfolink\" bgcolor=\"$group_bgcolor\" width=\"2%\" align=\"center\">";
					echo "&nbsp;";
				}
				else
				{
					$proginfolink="proginfo.php?$langvar=$act_lang&prog=".$myrow["prognr"];
					if(isset($layout))
						$proginfolink.="&layout=$layout";
					echo "<td width=\"2%\" align=\"center\" bgcolor=\"$group_bgcolor\" class=\"proginfolink\"";
					if($hovercells==1)
						echo " onMouseOver=\"this.style.backgroundColor='$activcellcolor';\" onMouseOut=\"this.style.backgroundColor='$group_bgcolor'\" onclick=\"openProginfo('$proginfolink')\"";
					echo ">";
					echo "<span style=\"font-face: $FontFace; font-size: $FontSize1;\">";
					echo "<a class\"proginfolink\" href=\"javascript:openProginfo('$proginfolink')\"><img src=\"$proginfopic\" border=\"0\" title=\"$l_proginfo\" alt=\"$l_proginfo\"></a></span>";
				}
				echo "</td>";
				echo "<td class=\"listrow\" align=\"right\">";
				echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
				echo $myrow["numcats"]."</span></td>\n";
				echo "<td class=\"listrow\" align=\"right\">";
				echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
				echo "$faqcount";
				if(isset($onlynewfaq) && ($onlynewfaq>0) && ($newfaqs>0))
					echo "<br>($l_new: $newfaqs)";
				echo "</span></td>\n";
				if($myrow["numcats"]>0)
				{
					if($altlinkmethod==0)
					{
						if($navframe==1)
							$link="$url_faqengine/faqframe.php";
						else
							$link=$act_script_url;
						if(isset($onlynewfaq))
							$link.="?list=all&amp;prog=$act_id&amp;$langvar=$act_lang&amp;onlynewfaq=$onlynewfaq";
						else
							$link.="?list=all&amp;prog=$act_id&amp;$langvar=$act_lang";
						if(isset($layout))
							$link.="&amp;layout=$layout";
						if(isset($limitprog))
							$link.="&amp;limitprog=$limitprog";
					}
					else
					{
						$link="listallfaq.php?id=$act_id|$act_lang|$layout";
					}
					echo "<td align=\"center\" class=\"listlink\"";
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
					echo $l_listall;
					echo "</a></span></td>";
					if($altlinkmethod==0)
					{
						if($navframe==1)
							$link="$url_faqengine/faqframe.php";
						else
							$link=$act_script_url;
						$link.="?list=categories";
						if(isset($onlynewfaq))
							$link.="&amp;prog=$act_id&amp;$langvar=$act_lang&amp;onlynewfaq=$onlynewfaq";
						else
							$link.="&amp;prog=$act_id&amp;$langvar=$act_lang";
						if(isset($layout))
							$link.="&amp;layout=$layout";
						if(isset($limitprog))
							$link.="&amp;limitprog=$limitprog";
					}
					else
						$link="listcats.php?id=$act_id|$act_lang|$layout";
					echo "<td align=\"center\" class=\"listlink\"";
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
					echo $l_listcats;
					echo "</a></span></td>";
				}
				else
					echo "<td>&nbsp;</td><td>&nbsp;</td>";
				echo "</tr>";
			} while($myrow = faqe_db_fetch_array($result));
		}
		if(($maxentries>0) && ($totalprogs>$maxentries))
		{
			$url_start="$act_script_url?$langvar=$act_lang&amp;list=progs&amp;onlynewfaq=$onlynewfaq";
			if(isset($layout))
				$url_start.="&amp;layout=$layout";
			if($navframe==1)
			{
				$url_start.="&amp;navframe=1";
				if(isset($limitprog))
					$url_start.="&amp;limitprog=$limitprog";
			}
			echo "<tr class=\"pagenav\" bgcolor=\"$actionbgcolor\"><td align=\"center\" valign=\"middle\" colspan=\"6\">";
			faq_pagenav($start, $maxentries, $totalprogs, $displayedprog, $url_start);
			echo "</td></tr>";
		}
		echo"</table></td></tr></table>";
		if($enablelanguageselector==1)
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
			if($navframe==1)
				echo "<input type=\"hidden\" name=\"navframe\" value=\"1\">";
			if(isset($layout))
				echo "<input type=\"hidden\" name=\"layout\" value=\"$layout\">";
			if(isset($onlynewfaq))
				echo "<input type=\"hidden\" name=\"onlynewfaq\" value=\"$onlynewfaq\">";
			if(isset($limitprog))
				echo "<input type=\"hidden\" name=\"limitprog\" value=\"$limitprog\">";
			echo "<input type=\"hidden\" name=\"list\" value=\"progs\">";
			echo "<span style=\"font-face: $FontFace; font-size: $langselectfontsize; color: $FontColor;\">";
			echo "$l_changelang: ".language_select($act_lang,$langvar,"./language","langselect");
			echo "&nbsp;&nbsp;&nbsp;<input class=\"langselect\" type=\"submit\" value=\"$l_ok\"></span>";
			echo "</td></tr></form></table>";
		}
		if($enablefaqnewdisplay==1)
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
			echo "<tr bgcolor=\"$group_bgcolor\"><td nowrap class=\"faqnewdisplay\" align=\"center\">";
			if($navframe==1)
				echo "<input type=\"hidden\" name=\"navframe\" value=\"1\">";
			if(isset($layout))
				echo "<input type=\"hidden\" name=\"layout\" value=\"$layout\">";
			if(isset($limitprog))
				echo "<input type=\"hidden\" name=\"limitprog\" value=\"$limitprog\">";
			echo "<input type=\"hidden\" name=\"list\" value=\"progs\">";
			echo "<input type=\"hidden\" name=\"$langvar\" value=\"$act_lang\">";
			echo "<span style=\"font-face: $FontFace; font-size: $faqnewfontsize; color: $FontColor\">";
			echo "$l_onlynewwithin ";
			echo "<select class=\"faqnewdisplay\" name=\"onlynewfaq\">";
			echo "<option value=\"0\">$l_all</option>";
			if(isset($onlynewfaq) and ($onlynewfaq>30))
				$maxselectdays=$onlynewfaq+1;
			else
				$maxselectdays=31;
			for($i=1;$i<$maxselectdays;$i++)
			{
				echo "<option value=\"$i\"";
				if(isset($onlynewfaq) && ($onlynewfaq==$i))
					echo " selected";
				echo ">$i</option>";
			}
			echo "</select>";
			echo "&nbsp;$l_days";
			echo "&nbsp;&nbsp;&nbsp;<input class=\"faqnewdisplay\" type=\"submit\" value=\"$l_ok\">\n";
			echo "</span></td></tr></form></table>";
		}
?>