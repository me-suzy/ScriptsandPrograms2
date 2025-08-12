<?php
/***************************************************************************
* (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
***************************************************************************/
if(($show_proglist==1) && ($enablejumpboxes==1))
{
?>
<tr><TD BGCOLOR="<?php echo $table_bgcolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<?php
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
		if(isset($limitprog))
			echo "<input type=\"hidden\" name=\"limitprog\" value=\"$limitprog\">";
		if(isset($layout))
			echo "<input type=\"hidden\" name=\"layout\" value=\"$layout\">";
		if(is_konqueror())
			echo "<tr><td></td></tr>";
		echo "<tr BGCOLOR=\"$actionbgcolor\">";
		echo "<td class=\"jumpbox\" align=\"right\">";
		echo "<span style=\"font-face: $FontFace; font-size: $jumpboxfontsize; color: $FontColor; font-weight: bold\">";
		echo "$l_program: ";
		echo "<input type=\"hidden\" name=\"$langvar\" value=\"$act_lang\">";
		echo "<input type=\"hidden\" name=\"onlynewfaq\" value=\"$onlynewfaq\">";
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
		echo "</span></td></tr></form></table></td></tr>";
	}
}
?>
<tr><TD BGCOLOR="<?php echo $table_bgcolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<?php
if(!isset($prog) || (!$prog))
{
	echo "<tr><td bgcolor=\"$group_bgcolor\">";
	die("Calling error");
}
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
$actfaq=0;
$displayedfaq=0;
if($maxentries>0)
{
	include_once("./includes/faq_pagenav.inc");
	$actdate = date("Y-m-d");
	$sql = "select dat.* from ".$tableprefix."_data dat, ".$tableprefix."_category cat where cat.programm=$prognr and dat.category=cat.catnr ";
	if(isset($onlynewfaq) && ($onlynewfaq>0) && ($faqnewdisplaymethod==0))
		$sql.=" and dat.editdate >= DATE_SUB('$actdate', INTERVAL $onlynewfaq DAY)";
	if(!$result = faqe_db_query($sql, $db))
	{
		echo "<tr><td bgcolor=\"$heading_bgcolor\">";
		die("Could not connect to the database.");
	}
	$totalfaqs=faqe_db_num_rows($result);
	if($allowquestions==1)
	{
		$sql = "select * from ".$tableprefix."_questions where prognr='$prognr' and language='$act_lang' and publish=1 and faqref=0 ";
		if(isset($onlynewfaq) && ($onlynewfaq>0) && ($faqnewdisplaymethod==0))
			$sql.=" and enterdate >= DATE_SUB('$actdate', INTERVAL $onlynewfaq DAY)";
		$sql.=" order by enterdate desc";
		if(!$result = faqe_db_query($sql, $db))
		{
			echo "<tr><td bgcolor=\"$heading_bgcolor\">";
			die("Could not connect to the database.".faqe_db_error());
		}
		$totalfaqs+=faqe_db_num_rows($result);
	}
}
else
	$totalfaqs=0;
?>
<tr BGCOLOR="<?php echo $subheadingbgcolor?>" ALIGN="CENTER">
<?php
echo "<TD class=\"subheading\" ALIGN=\"CENTER\" VALIGN=\"MIDDLE\"";
if($proginfoavail==1)
	echo " width=\"95%\"";
echo ">";
?>
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize2?>; color: <?php echo $SubheadingFontColor?>; font-weight: bold;">
<?php echo $l_listall?>: <?php echo $progname?></span></td>
<?php
if($proginfoavail==1)
{
	$proginfolink="proginfo.php?$langvar=$act_lang&prog=".$prognr;
	if(isset($layout))
		$proginfolink.="&layout=$layout";
	echo "<td width=\"5%\" align=\"center\" bgcolor=\"$subheadingbgcolor\" class=\"proginfolink\"";
	if($hovercells==1)
		echo " onMouseOver=\"this.style.backgroundColor='$activcellcolor';\" onMouseOut=\"this.style.backgroundColor='$subheadingbgcolor'\" onclick=\"openProginfo('$proginfolink')\"";
	echo ">";
	echo "<a href=\"javascript:openProginfo('$proginfolink')\"><img src=\"$proginfopic\" border=\"0\" title=\"$l_proginfo\" alt=\"$l_proginfo\"></a></td>";
}
?>
</tr></table></td></tr>
<tr><TD BGCOLOR="<?php echo $table_bgcolor?>">
<?php
if($listallfaqmethod==0)
{
	$sql = "select * from ".$tableprefix."_category where (programm=$prognr) order by displaypos asc";
	if(!$result = faqe_db_query($sql, $db))
	{
		echo "<tr><td bgcolor=\"$heading_bgcolor\">";
		die("Could not connect to the database.");
	}
	if (!$myrow = faqe_db_fetch_array($result))
	{
		echo "<TABLE BORDER=\"0\" CELLPADDING=\"$tablepadding\" CELLSPACING=\"$tablespacing\" WIDTH=\"100%\">";
		echo "<tr><td class=\"catrow\" bgcolor=\"$row_bgcolor\" align=\"center\">";
		echo "<span style=\"font-face: $FontFace; font-size: $FontSize5; color: $FontColor;\">";
		echo "$l_nocategories</span>";
		echo "</td></tr></table>";
	}
	else
	{
		do {
// loop through categories start
			if($maxentries>0)
			{
				$actdate = date("Y-m-d");
				$sql2 = "select * from ".$tableprefix."_data where (category=".$myrow["catnr"].")";
				if(isset($onlynewfaq) && ($onlynewfaq>0) && ($faqnewdisplaymethod==0))
					$sql2 .= " and editdate >= DATE_SUB('$actdate', INTERVAL $onlynewfaq DAY)";
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
				if(!$tempresult = faqe_db_query($sql2, $db))
				{
					echo "<tr><td bgcolor=\"$heading_bgcolor\">";
					die("Could not connect to the database.");
				}
				$catfaqs=faqe_db_num_rows($tempresult);
			}
			if(($maxentries>0) && ( ($actfaq+$catfaqs<$start) || ($actfaq+1>$start+$maxentries) || ($catfaqs<=0)))
			{
				if($actfaq<$start+$maxentries)
					$displayedfaq+=$catfaqs;
				$actfaq+=$catfaqs;
				continue;
			}
			$catnr=$myrow["catnr"];
			if($altlinkmethod==0)
			{
				if($navframe==1)
					$link=$url_faqengine."/faqframe.php";
				else
					$link=$act_script_url;
				$link.="?$langvar=$act_lang&amp;list=category&amp;catnr=$catnr&amp;prog=$prog";
				if(isset($layout))
					$link.="&amp;layout=$layout";
				if(isset($onlynewfaq))
					$link.="&amp;onlynewfaq=$onlynewfaq";
				if(isset($limitprog))
					$link.="&amp;limitprog=$limitprog";
			}
			else
			{
				$link="listcat.php?id=$catnr|$act_lang|$layout";
			}
			echo "<TABLE BORDER=\"0\" CELLPADDING=\"1\" CELLSPACING=\"1\" WIDTH=\"100%\">";
			echo "<tr><td colspan=\"2\">";
			echo "<table border=\"0\" cellpadding=\"$tablepadding\" cellspacing=\"$tablespacing\" width=\"100%\" bgcolor=\"$group_bgcolor\">";
			echo "<TR BGCOLOR=\"$group_bgcolor\" ALIGN=\"LEFT\">";
			echo "<td bgcolor=\"$group_bgcolor\" class=\"catrow\"";
			if($hovercells==1)
			{
				echo " onMouseOver=\"this.style.backgroundColor='$activcellcolor';\" onMouseOut=\"this.style.backgroundColor='$group_bgcolor'\"";
				if($navframe==0)
					echo " onclick=\"window.location.href='$link'\"";
			}
			echo " width=\"95%\">";
			echo "<span style=\"font-face: $FontFace; font-size: $FontSize5; color: $GroupFontColor; font-weight: bold\">";
			echo "<a class=\"catlink\" href=\"$link\"";
			if($navframe==1)
				echo " target=\"_parent\"";
			echo ">";
			echo display_encoded($myrow["categoryname"]);
			echo "</a></span></td>";
			echo "<td class=\"catrow\" width=\"5%\" align=\"right\">";
			echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
			echo $myrow["numfaqs"]."</span></td></tr></table></td></tr>";

// display FAQs with no subcategory start
			$actdate = date("Y-m-d");
			$sql2 = "select * from ".$tableprefix."_data where (category=".$myrow["catnr"].") and subcategory=0";
			if(isset($onlynewfaq) && ($onlynewfaq>0) && ($faqnewdisplaymethod==0))
				$sql2 .= " and editdate >= DATE_SUB('$actdate', INTERVAL $onlynewfaq DAY)";
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
			if(!$tempresult = faqe_db_query($sql2, $db))
			{
				echo "<tr><td bgcolor=\"$heading_bgcolor\">";
				die("Could not connect to the database.");
			}
			if (!$temprow = faqe_db_fetch_array($tempresult))
			{
				echo "</table>";
				echo "<TABLE BORDER=\"0\" CELLPADDING=\"$tablepadding\" CELLSPACING=\"$tablespacing\" WIDTH=\"100%\">";
				$subsql="select * from ".$tableprefix."_subcategory where category=".$myrow["catnr"]." order by displaypos asc";
				if(!$subresult = faqe_db_query($subsql, $db))
				{
					echo "<tr><td bgcolor=\"$heading_bgcolor\">";
					die("Could not connect to the database.");
				}
				echo "<TR BGCOLOR=\"$row_bgcolor\" ALIGN=\"LEFT\">";
				echo "<td class=\"newmarker\" width=\"5%\">&nbsp;";
				echo "</td><td class=\"listrow\" width=\"95%\">";
				echo "<span style=\"font-face: $FontFace; font-size: $FontSize1;\">";
				if(isset($onlynewfaq) && ($onlynewfaq>0) && ($faqnewdisplaymethod==0))
					echo $l_nonewentries;
				else if(faqe_db_num_rows($subresult)<1)
					echo $l_noentries;
				echo "</span></td></tr>";
			}
			else
			{
				$shortbardisplayed=false;
				echo "</table>";
				echo "<TABLE BORDER=\"0\" CELLPADDING=\"$tablepadding\" CELLSPACING=\"$tablespacing\" WIDTH=\"100%\">";
				do {
					$actfaq++;
					if(($maxentries>0) && (($actfaq-1 >= ($start+$maxentries)) ))
						continue;
					$displayedfaq++;
					if($actfaq-1<$start)
						continue;
					if(($faqsortmethod==2) && ($enableshortcutbar==1) && !$shortbardisplayed)
					{
						echo "<tr bgcolor=\"$group_bgcolor\"><td class=\"shortbar\" align=\"left\" colspan=\"2\">";
						echo "<span style=\"font-face: $FontFace; font-size: $shortbarfontsize; color: $GroupFontColor;\">";
						for($i=65;$i<91;$i++)
							echo "<a class=\"shortbar\" href=\"#".chr($i)."\">".chr($i)."</a> ";
						echo "</span></td></tr>";
						$startchar="";
						$shortbardisplayed=true;
					}
					$act_id=$temprow["faqnr"];
					$faqbgcolor=$row_bgcolor;
					$faqfontcolor=$FontColor;
					if(isset($onlynewfaq) && ($onlynewfaq>0) && ($faqnewdisplaymethod==1))
					{
						$actdate=getdate(time());
						$datedays=dateToJuliandays($actdate["mday"],$actdate["mon"],$actdate["year"]);
						$onlynewdays = $datedays-$onlynewfaq;
						list($year, $month, $day) = explode("-", $temprow["editdate"]);
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
						$asc_heading=undo_htmlentities($temprow["heading"]);
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
						list($year, $month, $day) = explode("-", $temprow["editdate"]);
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
						if(($temprow["linkedfaq"]==0) || ($lhide==1))
							$link.="?display=faq&amp;faqnr=$act_id&amp;catnr=$catnr&amp;prog=$prog&amp;$langvar=$act_lang";
						else
						{
							$linksql="select dat.*, prog.progid from ".$tableprefix."_data dat, ".$tableprefix."_category cat, ".$tableprefix."_programm prog where dat.faqnr=".$temprow["linkedfaq"]." and cat.catnr=dat.category and prog.prognr=cat.programm";
							if(!$linkresult = faqe_db_query($linksql, $db))
								die("Could not connect to the database.");
							if($linkrow=faqe_db_fetch_array($linkresult))
								$link.="?display=faq&amp;faqnr=".$linkrow["faqnr"]."&amp;catnr=".$linkrow["category"]."&amp;prog=".$linkrow["progid"]."&amp;$langvar=$act_lang";
							else
								die("Could not connect to the database.");
						}
						if(isset($layout))
							$link.="&amp;layout=$layout";
						if(isset($onlynewfaq))
							$link.="&amp;onlynewfaq=$onlynewfaq";
						if(isset($limitprog))
							$link.="&amp;limitprog=$limitprog";
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
					echo undo_html_ampersand(stripslashes($temprow["heading"]));
					echo "</a></span>";
					if($summaryintotallist==1)
					{
						echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $faqfontcolor;\">";
						$summarytext=get_summary($temprow["questiontext"],$summarychars);
						echo "<br>$l_question: $summarytext";
						$summarytext=get_summary($temprow["answertext"],$summarychars);
						echo "<br>$l_answer: $summarytext</span>";
					}
					echo "</td>";
					if($displayvotesinline==1)
					{
						echo "<td width=\"5%\" nowrap align=\"right\" class=\"ratingrow\" bgcolor=\"$faqbgcolor\" title=\"$l_userrating\">";
						echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $faqfontcolor;\">";
						if($votesinlinedisplaymode==1)
							echo display_ratings_inline($temprow["rating"],$temprow["ratingcount"]);
						else
						{
							if($temprow["ratingcount"]>0)
								echo round($temprow["rating"]/$temprow["ratingcount"],2). " (".$temprow["ratingcount"].")";
							else
								echo "&nbsp;";
						}
						echo "</span></td>";
					}
					echo "</tr>";
				} while($temprow = faqe_db_fetch_array($tempresult));
			}
// display FAQs with no subcategory end
// display FAQs with subcategory start
			$subsql="select * from ".$tableprefix."_subcategory where category=".$myrow["catnr"]." order by displaypos asc";
			if(!$subresult = faqe_db_query($subsql, $db))
			{
				echo "<tr><td bgcolor=\"$heading_bgcolor\">";
				die("Could not connect to the database.");
			}
			if ($subrow = faqe_db_fetch_array($subresult))
			{
				do{
					$actdate = date("Y-m-d");
					$sql2 = "select * from ".$tableprefix."_data where subcategory=".$subrow["catnr"];
					if(isset($onlynewfaq) && ($onlynewfaq>0) && ($faqnewdisplaymethod==0))
						$sql2 .= " and editdate >= DATE_SUB('$actdate', INTERVAL $onlynewfaq DAY)";
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
					if(!$tempresult = faqe_db_query($sql2, $db))
					{
						echo "<tr><td bgcolor=\"$heading_bgcolor\">";
						die("Could not connect to the database.");
					}
					$catfaqs=faqe_db_num_rows($tempresult);
					if(($maxentries>0) && ( (($actfaq+$catfaqs)<=$start) || (($actfaq+1)>($start+$maxentries)) || ($catfaqs<=0)))
					{
						if($actfaq<$start+$maxentries)
							$displayedfaq+=$catfaqs;
						$actfaq+=$catfaqs;
						continue;
					}
					if($navframe==1)
						$link=$url_faqengine."/faqframe.php";
					else
						$link=$act_script_url;
					$link.="?$langvar=$act_lang&amp;list=subcategory&amp;catnr=$catnr&amp;subcatnr=".$subrow["catnr"]."&amp;prog=$prog";
					if(isset($onlynewfaq))
						$link.="&amp;onlynewfaq=$onlynewfaq";
					if(isset($limitprog))
						$link.="&amp;limitprog=$limitprog";
					if(isset($layout))
						$link.="&amp;layout=$layout";
					echo "</table>";
					echo "<TABLE BORDER=\"0\" CELLPADDING=\"$tablepadding\" CELLSPACING=\"$tablespacing\" WIDTH=\"100%\">";
					echo "<TR BGCOLOR=\"$subcatbgcolor\" ALIGN=\"LEFT\">";
					echo "<td colspan=\"2\" bgcolor=\"$subcatbgcolor\" class=\"catrow\"";
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
					echo "<span style=\"font-face: $FontFace; font-size: $subcatfontsize; color: $subcatfontcolor; $addstyle\">";
					echo "<a class=\"catlink\" href=\"$link\"";
					if($navframe==1)
						echo " target=\"_parent\"";
					echo ">";
					echo display_encoded($subrow["categoryname"]);
					echo "</a></span></td></tr>";
					if (!$temprow = faqe_db_fetch_array($tempresult))
					{
						echo "</table>";
						echo "<TABLE BORDER=\"0\" CELLPADDING=\"$tablepadding\" CELLSPACING=\"$tablespacing\" WIDTH=\"100%\">";
						echo "<TR BGCOLOR=\"$row_bgcolor\" ALIGN=\"LEFT\">";
						echo "<td width=\"5%\">&nbsp;";
						echo "</td><td class=\"listrow\" width=\"95%\">";
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
							echo "<tr bgcolor=\"$subcatbgcolor\"><td class=\"shortbar\" align=\"left\" colspan=\"2\">";
							echo "<span style=\"font-face: $FontFace; font-size: $shortbarfontsize; color: $subcatfontcolor;\">";
							for($i=65;$i<91;$i++)
								echo "<a class=\"shortbar\" href=\"#".chr($i)."\">".chr($i)."</a> ";
							echo "</span></td></tr>";
							$startchar="";
						}
						echo "</table>";
						echo "<TABLE BORDER=\"0\" CELLPADDING=\"$tablepadding\" CELLSPACING=\"$tablespacing\" WIDTH=\"100%\">";
						do {
							$actfaq++;
							if( ($maxentries>0) && ($actfaq > ($start+$maxentries)) )
								continue;
							$displayedfaq++;
							if($actfaq-1<$start)
								continue;
							$act_id=$temprow["faqnr"];
							$faqbgcolor=$row_bgcolor;
							$faqfontcolor=$FontColor;
							if(isset($onlynewfaq) && ($onlynewfaq>0) && ($faqnewdisplaymethod==1))
							{
								$actdate=getdate(time());
								$datedays=dateToJuliandays($actdate["mday"],$actdate["mon"],$actdate["year"]);
								$onlynewdays = $datedays-$onlynewfaq;
								list($year, $month, $day) = explode("-", $temprow["editdate"]);
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
								$asc_heading=undo_htmlentities($temprow["heading"]);
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
								list($year, $month, $day) = explode("-", $temprow["editdate"]);
								$tempdays=dateToJuliandays($day, $month, $year);
								if($tempdays>$newdatedays)
									echo "<img class=\"newmarker\" src=\"$newpic\" border=\"0\" alt=\"\">";
								else
									echo "&nbsp;";
							}
							echo "</td>";
							if($navframe==1)
								$link=$url_faqengine."/faqframe.php";
							else
								$link=$act_script_url;
							if(($temprow["linkedfaq"]==0) || ($lhide==1))
								$link.="?display=faq&amp;faqnr=$act_id&amp;catnr=$catnr&amp;prog=$prog&amp;$langvar=$act_lang";
							else
							{
								$linksql="select dat.*, prog.progid from ".$tableprefix."_data dat, ".$tableprefix."_category cat, ".$tableprefix."_programm prog where dat.faqnr=".$temprow["linkedfaq"]." and cat.catnr=dat.category and prog.prognr=cat.programm";
								if(!$linkresult = faqe_db_query($linksql, $db))
									die("Could not connect to the database.");
								if($linkrow=faqe_db_fetch_array($linkresult))
									$link.="?display=faq&amp;faqnr=".$linkrow["faqnr"]."&amp;catnr=".$linkrow["category"]."&amp;prog=".$linkrow["progid"]."&amp;$langvar=$act_lang";
								else
									die("Could not connect to the database.");
							}
							if(isset($layout))
								$link.="&amp;layout=$layout";
							if(isset($onlynewfaq))
								$link.="&amp;onlynewfaq=$onlynewfaq";
							if(isset($limitprog))
								$link.="&amp;limitprog=$limitprog";
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
							echo "<a href=\"$link\" class=\"listlink\"";
							if($navframe==1)
								echo " target=\"_parent\"";
							echo ">";
							echo undo_html_ampersand(stripslashes($temprow["heading"]));
							echo "</a></span>";
							if($summaryintotallist==1)
							{
								echo "<span style=\"font-face: $FontFace; font-size: $FontSize4; color: $faqfontcolor;\">";
								$summarytext=get_summary($temprow["questiontext"],$summarychars);
								echo "<br>$l_question: $summarytext";
								$summarytext=get_summary($temprow["answertext"],$summarychars);
								echo "<br>$l_answer: $summarytext</span>";
							}
							echo "</td>";
							if($displayvotesinline==1)
							{
								echo "<td width=\"5%\" nowrap align=\"right\" class=\"ratingrow\" bgcolor=\"$faqbgcolor\" title=\"$l_userrating\">";
								echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $faqfontcolor;\">";
								if($votesinlinedisplaymode==1)
									echo display_ratings_inline($temprow["rating"],$temprow["ratingcount"]);
								else
								{
									if($temprow["ratingcount"]>0)
										echo round($temprow["rating"]/$temprow["ratingcount"],2). " (".$temprow["ratingcount"].")";
									else
										echo "&nbsp;";
								}
								echo "</span></td>";
							}
							echo "</tr>";
						} while($temprow = faqe_db_fetch_array($tempresult));
					}
				}while($subrow=faqe_db_fetch_array($subresult));
			}
			echo "</table>";
// display FAQs with subcategory end
	   } while($myrow = faqe_db_fetch_array($result));
// loop throught categories end
	}
}
else
{
	echo "<TABLE BORDER=\"0\" CELLPADDING=\"$tablepadding\" CELLSPACING=\"$tablespacing\" WIDTH=\"100%\">";
	$actdate = date("Y-m-d");
	$sql = "select dat.* from ".$tableprefix."_data dat, ".$tableprefix."_category cat, ".$tableprefix."_programm prog where prog.progid='$prog' and prog.language='$act_lang' and dat.category=cat.catnr and cat.programm=prog.prognr";
	if(isset($onlynewfaq) && ($onlynewfaq>0) && ($faqnewdisplaymethod==0))
		$sql.= " and dat.editdate >= DATE_SUB('$actdate', INTERVAL $onlynewfaq DAY)";
	switch($faqsortmethod)
	{
		case 2:
			$sql.=" order by dat.heading asc";
			break;
		default:
			$sql.=" order by dat.editdate desc";
			break;
	}
	if(!$result = faqe_db_query($sql, $db))
	{
		echo "<tr><td bgcolor=\"$heading_bgcolor\">";
		die("Could not connect to the database.".faqe_db_error());
	}
	if (!$temprow = faqe_db_fetch_array($result))
	{
		echo "<td class=\"newmarker\" width=\"5%\">&nbsp;";
		echo "</td><td class=\"listrow\" with=\"95%\">";
		echo "<span style=\"font-face: $FontFace; font-size: $FontSize1;\">";
		if(isset($onlynewfaq) && ($onlynewfaq>0) && ($faqnewdisplaymethod==0))
			echo $l_nonewentries;
		else
			echo $l_noentries;
		echo "</span></td></tr></table>";
	}
	else
	{
		if(($faqsortmethod==2) && ($enableshortcutbar==1))
		{
			echo "<tr bgcolor=\"$group_bgcolor\"><td class=\"shortbar\" align=\"left\" colspan=\"2\">";
			echo "<span style=\"font-face: $FontFace; font-size: $shortbarfontsize; color: $GroupFontColor;\">";
			for($i=65;$i<91;$i++)
				echo "<a href=\"#".chr($i)."\">".chr($i)."</a> ";
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
			$act_id=$temprow["faqnr"];
			$faqbgcolor=$row_bgcolor;
			$faqfontcolor=$FontColor;
			$catnr=$temprow["category"];
			if(isset($onlynewfaq) && ($onlynewfaq>0) && ($faqnewdisplaymethod==1))
			{
				$actdate=getdate(time());
				$datedays=dateToJuliandays($actdate["mday"],$actdate["mon"],$actdate["year"]);
				$onlynewdays = $datedays-$onlynewfaq;
				list($year, $month, $day) = explode("-", $temprow["editdate"]);
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
				$asc_heading=undo_htmlentities($temprow["heading"]);
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
				list($year, $month, $day) = explode("-", $temprow["editdate"]);
				$tempdays=dateToJuliandays($day, $month, $year);
				if($tempdays>$newdatedays)
					echo "<img class=\"newmarker\" src=\"$newpic\" border=\"0\" alt=\"\">";
				else
					echo "&nbsp;";
			}
			echo "</td>";
			if($navframe==0)
				$link=$url_faqengine."/faqframe.php";
			else
				$link=$act_script_url;
			$link.="?display=faq&amp;faqnr=$act_id&amp;catnr=$catnr&amp;prog=$prog&amp;$langvar=$act_lang";
			if(isset($layout))
				$link.="&amp;layout=$layout";
			if(isset($onlynewfaq))
				$link.="&amp;onlynewfaq=$onlynewfaq";
			if(isset($limitprog))
				$link.="&amp;limitprog=$limitprog";
			if($displayvotesinline==1)
				$tblwidth="90%";
			else
				$tblwidth="95%";
			echo "<td width=\"$tblwidth\" bgcolor=\"$faqbgcolor\" class=\"listrow\"";
			if($hovercells==1)
				echo " onMouseOver=\"this.style.backgroundColor='$activcellcolor';\" onMouseOut=\"this.style.backgroundColor='$faqbgcolor'\" onclick=\"window.location.href='$link'\"";
			echo ">";
			echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $faqfontcolor;\">";
			echo "<a href=\"$link\" class=\"listlink\">";
			echo undo_html_ampersand(stripslashes($temprow["heading"]));
			echo "</a></span>";
			if($summaryintotallist==1)
			{
				echo "<span style=\"font-face: $FontFace; font-size: $FontSize4; color: $faqfontcolor;\">";
				$summarytext=get_summary($temprow["questiontext"],$summarychars);
				echo "<br>$l_question: $summarytext";
				$summarytext=get_summary($temprow["answertext"],$summarychars);
				echo "<br>$l_answer: $summarytext</span>";
			}
			echo "</td>";
			if($displayvotesinline==1)
			{
				echo "<td width=\"5%\" nowrap align=\"right\" class=\"ratingrow\" bgcolor=\"$faqbgcolor\" title=\"$l_userrating\">";
				echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $faqfontcolor;\">";
				if($votesinlinedisplaymode==1)
					echo display_ratings_inline($temprow["rating"],$temprow["ratingcount"]);
				else
				{
					if($temprow["ratingcount"]>0)
						echo round($temprow["rating"]/$temprow["ratingcount"],2). " (".$temprow["ratingcount"].")";
					else
						echo "&nbsp;";
				}
				echo "</span></td>";
			}
			echo "</tr>";
		} while($temprow = faqe_db_fetch_array($result));
	}
	echo "</table>";
}
if($allowquestions==1)
{
	$actdate = date("Y-m-d");
	$sql = "select * from ".$tableprefix."_questions where prognr='$prognr' and language='$act_lang' and publish=1 and faqref=0 ";
	if(isset($onlynewfaq) && ($onlynewfaq>0) && ($faqnewdisplaymethod==0))
		$sql.=" and enterdate >= DATE_SUB('$actdate', INTERVAL $onlynewfaq DAY)";
	$sql.=" order by enterdate desc";
	if(!$result = faqe_db_query($sql, $db))
	{
		echo "<tr><td bgcolor=\"$heading_bgcolor\">";
		die("Could not connect to the database.".faqe_db_error());
	}
	$catfaqs=faqe_db_num_rows($result);
	if(($maxentries<=0) || (($maxentries>0) && ($actfaq+$catfaqs>$start) && ($actfaq<$start+$maxentries) && ($catfaqs>0)))
	{
		echo "<TABLE BORDER=\"0\" CELLPADDING=\"$tablepadding\" CELLSPACING=\"$tablespacing\" WIDTH=\"100%\">";
		echo "<TR BGCOLOR=\"$group_bgcolor\">";
		if($altlinkmethod==0)
		{
			if($navframe==1)
				$link=$url_faqengine."/faqframe.php";

			else
				$link=$act_script_url;
			$link.="?$langvar=$act_lang&amp;list=questions&amp;prog=$prog";
			if(isset($onlynewfaq))
				$link.="&amp;onlynewfaq=$onlynewfaq";
			if(isset($layout))
				$link.="&amp;layout=$layout";
			if(isset($limitprog))
				$link.="&amp;limitprog=$limitprog";
		}
		else
		{
			$link="listqes.php?id=$prog|$act_lang|$layout";
		}
		echo "<td colspan=\"2\" bgcolor=\"$group_bgcolor\" class=\"catrow\"";
		if($hovercells==1)
		{
			echo " onMouseOver=\"this.style.backgroundColor='$activcellcolor';\" onMouseOut=\"this.style.backgroundColor='$group_bgcolor'\"";
			if($navframe==0)
				echo " onclick=\"window.location.href='$link'\"";
		}
		echo ">";
		echo "<span style=\"font-face: $FontFace; font-size: $FontSize5; color: $GroupFontColor; font-weight: bold;\">";
		echo "<a href=\"$link\" class=\"catrow\"";
		if($navframe==1)
			echo " target=\"_parent\"";
		echo ">";
		echo $l_userquestions;
		echo "</span></td></tr>";
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
			do {
				$actfaq++;
				if( ($maxentries>0) && ($actfaq > ($start+$maxentries)) )
					continue;
				$displayedfaq++;
				if($actfaq-1<$start)
					continue;
				$act_id=$myrow["questionnr"];
				$questionbgcolor=$row_bgcolor;
				$questionfontcolor=$FontColor;
				if(isset($onlynewfaq) && ($onlynewfaq>0) && ($faqnewdisplaymethod==1))
				{
					$actdate=getdate(time());
					$datedays=dateToJuliandays($actdate["mday"],$actdate["mon"],$actdate["year"]);
					$onlynewdays = $datedays-$onlynewfaq;
					list($year, $month, $day) = explode("-", $myrow["enterdate"]);
					$tempdays=dateToJuliandays($day, $month, $year);
					if($tempdays>$onlynewdays)
					{
						$questionbgcolor=$faqnewdisplaybgcolor;
						$questionfontcolor=$faqnewdisplayfontcolor;
					}
				}
				echo "<TR BGCOLOR=\"$row_bgcolor\" ALIGN=\"LEFT\">";
				echo "<td class=\"newmarker\" width=\"5%\" align=\"center\">";
				if($newtime>0)
				{
					list($year, $month, $day) = explode("-", $myrow["enterdate"]);
					$tempdays=dateToJuliandays($day, $month, $year);
					if($tempdays>$newdatedays)
						echo "<img class=\"newmarker\" src=\"$newpic\" border=\"0\" alt=\"\">";
					else
						echo "&nbsp;";
				}
				echo "</td>";
				if($altlinkmethod==0)
				{
					if($navframe==1)
						$backurl=$url_faqengine."/faqframe.php";
					else
						$backurl=$url_faqengine."/faq.php";
					$backurl.="?list=all&prog=$prog&$langvar=$act_lang";
					if(isset($onlynewfaq))
						$backurl.="&onlynewfaq=$onlynewfaq";
					if(isset($limitprog))
						$backurl.="&limitprog=$limitprog";
					if(isset($layout))
						$backurl.="&layout=$layout";
					$backurl=urlencode($backurl);
					if($navframe==1)
						$link=$url_faqengine."/faqframe.php?special=question&";
					else
						$link=$url_faqengine."/question.php?";
					$link.="mode=read&amp;question=$act_id&amp;prog=$prog&amp;$langvar=$act_lang";
					if(isset($layout))
						$link.="&amp;layout=$layout";
					if(isset($onlynewfaq))
						$link.="&amp;onlynewfaq=$onlynewfaq";
					if(isset($limitprog))
						$link.="&amp;limitprog=$limitprog";
					$link.="&amp;backurl=$backurl";
				}
				else
				{
					$backurl=$url_faqengine."/listallfaq.php?id=".$prog."§".$act_lang."§".$layout;
					$backurl=urlencode($backurl);
					$link="dispqes.php?id=$act_id|$act_lang|$layout|$backurl";
				}
				echo "<td bgcolor=\"$questionbgcolor\" class=\"catrow\"";
				if($hovercells==1)
				{
					echo " onMouseOver=\"this.style.backgroundColor='$activcellcolor';\" onMouseOut=\"this.style.backgroundColor='$questionbgcolor'\"";
					if($navframe==0)
						echo " onclick=\"window.location.href='$link'\"";
				}
				echo ">";
				echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $questionfontcolor;\">";
				echo "<a href=\"$link\" class=\"listlink\"";
				if($navframe==1)
					echo " target=\"_parent\"";
				echo ">";
				if($questionshorting>0)
					$questiontext=substr($myrow["question"],0,$questionshorting);
				else
					$questiontext=$myrow["question"];
				echo display_encoded($questiontext);
				echo "</a></span></td></tr>";
			} while($myrow = faqe_db_fetch_array($result));
		}
		echo "</table>";
	}
}
if(($maxentries>0) && ($totalfaqs>$maxentries))
{
	$url_start="$act_script_url?$langvar=$act_lang&amp;list=all&amp;prog=$prog&amp;onlynewfaq=$onlynewfaq";
	if(isset($layout))
		$url_start.="&amp;layout=$layout";
	if($navframe==1)
	{
		$url_start.="&amp;navframe=1";
		if(isset($limitprog))
			$url_start.="&amp;limitprog=$limitprog";
	}
	echo "<TABLE BORDER=\"0\" CELLPADDING=\"1\" CELLSPACING=\"1\" WIDTH=\"100%\">";
	echo "<tr bgcolor=\"$actionbgcolor\"><td class=\"pagenav\" align=\"center\" valign=\"middle\"";
	if($proginfoavail==1)
		echo " colspan=\"2\"";
	echo">";
	faq_pagenav($start, $maxentries, $totalfaqs, $displayedfaq, $url_start);
	echo "</td></tr></table>";
}
?>
</td></tr>
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
	echo "<a class=\"actionline\" href=\"$linkurl\"";
	if($navframe==1)
		echo " target=\"_parent\"";
	echo ">$l_listcats</a>";
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
			$linkurl.="&amp;layout=$layout";
		if(isset($onlynewfaq))
			$linkurl.="&amp;onlynewfaq=$onlynewfaq";
		if(isset($limitprog))
			$linkurl.="&amp;limitprog=$limitprog";
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
<td class="gototop" align="right" width="1%"><a class="gototop" href="#top">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $actionlinefontsize?>;">
<img src="<?php echo $pagetoppic?>" border="0" align="middle" title="<?php echo $l_top?>" alt="<?php echo $l_top?>"></a></span></td>
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
	echo "<tr bgcolor=\"$group_bgcolor\"><td class=\"langselect\" align=\"center\" valign=\"middle\">";
	echo "<input type=\"hidden\" name=\"list\" value=\"all\">";
	echo "<input type=\"hidden\" name=\"prog\" value=\"$prog\">";
	if($navframe==1)
		echo "<input type=\"hidden\" name=\"navframe\" value=\"1\">";
	if(isset($onlynewfaq))
		echo "<input type=\"hidden\" name=\"onlynewfaq\" value=\"$onlynewfaq\">";
	if(isset($layout))
		echo "<input type=\"hidden\" name=\"layout\" value=\"$layout\">";
	if(isset($limitprog))
		echo "<input type=\"hidden\" name=\"limitprog\" value=\"$limitprog\">";
	echo "<span style=\"font-face: $FontFace; font-size: $langselectfontsize; color: $FontColor;\">";
	echo "$l_changelang: ".language_select($act_lang,$langvar,"./language","langselect");
	echo "&nbsp;&nbsp;&nbsp;<input class=\"langselect\" type=\"submit\" value=\"$l_ok\">";
	echo "</span></td></tr></form></table>";
}
if($enablefaqnewdisplay==1)
{
	echo "<table style=\"clear:both\" width=\"$TableWidth\" align=\"$tblalign\" border=\"0\">\n";
	echo "<form method=\"post\" action=\"$act_script_url\">\n";
	echo "<tr bgcolor=\"$group_bgcolor\"><td class=\"faqnewdisplay\" align=\"center\">\n";
	echo "<input type=\"hidden\" name=\"list\" value=\"all\">\n";
	echo "<input type=\"hidden\" name=\"prog\" value=\"$prog\">\n";
	echo "<input type=\"hidden\" name=\"$langvar\" value=\"$act_lang\">\n";
	if($navframe==1)
		echo "<input type=\"hidden\" name=\"navframe\" value=\"1\">";
	if(isset($layout))
		echo "<input type=\"hidden\" name=\"layout\" value=\"$layout\">";
	if(isset($limitprog))
		echo "<input type=\"hidden\" name=\"limitprog\" value=\"$limitprog\">";
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
	echo " $l_days\n";
	echo "&nbsp;&nbsp;&nbsp;<input class=\"faqnewdisplay\" type=\"submit\" value=\"$l_ok\">\n";
	echo "</span></td></tr></form></table>\n";
}
?>