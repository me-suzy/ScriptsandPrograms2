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
require_once('./language/lang_'.$act_lang.'.php');
if(!isset($preview))
	$preview=0;
require_once('./auth.php');
$page="sendoldnews";
$bbcbuttons=true;
$page_title=$l_emailoldnews;
require_once('./heading.php');
require_once('../includes/htmlMimeMail.inc');
require_once("./includes/bbcode_buttons.inc");
require_once("./functions.php");
require_once("../functions.php");
if($use_smtpmail)
{
	require_once('../includes/smtp.inc');
	require_once('../includes/RFC822.inc');
}
$sql = "select * from ".$tableprefix."_settings where (settingnr=1)";
if(!$result = mysql_query($sql, $db))
	die("Could not connect to the database.");
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
unset($myrow);
@mysql_free_result($result);
if($admin_rights<$sublevel)
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
	die("$l_functionnotallowed");
}
if(!isset($mode))
{
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<form name="inputform" method="post" action="<?php echo $act_script_url?>">
<?php
	if(is_konqueror())
		echo "<tr><td></td></tr>";
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="mode" value="preview">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_category?>:</td>
<td><select name="catnr">
<?php
if(bittst($secsettings,BIT_1) || ($userdata["rights"]>2))
	echo "<option value=\"0\">$l_general</option>";
if($admin_rights==2)
	$sql="select cat.* from ".$tableprefix."_categories cat, ".$tableprefix."_cat_adm ca where cat.catnr=ca.catnr and ca.usernr=".$userdata["usernr"]." group by cat.catnr";
else
	$sql="select cat.* from ".$tableprefix."_categories cat";
$sql.=" order by cat.displaypos asc";
if(!$result = mysql_query($sql, $db))
	die("<tr class=\"errorrow\"><td>Unable to conntect to database.");
while($myrow=mysql_fetch_array($result))
{
	echo "<option value=\"".$myrow["catnr"]."\">";
	echo display_encoded($myrow["catname"])."</option>";
}
?>
</select>
</td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_language?>:</td>
<td><?php echo language_select($act_lang,"filterlang","../language/")?></td></tr>
<tr class="inputrow"><td align="right" width="30%" valign="top"><?php echo $l_additionalcomment?>:</td>
<td><textarea name="addcomment" rows="6" cols="40" class="sninput"></textarea><br>
<?php display_bbcode_buttons($l_bbbuttons,"addcomment",false,false,"inputform")?>
</td></tr>
</tr>
<tr class="actionrow"><td align="center" colspan="2"><input type="submit" class="snbutton" name="submit" value="<?php echo $l_continue?>"></td></tr></form>
</table></td></tr></table>
<?php
require('./trailer.php');
exit;
}
if($mode=="preview")
{
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
	if(!isset($filterlang))
	{
?>
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		echo "<tr class=\"errorrow\"><td align=\"center\">";
		echo "$l_nolanguageselected</td></tr>";
		echo "<tr class=\"actionrow\" align=\"center\"><td>";
		echo "<a href=\"javascript:history.back()\">$l_back</a>";
		echo "</td></tr></table></td></tr></table>";
		require('./trailer.php');
		exit;
	}
?>
<form name="entrylist" method="post" action="<?php echo $act_script_url?>">
<?php
	if(is_konqueror())
		echo "<tr><td></td></tr>";
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<input type="hidden" name="catnr" value="<?php echo $catnr?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="mode" value="send">
<input type="hidden" name="addcomment" value="<?php echo do_htmlentities($addcomment)?>">
<input type="hidden" name="filterlang" value="<?php echo $filterlang?>">
<?php
	echo "<tr class=\"inforow\"><td colspan=\"5\" align=\"center\"><b>$l_category:</b> ";
	if($catnr>0)
	{
		$catsql="select * from ".$tableprefix."_categories where catnr=$catnr";
		if(!$catresult = mysql_query($catsql, $db))
			die("Unable to connect to database.".mysql_error());
		$catrow=mysql_fetch_array($catresult);
		echo display_encoded($catrow["catname"]);
	}
	else
		echo $l_general;
	@mysql_free_result($catresult);
	echo "<br><b>$l_language:</b> $filterlang";
	echo "</td></tr>";
	echo "<tr class=\"inforow\"><td colspan=\"5\" align=\"center\"><b>News</b></td></tr>";
	$found=0;
	$sql2="select * from ".$tableprefix."_data where linknewsnr=0 and lang='".$filterlang."' and dontemail=0";
	$sql2.=" and category=".$catnr;
	$sql2.=" order by date desc";
	if(!$result2 = mysql_query($sql2, $db))
		die("Unable to connect to database.".mysql_error());
	if($myrow2=mysql_fetch_array($result2))
	{
		echo "<tr class=\"rowheadings\">";
		echo "<td align=\"center\" width=\"1%\">&nbsp;</td>";
		echo "<td align=\"center\" width=\"5%\"><b>#</b></td>";
		echo "<td align=\"center\" width=\"60%\"><b>$l_news</b></td>";
		echo "<td align=\"center\" width=\"20%\"><b>$l_date</b></td>";
		echo "</tr>";
		do{
			$found++;
			echo "<tr class=\"displayrow\">";
			echo "<td align=\"center\">";
			echo "<input type=\"checkbox\" name=\"selnews[]\" value=\"".$myrow2["newsnr"]."\">";
			echo "</td>";
			echo "<td align=\"center\">";
			$showurl=do_url_session("nshow.php?$langvar=$act_lang&newsnr=".$myrow2["newsnr"]);
			echo "<a class=\"shdetailslink\" href=\"javascript:openWindow3('$showurl','nShow',20,20,400,200);\">";
			echo $myrow2["newsnr"]."</a></td>";
			$newstext=stripslashes($myrow2["text"]);
			$newstext = undo_htmlspecialchars($newstext);
			if($myrow2["heading"])
				$displaytext="<b>".$myrow2["heading"]."</b><br>".$newstext;
			else
				$displaytext=$newstext;
			echo "</td><td class=\"newsentry\" align=\"left\">";
			echo "$displaytext</td>";
			echo "<td valign=\"top\" class=\"newsdate\" align=\"center\" width=\"20%\">";
			list($mydate,$mytime)=explode(" ",$myrow2["date"]);
			list($year, $month, $day) = explode("-", $mydate);
			list($hour, $min, $sec) = explode(":",$mytime);
			$temptime=mktime($hour,$min,$sec,$month,$day,$year);
			$temptime=transposetime($temptime,$servertimezone,$displaytimezone);
			$displaydate=date($l_admdateformat,$temptime);
			echo "$displaydate</td></tr>";
		}while($myrow2=mysql_fetch_array($result2));
	}
	if($found<1)
		echo "<tr><td class=\"displayrow\" align=\"center\" colspan=\"5\">$l_noentries</td></tr>";
	if($evnewsletterinclude==1)
	{
		echo "<tr class=\"inforow\"><td colspan=\"5\" align=\"center\"><b>Events</b></td></tr>";
		$found=0;
		$sql2="select * from ".$tableprefix."_events where linkeventnr=0 and lang='".$filterlang."' and dontemail=0";
		$sql2.=" and category=".$catnr;
		$sql2.=" order by date desc";
		if(!$result2 = mysql_query($sql2, $db))
			die("Unable to connect to database.".mysql_error());
		if($myrow2=mysql_fetch_array($result2))
		{
			echo "<tr class=\"rowheadings\">";
			echo "<td align=\"center\" width=\"1%\">&nbsp;</td>";
			echo "<td align=\"center\" width=\"5%\"><b>#</b></td>";
			echo "<td align=\"center\" width=\"60%\"><b>$l_event</b></td>";
			echo "<td align=\"center\" width=\"20%\"><b>$l_date</b></td>";
			echo "</tr>";
			do{
				$found++;
				echo "<tr class=\"displayrow\">";
				echo "<td align=\"center\">";
				echo "<input type=\"checkbox\" name=\"selev[]\" value=\"".$myrow2["eventnr"]."\">";
				echo "</td>";
				echo "<td align=\"center\">";
				$showurl=do_url_session("evshow.php?$langvar=$act_lang&eventnr=".$myrow2["eventnr"]);
				echo "<a class=\"shdetailslink\" href=\"javascript:openWindow3('$showurl','nShow',20,20,400,200);\">";
				echo $myrow2["eventnr"]."</a></td>";
				$newstext=stripslashes($myrow2["text"]);
				$newstext = undo_htmlspecialchars($newstext);
				if($myrow2["heading"])
					$displaytext="<b>".$myrow2["heading"]."</b><br>".$newstext;
				else
					$displaytext=$newstext;
				echo "</td><td class=\"newsentry\" align=\"left\">";
				echo "$displaytext</td>";
				echo "<td valign=\"top\" class=\"newsdate\" align=\"center\" width=\"20%\">";
				list($curdate,$curtime)=explode(" ",$myrow2["date"]);
				list($curyear,$curmonth,$curday)=explode("-",$curdate);
				list($curhour,$curmin,$cursec)=explode(":",$curtime);
				$tmpdate=mktime($curhour,$curmin,$cursec,$curmonth,$curday,$curyear);
				if(($curhour>0) || ($curmin>0) || ($cursec>0))
					$displaydate=date($l_admdateformat,$tmpdate);
				else
					$displaydate=date($l_admdateformat2,$tmpdate);
				echo "$displaydate</td></tr>";
			}while($myrow2=mysql_fetch_array($result2));
		}
		if($found<1)
			echo "<tr><td class=\"displayrow\" align=\"center\" colspan=\"5\">$l_noentries</td></tr>";
	}
	echo "<tr class=\"actionrow\"><td colspan=\"5\" align=\"center\">";
	echo "<input type=\"submit\" value=\"$l_sendnews\" name=\"submit\" class=\"snbutton\">";
	echo "&nbsp; <input class=\"snbutton\" type=\"button\" onclick=\"checkAll(document.entrylist)\" value=\"$l_checkall\">";
	echo "&nbsp; <input class=\"snbutton\" type=\"button\" onclick=\"uncheckAll(document.entrylist)\" value=\"$l_uncheckall\">";
	echo "&nbsp; <input class=\"snbutton\" type=\"button\" value=\"$l_back\" onclick=\"self.history.back();\">";
	echo "</td></tr></form></table></td></tr></table>";
	require('./trailer.php');
	exit;
}
if($mode=="send")
{
	if(!isset($filterlang))
	{
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		echo "<tr class=\"errorrow\"><td align=\"center\">";
		echo "$l_nolanguageselected</td></tr>";
		echo "<tr class=\"actionrow\" align=\"center\"><td>";
		echo "<a href=\"javascript:history.back()\">$l_back</a>";
		echo "</td></tr></table></td></tr></table>";
		require('./trailer.php');
		exit;
	}
	if(!isset($selnews) && !isset($selev))
	{
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		echo "<tr class=\"errorrow\"><td align=\"center\">";
		echo "$l_noentryselected</td></tr>";
		echo "<tr class=\"actionrow\" align=\"center\"><td>";
		echo "<a href=\"javascript:history.back()\">$l_back</a>";
		echo "</td></tr></table></td></tr></table>";
		require('./trailer.php');
		exit;
	}
}
$actdate = date("Y-m-d H:i:s");
$sentmails=0;
$workedon=0;
if($showsendprogress==1)
{
	echo "<div id=\"progressbox\" class=\"progress\" style=\"z-index:100\">$l_sendingmail: ";
	flush();
}
if(!$insafemode)
	@set_time_limit($msendlimit);
$sentnewsentries=array();
$sentevententries=array();
$layout_sql="select * from ".$tableprefix."_layout where lang='".$filterlang."' and deflayout=1";
if(!$layout_result = mysql_query($layout_sql, $db))
	die("Unable to connect to database.".mysql_error());
if(!$layoutrow=mysql_fetch_array($layout_result))
	die("Layout setup error");
$event_dateformat=$layoutrow["event_dateformat"];
$event_dateformat2=$layoutrow["event_dateformat2"];
$dateformat=$layoutrow["dateformat"];
$timestampfontcolor=$layoutrow["timestampfontcolor"];
$timestampfontsize=$layoutrow["timestampfontsize"];
$timestampfont=$layoutrow["timestampfont"];
$timestampstyle=$layoutrow["timestampstyle"];
$timestampbgcolor=$layoutrow["timestampbgcolor"];
$globalheading=$layoutrow["heading"];
$headingbgcolor=$layoutrow["headingbgcolor"];
$headingfontcolor=$layoutrow["headingfontcolor"];
$headingfont=$layoutrow["headingfont"];
$headingfontsize=$layoutrow["headingfontsize"];
$bordercolor=$layoutrow["bordercolor"];
$contentbgcolor=$layoutrow["contentbgcolor"];
$contentfontcolor=$layoutrow["contentfontcolor"];
$contentfont=$layoutrow["contentfont"];
$contentfontsize=$layoutrow["contentfontsize"];
$TableWidth=$layoutrow["TableWidth"];
$newsheadingbgcolor=$layoutrow["newsheadingbgcolor"];
$newsheadingfontcolor=$layoutrow["newsheadingfontcolor"];
$newsheadingstyle=$layoutrow["newsheadingstyle"];
$newsheadingfont=$layoutrow["newsheadingfont"];
$newsheadingfontsize=$layoutrow["newsheadingfontsize"];
$displayposter=$layoutrow["displayposter"];
$posterbgcolor=$layoutrow["posterbgcolor"];
$posterfontcolor=$layoutrow["posterfontcolor"];
$posterfont=$layoutrow["posterfont"];
$posterfontsize=$layoutrow["posterfontsize"];
$posterstyle=$layoutrow["posterstyle"];
$copyrightbgcolor=$layoutrow["copyrightbgcolor"];
$copyrightfontcolor=$layoutrow["copyrightfontcolor"];
$copyrightfont=$layoutrow["copyrightfont"];
$copyrightfontsize=$layoutrow["copyrightfontsize"];
$attachpic=$layoutrow["attachpic"];
$categorybgcolor=$layoutrow["categorybgcolor"];
$categoryfont=$layoutrow["categoryfont"];
$categoryfontcolor=$layoutrow["categoryfontcolor"];
$categoryfontsize=$layoutrow["categoryfontsize"];
$categorystyle=$layoutrow["categorystyle"];
$newsletterbgcolor=$layoutrow["newsletterbgcolor"];
$newslettercustomheader=$layoutrow["newslettercustomheader"];
$newslettercustomfooter=$layoutrow["newslettercustomfooter"];
$newsletteralign=$layoutrow["newsletteralign"];
$emailremark=$layoutrow["emailremark"];
$defsignature=$layoutrow["defsignature"];
@mysql_free_result($layout_result);
$numnews=0;
$numevents=0;
$asc_mailmsg="";
require("./language/mail_".$filterlang.".php");
$html_mailmsg="<html>";
switch($newsletteralign)
{
	case 0:
		$tblalign="left";
		break;
	case 1:
		$tblalign="right";
		break;
	default:
		$tblalign="center";
		break;
}
if($newsletteralign<2)
{
	$html_mailmsg.="<head>\n";
	$html_mailmsg.="<style>\n";
	$html_mailmsg.="table.sntable {\n";
	$html_mailmsg.="  float: $tblalign;\n";
	$html_mailmsg.="}\n";
	$html_mailmsg.="</style>\n";
	$html_mailmsg.="</head>\n";
}
$html_mailmsg.="<body bgcolor=\"$newsletterbgcolor\">";
if($newslettercustomheader)
{
	$html_mailmsg.="<div align=\"$tblalign\">";
	if($newsletterattachinlinepix==1)
		$newslettercustomheader=recode_img_for_emails($newslettercustomheader);
	$html_mailmsg.=$newslettercustomheader;
	$html_mailmsg.="</div>";
}
$html_mailmsg.="<div align=\"$tblalign\"><table width=\"$TableWidth\" border=\"0\" CELLPADDING=\"1\" CELLSPACING=\"0\" ALIGN=\"$tblalign\" VALIGN=\"TOP\" class=\"sntable\">".$crlf;
$html_mailmsg.="<tr><TD BGCOLOR=\"$bordercolor\">".$crlf;
$html_mailmsg.="<TABLE BORDER=\"0\" CELLPADDING=\"1\" CELLSPACING=\"1\" WIDTH=\"100%\">".$crlf;
if(strlen($globalheading)>0)
{
	$html_mailmsg.="<TR BGCOLOR=\"$headingbgcolor\" ALIGN=\"CENTER\">".$crlf;
	$html_mailmsg.="<TD ALIGN=\"CENTER\" VALIGN=\"MIDDLE\"";
	if($newsletternoicons==0)
		$html_mailmsg.=" colspan=\"2\"";
	$html_mailmsg.="><font face=\"$headingfont\" size=\"$headingfontsize\" color=\"$headingfontcolor\"><b>$globalheading</b></font></td></tr>".$crlf;
}
if($addcomment)
{
	$addcomment=stripslashes($addcomment);
	$addcomment=bbencode($addcomment);
	$asc_comment=strip_tags($addcomment);
	$asc_mailmsg.=$asc_comment.$crlf.$crlf;
	$addcomment=str_replace("\n","<BR>",$addcomment);
	$html_mailmsg.="<TR BGCOLOR=\"$contentbgcolor\" ALIGN=\"CENTER\">".$crlf;
	$html_mailmsg.="<TD ALIGN=\"LEFT\" VALIGN=\"MIDDLE\"";
	if($newsletternoicons==0)
		$html_mailmsg.=" colspan=\"2\"";
	$html_mailmsg.="><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
	if($newsletterattachinlinepix==1)
		$addcomment=recode_img_for_emails($addcomment);
	$html_mailmsg.="$addcomment</font></td></tr>".$crlf;
}
if(isset($selnews))
	require("./includes/mail_news2.inc");
if(($evnewsletterinclude==1) && isset($selev))
	require("./includes/mail_events2.inc");
$html_mailmsg.="<TR BGCOLOR=\"$copyrightbgcolor\" ALIGN=\"CENTER\">".$crlf;
$html_mailmsg.="<TD ALIGN=\"CENTER\" VALIGN=\"MIDDLE\"";
if($newsletternoicons==0)
	$html_mailmsg.=" colspan=\"2\"";
$html_mailmsg.="><font face=\"$copyrightfont\" size=\"$copyrightfontsize\" color=\"$copyrightfontcolor\">".$crlf;
$html_mailmsg.="Powered by SimpNews $version</td></tr>";
$html_mailmsg.="</table></td></tr></table></div><br clear=\"all\">".$crlf;
if($newslettercustomfooter)
{
	$html_mailmsg.="<div align=\"$tblalign\">";
	if($newsletterattachinlinepix==1)
		$newslettercustomfooter=recode_img_for_emails($newslettercustomfooter);
	$html_mailmsg.=$newslettercustomfooter;
	$html_mailmsg.="</div>";
}
if($newsletternoicons==0)
	$html_mailmsg=recode_emoticons_for_emails($html_mailmsg);
if(($numnews>0) || ($numevents>0))
{
	if($showsendprogress==1)
	{
		if(($workedon%100)==0)
			echo "<br>";
		echo "&bull;";
		flush();
	}
	$sql="select * from ".$tableprefix."_subscriptions where confirmed=1 and language='".$filterlang."'";
	$sql.=" and category=0";
	if($catnr>0)
		$sql.=" or category=$catnr";
	$sql.=" group by email";
	if(!$result = mysql_query($sql, $db))
		die("Unable to connect to database.".mysql_error());
	while($myrow=mysql_fetch_array($result))
	{
		$mail = new htmlMimeMail();
		$unsubscribeurl=$simpnews_fullurl."subscription.php?$langvar=".$myrow["language"]."&id=".$myrow["unsubscribeid"]."&mode=remove&email=".$myrow["email"];
		$unsubscribeurl_html="<font face=\"Verdana, Geneva, Arial, Helvetica, sans-serif\" size=\"1\"><a href=\"$unsubscribeurl\">$unsubscribeurl</a></font>";
		if($emailremark)
			$html_remark=str_replace("{unsubscribeurl}",$unsubscribeurl_html,$emailremark);
		else
			$html_remark="unsubscribe: $unsubscribeurl_html";
		$html_remark=str_replace("\n","<br>".$crlf,$html_remark);
		if($emailremark)
			$asc_remark=str_replace("{unsubscribeurl}",$unsubscribeurl,strip_tags($emailremark));
		else
			$asc_remark="unsubscribe: $unsubscribeurl";
		$asc_remark=str_replace("\n",$crlf,$asc_remark);
		$userhtmlbody=$html_mailmsg;
		if(strlen($html_remark)>0)
			$userhtmlbody.="<hr>$html_remark<br><br>".$crlf;
		$userhtmlbody.="---<BR>".$crlf.str_replace("\n","<BR>".$crlf,$defsignature);
		$userhtmlbody.="</body></html>";
		$userascbody=$asc_mailmsg;
		if(strlen($asc_remark)>0)
			$userascbody.="---------------------------".$crlf.$asc_remark.$crlf.$crlf;
		$userascbody.="---".$crlf.str_replace("\n",$crlf,$defsignature);
		$mail->setCrlf($crlf);
		$mail->setTextWrap($mailmaxlinelength);
		if($myrow["emailtype"]==0)
		{
			$mail->setHTMLCharset($contentcharset);
			$mail->setTextCharset($contentcharset);
			if(($newsletternoicons==0) || ($newsletterattachinlinepix==1))
				$mail->setHTML($userhtmlbody,$userascbody,$path_gfx."/");
			else
				$mail->setHTML($userhtmlbody,$userascbody);
		}
		else
		{
			$mail->setTextCharset($contentcharset);
			$mail->setText($userascbody);
		}
		if($simpnewsmailname)
			$fromadr="\"$simpnewsmailname\" <$simpnewsmail>";
		else
			$fromadr=$simpnewsmail;
		$mail->setSubject($subject);
		$mail->setFrom($fromadr);
		$receiver=array();
		array_push($receiver,$myrow["email"]);
		if($use_smtpmail)
		{
			$mail->setSMTPParams($smtpserver,$smtpport,NULL,$smtpauth,$smtpuser,$smtppasswd);
			$sendresult=$mail->send($receiver, "smtp");
		}
		else
			$sendresult=$mail->send($receiver, "mail");
    		$addlogtxt="sendnews.php - subscription#: ".$myrow["subscriptionnr"]." - admin: ".$userdata["username"];
    		if(count($sentnewsentries)>0)
    		{
			$addlogtxt.=" - news# ";
			for($i=0;$i<count($sentnewsentries);$i++)
			{
				if($i>0)
					$addlogtxt.=",";
				$addlogtxt.=$sentnewsentries[$i];
			}
    		}
    		if(count($sentevententries)>0)
    		{
			$addlogtxt.=" - events# ";
			for($i=0;$i<count($sentevententries);$i++)
			{
				if($i>0)
					$addlogtxt.=",";
				$addlogtxt.=$sentevententries[$i];
			}
		}
		$addlogtxt.=" - cat# $catnr";
		do_emaillog($sendresult,$myrow["email"],$addlogtxt);
		if(!$sendresult)
		{
			echo "<br>Unable to send email for ".$myrow["email"]." (#".$myrow["subscriptionnr"].")";
			if($use_smtpmail)
			{
				echo "<br>";
				print_array($mail->errors);
			}
			if($emailerrordie==1)
				die();
			else
				echo "<br>";
		}
		$sentmails++;
		if(($sendnewsdelay>0) && (($sentmails%$senddelayinterval)==0))
		{
			if(!$insafemode)
			{
				if($msendlimit>($sendnewsdelay/1000+2))
					@set_time_limit($msendlimit);
				else
					@set_time_limit($sendnewsdelay/1000+2);
			}
			usleep($sendnewsdelay);
		}
	}
	if($showsendprogress==1)
	{
		if(($workedon%100)==0)
			echo "<br>";
		echo "&loz;";
		flush();
	}
	$workedon++;
}
if($showsendprogress==1)
{
	echo "<br>";
	echo "<a href=\"javascript:hideprogressbox()\"";
	echo " class=\"actionlink\">$l_hideprogressbox</a>";
	echo "</div>\n";
	echo "<script type=\"text/javascript\" language=\"javascript\">\n";
	echo "<!--\n";
	echo "	hideprogressbox();\n";
	echo "// -->\n";
	echo "</script>\n";
	flush();
}
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
echo "<tr class=\"inforow\"><td align=\"center\"><b>$l_category:</b> ";
if($catnr>0)
{
	$catsql="select * from ".$tableprefix."_categories where catnr=$catnr";
	if(!$catresult = mysql_query($catsql, $db))
		die("Unable to connect to database.".mysql_error());
	$catrow=mysql_fetch_array($catresult);
		echo display_encoded($catrow["catname"]);
}
else
	echo $l_general;
@mysql_free_result($catresult);
echo "<br><b>$l_language:</b> $filterlang";
echo "</td></tr>";
echo "<tr class=\"inforow\"><td align=\"center\"><b>$l_numselectednews:</b> ".$numnews;
echo "<br><b>$l_numselectedevents:</b> ".$numevents;
echo "</td></tr>";
echo "<tr class=\"displayrow\"><td align=\"center\">";
echo "$l_emailssent ($sentmails/$workedon)</td></tr>";
if($showsendprogress==1)
{
	echo "<tr class=\"displayrow\"><td align=\"center\">";
	echo "<a href=\"javascript:showprogressbox()\"";
	echo " class=\"actionlink\">$l_reshowprogressbox</a>";
	echo "</td></tr>";
}
echo "</table></td></tr></table>";
require('./trailer.php');
?>