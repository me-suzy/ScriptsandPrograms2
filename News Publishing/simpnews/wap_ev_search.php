<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require_once('./config.php');
require_once('./functions.php');
if(!isset($category))
	$category=0;
if(!isset($$langvar) || !$$langvar)
	$act_lang=$default_lang;
else
	$act_lang=$$langvar;
include_once('./language/lang_'.$act_lang.'.php');
require_once('./includes/get_settings.inc');
require_once('./includes/wap_get_settings.inc');
if($wap_enable==0)
	die("disabled");
setlocale(LC_TIME, $def_locales[$act_lang]);
$actdate = date("Y-m-d H:i:00");
if(!isset($backscript))
	$backscript="wap_catlist";
if(!isset($searchtype))
{
	if(bittst($wap_options,BIT_8))
		$searchtype="text";
	else
		$searchtype="standard";
}
if(!isset($start))
	$start=1;
if(!isset($sortorder))
	$sortorder=0;
$numentries=0;
$wap_data="<?xml version=\"1.0\" encoding=\"$contentcharset\" ?>".$crlf;
$wap_data.="<!DOCTYPE wml PUBLIC \"-//WAPFORUM//DTD WML 1.1//EN\" \"http://www.wapforum.org/DTD/wml_1.1.xml\">".$crlf;
$wap_data.="<wml>".$crlf;
if(!isset($mode))
{
	$totalentries=0;
	$wap_data.="<card>".$crlf;
	$wap_data.="<p>".wml_encode($wap_ev_title)."</p>".$crlf;
	$wap_data.="<p>".wml_encode($wap_ev_description)."</p>".$crlf;
	if($wap_copyright)
		$wap_data.="<p>".wml_encode($wap_copyright)."</p>".$crlf;
	$wap_data.="<p>".wml_encode($l_search_events)."</p>".$crlf;
	$wap_data.="<p>".wml_encode($l_category).": ";
	$numcats=0;
	$selectedcat=0;
	$cat_options="";
	if(!bittst($wap_options,BIT_6))
	{
		$numcats++;
		if($category==0)
			$selectedcat=$numcats;
		$cat_options.="<option value=\"0\">".wml_encode($l_general)."</option>".$crlf;
	}
	else
	{
		$tmpsql="select * from ".$tableprefix."_wap_catlist where catnr=0 and layoutid='$layout'";
		if(!$tmpresult = mysql_query($tmpsql, $db))
		    die("Unable to connect to database.".mysql_error());
		if(mysql_num_rows($tmpresult)>0)
		{
			$numcats++;
			if($category==0)
				$selectedcat=$numcats;
			$cat_options.="<option value=\"0\">".wml_encode($l_general)."</option>".$crlf;
		}
	}
	if(!bittst($wap_options,BIT_6))
		$sql="select * from ".$tableprefix."_categories where hideincatlist=0 and hideintotallist=0 order by displaypos asc";
	else
		$sql="select cat.* from ".$tableprefix."_categories cat, ".$tableprefix."_wap_catlist wc where cat.catnr=wc.catnr and wc.layoutid='$layout' order by cat.displaypos asc";
	if(!$result = mysql_query($sql, $db))
		die("Unable to connect to database.".mysql_error());
	while($myrow=mysql_fetch_array($result))
	{
		$cattext=undo_htmlentities(stripslashes($myrow["catname"]));
		$tmpsql="select * from ".$tableprefix."_catnames where catnr=".$myrow["catnr"]." and lang='".$act_lang."'";
		if(!$tmpresult=mysql_query($tmpsql,$db))
			die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
		if($tmprow=mysql_fetch_array($tmpresult))
		{
			if(strlen($tmprow["catname"])>0)
				$cattext=undo_htmlentities(stripslashes($tmprow["catname"]));
		}
		$numcats++;
		if($category==$myrow["catnr"])
			$selectedcat=$numcats;
		$cat_options.="<option value=\"".$myrow["catnr"]."\">";
		$cat_options.=wml_encode($cattext)."</option>".$crlf;
	}
	$wap_data.="<select name=\"cat\" ivalue=\"".($selectedcat)."\">".$crlf;
	$wap_data.=$cat_options;
	$wap_data.="</select></p>".$crlf;
	if($searchtype=="standard")
	{
		$wap_data.="<p>".wml_encode($l_date).":</p>".$crlf;
		$wap_data.="<p>".wml_encode($l_day).": ".$crlf;
		$curday=date("d");
		$wap_data.="<select name=\"d\" ivalue=\"$curday\">".$crlf;
		for($i=1;$i<32;$i++)
			$wap_data.="<option value=\"".$i."\">".sprintf("%02d",$i)."</option>".$crlf;
		$wap_data.="</select></p>".$crlf;
		$wap_data.="<p>".wml_encode($l_month).": ".$crlf;
		$curmonth=date("m");
		$wap_data.="<select name=\"m\" ivalue=\"$curmonth\">".$crlf;
		for($i=1;$i<13;$i++)
			$wap_data.="<option value=\"".$i."\">".sprintf("%02d",$i)."</option>".$crlf;
		$wap_data.="</select>".$crlf;
		$wap_data.="</p>".$crlf;
		$wap_data.="<p>".wml_encode($l_year).": ".$crlf;
		$wap_data.="<select name=\"y\" ivalue=\"3\">".$crlf;
		$curyear=date("Y");
		for($i=($curyear-2);$i<($curyear+3);$i++)
			$wap_data.="<option value=\"".$i."\">".$i."</option>".$crlf;
		$wap_data.="</select>".$crlf;
		$wap_data.="</p>".$crlf;
		if(bittst($wap_options,BIT_7))
		{
			$wap_data.="<p>".wml_encode($l_searchtext).": ";
			$wap_data.="<input type=\"text\" name=\"st\" size=\"20\" maxlength=\"128\" />";
			$wap_data.="</p>".$crlf;
		}
		if(bittst($wap_options,BIT_5))
		{
			if($wap_evs_dayrange<1)
				$wap_evs_dayrange=1;
			$wap_data.="<p>".$crlf;
			$wap_data.=wml_encode($l_wap_evs_dayrange).": ".$crlf;
			$wap_data.="<select name=\"dr\" ivalue=\"$selectedcat\">".$crlf;
			$wap_data.="<option value=\"0\">".wml_encode($l_only_selday)."</option>".$crlf;
			for($i=1;$i<=$wap_evs_dayrange;$i++)
				$wap_data.="<option value=\"".$i."\">".wml_encode(sprintf("%02d ".$l_days,$i))."</option>".$crlf;
			$wap_data.="</select>".$crlf;
			$wap_data.="</p>".$crlf;
		}
		$wap_data.="<p>".$crlf;
		$wap_data.="<anchor>".$crlf;
		$wap_data.=wml_encode($l_do_search).$crlf;
		$wap_data.="<go href=\"$act_script_url\" method=\"post\">".$crlf;
		$wap_data.="<postfield name=\"backscript\" value=\"$backscript\" />".$crlf;
		$wap_data.="<postfield name=\"category\" value=\"\$(cat)\" />".$crlf;
		$wap_data.="<postfield name=\"day\" value=\"\$(d)\" />".$crlf;
		$wap_data.="<postfield name=\"month\" value=\"\$(m)\" />".$crlf;
		$wap_data.="<postfield name=\"year\" value=\"\$(y)\" />".$crlf;
		$wap_data.="<postfield name=\"mode\" value=\"search\" />".$crlf;
		$wap_data.="<postfield name=\"searchtype\" value=\"standard\" />".$crlf;
		if(bittst($wap_options,BIT_7))
			$wap_data.="<postfield name=\"searchtext\" value=\"\$(st)\" />".$crlf;
		$wap_data.="<postfield name=\"$langvar\" value=\"$act_lang\" />".$crlf;
		$wap_data.="<postfield name=\"layout\" value=\"$layout\" />".$crlf;
		if(bittst($wap_options,BIT_5))
			$wap_data.="<postfield name=\"dayrange\" value=\"\$(dr)\" />".$crlf;
		$wap_data.="</go>".$crlf;
		$wap_data.="</anchor>".$crlf;
		$wap_data.="</p>".$crlf;
	}
	if($searchtype=="text")
	{
		$wap_data.="<p>".wml_encode($l_searchtext).": ";
		$wap_data.="<input type=\"text\" name=\"st\" size=\"20\" maxlength=\"128\" />";
		$wap_data.="</p>".$crlf;
		$wap_data.="<p>".$crlf;
		$wap_data.="<anchor>".$crlf;
		$wap_data.=wml_encode($l_do_search).$crlf;
		$wap_data.="<go href=\"$act_script_url\" method=\"post\">".$crlf;
		$wap_data.="<postfield name=\"backscript\" value=\"$backscript\" />".$crlf;
		$wap_data.="<postfield name=\"category\" value=\"\$(cat)\" />".$crlf;
		$wap_data.="<postfield name=\"day\" value=\"".date("d")."\" />".$crlf;
		$wap_data.="<postfield name=\"month\" value=\"".date("m")."\" />".$crlf;
		$wap_data.="<postfield name=\"year\" value=\"".date("Y")."\" />".$crlf;
		$wap_data.="<postfield name=\"mode\" value=\"txtsearch\" />".$crlf;
		$wap_data.="<postfield name=\"searchtext\" value=\"\$(st)\" />".$crlf;
		$wap_data.="<postfield name=\"searchtype\" value=\"text\" />".$crlf;
		$wap_data.="<postfield name=\"dayrange\" value=\"".$wap_evs_dayrange."\" />".$crlf;
		$wap_data.="<postfield name=\"$langvar\" value=\"$act_lang\" />".$crlf;
		$wap_data.="<postfield name=\"layout\" value=\"$layout\" />".$crlf;
		$wap_data.="</go>".$crlf;
		$wap_data.="</anchor>".$crlf;
		$wap_data.="</p>".$crlf;
	}
	if(bittst($wap_options,BIT_4))
	{
		if($wap_evs_maxldays<2)
			$wap_evs_maxldays=2;
		$wap_data.="<p><br/>".$crlf;
		$wap_data.=wml_encode($l_events_for)." ".$crlf;
		$wap_data.="<select name=\"ldays\" ivalue=\"1\">".$crlf;
		for($i=2;$i<=$wap_evs_maxldays;$i++)
			$wap_data.="<option value=\"".$i."\">".sprintf("%02d",$i)."</option>".$crlf;
		$wap_data.="</select> ".wml_encode($l_days).$crlf;
		$wap_data.="</p>".$crlf;
		$wap_data.="<p>".wml_encode($l_category).": ";
		$wap_data.="<select name=\"cat2\" ivalue=\"".($selectedcat)."\">".$crlf;
		$wap_data.=$cat_options;
		$wap_data.="</select></p>".$crlf;
		$wap_data.="<p>".$crlf;
		$wap_data.="<anchor>".$crlf;
		$wap_data.=wml_encode($l_ok).$crlf;
		$wap_data.="<go href=\"wap_short_events.php\" method=\"post\">".$crlf;
		$wap_data.="<postfield name=\"backscript\" value=\"$backscript\" />".$crlf;
		$wap_data.="<postfield name=\"category\" value=\"\$(cat2)\" />".$crlf;
		$wap_data.="<postfield name=\"$langvar\" value=\"$act_lang\" />".$crlf;
		$wap_data.="<postfield name=\"layout\" value=\"$layout\" />".$crlf;
		$wap_data.="<postfield name=\"mode\" value=\"list\" />".$crlf;
		$wap_data.="<postfield name=\"start\" value=\"1\" />".$crlf;
		$wap_data.="<postfield name=\"limitdays\" value=\"\$(ldays)\" />".$crlf;
		$wap_data.="<postfield name=\"goback\" value=\"search\" />".$crlf;
		$wap_data.="</go>".$crlf;
		$wap_data.="</anchor>".$crlf;
		$wap_data.="</p>".$crlf;
	}
	if(bittst($wap_options,BIT_10))
	{
		$wap_data.="<p><br /></p>".$crlf;
		$wap_data.="<p><a href=\"".$backscript.".php?mode=evsearch&amp;$langvar=$act_lang&amp;layout=$layout&amp;start=0\">";
		$wap_data.=wml_encode($l_listofcats);
		$wap_data.="</a></p>".$crlf;
	}
	$wap_data.="</card>".$crlf;
}
else
{
	if($mode=="search")
	{
		if(!checkdate($month,$day,$year))
		{
			$wap_data.="<card>".$crlf;
			$errmsg=undo_htmlentities($l_novaliddate);
			$errmsg=str_replace("{day}",$day,$errmsg);
			$errmsg=str_replace("{month}",$month,$errmsg);
			$errmsg=str_replace("{year}",$year,$errmsg);
			$wap_data.="<p>".wml_encode($errmsg)."</p>".$crlf;
			$wap_data.="<do type=\"prev\" label=\"".wml_encode($l_back)."\">".$crlf;
			$wap_data.="<prev/>".$crlf."</do>".$crlf;
			$wap_data.="</card>".$crlf."</wml>".$crlf;
			header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
			header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
			header("Cache-Control: no-cache, must-revalidate");
			header("Cache-Control: post-check=0,pre-check=0");
			header("Cache-Control: max-age=0");
			header("Pragma: no-cache");
			header('Content-Type: text/vnd.wap.wml');
			header("Content-length: " . strlen($wap_data) . "\n");
			print($wap_data);
			exit;
		}
	}
	$restarturl=$act_script_url."?$langvar=$act_lang&amp;layout=$layout&amp;searchtype=$searchtype&amp;backscript=$backscript";
	$baseurl=$act_script_url."?$langvar=$act_lang&amp;category=$category&amp;day=$day&amp;month=$month&amp;year=$year&amp;layout=$layout&amp;mode=$mode&amp;searchtype=$searchtype&amp;backscript=$backscript";
	if(isset($searchtext))
		$baseurl.="&amp;searchtext=".urlencode($searchtext);
	if(isset($dayrange))
		$baseurl.="&amp;dayrange=$dayrange";
	if(!isset($dayrange))
		$dayrange=0;
	$catname="";
	$searchdatetime=mktime(0,0,0,$month,$day,$year);
	if($category>0)
	{
		$sql = "select * from ".$tableprefix."_categories where catnr='$category'";
		if(!$result = mysql_query($sql, $db))
			die("Unable to connect to database.".mysql_error());
		if($myrow=mysql_fetch_array($result))
		{
			$catname=undo_htmlentities(stripslashes($myrow["catname"]));
			$tmpsql="select * from ".$tableprefix."_catnames where catnr=".$myrow["catnr"]." and lang='".$act_lang."'";
			if(!$tmpresult=mysql_query($tmpsql,$db))
				die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
			if($tmprow=mysql_fetch_array($tmpresult))
			{
				if(strlen($tmprow["catname"])>0)
					$catname=undo_htmlentities(stripslashes($tmprow["catname"]));
			}
		}
	}
	else if($category==0)
		$catname=$l_general;
	$searchcriterias=0;
	if(isset($searchtext))
	{
		$musts=array();
		$cans=array();
		$nots=array();
		$searchterms = explode(" ",$searchtext);
		foreach($searchterms as $searchstring)
		{
			$qualifier=substr($searchstring,0,1);
			if($qualifier=='-')
			{
				$actnot=trim(substr($searchstring,1,strlen($searchstring)-1));
				if(strlen($actnot)>0)
				{
					array_push($nots,$actnot);
					$searchcriterias++;
				}
			}else if ($qualifier=='+')
			{
				$actmust=trim(substr($searchstring,1,strlen($searchstring)-1));
				if(strlen($actmust)>0)
				{
					array_push($musts,$actmust);
					$searchcriterias++;
				}
			}
			else
			{
				$actcan=trim($searchstring);
				if(strlen($actcan)>0)
				{
					array_push($cans,$actcan);
					$searchcriterias++;
				}
			}
		}
	}
	if($searchcriterias>0)
	{
		$sql = "select ev.* from ".$tableprefix."_events ev, ".$tableprefix."_evsearch search where ev.eventnr=search.eventnr and ev.wap_nopublish=0 ";
		$first=1;
		$searchcriterias=0;
		if(count($musts)>0)
		{
			$sql .="and ((";
			$searchcriterias++;
			for($i=0;$i<count($musts);$i++)
			{
				if($first==1)
					$first=0;
				else
					$sql .=" and ";
				$sql.="search.text like '%".$musts[$i]."%'";
			}
			$sql .=")";
		}
		$first=1;
		if(count($nots)>0)
		{
			if($searchcriterias>0)
				$sql.=" and ";
			else
				$sql.="and (";
			$sql .="(";
			$searchcriterias++;
			for($i=0;$i<count($nots);$i++)
			{
				if($first==1)
					$first=0;
				else
					$sql.=" and ";
				$sql.="search.text not like '%".$nots[$i]."%'";
			}
			$sql .=")";
		}
		$first=1;
		if((count($cans)>0) && (count($musts)<1))
		{
			if($searchcriterias>0)
				$sql.=" and ";
			else
				$sql.="and (";
			$sql.="(";
			$searchcriterias++;
			for($i=0;$i<count($cans);$i++)
			{
				if($first==1)
					$first=0;
				else
					$sql .=" or ";
				$sql.="search.text like '%".$cans[$i]."%'";
			}
			$sql .=")";
		}
		if($searchcriterias>0)
			$sql.=") ";
	}
	else
		$sql = "select ev.* from ".$tableprefix."_events ev where ev.wap_nopublish=0";
	if($category>=0)
		$sql.=" and ev.category='$category'";
	if($separatebylang==1)
		$sql.=" and ev.lang='$act_lang'";
	if($mode=="txtsearch")
	{
		$sql.=" and DATE_FORMAT(ev.date,'%Y-%m-%d')>=DATE_FORMAT('".date("Y-m-d",$searchdatetime)."','%Y-%m-%d')";
		$daysecs=24*60*60;
		$enddate=date("Y-m-d",($searchdatetime+($dayrange*$daysecs)));
		$sql.=" and DATE_FORMAT(ev.date,'%Y-%m-%d')<=DATE_FORMAT('".$enddate."','%Y-%m-%d')";
	}
	else if($dayrange==0)
		$sql.=" and DATE_FORMAT(ev.date,'%Y-%m-%d')=DATE_FORMAT('".date("Y-m-d",$searchdatetime)."','%Y-%m-%d')";
	else
	{
		$daysecs=24*60*60;
		$startdate=date("Y-m-d",($searchdatetime-($dayrange*$daysecs)));
		$enddate=date("Y-m-d",($searchdatetime+($dayrange*$daysecs)));
		$sql.=" and DATE_FORMAT(ev.date,'%Y-%m-%d')>=DATE_FORMAT('".$startdate."','%Y-%m-%d')";
		$sql.=" and DATE_FORMAT(ev.date,'%Y-%m-%d')<=DATE_FORMAT('".$enddate."','%Y-%m-%d')";
	}
	switch($sortorder)
	{
		case 0:
			$sql.=" order by ev.date desc";
			break;
		case 1:
			$sql.=" order by ev.date asc";
			break;
		case 2:
			$sql.=" order by ev.heading asc";
			break;
		case 3:
			$sql.=" order by ev.heading desc";
			break;
	}
	if(!$result = mysql_query($sql, $db))
		die("Unable to connect to database.".mysql_error());
	$numentries=mysql_num_rows($result);
	$wap_data.="<card>".$crlf;
	$displaydate=date($event_dateformat,$searchdatetime);
	if($mode=="txtsearch")
	{
		$daysecs=24*60*60;
		$displayenddate=date($event_dateformat,($searchdatetime+($dayrange*$daysecs)));
		$wap_data.="<p>".wml_encode(sprintf($l_search_results3,$displaydate,$displayenddate,$numentries))."</p>".$crlf;
	}
	else if($dayrange==0)
		$wap_data.="<p>".wml_encode(sprintf($l_search_results,$displaydate,$numentries))."</p>".$crlf;
	else
		$wap_data.="<p>".wml_encode(sprintf($l_search_results2,$displaydate,$dayrange,$numentries))."</p>".$crlf;
	$wap_data.="<p>".wml_encode($l_category).": ".wml_encode($catname)."</p>".$crlf;
	if(isset($searchtext))
		$wap_data.="<p>".wml_encode($l_searchtext.": ".$searchtext)."</p>".$crlf;
	$wap_data.="<p><br /></p>".$crlf;
	$sql.=" limit ".($start-1).",1";
	if(!$result = mysql_query($sql, $db))
		die("Unable to connect to database.".mysql_error());
	if($myrow=mysql_fetch_array($result))
	{
		if($myrow["linkeventnr"]==0)
			$entrydata=$myrow;
		else
		{
			$tmpsql="select * from ".$tableprefix."_events where eventnr=".$myrow["linkeventnr"];
			if(!$tmpresult = mysql_query($tmpsql, $db))
				die("Unable to connect to database.".mysql_error());
			if(!$tmprow=mysql_fetch_array($tmpresult))
				die("DB error: no news entry for link (".$myrow["linkeventnr"].")");
			$entrydata=$tmprow;
		}
		if($entrydata["wap_short"])
			$text=stripslashes($entrydata["wap_short"]);
		else
		{
			$text=stripslashes($entrydata["text"]);
			$text=undo_htmlentities($text);
			$text=strip_tags($text);
			$text=substr($text,0,$wap_auto['short']);
		}
		if($entrydata["heading"])
		{
			$heading=stripslashes($entrydata["heading"]);
			$heading=undo_htmlentities($heading);
			$heading=strip_tags($heading);
		}
		else
			$heading=substr($text,0,$wap_auto['title']);
		list($mydate,$mytime)=explode(" ",$entrydata["date"]);
		list($year, $month, $day) = explode("-", $mydate);
		list($hour, $min, $sec) = explode(":",$mytime);
		if($month>0)
		{
			$temptime=mktime($hour,$min,$sec,$month,$day,$year);
			$temptime=transposetime($temptime,$servertimezone,$displaytimezone);
			if(($hour>0) || ($min>0))
				$displaydate=date($event_dateformat2,mktime($hour,$min,0,$month,$day,$year));
			else
				$displaydate=date($event_dateformat,mktime(0,0,0,$month,$day,$year));
		}
		else
			$displaydate="";
		$heading=wml_encode($heading);
		$text=wml_encode($text);
		if($displaydate)
			$wap_data.="<p>".$displaydate.":</p>".$crlf;
		$wap_data.="<p>".$heading."</p>".$crlf;
		$wap_data.="<p>".$text."</p>".$crlf;
		$hasemptyline=false;
		$usedbuttons=0;
		if($start<$numentries)
		{
			$usedbuttons++;
			$wap_data.="<p>".$crlf;
			if(!$hasemptyline)
			{
				$wap_data.="<br/>";
				$hasemptyline=true;
			}
			$wap_data.="<a href=\"".$baseurl."&amp;start=".($start+1)."\">".wml_encode($l_next_entry)."</a>".$crlf."</p>".$crlf;
			$wap_data.="<do type=\"accept\" name=\"fwd\" label=\"".wml_encode($l_next_entry)."\">".$crlf;
			$wap_data.="<go href=\"".$baseurl."&amp;start=".($start+1)."\" />".$crlf;
			$wap_data.="</do>".$crlf;
		}
		if($start>1)
		{
			$usedbuttons++;
			$wap_data.="<p>".$crlf;
			if(!$hasemptyline)
			{
				$wap_data.="<br/>";
				$hasemptyline=true;
			}
			$wap_data.="<a href=\"".$baseurl."&amp;start=".($start-1)."\">".wml_encode($l_prev_entry)."</a>".$crlf."</p>".$crlf;
			$wap_data.="<do type=\"accept\" name=\"back\" label=\"".wml_encode($l_prev_entry)."\">".$crlf;
			$wap_data.="<go href=\"".$baseurl."&amp;start=".($start-1)."\" />".$crlf;
			$wap_data.="</do>".$crlf;
		}
		$wap_data.="<p>".$crlf;
		if(!$hasemptyline)
		{
			$wap_data.="<br/>";
			$hasemptyline=true;
		}
		$wap_data.="<a href=\"".$restarturl."\">".wml_encode($l_restart_search)."</a>".$crlf."</p>".$crlf;
		if($usedbuttons<2)
		{
			$wap_data.="<do type=\"accept\" name=\"restart\" label=\"".wml_encode($l_restart_search)."\">".$crlf;
			$wap_data.="<go href=\"".$restarturl."\" />".$crlf;
			$wap_data.="</do>".$crlf;
		}
	}
	else
	{
		$wap_data.="<p><br/>".wml_encode(undo_htmlentities($l_noentries))."</p>".$crlf;
		$wap_data.="<do type=\"accept\" label=\"".wml_encode($l_restart_search)."\">".$crlf;
		$wap_data.="<go href=\"".$restarturl."\" />".$crlf;
		$wap_data.="</do>".$crlf;
		$wap_data.="<p><br/>".$crlf."<a href=\"".$restarturl."\">".wml_encode($l_restart_search)."</a>".$crlf."</p>".$crlf;
	}
	if(bittst($wap_options,BIT_10))
	{
		$wap_data.="<p><br /></p>".$crlf;
		if($backscript=="wap_catlist")
			$wap_data.="<p><a href=\"".$backscript.".php?mode=evsearch&amp;$langvar=$act_lang&amp;layout=$layout&amp;start=0\">";
		else
			$wap_data.="<p><a href=\"".$backscript.".php?$langvar=$act_lang&amp;layout=$layout&amp;start=0\">";
		$wap_data.=wml_encode($l_listofcats);
		$wap_data.="</a></p>".$crlf;
	}
	$wap_data.="</card>".$crlf;
}
$wap_data.="</wml>".$crlf;
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache");
header("Cache-Control: post-check=0,pre-check=0");
header("Cache-Control: max-age=0");
header("Pragma: no-cache");
header('Content-Type: text/vnd.wap.wml');
header("Content-length: " . strlen($wap_data) . "\n");
print($wap_data);
exit;
?>