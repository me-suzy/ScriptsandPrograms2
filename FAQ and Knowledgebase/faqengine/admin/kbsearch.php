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
$page_title=$l_kbsearch;
$page="kbsearch";
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
	$search_text=trim($searchwords);
	$num_results=0;
	$numcriterias=0;
	if($searchtype!=0)
	{
		if(isset($prog) && ($prog))
			$sql = "select kb.* from ".$tableprefix."_kb_articles kb, ".$tableprefix."_programm prog where prog.progid='$prog' and prog.language='$act_lang' and kb.programm=prog.prognr and ";
		else
			$sql = "select kb.* from ".$tableprefix."_kb_articles kb where ";
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
					$sql.="kb.heading like '%".$musts[$i]."%'";
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
					$sql.="kb.heading not like '%".$nots[$i]."%'";
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
					$sql.="kb.heading like '%".$cans[$i]."%'";
				}
				$sql .=")";
			}
			if($searchcriterias>0)
				$sql.=")";
		}
		if($search_text)
		{
			$search_text=do_htmlentities($search_text);
			$musts=array();
			$cans=array();
			$nots=array();
			if($numcriterias>0)
				$sql .=" OR ";
			$numcriterias+=1;
			$searchcriterias=0;
			$searchterms = explode(" ",$search_text);
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
					$sql.="kb.article like '%".$musts[$i]."%'";
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
					$sql.="kb.article not like '%".$nots[$i]."%'";
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
					$sql.="kb.article like '%".$cans[$i]."%'";
				}
				$sql .=")";
			}
			if($searchcriterias>0)
				$sql.=")";
		}
	}
	else
	{
		if(isset($prog) && ($prog))
			$sql = "select kb.* from ".$tableprefix."_kb_articles kb, ".$tableprefix."_programm prog where prog.progid='$prog' and prog.language='$act_lang' and kb.programm=prog.prognr and ";
		else
			$sql = "select kb.* from ".$tableprefix."_kb_articles kb where ";
		$kbnrs=array();
		$excludekbs=array();
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
				$tempsql="select kb.articlenr from ".$tableprefix."_kb_keywords kb, ".$tableprefix."_keywords kw where kb.keywordnr=kw.keywordnr";
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
					array_push($excludekbs,$temprow["articlenr"]);
				}
			}
			if(count($musts)>0)
			{
				$numcriterias++;
				$tempsql="select kb.articlenr from ".$tableprefix."_kb_keywords kb, ".$tableprefix."_keywords kw where kb.keywordnr=kw.keywordnr";
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
					if(!in_array($temprow["articlenr"],$excludekbs))
						array_push($kbnrs,$temprow["articlenr"]);
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
					$tempsql.="kw.keyword like '%".$cans[$i]."%'";
				}
				$tempsql.=")";
				if(!$result = faqe_db_query($tempsql, $db)) {
					die("Could not connect to the database (3).".faqe_db_error());
				}
				while($temprow=faqe_db_fetch_array($result))
				{
					if(!in_array($temprow["articlenr"],$excludekbs))
						array_push($kbnrs,$temprow["articlenr"]);
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
			if(count($kbnrs)>0)
			{
				$sql .=" kb.articlenr in (";
				$first=1;
				for($i=0;$i<count($kbnrs);$i++)
				{
					if($first==1)
						$first=0;
					else
						$sql.=", ";
					$sql.=$kbnrs[$i];
				}
				$sql .=") group by kb.articlenr order by kb.lastedited desc";
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
			$sql .=") group by kb.article order BY kb.lastedited desc";
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
			echo "<td width=\"50%\"><b>$l_article</b></td>";
			echo "<td width=\"15%\"><b>$l_programm</b></td>";
			echo "<td width=\"15%\"><b>$l_category</b></td>";
			echo "<td width=\"15%\">&nbsp;</td></tr>";
			WHILE ($myrow=faqe_db_fetch_array($result))
			{
				$act_id=$myrow["articlenr"];
				$tempsql = "select * from ".$tableprefix."_programm where (prognr=".$myrow["programm"].")";
				if(!$tempresult = faqe_db_query($tempsql, $db)) {
				    die("Could not connect to the database.");
				}
				if (!$temprow = faqe_db_fetch_array($tempresult))
				{
					$progid="";
					$proglang=$default_lang;
					$progname="";
				}
				else
				{
					$progid=$temprow["progid"];
					$proglang=$temprow["language"];
					$progname=display_encoded($temprow["programmname"]);
				}
				echo "<tr class=\"displayrow\">";
				echo "<td valign=\"top\" align=\"right\">";
				echo $myrow["articlenr"];
				echo "</td>";
				echo "<td valign=\"top\" ALIGN=\"LEFT\">";
				echo undo_html_ampersand(stripslashes($myrow["heading"]));
				$summarytext=get_summary($myrow["article"],250);
				$summarytext=search_highlight($summarytext,$musts,$cans);
				echo "<br><i>$l_article:</i> $summarytext";
				echo "<td valign=\"top\" align=\"left\">";
				echo undo_html_ampersand(stripslashes($progname))." [".undo_html_ampersand(stripslashes($proglang))."]</td>";
				echo "<td valign=\"top\">";
				if($myrow["subcategory"]>0)
				{
					$tempsql = "select subcat.*, cat.catname as maincat from ".$tableprefix."_kb_subcat subcat, ".$tableprefix."_kb_cat cat where subcat.catnr=".$myrow["subcategory"]." and cat.catnr=subcat.category";
					if(!$tempresult = faqe_db_query($tempsql, $db))
						die("<tr class=\"errorrow\"><td>Could not connect to the database.");
					if ($temprow = faqe_db_fetch_array($tempresult))
					{
						echo display_encoded($temprow["maincat"])." : ".display_encoded($temprow["catname"]);
					}
					else
						echo $l_none;
				}
				else if($myrow["category"]>0)
				{
					$tempsql = "select * from ".$tableprefix."_kb_cat where catnr=".$myrow["category"];
					if(!$tempresult = faqe_db_query($tempsql, $db))
						die("<tr class=\"errorrow\"><td>Could not connect to the database.");
					if ($temprow = faqe_db_fetch_array($tempresult))
					{
						echo display_encoded($temprow["catname"]);
					}
					else
						echo $l_none;
				}
				else
					echo $l_none;
				echo "</td>";
				if($admin_rights > 1)
				{
					$modsql="select * from ".$tableprefix."_programm_admins where (prognr=".$myrow["programm"].") and (usernr=".$userdata["usernr"].")";
					if(!$modresult = faqe_db_query($modsql, $db))
					    die("<tr bgcolor=\"#cccccc\"><td>Could not connect to the database.");
					if(faqe_db_num_rows($modresult,$db)>0)
						$is_mod=true;
					else
						$is_mod=false;
				}
				echo "<td valign=\"top\">";
				echo "<a class=\"listlink2\" href=\"".do_url_session("kb.php?mode=display&input_articlenr=$act_id&$langvar=$act_lang")."\">";
				echo "<img src=\"gfx/view.gif\" border=\"0\" title=\"$l_display\" alt=\"$l_display\"></a>";
				if($admin_rights > 1)
				{
					if($is_mod || ($admin_rights > 2))
					{
						echo "&nbsp; <a class=\"listlink2\" href=\"".do_url_session("kb.php?mode=delete&input_articlenr=$act_id&$langvar=$act_lang&heading=".$myrow["heading"])."\">";
						echo "<img src=\"gfx/delete.gif\" border=\"0\" title=\"$l_delete\" alt=\"$l_delete\"></a>";
						echo "&nbsp; ";
						echo "<a class=\"listlink2\" href=\"".do_url_session("kb.php?mode=edit&input_articlenr=$act_id&$langvar=$act_lang")."\">";
						echo "<img src=\"gfx/edit.gif\" border=\"0\" title=\"$l_edit\" alt=\"$l_edit\"></a>";
						echo "&nbsp; ";
						$attachsql="select * from ".$tableprefix."_kb_attachs where articlenr=$act_id";
						if(!$attachresult = faqe_db_query($attachsql, $db))
							die("<tr class=\"errorrow\"><td>Could not connect to the database.");
						if(faqe_db_num_rows($attachresult)>0)
						{
							echo "<a class=\"listlink2\" href=\"".do_url_session("kb.php?mode=delattach&input_articlenr=$act_id&$langvar=$act_lang")."\">";
							echo "<img src=\"gfx/delattach.gif\" border=\"0\" alt=\"$l_delattach\" title=\"$l_delattach\"></a>&nbsp; ";
						}
						$ratingsql="select * from ".$tableprefix."_kb_ratings where articlenr=$act_id";
						if(!$ratingresult = faqe_db_query($ratingsql, $db))
							die("<tr class=\"errorrow\"><td>Could not connect to the database.");
						if(faqe_db_num_rows($ratingresult)>0)
						{
							echo "<a class=\"listlink2\" href=\"".do_url_session("kb_ratingcomments.php?input_articlenr=$act_id&$langvar=$act_lang")."\">";
							echo "<img src=\"gfx/comment.gif\" border=\"0\" title=\"$l_ratingcomments\" alt=\"$l_ratingcomments\"></a>";
							echo "&nbsp; ";
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