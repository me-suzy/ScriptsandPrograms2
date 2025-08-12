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
require('config.php');
include('functions.php');
require_once('./includes/htmlMimeMail.inc');
if($use_smtpmail)
{
	require_once('./includes/smtp.inc');
	require_once('./includes/RFC822.inc');
}
echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">";
$sql = "select * from ".$tableprefix."_layout where (layoutnr=1)";
if(!$result = mysql_query($sql, $db))
	die("Could not connect to the database.");
if ($myrow = mysql_fetch_array($result))
{
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
	$page_bgcolor=$myrow["pagebg"];
	$HeadingFontColor=$myrow["headingfontcolor"];
	$SubheadingFontColor=$myrow["subheadingfontcolor"];
	$GroupFontColor=$myrow["groupfontcolor"];
	$LinkColor=$myrow["linkcolor"];
	$VLinkColor=$myrow["vlinkcolor"];
	$ALinkColor=$myrow["alinkcolor"];
	$TableDescFontColor=$myrow["tabledescfontcolor"];
	$dateformat=$myrow["dateformat"];
	$progsysmail=$myrow["progsysmail"];
	$server_timezone=$myrow["timezone"];
	$mailsig=$myrow["mailsig"];
	$checkrefs=$myrow["checkrefs"];
	$refchkaffects=$myrow["refchkaffects"];
	$msendlimit=$myrow["msendlimit"];
	$newrefnotify=$myrow["newrefnotify"];
	if(!$progsysmail)
		$progsysmail="progsys@foo.bar";
	$autoapprove=$myrow["autoapprove"];
}
else
	die("Layout not set up");
if(($checkrefs==1) && bittst($refchkaffects,BIT_4))
{
	if(!ref_allowed())
		die("Direct linking from this site ($HTTP_REFERER) not allowed");
}
else if($checkrefs==2)
{
	if(ref_forbidden())
		die("Direct linking from this site ($HTTP_REFERER) not allowed");
}
if(!isset($lang) || !$lang)
	$lang=$default_lang;
if(!language_avail($lang))
	die ("Language <b>$lang</b> not configured");
include('./language/lang_'.$lang.'.php');
if(!isset($prog))
	die($l_callingerror);
$sql = "select * from ".$tableprefix."_programm where progid='$prog' and language='$lang'";
if(!$result = mysql_query($sql, $db))
	die("Could not connect to the database.");
if(!$myrow=mysql_fetch_array($result))
	die("No such programm");
$stylesheet=$myrow["stylesheet"];
$usecustomheader=$myrow["usecustomheader"];
$usecustomfooter=$myrow["usecustomfooter"];
$headerfile=$myrow["headerfile"];
$footerfile=$myrow["footerfile"];
$pageheader=$myrow["pageheader"];
$pagefooter=$myrow["pagefooter"];
if((!$pageheader) && (!$headerfile))
	$usecustomheader=0;
if((!$pagefooter) && (!$footerfile))
	$usecustomfooter=0;
$errors=0;
$errmsg="";
if(!isset($submit))
{
	$input_url="";
	$contactname="";
	$contactmail="";
	$sitename="";
	$heardfrom="";
	$publish=1;
	$note="";
}
?>
<html>
<head>
<?php
if(file_exists("metadata.php"))
	include ("metadata.php");
?>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $contentcharset?>">
<title><?php echo $l_references_heading?></title>
<?php
echo"<link rel=stylesheet href=\"progsys.css\" type=\"text/css\">";
if($stylesheet)
	echo"<link rel=stylesheet href=\"$stylesheet\" type=\"text/css\">";
?>
</head>
<body bgcolor="<?php echo $page_bgcolor?>" link="<?php echo $LinkColor?>" vlink="<?php echo $VLinkColor?>" alink="<?php echo $ALinkColor?>" text="<?php echo $FontColor?>">
<?php
if($usecustomheader==1)
{
	if($headerfile)
		include($headerfile);
	echo $pageheader;
}
?>
<table width="<?php echo $TableWidth?>" border="0" CELLPADDING="1" CELLSPACING="0" ALIGN="CENTER" VALIGN="TOP">
<tr><TD BGCOLOR="<?php echo $table_bgcolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<TR BGCOLOR="<?php echo $heading_bgcolor?>" ALIGN="CENTER">
<TD ALIGN="CENTER" VALIGN="MIDDLE" colspan="2"><font face="<?php echo $FontFace?>" size="<?php echo $FontSize3?>" color="<?php echo $HeadingFontColor?>"><b><?php echo $l_references_heading?></b></font></td>
<?php
$sql = "select * from ".$tableprefix."_misc";
if(!$result = mysql_query($sql, $db)) {
    die("<tr bgcolor=\"$heading_bgcolor\" align=\"center\"><td>Could not connect to the database.");
}
if ($myrow = mysql_fetch_array($result))
{
	if($myrow["shutdown"]==1)
	{
?>
</tr></table></td></tr>
<tr><TD BGCOLOR="<?php echo $table_bgcolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<tr BGCOLOR="<?php echo $row_bgcolor?>" ALIGN="CENTER">
<TD ALIGN="CENTER" VALIGN="MIDDLE"><font face="<?php echo $FontFace?>" size="<?php echo $FontSize2?>" color="<?php echo $SubheadingFontColor?>"></td>
<?php
		$shutdowntext=stripslashes($myrow["shutdowntext"]);
		$shutdowntext = undo_htmlspecialchars($shutdowntext);
		echo $shutdowntext;
		echo "</font></td></tr></table></td></tr></table>";
		echo "<br><div align=\"center\"><font face=\"$FontFace\" SIZE=\"$FontSize4\">";
		echo "<span class=\"timezone\">$l_timezone_note ".timezonename($server_timezone);
		$gmtoffset=tzgmtoffset($server_timezone);
		if($gmtoffset)
			echo " (".$gmtoffset.")";
		echo "</span><br>";
		echo "$l_powered_by $copyright_url, $copyright_note</font></div>";
		if($usecustomfooter==1)
		{
			if($footerfile)
				include($footerfile);
			echo $pagefooter;
		}
		exit;
	}
}
$progsql = "select * from ".$tableprefix."_programm where progid='$prog' and language='$lang'";
if(!$progresult = mysql_query($progsql, $db))
	die("<tr bgcolor=\"$heading_bgcolor\" align=\"center\"><td>Could not connect to the database.");
if(!$progrow=mysql_fetch_array($progresult))
{
	$progsql = "select * from ".$tableprefix."_programm where progid='$prog'";
	if(!$progresult = mysql_query($progsql, $db))
	    die("<tr bgcolor=\"$heading_bgcolor\" align=\"center\"><td>Could not connect to the database.");
	if(!$progrow=mysql_fetch_array($progresult))
		die ("<tr bgcolor=\"$group_bgcolor\" align=\"center\"><td>$l_noentries</td></tr>");
}
$progname=$progrow["programmname"];
$prognr=$progrow["prognr"];
$disableref=$progrow["disableref"];
?>
<tr BGCOLOR="<?php echo $heading_bgcolor?>" ALIGN="CENTER">
<TD ALIGN="CENTER" VALIGN="MIDDLE" colspan="2"><font face="<?php echo $FontFace?>" size="<?php echo $FontSize2?>" color="<?php echo $SubheadingFontColor?>"><b><?php echo $l_programm.": ".$progname?></b></font>
</td></tr>
</table></td></tr>
<?php
if(isset($pinlost))
{
?>
<tr><TD BGCOLOR="<?php echo $table_bgcolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<?php
	$errors=0;
	if(!$email)
	{
		$errmsg.="<li>$l_noemail";
		$errors=1;
	}
	if($errors==0)
	{
		$sql = "select * from ".$tableprefix."_programm where progid='$prog' and language='$lang'";
		if(!$result = mysql_query($sql, $db))
			die("Could not connect to the database.");
		if(!$myrow=mysql_fetch_array($result))
			die($l_callingerror);
		$prognr=$myrow["prognr"];
		$progname=$myrow["programmname"];
		$sql = "select * from ".$tableprefix."_references where contactmail='".addslashes($email)."' and programm='$prognr'";
		if(!$result = mysql_query($sql, $db))
		    die("Could not connect to the database.");
		if(!$myrow=mysql_fetch_array($result))
		{
			echo "<tr bgcolor=\"$group_bgcolor\"><td align=\"center\">";
			echo "<font face=\"$FontFace\" size=\"$FontSize2\">";
			die($l_nosuchentry);
		}
		if($myrow["contactname"])
		{
			$mailmsg=$l_salutation1." ".$myrow["contactname"].",".$crlf.$crlf;
			$toadr = "\"".$myrow["contactname"]."\" <".$myrow["contactmail"].">";
		}
		else
		{
			$mailmsg=$l_salutation2.",$crlf$crlf";
			$toadr = $myrow["contactmail"];
		}
		$mailmsg.=$l_pinmail;
		$mailmsg.=$myrow["pin"].$crlf;
		$mailmsg.=$l_programm.": ".$progname;
		$mailmsg.=$crlf.$crlf;
		$mailmsg.=$l_greeting.$crlf.$crlf;
		$mailmsg.="---$crlf";
		$mailmsg.=str_replace("\n",$crlf,$mailsig);
		$mailmsg.=$crlf.$crlf;
		$subject=$l_pinsubject." ".$progname;
		@set_time_limit($msendlimit);
		$mail = new htmlMimeMail();
		$mail->setCrlf($crlf);
		$mail->setTextCharset($contentcharset);
		$mail->setSubject($subject);
		$mail->setFrom($progsysmail);
	   	$mail->setText($mailmsg);
		$currentreceiver=array($toadr);
		if($use_smtpmail)
		{
			$mail->setSMTPParams($smtpserver,$smtpport,NULL,$smtpauth,$smtpuser,$smtppasswd);
	        	$mail->send($currentreceiver, "smtp");
		}
		else
	    		$mail->send($currentreceiver, "mail");
?>
<tr bgcolor="<?php echo $group_bgcolor?>"><td align="center">
<font face="<?php echo $FontFace?>" size="<?php echo $FontSize2?>"><?php echo $l_pinsent?></font></td></tr>
</table></td></tr></table>
<div align="center"><font face="<?php echo $FontFace?>" Size="<?php echo $FontSize2?>"><a href="<?php echo $act_script_url?>?display=1&amp;lang=<?php echo $lang?>&amp;prog=<?php echo $prog?>"><?php echo $l_reflist?></font></a></div>
<?php
	}
	else
	{
?>
<tr bgcolor="<?php echo $group_bgcolor?>"><td align="center">
<font face="<?php echo $FontFace?>" size="<?php echo $FontSize2?>"><?php echo $l_inputerrors?><ul><?php echo $errmsg?></ul></font></td></tr>
<tr bgcolor="<?php echo $heading_bgcolor?>"><td align="center">
<font face="<?php echo $FontFace?>" size="<?php echo $FontSize2?>"><a href="javascript:history.back()"><?php echo $l_back?></a></font></td></tr></table></td></tr></table>
<?php
	}
	echo "<br><div align=\"center\"><font face=\"$FontFace\" SIZE=\"$FontSize4\">";
	echo "<span class=\"timezone\">$l_timezone_note ".timezonename($server_timezone);
	$gmtoffset=tzgmtoffset($server_timezone);
	if($gmtoffset)
		echo " (".$gmtoffset.")";
	echo "</span><br>";
	echo "$l_powered_by $copyright_url, $copyright_note</font></div>";
	if($usecustomfooter==1)
	{
		if($footerfile)
			include($footerfile);
		echo $pagefooter;
	}
	echo "</body></html>";
	exit;
}
if(isset($mode))
{
	if($mode=="add")
	{
		if(!isset($do_publish))
			$publish=0;
		else
			$publish=1;
		if(!$contactmail || !validate_email($contactmail))
		{
			$errmsg.="<li>$l_noemail";
			$errors=1;
		}
		if(!$input_url)
		{
			$errmsg.="<li>$l_nourl";
			$errors=1;
		}
		if(!$sitename)
		{
			$errmsg.="<li>$l_nositename";
			$errors=1;
		}
		if($errors==0)
		{
			if(substr($input_url,0,7)=="http://")
				$input_url=substr($input_url,7,strlen($input_url)-7);
			if(substr($input_url,0,8)=="https://")
				$input_url=substr($input_url,8,strlen($input_url)-8);
			$pin=0;
			do{
				$maximum=9999999999;
				if($maximum>mt_getrandmax())
					$maximum=mt_getrandmax();
				mt_srand((double)microtime()*1000000);
				$pin=mt_rand(10000,$maximum);
				$sql = "select * from ".$tableprefix."_references where pin='$pin'";
				if(!$result = mysql_query($sql, $db))
					die("Could not connect to the database.");
			}while($myrow=mysql_fetch_array($result));
			$input_url=addslashes($input_url);
			$contactname=addslashes($contactname);
			$contactmail=addslashes($contactmail);
			$headfrom=addslashes($heardfrom);
			$note=addslashes($note);
			$sql = "INSERT into ".$tableprefix."_references (url, sitename, publish, contactmail, contactname, enter_lang, pin, programm, approved, prot) ";
			$sql .="VALUES ('$input_url', '$sitename', $publish, '$contactmail', '$contactname', '$lang', '$pin', '$input_prognr', $autoapprove, '$prot')";
			if(!$result = mysql_query($sql, $db))
				die("Could not connect to the database.");
			if($newrefnotify==1)
			{
				$sql="select user.* from ".$tableprefix."_programm_admins pa, ".$tableprefix."_admins user where user.usernr=pa.usernr and pa.prognr=$input_prognr";
				if(!$result = mysql_query($sql, $db))
					die("Could not connect to the database.");
				while($myrow=mysql_fetch_array($result))
				{
					$userlang=$myrow["language"];
					$usermail=$myrow["email"];
					include('language/newref_'.$userlang.'.php');
					@set_time_limit($msendlimit);
					$mail = new htmlMimeMail();
					$mail->setCrlf($crlf);
					$mail->setTextCharset($contentcharset);
					$mail->setText($l_newrefmail);
					$mail->setSubject($l_newrefsubject);
					$mail->setFrom($progsysmail);
					$currentreceiver=array($myrow["email"]);
					@set_time_limit($msendlimit);
					if($use_smtpmail)
					{
						$mail->setSMTPParams($smtpserver,$smtpport,NULL,$smtpauth,$smtpuser,$smtppasswd);
						$mail->send($currentreceiver, "smtp");
					}
					else
						$mail->send($currentreceiver, "mail");
				}
			}
?>
<tr><TD BGCOLOR="<?php echo $table_bgcolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<tr bgcolor="<?php echo $group_bgcolor?>"><td align="center">
<font face="<?php echo $FontFace?>" size="<?php echo $FontSize3?>"><?php echo $l_entryadded?></font></td></tr>
<tr bgcolor="<?php echo $group_bgcolor?>"><td align="center">
<?php echo "$l_pin1: $pin<br>$l_pin2"?></td></tr></table></td></tr></table>
<div align="center"><font face="<?php echo $FontFace?>" Size="<?php echo $FontSize2?>"><a href="<?php echo $act_script_url?>?display=1&amp;lang=<?php echo $lang?>&amp;prog=<?php echo $prog?>"><?php echo $l_reflist?></font></a></div>
<?php
			echo "<br><div align=\"center\"><font face=\"$FontFace\" SIZE=\"$FontSize4\">";
			echo "<span class=\"timezone\">$l_timezone_note ".timezonename($server_timezone);
			$gmtoffset=tzgmtoffset($server_timezone);
			if($gmtoffset)
				echo " (".$gmtoffset.")";
			echo "</span><br>";
			echo "$l_powered_by $copyright_url, $copyright_note</font></div>";
			if($usecustomfooter==1)
			{
				if($footerfile)
					include($footerfile);
				echo $pagefooter;
			}
			echo "</body></html>";
			exit;
		}
	}
	if($mode=="edit")
	{
?>
<tr><TD BGCOLOR="<?php echo $table_bgcolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<tr bgcolor="<?php echo $group_bgcolor?>"><td align="center" colspan="2">
<b><?php echo $l_updateentry?></b></td></tr>
<form method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="lang" value="<?php echo $lang?>">
<input type="hidden" name="prog" value="<?php echo $prog?>">
<tr bgcolor="<?php echo $row_bgcolor?>"><td align="right" width="30%"><?php echo $l_email?>:</td>
<td><input class="psysinput" type="text" name="email" size="40" maxlength="250"></td></tr>
<tr bgcolor="<?php echo $row_bgcolor?>"><td align="right" width="30%"><?php echo $l_pin?>:</td>
<td><input class="psysinput" type="text" name="pin" size="10" maxlength="10"></td></tr>
<tr bgcolor="<?php echo $heading_bgcolor?>"><td colspan="2" align="center"><input type="hidden" name="mode" value="doedit"><input class="psysbutton" type="submit" name="submit" value="<?php echo $l_ok?>">
&nbsp;&nbsp;&nbsp;&nbsp;
<input class="psysbutton" type="submit" name="pinlost" value="<?php echo $l_pinlost?>">
</td></tr>
</form>
</table></td></tr></table>
<?php
		echo "<br><div align=\"center\"><font face=\"$FontFace\" SIZE=\"$FontSize4\">";
		echo "<span class=\"timezone\">$l_timezone_note ".timezonename($server_timezone);
		$gmtoffset=tzgmtoffset($server_timezone);
		if($gmtoffset)
			echo " (".$gmtoffset.")";
		echo "</span><br>";
		echo "$l_powered_by $copyright_url, $copyright_note</font></div>";
		if($usecustomfooter==1)
		{
			if($footerfile)
				include($footerfile);
			echo $pagefooter;
		}
?>
</body></html>
<?php
		exit;
	}
	if($mode=="doedit")
	{
		$email=addslashes($email);
		$sql = "select * from ".$tableprefix."_references where pin='$pin' and contactmail='$email'";
		if(!$result = mysql_query($sql, $db))
		    die("Could not connect to the database.");
?>
<tr><TD BGCOLOR="<?php echo $table_bgcolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<tr bgcolor="<?php echo $group_bgcolor?>"><td align="center" colspan="2">
<b><?php echo $l_updateentry?></b></td></tr>
<?php
		if(!$myrow=mysql_fetch_array($result))
		{
			echo "<tr bgcolor=\"$group_bgcolor\"><td colspan=\"2\" align=\"center\">";
			die($l_nosuchentry);
		}
?>
<form method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="lang" value="<?php echo $lang?>">
<input type="hidden" name="input_id" value="<?php echo $myrow["id"]?>">
<input type="hidden" name="prog" value="<?php echo $prog?>">
<tr bgcolor="<?php echo $row_bgcolor?>"><td align="right" width="40%"><?php echo $l_sitename?>:</td>
<td><input class="psysinput" type="text" name="sitename" size="40" maxlength="250" value="<?php echo $myrow["sitename"]?>"></td></tr>
<tr bgcolor="<?php echo $row_bgcolor?>"><td align="right"><?php echo $l_siteurl?>:</td>
<td><select name="prot">
<?php
echo "<option value=\"http\"";
if($myrow["prot"]=="http")
	echo "selected";
echo ">http</option>";
echo "<option value=\"https\"";
if($myrow["prot"]=="https")
	echo "selected";
echo ">https</option>";
echo "</select><b>://</b>";
?>
<input class="psysinput" type="text" name="input_url" size="40" maxlength="245" value="<?php echo $myrow["url"]?>"></td></tr>
<tr bgcolor="<?php echo $row_bgcolor?>"><td align="right"><?php echo $l_contact?>:</td>
<td><input class="psysinput" type="text" name="contactname" value="<?php echo $myrow["contactname"]?>" size="40" maxlength="250"></td></tr>
<tr bgcolor="<?php echo $row_bgcolor?>"><td align="right"><?php echo $l_email?>:</td>
<td><input class="psysinput" type="text" name="contactmail" value="<?php echo $myrow["contactmail"]?>" size="40" maxlength="250"></td></tr>
<tr bgcolor="<?php echo $row_bgcolor?>"><td align="right"><input type="checkbox" name="do_publish" value="1"
<?php if($myrow["publish"]==1) echo "checked"?>></td>
<td><?php echo $l_publish?></td>
</tr>
<tr bgcolor="<?php echo $heading_bgcolor?>"><td colspan="2" align="center"><input type="hidden" name="mode" value="update"><input class="psysbutton" type="submit" name="submit" value="<?php echo $l_submit?>"></td></tr>
</form>
</table></tr></td></table>
<?php
		echo "<br><div align=\"center\"><font face=\"$FontFace\" SIZE=\"$FontSize4\">";
		echo "<span class=\"timezone\">$l_timezone_note ".timezonename($server_timezone);
		$gmtoffset=tzgmtoffset($server_timezone);
		if($gmtoffset)
			echo " (".$gmtoffset.")";
		echo "</span><br>";
		echo "$l_powered_by $copyright_url, $copyright_note</font></div>";
		if($usecustomfooter==1)
		{
			if($footerfile)
				include($footerfile);
			echo $pagefooter;
		}
		echo "</body></html>";
		exit;
	}
	if($mode=="update")
	{
?>
<tr><TD BGCOLOR="<?php echo $table_bgcolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<tr bgcolor="<?php echo $group_bgcolor?>"><td align="center" colspan="2">
<b><?php echo $l_updateentry?></b></td></tr>
<?php
		if(!isset($do_publish))
			$publish=0;
		else
			$publish=1;
		if(!$input_url)
		{
			$errmsg.="<li>$l_nourl";
			$errors=1;
		}
		if(!$sitename)
		{
			$errmsg.="<li>$l_nositename";
			$errors=1;
		}
		if(!$contactmail || !validate_email($contactmail))
		{
			$errmsg.="<li>$l_noemail";
			$errors=1;
		}
		if($errors==0)
		{
			$input_url=addslashes($input_url);
			$contactname=addslashes($contactname);
			$contactmail=addslashes($contactmail);
			$sql = "UPDATE ".$tableprefix."_references set url='$input_url', publish=$publish, sitename='$sitename', ";
			$sql .="contactmail = '$contactmail', contactname='$contactname', prot='$prot' ";
			$sql .="where id = $input_id";
			if(!$result = mysql_query($sql, $db))
			    die("Could not connect to the database.".mysql_error());
			else
			{
				echo "<tr bgcolor=\"$group_bgcolor\"><td align=\"center\">";
				echo "$l_entryupdated</td></tr>";
				echo "</table></td></tr></table>";
				echo "<div align=\"center\"><font face=\"$FontFace\" Size=\"$FontSize2\"><a href=\"$act_script_url?display=1&amp;lang=$lang&amp;prog=$prog\">$l_reflist</font></a></div>";
				exit;
			}
		}
		else
		{
				echo "<tr bgcolor=\"$group_bgcolor\"><td align=\"center\">$l_inputerrors<ul>$errmsg</ul></td></tr>";
				echo "<tr bgcolor=\"$heading_bgcolor\"><td align=\"center\">";
				echo "<font face=\"$FontFace\" size=\"$FontSize2\"><a href=\"javascript:history.back()\">$l_back</a></font></td></tr></table></td></tr></table>";
		}
		echo "<br><div align=\"center\"><font face=\"$FontFace\" SIZE=\"$FontSize4\">";
		echo "<span class=\"timezone\">$l_timezone_note ".timezonename($server_timezone);
		$gmtoffset=tzgmtoffset($server_timezone);
		if($gmtoffset)
			echo " (".$gmtoffset.")";
		echo "</span><br>";
		echo "$l_powered_by $copyright_url, $copyright_note</font></div>";
		if($usecustomfooter==1)
		{
			if($footerfile)
				include($footerfile);
			echo $pagefooter;
		}
		echo "</body></html>";
		exit;
	}
}
if(isset($postbroken))
{
?>
<tr><TD BGCOLOR="<?php echo $table_bgcolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<?php
	$sql = "select * from ".$tableprefix."_references where (id=$postbroken)";
	if(!$result = mysql_query($sql, $db))
	    die("Could not connect to the database.");
	if (!$myrow = mysql_fetch_array($result))
	{
		echo "<tr><td align=\"center\">calling error</td></tr>";
	}
	else
	{
?>
<tr bgcolor="<?php echo $group_bgcolor?>"><td align="center" colspan="2">
<?php echo $l_brokenprelude?>
<br>
<?php echo "<b>".$myrow["prot"]."://".$myrow["url"]."</b>"?></td></tr>
<form method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="lang" value="<?php echo $lang?>">
<input type="hidden" name="prog" value="<?php echo $prog?>">
<input type="hidden" name="brokenlink" value="<?php echo $postbroken?>">
<tr bgcolor="<?php echo $row_bgcolor?>"><td align="right" width="40%"><?php echo $l_sendermail?>:</td>
<td><input class="psysinput" type="text" name="reportermail" size="40" maxlength="120"></td></tr>
<tr bgcolor="<?php echo $row_bgcolor?>"><td align="right" width="40%" valign="top"><?php echo $l_reason?>:</td>
<td>
<?php
	for($i=0;$i<count($l_broken_reasons);$i++)
	{
		echo "<input type=\"radio\" name=\"reason\" value=\"$i\"";
		if($i==4)
			echo " checked";
		echo ">".$l_broken_reasons[$i]."<br>";
	}
?>
</td></tr>
<tr bgcolor="<?php echo $heading_bgcolor?>"><td align="center" colspan="2"><input class="psysbutton" type="submit" value="<?php echo $l_sendreport?>"></td></tr>
</form></table></td></tr></table>
<?php
	}
	echo "<br><div align=\"center\"><font face=\"$FontFace\" SIZE=\"$FontSize4\">";
	echo "<span class=\"timezone\">$l_timezone_note ".timezonename($server_timezone);
	$gmtoffset=tzgmtoffset($server_timezone);
	if($gmtoffset)
		echo " (".$gmtoffset.")";
	echo "</span><br>";
	echo "$l_powered_by $copyright_url, $copyright_note</font></div>";
	if($usecustomfooter==1)
	{
		if($footerfile)
			include($footerfile);
		echo $pagefooter;
	}
	echo "</body></html>";
	exit;
}
if(isset($brokenlink))
{
?>
<tr><TD BGCOLOR="<?php echo $table_bgcolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<?php
	$sql = "select ref.*, prog.programmname from ".$tableprefix."_references ref, ".$tableprefix."_programm prog where (id=$brokenlink) and prog.prognr=ref.programm";
	if(!$result = mysql_query($sql, $db))
		die("Could not connect to the database.");
	if (!$myrow = mysql_fetch_array($result))
	{
		echo "<tr><td align=\"center\">calling error</td></tr>";
	}
	else
	{
			$sql2 = "select u.* from ".$tableprefix."_programm_admins pa, ".$tableprefix."_admins u where pa.prognr=".$myrow["programm"]." and u.usernr = pa.usernr";
			if(!$result2 = mysql_query($sql2, $db))
			    die("Could not connect to the database.");
			while($temprow=mysql_fetch_array($result2))
			{
				$adminlang=$temprow["language"];
				include('language/broken_'.$adminlang.'.php');
				$mailmsg = $l_bm_mailstart;
				$mailmsg.= "$l_bm_sitename: ".$myrow["sitename"]."$crlf";
				$mailmsg.= "$l_bm_url: ".$myrow["prot"]."://".$myrow["url"]."$crlf";
				$mailmsg.= "$l_bm_email: ".$myrow["contactmail"]."$crlf";
				$mailmsg.= "$l_bm_contact: ".$myrow["contactname"]."$crlf";
				$mailmsg.= "$l_bm_language: ".$myrow["enter_lang"]."$crlf";
				$mailmsg.= "$l_bm_reporttime: ".date("d.m.Y H:i:s")."$crlf";
				$mailmsg.= "$l_bm_reportermail: ".$reportermail."$crlf";
				$mailmsg.= "$l_bm_reporterip: ".get_userip()."$crlf";
				$mailmsg.= "$l_bm_reason: ".$l_bm_reasons[$reason]."$crlf";
				$mailmsg.= "$l_bm_program: ".$myrow["programmname"]."$crlf";
				$mail = new htmlMimeMail();
				$mail->setCrlf($crlf);
				$mail->setTextCharset($contentcharset);
				$mail->setText($mailmsg);
				$mail->setSubject($l_bm_subject);
				$mail->setFrom($progsysmail);
				$currentreceiver=array($temprow["email"]);
				@set_time_limit($msendlimit);
				if($use_smtpmail)
				{
					$mail->setSMTPParams($smtpserver,$smtpport,NULL,$smtpauth,$smtpuser,$smtppasswd);
					$mail->send($currentreceiver, "smtp");
				}
				else
					$mail->send($currentreceiver, "mail");
			}
			echo "<tr bgcolor=\"$group_bgcolor\"><td align=\"center\">$l_brokendone</td></tr>";

	}
	echo "<tr bgcolor=\"$group_bgcolor\"><td align=\"center\"><font face=\"$FontFace\" Size=\"$FontSize2\"><a href=\"$act_script_url?display=1&amp;lang=$lang&amp;prog=$prog\">$l_back</a></font></td></tr>";
	echo "</table></td></tr></table>";
	echo "<br><div align=\"center\"><font face=\"$FontFace\" SIZE=\"$FontSize4\">";
	echo "<span class=\"timezone\">$l_timezone_note ".timezonename($server_timezone);
	$gmtoffset=tzgmtoffset($server_timezone);
	if($gmtoffset)
		echo " (".$gmtoffset.")";
	echo "</span><br>";
	echo "$l_powered_by $copyright_url, $copyright_note</font></div>";
	if($usecustomfooter==1)
	{
		if($footerfile)
			include($footerfile);
		echo $pagefooter;
	}
	echo "</body></html>";
	exit;
}
if(isset($display))
{
?>
<tr><TD BGCOLOR="<?php echo $table_bgcolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<tr bgcolor="<?php echo $group_bgcolor?>"><td align="center" colspan="2">
<?php echo $l_reflistprelude?></td></tr>
<?php
	$tempsql="select * from ".$tableprefix."_programm where progid='$prog'";
	if(!$tempresult = mysql_query($tempsql, $db))
		die("<tr bgcolor=\"$row_bgcolor?>><td align=\"center\">Could not connect to the database (1)".mysql_error());
	if (!$temprow = mysql_fetch_array($tempresult))
	{
		echo "<tr bgcolor=\"$row_bgcolor\"><td align=\"center\">$l_noentries</td></tr>";
	}
	else
	{
		$sql = "select * from ".$tableprefix."_references where publish=1 and approved=1 and (";
		$firstpart=1;
		do{
			if($firstpart==1)
				$firstpart=0;
			else
				$sql.="or ";
			$sql.="programm=";
			$sql.=$temprow["prognr"];
			$sql.=" ";
		} while($temprow=mysql_fetch_array($tempresult));
		$sql .=") order by sitename asc";
		if(!$result = mysql_query($sql, $db))
			die("<tr bgcolor=\"$row_bgcolor?>><td align=\"center\">Could not connect to the database.");
		if (!$myrow = mysql_fetch_array($result))
		{
			echo "<tr bgcolor=\"$row_bgcolor\"><td align=\"center\">$l_noentries</td></tr>";
		}
		else
		{
			do {
				$siteurl=$myrow["prot"]."://".$myrow["url"];
				$sitename=$myrow["sitename"];
				if(!$sitename)
					$sitename=$myrow["url"];
				echo "<tr bgcolor=\"$row_bgcolor\"><td align=\"left\" width=\"60%\"><font face=\"$FontFace\" Size=\"$FontSize2\"><a href=\"$siteurl\" target=\"_blank\"><b>$sitename</b></a></font></td>";
				echo "<td align=\"center\" width=\"40%\"><font face=\"$FontFace\" Size=\"$FontSize2\">(<a href=\"$act_script_url?postbroken=".$myrow["id"]."&amp;lang=$lang&amp;prog=$prog\">$l_reportbroken</a>)</font></td>";
				echo "</tr>";
			} while($myrow = mysql_fetch_array($result));
		}
	}
?>
<tr bgcolor="<?php echo $group_bgcolor?>"><td colspan="2" align="center"><font face="<?php echo $FontFace?>" Size="<?php echo $FontSize2?>"><?php echo $l_alsousing?></font></td></tr>
<tr bgcolor="<?php echo $heading_bgcolor?>"><td colspan="2" align="center"><font face="<?php echo $FontFace?>" Size="<?php echo $FontSize2?>"><a href="<?php echo $act_script_url?>?lang=<?php echo $lang?>&amp;prog=<?php echo $prog?>"><?php echo $l_addsite?></a><a href="<?php echo $act_script_url?>?mode=edit&amp;lang=<?php echo $lang?>&amp;prog=<?php echo $prog?>">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $l_updateentry?></a></td></tr>
</table></td></tr></table>
<?php
echo "<br><div align=\"center\"><font face=\"$FontFace\" SIZE=\"$FontSize4\">";
echo "<span class=\"timezone\">$l_timezone_note ".timezonename($server_timezone);
$gmtoffset=tzgmtoffset($server_timezone);
if($gmtoffset)
	echo " (".$gmtoffset.")";
echo "</span><br>";
echo "$l_powered_by $copyright_url, $copyright_note</font></div>";
if($usecustomfooter==1)
{
	if($footerfile)
		include($footerfile);
	echo $pagefooter;
}
?>
</body></html>
<?php
	exit;
}
if($disableref==1)
{
?>
<tr><TD BGCOLOR="<?php echo $table_bgcolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<tr bgcolor="<?php echo $group_bgcolor?>"><td colspan="2" align="center">
<font face="<?php echo $FontFace?>" size="<?php echo $FontSize2?>" color="<?php echo $FontColor?>">
<?php echo $l_function_disabled?>
</td></tr></table></td></tr></table>
<?php
echo "<br><div align=\"center\"><font face=\"$FontFace\" SIZE=\"$FontSize4\">";
echo "<span class=\"timezone\">$l_timezone_note ".timezonename($server_timezone);
$gmtoffset=tzgmtoffset($server_timezone);
if($gmtoffset)
	echo " (".$gmtoffset.")";
echo "</span><br>";
echo "$l_powered_by $copyright_url, $copyright_note</font></div>";
if($usecustomfooter==1)
{
	if($footerfile)
		include($footerfile);
	echo $pagefooter;
}
?>
</body></html>
<?php
	exit;
}
?>
<tr><TD BGCOLOR="<?php echo $table_bgcolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<tr bgcolor="<?php echo $group_bgcolor?>"><td colspan="2" align="center">
<font face="<?php echo $FontFace?>" size="<?php echo $FontSize2?>" color="<?php echo $FontColor?>">
<?php
if($errors==0)
	echo "$l_inputprelude";
else
{
	echo $l_inputerrors;
	echo "<ul>";
	echo $errmsg;
	echo "</ul>";
}
?>
</font>
</td></tr>
<form method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="lang" value="<?php echo $lang?>">
<input type="hidden" name="input_prognr" value="<?php echo $prognr?>">
<input type="hidden" name="prog" value="<?php echo $prog?>">
<tr bgcolor="<?php echo $row_bgcolor?>"><td align="right" width="30%"><font face="<?php echo $FontFace?>" size="<?php echo $FontSize2?>" color="<?php echo $FontColor?>"><?php echo $l_sitename?>:</font></td>
<td><font face="<?php echo $FontFace?>" size="<?php echo $FontSize2?>" color="<?php echo $FontColor?>"><input class="psysinput" type="text" name="sitename" size="40" maxlength="250" value="<?php echo $sitename?>"></font></td></tr>
<tr bgcolor="<?php echo $row_bgcolor?>"><td align="right"><font face="<?php echo $FontFace?>" size="<?php echo $FontSize2?>" color="<?php echo $FontColor?>"><?php echo $l_siteurl?>:</font></td>
<td><font face="<?php echo $FontFace?>" size="<?php echo $FontSize2?>" color="<?php echo $FontColor?>"><select name="prot">
<?php
echo "<option value=\"http\"";
if($myrow["prot"]=="http")
	echo "selected";
echo ">http</option>";
echo "<option value=\"https\"";
if($myrow["prot"]=="https")
	echo "selected";
echo ">https</option>";
echo "</select><b>://</b>";
?>
<input class="psysinput" type="text" name="input_url" size="40" maxlength="245" value="<?php echo $input_url?>"></font></td></tr>
<tr bgcolor="<?php echo $row_bgcolor?>"><td align="right"><font face="<?php echo $FontFace?>" size="<?php echo $FontSize2?>" color="<?php echo $FontColor?>"><?php echo $l_contact?>:</font></td>
<td><font face="<?php echo $FontFace?>" size="<?php echo $FontSize2?>" color="<?php echo $FontColor?>"><input class="psysinput" type="text" name="contactname" value="<?php echo $contactname?>" size="40" maxlength="250"></font></td></tr>
<tr bgcolor="<?php echo $row_bgcolor?>"><td align="right"><font face="<?php echo $FontFace?>" size="<?php echo $FontSize2?>" color="<?php echo $FontColor?>"><?php echo $l_email?>:</font></td>
<td><font face="<?php echo $FontFace?>" size="<?php echo $FontSize2?>" color="<?php echo $FontColor?>"><input class="psysinput" type="text" name="contactmail" value="<?php echo $contactmail?>" size="40" maxlength="250"></font></td></tr>
<tr bgcolor="<?php echo $row_bgcolor?>"><td align="right"><input type="checkbox" name="do_publish" value="1"
<?php if($publish==1) echo "checked"?>></td>
<td><font face="<?php echo $FontFace?>" size="<?php echo $FontSize2?>" color="<?php echo $FontColor?>"><?php echo $l_publish?></font></td>
</tr>
<tr bgcolor="<?php echo $row_bgcolor?>"><td align="right" valign="top"><font face="<?php echo $FontFace?>" size="<?php echo $FontSize2?>" color="<?php echo $FontColor?>"><?php echo $l_heardfrom?>:</font></td>
<td align="left"><font face="<?php echo $FontFace?>" size="<?php echo $FontSize2?>" color="<?php echo $FontColor?>"><input class="psysinput" type="text" name="heardfrom" value="<?php echo $heardfrom?>" size="40" maxlength="240"></font></td></tr>
<tr bgcolor="<?php echo $row_bgcolor?>"><td align="right" valign="top"><font face="<?php echo $FontFace?>" size="<?php echo $FontSize2?>" color="<?php echo $FontColor?>"><?php echo $l_note2us?>:</font></td>
<td align="left"><font face="<?php echo $FontFace?>" size="<?php echo $FontSize2?>" color="<?php echo $FontColor?>"><textarea class="psysinput" name="note" rows="5" cols="30"><?php echo $note?></textarea></font></td></tr>
<tr BGCOLOR="<?php echo $heading_bgcolor?>"><td colspan="2" align="center"><font face="<?php echo $FontFace?>" size="<?php echo $FontSize2?>" color="<?php echo $FontColor?>"><input type="hidden" name="mode" value="add"><input class="psysbutton" type="submit" name="submit" value="<?php echo $l_submit?>"></font></td></tr>
</form>
</table></td></tr></table>
<?php
echo "<br><div align=\"center\"><font face=\"$FontFace\" SIZE=\"$FontSize4\">";
echo "<span class=\"timezone\">$l_timezone_note ".timezonename($server_timezone);
$gmtoffset=tzgmtoffset($server_timezone);
if($gmtoffset)
	echo " (".$gmtoffset.")";
echo "</span><br>";
echo "$l_powered_by $copyright_url, $copyright_note</font></div>";
if($usecustomfooter==1)
{
	if($footerfile)
		include($footerfile);
	echo $pagefooter;
}
?>
</body></html>
