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
include_once('./language/lang_'.$act_lang.'.php');
include_once('../includes/htmlMimeMail.inc');
if($use_smtpmail)
{
	include_once('../includes/smtp.inc');
	include_once('../includes/RFC822.inc');
}
$page_title=$l_transferquestion;
$page="question2faq";
$uses_bbcode=true;
require_once('./heading.php');
include_once("./includes/bbcode_buttons.inc");
include_once("./includes/get_layout.inc");
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<?php
if(isset($mode))
{
	if($mode=="transfer")
	{
		if($admin_rights < 1)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
		$sql = "select * from ".$tableprefix."_questions where(questionnr=$input_questionnr)";
		if(!$result = faqe_db_query($sql, $db)) {
			echo "<tr class=\"errorrow\"><td align=\"center\">";
		    die("Could not connect to the database.");
		}
		if(!$myrow=faqe_db_fetch_array($result))
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("No such entry");
		}
		$prog_sql="select * from ".$tableprefix."_programm where prognr=".$myrow["prognr"];
		if(!$prog_result = faqe_db_query($prog_sql, $db)) {
			echo "<tr class=\"errorrow\"><td align=\"center\">";
		    die("Could not connect to the database.");
		}
		if($progrow=faqe_db_fetch_array($prog_result))
			$progname=display_encoded($progrow["programmname"]);
		else
			$progname=$l_undefined;
		$mod_sql ="select * from ".$tableprefix."_programm_admins where prognr=".$myrow["prognr"]." and usernr=".$userdata["usernr"];
		if(!$mod_result = faqe_db_query($mod_sql, $db)) {
			echo "<tr class=\"errorrow\"><td align=\"center\">";
		    die("Could not connect to the database.");
		}
		if($modrow=faqe_db_fetch_array($mod_result))
			$ismod=1;
		else
			$ismod=0;
		if(($admin_rights <3) && ($ismod==0))
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
		$actcat=-1;
		if($myrow["faqref"]>0)
		{
			$tempsql="select category from ".$tableprefix."_data where faqnr=".$myrow["faqref"];
			if(!$tempresult = faqe_db_query($tempsql, $db)) {
			    die("Could not connect to the database.");
			}
			if($temprow=faqe_db_fetch_array($tempresult))
				$actcat=$temprow["category"];
		}
		$questiontext = stripslashes($myrow["question"]);
		$questiontext = str_replace("<BR>", "\n", $questiontext);
		$questiontext = undo_htmlspecialchars($questiontext);
		$questiontext = bbdecode($questiontext);
		$questiontext = undo_make_clickable($questiontext);
		$answertext = stripslashes($myrow["answer"]);
		$answertext = str_replace("<BR>", "\n", $answertext);
		$answertext = undo_htmlspecialchars($answertext);
		$answertext = bbdecode($answertext);
		$answertext = undo_make_clickable($answertext);
		if(isset($delquotes))
			$answertext = preg_replace("#^>(.*)\n#m", "", $answertext);
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="headingrow"><td align="center" colspan="2"><b><?php echo $l_transferquestion?></b></td></tr>
<form name="inputform" onsubmit="return checkform()" method="post" action="<?php echo $act_script_url?>">
<?php
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
		if(is_konqueror())
			echo "<tr><td></td></tr>";
?>
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_heading?>:</td><td><input class="faqeinput" type="text" name="heading" size="40" maxlength="80"></td></tr>
<?php
		if($actcat<0)
		{
?>
<tr class="displayrow"><td align="right"><?php echo $l_programm?>:</td><td><?php echo $progname?></td></tr>
<?php
		}
?>
<tr class="inputrow"><td align="right"><?php echo $l_category?>:</td>
<td>
<?php
		if($admin_rights<3)
			$sql1 = "select cat.* from ".$tableprefix."_category cat, ".$tableprefix."_category_admins ca where cat.catnr = ca.catnr and ca.usernr=$act_usernr order by cat.catnr";
		else
			$sql1 = "select cat.* from ".$tableprefix."_category cat order by cat.catnr";
		if(!$result1 = faqe_db_query($sql1, $db))
			die("Could not connect to the database (3).");
		if (!$temprow = faqe_db_fetch_array($result1))
		{
			echo "<a href=\"".do_url_session("categories.php?mode=new&$langvar=$act_lang")."\" target=\"_blank\">$l_new</a>";
		}
		else
		{
?>
<select name="category">
<option value="-1">???</option>
<?php
			do {
				$catname=display_encoded($temprow["categoryname"]);
				$prognr=$temprow["programm"];
				$sql = "select * from ".$tableprefix."_programm where (prognr=$prognr)";
				if(!$result2 = faqe_db_query($sql, $db)) {
					die("Could not connect to the database (3).");
				}
				if($temprow2 = faqe_db_fetch_array($result2))
				{
					$progname=display_encoded($temprow2["programmname"]);
					$proglang=$temprow2["language"];
				}
				else
				{
					$progname=$l_undefined;
					$proglang=$l_none;
				}
				echo "<option value=\"".$temprow["catnr"]."\"";
				if($temprow["catnr"]==$actcat)
					echo " selected";
				echo ">";
				echo "$catname ($progname [$proglang])";
				echo "</option>";
			} while($temprow = faqe_db_fetch_array($result1));
?>
</select>
<?php
		}
?>
</td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_or?> <?php echo $l_subcategory?>:</td><td>
<?php
	if($admin_rights<3)
		$sql = "select cat.categoryname as catname, subcat.catnr as subcatnr, subcat.categoryname as subcatname, prog.programmname, prog.language, prog.prognr from ".$tableprefix."_category cat, ".$tableprefix."_category_admins ca, ".$tableprefix."_subcategory subcat, ".$tableprefix."_programm prog  where cat.catnr = ca.catnr and ca.usernr=$act_usernr and subcat.category=cat.catnr and prog.prognr=cat.programm order by subcat.catnr";
	else
		$sql = "select cat.categoryname as catname, subcat.catnr as subcatnr, subcat.categoryname as subcatname, prog.programmname, prog.language, prog.prognr from ".$tableprefix."_category cat, ".$tableprefix."_subcategory subcat, ".$tableprefix."_programm prog  where subcat.category=cat.catnr and prog.prognr=cat.programm order by subcat.catnr";
	if(!$result = faqe_db_query($sql, $db))
		die("<tr class=\"errorrow\"><td>Could not connect to the database (3).");
	if (!$temprow = faqe_db_fetch_array($result))
	{
		echo "<a href=\"".do_url_session("subcategories.php?mode=new&$langvar=$act_lang")."\" target=\"_blank\">$l_new</a>";
	}
	else
	{
?>
<select name="subcategory" onchange="document.inputform.selprog.value=this.options[this.selectedIndex].value">
<option value="0"><?php echo $l_none?></option>
<?php
		do {
			echo "<option value=\"".$temprow["subcatnr"]."\">";
			$progname=display_encoded($temprow["programmname"]);
			$proglang=$temprow["language"];
			$catname=display_encoded($temprow["catname"]);
			$subcatname=display_encoded($temprow["subcatname"]);
			echo "$subcatname ($catname : $progname [$proglang])";
			echo "</option>";
		} while($temprow = faqe_db_fetch_array($result));
		echo "</select>";
	}
	echo "</td></tr>";
?>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_question?>:
</td><td><textarea class="faqeinput" name="question" cols="50" rows="10"><?php echo $questiontext?></textarea>
<br>
<?php display_bbcode_buttons($l_bbbuttons,"question")?>
<?php echo faq_addopts("qref","faqref","question")?>
</td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_answer?>:
</td><td><textarea class="faqeinput" name="answer" cols="50" rows="10"><?php echo $answertext?></textarea>
<br>
<?php display_bbcode_buttons($l_bbbuttons,"answer")?>
<?php echo faq_addopts("qref","faqref","answer")?>
</td></tr>
<tr class="optionrow"><td align="right" valign="top"><?php echo $l_options?>:</td><td align="left">
<input type="checkbox" name="local_urlautoencode" value="1" <?php if($urlautoencode==1) echo "checked"?>> <?php echo $l_urlautoencode?><br>
<input type="checkbox" name="local_enablespcode" value="1" <?php if($enablespcode==1) echo "checked"?>> <?php echo $l_enablespcode?><br>
<input type="checkbox" name="disablehtml" value="1"> <?php echo $l_disablehtml?><br>
<input type="checkbox" name="transferdel" value="1" <?php if (isset($transferdel)) echo "checked"?>> <?php echo $l_deleteaftertransfer?>
<?php
if($myrow["email"])
{
?>
<br><input type="checkbox" name="mailanswer" value="1" <?php if($userquestionanswermail==1) echo "checked"?>> <?php echo $l_sendanswermail?>
<?php
}
?>
<input type="hidden" name="input_questionnr" value="<?php echo $input_questionnr?>">
</td></tr>
<tr class="actionrow"><td align="center" colspan="2">
<input type="hidden" name="mode" value="add">
<input class="faqebutton" type="submit" value="<?php echo $l_enter?>">&nbsp;&nbsp;<input class="faqebutton" type="submit" name="preview" value="<?php echo $l_preview?>"></td></tr>
</form>
</table></td></tr></table>
<?php
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("userquestions.php?$langvar=$act_lang")."\">$l_userquestions</a></div>";
	}
	if($mode=="add")
	{
		if($admin_rights < 2)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		// Add new FAQ to database
		$errors=0;
		if(!$heading)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_noheading</td></tr>";
			$errors=1;
		}
		if(($category<0) && ($subcategory<=0))
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_nocategory</td></tr>";
			$errors=1;
		}
		if($errors==0)
		{
			if(isset($preview))
			{
				$displayheading=display_encoded($heading);
				$sql = "select prog.*, cat.categoryname from ".$tableprefix."_programm prog, ".$tableprefix."_category cat where prog.prognr=cat.programm and cat.catnr=$category";
				if(!$result = faqe_db_query($sql, $db))
				{
					echo "<tr class=\"errorrow\"><td align=\"center\">";
				    die("Unable to connect to database.");
				}
				if(!$myrow=faqe_db_fetch_array($result))
				{
					$progname=$l_unknown;
					$proglang=$l_unknown;
					$catname=$l_unknown;
				}
				else
				{
					$proglang=$myrow["language"];
					$progname=display_encoded($myrow["programmname"]);
					$catname=display_encoded($myrow["categoryname"]);
				}
				if(!isset($local_urlautoencode))
					$urlautoencode=0;
				else
					$urlautoencode=1;
				if(!isset($local_enablespcode))
					$enablespcode=0;
				else
					$enablespcode=1;
				$displayquestion="";
				$displayanswer="";
				if($question)
				{
					$displayquestion=stripslashes($question);
					if(isset($disablehtml))
					{
						$displayquestion = htmlspecialchars($displayquestion);
						$displayquestion = undo_html_ampersand($displayquestion);
					}
					if($urlautoencode==1)
						$displayquestion = make_clickable($displayquestion);
					if($enablespcode==1)
						$displayquestion = bbencode($displayquestion);
					$displayquestion = do_htmlentities($displayquestion);
					$displayquestion = str_replace("\n", "<BR>", $displayquestion);
					$displayquestion = undo_htmlspecialchars($displayquestion);
				}
				if($answer)
				{
					$displayanswer=stripslashes($answer);
					if(isset($disablehtml))
					{
						$displayanswer = htmlspecialchars($displayanswer);
						$displayanswer = undo_html_ampersand($displayanswer);
					}
					if($urlautoencode==1)
						$displayanswer = make_clickable($displayanswer);
					if($enablespcode==1)
						$displayanswer = bbencode($displayanswer);
					$displayanswer = do_htmlentities($displayanswer);
					$displayanswer = str_replace("\n", "<BR>", $displayanswer);
					$displayanswer = undo_htmlspecialchars($displayanswer);
				}
				echo "<tr><td class=\"headingrow\" align=\"center\" colspan=\"2\"><b>$l_newfaq</b></td></tr>";
				echo "<tr class=\"inforow\"><td align=\"center\" colspan=\"2\">";
				echo "$l_previewprelude:";
				echo "</td></tr>";
				echo "<tr class=\"displayrow\"><td align=\"center\" colspan=\"2\">";
				echo "<b>$progname [$proglang] : $catname : $displayheading</b></td></tr>";
				echo "<tr class=\"displayrow\"><td align=\"right\" width=\"20%\" valign=\"top\">";
				echo "$l_question:</td>";
				echo "<td align=\"left\" width=\"80%\">$displayquestion</td></tr>";
				echo "<tr class=\"displayrow\"><td align=\"right\" width=\"20%\" valign=\"top\">";
				echo "$l_answer:</td>";
				echo "<td align=\"left\" width=\"80%\">$displayanswer</td></tr>";
?>
<form method="post" action="<?php echo $act_script_url?>">
<?php
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
		if(is_konqueror())
			echo "<tr><td></td></tr>";
?>
<tr class="actionrow" align="center"><td colspan="2">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="mode" value="add">
<?php
if(isset($disablehtml))
	echo "<input type=\"hidden\" name=\"disablehtml\" value=\"1\">";
if(isset($local_urlautoencode))
	echo "<input type=\"hidden\" name=\"local_urlautoencode\" value=\"1\">";
if(isset($local_enablespcode))
	echo "<input type=\"hidden\" name=\"local_enablespcode\" value=\"1\">";
if(isset($transferdel))
	echo "<input type=\"hidden\" name=\"transferdel\" value=\"1\">";
if(isset($mailanswer))
	echo "<input type=\"hidden\" name=\"mailanswer\" value=\"1\">";
?>
<input type="hidden" name="input_questionnr" value="<?php echo $input_questionnr?>">
<input type="hidden" name="heading" value="<?php echo do_htmlentities($heading)?>">
<input type="hidden" name="category" value="<?php echo $category?>">
<input type="hidden" name="question" value="<?php echo do_htmlentities($question)?>">
<input type="hidden" name="input_questionnr" value="<?php echo $input_questionnr?>">
<input type="hidden" name="answer" value="<?php echo do_htmlentities($answer)?>">
<input class="faqebutton" type="submit" value="<?php echo $l_enter?>">&nbsp;&nbsp;
<input class="faqebutton" type="button" value="<?php echo $l_back ?>" onclick="self.history.back();">
</td></form></tr></table></td></tr></table>
<?php
			}
			else
			{
				if(isset($transferdel))
				{
					if(isset($refaction))
					{
						if($refaction==1)
						{
							$sql="update ".$tableprefix."_questions set questionref=0 where questionref=$input_questionnr";
							if(!$result = faqe_db_query($sql, $db))
							{
								echo "<tr class=\"errorrow\"><td align=\"center\">";
							    die("Unable to connect to database.");
							}
						}
						if($refaction==2)
						{
							$sql="delete from ".$tableprefix."_questions where questionref=$input_questionnr";
							if(!$result = faqe_db_query($sql, $db))
							{
								echo "<tr class=\"errorrow\"><td align=\"center\">";
							    die("Unable to connect to database.");
							}
						}
					}
					$sql="select count(questionnr) from ".$tableprefix."_questions where questionref=$input_questionnr";
					if(!$result = faqe_db_query($sql, $db))
					{
						echo "<tr class=\"errorrow\"><td align=\"center\">";
					    die("Unable to connect to database.");
					}
					if ($temprow = faqe_db_fetch_array($result))
						$refcount=$temprow["count(questionnr)"];
					else
						$refcount=0;
					if($refcount>0)
					{
?>
<tr class="inforow"><form method="post" action="<?php echo $act_script_url?>">
<?php
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
		if(is_konqueror())
			echo "<tr><td></td></tr>";
?>
<td align="center" colspan="2"><?php echo $l_qrefexist?></td></tr>
<tr class="inputrow"><td width="20%">&nbsp;</td><td><input type="radio" name="refaction" value="1"> <?php echo $l_qrefremove?></td></tr>
<tr class="inputrow"><td width="20%">&nbsp;</td><td><input type="radio" name="refaction" value="2"> <?php echo $l_qrefdel?>
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="mode" value="add">
<?php
if(isset($disablehtml))
	echo "<input type=\"hidden\" name=\"disablehtml\" value=\"1\">";
if(isset($local_urlautoencode))
	echo "<input type=\"hidden\" name=\"local_urlautoencode\" value=\"1\">";
if(isset($local_enablespcode))
	echo "<input type=\"hidden\" name=\"local_enablespcode\" value=\"1\">";
?>
<input type="hidden" name="transferdel" value="1">
<input type="hidden" name="input_questionnr" value="<?php echo $input_questionnr?>">
<input type="hidden" name="heading" value="<?php echo do_htmlentities($heading)?>">
<input type="hidden" name="category" value="<?php echo $category?>">
<input type="hidden" name="question" value="<?php echo do_htmlentities($question)?>">
<input type="hidden" name="answer" value="<?php echo do_htmlentities($answer)?>">
</td></tr><tr class="actionrow" align="center">
<td colspan="2"><input class="faqebutton" type="submit" value="<?php echo $l_ok?>">
</td></form></tr></table></td></tr></table>
<?php
						include('./trailer.php');
						exit;
					}
				}
				$heading=stripslashes($heading);
				$heading=strip_tags($heading);
				$heading=do_htmlentities($heading);
				$heading=addslashes($heading);
				$editor=$userdata["username"];
				if($editor==-1)
					$editor="unknown";
				else
					$editor=addslashes($editor);
				if(!isset($local_urlautoencode))
					$urlautoencode=0;
				else
					$urlautoencode=1;
				if(!isset($local_enablespcode))
					$enablespcode=0;
				else
					$enablespcode=1;
				if($question)
				{
					$question=stripslashes($question);
					if(isset($disablehtml))
					{
						$question=htmlspecialchars($question);
						$question=undo_html_ampersand($question);
					}
					if($urlautoencode==1)
						$question = make_clickable($question);
					if($enablespcode==1)
						$question = bbencode($question);
					$question = do_htmlentities($question);
					$question = str_replace("\n", "<BR>", $question);
					$question=addslashes($question);
				}
				if($answer)
				{
					$answer=stripslashes($answer);
					if(isset($disablehtml))
					{
						$answer = htmlspecialchars($answer);
						$answer = undo_html_ampersand($answer);
					}
					if($urlautoencode==1)
						$answer = make_clickable($answer);
					if($enablespcode==1)
						$answer = bbencode($answer);
					$answer = do_htmlentities($answer);
					$answer = str_replace("\n", "<BR>", $answer);
					$answer=addslashes($answer);
				}
				if(isset($mailanswer))
				{
					$sql = "select * from ".$tableprefix."_questions where questionnr=$input_questionnr";
					if(!$result = faqe_db_query($sql, $db))
					{
						echo "<tr class=\"errorrow\"><td align=\"center\">";
					    die("Unable to connect to database.");
					}
					if(!$myrow=faqe_db_fetch_array($result))
					{
						echo "<tr class=\"errorrow\"><td align=\"center\">";
						die("No such userquestion.");
					}
					if($myrow["email"])
					{
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
						include_once('./language/userquestions_'.$questionlang.'.php');
						$asc_answer=undo_htmlentities(stripslashes($answer));
						$asc_answer=str_replace("<BR>","\n",$asc_answer);
						$asc_answer=strip_tags($asc_answer);
						$asc_question=undo_htmlentities(stripslashes($myrow["question"]));
						$asc_question=str_replace("<BR>","\n",$asc_question);
						$asc_question=strip_tags($asc_question);
						$asc_mailbody = str_replace("{date}",$displaydate,$l_uq_answer_prelude)."\n".$asc_question;
						$asc_mailbody.= "\n\n".$l_uq_answer_is."\n".$asc_answer."\n\n";
						$asc_mailbody = str_replace("\n","\r\n",$asc_mailbody);
						$html_mailbody="<span style=\"font-face: $FontFace; font-size: $FontSize1;\">";
						$html_mailbody.=str_replace("{date}",$displaydate,$l_uq_answer_prelude)."<br>";
						$questiontext = stripslashes($question);
						$questiontext = undo_htmlspecialchars($questiontext);
						$questiontext = str_replace("{lang}","$langvar=$act_lang",$questiontext);
						$questiontext = str_replace("{url_faqengine}",$url_faqengine,$questiontext);
						$questiontext = str_replace("{onlynewfaq}",0,$questiontext);
						$questiontext = str_replace("{bbc_code}",$l_bbccode,$questiontext);
						$questiontext = str_replace("{bbc_quote}",$l_bbcquote,$questiontext);
						$answertext = stripslashes($answer);
						$answertext = undo_htmlspecialchars($answertext);
						$answertext = str_replace("{lang}","$langvar=$act_lang",$answertext);
						$answertext = str_replace("{url_faqengine}",$url_faqengine,$answertext);
						$answertext = str_replace("{onlynewfaq}",0,$answertext);
						$answertext = str_replace("{bbc_code}",$l_bbccode,$answertext);
						$answertext = str_replace("{bbc_quote}",$l_bbcquote,$answertext);
						$html_mailbody.=$questiontext."</span><br><br>";
						$html_mailbody.="<span style=\"font-face: $FontFace; font-size: $FontSize1;\">";
						$html_mailbody.=$l_uq_answer_is."<br>".$answertext."</span><br><br>";
						if(strlen($userdata["signature"])>1)
						{
							$asc_sig=str_replace("\n","\r\n",$userdata["signature"]);
							$asc_sig=strip_tags($asc_sig);
							$asc_mailbody.= "---\r\n";
							$asc_mailbody.=$asc_sig;
							$asc_mailbody.="\r\n\r\n";
							$sigtext=str_replace("\n","<br>",$userdata["signature"]);
							$html_mailbody.= "---<br>";
							$html_mailbody.="<span style=\"font-face: $FontFace; font-size: $FontSize1;\">";
							$html_mailbody.=display_encoded($sigtext)."</span><br>";
						}
						$asc_mailbody.="\r\n";
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
						$mail->setSubject(str_replace("{refid}",$input_questionnr,$l_uq_answer_subject));
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
				$actdate = date("Y-m-d");
				$sql = "UPDATE ".$tableprefix."_category SET numfaqs = numfaqs + 1 WHERE (catnr = $category)";
				@faqe_db_query($sql, $db);
				$sql = "INSERT INTO ".$tableprefix."_data (heading, category, questiontext, answertext, editor, editdate) ";
				$sql .="VALUES ('$heading', $category, '$question', '$answer', '$editor', '$actdate')";
				if(!$result = faqe_db_query($sql, $db))
				{
					echo "<tr class=\"errorrow\"><td align=\"center\">";
				    die("Unable to add FAQ to database.");
				}
				if(isset($transferdel))
				{
					$sql = "delete from ".$tableprefix."_questions where questionnr=$input_questionnr";
					if(!$result = faqe_db_query($sql, $db))
					{
						echo "<tr class=\"errorrow\"><td align=\"center\">";
					    die("Unable to delete user question from database.");
					}
				}
				echo "<tr class=\"displayrow\" align=\"center\"><td>";
				echo "$l_faqadded";
				if(isset($transferdel))
					echo "<br>$l_questiondeleted";
				echo "</td></tr></table></td></tr></table>";
				echo "<div class=\"bottombox\" align=\"center\">";
				echo "<a href=\"".do_url_session("faq.php?$langvar=$act_lang")."\">$l_faqlist</a>";
				echo "&nbsp;&nbsp;&nbsp;&nbsp;";
				echo "<a href=\"".do_url_session("userquestions.php?$langvar=$act_lang")."\">$l_userquestions</a>";
				echo "</div>";

			}
		}
		else
		{
			echo "<tr class=\"actionrow\" align=\"center\"><td>";
			echo "<a href=\"javascript:history.back()\">$l_back</a>";
			echo "</td></tr></table></td></tr></table>";
		}
	}
}
else
{
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="errorrow"><td align="center"><?php echo $l_callingerror?></td></tr>
</table></td></tr></table>
<?php
}
include('./trailer.php');
?>