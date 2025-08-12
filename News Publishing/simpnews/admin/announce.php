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
if(!isset($announcelang))
	$announcelang=$act_lang;
if(!isset($start))
	$start=0;
$useannouncedate=0;
$wap_nopublish=0;
require_once('./auth.php');
$page_title=$l_announcements;
$bbcbuttons=true;
$page="announce";
require_once('./heading.php');
include_once("./includes/bbcode_buttons.inc");
include_once("./includes/email_functions.inc");
$infotext="";
if(!isset($headingtext))
	$headingtext="";
if(!isset($sorting))
	$sorting=32;
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
		if(sn_array_key_exists($admcookievals,"announce_catnr"))
			$catnr=$admcookievals["announce_catnr"];
		if(sn_array_key_exists($admcookievals,"announce_lang"))
			$announcelang=$admcookievals["announce_lang"];
		if(sn_array_key_exists($admcookievals,"announce_sorting"))
			$sorting=$admcookievals["announce_sorting"];
		if(sn_array_key_exists($admcookievals,"announce_limiting"))
			$limiting=$admcookievals["announce_limiting"];
	}
}
if(!isset($limiting))
	$limiting=0;
if(!isset($catnr))
{
	if(($admin_rights<3) && bittst($secsettings,BIT_17) && !bittst($secsettings,BIT_4))
		$catnr=-1;
	else
		$catnr=0;
}
if(($admin_rights<3) && bittst($secsettings,BIT_17) && !bittst($secsettings,BIT_4) && ($catnr==0))
	$catnr=-1;
$hasattach=0;
$errmsg="";
$allowadding=true;
if($admin_rights < $anlevel)
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
	die("$l_functionnotallowed");
}
if(($userdata["rights"]==2) && !bittst($secsettings,BIT_3))
	$allowadding=false;
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
	else if(!bittst($secsettings,BIT_4))
			$allowadding=false;
}
$announcedate=date("Y-m-d H:i:s");
$expiredate=date("Y-m-d 23:59");
$firstdate=date("Y-m-d 00:00");
$errmsg="";
if(isset($mode) && ($admin_rights>1))
{
	if($mode=="catmove")
	{
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="inforow"><td align="center" colspan="2"><?php echo "$l_catmove ($l_announce# $input_announcenr)"?></td></tr>
<form method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="announcelang" value="<?php echo $announcelang?>">
<input type="hidden" name="catnr" value="<?php echo $catnr?>">
<input type="hidden" name="input_announcenr" value="<?php echo $input_announcenr?>">
<input type="hidden" name="mode" value="domove">
<?php
			if(is_konqueror())
				echo "<tr><td></td></tr>";
			if($sessid_url)
				echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
			echo "<tr class=\"inputrow\"><td align=\"right\" width=\"30%\">$l_destcat:</td><td>";
			$numdestcats=0;
			if(($catnr!=0) && (bittst($secsettings,BIT_4) || ($userdata["rights"]>2)))
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
				if(($catnr!=0) && (bittst($secsettings,BIT_4) || ($userdata["rights"]>2)))
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
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang&catnr=$catnr&announcelang=$announcelang")."\">$l_announcementlist</a></div>";
			include('./trailer.php');
			exit;
	}
	if($mode=="clone")
	{
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="inforow"><td align="center" colspan="2"><?php echo "$l_clone ($l_announce# $input_announcenr)"?></td></tr>
<form method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="input_announcenr" value="<?php echo $input_announcenr?>">
<input type="hidden" name="mode" value="doclone">
<?php
			if(is_konqueror())
				echo "<tr><td></td></tr>";
			if($sessid_url)
				echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
			echo "<tr class=\"inputrow\"><td align=\"right\" width=\"30%\">$l_destcat:</td><td>";
			$numdestcats=0;
			if(bittst($secsettings,BIT_4) || ($userdata["rights"]>2))
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
				echo "<select name=\"newcatnr\">";
				echo "<option value=\"-1\"></option>";
				if(bittst($secsettings,BIT_4) || ($userdata["rights"]>2))
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
				echo language_select($announcelang,"announcelang","../language/");
				echo "</td></tr>";
				echo "<tr class=\"actionrow\"><td align=\"center\" colspan=\"2\">";
				echo "<input class=\"snbutton\" type=\"submit\" name=\"submit\" value=\"$l_clone\">";
				echo "</td></tr>";
			}
			echo "</form></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang&catnr=$catnr&announcelang=$announcelang")."\">$l_announcementlist</a></div>";
			include('./trailer.php');
			exit;
	}
	if($mode=="doclone")
	{
		if(!isset($newcatnr) || ($newcatnr<0))
		{
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="inforow"><td align="center" colspan="2"><?php echo "$l_clone ($l_announce# $input_announcenr)"?></td></tr>
<?php
			echo "<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">$l_nodestcat</td></tr>";
			echo "<tr class=\"actionrow\"><td align=\"center\" colspan=\"2\">";
			echo "<a href=\"javascript:history.back()\">$l_back</a>";
			echo "</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang&catnr=$catnr&announcelang=$announcelang")."\">$l_announcementlist</a></div>";
			include('./trailer.php');
			exit;
		}
		$sql="select * from ".$tableprefix."_announce where entrynr=$input_announcenr";
		if(!$result = mysql_query($sql, $db))
		    die("Could not connect to the database.".mysql_error());
		if($myrow=mysql_fetch_array($result))
		{
			$doclone=1;
			$catnr=$newcatnr;
			$tmptxt=stripslashes($myrow["text"]);
			$tmptxt = str_replace("<BR>", "\n", $tmptxt);
			$tmptxt = undo_htmlspecialchars($tmptxt);
			$tmptxt = decode_emoticons($tmptxt, $url_emoticons, $db);
			$tmptxt = bbdecode($tmptxt);
			$tmptxt = undo_make_clickable($tmptxt);
			$announcetext = $tmptxt;
			$actheadingicon=$myrow["headingicon"];
			$headingtext=display_encoded($myrow["heading"]);
			$announcedate=$myrow["date"];
			$useannouncedate=1;
			$wap_nopublish=$myrow["wap_nopublish"];
			$wap_short=stripslashes($myrow["wap_short"]);
			if($myrow["expiredate"]>0)
			{
				$useexpiredate=1;
				$expiredate=date("Y-m-d H:i",$myrow["expiredate"]);
			}
			if($myrow["firstdate"]>0)
			{
				$usefirstdate=1;
				$firstdate=date("Y-m-d H:i",$myrow["firstdate"]);
			}
		}
	}
	if($mode=="domove")
	{
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="inforow"><td align="center" colspan="2"><?php echo $l_clone?></td></tr>
<?php
		if(!isset($destcat) || !$destcat || ($destcat<0))
		{
			echo "<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">$l_nodestcat</td></tr>";
			echo "<tr class=\"actionrow\"><td align=\"center\" colspan=\"2\">";
			echo "<a href=\"javascript:history.back()\">$l_back</a>";
			echo "</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang&catnr=$catnr&announcelang=$announcelang")."\">$l_announcementlist</a></div>";
			include('./trailer.php');
			exit;
		}
		$sql="update ".$tableprefix."_announce set category=$destcat where entrynr=$input_announcenr";
		if(!$result = mysql_query($sql, $db))
		    die("<tr bgcolor=\"#cccccc\"><td>Unable to connect to database.".mysql_error());
		echo "<tr class=\"displayrow\"><td align=\"center\" colspan=\"2\">$l_newcatmoved</td></tr>";
		echo "</td></tr></form></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang&catnr=$destcat&announcelang=$announcelang")."\">$l_announcementlist</a></div>";
		include('./trailer.php');
		exit;
	}
	if($mode=="add")
	{
		if(!isset($headingicon))
			$headingicon="";
		$announcetext=trim($announcetext);
		if(!isset($announcetext) || !$announcetext)
		{
			unset($preview);
			$errmsg=$l_noannouncetext;
			$headingtext=display_encoded($heading);
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
				if(is_konqueror())
					echo "<tr><td></td></tr>";
				if($sessid_url)
					echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
				if(isset($no_wap))
					echo "<input type=\"hidden\" name=\"no_wap\" value=\"1\">";
				if(isset($wap_short))
					echo "<input type=\"hidden\" name=\"rss_short\" value=\"$wap_short\">";
				if(isset($urlautoencode))
					echo "<input type=\"hidden\" name=\"urlautoencode\" value=\"1\">";
				if(isset($enablespcode))
					echo "<input type=\"hidden\" name=\"enablespcode\" value=\"1\">";
				$announcetext=stripslashes($announcetext);
				$heading=stripslashes($heading);
?>
<input type="hidden" name="announcelang" value="<?php echo $announcelang?>">
<input type="hidden" name="catnr" value="<?php echo $catnr?>">
<input type="hidden" name="announcetext" value="<?php echo display_encoded($announcetext)?>">
<input type="hidden" name="heading" value="<?php echo display_encoded($heading)?>">
<input type="hidden" name="headingicon" value="<?php echo $headingicon?>">
<input type="hidden" name="mode" value="add">
<input type="hidden" name="tickerurl" value="<?php echo $tickerurl?>">
<tr class="inforow"><td align="center" colspan="2"><?php echo $l_previewprelude?></td></tr>
<?php
				if(isset($immediatlysendemail))
					echo "<input type=\"hidden\" name=\"immediatlysendemail\" value=\"1\">";
				if(isset($urlautoencode))
					$announcetext = make_clickable($announcetext);
				if(isset($enablespcode))
					$announcetext = bbencode($announcetext);
				if(!isset($disableemoticons))
					$announcetext = encode_emoticons($announcetext, $url_emoticons, $db);
				$announcetext = do_htmlentities($announcetext);
				$announcetext = str_replace("\n", "<BR>", $announcetext);
				$announcetext = str_replace("\r","",$announcetext);
				$announcetext = undo_htmlspecialchars($announcetext);
				if(isset($specialdate))
				{
					echo "<input type=\"hidden\" name=\"specialdate\" value=\"1\">";
					echo "<input type=\"hidden\" name=\"input_hour\" value=\"$input_hour\">";
					echo "<input type=\"hidden\" name=\"input_min\" value=\"$input_min\">";
					echo "<input type=\"hidden\" name=\"input_month\" value=\"$input_month\">";
					echo "<input type=\"hidden\" name=\"input_day\" value=\"$input_day\">";
					echo "<input type=\"hidden\" name=\"input_year\" value=\"$input_year\">";
					$temptime=mktime($input_hour,$input_min,0,$input_month,$input_day,$input_year);
					$actdate=date($l_admdateformat,$temptime);
				}
				else
				{
					$acttime=transposetime(time(),$servertimezone,$displaytimezone);
					$actdate = date($l_admdateformat,$acttime);
				}
				if(isset($usefirstdate))
				{
					echo "<input type=\"hidden\" name=\"usefirstdate\" value=\"1\">";
					echo "<input type=\"hidden\" name=\"firstdate_month\" value=\"$firstdate_month\">";
					echo "<input type=\"hidden\" name=\"firstdate_day\" value=\"$firstdate_day\">";
					echo "<input type=\"hidden\" name=\"firstdate_year\" value=\"$firstdate_year\">";
					echo "<input type=\"hidden\" name=\"firstdate_hour\" value=\"$firstdate_hour\">";
					echo "<input type=\"hidden\" name=\"firstdate_minute\" value=\"$firstdate_minute\">";
					$firstdatetime=mktime($firstdate_hour,$firstdate_minute,0,$firstdate_month,$firstdate_day,$firstdate_year);
					$firstdate=date($l_admdateformat,$firstdatetime);
				}
				else
					$firstdatetime=0;
				if(isset($useexpiredate))
				{
					echo "<input type=\"hidden\" name=\"useexpiredate\" value=\"1\">";
					echo "<input type=\"hidden\" name=\"expire_month\" value=\"$expire_month\">";
					echo "<input type=\"hidden\" name=\"expire_day\" value=\"$expire_day\">";
					echo "<input type=\"hidden\" name=\"expire_year\" value=\"$expire_year\">";
					echo "<input type=\"hidden\" name=\"expire_hour\" value=\"$expire_hour\">";
					echo "<input type=\"hidden\" name=\"expire_minute\" value=\"$expire_minute\">";
					$expiretime=mktime($expire_hour,$expire_minute,59,$expire_month,$expire_day,$expire_year);
					$expires=date($l_admdateformat,$expiretime);
				}
				else
					$expiretime=0;
				if(isset($new_files))
				{
					while(list($null, $actattach) = each($_POST["new_files"]))
						echo "<input type=\"hidden\" name=\"new_files[]\" value=\"$actattach\">";
				}
				echo "<tr><td width=\"2%\" height=\"100%\" align=\"center\" class=\"newsicon\">";
				if($headingicon)
					echo "<img src=\"$url_icons/".$headingicon."\" border=\"0\" align=\"middle\"> ";
				else
					echo "&nbsp;";
				echo "</td>";
				echo "<td align=\"center\"><table width=\"100%\" align=\"center\" cellspacing=\"0\" cellpadding=\"0\">";
				echo "<tr><td align=\"left\" class=\"newsdate\">";
				echo $actdate."</td></tr>";
				if(strlen($heading)>0)
				{
					echo "<tr class=\"newsheading\"><td align=\"left\">";
					echo display_encoded(stripslashes($heading));
					echo "</td></tr>";
				}
				echo "<tr class=\"newsentry\"><td align=\"left\">";
				echo $announcetext;
				echo "</td></tr>";
				if(isset($wap_short))
				{
					echo "<tr class=\"displayrow\"><td align=\"left\">";
					echo "$l_wap_short:<br>$wap_short";
					echo "</td></tr>";
				}
				if($firstdatetime>0)
				{
					echo "<tr class=\"displayrow\"><td align=\"left\">$l_firstpublishon:&nbsp;";
					echo $firstdate;
					echo "</td></tr>";
				}
				if($expiretime>0)
				{
					echo "<tr class=\"displayrow\"><td align=\"left\">$l_expireson:&nbsp;";
					echo $expires;
					echo "</td></tr>";
				}
				if($tickerurl)
				{
					echo "<tr class=\"displayrow\"><td align=\"left\">";
					echo "$l_tickerurl: $tickerurl";
					echo "</td></tr>";
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
				if(strlen($userdata["realname"])>0)
					$poster=$userdata["realname"];
				else
					$poster=$userdata["username"];
				if(isset($urlautoencode))
					$announcetext = make_clickable($announcetext);
				if(isset($enablespcode))
					$announcetext = bbencode($announcetext);
				if(!isset($disableemoticons))
					$announcetext = encode_emoticons($announcetext, $url_emoticons, $db);
				if($heading)
					$searchtext = stripslashes($heading)." ";
				else
					$searchtext = "";
				$searchtext.= stripslashes(strip_tags($announcetext));
				$searchtext = remove_htmltags($searchtext);
				$searchtext = strtolower($searchtext);
				$searchtext = addslashes($searchtext);
				$announcetext = do_htmlentities($announcetext);
				$announcetext = str_replace("\n", "<BR>", $announcetext);
				$announcetext = str_replace("\r", "", $announcetext);
				$announcetext=addslashes($announcetext);
				if(isset($specialdate))
				{
					$temptime=mktime($input_hour,$input_min,0,$input_month,$input_day,$input_year);
					$temptime=transposetime($temptime,$servertimezone,$displaytimezone);
					$actdate=date("Y-m-d H:i:s",$temptime);
				}
				else
					$actdate = date("Y-m-d H:i:s");
				if(isset($useexpiredate))
					$expiretime=mktime($expire_hour,$expire_minute,59,$expire_month,$expire_day,$expire_year);
				else
					$expiretime=0;
				if(isset($usefirstdate))
					$firstdatetime=mktime($firstdate_hour,$firstdate_minute,0,$firstdate_month,$firstdate_day,$firstdate_year);
				else
					$firstdatetime=0;
				$sql = "insert into ".$tableprefix."_announce (lang, date, text, heading, poster, headingicon, category, posterid, expiredate, firstdate, tickerurl, wap_nopublish";
				if(isset($wap_short))
					$sql.=", wap_short";
				$sql.= ")";
				$sql.= "values ('$announcelang', '$actdate', '$announcetext', '$heading', '$poster', '$headingicon', $catnr, ".$userdata["usernr"].", $expiretime, $firstdatetime, '$tickerurl', $wap_nopublish";
				if(isset($wap_short))
					$sql.=", '$wap_short'";
				$sql.= ")";
				if(!$result = mysql_query($sql, $db))
				    die("Unable to connect to database.".mysql_error());
				$annr=mysql_insert_id($db);
				$sql = "insert into ".$tableprefix."_ansearch (annr, text) values ($annr, '$searchtext')";
				if(!$result = mysql_query($sql, $db))
				    die("Unable to connect to database.".mysql_error());
				if(isset($new_files))
				{
					while(list($null, $actattach) = each($_POST["new_files"]))
					{
						$sql="insert into ".$tableprefix."_announce_attachs (attachnr,announcenr) values ('$actattach','$annr')";
						@mysql_query($sql, $db);
					}
				}
				$infotext=$l_entryadded." (#".$annr.")";
				if(($enablesubscriptions==1) && isset($immediatlysendemail))
				{
					$sentmails=email_single_announce($heading, $announcetext, $announcelang, $db, $subject, $simpnewsmail, $simpnewsmailname, $actdate, $poster, $annr, $headingicon, $catnr);
					$infotext.="<br>$l_emailssent ($sentmails)";
					if($showsendprogress==1)
					{
						$infotext.="<br><a href=\"javascript:showprogressbox()\"";
						$infotext.=" class=\"actionlink\">$l_reshowprogressbox</a>";
					}
				}
				unset($useexpiredate);
				unset($usefirstdate);
				unset($tickerurl);
				$expiredate=date("Y-m-d 23:59");
				$firstdate=date("Y-m-d 00:00");
				$wap_nopublish=0;
			}
		}
	}
	if($mode=="massdel")
	{
		if(isset($announcenr))
		{
    		while(list($null, $input_announcenr) = each($_POST["announcenr"]))
    		{
				$sql = "delete from ".$tableprefix."_announce where entrynr=$input_announcenr";
				if(!$result = mysql_query($sql, $db))
				    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
				$sql = "delete from ".$tableprefix."_ansearch where annr=$input_announcenr";
				if(!$result = mysql_query($sql, $db))
				    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
				$sql = "delete from ".$tableprefix."_announce_attachs where announcenr=$input_announcenr";
				if(!$result = mysql_query($sql, $db))
				    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
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
<input type="hidden" name="announcelang" value="<?php echo $announcelang?>">
<input type="hidden" name="catnr" value="<?php echo $catnr?>">
<input type="hidden" name="mode" value="del">
<input type="hidden" name="input_announcenr" value="<?php echo $input_announcenr?>">
<?php
			if(is_konqueror())
				echo "<tr><td></td></tr>";
			if($sessid_url)
				echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
			echo "<tr class=\"inforow\"><td align=\"center\">";
			echo "$l_confirmdel: $l_announce #$input_announcenr";
			echo "</td></tr>";
			echo "<tr class=\"actionrow\"><td align=\"center\">";
			echo "<input class=\"snbutton\" type=\"submit\" name=\"submit\" value=\" $l_yes \">";
			echo "&nbsp;<input class=\"snbutton\" type=\"button\" value=\" $l_no \" onclick=\"self.history.back();\">";
			echo "</td></tr>";
			echo "</form></table></td></tr></table>";
			include('./trailer.php');
			exit;
		}
		$sql = "delete from ".$tableprefix."_announce where entrynr=$input_announcenr";
		if(!$result = mysql_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
		$sql = "delete from ".$tableprefix."_ansearch where annr=$input_announcenr";
		if(!$result = mysql_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
		$sql = "delete from ".$tableprefix."_announce_attachs where announcenr=$input_announcenr";
		if(!$result = mysql_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
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
<input type="hidden" name="announcelang" value="<?php echo $announcelang?>">
<input type="hidden" name="catnr" value="<?php echo $catnr?>">
<input type="hidden" name="mode" value="delattach">
<input type="hidden" name="input_announcenr" value="<?php echo $input_announcenr?>">
<?php
			if(is_konqueror())
				echo "<tr><td></td></tr>";
			if($sessid_url)
				echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
			echo "<tr class=\"inforow\"><td align=\"center\">";
			echo "$l_confirmdel: $l_attachements - $l_announce #$input_announcenr";
			echo "</td></tr>";
			echo "<tr class=\"actionrow\"><td align=\"center\">";
			echo "<input class=\"snbutton\" type=\"submit\" name=\"submit\" value=\" $l_yes \">";
			echo "&nbsp;<input class=\"snbutton\" type=\"button\" value=\" $l_no \" onclick=\"self.history.back();\">";
			echo "</td></tr>";
			echo "</form></table></td></tr></table>";
			include('./trailer.php');
			exit;
		}
		$sql = "delete from ".$tableprefix."_announce_attachs where announcenr=$input_announcenr";
		if(!$result = mysql_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
	}
	if($mode=="edit")
	{
		$sql = "select * from ".$tableprefix."_announce where entrynr=$input_announcenr";
		if(!$result = mysql_query($sql, $db))
		    die("Unable to connect to database.".mysql_error());
		if ($myrow = mysql_fetch_array($result))
		{
			$doedit=1;
			$tickerurl=$myrow["tickerurl"];
			$headingtext=display_encoded(stripslashes($myrow["heading"]));
			$announcetext = stripslashes($myrow["text"]);
			$announcetext = str_replace("<BR>", "\n", $announcetext);
			$announcetext = undo_htmlspecialchars($announcetext);
			$announcetext = decode_emoticons($announcetext, $url_emoticons, $db);
			$announcetext = bbdecode($announcetext);
			$announcetext = undo_make_clickable($announcetext);
			$actheadingicon=$myrow["headingicon"];
			$announcedate=$myrow["date"];
			$currentviews=$myrow["views"];
			$wap_nopublish=$myrow["wap_nopublish"];
			$wap_short=stripslashes($myrow["wap_short"]);
			if($myrow["expiredate"]>0)
			{
				$useexpiredate=1;
				$expiredate=date("Y-m-d H:i",$myrow["expiredate"]);
			}
			if($myrow["firstdate"]>0)
			{
				$usefirstdate=1;
				$firstdate=date("Y-m-d H:i",$myrow["firstdate"]);
			}
		}
		$sql="select ana.entrynr as attentry, files.* from ".$tableprefix."_announce_attachs ana, ".$tableprefix."_files files where files.entrynr=ana.attachnr and ana.announcenr=$input_announcenr";
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
		$announcetext=trim($announcetext);
		if(isset($announcetext) && $announcetext)
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
				$announcetext=stripslashes($announcetext);
				$heading=stripslashes($heading);
				if(isset($no_wap))
					echo "<input type=\"hidden\" name=\"no_wap\" value=\"1\">";
				if(isset($wap_short))
					echo "<input type=\"hidden\" name=\"rss_short\" value=\"$wap_short\">";
				if(is_konqueror())
					echo "<tr><td></td></tr>";
				if($sessid_url)
					echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
				if(isset($resetviews))
					echo "<input type=\"hidden\" name\"resetviews\" value=\"1\">";
				if(isset($urlautoencode))
					echo "<input type=\"hidden\" name=\"urlautoencode\" value=\"1\">";
				if(isset($enablespcode))
					echo "<input type=\"hidden\" name=\"enablespcode\" value=\"1\">";
				if(isset($resetdate))
					echo "<input type=\"hidden\" name=\"resetdate\" value=\"1\">";
				if(isset($immediatlysendemail))
					echo "<input type=\"hidden\" name=\"immediatlysendemail\" value=\"1\">";
?>
<input type="hidden" name="announcelang" value="<?php echo $announcelang?>">
<input type="hidden" name="catnr" value="<?php echo $catnr?>">
<input type="hidden" name="announcetext" value="<?php echo display_encoded($announcetext)?>">
<input type="hidden" name="heading" value="<?php echo display_encoded($heading)?>">
<input type="hidden" name="headingicon" value="<?php echo $headingicon?>">
<input type="hidden" name="mode" value="update">
<input type="hidden" name="input_announcenr" value="<?php echo $input_announcenr?>">
<input type="hidden" name="frompreview" value="1">
<input type="hidden" name="tickerurl" value="<?php echo $tickerurl?>">
<tr class="inforow"><td align="center" colspan="2"><?php echo $l_previewprelude?></td></tr>
<?php
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
				if(isset($urlautoencode))
					$announcetext = make_clickable($announcetext);
				if(isset($enablespcode))
					$announcetext = bbencode($announcetext);
				if(!isset($disableemoticons))
					$announcetext = encode_emoticons($announcetext, $url_emoticons, $db);
				$announcetext = do_htmlentities($announcetext);
				$announcetext = str_replace("\n", "<BR>", $announcetext);
				$announcetext = str_replace("\r", "", $announcetext);
				$announcetext = undo_htmlspecialchars($announcetext);
				if(isset($resetdate))
				{
					$acttime=transposetime(time(),$servertimezone,$displaytimezone);
					$actdate = date($l_admdateformat,$acttime);
				}
				else if(isset($specialdate))
				{
					echo "<input type=\"hidden\" name=\"specialdate\" value=\"1\">";
					echo "<input type=\"hidden\" name=\"input_hour\" value=\"$input_hour\">";
					echo "<input type=\"hidden\" name=\"input_min\" value=\"$input_min\">";
					echo "<input type=\"hidden\" name=\"input_month\" value=\"$input_month\">";
					echo "<input type=\"hidden\" name=\"input_day\" value=\"$input_day\">";
					echo "<input type=\"hidden\" name=\"input_year\" value=\"$input_year\">";
					$temptime=mktime($input_hour,$input_min,0,$input_month,$input_day,$input_year);
					$actdate=date($l_admdateformat,$temptime);
				}
				else
				{
					$tempsql="select * from ".$tableprefix."_announce where entrynr=$input_announcenr";
					if(!$tempresult = mysql_query($tempsql, $db))
					    die("Unable to connect to database.".mysql_error());
					if($temprow=mysql_fetch_array($tempresult))
					{
						list($mydate,$mytime)=explode(" ",$temprow["date"]);
						list($year, $month, $day) = explode("-", $mydate);
						list($hour, $min, $sec) = explode(":",$mytime);
						$temptime=mktime($hour,$min,$sec,$month,$day,$year);
						$temptime=transposetime($temptime,$servertimezone,$displaytimezone);
						$actdate=date($l_admdateformat,$temptime);
					}
					else
						$actdate="";
				}
				if(isset($usefirstdate))
				{
					echo "<input type=\"hidden\" name=\"usefirstdate\" value=\"1\">";
					echo "<input type=\"hidden\" name=\"firstdate_month\" value=\"$firstdate_month\">";
					echo "<input type=\"hidden\" name=\"firstdate_day\" value=\"$firstdate_day\">";
					echo "<input type=\"hidden\" name=\"firstdate_year\" value=\"$firstdate_year\">";
					echo "<input type=\"hidden\" name=\"firstdate_hour\" value=\"$firstdate_hour\">";
					echo "<input type=\"hidden\" name=\"firstdate_minute\" value=\"$firstdate_minute\">";
					$firstdatetime=mktime($firstdate_hour,$firstdate_minute,0,$firstdate_month,$firstdate_day,$firstdate_year);
					$firstdate=date($l_admdateformat,$firstdatetime);
				}
				else
					$firstdatetime=0;
				if(isset($useexpiredate))
				{
					echo "<input type=\"hidden\" name=\"useexpiredate\" value=\"1\">";
					echo "<input type=\"hidden\" name=\"expire_month\" value=\"$expire_month\">";
					echo "<input type=\"hidden\" name=\"expire_day\" value=\"$expire_day\">";
					echo "<input type=\"hidden\" name=\"expire_year\" value=\"$expire_year\">";
					echo "<input type=\"hidden\" name=\"expire_hour\" value=\"$expire_hour\">";
					echo "<input type=\"hidden\" name=\"expire_minute\" value=\"$expire_minute\">";
					$expiretime=mktime($expire_hour,$expire_minute,59,$expire_month,$expire_day,$expire_year);
					$expires=date($l_admdateformat,$expiretime);
				}
				else
					$expiretime=0;
				echo "<tr><td width=\"2%\" height=\"100%\" align=\"center\" class=\"newsicon\">";
				if($headingicon)
					echo "<img src=\"$url_icons/".$headingicon."\" border=\"0\" align=\"middle\"> ";
				else
					echo "&nbsp;";
				echo "</td>";
				echo "<td align=\"center\"><table width=\"100%\" align=\"center\" bgcolor=\"#c0c0c0\" cellspacing=\"0\" cellpadding=\"0\">";
				echo "<tr><td align=\"left\" class=\"newsdate\">";
				echo $actdate."</td></tr>";
				if(strlen($heading)>0)
				{
					echo "<tr class=\"newsheading\"><td align=\"left\">";
					echo display_encoded($heading);
					echo "</td></tr>";
				}
				echo "<tr class=\"newsentry\"><td align=\"left\">";
				echo $announcetext;
				echo "</td></tr>";
				if(isset($wap_short))
				{
					echo "<tr class=\"displayrow\"><td align=\"left\">";
					echo "$l_wap_short:<br>$wap_short";
					echo "</td></tr>";
				}
				if($firstdatetime>0)
				{
					echo "<tr class=\"displayrow\"><td align=\"left\">$l_firstpublishon:&nbsp;";
					echo $firstdate;
					echo "</td></tr>";
				}
				if($expiretime>0)
				{
					echo "<tr class=\"displayrow\"><td align=\"left\">";
					echo "$l_expireson: $expires";
					echo "</td></tr>";
				}
				if($tickerurl)
				{
					echo "<tr class=\"displayrow\"><td align=\"left\">";
					echo "$l_tickerurl: $tickerurl";
					echo "</td></tr>";
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
				if(isset($urlautoencode))
					$announcetext = make_clickable($announcetext);
				if(isset($enablespcode))
					$announcetext = bbencode($announcetext);
				if(!isset($disableemoticons))
					$announcetext = encode_emoticons($announcetext, $url_emoticons, $db);
				if($heading)
					$searchtext = stripslashes($heading)." ";
				else
					$searchtext = "";
				$searchtext.= stripslashes(strip_tags($announcetext));
				$searchtext = remove_htmltags($searchtext);
				$searchtext = strtolower($searchtext);
				$searchtext = addslashes($searchtext);
				$announcetext = do_htmlentities($announcetext);
				$announcetext = str_replace("\n", "<BR>", $announcetext);
				$announcetext = str_replace("\r", "", $announcetext);
				$announcetext=addslashes($announcetext);
				$actdate = date("Y-m-d H:i:s");
				if(isset($useexpiredate))
					$expiretime=mktime($expire_hour,$expire_minute,59,$expire_month,$expire_day,$expire_year);
				else
					$expiretime=0;
				if(isset($usefirstdate))
					$firstdatetime=mktime($firstdate_hour,$firstdate_minute,0,$firstdate_month,$firstdate_day,$firstdate_year);
				else
					$firstdatetime=0;
				$sql = "update ".$tableprefix."_announce set tickerurl='$tickerurl', headingicon='$headingicon', text='$announcetext', heading='$heading', expiredate=$expiretime, firstdate=$firstdatetime";
				if(isset($resetviews))
					$sql.=", views=0";
				if(isset($resetdate))
					$sql.=", date='$actdate'";
				else if(isset($specialdate))
				{
					$temptime=mktime($input_hour,$input_min,0,$input_month,$input_day,$input_year);
					$temptime=transposetime($temptime,$displaytimezone,$servertimezone);
					$actdate=date("Y-m-d H:i:s",$temptime);
					$sql.=", date='$actdate'";
				}
				$sql.= ", wap_nopublish=$wap_nopublish";
				if(isset($wap_short))
					$sql.= ", wap_short='$wap_short'";
				$sql.= " where entrynr=$input_announcenr";
				if(!$result = mysql_query($sql, $db))
				    die("Unable to connect to database.".mysql_error());
				if(isset($new_files))
				{
					while(list($null, $actattach) = each($_POST["new_files"]))
					{
						$sql="insert into ".$tableprefix."_announce_attachs (attachnr,announcenr) values ('$actattach','$input_announcenr')";
						@mysql_query($sql, $db);
					}
				}
				if(isset($del_files))
				{
					while(list($null, $actattach) = each($_POST["del_files"]))
					{
						$sql="delete from ".$tableprefix."_announce_attachs where entrynr=$actattach";
						@mysql_query($sql, $db);
					}
				}
				$sql = "update ".$tableprefix."_ansearch set text='$searchtext' where annr=$input_announcenr";
				if(!$result = mysql_query($sql, $db))
				    die("Unable to connect to database.".mysql_error());
				$infotext=$l_entryupdated." (#".$input_announcenr.")";
				if(($enablesubscriptions==1) && isset($immediatlysendemail))
				{
					$sql = "select * from ".$tableprefix."_announce where entrynr=$input_announcenr";
					if(!$result = mysql_query($sql, $db))
					    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
					if($myrow=mysql_fetch_array($result))
					{
						$poster=$myrow["poster"];
						list($mydate,$mytime)=explode(" ",$myrow["date"]);
						list($year, $month, $day) = explode("-", $mydate);
						list($hour, $min, $sec) = explode(":",$mytime);
						$temptime=mktime($hour,$min,$sec,$month,$day,$year);
						$temptime=transposetime($temptime,$servertimezone,$displaytimezone);
						$actdate=date("Y-m-d H:i:s",$temptime);
						$sentmails=email_single_announce($heading, $announcetext, $announcelang, $db, $subject, $simpnewsmail, $simpnewsmailname, $actdate, $poster, $input_announcenr, $headingicon, $catnr);
						$infotext.="<br>$l_emailssent ($sentmails)";
						if($showsendprogress==1)
						{
							$infotext.="<br><a href=\"javascript:showprogressbox()\"";
							$infotext.=" class=\"actionlink\">$l_reshowprogressbox</a>";
						}
					}
				}
				unset($useexpiredate);
				unset($usefirstdate);
				$expiredate=date("Y-m-d 23:59");
				$firstdate=date("Y-m-d 00:00");
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
	if(($admin_rights<3) && bittst($secsettings,BIT_17) && !bittst($secsettings,BIT_4))
		$numavailcats=0;
	else
		$numavailcats=1;
	if(($admin_rights>2) || !bittst($secsettings,BIT_17))
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
<td align="left" width="40%"><select class="snselect" name="catnr">
<?php
if($admin_rights>2)
{
	echo "<option value=\"0\"";
	if($catnr==0)
		echo "selected";
	echo ">$l_general</option>";
}
else if(!bittst($secsettings,BIT_17))
{
	echo "<option value=\"0\"";
	if($catnr==0)
		echo "selected";
	echo ">$l_general</option>";
}
else if(bittst($secsettings,BIT_4))
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
<tr class="inputrow"><td align="right" width="50%"><?php echo $l_anedlang?>:</td>
<td align="left" width="40%"><?php echo language_select($announcelang,"announcelang","../language/")?></td>
<td align="right" width="10%"><input class="snbutton" type="submit" value="<?php echo $l_change?>"></td></tr></table></td></tr></form>
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
		$catname=$l_general;
}
echo "<tr class=\"inforow\"><td align=\"center\" valign=\"top\" colspan=\"2\"><b>$l_actuallyselected: ".display_encoded($catname).", $announcelang</b></td></tr>";
if($errmsg)
	echo "<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">$errmsg</td></tr>";
if(($admin_rights > 1) && ($allowadding))
{
?>
<form name="inputform" method="post" action="<?php echo $act_script_url?>"  target="_self" onsubmit="return checkinputform()">
<?php
	if(is_konqueror())
		echo "<tr><td></td></tr>";
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="announcelang" value="<?php echo $announcelang?>">
<tr class="inputrow"><td align="right" width="20%"><?php echo $l_heading?>:</td>
<td><input class="sninput" type="text" name="heading" value="<?php echo $headingtext?>" size="40" maxlength="80"></td></tr>
<?php
if(($admin_rights>2) || !bittst($userdata["addoptions"],BIT_1))
{
	$sql = "select * from ".$tableprefix."_icons order by icon_url asc";
	if(!$result = mysql_query($sql, $db))
	    die("<tr bgcolor=\"#cccccc\"><td>Unable to connect to database.".mysql_error());
	if($myrow=mysql_fetch_array($result))
	{
?>
<tr class="inputrow"><td align="right" valign="top" width="20%"><?php echo $l_headingicon?>:</td>
<td valign="middle" nowrap>
<table width="100%" class="iconselector" cellspacing="1" cellpadding="1" align="center">
<tr>
<td class="iconselector" align="center"><input type="radio" name="headingicon" value="" <?php if(!isset($actheadingicon) || !$actheadingicon) echo "checked"?>>
<?php echo $l_without?></td>
<?php
		$iconcount=1;
		do{
			$iconcount++;
			echo "<td class=\"iconselector\" align=\"center\"><input type=\"radio\" name=\"headingicon\" value=\"",$myrow["icon_url"]."\"";
			if(isset($actheadingicon) && ($actheadingicon==$myrow["icon_url"]))
				echo " checked";
			echo "> <img src=\"$url_icons/".$myrow["icon_url"]."\" border=\"0\" align=\"top\"></td>";
			if($iconcount>4)
			{
				$iconcount=0;
				echo "</tr><tr>";
			}
		}while($myrow=mysql_fetch_array($result));
		echo "</tr></table></td></tr>";
	}
}
else
	if(isset($actheadingicon))
		echo "<input type=\"hidden\" name=\"headingicon\" value=\"".do_htmlentities($actheadingicon)."\">";
?>
<tr class="inputrow"><td align="right" valign="top" width="20%"><?php echo $l_announce?>:
</td>
<td align="left"><textarea class="sninput" name="announcetext" rows="10" cols="50">
<?php
if(isset($doedit) || isset($transfer) || isset($doclone))
	echo $announcetext;
?>
</textarea><br>
<?php
	if(($admin_rights>2) || !bittst($userdata["addoptions"],BIT_2))
		display_smiliebox("announcetext");
	if(($admin_rights>2) || !bittst($userdata["addoptions"],BIT_3))
		display_bbcode_buttons($l_bbbuttons,"announcetext")
?>
</td></tr>
<?php
if(isset($doedit))
{
	echo "<input type=\"hidden\" name=\"mode\" value=\"update\">";
	echo "<input type=\"hidden\" name=\"input_announcenr\" value=\"$input_announcenr\">";
}
else
	echo "<input type=\"hidden\" name=\"mode\" value=\"add\">";
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
if($enablesubscriptions==1)
{
	if($admin_rights>=$sublevel)
	{
		echo "<tr class=\"optionrow\"><td>&nbsp;</td><td><input type=\"checkbox\" name=\"immediatlysendemail\" value=\"1\"";
		echo "> $l_sendinnewsletter</td></tr>";
	}
}
if($wap_enable==1)
{
	echo "<tr class=\"optionrow\"><td>&nbsp;</td><td>";
	echo "<input type=\"checkbox\" name=\"no_wap\" value=\"1\"";
	if($wap_nopublish==1)
		echo "checked";
	echo "> $l_wap_no_publish</td></tr>";
}
?>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_tickerurl?>:</td>
<td><input class="sninput" type="text" name="tickerurl" size="40" maxlength="240" <?php if(isset($tickerurl)) echo "value=\"$tickerurl\""?>></td></tr>
<?php
if($upload_avail)
{
	echo "<tr class=\"inputrow\"><td align=\"right\" valign=\"top\" width=\"30%\">$l_attachfile:</td>";
	echo "<td>";
	echo "<select id=\"new_files\" name=\"new_files[]\" size=\"5\" multiple>";
	if(isset($transfer) && isset($tmpdata) && isset($newslang))
	{
		$tmpsql="select f.* from ".$tableprefix."_files f, ".$tableprefix."_tmpnews_attachs tna where tna.newsnr=$tmpdata and f.entrynr=tna.attachnr";
		if(!$tmpresult = mysql_query($tmpsql, $db))
		    die("Could not connect to the database.".mysql_error());
		if($tmprow=mysql_fetch_array($tmpresult))
			echo "<option value=\"".$tmprow["entrynr"]."\" selected>".$tmprow["filename"]."</option>";
	}
	if(isset($doclone))
	{
		$tmpsql="select f.* from ".$tableprefix."_files f, ".$tableprefix."_announce_attachs ana where ana.announcenr=$input_announcenr and f.entrynr=ana.attachnr";
		if(!$tmpresult = mysql_query($tmpsql, $db))
		    die("Could not connect to the database.".mysql_error());
		if($tmprow=mysql_fetch_array($tmpresult))
			echo "<option value=\"".$tmprow["entrynr"]."\" selected>".$tmprow["filename"]."</option>";
	}
	echo "</select><br>";
	echo "<a class=\"listlink\" href=\"javascript:openWindow2('".do_url_session("dbfiles.php?$langvar=$lang&mode=1")."',20,20,620,300);\">";
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
if(isset($announcedate))
{
	list($mydate,$mytime)=explode(" ",$announcedate);
	list($year, $month, $day) = explode("-", $mydate);
	list($hour, $min, $sec) = explode(":",$mytime);
	$temptime=mktime($hour,$min,$sec,$month,$day,$year);
	$temptime=transposetime($temptime,$servertimezone,$displaytimezone);
	$year=date("Y",$temptime);
	$month=date("m",$temptime);
	$day=date("d",$temptime);
	$hour=date("H",$temptime);
	$min=date("i",$temptime);
}
if(isset($doedit))
{
?>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="resetviews" value="1"> <?php echo $l_resetviews?>
<?php echo " ($l_current: ".$currentviews.")"?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="resetdate" value="1" onclick="reset_date(this.checked)"> <?php echo $l_resetdate?></td></tr>
<?php
}
?>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="specialdate" value="1" onclick="special_date(this.checked)" <?php if($useannouncedate==1) echo "checked"?>> <?php echo $l_usethisdate?>:<br>
<table width="100%">
<tr>
<td align="center"><?php echo $l_day?></td><td align="center"><?php echo $l_month?></td><td align="center"><?php echo $l_year?></td>
<td align="center"><?php echo $l_hour?></td><td align="center"><?php echo $l_minutes?></td></tr>
<tr>
<td align="center">
<select name="input_day" <?php if($useannouncedate==0) echo "disabled"?>>
<?php
for($i=1;$i<32;$i++)
{
	echo "<option value=\"$i\"";
	if($i==$day)
		echo " selected";
	echo ">$i</option>";
}
?>
</select>
</td>
<td align="center">
<select name="input_month" <?php if($useannouncedate==0) echo "disabled"?>>
<?php
for($i=1;$i<13;$i++)
{
	echo "<option value=\"$i\"";
	if($i==$month)
		echo " selected";
	echo ">$i</option>";
}
?>
</select>
</td>
<td align="center">
<select name="input_year" <?php if($useannouncedate==0) echo "disabled"?>>
<?php
for($i=$year-$yearrange;$i<$year+$yearrange+1;$i++)
{
	echo "<option value=\"$i\"";
	if($i==$year)
		echo " selected";
	echo ">$i</option>";
}
?>
</select>
</td>
<td align="center">
<select name="input_hour"  <?php if($useannouncedate==0) echo "disabled"?>>
<?php
for($i=0;$i<24;$i++)
{
	echo "<option value=\"$i\"";
	if($i==$hour)
		echo " selected";
	echo ">$i</option>";
}
?>
</select>
</td>
<td align="center">
<select name="input_min"  <?php if($useannouncedate==0) echo "disabled"?>>
<?php
for($i=0;$i<60;$i++)
{
	echo "<option value=\"$i\"";
	if($i==$min)
		echo " selected";
	echo ">$i</option>";
}
?>
</select>
</td>
</tr>
</table>
</td></tr>
<?php
list($exdate,$extime)=explode(" ",$expiredate);
list($expireyear, $expiremonth, $expireday) = explode("-", $exdate);
list($expirehour, $expireminute) = explode(":", $extime);
list($firstd,$firstt)=explode(" ",$firstdate);
list($firstdateyear, $firstdatemonth, $firstdateday) = explode("-", $firstd);
list($firstdatehour,$firstdateminute) = explode(":", $firstt);
?>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="useexpiredate" value="1" onclick="expire_date(this.checked)" <?php if(isset($useexpiredate)) echo "checked"?>> <?php echo $l_expireson?>:<br>
<table width="50%">
<tr>
<td align="center"><?php echo $l_day?></td><td align="center"><?php echo $l_month?></td><td align="center"><?php echo $l_year?></td>
<td align="center"><?php echo $l_hour?></td><td align="center"><?php echo $l_minutes?></td>
</tr>
<tr>
<td align="center">
<select name="expire_day" <?php if(!isset($useexpiredate)) echo "disabled"?>>
<?php
for($i=1;$i<32;$i++)
{
	echo "<option value=\"$i\"";
	if($i==$expireday)
		echo " selected";
	echo ">$i</option>";
}
?>
</select>
</td>
<td align="center">
<select name="expire_month" <?php if(!isset($useexpiredate)) echo "disabled"?>>
<?php
for($i=1;$i<13;$i++)
{
	echo "<option value=\"$i\"";
	if($i==$expiremonth)
		echo " selected";
	echo ">$i</option>";
}
?>
</select>
</td>
<td align="center">
<select name="expire_year" <?php if(!isset($useexpiredate)) echo "disabled"?>>
<?php
for($i=$year-$yearrange;$i<$year+$yearrange+1;$i++)
{
	echo "<option value=\"$i\"";
	if($i==$expireyear)
		echo " selected";
	echo ">$i</option>";
}
?>
</select>
<td align="center">
<select name="expire_hour" <?php if(!isset($useexpiredate)) echo "disabled"?>>
<?php
for($i=0;$i<24;$i++)
{
	echo "<option value=\"$i\"";
	if($i==$expirehour)
		echo " selected";
	echo ">$i</option>";
}
?>
</select>
</td>
<td align="center">
<select name="expire_minute" <?php if(!isset($useexpiredate)) echo "disabled"?>>
<?php
for($i=0;$i<60;$i++)
{
	echo "<option value=\"$i\"";
	if($i==$expireminute)
		echo " selected";
	echo ">$i</option>";
}
?>
</select>
</td>
</tr>
</table>
</td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="usefirstdate" value="1" onclick="first_date(this.checked)" <?php if(isset($usefirstdate)) echo "checked"?>> <?php echo $l_firstpublishon?>:<br>
<table width="50%">
<tr>
<td align="center"><?php echo $l_day?></td><td align="center"><?php echo $l_month?></td><td align="center"><?php echo $l_year?></td>
<td align="center"><?php echo $l_hour?></td><td align="center"><?php echo $l_minutes?></td></tr>
<tr>
<td align="center">
<select name="firstdate_day" <?php if(!isset($usefirstdate)) echo "disabled"?>>
<?php
for($i=1;$i<32;$i++)
{
	echo "<option value=\"$i\"";
	if($i==$firstdateday)
		echo " selected";
	echo ">$i</option>";
}
?>
</select>
</td>
<td align="center">
<select name="firstdate_month" <?php if(!isset($usefirstdate)) echo "disabled"?>>
<?php
for($i=1;$i<13;$i++)
{
	echo "<option value=\"$i\"";
	if($i==$firstdatemonth)
		echo " selected";
	echo ">$i</option>";
}
?>
</select>
</td>
<td align="center">
<select name="firstdate_year" <?php if(!isset($usefirstdate)) echo "disabled"?>>
<?php
for($i=$year-$yearrange;$i<$year+$yearrange+1;$i++)
{
	echo "<option value=\"$i\"";
	if($i==$firstdateyear)
		echo " selected";
	echo ">$i</option>";
}
?>
</select>
</td>
<td align="center">
<select name="firstdate_hour" <?php if(!isset($usefirstdate)) echo "disabled"?>>
<?php
for($i=0;$i<24;$i++)
{
	echo "<option value=\"$i\"";
	if($i==$firstdatehour)
		echo " selected";
	echo ">$i</option>";
}
?>
</select>
</td>
<td align="center">
<select name="firstdate_minute" <?php if(!isset($usefirstdate)) echo "disabled"?>>
<?php
for($i=0;$i<60;$i++)
{
	echo "<option value=\"$i\"";
	if($i==$firstdateminute)
		echo " selected";
	echo ">$i</option>";
}
?>
</select>
</td>
</tr>
</table>
</td></tr>
<tr class="actionrow"><td align="center" colspan="2"><input class="snbutton" type="submit" name="dosubmit" value="<?php if(isset($doedit)) echo $l_update; else echo $l_add?>">&nbsp;&nbsp;
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
<input type="hidden" name="announcelang" value="<?php echo $announcelang?>">
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
$sql = "select * from ".$tableprefix."_announce where lang='$announcelang' and category=$catnr ";
if($limiting==1)
	$sql.="and posterid=".$userdata["usernr"]." ";
switch($sorting)
{
	case 11:
		$sql.="order by entrynr asc";
		break;
	case 12:
		$sql.="order by entrynr desc";
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
		$sql.="order by expiredate asc";
		break;
	case 42:
		$sql.="order by expiredate desc";
		break;
	case 51:
		$sql.="order by firstdate asc";
		break;
	case 52:
		$sql.="order by firstdate desc";
		break;
	case 61:
		$sql.="order by views asc";
		break;
	case 62:
		$sql.="order by views desc";
		break;
}
if(!$result = mysql_query($sql, $db))
    die("<tr bgcolor=\"#cccccc\"><td>Unable to connect to database.".mysql_error());
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
	$baselink="$act_script_url?$langvar=$act_lang&announcelang=$announcelang&catnr=$catnr";
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
$acttime=transposetime(time(),$servertimezone,$displaytimezone);
if ($myrow = mysql_fetch_array($result))
{
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<form name="newslist" method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="announcelang" value="<?php echo $announcelang?>">
<input type="hidden" name="mode" value="massdel">
<input type="hidden" name="catnr" value="<?php echo $catnr?>">
<?php
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
	if(is_konqueror())
		echo "<tr><td></td></tr>";
	$baseurl=$act_script_url."?".$langvar."=".$act_lang;
	$maxsortcol=6;
	if($admstorefilter==1)
		$baseurl.="&dostorefilter=1";
	if(isset($announcelang))
		$baseurl.="&announcelang=$announcelang";
	if(isset($catnr))
		$baseurl.="&catnr=$catnr";
	echo "<tr class=\"rowheadings\"><td><a id=\"resultlist\">&nbsp;</a></td>";
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
	echo "$l_expireson</a>";
	echo getSortMarker($sorting, 4, $maxsortcol);
	echo "</b></td>";
	echo "<td align=\"center\"><b>";
	$sorturl=getSortURL($sorting, 5, $maxsortcol, $baseurl, "resultlist");
	echo "<a class=\"sorturl\" href=\"".do_url_session($sorturl)."\">";
	echo getSortMarker($sorting, 5, $maxsortcol);
	echo "$l_firstpublishon</a>";
	echo "</b></td>";
	echo "<td align=\"center\"><b>";
	$sorturl=getSortURL($sorting, 6, $maxsortcol, $baseurl, "resultlist");
	echo "<a class=\"sorturl\" href=\"".do_url_session($sorturl)."\">";
	echo getSortMarker($sorting, 6, $maxsortcol);
	echo "$l_views</a>";
	echo "</b></td>";
	echo "<td colspan=\"5\">&nbsp;</td>";
	echo "</tr>";
	do{
		$act_id=$myrow["entrynr"];
		$announcetext = stripslashes($myrow["text"]);
		$announcetext = undo_htmlspecialchars($announcetext);
		if($admentrychars>0)
		{
			$announcetext=undo_htmlentities($announcetext);
			$announcetext=strip_tags($announcetext);
			$announcetext=substr($announcetext,0,$admentrychars);
			$announcetext.="[...]";
		}
		if($admonlyentryheadings==0)
		{
			if($myrow["heading"])
				$displaytext="<b>".$myrow["heading"]."</b><br>".$announcetext;
			else
				$displaytext=$announcetext;
		}
		else
		{
			if($myrow["heading"])
				$displaytext="<b>".$myrow["heading"]."</b>";
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
		$allowactions=false;
		if($admrestrict==1)
		{
			if(($admin_rights>2) || bittst($userdata["addoptions"],BIT_5))
				$allowactions=true;
			else if(bittst($secsettings,BIT_3))
			{
				if($myrow["posterid"]==$userdata["usernr"])
					$allowactions=true;
			}
		}
		else if($admin_rights > 1)
		{
			if($userdata["rights"]>2)
				$allowactions=true;
			else if(bittst($secsettings,BIT_3))
				$allowactions=true;
		}
		echo "<tr valign=\"top\">";
		if($allowactions)
		{
			echo "<td class=\"actionrow\" align=\"center\" width=\"1%\">";
			echo "<input type=\"checkbox\" name=\"announcenr[]\" value=\"$act_id\">";
			echo "</td>";
		}
		echo "<td class=\"displayrow\" align=\"center\" width=\"5%\">";
		$showurl=do_url_session("anshow.php?$langvar=$act_lang&annr=".$myrow["entrynr"]);
		echo "<a class=\"shdetailslink\" href=\"javascript:openWindow3('$showurl','nShow',20,20,400,200);\">";
		echo $myrow["entrynr"];
		echo "</a></td>";
		echo "<td class=\"newsicon\" align=\"center\" width=\"5%\">";
		if($myrow["headingicon"])
			echo "<img src=\"$url_icons/".$myrow["headingicon"]."\" border=\"0\" align=\"bottom\">";
		else
			echo "&nbsp;";
		echo "</td><td class=\"newsentry\" align=\"left\" width=\"40%\">";
		echo "$displaytext</td>";
		echo "<td class=\"newsdate\" align=\"center\" width=\"10%\">";
		list($mydate,$mytime)=explode(" ",$myrow["date"]);
		list($year, $month, $day) = explode("-", $mydate);
		list($hour, $min, $sec) = explode(":",$mytime);
		$temptime=mktime($hour,$min,$sec,$month,$day,$year);
		$temptime=transposetime($temptime,$servertimezone,$displaytimezone);
		$displaydate=date($l_admdateformat,$temptime);
		echo "$displaydate</td>";
		if(($myrow["expiredate"]>0) && ($acttime>$myrow["expiredate"]))
			$rowclass="expireddate";
		else
			$rowclass="newsdate";
		echo "<td class=\"$rowclass\" align=\"center\" width=\"10%\">";
		if($myrow["expiredate"]>0)
			$expires=date($l_admdateformat,$myrow["expiredate"]);
		else
			$expires=$l_never;
		echo $expires;
		echo "</td>";
		echo "<td class=\"$rowclass\" align=\"center\" width=\"10%\">";
		if($myrow["firstdate"]>0)
			$firstdisplay=date($l_admdateformat,$myrow["firstdate"]);
		else
			$firstdisplay=$l_immediately;
		echo $firstdisplay;
		echo "</td>";
		echo "<td class=\"displayrow\" align=\"right\" width=\"10%\">";
		echo $myrow["views"];
		echo "</td>";
		if($allowactions)
		{
			echo "<td class=\"adminactions\" align=\"center\" height=\"16\" width=\"16\">";
			$tempsql="select * from ".$tableprefix."_announce_attachs where announcenr=$act_id";
			if(!$tempresult=mysql_query($tempsql,$db))
				die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
			$numattachs=mysql_num_rows($tempresult);
			if($numattachs>0)
			{
				$dellink=do_url_session("$act_script_url?$langvar=$act_lang&announcelang=$announcelang&mode=delattach&input_announcenr=$act_id&catnr=$catnr");
				if($admdelconfirm==2)
					echo "<a class=\"listlink2\" href=\"javascript:confirmDel('$l_attachements - $l_announce #$act_id','$dellink')\">";
				else
					echo "<a class=\"listlink2\" href=\"$dellink\" valign=\"top\">";
				echo "<img height=\"16\" width=\"16\" src=\"gfx/delattach.gif\" border=\"0\" align=\"absmiddle\" alt=\"$l_delattach ($numattachs)\" title=\"$l_delattach ($numattachs)\"></a>";
			}
			else
				echo "&nbsp;";
			echo "</td>";
			echo "<td class=\"adminactions\" align=\"center\" height=\"16\" width=\"16\">";
			echo "<a href=\"".do_url_session("$act_script_url?$langvar=$act_lang&announcelang=$announcelang&mode=catmove&input_announcenr=$act_id&catnr=$catnr")."\"><img height=\"16\" width=\"16\" src=\"gfx/move.gif\" border=\"0\" title=\"$l_catmove\" alt=\"$l_catmove\"></a>";
			echo "</td>";
			echo "<td class=\"adminactions\" align=\"center\" height=\"16\" width=\"16\">";
			echo "<a href=\"".do_url_session("$act_script_url?$langvar=$act_lang&announcelang=$announcelang&mode=clone&input_announcenr=$act_id&catnr=$catnr")."\"><img height=\"16\" width=\"16\" src=\"gfx/clone.gif\" border=\"0\" title=\"$l_clone\" alt=\"$l_clone\"></a>";
			echo "</td>";
			echo "<td class=\"adminactions\" align=\"center\" height=\"16\" width=\"16\">";
			echo "<a href=\"".do_url_session("$act_script_url?$langvar=$act_lang&announcelang=$announcelang&mode=edit&input_announcenr=$act_id&catnr=$catnr")."\"><img height=\"16\" width=\"16\" src=\"gfx/edit.gif\" border=\"0\" title=\"$l_edit\" alt=\"$l_edit\"></a>";
			echo "</td>";
			echo "<td class=\"adminactions\" align=\"center\" height=\"16\" width=\"16\">";
			$dellink=do_url_session("$act_script_url?$langvar=$act_lang&announcelang=$announcelang&mode=del&input_announcenr=$act_id&catnr=$catnr");
			if($admdelconfirm==2)
				echo "<a class=\"listlink2\" href=\"javascript:confirmDel('$l_announce #$act_id','$dellink')\">";
			else
				echo "<a class=\"listlink2\" href=\"$dellink\">";
			echo "<img height=\"16\" width=\"16\" src=\"gfx/delete.gif\" border=\"0\" title=\"$l_delete\" alt=\"$l_delete\"></a></td>";
		}
		else
		{
			for($i=0;$i<4;$i++)
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
		echo "<tr class=\"actionrow\"><td colspan=\"13\" align=\"left\"><input class=\"snbutton\" type=\"submit\" value=\"$l_delselected\">";
		echo "&nbsp; <input class=\"snbutton\" type=\"button\" onclick=\"checkAll(document.newslist)\" value=\"$l_checkall\">";
		echo "&nbsp; <input class=\"snbutton\" type=\"button\" onclick=\"uncheckAll(document.newslist)\" value=\"$l_uncheckall\">";
		echo "</td></tr>";
	}
	echo "</form></table></td></tr></table>";
}
if(($admepp>0) && ($numentries>$admepp))
{
	$baselink="$act_script_url?$langvar=$act_lang&announcelang=$announcelang&catnr=$catnr";
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
include('./trailer.php');
?>
