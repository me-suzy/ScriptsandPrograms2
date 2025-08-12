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
if(!$result = mysql_query($sql, $db)) {
    die("Could not connect to the database.");
}
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
	$newbugnotify=$myrow["newbugnotify"];
	$entriesperpage=$myrow["entriesperpage"];
	$checkrefs=$myrow["checkrefs"];
	$refchkaffects=$myrow["refchkaffects"];
	$msendlimit=$myrow["msendlimit"];
	$emaildisplay=$myrow["emaildisplay"];
	if(!$progsysmail)
		$progsysmail="progsys@foo.bar";
}
else
	die("Layout not set up");
if(!isset($lang) || !$lang)
	$lang=$default_lang;
if(!language_avail($lang))
	die ("Language <b>$lang</b> not configured");
include('language/lang_'.$lang.'.php');
if(($checkrefs==1) && bittst($refchkaffects,BIT_3))
{
	if(!ref_allowed())
		die("Direct linking from this site ($HTTP_REFERER) not allowed");
}
else if($checkrefs==2)
{
	if(ref_forbidden())
		die("Direct linking from this site ($HTTP_REFERER) not allowed");
}
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
$enablebugentries=$myrow["enablebugentries"];
$publishnewbugentries=$myrow["publishnewbugentries"];

if((!$pageheader) && (!$headerfile))
	$usecustomheader=0;
if((!$pagefooter) && (!$footerfile))
	$usecustomfooter=0;
?>
<html>
<head>
<?php
if(file_exists("metadata.php"))
	include ("metadata.php");
?>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $contentcharset?>">
<title><?php echo $l_bugtraq_heading?></title>
<?php
echo "<link rel=stylesheet href=\"progsys.css\" type=\"text/css\">";
if($stylesheet)
	echo "<link rel=stylesheet href=\"$stylesheet\" type=\"text/css\">";
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
<TD ALIGN="CENTER" VALIGN="MIDDLE" colspan="2"><font face="<?php echo $FontFace?>" size="<?php echo $FontSize3?>" color="<?php echo $HeadingFontColor?>"><b><?php echo $l_bugtraq_heading?></b></font></td>
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
		exit;
	}
}
?>
<?php
if(isset($mode))
{
	if($mode=="newbug")
	{
		$progsql = "select * from ".$tableprefix."_programm where progid='$prog' and language='$lang'";
		if(!$progresult = mysql_query($progsql, $db))
		    die("<tr bgcolor=\"$heading_bgcolor\" align=\"center\"><td>Could not connect to the database.");
		if(!$progrow=mysql_fetch_array($progresult))
			die ("<tr bgcolor=\"$group_bgcolor\" align=\"center\"><td>$l_noentries</td></tr>");
		$progname=$progrow["programmname"];
		$prognr=$progrow["prognr"];
?>
<tr BGCOLOR="<?php echo $heading_bgcolor?>" ALIGN="CENTER">
<TD ALIGN="CENTER" VALIGN="MIDDLE" colspan="2"><font face="<?php echo $FontFace?>" size="<?php echo $FontSize2?>" color="<?php echo $SubheadingFontColor?>"><b><?php echo $l_programm.": ".$progname?></b></font>
</td></tr>
<tr BGCOLOR="<?php echo $heading_bgcolor?>" ALIGN="CENTER">
<TD ALIGN="CENTER" VALIGN="MIDDLE" colspan="2"><font face="<?php echo $FontFace?>" size="<?php echo $FontSize2?>" color="<?php echo $SubheadingFontColor?>"><b><?php echo $l_enternewbug?></b></font></td></tr>
</table></td></tr>
<tr><TD BGCOLOR="<?php echo $table_bgcolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<form method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="prognr" value="<?php echo $prognr?>">
<input type="hidden" name="lang" value="<?php echo $lang?>">
<input type="hidden" name="prog" value="<?php echo $prog?>">
<tr bgcolor="<?php echo $row_bgcolor?>">
<td align="right" width="20%"><font face="<?php echo $FontFace?>" size="<?php echo $FontSize2?>" color="<?php echo $FontColor?>"><?php echo $l_yourname?>:</font></td>
<td><input class="psysinput" type="text" name="custname" size="40" maxlength="120"></td></tr>
<tr bgcolor="<?php echo $row_bgcolor?>">
<td align="right" width="20%"><font face="<?php echo $FontFace?>" size="<?php echo $FontSize2?>" color="<?php echo $FontColor?>"><?php echo $l_sendermail?>:</font></td>
<td><input class="psysinput" type="text" name="custmail" size="40" maxlength="120"></td></tr>
<tr bgcolor="<?php echo $row_bgcolor?>">
<td align="right" width="20%"><font face="<?php echo $FontFace?>" size="<?php echo $FontSize2?>" color="<?php echo $FontColor?>"><?php echo $l_usedversion?>:</font></td>
<td><input class="psysinput" type="text" name="usedversion" size="10" maxlength="10"></td></tr>
<tr bgcolor="<?php echo $row_bgcolor?>">
<td align="right" width="20%" valign="top"><font face="<?php echo $FontFace?>" size="<?php echo $FontSize2?>" color="<?php echo $FontColor?>"><?php echo $l_bugreport?>:</font></td>
<td><font face="<?php echo $FontFace?>" size="<?php echo $FontSize2?>" color="<?php echo $FontColor?>"><textarea class="psysinput" name="bugtext" cols="40" rows="10" wrap="virtual"></textarea></font></td></tr>
<input type="hidden" name="mode" value="postbug">
<tr bgcolor="<?php echo $heading_bgcolor?>"><td colspan="2" align="center"><font face="<?php echo $FontFace?>" size="<?php echo $FontSize2?>" color="<?php echo $FontColor?>"><input class="psysbutton" type="submit" name="submit" value="<?php echo $l_submit?>"></font></td></tr>
</form>
</table></td></tr></table>
<?php
	}
	if($mode=="postbug")
	{
		$errors=0;
		if(!$custname)
		{
			echo "<tr bgcolor=\"#c0c0c0\" align=\"center\"><td>";
			echo "$l_noname</td></tr>";
			$errors=1;
		}
		if(!$custmail || !validate_email($custmail))
		{
			echo "<tr bgcolor=\"#c0c0c0\" align=\"center\"><td>";
			echo "$l_invalidemail</td></tr>";
			$errors=1;
		}
		if(!$usedversion)
		{
			echo "<tr bgcolor=\"#c0c0c0\" align=\"center\"><td>";
			echo "$l_noversion</td></tr>";
			$errors=1;
		}
		if(!$bugtext)
		{
			echo "<tr bgcolor=\"#c0c0c0\" align=\"center\"><td>";
			echo "$l_nobugreport</td></tr>";
			$errors=1;
		}
		if($errors==0)
		{
			$bugtext = htmlentities($bugtext);
			$bugtext = str_replace("\n", "<BR>", $bugtext);
			$bugtext = str_replace("\r", "", $bugtext);
			$bugtext=addslashes($bugtext);
			$custname=addslashes($custname);
			$custmail=addslashes($custmail);
			$actdate = date("Y-m-d");
			$sql = "insert into ".$tableprefix."_bugtraq (programm, custname, custmail, enterdate, state, bugtext, usedversion, enterip, lastedited) ";
			$sql .="values ($prognr, '$custname', '$custmail', '$actdate', 0, '$bugtext', '$usedversion', '".get_userip()."', '$actdate')";
			if(!$result = mysql_query($sql, $db))
			    die("<tr bgcolor=\"$heading_bgcolor\" align=\"center\"><td>Could not connect to the database.".mysql_error());
			$bugnr=mysql_insert_id();
			if($newbugnotify==1)
			{
				$sql = "select u.email from ".$tableprefix."_admins u, ".$tableprefix."_programm_admins pa ";
				$sql .=" where (pa.prognr='$prognr' and u.usernr=pa.usernr) or u.rights>2 group by u.usernr";
				if(!$result = mysql_query($sql, $db)) {
				    die("<tr bgcolor=\"$heading_bgcolor\" align=\"center\"><td>Could not connect to the database.".mysql_error());
				}
				if($myrow=mysql_fetch_array($result))
				{
					$subject = "Info from ProgSys";
					$displaydate=date("Y-m-d H:i:s");
					$mailmsg = "$displaydate:$crlf";
					$mailmsg .= "Neuer Fehlerreport #$bugnr$crlf";
					$mailmsg .= "New bugreport #$bugnr$crlf$crlf";
					do{
						if(strlen($myrow["email"])>1)
						{
							@set_time_limit($msendlimit);
							$mail = new htmlMimeMail();
							$mail->setCrlf($crlf);
							$mail->setTextCharset($contentcharset);
							$mail->setText($mailmsg);
							$mail->setSubject($subject);
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
					}while($myrow = mysql_fetch_array($result));
				}
			}
			echo "<tr bgcolor=\"#c0c0c0\" align=\"center\"><td>";
			echo "$l_bugadded";
			echo "</td></tr></table></td></tr></table>";
			echo "<div align=\"center\"><font face=\"$FontFace\" Size=\"$FontSize2\"><a href=\"$act_script_url?lang=$lang&amp;prog=$prog\">$l_buglist</a></font></div>";
		}
		else
		{
			echo "<tr bgcolor=\"#94AAD6\" align=\"center\"><td>";
			echo "<a href=\"javascript:history.back()\">$l_back</a>";
			echo "</td></tr></table></td></tr></table>";
		}

	}
}
else
{
$progsql = "select * from ".$tableprefix."_programm where progid='$prog'";
if(!$progresult = mysql_query($progsql, $db))
    die("<tr bgcolor=\"$heading_bgcolor\" align=\"center\"><td>Could not connect to the database.");
if(!$progrow=mysql_fetch_array($progresult))
	die ("<tr bgcolor=\"$group_bgcolor\" align=\"center\"><td>$l_noentries</td></tr>");
$progname=$progrow["programmname"];
$entriesfound=0;
?>
<tr BGCOLOR="<?php echo $heading_bgcolor?>" ALIGN="CENTER">
<TD ALIGN="CENTER" VALIGN="MIDDLE" colspan="2"><font face="<?php echo $FontFace?>" size="<?php echo $FontSize2?>" color="<?php echo $SubheadingFontColor?>"><b><?php echo $l_programm.": ".$progname?></b></font>
</td></tr>
<?php
	if(isset($filterstate) && ($filterstate>=0))
		$sql = "select bug.* from ".$tableprefix."_bugtraq bug, ".$tableprefix."_programm prog where bug.programm=prog.prognr and prog.progid='$prog' and bug.state='$filterstate' order by enterdate desc";
	else
	{
		$filterstate=-1;
		$sql = "select bug.* from ".$tableprefix."_bugtraq bug, ".$tableprefix."_programm prog where bug.programm=prog.prognr and prog.progid='$prog' ";
		if($publishnewbugentries==0)
			$sql.="and bug.state!=0 ";
		$sql.="order by enterdate desc, bugnr desc";
	}
	if(!$result = mysql_query($sql, $db))
	    die("<tr bgcolor=\"$heading_bgcolor\" align=\"center\"><td>Could not connect to the database.".mysql_error());
	$numentries=mysql_num_rows($result);
	if($numentries>0)
	{
		if($entriesperpage>0)
		{
?>
<TR BGCOLOR="<?php echo $heading_bgcolor?>" ALIGN="CENTER">
<TD ALIGN="CENTER" VALIGN="MIDDLE" colspan="2"><font face="<?php echo $FontFace?>" size="<?php echo $FontSize5?>" color="<?php echo $SubheadingFontColor?>">
<?php
			if(isset($start) && ($start>0) && ($numentries>$entriesperpage))
			{
				$sql .=" limit $start,$entriesperpage";
			}
			else
			{
				$sql .=" limit $entriesperpage";
				$start=0;
			}
			if(!$result = mysql_query($sql, $db))
			    die("Unable to connect to database.".mysql_error());
			if(mysql_num_rows($result)>0)
			{
				if(($entriesperpage+$start)>$numentries)
					$displayresults=$numentries;
				else
					$displayresults=($entriesperpage+$start);
				$displaystart=$start+1;
				$displayend=$displayresults;
				echo "<b>$l_page ".ceil(($start/$entriesperpage)+1)."/".ceil(($numentries/$entriesperpage))."</b><br><b>($l_entries $displaystart - $displayend $l_of $numentries)</b>";
			}
			else
				echo "&nbsp;";
			echo "</font></td></tr>";
		}
		if(($entriesperpage>0) && ($numentries>$entriesperpage))
		{
			echo "<tr bgcolor=\"$heading_bgcolor\"><td align=\"center\" colspan=\"2\">";
			echo "<font face=\"$FontFace\" size=\"$FontSize1\" color=\"$SubheadingFontColor\"><b>$l_page</b> ";
			if(floor(($start+$entriesperpage)/$entriesperpage)>1)
			{
				echo "<a href=\"$act_script_url?lang=$lang&amp;prog=$prog&amp;start=0";
				if(isset($filterstate))
					echo "&amp;filterstate=$filterstate";
				echo "\"><b>[&lt;&lt;]</b></a> ";
				echo "<a href=\"$act_script_url?lang=$lang&amp;prog=$prog&amp;start=".($start-$entriesperpage);
				if(isset($filterstate))
					echo "&amp;filterstate=$filterstate";
				echo "\"><b>[&lt;]</b></a> ";
			}
			for($i=1;$i<($numentries/$entriesperpage)+1;$i++)
			{
				if(floor(($start+$entriesperpage)/$entriesperpage)!=$i)
				{
					echo "<a href=\"$act_script_url?lang=$lang&amp;prog=$prog&amp;start=".(($i-1)*$entriesperpage);
					if(isset($filterstate))
						echo "&amp;filterstate=$filterstate";
					echo "\"><b>[$i]</b></a> ";
				}
				else
					echo "<b>($i)</b> ";
			}
			if($start < (($i-2)*$entriesperpage))
			{
				echo "<a href=\"$act_script_url?lang=$lang&amp;prog=$prog&amp;start=".($start+$entriesperpage);
				if(isset($filterstate))
					echo "&amp;filterstate=$filterstate";
				echo "\"><b>[&gt;]</b></a> ";
				echo "<a href=\"$act_script_url?lang=$lang&amp;prog=$prog&amp;start=".(($i-2)*$entriesperpage);
				if(isset($filterstate))
					echo "&amp;filterstate=$filterstate";
				echo "\"><b>[&gt;&gt;]</b></a> ";
			}
			echo "</font></td></tr>";
		}
		while($myrow=mysql_fetch_array($result))
		{
			$entriesfound++;
			list($year, $month, $day) = explode("-", $myrow["enterdate"]);
			if($month>0)
				$displaydate=date($dateformat,mktime(0,0,0,$month,$day,$year));
			else
				$displaydate="";
			echo "<tr bgcolor=\"$group_bgcolor\">";
			echo "<td align=\"left\" colspan=\"2\"><b>#".$myrow["bugnr"]."</b> ".$myrow["custname"]." ";
			if(!bittst($emaildisplay,BIT_1))
				echo "(".$myrow["custmail"]."), ";
			echo $displaydate."</td></tr>";
			echo "<tr bgcolor=\"$row_bgcolor\"><td align=\"right\" width=\"20%\">";
			echo "$l_version:</td>";
			echo "<td align=\"left\">";
			echo $myrow["usedversion"];
			echo "</td></tr>";
			echo "<tr bgcolor=\"$row_bgcolor\"><td align=\"right\" width=\"20%\">";
			echo "$l_bug:</td>";
			echo "<td align=\"left\">";
			$bugtext=stripslashes($myrow["bugtext"]);
			$bugtext = undo_htmlspecialchars($bugtext);
			echo $bugtext;
			echo "</td></tr>";
			echo "<tr bgcolor=\"$row_bgcolor\"><td align=\"right\" width=\"20%\">";
			echo "$l_state:</td>";
			echo "<td align=\"left\">";
			echo $l_states[$myrow["state"]]."</td></tr>";
			if($myrow["fixversion"])
			{
				echo "<tr bgcolor=\"$row_bgcolor\"><td align=\"right\" width=\"20%\">";
				echo "$l_fixversion:";
				echo "<td align=\"left\">";
				echo $myrow["fixversion"]."</td></tr>";
			}
			if($myrow["fixtext"])
			{
				echo "<tr bgcolor=\"$row_bgcolor\"><td align=\"right\" width=\"20%\">";
				echo "$l_fix:</td>";
				echo "<td align=\"left\">";
				$fixtext=stripslashes($myrow["fixtext"]);
				$fixtext = undo_htmlspecialchars($fixtext);
				echo $fixtext;
				echo "</td></tr>";
			}
		}
		if(($entriesperpage>0) && ($numentries>$entriesperpage))
		{
			echo "<tr bgcolor=\"$heading_bgcolor\"><td align=\"center\" colspan=\"2\">";
			echo "<font face=\"$FontFace\" size=\"$FontSize1\" color=\"$SubheadingFontColor\"><b>$l_page</b> ";
			if(floor(($start+$entriesperpage)/$entriesperpage)>1)
			{
				echo "<a href=\"$act_script_url?lang=$lang&amp;prog=$prog&amp;start=0";
				if(isset($filterstate))
					echo "&amp;filterstate=$filterstate";
				echo "\"><b>[&lt;&lt;]</b></a> ";
				echo "<a href=\"$act_script_url?lang=$lang&amp;prog=$prog&amp;start=".($start-$entriesperpage);
				if(isset($filterstate))
					echo "&amp;filterstate=$filterstate";
				echo "\"><b>[&lt;]</b></a> ";
			}
			for($i=1;$i<($numentries/$entriesperpage)+1;$i++)
			{
				if(floor(($start+$entriesperpage)/$entriesperpage)!=$i)
				{
					echo "<a href=\"$act_script_url?lang=$lang&amp;prog=$prog&amp;start=".(($i-1)*$entriesperpage);
					if(isset($filterstate))
						echo "&amp;filterstate=$filterstate";
					echo "\"><b>[$i]</b></a> ";
				}
				else
					echo "<b>($i)</b> ";
			}
			if($start < (($i-2)*$entriesperpage))
			{
				echo "<a href=\"$act_script_url?lang=$lang&amp;prog=$prog&amp;start=".($start+$entriesperpage);
				if(isset($filterstate))
					echo "&amp;filterstate=$filterstate";
				echo "\"><b>[&gt;]</b></a> ";
				echo "<a href=\"$act_script_url?lang=$lang&amp;prog=$prog&amp;start=".(($i-2)*$entriesperpage);
				if(isset($filterstate))
					echo "&amp;filterstate=$filterstate";
				echo "\"><b>[&gt;&gt;]</b></a> ";
			}
			echo "</font></td></tr>";
		}
	}
	else
		echo "<tr bgcolor=\"$group_bgcolor\" align=\"center\"><td>$l_noentries</td></tr>";
?>
</table></td></tr></table>
<div align="center">
<form method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="lang" value="<?php echo $lang?>">
<input type="hidden" name="prog" value="<?php echo $prog?>">
<?php echo $l_statefilter?>: <select name="filterstate">
<option value="-1"><?php echo $l_all?></option>
<?php
if($publishnewbugentries==1)
	$startstate=0;
else
	$startstate=1;
for($i=$startstate;$i<count($l_states);$i++)
{
	echo "<option value=\"$i\"";
	if($i==$filterstate)
		echo " selected";
	echo ">".$l_states[$i]."</option>";
}
?>
</select>
<input class="psysbutton" name="submit" type="submit" value="<?php echo $l_ok?>"></form>
</div>
<?php
	if($enablebugentries==1)
	{
?>
<div align="center"><font face="<?php echo $FontFace?>" Size="<?php echo $FontSize2?>"><a href="<?php echo $act_script_url?>?mode=newbug&amp;prog=<?php echo $prog?>&amp;lang=<?php echo $lang?>"><?php echo $l_enternewbug?></a></font></div>
<?php
	}
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
?>
</body></html>
