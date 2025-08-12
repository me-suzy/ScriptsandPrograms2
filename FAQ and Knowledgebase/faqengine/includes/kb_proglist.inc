<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
echo "</table></td></tr>";
echo "<tr><TD BGCOLOR=\"$table_bgcolor\">";
echo "<TABLE BORDER=\"0\" CELLPADDING=\"$tablepadding\" CELLSPACING=\"$tablespacing\" WIDTH=\"100%\">";
?>
<tr><td bgcolor="<?php echo $subheadingbgcolor?>" align="center" colspan="4">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize2?>; color: <?php echo $SubheadingFontColor?>; font-weight: bold;">
<?php echo $l_proglist?></span></td></tr>
<tr bgcolor="<?php echo $group_bgcolor?>">
<td colspan="2" align="center">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $tabledescfontsize?>; color: <?php echo $TableDescFontColor?>; font-weight: bold;">
<?php echo $l_progname?></span></td>
<td width="15%">&nbsp;</td><td width="15%">&nbsp;</td></tr>
<?php
	$actprog=0;
	$displayedprog=0;
	$sql = "select * from ".$tableprefix."_programm where language='$act_lang' order by displaypos asc";
	if(!$result = faqe_db_query($sql, $db))
	    die("<tr bgcolor=\"#cccccc\"><td>Could not connect to the database.".faqe_db_error());
	if($maxentries>0)
	{
		include_once("./includes/faq_pagenav.inc");
		$totalprogs=faqe_db_num_rows($result);
	}
	else
		$totalprogs=0;
	if (!$myrow = faqe_db_fetch_array($result))
		die("<tr bgcolor=\"#cccccc\"><td>$l_noprogsdefined");
	do{
		$actprog++;
		if(($maxentries>0) && (($actprog-1 >= ($start+$maxentries)) ))
			continue;
		$displayedprog++;
		if($actprog-1<$start)
			continue;
		echo "<tr bgcolor=\"$row_bgcolor\"><td class=\"listrow\" align=\"left\" width=\"68%\">";
		echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
		echo display_encoded($myrow["programmname"])."</span></td>";
		if(!$myrow["description"])
		{
			echo "<td class=\"proginfolink\" bgcolor=\"$row_bgcolor\" width=\"2%\">";
			echo "&nbsp;";
		}
		else
		{
			$proginfolink="proginfo.php?$langvar=$act_lang&prog=".$myrow["prognr"];
			if(isset($layout))
				$proginfolink.="&layout=$layout";
			echo "<td width=\"2%\" align=\"center\" bgcolor=\"$row_bgcolor\" class=\"proginfolink\"";
			if($hovercells==1)
				echo " onMouseOver=\"this.style.backgroundColor='$activcellcolor';\" onMouseOut=\"this.style.backgroundColor='$row_bgcolor'\" onclick=\"openProginfo('$proginfolink')\"";
			echo "><a class=\"proginfolink\" href=\"javascript:openProginfo('$proginfolink')\"><img class=\"proginfolink\" src=\"$proginfopic\" border=\"0\" title=\"$l_proginfo\" alt=\"$l_proginfo\"></a>";
		}
		echo "</td>";
		if($navframe==1)
			$link=$url_faqengine."/kbframe.php";
		else
			$link=$act_script_url;
		$link.="?$langvar=$act_lang&amp;mode=catlist&amp;prog=".$myrow["progid"];
		if(isset($limitprog))
			$link.="&amp;limitprog=$limitprog";
		if(isset($layout))
			$link.="&amp;layout=$layout";
		echo "<td width=\"15%\" align=\"center\" class=\"listlink\"";
		if($hovercells==1)
		{
			echo " onMouseOver=\"this.style.backgroundColor='$activcellcolor';\" onMouseOut=\"this.style.backgroundColor='$row_bgcolor'\"";
			if($navframe==0)
				echo " onclick=\"window.location.href='$link'\"";
		}
		echo ">";
		echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
		echo "<a class=\"listlink\" href=\"$link\"";
		if($navframe==1)
			echo " target=\"_parent\"";
		echo ">";
		echo "$l_listcats</a></span></td>";
		if($navframe==1)
			$link=$url_faqengine."/kbframe.php";
		else
			$link=$act_script_url;
		$link.="?$langvar=$act_lang&amp;mode=listall&amp;prog=".$myrow["progid"];
		if(isset($limitprog))
			$link.="&amp;limitprog=$limitprog";
		if(isset($layout))
			$link.="&amp;layout=$layout";
		echo "<td width=\"15%\" align=\"center\" class=\"listlink\"";
		if($hovercells==1)
		{
			echo " onMouseOver=\"this.style.backgroundColor='$activcellcolor';\" onMouseOut=\"this.style.backgroundColor='$row_bgcolor'\"";
			if($navframe==0)
				echo " onclick=\"window.location.href='$link'\"";
		}
		echo ">";
		echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
		echo "<a class=\"listlink\" href=\"$link\"";
		if($navframe==1)
			echo " target=\"_parent\"";
		echo ">";
		echo "$l_listallarticles</a></span>";
		echo "</td></tr>";
	}while($myrow=faqe_db_fetch_array($result));
	echo "</table></td></tr>";
?>
<tr><TD BGCOLOR="<?php echo $table_bgcolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<?php
	if(($maxentries>0) && ($totalprogs>$maxentries))
	{
		$url_start="$act_script_url?$langvar=$act_lang&amp;mode=proglist";
		echo "<tr bgcolor=\"$actionbgcolor\"><td class=\"pagenav\" align=\"center\" valign=\"middle\" colspan=\"6\">";
		faq_pagenav($start, $maxentries, $totalprogs, $displayedprog, $url_start);
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
<td class="actionline" align="center" valign="middle" width="99%">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $actionlinefontsize?>; color: <?php echo $FontColor?>;">
<a class="actionline" href="<?php echo $linkbase?><?php echo "$langvar=$act_lang"?>&amp;mode=search" target="<?php echo $linktarget?>"><?php echo $l_keywordsearch?></a>
| <a class="actionline" href="<?php echo $linkbase?><?php echo "$langvar=$act_lang"?>&amp;mode=wizard" target="<?php echo $linktarget?>"><?php echo $l_wizard?></a>
</span></td>
<td class="gototop" align="right" width="1%"><a class="gototop" href="#top">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $actionlinefontsize?>;"><img class="gototop" src="<?php echo $pagetoppic?>" border="0" align="middle" title="<?php echo $l_top?>" alt="<?php echo $l_top?>"></span></a></td>
</tr>
</table></td></tr></table>
<?php
	if($enablelanguageselector==1)
	{
		echo "<table style=\"clear:both\" width=\"$TableWidth\" align=\"$tblalign\" border=\"0\">";
		echo "<form method=\"post\" action=\"$form_url\" target=\"$form_target\">";
		if(isset($layout))
			echo "<input type=\"hidden\" name=\"layout\" value=\"$layout\">";
		if(isset($limitprog))
			echo "<input type=\"hidden\" name=\"limitprog\" value=\"$limitprog\">";
		echo "<input type=\"hidden\" name=\"mode\" value=\"$mode\">";
		echo "<tr bgcolor=\"$group_bgcolor\"><td class=\"langselect\" align=\"center\">";
		echo "<span style=\"color: $FontColor; font-face: $FontFace; font-size: $langselectfontsize;\">";
		echo "$l_changelang: ".language_select($act_lang,$langvar,"./language","langselect");
		echo "&nbsp;&nbsp;&nbsp;<input class=\"langselect\" type=\"submit\" value=\"$l_ok\">";
		echo "</span></td></tr></form></table>";
	}
?>