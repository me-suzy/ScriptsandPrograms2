<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
	if(!isset($prog) or !isset($catnr))
		die("<tr bgcolor=\"#cccccc\"><td>$l_callingerror");
	echo "</table></td></tr>";
	echo "<tr><TD BGCOLOR=\"$table_bgcolor\">";
	echo "<TABLE BORDER=\"0\" CELLPADDING=\"$tablepadding\" CELLSPACING=\"$tablespacing\" WIDTH=\"100%\">";
	$actfaq=0;
	$displayedfaq=0;
	if($catnr>0)
	{
		$sql = "select * from ".$tableprefix."_kb_cat where (catnr='$catnr')";
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
		$catname=display_encoded($myrow["catname"]);
	}
	else
		$catname=$l_withoutcategory;
	$sql2 = "select * from ".$tableprefix."_programm where (progid='$prog') and (language='$act_lang')";
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
	echo "<TR BGCOLOR=\"$subheadingbgcolor\" ALIGN=\"LEFT\">";
	echo "<td";
	if($proginfoavail==0)
		echo " colspan=\"2\"";
	else
		echo " width=\"98%\"";
	echo " align=\"center\">";
	echo "<span style=\"font-face: $FontFace; font-size: $FontSize2; color: $SubheadingFontColor; font-weight: bold;\">";
	echo "$l_singlecat: <i>";
	if($progname)
		echo stripslashes($progname).": ";
	echo $catname;
	echo "</i></span></td>";
	if($proginfoavail==1)
	{
		$proginfolink="proginfo.php?$langvar=$act_lang&prog=$prognr";
		if(isset($layout))
			$proginfolink.="&layout=$layout";
		echo "<td width=\"2%\" align=\"center\" class=\"proginfolink\"";
		if($hovercells==1)
			echo " onMouseOver=\"this.style.backgroundColor='$activcellcolor';\" onMouseOut=\"this.style.backgroundColor='$subheadingbgcolor'\" onclick=\"openProginfo('$proginfolink')\"";
		echo "><a class=\"proginfolink\" href=\"javascript:openProginfo('$proginfolink')\"><img src=\"$proginfopic\" border=\"0\" title=\"$l_proginfo\" alt=\"$l_proginfo\"></a></td>";
	}
	echo "</tr>";
	if($maxentries>0)
	{
		include_once("./includes/faq_pagenav.inc");
		$sql = "select * from ".$tableprefix."_kb_articles where (category=$catnr) and (programm=$prognr)";
		if(!$result = faqe_db_query($sql, $db))
		{
			echo "<tr><td bgcolor=\"$heading_bgcolor\">";
			die("Could not connect to the database.");
		}
		$totalfaqs=faqe_db_num_rows($result);
	}
	$sql = "select * from ".$tableprefix."_kb_articles where (category=$catnr) and (programm=$prognr) and subcategory=0";
	$sql.=" order by displaypos asc";
	if(!$result = faqe_db_query($sql, $db))
	{
		echo "<tr><td bgcolor=\"$heading_bgcolor\">";
		die("Could not connect to the database.");
	}
	$numfaqs=faqe_db_num_rows($result);
	if(($maxentries<=0) || (($maxentries>0) && ($actfaq+$numfaqs>$start) && ($actfaq+1<$start+$maxentries)))
	{
		echo "<TR BGCOLOR=\"$subcatbgcolor\" ALIGN=\"LEFT\">";
		echo "<td align=\"left\" colspan=\"2\">";
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
		echo $l_nosubcat;
		echo "</span></td></tr>";
		if (!$myrow = faqe_db_fetch_array($result))
		{
			echo "<TR BGCOLOR=\"$row_bgcolor\" ALIGN=\"LEFT\">";
			echo "<td class=\"listrow\" align=\"center\" colspan=\"2\">";
			echo "<span style=\"font-face: $FontFace; font-size: $FontSize1;\">$l_noentries";
			echo "</span></td></tr>";
		}
		else
		{
			do {
				$actfaq++;
				if(($maxentries>0) && (($actfaq-1 >= ($start+$maxentries)) ))
					continue;
				$displayedfaq++;
				if($actfaq-1<$start)
					continue;
				$act_id=$myrow["articlenr"];
				echo "<TR BGCOLOR=\"$row_bgcolor\" ALIGN=\"LEFT\">";
				if($navframe==1)
					$backurl=$url_faqengine."/kbframe.php?";
				else
					$backurl=$act_script_url."?";
				if(isset($limitprog))
					$backurl.="limitprog=$limitprog&";
				$backurl=urlencode($backurl."mode=category&$langvar=$act_lang&prog=$prog&catnr=$catnr");
				if($navframe==1)
					$link=$url_faqengine."/kbframe.php";
				else
					$link=$act_script_url;
				$link.="?mode=display&amp;kbnr=$act_id&amp;$langvar=$act_lang&amp;prog=$prog";
				if(isset($limitprog))
					$link.="&amp;limitprog=$limitprog";
				$link.="&amp;backurl=$backurl";
				if(isset($layout))
					$link.="&amp;layout=$layout";
				echo "<td colspan=\"2\" align=\"left\" class=\"listlink\"";
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
			} while($myrow = faqe_db_fetch_array($result));
		}
	}
	else
	{
		if($actfaq<$start+$maxentries)
			$displayedfaq+=$numfaqs;
		$actfaq+=$numfaqs;
	}
	$sql = "select * from ".$tableprefix."_kb_subcat where category=$catnr order by displaypos asc";
	if(!$result = faqe_db_query($sql, $db))
	{
		echo "<tr><td bgcolor=\"$heading_bgcolor\">";
	   	die("Could not connect to the database.");
	}
	if ($myrow = faqe_db_fetch_array($result))
	{
		do {
			$datasql = "select * from ".$tableprefix."_kb_articles where (category=$catnr) and (programm=$prognr) and subcategory=".$myrow["catnr"];
			$datasql.=" order by displaypos asc";
			if(!$dataresult = faqe_db_query($datasql, $db))
			{
				echo "<tr><td bgcolor=\"$heading_bgcolor\">";
		    		die("Could not connect to the database.");
		    	}
		    	$numfaqs=faqe_db_num_rows($dataresult);
			if(($maxentries<=0) || (($maxentries>0) && ($actfaq+$numfaqs>$start) && ($actfaq+1<$start+$maxentries)))
			{
				echo "<TR BGCOLOR=\"$subcatbgcolor\" ALIGN=\"LEFT\">";
				echo "<td class=\"subcat\" align=\"left\" colspan=\"2\">";
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
				echo display_encoded($myrow["heading"]);
				echo "</span></td></tr>";
				if (!$datarow = faqe_db_fetch_array($dataresult))
				{
					echo "<TR BGCOLOR=\"$row_bgcolor\" ALIGN=\"LEFT\">";
					echo "<td class=\"listrow\" align=\"center\" colspan=\"2\">";
					echo "<span style=\"font-face: $FontFace; font-size: $FontSize5;\">$l_noentries";
					echo "</span></td></tr>";
				}
				else
				{
					do {
						$actfaq++;
						if(($maxentries>0) && (($actfaq-1 >= ($start+$maxentries)) ))
							continue;
						$displayedfaq++;
						if($actfaq-1<$start)
							continue;
						$act_id=$datarow["articlenr"];
						echo "<TR BGCOLOR=\"$row_bgcolor\" ALIGN=\"LEFT\">";
						if($navframe==1)
							$backurl=$url_faqengine."/kbframe.php?";
						else
							$backurl=$act_script_url."?";
						if(isset($limitprog))
							$backurl.="limitprog=$limitprog&";
						$backurl=urlencode($backurl."mode=category&$langvar=$act_lang&prog=$prog&catnr=$catnr");
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
						echo "<td colspan=\"2\" align=\"left\" class=\"listlink\"";
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
					} while($datarow = faqe_db_fetch_array($dataresult));
				}
			}
			else
			{
				if($actfaq<$start+$maxentries)
					$displayedfaq+=$numfaqs;
				$actfaq+=$numfaqs;
			}
		} while($myrow = faqe_db_fetch_array($result));
	}
?>
</table></td></tr>
<tr><TD BGCOLOR="<?php echo $table_bgcolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<?php
	if(($maxentries>0) && ($totalfaqs>$maxentries))
	{
		$url_start="$act_script_url?$langvar=$act_lang&amp;mode=category&amp;catnr=$catnr&amp;prog=$prog";
		if(isset($limitprog))
			$url_start.="&amp;limitprog=$limitprog";
		if($navframe==1)
			$url_start.="&amp;navframe=1";
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
| <a class="actionline" href="<?php echo $linkbase?>mode=listall&amp;prog=<?php echo $prog?>&amp;<?php echo "$langvar=$act_lang"?>" target="<?php echo $linktarget?>"><?php echo $l_listallarticles?></a>
<?php
if(!isset($limitprog))
	echo " | <a class=\"actionline\" href=\"".$linkbase."mode=proglist&amp;$langvar=$act_lang\" target=\"$linktarget\">$l_listprogs</a>";
?>
| <a class="actionline" href="<?php echo $linkbase?><?php echo "$langvar=$act_lang"?>&amp;mode=search" target="<?php echo $linktarget?>"><?php echo $l_keywordsearch?></a>
| <a class="actionline" href="<?php echo $linkbase?><?php echo "$langvar=$act_lang"?>&amp;mode=wizard" target="<?php echo $linktarget?>"><?php echo $l_wizard?></a>
</span></td>
<td class="gototop" align="right" width="1%"><a class="gototop" href="#top">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $actionlinefontsize?>;"><img class="gototop" src="<?php echo $pagetoppic?>" border="0" align="middle" title="<?php echo $l_top?>" alt="<?php echo $l_top?>"></span></a></td>
</tr></table></td></tr></table>
