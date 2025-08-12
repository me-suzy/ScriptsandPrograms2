<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
$actcat=0;
$displayedcat=0;
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
		if(isset($layout))
			echo "<input type=\"hidden\" name=\"layout\" value=\"$layout\">";
		if($navframe==1)
			echo "<input type=\"hidden\" name=\"navframe\" value=\"1\">";
		if(isset($limitprog))
			echo "<input type=\"hidden\" name=\"limitprog\" value=\"$limitprog\">";
		if(is_konqueror())
			echo "<tr><td></td></tr>";
		echo "<tr BGCOLOR=\"$actionbgcolor\">";
		echo "<td class=\"jumpbox\" align=\"right\">";
		echo "<span style=\"font-face: $FontFace; font-size: $jumpboxfontsize; color: $FontColor; font-weight: bold;\">";
		echo "$l_program: ";
		echo "<input type=\"hidden\" name=\"$langvar\" value=\"$act_lang\">";
		echo "<input type=\"hidden\" name=\"onlynewfaq\" value=\"$onlynewfaq\">";
		echo "<input type=\"hidden\" name=\"list\" value=\"categories\">";
		echo "<select class=\"jumpbox\" name=\"prog\">";
		do{
			echo "<option value=\"".$myrow["progid"]."\"";
			if($myrow["progid"]==$prog)
				echo " selected";
			echo ">".display_encoded($myrow["programmname"])."</option>";
		}while($myrow=faqe_db_fetch_array($result));
		echo "</select>&nbsp;&nbsp;";
		echo "<input class=\"jumpbox\" type=\"submit\" value=\"$l_change\">&nbsp;";
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
	$proginfoavail=0;
	$prognr=0;
	$progname=$l_undefined;
}
echo "<tr BGCOLOR=\"$subheadingbgcolor\" ALIGN=\"CENTER\">";
echo "<TD class=\"subheading\" ALIGN=\"CENTER\" VALIGN=\"MIDDLE\"";
if($proginfoavail==1)
	echo "WIDTH=\"95%\"";
echo ">";
echo "<span style=\"font-face: $FontFace; font-size: $FontSize2; color: $SubheadingFontColor; font-weight: bold\">$l_catlist: $progname</span>";
echo "</td>";
if($proginfoavail==1)
{
	echo "<td width=\"5%\" align=\"center\" bgcolor=\"$subheadingbgcolor\" class=\"proginfolink\"";
	echo " onMouseOver=\"this.style.backgroundColor='$activcellcolor';\" onMouseOut=\"this.style.backgroundColor='$subheadingbgcolor'\" onclick=\"openProginfo('proginfo.php?$langvar=$act_lang&prog=".$prognr."')\">";
	echo "<a class=\"proginfolink\" href=\"javascript:openProginfo('proginfo.php?$langvar=$act_lang&prog=".$prognr."')\"><img src=\"$proginfopic\" border=\"0\" title=\"$l_proginfo\" alt=\"$l_proginfo\"></a></td>";
}
?>
</tr></table></td></tr>
<tr><TD BGCOLOR="<?php echo $table_bgcolor?>">
<TABLE BORDER="0" CELLPADDING="<?php echo $tablepadding?>" CELLSPACING="<?php echo $tablespacing?>" WIDTH="100%">
<?php
$actdate = date("Y-m-d");
if(isset($onlynewfaq) && ($onlynewfaq>0) && ($faqnewdisplaymethod==0))
	$sql = "select cat.* from ".$tableprefix."_category cat, ".$tableprefix."_data dat where (cat.programm=$prognr) and cat.catnr=dat.category and dat.editdate >= DATE_SUB('$actdate', INTERVAL $onlynewfaq DAY) group by cat.catnr order by cat.displaypos asc";
else
	$sql = "select * from ".$tableprefix."_category where (programm=$prognr) order by displaypos asc";
if(!$result = faqe_db_query($sql, $db))
{
	echo "<tr><td bgcolor=\"$heading_bgcolor\">";
	die("Could not connect to the database.");
}
if($maxentries>0)
{
	include_once("./includes/faq_pagenav.inc");
	$totalcats=faqe_db_num_rows($result);
	if($allowquestions==1)
	{
		$sql2 = "select * from ".$tableprefix."_questions where prognr='$prognr' and publish=1 and language='$act_lang' and faqref=0";
		if(!$result2 = faqe_db_query($sql2, $db))
		{
			echo "<tr><td bgcolor=\"$heading_bgcolor\">";
			die("Could not connect to the database.".faqe_db_error());
		}
		$numquestions=faqe_db_num_rows($result2);
		if ($numquestions>0)
			$totalcats++;
	}
}
else
	$totalcats=0;
if (!$myrow = faqe_db_fetch_array($result))
{
	echo "<tr><td class=\"listrow\" bgcolor=\"$group_bgcolor\" align=\"center\">";
	echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
	if(isset($onlynewfaq) && ($onlynewfaq>0) && ($faqnewdisplaymethod==0))
		echo $l_nonewentries;
	else
		echo $l_nocategories;
	echo "</span></tr></td>";
}
else
{
	do {
		$actcat++;
		if(($maxentries>0) && (($actcat-1 >= ($start+$maxentries)) ))
			continue;
		$displayedcat++;
		if($actcat-1<$start)
			continue;
		$act_id=$myrow["catnr"];
		$catbgcolor=$row_bgcolor;
		$catfontcolor=$FontColor;
		if(isset($onlynewfaq) && ($onlynewfaq>0))
		{
			$numsql = "select count(faqnr) as numfaqs from ".$tableprefix."_data where (category=$act_id) and (editdate >= DATE_SUB('$actdate', INTERVAL $onlynewfaq DAY))";
			if(!$numresult = faqe_db_query($numsql, $db))
				die("Could not connect to the database.");
			if($numrow=faqe_db_fetch_array($numresult))
			{
				if($numrow["numfaqs"]>0)
					$numfaqs=$numrow["numfaqs"]."/".$myrow["numfaqs"];
				else
				$numfaqs=$myrow["numfaqs"];
			}
			else
				$numfaqs=$myrow["numfaqs"];
			if($numrow["numfaqs"]>0)
			{
				$catbgcolor=$faqnewdisplaybgcolor;
				$catfontcolor=$faqnewdisplayfontcolor;
			}
		}
		else
			$numfaqs=$myrow["numfaqs"];
		echo "<TR BGCOLOR=\"$row_bgcolor\" ALIGN=\"LEFT\">";
		echo "<td class=\"newmarker\" width=\"5%\" align=\"center\">";
		if($newtime>0)
		{
			$sql2 = "select * from ".$tableprefix."_data where (category=$act_id) and (editdate >= '$newdate')";
			if(!$result2 = faqe_db_query($sql2, $db))
			die("Could not connect to the database.");
			if(faqe_db_num_rows($result2)>0)
				echo "<img class=\"newmarker\" src=\"$newpic\" border=\"0\" title=\"$l_newfaq2\" alt=\"$l_newfaq2\">";
			else
				echo "&nbsp;";
		}
		else
			echo "&nbsp;";
		echo "</td>";
		if($numfaqs>0)
		{
			if($altlinkmethod==0)
			{
				if($navframe==1)
					$link=$url_faqengine."/faqframe.php";
				else
					$link=$act_script_url;
				if(isset($onlynewfaq))
					$link.="?list=category&amp;catnr=$act_id&amp;prog=$prog&amp;$langvar=$act_lang&amp;onlynewfaq=$onlynewfaq";
				else
					$link.="?list=category&amp;catnr=$act_id&amp;prog=$prog&amp;$langvar=$act_lang";
				if(isset($layout))
					$link.="&amp;layout=$layout";
				if(isset($limitprog))
					$link.="&amp;limitprog=$limitprog";
			}
			else
			{
				$link="listcat.php?id=$act_id|$act_lang|$layout";
			}
			echo "<td bgcolor=\"$catbgcolor\" class=\"listrow\"";
			if($hovercells==1)
			{
				echo " onMouseOver=\"this.style.backgroundColor='$activcellcolor';\" onMouseOut=\"this.style.backgroundColor='$catbgcolor'\"";
				if($navframe==0)
					echo " onclick=\"window.location.href='$link'\"";
			}
			echo ">";
			echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $catfontcolor;\">";
			echo "<a class=\"listlink\" href=\"$link\"";
			if($navframe==1)
				echo " target=\"_parent\"";
			echo ">";
		}
		else
		{
			echo "<td width=\"90%\" bgcolor=\"$catbgcolor\">";
			echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $catfontcolor;\">";
		}
		echo display_encoded($myrow["categoryname"]);
		if($numfaqs>0)
			echo "</a>";
		echo"</span></td>";
		echo "<td class=\"listrow\" width=\"5%\" align=\"right\">";
		echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
		echo $numfaqs;
		echo "</span></td></tr>";
	} while($myrow = faqe_db_fetch_array($result));
}
if($allowquestions==1)
{
	$sql = "select * from ".$tableprefix."_questions where prognr='$prognr' and publish=1 and language='$act_lang' and faqref=0";
	if(!$result = faqe_db_query($sql, $db))
	{
		echo "<tr><td bgcolor=\"$heading_bgcolor\">";
		die("Could not connect to the database.".faqe_db_error());
	}
	$numquestions=faqe_db_num_rows($result);
	if (($numquestions>0) && (($maxentries<=0) || (($maxentries>0) && ($actcat<$start+$maxentries))))
	{
		$catbgcolor=$row_bgcolor;
		$catfontcolor=$FontColor;
		if(isset($onlynewfaq) && ($onlynewfaq>0))
		{
			$numsql = "select count(questionnr) as newquestions from ".$tableprefix."_questions where prognr='$prognr' and publish=1 and language='$act_lang' and (enterdate >= DATE_SUB('$actdate', INTERVAL $onlynewfaq DAY)) and faqref=0";
			if(!$numresult = faqe_db_query($numsql, $db))
			die("Could not connect to the database.");
			if($numrow=faqe_db_fetch_array($numresult))
			{
				if($numrow["newquestions"]>0)
					$numquestions=$numrow["newquestions"]."/".$numquestions;
			}
			if($numrow["newquestions"]>0)
			{
				$catbgcolor=$faqnewdisplaybgcolor;
				$catfontcolor=$faqnewdisplayfontcolor;
			}
		}
		echo "<TR BGCOLOR=\"$row_bgcolor\" ALIGN=\"LEFT\">";
		echo "<td class=\"newmarker\" width=\"5%\" align=\"center\">";
		if($newtime>0)
		{
			$sql2 = "select * from ".$tableprefix."_questions where prognr='$prognr' and publish=1 and language='$act_lang' and (enterdate >= '$newdate') and faqref=0";
			if(!$result2 = faqe_db_query($sql2, $db))
			die("Could not connect to the database.");
			if(faqe_db_num_rows($result2)>0)
				echo "<img class=\"newmarker\" src=\"$newpic\" border=\"0\" title=\"$l_newfaq2\" alt=\"$l_newfaq2\">";
			else
				echo "&nbsp;";
		}
		else
			echo "&nbsp;";
		echo "</td>";
		if($numquestions>0)
		{
			if($altlinkmethod==0)
			{
				if($navframe==1)
					$link=$url_faqengine."/faqframe.php";
				else
					$link=$act_script_url;
				if(isset($onlynewfaq))
					$link.="?list=questions&amp;prog=$prog&amp;$langvar=$act_lang&amp;onlynewfaq=$onlynewfaq";
				else
					$link.="?list=questions&amp;prog=$prog&amp;$langvar=$act_lang";
				if(isset($layout))
					$link.="&amp;layout=$layout";
				if(isset($limitprog))
					$link.="&amp;limitprog=$limitprog";
			}
			else
				$link="listqes.php?id=$prog|$act_lang|$layout";
			echo "<td width=\"90%\" bgcolor=\"$catbgcolor\" class=\"listrow\"";
			if($hovercells==1)
			{
				echo " onMouseOver=\"this.style.backgroundColor='$activcellcolor';\" onMouseOut=\"this.style.backgroundColor='$catbgcolor'\"";
				if($navframe==0)
					echo " onclick=\"window.location.href='$link'\"";
			}
			echo ">";
			echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $catfontcolor;\">";
			echo "<a class=\"listlink\" href=\"$link\"";
			if($navframe==1)
				echo " target=\"_parent\"";
			echo ">";
		}
		else
		{
			echo "<td class=\"listrow\" width=\"90%\" bgcolor=\"$catbgcolor\">";
			echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $catfontcolor;\">";
		}
		echo $l_userquestions;
		if($numquestions>0)
			echo "</a>";
		echo"</span></td>";
		echo "<td class=\"listrow\" width=\"5%\" align=\"right\">";
		echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
		echo $numquestions;
		echo "</span></td></tr>";
	}
}
if(($maxentries>0) && ($totalcats>$maxentries))
{
	$url_start="$act_script_url?$langvar=$act_lang&amp;list=categories&amp;prog=$prog&amp;onlynewfaq=$onlynewfaq";
	if(isset($layout))
		$url_start.="&amp;layout=$layout";
	if($navframe==1)
	{
		$url_start.="&amp;navframe=1";
		if(isset($limitprog))
			$url_start.="&amp;limitprog=$limitprog";
	}
	echo "<tr bgcolor=\"$actionbgcolor\"><td class=\"pagenav\" align=\"center\" valign=\"middle\" colspan=\"3\">";
	faq_pagenav($start, $maxentries, $totalcats, $displayedcat, $url_start);
	echo "</td></tr>";
}
?>
</td></tr></table></td></tr>
<tr><TD BGCOLOR="<?php echo $table_bgcolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<TR BGCOLOR="<?php echo $actionbgcolor?>" ALIGN="LEFT">
<td class="actionline" align="center" valign="middle" width="98%">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $actionlinefontsize?>; color: <?php echo $FontColor?>;">
<?php
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
echo "<a class=\"actionline\" href=\"$linkurl\"";
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
		if(isset($layout))
			$linkurl.="&amp;layout=$layout";
		if(isset($limitprog))
			$link.="&amp;limitprog=$limitprog";
	}
	else
	{
		$linkurl="listprogs.php?id=$act_lang|$layout";
	}
	echo "| <a class=\"actionline\" href=\"$linkurl\"";
	if($navframe==1)
		echo " target=\"_parent\"";
	echo ">$l_listprogs</a>";
}
?>
</span>
</td>
</td>
<td class="gototop" align="right" width="1%"><a href="#top">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize4?>;"><img class="gototop" src="<?php echo $pagetoppic?>" border="0" align="middle" title="<?php echo $l_top?>" alt="<?php echo $l_top?>"></a></span></td>
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
	echo "<input type=\"hidden\" name=\"list\" value=\"categories\">";
	echo "<input type=\"hidden\" name=\"prog\" value=\"$prog\">";
	echo "<span style=\"font-face: $FontFace; font-size: $langselectfontsize; color: $FontColor;\">";
	echo "$l_changelang: ".language_select($act_lang,$langvar,"./language","langselect");
	echo "&nbsp;&nbsp;&nbsp;<input class=\"langselect\" type=\"submit\" value=\"$l_ok\">";
	echo "</span></td></tr></form></table>";
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
	echo "<input type=\"hidden\" name=\"list\" value=\"categories\">";
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