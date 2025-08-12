<?php
/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/
/***************************************************************************
 * Created by: Boesch IT-Consulting (info@boesch-it.de)
 * (c)2002-2005 Boesch IT-Consulting
 * *************************************************************************/
require('../config.php');
require('./auth.php');
if(!isset($lang) || !$lang)
	$lang=$admin_lang;
include('./language/lang_'.$lang.'.php');
$page_title=$l_newsletter;
require('./heading.php');
require_once('../includes/htmlMimeMail.inc');
if($use_smtpmail)
{
	require_once('../includes/smtp.inc');
	require_once('../includes/RFC822.inc');
}
$sql = "select * from ".$tableprefix."_layout where (layoutnr=1)";
if(!$result = mysql_query($sql, $db)) {
    die("Could not connect to the database.");
}
$progsysmail=$myrow["progsysmail"];
$psysmailname=$myrow["psysmailname"];
$page_bgcolor=$myrow["pagebg"];
$mailsig=$myrow["mailsig"];
$FontFace=$myrow["fontface"];
$FontSize1=$myrow["fontsize1"];
$FontSize2=$myrow["fontsize2"];
$FontSize3=$myrow["fontsize3"];
$FontSize4=$myrow["fontsize4"];
$FontSize5=$myrow["fontsize5"];
$FontColor=$myrow["fontcolor"];
$TableWidth=$myrow["tablewidth"];
$heading_bgcolor=$myrow["headingbg"];
$table_bgcolor=$myrow["bgcolor1"];
$row_bgcolor=$myrow["bgcolor2"];
$group_bgcolor=$myrow["bgcolor3"];
$HeadingFontColor=$myrow["headingfontcolor"];
$SubheadingFontColor=$myrow["subheadingfontcolor"];
$GroupFontColor=$myrow["groupfontcolor"];
$TableDescFontColor=$myrow["tabledescfontcolor"];
$msendlimit=$myrow["msendlimit"];
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<?php
if(!isset($listtype))
	$listtype=0;
if(isset($mode))
{
	if($mode=="changelog")
	{
		if($admin_rights < 2)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="headingrow"><td align="center" colspan="2"><b><?php echo $l_enternewsletter?></b></td></tr>
<?php
		$errors=0;
		if(!isset($prognr) || ($prognr<0))
		{
			echo "<tr class=\"errorrow\" align=\"center\"><td>";
			echo "$l_noprogramm</td></tr>";
			$errors=1;
		}
		if(!isset($changelognr))
		{
			echo "<tr class=\"errorrow\" align=\"center\"><td>";
			echo "$l_nochangelog</td></tr>";
			$errors=1;
		}
		if($errors==1)
		{
			echo "<tr class=\"actionrow\" align=\"center\"><td>";
			echo "<a href=\"javascript:history.back()\">$l_back</a>";
			echo "</td></tr></table></td></tr></table>";
		}
		else
		{
			$sql = "select prog.* from ".$tableprefix."_programm prog where prognr='$prognr'";
			if(!$result = mysql_query($sql, $db))
				die("<tr class=\"errorrow\"><td>Could not connect to the database. (".mysql_error().")");
			if (!$myrow = mysql_fetch_array($result))
			{
				echo "<tr class=\"displayrow\"><td align=\"center\" colspan=\"3\">";
				echo $l_noentries;
				die("</td></tr></table></td></tr></table>");
			}
			$progname=$myrow["programmname"];
			$proglang=$myrow["language"];
			$sql = "select * from ".$tableprefix."_changelog where entrynr='$changelognr'";
			if(!$result = mysql_query($sql, $db))
				die("<tr class=\"errorrow\"><td>Could not connect to the database. (".mysql_error().")");
			if (!$myrow = mysql_fetch_array($result))
			{
				echo "<tr class=\"displayrow\"><td align=\"center\" colspan=\"3\">";
				echo $l_noentries;
				die("</td></tr></table></td></tr></table>");
			}
			include("./language/datefmt_".$proglang.".php");
			list($year, $month, $day) = explode("-", $myrow["versiondate"]);
			$releasedate=date($l_usrdateformat,mktime(0,0,0,$month,$day,$year));
			$progversion=$myrow["version"];
			$changes=$myrow["changes"];
			$sql = "select * from ".$tableprefix."_texts where textid='chnl' and lang='$proglang'";
			include('./language/ch2nl_'.$proglang.'.php');
			if(!$result = mysql_query($sql, $db))
				die("<tr class=\"errorrow\"><td>Could not connect to the database. (".mysql_error().")");
			if (!$myrow = mysql_fetch_array($result))
				$prelude="";
			else
				$prelude=$myrow["text"];
			$displaysubject=str_replace("{progname}",$progname,$l_chnlsubject);
			$displaysubject=str_replace("{progversion}",$progversion,$displaysubject);
			$displaytext="";
			if(strlen($prelude)>0)
			{
				$prelude=stripslashes($prelude);
				$prelude = str_replace("<BR>", "\n", $prelude);
				$prelude = undo_htmlspecialchars($prelude);
				$prelude = bbdecode($prelude);
				$prelude = undo_make_clickable($prelude);
				$prelude=str_replace("{progname}",$progname,$prelude);
				$prelude=str_replace("{progversion}",$progversion,$prelude);
				$prelude=str_replace("{releasedate}",$releasedate,$prelude);
				$displaytext.=$prelude."\n";
			}
			$displaytext.=$ch2nl_changes.":\n";
			$changelogtext=stripslashes($changes);
			$changelogtext = str_replace("<BR>", "\n", $changelogtext);
			$changelogtext = undo_htmlspecialchars($changelogtext);
			$changelogtext = bbdecode($changelogtext);
			$changelogtext = undo_make_clickable($changelogtext);
			$displaytext.=$changelogtext;
?>
<tr class="inforow"><td align="center" colspan="2"><b><?php echo "$progname [$proglang]"?></b></td></tr>
<form method="post" action="<?php echo $act_script_url?>">
<?php
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<input type="hidden" name="prognr" value="<?php echo $prognr?>">
<input type="hidden" name="lang" value="<?php echo $lang?>">
<input type="hidden" name="changelognr" value="<?php echo $changelognr?>">
<input type="hidden" name="mode" value="subscribers">
<input type="hidden" name="listtype" value="<?php echo $listtype?>">
<tr class="inputrow"><td align="right" valign="top" width="30%"><?php echo $l_subject?>:</td>
<td><input class="psysinput" type="text" name="subject" size="40" maxlength="80" value="<?php echo $displaysubject?>"></td></tr>
<tr class="inputrow"><td align="right" valign="top" width="30%"><?php echo $l_text?>:<br>
<?php echo "<a class=\"listlink\" href=\"help/".$lang."/bbcode.html\" target=\"_blank\">$l_bbcodehelp</a>"?></td>
<td><textarea class="psysinput" name="newslettertext" rows="20" cols="60"><?php echo $displaytext?></textarea></td></tr>
<tr class="inputrow"><td align="right" valign="top" width="30%"><?php echo $l_additionalremark?>:</td>
<td><textarea class="psysinput" name="remark" rows="6" cols="60"></textarea></td></tr>
<tr class="optionrow"><td align="right" valign="top"><?php echo $l_options?>:</td><td align="left">
<input type="checkbox" name="local_urlautoencode" value="1" <?php if($urlautoencode==1) echo "checked"?>> <?php echo $l_urlautoencode?><br>
<input type="checkbox" name="local_enablespcode" value="1" <?php if($enablespcode==1) echo "checked"?>> <?php echo $l_enablespcode?>
</td></tr>
<tr class="actionrow"><td align="center" colspan="2">
<input class="psysbutton" type="submit" value="<?php echo $l_next?>"></td></tr></form></table></td></tr></table>
<?php
		}
	}
	if($mode=="enter")
	{
		if($admin_rights < 2)
		{
			echo "<tr bgcolor=\"#cccccc\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="headingrow"><td align="center" colspan="2"><b><?php echo $l_enternewsletter?></b></td></tr>
<?php
		if(!isset($prognr) || ($prognr<0))
		{
			echo "<tr class=\"errorrow\" align=\"center\"><td>";
			echo "$l_noprogramm</td></tr>";
			echo "<tr class=\"actionrow\" align=\"center\"><td>";
			echo "<a href=\"javascript:history.back()\">$l_back</a>";
			echo "</td></tr></table></td></tr></table>";
		}
		else
		{
			if($admin_rights<3)
				$sql = "select prog.* from ".$tableprefix."_programm prog, ".$tableprefix."_programm_admins pa where prog.prognr=$prognr and prog.prognr = pa.prognr and pa.usernr=$act_usernr and prog.enablenewsletter=1 order by prog.prognr";
			else
				$sql = "select prog.* from ".$tableprefix."_programm prog  where prog.enablenewsletter=1 and prog.prognr=$prognr order by prog.prognr";
			if(!$result = mysql_query($sql, $db))
			    die("Could not connect to the database. (".mysql_error().")");
			if (!$myrow = mysql_fetch_array($result))
			{
				echo "<tr class=\"displayrow\"><td align=\"center\" colspan=\"3\">";
				echo $l_noentries;
				die("</td></tr></table></td></tr></table>");
			}
			$progname=$myrow["programmname"];
			$proglang=$myrow["language"];
?>
<tr class="inforow"><td align="center" colspan="2"><b><?php echo "$progname [$proglang]"?></b></td></tr>
<form ENCTYPE="multipart/form-data" method="post" action="<?php echo $act_script_url?>">
<?php
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<input type="hidden" name="prognr" value="<?php echo $prognr?>">
<input type="hidden" name="lang" value="<?php echo $lang?>">
<input type="hidden" name="mode" value="subscribers">
<input type="hidden" name="listtype" value="<?php echo $listtype?>">
<tr class="inputrow"><td align="right" valign="top" width="30%"><?php echo $l_subject?>:</td>
<td><input class="psysinput" type="text" name="subject" size="40" maxlength="80"></td></tr>
<tr class="inputrow"><td align="right" valign="top" width="30%"><?php echo $l_text?>:<br>
<?php echo "<a class=\"listlink\" href=\"help/".$lang."/bbcode.html\" target=\"_blank\">$l_bbcodehelp</a>"?></td>
<td><textarea class="psysinput" name="newslettertext" rows="20" cols="60"></textarea><br>
<?php echo $l_fromfile?>:<br>
<input class="psysfile" type="file" size="50" name="newslettertextfile"></td></tr>
<tr class="inputrow"><td align="right" valign="top" width="30%"><?php echo $l_additionalremark?>:</td>
<td><textarea class="psysinput" name="remark" rows="6" cols="60"></textarea></td></tr>
<tr class="optionrow"><td align="right" valign="top"><?php echo $l_options?>:</td><td align="left">
<input type="checkbox" name="local_urlautoencode" value="1" <?php if($urlautoencode==1) echo "checked"?>> <?php echo $l_urlautoencode?><br>
<input type="checkbox" name="local_enablespcode" value="1" <?php if($enablespcode==1) echo "checked"?>> <?php echo $l_enablespcode?>
</td></tr>
<tr class="actionrow"><td align="center" colspan="2">
<input class="psysbutton" type="submit" value="<?php echo $l_next?>"></td></tr></form></table></td></tr></table>
<?php
		}
	}
	if($mode=="subscribers")
	{
		if($admin_rights < 2)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="headingrow"><td align="center" colspan="2"><b><?php echo $l_enternewsletter?></b></td></tr>
<?php
		$errors=0;
		if(!isset($newslettertextfile))
			$newslettertextfile="none";
		if(($newslettertextfile=="none") && (strlen($newslettertext)<1))
		{
			echo "<tr class=\"errorrow\" align=\"center\"><td>";
			echo "$l_nonewslettertext</td></tr>";
			$errors=1;
		}
		if(!$subject)
		{
			echo "<tr class=\"errorrow\" align=\"center\"><td>";
			echo "$l_nosubject</td></tr>";
			$errors=1;
		}
		if($errors==0)
		{
			if(!isset($local_urlautoencode))
				$urlautoencode=0;
			else
				$urlautoencode=1;
			if(!isset($local_enablespcode))
				$enablespcode=0;
			else
				$enablespcode=1;
			if($new_global_handling)
				$tmp_file=$HTTP_POST_FILES['newslettertextfile']['tmp_name'];
			else
				$tmp_file=$_FILES['newslettertextfile']['tmp_name'];
			if(is_uploaded_file($tmp_file))
				$newslettertext = fread(fopen($tmp_file,"r"), filesize($tmp_file));
			$displaynewslettertext=stripslashes($newslettertext);
			if($urlautoencode==1)
				$displaynewslettertext = make_clickable($displaynewslettertext);
			if($enablespcode==1)
					$displaynewslettertext = bbencode($displaynewslettertext);
			$displaynewslettertext = htmlentities($displaynewslettertext);
			$displaynewslettertext = str_replace("\n", "<BR>", $displaynewslettertext);
			$displaynewslettertext = undo_htmlspecialchars($displaynewslettertext);
			echo "<tr class=\"displayrow\"><td align=\"right\" valign=\"top\" width=\"30%\">";
			echo "$l_subject:</td><td>$subject</td></tr>";
			echo "<tr class=\"displayrow\"><td align=\"right\" valign=\"top\" width=\"30%\">";
			echo "$l_text:</td><td>$displaynewslettertext</td></tr>";
			if($remark)
			{
				$displayremark=str_replace("\n","<BR>",$remark);
				$displayremark=htmlentities(stripslashes($displayremark));
				echo "<tr class=\"displayrow\"><td align=\"right\" valign=\"top\" width=\"30%\">";
				echo "$l_additionalremark:</td><td>$displayremark</td></tr>";
			}
?>
<form method="post" action="<?php echo $act_script_url?>">
<?php
			if($sessid_url)
				echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
			if(isset($local_urlautoencode))
				echo "<input type=\"hidden\" name=\"local_urlautoencode\" value=\"1\">";
			if(isset($local_enablespcode))
				echo "<input type=\"hidden\" name=\"local_enablespcode\" value=\"1\">";
			if(isset($changelognr) && ($changelognr))
				echo "<input type=\"hidden\" name=\"changelognr\" value=\"".$changelognr."\">";
?>
<input type="hidden" name="subject" value="<?php echo $subject?>">
<input type="hidden" name="lang" value="<?php echo $lang?>">
<input type="hidden" name="prognr" value="<?php echo $prognr?>">
<input type="hidden" name="newslettertext" value="<?php echo htmlentities(stripslashes($newslettertext))?>">
<input type="hidden" name="mode" value="sendnews">
<input type="hidden" name="remark" value="<?php echo htmlentities(stripslashes($remark))?>">
<tr class="inputrow"><td align="right" valign="top" width="30%"><?php echo $l_sendto?>:</td>
<td>
<?php
			$sql="select * from ".$tableprefix."_newsletter where programm='$prognr' and confirmed=1 and listtype='$listtype' order by enterdate desc";
			if(!$result = mysql_query($sql, $db)) {
			    die("Could not connect to the database. (".mysql_error().")");
			}
			if (!$myrow = mysql_fetch_array($result))
				echo $l_noentries;
			else
			{
				echo "<select name=\"receivers[]\" size=\"10\" multiple>";
				$numreceivers=0;
				do
				{
					echo "<option value=\"".$myrow["entrynr"]."\" selected>".$myrow["email"]."</option>";
					$numreceivers++;
				}while($myrow=mysql_fetch_array($result));
				echo "</select>";
				echo "<br>$l_numreceivers: $numreceivers";
?>
</td></tr>
<tr class="actionrow"><td align="center" colspan="2">
<input class="psysbutton" type="submit" value="<?php echo $l_send?>"></td></tr>
<?php
			}
			echo "</form></table></td></tr></table>";
		}
		else
		{
			echo "<tr class=\"actionrow\" align=\"center\"><td>";
			echo "<a href=\"javascript:history.back()\">$l_back</a>";
			echo "</td></tr></table></td></tr></table>";
		}
	}
	if($mode=="sendnews")
	{
		if($admin_rights < 2)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_sendnewsletter?></b></td></tr>
<?php
		if(!isset($receivers))
		{
			echo "<tr class=\"errorrow\" align=\"center\"><td>";
			echo "$l_receivers</td></tr>";
			echo "<tr class=\"actionrow\" align=\"center\"><td>";
			echo "<a href=\"javascript:history.back()\">$l_back</a>";
			echo "</td></tr></table></td></tr></table>";
		}
		else
		{
			if(!isset($local_urlautoencode))
				$urlautoencode=0;
			else
				$urlautoencode=1;
			if(!isset($local_enablespcode))
				$enablespcode=0;
			else
				$enablespcode=1;
			$displaynewslettertext=stripslashes($newslettertext);
			if($urlautoencode==1)
				$displaynewslettertext = make_clickable($displaynewslettertext);
			if($enablespcode==1)
					$displaynewslettertext = bbencode($displaynewslettertext);
			$displaynewslettertext = htmlentities($displaynewslettertext);
			$displaynewslettertext = str_replace("\n", "<BR>", $displaynewslettertext);
			$displaynewslettertext = str_replace("\r", "", $displaynewslettertext);
			$displaynewslettertext = undo_htmlspecialchars($displaynewslettertext);
			$sql = "select * from ".$tableprefix."_programm where prognr='$prognr'";
			if(!$result = mysql_query($sql, $db))
			    die("Could not connect to the database. (".mysql_error().")");
			if (!$myrow = mysql_fetch_array($result))
			{
				echo "<tr class=\"errorrow\"><td align=\"center\">";
				echo $l_nosuchprogramm;
				echo "</td></tr></table></td></tr></table>";
			}
			else
			{
				$progname=$myrow["programmname"];
				$proglang=$myrow["language"];
				$progid=$myrow["progid"];
				$progtxt="<i>$progname";
				if($listtype==1)
					$progtxt.=" (Beta)";
				$progtxt.="</i>";
				$newsletterremark=str_replace("{progname}",$progtxt,$myrow["newsletterremark"]);
				$html_mail="<html><body bgcolor=\"$page_bgcolor\">";
				$asc_mail="";
				$html_mail.="<table width=\"$TableWidth\" border=\"0\" CELLPADDING=\"1\" CELLSPACING=\"0\" ALIGN=\"CENTER\" VALIGN=\"TOP\" BGCOLOR=\"$table_bgcolor\">";
				$html_mail.="<tr BGCOLOR=\"$table_bgcolor\"><TD>";
				$html_mail.="<TABLE BORDER=\"0\" CELLPADDING=\"1\" CELLSPACING=\"1\" WIDTH=\"100%\">";
				$html_mail.="<TR BGCOLOR=\"$heading_bgcolor\" ALIGN=\"CENTER\">";
				$html_mail.="<TD ALIGN=\"CENTER\" VALIGN=\"MIDDLE\"><font face=\"$FontFace\" size=\"$FontSize3\" color=\"$HeadingFontColor\"><b>".htmlentities($subject)."</b></font></td></tr>";
				$html_mail.="<tr bgcolor=\"$row_bgcolor\" align=\"left\"><td><font face=\"$FontFace\" size=\"$FontSize2\" color=\"$FontColor\">";
				$html_mail.="$displaynewslettertext</font></td></tr></table></td></tr></table><br><br>";
				$asc_subject=strip_tags($subject);
				$asc_mail.=$subject.$crlf;
				for($i=0;$i<strlen($subject);$i++)
				{
					$asc_mail.="-";
				}
				$asc_mail.=$crlf;
				$asc_text=str_replace("<BR>",$crlf,$displaynewslettertext);
				$asc_text=undo_htmlentities($asc_text);
				$asc_text=strip_tags($asc_text);
				$asc_mail.=$asc_text.$crlf.$crlf;
				if($remark)
				{
					$html_remark=str_replace("\n","<BR>",stripslashes($remark));
					$html_remark=str_replace("\r","",stripslashes($remark));
					$html_remark=htmlentities($html_remark);
					$html_mail.=$html_remark."<BR><BR>";
					$asc_remark=str_replace("\r","",stripslashes(remark));
					$asc_remark=str_replace("\n",$crlf,asc_remark);
					$asc_mail=$asc_remark.$crlf.$crlf;
				}
				$sendcount=0;
				$numreceivers=0;
				while(list($null, $receiver) = each($_POST["receivers"]))
				{
					$receiver_query = "SELECT * from ".$tableprefix."_newsletter where entrynr='$receiver' and listtype='$listtype'";
	    				if(!$receiver_result=mysql_query($receiver_query, $db))
						die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
					if($receiver_row=mysql_fetch_array($receiver_result))
					{
						$receiverdata[$numreceivers]=$receiver_row;
						$numreceivers++;
					}
					@mysql_free_result($receiver_result);
				}
				if(isset($changelognr) && ($changelognr))
				{
					$tmpsql="update ".$tableprefix."_changelog set nlsenddate=now() where entrynr='$changelognr'";
					@mysql_query($tmpsql,$db);
				}
				$errors="";
				for($i=0;$i<$numreceivers;$i++)
				{
					$receiver_row=$receiverdata[$i];
					$mail = new htmlMimeMail();
					$mail->setCrlf($crlf);
					$mail->setTextCharset($contentcharset);
					$unsubscribeurl=$progsys_fullurl."subscription.php?lang=$proglang&prog=$progid&id=".$receiver_row["unsubscribeid"]."&mode=remove&email=".urlencode($receiver_row["email"]);
					$unsubscribeurl_html="<a href=\"$unsubscribeurl\">$unsubscribeurl</a>";
					$homepagelink_html="<a href=\"$homepageurl\">$homepagedesc</a>";
					$homepagelink="$homepagedesc ($homepageurl)";
					$html_remark=str_replace("{unsubscribeurl}",$unsubscribeurl_html,$newsletterremark);
					$html_remark=str_replace("{homepageurl}",$homepagelink_html,$html_remark);
					$html_remark=str_replace("\n","<br>",$html_remark);
					$asc_remark=str_replace("{unsubscribeurl}",$unsubscribeurl,strip_tags($newsletterremark));
					$asc_remark=str_replace("{homepageurl}",$homepagelink,$asc_remark);
					$asc_remark=str_replace("\r","",$asc_remark);
					$asc_remark=str_replace("\n",$crlf,$asc_remark);
					$userhtmlbody=$html_mail;
					if(strlen($html_remark)>0)
						$userhtmlbody.="<hr>$html_remark<br><br>";
					$tmpsig=str_replace("\n","<BR>",$mailsig);
					$tmpsig=str_replace("\r","",$tmpsig);
					$userhtmlbody.="---<BR>".$tmpsig;
					$userhtmlbody.="</body></html>";
					$userascbody=$asc_mail;
					if(strlen($asc_remark)>0)
						$userascbody.="---------------------------".$crlf.$crlf.$asc_remark.$crlf.$crlf;
					$tmpsig=str_replace("\r","",$mailsig);
					$tmpsig=str_replace("\n",$crlf,$tmpsig);
					$userascbody.="---".$crlf.$tmpsig;
					if($receiver_row["emailtype"]==0)
					{
						$mail->setHTMLCharset($contentcharset);
						$mail->setHTML($userhtmlbody, $userascbody);
					}
					else
						$mail->setText($userascbody);
					$mail->setSubject(strip_tags($subject));
					if(strlen($myrow["emailname"])>0)
						$psysfrom="\"".$myrow["emailname"]."\" <".$progsysmail.">";
					else if(strlen($psysmailname)>0)
						$psysfrom="\"".$psysmailname."\" <".$progsysmail.">";
					else
						$psysfrom=$progsysmail;
					$mail->setFrom($psysfrom);
					if(strlen($receiver_row["subscribername"])>0)
					{
						$tmpreceiver="\"".$receiver_row["subscribername"]."\"";
						$tmpreceiver.=" <".$receiver_row["email"].">";
						$currentreceiver=array($tmpreceiver);
					}
					else
						$currentreceiver=array($receiver_row["email"]);
					@set_time_limit($msendlimit);
					if($use_smtpmail)
					{
						$mail->setSMTPParams($smtpserver,$smtpport,NULL,$smtpauth,$smtpuser,$smtppasswd);
						$success=$mail->send($currentreceiver, "smtp");
					}
					else
						$success=$mail->send($currentreceiver, "mail");
					if(!$success)
					{
						$errors.=$l_msenderror." (<i>".$receiver_row["email"]."</i>)";
						if($use_smtpmail)
						{
							for($x=0;$x<count($mail->errors);$x++)
								$errors.=" [".$mail->errors[$x]."]";
						}
						$errors.="<br>";
					}
					else
						$sendcount++;
				}
			}
			echo "<tr class=\"displayrow\" align=\"center\"><td>";
			if($errors)
				echo $errors;
			echo "<i>$sendcount</i> $l_newslettersent";
			echo "</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("changelog.php?lang=$lang")."\">$l_changeloglist</a></div>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_sendnewsletter</a></div>";
		}
	}
}
else
{
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
	if($admin_rights < 2)
	{
		echo "<tr class=\"errorrow\"><td align=\"center\">";
		die("$l_functionnotallowed");
	}
	if($admin_rights<3)
		$sql = "select prog.* from ".$tableprefix."_programm prog, ".$tableprefix."_programm_admins pa, ".$tableprefix."_newsletter nl where prog.prognr=nl.programm and nl.confirmed=1 and prog.prognr = pa.prognr and pa.usernr=$act_usernr and prog.enablenewsletter=1 group by prog.prognr order by prog.prognr";
	else
		$sql = "select prog.* from ".$tableprefix."_programm prog, ".$tableprefix."_newsletter nl where prog.prognr=nl.programm and nl.confirmed=1 and prog.enablenewsletter=1 group by prog.prognr order by prog.prognr";
	if(!$result = mysql_query($sql, $db)) {
	    die("Could not connect to the database. (".mysql_error().")");
	}
	if (!$myrow = mysql_fetch_array($result))
	{
		echo "<tr class=\"displayrow\"><td align=\"center\" colspan=\"3\">";
		echo $l_noentries;
		echo "</td></tr></table></td></tr></table>";
	}
	else
	{
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="headingrow"><td align="center" colspan="2"><b><?php echo $l_sendnewsletter?></b></td></tr>
<form method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="lang" value="<?php echo $lang?>">
<?php
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<input type="hidden" name="mode" value="enter">
<tr class="inforow"><td align="center" colspan="2"><?php echo $l_selectnewsletterprog?></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_programm?>:</td>
<td><select name="prognr"><option value="-1">???</option>
<?php
		do{
			echo "<option value=\"".$myrow["prognr"]."\">".$myrow["programmname"]." [".$myrow["language"]."] </option>";
		}while($myrow=mysql_fetch_array($result));
?>
</select></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td><input type="radio" name="listtype" value="0" checked>release<br>
<input type="radio" name="listtype" value="1">beta</td></tr>
<tr class="actionrow"><td align="center" colspan="2">
<input class="psysbutton" type="submit" value="<?php echo $l_ok?>"></td></tr></form></table></td></tr></table>
<?php
	}
}
include('trailer.php');
?>