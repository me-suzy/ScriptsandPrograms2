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
	$proginfoavail=0;
	$prognr=0;
	$progname=$l_undefined;
}
echo "<tr bgcolor=\"$subheadingbgcolor\">";
echo "<TD ALIGN=\"CENTER\" VALIGN=\"MIDDLE\"";
if($proginfoavail==1)
	echo "WIDTH=\"98%\"";
echo ">";
?>
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize2?>; color: <?php echo $SubheadingFontColor?>; font-weight: bold;">
<?php echo $l_catlist?><br>
<?php echo "$l_program: $progname"?></span>
</td>
<?php
if($proginfoavail==1)
{
	$proginfolink="proginfo.php?$langvar=$act_lang&prog=$prognr";
	if(isset($layout))
		$proginfolink.="&layout=$layout";
	echo "<td valign=\"middle\" width=\"2%\" align=\"center\" bgcolor=\"$subheadingbgcolor\" class=\"proginfolink\"";
	if($hovercells==1)
		echo " onMouseOver=\"this.style.backgroundColor='$activcellcolor';\" onMouseOut=\"this.style.backgroundColor='$subheadingbgcolor'\" onclick=\"openProginfo('$proginfolink')\"";
	echo "><a class=\"proginfolink\" href=\"javascript:openProginfo('$proginfolink')\"><img src=\"$proginfopic\" border=\"0\" title=\"$l_proginfo\" alt=\"$l_proginfo\"></a></td>";
}
?>
</tr></table></td></tr>
<tr><TD BGCOLOR="<?php echo $table_bgcolor?>">
<TABLE BORDER="0" CELLPADDING="<?php echo $tablepadding?>" CELLSPACING="<?php echo $tablespacing?>" WIDTH="100%">
<?php
$actcat=0;
$displayedcat=0;
$sql = "select * from ".$tableprefix."_kb_cat where (programm=$prognr) order by displaypos asc";
if(!$result = faqe_db_query($sql, $db))
{
	echo "<tr><td bgcolor=\"$heading_bgcolor\">";
	die("Could not connect to the database.");
}
if($maxentries>0)
{
	include_once("./includes/faq_pagenav.inc");
	$totalcats=faqe_db_num_rows($result)+1;
}
else
	$totalcats=0;
if ($myrow = faqe_db_fetch_array($result))
{
	do {
		$actcat++;
		if(($maxentries>0) && (($actcat-1 >= ($start+$maxentries)) ))
			continue;
		$displayedcat++;
		if($actcat-1<$start)
			continue;
		$tempsql="select count(articlenr) as numarticles from ".$tableprefix."_kb_articles where category=".$myrow["catnr"]." and programm=$prognr";
		if(!$tempresult=faqe_db_query($tempsql,$db))
		{
			echo "<tr><td bgcolor=\"$heading_bgcolor\">";
			die("Could not connect to the database.");
		}
		if($temprow=faqe_db_fetch_array($tempresult))
			$numarticles=$temprow["numarticles"];
		else
			$numarticles=0;
		$act_id=$myrow["catnr"];
		echo "<TR BGCOLOR=\"$row_bgcolor\" ALIGN=\"LEFT\">";
		if($navframe==1)
			$link=$url_faqengine."/kbframe.php";
		else
			$link=$act_script_url;
		$link.="?mode=category&amp;catnr=$act_id&amp;prog=$prog&amp;$langvar=$act_lang";
		if(isset($limitprog))
			$link.="&amp;limitprog=$limitprog";
		if(isset($layout))
			$link.="&amp;layout=$layout";
		if($numarticles>0)
		{
			echo "<td width=\"95%\" align=\"left\" class=\"listlink\"";
			if($hovercells==1)
			{
				echo " onMouseOver=\"this.style.backgroundColor='$activcellcolor';\" onMouseOut=\"this.style.backgroundColor='$row_bgcolor'\"";
				if($navframe==0)
					echo " onclick=\"window.location.href='$link'\"";
			}
			echo ">";
		}
		else
			echo "<td class=\"listrow\" width=\"95%\">";
		echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
		if($numarticles>0)
		{
			echo "<a class=\"listlink\" href=\"$link\"";
			if($navframe==1)
				echo " target=\"_parent\"";
			echo ">";
		}
		echo display_encoded($myrow["catname"]);
		if($numarticles>0)
			echo "</a>";
		echo"</span></td>";
		echo "<td class=\"listrow\" width=\"5%\" align=\"right\">";
		echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
		echo $numarticles;
		echo "</span></td></tr>";
	} while($myrow = faqe_db_fetch_array($result));
}
$actcat++;
if(($maxentries<=0) || ( ($maxentries>0) && ($actcat < ($start+$maxentries)) && ($actcat>$start) ))
{
	$displayedcat++;
	$tempsql="select count(articlenr) as numarticles from ".$tableprefix."_kb_articles where category=0 and programm=$prognr";
	if(!$tempresult=faqe_db_query($tempsql,$db))
	{
		echo "<tr><td bgcolor=\"$heading_bgcolor\">";
		die("Could not connect to the database.");
	}
	if($temprow=faqe_db_fetch_array($tempresult))
		$numarticles=$temprow["numarticles"];
	else
		$numarticles=0;
	echo "<TR BGCOLOR=\"$row_bgcolor\" ALIGN=\"LEFT\">";
	if($navframe==1)
		$link=$url_faqengine."/kbframe.php";
	else
		$link=$act_script_url;
	$link.="?mode=category&amp;catnr=0&amp;prog=$prog&amp;$langvar=$act_lang";
	if(isset($limitprog))
		$link.="&amp;limitprog=$limitprog";
	if(isset($layout))
		$link.="&amp;layout=$layout";
	if($numarticles>0)
	{
		echo "<td width=\"95%\" align=\"left\" class=\"listlink\"";
		if($hovercells==1)
		{
			echo " onMouseOver=\"this.style.backgroundColor='$activcellcolor';\" onMouseOut=\"this.style.backgroundColor='$row_bgcolor'\"";
			if($navframe==0)
				echo " onclick=\"window.location.href='$link'\"";
		}
		echo ">";
	}
	else
		echo "<td class=\"listrow\" width=\"95%\">";
	echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
	if($numarticles>0)
	{
		echo "<a class=\"listlink\" href=\"$link\"";
		if($navframe==1)
			echo " target=\"_parent\"";
		echo ">";
	}
	echo $l_withoutcategory;
	if($numarticles>0)
	echo "</a>";
	echo"</span></td>";
	echo "<td class=\"listrow\" width=\"5%\" align=\"right\">";
	echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
	echo $numarticles;
	echo "</span></td></tr>";
}
if($displayedcat<1)
{
	echo "<tr><td class=\"listrow\" bgcolor=\"$group_bgcolor\" align=\"center\">";
	echo "<span style=\"font-face: $FontFace; font-size: $FontSize5; color: $FontColor;\">";
	echo "$l_nocategories</span></tr></td>";
}
?>
</td></tr></table></td></tr>
<tr><TD BGCOLOR="<?php echo $table_bgcolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<?php
if(($maxentries>0) && ($totalcats>$maxentries))
{
	$url_start="$act_script_url?$langvar=$act_lang&amp;mode=catlist&amp;prog=$prog";
	if(isset($limitprog))
		$url_start.="&amp;limitprog=$limitprog";
	if($navframe==1)
		$url_start.="&amp;navframe=1";
	echo "<tr bgcolor=\"$actionbgcolor\"><td class=\"pagenav\" align=\"center\" valign=\"middle\" colspan=\"6\">";
	faq_pagenav($start, $maxentries, $totalcats, $displayedcat, $url_start);
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
<a class="actionline" href="<?php echo $linkbase?>mode=listall&amp;prog=<?php echo $prog?>&amp;<?php echo "$langvar=$act_lang"?>" target="<?php echo $linktarget?>"><?php echo $l_listallarticles?></a>
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
<?php
	if($enablelanguageselector==1)
	{
		echo "<table style=\"clear:both\" width=\"$TableWidth\" align=\"$tblalign\" border=\"0\">";
		echo "<form method=\"post\" action=\"$form_url\" target=\"$form_target\">";
		if(isset($limitprog))
			echo "<input type=\"hidden\" name=\"limitprog\" value=\"$limitprog\">";
		if(isset($layout))
			echo "<input type=\"hidden\" name=\"layout\" value=\"$layout\">";
		echo "<input type=\"hidden\" name=\"mode\" value=\"$mode\">";
		echo "<input type=\"hidden\" name=\"prog\" value=\"$prog\">";
		echo "<tr bgcolor=\"$group_bgcolor\"><td align=\"center\" class=\"langselect\">";
		echo "<span style=\"color: $FontColor; font-face: $FontFace; font-size: $langselectfontsize;\">";
		echo "$l_changelang: ".language_select($act_lang,$langvar,"./language","langselect");
		echo "&nbsp;&nbsp;&nbsp;<input class=\"langselect\" type=\"submit\" value=\"$l_ok\">";
		echo "</span></td></tr></form></table>";
	}
?>