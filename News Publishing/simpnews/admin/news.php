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
$page_title=$l_news;
$bbcbuttons=true;
$page="news";
$rss_nopublish=0;
$wap_nopublish=0;
$dontpurge=0;
require_once('./heading.php');
include_once("./includes/bbcode_buttons.inc");
include_once("./includes/icon_selector.inc");
include_once("./includes/email_functions.inc");
if(!isset($headingtext))
	$headingtext="";
if(!isset($sorting))
	$sorting=32;
$infotext="";
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
		if(sn_array_key_exists($admcookievals,"news_catnr") && !isset($catnr))
			$catnr=$admcookievals["news_catnr"];
		if(sn_array_key_exists($admcookievals,"news_lang"))
			$newslang=$admcookievals["news_lang"];
		if(sn_array_key_exists($admcookievals,"news_sorting"))
			$sorting=$admcookievals["news_sorting"];
		if(sn_array_key_exists($admcookievals,"news_limiting"))
			$limiting=$admcookievals["news_limiting"];
	}
}
if(!isset($limiting))
	$limiting=0;
if(!isset($newslang))
	$newslang=$act_lang;
if(!isset($catnr))
{
	if(($admin_rights<3) && bittst($secsettings,BIT_15) && !bittst($secsettings,BIT_1))
		$catnr=-1;
	else
		$catnr=0;
}
if(($admin_rights<3) && bittst($secsettings,BIT_15) && !bittst($secsettings,BIT_1) && ($catnr==0))
	$catnr=-1;
$allowcomments=$comments_allowed;
$hasattach=0;
$errmsg="";
$allowadding=true;
$dontsendmail=0;
if(isset($transfer) && isset($tmpdata) && isset($newslang))
{
	if(isset($chgnewsnr) && ($asnewentry==0))
	{
		$doedit=1;
		$input_newsnr=$chgnewsnr;
		$sql="select * from ".$tableprefix."_data where newsnr=$chgnewsnr";
		if(!$result = mysql_query($sql, $db))
		    die("Could not connect to the database.".mysql_error());
		if($myrow=mysql_fetch_array($result))
		{
			$newslang=$myrow["lang"];
			$catnr=$myrow["category"];
			if($replacemode==1)
			{
				$tmptxt=stripslashes($myrow["text"]);
				$tmptxt = str_replace("<BR>", "\n", $tmptxt);
				$tmptxt = undo_htmlspecialchars($tmptxt);
				$tmptxt = decode_emoticons($tmptxt, $url_emoticons, $db);
				$tmptxt = bbdecode($tmptxt);
				$tmptxt = undo_make_clickable($tmptxt);
				$newstext = $tmptxt."\n\n".$newstext;
			}
		}
	}
	if(!isset($replacemode) || ($replacemode==0))
	{
		$sql="select * from ".$tableprefix."_texts where lang='$newslang' and textid='proposed'";
		if(!$result = mysql_query($sql, $db))
		    die("Could not connect to the database.".mysql_error());
		if($myrow=mysql_fetch_array($result))
		{
			$trailer = stripslashes($myrow["text"]);
			$trailer = str_replace("<BR>", "\n", $trailer);
			$trailer = undo_htmlspecialchars($trailer);
			$trailer = bbdecode($trailer);
			$trailer = undo_make_clickable($trailer);
			$sql="select poster.name, poster.email from ".$tableprefix."_tmpdata tmp, ".$tableprefix."_poster poster where tmp.entrynr=$tmpdata and poster.entrynr=tmp.posterid and tmp.posterid!=0";
			if(!$result = mysql_query($sql, $db))
			    die("Could not connect to the database.".mysql_error());
			if($myrow=mysql_fetch_array($result))
			{
				$trailer=str_replace("{postername}",$myrow["name"],$trailer);
				$trailer=str_replace("{postermail}",$myrow["email"],$trailer);
				$newstext.="\n\n".$trailer;
			}
		}
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
	else if(!bittst($secsettings,BIT_1))
		$allowadding=false;
}
$newsdate=date("Y-m-d H:i:s");
$errmsg="";
if(isset($mode) && ($admin_rights>1))
{
	if($mode=="catlink")
	{
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="inforow"><td align="center" colspan="2">
<?php
		echo "$l_catlink (News# ";
		$showurl=do_url_session("nshow.php?$langvar=$act_lang&newsnr=".$input_newsnr);
		echo "<a class=\"shdetailslink\" href=\"javascript:openWindow3('$showurl','nShow',20,20,400,200);\">";
		echo "$input_newsnr)</a></td></tr>";
?>
<form name="linkform" method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="newslang" value="<?php echo $newslang?>">
<input type="hidden" name="catnr" value="<?php echo $catnr?>">
<input type="hidden" name="input_newsnr" value="<?php echo $input_newsnr?>">
<input type="hidden" name="mode" value="dolink">
<?php
			if(!isset($destlang))
				$destlang=$newslang;
			if(is_konqueror())
				echo "<tr><td></td></tr>";
			if($sessid_url)
				echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
			$sql="select * from ".$tableprefix."_categories where catnr!=$catnr";
			if(!$result = mysql_query($sql, $db))
				die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
			$totalcats=mysql_num_rows($result);
			if($catnr!=0)
				$totalcats++;
			$excludecats=array();
			echo "<tr class=\"inputrow\"><td align=\"right\" width=\"30%\">$l_destcat:</td><td>";
			if(($admrestrict==1) && ($userdata["rights"]==2) && !bittst($userdata["addoptions"],BIT_6))
				$sql="select cat.* from ".$tableprefix."_cat_adm ca, ".$tableprefix."_categories cat where cat.catnr=ca.catnr and cat.catnr!=$catnr and ca.usernr=".$userdata["usernr"];
			else
				$sql="select * from ".$tableprefix."_categories where catnr!=$catnr";
			if(!$result = mysql_query($sql, $db))
				die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
			$numdestcats=mysql_num_rows($result);
			if($catnr!=0)
			{
				if(($userdata["rights"]>2) || bittst($secsettings,BIT_1))
				{
					$numdestcats++;
					array_push($excludecats,0);
				}
			}
			if($numdestcats>0)
			{
				echo "<select name=\"destcat\">";
				echo "<option value=\"-1\"></option>";
				if(($catnr!=0) && (($userdata["rights"]>2) || bittst($secsettings,BIT_1)))
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
				echo "<form name=\"linkform\" method=\"post\" action=\"$act_script_url\">";
				echo "<input type=\"hidden\" name=\"$langvar\" value=\"$act_lang\">";
				echo "<input type=\"hidden\" name=\"newslang\" value=\"$newslang\">";
				echo "<input type=\"hidden\" name=\"catnr\" value=\"$catnr\">";
				echo "<input type=\"hidden\" name=\"input_newsnr\" value=\"$input_newsnr\">";
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
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang&catnr=$catnr&newslang=$newslang")."\">$l_newslist</a></div>";
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
			$actionlink=$simpnews_fullurl."admin/news.php?$langvar=".$myrow["language"]."&mode=catlink&input_newsnr=$input_newsnr&destcat=$destcat&destlang=$destlang";
			$htmlactionlink="<a href=\"$actionlink\">$actionlink</a>";
			$mailmsg=str_replace("{adminname}",$userdata["username"],$mailmsg);
			$mailmsg=str_replace("{input_entrynr}",$input_newsnr,$mailmsg);
			$mailmsg=str_replace("{entrytype}",$l_mail_news,$mailmsg);
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
					$sendsuccess=$mail->send($receiver, "mail","news.php - requesting link");
				do_emaillog($sendsuccess,$myrow["email"]);
			}
		}
		echo "<tr class=\"displayrow\"><td align=\"center\" colspan=\"2\">$l_linkrequested</td></tr>";
		echo "</td></tr></form></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang&catnr=$destcat&newslang=$newslang")."\">$l_newslist</a></div>";
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
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang&catnr=$catnr&newslang=$newslang")."\">$l_newslist</a></div>";
			include('./trailer.php');
			exit;
		}
		$sql="select * from ".$tableprefix."_data where newsnr=$input_newsnr";
		if(!$result = mysql_query($sql, $db))
		    die("<tr bgcolor=\"#cccccc\"><td>Unable to connect to database.".mysql_error());
		if(!$myrow=mysql_fetch_array($result))
		    die("<tr bgcolor=\"#cccccc\"><td>Unable to connect to database.".mysql_error());
		$newsdate=$myrow["date"];
		$sql="insert into ".$tableprefix."_data (category, linknewsnr, lang, date) values ($destcat, $input_newsnr, '$destlang', '$newsdate')";
		if(!$result = mysql_query($sql, $db))
		    die("<tr bgcolor=\"#cccccc\"><td>Unable to connect to database.".mysql_error());
		echo "<tr class=\"displayrow\"><td align=\"center\" colspan=\"2\">$l_newcatlinked</td></tr>";
		echo "</td></tr></form></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang&catnr=$destcat&newslang=$newslang")."\">$l_newslist</a></div>";
		include('./trailer.php');
		exit;
	}
	if($mode=="clone")
	{
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="inforow"><td align="center" colspan="2"><?php echo "$l_clone (News# $input_newsnr)"?></td></tr>
<form name="cloneform" method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="input_newsnr" value="<?php echo $input_newsnr?>">
<input type="hidden" name="mode" value="doclone">
<?php
			if(is_konqueror())
				echo "<tr><td></td></tr>";
			if($sessid_url)
				echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
			echo "<tr class=\"inputrow\"><td align=\"right\" width=\"30%\">$l_destcat:</td><td>";
			$numdestcats=0;
			if(bittst($secsettings,BIT_1) || ($userdata["rights"]>2))
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
				if(bittst($secsettings,BIT_1) || ($userdata["rights"]>2))
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
				echo language_select($newslang,"newslang","../language/");
				echo "</td></tr>";
				echo "<tr class=\"inputrow\"><td>&nbsp;</td><td>";
				echo "<input type=\"checkbox\" name=\"transattach\" value=\"1\" checked> $l_transattach";
				echo "</td></tr>";
				echo "<tr class=\"inputrow\"><td>&nbsp;</td><td>";
				echo "<input type=\"checkbox\" name=\"transdate\" value=\"1\" checked> $l_transdate";
				echo "</td></tr>";
				echo "<tr class=\"actionrow\"><td align=\"center\" colspan=\"2\">";
				echo "<input class=\"snbutton\" type=\"submit\" name=\"submit\" value=\"$l_clone\">";
				echo "</td></tr>";
			}
			echo "</form></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang&catnr=$catnr&newslang=$newslang")."\">$l_newslist</a></div>";
			include('./trailer.php');
			exit;
	}
	if($mode=="catmove")
	{
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="inforow"><td align="center" colspan="2"><?php echo "$l_catmove (News# $input_newsnr)"?></td></tr>
<form name="moveform" method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="newslang" value="<?php echo $newslang?>">
<input type="hidden" name="catnr" value="<?php echo $catnr?>">
<input type="hidden" name="input_newsnr" value="<?php echo $input_newsnr?>">
<input type="hidden" name="mode" value="domove">
<?php
			if(is_konqueror())
				echo "<tr><td></td></tr>";
			if($sessid_url)
				echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
			echo "<tr class=\"inputrow\"><td align=\"right\" width=\"30%\">$l_destcat:</td><td>";
			$numdestcats=0;
			if(($catnr!=0) && (bittst($secsettings,BIT_1) || ($userdata["rights"]>2)))
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
				if(($catnr!=0) && (bittst($secsettings,BIT_1) || ($userdata["rights"]>2)))
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
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang&catnr=$catnr&newslang=$newslang")."\">$l_newslist</a></div>";
			include('./trailer.php');
			exit;
	}
	if($mode=="massmove")
	{
		$selectednews=$_POST["newsnr"];
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="inforow"><td align="center" colspan="2">
<?php
		echo "$l_catmove (News ";
		$counter=0;
		while(list($null, $input_newsnr) = each($_POST["newsnr"]))
		{
			if($counter>0)
				echo ", ";
			echo "#$input_newsnr";
			$counter++;
		}
		echo ")"
?>
</td></tr>
<form name="moveform" method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="newslang" value="<?php echo $newslang?>">
<input type="hidden" name="catnr" value="<?php echo $catnr?>">
<input type="hidden" name="mode" value="domassmove">
<?php
    		while(list($null, $input_newsnr) = each($selectednews))
    			echo "<input type=\"hidden\" name=\"newsnr[]\" value=\"$input_newsnr\">";
			if(is_konqueror())
				echo "<tr><td></td></tr>";
			if($sessid_url)
				echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
			echo "<tr class=\"inputrow\"><td align=\"right\" width=\"30%\">$l_destcat:</td><td>";
			$numdestcats=0;
			if(($catnr!=0) && (bittst($secsettings,BIT_1) || ($userdata["rights"]>2)))
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
				if(($catnr!=0) && (bittst($secsettings,BIT_1) || ($userdata["rights"]>2)))
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
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang&catnr=$catnr&newslang=$newslang")."\">$l_newslist</a></div>";
			include('./trailer.php');
			exit;
	}
	if($mode=="domassmove")
	{
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="inforow"><td align="center" colspan="2"><?php echo $l_catmove?></td></tr>
<?php
		if(!isset($destcat) || ($destcat<0))
		{
			echo "<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">$l_nodestcat</td></tr>";
			echo "<tr class=\"actionrow\"><td align=\"center\" colspan=\"2\">";
			echo "<a href=\"javascript:history.back()\">$l_back</a>";
			echo "</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang&catnr=$catnr&newslang=$newslang")."\">$l_newslist</a></div>";
			include('./trailer.php');
			exit;
		}
		$selectednews=$_POST["newsnr"];
   		while(list($null, $input_newsnr) = each($_POST["newsnr"]))
   		{
			if($destcat>0)
			{
				$tmpsql="select * from ".$tableprefix."_categories where catnr=$destcat";
				if(!$tmpresult = mysql_query($tmpsql, $db))
					die("<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">Could not connect to the database.".mysql_error());
				if(!$tmprow=mysql_fetch_array($tmpresult))
					die("<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">Could not get data from database.");
				if($tmprow["isarchiv"]==1)
				{
					$tempsql="select max(displaypos) as newdisplaypos from ".$tableprefix."_data where category=$destcat";
					if(!$tempresult = mysql_query($tempsql, $db))
						die("<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">Could not connect to the database.".mysql_error());
					if(!$temprow = mysql_fetch_array($tempresult))
						$newpos=1;
					else
						$newpos=$temprow["newdisplaypos"]+1;
				}
				else
					$newpos=0;
			}
			else
				$newpos=0;
			$sql="update ".$tableprefix."_data set category=$destcat, displaypos=$newpos where newsnr=$input_newsnr";
			if(!$result = mysql_query($sql, $db))
				die("<tr bgcolor=\"#cccccc\"><td>Unable to connect to database.".mysql_error());
		}
		echo "<tr class=\"displayrow\"><td align=\"center\" colspan=\"2\">$l_newcatmoved2<br>";
		$counter=0;
		while(list($null, $input_newsnr) = each($selectednews))
		{
			if($counter>0)
				echo ", ";
			echo "#$input_newsnr";
			$counter++;
		}
		echo "</td></tr>";
		echo "</td></tr></form></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang&catnr=$destcat&newslang=$newslang")."\">$l_newslist</a></div>";
		include('./trailer.php');
		exit;
	}
	if($mode=="doclone")
	{
		if(!isset($catnr) || ($catnr<0))
		{
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="inforow"><td align="center" colspan="2"><?php echo "$l_clone (News# $input_newsnr)"?></td></tr>
<?php
			echo "<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">$l_nodestcat</td></tr>";
			echo "<tr class=\"actionrow\"><td align=\"center\" colspan=\"2\">";
			echo "<a href=\"javascript:history.back()\">$l_back</a>";
			echo "</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang&catnr=$catnr&newslang=$newslang")."\">$l_newslist</a></div>";
			include('./trailer.php');
			exit;
		}
		$sql="select * from ".$tableprefix."_data where newsnr=$input_newsnr";
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
			$newstext = $tmptxt;
			$actheadingicon=$myrow["headingicon"];
			$headingtext=display_encoded($myrow["heading"]);
			$dontsendmail=$myrow["dontemail"];
			$tickerurl=$myrow["tickerurl"];
			$allowcomments=$myrow["allowcomments"];
			$newsdate=$myrow["date"];
		}
	}
	if($mode=="domove")
	{
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="inforow"><td align="center" colspan="2"><?php echo $l_catmove?></td></tr>
<?php
		if(!isset($destcat) || !$destcat || ($destcat<0))
		{
			echo "<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">$l_nodestcat</td></tr>";
			echo "<tr class=\"actionrow\"><td align=\"center\" colspan=\"2\">";
			echo "<a href=\"javascript:history.back()\">$l_back</a>";
			echo "</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang&catnr=$catnr&newslang=$newslang")."\">$l_newslist</a></div>";
			include('./trailer.php');
			exit;
		}
		if($destcat>0)
		{
			$tmpsql="select * from ".$tableprefix."_categories where catnr=$destcat";
			if(!$tmpresult = mysql_query($tmpsql, $db))
				die("<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">Could not connect to the database.".mysql_error());
			if(!$tmprow=mysql_fetch_array($tmpresult))
				die("<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">Could not get data from database.");
			if($tmprow["isarchiv"]==1)
			{
				$tempsql="select max(displaypos) as newdisplaypos from ".$tableprefix."_data where category=$destcat";
				if(!$tempresult = mysql_query($tempsql, $db))
				    die("<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">Could not connect to the database.".mysql_error());
				if(!$temprow = mysql_fetch_array($tempresult))
					$newpos=1;
				else
					$newpos=$temprow["newdisplaypos"]+1;
			}
			else
				$newpos=0;
		}
		else
			$newpos=0;
		$sql="update ".$tableprefix."_data set category=$destcat, displaypos=$newpos where newsnr=$input_newsnr";
		if(!$result = mysql_query($sql, $db))
		    die("<tr bgcolor=\"#cccccc\"><td>Unable to connect to database.".mysql_error());
		echo "<tr class=\"displayrow\"><td align=\"center\" colspan=\"2\">$l_newcatmoved</td></tr>";
		echo "</td></tr></form></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang&catnr=$destcat&newslang=$newslang")."\">$l_newslist</a></div>";
		include('./trailer.php');
		exit;
	}
	if($mode=="add")
	{
		if(!isset($headingicon))
			$headingicon="";
		$newstext=trim($newstext);
		if(!isset($newstext) || !$newstext)
		{
			unset($preview);
			$errmsg=$l_nonewstext;
			$headingtext=display_encoded($heading);
		}
		else
		{
			if(isset($rss_short))
			{
				$rss_short=stripslashes($rss_short);
				$rss_short=strip_tags($rss_short);
				$rss_short=addslashes($rss_short);
			}
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
				if(isset($no_wap))
					echo "<input type=\"hidden\" name=\"no_wap\" value=\"1\">";
				if(isset($rss_short))
					echo "<input type=\"hidden\" name=\"rss_short\" value=\"$rss_short\">";
				if(isset($no_rss))
					echo "<input type=\"hidden\" name=\"no_rss\" value=\"1\">";
				if(isset($tmpdata))
					echo "<input type=\"hidden\" name=\"tmpdata\" value=\"$tmpdata\">";
				if(isset($delpropose))
					echo "<input type=\"hidden\" name=\"delpropose\" value=\"1\">";
				if($sessid_url)
					echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
				if(isset($enablecomments))
					echo "<input type=\"hidden\" name=\"enablecomments\" value=\"1\">";
				if(isset($urlautoencode))
					echo "<input type=\"hidden\" name=\"urlautoencode\" value=\"1\">";
				if(isset($enablespcode))
					echo "<input type=\"hidden\" name=\"enablespcode\" value=\"1\">";
				if(isset($dontsendemail))
					echo "<input type=\"hidden\" name=\"dontsendemail\" value=\"1\">";
				if(isset($immediatlysendemail))
					echo "<input type=\"hidden\" name=\"immediatlysendemail\" value=\"1\">";
				$newstext=stripslashes($newstext);
				$heading=stripslashes($heading);
?>
<input type="hidden" name="newslang" value="<?php echo $newslang?>">
<input type="hidden" name="catnr" value="<?php echo $catnr?>">
<input type="hidden" name="newstext" value="<?php echo display_encoded($newstext)?>">
<input type="hidden" name="heading" value="<?php echo display_encoded($heading)?>">
<input type="hidden" name="headingicon" value="<?php echo $headingicon?>">
<input type="hidden" name="mode" value="add">
<input type="hidden" name="tickerurl" value="<?php echo $tickerurl?>">
<input type="hidden" name="frompreview" value="1">
<tr class="inforow"><td align="center" colspan="2"><?php echo $l_previewprelude?></td></tr>
<?php
				if(isset($urlautoencode))
					$newstext = make_clickable($newstext);
				if(isset($enablespcode))
					$newstext = bbencode($newstext);
				if(!isset($disableemoticons))
					$newstext = encode_emoticons($newstext, $url_emoticons, $db);
				$newstext = do_htmlentities($newstext);
				$newstext = str_replace("\n", "<BR>", $newstext);
				$newstext = str_replace("\r","",$newstext);
				$newstext = undo_htmlspecialchars($newstext);
				if(isset($specialdate))
				{
					echo "<input type=\"hidden\" name=\"specialdate\" value=\"1\">";
					echo "<input type=\"hidden\" name=\"input_hour\" value=\"$input_hour\">";
					echo "<input type=\"hidden\" name=\"input_min\" value=\"$input_min\">";
					echo "<input type=\"hidden\" name=\"input_month\" value=\"$input_month\">";
					echo "<input type=\"hidden\" name=\"input_day\" value=\"$input_day\">";
					echo "<input type=\"hidden\" name=\"input_year\" value=\"$input_year\">";
					$temptime=mktime($input_hour,$input_min,0,$input_month,$input_day,$input_year);
					$actdate=date("Y-m-d H:i:s",$temptime);
				}
				else
					$actdate = date("Y-m-d H:i:s");
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
				echo $newstext;
				echo "</td></tr>";
				if(isset($rss_short))
				{
					echo "<tr class=\"displayrow\"><td align=\"left\">";
					echo "$l_rss_short:<br>$rss_short";
					echo "</td></tr>";
				}
				if(isset($tickerurl) && ($tickerurl))
				{
					echo "<tr class=\"displayrow\"><td align=\"left\">";
					echo "$l_tickerurl: $tickerurl";
					echo "</td></tr>";
				}
				if(isset($new_files))
				{
					while(list($null, $actattach) = each($_POST["new_files"]))
						echo "<input type=\"hidden\" name=\"new_files[]\" value=\"$actattach\">";
				}
				if(isset($nopurge))
				{
					echo "<tr class=\"displayrow\"><td>&nbsp;</td><td>";
					echo $l_dontpurge;
					echo "<input type=\"hidden\" name=\"dontpurge\" value=\"1\">";
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
				if(isset($no_rss))
					$rss_nopublish=1;
				else
					$rss_nopublish=0;
				if(isset($dontsendemail))
					$dontemail=1;
				else
					$dontemail=0;
				if(isset($enablecomments))
					$allowcomments=1;
				else
					$allowcomments=0;
				if(strlen($userdata["realname"])>0)
					$poster=$userdata["realname"];
				else
					$poster=$userdata["username"];
				if(isset($urlautoencode))
					$newstext = make_clickable($newstext);
				if(isset($enablespcode))
					$newstext = bbencode($newstext);
				if(!isset($disableemoticons))
					$newstext = encode_emoticons($newstext, $url_emoticons, $db);
				if($heading)
					$searchtext = stripslashes($heading)." ";
				else
					$searchtext = "";
				$searchtext.= stripslashes(strip_tags($newstext));
				$newstext = do_htmlentities($newstext);
				$newstext = str_replace("\n", "<BR>", $newstext);
				$newstext = str_replace("\r", "", $newstext);
				$newstext=addslashes($newstext);
				$searchtext = remove_htmltags($searchtext);
				$searchtext = strtolower($searchtext);
				$searchtext = addslashes($searchtext);
				if(isset($specialdate))
				{
					$temptime=mktime($input_hour,$input_min,0,$input_month,$input_day,$input_year);
					$temptime=transposetime($temptime,$displaytimezone,$servertimezone);
					$actdate=date("Y-m-d H:i:s",$temptime);
				}
				else
					$actdate = date("Y-m-d H:i:s");
				$today=date("Y-m-d H:i:s");
				if(isset($tmpdata) && $tmpdata)
				{
					$postersql="select * from ".$tableprefix."_tmpdata where entrynr=$tmpdata";
					if(!$presult = mysql_query($postersql, $db))
					    die("Unable to connect to database.".mysql_error());
					if($prow=mysql_fetch_array($presult))
						$exposter=$prow["posterid"];
				}
				if($catnr>0)
				{
					$tmpsql="select * from ".$tableprefix."_categories where catnr=$catnr";
					if(!$tmpresult = mysql_query($tmpsql, $db))
					    die("Unable to connect to database.".mysql_error());
					if(!$tmprow=mysql_fetch_array($tmpresult))
					    die("Unable to get data from database. (categories)");
					if($tmprow["isarchiv"]==1)
					{
						$tempsql="select max(displaypos) as newdisplaypos from ".$tableprefix."_data where category=$catnr";
						if(!$tempresult = mysql_query($tempsql, $db))
							die("<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">Could not connect to the database.".mysql_error());
						if(!$temprow = mysql_fetch_array($tempresult))
							$displaypos=1;
						else
							$displaypos=$temprow["newdisplaypos"]+1;
					}
					else
						$displaypos=0;
				}
				else
					$displaypos=0;
				if(isset($nopurge))
					$dontpurge=1;
				else
					$dontpurge=0;
				$sql = "insert into ".$tableprefix."_data (lang, date, text, heading, poster, headingicon, category, allowcomments, tickerurl, posterid, dontemail, added, displaypos, wap_nopublish, rss_nopublish, dontpurge";
				if(isset($rss_short))
					$sql.=", rss_short";
				if(isset($exposter))
					$sql.=", exposter";
				$sql.= ") values ('$newslang', '$actdate', '$newstext', '$heading', '$poster', '$headingicon', $catnr, $allowcomments, '$tickerurl', ".$userdata["usernr"].", $dontemail, '$today', $displaypos, $wap_nopublish, $rss_nopublish, $dontpurge";
				if(isset($rss_short))
					$sql.=", '$rss_short'";
				if(isset($exposter))
					$sql.=", $exposter";
				$sql.=")";
				if(!$result = mysql_query($sql, $db))
				    die("Unable to connect to database.".mysql_error());
				$newsnr=mysql_insert_id($db);
				if(isset($tmpdata) && $tmpdata && isset($delpropose))
				{
					$delsql="delete from ".$tableprefix."_tmpdata where entrynr=$tmpdata";
					if(!$result = mysql_query($delsql, $db))
					    die("Unable to connect to database.".mysql_error());
					$delsql="delete from ".$tableprefix."_tmpnews_attachs where newsnr=$tmpdata";
					if(!$result = mysql_query($delsql, $db))
					    die("Unable to connect to database.".mysql_error());
				}
				$sql = "insert into ".$tableprefix."_search (newsnr, text) values ($newsnr, '$searchtext')";
				if(!$result = mysql_query($sql, $db))
				    die("Unable to connect to database.".mysql_error());
				if(isset($new_files))
				{
					while(list($null, $actattach) = each($_POST["new_files"]))
					{
						$sql="insert into ".$tableprefix."_news_attachs (attachnr,newsnr) values ('$actattach','$newsnr')";
						@mysql_query($sql, $db);
					}
				}
				$infotext=$l_entryadded." (#".$newsnr.")";
				if(($enablesubscriptions==1) && (($subscriptionsendmode==0) || isset($immediatlysendemail)) && (!isset($dontsendemail)))
				{
					$sentmails=email_single_news($heading, $newstext, $newslang, $db, $subject, $simpnewsmail, $simpnewsmailname, $actdate, $poster, $newsnr, $headingicon, $catnr);
					$infotext.="<br>$l_emailssent ($sentmails)";
					if($showsendprogress==1)
					{
						$infotext.="<br><a href=\"javascript:showprogressbox()\"";
						$infotext.=" class=\"actionlink\">$l_reshowprogressbox</a>";
					}
				}
				unset($tickerurl);
				$rss_nopublish=0;
				$wap_nopublish=0;
				$dontpurge=0;
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
<form name="delform" method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="confirmed" value="1">
<input type="hidden" name="newslang" value="<?php echo $newslang?>">
<input type="hidden" name="catnr" value="<?php echo $catnr?>">
<input type="hidden" name="mode" value="massdel">
<?php
			$selectednews=$_POST["newsnr"];
    		while(list($null, $input_newsnr) = each($_POST["newsnr"]))
    			echo "<input type=\"hidden\" name=\"newsnr[]\" value=\"$input_newsnr\">";
			if(is_konqueror())
				echo "<tr><td></td></tr>";
			if($sessid_url)
				echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
			echo "<tr class=\"inforow\"><td align=\"center\">";
			echo "$l_confirmdel2: News ";
			$counter=0;
    		while(list($null, $input_newsnr) = each($selectednews))
    		{
    			if($counter>0)
    				echo ", ";
				echo "#$input_newsnr";
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
		if(isset($newsnr))
		{
    		while(list($null, $input_newsnr) = each($_POST["newsnr"]))
    		{
				$sql = "select * from ".$tableprefix."_data where newsnr=$input_newsnr";
				if(!$result = mysql_query($sql, $db))
				    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
				if(!$myrow=mysql_fetch_array($result))
				    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
				if($myrow["linknewsnr"]==0)
				{
					$sql = "delete from ".$tableprefix."_data where linknewsnr=$input_newsnr";
					if(!$result = mysql_query($sql, $db))
					    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
				}
				$sql = "delete from ".$tableprefix."_data where newsnr=$input_newsnr";
				if(!$result = mysql_query($sql, $db))
				    die("Unable to connect to database.".mysql_error());
				$sql = "delete from ".$tableprefix."_search where newsnr=$input_newsnr";
				if(!$result = mysql_query($sql, $db))
				    die("Unable to connect to database.".mysql_error());
				$sql = "delete from ".$tableprefix."_news_attachs where newsnr=$input_newsnr";
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
<form name="delform" method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="confirmed" value="1">
<input type="hidden" name="newslang" value="<?php echo $newslang?>">
<input type="hidden" name="catnr" value="<?php echo $catnr?>">
<input type="hidden" name="mode" value="del">
<input type="hidden" name="input_newsnr" value="<?php echo $input_newsnr?>">
<?php
			if(is_konqueror())
				echo "<tr><td></td></tr>";
			if($sessid_url)
				echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
			echo "<tr class=\"inforow\"><td align=\"center\">";
			echo "$l_confirmdel: News #$input_newsnr";
			echo "</td></tr>";
			echo "<tr class=\"actionrow\"><td align=\"center\">";
			echo "<input class=\"snbutton\" type=\"submit\" name=\"submit\" value=\" $l_yes \">";
			echo "&nbsp;<input class=\"snbutton\" type=\"button\" value=\" $l_no \" onclick=\"self.history.back();\">";
			echo "</td></tr>";
			echo "</form></table></td></tr></table>";
			include('./trailer.php');
			exit;
		}
		$sql = "select * from ".$tableprefix."_data where newsnr=$input_newsnr";
		if(!$result = mysql_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
		if(!$myrow=mysql_fetch_array($result))
		    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
		if($myrow["linknewsnr"]==0)
		{
			$sql = "delete from ".$tableprefix."_data where linknewsnr=$input_newsnr";
			if(!$result = mysql_query($sql, $db))
			    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
		}
		$sql = "delete from ".$tableprefix."_data where newsnr=$input_newsnr";
		if(!$result = mysql_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
		$sql = "delete from ".$tableprefix."_search where newsnr=$input_newsnr";
		if(!$result = mysql_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
		$sql = "delete from ".$tableprefix."_news_attachs where newsnr=$input_newsnr";
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
<form name="delform" method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="confirmed" value="1">
<input type="hidden" name="newslang" value="<?php echo $newslang?>">
<input type="hidden" name="catnr" value="<?php echo $catnr?>">
<input type="hidden" name="mode" value="delattach">
<input type="hidden" name="input_newsnr" value="<?php echo $input_newsnr?>">
<?php
			if(is_konqueror())
				echo "<tr><td></td></tr>";
			if($sessid_url)
				echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
			echo "<tr class=\"inforow\"><td align=\"center\">";
			echo "$l_confirmdel: $l_attachements - News #$input_newsnr";
			echo "</td></tr>";
			echo "<tr class=\"actionrow\"><td align=\"center\">";
			echo "<input class=\"snbutton\" type=\"submit\" name=\"submit\" value=\" $l_yes \">";
			echo "&nbsp;<input class=\"snbutton\" type=\"button\" value=\" $l_no \" onclick=\"self.history.back();\">";
			echo "</td></tr>";
			echo "</form></table></td></tr></table>";
			include('./trailer.php');
			exit;
		}
		$sql = "delete from ".$tableprefix."_news_attachs where newsnr=$input_newsnr";
		if(!$result = mysql_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
	}
	if($mode=="edit")
	{
		$sql = "select * from ".$tableprefix."_data where newsnr=$input_newsnr";
		if(!$result = mysql_query($sql, $db))
		    die("Unable to connect to database.".mysql_error());
		if ($myrow = mysql_fetch_array($result))
		{
			$doedit=1;
			$headingtext=display_encoded(stripslashes($myrow["heading"]));
			$newstext = stripslashes($myrow["text"]);
			$newstext = str_replace("<BR>", "\n", $newstext);
			$newstext = undo_htmlspecialchars($newstext);
			$newstext = decode_emoticons($newstext, $url_emoticons, $db);
			$newstext = bbdecode($newstext);
			$newstext = undo_make_clickable($newstext);
			$actheadingicon=$myrow["headingicon"];
			$allowcomments=$myrow["allowcomments"];
			$tickerurl=$myrow["tickerurl"];
			$newsdate=$myrow["date"];
			$dontsendmail=$myrow["dontemail"];
			$currentviews=$myrow["views"];
			$wap_nopublish=$myrow["wap_nopublish"];
			$rss_nopublish=$myrow["rss_nopublish"];
			$rss_short=stripslashes($myrow["rss_short"]);
			$dontpurge=$myrow["dontpurge"];
		}
		$sql="select na.entrynr as attentry, files.* from ".$tableprefix."_news_attachs na, ".$tableprefix."_files files where files.entrynr=na.attachnr and na.newsnr=$input_newsnr";
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
		$newstext=trim($newstext);
		if(isset($newstext) && $newstext)
		{
			if(isset($enablecomments))
				$allowcomments=1;
			else
				$allowcomments=0;
			if(isset($rss_short))
			{
				$rss_short=stripslashes($rss_short);
				$rss_short=strip_tags($rss_short);
				$rss_short=addslashes($rss_short);
			}
			if(isset($preview))
			{
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<form method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<?php
				$newstext=stripslashes($newstext);
				$heading=stripslashes($heading);
				if(is_konqueror())
					echo "<tr><td></td></tr>";
				if(isset($no_wap))
					echo "<input type=\"hidden\" name=\"no_wap\" value=\"1\">";
				if(isset($rss_short))
					echo "<input type=\"hidden\" name=\"rss_short\" value=\"$rss_short\">";
				if(isset($no_rss))
					echo "<input type=\"hidden\" name=\"no_rss\" value=\"1\">";
				if(isset($resetviews))
					echo "<input type=\"hidden\" name=\"resetviews\" value=\"1\">";
				if($sessid_url)
					echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
				if(isset($enablecomments))
					echo "<input type=\"hidden\" name=\"enablecomments\" value=\"1\">";
				if(isset($urlautoencode))
					echo "<input type=\"hidden\" name=\"urlautoencode\" value=\"1\">";
				if(isset($enablespcode))
					echo "<input type=\"hidden\" name=\"enablespcode\" value=\"1\">";
				if(isset($delattach))
					echo "<input type=\"hidden\" name=\"delattach\" value=\"1\">";
				if(isset($resetdate))
					echo "<input type=\"hidden\" name=\"resetdate\" value=\"1\">";
				if(isset($dontsendemail))
					echo "<input type=\"hidden\" name=\"dontsendemail\" value=\"1\">";
				if(isset($immediatlysendemail))
					echo "<input type=\"hidden\" name=\"immediatlysendemail\" value=\"1\">";
?>
<input type="hidden" name="newslang" value="<?php echo $newslang?>">
<input type="hidden" name="catnr" value="<?php echo $catnr?>">
<input type="hidden" name="newstext" value="<?php echo do_htmlentities($newstext)?>">
<input type="hidden" name="heading" value="<?php echo do_htmlentities($heading)?>">
<input type="hidden" name="headingicon" value="<?php echo $headingicon?>">
<input type="hidden" name="mode" value="update">
<input type="hidden" name="input_newsnr" value="<?php echo $input_newsnr?>">
<input type="hidden" name="tickerurl" value="<?php echo $tickerurl?>">
<input type="hidden" name="frompreview" value="1">
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
					$newstext = make_clickable($newstext);
				if(isset($enablespcode))
					$newstext = bbencode($newstext);
				if(!isset($disableemoticons))
					$newstext = encode_emoticons($newstext, $url_emoticons, $db);
				$newstext = do_htmlentities($newstext);
				$newstext = str_replace("\n", "<BR>", $newstext);
				$newstext = str_replace("\r", "", $newstext);
				$newstext = undo_htmlspecialchars($newstext);
				$today=date("Y-m-d H:i:s");
				if(isset($resetdate))
				{
					$acttime=transposetime(time(),$servertimezone,$displaytimezone);
					$actdate = date("Y-m-d H:i:s",$acttime);
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
					$actdate=date("Y-m-d H:i:s",$temptime);
				}
				else
				{
					$tempsql="select * from ".$tableprefix."_data where newsnr=$input_newsnr";
					if(!$tempresult = mysql_query($tempsql, $db))
					    die("Unable to connect to database.".mysql_error());
					if($temprow=mysql_fetch_array($tempresult))
					{
						list($mydate,$mytime)=explode(" ",$temprow["date"]);
						list($year, $month, $day) = explode("-", $mydate);
						list($hour, $min, $sec) = explode(":",$mytime);
						$temptime=mktime($hour,$min,$sec,$month,$day,$year);
						$temptime=transposetime($temptime,$servertimezone,$displaytimezone);
						$actdate=date("Y-m-d H:i:s",$temptime);
					}
					else
						$actdate="";
				}
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
					echo do_htmlentities($heading);
					echo "</td></tr>";
				}
				echo "<tr class=\"newsentry\"><td align=\"left\">";
				echo $newstext;
				echo "</td></tr>";
				if(isset($rss_short))
				{
					echo "<tr class=\"displayrow\"><td align=\"left\">";
					echo "$l_rss_short:<br>$rss_short";
					echo "</td></tr>";
				}
				if(isset($tickerurl) && ($tickerurl))
				{
					echo "<tr class=\"displayrow\"><td align=\"left\">";
					echo "$l_tickerurl: $tickerurl";
					echo "</td></tr>";
				}
				if(isset($nopurge))
				{
					echo "<tr class=\"displayrow\"><td>&nbsp;</td><td>";
					echo $l_dontpurge;
					echo "<input type=\"hidden\" name=\"dontpurge\" value=\"1\">";
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
				if(isset($no_rss))
					$rss_nopublish=1;
				else
					$rss_nopublish=0;
				if(isset($dontsendemail))
					$dontemail=1;
				else
					$dontemail=0;
				if(isset($urlautoencode))
					$newstext = make_clickable($newstext);
				if(isset($enablespcode))
					$newstext = bbencode($newstext);
				if(!isset($disableemoticons))
					$newstext = encode_emoticons($newstext, $url_emoticons, $db);
				if($heading)
					$searchtext = $heading." ";
				else
					$searchtext = "";
				$searchtext.= $newstext;
				$newstext = do_htmlentities($newstext);
				$newstext = str_replace("\n", "<BR>", $newstext);
				$newstext = str_replace("\r", "", $newstext);
				$searchtext = remove_htmltags($searchtext);
				$searchtext = strtolower($searchtext);
				$newstext=addslashes($newstext);
				$actdate = date("Y-m-d H:i:s");
				$today = date("Y-m-d H:i:s");
				$sql = "update ".$tableprefix."_data set added='$today', headingicon='$headingicon', text='$newstext', heading='$heading', allowcomments=$allowcomments, tickerurl='$tickerurl', dontemail=$dontemail, rss_nopublish=$rss_nopublish, wap_nopublish=$wap_nopublish";
				if(isset($rss_short))
					$sql.=", rss_short='$rss_short'";
				if(isset($resetviews))
					$sql.=", views=0";
				if(isset($nopurge))
					$sql.=", dontpurge=1";
				else
					$sql.=", dontpurge=0";
				if(isset($resetdate))
				{
					$sql.=", date='$actdate'";
					$tmpsql="update ".$tableprefix."_data set date='$actdate' where linknewsnr=$input_newsnr";
					if(!$tmpresult = mysql_query($tmpsql, $db))
					    die("<tr class=\"errorrow\"><td>Unable to update entry in database.");
				}
				else if(isset($specialdate))
				{
					$temptime=mktime($input_hour,$input_min,0,$input_month,$input_day,$input_year);
					$temptime=transposetime($temptime,$displaytimezone,$servertimezone);
					$actdate=date("Y-m-d H:i:s",$temptime);
					$sql.=", date='$actdate'";
					$tmpsql="update ".$tableprefix."_data set date='$actdate' where linknewsnr=$input_newsnr";
					if(!$tmpresult = mysql_query($tmpsql, $db))
					    die("<tr class=\"errorrow\"><td>Unable to update entry in database.");
				}
				$sql.= " where newsnr=$input_newsnr";
				if(!$result = mysql_query($sql, $db))
				    die("Unable to connect to database.".mysql_error());
				$sql = "update ".$tableprefix."_search set text='$searchtext' where newsnr=$input_newsnr";
				if(!$result = mysql_query($sql, $db))
				    die("Unable to connect to database.".mysql_error());
				if(isset($new_files))
				{
					while(list($null, $actattach) = each($_POST["new_files"]))
					{
						$sql="insert into ".$tableprefix."_news_attachs (attachnr,newsnr) values ('$actattach','$input_newsnr')";
						@mysql_query($sql, $db);
					}
				}
				if(isset($del_files))
				{
					while(list($null, $actattach) = each($_POST["del_files"]))
					{
						$sql="delete from ".$tableprefix."_news_attachs where entrynr=$actattach";
						@mysql_query($sql, $db);
					}
				}
				$infotext=$l_entryupdated." (#".$input_newsnr.")";
				if(($enablesubscriptions==1) && (($subscriptionsendmode==0) || isset($immediatlysendemail)) && (!isset($dontsendemail)))
				{
					$sql = "select * from ".$tableprefix."_data where newsnr=$input_newsnr";
					if(!$result = mysql_query($sql, $db))
					    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
					if($myrow=mysql_fetch_array($result))
					{
						$poster=$myrow["poster"];
						$sentmails=email_single_news($heading, $newstext, $newslang, $db, $subject, $simpnewsmail, $simpnewsmailname, $actdate, $poster, $input_newsnr, $headingicon, $catnr);
						$infotext.="<br>$l_emailssent ($sentmails)";
						if($showsendprogress==1)
						{
							$infotext.="<br><a href=\"javascript:showprogressbox()\"";
							$infotext.=" class=\"actionlink\">$l_reshowprogressbox</a>";
						}
					}
				}
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
if(($admin_rights<3) && bittst($secsettings,BIT_15) && !bittst($secsettings,BIT_1))
	$numavailcats=0;
else
	$numavailcats=1;
if(($admin_rights>2) || !bittst($secsettings,BIT_15))
	$catsql="select cat.* from ".$tableprefix."_categories cat";
else
	$catsql="select cat.* from ".$tableprefix."_categories cat, ".$tableprefix."_cat_adm ca where cat.catnr=ca.catnr and ca.usernr=".$userdata["usernr"];
$catsql.=" order by cat.displaypos asc";
if(!$result = mysql_query($catsql, $db))
    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
$numavailcats+=mysql_num_rows($result);
if($numavailcats<1)
{
	echo "<tr class=\"inforow\"><td align=\"center\" valign=\"top\" colspan=\"2\"><b>$l_noeditcats</b></td></tr>";
	echo "</table></td></tr></table>";
	include('./trailer.php');
	exit;
}
echo "<form name=\"filterform\" method=\"post\" action=\"$act_script_url\">";
echo "<input type=\"hidden\" name=\"$langvar\" value=\"$act_lang\">";
	if(is_konqueror())
		echo "<tr><td></td></tr>";
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
	if($admstorefilter==1)
		echo "<input type=\"hidden\" name=\"dostorefilter\" value=\"1\">";
?>
<tr class="inputrow"><td align="center" colspan="2">
<table width="100%" align="center" cellspacing="0" cellpadding="0" border="0">
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
else if(!bittst($secsettings,BIT_15))
{
	echo "<option value=\"0\"";
	if($catnr==0)
		echo "selected";
	echo ">$l_general</option>";
}
else if(bittst($secsettings,BIT_1))
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
<tr class="inputrow"><td align="right" width="50%"><?php echo $l_edlang?>:</td><td align="left" width="40%">
<?php echo language_select($newslang,"newslang","../language/")?></td><td align="right" width="10%">
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
	    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
	if($temprow=mysql_fetch_array($tempresult))
		$catname=$temprow["catname"];
	else
		$catname=$l_without." ".$l_category;
}
echo "<tr class=\"inforow\"><td align=\"center\" valign=\"top\" colspan=\"2\"><b>$l_actuallyselected: $catname, $newslang</b></td></tr>";
if($errmsg)
	echo "<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">$errmsg</td></tr>";
if(($admin_rights > 1) && $allowadding)
{
	echo "<form name=\"inputform\" method=\"post\" action=\"$act_script_url\" target=\"_self\" onsubmit=\"return checkinputform()\">";
	if(is_konqueror())
		echo "<tr><td></td></tr>";
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="newslang" value="<?php echo $newslang?>">
<tr class="inputrow"><td align="right" width="20%"><?php echo $l_heading?>:</td>
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
<tr class="inputrow"><td align="right" valign="top" width="20%"><?php echo $l_news?>:
</td>
<td align="left"><textarea class="sninput" name="newstext" rows="10" cols="50">
<?php
if(isset($doedit) || isset($transfer) || isset($doclone))
	echo $newstext;
echo "</textarea><br>";
if(($admin_rights>2) || !bittst($userdata["addoptions"],BIT_2))
	display_smiliebox("newstext");
if(($admin_rights>2) || !bittst($userdata["addoptions"],BIT_3))
	display_bbcode_buttons($l_bbbuttons,"newstext");
echo "</td></tr>";
if(isset($doedit))
{
	echo "<input type=\"hidden\" name=\"mode\" value=\"update\">";
	echo "<input type=\"hidden\" name=\"input_newsnr\" value=\"$input_newsnr\">";
}
else
	echo "<input type=\"hidden\" name=\"mode\" value=\"add\">";
if(isset($tmpdata))
	echo "<input type=\"hidden\" name=\"tmpdata\" value=\"$tmpdata\">";
if(isset($delpropose))
	echo "<input type=\"hidden\" name=\"delpropose\" value=\"1\">";
if(($rss_enable==1) || ($wap_enable==1))
{
	echo "<tr class=\"inputrow\"><td align=\"right\" valign=\"top\" width=\"20%\">";
	echo "$l_rss_short :";
	if($wap_enable==1)
		echo "<br>($l_wap_rss_short)";
	echo "</td>";
	echo "<td align=\"left\"><textarea class=\"sninput\" name=\"rss_short\" rows=\"5\" cols=\"50\">";
	if(isset($doedit))
		echo $rss_short;
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
	if(($subscriptionsendmode==1) && ($admin_rights>=$sublevel))
	{
		echo "<tr class=\"optionrow\"><td>&nbsp;</td><td><input onClick=\"sendnow()\" type=\"checkbox\" name=\"immediatlysendemail\" value=\"1\"";
		if($dontsendmail==1)
			echo " disabled";
		echo "> $l_immediatlysendnewsletter</td></tr>";
	}
	echo "<tr class=\"optionrow\"><td>&nbsp;</td><td><input onClick=\"dontemail()\" type=\"checkbox\" name=\"dontsendemail\" value=\"1\" ";
	if($dontsendmail==1)
		echo "checked";
	echo "> $l_dontsendinnewsletter</td></tr>";
}
if($rss_enable==1)
{
	echo "<tr class=\"optionrow\"><td>&nbsp;</td><td>";
	echo "<input type=\"checkbox\" name=\"no_rss\" value=\"1\"";
	if($rss_nopublish==1)
		echo " checked";
	echo "> $l_rss_no_publish</td></tr>";
}
if($wap_enable==1)
{
	echo "<tr class=\"optionrow\"><td>&nbsp;</td><td>";
	echo "<input type=\"checkbox\" name=\"no_wap\" value=\"1\"";
	if($wap_nopublish==1)
		echo " checked";
	echo "> $l_wap_no_publish</td></tr>";
}
echo "<tr class=\"optionrow\"><td>&nbsp;</td><td>";
echo "<input type=\"checkbox\" name=\"nopurge\" value=\"1\"";
if($dontpurge==1)
	echo " checked";
echo "> $l_dontpurge</td></tr>";
?>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_tickerurl?>:</td>
<td><input class="sninput" type="text" name="tickerurl" size="40" maxlength="240" <?php if(isset($tickerurl)) echo "value=\"$tickerurl\""?>></td></tr>
<?php
if($upload_avail)
{
?>
<tr class="inputrow"><td align="right" valign="top" width="30%"><?php echo $l_attachfile?>:</td>
<td>
<?php
	echo "<select id=\"new_files\" name=\"new_files[]\" size=\"5\" multiple>";
	if(isset($transfer) && isset($tmpdata) && isset($newslang))
	{
		$tmpsql="select f.* from ".$tableprefix."_files f, ".$tableprefix."_tmpnews_attachs tna where tna.newsnr=$tmpdata and f.entrynr=tna.attachnr";
		if(!$tmpresult = mysql_query($tmpsql, $db))
		    die("Could not connect to the database.".mysql_error());
		if($tmprow=mysql_fetch_array($tmpresult))
			echo "<option value=\"".$tmprow["entrynr"]."\" selected>".$tmprow["filename"]."</option>";
	}
	if(isset($doclone) && isset($transattach))
	{
		$tmpsql="select f.* from ".$tableprefix."_files f, ".$tableprefix."_news_attachs na where na.newsnr=$input_newsnr and f.entrynr=na.attachnr";
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
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="enablecomments" value="1" <?php if($allowcomments==1) echo "checked"?>> <?php echo $l_commentsonpost?></td></tr>
<?php
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
list($mydate,$mytime)=explode(" ",$newsdate);
list($year, $month, $day) = explode("-", $mydate);
list($hour, $min, $sec) = explode(":",$mytime);
$temptime=mktime($hour,$min,$sec,$month,$day,$year);
$temptime=transposetime($temptime,$servertimezone,$displaytimezone);
$year=date("Y",$temptime);
$month=date("m",$temptime);
$day=date("d",$temptime);
$hour=date("H",$temptime);
$min=date("i",$temptime);
?>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="specialdate" value="1" onclick="special_date(this.checked)"> <?php echo $l_usethisdate?>:<br>
<table width="100%">
<tr>
<td align="center"><?php echo $l_day?></td><td align="center"><?php echo $l_month?></td><td align="center"><?php echo $l_year?></td>
<td align="center"><?php echo $l_hour?></td><td align="center"><?php echo $l_minutes?></td></tr>
<tr>
<td align="center">
<select name="input_day" disabled>
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
<select name="input_month" disabled>
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
<select name="input_year" disabled>
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
<select name="input_hour" disabled>
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
<select name="input_min" disabled>
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
<tr class="actionrow"><td align="center" colspan="2"><input class="snbutton" type="submit" name="sendform" value="<?php if(isset($doedit)) echo $l_update; else echo $l_add?>">&nbsp;&nbsp;
<?php
	if($admaltprv==1)
		echo "<input class=\"snbutton\" type=\"button\" onClick=\"dopreview()\" name=\"preview\" value=\"$l_preview\">";
	else
		echo "<input class=\"snbutton\" type=\"submit\" name=\"preview\" value=\"$l_preview\">";
	echo "</td></tr></form>";
}
echo "</table></td></tr></table>";
if(isset($doclone) && isset($transdate))
{
	echo "<script type=\"text/javascript\" language=\"JavaScript\">\r\n";
	echo "<!--\r\n";
	echo "special_date(true)\r\n";
	echo "document.inputform.specialdate.checked=true;\r\n";
	echo "//-->\r\n";
	echo "</script>\r\n";
}
echo "<p></p>";
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<form name="filterform2" method="post" action="<?php echo $act_script_url?>#entrylist">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="newslang" value="<?php echo $newslang?>">
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
$sql = "select * from ".$tableprefix."_data where lang='$newslang' and category=$catnr ";
if($limiting==1)
	$sql.="and posterid=".$userdata["usernr"]." ";
switch($sorting)
{
	case 11:
		$sql.="order by newsnr asc";
		break;
	case 12:
		$sql.="order by newsnr desc";
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
	die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
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
	    die("<tr class=\"errorow\"><td>Unable to connect to database.".mysql_error());
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
	$baselink="$act_script_url?$langvar=$act_lang&newslang=$newslang&catnr=$catnr&sorting=$sorting";
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
if ($myrow = mysql_fetch_array($result))
{
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<form name="newslist" method="post" action="<?php echo $act_script_url?>" target="_self">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="newslang" value="<?php echo $newslang?>">
<input type="hidden" name="mode" value="massdel">
<input type="hidden" name="catnr" value="<?php echo $catnr?>">
<?php
	$colspan=13;
	if($enablerating)
		$colspan++;
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
	if(is_konqueror())
		echo "<tr><td></td></tr>";
	$baseurl=$act_script_url."?".$langvar."=".$act_lang;
	if($admstorefilter==1)
		$baseurl.="&dostorefilter=1";
	if(isset($newslang))
		$baseurl.="&newslang=$newslang";
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
	if($enablerating==1)
	{
		echo "<td align=\"center\"><b>$l_ratings</b></td>";
	}
	echo "<td colspan=\"7\">&nbsp;</td>";
	echo "</tr>";
	do{
		$act_id=$myrow["newsnr"];
		if($myrow["linknewsnr"]==0)
			$entrydata=$myrow;
		else
		{
			$tmpsql="select * from ".$tableprefix."_data where newsnr=".$myrow["linknewsnr"];
			if(!$tmpresult = mysql_query($tmpsql, $db))
			    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
			if(!$tmprow=mysql_fetch_array($tmpresult))
			    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
			$entrydata=$tmprow;
		}
		$newstext = stripslashes($entrydata["text"]);
		$newstext = undo_htmlspecialchars($newstext);
		if($admentrychars>0)
		{
			$newstext=undo_htmlentities($newstext);
			$newstext=strip_tags($newstext);
			$newstext=substr($newstext,0,$admentrychars);
			$newstext.="[...]";
		}
		if($admonlyentryheadings==0)
		{
			if($entrydata["heading"])
				$displaytext="<b>".$entrydata["heading"]."</b><br>".$newstext;
			else
				$displaytext=$newstext;
		}
		else
		{
			if($entrydata["heading"])
				$displaytext="<b>".$entrydata["heading"]."</b>";
			else
			{
				$displaytext=strip_tags($entrydata["text"]);
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
			else
			{
				if($entrydata["posterid"]==$userdata["usernr"])
					$allowactions=true;
			}
		}
		else
			if($admin_rights > 1)
				$allowactions=$allowadding;
		echo "<tr>";
		echo "<td class=\"actionrow\" align=\"center\" width=\"1%\" valign=\"top\">";
		if($allowactions)
			echo "<input type=\"checkbox\" name=\"newsnr[]\" value=\"$act_id\">";
		else
			echo "&nbsp;";
		echo "</td>";
		echo "<td class=\"displayrow\" align=\"center\" width=\"8%\" valign=\"top\">";
		if($myrow["linknewsnr"]!=0)
			echo "<img src=\"gfx/link_small.gif\" border=\"0\" align=\"top\" title=\"$l_islink: ".$myrow["linknewsnr"]."\" alt=\"$l_islink: ".$myrow["linknewsnr"]."\"> ";
		else
		{
			$tmpsql="select * from ".$tableprefix."_data where linknewsnr=".$myrow["newsnr"];
			if(!$tmpresult = mysql_query($tmpsql, $db))
				die("<tr bgcolor=\"#cccccc\"><td>Unable to connect to database.".mysql_error());
			if(mysql_num_rows($tmpresult)>0)
				echo "<img src=\"gfx/link_target.gif\" border=\"0\" align=\"top\" title=\"$l_islinktarget\" alt=\"$l_islinktarget\"> ";
		}
		if($myrow["linknewsnr"]==0)
			$showurl=do_url_session("nshow.php?$langvar=$act_lang&newsnr=".$myrow["newsnr"]);
		else
			$showurl=do_url_session("nshow.php?$langvar=$act_lang&newsnr=".$myrow["linknewsnr"]);
		echo "<a class=\"shdetailslink\" href=\"javascript:openWindow3('$showurl','nShow',20,20,400,200);\">";
		echo $myrow["newsnr"];
		echo "</a>";
		if($myrow["dontpurge"]==1)
			echo "<br><img src=\"gfx/protected.gif\" border=\"0\" align=\"top\" title=\"".$l_dontpurge."\" alt=\"".$l_dontpurge."\">";
		echo "</td>";
		echo "<td class=\"newsicon\" align=\"center\" width=\"2%\">";
		if($entrydata["headingicon"])
			echo "<img src=\"$url_icons/".$entrydata["headingicon"]."\" border=\"0\" align=\"bottom\">";
		else
			echo "&nbsp;";
		echo "</td><td class=\"newsentry\" align=\"left\">";
		echo "$displaytext</td>";
		echo "<td class=\"newsdate\" align=\"center\" width=\"20%\" valign=\"top\">";
		list($mydate,$mytime)=explode(" ",$entrydata["date"]);
		list($year, $month, $day) = explode("-", $mydate);
		list($hour, $min, $sec) = explode(":",$mytime);
		$temptime=mktime($hour,$min,$sec,$month,$day,$year);
		$temptime=transposetime($temptime,$servertimezone,$displaytimezone);
		$displaydate=date($l_admdateformat,$temptime);
		echo "$displaydate</td>";
		echo "<td class=\"displayrow\" align=\"right\" width=\"5%\" valign=\"top\">";
		echo $myrow["views"];
		echo "</td>";
		if($enablerating)
		{
			echo "<td class=\"displayrow\" align=\"center\" width=\"5%\" valign=\"top\">";
			if($myrow["linknewsnr"]==0)
			{
				if($myrow["ratingcount"]<1)
					echo $l_none;
				else
				{
					$rating=round($myrow["ratings"]/$myrow["ratingcount"],2);
					echo $rating." (".$myrow["ratingcount"].")";
				}
			}
			else
				echo "&nbsp;";
			echo "</td>";
		}
		if($allowactions)
		{
			$tmpsql = "select * from ".$tableprefix."_comments where entryref=".$entrydata["newsnr"];
			if(!$tmpresult = mysql_query($tmpsql, $db))
			    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
			$numcomments=mysql_num_rows($tmpresult);
			echo "<td class=\"adminactions\" align=\"center\" width=\"2%\" valign=\"top\">";
			if($numcomments>0)
				echo "<a href=\"".do_url_session("comments.php?$langvar=$act_lang&entryref=".$myrow["newsnr"])."\"><img src=\"gfx/comment.gif\" border=\"0\" title=\"$numcomments $l_comments\" alt=\"$numcomments $l_comments\"></a>";
			else
				echo "&nbsp;";
			echo "</td>";
			echo "<td class=\"adminactions\" align=\"center\" height=\"16\" width=\"16\" valign=\"top\">";
			if($myrow["linknewsnr"]==0)
			{
				$tempsql="select * from ".$tableprefix."_news_attachs where newsnr=$act_id";
				if(!$tempresult=mysql_query($tempsql,$db))
				    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
				$numattachs=mysql_num_rows($tempresult);
				if($numattachs>0)
				{
					$dellink=do_url_session("$act_script_url?$langvar=$act_lang&newslang=$newslang&mode=delattach&input_newsnr=$act_id&catnr=$catnr");
					if($admdelconfirm==2)
						echo "<a class=\"listlink2\" href=\"javascript:confirmDel('$l_attachements - News #$act_id','$dellink')\">";
					else
						echo "<a class=\"listlink2\" href=\"$dellink\" valign=\"top\">";
					echo "<img height=\"16\" width=\"16\" src=\"gfx/delattach.gif\" border=\"0\" align=\"absmiddle\" alt=\"$l_delattach ($numattachs)\" title=\"$l_delattach ($numattachs)\"></a>";
				}
				else
					echo "&nbsp;";
			}
			else
				echo "&nbsp;";
			echo "</td>";
			echo "<td class=\"adminactions\" align=\"center\" height=\"16\" width=\"16\" valign=\"top\">";
			if($myrow["linknewsnr"]==0)
				echo "<a href=\"".do_url_session("$act_script_url?$langvar=$act_lang&newslang=$newslang&mode=catlink&input_newsnr=$act_id&catnr=$catnr")."\"><img height=\"16\" width=\"16\" src=\"gfx/link.gif\" border=\"0\" title=\"$l_catlink\" alt=\"$l_catlink\"></a>";
			else
				echo "&nbsp;";
			echo "</td>";
			echo "<td class=\"adminactions\" align=\"center\" height=\"16\" width=\"16\" valign=\"top\">";
			if($myrow["linknewsnr"]==0)
				echo "<a href=\"".do_url_session("$act_script_url?$langvar=$act_lang&newslang=$newslang&mode=catmove&input_newsnr=$act_id&catnr=$catnr")."\"><img height=\"16\" width=\"16\" src=\"gfx/move.gif\" border=\"0\" title=\"$l_catmove\" alt=\"$l_catmove\"></a>";
			else
				echo "&nbsp;";
			echo "</td>";
			echo "<td class=\"adminactions\" align=\"center\" height=\"16\" width=\"16\" valign=\"top\">";
			if($myrow["linknewsnr"]==0)
				echo "<a href=\"".do_url_session("$act_script_url?$langvar=$act_lang&newslang=$newslang&mode=clone&input_newsnr=$act_id&catnr=$catnr")."\"><img height=\"16\" width=\"16\" src=\"gfx/clone.gif\" border=\"0\" title=\"$l_clone\" alt=\"$l_clone\"></a>";
			else
				echo "<a href=\"".do_url_session("$act_script_url?$langvar=$act_lang&newslang=".$entrydata["lang"]."&mode=clone&input_newsnr=".$myrow["linknewsnr"]."&catnr=".$entrydata["category"])."\"><img height=\"16\" width=\"16\" src=\"gfx/clone.gif\" border=\"0\" title=\"$l_clone\" alt=\"$l_clone\"></a>";
			echo "</td>";
			echo "<td class=\"adminactions\" align=\"center\" height=\"16\" width=\"16\" valign=\"top\">";
			if($myrow["linknewsnr"]==0)
				echo "<a href=\"".do_url_session("$act_script_url?$langvar=$act_lang&newslang=$newslang&mode=edit&input_newsnr=$act_id&catnr=$catnr")."\"><img src=\"gfx/edit.gif\" border=\"0\" title=\"$l_edit\" alt=\"$l_edit\"></a>";
			else
				echo "<a href=\"".do_url_session("$act_script_url?$langvar=$act_lang&newslang=".$entrydata["lang"]."&mode=edit&input_newsnr=".$myrow["linknewsnr"]."&catnr=".$entrydata["category"])."\"><img src=\"gfx/edit.gif\" border=\"0\" title=\"$l_editoriginalentry\" alt=\"$l_editoriginalentry\"></a>";
			echo "</td>";
			echo "<td class=\"adminactions\" align=\"center\" height=\"16\" width=\"16\" valign=\"top\">";
			$dellink=do_url_session("$act_script_url?$langvar=$act_lang&newslang=$newslang&mode=del&input_newsnr=$act_id&catnr=$catnr");
			if($admdelconfirm==2)
				echo "<a class=\"listlink2\" href=\"javascript:confirmDel('News #$act_id','$dellink')\">";
			else
				echo "<a class=\"listlink2\" href=\"$dellink\" valign=\"top\">";
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
		echo "<tr class=\"actionrow\"><td colspan=\"$colspan\" align=\"left\"><input class=\"snbutton\" type=\"button\"  onclick=\"massdel()\" value=\"$l_delselected\">";
		echo "&nbsp; <input class=\"snbutton\" type=\"button\" onclick=\"massmove()\" value=\"$l_moveselected\">";
		echo "&nbsp; <input class=\"snbutton\" type=\"button\" onclick=\"checkAll(document.newslist)\" value=\"$l_checkall\">";
		echo "&nbsp; <input class=\"snbutton\" type=\"button\" onclick=\"uncheckAll(document.newslist)\" value=\"$l_uncheckall\">";
		echo "</td></tr>";
	}
	echo "</form></table></td></tr></table>";
}
if(($admepp>0) && ($numentries>$admepp))
{
	$baselink="$act_script_url?$langvar=$act_lang&newslang=$newslang&catnr=$catnr";
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
