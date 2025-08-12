<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
?>
<tr><TD BGCOLOR="<?php echo $table_bgcolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<tr BGCOLOR="<?php echo $subheadingbgcolor?>" ALIGN="CENTER">
<TD class="subheading" ALIGN="CENTER" VALIGN="MIDDLE">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize2?>; color: <?php echo $SubheadingFontColor?>; font-weight: bold;">
<?php echo $l_singlesubcat?></span>
</td></tr></table></td></tr>
<?php
		if($enablejumpboxes==1)
		{
?>
<tr><TD BGCOLOR="<?php echo $table_bgcolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<?php
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
			echo "<span style=\"font-face: $FontFace; font-size: $jumpboxfontsize; color: $FontColor; font-weight: bold\">";
			if($navframe==1)
				echo "<input type=\"hidden\" name=\"navframe\" value=\"1\">";
			if(isset($layout))
				echo "<input type=\"hidden\" name=\"layout\" value=\"$layout\">";
			if(isset($limitprog))
				echo "<input type=\"hidden\" name=\"limitprog\" value=\"$limitprog\">";
			echo "<input type=\"hidden\" name=\"$langvar\" value=\"$act_lang\">";
			echo "<input type=\"hidden\" name=\"onlynewfaq\" value=\"$onlynewfaq\">";
			echo "<input type=\"hidden\" name=\"list\" value=\"subcategory\">";
			echo "<input type=\"hidden\" name=\"prog\" value=\"$prog\">";
			echo "<input type=\"hidden\" name=\"catnr\" value=\"$catnr\">";
			$sql = "select subcat.* from ".$tableprefix."_subcategory subcat where subcat.category=$catnr ";
			switch($jumpboxsorting)
			{
				case 1:
					$sql.="order by subcat.categoryname asc";
					break;
				case 2:
					$sql.="order by subcat.displaypos asc";
					break;
				default:
					$sql.="order by subcat.catnr asc";
			}
			if(!$result = faqe_db_query($sql, $db))
			{
		    		die("Could not connect to the database.");
		    	}
			if($myrow=faqe_db_fetch_array($result))
			{
				echo "$l_subcategory: ";
				echo "<select class=\"jumpbox\" name=\"subcatnr\">";
				do{
					echo "<option value=\"".$myrow["catnr"]."\"";
					if($myrow["catnr"]==$subcatnr)
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
			$sql = "select subcat.* from ".$tableprefix."_subcategory subcat, ".$tableprefix."_related_subcat rsc where rsc.srccat=$subcatnr and subcat.catnr=rsc.destcat ";
			switch($jumpboxsorting)
			{
				case 1:
					$sql.="order by subcat.categoryname asc";
					break;
				case 2:
					$sql.="order by subcat.displaypos asc";
					break;
				default:
					$sql.="order by subcat.catnr asc";
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
				echo "<span style=\"font-face: $FontFace; font-size: $jumpboxfontsize; color: $FontColor; font-weight:bold\">";
				if($navframe==1)
					echo "<input type=\"hidden\" name=\"navframe\" value=\"1\">";
				if(isset($layout))
					echo "<input type=\"hidden\" name=\"layout\" value=\"$layout\">";
				if(isset($limitprog))
					echo "<input type=\"hidden\" name=\"limitprog\" value=\"$limitprog\">";
				echo "<input type=\"hidden\" name=\"$langvar\" value=\"$act_lang\">";
				echo "<input type=\"hidden\" name=\"onlynewfaq\" value=\"$onlynewfaq\">";
				echo "<input type=\"hidden\" name=\"list\" value=\"subcategory\">";
				echo "<input type=\"hidden\" name=\"catnr\" value=\"$catnr\">";
				echo "<input type=\"hidden\" name=\"prog\" value=\"$prog\">";
				echo "$l_relatedsubcats: ";
				echo "<select class=\"jumpbox\" name=\"nr\">";
				do{
					echo "<option value=\"".$myrow["catnr"]."\"";
					echo ">".display_encoded($myrow["categoryname"])."</option>";
				}while($myrow=faqe_db_fetch_array($result));
				echo "</select>&nbsp;&nbsp;";
				echo "<input class=\"jumpbox\" type=\"submit\" value=\"$l_change\">";
				echo "</span></td></form></tr>";
				echo "</table></td></tr>";
			}
		}
?>
<tr><TD BGCOLOR="<?php echo $table_bgcolor?>">
<TABLE BORDER="0" CELLPADDING="<?php echo $tablepadding?>" CELLSPACING="<?php echo $tablespacing?>" WIDTH="100%">
<?php
		if(!isset($subcatnr))
		{
			echo "<tr><td bgcolor=\"$heading_bgcolor\">";
			die("Calling error. No subcatnr found");
		}
		$sql = "select * from ".$tableprefix."_subcategory where (catnr='$subcatnr')";
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
		$sql2 = "select * from ".$tableprefix."_category where catnr=".$myrow["category"];
		if(!$result2 = faqe_db_query($sql2, $db))
		{
			echo "<tr><td bgcolor=\"$heading_bgcolor\">";
	    		die("Could not connect to the database.");
	    	}
		if ($myrow2 = faqe_db_fetch_array($result2))
		{
			$catnr=$myrow2["catnr"];
			$prognr=$myrow2["programm"];
			$catname=display_encoded($myrow2["categoryname"]);
		}
		else
		{
			$prognr=0;
			$catnr=0;
			$catname="";
		}
		$sql2 = "select * from ".$tableprefix."_programm where (prognr='$prognr')";
		if(!$result2 = faqe_db_query($sql2, $db))
		{
			echo "<tr><td bgcolor=\"$heading_bgcolor\">";
		    	die("Could not connect to the database.");
		}
		if ($myrow2 = faqe_db_fetch_array($result2))
		{
			$prognr=$myrow2["prognr"];
			$prog=$myrow2["progid"];
			$progname=display_encoded($myrow2["programmname"]);
			if($myrow2["description"])
				$proginfoavail=1;
			else
				$proginfoavail=0;
		}
		else
		{
			$prognr=0;
			$proginfoavail=0;
			$prog="";
			$progname="";
		}
		$subcatnr=$myrow["catnr"];
		echo "<TR BGCOLOR=\"$group_bgcolor\" ALIGN=\"LEFT\">";
		if($proginfoavail==1)
		{
			$proginfolink="proginfo.php?$langvar=$act_lang&prog=".$prognr;
			if(isset($layout))
				$proginfolink.="&layout=$layout";
			echo "<td width=\"5%\" align=\"center\" bgcolor=\"$group_bgcolor\" class=\"proginfolink\"";
			if($hovercells==1)
				echo " onMouseOver=\"this.style.backgroundColor='$activcellcolor';\" onMouseOut=\"this.style.backgroundColor='$group_bgcolor'\" onclick=\"openProginfo('$proginfolink')\"";
			echo ">";
			echo "<a class=\"proginfolink\" href=\"javascript:openProginfo('$proginfolink')\"><img src=\"$proginfopic\" border=\"0\" title=\"$l_proginfo\" alt=\"$l_proginfo\"></a>";
			echo "</td>";
		}
		echo "<td";
		if($proginfoavail==0)
			echo " colspan=\"2\"";
		else
			echo " width=\"95%\"";
		echo " class=\"proginfo\">";
		echo "<span style=\"font-face: $FontFace; font-size: $FontSize5; color: $GroupFontColor; font-weight: bold;\">";
		if($progname)
		{
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
					if(isset($layout))
						$proglink.="&layout=$layout";
					if(isset($onlynewfaq))
						$proglink.="&onlynewfaq=$onlynewfaq";
					if(isset($limitprog))
						$proglink.="&limitprog=$limitprog";
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
						$proglink.="listcats.php?if=$prog|$act_lang|$layout";
						$proglinkinfo=$l_listcats;
					}
				}
				echo "<a title=\"$proglinkinfo\" class=\"groupheadinglink\" href=\"$proglink\"";
				if($navframe==1)
					echo " target=\"_parent\"";
				echo ">$progname</a> : ";
			}
			else
				echo $progname.": ";
		}
		if($catname)
		{
			if(bittst($linkoptions,BIT_1))
			{
				if($altlinkmethod==0)
				{
					if($navframe==1)
						$catlink=$url_faqengine."/faqframe.php";
					else
						$catlink=$act_script_url;
					$catlink.="?list=category&catnr=$catnr&prog=$prog&$langvar=$act_lang";
					if(isset($onlynewfaq))
						$catlink.="&onlynewfaq=$onlynewfaq";
					if(isset($limitprog))
						$catlink.="&limitprog=$limitprog";
					if(isset($layout))
						$catlink.="&layout=$layout";
				}
				else
					$catlink="listcat.php?id=$catnr|$act_lang|$layout";
				echo "<a title=\"$l_listmaincat\" class=\"groupheadinglink\" href=\"$catlink\"";
				if($navframe==1)
					echo " target=\"_parent\"";
				echo ">$catname</a> : ";
			}
			else
				echo $catname." : ";
		}
		echo display_encoded($myrow["categoryname"]);
		echo "</span></td>";
		echo "</tr>";
		$actdate = date("Y-m-d");
		$sql = "select * from ".$tableprefix."_data where (subcategory=$subcatnr)";
		if(isset($onlynewfaq) && ($onlynewfaq>0) && ($faqnewdisplaymethod==0))
			$sql.=" and editdate >= DATE_SUB('$actdate', INTERVAL $onlynewfaq DAY)";
		switch($faqsortmethod)
		{
			case 0:
				$sql.=" order by editdate desc";
				break;
			case 1:
				$sql.=" order by displaypos asc";
				break;
			case 2:
				$sql.=" order by heading asc";
				break;
		}
		if(!$result = faqe_db_query($sql, $db))
		{
			echo "<tr><td bgcolor=\"$heading_bgcolor\">";
	    		die("Could not connect to the database.");
	    	}
		$actfaq=0;
		$displayedfaq=0;
		if($maxentries>0)
		{
			include_once("./includes/faq_pagenav.inc");
		    	$totalfaqs=faqe_db_num_rows($result);
		}
		else
			$totalfaqs=0;
		if (!$myrow = faqe_db_fetch_array($result))
		{
			echo "<TR BGCOLOR=\"$row_bgcolor\" ALIGN=\"LEFT\">";
			echo "<td class=\"listrow\" align=\"center\" colspan=\"2\">";
			echo "<span style=\"font-face: $FontFace; font-size: $FontSize1;\">";
			if(isset($onlynewfaq) && ($onlynewfaq>0) && ($faqnewdisplaymethod==0))
				echo $l_nonewentries;
			else
				echo $l_noentries;
			echo "</span></td></tr>";
		}
		else
		{
			if(($faqsortmethod==2) && ($enableshortcutbar==1))
			{
				echo "<tr bgcolor=\"$group_bgcolor\"><td class=\"shortbar\" align=\"left\" colspan=\"2\">";
				echo "<span style=\"font-face: $FontFace; font-size: $shortbarfontsize; color: $GroupFontColor;\">";
				for($i=65;$i<91;$i++)
				echo "<a class=\"shortbar\" href=\"#".chr($i)."\">".chr($i)."</a> ";
				echo "</span></td></tr>";
				$startchar="";
			}
			do {
				$actfaq++;
				if(($maxentries>0) && (($actfaq-1 >= ($start+$maxentries)) ))
					continue;
				$displayedfaq++;
				if($actfaq-1<$start)
					continue;
				$act_id=$myrow["faqnr"];
				$faqbgcolor=$row_bgcolor;
				$faqfontcolor=$FontColor;
				if(isset($onlynewfaq) && ($onlynewfaq>0) && ($faqnewdisplaymethod==1))
				{
					$actdate=getdate(time());
					$datedays=dateToJuliandays($actdate["mday"],$actdate["mon"],$actdate["year"]);
					$onlynewdays = $datedays-$onlynewfaq;
					list($year, $month, $day) = explode("-", $myrow["editdate"]);
					$tempdays=dateToJuliandays($day, $month, $year);
					if($tempdays>$onlynewdays)
					{
						$faqbgcolor=$faqnewdisplaybgcolor;
						$faqfontcolor=$faqnewdisplayfontcolor;
					}
				}
				echo "<TR BGCOLOR=\"$row_bgcolor\" ALIGN=\"LEFT\">";
				echo "<td class=\"newmarker\" width=\"5%\" align=\"center\">";
				if(($faqsortmethod==2) && ($enableshortcutbar==1))
				{
					$asc_heading=undo_htmlentities($myrow["heading"]);
					$firstchar=substr($asc_heading,0,1);
					$firstchar=strtoupper($firstchar);
					if(($firstchar!=$startchar) && (ord($firstchar)>64) && (ord($firstchar)<91))
					{
						$startchar=$firstchar;
						echo "<a name=\"#$startchar\"></a>";
					}
				}
				if($newtime>0)
				{
					list($year, $month, $day) = explode("-", $myrow["editdate"]);
					$tempdays=dateToJuliandays($day, $month, $year);
					if($tempdays>$newdatedays)
						echo "<img class=\"newmarker\" src=\"$newpic\" border=\"0\" title=\"$l_newfaq2\" alt=\"$l_newfaq2\">";
					else
						echo "&nbsp;";
				}
				echo "</td>";
				if($altlinkmethod==0)
				{
					if($navframe==1)
						$link=$url_faqengine."/faqframe.php";
					else
						$link=$act_script_url;
					if(($myrow["linkedfaq"]==0) || ($lhide==1))
						$link.="?display=faq&amp;faqnr=$act_id&amp;catnr=$catnr&amp;prog=$prog&amp;$langvar=$act_lang";
					else
					{
						$linksql="select dat.*, prog.progid from ".$tableprefix."_data dat, ".$tableprefix."_category cat, ".$tableprefix."_programm prog where dat.faqnr=".$myrow["linkedfaq"]." and cat.catnr=dat.category and prog.prognr=cat.programm";
						if(!$linkresult = faqe_db_query($linksql, $db))
							die("Could not connect to the database.");
						if($linkrow=faqe_db_fetch_array($linkresult))
							$link.="?display=faq&amp;faqnr=".$linkrow["faqnr"]."&amp;catnr=".$linkrow["category"]."&amp;prog=".$linkrow["progid"]."&amp;$langvar=$act_lang";
						else
							die("Could not connect to the database.");
					}
					if(isset($onlynewfaq))
						$link.="&amp;onlynewfaq=$onlynewfaq";
					if(isset($limitprog))
						$link.="&amp;limitprog=$limitprog";
					if(isset($layout))
						$link.="&layout=$layout";
				}
				else
				{
					$link="dispfaq.php?id=$act_id|$act_lang|$layout";
				}
				echo "<td bgcolor=\"$faqbgcolor\" class=\"listrow\"";
				if($hovercells==1)
				{
					echo " onMouseOver=\"this.style.backgroundColor='$activcellcolor';\" onMouseOut=\"this.style.backgroundColor='$faqbgcolor'\"";
					if($navframe==0)
						echo " onclick=\"window.location.href='$link'\"";
				}
				echo ">";
				echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $faqfontcolor;\">";
				echo "<a class=\"listlink\" href=\"$link\"";
				if($navframe==1)
					echo " target=\"_parent\"";
				echo ">";
				echo stripslashes($myrow["heading"]);
				echo "</a></span></td></tr>";
			} while($myrow = faqe_db_fetch_array($result));
		}
		if(($maxentries>0) && ($totalfaqs>$maxentries))
		{
			$url_start="$act_script_url?$langvar=$act_lang&amp;list=subcategory&amp;subcatnr=$subcatnr&amp;catnr=$catnr&amp;prog=$prog&amp;onlynewfaq=$onlynewfaq";
			if(isset($layout))
				$url_start.="&layout=$layout";
			if($navframe==1)
			{
				$url_start.="&amp;navframe=1";
				if(isset($limitprog))
					$url_start.="&amp;limitprog=$limitprog";
			}
			echo "<tr bgcolor=\"$actionbgcolor\"><td class=\"pagenav\" align=\"center\" valign=\"middle\"";
			if($proginfoavail==1)
				echo " colspan=\"2\"";
			echo">";
			faq_pagenav($start, $maxentries, $totalfaqs, $displayedfaq, $url_start);
			echo "</td></tr>";
		}
?>
</table></td></tr>
<tr><TD BGCOLOR="<?php echo $table_bgcolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
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
		$linkurl.="?list=category&amp;catnr=$catnr&amp;prog=$prog&amp;$langvar=$act_lang";
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
	echo ">$l_listmaincat</a>";
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
		echo "\">$l_listprogs</a>";
	}
?>
</span>
</td>
</td>
<td class="gototop" align="right" width="1%"><a href="#top">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $actionlinefontsize?>;">
<img class="gototop" src="<?php echo $pagetoppic?>" border="0" align="middle" title="<?php echo $l_top?>" alt="<?php echo $l_top?>"></a>
</span></td>
</tr>
<?php
	if($newtime>0)
	{
?>
<tr BGCOLOR="<?php echo $newinfobgcolor?>" valign="middle"><td class="newfaqline" colspan="2">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $newinfofontsize?>; color: <?php echo $newinfofontcolor?>;">
<img class="newfaqline" src="<?php echo $newpic?>" border="0" align="middle" title="<?php echo $l_newfaq2?>" alt="<?php echo $l_newfaq2?>"> = <?php echo str_replace("{newtime}",$newtime,$l_newfaq)?>
</span></td></tr>
<?php
	}
?>
</table></td></tr></table>
<?php
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
			if(navframe==1)
				echo "<input type=\"hidden\" name=\"navframe\" value=\"1\">";
			if(isset($limitprog))
				echo "<input type=\"hidden\" name=\"limitprog\" value=\"$limitprog\">";
			if(isset($layout))
				echo "<input type=\"hidden\" name=\"layout\" value=\"$layout\">";
			echo "<input type=\"hidden\" name=\"list\" value=\"category\">";
			echo "<input type=\"hidden\" name=\"subcatnr\" value=\"$subcatnr\">";
			echo "<input type=\"hidden\" name=\"prog\" value=\"$prog\">";
			echo "<input type=\"hidden\" name=\"$langvar\" value=\"$act_lang\">";
			echo "<span style=\"font-face: $FontFace; font-size: $faqnewfontsize; color: $FontColor;\">";
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
			echo "&nbsp;&nbsp;&nbsp;<input class=\"faqnewdisplay\" type=\"submit\" value=\"$l_ok\">";
			echo "</span></td></tr></form></table>";
		}
?>