<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
if($displayvotesinline==1)
	$colspan=3;
else
	$colspan=2;
?>
<tr><TD BGCOLOR="<?php echo $table_bgcolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<tr BGCOLOR="<?php echo $subheadingbgcolor?>" ALIGN="CENTER">
<TD class="subheading" ALIGN="CENTER" VALIGN="MIDDLE">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize2?>; color: <?php echo $SubheadingFontColor?>; font-weight: bold">
<?php echo $l_singlecat?></span>
</td></tr></table></td></tr>
<?php
		if(isset($chcatnr))
		{
			$sql="select * from ".$tableprefix."_category_ref where srccatnr='$chcatnr' and language='$lang'";
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
			$catnr=$myrow["destcatnr"];
		}
		$actfaq=0;
		$displayedfaq=0;
		if($maxentries>0)
		{
			include_once("./includes/faq_pagenav.inc");
			$sql = "select * from ".$tableprefix."_data where (category=$catnr) ";
			if(isset($onlynewfaq) && ($onlynewfaq>0) && ($faqnewdisplaymethod==0))
				$sql.=" and editdate >= DATE_SUB('$actdate', INTERVAL $onlynewfaq DAY)";
			if(!$result = faqe_db_query($sql, $db))
			{
				echo "<tr><td bgcolor=\"$heading_bgcolor\">";
		    		die("Could not connect to the database.");
		    	}
		    	$totalfaqs=faqe_db_num_rows($result);
		}
		else
			$totalfaqs=0;
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
			echo "<span style=\"font-face: $FontFace; font-size: $jumpboxfontsize; color: $FontColor;\">";
			echo "<input type=\"hidden\" name=\"$langvar\" value=\"$act_lang\">";
			echo "<input type=\"hidden\" name=\"onlynewfaq\" value=\"$onlynewfaq\">";
			echo "<input type=\"hidden\" name=\"list\" value=\"category\">";
			echo "<input type=\"hidden\" name=\"prog\" value=\"$prog\">";
			if(isset($layout))
				echo "<input type=\"hidden\" name=\"layout\" value=\"$layout\">";
			if($navframe==1)
				echo "<input type=\"hidden\" name=\"navframe\" value=\"1\">";
			if(isset($limitprog))
				echo "<input type=\"hidden\" name=\"limitprog\" value=\"1\">";
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
		    		die("Could not connect to the database.");
			if($myrow=faqe_db_fetch_array($result))
			{
				echo "<b>$l_category:</b> ";
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
			$sql = "select cat.* from ".$tableprefix."_category cat, ".$tableprefix."_related_categories rc where rc.srccat=$catnr and cat.catnr=rc.destcat ";
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
				echo "<span style=\"font-face: $FontFace; font-size: $jumpboxfontsize; color: $FontColor;\">";
				if($navframe==1)
					echo "<input type=\"hidden\" name=\"navframe\" value=\"1\">";
				if(isset($layout))
					echo "<input type=\"hidden\" name=\"layout\" value=\"$layout\">";
				if(isset($limitprog))
					echo "<input type=\"hidden\" name=\"limitprog\" value=\"$limitprog\">";
				echo "<input type=\"hidden\" name=\"$langvar\" value=\"$act_lang\">";
				echo "<input type=\"hidden\" name=\"onlynewfaq\" value=\"$onlynewfaq\">";
				echo "<input type=\"hidden\" name=\"list\" value=\"category\">";
				echo "<input type=\"hidden\" name=\"prog\" value=\"$prog\">";
				echo "<b>$l_relatedcats:</b> ";
				echo "<select class=\"related\" name=\"catnr\">";
				do{
					echo "<option value=\"".$myrow["catnr"]."\"";
					echo ">".display_encoded($myrow["categoryname"])."</option>";
				}while($myrow=faqe_db_fetch_array($result));
				echo "</select>&nbsp;&nbsp;";
				echo "<input class=\"related\" type=\"submit\" value=\"$l_change\">";
				echo "</span></td></form></tr>";
				echo "</table></td></tr>";
			}
		}
?>
<tr><TD BGCOLOR="<?php echo $table_bgcolor?>">
<TABLE BORDER="0" CELLPADDING="<?php echo $tablepadding?>" CELLSPACING="<?php echo $tablespacing?>" WIDTH="100%">
<?php
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
		$sql2 = "select * from ".$tableprefix."_programm where (prognr=".$myrow["programm"].")";
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
		$catnr=$myrow["catnr"];
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
			echo "<span style=\"font-face: $FontFace; font-size: $FontSize1;\">";
			echo "<a class=\"proginfolink\" href=\"javascript:openProginfo('$proginfolink')\"><img src=\"$proginfopic\" border=\"0\" title=\"$l_proginfo\" alt=\"$l_proginfo\"></a>";
			echo "</span></td>";
		}
		echo "<td";
		if($proginfoavail==0)
			echo " colspan=\"$colspan\"";
		else
		{
			if($displayvotesinline==1)
				echo " colspan=\"2\"";
			echo " width=\"95%\"";
		}
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
					if(isset($onlynewfaq))
						$proglink.="&onlynewfaq=$onlynewfaq";
					if(isset($limitprog))
						$proglink.="&limitprog=$limitprog";
					if(isset($layout))
						$proglink.="&layout=$layout";
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
				}
				echo "<a title=\"$proglinkinfo\" class=\"groupheadinglink\" href=\"$proglink\"";
				if($navframe==1)
					echo " target=\"_parent\"";
				echo ">$progname</a> : ";
			}
			else
				echo $progname.": ";
		}
		echo display_encoded($myrow["categoryname"]);
		echo "</span></td>";
		echo "</tr>";
		$actdate = date("Y-m-d");
		$sql = "select * from ".$tableprefix."_data where (category=$catnr) and subcategory=0";
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
	    		die("Could not connect to the database.".faqe_db_error());
	    	}
		if (!$myrow = faqe_db_fetch_array($result))
		{
			$tmpsql = "select * from ".$tableprefix."_data where (category=$catnr)";
			if(isset($onlynewfaq) && ($onlynewfaq>0) && ($faqnewdisplaymethod==0))
				$tmpsql.=" and editdate >= DATE_SUB('$actdate', INTERVAL $onlynewfaq DAY)";
			if(!$tmpresult = faqe_db_query($tmpsql, $db))
			{
				echo "<tr><td bgcolor=\"$heading_bgcolor\">";
				die("Could not connect to the database.".faqe_db_error());
			}
			echo "<TR BGCOLOR=\"$row_bgcolor\" ALIGN=\"LEFT\">";
			echo "<td class=\"listrow\" align=\"center\" colspan=\"$colspan\">";
			echo "<span style=\"font-face: $FontFace; font-size: $FontSize1;\">";
			if(isset($onlynewfaq) && ($onlynewfaq>0) && ($faqnewdisplaymethod==0))
				echo $l_nonewentries;
			else if(faqe_db_num_rows($tmpresult)<1)
				echo $l_noentries;
			echo "</span></td></tr>";
		}
		else
		{
			$shortbardisplayed=false;
			do {
				$actfaq++;
				if(($maxentries>0) && (($actfaq-1 >= ($start+$maxentries)) ))
					continue;
				$displayedfaq++;
				if($actfaq-1<$start)
					continue;
				if(($faqsortmethod==2) && ($enableshortcutbar==1) && !$shortbardisplayed)
				{
					echo "<tr bgcolor=\"$group_bgcolor\"><td class=\"shortbar\" align=\"left\" colspan=\"$colspan\">";
					echo "<span style=\"font-face: $FontFace; font-size: $shortbarfontsize; color: $GroupFontColor;\">";
					for($i=65;$i<91;$i++)
					echo "<a class=\"shortbar\" href=\"#".chr($i)."\">".chr($i)."</a> ";
					echo "</span></td></tr>";
					$startchar="";
					$shortbardisplayed=true;
				}
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
				if($displayvotesinline==1)
					$tblwidth="90%";
				else
					$tblwidth="95%";
				echo "<td width=\"$tblwidth\" bgcolor=\"$faqbgcolor\" class=\"listrow\"";
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
				echo undo_html_ampersand(stripslashes($myrow["heading"]));
				echo "</a></span></td>";
				if($displayvotesinline==1)
				{
					echo "<td width=\"5%\" nowrap align=\"right\" class=\"ratingrow\" bgcolor=\"$faqbgcolor\" title=\"$l_userrating\">";
					echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $faqfontcolor;\">";
					if($votesinlinedisplaymode==1)
						echo display_ratings_inline($myrow["rating"],$myrow["ratingcount"]);
					else
					{					
						if($myrow["ratingcount"]>0)
							echo round($myrow["rating"]/$myrow["ratingcount"],2). " (".$myrow["ratingcount"].")";
						else
							echo "&nbsp;";
					}
					echo "</span></td>";
				}
				echo "</tr>";
			} while($myrow = faqe_db_fetch_array($result));
		}
		$sql = "select * from ".$tableprefix."_subcategory where category=$catnr order by displaypos asc";
		if(!$result = faqe_db_query($sql, $db))
		{
			echo "<tr><td bgcolor=\"$heading_bgcolor\">";
		    	die("Could not connect to the database.");
		}
		if($myrow=faqe_db_fetch_array($result))
		{
			do{
				if(($maxentries>0) && ($actfaq > ($start+$maxentries-1)))
					continue;
				$actdate = date("Y-m-d");
				$sql2 = "select * from ".$tableprefix."_data where subcategory=".$myrow["catnr"];
				if(isset($onlynewfaq) && ($onlynewfaq>0) && ($faqnewdisplaymethod==0))
					$sql2.=" and editdate >= DATE_SUB('$actdate', INTERVAL $onlynewfaq DAY)";
				switch($faqsortmethod)
				{
					case 0:
						$sql2.=" order by editdate desc";
						break;
					case 1:
						$sql2.=" order by displaypos asc";
						break;
					case 2:
						$sql2.=" order by heading asc";
						break;
				}
				if(!$result2 = faqe_db_query($sql2, $db))
				{
					echo "<tr><td bgcolor=\"$heading_bgcolor\">";
	    				die("Could not connect to the database.");
	    			}
				if( ($maxentries<=0) || (($actfaq+faqe_db_num_rows($result2)>$start) && ($actfaq+1<=($start+$maxentries))))
				{
					if($altlinkmethod==0)
					{
						if($navframe==1)
							$link=$url_faqengine."/faqframe.php";
						else
							$link=$act_script_url;
						$link.="?list=subcategory&amp;$langvar=$act_lang&amp;prog=$prog&amp;catnr=$catnr&amp;subcatnr=".$myrow["catnr"];
						if(isset($onlynewfaq))
							$link.="&amp;onlynewfaq=$onlynewfaq";
						if(isset($layout))
							$link.="&layout=$layout";
						if(isset($limitprog))
							$link.="&limitprog=$limitprog";
					}
					else
					{
						$link="listsubcat.php?id=".$myrow["catnr"]."|$act_lang|$layout";
					}
					echo "<tr bgcolor=\"$subcatbgcolor\">";
					echo "<td align=\"left\" class=\"subcat\" colspan=\"$colspan\"";
					if($hovercells==1)
					{
						echo " onMouseOver=\"this.style.backgroundColor='$activcellcolor';\" onMouseOut=\"this.style.backgroundColor='$subcatbgcolor'\"";
						if($navframe==0)
							echo " onclick=\"window.location.href='$link'\"";
					}
					echo ">";
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
					echo "<span style=\" font-face: $FontFace; font-size: $subcatfontsize; color: $subcatfontcolor; $addstyle\">";
					echo "<a class=\"subcat\" href=\"$link\"";
					if($navframe==1)
						echo " target=\"_parent\"";
					echo ">";
					echo display_encoded($myrow["categoryname"]);
					echo "</a></span></td></tr>";
					if (!$myrow2 = faqe_db_fetch_array($result2))
					{
						echo "<TR BGCOLOR=\"$row_bgcolor\" ALIGN=\"LEFT\">";
						echo "<td class=\"listrow\" align=\"center\" colspan=\"$colspan\">";
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
							echo "<tr bgcolor=\"$subcatbgcolor\"><td class=\"shortbar\" align=\"left\" colspan=\"$colspan\">";
							echo "<span style=\"font-face: $FontFace; font-size: $shortbarfontsize; color: $subcatfontcolor;\">";
							for($i=65;$i<91;$i++)
							echo "<a class=\"shortbar\" href=\"#".chr($i)."\">".chr($i)."</a> ";
							echo "</span></td></tr>";
							$startchar="";
						}
						do {
							$actfaq++;
							if( ($maxentries>0) && ($actfaq > ($start+$maxentries)) )
								continue;
							$displayedfaq++;
							if($actfaq-1<$start)
								continue;
							$act_id=$myrow2["faqnr"];
							$faqbgcolor=$row_bgcolor;
							$faqfontcolor=$FontColor;
							if(isset($onlynewfaq) && ($onlynewfaq>0) && ($faqnewdisplaymethod==1))
							{
								$actdate=getdate(time());
								$datedays=dateToJuliandays($actdate["mday"],$actdate["mon"],$actdate["year"]);
								$onlynewdays = $datedays-$onlynewfaq;
								list($year, $month, $day) = explode("-", $myrow2["editdate"]);
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
								$asc_heading=undo_htmlentities($myrow2["heading"]);
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
								list($year, $month, $day) = explode("-", $myrow2["editdate"]);
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
								$link.="?display=faq&amp;faqnr=$act_id&amp;catnr=$catnr&amp;prog=$prog&amp;$langvar=$act_lang";
								if(isset($layout))
									$link.="&layout=$layout";
								if(isset($limitprog))
									$link.="&limitprog=$limitprog";
								if(isset($onlynewfaq))
									$link.="&amp;onlynewfaq=$onlynewfaq";
							}
							else
								$link="dispfaq.php?id=$act_id|$act_lang|$layout";
							if($displayvotesinline==1)
								$tblwidth="90%";
							else
								$tblwidth="95%";
							echo "<td width=\"$tblwidth\" bgcolor=\"$faqbgcolor\" class=\"listrow\"";
							if($hovercells==1)
							{
								echo " onMouseOver=\"this.style.backgroundColor='$activcellcolor';\" onMouseOut=\"this.style.backgroundColor='$faqbgcolor'\"";
								if($navframe==0)
									echo "onclick=\"window.location.href='$link'\"";
							}
							echo ">";
							echo "<span style=\"font-face: $FontFace; font-size:$FontSize1; color: $faqfontcolor;\">";
							echo "<a class=\"listlink\" href=\"$link\"";
							if($navframe==1)
								echo " target=\"_parent\"";
							echo ">";
							echo undo_html_ampersand(stripslashes($myrow2["heading"]));
							echo "</a></span></td>";
							if($displayvotesinline==1)
							{
								echo "<td width=\"5%\" nowrap align=\"right\" class=\"ratingrow\" bgcolor=\"$faqbgcolor\" title=\"$l_userrating\">";
								echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $faqfontcolor;\">";
								if($votesinlinedisplaymode==1)
									echo display_ratings_inline($myrow2["rating"],$myrow2["ratingcount"]);
								else
								{
									if($myrow2["ratingcount"]>0)
										echo round($myrow2["rating"]/$myrow2["ratingcount"],2). " (".$myrow2["ratingcount"].")";
									else
										echo "&nbsp;";
								}
								echo "</span></td>";
							}
							echo "</tr>";
						} while($myrow2 = faqe_db_fetch_array($result2));
					}
				}
				else
					$actfaq+=faqe_db_num_rows($result2);
	    		}while($myrow=faqe_db_fetch_array($result));
		}
		if(($maxentries>0) && ($totalfaqs>$maxentries))
		{
			$url_start="$act_script_url?$langvar=$act_lang&amp;list=category&amp;catnr=$catnr&amp;prog=$prog&amp;onlynewfaq=$onlynewfaq";
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
				echo " colspan=\"$colspan\"";
			else if($displayvotesinline==1)
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
		$linkurl.="?list=categories&amp;prog=$prog&amp;$langvar=$act_lang";
		if(isset($layout))
			$linkurl.="&layout=$layout";
		if(isset($onlynewfaq))
			$linkurl.="&amp;onlynewfaq=$onlynewfaq";
		if(isset($limitprog))
			$linkurl.="&amp;limitprog=$limitprog";
	}
	else
	{
		$linkurl="listcats.php?id=$prog|$act_lang|$layout";
	}
	echo "<a class=\"actionline\" href=\"$linkurl\"";
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
		if(isset($layout))
			$linkurl.="&layout=$layout";
		if(isset($onlynewfaq))
			$linkurl.="&amp;onlynewfaq=$onlynewfaq";
		if(isset($limitprog))
			$linkurl.="&amp;limitprog=$limitprog";
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
			if(isset($layout))
				$linkurl.="&layout=$layout";
			if(isset($onlynewfaq))
				$linkurl.="&amp;onlynewfaq=$onlynewfaq";
			if(isset($limitprog))
				$link.="&amp;limitprog=$limitprog";	
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
</span>
</td>
</td>
<td class="gototop" align="right" width="1%"><a href="#top">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize4?>;">
<img class="gototop" src="<?php echo $pagetoppic?>" border="0" align="middle" title="<?php echo $l_top?>" alt="<?php echo $l_top?>"></a></span></td>
</tr>
<?php
	if($newtime>0)
	{
?>
<tr BGCOLOR="<?php echo $newinfobgcolor?>" valign="middle"><td class="newfaqline" colspan="2">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $newinfofontsize?>; color: <?php echo $newinfofontcolor?>">
<img class="newfaqline" src="<?php echo $newpic?>" border="0" align="middle" title="<?php echo $l_newfaq2?>" alt="<?php echo $l_newfaq2?>"> = <?php echo str_replace("{newtime}",$newtime,$l_newfaq)?>
</span></td></tr>
<?php
	}
?>
</table></td></tr></table>
<?php
		if($enablelanguageselector==1)
		{
			$availlangs=language_list();
			if(count($availlangs)>0)
			{
				$sql = "select * from ".$tableprefix."_category_ref where srccatnr='$catnr' and language in (";
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
					echo "<table style=\"clear:both\" width=\"$TableWidth\" align=\"$tblalign\" border=\"0\">\n";
					if($navframe==1)
						$action_url=$url_faqengine."/faqframe.php";
					else
						$action_url=$act_script_url;
					echo "<form method=\"post\" action=\"$action_url\"";
					if($navframe==1)
						echo " target=\"_parent\"";
					echo ">";
					echo "<tr bgcolor=\"$group_bgcolor\"><td class=\"langselect\" align=\"center\">\n";
					if($navframe==1)
						echo "<input type=\"hidden\" name=\"navframe\" value=\"1\">";
					if(isset($onlynewfaq))
						echo "<input type=\"hidden\" name=\"onlynewfaq\" value=\"$onlynewfaq\">";
					if(isset($layout))
						echo "<input type=\"hidden\" name=\"layout\" value=\"$layout\">";
					if(isset($limitprog))
						echo "<input type=\"hidden\" name=\"limitprog\" value=\"$limitprog\">";
					echo "<input type=\"hidden\" name=\"list\" value=\"category\">";
					echo "<input type=\"hidden\" name=\"prog\" value=\"$prog\">";
					echo "<input type=\"hidden\" name=\"chcatnr\" value=\"$catnr\">";
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
			echo "<input type=\"hidden\" name=\"list\" value=\"category\">";
			echo "<input type=\"hidden\" name=\"catnr\" value=\"$catnr\">";
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