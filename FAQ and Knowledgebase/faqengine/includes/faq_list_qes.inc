<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
?>
<tr><TD BGCOLOR="<?php echo $table_bgcolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<?php
		if(!isset($prog) || (!$prog) || ($allowquestions==0))
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
?>
<tr BGCOLOR="<?php echo $subheadingbgcolor?>" ALIGN="CENTER">
<?php
		echo "<TD class=\"subheading\" ALIGN=\"CENTER\" VALIGN=\"MIDDLE\"";
		if($proginfoavail==1)
			echo "WIDTH=\"95%\"";
		echo ">";
?>
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize2?>; color: <?php echo $SubheadingFontColor?>; font-weight: bold;">
<?php echo $l_userquestions?>: <?php echo $progname?></span>
</td>
<?php
		if($proginfoavail==1)
		{
			$proginfolink="proginfo.php?$langvar=$act_lang&prog=".$prognr;
			if(isset($layout))
				$proginfolink.="&layout=$layout";
			echo "<td class=\"proginfolink\" align=\"center\" width=\"5%\"";
			if($hovercells==1)
				echo " onMouseOver=\"this.style.backgroundColor='$activcellcolor';\" onMouseOut=\"this.style.backgroundColor='$subheadingbgcolor'\" onclick=\"openProginfo('$proginfolink')\"";
			echo ">";
			echo "<a class=\"proginfolink\" href=\"javascript:openProginfo('$proginfolink')\"><img class=\"proginfolink\" src=\"$proginfopic\" border=\"0\" title=\"$l_proginfo\" alt=\"$l_proginfo\"></a></td>";
		}
?>
</tr></table></td></tr>
<tr><TD BGCOLOR="<?php echo $table_bgcolor?>">
<TABLE BORDER="0" CELLPADDING="<?php echo $tablepadding?>" CELLSPACING="<?php echo $tablespacing?>" WIDTH="100%">
<?php
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
		$actquestion=0;
		$displayedquestion=0;
		if($maxentries>0)
		{
			include_once("./includes/faq_pagenav.inc");
			$totalquestions=faqe_db_num_rows($result);
		}
		else
			$totalquestions=0;
		if (!$myrow = faqe_db_fetch_array($result))
		{
			echo "<TR BGCOLOR=\"$row_bgcolor\" ALIGN=\"LEFT\">";
			echo "<td class=\"listrow\" align=\"center\" colspan=\"2\">";
			echo "<span style=\"font-face: $FontFace; font-size: $FontSize5;\">";
			if(isset($onlynewfaq) && ($onlynewfaq>0) && ($faqnewdisplaymethod==0))
				echo $l_nonewentries;
			else
				echo $l_noentries;
			echo "</span></td></tr>";
		}
		else
		{
			do {
				$actquestion++;
				if(($maxentries>0) && (($actquestion-1 >= ($start+$maxentries)) ))
					continue;
				$displayedquestion++;
				if($actquestion-1<$start)
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
						echo "<img class=\"newmarker\" src=\"$newpic\" border=\"0\" title=\"$l_newfaq2\" alt=\"$l_newfaq2\">";
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
					$backurl.="?list=questions&prog=$prog&$langvar=$act_lang";
					if(isset($onlynewfaq))
						$backurl.="&onlynewfaq=$onlynewfaq";
					if(isset($layout))
						$backurl.="&layout=$layout";
					if(isset($limitprog))
						$backurl.="&limitprog=$limitprog";
					$backurl=urlencode($backurl);
					if($navframe==1)
						$link=$url_faqengine."/faqframe.php?special=question&";
					else
						$link=$url_faqengine."/question.php?";
					$link.="mode=read&amp;question=$act_id&amp;prog=$prog&amp;$langvar=$act_lang";
					if(isset($layout))
						$link.="&amp;layout=$layout";
					if(isset($limitprog))
						$link.="&amp;limitprog=$limitprog";
					if(isset($onlynewfaq))
						$link.="&amp;onlynewfaq=$onlynewfaq";
					$link.="&amp;backurl=$backurl";
				}
				else
				{
					$backurl="listqes.php?id=".$prog."§".$act_lang."§".$layout;
					$backurl=urlencode($backurl);
					$link="dispqes.php?id=$act_id|$act_lang|$layout|$backurl";
				}
				echo "<td width=\"95%\" class=\"listrow\" bgcolor=\"$questionbgcolor\"";
				if($hovercells==1)
				{
					echo " onMouseOver=\"this.style.backgroundColor='$activcellcolor';\" onMouseOut=\"this.style.backgroundColor='$questionbgcolor'\"";
					if($navframe==0)
						echo " onclick=\"window.location.href='$link'\"";
				}
				echo ">";
				echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $questionfontcolor;\">";
				echo "<a href=\"$link\"";
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
		if(($maxentries>0) && ($totalquestions>$maxentries))
		{
			$url_start="$act_script_url?$langvar=$act_lang&amp;list=questions&amp;prog=$prog&amp;onlynewfaq=$onlynewfaq";
			if(isset($layout))
				$url_start.="&amp;layout=$layout";
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
			faq_pagenav($start, $maxentries, $totalquestions, $displayedquestion, $url_start);
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
		if(isset($onlynewfaq))
			$linkurl.="&amp;onlynewfaq=$onlynewfaq";
		if(isset($limitprog))
			$linkurl.="&amp;limitprog=$limitprog";
		if(isset($layout))
			$linkurl.="&amp;layout=$layout";
	}
	else
		$linkurl="listcats.php?id=$prog|$act_lang|$layout";
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
		if(isset($onlynewfaq))
			$linkurl.="&amp;onlynewfaq=$onlynewfaq";
		if(isset($limitprog))
			$linkurl.="&amp;limitprog=$limitprog";
		if(isset($layout))
			$linkurl.="&amp;layout=$layout";
	}
	else
		$linkurl="listallfaq.php?id=$prog|$act_lang|$layout";
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
			$linkurl.="list=progs&amp;$langvar=$act_lang";
			if(isset($onlynewfaq))
				$linkurl.="&amp;onlynewfaq=$onlynewfaq";
			if(isset($limitprog))
				$linkurl.="&amp;limitprog=$limitprog";
			if(isset($layout))
				$linkurl.="&amp;layout=$layout";
		}
		else
			$linkurl="listprogs.php?id=$act_lang|$layout";
		echo "| <a class=\"actionline\" href=\"$linkurl\"";
		if($navframe==1)
			echo " target=\"_parent\"";
		echo "\">$l_listprogs</a>";
	}
?>
</span>
</td>
</td>
<td class="gototop" align="right" width="1%"><a class="gototop" href="#top">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $actionlinefontsize?>;">
<img class="gototop" src="<?php echo $pagetoppic?>" border="0" align="middle" title="<?php echo $l_top?>" alt="<?php echo $l_top?>"></a>
</span></td>
</tr>
<?php
	if($newtime>0)
	{
?>
<tr BGCOLOR="<?php echo $newinfobgcolor?>" valign="middle"><td colspan="2" class="newfaqline">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $newinfofontsize?>; color: <?php echo $newinfofontcolor?>;">
<img class="newfaqline" src="<?php echo $newpic?>" border="0" align="middle" title="<?php echo $l_newfaq2?>" alt="<?php echo $l_newfaq2?>"> = <?php echo str_replace("{newtime}",$newtime,$l_newfaq)?>
</span></td></tr>
<?php
	}
?>
</table></td></tr></table>
