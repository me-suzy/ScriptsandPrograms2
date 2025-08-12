<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require_once('./config.php');
require_once('./functions.php');
if(!isset($$langvar) || !$$langvar)
	$act_lang=$default_lang;
else
	$act_lang=$$langvar;
include_once('./includes/get_settings.inc');
require_once('./includes/block_leacher.inc');
include_once('./includes/htmlMimeMail.inc');
if($use_smtpmail)
{
	include_once('./includes/smtp.inc');
	include_once('./includes/RFC822.inc');
}
echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">";
if(!language_avail($act_lang))
	die ("Language <b>$act_lang</b> not configured");
include_once('./language/lang_'.$act_lang.'.php');
if($blockoldbrowser==1)
{
	if(is_ns3() || is_msie3())
	{
		$sql="select * from ".$tableprefix."_texts where textid='oldbrowser' and lang='$act_lang'";
		if(!$result = mysql_query($sql, $db))
		    die("Could not connect to the database.");
		if($myrow = mysql_fetch_array($result))
			echo undo_htmlspecialchars($myrow["text"]);
		else
			echo $l_oldbrowser;
		exit;
	}
}
if($allowusercomments!=1)
{
	die($l_disallowed);
}
if(!isset($mode) || !isset($prog) || !isset($faqnr) || !isset($catnr))
	die($l_callingerror);
if((@fopen("./config.php", "a")) && !$noseccheck)
	die($l_config_writeable);
?>
<html>
<head>
<meta name="generator" content="FAQEngine v<?php echo $faqeversion?>, <?php echo $copyright_asc?>">
<meta name="fid" content="022a9b32a909bf2b875da24f0c8f1225">
<?php
if(is_ns4() && $ns4style)
	echo"<link rel=stylesheet href=\"$ns4style\" type=\"text/css\">\n";
else if(is_ns6() && $ns6style)
	echo"<link rel=stylesheet href=\"$ns6style\" type=\"text/css\">\n";
else if(is_opera() && $operastyle)
	echo"<link rel=stylesheet href=\"$operastyle\" type=\"text/css\">\n";
else if(is_konqueror() && $konquerorstyle)
	echo"<link rel=stylesheet href=\"$konquerorstyle\" type=\"text/css\">\n";
else if(is_gecko() && $geckostyle)
	echo"<link rel=stylesheet href=\"$geckostyle\" type=\"text/css\">\n";
else if($stylesheet)
	echo"<link rel=stylesheet href=\"$stylesheet\" type=\"text/css\">\n";
include_once('./includes/styles.inc');
if(file_exists("./metadata.php"))
	include ("./metadata.php");
else
{
?>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $contentcharset?>">
<title><?php echo $l_heading?></title>
<?php
}
?>
<script type="text/javascript" language="JavaScript" src="./js/emailcheck.js"></script>
<script type="text/javascript" language="JavaScript">
<!--
function checkform()
{
	var mincommentlength=<?php echo $mincommentlength?>;
	if(document.commentform.email.value.length<1)
	{
		alert("<?php echo undo_htmlentities($l_noemail)?>");
		return false;
	}
	if(!emailCheck(document.commentform.email.value))
	{
		alert("<?php echo undo_htmlentities($l_invalidemail)?>");
		return false;
	}
	if(document.commentform.comment.value.length<1)
	{
		alert("<?php echo undo_htmlentities($l_nocomment)?>");
		return false;
	}
<?php
if($mincommentlength>0)
{
?>
	if(document.commentform.comment.value.length<mincommentlength)
	{
		alert("<?php echo undo_htmlentities("$l_shortcomment $l_minlength $mincommentlength $l_characters")?>");
		return false;
	}
<?php
}
?>
	return true;
}
//  End -->
</script>
</head>
<body bgcolor="<?php echo $page_bgcolor?>" link="<?php echo $LinkColor?>" vlink="<?php echo $VLinkColor?>" alink="<?php echo $ALinkColor?>" text="<?php echo $FontColor?>" <?php echo $addbodytags?>>
<?php
if($usecustomheader==1)
{
	echo "<div style=\"clear:both\">";
	if(($headerfile) && ($headerfilepos==0))
	{
		if(is_phpfile($headerfile))
			include($headerfile);
		else
			file_output($headerfile);
	}
	echo $pageheader;
	if(($headerfile) && ($headerfilepos==1))
	{
		if(is_phpfile($headerfile))
			include($headerfile);
		else
			file_output($headerfile);
	}
	echo "</div>";
}
?>
<div align="<?php echo $tblalign?>" style="clear:both">
<table width="<?php echo $TableWidth?>" border="0" CELLPADDING="1" CELLSPACING="0" ALIGN="<?php echo $tblalign?>" VALIGN="TOP">
<tr><TD BGCOLOR="<?php echo $table_bgcolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<TR BGCOLOR="<?php echo $heading_bgcolor?>" ALIGN="CENTER">
<TD ALIGN="CENTER" VALIGN="MIDDLE" colspan="2"><a name="#top">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize3?>; color: <?php echo $HeadingFontColor?>; font-weight: bold;">
<?php echo $l_heading?></span></a></td>
<?php
$sql = "select * from ".$tableprefix."_misc";
if(!$result = faqe_db_query($sql, $db)) {
    die("<tr bgcolor=\"$heading_bgcolor\" align=\"center\"><td>Could not connect to the database.");
}
if ($myrow = faqe_db_fetch_array($result))
{
	if($myrow["shutdown"]==1)
	{
?>
</tr></table></td></tr>
<tr><TD BGCOLOR="<?php echo $table_bgcolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<tr BGCOLOR="<?php echo $row_bgcolor?>" ALIGN="CENTER">
<TD ALIGN="CENTER" VALIGN="MIDDLE">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize2?>; color: <?php echo $FontColor?>;">
<?php
		$shutdowntext=stripslashes($myrow["shutdowntext"]);
		$shutdowntext = undo_htmlspecialchars($shutdowntext);
		echo $shutdowntext;
		echo "</span></td></tr></table></td></tr></table>";
		include('./includes/global.inc');
		exit;
	}
}
$heading=$l_usercomments;
if($mode=="write")
	$heading=$l_writecomment;
if(!isset($navframe))
	$navframe=0;
?>
<tr BGCOLOR="<?php echo $subheadingbgcolor?>" ALIGN="CENTER">
<TD ALIGN="CENTER" VALIGN="MIDDLE" width="95%">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize2?>; color: <?php echo $SubheadingFontColor?>; font-weight: bold;">
<?php echo $heading?></span>
</td>
<td align="center" valign="MIDDLE" width="5%">
<?php
if($navframe==1)
	$linkurl=$url_faqengine."/faqframe.php";
else
	$linkurl=$url_faqengine."/faq.php";
$linkurl.="?display=faq&amp;faqnr=$faqnr&amp;catnr=$catnr&amp;prog=$prog&amp;$langvar=$act_lang";
if(isset($onlynewfaq))
	$linkurl.="&amp;onlynewfaq=$onlynewfaq";
if(isset($limitprog))
	$linkurl.="&amp;limitprog=$limitprog";
if(isset($layout))
	$linkurl.="&amp;layout=$layout";
echo "<a class=\"mainaction\" href=\"$linkurl\"";
if($navframe==1)
	echo " target=\"_parent\"";
echo ">";
if($backpic)
	echo "<img src=\"$backpic\" border=\"0\" title=\"$l_faqlink\" alt=\"$l_faqlink\"></a>";
else
{
	echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $HeadingFontColor;\">";
	echo "[$l_back]</span></a> ";
}
?>
</td>
</tr></table></td></tr>
<tr><TD BGCOLOR="<?php echo $table_bgcolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<?php
if($mode=="display")
{
	$sql = "select * from ".$tableprefix."_comments where faqnr = $faqnr";
	if(!$result = faqe_db_query($sql, $db)) {
	    die("<tr bgcolor=\"$heading_bgcolor\" align=\"center\"><td>Could not connect to the database.");
	}
	if(!$myrow=faqe_db_fetch_array($result))
	{
		echo "<tr bgcolor=\"$group_bgcolor\" align=\"center\"><td>";
		echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
		echo "$l_noentries</span></td></tr>";
	}
	else
	{
		$tempsql = "select dat.heading, prog.programmname, cat.categoryname  from ".$tableprefix."_data dat, ".$tableprefix."_category cat, ".$tableprefix."_programm prog where ";
		$tempsql .="dat.category=cat.catnr and prog.prognr=cat.programm and dat.faqnr='$faqnr'";
		if(!$tempresult = faqe_db_query($tempsql, $db)) {
		    die("<tr bgcolor=\"$heading_bgcolor\" align=\"center\"><td>Could not connect to the database.");
		}
		if($temprow=faqe_db_fetch_array($tempresult))
		{
			echo "<tr bgcolor=\"$subheadingbgcolor\" align=\"center\"><td align=\"center\" colspan=\"2\">";
			echo "<span style=\"font-face: $FontFace; font-size: $FontSize2; color: $SubheadingFontColor; font-weight: bold;\">";
			echo display_encoded($temprow["programmname"]).":".display_encoded($temprow["categoryname"]).":".undo_html_ampersand(stripslashes($temprow["heading"]));
			echo "</span></td></tr>";
		}
		do{
			$updatesql = "update ".$tableprefix."_comments set views = views+1 where commentnr = ".$myrow["commentnr"];
			if(!$updateresult=faqe_db_query($updatesql, $db))
			    die("<tr bgcolor=\"$heading_bgcolor\" align=\"center\"><td>Could not connect to the database.");
			list($mydate,$mytime)=explode(" ",$myrow["postdate"]);
			list($year, $month, $day) = explode("-", $mydate);
			list($hour, $min, $sec) = explode(":",$mytime);
			if($month>0)
				$displaydate=date($dateformat,mktime($hour,$min,$sec,$month,$day,$year));
			else
				$displaydate="";
			$commenttext=display_encoded($myrow["comment"]);
			$commenttext = str_replace("\n", "<BR>", $commenttext);
			$email=emailencode(stripslashes($myrow["email"]));
			$commenthead="<a href=\"mailto:".$email."\">".$email."</a> $l_on $displaydate:";
			echo "<tr bgcolor=\"$row_bgcolor\" align=\"center\"><td align=\"left\" colspan=\"2\">";
			echo "<span style=\"font-face: $FontFace; font-size: $FontSize5; color: $FontColor;\">";
			echo $commenthead;
			echo "</span><br>";
			echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
			echo $commenttext;
			echo "</span></td></tr>";
			if($ratecomments==1)
			{
				echo "<TR BGCOLOR=\"$row_bgcolor\" ALIGN=\"LEFT\" valign=\"middle\">";
				echo "<form action=\"$act_script_url\" method=\"post\"><td colspan=\"2\" valign=\"top\">";
				if(isset($onlynewfaq))
					echo "<input type=\"hidden\" name=\"onlynewfaq\" value=\"$onlynewfaq\">";
				if(isset($limitprog))
					echo "<input type=\"hidden\" name=\"limitprog\" value=\"$limitprog\">";
				if($navframe==1)
					echo "<input type=\"hidden\" name=\"navframe\" value=\"1\">";
				if(isset($layout))
					echo "<input type=\"hidden\" name=\"layout\" value=\"$layout\">";
				echo "<input type=\"hidden\" name=\"mode\" value=\"rate\" alt=\"mode\">";
				echo "<input type=\"hidden\" name=\"$langvar\" value=\"$act_lang\" alt=\"$langvar\">";
				echo "<input type=\"hidden\" name=\"prog\" value=\"$prog\" alt=\"prog\">";
				echo "<input type=\"hidden\" name=\"catnr\" value=\"$catnr\" alt=\"catnr\">";
				echo "<input type=\"hidden\" name=\"faqnr\" value=\"$faqnr\" alt=\"faqnr\">";
				echo "<input type=\"hidden\" name=\"origin\" value=\"display\" alt=\"origin\">";
				echo "<input type=\"hidden\" name=\"commentnr\" value=\"".$myrow["commentnr"]."\" alt=\"commentnr\">";
				echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
				echo "&nbsp;$l_comratingprelude ";
				echo "<select class=\"faqeselect\" name=\"rating\">";
				for($i = 0; $i< count($l_ratings); $i++)
				{
					echo "<option value=\"$i\"";
					if($i==(count($l_ratings)-1))
						echo " selected";
					echo ">".$l_ratings[$i]."</option>";
				}
				echo "</select> ";
				echo "&nbsp;&nbsp;&nbsp;<input class=\"faqebutton\" type=\"submit\" value=\"$l_rate\" alt=\"$l_rate\"></span></td></form></tr>";
			}
			echo "<tr></tr>";
		}while($myrow=faqe_db_fetch_array($result));
	}
	echo "<tr bgcolor=\"$actionbgcolor\"><td align=\"center\" width=\"99%\">";
	echo "<span style=\"font-face: $FontFace; font-size: $actionlinefontsize; color: $FontColor;\">";
	echo "<a class=\"actionline\" href=\"$act_script_url?mode=write&amp;$langvar=$act_lang&amp;faqnr=$faqnr&amp;prog=$prog&amp;catnr=$catnr";
	if(isset($onlynewfaq))
		echo "&amp;onlynewfaq=$onlynewfaq";
	if(isset($limitprog))
		echo "&amp;limitprog=$limitprog";
	if($navframe==1)
		echo "&amp;navframe=1";
	if(isset($layout))
		echo "&amp;layout=$layout";
	echo "\">";
	echo "$l_writecomment</a></span></td>";
	echo "<td align=\"right\" width=\"1%\"><a href=\"#top\"><span style=\"font-face: $FontFace; font-size: $actionlinefontsize;\"><img src=\"$pagetoppic\" border=\"0\" align=\"middle\" alt=\"$l_top\"></span></a></td>";
	echo "</tr>";
}
if($mode=="rate")
{
	$sql = "UPDATE ".$tableprefix."_comments set rating=rating+$rating, ratingcount=ratingcount+1 where commentnr='$commentnr'";
	if(!$result = faqe_db_query($sql, $db)) {
	    die("<tr bgcolor=\"$heading_bgcolor\" align=\"center\"><td>Could not connect to the database.");
	}
	echo "<tr bgcolor=\"$group_bgcolor\" align=\"center\"><td align=\"center\">";
	echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
	echo "$l_commentrated";
	echo "</span></td></tr>";
	if($origin=="display")
		$backlink="<a class=\"actionline\" href=\"$act_script_url?mode=display&amp;$langvar=$act_lang&amp;prog=$prog&amp;catnr=$catnr&amp;faqnr=$faqnr";
	else
		$backlink="<a class=\"actionline\" href=\"$act_script_url?mode=read&amp;$langvar=$act_lang&amp;prog=$prog&amp;catnr=$catnr&amp;faqnr=$faqnr&amp;commentnr=$commentnr";
	if(isset($onlynewfaq))
		$backlink.="&amp;onlynewfaq=$onlynewfaq";
	if(isset($limitprog))
		$backlink.="&amp;limitprog=$limitprog";
	if($navframe==1)
		$backlink.="&amp;navframe=1";
	if(isset($layout))
		$backlink.="&amp;layout=$layout";
	$backlink.="\">";
	echo "<tr bgcolor=\"$actionbgcolor\" align=\"center\"><td align=\"center\">";
	echo "<span style=\"font-face: $FontFace; font-size: $actionlinefontsize; color: $FontColor\">";
	echo $backlink;
	echo $l_back;
	echo "</a></span></td></tr>";
}
if($mode=="read")
{
	$sql = "select * from ".$tableprefix."_comments where commentnr = $commentnr";
	if(!$result = faqe_db_query($sql, $db)) {
	    die("<tr bgcolor=\"$heading_bgcolor\" align=\"center\"><td>Could not connect to the database.");
	}
	if(!$myrow=faqe_db_fetch_array($result))
	{
		echo "<tr bgcolor=\"$group_bgcolor\" align=\"center\"><td>";
		echo "<span style=\"font-face: $FontFace; font-size: $FontSize5; color: $FontColor;\">";
		echo "$l_noentries</span></td></tr>";
	}
	else
	{
		$tempsql = "select dat.heading, prog.programmname, cat.categoryname  from ".$tableprefix."_data dat, ".$tableprefix."_category cat, ".$tableprefix."_programm prog where ";
		$tempsql .="dat.category=cat.catnr and prog.prognr=cat.programm and dat.faqnr=".$myrow["faqnr"];
		if(!$tempresult = faqe_db_query($tempsql, $db)) {
		    die("<tr bgcolor=\"$heading_bgcolor\" align=\"center\"><td>Could not connect to the database.");
		}
		if($temprow=faqe_db_fetch_array($tempresult))
		{
			echo "<tr bgcolor=\"$subheadingbgcolor\" align=\"center\"><td align=\"center\">";
			echo "<span style=\"font-face: $FontFace; font-size: $FontSize2; color: $SubheadingFontColor; font-weight: bold;\">";
			echo display_encoded($temprow["programmname"]).":".display_encoded($temprow["categoryname"]).":".undo_html_ampersand(stripslashes($temprow["heading"]));
			echo "</span></td></tr>";
		}
		$updatesql = "update ".$tableprefix."_comments set views = views+1 where commentnr = ".$myrow["commentnr"];
		if(!$updateresult=faqe_db_query($updatesql, $db))
		    die("<tr bgcolor=\"$heading_bgcolor\" align=\"center\"><td>Could not connect to the database.");
		list($mydate,$mytime)=explode(" ",$myrow["postdate"]);
		list($year, $month, $day) = explode("-", $mydate);
		list($hour, $min, $sec) = explode(":",$mytime);
		if($month>0)
			$displaydate=date($dateformat,mktime($hour,$min,$sec,$month,$day,$year));
		else
			$displaydate="";
		$commenttext=display_encoded($myrow["comment"]);
		if(isset($highlight) && ($highlight))
			$commenttext=highlight_words($commenttext,$highlight);
		$commenttext = str_replace("\n", "<BR>", $commenttext);
		$email=emailencode(stripslashes($myrow["email"]));
		$displaycomment ="<a href=\"mailto:".$email."\">".$email."</a> $l_on $displaydate:<br>";
		$displaycomment .=$commenttext;
		echo "<tr bgcolor=\"$row_bgcolor\" align=\"center\"><td align=\"left\">";
		echo "<span style=\"font-face: $FontFace; font-size: $FontSize5; color: $FontColor;\">";
		echo $displaycomment;
		echo "</span></td></tr>";
		if($ratecomments==1)
		{
			echo "<TR BGCOLOR=\"$row_bgcolor\" ALIGN=\"LEFT\" valign=\"middle\">";
			echo "<form action=\"$act_script_url\" method=\"post\"><td colspan=\"2\" valign=\"middle\">";
			if(isset($onlynewfaq))
				echo "<input type=\"hidden\ name=\"onlynewfaq\" value=\"$onlynewfaq\">";
			if(isset($limitprog))
				echo "<input type=\"hidden\" name=\"limitprog\" value=\"$limitprog\">";
			if($navframe==1)
				echo "<input type=\"hidden\" name=\"navframe\" value=\"1\">";
			if(isset($layout))
				echo "<input type=\"hidden\" name=\"layout\" value=\"$layout\">";
			echo "<input type=\"hidden\" name=\"mode\" value=\"rate\" alt=\"mode\">";
			echo "<input type=\"hidden\" name=\"$langvar\" value=\"$act_lang\" alt=\"$langvar\">";
			echo "<input type=\"hidden\" name=\"prog\" value=\"$prog\" alt=\"prog\">";
			echo "<input type=\"hidden\" name=\"catnr\" value=\"$catnr\" alt=\"catnr\">";
			echo "<input type=\"hidden\" name=\"faqnr\" value=\"$faqnr\" alt=\"faqnr\">";
			echo "<input type=\"hidden\" name=\"origin\" value=\"read\" alt=\"origin\">";
			echo "<input type=\"hidden\" name=\"commentnr\" value=\"".$myrow["commentnr"]."\" alt=\"commentnr\">";
			echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
			echo "$l_ratingprelude ";
			echo "<select class=\"faqeselect\" name=\"rating\">";
			for($i = 0; $i< count($l_ratings); $i++)
			{
				echo "<option value=\"$i\"";
				if($i==(count($l_ratings)-1))
					echo " selected";
				echo ">".$l_ratings[$i]."</option>";
			}
			echo "</select> ";
			echo "&nbsp;&nbsp;&nbsp;<input class=\"faqebutton\" type=\"submit\" value=\"$l_rate\" alt=\"$l_rate\"></span></td></form></tr>";
		}
		echo "<tr></tr>";
	}
	echo "<tr bgcolor=\"$actionbgcolor\"><td align=\"center\">";
	echo "<span style=\"font-face: $FontFace; font-size: $actionlinefontsize; color: $FontColor;\">";
	echo "<a class=\"actionline\" href=\"$act_script_url?mode=write&amp;$langvar=$act_lang&amp;faqnr=$faqnr&amp;prog=$prog&amp;catnr=$catnr";
	if(isset($onlynewfaq))
		echo "&amp;onlynewfaq=$onlynewfaq";
	if(isset($limitprog))
		echo "&amp;limitprog=$limitprog";
	if($navframe==1)
		echo "&amp;navframe=1";
	if(isset($layout))
		echo "&amp;layout=$layout";
	echo "\">";
	echo "$l_writecomment</a></span></td></tr>";
}
if($mode=="post")
{
	$errors=0;
	if((!$email) || (!validate_email($email)))
	{
		echo "<tr bgcolor=\"$group_bgcolor\" align=\"center\"><td>";
		echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
		echo "$l_invalidemail</span></td></tr>";
		$errors=1;
	}
	if(!$comment)
	{
		echo "<tr bgcolor=\"$group_bgcolor\" align=\"center\"><td>";
		echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
		echo "$l_nocomment</span></td></tr>";
		$errors=1;
	}
	else if(strlen($comment)<$mincommentlength)
	{
		echo "<tr bgcolor=\"$group_bgcolor\" align=\"center\"><td>";
		echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
		echo "$l_shortcomment<br>$l_minlength $mincommentlength $l_characters.<br>($l_entered: ".strlen($comment).")</span></td></tr>";
		$errors=1;
	}
	if($errors==0)
	{
		$actdate = date("Y-m-d H:i:s");
		$displaydate=date($dateformat);
		$comment=strip_tags($comment);
		if($usebwlist==1)
			$comment=censor_bad_words($comment,$badwordprefix,$db);
		$sql = "INSERT INTO ".$tableprefix."_comments (faqnr, email, comment, ipadr, postdate) ";
		$sql .="values ($faqnr, '$email', '$comment', '$REMOTE_ADDR', '$actdate')";
		if(!$result = faqe_db_query($sql, $db)) {
		    die("<tr bgcolor=\"$heading_bgcolor\" align=\"center\"><td>Could not connect to the database.");
		}
		if($newcommentnotify==1)
		{
			$sql = "select u.email from ".$tableprefix."_admins u, ".$tableprefix."_category cat, ".$tableprefix."_programm_admins pa, ".$tableprefix."_data dat ";
			$sql.=" where (dat.faqnr='$faqnr' and cat.catnr=dat.category and pa.prognr=cat.programm and u.usernr=pa.usernr)";
			if($nosunotify==0)
				$sql.=" or (u.rights>2)";
			$sql.=" group by u.usernr";
			if(!$result = faqe_db_query($sql, $db)) {
			    db_die("<tr bgcolor=\"$heading_bgcolor\" align=\"center\"><td>Could not connect to the database.");
			}
			if($myrow=faqe_db_fetch_array($result))
			{
				do{
					if(strlen($myrow["email"])>1)
					{
						include("./language/emails_".$myrow["language"].".php");
						$subject = $l_email_new_com_subject;
						$mailmsg = $l_email_new_com_mail;
						$mailmsg = str_replace("{date}",$displaydate,$mailmsg);
						$mailmsg = str_replace("{faqnr}",$faqnr,$mailmsg);
						$mail = new htmlMimeMail();
						$mail->setCrlf($crlf);
						$mail->setTextCharset($contentcharset);
						$mail->setText($mailmsg);
						$mail->setSubject($subject);
						$mail->setFrom($faqemail);
						if(!$insafemode)
							@set_time_limit($msendlimit);
						$receiver=array($myrow["email"]);
						if($use_smtpmail)
						{
							$mail->setSMTPParams($smtpserver,$smtpport,NULL,$smtpauth,$smtpuser,$smtppasswd);
							$mail->send($receiver, "smtp");
						}
						else
							$mail->send($receiver, "mail");
					}
				}while($myrow = faqe_db_fetch_array($result));
			}
		}
		echo "<tr bgcolor=\"$group_bgcolor\" align=\"center\"><td>";
		echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
		echo $l_commentposted;
		echo "</span></td></tr></table></td></tr></table>";
	}
	else
	{
		echo "<tr bgcolor=\"$actionbgcolor\" align=\"center\"><td>";
		echo "<span style=\"font-face: $FontFace; font-size: $actionlinefontsize; color: $FontColor;\">";
		echo "<a class=\"actionline\" href=\"javascript:history.back()\">$l_back</a>";
		echo "</span></td></tr></table></td></tr></table>";
	}
}
if($mode=="write")
{
	$sql = "select * from ".$tableprefix."_texts where textid='commpre' and lang='$act_lang'";
	if(!$result = faqe_db_query($sql, $db)) {
	    die("<tr bgcolor=\"$heading_bgcolor\" align=\"center\"><td>Could not connect to the database.");
	}
	if($myrow=faqe_db_fetch_array($result))
	{
		$displaytext=stripslashes($myrow["text"]);
		$displaytext = undo_htmlspecialchars($displaytext);
		echo "<tr bgcolor=\"$group_bgcolor\"><td align=\"center\" colspan=\"2\">";
		echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
		echo $displaytext;
		echo "</span></td></tr>\n";
	}
	echo "<form name=\"commentform\" onsubmit=\"return checkform();\" method=\"post\" action=\"$act_script_url\">";
	if(isset($limitprog))
		echo "<input type=\"hidden\" name=\"limitprog\" value=\"$limitprog\">";
	if($navframe==1)
		echo "<input type=\"hidden\" name=\"navframe\" value=\"1\">";
	if(isset($onlynewfaq))
		echo "<input type=\"hidden\" name=\"onlynewfaq\" value=\"$onlynewfaq\">";
	if(isset($layout))
		echo "<input type=\"hidden\" name=\"layout\" value=\"$layout\">";
	if(is_konqueror())
		echo "<tr><td></td></tr>";
?>
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="faqnr" value="<?php echo $faqnr?>">
<input type="hidden" name="prog" value="<?php echo $prog?>">
<input type="hidden" name="catnr" value="<?php echo $catnr?>">
<input type="hidden" name="mode" value="post">
<tr BGCOLOR="<?php echo $row_bgcolor?>" ALIGN="CENTER">
<TD ALIGN="right" width="30%">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>; color: <?php echo $FontColor?>; font-weight: bold;">
<?php echo $l_sendermail?>:</span></td>
<td align="left" width="70%">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>; color: <?php echo $FontColor?>;">
<input class="faqeinput" type="text" name="email" size="30" maxlength="140"></span></td></tr>
<tr BGCOLOR="<?php echo $row_bgcolor?>" ALIGN="CENTER">
<TD ALIGN="right" width="30%" valign="top">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>; color: <?php echo $FontColor?>; font-weight: bold;">
<?php echo $l_comment?>:</span>
<?php
if($mincommentlength>0)
	echo "<br><span style=\"font-face: $FontFace; font-size: $FontSize4; color: $FontColor;\">($l_min $mincommentlength $l_characters)</span>";
?>
</td>
<td align="left" width="70%">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>; color: <?php echo $FontColor?>;">
<textarea class="faqeinput" name="comment" cols="<?php echo $textareacols?>" rows="<?php echo $textarearows?>"></textarea>
</span></td></tr>
<tr bgcolor="<?php echo $actionbgcolor?>"><td colspan="2" align="center">
<font face="<?php echo $FontFace?>" size="<?php echo $FontSize2?>" color="<?php echo $FontColor?>">
<input class="faqebutton" type="submit" name="submit" value="<?php echo $l_submit?>"></font></td></tr>
</form>
<?php
}
echo "</table></td></tr></table></div>";
include_once('./includes/bottom.inc');
?>