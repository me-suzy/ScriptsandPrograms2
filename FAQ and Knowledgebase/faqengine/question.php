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
if(!isset($type))
	$type="";
if(!isset($navframe))
	$navframe=0;
include_once('./includes/htmlMimeMail.inc');
if($use_smtpmail)
{
	include_once('./includes/smtp.inc');
	include_once('./includes/RFC822.inc');
}
$cookieexpire=time()+(365*24*60*60);
$cookiedata="";
if($new_global_handling)
{
	if(isset($_COOKIE[$cookiename]))
		$cookiedata=$_COOKIE[$cookiename];
}
else
{
	if(isset($_COOKIE[$cookiename]))
		$cookiedata=$_COOKIE[$cookiename];
}
if($cookiedata && !isset($submit))
{
	if(faqe_array_key_exists($cookiedata,"email"))
		$cookieemail=$cookiedata["email"];
	if(isset($submit) && !isset($storedata))
		setcookie($cookiename."[email]","",time()-(24*60*60),$cookiepath,$cookiedomain,$cookiesecure);
}
if(isset($submit) && isset($storedata))
{
	setcookie($cookiename."[email]",$email,$cookieexpire,$cookiepath,$cookiedomain,$cookiesecure);
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<?php
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
if((@fopen("./config.php", "a")) && !$noseccheck)
{
	die($l_config_writeable);
}
if($allowquestions!=1)
{
	die($l_disallowed);
}
if(isset($prog) && ($prog) && !isset($newprog))
	$newprog=$prog;
?>
<html>
<head>
<meta name="generator" content="FAQEngine v<?php echo $faqeversion?>, <?php echo $copyright_asc?>">
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
	var minquestionlength=<?php echo $minquestionlength?>;
<?php
if($uq_allownoemail==0)
{
?>
	if(document.questionform.email.value.length<1)
	{
		alert("<?php echo undo_htmlentities($l_noemail)?>");
		return false;
	}
	if(!emailCheck(document.questionform.email.value))
	{
		alert("<?php echo undo_htmlentities($l_invalidemail)?>");
		return false;
	}
<?php
}
?>
	if(document.questionform.question.value.length<1)
	{
		alert("<?php echo undo_htmlentities($l_noquestion)?>");
		return false;
	}
<?php
if($minquestionlength>0)
{
?>
	if(document.questionform.question.value.length<minquestionlength)
	{
		alert("<?php echo undo_htmlentities("$l_shortquestion $l_minlength $minquestionlength $l_characters")?>");
		return false;
	}
<?php
}
if($questionrequireos==1)
{
?>
	if(document.questionform.osname.value.length<1)
	{
		alert("<?php echo undo_htmlentities($l_noos)?>");
		return false;
	}
<?php
}
if($questionrequireversion==1)
{
?>
	if(document.questionform.usedversion.value.length<1)
	{
		alert("<?php echo undo_htmlentities($l_noversion)?>");
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
<TD class="mainheading" ALIGN="CENTER" VALIGN="MIDDLE" colspan="2"><a name="#top">
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
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>; color: <?php echo $FontColor?>;">
<?php
		$shutdowntext=stripslashes($myrow["shutdowntext"]);
		$shutdowntext = undo_htmlspecialchars($shutdowntext);
		echo $shutdowntext;
		echo "</span></td></tr></table></td></tr></table></div>";
		include_once('./includes/bottom.inc');
		exit;
	}
}
$heading=$l_userquestions;
if(isset($mode))
{
	if($mode=="display")
		$heading=$l_displayquestions;
	else if($mode=="read")
		$heading=$l_displayquestions;
}
else
{
	if($type=="followup")
		$heading=$l_write_followup;
	else
		$heading=$l_askquestion;
}
?>
<tr BGCOLOR="<?php echo $subheadingbgcolor?>" ALIGN="CENTER">
<TD class=\"subheading\" ALIGN="CENTER" VALIGN="MIDDLE" width="95%">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize2?>; color: <?php echo $SubheadingFontColor?>; font-weight: bold">
<?php echo $heading?></span>
</td>
<td align="center" valign="MIDDLE" width="5%">
<?php
if(isset($backurl))
{
	echo "<a class=\"backurl\" href=\"".$backurl."\"";
	if($navframe==1)
		echo " target=\"_parent\"";
	echo ">";
}
else if(isset($faqnr) && ($faqnr>0))
{
	if($navframe==1)
		$linkurl=$url_faqengine."/faqframe.php";
	else
		$linkurl=$url_faqengine."/faq.php";
	echo "<a class=\"backurl\" href=\"$linkurl?display=faq&amp;faqnr=$faqnr&amp;catnr=$catnr&amp;prog=$prog&amp;$langvar=$act_lang";
	if(isset($onlynewfaq))
		echo "&amp;onlynewfaq=$onlynewfaq";
	if(isset($limitprog))
		echo "&amp;limitprog=$limitprog";
	if(isset($layout))
		echo "&amp;layout=$layout";
	echo "\"";
	if($navframe==1)
		echo " target=\"_parent\"";
	echo ">";
}
else
{
	$faqnr=0;
	if(isset($prog))
	{
		if($navframe==1)
			$linkurl=$url_faqengine."/faqframe.php";
		else
			$linkurl=$url_faqengine."/faq.php";
		echo "<a class=\"mainaction\" href=\"$linkurl?list=all&amp;prog=$prog&amp;$langvar=$act_lang";
		if(isset($onlynewfaq))
			echo "&amp;onlynewfaq=$onlynewfaq";
		if(isset($limitprog))
			echo "&amp;limitprog=$limitprog";
		if(isset($layout))
			echo "&amp;layout=$layout";
		echo "\"";
		if($navframe==1)
			echo " target=\"_parent\"";
		echo ">";
	}
	else
	{
		if($navframe==1)
			$linkurl=$url_faqengine."/faqframe.php";
		else
			$linkurl=$url_faqengine."/faq.php";
		echo "<a class=\"mainaction\" href=\"$linkurl?list=progs&amp;$langvar=$act_lang";
		if(isset($onlynewfaq))
			echo "&amp;onlynewfaq=$onlynewfaq";
		if(isset($limitprog))
			echo "&amp;limitprog=$limitprog";
		if(isset($layout))
			echo "&amp;layout=$layout";
		echo "\"";
		if($navframe==1)
			echo " target=\"_parent\"";
		echo ">";
	}
}
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
if(isset($mode))
{
	if($mode=="read")
	{
		echo "</table></td></tr>";
		echo "<tr><TD BGCOLOR=\"$table_bgcolor\">";
		echo "<TABLE BORDER=\"0\" CELLPADDING=\"$tablepadding\" CELLSPACING=\"$tablespacing\" WIDTH=\"100%\">";
		$sql = "select * from ".$tableprefix."_questions where questionnr='$question'";
		if(!$result = faqe_db_query($sql, $db))
		{
			echo "<tr><td bgcolor=\"$heading_bgcolor\">";
	    		die("Could not connect to the database.");
	    	}
		if (!$myrow = faqe_db_fetch_array($result))
		{
			echo "<tr bgcolor=\"$group_bgcolor\" align=\"center\"><td>";
			echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
			echo "$l_noentries</span></td></tr>";
		}
		else
		{
			list($mydate,$mytime)=explode(" ",$myrow["enterdate"]);
			list($year, $month, $day) = explode("-", $mydate);
			list($hour, $min, $sec) = explode(":",$mytime);
			if($month>0)
				$displaydate=date($dateformat,mktime($hour,$min,$sec,$month,$day,$year));
			else
			$displaydate="";
			$questiontext=display_encoded($myrow["question"]);
			if(isset($highlight) && ($highlight))
				$questiontext=highlight_words($questiontext,$highlight);
			$questiontext=str_replace("\n","<BR>",$questiontext);
			echo "<tr bgcolor=\"$group_bgcolor\">";
			echo "<td class=\"posterline\" colspan=\"2\">";
			echo "<span style=\"font-face: $FontFace; font-size: $FontSize5; color; $FontColor;\">";
			$tmpemail=emailencode(stripslashes($myrow["email"]));
			echo "<b>".$tmpemail."</b> $l_on $displaydate</span>";
			echo "<tr bgcolor=\"$row_bgcolor\">";
			echo "<td class=\"question\" width=\"20%\" valign=\"top\">";
			echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor; font-weight: bold;\">";
			echo "$l_question:";
			echo "</span></td>";
			echo "<td class=\"question\" width=\"80%\">";
			echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
			echo "$questiontext</span></td>";
			echo "</tr>";
			if($myrow["answerauthor"]>0)
			{
				list($mydate,$mytime)=explode(" ",$myrow["answerdate"]);
				list($year, $month, $day) = explode("-", $mydate);
				list($hour, $min, $sec) = explode(":",$mytime);
				if($month>0)
					$displaydate=date($dateformat,mktime($hour,$min,$sec,$month,$day,$year));
				else
					$displaydate="";
				$answertext=$myrow["answer"]."\n($displaydate)";
				$answertext=display_encoded($answertext);
				if(isset($highlight) && ($highlight))
					$answertext=highlight_words($answertext,$highlight);
				$answertext=str_replace("\n","<BR>",$answertext);
				echo "<tr bgcolor=\"$row_bgcolor\">";
				echo "<td class=\"answer\" width=\"20%\" valign=\"top\">";
				echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor; font-weight: bold;\">";
				echo "$l_answer:";
				echo "</span></td>";
				echo "<td class=\"answer\" width=\"80%\">";
				echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
				echo "$answertext</span></td>";
				echo "</tr>";
			}
			echo "<tr bgcolor=\"$actionbgcolor\"><td colspan=\"2\" align=\"center\">";
			if($myrow["questionref"]!=0)
				$refnr=$myrow["questionref"];
			else
				$refnr=$myrow["questionnr"];
			$link=$url_faqengine."/question.php?$langvar=$act_lang&amp;type=followup&amp;questionref=$refnr&amp;prog=$prog";
			if($myrow["faqref"]!=0)
			{
				$catsql="select * from ".$tableprefix."_data where faqnr=".$myrow["faqref"];
				if(!$catresult = faqe_db_query($catsql, $db))
				{
					echo "<tr><td bgcolor=\"$heading_bgcolor\">";
					die("Could not connect to the database.");
				}
				if($catrow = faqe_db_fetch_array($catresult))
					$link.="&amp;catnr=".$catrow["category"]."&amp;faqnr=".$myrow["faqref"];
			}
			if(isset($backurl))
				$link.="&amp;backurl=".urlencode($backurl);
			if($navframe==1)
				$link.="&amp;navframe=1";
			if(isset($limitprog))
				$link.="&amp;limitprog=$limitprog";
			if(isset($layout))
				$link.="&amp;layout=$layout";
			echo "<span style=\"font-face: $FontFace; font-size: $actionlinefontsize; color: $FontColor;\">";
			echo "<a class=\"actionline\" href=\"$link\">";
			echo "$l_followup</a></span></td></tr>";
		}
		echo "</table></td></tr></table></div>";
		include_once('./includes/bottom.inc');
		exit;
	}
	if($mode=="display")
	{
		echo "</table></td></tr>";
		echo "<tr><TD BGCOLOR=\"$table_bgcolor\">";
		echo "<TABLE BORDER=\"0\" CELLPADDING=\"$tablepadding\" CELLSPACING=\"$tablespacing\" WIDTH=\"100%\">";
		if(!isset($questionref))
			$sql = "select * from ".$tableprefix."_questions where faqref=$faqnr and publish=1 and questionref=0 order by enterdate desc";
		else
			$sql = "select * from ".$tableprefix."_questions where publish=1 and questionref=$questionref order by enterdate desc";
		if(!$result = faqe_db_query($sql, $db))
		{
			echo "<tr><td bgcolor=\"$heading_bgcolor\">";
	    		die("Could not connect to the database.");
	    	}
		if (!$myrow = faqe_db_fetch_array($result))
		{
			echo "<tr bgcolor=\"$group_bgcolor\" align=\"center\"><td>";
			echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
			echo "$l_noentries</span></td></tr>";
		}
		else
		{
			do{
				list($mydate,$mytime)=explode(" ",$myrow["enterdate"]);
				list($year, $month, $day) = explode("-", $mydate);
				list($hour, $min, $sec) = explode(":",$mytime);
				if($month>0)
					$displaydate=date($dateformat,mktime($hour,$min,$sec,$month,$day,$year));
				else
					$displaydate="";
				$questiontext=display_encoded($myrow["question"]);
				$questiontext=str_replace("\n","<BR>",$questiontext);
				echo "<tr bgcolor=\"$group_bgcolor\">";
				echo "<td class=\"posterline\" colspan=\"2\">";
				echo "<span style=\"font-face: $FontFace; font-size: $FontSize5; color: $FontColor;\">";
				$tmpemail=emailencode(stripslashes($myrow["email"]));
				echo "<b>".$tmpemail."</b> $l_on $displaydate</span></td></tr>";
				echo "<tr bgcolor=\"$row_bgcolor\">";
				echo "<td class=\"question\" width=\"20%\" valign=\"top\">";
				echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor; font-weight: bold;\">";
				echo "$l_question:";
				echo "</span></td>";
				echo "<td class=\"question\" width=\"80%\">";
				echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
				echo "$questiontext</span></td>";
				echo "</tr>";
				if($myrow["answerauthor"]>0)
				{
					list($mydate,$mytime)=explode(" ",$myrow["answerdate"]);
					list($year, $month, $day) = explode("-", $mydate);
					list($hour, $min, $sec) = explode(":",$mytime);
					if($month>0)
						$displaydate=date($dateformat,mktime($hour,$min,$sec,$month,$day,$year));
					else
						$displaydate="";
					$answertext=$myrow["answer"]."\n($displaydate)";
					$answertext=display_encoded($answertext);
					$answertext=str_replace("\n","<BR>",$answertext);
					echo "<tr bgcolor=\"$row_bgcolor\">";
					echo "<td class=\"answer\" width=\"20%\" valign=\"top\">";
					echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor; font-weight: bold;\">";
					echo "$l_answer:";
					echo "</span></td>";
					echo "<td class=\"answer\" width=\"80%\">";
					echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
					echo "$answertext</span></td>";
					echo "</tr>";
				}
				$tempsql="select * from ".$tableprefix."_questions where questionref=".$myrow["questionnr"]." and publish=1";
				if(!$tempresult = faqe_db_query($tempsql, $db))
				{
					echo "<tr><td bgcolor=\"$heading_bgcolor\">";
    					die("Could not connect to the database.");
    				}
				if ($temprow = faqe_db_fetch_array($tempresult))
				{
					echo "<tr bgcolor=\"$row_bgcolor\"><td class=\"userquestions\" colspan=\"2\">";
					echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
					echo faqe_db_num_rows($tempresult)." $l_followup_questions";
					echo "&nbsp;&nbsp;&nbsp;(<a class=\"userquestions\" href=\"".$url_faqengine."/question.php?prog=$prog&amp;catnr=$catnr&amp;faqnr=$faqnr&amp;mode=display&amp;questionref=".$myrow["questionnr"]."&amp;$langvar=$act_lang";
					if(isset($onlynewfaq))
						echo "&amp;onlynewfaq=$onlynewfaq";
					if(isset($layout))
						echo "&amp;layout=$layout";
					if(isset($limitprog))
						echo "&amp;limitprog=$limitprog";
					if($navframe==1)
						echo "&amp;navframe=1";
					echo "\">$l_display</a>)</span></td></tr>";
				}
				echo "<tr bgcolor=\"$actionbgcolor\"><td colspan=\"2\" align=\"center\">";
				echo "<table width=\"100%\" bgcolor=\"$actionbgcolor\" cellpadding=\"0\" cellspacing=\"0\">";
				echo "<tr><td class=\"userquestions\" align=\"center\" width=\"99%\">";
				if($myrow["questionref"]!=0)
					$refnr=$myrow["questionref"];
				else
					$refnr=$myrow["questionnr"];
				echo "<span style=\"font-face: $FontFace; font-size: $actionlinefontsize; color: $FontColor;\">";
				echo "<a class=\"actionline\" href=\"".$url_faqengine."/question.php?prog=$prog&amp;catnr=$catnr&amp;faqnr=$faqnr&amp;type=followup&amp;$langvar=$act_lang&amp;questionref=$refnr";
				if(isset($layout))
					echo "&amp;layout=$layout";
				if(isset($onlynewfaq))
					echo "&amp;onlynewfaq=$onlynewfaq";
				if(isset($limitprog))
					echo "&amp;limitprog=$limitprog";
				if($navframe==1)
					echo "&amp;navframe=1";
				echo "\">$l_followup</a></span></td>";
?>
<td class="gototop" align="right" width="1%"><a class="gototop" href="#top">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $actionlinefontsize?>;">
<img class="gototop" src="<?php echo $pagetoppic?>" border="0" align="middle" title="<?php echo $l_top?>" alt="<?php echo $l_top?>">
</span></a></td>
<?php
				echo "</tr></table></td></tr>";
			}while($myrow=faqe_db_fetch_array($result));
		}
		echo "</table></td></tr></table></div>";
		include_once('./includes/bottom.inc');
		exit;
	}
	if($mode=="post")
	{
		$errors=0;
		if(($uq_allownoemail==0) && ((!$email) || (!validate_email($email))))
		{
			echo "<tr bgcolor=\"#c0c0c0\" align=\"center\"><td class=\"error\">";
			echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
			echo "$l_invalidemail</span></td></tr>";
			$errors=1;
		}
		if(($questionrequireos==1) && !$osname)
		{
			echo "<tr bgcolor=\"#c0c0c0\" align=\"center\"><td class=\"error\">";
			echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
			echo "$l_noos</span></td></tr>";
			$errors=1;
		}
		if(($questionrequireversion==1) && !$usedversion)
		{
			echo "<tr bgcolor=\"#c0c0c0\" align=\"center\"><td class=\"error\">";
			echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
			echo "$l_noversion</span></td></tr>";
			$errors=1;
		}
		if(!$question)
		{
			echo "<tr bgcolor=\"#c0c0c0\" align=\"center\"><td class=\"error\">";
			echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
			echo "$l_noquestion</span></td></tr>";
			$errors=1;
		}
		else if(strlen($question)<$minquestionlength)
		{
			echo "<tr bgcolor=\"$group_bgcolor\" align=\"center\"><td class=\"error\">";
			echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
			echo "$l_shortquestion<br>$l_minlength $minquestionlength $l_characters.<br>($l_entered: ".strlen($question).")</span></td></tr>";
			$errors=1;
		}
		if(!isset($questionref))
			$questionref=0;
		if($errors==0)
		{
			if(!isset($questionref))
				$questionref=0;
			$actdate = date("Y-m-d H:i:s");
			$question=strip_tags($question);
			$question=addslashes($question);
			if($qesautopub==1)
				$qpublish=1;
			else
				$qpublish=0;
			$sql ="insert into ".$tableprefix."_questions (prognr, osname, versionnr, email, question, enterdate, faqref, posterip, language, questionref, publish) ";
			$sql .="values ($input_prognr, '$osname', '$usedversion', '$email', '$question', '$actdate', $faqnr, '$REMOTE_ADDR', '$act_lang', $questionref, $qpublish)";
			if(!$result = faqe_db_query($sql, $db)) {
				die("<tr bgcolor=\"$heading_bgcolor\" align=\"center\"><td>Could not connect to the database.");
			}
			$questionnr=faqe_db_insert_id($db);
			$sql ="select u.* from ".$tableprefix."_admins u, ".$tableprefix."_programm_admins pa where ((pa.prognr='$input_prognr' and pa.usernr=u.usernr)";
			if($nosunotify==0)
				$sql.=" or (u.rights>2))";
			else
				$sql.=")";
			$sql.=" group by u.usernr";
			if(!$result = faqe_db_query($sql, $db))
				die("<tr bgcolor=\"$heading_bgcolor\" align=\"center\"><td>Could not connect to the database.");
			if ($myrow = faqe_db_fetch_array($result))
			{
				$questionlink = $faqe_fullurl."/admin/userquestions.php?mode=display&input_questionnr=".$questionnr;
				do{
					if(strlen($myrow["email"])>1)
					{
						include("./language/emails_".$myrow["language"].".php");
						$tempsql="select * from ".$tableprefix."_texts where textid='uqsubj' and lang='".$myrow["language"]."'";
						if(!$tempresult = faqe_db_query($tempsql, $db))
							db_die("<tr bgcolor=\"$heading_bgcolor\" align=\"center\"><td>Could not connect to the database.");
						if($temprow=faqe_db_fetch_array($tempresult))
						{
							$subject=undo_htmlentities(stripslashes($temprow["text"]));
							$subject=strip_tags($subject);
						}
						else
							$subject=$l_email_new_userquestion_subject;
						$tempsql="select * from ".$tableprefix."_texts where textid='uqbody' and lang='".$myrow["language"]."'";
						if(!$tempresult = faqe_db_query($tempsql, $db))
							db_die("<tr bgcolor=\"$heading_bgcolor\" align=\"center\"><td>Could not connect to the database.");
						if($temprow=faqe_db_fetch_array($tempresult))
						{
							$asc_mailmsg=undo_htmlentities($temprow["text"]);
							$asc_mailmsg=str_replace("<BR>","\n",$asc_mailmsg);
							$asc_mailmsg=strip_tags($asc_mailmsg);
							$asc_mailmsg=str_replace("{qnr}",$questionnr,$asc_mailmsg);
							$asc_mailmsg=str_replace("{qlink}",$questionlink,$asc_mailmsg);
							$html_mailmsg=$temprow["text"];
							$html_mailmsg=str_replace("{qnr}",$questionnr,$html_mailmsg);
							$html_mailmsg=str_replace("{qlink}","<a href=\"$questionlink\">$questionlink</a>",$html_mailmsg);
						}
						else
						{
							$asc_mailmsg=$l_email_new_userquestion_ascmail;
							$asc_mailmsg=str_replace("{qnr}",$questionnr,$asc_mailmsg);
							$asc_mailmsg=str_replace("{qlink}",$questionlink,$asc_mailmsg);
							$html_mailmsg=$l_email_new_userquestion_htmlmail;
							$html_mailmsg=str_replace("{qnr}",$questionnr,$html_mailmsg);
							$html_mailmsg=str_replace("{qlink}","<a href=\"$questionlink\">$questionlink</a>",$html_mailmsg);
						}
						$mail = new htmlMimeMail();
						$mail->setCrlf($crlf);
						$mail->setTextCharset($contentcharset);
						if($disablehtmlemail==0)
						{
							$mail->setHTMLCharset($contentcharset);
							$mail->setHTML($html_mailmsg, $asc_mailmsg);
						}
						else
							$mail->SetText($asc_mailmsg);
						$mail->setSubject($subject);
						$mail->setFrom($faqemail);
						if(!$insafemode)
							@set_time_limit($msendlimit);
						$receiver=array();
						array_push($receiver,$myrow["email"]);
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
			if(($uqscmail==1) && $email)
			{
				$tempsql="select * from ".$tableprefix."_texts where lang='$act_lang' and textid='nuqb'";
				if(!$tempresult = faqe_db_query($tempsql, $db))
					die("<tr bgcolor=\"$heading_bgcolor\" align=\"center\"><td>Could not connect to the database.");
				if($temprow=faqe_db_fetch_array($tempresult))
					$mailbody=$temprow["text"];
				else
					$mailbody=$l_nuq_body;
				$tempsql="select * from ".$tableprefix."_texts where lang='$act_lang' and textid='nuqs'";
				if(!$tempresult = faqe_db_query($tempsql, $db))
					die("<tr bgcolor=\"$heading_bgcolor\" align=\"center\"><td>Could not connect to the database.");
				if($temprow=faqe_db_fetch_array($tempresult))
					$subject=$temprow["text"];
				else
					$subject=$l_nuq_subject;
				$subject=undo_htmlentities($subject);
				$subject=strip_tags($subject);
				$subject=str_replace("{refid}",$questionnr,$subject);
				$mailbody=str_replace("{refid}",$questionnr,$mailbody);
				$mailbody=str_replace("{date}",date($extdateformat),$mailbody);
				$asc_mailmsg=undo_htmlentities($mailbody);
				$asc_mailmsg=str_replace("<br>",$crlf,$asc_mailmsg);
				$asc_mailmsg=strip_tags($asc_mailmsg);
				$html_mailmsg=$mailbody;
				$mail = new htmlMimeMail();
				$mail->setCrlf($crlf);
				$mail->setTextCharset($contentcharset);
				if($disablehtmlemail==0)
				{
					$mail->setHTMLCharset($contentcharset);
					$mail->setHTML($html_mailmsg, $asc_mailmsg);
				}
				else
					$mail->SetText($asc_mailmsg);
				$mail->setSubject($subject);
				$mail->setFrom($faqemail);
				if(!$insafemode)
					@set_time_limit($msendlimit);
				$receiver=array();
				array_push($receiver,$email);
				if($use_smtpmail)
				{
					$mail->setSMTPParams($smtpserver,$smtpport,NULL,$smtpauth,$smtpuser,$smtppasswd);
					$mail->send($receiver, "smtp");
				}
				else
					$mail->send($receiver, "mail");
			}
			echo "<tr bgcolor=\"$group_bgcolor\" align=\"center\"><td class=\"actionmsg\">";
			echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
			echo $l_questiondone;
			echo "</span></td></tr></table></td></tr></table>";
		}
		else
		{
			echo "<tr bgcolor=\"$actionbgcolor\" align=\"center\"><td class=\"actionline\">";
			echo "<span style=\"font-face: $FontFace; font-size: $actionlinefontsize; color: $FontColor;\">";
			echo "<a class=\"actionline\" href=\"javascript:history.back()\">$l_back</a>";
			echo "</span></td></tr></table></td></tr></table>";
		}
		echo "</div>";
		include_once('./includes/bottom.inc');
		exit;
	}
}
$sql = "select * from ".$tableprefix."_texts where textid='questpre' and lang='$act_lang'";
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
?>
<form name="questionform" onsubmit="return checkform();" method="post" action="<?php echo $act_script_url?>">
<?php
	if(isset($layout))
		echo "<input type=\"hidden\" name=\"layout\" value=\"$layout\">";
	if(isset($onlynewfaq))
		echo "<input type=\"hidden\" name=\"onlynewfaq\" value=\"$onlynewfaq\">";
	if(is_konqueror())
		echo "<tr><td></td></tr>";
	if($navframe==1)
		echo "<input type=\"hidden\" name=\"navframe\" value=\"1\">";
	if(isset($prog) && $prog)
		echo "<input type=\"hidden\" name=\"prog\" value=\"$prog\">";
	if(isset($limitprog))
		echo "<input type=\"hidden\" name=\"limitprog\" value=\"$limitprog\">";
?>
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="faqnr" value="<?php echo $faqnr?>">
<input type="hidden" name="type" value="<?php echo $type?>">
<?php
if($type=="followup")
{
	echo "<input type=\"hidden\" name=\"questionref\" value=\"$questionref\">";
	echo "<input type=\"hidden\" name=\"catnr\" value=\"$catnr\">";
}
?>
<tr BGCOLOR="<?php echo $row_bgcolor?>" ALIGN="CENTER">
<TD ALIGN="right" width="30%" class="questionform">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>; color: <?php echo $FontColor?>; font-weight: bold">
<?php echo $l_progname?>:</span></td>
<td align="left" width="70%" class="questionform">
<?php
$progdefined=false;
if(isset($newprog))
{
	$sql = "select * from ".$tableprefix."_programm where progid='$newprog' and language = '$act_lang'";
	if(!$result = faqe_db_query($sql, $db)) {
	    die("<tr bgcolor=\"$heading_bgcolor\" align=\"center\"><td>Could not connect to the database.");
	}
	if ($myrow = faqe_db_fetch_array($result))
	{
		$input_prognr=$myrow["prognr"];
		echo "<input type=\"hidden\" name=\"input_prognr\" value=\"$input_prognr\">";
		$progdefined=true;
	}
}
if($progdefined)
{
	echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor\">";
	echo display_encoded($myrow["programmname"]);
	echo "</span>";
}
else
{
	$sql = "select * from ".$tableprefix."_programm where language = '$act_lang'";
	if(!$result = faqe_db_query($sql, $db)) {
	    die("Could not connect to the database.");
	}
	if ($myrow = faqe_db_fetch_array($result))
	{
		echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor\">";
		echo "<select class=\"faqeselect\" name=\"newprog\">";
		do{
			echo "<OPTION VALUE=\"".$myrow["progid"]."\" >".display_encoded($myrow["programmname"])."</OPTION>\n";
		} while($myrow = faqe_db_fetch_array($result));
		echo "</select>";
		echo "</span></td></tr>";
		echo "<tr bgcolor=\"$actionbgcolor\"><td class=\"questionform\" colspan=\"2\" align=\"center\"><input class=\"faqebutton\" type=\"submit\" value=\"$l_select\"></td></tr>";
		echo "</form></table></td></tr></table></div>";
		include_once('./includes/bottom.inc');
		exit;
	}
	else
		die ($l_noprogsdefined);
}
?>
</td></tr>
<?php
$sql = "select os.* from ".$tableprefix."_os os, ".$tableprefix."_prog_os po where po.prognr='$input_prognr' and os.osnr=po.osnr order by os.osnr";
if(!$result = faqe_db_query($sql, $db)) {
    die("<tr bgcolor=\"$heading_bgcolor\" align=\"center\"><td>Could not connect to the database.");
}
if ($myrow = faqe_db_fetch_array($result))
{
?>
<tr BGCOLOR="<?php echo $row_bgcolor?>" ALIGN="CENTER">
<TD ALIGN="right" width="30%" class="questionform">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>; color: <?php echo $FontColor?>; font-weight: bold;">
<?php echo $l_os?>:</span></td>
<td align="left" width="70%" class="questionform">
<?php
	echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor\">";
	echo "<select class=\"faqeselect\" name=\"osname\">";
	do{
		echo "<OPTION VALUE=\"".do_htmlentities(stripslashes($myrow["osname"]))."\" >".display_encoded($myrow["osname"])."</OPTION>\n";
	} while($myrow = faqe_db_fetch_array($result));
	echo "</select>";
	echo "</span></td></tr>";
}
else if($questionrequireos==1)
{
?>
<tr BGCOLOR="<?php echo $row_bgcolor?>" ALIGN="CENTER">
<TD ALIGN="right" width="30%" class="questionform">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>; color: <?php echo $FontColor?>; font-weight: bold;">
<?php echo $l_os?>:</span></td>
<td align="left" width="70%" class="questionform">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>; color: <?php echo $FontColor?>;">
<input class="faqeinput" type="text" name="osname" size ="40" maxlength="180"></span>
</td></tr>
<?php
}
else
	echo "<input type=\"hidden\" name=\"osname\" value=\"\">";
if(isset($catnr))
	echo "<input type=\"hidden\" name=\"catnr\" value=\"$catnr\">";
?>
<tr BGCOLOR="<?php echo $row_bgcolor?>" ALIGN="CENTER">
<?php
$sql = "select * from ".$tableprefix."_programm_version where programm='$input_prognr'";
if(!$result = faqe_db_query($sql, $db)) {
    die("<tr bgcolor=\"$heading_bgcolor\" align=\"center\"><td>Could not connect to the database.");
}
if ($myrow = faqe_db_fetch_array($result))
{
?>
<TD ALIGN="right" width="30%" class="questionform">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>; color: <?php echo $FontColor?>; font-weight: bold;">
<?php echo $l_usedversion?>:</span></td>
<td align="left" width="70%" class="questionform">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>; color: <?php echo $FontColor?>;">
<?php
	echo "<select class=\"faqeselect\" name=\"usedversion\">";
	do{
		echo "<OPTION VALUE=\"".$myrow["version"]."\" >".do_htmlentities($myrow["version"])."</OPTION>\n";
	} while($myrow = faqe_db_fetch_array($result));
	echo "</select>";
	echo "</span></td></tr>";
}
else if($questionrequireversion==1)
{
?>
<TD ALIGN="right" width="30%" class="questionform">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>; color: <?php echo $FontColor?>; font-weight: bold;">
<?php echo $l_usedversion?>:</span></td>
<td align="left" width="70%" class="questionform">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>; color: <?php echo $FontColor?>;">
<input class="faqeinput" type="text" name="usedversion" size="10" maxlength="10">
</span></td></tr>
<?php
}
else
	echo "<input type=\"hidden\" name=\"usedversion\" value=\"\">";
?>
<tr BGCOLOR="<?php echo $row_bgcolor?>" ALIGN="CENTER">
<TD ALIGN="right" width="30%" class="questionform">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>; color: <?php echo $FontColor?>; font-weight: bold;">
<?php echo $l_sendermail?>:</span></td>
<td align="left" width="70%" class="questionform">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>; color: <?php echo $FontColor?>;">
<input class="faqeinput" type="text" name="email" size="30" maxlength="140" <?php if(isset($cookieemail)) echo "value=\"$cookieemail\""?>>
</span></td></tr>
<tr BGCOLOR="<?php echo $row_bgcolor?>" ALIGN="CENTER">
<TD ALIGN="right" width="30%" valign="top" class="questionform">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>; color: <?php echo $FontColor?>; font-weight: bold;">
<?php echo $l_question?>:</span>
<?php
if($minquestionlength>0)
	echo "<br><span style=\"font-face: $FontFace; font-size: $FontSize4; color: $FontColor;\">($l_min $minquestionlength $l_characters)</span>";
?>
</td>
<td align="left" width="70%" class="questionform">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>; color: <?php echo $FontColor?>;">
<textarea class="faqeinput" name="question" rows="<?php echo $textarearows?>" cols="<?php echo $textareacols?>" wrap="virtual"></textarea>
</span></td></tr>
<tr bgcolor="<?php echo $row_bgcolor?>">
<td>&nbsp;</td><td align="left" class="questionform">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>; color: <?php echo $FontColor?>;">
<input type="hidden" name="mode" value="post">
<input type="checkbox" name="storedata" value="1" <?php if(isset($storedata) || isset($cookieemail)) echo "checked"?>><?php echo $l_storedata?></span></td></tr>
<tr bgcolor="<?php echo $actionbgcolor?>"><td class="questionform" colspan="2" align="center"><input class="faqebutton" type="submit" name="submit" value="<?php echo $l_submit?>"></td></tr>
</form>
</table></td></tr></table></div>
<?php
include_once('./includes/bottom.inc');
?>