<?php
/***************************************************************************
 * (c)2002-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require_once('../config.php');
require_once('./admchk.php');
if(!isset($$langvar) || !$$langvar)
	$act_lang=$admin_lang;
else
	$act_lang=$$langvar;
include_once('./language/lang_'.$act_lang.'.php');
if(!isset($start))
	$start=0;
require_once('./auth.php');
$wap_nopublish=0;
$page_title=$l_events;
$bbcbuttons=true;
$page="events";
$infotext="";
require_once('./heading.php');
include_once("./includes/bbcode_buttons.inc");
include_once("./includes/icon_selector.inc");
include_once("./includes/email_functions.inc");
if(!isset($headingtext))
	$headingtext="";
$hasattach=0;
if(!isset($dostorefilter) && ($admstorefilter==1))
{
	$admcookievals="";
	if($new_global_handling)
	{
		if(isset($_COOKIE[$admcookiename]))
			$admcookievals = $_COOKIE[$admcookiename];
	}
	else
	{
		if(isset($_COOKIE[$admcookiename]))
			$admcookievals = $_COOKIE[$admcookiename];
	}
	if($admcookievals)
	{
		if(sn_array_key_exists($admcookievals,"ev_catnr") && !isset($catnr))
			$catnr=$admcookievals["ev_catnr"];
		if(sn_array_key_exists($admcookievals,"ev_lang"))
			$eventlang=$admcookievals["ev_lang"];
		if(sn_array_key_exists($admcookievals,"ev_sorting"))
			$sorting=$admcookievals["ev_sorting"];
		if(sn_array_key_exists($admcookievals,"ev_limiting"))
			$limiting=$admcookievals["ev_limiting"];
	}
}
if($admin_rights < 1)
{
	echo "<table align=\"center\" width=\"80%\" CELLPADDING=\"1\" CELLSPACING=\"0\" border=\"0\" valign=\"top\">";
	echo "<tr class=\"errorrow\"><td align=\"center\">";
	echo "$l_functionnotallowed</td></tr></table>";
	include('./trailer.php');
	exit;
}
if(!isset($eventlang))
	$eventlang=$act_lang;
if(!isset($sorting))
	$sorting=32;
if(!isset($usetime))
	$usetime=0;
if(!isset($limiting))
	$limiting=0;
if(!isset($catnr))
{
	if(($admin_rights<3) && bittst($secsettings,BIT_16) && !bittst($secsettings,BIT_2))
		$catnr=-1;
	else
		$catnr=0;
}
if(($admin_rights<3) && bittst($secsettings,BIT_16) && !bittst($secsettings,BIT_2) && ($catnr==0))
	$catnr=-1;
$allowcomments=1;
$hasattach=0;
$errmsg="";
$allowadding=true;
$sql = "select * from ".$tableprefix."_settings where (settingnr=1)";
if(!$result = mysql_query($sql, $db)) {
    die("Could not connect to the database.");
}
if ($myrow = mysql_fetch_array($result))
{
	$subscriptionsendmode=$myrow["subscriptionsendmode"];
	$enablesubscriptions=$myrow["enablesubscriptions"];
	$subject=$myrow["subject"];
	$simpnewsmail=$myrow["simpnewsmail"];
	$simpnewsmailname=$myrow["simpnewsmailname"];
}
else
{
	$subscriptionsendmode=0;
	$enablesubscriptions=0;
	$subject="News";
	$simpnewsmail="simpnews@foo.bar";
	$simpnewsmailname="SimpNews";
}
$dontsendmail=0;
if(isset($transfer) && isset($tmpdata) && isset($eventlang))
{
	if(isset($chgeventnr) && ($asnewentry==0))
	{
		$doedit=1;
		$input_eventnr=$chgeventnr;
		$sql="select * from ".$tableprefix."_events where eventnr=$chgeventnr";
		if(!$result = mysql_query($sql, $db))
		    die("Could not connect to the database.".mysql_error());
		if($myrow=mysql_fetch_array($result))
		{
			$eventlang=$myrow["lang"];
			$catnr=$myrow["category"];
			if($replacemode==1)
			{
				$tmptxt=stripslashes($myrow["text"]);
				$tmptxt = undo_htmlspecialchars($tmptxt);
				$tmptxt = decode_emoticons($tmptxt, $url_emoticons, $db);
				$tmptxt = bbdecode($tmptxt);
				$tmptxt = undo_make_clickable($tmptxt);
				$eventtext = $tmptxt."\n\n".$eventtext;
			}
		}
	}
	if(!isset($replacemode) || ($replacemode==0))
	{
		$sql="select * from ".$tableprefix."_texts where lang='$eventlang' and textid='proposed'";
		if(!$result = mysql_query($sql, $db))
		    die("Could not connect to the database.".mysql_error());
		if($myrow=mysql_fetch_array($result))
		{
			$trailer = stripslashes($myrow["text"]);
			$trailer = str_replace("<BR>", "\n", $trailer);
			$trailer = undo_htmlspecialchars($trailer);
			$trailer = bbdecode($trailer);
			$trailer = undo_make_clickable($trailer);
			$sql="select tmp.*, poster.name, poster.email from ".$tableprefix."_tmpevents tmp, ".$tableprefix."_poster poster where tmp.entrynr=$tmpdata and poster.entrynr=tmp.posterid and tmp.posterid!=0";
			if(!$result = mysql_query($sql, $db))
			    die("Could not connect to the database.".mysql_error());
			if($myrow=mysql_fetch_array($result))
			{
				$trailer=str_replace("{postername}",$myrow["name"],$trailer);
				$trailer=str_replace("{postermail}",$myrow["email"],$trailer);
				$eventtext.="\n\n".$trailer;
			}
		}
	}
	$eventtext = str_replace("<BR>", "\n", $eventtext);
}
if($userdata["rights"]==2)
{
	if($catnr>0)
	{
		$tempsql="select * from ".$tableprefix."_cat_adm where catnr=$catnr and usernr=".$userdata["usernr"];
		if(!$result = mysql_query($tempsql, $db))
		    die("Could not connect to the database.".mysql_error());
		if(mysql_num_rows($result)<1)
		{
			$allowadding=false;
			$catnr=-1;
		}
	}
	else if(!bittst($secsettings,BIT_2))
		$allowadding=false;
}
if(!isset($sel_year))
	list($sel_year, $sel_month, $sel_day) = explode("-", date("Y-m-d"));
if(!isset($sel_hour))
	list($sel_hour,$sel_min)=explode(":", date("H:i"));
if(isset($mode) && ($admin_rights>1))
{
	if($mode=="recur")
	{
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="inforow"><td align="center" colspan="2">
<?php
		echo "$l_recurevent (Event# ";
		$showurl=do_url_session("evshow.php?$langvar=$act_lang&eventnr=".$input_eventnr);
		echo "<a class=\"shdetailslink\" href=\"javascript:openWindow3('$showurl','nShow',20,20,400,200);\">";
		echo "$input_eventnr)</a></td></tr>";
		$sql="select * from ".$tableprefix."_events where eventnr=$input_eventnr";
		if(!$result = mysql_query($sql, $db))
			die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
		if(!$myrow=mysql_fetch_array($result))
			die("<tr class=\"errorrow\"><td>No such entry");
		list($curdate,$curtime)=explode(" ",$myrow["date"]);
		list($curyear,$curmonth,$curday)=explode("-",$curdate);
		list($curhour,$curmin,$cursec)=explode(":",$curtime);
		$curdate=mktime($curhour,$curmin,$cursec,$curmonth,$curday,$curyear);
		if(($curhour>0) || ($curmin>0) || ($cursec>0))
			$displaydate=date($l_admdateformat,$curdate);
		else
			$displaydate=date($l_admdateformat2,$curdate);
		$curdatear=getdate($curdate);
		$curweekday=$curdatear['wday'];
?>
<form name="inputform" method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="eventlang" value="<?php echo $eventlang?>">
<input type="hidden" name="catnr" value="<?php echo $catnr?>">
<input type="hidden" name="input_eventnr" value="<?php echo $input_eventnr?>">
<input type="hidden" name="mode" value="dorecur">
<?php
		if(is_konqueror())
			echo "<tr><td></td></tr>";
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
		echo "<tr class=\"displayrow\"><td align=\"right\" width=\"30%\">";
		echo "$l_startdate:</td><td>";
		echo "$displaydate (".$l_weekdays["$curweekday"].")";
		echo "</td></tr>";
		echo "<tr class=\"inputrow\"><td align=\"right\">";
		echo "$l_enddate:</td><td>";
?>
<table width="50%" cellpadding="0" cellspacing="0" border="0" align="left">
<tr>
<td align="center" width="20%"><?php echo $l_day?></td>
<td align="center" width="20%"><?php echo $l_month?></td>
<td align="center" width="20%"><?php echo $l_year?></td>
</tr>
<tr>
<td align="center"><select name="end_day">
<?php
for($i=1;$i<32;$i++)
{
	echo "<option value=\"$i\"";
	if($i==$curday)
		echo " selected";
	echo ">$i</option>";
}
?>
</select></td>
<td align="center"><select name="end_month">
<?php
for($i=1;$i<13;$i++)
{
	echo "<option value=\"$i\"";
	if($i==$curmonth)
		echo " selected";
	echo ">".$l_monthname[$i-1]."</option>";
}
?>
</select></td>
<td align="center"><select name="end_year">
<?php
for($i=$curyear;$i<$curyear+($yearrange*2)+1;$i++)
{
	echo "<option value=\"$i\"";
	if($i==$curyear)
		echo " selected";
	echo ">$i</option>";
}
?>
</select></td>
</tr>
</table></td></tr>
<?php
		echo "<tr class=\"inputrow\"><td>&nbsp;</td><td>";
		echo "<input type=\"radio\" name=\"recurtype\" value=\"1\" onclick=\"reccheck()\"> $l_daily";
		echo "</td></tr>";
		echo "<tr class=\"inputrow\"><td>&nbsp;</td><td>";
		echo "<input type=\"radio\" name=\"recurtype\" value=\"2\" onclick=\"reccheck()\"> $l_weekly, ";
		echo "$l_every ";
		echo "<select name=\"recur_weekday\" disabled>";
		for($i=0;$i<count($l_weekdays);$i++)
		{
			echo "<option value=\"$i\"";
			if($i==$curweekday)
				echo " selected";
			echo ">".$l_weekdays[$i]."</option>";
		}
		echo "</select>";
		echo "</td></tr>";
		echo "<tr class=\"inputrow\"><td>&nbsp;</td><td>";
		echo "<input type=\"radio\" name=\"recurtype\" value=\"3\" onclick=\"reccheck()\"> $l_monthly";
		echo "</td></tr>";
		echo "<tr class=\"inputrow\"><td>&nbsp;</td><td>";
		echo "<input type=\"radio\" name=\"recurtype\" value=\"4\" onclick=\"reccheck()\"> $l_yearly";
		echo "</td></tr>";
		echo "<tr class=\"inputrow\"><td>&nbsp;</td><td>";
		echo "<input type=\"radio\" name=\"recurtype\" value=\"5\" onclick=\"reccheck()\"> $l_every ";
		echo "<select name=\"recur_numweek\" disabled>";
		for($i=1;$i<5;$i++)
		{
			echo "<option value=\"";
			echo $i;
			echo "\">";
			echo $i;
			echo ".</option>";
		}
		echo "</select>";
		echo "<select name=\"recur_wday2\" disabled>";
		for($i=0;$i<count($l_weekdays);$i++)
		{
			echo "<option value=\"$i\"";
			if($i==$curweekday)
				echo " selected";
			echo ">".$l_weekdays[$i]."</option>";
		}
		echo "</select> $l_of_month";
		echo "<tr class=\"actionrow\"><td align=\"center\" colspan=\"2\">";
		echo "<input class=\"snbutton\" type=\"submit\" name=\"submit\" value=\"$l_add\">";
		echo "</td></tr>";
		echo "</table></td></tr></form></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang&catnr=$catnr&eventlang=$eventlang")."\">$l_eventlist</a></div>";
		include('./trailer.php');
		exit;
	}
	if($mode=="dorecur")
	{
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="inforow"><td align="center" colspan="2"><?php echo $l_recurevent?></td></tr>
<?php
		$sql="select * from ".$tableprefix."_events where eventnr=$input_eventnr";
		if(!$result = mysql_query($sql, $db))
			die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
		if(!$curentry=mysql_fetch_array($result))
			die("<tr class=\"errorrow\"><td>No such entry");
		list($curyear,$curmonth,$curday)=explode("-",$curentry["date"]);
		$curdate=mktime(0,0,0,$curmonth,$curday,$curyear);
		$curdatear=getdate($curdate);
		$curweekday=$curdatear['wday'];
		$errors=0;
		$warnings=0;
		if(!isset($recurtype) || ($recurtype==0))
		{
				echo "<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">";
				echo "$l_norecurring";
				echo "</td></tr>";
				$errors=1;
		}
		else
		{
			if($recurtype==2)
			{
				if(($curweekday!=$recur_weekday) && !isset($ignorewarning))
				{
					echo "<tr class=\"warningrow\"><td align=\"center\" colspan=\"2\">";
					echo "$l_wrongstartweekday (".$l_weekdays[$curweekday]." &ne; ".$l_weekdays[$recur_weekday].")";
					echo "</td></tr>";
					$errors=1;
				}
			}
			if(recurtype==5)
			{
				if(($curweekday!=$recur_wday2) && !isset($ignorewarning))
				{
					echo "<tr class=\"warningrow\"><td align=\"center\" colspan=\"2\">";
					echo "$l_wrongstartweekday (".$l_weekdays[$curweekday]." &ne; ".$l_weekdays[$recur_wday2].")";
					echo "</td></tr>";
					$errors=1;
				}
			}
			if($errors==1)
			{
?>
<form name="inputform" method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="eventlang" value="<?php echo $eventlang?>">
<input type="hidden" name="catnr" value="<?php echo $catnr?>">
<input type="hidden" name="input_eventnr" value="<?php echo $input_eventnr?>">
<input type="hidden" name="mode" value="dorecur">
<input type="hidden" name="ignorewarning" value="1">
<input type="hidden" name="recurtype" value="<?php echo $recurtype?>">
<input type="hidden" name="end_day" value="<?php echo $end_day?>">
<input type="hidden" name="end_month" value="<?php echo $end_month?>">
<input type="hidden" name="end_year" value="<?php echo $end_year?>">
<?php
				if(isset($recur_weekday))
					echo "<input type=\"hidden\" name=\"recur_weekday\" value=\"$recur_weekday\">";
				if(is_konqueror())
					echo "<tr><td></td></tr>";
				if($sessid_url)
					echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
				echo "<tr class=\"actionrow\"><td align=\"center\" colspan=\"2\">";
				echo "<input type=\"submit\" name=\"submit\" class=\"snbutton\" value=\"$l_ignorewarnings\"></td></tr></form>";
			}
		}
		if($errors==1)
		{
			echo "<tr class=\"actionrow\"><td align=\"center\" colspan=\"2\">";
			echo "<a href=\"javascript:history.back()\">$l_back</a>";
			echo "</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang&catnr=$catnr&eventlang=$eventlang")."\">$l_eventlist</a></div>";
			include('./trailer.php');
			exit;
		}
		switch($recurtype)
		{
			case 1:
				include("./includes/ev_rec_daily.inc");
				break;
			case 2:
				include("./includes/ev_rec_weekly.inc");
				break;
			case 3:
				include("./includes/ev_rec_monthly.inc");
				break;
			case 4:
				include("./includes/ev_rec_yearly.inc");
				break;
			case 5:
				include("./includes/ev_rec_period.inc");
				break;
		}
		echo "<tr class=\"displayrow\"><td align=\"center\" colspan=\"2\">$added $l_eventsadded</td></tr>";
		echo "</td></tr></form></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang&catnr=$catnr&eventlang=$eventlang")."\">$l_eventlist</a></div>";
		include('./trailer.php');
		exit;
	}
	if($mode=="catlink")
	{
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="inforow"><td align="center" colspan="2">
<?php
	echo "$l_catlink (Event# ";
	$showurl=do_url_session("evshow.php?$langvar=$act_lang&eventnr=".$input_eventnr);
	echo "<a class=\"shdetailslink\" href=\"javascript:openWindow3('$showurl','nShow',20,20,400,200);\">";
	echo "$input_eventnr)</a></td></tr>";
?>
<form method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="eventlang" value="<?php echo $eventlang?>">
<input type="hidden" name="catnr" value="<?php echo $catnr?>">
<input type="hidden" name="input_eventnr" value="<?php echo $input_eventnr?>">
<input type="hidden" name="mode" value="dolink">
<?php
			if(!isset($destlang))
				$destlang=$eventlang;
			if(is_konqueror())
				echo "<tr><td></td></tr>";
			if($sessid_url)
				echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
			echo "<tr class=\"inputrow\"><td align=\"right\" width=\"30%\">$l_destcat:</td><td>";
			$sql="select * from ".$tableprefix."_categories where catnr!=$catnr";
			if(!$result = mysql_query($sql, $db))
			    die("<tr bgcolor=\"#cccccc\"><td>Unable to connect to database.".mysql_error());
			$totalcats=mysql_num_rows($result);
			if($catnr!=0)
				$totalcats++;
			$excludecats=array();
			if(($admrestrict==1) && ($userdata["rights"]==2) && !bittst($userdata["addoptions"],BIT_6))
				$sql="select cat.* from ".$tableprefix."_cat_adm ca, ".$tableprefix."_categories cat where cat.catnr=ca.catnr and cat.catnr!=$catnr and ca.usernr=".$userdata["usernr"];
			else
				$sql="select * from ".$tableprefix."_categories where catnr!=$catnr";
			if(!$result = mysql_query($sql, $db))
			    die("<tr bgcolor=\"#cccccc\"><td>Unable to connect to database.".mysql_error());
			$numdestcats=mysql_num_rows($result);
			if($catnr!=0)
			{
				if(($userdata["rights"]>2) || bittst($secsettings,BIT_2))
				{
					$numdestcats++;
					array_push($excludecats,0);
				}
			}
			if($numdestcats>0)
			{
				echo "<select name=\"destcat\">";
				echo "<option value=\"-1\"></option>";
				if(($catnr!=0) && (($userdata["rights"]>2) || bittst($secsettings,BIT_2)))
				{
					echo "<option value=\"0\"";
					if(isset($destcat) && ($destcat==0))
						echo " selected";
					echo ">$l_general</option>";
				}
				while($myrow=mysql_fetch_array($result))
				{
					array_push($excludecats,$myrow["catnr"]);
					echo "<option value=\"".$myrow["catnr"]."\"";
					if(isset($destcat) && ($destcat==$myrow["catnr"]))
						echo " selected";
					echo ">";
					echo display_encoded($myrow["catname"]);
					echo "</option>";
				}
				echo "</select>";
			}
			else
				echo $l_noneavailable;
			echo "</td></tr>";
			if($numdestcats>0)
			{
				echo "<tr class=\"inputrow\"><td align=\"right\" width=\"30%\">$l_destlang:</td><td>";
				echo language_select($destlang,"destlang","../language/");
				echo "</td></tr>";
				echo "<tr class=\"actionrow\"><td align=\"center\" colspan=\"2\">";
				echo "<input class=\"snbutton\" type=\"submit\" name=\"submit\" value=\"$l_genlink\">";
				echo "</td></tr>";
			}
			echo "</form></table></td></tr>";
			if(($numdestcats<$totalcats) && ($userdata["rights"]==2) && bittst($secsettings,BIT_23))
			{
				echo "<tr><TD BGCOLOR=\"#000000\">";
				echo "<table align=\"center\" width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">";
				echo "<form method=\"post\" action=\"$act_script_url\">";
				echo "<input type=\"hidden\" name=\"$langvar\" value=\"$act_lang\">";
				echo "<input type=\"hidden\" name=\"eventlang\" value=\"$eventlang\">";
				echo "<input type=\"hidden\" name=\"catnr\" value=\"$catnr\">";
				echo "<input type=\"hidden\" name=\"input_eventnr\" value=\"$input_eventnr\">";
				echo "<input type=\"hidden\" name=\"mode\" value=\"requestlink\">";
				echo "<tr class=\"inforow\"><td align=\"center\" colspan=\"2\">$l_requestlink</td></tr>";
				$sql="select * from ".$tableprefix."_categories where catnr!=$catnr";
				for($i=0;$i<count($excludecats);$i++)
				{
					if($excludecats[$i]!=0)
						$sql.=" and $catnr!=".$excludecats[$i];
				}
				if(!$result = mysql_query($sql, $db))
				    die("<tr bgcolor=\"#cccccc\"><td>Unable to connect to database.".mysql_error());
				echo "<tr class=\"inputrow\"><td align=\"right\" width=\"30%\">$l_destcat:</td><td>";
				echo "<select name=\"destcat\">";
				echo "<option value=\"-1\"></option>";
				if(($catnr!=0) && !in_array($excludecats,0))
					echo "<option value=\"0\">$l_general</option>";
				while($myrow=mysql_fetch_array($result))
				{
					echo "<option value=\"".$myrow["catnr"]."\">";
					echo display_encoded($myrow["catname"]);
					echo "</option>";
				}
				echo "</select></td></tr>";
				echo "<tr class=\"inputrow\"><td align=\"right\" width=\"30%\">$l_destlang:</td><td>";
				echo language_select($destlang,"destlang","../language/");
				echo "</td></tr>";
				echo "<tr class=\"actionrow\"><td align=\"center\" colspan=\"2\">";
				echo "<input class=\"snbutton\" type=\"submit\" name=\"submit\" value=\"$l_requestlink\">";
				echo "</td></tr>";
				echo "</form></table></td></tr>";
			}
			echo "</table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang&catnr=$catnr&eventlang=$eventlang")."\">$l_eventlist</a></div>";
			include('./trailer.php');
			exit;
	}
	if($mode=="requestlink")
	{
		include_once('../includes/htmlMimeMail.inc');
		if($use_smtpmail)
		{
			include_once('../includes/smtp.inc');
			include_once('../includes/RFC822.inc');
		}
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="inforow"><td align="center" colspan="2"><?php echo "$l_catlink ($l_requestlink)"?></td></tr>
<?php
		$sql="select * from ".$tableprefix."_users where rights>2 and usernr!=".$userdata["usernr"];
		if(!$result = mysql_query($sql, $db))
			die("<tr bgcolor=\"#cccccc\"><td>Unable to connect to database.".mysql_error());
		while($myrow=mysql_fetch_array($result))
		{
			include("./language/mail_".$myrow["language"].".php");
			if($destcat==0)
				$destcatname=$l_mail_general;
			else
			{
				$tmpsql="select * from ".$tableprefix."_categories where catnr=$destcat";
				if(!$tmpresult = mysql_query($tmpsql, $db))
					die("<tr bgcolor=\"#cccccc\"><td>Unable to connect to database.".mysql_error());
				if($tmprow=mysql_fetch_array($tmpresult))
					$destcatname=$tmprow["catname"];
				else
					$destcatname=$l_mail_undefined;
			}
			$mailmsg=$l_mail_linkrequest;
			$actionlink=$simpnews_fullurl."admin/events.php?$langvar=".$myrow["language"]."&mode=catlink&input_eventnr=$input_eventnr&destcat=$destcat&destlang=$destlang";
			$htmlactionlink="<a href=\"$actionlink\">$actionlink</a>";
			$mailmsg=str_replace("{adminname}",$userdata["username"],$mailmsg);
			$mailmsg=str_replace("{input_entrynr}",$input_eventnr,$mailmsg);
			$mailmsg=str_replace("{entrytype}",$l_mail_event,$mailmsg);
			$mailmsg=str_replace("{destcatname}",$destcatname,$mailmsg);
			$mailmsg=str_replace("{destcatnr}",$destcat,$mailmsg);
			$mailmsg=str_replace("{destlang}",$destlang,$mailmsg);
			$htmlmailmsg=str_replace("\n","<br>",$mailmsg);
			$htmlmailmsg=str_replace("\r","",$htmlmailmsg);
			$mailmsg=str_replace("{actionlink}",$actionlink,$mailmsg);
			$htmlmailmsg=str_replace("{actionlink}",$htmlactionlink,$htmlmailmsg);
			$mail = new htmlMimeMail();
			$mail->setCrlf($crlf);
			$mail->setTextWrap($mailmaxlinelength);
			$mail->setHTMLCharset($contentcharset);
			$mail->setTextCharset($contentcharset);
			$mail->setHTML($htmlmailmsg,$mailmsg);
			if($simpnewsmailname)
				$fromadr="\"$simpnewsmailname\" <$simpnewsmail>";
			else
				$fromadr=$simpnewsmail;
			$mail->setSubject($l_mail_linkrequest_subj);
			$mail->setFrom($fromadr);
			if($myrow["email"])
			{
				$receiver=array();
				array_push($receiver,$myrow["email"]);
				if(!$insafemode)
					@set_time_limit($msendlimit);
				if($use_smtpmail)
				{
					$mail->setSMTPParams($smtpserver,$smtpport,NULL,$smtpauth,$smtpuser,$smtppasswd);
					$sendsuccess=$mail->send($receiver, "smtp");
				}
				else
					$sendsuccess=$mail->send($receiver, "mail");
				do_emaillog($sendsuccess,$myrow["email"],"events.php - requesting link");
			}
		}
		echo "<tr class=\"displayrow\"><td align=\"center\" colspan=\"2\">$l_linkrequested</td></tr>";
		echo "</td></tr></form></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang&catnr=$destcat&eventlang=$eventlang")."\">$l_eventlist</a></div>";
		include('./trailer.php');
		exit;
	}
	if($mode=="dolink")
	{
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="inforow"><td align="center" colspan="2"><?php echo $l_catlink?></td></tr>
<?php
		if(!isset($destcat) || ($destcat<0))
		{
			echo "<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">$l_nodestcat</td></tr>";
			echo "<tr class=\"actionrow\"><td align=\"center\" colspan=\"2\">";
			echo "<a href=\"javascript:history.back()\">$l_back</a>";
			echo "</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang&catnr=$catnr&eventlang=$eventlang")."\">$l_eventlist</a></div>";
			include('./trailer.php');
			exit;
		}
		$sql="select * from ".$tableprefix."_events where eventnr=$input_eventnr";
		if(!$result = mysql_query($sql, $db))
		    die("<tr bgcolor=\"#cccccc\"><td>Unable to connect to database.".mysql_error());
		if(!$myrow=mysql_fetch_array($result))
		    die("<tr bgcolor=\"#cccccc\"><td>Unable to connect to database.".mysql_error());
		$entrydate=$myrow["date"];
		$entryadded=$myrow["added"];
		$sql="insert into ".$tableprefix."_events (category, linkeventnr, lang, date, added) values ($destcat, $input_eventnr, '$destlang', '$entrydate', '$entryadded')";
		if(!$result = mysql_query($sql, $db))
		    die("<tr bgcolor=\"#cccccc\"><td>Unable to connect to database.".mysql_error());
		$linkid=mysql_insert_id($db);
		echo "<tr class=\"displayrow\"><td align=\"center\" colspan=\"2\">$l_newcatlinked (Event: $input_eventnr - Link: $linkid)</td></tr>";
		echo "</td></tr></form></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang&catnr=$destcat&eventlang=$eventlang")."\">$l_eventlist</a></div>";
		include('./trailer.php');
		exit;
	}
	if($mode=="catmove")
	{
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="inforow"><td align="center" colspan="2"><?php echo "$l_catmove (Event# $input_eventnr)"?></td></tr>
<form method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="eventlang" value="<?php echo $eventlang?>">
<input type="hidden" name="catnr" value="<?php echo $catnr?>">
<input type="hidden" name="input_eventnr" value="<?php echo $input_eventnr?>">
<input type="hidden" name="mode" value="domove">
<?php
			if(is_konqueror())
				echo "<tr><td></td></tr>";
			if($sessid_url)
				echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
			echo "<tr class=\"inputrow\"><td align=\"right\" width=\"30%\">$l_destcat:</td><td>";
			$numdestcats=0;
			if(($catnr!=0) && (bittst($secsettings,BIT_2) || ($userdata["rights"]>2)))
				$numdestcats++;
			if(($admrestrict==1) && ($userdata["rights"]==2))
				$sql="select cat.* from ".$tableprefix."_cat_adm ca, ".$tableprefix."_categories cat where cat.catnr=ca.catnr and cat.catnr!=$catnr and ca.usernr=".$userdata["usernr"];
			else
				$sql="select * from ".$tableprefix."_categories where catnr!=$catnr";
			if(!$result = mysql_query($sql, $db))
			    die("<tr bgcolor=\"#cccccc\"><td>Unable to connect to database.".mysql_error());
			$numdestcats+=mysql_num_rows($result);
			if($numdestcats>0)
			{
				echo "<select name=\"destcat\">";
				echo "<option value=\"-1\"></option>";
				if(($catnr!=0) && (bittst($secsettings,BIT_2) || ($userdata["rights"]>2)))
					echo "<option value=\"0\">$l_general</option>";
				while($myrow=mysql_fetch_array($result))
				{
					echo "<option value=\"".$myrow["catnr"]."\">";
					echo display_encoded($myrow["catname"]);
					echo "</option>";
				}
				echo "</select>";
			}
			else
				echo $l_noneavailable;
			echo "</td></tr>";
			if($numdestcats>0)
			{
				echo "<tr class=\"actionrow\"><td align=\"center\" colspan=\"2\">";
				echo "<input class=\"snbutton\" type=\"submit\" name=\"submit\" value=\"$l_move\">";
				echo "</td></tr>";
			}
			echo "</form></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang&catnr=$catnr&eventlang=$eventlang")."\">$l_eventlist</a></div>";
			include('./trailer.php');
			exit;
	}
	if($mode=="domove")
	{
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="inforow"><td align="center" colspan="2"><?php echo "$l_catmove (Event# $input_eventnr)"?></td></tr>
<?php
		if(!isset($destcat) || !$destcat || ($destcat<0))
		{
			echo "<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">$l_nodestcat</td></tr>";
			echo "<tr class=\"actionrow\"><td align=\"center\" colspan=\"2\">";
			echo "<a href=\"javascript:history.back()\">$l_back</a>";
			echo "</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang&catnr=$catnr&eventlang=$eventlang")."\">$l_eventlist</a></div>";
			include('./trailer.php');
			exit;
		}
		$sql="update ".$tableprefix."_events set category=$destcat where eventnr=$input_eventnr";
		if(!$result = mysql_query($sql, $db))
		    die("<tr bgcolor=\"#cccccc\"><td>Unable to connect to database.".mysql_error());
		echo "<tr class=\"displayrow\"><td align=\"center\" colspan=\"2\">$l_newcatmoved</td></tr>";
		echo "</td></tr></form></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang&catnr=$destcat&eventlang=$eventlang")."\">$l_eventlist</a></div>";
		include('./trailer.php');
		exit;
	}
	if($mode=="massmove")
	{
		$selectedevents=$_POST["eventnr"];
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="inforow"><td align="center" colspan="2">
<?php
		echo "$l_catmove (Event ";
		$counter=0;
		while(list($null, $input_eventnr) = each($_POST["eventnr"]))
		{
			if($counter>0)
				echo ", ";
			echo "#$input_eventnr";
			$counter++;
		}
		echo ")"
?>
</td></tr>
<form method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="eventlang" value="<?php echo $eventlang?>">
<input type="hidden" name="catnr" value="<?php echo $catnr?>">
<input type="hidden" name="mode" value="domassmove">
<?php
    		while(list($null, $input_eventnr) = each($selectedevents))
    			echo "<input type=\"hidden\" name=\"eventnr[]\" value=\"$input_eventnr\">";
			if(is_konqueror())
				echo "<tr><td></td></tr>";
			if($sessid_url)
				echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
			echo "<tr class=\"inputrow\"><td align=\"right\" width=\"30%\">$l_destcat:</td><td>";
			$numdestcats=0;
			if(($catnr!=0) && (bittst($secsettings,BIT_2) || ($userdata["rights"]>2)))
				$numdestcats++;
			if(($admrestrict==1) && ($userdata["rights"]==2))
				$sql="select cat.* from ".$tableprefix."_cat_adm ca, ".$tableprefix."_categories cat where cat.catnr=ca.catnr and cat.catnr!=$catnr and ca.usernr=".$userdata["usernr"];
			else
				$sql="select * from ".$tableprefix."_categories where catnr!=$catnr";
			if(!$result = mysql_query($sql, $db))
			    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
			$numdestcats+=mysql_num_rows($result);
			if($numdestcats>0)
			{
				echo "<select name=\"destcat\">";
				echo "<option value=\"-1\"></option>";
				if(($catnr!=0) && (bittst($secsettings,BIT_2) || ($userdata["rights"]>2)))
					echo "<option value=\"0\">$l_general</option>";
				while($myrow=mysql_fetch_array($result))
				{
					echo "<option value=\"".$myrow["catnr"]."\">";
					echo display_encoded($myrow["catname"]);
					echo "</option>";
				}
				echo "</select>";
			}
			else
				echo $l_noneavailable;
			echo "</td></tr>";
			if($numdestcats>0)
			{
				echo "<tr class=\"actionrow\"><td align=\"center\" colspan=\"2\">";
				echo "<input class=\"snbutton\" type=\"submit\" name=\"submit\" value=\"$l_move\">";
				echo "</td></tr>";
			}
			echo "</form></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang&catnr=$catnr&eventlang=$eventlang")."\">$l_eventlist</a></div>";
			include('./trailer.php');
			exit;
	}
	if($mode=="domassmove")
	{
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="inforow"><td align="center" colspan="2"><?php echo "$l_catmove"?></td></tr>
<?php
		if(!isset($destcat) || !$destcat || ($destcat<0))
		{
			echo "<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">$l_nodestcat</td></tr>";
			echo "<tr class=\"actionrow\"><td align=\"center\" colspan=\"2\">";
			echo "<a href=\"javascript:history.back()\">$l_back</a>";
			echo "</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang&catnr=$catnr&eventlang=$eventlang")."\">$l_eventlist</a></div>";
			include('./trailer.php');
			exit;
		}
		$selectedevents=$_POST["eventnr"];
		if(isset($eventnr))
		{
    		while(list($null, $input_eventnr) = each($_POST["eventnr"]))
    		{
				$sql="update ".$tableprefix."_events set category=$destcat where eventnr=$input_eventnr";
				if(!$result = mysql_query($sql, $db))
					die("<tr bgcolor=\"#cccccc\"><td>Unable to connect to database.".mysql_error());
			}
		}
		echo "<tr class=\"displayrow\"><td align=\"center\" colspan=\"2\">$l_newcatmoved2<br>";
		$counter=0;
		while(list($null, $input_eventnr) = each($selectedevents))
		{
			if($counter>0)
				echo ", ";
			echo "#$input_eventnr";
			$counter++;
		}
		echo "</td></tr>";
		echo "</td></tr></form></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang&catnr=$destcat&eventlang=$eventlang")."\">$l_eventlist</a></div>";
		include('./trailer.php');
		exit;
	}
	if($mode=="clone")
	{
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="inforow"><td align="center" colspan="2"><?php echo "$l_clone (Event# $input_eventnr)"?></td></tr>
<form method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="input_eventnr" value="<?php echo $input_eventnr?>">
<input type="hidden" name="mode" value="doclone">
<?php
			if(is_konqueror())
				echo "<tr><td></td></tr>";
			if($sessid_url)
				echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
			echo "<tr class=\"inputrow\"><td align=\"right\" width=\"30%\">$l_destcat:</td><td>";
			$numdestcats=0;
			if(bittst($secsettings,BIT_2) || ($userdata["rights"]>2))
				$numdestcats++;
			if(($admrestrict==1) && ($userdata["rights"]==2))
				$sql="select cat.* from ".$tableprefix."_cat_adm ca, ".$tableprefix."_categories cat where cat.catnr=ca.catnr and ca.usernr=".$userdata["usernr"];
			else
				$sql="select * from ".$tableprefix."_categories ";
			if(!$result = mysql_query($sql, $db))
			    die("<tr bgcolor=\"#cccccc\"><td>Unable to connect to database.".mysql_error());
			$numdestcats+=mysql_num_rows($result);
			if($numdestcats>0)
			{
				echo "<select name=\"catnr\">";
				echo "<option value=\"-1\"></option>";
				if(bittst($secsettings,BIT_2) || ($userdata["rights"]>2))
					echo "<option value=\"0\">$l_general</option>";
				while($myrow=mysql_fetch_array($result))
				{
					echo "<option value=\"".$myrow["catnr"]."\">";
					echo display_encoded($myrow["catname"]);
					echo "</option>";
				}
				echo "</select>";
			}
			else
				echo $l_noneavailable;
			echo "</td></tr>";
			if($numdestcats>0)
			{
				echo "<tr class=\"inputrow\"><td align=\"right\">";
				echo "$l_language:</td><td>";
				echo language_select($eventlang,"eventlang","../language/");
				echo "</td></tr>";
				echo "<tr class=\"actionrow\"><td align=\"center\" colspan=\"2\">";
				echo "<input class=\"snbutton\" type=\"submit\" name=\"submit\" value=\"$l_clone\">";
				echo "</td></tr>";
			}
			echo "</form></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang&catnr=$catnr&eventlang=$eventlang")."\">$l_eventlist</a></div>";
			include('./trailer.php');
			exit;
	}
	if($mode=="doclone")
	{
		if(!isset($catnr) || !$catnr || ($catnr<0))
		{
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="inforow"><td align="center" colspan="2"><?php echo "$l_clone (Event# $input_eventnr)"?></td></tr>
<?php
			echo "<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">$l_nodestcat</td></tr>";
			echo "<tr class=\"actionrow\"><td align=\"center\" colspan=\"2\">";
			echo "<a href=\"javascript:history.back()\">$l_back</a>";
			echo "</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang&catnr=$catnr&eventlang=$newslang")."\">$l_eventlist</a></div>";
			include('./trailer.php');
			exit;
		}
		$sql="select * from ".$tableprefix."_events where eventnr=$input_eventnr";
		if(!$result = mysql_query($sql, $db))
		    die("Could not connect to the database.".mysql_error());
		if($myrow=mysql_fetch_array($result))
		{
			$doclone=1;
			$tmptxt=stripslashes($myrow["text"]);
			$tmptxt = str_replace("<BR>", "\n", $tmptxt);
			$tmptxt = undo_htmlspecialchars($tmptxt);
			$tmptxt = decode_emoticons($tmptxt, $url_emoticons, $db);
			$tmptxt = bbdecode($tmptxt);
			$tmptxt = undo_make_clickable($tmptxt);
			$eventtext = $tmptxt;
			$actheadingicon=$myrow["headingicon"];
			$headingtext=do_htmlentities($myrow["heading"]);
			$dontsendmail=$myrow["dontemail"];
			$wap_nopublish=$myrow["wap_nopublish"];
			$wap_short=stripslashes($myrow["wap_short"]);
			list($sel_year, $sel_month, $sel_day) = explode("-", $myrow["date"]);
		}
	}
	if($mode=="add")
	{
		if(!isset($headingicon))
			$headingicon="";
		if(!isset($eventtext) || !$eventtext)
		{
			unset($preview);
			$errmsg=$l_noeventtext;
			$headingtext=do_htmlentities($heading);
		}
		else
		{
			if(isset($preview))
			{
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<form method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<?php
				$heading=stripslashes($heading);
				$eventtext=stripslashes($eventtext);
				if(is_konqueror())
					echo "<tr><td></td></tr>";
				if(isset($no_wap))
					echo "<input type=\"hidden\" name=\"no_wap\" value=\"1\">";
				if(isset($wap_short))
					echo "<input type=\"hidden\" name=\"rss_short\" value=\"$wap_short\">";
				if($sessid_url)
					echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
				if(isset($urlautoencode))
					echo "<input type=\"hidden\" name=\"urlautoencode\" value=\"1\">";
				if(isset($enablespcode))
					echo "<input type=\"hidden\" name=\"enablespcode\" value=\"1\">";
				if(isset($tmpdata))
					echo "<input type=\"hidden\" name=\"tmpdata\" value=\"$tmpdata\">";
				if(isset($delpropose))
					echo "<input type=\"hidden\" name=\"delpropose\" value=\"1\">";
				if(isset($dontsendemail))
					echo "<input type=\"hidden\" name=\"dontsendemail\" value=\"1\">";
				if(isset($postasnews))
					echo "<input type=\"hidden\" name=\"postasnews\" value=\"1\">";
				if(isset($immediatlysendemail))
					echo "<input type=\"hidden\" name=\"immediatlysendemail\" value=\"1\">";
?>
<input type="hidden" name="eventlang" value="<?php echo $eventlang?>">
<input type="hidden" name="sel_day" value="<?php echo $sel_day?>">
<input type="hidden" name="sel_month" value="<?php echo $sel_month?>">
<input type="hidden" name="sel_year" value="<?php echo $sel_year?>">
<input type="hidden" name="catnr" value="<?php echo $catnr?>">
<input type="hidden" name="eventtext" value="<?php echo do_htmlentities($eventtext)?>">
<input type="hidden" name="heading" value="<?php echo do_htmlentities($heading)?>">
<input type="hidden" name="headingicon" value="<?php echo $headingicon?>">
<input type="hidden" name="mode" value="add">
<input type="hidden" name="frompreview" value="1">
<tr class="inforow"><td align="center" colspan="2"><?php echo $l_previewprelude?></td></tr>
<?php
				if($usetime!=0)
				{
					$displaydate=date($l_admdateformat,mktime($sel_hour,$sel_min,0,$sel_month,$sel_day,$sel_year));
					echo "<input type=\"hidden\" name=\"usetime\" value=\"1\">";
					echo "<input type=\"hidden\" name=\"sel_hour\" value=\"$sel_hour\">";
					echo "<input type=\"hidden\" name=\"sel_min\" value=\"$sel_min\">";
				}
				else
					$displaydate=date($l_admdateformat2,mktime(0,0,0,$sel_month,$sel_day,$sel_year));
				if(isset($urlautoencode))
					$eventtext = make_clickable($eventtext);
				if(isset($enablespcode))
					$eventtext = bbencode($eventtext);
				if(!isset($disableemoticons))
					$eventtext = encode_emoticons($eventtext, $url_emoticons, $db);
				$eventtext = do_htmlentities($eventtext);
				$eventtext = str_replace("\n", "<BR>", $eventtext);
				$eventtext = undo_htmlspecialchars($eventtext);
				echo "<tr><td width=\"2%\" height=\"100%\" align=\"center\" class=\"eventicon\">";
				if($headingicon)
					echo "<img src=\"$url_icons/".$headingicon."\" border=\"0\" align=\"middle\"> ";
				else
					echo "&nbsp;";
				echo "</td>";
				echo "<td align=\"center\"><table width=\"100%\" align=\"center\" cellspacing=\"0\" cellpadding=\"0\">";
				echo "<tr><td align=\"left\" class=\"eventdate\">";
				echo $displaydate."</td></tr>";
				if(strlen($heading)>0)
				{
					echo "<tr class=\"eventheading\"><td align=\"left\">";
					echo do_htmlentities($heading);
					echo "</td></tr>";
				}
				echo "<tr class=\"evententry\"><td align=\"left\">";
				echo $eventtext;
				echo "</td></tr>";
				if(isset($wap_short))
				{
					echo "<tr class=\"displayrow\"><td align=\"left\">";
					echo "$l_wap_short:<br>$wap_short";
					echo "</td></tr>";
				}
				if(isset($new_files))
				{
					while(list($null, $actattach) = each($_POST["new_files"]))
						echo "<input type=\"hidden\" name=\"new_files[]\" value=\"$actattach\">";
				}
				echo "</table></td></tr>";
				echo "<tr class=\"actionrow\"><td align=\"center\" colspan=\"2\"><input class=\"snbutton\" type=\"submit\" value=\"$l_add\">&nbsp;&nbsp;<input class=\"snbutton\" type=\"button\" value=\"$l_back\" onclick=\"self.history.back();\"></td></tr>";
				echo "</table></td></tr></table>";
			}
			else
			{
				if(isset($no_wap))
					$wap_nopublish=1;
				else
					$wap_nopublish=0;
				if(isset($dontsendemail))
					$dontemail=1;
				else
					$dontemail=0;
				$adddate = date("Y-m-d H:i:00");
				if($usetime!=0)
					$entrydate=mktime($sel_hour,$sel_min,0,$sel_month,$sel_day,$sel_year);
				else
					$entrydate=mktime(0,0,0,$sel_month,$sel_day,$sel_year);
				$entrydate=date("Y-m-d H:i:00",$entrydate);
				if(isset($enablecomments))
					$allowcomments=1;
				else
					$allowcomments=0;
				if(strlen($userdata["realname"])>0)
					$poster=$userdata["realname"];
				else
					$poster=$userdata["username"];
				if(isset($urlautoencode))
					$eventtext = make_clickable($eventtext);
				if(isset($enablespcode))
					$eventtext = bbencode($eventtext);
				if(!isset($disableemoticons))
					$eventtext = encode_emoticons($eventtext, $url_emoticons, $db);
				if($heading)
					$searchtext = stripslashes($heading)." ";
				else
					$searchtext = "";
				$searchtext.= stripslashes(strip_tags($eventtext));
				$searchtext = remove_htmltags($searchtext);
				$searchtext = strtolower($searchtext);
				$searchtext = addslashes($searchtext);
				$eventtext = do_htmlentities($eventtext);
				$eventtext = str_replace("\n", "<BR>", $eventtext);
				$eventtext = str_replace("\r", "", $eventtext);
				$eventtext=addslashes($eventtext);
				if(isset($tmpdata) && $tmpdata)
				{
					$postersql="select * from ".$tableprefix."_tmpdata where entrynr=$tmpdata";
					if(!$presult = mysql_query($postersql, $db))
					    die("Unable to connect to database.".mysql_error());
					if($prow=mysql_fetch_array($presult))
						$exposter=$prow["posterid"];
				}
				$sql = "insert into ".$tableprefix."_events (lang, date, text, heading, poster, headingicon, category, added, posterid, wap_nopublish";
				if(isset($wap_short))
					$sql.=", wap_short";
				if(isset($exposter))
					$sql.=", exposter";
				$sql.= ", dontemail) values ('$eventlang', '$entrydate', '$eventtext', '$heading', '$poster', '$headingicon', $catnr, '$adddate', ".$userdata["usernr"].", $wap_nopublish";
				if(isset($wap_short))
					$sql.=", '$wap_short'";
				if(isset($exposter))
					$sql.=", $exposter";
				$sql.=", $dontemail)";
				if(!$result = mysql_query($sql, $db))
				    die("Unable to connect to database.".mysql_error());
				$eventnr=mysql_insert_id($db);
				$sql = "insert into ".$tableprefix."_evsearch (eventnr, text) values ($eventnr, '$searchtext')";
				if(!$result = mysql_query($sql, $db))
				    die("Unable to connect to database.".mysql_error());
				if(isset($tmpdata) && $tmpdata && isset($delpropose))
				{
					$delsql="delete from ".$tableprefix."_tmpevents where entrynr=$tmpdata";
					if(!$result = mysql_query($delsql, $db))
					    die("Unable to connect to database.".mysql_error());
					$delsql="delete from ".$tableprefix."_tmpevents_attachs where eventnr=$tmpdata";
					if(!$result = mysql_query($delsql, $db))
					    die("Unable to connect to database.".mysql_error());
				}
				if(isset($postasnews))
				{
					$newsdate=date("Y-m-d H:i:s",mktime(0,0,0,$sel_month,$sel_day,$sel_year));
					$sql = "insert into ".$tableprefix."_data (lang, date, text, heading, poster, headingicon, category, allowcomments, tickerurl, posterid, dontemail, wap_nopublish";
					if(isset($wap_short))
						$sql.=", rss_short";
					$sql.= ")";
					$sql.= "values ('$eventlang', '$newsdate', '$eventtext', '$heading', '$poster', '$headingicon', $catnr, 0, '', ".$userdata["usernr"].", 1, $wap_nopublish";
					if(isset($wap_short))
						$sql.=", '$rss_short'";
					$sql.= ")";
					if(!$result = mysql_query($sql, $db))
					    die("Unable to connect to database.".mysql_error());
					$newsnr=mysql_insert_id($db);
					if(isset($new_files))
					{
						while(list($null, $actattach) = each($_POST["new_files"]))
						{
							$sql="insert into ".$tableprefix."_news_attachs (attachnr,newsnr) values ('$actattach','$newsnr')";
							@mysql_query($sql, $db);
						}
					}
				}
				if(isset($new_files))
				{
					while(list($null, $actattach) = each($_POST["new_files"]))
					{
						$sql="insert into ".$tableprefix."_events_attachs (attachnr,eventnr) values ('$actattach','$eventnr')";
						@mysql_query($sql, $db);
					}
				}
				$infotext=$l_entryadded." (#".$eventnr.")";
				list($sel_year, $sel_month, $sel_day) = explode("-", date("Y-m-d"));
				if(($enablesubscriptions==1) && (($subscriptionsendmode==0) || isset($immediatlysendemail)) && (!isset($dontsendemail)) && ($evnewsletterinclude==1))
				{
					$sentmails=email_single_event($heading, $eventtext, $eventlang, $db, $subject, $simpnewsmail, $simpnewsmailname, $entrydate, $poster, $eventnr, $headingicon, $catnr);
					$infotext.="<br>$l_emailssent ($sentmails)";
					if($showsendprogress==1)
					{
						$infotext.="<br><a href=\"javascript:showprogressbox()\"";
						$infotext.=" class=\"actionlink\">$l_reshowprogressbox</a>";
					}
				}
				$usetime=0;
				$wap_nopublish=0;
			}
		}
	}
	if($mode=="massdel")
	{
		if(($admdelconfirm==1) && !isset($confirmed))
		{
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<form method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="confirmed" value="1">
<input type="hidden" name="eventlang" value="<?php echo $eventlang?>">
<input type="hidden" name="catnr" value="<?php echo $catnr?>">
<input type="hidden" name="mode" value="massdel">
<?php
			$selectedevents=$_POST["eventnr"];
    		while(list($null, $input_eventnr) = each($_POST["eventnr"]))
    			echo "<input type=\"hidden\" name=\"eventnr[]\" value=\"$input_eventnr\">";
			if(is_konqueror())
				echo "<tr><td></td></tr>";
			if($sessid_url)
				echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
			echo "<tr class=\"inforow\"><td align=\"center\">";
			echo "$l_confirmdel2: Event ";
			$counter=0;
    		while(list($null, $input_eventnr) = each($selectedevents))
    		{
    			if($counter>0)
    				echo ", ";
				echo "#$input_eventnr";
				$counter++;
			}
			echo "</td></tr>";
			echo "<tr class=\"actionrow\"><td align=\"center\">";
			echo "<input class=\"snbutton\" type=\"submit\" name=\"submit\" value=\" $l_yes \">";
			echo "&nbsp;<input class=\"snbutton\" type=\"button\" value=\" $l_no \" onclick=\"self.history.back();\">";
			echo "</td></tr>";
			echo "</form></table></td></tr></table>";
			include('./trailer.php');
			exit;
		}
		if(isset($eventnr))
		{
    		while(list($null, $input_eventnr) = each($_POST["eventnr"]))
    		{
				$sql = "delete from ".$tableprefix."_events where linkeventnr=$input_eventnr";
				if(!$result = mysql_query($sql, $db))
				    die("Unable to connect to database.".mysql_error());
				$sql = "delete from ".$tableprefix."_events where eventnr=$input_eventnr";
				if(!$result = mysql_query($sql, $db))
				    die("Unable to connect to database.".mysql_error());
				$sql = "delete from ".$tableprefix."_events_attachs where eventnr=$input_eventnr";
				if(!$result = mysql_query($sql, $db))
				    die("Unable to connect to database.".mysql_error());
			}
		}
	}
	if($mode=="del")
	{
		if(($admdelconfirm==1) && !isset($confirmed))
		{
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<form method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="confirmed" value="1">
<input type="hidden" name="eventlang" value="<?php echo $eventlang?>">
<input type="hidden" name="catnr" value="<?php echo $catnr?>">
<input type="hidden" name="mode" value="del">
<input type="hidden" name="input_eventnr" value="<?php echo $input_eventnr?>">
<?php
			if(is_konqueror())
				echo "<tr><td></td></tr>";
			if($sessid_url)
				echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
			echo "<tr class=\"inforow\"><td align=\"center\">";
			echo "$l_confirmdel: Event #$input_eventnr";
			echo "</td></tr>";
			echo "<tr class=\"actionrow\"><td align=\"center\">";
			echo "<input class=\"snbutton\" type=\"submit\" name=\"submit\" value=\" $l_yes \">";
			echo "&nbsp;<input class=\"snbutton\" type=\"button\" value=\" $l_no \" onclick=\"self.history.back();\">";
			echo "</td></tr>";
			echo "</form></table></td></tr></table>";
			include('./trailer.php');
			exit;
		}
		$sql = "delete from ".$tableprefix."_events where linkeventnr=$input_eventnr";
		if(!$result = mysql_query($sql, $db))
			die("Unable to connect to database.".mysql_error());
		$sql = "delete from ".$tableprefix."_events where eventnr=$input_eventnr";
		if(!$result = mysql_query($sql, $db))
		    die("Unable to connect to database.".mysql_error());
		$sql = "delete from ".$tableprefix."_events_attachs where eventnr=$input_eventnr";
		if(!$result = mysql_query($sql, $db))
		    die("Unable to connect to database.".mysql_error());
	}
	if($mode=="delattach")
	{
		if(($admdelconfirm==1) && !isset($confirmed))
		{
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<form method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="confirmed" value="1">
<input type="hidden" name="eventlang" value="<?php echo $eventlang?>">
<input type="hidden" name="catnr" value="<?php echo $catnr?>">
<input type="hidden" name="mode" value="delattach">
<input type="hidden" name="input_eventnr" value="<?php echo $input_eventnr?>">
<?php
			if(is_konqueror())
				echo "<tr><td></td></tr>";
			if($sessid_url)
				echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
			echo "<tr class=\"inforow\"><td align=\"center\">";
			echo "$l_confirmdel: $l_attachements - Event #$input_eventnr";
			echo "</td></tr>";
			echo "<tr class=\"actionrow\"><td align=\"center\">";
			echo "<input class=\"snbutton\" type=\"submit\" name=\"submit\" value=\" $l_yes \">";
			echo "&nbsp;<input class=\"snbutton\" type=\"button\" value=\" $l_no \" onclick=\"self.history.back();\">";
			echo "</td></tr>";
			echo "</form></table></td></tr></table>";
			include('./trailer.php');
			exit;
		}
		$sql = "delete from ".$tableprefix."_events_attachs where eventnr=$input_eventnr";
		if(!$result = mysql_query($sql, $db))
		    die("Unable to connect to database.".mysql_error());
	}
	if($mode=="edit")
	{
		$sql = "select * from ".$tableprefix."_events where eventnr=$input_eventnr";
		if(!$result = mysql_query($sql, $db))
		    die("Unable to connect to database.".mysql_error());
		if ($myrow = mysql_fetch_array($result))
		{
			$doedit=1;
			list($input_date,$input_time)=explode(" ",$myrow["date"]);
			list($sel_year, $sel_month, $sel_day) = explode("-", $input_date);
			list($tmp_hour, $tmp_min, $null) = explode(":", $input_time);
			if(($tmp_hour>0) || ($tmp_min>0))
			{
				$usetime=1;
				$sel_hour=$tmp_hour;
				$sel_min=$tmp_min;
			}
			$headingtext=$myrow["heading"];
			$eventtext = stripslashes($myrow["text"]);
			$eventtext = str_replace("<BR>", "\n", $eventtext);
			$eventtext = undo_htmlspecialchars($eventtext);
			$eventtext = decode_emoticons($eventtext, $url_emoticons, $db);
			$eventtext = bbdecode($eventtext);
			$eventtext = undo_make_clickable($eventtext);
			$actheadingicon=$myrow["headingicon"];
			$dontsendmail=$myrow["dontemail"];
			$currentviews=$myrow["views"];
			$wap_nopublish=$myrow["wap_nopublish"];
			$wap_short=stripslashes($myrow["wap_short"]);
		}
		$sql="select eva.entrynr as attentry, files.* from ".$tableprefix."_events_attachs eva, ".$tableprefix."_files files where files.entrynr=eva.attachnr and eva.eventnr=$input_eventnr";
		if(!$result = mysql_query($sql, $db))
		    die("Unable to connect to database.".mysql_error());
		$attachements=array();
		while($myrow = mysql_fetch_array($result))
		{
			$hasattach=1;
			array_push($attachements,$myrow);
		}
	}
	if($mode=="update")
	{
		if(!isset($headingicon))
			$headingicon="";
		if(isset($eventtext) && $eventtext)
		{
			if(isset($preview))
			{
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<form method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<?php
				$heading=stripslashes($heading);
				$eventtext=stripslashes($eventtext);
				if(isset($no_wap))
					echo "<input type=\"hidden\" name=\"no_wap\" value=\"1\">";
				if(isset($wap_short))
					echo "<input type=\"hidden\" name=\"rss_short\" value=\"$wap_short\">";
				if(is_konqueror())
					echo "<tr><td></td></tr>";
				if($sessid_url)
					echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
				if(isset($resetviews))
					echo "<input type=\"hidden\" name=\"resetviews\" value=\"1\">";
				if(isset($urlautoencode))
					echo "<input type=\"hidden\" name=\"urlautoencode\" value=\"1\">";
				if(isset($enablespcode))
					echo "<input type=\"hidden\" name=\"enablespcode\" value=\"1\">";
				if(isset($dontsendemail))
					echo "<input type=\"hidden\" name=\"dontsendemail\" value=\"1\">";
				if(isset($immediatlysendemail))
					echo "<input type=\"hidden\" name=\"immediatlysendemail\" value=\"1\">";
?>
<input type="hidden" name="eventlang" value="<?php echo $eventlang?>">
<input type="hidden" name="sel_day" value="<?php echo $sel_day?>">
<input type="hidden" name="sel_month" value="<?php echo $sel_month?>">
<input type="hidden" name="sel_year" value="<?php echo $sel_year?>">
<input type="hidden" name="catnr" value="<?php echo $catnr?>">
<input type="hidden" name="eventtext" value="<?php echo do_htmlentities($eventtext)?>">
<input type="hidden" name="heading" value="<?php echo do_htmlentities($heading)?>">
<input type="hidden" name="headingicon" value="<?php echo $headingicon?>">
<input type="hidden" name="mode" value="update">
<input type="hidden" name="input_eventnr" value="<?php echo $input_eventnr?>">
<input type="hidden" name="frompreview" value="1">
<tr class="inforow"><td align="center" colspan="2"><?php echo $l_previewprelude?></td></tr>
<?php
				if($usetime!=0)
				{
					$displaydate=date($l_admdateformat,mktime($sel_hour,$sel_min,0,$sel_month,$sel_day,$sel_year));
					echo "<input type=\"hidden\" name=\"usetime\" value=\"1\">";
					echo "<input type=\"hidden\" name=\"sel_hour\" value=\"$sel_hour\">";
					echo "<input type=\"hidden\" name=\"sel_min\" value=\"$sel_min\">";
				}
				else
					$displaydate=date($l_admdateformat2,mktime(0,0,0,$sel_month,$sel_day,$sel_year));
				if(isset($urlautoencode))
					$eventtext = make_clickable($eventtext);
				if(isset($enablespcode))
					$eventtext = bbencode($eventtext);
				if(!isset($disableemoticons))
					$eventtext = encode_emoticons($eventtext, $url_emoticons, $db);
				$eventtext = do_htmlentities($eventtext);
				$eventtext = str_replace("\n", "<BR>", $eventtext);
				$eventtext = undo_htmlspecialchars($eventtext);
				echo "<tr><td width=\"2%\" height=\"100%\" align=\"center\" class=\"eventicon\">";
				if($headingicon)
					echo "<img src=\"$url_icons/".$headingicon."\" border=\"0\" align=\"middle\"> ";
				else
					echo "&nbsp;";
				echo "</td>";
				echo "<td align=\"center\"><table width=\"100%\" align=\"center\" cellspacing=\"0\" cellpadding=\"0\">";
				echo "<tr><td align=\"left\" class=\"eventdate\">";
				echo $displaydate."</td></tr>";
				if(strlen($heading)>0)
				{
					echo "<tr class=\"eventheading\"><td align=\"left\">";
					echo do_htmlentities($heading);
					echo "</td></tr>";
				}
				echo "<tr class=\"evententry\"><td align=\"left\">";
				echo $eventtext;
				echo "</td></tr>";
				if(isset($wap_short))
				{
					echo "<tr class=\"displayrow\"><td align=\"left\">";
					echo "$l_wap_short:<br>$wap_short";
					echo "</td></tr>";
				}
				if(isset($new_files))
				{
					while(list($null, $actattach) = each($_POST["new_files"]))
						echo "<input type=\"hidden\" name=\"new_files[]\" value=\"$actattach\">";
				}
				if(isset($del_files))
				{
					while(list($null, $actattach) = each($_POST["del_files"]))
						echo "<input type=\"hidden\" name=\"del_files[]\" value=\"$actattach\">";
				}
				echo "</table></td></tr>";
				echo "<tr class=\"actionrow\"><td align=\"center\" colspan=\"2\"><input class=\"snbutton\" type=\"submit\" value=\"$l_update\">&nbsp;&nbsp;<input class=\"snbutton\" type=\"button\" value=\"$l_back\" onclick=\"self.history.back();\"></td></tr>";
				echo "</table></td></tr></table>";
			}
			else
			{
				if(isset($no_wap))
					$wap_nopublish=1;
				else
					$wap_nopublish=0;
				if(isset($dontsendemail))
					$dontemail=1;
				else
					$dontemail=0;
				$adddate = date("Y-m-d H:i:00");
				if($usetime!=0)
					$entrydate=mktime($sel_hour,$sel_min,0,$sel_month,$sel_day,$sel_year);
				else
					$entrydate=mktime(0,0,0,$sel_month,$sel_day,$sel_year);
				$entrydate=date("Y-m-d H:i:00",$entrydate);
				if(isset($urlautoencode))
					$eventtext = make_clickable($eventtext);
				if(isset($enablespcode))
					$eventtext = bbencode($eventtext);
				if(!isset($disableemoticons))
					$eventtext = encode_emoticons($eventtext, $url_emoticons, $db);
				if($heading)
					$searchtext = stripslashes($heading)." ";
				else
					$searchtext = "";
				$searchtext.= stripslashes(strip_tags($eventtext));
				$searchtext = remove_htmltags($searchtext);
				$searchtext = strtolower($searchtext);
				$searchtext = addslashes($searchtext);
				$eventtext = do_htmlentities($eventtext);
				$eventtext = str_replace("\n", "<BR>", $eventtext);
				$eventtext=addslashes($eventtext);
				$sql = "update ".$tableprefix."_events set headingicon='$headingicon', text='$eventtext', heading='$heading', date='$entrydate', added='$adddate', dontemail=$dontemail, wap_nopublish=$wap_nopublish";
				if(isset($wap_short))
					$sql.=", wap_short='$wap_short'";
				if(isset($resetviews))
					$sql.=", views=0";
				$sql.= " where eventnr=$input_eventnr";
				if(!$result = mysql_query($sql, $db))
				    die("Unable to connect to database.".mysql_error());
				$sql = "update ".$tableprefix."_evsearch set text='$searchtext' where eventnr=$input_eventnr";
				if(!$result = mysql_query($sql, $db))
				    die("Unable to connect to database.".mysql_error());
				$sql = "update ".$tableprefix."_events set date='$entrydate' where linkeventnr='$input_eventnr'";
				if(!$result = mysql_query($sql, $db))
				    die("Unable to connect to database.".mysql_error());
				if(isset($new_files))
				{
					while(list($null, $actattach) = each($_POST["new_files"]))
					{
						$sql="insert into ".$tableprefix."_events_attachs (attachnr,eventnr) values ('$actattach','$input_eventnr')";
						@mysql_query($sql, $db);
					}
				}
				if(isset($del_files))
				{
					while(list($null, $actattach) = each($_POST["del_files"]))
					{
						$sql="delete from ".$tableprefix."_events_attachs where entrynr=$actattach";
						@mysql_query($sql, $db);
					}
				}
				$infotext=$l_entryupdated." (#".$input_eventnr.")";
				list($sel_year, $sel_month, $sel_day) = explode("-", date("Y-m-d"));
				if(($enablesubscriptions==1) && (($subscriptionsendmode==0) || isset($immediatlysendemail)) && (!isset($dontsendemail)) && ($evnewsletterinclude==1))
				{
					$sql = "select * from ".$tableprefix."_events where eventnr=$input_eventnr";
					if(!$result = mysql_query($sql, $db))
					    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
					if($myrow=mysql_fetch_array($result))
					{
						$poster=$myrow["poster"];
						$sentmails=email_single_event($heading, $eventtext, $eventlang, $db, $subject, $simpnewsmail, $simpnewsmailname, $entrydate, $poster, $input_eventnr, $headingicon, $catnr);
						$infotext.="<br>$l_emailssent ($sentmails)";
						if($showsendprogress==1)
						{
							$infotext.="<br><a href=\"javascript:showprogressbox()\"";
							$infotext.=" class=\"actionlink\">$l_reshowprogressbox</a>";
						}
					}
				}
				$usetime=0;
				$wap_nopublish=0;
			}
		}
	}
}
if(!isset($preview))
{
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
	if($infotext)
		echo "<tr class=\"inforow\"><td align=\"center\" colspan=\"2\">$infotext</td></tr>";
	if(($admin_rights<3) && bittst($secsettings,BIT_16) && !bittst($secsettings,BIT_2))
		$numavailcats=0;
	else
		$numavailcats=1;
	if(($admin_rights>2) || !bittst($secsettings,BIT_16))
		$catsql="select cat.* from ".$tableprefix."_categories cat";
	else
		$catsql="select cat.* from ".$tableprefix."_categories cat, ".$tableprefix."_cat_adm ca where cat.catnr=ca.catnr and ca.usernr=".$userdata["usernr"];
	$catsql.=" order by cat.displaypos asc";
	if(!$result = mysql_query($catsql, $db))
		die("<tr bgcolor=\"#cccccc\"><td>Unable to connect to database.".mysql_error());
	$numavailcats+=mysql_num_rows($result);
	if($numavailcats<1)
	{
		echo "<tr class=\"inforow\"><td align=\"center\" valign=\"top\" colspan=\"2\"><b>$l_noeditcats</b></td></tr>";
		echo "</table></td></tr></table>";
		include('./trailer.php');
		exit;
	}
	echo "<form method=\"post\" action=\"$act_script_url\">";
	if(is_konqueror())
		echo "<tr><td></td></tr>";
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
	if($admstorefilter==1)
		echo "<input type=\"hidden\" name=\"dostorefilter\" value=\"1\">";
?>
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<tr class="inputrow"><td align="center" colspan="2"><table width="100%" border="0" align="center" cellspacing="0" cellpadding="0">
<tr class="inputrow"><td align="right" width="50%"><?php echo $l_category?>:</td>
<td align="left" width="40%">
<select class="snselect" name="catnr">
<?php
if($admin_rights>2)
{
	echo "<option value=\"0\"";
	if($catnr==0)
		echo "selected";
	echo ">$l_general</option>";
}
else if(!bittst($secsettings,BIT_16))
{
	echo "<option value=\"0\"";
	if($catnr==0)
		echo "selected";
	echo ">$l_general</option>";
}
else if(bittst($secsettings,BIT_2))
{
	echo "<option value=\"0\"";
	if($catnr==0)
		echo "selected";
	echo ">$l_general</option>";
}
if($myrow=mysql_fetch_array($result))
{
	do{
		echo "<option value=\"".$myrow["catnr"]."\"";
		if($myrow["catnr"]==$catnr)
			echo " selected";
		echo ">".display_encoded($myrow["catname"])."</option>";
	}while($myrow=mysql_fetch_array($result));
}
?>
</select></td><td width="10%">&nbsp;</td></tr>
<tr class="inputrow"><td align="right" width="50%"><?php echo $l_edlang2?>:</td><td align="left" width="40%">
<?php echo language_select($eventlang,"eventlang","../language/")?></td><td align="right" width="10%">
<input class="snbutton" type="submit" value="<?php echo $l_change?>"></td></tr></table></td></tr></form>
<?php
if($catnr<0)
{
	echo "<tr class=\"inforow\"><td align=\"center\" valign=\"top\" colspan=\"2\"><b>$l_selectcat</b></td></tr>";
	echo "</table></td></tr></table>";
	include('./trailer.php');
	exit;
}
if($catnr==0)
	$catname=$l_general;
else
{
	$tempsql="select * from ".$tableprefix."_categories where catnr=$catnr";
	if(!$tempresult = mysql_query($tempsql, $db))
	    die("<tr bgcolor=\"#cccccc\"><td>Unable to connect to database.".mysql_error());
	if($temprow=mysql_fetch_array($tempresult))
		$catname=$temprow["catname"];
	else
		$catname=$l_without." ".$l_category;
}
echo "<tr class=\"inforow\"><td align=\"center\" valign=\"top\" colspan=\"2\"><b>$l_actuallyselected: ".display_encoded($catname).", $eventlang</b></td></tr>";
if($errmsg)
	echo "<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">$errmsg</td></tr>";
if(($admin_rights > 1) && $allowadding)
{
?>
<form name="inputform" method="post" action="<?php echo $act_script_url?>" target="_self" onsubmit="return checkinputform()">
<?php
	if(is_konqueror())
		echo "<tr><td></td></tr>";
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
	if($admstorefilter==1)
		echo "<input type=\"hidden\" name=\"dostorefilter\" value=\"1\">";
?>
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="eventlang" value="<?php echo $eventlang?>">
<tr class="inputrow"><td align="right" width="20%"><?php echo $l_date?>:</td>
<td>
<table width="50%" cellpadding="0" cellspacing="0" border="0" align="left">
<tr>
<td align="center" width="20%"><?php echo $l_day?></td>
<td align="center" width="20%"><?php echo $l_month?></td>
<td align="center" width="20%"><?php echo $l_year?></td>
</tr>
<tr>
<td align="center"><select name="sel_day">
<?php
for($i=1;$i<32;$i++)
{
	echo "<option value=\"$i\"";
	if($i==$sel_day)
		echo " selected";
	echo ">$i</option>";
}
?>
</select></td>
<td align="center"><select name="sel_month">
<?php
for($i=1;$i<13;$i++)
{
	echo "<option value=\"$i\"";
	if($i==$sel_month)
		echo " selected";
	echo ">".$l_monthname[$i-1]."</option>";
}
?>
</select></td>
<td align="center"><select name="sel_year">
<?php
for($i=$sel_year-$yearrange;$i<$sel_year+$yearrange+1;$i++)
{
	echo "<option value=\"$i\"";
	if($i==$sel_year)
		echo " selected";
	echo ">$i</option>";
}
?>
</select></td>
</tr>
</table></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_time?>:
</td><td>
<input type="checkbox" onclick="use_time(this.checked)" name="usetime" value="1" <?php if($usetime==1) echo "checked"?>>
<?php echo $l_usetime?><br>
<table width="100%"><tr>
<td align="center" width="20%"><?php echo $l_hour?></td><td align="center" width="20%"><?php echo $l_minutes?></td>
<td width="60%">&nbsp;</td>
</tr>
<tr>
<td align="center">
<select name="sel_hour" <?php if($usetime==0) echo "disabled"?>>
<?php
for($i=0;$i<24;$i++)
{
	echo "<option value=\"$i\"";
	if($i==$sel_hour)
		echo " selected";
	echo ">$i</option>";
}
?>
</select>
</td>
<td align="center">
<select name="sel_min" <?php if($usetime==0) echo "disabled"?>>
<?php
for($i=0;$i<60;$i++)
{
	echo "<option value=\"$i\"";
	if($i==$sel_min)
		echo " selected";
	echo ">$i</option>";
}
?>
</select>
</td>
</tr></table>
</td></tr>
<tr class="inputrow"><td align="right" valign="top" width="20%"><?php echo $l_heading?>:</td>
<td><input class="sninput" type="text" name="heading" value="<?php echo $headingtext?>" size="40" maxlength="80"></td></tr>
<?php
if(($admin_rights>2) || !bittst($userdata["addoptions"],BIT_1))
{
	echo "<tr class=\"inputrow\"><td align=\"right\" valign=\"top\" width=\"20%\">$l_headingicon:</td>";
	echo "<td valign=\"middle\" nowrap>";
	if(!isset($actheadingicon))
		$actheadingicon="";
	icon_selector($actheadingicon,"headingicon");
}
else
	if(isset($actheadingicon))
		echo "<input type=\"hidden\" name=\"headingicon\" value=\"".do_htmlentities($actheadingicon)."\">";
?>
<tr class="inputrow"><td align="right" valign="top" width="20%"><?php echo $l_text?>:</td>
<td align="left"><textarea class="sninput" name="eventtext" rows="10" cols="50">
<?php
if(isset($doedit) || isset($transfer) || isset($doclone))
	echo $eventtext;
echo "</textarea><br>";
if(($admin_rights>2) || !bittst($userdata["addoptions"],BIT_2))
	display_smiliebox("eventtext");
if(($admin_rights>2) || !bittst($userdata["addoptions"],BIT_3))
	display_bbcode_buttons($l_bbbuttons,"eventtext");
echo "</td></tr>";
if(isset($doedit))
{
	echo "<input type=\"hidden\" name=\"mode\" value=\"update\">";
	echo "<input type=\"hidden\" name=\"input_eventnr\" value=\"$input_eventnr\">";
}
else
	echo "<input type=\"hidden\" name=\"mode\" value=\"add\">";
if(isset($tmpdata))
	echo "<input type=\"hidden\" name=\"tmpdata\" value=\"$tmpdata\">";
if(isset($delpropose))
	echo "<input type=\"hidden\" name=\"delpropose\" value=\"1\">";
if($wap_enable==1)
{
	echo "<tr class=\"inputrow\"><td align=\"right\" valign=\"top\" width=\"20%\">";
	echo "$l_wap_short :";
	echo "</td>";
	echo "<td align=\"left\"><textarea class=\"sninput\" name=\"wap_short\" rows=\"5\" cols=\"50\">";
	if(isset($doedit))
		echo $wap_short;
	echo "</textarea></td></tr>";
}
if(($admin_rights>2) || !bittst($userdata["addoptions"],BIT_2) || !bittst($userdata["addoptions"],BIT_3))
{
	echo "<tr class=\"optionrow\"><td align=\"right\" valign=\"top\">$l_options:</td><td align=\"left\">";
	if(($admin_rights>2) || !bittst($userdata["addoptions"],BIT_3))
	{
		echo "<input type=\"checkbox\" name=\"urlautoencode\" value=\"1\" checked> $l_urlautoencode<br>";
		echo "<input type=\"checkbox\" name=\"enablespcode\" value=\"1\" checked> $l_enablespcode<br>";
	}
	if(($admin_rights>2) || !bittst($userdata["addoptions"],BIT_2))
		echo "<input type=\"checkbox\" name=\"disableemoticons\" value=\"1\"> $l_disableemoticons";
	else
		echo "<input type=\"hidden\" name=\"disableemoticons\" value=\"1\">";
	echo "</td></tr>";
}
echo "<input type=\"hidden\" name=\"catnr\" value=\"$catnr\">";
if(($enablesubscriptions==1) && ($evnewsletterinclude==1))
{
	if(($subscriptionsendmode==1) && ($admin_rights>=$sublevel))
	{
		echo "<tr class=\"optionrow\"><td>&nbsp;</td><td><input onClick=\"sendnow()\" type=\"checkbox\" name=\"immediatlysendemail\" value=\"1\"";
		if($dontsendmail==1)
			echo " disabled";
		echo "> $l_immediatlysendnewsletter</td></tr>";
	}
	echo "<tr class=\"optionrow\"><td>&nbsp;</td><td><input onClick=\"dontemail()\" type=\"checkbox\" name=\"dontsendemail\" value=\"1\"";
	if($dontsendmail==1)
		echo "checked";
	echo "> $l_dontsendinnewsletter</td></tr>";
}
if(!isset($doedit))
	echo "<tr class=\"optionrow\"><td>&nbsp;</td><td><input type=\"checkbox\" name=\"postasnews\" value=\"1\"> $l_crosspostasnews</td></tr>";
if($wap_enable==1)
{
	echo "<tr class=\"optionrow\"><td>&nbsp;</td><td>";
	echo "<input type=\"checkbox\" name=\"no_wap\" value=\"1\"";
	if($wap_nopublish==1)
		echo "checked";
	echo "> $l_wap_no_publish</td></tr>";
}
if(isset($doedit))
{
?>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="resetviews" value="1"> <?php echo $l_resetviews?>
<?php echo " ($l_current: ".$currentviews.")"?></td></tr>
<?php
}
if($upload_avail)
{
?>
<INPUT TYPE="hidden" name="MAX_FILE_SIZE" value="<?php echo $maxfilesize?>">
<tr class="inputrow"><td align="right" width="30%" valign="top"><?php echo $l_attachfile?>:</td>
<td>
<?php
	echo "<select id=\"new_files\" name=\"new_files[]\" size=\"5\" multiple>";
	if(isset($transfer) && isset($tmpdata) && isset($eventlang))
	{
		$tmpsql="select f.* from ".$tableprefix."_files f, ".$tableprefix."_tmpevents_attachs tea where tea.eventnr=$tmpdata and f.entrynr=tea.attachnr";
		if(!$tmpresult = mysql_query($tmpsql, $db))
		    die("Could not connect to the database.".mysql_error());
		if($tmprow=mysql_fetch_array($tmpresult))
			echo "<option value=\"".$tmprow["entrynr"]."\" selected>".$tmprow["filename"]."</option>";
	}
	if(isset($doclone))
	{
		$tmpsql="select f.* from ".$tableprefix."_files f, ".$tableprefix."_events_attachs eva where eva.eventnr=$input_eventnr and f.entrynr=eva.attachnr";
		if(!$tmpresult = mysql_query($tmpsql, $db))
		    die("Could not connect to the database.".mysql_error());
		if($tmprow=mysql_fetch_array($tmpresult))
			echo "<option value=\"".$tmprow["entrynr"]."\" selected>".$tmprow["filename"]."</option>";
	}
	echo "</select><br>";
	echo "<a class=\"listlink\" href=\"javascript:openWindow2('".do_url_session("dbfiles.php?$langvar=$act_lang&mode=1")."',20,20,620,300);\">";
	echo "$l_select_file_from_db</a>";
	if($hasattach==1)
	{
		echo "<br><br><b>$l_actualattached:</b><br><table width=\100%\">";
    	while(list($null, $currAttachement) = each($attachements))
    	{
    		echo "<tr><td width=\"50%\" valign=\"top\">";
			echo $currAttachement["filename"]." (".$currAttachement["filesize"]." Bytes)";
			echo "</td><td width=\"50%\" valign=\"top\"><input type=\"checkbox\" name=\"del_files[]\" value=\"".$currAttachement["attentry"]."\">$l_delattach";
			echo "</td></tr>";
		}
		echo "</table>";
	}
	echo "</td></tr>";
}
?>
<tr class="actionrow"><td align="center" colspan="2"><input class="snbutton" name="dosubmit" type="submit" value="<?php if(isset($doedit)) echo $l_update; else echo $l_add?>">&nbsp;&nbsp;
<?php
	if($admaltprv==1)
		echo "<input class=\"snbutton\" type=\"button\" onClick=\"dopreview()\" name=\"preview\" value=\"$l_preview\">";
	else
		echo "<input class=\"snbutton\" type=\"submit\" name=\"preview\" value=\"$l_preview\">";
	echo "</td></tr></form>";
}
?>
</table></td></tr></table>
<p></p>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<form method="post" action="<?php echo $act_script_url?>#entrylist">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="eventlang" value="<?php echo $eventlang?>">
<input type="hidden" name="catnr" value="<?php echo $catnr?>">
<?php
if(is_konqueror())
	echo "<tr><td></td></tr>";
if($sessid_url)
	echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
if($admstorefilter==1)
	echo "<input type=\"hidden\" name=\"dostorefilter\" value=\"1\">";
echo "<tr class=\"inputrow\"><td align=\"center\" valign=\"top\" colspan=\"2\">";
echo "<a name=\"entrylist\"><a>";
echo $l_show.": ";
echo "<select name=\"limiting\">";
echo "<option value=\"0\"";
if($limiting==0)
	echo " selected";
echo ">$l_allentries</option>";
echo "<option value=\"1\"";
if($limiting==1)
	echo " selected";
echo ">$l_ownentries</option>";
echo "</select> ";
echo "<input class=\"snbutton\" type=\"submit\" value=\"$l_change\">";
echo "</td></tr></form></table></td></tr></table>";
$sql = "select * from ".$tableprefix."_events where lang='$eventlang' and category=$catnr ";
if($limiting==1)
	$sql.="and posterid=".$userdata["usernr"]." ";
switch($sorting)
{
	case 11:
		$sql.="order by eventnr asc";
		break;
	case 12:
		$sql.="order by eventnr desc";
		break;
	case 21:
		$sql.="order by heading asc";
		break;
	case 22:
		$sql.="order by heading desc";
		break;
	case 31:
		$sql.="order by date asc";
		break;
	case 32:
		$sql.="order by date desc";
		break;
	case 41:
		$sql.="order by views asc";
		break;
	case 42:
		$sql.="order by views desc";
		break;
}
if(!$result = mysql_query($sql, $db))
    die("<tr bgcolor=\"#cccccc\"><td>Unable to connect to database.<br>".mysql_error()."<br>".$sql);
$numentries=mysql_num_rows($result);
if($admepp>0)
{
	if(($start>0) && ($numentries>$admepp))
	{
		$sql .=" limit $start,$admepp";
	}
	else
	{
		$sql .=" limit $admepp";
	}
	if(!$result = mysql_query($sql, $db))
	    die("<tr bgcolor=\"#cccccc\"><td>Unable to connect to database.".mysql_error());
	if(mysql_num_rows($result)>0)
	{
		echo "<table align=\"center\" width=\"80%\" CELLPADDING=\"1\" CELLSPACING=\"0\" border=\"0\" valign=\"top\">";
		echo "<tr><TD BGCOLOR=\"#000000\">";
		echo "<table align=\"center\" width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">";
		if(($admepp+$start)>$numentries)
			$displayresults=$numentries;
		else
			$displayresults=($admepp+$start);
		$displaystart=$start+1;
		$displayend=$displayresults;
		echo "<tr class=\"pagenav\"><td align=\"center\">";
		echo "<a id=\"list\"><b>$l_page ".ceil(($start/$admepp)+1)."/".ceil(($numentries/$admepp))."</b><br><b>($l_entries $displaystart - $displayend $l_of $numentries)</b></a>";
		echo "</td></tr></table></td></tr></table>";
	}
}
if(($admepp>0) && ($numentries>$admepp))
{
	$baselink="$act_script_url?$langvar=$act_lang&eventlang=$eventlang&catnr=$catnr";
	echo "<table align=\"center\" width=\"80%\" CELLPADDING=\"1\" CELLSPACING=\"0\" border=\"0\" valign=\"top\">";
	echo "<tr><TD BGCOLOR=\"#000000\">";
	echo "<table align=\"center\" width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">";
	echo "<tr class=\"pagenav\"><td align=\"center\">";
	echo "<b>$l_page</b> ";
	if(floor(($start+$admepp)/$admepp)>1)
	{
		echo "<a class=\"pagenav\" href=\"".do_url_session("$baselink&start=0")."#list\">";
		echo "<img src=\"../gfx/first.gif\" border=\"0\" align=\"absmiddle\" title=\"$l_page_first\" alt=\"$l_page_first\">";
		echo "</a> ";
		echo "<a class=\"pagenav\" href=\"".do_url_session("$baselink&start=".($start-$admepp))."#list\">";
		echo "<img src=\"../gfx/prev.gif\" border=\"0\" align=\"absmiddle\" title=\"$l_page_back\" alt=\"$l_page_back\">";
		echo "</a> ";
	}
	for($i=1;$i<($numentries/$admepp)+1;$i++)
	{
		if(floor(($start+$admepp)/$admepp)!=$i)
		{
			echo "<a class=\"pagenav\" href=\"".do_url_session("$baselink&start=".(($i-1)*$admepp));
			echo "#list\"><b>[$i]</b></a> ";
		}
		else
			echo "<b>($i)</b> ";
	}
	if($start < (($i-2)*$admepp))
	{
		echo "<a class=\"pagenav\" href=\"".do_url_session("$baselink&start=".($start+$admepp))."#list\">";
		echo "<img src=\"../gfx/next.gif\" border=\"0\" align=\"absmiddle\" title=\"$l_page_forward\" alt=\"$l_page_forward\">";
		echo "</a> ";
		echo "<a class=\"pagenav\" href=\"".do_url_session("$baselink&start=".(($i-2)*$admepp))."#list\">";
		echo "<img src=\"../gfx/last.gif\" border=\"0\" align=\"absmiddle\" title=\"$l_page_last\" alt=\"$l_page_last\">";
		echo "</a> ";
	}
	echo "</font></td></tr></table></td></tr></table>";
}
if($myrow = mysql_fetch_array($result))
{
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<form name="eventlist" method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="mode" value="massdel">
<input type="hidden" name="eventlang" value="<?php echo $eventlang?>">
<input type="hidden" name="catnr" value="<?php echo $catnr?>">
<?php
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
	$baseurl=$act_script_url."?".$langvar."=".$act_lang;
	if($admstorefilter==1)
		$baseurl.="&dostorefilter=1";
	if(isset($eventlang))
		$baseurl.="&eventlang=$eventlang";
	if(isset($catnr))
		$baseurl.="&catnr=$catnr";
	$maxsortcol=4;
	echo "<tr class=\"rowheadings\">";
	echo "<td><a id=\"resultlist\">&nbsp;</a></td>";
	echo "<td align=\"center\"><b>";
	$sorturl=getSortURL($sorting, 1, $maxsortcol, $baseurl, "resultlist");
	echo "<a class=\"sorturl\" href=\"".do_url_session($sorturl)."\">";
	echo "#</a>";
	echo getSortMarker($sorting, 1, $maxsortcol);
	echo "</b></td>";
	echo "<td>&nbsp;</td>";
	echo "<td align=\"center\"><b>";
	$sorturl=getSortURL($sorting, 2, $maxsortcol, $baseurl, "resultlist");
	echo "<a class=\"sorturl\" href=\"".do_url_session($sorturl)."\">";
	echo "$l_entry</a>";
	echo getSortMarker($sorting, 2, $maxsortcol);
	echo "</b></td>";
	echo "<td align=\"center\"><b>";
	$sorturl=getSortURL($sorting, 3, $maxsortcol, $baseurl, "resultlist");
	echo "<a class=\"sorturl\" href=\"".do_url_session($sorturl)."\">";
	echo "$l_date</a>";
	echo getSortMarker($sorting, 3, $maxsortcol);
	echo "</b></td>";
	echo "<td align=\"center\"><b>";
	$sorturl=getSortURL($sorting, 4, $maxsortcol, $baseurl, "resultlist");
	echo "<a class=\"sorturl\" href=\"".do_url_session($sorturl)."\">";
	echo "$l_views</a>";
	echo getSortMarker($sorting, 4, $maxsortcol);
	echo "</b></td>";
	echo "<td colspan=\"7\">&nbsp;</td>";
	echo "</tr>";
	do{
		$act_id=$myrow["eventnr"];
		if($myrow["linkeventnr"]==0)
			$entrydata=$myrow;
		else
		{
			$tmpsql="select * from ".$tableprefix."_events where eventnr=".$myrow["linkeventnr"];
			if(!$tmpresult = mysql_query($tmpsql, $db))
			    die("<tr bgcolor=\"#cccccc\"><td>Error connecting to database.".mysql_error());
			if(!$tmprow=mysql_fetch_array($tmpresult))
			    die("<tr bgcolor=\"#cccccc\"><td>broken event link (linking to: ".$myrow["linkeventnr"]." - entry#: ".$myrow["eventnr"].").</td></tr>");
			$entrydata=$tmprow;
		}
		$eventtext=stripslashes($entrydata["text"]);
		$eventtext = undo_htmlspecialchars($eventtext);
		if($admentrychars>0)
		{
			$eventtext=strip_tags($eventtext);
			$eventtext=substr($eventtext,0,$admentrychars);
			$eventtext.="[...]";
		}
		if($admonlyentryheadings==0)
		{
			if($entrydata["heading"])
				$displaytext="<b>".$entrydata["heading"]."</b><br>".$eventtext;
			else
				$displaytext=$eventtext;
		}
		else
		{
			if($entrydata["heading"])
				$displaytext="<b>".$entrydata["heading"]."</b>";
			else
			{
				$displaytext=strip_tags($myrow["text"]);
				if($admentrychars>0)
					$displaytext=substr($displaytext,0,$admentrychars);
				else
					$displaytext=substr($displaytext,0,20);
				$displaytext.="[...]";
			}
		}
		echo "<tr valign=\"top\">";
		if($admrestrict==1)
		{
			if(($admin_rights>2) || bittst($userdata["addoptions"],BIT_5))
				$allowactions=true;
			else
			{
				if($entrydata["posterid"]==$userdata["usernr"])
					$allowactions=true;
			}
		}
		else
			if($admin_rights > 1)
				$allowactions=$allowadding;
		echo "<td class=\"inputrow\" align=\"center\" width=\"1%\">";
		if($allowactions)
			echo "<input type=\"checkbox\" name=\"eventnr[]\" value=\"$act_id\">";
		else
			echo "&nbsp;";
		echo "</td>";
		echo "<td class=\"displayrow\" align=\"center\" width=\"8%\">";
		if($myrow["linkeventnr"]!=0)
			echo "<img src=\"gfx/link_small.gif\" border=\"0\" align=\"top\" title=\"$l_islink: ".$myrow["linkeventnr"]."\" alt=\"$l_islink: ".$myrow["linkeventnr"]."\"> ";
		else
		{
			$tmpsql="select * from ".$tableprefix."_events where linkeventnr=".$myrow["eventnr"];
			if(!$tmpresult = mysql_query($tmpsql, $db))
				die("<tr bgcolor=\"#cccccc\"><td>Unable to connect to database.".mysql_error());
			if(mysql_num_rows($tmpresult)>0)
				echo "<img src=\"gfx/link_target.gif\" border=\"0\" align=\"top\" title=\"$l_islinktarget\" alt=\"$l_islinktarget\"> ";
		}
		if($myrow["linkeventnr"]==0)
			$showurl=do_url_session("evshow.php?$langvar=$act_lang&eventnr=".$myrow["eventnr"]);
		else
			$showurl=do_url_session("evshow.php?$langvar=$act_lang&eventnr=".$myrow["linkeventnr"]);
		echo "<a class=\"shdetailslink\" href=\"javascript:openWindow3('$showurl','nShow',20,20,400,200);\">";
		echo $myrow["eventnr"]."</a></td>";
		echo "<td class=\"eventicon\" align=\"center\" width=\"2%\">";
		if($entrydata["headingicon"])
			echo "<img src=\"$url_icons/".$entrydata["headingicon"]."\" border=\"0\" align=\"bottom\">";
		else
			echo "&nbsp;";
		list($curdate,$curtime)=explode(" ",$entrydata["date"]);
		list($curyear,$curmonth,$curday)=explode("-",$curdate);
		list($curhour,$curmin,$cursec)=explode(":",$curtime);
		$tmpdate=mktime($curhour,$curmin,$cursec,$curmonth,$curday,$curyear);
		if(($curhour>0) || ($curmin>0) || ($cursec>0))
			$displaydate=date($l_admdateformat,$tmpdate);
		else
			$displaydate=date($l_admdateformat2,$tmpdate);
		echo "</td><td class=\"evententry\" align=\"left\">";
		echo "$displaytext</td>";
		echo "<td class=\"eventdate\" align=\"center\" width=\"20%\">";
		echo $displaydate."</td>";
		echo "<td class=\"displayrow\" align=\"right\" widht=\"10%\">";
		echo $entrydata["views"]."</td>";
		if($allowactions)
		{
			echo "<td class=\"adminactions\" align=\"center\" height=\"16\" width=\"16\">";
			if($myrow["linkeventnr"]==0)
			{
				$tempsql="select * from ".$tableprefix."_events_attachs where eventnr=$act_id";
				if(!$tempresult=mysql_query($tempsql,$db))
				    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
				$numattachs=mysql_num_rows($tempresult);
				if($numattachs>0)
				{
					$dellink=do_url_session("$act_script_url?$langvar=$act_lang&eventlang=$eventlang&mode=delattach&input_eventnr=$act_id&catnr=$catnr");
					if($admdelconfirm==2)
						echo "<a class=\"listlink2\" href=\"javascript:confirmDel('$l_attachements - Event #$act_id','$dellink')\">";
					else
						echo "<a class=\"listlink2\" href=\"$dellink\">";
					echo "<img height=\"16\" width=\"16\" src=\"gfx/delattach.gif\" border=\"0\" align=\"absmiddle\" alt=\"$l_delattach ($numattachs)\" title=\"$l_delattach ($numattachs)\"></a>";
				}
				else
					echo "&nbsp;";
			}
			else
				echo "&nbsp;";
			echo "</td>";
			echo "<td class=\"adminactions\" align=\"center\" height=\"16\" width=\"16\">";
			if($myrow["linkeventnr"]==0)
				echo "<a href=\"".do_url_session("$act_script_url?$langvar=$act_lang&eventlang=$eventlang&mode=catlink&input_eventnr=$act_id&catnr=$catnr")."\"><img height=\"16\" width=\"16\" src=\"gfx/link.gif\" border=\"0\" title=\"$l_catlink\" alt=\"$l_catlink\"></a>";
			else
				echo "&nbsp;";
			echo "</td>";
			echo "<td class=\"adminactions\" align=\"center\" height=\"16\" width=\"16\">";
			if($myrow["linkeventnr"]==0)
				echo "<a href=\"".do_url_session("$act_script_url?$langvar=$act_lang&eventlang=$eventlang&mode=catmove&input_eventnr=$act_id&catnr=$catnr")."\"><img height=\"16\" width=\"16\" src=\"gfx/move.gif\" border=\"0\" title=\"$l_catmove\" alt=\"$l_catmove\"></a>";
			else
				echo "&nbsp;";
			echo "</td>";
			echo "<td class=\"adminactions\" align=\"center\" height=\"16\" width=\"16\">";
			if($myrow["linkeventnr"]==0)
				echo "<a href=\"".do_url_session("$act_script_url?$langvar=$act_lang&eventlang=$eventlang&mode=clone&input_eventnr=$act_id&catnr=$catnr")."\"><img height=\"16\" width=\"16\" src=\"gfx/clone.gif\" border=\"0\" title=\"$l_clone\" alt=\"$l_clone\"></a>";
			else
				echo "<a href=\"".do_url_session("$act_script_url?$langvar=$act_lang&eventlang=".$entrydata["lang"]."&mode=clone&input_eventnr=".$entrydata["eventnr"]."&catnr=".$entrydata["category"])."\"><img height=\"16\" width=\"16\" src=\"gfx/clone.gif\" border=\"0\" title=\"$l_clone\" alt=\"$l_clone\"></a>";
			echo "</td>";
			echo "<td class=\"adminactions\" align=\"center\" height=\"16\" width=\"16\">";
			if($myrow["linkeventnr"]==0)
				echo "<a href=\"".do_url_session("$act_script_url?$langvar=$act_lang&eventlang=$eventlang&mode=recur&input_eventnr=$act_id&catnr=$catnr")."\"><img height=\"16\" width=\"16\" src=\"gfx/cal.gif\" border=\"0\" title=\"$l_recurevent\" alt=\"$l_recurevent\"></a>";
			else
				echo "&nbsp;";
			echo "</td>";
			echo "<td class=\"adminactions\" align=\"center\" height=\"16\" width=\"16\">";
			if($myrow["linkeventnr"]==0)
				echo "<a href=\"".do_url_session("$act_script_url?$langvar=$act_lang&eventlang=$eventlang&mode=edit&input_eventnr=$act_id&catnr=$catnr")."\"><img height=\"16\" width=\"16\" src=\"gfx/edit.gif\" border=\"0\" title=\"$l_edit\" alt=\"$l_edit\"></a>";
			else
				echo "<a href=\"".do_url_session("$act_script_url?$langvar=$act_lang&eventlang=".$entrydata["lang"]."&mode=edit&input_eventnr=".$entrydata["eventnr"]."&catnr=".$entrydata["category"])."\"><img height=\"16\" width=\"16\" src=\"gfx/edit.gif\" border=\"0\" title=\"$l_editoriginalentry\" alt=\"$l_editoriginalentry\"></a>";
			echo "</td>";
			echo "<td class=\"adminactions\" align=\"center\" height=\"16\" width=\"16\">";
			$dellink=do_url_session("$act_script_url?$langvar=$act_lang&eventlang=$eventlang&mode=del&input_eventnr=$act_id&catnr=$catnr");
			if($admdelconfirm==2)
				echo "<a class=\"listlink2\" href=\"javascript:confirmDel('Event #$act_id','$dellink')\">";
			else
				echo "<a class=\"listlink2\" href=\"$dellink\">";
			echo "<img height=\"16\" width=\"16\" src=\"gfx/delete.gif\" border=\"0\" title=\"$l_delete\" alt=\"$l_delete\"></a></td>";
		}
		else
		{
			for($i=0;$i<7;$i++)
			{
				echo "<td class=\"adminactions\" align=\"center\" height=\"16\" width=\"16\">";
				echo "&nbsp;";
				echo "</td>";
			}
		}
		echo "</tr>";
	}while($myrow=mysql_fetch_array($result));
	if($admin_rights > 1)
	{
		echo "<tr class=\"actionrow\"><td colspan=\"13\" align=\"left\"><input class=\"snbutton\" type=\"button\" onclick=\"massdel()\" value=\"$l_delselected\">";
		echo "&nbsp; <input class=\"snbutton\" type=\"button\" onclick=\"massmove()\" value=\"$l_moveselected\">";
		echo "&nbsp; <input class=\"snbutton\" type=\"button\" onclick=\"checkAll(document.eventlist)\" value=\"$l_checkall\">";
		echo "&nbsp; <input class=\"snbutton\" type=\"button\" onclick=\"uncheckAll(document.eventlist)\" value=\"$l_uncheckall\">";
		echo "</td></tr>";
	}
	echo "</form></table></td></tr></table>";
}
if(($admepp>0) && ($numentries>$admepp))
{
	$baselink="$act_script_url?$langvar=$act_lang&eventlang=$eventlang&catnr=$catnr";
	echo "<table align=\"center\" width=\"80%\" CELLPADDING=\"1\" CELLSPACING=\"0\" border=\"0\" valign=\"top\">";
	echo "<tr><TD BGCOLOR=\"#000000\">";
	echo "<table align=\"center\" width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">";
	echo "<tr class=\"pagenav\"><td align=\"center\">";
	echo "<b>$l_page</b> ";
	if(floor(($start+$admepp)/$admepp)>1)
	{
		echo "<a class=\"pagenav\" href=\"".do_url_session("$baselink&start=0")."#list\">";
		echo "<img src=\"../gfx/first.gif\" border=\"0\" align=\"absmiddle\" title=\"$l_page_first\" alt=\"$l_page_first\">";
		echo "</a> ";
		echo "<a class=\"pagenav\" href=\"".do_url_session("$baselink&start=".($start-$admepp))."#list\">";
		echo "<img src=\"../gfx/prev.gif\" border=\"0\" align=\"absmiddle\" title=\"$l_page_back\" alt=\"$l_page_back\">";
		echo "</a> ";
	}
	for($i=1;$i<($numentries/$admepp)+1;$i++)
	{
		if(floor(($start+$admepp)/$admepp)!=$i)
		{
			echo "<a class=\"pagenav\" href=\"".do_url_session("$baselink&start=".(($i-1)*$admepp));
			echo "#list\"><b>[$i]</b></a> ";
		}
		else
			echo "<b>($i)</b> ";
	}
	if($start < (($i-2)*$admepp))
	{
		echo "<a class=\"pagenav\" href=\"".do_url_session("$baselink&start=".($start+$admepp))."#list\">";
		echo "<img src=\"../gfx/next.gif\" border=\"0\" align=\"absmiddle\" title=\"$l_page_forward\" alt=\"$l_page_forward\">";
		echo "</a> ";
		echo "<a class=\"pagenav\" href=\"".do_url_session("$baselink&start=".(($i-2)*$admepp))."#list\">";
		echo "<img src=\"../gfx/last.gif\" border=\"0\" align=\"absmiddle\" title=\"$l_page_last\" alt=\"$l_page_last\">";
		echo "</a> ";
	}
	echo "</font></td></tr></table></td></tr></table>";
}
}
include('./trailer.php')
?>
