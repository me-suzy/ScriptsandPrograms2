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
require_once('./auth.php');
$bbcbuttons=true;
$page_title=$l_emailnews;
require_once('./heading.php');
include_once('../includes/htmlMimeMail.inc');
include_once("./includes/bbcode_buttons.inc");
if($use_smtpmail)
{
	include_once('../includes/smtp.inc');
	include_once('../includes/RFC822.inc');
}
if(!isset($input_subscriptionnr))
	die("calling error");
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
	$servertimezone=$myrow["servertimezone"];
	$displaytimezone=$myrow["displaytimezone"];
}
else
{
	$subscriptionsendmode=0;
	$enablesubscriptions=0;
	$subject="News";
	$simpnewsmail="simpnews@foo.bar";
	$simpnewsmailname="SimpNews";
}
if($admin_rights<2)
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
	die("$l_functionnotallowed");
}
if(!isset($filtercat))
	$filtercat=-1;
$actdate = date("Y-m-d H:i:s");
$sql="select * from ".$tableprefix."_subscriptions where confirmed=1 and subscriptionnr=$input_subscriptionnr";
if(!$result = mysql_query($sql, $db))
    die("Unable to connect to database.".mysql_error());
if(!$myrow=mysql_fetch_array($result))
    die("No such subscriber");
$newscat=$myrow["category"];
if(!isset($mode))
{
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
	echo "<form name=\"newslist\" method=\"post\" action=\"$act_script_url\">";
	if(is_konqueror())
		echo "<tr><td></td></tr>";
	echo "<input type=\"hidden\" name=\"$langvar\" value=\"$act_lang\">";
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
	echo "<input type=\"hidden\" name=\"mode\" value=\"send\">";
	echo "<input type=\"hidden\" name=\"input_subscriptionnr\" value=\"$input_subscriptionnr\">";
	echo "<tr class=\"rowheadings\"><td width=\"2%\"></td>";
	echo "<td align=\"center\" width=\"5%\"><b>#</b></td>";
	echo "<td align=\"center\" width=\"40%\"><b>$l_news</b></td>";
	echo "<td align=\"center\" width=\"20%\"><b>$l_date</b></td>";
	echo "<td align=\"center\" width=\"20%\"><b>$l_category</b></td>";
	echo "</tr>";
	$newssql="select * from ".$tableprefix."_data where linknewsnr=0 and lang='".$myrow["language"]."'";
	if($newscat>0)
		$newssql.=" and category=$newscat";
	else
	{
		if($admin_rights<3)
		{
			if(bittst($secsettings,BIT_10))
				$newssql.=" and category=0 ";
			$tmpsql="select cat.* from ".$tableprefix."_categories cat, ".$tableprefix."_cat_adm ca where cat.catnr=ca.catnr and cat.excludefromnewsletter=0 and ca.usernr=".$userdata["usernr"];
			if(!$tmpresult = mysql_query($tmpsql, $db))
			    die("Unable to connect to database.".mysql_error());
			while($tmprow=mysql_fetch_array($tmpresult))
				$newssql.=" or category=".$tmprow["catnr"];
		}
		else
		{
			$tmpsql="select * from ".$tableprefix."_categories where excludefromnewsletter=1";
			if(!$tmpresult = mysql_query($tmpsql, $db))
			    die("Unable to connect to database.".mysql_error());
			while($tmprow=mysql_fetch_array($tmpresult))
				$newssql.=" and category!=".$tmprow["catnr"];
		}
	}
	$newssql.=" order by category asc, date desc";
	if(!$newsresult = mysql_query($newssql, $db))
	    die("Unable to connect to database.".mysql_error());
	$numnews=mysql_num_rows($newsresult);
	$numevents=0;
	while($newsrow=mysql_fetch_array($newsresult))
	{
		if($newsrow["category"]>0)
		{
			$catsql="select * from ".$tableprefix."_categories where catnr=".$newsrow["category"];
			if(!$catresult = mysql_query($catsql, $db))
			    die("Unable to connect to database.".mysql_error());
			if(!$catrow = mysql_fetch_array($catresult))
			    die("Unable to connect to database.");
			$catname=do_htmlentities(stripslashes($catrow["catname"]));
		}
		else
			$catname=$l_general;
		echo "<tr><td valign=\"top\" class=\"displayrow\" align=\"center\"><input type=\"checkbox\" name=\"newsnr[]\" value=\"".$newsrow["newsnr"]."\"></td>";
		echo "<td class=\"displayrow\" align=\"center\" valign=\"top\">";
		$showurl=do_url_session("nshow.php?$langvar=$act_lang&newsnr=".$newsrow["newsnr"]);
		echo "<a class=\"shdetailslink\" href=\"javascript:openWindow3('$showurl','nShow',20,20,400,200);\">";
		echo $newsrow["newsnr"]."</a></td>";
		$newstext=stripslashes($newsrow["text"]);
		$newstext = undo_htmlspecialchars($newstext);
		if($newsrow["heading"])
			$displaytext="<b>".$newsrow["heading"]."</b><br>".$newstext;
		else
			$displaytext=$newstext;
		echo "</td><td class=\"newsentry\" align=\"left\">";
		echo "$displaytext</td>";
		echo "<td valign=\"top\" class=\"newsdate\" align=\"center\" width=\"20%\">";
		list($mydate,$mytime)=explode(" ",$newsrow["date"]);
		list($year, $month, $day) = explode("-", $mydate);
		list($hour, $min, $sec) = explode(":",$mytime);
		$temptime=mktime($hour,$min,$sec,$month,$day,$year);
		$temptime=transposetime($temptime,$servertimezone,$displaytimezone);
		$displaydate=date($l_admdateformat,$temptime);
		echo "$displaydate</td>";
		echo "<td class=\"displayrow\" align=\"center\" valign=\"top\">";
		echo $catname;
		echo "</td></tr>";
	}
	if($numnews<1)
		echo "<tr><td class=\"displayrow\" align=\"center\" colspan=\"5\">$l_noentries</td></tr>";
	if($evnewsletterinclude==1)
	{
		echo "<tr class=\"rowheadings\"><td width=\"2%\"></td>";
		echo "<td align=\"center\" width=\"5%\"><b>#</b></td>";
		echo "<td align=\"center\" width=\"40%\"><b>$l_events</b></td>";
		echo "<td align=\"center\" width=\"20%\"><b>$l_date</b></td>";
		echo "<td align=\"center\" width=\"20%\"><b>$l_category</b></td>";
		echo "</tr>";
		$newssql="select * from ".$tableprefix."_events where linkeventnr=0 and lang='".$myrow["language"]."'";
		if($newscat>0)
			$newssql.=" and category=$newscat";
		else if($admin_rights<3)
		{
			if(bittst($secsettings,BIT_10))
				$newssql.=" and category=0 ";
			$tmpsql="select cat.* from ".$tableprefix."_categories cat, ".$tableprefix."_cat_adm ca where cat.catnr=ca.catnr and cat.excludefromnewsletter=0 and ca.usernr=".$userdata["usernr"];
			if(!$tmpresult = mysql_query($tmpsql, $db))
			    die("Unable to connect to database.".mysql_error());
			while($tmprow=mysql_fetch_array($tmpresult))
				$newssql.=" or category=".$tmprow["catnr"];
		}
		else
		{
			$tmpsql="select * from ".$tableprefix."_categories where excludefromnewsletter=1";
			if(!$tmpresult = mysql_query($tmpsql, $db))
			    die("Unable to connect to database.".mysql_error());
			while($tmprow=mysql_fetch_array($tmpresult))
				$newssql.=" and category!=".$tmprow["catnr"];
		}
		$newssql.=" order by category asc, date desc";
		if(!$newsresult = mysql_query($newssql, $db))
			die("Unable to connect to database.".mysql_error());
		$numevents=mysql_num_rows($newsresult);
		while($newsrow=mysql_fetch_array($newsresult))
		{
			if($newsrow["category"]>0)
			{
				$catsql="select * from ".$tableprefix."_categories where catnr=".$newsrow["category"];
				if(!$catresult = mysql_query($catsql, $db))
					die("Unable to connect to database.".mysql_error());
				if(!$catrow = mysql_fetch_array($catresult))
					die("Unable to connect to database.");
				$catname=do_htmlentities(stripslashes($catrow["catname"]));
			}
			else
				$catname=$l_general;
			echo "<tr><td valign=\"top\" class=\"displayrow\" align=\"center\"><input type=\"checkbox\" name=\"eventnr[]\" value=\"".$newsrow["eventnr"]."\"></td>";
			echo "<td class=\"displayrow\" align=\"center\" valign=\"top\">".$newsrow["eventnr"]."</td>";
			$newstext=stripslashes($newsrow["text"]);
			$newstext = undo_htmlspecialchars($newstext);
			if($newsrow["heading"])
				$displaytext="<b>".$newsrow["heading"]."</b><br>".$newstext;
			else
				$displaytext=$newstext;
			echo "</td><td class=\"newsentry\" align=\"left\">";
			echo "$displaytext</td>";
			echo "<td valign=\"top\" class=\"newsdate\" align=\"center\" width=\"20%\">";
			list($curdate,$curtime)=explode(" ",$newsrow["date"]);
			list($curyear,$curmonth,$curday)=explode("-",$curdate);
			list($curhour,$curmin,$cursec)=explode(":",$curtime);
			$tmpdate=mktime($curhour,$curmin,$cursec,$curmonth,$curday,$curyear);
			if(($curhour>0) || ($curmin>0) || ($cursec>0))
				$displaydate=date($l_admdateformat,$tmpdate);
			else
				$displaydate=date($l_admdateformat2,$tmpdate);
			echo "$displaydate</td>";
			echo "<td class=\"displayrow\" align=\"center\" valign=\"top\">";
			echo $catname;
			echo "</td></tr>";
		}
	}
	if($numevents<1)
		echo "<tr><td class=\"displayrow\" align=\"center\" colspan=\"5\">$l_noentries</td></tr>";
	if(($numnews>0) || ($numevents>0))
	{
		echo "<tr class=\"inputrow\"><td colspan=\"9\"><table width=\"100%\">";
		echo "<tr class=\"inputrow\"><td align=\"right\"width=\"30%\" valign=\"top\">";
		echo "$l_additionalcomment:</td>";
		echo "<td><textarea name=\"addcomment\" rows=\"6\" cols=\"40\" class=\"sninput\"></textarea><br>";
		display_bbcode_buttons($l_bbbuttons,"addcomment",false,false,"newslist");
		echo "</td></tr></table></td></tr>";
		echo "<tr class=\"actionrow\"><td colspan=\"9\" align=\"left\"><input class=\"snbutton\" type=\"submit\" value=\"$l_sendselected\">";
		echo "&nbsp; <input class=\"snbutton\" type=\"button\" onclick=\"checkAll(document.newslist)\" value=\"$l_checkall\">";
		echo "&nbsp; <input class=\"snbutton\" type=\"button\" onclick=\"uncheckAll(document.newslist)\" value=\"$l_uncheckall\">";
		echo "</td></tr>";
	}
	echo "</form>";
	echo"</table></td></tr></table>";
	if($newscat==0)
	{
		echo "<table class=\"filterbox\" align=\"center\" width=\"80%\" cellspacing=\"0\" cellpadding=\"1\" valign=\"top\">";
		echo "<form method=\"post\" action=\"$act_script_url\">";
		echo "<tr><td align=\"center\" valign=\"middle\">";
		echo "<input type=\"hidden\" name=\"$langvar\" value=\"$act_lang\">";
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
		echo "<input type=\"hidden\" name=\"input_subscriptionnr\" value=\"$input_subscriptionnr\">";
		echo "<b>$l_filtercat:</b> ";
		echo "<select name=\"filtercat\">";
		echo "<option value=\"-1\"";
		if($filtercat==-1)
			echo " selected";
		echo ">$l_nofilter</option>";
		echo "<option value=\"0\"";
		if($filtercat==0)
			echo " selected";
		echo ">$l_general</option>";
		$catsql="select * from ".$tableprefix."_categories";
		if(!$catresult = mysql_query($catsql, $db))
		    die("Unable to connect to database.".mysql_error());
		while($catrow=mysql_fetch_array($catresult))
		{
			echo "<option value=\"".$catrow["catnr"]."\"";
			if($filtercat==$catrow["catnr"])
				echo " selected";
			echo ">".stripslashes($catrow["catname"])."</option>";
		}
		echo "</select>&nbsp; ";
		echo "<input class=\"snbutton\" type=\"submit\" value=\"$l_ok\">";
		echo "</td></tr></form></table>";
	}
}
else
{
	if(!isset($newsnr) && !isset($eventnr))
	{
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		echo "<tr class=\"errorrow\"><td align=\"center\">";
		echo $l_noentriesselected;
		echo "</td></tr>";
		echo "<tr class=\"actionrow\" align=\"center\"><td>";
		echo "<a href=\"javascript:history.back()\">$l_back</a>";
		echo "</td></tr>";
		echo"</table></td></tr></table>";
		include('./trailer.php');
		exit;
	}
	include("./language/mail_".$myrow["language"].".php");
	$layout_sql="select * from ".$tableprefix."_layout where lang='".$myrow["language"]."' and deflayout=1";
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
	$newsletterbgcolor=$layoutrow["newsletterbgcolor"];
	$newslettercustomheader=$layoutrow["newslettercustomheader"];
	$newslettercustomfooter=$layoutrow["newslettercustomfooter"];
	$newsletteralign=$layoutrow["newsletteralign"];
	$numnews=0;
	$asc_mailmsg="";
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
		echo "<head>\n";
		echo "<style>\n";
		echo "table.sntable {\n";
		echo "  float: $tblalign;\n";
		echo "}\n";
		echo "</style>\n";
		echo "</head>\n";
	}
	$html_mailmsg="<body bgcolor=\"$newsletterbgcolor\">";
	if($newslettercustomheader)
	{
		$html_mailmsg.="<div align=\"$tblalign\">";
		if($newsletterattachinlinepix==1)
			$newslettercustomheader=recode_img_for_emails($newslettercustomheader);
		$html_mailmsg.=$newslettercustomheader;
		$html_mailmsg.="</div>";
	}
	$html_mailmsg.="<div align=\"$tblalign\"><table width=\"$TableWidth\" border=\"0\" CELLPADDING=\"1\" CELLSPACING=\"0\" ALIGN=\"$tblalign\" VALIGN=\"TOP\" class=\"sntable\">".$crlf;
	$html_mailmsg.="<tr><TD BGCOLOR=\"$bordercolor\">";
	$html_mailmsg.="<TABLE BORDER=\"0\" CELLPADDING=\"1\" CELLSPACING=\"1\" WIDTH=\"100%\">";
	if(strlen($globalheading)>0)
	{
		$html_mailmsg.="<TR BGCOLOR=\"$headingbgcolor\" ALIGN=\"CENTER\">";
		$html_mailmsg.="<TD ALIGN=\"CENTER\" VALIGN=\"MIDDLE\"";
		if($newsletternoicons==0)
			$html_mailmsg.= " colspan=\"2\"";
		$html_mailmsg.="><font face=\"$headingfont\" size=\"$headingfontsize\" color=\"$headingfontcolor\"><b>$globalheading</b></font></td></tr>";
	}
	if($addcomment)
	{
		$addcomment=bbencode($addcomment);
		$asc_comment=strip_tags($addcomment);
		$asc_mailmsg.=$asc_comment.$crlf.$crlf;
		$addcomment=str_replace("\n","<BR>",$addcomment);
		$html_mailmsg.="<TR BGCOLOR=\"$contentbgcolor\" ALIGN=\"CENTER\">".$crlf;
		$html_mailmsg.="<TD ALIGN=\"LEFT\" VALIGN=\"MIDDLE\"";
		if($newsletternoicons==0)
			$html_mailmsg.=" colspan=\"2\"";
		if($newsletterattachinlinepix==1)
			$addcomment=recode_img_for_emails($addcomment);
		$html_mailmsg.="><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">$addcomment</font></td></tr>".$crlf;
	}
	if(isset($newsnr))
	{
		$mail = new htmlMimeMail();
		$sql2="select * from ".$tableprefix."_data where linknewsnr=0";
		$firstarg=true;
		while(list($null, $newsentry) = each($_POST["newsnr"]))
		{
			if($firstarg)
			{
				$firstarg=false;
				$sql2.= " and (newsnr=$newsentry";
			}
			else
				$sql2.= " or newsnr=$newsentry";
		}
		$sql2.=") order by date desc";
		if(!$result2 = mysql_query($sql2, $db))
			die("Unable to connect to database.".mysql_error());
		while($myrow2=mysql_fetch_array($result2))
		{
			$numnews++;
			list($mydate,$mytime)=explode(" ",$myrow2["date"]);
			list($year, $month, $day) = explode("-", $mydate);
			list($hour, $min, $sec) = explode(":",$mytime);
			$temptime=mktime($hour,$min,$sec,$month,$day,$year);
			$temptime=transposetime($temptime,$servertimezone,$displaytimezone);
			$displaydate=date($dateformat,$temptime);
			$asc_mailmsg.="$displaydate:".$crlf;
			if($newsletternoicons==0)
			{
				$html_mailmsg.="<tr>".$crlf;
				$html_mailmsg.="<td width=\"2%\" height=\"100%\" align=\"center\" bgcolor=\"$contentbgcolor\">".$crlf;
				if($myrow2["headingicon"])
					$html_mailmsg.=str_replace($url_gfx."/","","<img src=\"$url_icons/".$myrow2["headingicon"]."\" border=\"0\" align=\"middle\"> ".$crlf);
				else
					$html_mailmsg.="&nbsp;";
				$html_mailmsg.="</td>".$crlf;
				$html_mailmsg.="<td  align=\"center\"><table width=\"100%\" align=\"center\" bgcolor=\"$contentbgcolor\" cellspacing=\"0\" cellpadding=\"0\">".$crlf;
			}
			$html_mailmsg.="<tr bgcolor=\"$timestampbgcolor\"><td align=\"left\">".$crlf;
			$html_mailmsg.="<font face=\"$timestampfont\" size=\"$timestampfontsize\" color=\"$timestampfontcolor\">".$crlf;
			$html_mailmsg.=get_start_tag($timestampstyle);
			$html_mailmsg.=$displaydate;
			$html_mailmsg.=get_end_tag($timestampstyle);
			$html_mailmsg.="</font></td></tr>".$crlf;
			$entrylink=$simpnews_fullurl."singlenews.php?$langvar=".$myrow2["lang"]."&category=".$myrow2["category"]."&newsnr=".$myrow2["newsnr"];
			if(strlen($myrow2["heading"])>0)
			{
				$asc_heading=strip_tags($myrow2["heading"]);
				$asc_mailmsg.=$asc_heading;
				$asc_mailmsg.=$crlf;
				for($i=0;$i<strlen($asc_heading);$i++)
					$asc_mailmsg.="-";
				$asc_mailmsg.=$crlf;
				$html_mailmsg.="<tr bgcolor=\"$newsheadingbgcolor\"><td align=\"left\">".$crlf;
				$html_mailmsg.="<font face=\"$newsheadingfont\" size=\"$newsheadingfontsize\" color=\"$newsheadingfontcolor\">".$crlf;
				if($newsletterlinking==1)
					$html_mailmsg.="<a href=\"".$entrylink."\">";
				$html_mailmsg.=get_start_tag($newsheadingstyle);
				$html_mailmsg.=do_htmlentities(stripslashes($myrow2["heading"]));
				$html_mailmsg.=get_end_tag($newsheadingstyle);
				if($newsletterlinking==1)
					$html_mailmsg.="</a>";
				$html_mailmsg.="</font></td></tr>".$crlf;
			}
			$html_mailmsg.="<tr bgcolor=\"$contentbgcolor\"><td align=\"left\">".$crlf;
			$html_mailmsg.="<font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">".$crlf;
			$html_news=stripslashes($myrow2["text"]);
			$html_news=undo_htmlspecialchars($html_news);
			if($newsletterlinking==2)
				$html_news.="<br><a href=\"".$entrylink."\">".$l_mail_readmore."</a>";
			if($newsletterattachinlinepix==1)
				$html_news=recode_img_for_emails($html_news);
			$asc_newstext=str_replace("<BR>",$crlf,$html_news);
			$html_news=str_replace("<BR>","<BR>".$crlf,$html_news);
			$html_mailmsg.=$html_news."</font></td></tr>".$crlf;
			$asc_newstext=undo_htmlentities($asc_newstext);
			$asc_newstext=strip_tags($asc_newstext);
			if($newsletterlinking!=0)
				$asc_newstext.=$crlf."[".$entrylink."]";
			$asc_mailmsg.=$asc_newstext.$crlf;
			if($displayposter && (strlen($myrow2["poster"])>0))
			{
				$html_mailmsg.="<tr bgcolor=\"$posterbgcolor\"><td align=\"left\">".$crlf;
				$html_mailmsg.="<font face=\"$posterfont\" size=\"$posterfontsize\" color=\"$posterfontcolor\">".$crlf;
				$html_mailmsg.=get_start_tag($posterstyle);
				$html_mailmsg.="$l_poster: ".do_htmlentities($myrow2["poster"]);
				$html_mailmsg.=get_end_tag($posterstyle);
				$html_mailmsg.="</font></td></tr>".$crlf;
			}
			if($mailattach==0)
			{
				$attachsql="select f.filename, f.filesize, f.mimetype, na.* from ".$tableprefix."_news_attachs na, ".$tableprefix."_files f where f.entrynr=na.attachnr and na.newsnr=".$myrow2["newsnr"];
				if(!$attachresult = mysql_query($attachsql, $db))
					die("Could not connect to the database.");
				if($attachrow=mysql_fetch_array($attachresult))
				{
					do{
						$html_mailmsg.="<tr bgcolor=\"$contentbgcolor\">".$crlf;
						$html_mailmsg.="<td align=\"left\">".$crlf;
						$html_mailmsg.="<font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">".$crlf;
						$fileinfo=$attachrow["filename"]." (".format_bytes($attachrow["filesize"]).")";
						$mimesql="select * from ".$tableprefix."_mimetypes where mimetype='".$attachrow["mimetype"]."'";
						$imgtxt="";
						if(!$mimeresult = mysql_query($mimesql, $db))
							die("Could not connect to the database.");
						if($mimerow=mysql_fetch_array($mimeresult))
						{
							$fdsql="select * from ".$tableprefix."_filetypedescription where language='$act_lang' and mimetype=".$mimerow["entrynr"];
							if(!$fdresult = mysql_query($fdsql, $db))
								die("Could not connect to the database.");
							if($fdrow=mysql_fetch_array($fdresult))
								$dfileinfo=$fdrow["description"].": ".$fileinfo;
							else
								$dfileinfo=$fileinfo;
							if($mimerow["icon"])
								$imgtxt="<img class=\"attach\" src=\"".$mimerow["icon"]."\" border=\"0\" align=\"absmiddle\" title=\"$dfileinfo\" alt=\"$dfileinfo\"> ";
							else
								$imgtxt="<img src=\"$attachpic\" border=\"0\" align=\"absmiddle\" title=\"$dfileinfo\" alt=\"$dfileinfo\">";
						}
						else
							$imgtxt="<img src=\"$attachpic\" border=\"0\" align=\"absmiddle\" title=\"$fileinfo\" alt=\"$fileinfo\">";
						if($newsletternoicons==0)
						{
							$html_mailmsg.="&nbsp;<a href=\"".$simpnews_fullurl."sndownload.php?entrynr=".$attachrow["attachnr"]."\" target=\"_blank\">";
							$html_mailmsg.=str_replace($url_gfx."/","",$imgtxt);
							$html_mailmsg.="</a>&nbsp; ";
						}
						else
							$html_mailmsg.="Attachement:&nbsp;";
						$html_mailmsg.="<a href=\"".$simpnews_fullurl."sndownload.php?entrynr=".$attachrow["attachnr"]."\" target=\"_blank\">Download</a></font></td></tr>".$crlf;
						$html_mailmsg.="</td></tr>";
						$asc_mailmsg.= "Download attachement: ".$simpnews_fullurl."sndownload.php?entrynr=".$myrow2["newsnr"].$crlf;
					}while($attachrow=mysql_fetch_array($attachresult));
				}
			}
			else
			{
				$tmpsql="select files.* from ".$tableprefix."_news_attachs na, ".$tableprefix."_files files where files.entrynr=na.attachnr and na.newsnr=".$myrow2["newsnr"];
				if(!$tmpresult = mysql_query($tmpsql, $db))
					die("Unable to connect to database.".mysql_error());
				while($tmprow=mysql_fetch_array($tmpresult))
				{
					if(!$attach_in_fs)
						$file_data=$tmprow["bindata"];
					else
						$file_data=get_file($path_attach."/".$tmprow["fs_filename"]);
					$mail->addAttachment($file_data, $tmprow["filename"], $tmprow["mimetype"]);
				}
			}
			$asc_mailmsg.=$crlf;
			if($newsletternoicons==0)
				$html_mailmsg.="</table></td></tr>".$crlf;
		}
	}
	if(isset($eventnr) && ($evnewsletterinclude==1))
	{
		$sql2="select * from ".$tableprefix."_events where linkeventnr=0";
		$firstarg=true;
		while(list($null, $evententry) = each($_POST["eventnr"]))
		{
			if($firstarg)
			{
				$firstarg=false;
				$sql2.= " and(eventnr=$evententry";
			}
			else
				$sql2.= " or eventnr=$evententry";
		}
		$sql2.=") order by date desc";
		if(!$result2 = mysql_query($sql2, $db))
			die("Unable to connect to database.".mysql_error());
		while($myrow2=mysql_fetch_array($result2))
		{
			$numnews++;
			list($tmpdate, $tmptime)=explode(" ",$myrow2["date"]);
			list($year, $month, $day) = explode("-", $tmpdate);
			list($hour, $min, $sec) = explode(":", $tmptime);
			$temptime=mktime($hour,$min,$sec,$month,$day,$year);
			$link_date=date("Y-m-d",$temptime);
			if(($hour>0) || ($min>0))
				$displaydate=date($event_dateformat2,mktime($hour,$min,0,$month,$day,$year));
			else
				$displaydate=date($event_dateformat,mktime(0,0,0,$month,$day,$year));
			$asc_mailmsg.="$displaydate:".$crlf;
			if($newsletternoicons==0)
			{
				$html_mailmsg.="<tr>".$crlf;
				$html_mailmsg.="<td width=\"2%\" height=\"100%\" align=\"center\" bgcolor=\"$contentbgcolor\">".$crlf;
				if($myrow2["headingicon"])
					$html_mailmsg.=str_replace($url_gfx."/","","<img src=\"$url_icons/".$myrow2["headingicon"]."\" border=\"0\" align=\"middle\"> ".$crlf);
				else
					$html_mailmsg.="&nbsp;";
				$html_mailmsg.="</td>".$crlf;
				$html_mailmsg.="<td  align=\"center\"><table width=\"100%\" align=\"center\" bgcolor=\"$contentbgcolor\" cellspacing=\"0\" cellpadding=\"0\">".$crlf;
			}
			$html_mailmsg.="<tr bgcolor=\"$timestampbgcolor\"><td align=\"left\">".$crlf;
			$html_mailmsg.="<font face=\"$timestampfont\" size=\"$timestampfontsize\" color=\"$timestampfontcolor\">".$crlf;
			$html_mailmsg.=get_start_tag($timestampstyle);
			$html_mailmsg.=$displaydate;
			$html_mailmsg.=get_end_tag($timestampstyle);
			$html_mailmsg.="</font></td></tr>".$crlf;
			$entrylink=$simpnews_fullurl."events.php?$langvar=".$myrow2["lang"]."&category=".$myrow2["category"]."&link_date=".$link_date;
			if(strlen($myrow2["heading"])>0)
			{
				$asc_heading=strip_tags($myrow2["heading"]);
				$asc_mailmsg.=$asc_heading;
				$asc_mailmsg.=$crlf;
				for($i=0;$i<strlen($asc_heading);$i++)
					$asc_mailmsg.="-";
				$asc_mailmsg.=$crlf;
				$html_mailmsg.="<tr bgcolor=\"$newsheadingbgcolor\"><td align=\"left\">".$crlf;
				$html_mailmsg.="<font face=\"$newsheadingfont\" size=\"$newsheadingfontsize\" color=\"$newsheadingfontcolor\">".$crlf;
				if($newsletterlinking==1)
					$html_mailmsg.="<a href=\"".$entrylink."\">";
				$html_mailmsg.=get_start_tag($newsheadingstyle);
				$html_mailmsg.=do_htmlentities(stripslashes($myrow2["heading"]));
				$html_mailmsg.=get_end_tag($newsheadingstyle);
				if($newsletterlinking==1)
					$html_mailmsg.="</a>";
				$html_mailmsg.="</font></td></tr>".$crlf;
			}
			$html_mailmsg.="<tr bgcolor=\"$contentbgcolor\"><td align=\"left\">".$crlf;
			$html_mailmsg.="<font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">".$crlf;
			$html_news=stripslashes($myrow2["text"]);
			$html_news=undo_htmlspecialchars($html_news);
			if($newsletterlinking==2)
				$html_news.="<br><a href=\"".$entrylink."\">".$l_mail_readmore."</a>";
			if($newsletterattachinlinepix==1)
				$html_news=recode_img_for_emails($html_news);
			$asc_newstext=str_replace("<BR>",$crlf,$html_news);
			$html_news=str_replace("<BR>","<BR>".$crlf,$html_news);
			$html_mailmsg.=$html_news."</font></td></tr>".$crlf;
			$asc_newstext=undo_htmlentities($asc_newstext);
			$asc_newstext=strip_tags($asc_newstext);
			if($newsletterlinking!=0)
				$asc_newstext.=$crlf."[".$entrylink."]";
			$asc_mailmsg.=$asc_newstext.$crlf;
			if($displayposter && (strlen($myrow2["poster"])>0))
			{
				$html_mailmsg.="<tr bgcolor=\"$posterbgcolor\"><td align=\"left\">".$crlf;
				$html_mailmsg.="<font face=\"$posterfont\" size=\"$posterfontsize\" color=\"$posterfontcolor\">".$crlf;
				$html_mailmsg.=get_start_tag($posterstyle);
				$html_mailmsg.="$l_poster: ".do_htmlentities($myrow2["poster"]);
				$html_mailmsg.=get_end_tag($posterstyle);
				$html_mailmsg.="</font></td></tr>".$crlf;
			}
			if($mailattach==0)
			{
				$attachsql="select f.filename, f.filesize, f.mimetype, eva.* from ".$tableprefix."_events_attachs eva, ".$tableprefix."_files f where f.entrynr=eva.attachnr and eva.eventnr=".$myrow2["eventnr"];
				if(!$attachresult = mysql_query($attachsql, $db))
					die("Could not connect to the database.");
				if($attachrow=mysql_fetch_array($attachresult))
				{
					do{
						$html_mailmsg.="<tr bgcolor=\"$contentbgcolor\">".$crlf;
						$html_mailmsg.="<td align=\"left\">".$crlf;
						$html_mailmsg.="<font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">".$crlf;
						$fileinfo=$attachrow["filename"]." (".format_bytes($attachrow["filesize"]).")";
						$mimesql="select * from ".$tableprefix."_mimetypes where mimetype='".$attachrow["mimetype"]."'";
						$imgtxt="";
						if(!$mimeresult = mysql_query($mimesql, $db))
							die("Could not connect to the database.");
						if($mimerow=mysql_fetch_array($mimeresult))
						{
							$fdsql="select * from ".$tableprefix."_filetypedescription where language='$act_lang' and mimetype=".$mimerow["entrynr"];
							if(!$fdresult = mysql_query($fdsql, $db))
								die("Could not connect to the database.");
							if($fdrow=mysql_fetch_array($fdresult))
								$dfileinfo=$fdrow["description"].": ".$fileinfo;
							else
								$dfileinfo=$fileinfo;
							if($mimerow["icon"])
								$imgtxt="<img class=\"attach\" src=\"".$mimerow["icon"]."\" border=\"0\" align=\"absmiddle\" title=\"$dfileinfo\" alt=\"$dfileinfo\"> ";
							else
								$imgtxt="<img src=\"$attachpic\" border=\"0\" align=\"absmiddle\" title=\"$dfileinfo\" alt=\"$dfileinfo\">";
						}
						else
							$imgtxt="<img src=\"$attachpic\" border=\"0\" align=\"absmiddle\" title=\"$fileinfo\" alt=\"$fileinfo\">";
						if($newsletternoicons==0)
						{
							$html_mailmsg.="&nbsp;<a href=\"".$simpnews_fullurl."sndownload.php?entrynr=".$attachrow["attachnr"]."\" target=\"_blank\">";
							$html_mailmsg.=str_replace($url_gfx."/","",$imgtxt);
							$html_mailmsg.="</a>&nbsp; ";
						}
						else
							$html_mailmsg.="Attachement:&nbsp;";
						$html_mailmsg.="<a href=\"".$simpnews_fullurl."sndownload.php?entrynr=".$attachrow["attachnr"]."\" target=\"_blank\">Download</a></font></td></tr>".$crlf;
						$html_mailmsg.="</td></tr>";
						$asc_mailmsg.= "Download attachement: ".$simpnews_fullurl."sndownload.php?entrynr=".$attachrow["attachnr"].$crlf;
					}while($attachrow=mysql_fetch_array($attachresult));
				}
			}
			else
			{
				$tmpsql="select files.* from ".$tableprefix."_events_attachs eva, ".$tableprefix."_files files where files.entrynr=eva.attachnr and eva.eventnr=".$myrow2["eventnr"];
				if(!$tmpresult = mysql_query($tmpsql, $db))
					die("Unable to connect to database.".mysql_error());
				while($tmprow=mysql_fetch_array($tmpresult))
				{
					if(!$attach_in_fs)
						$file_data=$tmprow["bindata"];
					else
						$file_data=get_file($path_attach."/".$tmprow["fs_filename"]);
					$mail->addAttachment($file_data, $tmprow["filename"], $tmprow["mimetype"]);
				}
			}
			$asc_mailmsg.=$crlf;
			if($newsletternoicons==0)
				$html_mailmsg.="</table></td></tr>".$crlf;
		}
	}
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
	if($numnews>0)
	{
		$unsubscribeurl=$simpnews_fullurl."subscription.php?$langvar=".$myrow["language"]."&id=".$myrow["unsubscribeid"]."&mode=remove&email=".$myrow["email"];
		$unsubscribeurl_html="<font face=\"Verdana, Geneva, Arial, Helvetica, sans-serif\" size=\"1\"><a href=\"$unsubscribeurl\">$unsubscribeurl</a></font>";
		if($layoutrow["emailremark"])
			$html_remark=str_replace("{unsubscribeurl}",$unsubscribeurl_html,$layoutrow["emailremark"]);
		else
			$html_remark="unsubscribe: ".$unsubscribeurl_html;
		$html_remark=str_replace("\n","<br>".$crlf,$html_remark);
		if($layoutrow["emailremark"])
			$asc_remark=str_replace("{unsubscribeurl}",$unsubscribeurl,strip_tags($layoutrow["emailremark"]));
		else
			$asc_remark="unsubscribe: ".$unsubscribeurl;
		$asc_remark=str_replace("\n",$crlf,$asc_remark);
		$userhtmlbody=$html_mailmsg;
		if(strlen($html_remark)>0)
			$userhtmlbody.="<hr>$html_remark<br><br>".$crlf;
		$userhtmlbody.="---<BR>".str_replace("\n","<BR>".$crlf,$layoutrow["defsignature"]).$crlf;
		$userascbody=$asc_mailmsg;
		if(strlen($asc_remark)>0)
			$userascbody.="---------------------------".$crlf.$asc_remark.$crlf.$crlf;
		$userascbody.="---".$crlf.str_replace("\n",$crlf,$layoutrow["defsignature"]);
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
    	if(!$insafemode)
		@set_time_limit($msendlimit);
	$receiver=array();
	array_push($receiver,$myrow["email"]);
	if($use_smtpmail)
	{
		$mail->setSMTPParams($smtpserver,$smtpport,NULL,$smtpauth,$smtpuser,$smtppasswd);
		$sendresult=$mail->send($receiver, "smtp");
	}
	else
	    	$sendresult=$mail->send($receiver, "mail");
	$addlogtxt="newsmailer.php - subscription#: ".$myrow["subscriptionnr"]." - admin: ".$userdata["username"];
	if(isset($newsnr))
	{
	    	$addlogtxt.=" - news#: ";
	    	for($i=0;$i<count($newsnr);$i++)
	    	{
	    		if($i>0)
	    			$addlogtxt.=",";
	    		$addlogtxt.=$newsnr[$i];
	    	}
	}
	if(isset($eventnr))
	{
	    	$addlogtxt.=" - events#: ";
	    	for($i=0;$i<count($eventnr);$i++)
	    	{
	    		if($i>0)
	    			$addlogtxt.=",";
	    		$addlogtxt.=$eventnr[$i];
	    	}
	}
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
}
    $sql2="update ".$tableprefix."_subscriptions set lastmanual='$actdate' where subscriptionnr=$input_subscriptionnr";
	if(!$result2 = mysql_query($sql2, $db))
		die("Unable to connect to database.".mysql_error());
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="displayrow"><td align="center"><?php echo $l_emailssent?></td></tr>
<?php
echo"</table></td></tr></table>";
}
echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("subscribers.php?$langvar=$act_lang")."\">$l_subscribers</a></div>";
include('./trailer.php');
?>