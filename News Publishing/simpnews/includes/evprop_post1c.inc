<?php
/***************************************************************************
 * (c)2002-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
include_once('./includes/htmlMimeMail.inc');
if($use_smtpmail)
{
	include_once('./includes/smtp.inc');
	include_once('./includes/RFC822.inc');
}
include_once('./includes/sesshandling.inc');
$loggedin=is_loggedin();
if(!$loggedin)
{
	include_once("./includes/head.inc");
	$link="$act_script_url?$langvar=$lang&layout=$layout&mode=edlst";
	if(isset($backurl))
		$link.="&backurl=".urlencode($backurl);
?>
<tr bgcolor="<?php echo $headingbgcolor?>" align="center">
<td colspan="2">
<font face="<?php echo $headingfont?>" size="<?php echo $headingfontsize?>" color="<?php echo $headingfontcolor?>">
<?php echo $l_edit_proposed_events?></font>
</td></tr>
<tr bgcolor="<?php echo $headingbgcolor?>" align="center">
<td colspan="2">
<font face="<?php echo $headingfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $headingfontcolor?>">
<?php echo "$l_loginfirst"?></font>
</td></tr>
<tr bgcolor="<?php echo $headingbgcolor?>" align="center">
<td colspan="2">
<font face="<?php echo $headingfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $headingfontcolor?>">
<a href="<?php echo $link?>" class="actionlink"><?php echo $l_login?></a></font>
</td></tr>
<?php
}
else
{
	$errors=0;
	$errmsg="";
	if(!isset($input_heading))
		$input_heading="";
	if(!isset($entrytxt))
		$entrytxt="";
	$entrytxt=trim($entrytxt);
	$heading=trim($heading);
	if(!isset($postingid) || !$postingid)
	{
		include_once("./includes/head.inc");
?>
<tr bgcolor="<?php echo $headingbgcolor?>" align="center">
<td colspan="2">
<font face="<?php echo $headingfont?>" size="<?php echo $headingfontsize?>" color="<?php echo $headingfontcolor?>">
<?php echo $l_edit_proposed_events?></font>
</td></tr>
<?php
		echo "<tr bgcolor=\"$contentbgcolor\" align=\"center\">";
		echo "<td align=\"center\" colspan=\"2\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
		echo "$l_callingerror</td></tr>";
		echo "</table></td></tr></table>";
		include_once("./includes/footer.inc");
		exit;
	}
	if(bittst($proposereq,BIT_3) && !$input_heading)
	{
		$errors=1;
		$errmsg.="<tr bgcolor=\"$contentbgcolor\" align=\"center\">";
		$errmsg.="<td align=\"center\" colspan=\"2\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
		$errmsg.="$l_noheading</td></tr>";
	}
	if(!isset($entrytext) || !$entrytext)
	{
		$errors=1;
		$errmsg.="<tr bgcolor=\"$contentbgcolor\" align=\"center\">";
		$errmsg.="<td align=\"center\" colspan=\"2\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
		$errmsg.="$l_notext</td></tr>";
	}
	if(!checkdate($sel_month,$sel_day,$sel_year))
	{
		$errors=1;
		$errmsg.="<tr bgcolor=\"$contentbgcolor\" align=\"center\">";
		$errmsg.="<td align=\"center\" colspan=\"2\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
		$tmpmsg=str_replace("{day}",$sel_day,$l_novaliddate);
		$tmpmsg=str_replace("{month}",$sel_month,$tmpmsg);
		$tmpmsg=str_replace("{year}",$sel_year,$tmpmsg);
		$errmsg.=$tmpmsg;
		$errmsg.="</td></tr>";
	}
	else
	{
		$event_time=mktime(0,0,0,$sel_month,$sel_day,$sel_year);
		$eventdate=date("Y-m-d",$event_time);
	}
	if($errors==0)
	{
		$sql="select * from ".$tableprefix."_tmpevents where postingid='".$postingid."'";
		if(!$result = mysql_query($sql, $db))
			die("<tr class=\"errorrow\"><td>Could not connect to the database.".mysql_error());
		if($tmprow=mysql_fetch_array($result))
		{
			$errors=1;
			$errmsg.="<tr bgcolor=\"$contentbgcolor\" align=\"center\">";
			$errmsg.="<td align=\"center\" colspan=\"2\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
			$errmsg.="$l_noflooding</td></tr>";
		}
	}
	if($errors==1)
	{
		include_once("./includes/head.inc");
?>
<tr bgcolor="<?php echo $headingbgcolor?>" align="center">
<td colspan="2">
<font face="<?php echo $headingfont?>" size="<?php echo $headingfontsize?>" color="<?php echo $headingfontcolor?>">
<?php echo $l_edit_proposed_events?></font>
</td></tr>
<?php
		echo $errmsg;
		echo "<tr bgcolor=\"$headingbgcolor\" align=\"center\">";
		echo "<td align=\"center\" colspan=\"2\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
		echo "<a class=\"actionlink\" href=\"javascript:history.back()\">$l_back</a></td></tr>";
		echo "</table></td></tr></table>";
		include_once("./includes/footer.inc");
		exit;
	}
	if(bittst($proposepermissions,BIT_4) || ($userdata["disablebbcode"]==1))
	{
		unset($urlautoencode);
		unset($enablespcode);
		$disableemoticons=1;
	}
	$actdate = date("Y-m-d H:i:s");
	$eventtext = $entrytext;
	$eventtext = do_htmlentities($eventtext);
	$eventtext = str_replace("\n", "<BR>", $eventtext);
	$eventtext = str_replace("\r", "", $eventtext);
	$eventtext=addslashes($eventtext);
	$tmpsql="select * from ".$tableprefix."_events where eventnr='$refeventnr'";
	if(!$result = mysql_query($tmpsql, $db))
		die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
	if(!$eventrow=mysql_fetch_array($result))
		die("<tr class=\"errorrow\"><td>Unable to get data");
	$posterip = get_userip();
	$category=$eventrow["category"];
	$language=$eventrow["lang"];
	$sql = "insert into ".$tableprefix."_tmpevents (lang, date, added, text, heading, category, posterip, posterid, chgevent, postingid)";
	$sql.= "values ('$language', '$eventdate', '$actdate', '$eventtext', '$input_heading', $category, '$posterip', ".$userdata["entrynr"].", $refeventnr, '$postingid')"; 
	if(!$result = mysql_query($sql, $db))
		die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
	$proposenr=mysql_insert_id($db);
	if($proposenotify==1)
	{
		if($category>0)
		{
			$tmpsql="select * from ".$tableprefix."_categories where catnr='$category'";
			if(!$tmpresult = mysql_query($tmpsql, $db))
				die("<tr class=\"errorrow\"><td>Could not connect to the database.".mysql_error());
			if($tmprow=mysql_fetch_array($tmpresult))
			{
				$catname=stripslashes($tmprow["catname"]);
				$tmpsql2="select * from ".$tableprefix."_catnames where catnr=".$tmprow["catnr"]." and lang='".$act_lang."'";
				if(!$tmpresult2=mysql_query($tmpsql2,$db))
					die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
				if($tmprow2=mysql_fetch_array($tmpresult2))
				{
					if(strlen($tmprow2["catname"])>0)
						$catname=stripslashes($tmprow2["catname"]);
				}
			}
			else
				$catname="???";
		}
		else
			$catname=$l_general;
		if(($notifymode==0) || ($category==0))
			$tmpsql="select u.* from ".$tableprefix."_notifylist nl, ".$tableprefix."_users u where u.usernr=nl.usernr";
		else
			$tmpsql="select u.* from ".$tableprefix."_users u, ".$tableprefix."_cat_adm ca where u.usernr=ca.usernr and ca.catnr='$category'";
		if(!$tmpresult = mysql_query($tmpsql, $db))
			die("<tr class=\"errorrow\"><td>Could not connect to the database.".mysql_error());
		while($tmprow=mysql_fetch_array($tmpresult))
		{
			$linkurl=$simpnews_fullurl."admin/evproposes.php?$langvar=".$tmprow["language"]."&mode=display&input_entrynr=$proposenr";
			$tmpsql2="select * from ".$tableprefix."_texts where lang='".$tmprow["language"]."' and textid='notsubj'";
			if(!$tmpresult2 = mysql_query($tmpsql2, $db))
				die("<tr class=\"errorrow\"><td>Could not connect to the database.".mysql_error());
			if(!$tmprow2=mysql_fetch_array($tmpresult2))
				$subject=$l_notsubj;
			else
				$subject=undo_htmlspecialchars($tmprow2["text"]);
			$subject=str_replace("{sitename}",$simpnewssitename,$subject);
			$subject=str_replace("{ptype}","News",$subject);
			$subject=strip_tags($subject);
			$tmpsql2="select * from ".$tableprefix."_texts where lang='".$tmprow["language"]."' and textid='notmsg'";
			if(!$tmpresult2 = mysql_query($tmpsql2, $db))
				die("<tr class=\"errorrow\"><td>Could not connect to the database.".mysql_error());
			if(!$tmprow2=mysql_fetch_array($tmpresult2))
				$mailmsg=$l_notmsg;
			else
				$mailmsg=undo_htmlspecialchars($tmprow2["text"]);
			$mailmsg=str_replace("{sitename}",$simpnewssitename,$mailmsg);
			$mailmsg=str_replace("{ptype}","Events",$mailmsg);
			$mailmsg=str_replace("{category}",$catname,$mailmsg);
			$mailmsg=str_replace("{linkurl}","<a href=\"$linkurl\">$linkurl</a>",$mailmsg);
			$mailmsg=str_replace("{postername}",$userdata["name"],$mailmsg);
			$mailmsg=str_replace("{postermail}",$userdata["email"],$mailmsg);
			$mailmsg_asc=str_replace("<BR>","\r\n",$mailmsg);
			$mailmsg_asc=strip_tags($mailmsg_asc);
			$mail = new htmlMimeMail();
			$mail->setCrlf($crlf);
			$mail->setTextWrap($mailmaxlinelength);
			$mail->setHTMLCharset($contentcharset);
			$mail->setTextCharset($contentcharset);
			$mail->setHTML($mailmsg,$mailmsg_asc);
			$mail->setSubject($subject);
			if($simpnewsmailname)
				$fromadr="\"$simpnewsmailname\" <$simpnewsmail>";
			else
				$fromadr=$simpnewsmail;
			$mail->setFrom($fromadr);
			$receiver=array($tmprow["email"]);
			if(!$insafemode)
				@set_time_limit($msendlimit);
			if($use_smtpmail)
			{
				$mail->setSMTPParams($smtpserver,$smtpport,NULL,$smtpauth,$smtpuser,$smtppasswd);
				$sendresult=$mail->send($receiver, "smtp");
			}
			else
				$sendresult=$mail->send($receiver, "mail");
			do_emaillog($sendresult,$tmprow["email"],"new event proposal 2");
		}
	}
	include_once("./includes/head.inc");
?>
<tr bgcolor="<?php echo $headingbgcolor?>" align="center">
<td colspan="2"><font face="<?php echo $headingfont?>" size="<?php echo $headingfontsize?>" color="<?php echo $headingfontcolor?>">
<?php echo $l_propose_event?></font></td></tr>
<?php
	echo "<tr bgcolor=\"$contentbgcolor\" align=\"center\">";
	echo "<td align=\"center\" colspan=\"2\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
	echo "$l_entryupdated</td></tr>";
	$linkurl="$act_script_url?$langvar=$act_lang&mode=edlst&layout=$layout";
	if(isset($backurl))
		$linkurl.="&backurl=".urlencode($backurl);
	echo "<tr bgcolor=\"$headingbgcolor\" align=\"center\">";
	echo "<td align=\"center\" colspan=\"2\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$headingfontcolor\">";
	echo "<a class=\"actionlink\" href=\"$linkurl\">";
	echo "$l_editownproposals</a></font></td></tr>";
}
?>