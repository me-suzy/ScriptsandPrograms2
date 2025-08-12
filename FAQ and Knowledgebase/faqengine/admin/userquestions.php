<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require_once('../config.php');
require_once('./auth.php');
if(!isset($$langvar) || !$$langvar)
	$act_lang=$admin_lang;
else
	$act_lang=$$langvar;
require_once('language/lang_'.$act_lang.'.php');
$page_title=$l_userquestions;
$page="userquestions";
require_once('./heading.php');
include_once('../includes/htmlMimeMail.inc');
include_once("./includes/get_layout.inc");
if($use_smtpmail)
{
	include_once('../includes/smtp.inc');
	include_once('../includes/RFC822.inc');
}
if(!isset($storefaqfilter) && ($admstorefaqfilters==1))
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
		if(faqe_array_key_exists($admcookievals,"uq_filterprog"))
			$filterprog=$admcookievals["uq_filterprog"];
		if(faqe_array_key_exists($admcookievals,"uq_filterlang"))
			$filterlang=$admcookievals["uq_filterlang"];
		if(faqe_array_key_exists($admcookievals,"uq_addfilter"))
			$addfilters=$admcookievals["uq_addfilter"];
		if(faqe_array_key_exists($admcookievals,"uq_sorting"))
			$sorting=$admcookievals["uq_sorting"];
		if(faqe_array_key_exists($admcookievals,"uq_statefilter"))
			$statefilter=$admcookievals["uq_statefilter"];
	}
}
if(!isset($addfilters))
	$addfilters=0;
if(!isset($statefilter))
	$statefilter=0;
if(!isset($sorting))
	$sorting=11;
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<?php
if(isset($mode))
{
	if($mode=="display")
	{
		if($admin_rights < 1)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		$sql = "select * from ".$tableprefix."_questions where (questionnr=$input_questionnr)";
		if(!$result = faqe_db_query($sql, $db))
			die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if (!$myrow = faqe_db_fetch_array($result))
			die("<tr class=\"errorrow\"><td>no such entry");
		$progsql = "select * from ".$tableprefix."_programm where (prognr=".$myrow["prognr"].")";
		if(!$progresult=faqe_db_query($progsql,$db))
			die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if(!$progrow=faqe_db_fetch_array($progresult))
			$progname=$l_none;
		else
			$progname=display_encoded($progrow["programmname"]);
		$permsql="select * from ".$tableprefix."_programm_admins where prognr=".$myrow["prognr"]." and usernr=".$userdata["usernr"];
		if(!$permresult=faqe_db_query($permsql,$db))
			die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if(faqe_db_num_rows($permresult)<1)
			$isresponsible=false;
		else
			$isresponsible=true;
		$questiontext = $myrow["question"];
		$questiontext = display_encoded($questiontext);
		$questiontext = str_replace("\n", "<BR>", $questiontext);
?>
<tr class="headingrow"><td align="center" colspan="2"><b><?php echo $l_displayuserquestion?></b></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_progname?>:</td><td><?php echo $progname?></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_os?>:</td><td><?php echo display_encoded($myrow["osname"])?></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_usedversion?>:</td><td><?php echo $myrow["versionnr"]?></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_email?>:</td><td><?php echo $myrow["email"]?></td></tr>
<?php
		list($mydate,$mytime)=explode(" ",$myrow["enterdate"]);
		list($year, $month, $day) = explode("-", $mydate);
		list($hour, $min, $sec) = explode(":",$mytime);
		if($month>0)
			$displaydate=date($dateformat,mktime($hour,$min,$sec,$month,$day,$year));
		else
			$displaydate="";
?>
<tr class="displayrow"><td align="right"><?php echo $l_date?>:</td><td><?php echo $displaydate?></td></tr>
<?php
		if($myrow["faqref"]>0)
		{
			$tempsql = "select * from ".$tableprefix."_data where faqnr=".$myrow["faqref"];
			if(!$tempresult=faqe_db_query($tempsql,$db))
			    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
			if($temprow=faqe_db_fetch_array($tempresult))
			{
				$faq_heading=undo_html_ampersand(stripslashes($temprow["heading"]));
				$tempsql = "select * from ".$tableprefix."_category where catnr=".$temprow["category"];
				if(!$tempresult=faqe_db_query($tempsql,$db))
				    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
				if($temprow=faqe_db_fetch_array($tempresult))
					$faq_category=display_encoded($temprow["categoryname"]);
				else
					$faq_category=$l_none;
?>
<tr class="displayrow"><td align="right" valign="top"><?php echo $l_faqref2?>:</td><td><i><?php echo $faq_category?></i><br>
<?php echo $faq_heading?><br>
<?php
if($admin_rights<2)
	echo "<a href=\"".do_url_session("faq.php?$langvar=$act_lang&input_faqnr=".$myrow["faqref"]."&mode=display")."\" target=\"_blank\">$l_display";
else
	echo "<a href=\"".do_url_session("faq.php?$langvar=$act_lang&input_faqnr=".$myrow["faqref"]."&mode=edit")."\" target=\"_blank\">$l_edit";
?>
</a>
</td></tr>
<?php
			}
		}
		if($myrow["questionref"]>0)
		{
?>
<tr class="displayrow"><td align="right"><?php echo $l_questionref?>:</td><td><?php echo "#".$myrow["questionref"]?>
&nbsp;&nbsp;&nbsp;<a href="<?php echo do_url_session("$act_script_url?$langvar=$act_lang&mode=display&input_questionnr=".$myrow["questionref"])?>"><?php echo $l_display?></a>
<?php
		}
?>
<tr class="displayrow"><td align="right"><?php echo $l_ipadr?>:</td><td><?php echo $myrow["posterip"]?></td></tr>
<tr class="displayrow"><td align="right" valign="top"><?php echo $l_question?>:</td><td><?php echo display_encoded($questiontext)?></td></tr>
<form method="post" action="<?php echo $act_script_url?>">
<?php
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
		if(is_konqueror())
			echo "<tr><td></td></tr>";
?>
<input type="hidden" name="mode" value="changestate">
<input type="hidden" name="input_questionnr" value="<?php echo $input_questionnr?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_state?>:</td><td>
<select name="qstate">
<?php
		for($i=0;$i<count($l_qstates);$i++)
		{
			echo "<option value=\"$i\"";
			if($i==$myrow["state"])
				echo " selected";
			echo ">".$l_qstates[$i]."</option>";
		}
?>
</select>
&nbsp;&nbsp;&nbsp;<input class="faqebutton" type="submit" value="<?php echo $l_ok?>"></td></tr></form>
<form method="post" action="<?php echo $act_script_url?>">
<?php
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
		if(is_konqueror())
			echo "<tr><td></td></tr>";
?>
<input type="hidden" name="mode" value="publish">
<input type="hidden" name="input_questionnr" value="<?php echo $input_questionnr?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<tr class="inputrow"><td>&nbsp;</td><td valign="middle">
<input type="checkbox" name="dopublish" value="1" <?php if ($myrow["publish"]==1) echo "checked"?>> <?php echo $l_publish?>
&nbsp;&nbsp;&nbsp;<input class="faqebutton" type="submit" value="<?php echo $l_ok?>"></td></tr></form>
<?php
if($myrow["answerauthor"]>0)
{
	$tempsql = "select username from ".$tableprefix."_admins where usernr=".$myrow["answerauthor"];
	if(!$tempresult=faqe_db_query($tempsql,$db))
	    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
	if($temprow=faqe_db_fetch_array($tempresult))
		$authorname=$temprow["username"];
	else
		$authorname=$l_none;
	list($mydate,$mytime)=explode(" ",$myrow["answerdate"]);
	list($year, $month, $day) = explode("-", $mydate);
	list($hour, $min, $sec) = explode(":",$mytime);
	if($month>0)
		$displaydate=date($dateformat,mktime($hour,$min,$sec,$month,$day,$year));
	else
		$displaydate=$l_unknown;
?>
<tr class="displayrow"><td align="right"><?php echo $l_answered?>:</td><td><?php echo $authorname.", ".$displaydate?></td></tr>
<?php
	if(($admin_rights>2) || (($admin_rights>1) && ($myrow["answerauthor"]==$userdata["usernr"])))
	{
		$answertext=$myrow["answer"];
		$answertext=display_encoded($answertext);
		$answertext = str_replace("\n", "<BR>", $answertext);
?>
<tr class="displayrow"><td align="right" valign="top"><?php echo $l_answer?>:</td><td><?php echo $answertext?></td></tr>
<form method="post" action="question2faq.php">
<?php
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
		if(is_konqueror())
			echo "<tr><td></td></tr>";
?>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_transferquestion?>:</td>
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="mode" value="transfer">
<input type="hidden" name="input_questionnr" value="<?php echo $input_questionnr?>">
<td><input type="checkbox" name="transferdel" value="1"><?php echo $l_deleteaftertransfer?><br>
<input type="checkbox" name="delquotes" value="1"><?php echo $l_removequotes?><br>
<input class="faqebutton" type="submit" value="<?php echo $l_copy?>">
</td></form>
</tr>
<?php
		if($userquestionanswermode==0)
		{
?>
<tr class="actionrow"><td align="center" colspan="2">
<a href="<?php echo do_url_session("$act_script_url?$langvar=$act_lang&input_questionnr=$input_questionnr&mode=answer")?>"><?php echo $l_answerquestion?></a>
</td></tr>
<?php
		}
		else
		{
?>
<tr class="actionrow"><td align="center" colspan="2">
<a href="<?php echo do_url_session("question2faq.php?mode=transfer&$langvar=$act_lang&input_questionnr=$input_questionnr&transferdel=1")?>"><?php echo $l_answerquestion?></a>
&nbsp;&nbsp;&nbsp;
<?php
			if($myrow["email"])
			{
?>
<a href="<?php echo do_url_session("$act_script_url?$langvar=$act_lang&input_questionnr=$input_questionnr&mode=mailanswer")?>"><?php echo $l_onlymailanswer?></a>
<?php
			}
?>
</td></tr>
<?php
		}
	}
	else if($isresponsible)
	{
		$answertext=$myrow["answer"];
		$answertext=display_encoded($answertext);
		$answertext = str_replace("\n", "<BR>", $answertext);
?>
<tr class="displayrow"><td align="right" valign="top"><?php echo $l_answer?>:</td><td><?php echo $answertext?></td></tr>
<?php
	}
}
else
{
		if($userquestionanswermode==0)
		{
?>
<tr class="actionrow"><td align="center" colspan="2"><a href="<?php echo do_url_session("$act_script_url?$langvar=$act_lang&input_questionnr=$input_questionnr&mode=answer")?>"><?php echo $l_answerquestion?></a></td></tr>
<?php
		}
		else
		{
?>
<tr class="actionrow"><td align="center" colspan="2">
<a href="<?php echo do_url_session("question2faq.php?mode=transfer&$langvar=$act_lang&input_questionnr=$input_questionnr&transferdel=1")?>"><?php echo $l_answerquestion?></a>
&nbsp;&nbsp;&nbsp;
<?php
			if($myrow["email"])
			{
?>
<a href="<?php echo do_url_session("$act_script_url?$langvar=$act_lang&input_questionnr=$input_questionnr&mode=mailanswer")?>"><?php echo $l_onlymailanswer?></a>
<?php
			}
?>
</td></tr>
<?php
		}
	}
		echo "</table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_userquestions</a></div>";
	}
	if($mode=="chstate")
	{
		if($admin_rights < 2)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="headingrow"><td align="center" colspan="2"><b><?php echo $l_changestate?></b></td></tr>
<?php
		$sql = "select * from ".$tableprefix."_questions where (questionnr=$input_questionnr)";
		if(!$result = faqe_db_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if (!$myrow = faqe_db_fetch_array($result))
			die("<tr class=\"errorrow\"><td>no such entry");
?>
<tr class="displayrow"><td align="right" valign="top"><?php echo $l_question?>:</td><td>#<?php echo $input_questionnr?></td></tr>
<form method="post" action="<?php echo $act_script_url?>">
<?php
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
		if(is_konqueror())
			echo "<tr><td></td></tr>";
?>
<input type="hidden" name="mode" value="changestate">
<input type="hidden" name="input_questionnr" value="<?php echo $input_questionnr?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_state?>:</td><td>
<select name="qstate">
<?php
		for($i=0;$i<count($l_qstates);$i++)
		{
			echo "<option value=\"$i\"";
			if($i==$myrow["state"])
				echo " selected";
			echo ">".$l_qstates[$i]."</option>";
		}
?>
</select></td></tr>
<tr class="actionrow"><td align="center" colspan="2"><input class="faqebutton" type="submit" value="<?php echo $l_ok?>"></td></tr></form>
</table></td></tr></table>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?$langvar=$act_lang")?>"><?php echo $l_userquestions?></a></div>
<?php
	}
	if($mode=="changestate")
	{
		if($admin_rights < 2)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
		$sql = "select * from ".$tableprefix."_questions where questionnr=$input_questionnr";
		if(!$result = faqe_db_query($sql, $db))
			die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if(!$myrow=faqe_db_fetch_array($result))
			die("<tr class=\"errorrow\"><td>No such entry.");
		$oldstate=$myrow["state"];
		$sql = "update ".$tableprefix."_questions set state=$qstate where (questionnr=$input_questionnr)";
		if(!$result = faqe_db_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if(($uqscmail==1) && ($oldstate!=$qstate))
		{
			$sql = "select * from ".$tableprefix."_questions where questionnr=$input_questionnr";
			if(!$result = faqe_db_query($sql, $db))
			    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
			if(!$myrow=faqe_db_fetch_array($result))
			    die("<tr class=\"errorrow\"><td>No such entry.");
			if($myrow["email"])
			{
				if(strlen($myrow["language"])>0)
					$questionlang=$myrow["language"];
				else
					$questionlang=$default_lang;
				require_once('./language/userquestions_'.$questionlang.'.php');
				if($userdata["hideemail"]==0)
					$sendermail=$userdata["email"];
				else
					$sendermail=$faqemail;
				list($mydate,$mytime)=explode(" ",$myrow["enterdate"]);
				list($year, $month, $day) = explode("-", $mydate);
				list($hour, $min, $sec) = explode(":",$mytime);
				if($month>0)
					$displaydate=date($l_uq_dateformat,mktime($hour,$min,$sec,$month,$day,$year));
				else
					$displaydate="";
				$subject = str_replace("{refid}",$input_questionnr,$l_uq_sc_subject);
				$mailbody = str_replace("{refid}",$input_questionnr,$l_uq_sc_body);
				$mailbody = str_replace("{date}",$displaydate,$mailbody);
				$mailbody = str_replace("{state}",$l_uq_sc_states[$myrow["state"]],$mailbody);
				$asc_mailbody=str_replace("\n",$crlf,$mailbody);
				$html_mailbody=do_htmlentities($mailbody);
				$html_mailbody=str_replace("\n","<br>",$html_mailbody);
				$mail = new htmlMimeMail();
				$mail->setCrlf($crlf);
				$mail->setTextCharset($contentcharset);
				if($disablehtmlemail==0)
				{
					$mail->setHTMLCharset($contentcharset);
					$mail->setHTML($html_mailbody, $asc_mailbody);
				}
				else
					$mail->setText($asc_mailbody);
				$mail->setSubject($subject);
				$mail->setFrom($sendermail);
				if(!$insafemode)
					@set_time_limit($msendlimit);
				$receiver=array();
				array_push($receiver,stripslashes($myrow["email"]));
				if($use_smtpmail)
				{
					$mail->setSMTPParams($smtpserver,$smtpport,NULL,$smtpauth,$smtpuser,$smtppasswd);
					$mail->send($receiver, "smtp");
				}
				else
					$mail->send($receiver, "mail");
			}
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="displayrow"><td align="center"><?php echo $l_statechanged?></td></tr>
</table></td></tr></table>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?$langvar=$act_lang")?>"><?php echo $l_userquestions?></a></div>
<?php
	}
	if($mode=="publish")
	{
		if($admin_rights < 2)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
		if(isset($dopublish))
			$publish=1;
		else
			$publish=0;
		$sql = "update ".$tableprefix."_questions set publish=$publish where (questionnr=$input_questionnr)";
		if(!$result = faqe_db_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="headingrow"><td align="center"><b><?php echo $l_publishquestion?></b></td></tr>
<tr class="displayrow"><td align="center"><?php echo $l_questionpublished?></td></tr>
</table></td></tr></table>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?$langvar=$act_lang")?>"><?php echo $l_userquestions?></a></div>
<?php
	}
	if($mode=="mailanswer")
	{
		if($admin_rights < 2)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
		if(($userdata["hideemail"]==0) && (strlen($userdata["email"])<1))
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_noadminmail");
		}
		$sql = "select * from ".$tableprefix."_questions where (questionnr=$input_questionnr)";
		if(!$result = faqe_db_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if (!$myrow = faqe_db_fetch_array($result))
			die("<tr class=\"errorrow\"><td>no such entry");
		if(!$myrow["email"])
		{
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="headingrow"><td align="center"><b><?php echo $l_answerquestion?></b></td></tr>
<tr class="displayrow"><td align="center"><?php echo $l_noansermailadr?></td></tr>
</table></td></tr></table>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?$langvar=$act_lang")?>"><?php echo $l_userquestions?></a></div>
<?php
			include('trailer.php');
			exit;
		}
		list($mydate,$mytime)=explode(" ",$myrow["enterdate"]);
		list($year, $month, $day) = explode("-", $mydate);
		list($hour, $min, $sec) = explode(":",$mytime);
		if($month>0)
			$displaydate=date($dateformat,mktime($hour,$min,$sec,$month,$day,$year));
		else
			$displaydate="";
		if(strlen($myrow["language"])>0)
			$questionlang=$myrow["language"];
		else
			$questionlang=$default_lang;
		include('language/userquestions_'.$questionlang.'.php');
		$answertext = "> ".str_replace("\n", "\n> ", display_encoded($myrow["question"]));
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="headingrow"><td align="center" colspan="2"><b><?php echo $l_answerquestion?></b></td></tr>
<form name="answerform" method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<?php
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
		if(is_konqueror())
			echo "<tr><td></td></tr>";
?>
<input type="hidden" name="input_questionnr" value="<?php echo $input_questionnr?>">
<input type="hidden" name="mode" value="sendanswermail">
<?php
	if($userdata["hideemail"]==0)
		$sendermail=$userdata["email"];
	else
		$sendermail=$faqemail;
?>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_sender?>:</td><td><?php echo $sendermail?></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_receiver?>:</td><td><?php echo $myrow["email"]?></td></tr>
<tr class="inputrow"><td align="right" width="30%" valign="top"><?php echo $l_answer?>:</td>
<td><?php echo $l_answer_prelude?><br><textarea class="faqeinput" name="answer" cols="50" rows="10" wrap="virtual"><?php echo $answertext?></textarea></td></tr>
<tr class="optionrow"><td align="right" valign="middle"><?php echo $l_options?>:</td>
<td align="left">
<input type="checkbox" name="delquestion" value="1"> <?php echo $l_delquestionafteranswer?><br>
</td></tr>
<tr class="actionrow"><td align="center" colspan="2"><input class="faqebutton" type="submit" value="<?php echo $l_send?>"></td></tr>
</form>
</table></td></tr></table>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?$langvar=$act_lang")?>"><?php echo $l_userquestions?></a></div>
<?php
	}
	if($mode=="sendanswermail")
	{
		if($admin_rights < 2)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
		if(($userdata["hideemail"]==0) && (strlen($userdata["email"])<1) && (!isset($dontemail)))
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_noadminmail");
		}
		$asc_answer=strip_tags($answer);
		$sql = "select * from ".$tableprefix."_questions where (questionnr=$input_questionnr)";
		if(!$result = faqe_db_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if (!$myrow = faqe_db_fetch_array($result))
			die("<tr class=\"errorrow\"><td>no such entry");
		if(!$myrow["email"])
		{
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="headingrow"><td align="center"><b><?php echo $l_answerquestion?></b></td></tr>
<tr class="displayrow"><td align="center"><?php echo $l_noansermailadr?></td></tr>
</table></td></tr></table>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?$langvar=$act_lang")?>"><?php echo $l_userquestions?></a></div>
<?php
			include('trailer.php');
			exit;
		}
		list($mydate,$mytime)=explode(" ",$myrow["enterdate"]);
		list($year, $month, $day) = explode("-", $mydate);
		list($hour, $min, $sec) = explode(":",$mytime);
		if($month>0)
			$displaydate=date($dateformat,mktime($hour,$min,$sec,$month,$day,$year));
		else
			$displaydate="";
		if($userdata["hideemail"]==0)
			$sendermail=$userdata["email"];
		else
			$sendermail=$faqemail;
		if(strlen($myrow["language"])>0)
			$questionlang=$myrow["language"];
		else
			$questionlang=$default_lang;
		require_once('./language/userquestions_'.$questionlang.'.php');
		$asc_mailbody = str_replace("{date}",$displaydate,$l_uq_answer_prelude)."\n".$asc_answer;
		$asc_mailbody = str_replace("\n","\r\n",$asc_mailbody);
		$asc_mailbody .="\r\n\r\n";
		if(strlen($userdata["signature"])>1)
		{
			$sigtext=str_replace("\n","\r\n",$userdata["signature"]);
			$asc_mailbody .= "---\r\n";
			$asc_mailbody .=$sigtext;
			$asc_mailbody .="\r\n\r\n";
		}
		$asc_mailbody.="\r\n";
		$html_mailbody ="<span style=\"font-face: $FontFace; font-size: $FontSize1;\">";
		$html_mailbody.=str_replace("{date}",$displaydate,$l_uq_answer_prelude)."<BR>";
		$html_answer=str_replace("\n","<BR>",stripslashes($answer));
		$html_mailbody.=undo_html_specialchars($html_answer);
		$html_mailbody.="</span><br><br>";
		if(strlen($userdata["signature"])>1)
		{
			$sigtext=str_replace("\n","<br>",$userdata["signature"]);
			$html_mailbody.= "---<br>";
			$html_mailbody.="<span style=\"font-face: $FontFace; font-size: $FontSize1;\">";
			$html_mailbody.=display_encoded($sigtext)."</span><br>";
		}
		$subject = str_replace("{refid}",$input_questionnr,$l_uq_answer_subject);
		$mail = new htmlMimeMail();
		$mail->setCrlf($crlf);
		$mail->setTextCharset($contentcharset);
		if($disablehtmlemail==0)
		{
			$mail->setHTMLCharset($contentcharset);
			$mail->setHTML($html_mailbody, $asc_mailbody);
		}
		else
			$mail->setText($asc_mailbody);
		$mail->setSubject($subject);
		$mail->setFrom($sendermail);
		if(!$insafemode)
			@set_time_limit($msendlimit);
		$receiver=array();
		array_push($receiver,stripslashes($myrow["email"]));
		if($use_smtpmail)
		{
			$mail->setSMTPParams($smtpserver,$smtpport,NULL,$smtpauth,$smtpuser,$smtppasswd);
			$mail->send($receiver, "smtp");
		}
		else
			$mail->send($receiver, "mail");
		if(isset($delquestion))
		{
			$deleteSQL = "delete from ".$tableprefix."_questions where (questionnr=$input_questionnr)";
			$success = faqe_db_query($deleteSQL,$db);
			if (!$success)
				die("<tr class=\"errorrow\"><td>$l_cantdelete.");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="headingrow"><td align="center"><b><?php echo $l_answerquestion?></b></td></tr>
<tr class="displayrow"><td align="center"><?php echo $l_answersent?></td></tr>
</table></td></tr></table>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?$langvar=$act_lang")?>"><?php echo $l_userquestions?></a></div>
<?php
	}
	if($mode=="answer")
	{
		if($admin_rights < 2)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
		if(($userdata["hideemail"]==0) && (strlen($userdata["email"])<1))
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_noadminmail");
		}
		$sql = "select * from ".$tableprefix."_questions where (questionnr=$input_questionnr)";
		if(!$result = faqe_db_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if (!$myrow = faqe_db_fetch_array($result))
			die("<tr class=\"errorrow\"><td>no such entry");
		list($mydate,$mytime)=explode(" ",$myrow["enterdate"]);
		list($year, $month, $day) = explode("-", $mydate);
		list($hour, $min, $sec) = explode(":",$mytime);
		if($month>0)
			$displaydate=date($dateformat,mktime($hour,$min,$sec,$month,$day,$year));
		else
			$displaydate="";
		if(strlen($myrow["language"])>0)
			$questionlang=$myrow["language"];
		else
			$questionlang=$default_lang;
		include('language/userquestions_'.$questionlang.'.php');
		$answertext = "> ".str_replace("\n", "\n> ", $myrow["question"]);
		$answertext = display_encoded($answertext);
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="headingrow"><td align="center" colspan="2"><b><?php echo $l_answerquestion?></b></td></tr>
<form name="answerform" method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<?php
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
		if(is_konqueror())
			echo "<tr><td></td></tr>";
?>
<input type="hidden" name="input_questionnr" value="<?php echo $input_questionnr?>">
<input type="hidden" name="mode" value="sendanswer">
<?php
	if($userdata["hideemail"]==0)
		$sendermail=$userdata["email"];
	else
		$sendermail=$faqemail;
?>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_sender?>:</td><td><?php echo $sendermail?></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_receiver?>:</td><td><?php echo $myrow["email"]?></td></tr>
<tr class="inputrow"><td align="right" width="30%" valign="top"><?php echo $l_answer?>:</td>
<td><?php echo $l_answer_prelude?><br><textarea class="faqeinput" name="answer" cols="50" rows="10" wrap="virtual"><?php echo $answertext?></textarea></td></tr>
<tr class="optionrow"><td align="right" valign="top"><?php echo $l_options?>:</td>
<td align="left">
<?php
if(!$myrow["email"])
	echo "<input type=\"hidden\" name=\"dontemail\" value=\"1\">";
else
{
?>
<input type="checkbox" name="dontemail" value="1" <?php if($userquestionanswermail==0) echo "checked"?>> <?php echo $l_dontsendanswer?><br>
<?php
}
?>
<input type="checkbox" name="dontstore" value="1"> <?php echo $l_dontstoreanswer?><br>
<input type="checkbox" name="delquestion" onClick="qes_delqes()" value="1"> <?php echo $l_delquestionafteranswer?><br>
</td></tr>
<tr class="actionrow"><td align="center" colspan="2"><input class="faqebutton" type="submit" value="<?php echo $l_send?>"></td></tr>
</form>
</table></td></tr></table>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?$langvar=$act_lang")?>"><?php echo $l_userquestions?></a></div>
<?php
	}
	if($mode=="sendanswer")
	{
		if($admin_rights < 2)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
		if(($userdata["hideemail"]==0) && (strlen($userdata["email"])<1) && (!isset($dontemail)))
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_noadminmail");
		}
		$asc_answer=strip_tags($answer);
		$sql = "select * from ".$tableprefix."_questions where (questionnr=$input_questionnr)";
		if(!$result = faqe_db_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if (!$myrow = faqe_db_fetch_array($result))
			die("<tr class=\"errorrow\"><td>no such entry");
		list($mydate,$mytime)=explode(" ",$myrow["enterdate"]);
		list($year, $month, $day) = explode("-", $mydate);
		list($hour, $min, $sec) = explode(":",$mytime);
		if($month>0)
			$displaydate=date($dateformat,mktime($hour,$min,$sec,$month,$day,$year));
		else
			$displaydate="";
		if(!isset($dontemail))
		{
			if($userdata["hideemail"]==0)
				$sendermail=$userdata["email"];
			else
				$sendermail=$faqemail;
			if(strlen($myrow["language"])>0)
				$questionlang=$myrow["language"];
			else
				$questionlang=$default_lang;
			require_once('./language/userquestions_'.$questionlang.'.php');
			$asc_mailbody = str_replace("{date}",$displaydate,$l_uq_answer_prelude)."\n".$asc_answer;
			$asc_mailbody = str_replace("\n","\r\n",$asc_mailbody);
			$asc_mailbody .="\r\n\r\n";
			if(strlen($userdata["signature"])>1)
			{
				$sigtext=str_replace("\n","\r\n",$userdata["signature"]);
				$asc_mailbody .= "---\r\n";
				$asc_mailbody .=$sigtext;
				$asc_mailbody .="\r\n\r\n";
			}
			$asc_mailbody.="\r\n";
			$html_mailbody ="<span style=\"font-face: $FontFace; font-size: $FontSize1;\">";
			$html_mailbody.=str_replace("{date}",$displaydate,$l_uq_answer_prelude)."<br>";
			$html_answer=str_replace("\n","<BR>",stripslashes($answer));
			$html_mailbody.=undo_htmlspecialchars($html_answer);
			$html_mailbody.="</span><br><br>";
			if(strlen($userdata["signature"])>1)
			{
				$sigtext=str_replace("\n","<br>",$userdata["signature"]);
				$html_mailbody.= "---<br>";
				$html_mailbody.="<span style=\"font-face: $FontFace; font-size: $FontSize1;\">";
				$html_mailbody.=display_encoded($sigtext)."</span><br>";
			}
			$subject = str_replace("{refid}",$input_questionnr,$l_uq_answer_subject);
			$mail = new htmlMimeMail();
			$mail->setCrlf($crlf);
			$mail->setTextCharset($contentcharset);
			if($disablehtmlemail==0)
			{
				$mail->setHTMLCharset($contentcharset);
				$mail->setHTML($html_mailbody, $asc_mailbody);
			}
			else
				$mail->setText($asc_mailbody);
			$mail->setSubject($subject);
			$mail->setFrom($sendermail);
			if(!$insafemode)
				@set_time_limit($msendlimit);
			$receiver=array();
			array_push($receiver,stripslashes($myrow["email"]));
			if($use_smtpmail)
			{
				$mail->setSMTPParams($smtpserver,$smtpport,NULL,$smtpauth,$smtpuser,$smtppasswd);
				$mail->send($receiver, "smtp");
			}
			else
				$mail->send($receiver, "mail");
		}
		if(!isset($dontstore))
		{
			$actdate = date("Y-m-d H:i:s");
			$sql = "UPDATE ".$tableprefix."_questions set answerauthor=".$userdata["usernr"].", answerdate='$actdate', answer='$answer'";
			if($userquestionautopublish==1)
				$sql.=", publish=1";
			$sql.=" where questionnr=$input_questionnr";
			if(!$result = faqe_db_query($sql, $db))
			    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		}
		if(isset($delquestion))
		{
			$deleteSQL = "delete from ".$tableprefix."_questions where (questionnr=$input_questionnr)";
			$success = faqe_db_query($deleteSQL,$db);
			if (!$success)
				die("<tr class=\"errorrow\"><td>$l_cantdelete.");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="headingrow"><td align="center"><b><?php echo $l_answerquestion?></b></td></tr>
<tr class="displayrow"><td align="center"><?php echo $l_questionanswered?></td></tr>
<?php
		if(!isset($dontemail))
			echo "<tr class=\"displayrow\"><td align=\"center\">$l_answersent</td></tr>";
?>
</table></td></tr></table>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?$langvar=$act_lang")?>"><?php echo $l_userquestions?></a></div>
<?php
	}
	if($mode=="delete")
	{
		if($admin_rights < 2)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		$deleteSQL = "delete from ".$tableprefix."_questions where (questionnr=$input_questionnr)";
		$success = faqe_db_query($deleteSQL,$db);
		if (!$success)
			die("<tr class=\"errorrow\"><td>$l_cantdelete.");
		echo "<tr class=\"displayrow\" align=\"center\"><td>";
		echo "$l_deleted<br>";
		echo "</td></tr></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_userquestions</a></div>";
	}
	if($mode=="massdel")
	{
		if($admin_rights < 2)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		$numdeleted=0;
		while(list($null, $input_questionnr) = each($questionnrs)) {
			$deleteSQL = "delete from ".$tableprefix."_questions where (questionnr=$input_questionnr)";
			$success = faqe_db_query($deleteSQL,$db);
			if (!$success)
				die("<tr class=\"errorrow\"><td>$l_cantdelete.");
			$numdeleted++;
		}
		echo "<tr class=\"displayrow\" align=\"center\"><td>";
		echo "$l_deleted ($numdeleted)";
		echo "</td></tr></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_userquestions</a></div>";
	}
}
else
{
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
if($admin_rights<3)
	$sql = "select q.* from ".$tableprefix."_questions q, ".$tableprefix."_programm_admins pa, ".$tableprefix."_programm prog where pa.usernr=$act_usernr and q.prognr=pa.prognr and prog.prognr=q.prognr ";
else
	$sql = "select q.* from ".$tableprefix."_questions q, ".$tableprefix."_programm prog where prog.prognr=q.prognr ";
if(isset($filterprog) and ($filterprog>=0))
	$sql.="and q.prognr=$filterprog ";
if(isset($filterlang) and ($filterlang!="none"))
	$sql.="and q.language='$filterlang' ";
if(isset($statefilter) && ($statefilter>0))
	$sql.="and q.state=".($statefilter-1)." ";
if(isset($addfilters))
{
	switch($addfilters)
	{
		case 1:
			$sql.="and q.publish=0 ";
			break;
		case 2:
			$sql.="and q.publish=1 ";
			break;
		case 3:
			$sql.="and q.enterdate>='".$userdata["lastlogin"]."' ";
			break;
	}
}
switch($sorting)
{
	case 12:
		$sql.="order by q.questionnr desc";
		break;
	case 21:
		$sql.="order by prog.programmname asc";
		break;
	case 22:
		$sql.="order by prog.programmname desc";
		break;
	case 31:
		$sql.="order by q.enterdate asc";
		break;
	case 32:
		$sql.="order by q.enterdate desc";
		break;
	case 41:
		$sql.="order by q.email asc";
		break;
	case 42:
		$sql.="order by q.email desc";
		break;
	case 51:
		$sql.="order by q.state asc";
		break;
	case 52:
		$sql.="order by q.state desc";
		break;
	default:
		$sql.="order by q.questionnr asc";
		break;
}
if(!$result = faqe_db_query($sql, $db)) {
    die("Could not connect to the database.");
}
if (!$myrow = faqe_db_fetch_array($result))
{
	echo "<tr class=\"bottombox\"><td align=\"center\" colspan=\"3\">";
	echo $l_noentries;
	echo "</td></tr></table></td></tr></table>";
}
else
{
?>
<form name="listform" action="<?php echo $act_script_url?>" method="post">
<?php
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
	if(is_konqueror())
		echo "<tr><td></td></tr>";
	$maxsortcol=5;
	$baseurl="$act_script_url?$langvar=$act_lang";
	if(isset($filterprog))
		$baseurl.="&filterprog=$filterprog";
	if(isset($filtercat))
		$baseurl.="&filtercat=$filtercat";
	if(isset($filterlang))
		$baseurl.="&filterlang=$filterlang";
	if(isset($addfilters))
		$baseurl.="&addfilters=$addfilters";
	if($admstorefaqfilters==1)
		$baseurl.="&storefaqfilter=1";
	echo "<input type=\"hidden\" name=\"$langvar\" value=\"$act_lang\">";
	echo "<input type=\"hidden\" name=\"mode\" value=\"massdel\">";
	echo "<tr class=\"rowheadings\">";
	echo "<td align=\"center\">&nbsp;</td>";
	echo "<td align=\"center\">";
	$sorturl=getSortURL($sorting, 1, $maxsortcol, $baseurl);
	echo "<a class=\"rowheadings\" href=\"".do_url_session($sorturl)."\">";
	echo "<b>#</b></a>";
	echo getSortMarker($sorting, 1, $maxsortcol);
	echo "</td>";
	echo "<td align=\"center\">";
	$sorturl=getSortURL($sorting, 2, $maxsortcol, $baseurl);
	echo "<a class=\"rowheadings\" href=\"".do_url_session($sorturl)."\">";
	echo "<b>$l_progname</b></a>";
	echo getSortMarker($sorting, 2, $maxsortcol);
	echo "</td>";
	echo "<td align=\"center\">";
	$sorturl=getSortURL($sorting, 3, $maxsortcol, $baseurl);
	echo "<a class=\"rowheadings\" href=\"".do_url_session($sorturl)."\">";
	echo "<b>$l_date</b></a>";
	echo getSortMarker($sorting, 3, $maxsortcol);
	echo "</td>";
	echo "<td align=\"center\">";
	$sorturl=getSortURL($sorting, 4, $maxsortcol, $baseurl);
	echo "<a class=\"rowheadings\" href=\"".do_url_session($sorturl)."\">";
	echo "<b>$l_email</b></a>";
	echo getSortMarker($sorting, 4, $maxsortcol);
	echo "</td>";
	echo "<td align=\"center\">";
	$sorturl=getSortURL($sorting, 5, $maxsortcol, $baseurl);
	echo "<a class=\"rowheadings\" href=\"".do_url_session($sorturl)."\">";
	echo "<b>$l_state</b></a>";
	echo getSortMarker($sorting, 5, $maxsortcol);
	echo "</td>";
	echo "<td align=\"center\">";
	echo "<b>$l_published</b></td>";
	echo "<td>&nbsp;</td></tr>";
	do {
		$act_id=$myrow["questionnr"];
		$prog_sql="select * from ".$tableprefix."_programm where prognr=".$myrow["prognr"];
		if(!$prog_result = faqe_db_query($prog_sql, $db)) {
		    die("Could not connect to the database.");
		}
		if($progrow=faqe_db_fetch_array($prog_result))
		{
			$progname=display_encoded($progrow["programmname"]);
			$proglang=stripslashes($progrow["language"]);
		}
		else
		{
			$progname=$l_undefined;
			$proglang=$l_undefined;
		}
		$mod_sql ="select * from ".$tableprefix."_programm_admins where prognr=".$myrow["prognr"]." and usernr=".$userdata["usernr"];
		if(!$mod_result = faqe_db_query($mod_sql, $db)) {
		    die("Could not connect to the database.");
		}
		if(($modrow=faqe_db_fetch_array($mod_result)) || ($admin_rights>2))
			$ismod=1;
		else
			$ismod=0;
		if($myrow["answerauthor"]>0)
		{
			if($myrow["enterdate"]>$userdata["lastlogin"])
				echo "<tr class=\"displayrownew\">";
			else
				echo "<tr class=\"displayrow\">";
		}
		else
		{
			if($myrow["enterdate"]>$userdata["lastlogin"])
				echo "<tr class=\"displayrownoansnew\">";
			else
				echo "<tr class=\"displayrownoans\">";
		}
		list($mydate,$mytime)=explode(" ",$myrow["enterdate"]);
		list($year, $month, $day) = explode("-", $mydate);
		list($hour, $min, $sec) = explode(":",$mytime);
		if($month>0)
			$displaydate=date($dateformat,mktime($hour,$min,$sec,$month,$day,$year));
		else
			$displaydate="";
		if($ismod==1)
			echo "<td width=\"5%\" align=\"center\"><input type=\"checkbox\" name=\"questionnrs[]\" value=\"".$myrow["questionnr"]."\"></td>";
		else
			echo "<td width=\"5%\">&nbsp;</td>";
		echo "<td width=\"5%\" align=\"right\">".$myrow["questionnr"]."</td>";
		echo "<td width=\"20%\" align=\"center\">$progname [$proglang]</td>";
		echo "<td width=\"25%\" align=\"center\">$displaydate</td>";
		echo "<td width=\"25%\" align=\"center\">".$myrow["email"]."</td>";
		echo "<td align=\"center\" width=\"10%\">";
		if($admin_rights > 1)
			echo "<a class=\"listlink2\" href=\"".do_url_session("$act_script_url?mode=chstate&$langvar=$act_lang&input_questionnr=$act_id")."\">";
		echo $l_qstates[$myrow["state"]];
		if($admin_rights > 1)
			echo "</a>";
		echo "</td><td align=\"center\" width=\"1%\">";
		if($myrow["publish"]==1)
			echo "*";
		else
			echo "&nbsp;";
		echo "</td><td>";
		if(($admin_rights > 2) || ($ismod==1))
		{
			echo "<a class=\"listlink2\" href=\"".do_url_session("$act_script_url?mode=display&$langvar=$act_lang&input_questionnr=$act_id")."\">";
			echo "<img src=\"gfx/view.gif\" border=\"0\" title=\"$l_display\" alt=\"$l_display\"></a>";
			echo "&nbsp; ";
			echo "<a class=\"listlink2\" href=\"".do_url_session("$act_script_url?mode=delete&input_questionnr=$act_id&$langvar=$act_lang")."\">";
			echo "<img src=\"gfx/delete.gif\" border=\"0\" title=\"$l_delete\" alt=\"$l_delete\"></a>";
			if($myrow["state"]==3)
			{
				echo "&nbsp; ";
				echo "<a class=\"listlink2\" href=\"".do_url_session("question2faq.php?mode=transfer&input_questionnr=$act_id&$langvar=$act_lang")."\">";
				echo "<img src=\"gfx/move.gif\" border=\"0\" title=\"$l_transferquestion\" alt=\"$l_transferquestion\"></a>";
			}
		}
		echo "</td></tr>";
	} while($myrow = faqe_db_fetch_array($result));
	echo "<tr class=\"actionrow\"><td colspan=\"8\">";
	echo "<input class=\"faqebutton\" type=\"submit\" value=\"$l_del_selected\">";
	echo "\n&nbsp;&nbsp;&nbsp;&nbsp;<input class=\"faqebutton\" type=\"button\" onclick=\"checkAll(document.listform)\" value=\"$l_checkall\">\n";
	echo "\n&nbsp;&nbsp;<input class=\"faqebutton\" type=\"button\" onclick=\"uncheckAll(document.listform)\" value=\"$l_uncheckall\">\n";
	echo "</td></tr></form>";
	echo "</table></tr></td></table>";
}
if($admin_rights > 1)
{
	include('./includes/prog_filterbox.inc');
	include('./includes/language_filterbox.inc');
?>
<table class="filterbox" align="center" width="80%" border="0" cellspacing="0" cellpadding="1" valign="top">
<form action="<?php echo $act_script_url?>" method="post">
<input type="hidden" name="lang" value="<?php echo $lang?>">
<?php
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
	if($admstorefaqfilters==1)
		echo "<input type=\"hidden\" name=\"storefaqfilter\" value=\"1\">";
	if(isset($sorting))
		echo "<input type=\"hidden\" name=\"sorting\" value=\"$sorting\">";
	if(isset($filterlang))
		echo "<input type=\"hidden\" name=\"filterlang\" value=\"$filterlang\">";
	if(isset($filterprog))
		echo "<input type=\"hidden\" name=\"filterprog\" value=\"$filterprog\">";
?>
<tr><td align="right" width="50%" valign="middle"><?php echo $l_statefilter?>:</td>
<td align="left" width="30%"><select name="statefilter">
<?php
	echo "<option value=\"0\"";
	if($statefilter==0)
		echo " selected";
	echo ">$l_nofilter</option>";
	for($i=0;$i<count($l_qstates);$i++)
	{
		echo "<option value=\"".($i+1)."\"";
		if(($i+1)==$statefilter)
			echo " selected";
		echo ">".$l_qstates[$i]."</option>";
	}
?>
</select></td><td align="left"><input class="faqebutton" type="submit" value="<?php echo $l_ok?>"></td></tr>
</form></table>
<table class="filterbox" align="center" width="80%" border="0" cellspacing="0" cellpadding="1" valign="top">
<form action="<?php echo $act_script_url?>" method="post">
<input type="hidden" name="lang" value="<?php echo $lang?>">
<?php
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
	if($admstorefaqfilters==1)
		echo "<input type=\"hidden\" name=\"storefaqfilter\" value=\"1\">";
	if(isset($sorting))
		echo "<input type=\"hidden\" name=\"sorting\" value=\"$sorting\">";
	if(isset($filterlang))
		echo "<input type=\"hidden\" name=\"filterlang\" value=\"$filterlang\">";
	if(isset($filterprog))
		echo "<input type=\"hidden\" name=\"filterprog\" value=\"$filterprog\">";
	if(isset($statefilter))
		echo "<input type=\"hidden\" name=\"statefilter\" value=\"$statefilter\">";
?>
<tr><td align="right" width="50%" valign="middle"><?php echo $l_addfilter?>:</td>
<td align="left" width="30%"><select name="addfilters">
<?php
	for($i=0;$i<count($l_uqfilters);$i++)
	{
		echo "<option value=\"$i\"";
		if($i==$addfilters)
			echo " selected";
		echo ">".$l_uqfilters[$i]."</option>";
	}
?>
</select></td><td align="left"><input class="faqebutton" type="submit" value="<?php echo $l_ok?>"></td></tr>
</form></table>
<?php
}
}
include('trailer.php');
?>