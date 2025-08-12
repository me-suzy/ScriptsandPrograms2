<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
?>
<tr bgcolor="<?php echo $subheadingbgcolor?>"><td align="center" colspan="3">
<span style="color: <?php echo $SubheadingFontColor?>; font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize2?>; font-weight: bold;">
<?php echo $l_keywordsearch?></span>
</td><td align="right">
<a class="mainaction" href="javascript:openSearchHelp('<?php echo $url_faqengine?>/help/<?php echo $act_lang?>/kbsearch.php?<?php echo "$langvar=$act_lang"?>')">
<?php
if($helppic)
	echo "<img src=\"$helppic\" border=\"0\" title=\"$l_help\" alt=\"$l_help\"></a>";
else
{
	echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $HeadingFontColor;\">";
	echo "[$l_help]</span></a> ";
}
echo "</td></tr>";
if(isset($prog) && ($prog))
{
	$sql = "select * from ".$tableprefix."_programm where progid='$prog' and language='$act_lang'";
	if(!$result=faqe_db_query($sql,$db))
		die("<tr class=\"errorow\"><td>Could not connect to the database (1). ".faqe_db_error());
	if(!$myrow=faqe_db_fetch_array($result))
		die("<tr bgcolor=\"$row_bgcolor\"><td align=\"center\" colspan=\"4\">$l_nosuchprog");
?>
<tr bgcolor="<?php echo $subheadingbgcolor?>"><td align="center" colspan="4">
<span style="color: <?php echo $SubheadingFontColor?>; font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize2?>; font-weight: bold;">
<?php echo $l_program?>: <?php echo $myrow["programmname"]?>
</span></td></tr>
<?php
}
if(isset($search_keywords))
	$displaykeywords=$search_keywords;
else
	$displaykeywords="";
if(!isset($max_results))
	$max_results=0;
?>
<form name="searchform" method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="mode" value="search">
<?php
if(isset($layout))
	echo "<input type=\"hidden\" name=\"layout\" value=\"$layout\">";
if(isset($limitprog))
	echo "<input type=\"hidden\" name=\"limitprog\" value=\"$limitprog\">";
if($navframe==1)
	echo "<input type=\"hidden\" name=\"navframe\" value=\"1\">";
if(isset($prog) && ($prog))
	echo "<input type=\"hidden\" name=\"prog\" value=\"$prog\">";
if(is_konqueror())
	echo "<tr><td></td></tr>";
?>
<tr bgcolor="<?php echo $row_bgcolor?>"><td class=\"searchform\" align="center" colspan="4">
<span style="color: <?php echo $FontColor?>; font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>;">
<input class="faqeinput" type="text" name="search_keywords" value="<?php echo display_encoded($displaykeywords)?>" size="50" maxlength="250">
<a href="javascript:srchTool('search_keywords')"><img src="gfx/srchtool.gif" border="0" align="absmiddle" title="extended input form" alt="extended input form"></a></span></td></tr>
<tr bgcolor="<?php echo $row_bgcolor?>"><td class="searchform" align="center" colspan="4">
<span style="color: <?php echo $FontColor?>; font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>;">
<select class="faqeselect2" name="max_results"><option value="0" <?php if($max_results==0) echo "selected"?>>
<?php echo $l_all?></option>
<?php
for($i=5;$i<51;$i+=5)
{
	echo "<option value=\"$i\"";
	if($i==$max_results)
		echo " selected";
	echo ">$i</option>";
}
?>
</select> <?php echo $l_perpage?></span></td></tr>
<tr bgcolor="<?php echo $actionbgcolor?>"><td class=\"searchform\" align="center" colspan="4">
<input class="faqebutton" type="submit" value="<?php echo $l_dosearch?>"></td></tr></form>
<?php
	echo "<tr bgcolor=\"$actionbgcolor\"><td class=\"actionline\" align=\"center\" colspan=\"4\">";
	echo "<span style=\"color: $FontColor; font-face: $FontFace; font-size: $actionlinefontsize;\">";
	if($navframe==1)
		$linkurl=$url_faqengine."/kbframe.php?";
	else
		$linkurl=$act_script_url."?";
	if(isset($limitprog))
		$linkurl.="limitprog=$limitprog&";
	if(isset($layout))
		$linkurl.="layout=$layout&";
	if(isset($prog) && ($prog))
	{
		echo "<a class=\"actionline\" href=\"".$linkurl."prog=$prog&amp;$langvar=$act_lang&amp;mode=wizard\"";
		if($navframe==1)
			echo " target=\"_parent\"";
		echo ">";
	}
	else
	{
		echo "<a class=\"actionline\" href=\"".$linkurl."$langvar=$act_lang&amp;mode=wizard\"";
		if($navframe==1)
			echo " target=\"_parent\"";
		echo ">";
	}
	echo "$l_wizard</a>";
	echo " | ";
	if(!isset($limitprog))
	{
		echo "<a class=\"actionline\" href=\"".$linkurl."$langvar=$act_lang&amp;mode=proglist\"";
		if($navframe==1)
			echo " target=\"_parent\"";
		echo ">$l_proglist</a>";
	}
	else
	{
		echo "<a class=\"actionline\" href=\"".$linkurl."$langvar=$act_lang&amp;mode=listall&amp;prog=$limitprog\"";
		if($navframe==1)
			echo " target=\"_parent\"";
		echo ">$l_listallarticles</a>";
	}
	echo "</span></td></tr>";
?>
</table></td></tr>
<?php
	if(isset($search_keywords))
	{
?>
<tr><TD BGCOLOR="<?php echo $table_bgcolor?>">
<TABLE BORDER="0" CELLPADDING="<?php echo $tablepadding?>" CELLSPACING="<?php echo $tablespacing?>" WIDTH="100%">
<?php
		$logtxt ="[".date($logdateformat)."] {KB} ".$search_keywords." ";
		echo "<tr bgcolor=\"$subheadingbgcolor\"><td align=\"center\" colspan=\"3\" class=\"subheading\">";
		echo "<span style=\"color: $SubheadingFontColor; font-face: $FontFace; font-size: $FontSize2; font-weight: bold;\">";
		echo "$l_found_articles</span></td></tr>";
		$num_results=0;
		$numcriterias=0;
		if(isset($prog) && ($prog))
			$sql = "select kb.* from ".$tableprefix."_kb_articles kb, ".$tableprefix."_programm prog where prog.progid='$prog' and prog.language='$act_lang' and kb.programm=prog.prognr and ";
		else
			$sql = "select kb.* from ".$tableprefix."_kb_articles kb where ";
		$articlenrs=array();
		$excludearticles=array();
		$musts=array();
		$cans=array();
		$nots=array();
		if($search_keywords)
		{
			$searchterms = explode(" ",$search_keywords);
			foreach($searchterms as $searchstring)
			{
				$qualifier=substr($searchstring,0,1);
				if($qualifier=='-')
				{
					array_push($nots,substr($searchstring,1,strlen($searchstring)-1));
				}elseif ($qualifier=='+')
				{
					array_push($musts,substr($searchstring,1,strlen($searchstring)-1));
				}
				else
				{
					array_push($cans,$searchstring);
				}
			}
			if(count($nots)>0)
			{
				$numcriterias++;
				$tempsql="select kb.articlenr from ".$tableprefix."_kb_keywords kb, ".$tableprefix."_keywords kw where kb.keywordnr=kw.keywordnr";
				for($i=0;$i<count($nots);$i++)
				{
					$tempsql .=" and ";
					$tempsql.="kw.keyword ='".$nots[$i]."'";
				}
				if(!$result = faqe_db_query($tempsql, $db))
					die("<tr class=\"errorow\"><td>Could not connect to the database (2).".faqe_db_error());
				while($temprow=faqe_db_fetch_array($result))
				{
					array_push($excludearticles,$temprow["articlenr"]);
				}
			}
			if(count($musts)>0)
			{
				$numcriterias++;
				$tempsql="select kb.articlenr from ".$tableprefix."_kb_keywords kb, ".$tableprefix."_keywords kw where kb.keywordnr=kw.keywordnr";
				for($i=0;$i<count($musts);$i++)
				{
					$tempsql .= " and ";
					$tempsql .="kw.keyword='".$musts[$i]."'";
				}
				if(!$result = faqe_db_query($tempsql, $db))
					die("<tr class=\"errorow\"><td>Could not connect to the database (3).".faqe_db_error());
				while($temprow=faqe_db_fetch_array($result))
				{
					if(!in_array($temprow["articlenr"],$excludearticles))
						array_push($articlenrs,$temprow["articlenr"]);
				}
			}
			if((count($cans)>0) && (count($musts)<1))
			{
				$numcriterias++;
				$tempsql="select kb.articlenr from ".$tableprefix."_kb_keywords kb, ".$tableprefix."_keywords kw where kb.keywordnr=kw.keywordnr and (";
				$first=1;
				for($i=0;$i<count($cans);$i++)
				{
					if($first==1)
						$first=0;
					else
						$tempsql .=" or ";
					$tempsql.="kw.keyword='".$cans[$i]."'";
				}
				$tempsql.=")";
				if(!$result = faqe_db_query($tempsql, $db)) {
					die("<tr class=\"errorow\"><td>Could not connect to the database (4).".faqe_db_error());
				}
				while($temprow=faqe_db_fetch_array($result))
				{
					if(!in_array($temprow["articlenr"],$excludearticles))
						array_push($articlenrs,$temprow["articlenr"]);
				}
			}
		}
		if($numcriterias<1)
		{
			$num_results=0;
			echo "<tr>";
			echo "<td bgcolor=\"$group_bgcolor\" ALIGN=\"CENTER\" colspan=\"3\" class=\"error\">";
			echo "<span style=\"color: $FontColor; font-face: $FontFace; font-size: $FontSize1;\">";
			echo $l_searchnoquery;
			echo "</span></td></tr>";
		}
		else
		{
			if(count($articlenrs)>0)
			{
				$sql .=" kb.articlenr in (";
				$first=1;
				for($i=0;$i<count($articlenrs);$i++)
				{
					if($first==1)
						$first=0;
					else
						$sql.=", ";
					$sql.=$articlenrs[$i];
				}
				$sql .=") group by kb.articlenr ";
				switch($kbsortmethod)
				{
					case 0:
						$sql.="order by kb.lastedited desc";
						break;
					case 1:
						$sql.="order by kb.displaypos asc";
						break;
					case 2:
						$sql.="order by kb.heading asc";
						break;
				}
				if(!$result = faqe_db_query($sql, $db)) {
					die("<tr class=\"errorow\"><td>Could not connect to the database (5).".faqe_db_error());
				}
				$num_results = faqe_db_num_rows($result);
			}
			else
				$num_results = 0;
			$logtxt.="{".$num_results."} ";
			if($num_results<1)
			{
				echo "<td bgcolor=\"$group_bgcolor\" ALIGN=\"CENTER\" colspan=\"3\" class=\"listrow\">";
				echo "<span style=\"color: $FontColor; font-face: $FontFace; font-size: $FontSize1;\">";
				echo $l_searchnonefound;
				echo "</span></td></tr>";
			}
			else
			{
				if($max_results>0)
				{
					if(isset($start) && ($start>0) && ($num_results>$max_results))
					{
						$sql .=" limit $start,$max_results";
					}
					else
					{
						$sql .=" limit $max_results";
						$start=0;
					}
					if(!$result = faqe_db_query($sql, $db))
						die("<tr class=\"errorow\"><td>Could not connect to the database (6).".faqe_db_error());
				}
				echo "<td bgcolor=\"$group_bgcolor\" ALIGN=\"CENTER\" colspan=\"3\" class=\"listrow\">";
				echo "<span style=\"color: $FontColor; font-face: $FontFace; font-size: $FontSize5;\">";
				echo "$num_results $l_searchnumresults";
				if(($max_results>0) && ($num_results>$max_results))
				{
					if(($max_results+$start)>$num_results)
						$displayresults=$num_results;
					else
						$displayresults=($max_results+$start);
					echo "<br>($l_showing: ".($start+1)." - $displayresults)";
				}
				echo "</span></td></tr>";
				WHILE ($myrow=faqe_db_fetch_array($result))
				{
					list($year, $month, $day) = explode("-", $myrow["lastedited"]);
					if($month>0)
						$displaydate=date($dateformat,mktime(0,0,0,$month,$day,$year));
					else
						$displaydate="";
					echo "<tr bgcolor=\"$row_bgcolor\"><td class=\"listrow\" align=\"center\" width=\"10%\">";
					echo "<span style=\"color: $FontColor; font-face: $FontFace; font-size: $FontSize1;\">";
					echo $myrow["articlenr"];
					echo "</span></td>";
					$tmpsql = "select * from ".$tableprefix."_programm where prognr=".$myrow["programm"];
					if(!$tmpresult = faqe_db_query($tmpsql, $db))
						die("<tr class=\"errorow\"><td>Could not connect to the database (7).".faqe_db_error());
					if(!$tmprow=faqe_db_fetch_array($tmpresult))
						die("<tr class=\"errorow\"><td>no such program ".$myrow["programm"]);
					$myprog=$tmprow["progid"];
					$myprogname=display_encoded($tmprow["programmname"]);
					if($navframe==1)
						$backurl=$url_faqengine."/kbframe.php";
					else
						$backurl=$act_script_url;
					$backurl.="?mode=search&$langvar=$act_lang";
					if(isset($search_keywords))
						$backurl.="&search_keywords=$search_keywords";
					if(isset($max_results))
						$backurl.="&max_results=$max_results";
					if(isset($limitprog))
						$backurl.="&limitprog=$limitprog";
					if(isset($layout))
						$backurl.="&layout=$layout";
					$backurl=urlencode($backurl);
					if($navframe==1)
						$listlink=$url_faqengine."/kbframe.php";
					else
						$listlink=$act_script_url;
					$listlink.="?$langvar=$act_lang&amp;mode=display&amp;kbnr=".$myrow["articlenr"]."&amp;prog=$myprog";
					if(isset($limitprog))
						$listlink.="&amp;limitprog=$limitprog";
					if(isset($layout))
						$listlink.="&amp;layout=$layout";
					$listlink.="&amp;backurl=$backurl";
					echo "<td width=\"80%\" align=\"left\" class=\"listlink\"";
					if($hovercells==1)
					{
						echo " onMouseOver=\"this.style.backgroundColor='$activcellcolor';\" onMouseOut=\"this.style.backgroundColor='$row_bgcolor'\"";
						if($navframe==0)
							echo " onclick=\"window.location.href='$listlink'\"";
					}
					echo ">";
					echo "<span style=\"color: $FontColor; font-face: $FontFace; font-size: $FontSize1;\">";
					echo "<a class=\"listlink\" href=\"$listlink\"";
					if($navframe==1)
						echo " target=\"_parent\"";
					echo ">";
					if(bittst($kbsearchoptions,BIT_1))
						echo "<i>".$myprogname.":</i>";
					if(bittst($kbsearchoptions,BIT_2))
					{
						if($myrow["category"]>0)
						{
							$tmpsql2="select * from ".$tableprefix."_kb_cat where catnr=".$myrow["category"];
							if(!$tmpresult2 = faqe_db_query($tmpsql2, $db))
								die("<tr class=\"errorow\"><td>Could not connect to the database (8).".faqe_db_error());
							if(!$tmprow2=faqe_db_fetch_array($tmpresult2))
								die("<tr class=\"errorow\"><td>No such category ".$myrow["category"]);
							echo "<i>".display_encoded($tmprow2["catname"]).":</i>";
						}
					}
					echo undo_html_ampersand(stripslashes($myrow["heading"]));
					echo "</a></span></td><td width=\"10%\" class=\"listrow\" align=\"center\">";
					echo "<span style=\"color: $FontColor; font-face: $FontFace; font-size: $FontSize1;\">";
					echo $displaydate;
					echo "</span></td></tr>";
				}
				if(($max_results>0) && ($num_results>$max_results))
				{
					echo "<tr><td align=\"center\" class=\"pagenav\" colspan=\"3\" bgcolor=\"$actionbgcolor\">";
					echo "<span style=\"color: $FontColor; font-face: $FontFace; font-size: $actionlinefontsize;\">";
					echo "$l_page ";
					for($i=1;$i<($num_results/$max_results)+1;$i++)
					{
						if(floor(($start+$max_results)/$max_results)!=$i)
						{
							echo "<a class=\"pagenav\" href=\"$act_script_url?$langvar=$lang&amp;mode=$mode&amp;start=".(($i-1)*$max_results);
							echo "&amp;max_results=$max_results";
							if(isset($layout))
								echo "&amp;layout=$layout";
							if(isset($limitprog))
								echo "&amp;limitprog=$limitprog";
							echo "&amp;search_keywords=".urlencode($search_keywords);
							echo "\">$i</a> ";
						}
						else
							echo "$i ";
					}
					echo "</span></td></tr>";
				}
			}
		}
		echo "</table></td></tr>";
		if($dosearchlog==1)
		{
			$logfile=@fopen($path_logfiles."/search.log","a");
			if($logfile)
			{
				fwrite($logfile,$logtxt.$crlf);
				fclose($logfile);
			}
		}
	}
	echo "</table>";
	if(($enablelanguageselector==1) && !isset($category) && !isset($search_keywords))
	{
		echo "<table style=\"clear:both\" width=\"$TableWidth\" align=\"$tblalign\" border=\"0\">";
		echo "<form method=\"post\" action=\"$form_url\" target=\"$form_target\">";
		echo "<tr bgcolor=\"$group_bgcolor\"><td class=\"langselect\" align=\"center\">";
		echo "<input type=\"hidden\" name=\"mode\" value=\"$mode\">";
		if(isset($prog) && $prog)
			echo "<input type=\"hidden\" name=\"prog\" value=\"$prog\">";
		if(isset($limitprog))
			echo "<input type=\"hidden\" name=\"limitprog\" value=\"$limitprog\">";
		if(isset($layout))
			echo "<input type=\"hidden\" name=\"layout\" value=\"$layout\">";
		echo "<span style=\"color: $FontColor; font-face: $FontFace; font-size: $langselectfontsize;\">";
		echo "$l_changelang: ".language_select($act_lang,$langvar,"./language","langselect");
		echo "&nbsp;&nbsp;&nbsp;<input class=\"langselect\" type=\"submit\" value=\"$l_ok\">";
		echo "</span></td></tr></form></table>";
	}
?>