<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require_once('../config.php');
require_once('./auth.php');
if(!isset($$langvar) || !$$langvar)
	$act_lang=$admin_lang;
else
	$act_lang=$$langvar;
include_once('./language/lang_'.$act_lang.'.php');
$page_title=$l_faqsearch;
$page="faqsearch";
if(!isset($searchwords))
	$searchwords="";
if(!isset($searchtype))
	$searchtype=1;
if(!isset($limitprog))
	$limitprog=-1;
require_once('./heading.php');
echo "<table align=\"center\" width=\"80%\" CELLPADDING=\"1\" CELLSPACING=\"0\" border=\"0\" valign=\"top\">";
echo "<tr><TD BGCOLOR=\"#000000\">";
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<form name="inputform" method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<?php
if($sessid_url)
	echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
if(is_konqueror())
	echo "<tr><td></td></tr>";
?>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_searchwords?>:</td><td><input class="faqeinput" type="text" name="searchwords" size="30" maxlength="255" value="<?php echo $searchwords?>"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_searchtype?>:</td><td>
<input type="radio" name="searchtype" value="1" <?php if($searchtype==1) echo "checked"?>><?php echo $l_search_fulltext?><br>
<input type="radio" name="searchtype" value="0" <?php if($searchtype==0) echo "checked"?>><?php echo $l_search_keywords?>
</td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_progfilter?>:</td><td><select name="limitprog">
<option value="-1" <?php if($limitprog==-1) echo "selected"?>><?php echo $l_nofilter?></option>
<?php
$sql="select * from ".$tableprefix."_programm order by language asc, displaypos asc";
if(!$result = faqe_db_query($sql, $db))
	die("<tr class=\"errorrow\"><td>Could not connect to the database.");
while($myrow=faqe_db_fetch_array($result))
{
	echo "<option value=\"";
	echo $myrow["prognr"];
	echo "\"";
	if($myrow["prognr"]==$limitprog)
		echo " selected";
	echo ">";
	echo display_encoded($myrow["programmname"]);
	echo " [";
	echo $myrow["language"];
	echo "]</option>";
}
?>
</select></td></tr>
<tr class="actionrow"><td align="center" colspan="2"><input type="submit" name="dosearch" value="<?php echo $l_dosearch?>" class="faqebutton"></td></tr>
<?php
echo "</td></tr></table>";
if(isset($dosearch))
{
	$search_head=trim($searchwords);
	$search_question=trim($searchwords);
	$search_answer=trim($searchwords);
	$num_results_faq=0;
	$numcriterias=0;
	if($searchtype!=0)
	{
		$sql ="SELECT dat.* from ".$tableprefix."_data dat, ".$tableprefix."_category cat, ".$tableprefix."_programm prog ";
		if(!isset($prog) || !$prog)
			$sql .="where dat.linkedfaq=0 and cat.programm=prog.prognr and dat.category=cat.catnr and prog.language='$act_lang' and (";
		else
			$sql .="where dat.linkedfaq=0 and cat.programm=prog.prognr and dat.category=cat.catnr and prog.progid='$prog' and prog.language='$act_lang' and (";
		if($search_head)
		{
			$search_head=do_htmlentities($search_head);
			$musts=array();
			$cans=array();
			$nots=array();
			$numcriterias+=1;
			$searchcriterias=0;
			$searchterms = explode(" ",$search_head);
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
			$first=1;
			if(count($musts)>0)
			{
				$sql .="((";
				$searchcriterias++;
				for($i=0;$i<count($musts);$i++)
				{
					if($first==1)
						$first=0;
					else
						$sql .=" and ";
					$sql.="dat.heading like '%".$musts[$i]."%'";
				}
				$sql .=")";

			}
			$first=1;
			if(count($nots)>0)
			{
				if($searchcriterias>0)
					$sql .=" and ";
				else
					$sql.="(";
				$sql .="(";
				$searchcriterias++;
				for($i=0;$i<count($nots);$i++)
				{
					if($first==1)
					$first=0;
					else
						$sql .=" and ";
					$sql.="dat.heading not like '%".$nots[$i]."%'";
				}
				$sql .=")";
			}
			$first=1;
			if((count($cans)>0) && (count($musts)<1))
			{
				if($searchcriterias>0)
					$sql .=" and ";
				else
					$sql .="(";
				$sql .="(";
				$searchcriterias++;
				for($i=0;$i<count($cans);$i++)
				{
					if($first==1)
						$first=0;
					else
						$sql .=" or ";
					$sql.="dat.heading like '%".$cans[$i]."%'";
				}
				$sql .=")";
			}
			if($searchcriterias>0)
				$sql.=")";
		}
		if($search_question)
		{
			$search_question=do_htmlentities($search_question);
			$musts=array();
			$cans=array();
			$nots=array();
			if($numcriterias>0)
				$sql .=" OR ";
			$numcriterias+=1;
			$searchcriterias=0;
			$searchterms = explode(" ",$search_question);
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
			$first=1;
			if(count($musts)>0)
			{
				$sql .="((";
				$searchcriterias++;
				for($i=0;$i<count($musts);$i++)
				{
					if($first==1)
						$first=0;
					else
						$sql .=" and ";
					$sql.="dat.questiontext like '%".$musts[$i]."%'";
				}
				$sql .=")";
			}
			$first=1;
			if(count($nots)>0)
			{
				if($searchcriterias>0)
					$sql .=" and ";
				else
					$sql .="(";
				$sql .="(";
				$searchcriterias++;
				for($i=0;$i<count($nots);$i++)
				{
					if($first==1)
						$first=0;
					else
						$sql .=" and ";
					$sql.="dat.questiontext not like '%".$nots[$i]."%'";
				}
				$sql .=")";
			}
			$first=1;
			if((count($cans)>0) && (count($musts)<1))
			{
				if($searchcriterias>0)
					$sql .=" and ";
				else
					$sql .="(";
				$sql .="(";
				$searchcriterias++;
				for($i=0;$i<count($cans);$i++)
				{
					if($first==1)
						$first=0;
					else
						$sql .=" or ";
					$sql.="dat.questiontext like '%".$cans[$i]."%'";
				}
				$sql .=")";
			}
			if($searchcriterias>0)
				$sql.=")";
		}
		if($search_answer)
		{
			$search_answer=do_htmlentities($search_answer);
			$musts=array();
			$cans=array();
			$nots=array();
			if($numcriterias>0)
				$sql .=" OR ";
			$numcriterias+=1;
			$searchcriterias=0;
			$searchterms = explode(" ",$search_answer);
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
			$first=1;
			if(count($musts)>0)
			{
				$sql .="((";
				$searchcriterias++;
				for($i=0;$i<count($musts);$i++)
				{
					if($first==1)
						$first=0;
					else
						$sql .=" and ";
					$sql.="dat.answertext like '%".$musts[$i]."%'";
				}
				$sql .=")";
			}
			$first=1;
			if(count($nots)>0)
			{
				if($searchcriterias>0)
					$sql .=" and ";
				else
					$sql.="(";
				$sql .="(";
				$searchcriterias++;
				for($i=0;$i<count($nots);$i++)
				{
					if($first==1)
						$first=0;
					else
						$sql .=" and ";
					$sql.="dat.answertext not like '%".$nots[$i]."%'";
				}
				$sql .=")";
			}
			$first=1;
			if((count($cans)>0) && (count($musts)<1))
			{
				if($searchcriterias>0)
					$sql .=" and ";
				else
					$sql.="(";
				$sql .="(";
				$searchcriterias++;
				for($i=0;$i<count($cans);$i++)
				{
					if($first==1)
						$first=0;
					else
						$sql .=" or ";
					$sql.="dat.answertext like '%".$cans[$i]."%'";
				}
				$sql .=")";
			}
			if($searchcriterias>0)
				$sql.=")";
		}
	}
	else
	{
		$sql = "select faq.* from ".$tableprefix."_data faq, ".$tableprefix."_category cat, ".$tableprefix."_programm prog ";
		if(!isset($prog) || !$prog)
			$sql .="where cat.programm=prog.prognr and faq.category=cat.catnr and prog.language='$act_lang' and ";
		else
			$sql .="where cat.programm=prog.prognr and faq.category=cat.catnr and prog.progid='$prog' and prog.language='$act_lang' and ";
		$faqnrs=array();
		$excludefaqs=array();
		$musts=array();
		$cans=array();
		$nots=array();
		if($searchwords)
		{
			$searchterms = explode(" ",$searchwords);
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
				$tempsql="select faq.faqnr from ".$tableprefix."_faq_keywords faq, ".$tableprefix."_keywords kw where faq.keywordnr=kw.keywordnr";
				for($i=0;$i<count($nots);$i++)
				{
					$tempsql .=" and ";
					if($keywordsearchmode==0)
						$tempsql.="kw.keyword ='".$nots[$i]."'";
					else
						$tempsql.="kw.keyword like '%".$nots[$i]."%'";
				}
				if(!$result = faqe_db_query($tempsql, $db)) {
					die("Could not connect to the database (3).".faqe_db_error());
				}
				while($temprow=faqe_db_fetch_array($result))
				{
					array_push($excludefaqs,$temprow["faqnr"]);
				}
			}
			if(count($musts)>0)
			{
				$numcriterias++;
				$tempsql="select faq.faqnr from ".$tableprefix."_faq_keywords faq, ".$tableprefix."_keywords kw where faq.keywordnr=kw.keywordnr";
				for($i=0;$i<count($musts);$i++)
				{
					$tempsql .= " and ";
					if($keywordsearchmode==0)
						$tempsql .="kw.keyword='".$musts[$i]."'";
					else
						$tempsql.="kw.keyword like '%".$musts[$i]."%'";
				}
				if(!$result = faqe_db_query($tempsql, $db)) {
					die("Could not connect to the database (3).".faqe_db_error());
				}
				while($temprow=faqe_db_fetch_array($result))
				{
					if(!in_array($temprow["faqnr"],$excludefaqs))
						array_push($faqnrs,$temprow["faqnr"]);
				}
			}
			if((count($cans)>0) && (count($musts)<1))
			{
				$numcriterias++;
				$tempsql="select faq.faqnr from ".$tableprefix."_faq_keywords faq, ".$tableprefix."_keywords kw where faq.keywordnr=kw.keywordnr and (";
				$first=1;
				for($i=0;$i<count($cans);$i++)
				{
					if($first==1)
						$first=0;
					else
						$tempsql .=" or ";
					$tempsql.="kw.keyword like '%".$cans[$i]."%'";
				}
				$tempsql.=")";
				if(!$result = faqe_db_query($tempsql, $db)) {
					die("Could not connect to the database (3).".faqe_db_error());
				}
				while($temprow=faqe_db_fetch_array($result))
				{
					if(!in_array($temprow["faqnr"],$excludefaqs))
						array_push($faqnrs,$temprow["faqnr"]);
				}
			}
		}
	}
	echo "<tr><TD BGCOLOR=\"#000000\">";
	echo "<table align=\"center\" width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">";
	echo "<tr class=\"inforow\"><td align=\"center\" colspan=\"5\"><b>$l_searchresult</b></td></tr>";
	if($numcriterias<1)
	{
		$num_results=0;
		echo "<tr class=\"displayrow\"><td ALIGN=\"CENTER\" colspan=\"5\">";
		echo $l_searchnoquery;
		echo "</td></tr>";
	}
	else
	{
		if($searchtype==0)
		{
			if(count($faqnrs)>0)
			{
				$sql .=" faq.faqnr in (";
				$first=1;
				for($i=0;$i<count($faqnrs);$i++)
				{
					if($first==1)
						$first=0;
					else
						$sql.=", ";
					$sql.=$faqnrs[$i];
				}
				$sql .=") group by faq.faqnr order by faq.editdate desc";
				if(!$result = faqe_db_query($sql, $db)) {
					die("Could not connect to the database (3).".faqe_db_error());
				}
				$num_results = faqe_db_num_rows($result);
			}
			else
				$num_results = 0;
		}
		else
		{
			$sql .=") group by dat.faqnr order BY dat.editdate desc";
			if(!$result = faqe_db_query($sql, $db)) {
				die("Could not connect to the database (3).".faqe_db_error());
			}
			$num_results = faqe_db_num_rows($result);
		}
		if($num_results<1)
		{
			echo "<tr class=\"displayrow\"><td ALIGN=\"CENTER\" colspan=\"5\">";
			echo $l_searchnonefound;
			echo "</td></tr>";
		}
		else
		{
			echo "<tr class=\"rowheadings\">";
			echo "<td width=\"5%\" align=\"center\"><b>#</b></td>";
			echo "<td width=\"50%\"><b>FAQ</b></td>";
			echo "<td width=\"15%\"><b>$l_programm</b></td>";
			echo "<td width=\"15%\"><b>$l_category</b></td>";
			echo "<td width=\"15%\">&nbsp;</td></tr>";
			WHILE ($myrow=faqe_db_fetch_array($result))
			{
				$act_id=$myrow["faqnr"];
				$catnr=$myrow["category"];
				$sql = "select * from ".$tableprefix."_category where (catnr=$catnr)";
				if(!$result2 = faqe_db_query($sql, $db)) {
					die("Could not connect to the database (3).");
				}
				if($myrow2=faqe_db_fetch_array($result2))
				{
					$prognr=$myrow2["programm"];
					$catname=$myrow2["categoryname"];
				}
				else
				{
					$prognr=0;
					$catname="";
				}
				$sql = "select * from ".$tableprefix."_programm where (prognr=$prognr)";
				if(!$result2 = faqe_db_query($sql, $db)) {
					die("Could not connect to the database (3).");
				}
				if($myrow2=faqe_db_fetch_array($result2))
				{
					$progid=$myrow2["progid"];
					$progname=$myrow2["programmname"];
					$language=$myrow2["language"];
				}
				else
				{
					$progid="";
					$progname="";
					$language=$default_lang;
				}
				echo "<tr class=\"displayrow\">";
				echo "<td valign=\"top\" align=\"right\">";
				echo $myrow["faqnr"];
				echo "</td>";
				echo "<td valign=\"top\" ALIGN=\"LEFT\">";
				echo undo_html_ampersand(stripslashes($myrow["heading"]));
				$summarytext=get_summary($myrow["questiontext"],250);
				$summarytext=search_highlight($summarytext,$musts,$cans);
				echo "<br><i>$l_question:</i> $summarytext";
				$summarytext=get_summary($myrow["answertext"],250);
				$summarytext=search_highlight($summarytext,$musts,$cans);
				echo "<br><i>$l_answer:</i> $summarytext</td>";
				echo "<td valign=\"top\" align=\"left\">";
				echo undo_html_ampersand(stripslashes($progname))." [".undo_html_ampersand(stripslashes($language))."]</td>";
				echo "<td valign=\"top\">".display_encoded($catname)."</td>";
				if($admin_rights > 1)
				{
					$modsql="select * from ".$tableprefix."_category_admins where (catnr=".$myrow["category"].") and (usernr=".$userdata["usernr"].")";
					if(!$modresult = faqe_db_query($modsql, $db))
					    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
					if(faqe_db_num_rows($modresult,$db)>0)
						$ismod=1;
					else
						$ismod=0;
				}
				echo "<td valign=\"top\">";
				if($myrow["linkedfaq"]==0)
				{
					echo "<a class=\"listlink2\" href=\"".do_url_session("faq.php?mode=display&input_faqnr=$act_id&$langvar=$act_lang")."\">";
					echo "<img src=\"gfx/view.gif\" border=\"0\" title=\"$l_display\" alt=\"$l_display\"></a>&nbsp; ";
				}
				if($admin_rights > 1)
				{
					if(($ismod==1) || ($admin_rights > 2))
					{
						$dellink=do_url_session("faq.php?mode=delete&input_faqnr=$act_id&$langvar=$act_lang&oldcat=".$myrow["category"]);
						if($admdelconfirm==2)
							echo "<a class=\"listlink2\" href=\"javascript:confirmDel('FAQ #$act_id','$dellink')\">";
						else
							echo "<a class=\"listlink2\" href=\"$dellink\">";
						echo "<img src=\"gfx/delete.gif\" border=\"0\" alt=\"$l_delete\" title=\"$l_delete\"></a>&nbsp; ";
						if($myrow["linkedfaq"]==0)
						{
							echo "<a class=\"listlink2\" href=\"".do_url_session("faq.php?mode=edit&input_faqnr=$act_id&$langvar=$act_lang&oldcat=".$myrow["category"])."\">";
							echo "<img src=\"gfx/edit.gif\" border=\"0\" title=\"$l_edit\" alt=\"$l_edit\"></a>&nbsp; ";
							echo "<a class=\"listlink2\" href=\"".do_url_session("faq.php?mode=mklink&input_faqnr=$act_id&$langvar=$act_lang")."\">";
							echo "<img src=\"gfx/linktarget.gif\" border=\"0\" title=\"$l_mkfaqlink\" alt=\"$l_mkfaqlink\"></a> ";
							echo "<a class=\"listlink2\" href=\"".do_url_session("transfer2kb.php?input_faqnr=$act_id&$langvar=$act_lang")."\">";
							echo "<img src=\"gfx/kbcopy.gif\" border=\"0\" title=\"$l_transfer2kb\" alt=\"$l_transfer2kb\"></a>";
						}
					}
				}
				echo "</td></tr>";
			}
		}
	}
	echo "</td></tr></table>";
}
echo "</td></tr></table>";
include('./trailer.php');
?>