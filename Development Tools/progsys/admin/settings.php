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
if(!isset($lang) || !$lang)
	$lang=$admin_lang;
include('./language/lang_'.$lang.'.php');
require('./auth.php');
$page_title=$l_settings_title;
$page="settings";
require('./heading.php');
$checked_pic="gfx/checked.gif";
$unchecked_pic="gfx/unchecked.gif";
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<?php
if($admin_rights < 3)
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
	die("$l_functionnotallowed");
}
if(isset($submit))
{
	if($admin_rights < 3)
	{
		echo "<tr class=\"errorrow\"><td align=\"center\">";
		die("$l_functionnotallowed");
	}
	if(isset($autothumbs))
		$autogenthumbs=1;
	else
		$autogenthumbs=0;
	if(isset($autocheckms))
		$automscheck=1;
	else
		$automscheck=0;
	if(isset($storefilter))
		$admstorefilter=1;
	else
		$admstorefilter=0;
	if(isset($filterattop))
		$topfilter=1;
	else
		$topfilter=0;
	$emaildisplay=0;
	if(isset($confirmdel))
		$admdelconfirm=1;
	else
		$admdelconfirm=0;
	if(isset($bugtraqhideemail))
		$emaildisplay=setbit($emaildisplay,BIT_1);
	if(isset($refnotify))
		$newrefnotify=1;
	else
		$newrefnotify=0;
	if(isset($reqnotify))
		$newreqnotify=1;
	else
		$newreqnotify=0;
	if(isset($alwaysapproved))
		$autoapprove=1;
	else
		$autoapprove=0;
	$refchkaffects=0;
	if(isset($refdownload))
		$refchkaffects=setbit($refchkaffects,BIT_1);
	if(isset($refchangelog))
		$refchkaffects=setbit($refchkaffects,BIT_2);
	if(isset($refbugtraq))
		$refchkaffects=setbit($refchkaffects,BIT_3);
	if(isset($refreferences))
		$refchkaffects=setbit($refchkaffects,BIT_4);
	if(isset($refrequests))
		$refchkaffects=setbit($refchkaffects,BIT_5);
	if(isset($refsubscribe))
		$refchkaffects=setbit($refchkaffects,BIT_6);
	if(isset($reftodo))
		$refchkaffects=setbit($refchkaffects,BIT_7);
	if(isset($dologincount))
		$watchlogins=1;
	else
		$watchlogins=0;
	if(isset($enablecustomheader))
		$usecustomheader=1;
	else
		$usecustomheader=0;
	if(isset($enablecustomfooter))
		$usecustomfooter=1;
	else
		$usecustomfooter=0;
	if(isset($enableautourl))
		$urlautoencode=1;
	else
		$urlautoencode=0;
	if(isset($allowspcode))
		$enablespcode=1;
	else
		$enablespcode=0;
	if(isset($enablefreemailer))
		$nofreemailer=0;
	else
		$nofreemailer=1;
	if(isset($dofailednotify))
		$enablefailednotify=1;
	else
		$enablefailednotify=0;
	if(isset($allowhostresolve))
		$enablehostresolve=1;
	else
		$enablehostresolve=0;
	if(isset($enablemenubar))
		$usemenubar=1;
	else
		$usemenubar=0;
	if(isset($bugnotify))
		$newbugnotify=1;
	else
		$newbugnotify=0;
	$witherrors=false;
	$errmsg="";
	if(!is_numeric($new_loginlimit))
	{
		$witherrors=true;
		$errmsg.="<li>".str_replace("{field}",$l_loginlimit,$l_hastobenumeric)."</li>";
		
	}
	if(!is_numeric($entriesperpage))
	{
		$witherrors=true;
		$errmsg.="<li>".str_replace("{field}",$l_entriesperpage,$l_hastobenumeric)."</li>";
		
	}
	if(!is_numeric($input_msendlimit))
	{
		$witherrors=true;
		$errmsg.="<li>".str_replace("{field}",$l_msendlimit,$l_hastobenumeric)."</li>";
		
	}
	if(!is_numeric($thumbs_maxx))
	{
		$witherrors=true;
		$errmsg.="<li>".str_replace("{field}",$l_thumbnails." - ".$l_maxx,$l_hastobenumeric)."</li>";
		
	}
	if(!is_numeric($thumbs_maxy))
	{
		$witherrors=true;
		$errmsg.="<li>".str_replace("{field}",$l_thumbnails." - ".$l_maxy,$l_hastobenumeric)."</li>";
		
	}
	if(!is_numeric($thumbs_numcols))
	{
		$witherrors=true;
		$errmsg.="<li>".str_replace("{field}",$l_thumbs_numcols,$l_hastobenumeric)."</li>";
		
	}
	if($witherrors)
	{
		echo "<tr class=\"errorrow\" align=\"center\"><td>";
		echo "<ul>";
		echo $errmsg;
		echo "</ul>";
		echo "</td></tr></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"avascript:history.back()\">$l_back</a></div>";
		exit;
	}
	if($new==1)
	{
		$sql = "INSERT INTO ".$tableprefix."_layout (layoutnr, headingbg, bgcolor1, bgcolor2, bgcolor3, pagebg, tablewidth, fontface, fontsize1, fontsize2, fontsize3, fontsize4, fontcolor, headingfontcolor, subheadingfontcolor, linkcolor, vlinkcolor, alinkcolor, groupfontcolor, tabledescfontcolor, fontsize5, dateformat, watchlogins, urlautoencode, enablespcode, nofreemailer, enablefailednotify, timezone, loginlimit, enablehostresolve, usemenubar, newbugnotify, progsysmail, mailsig, entriesperpage, checkrefs, refchkaffects, autoapprove, msendlimit, newreqnotify, newrefnotify, emaildisplay, admdelconfirm, homepageurl, homepagedesc, topfilter, psysmailname, admstorefilter, automscheck, thumbs_maxx, thumbs_maxy, thumbs_numcols, autogenthumbs, dateformatlong) ";
		$sql .="VALUES (1, '$headingbg', '$bgcolor1', '$bgcolor2', '$bgcolor3', '$pagebg', '$tablewidth', '$fontface', '$fontsize1', '$fontsize2', '$fontsize3', '$fontsize4', '$fontcolor', '$headingfontcolor', '$subheadingfontcolor', '$linkcolor', '$vlinkcolor', '$alinkcolor', '$groupfontcolor', '$tabledescfontcolor', '$fontsize5', '$dateformat', $watchlogins, $urlautoencode, $enablespcode, $nofreemailer, $enablefailednotify, '$timezone', $new_loginlimit, $enablehostresolve, $usemenubar, $newbugnotify, '$progsysmail', '$mailsig', $entriesperpage, $checkrefs, $refchkaffects, $autoapprove, $input_msendlimit, $newreqnotify, $newrefnotify, $emaildisplay, $admdelconfirm, '$new_homepageurl', '$new_homepagedesc', $topfilter, '$psysmailname', $admstorefilter, $automscheck, $thumbs_maxx, $thumbs_maxy, $thumbs_numcols, $autogenthumbs,' $i_dateformatlong')";
		if(!$result = mysql_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Unable to add layout to database. ".mysql_error());
		if(isset($mods))
		{
    			while(list($null, $mod) = each($_POST["mods"]))
    			{
				$mod_query = "INSERT INTO ".$tableprefix."_failed_notify (usernr) VALUES ('$mod')";
    			   	if(!mysql_query($mod_query, $db))
				    die("<tr class=\"errorrow\"><td>Unable to update the database.");
			}
		}
		if(isset($rem_mods))
		{
			while(list($null, $mod) = each($_POST["rem_mods"]))
			{
				$rem_query = "DELETE FROM ".$tableprefix."_failed_notify WHERE usernr = '$mod'";
       				if(!mysql_query($rem_query))
				    die("<tr class=\"errorrow\"><td>Unable to update the database.");
			}
		}
		if(isset($newrefs))
		{
    			while(list($null, $mod) = each($_POST["newrefs"]))
    			{
				$mod_query = "INSERT INTO ".$tableprefix."_newref_notify (usernr) VALUES ('$mod')";
    			   	if(!mysql_query($mod_query, $db))
				    die("<tr class=\"errorrow\"><td>Unable to update the database.");
			}
		}
		if(isset($rem_newrefs))
		{
			while(list($null, $mod) = each($_POST["rem_newrefs"]))
			{
				$rem_query = "DELETE FROM ".$tableprefix."_newref_notify WHERE usernr = '$mod'";
       			if(!mysql_query($rem_query))
				    die("<tr class=\"errorrow\"><td>Unable to update the database.");
			}
		}
		echo "<tr class=\"displayrow\" align=\"center\"><td>";
		echo "$l_settingsupdated";
		echo "</td></tr></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_editsettings</a></div>";
	}
	else
	{
		$sql = "UPDATE ".$tableprefix."_layout SET headingbg='$headingbg', bgcolor1='$bgcolor1', bgcolor2='$bgcolor2', bgcolor3='$bgcolor3', pagebg='$pagebg', tablewidth='$tablewidth', fontface='$fontface', ";
		$sql .="fontsize1='$fontsize1', fontsize2='$fontsize2', fontsize3='$fontsize3', fontsize4='$fontsize4', fontcolor='$fontcolor', headingfontcolor='$headingfontcolor', ";
		$sql .="subheadingfontcolor='$subheadingfontcolor', linkcolor='$linkcolor', vlinkcolor='$vlinkcolor', alinkcolor='$alinkcolor', groupfontcolor='$groupfontcolor', tabledescfontcolor='$tabledescfontcolor', fontsize5='$fontsize5', dateformat='$dateformat', ";
		$sql .="watchlogins=$watchlogins, checkrefs=$checkrefs, refchkaffects=$refchkaffects, ";
		$sql .="urlautoencode=$urlautoencode, enablespcode=$enablespcode, nofreemailer=$nofreemailer, progsysmail='$progsysmail', ";
		$sql .="enablefailednotify=$enablefailednotify, loginlimit=$new_loginlimit, timezone='$timezone', enablehostresolve=$enablehostresolve, ";
		$sql .="usemenubar=$usemenubar, newbugnotify=$newbugnotify, mailsig='$mailsig', entriesperpage=$entriesperpage, autoapprove=$autoapprove, msendlimit=$input_msendlimit, newreqnotify=$newreqnotify, newrefnotify=$newrefnotify, emaildisplay=$emaildisplay, ";
		$sql .="admdelconfirm=$admdelconfirm, homepageurl='$new_homepageurl', homepagedesc='$new_homepagedesc', topfilter=$topfilter, psysmailname='$psysmailname', admstorefilter=$admstorefilter, automscheck=$automscheck, ";
		$sql .="thumbs_maxx=$thumbs_maxx, thumbs_maxy=$thumbs_maxy, thumbs_numcols=$thumbs_numcols, autogenthumbs=$autogenthumbs, dateformatlong='$i_dateformatlong' ";
		$sql .="WHERE (layoutnr=1)";
		if(!$result = mysql_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Unable to update the database. ".mysql_error());
		if(isset($mods))
		{
			while(list($null, $mod) = each($_POST["mods"]))
			{
				$mod_query = "INSERT INTO ".$tableprefix."_failed_notify (usernr) VALUES ('$mod')";
	    		   	if(!mysql_query($mod_query, $db))
					die("<tr class=\"errorrow\"><td>Unable to update the database.");
			}
		}
		if(isset($rem_mods))
		{
			while(list($null, $mod) = each($_POST["rem_mods"]))
			{
				$rem_query = "DELETE FROM ".$tableprefix."_failed_notify WHERE usernr = '$mod'";
	       			if(!mysql_query($rem_query))
					die("<tr class=\"errorrow\"><td>Unable to update the database.");
			}
		}
		if(isset($newrefs))
		{
			while(list($null, $mod) = each($_POST["newrefs"]))
	    		{
				$mod_query = "INSERT INTO ".$tableprefix."_newref_notify (usernr) VALUES ('$mod')";
				if(!mysql_query($mod_query, $db))
					die("<tr class=\"errorrow\"><td>Unable to update the database.");
			}
		}
		if(isset($rem_newrefs))
		{
			while(list($null, $mod) = each($_POST["rem_newrefs"]))
			{
				$rem_query = "DELETE FROM ".$tableprefix."_newref_notify WHERE usernr = '$mod'";
				if(!mysql_query($rem_query))
					die("<tr class=\"errorrow\"><td>Unable to update the database.");
			}
		}
		echo "<tr class=\"displayrow\" align=\"center\"><td>";
		echo "$l_settingsupdated";
		echo "</td></tr></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_editsettings</a></div>";
	}
	include('trailer.php');
	exit;
}
{
$sql="select * from ".$tableprefix."_layout where (layoutnr=1)";
if(!$result = mysql_query($sql, $db)) {
    die("Could not connect to the database.");
}
if (!$myrow = mysql_fetch_array($result))
{
	$new=1;
	$headingbg="#94AAD6";
	$bgcolor1="#000000";
	$bgcolor2="#CCCCCC";
	$bgcolor3="#C0C0C0";
	$pagebg="#C0C0C0";
	$tablewidth="98%";
	$fontface="Verdana, Geneva, Arial, Helvetica, sans-serif";
	$fontsize1="1";
	$fontsize2="2";
	$fontsize3="+1";
	$fontsize4="-2";
	$fontsize5="2";
	$fontcolor="#000000";
	$headingfontcolor="#FFF0C0";
	$subheadingfontcolor="#F0F0F0";
	$linkcolor="#CC0000";
	$vlinkcolor="#CC0000";
	$alinkcolor="#0000CC";
	$groupfontcolor="#2C2C2C";
	$tabledescfontcolor="#2C2C2C";
	$dateformat="j.m.Y";
	$dateformatlong="j.m.Y H:i:s";
	$watchlogins=1;
	$urlautoencode=1;
	$enablespcode=1;
	$nofreemailer=0;
	$progsysmail="progsys@localhost";
	$mailsig="";
	$enablefailednotify=0;
	$timezone=0;
	$old_loginlimit=0;
	$enablehostresolve=1;
	$usemenubar=1;
	$newbugnotify=0;
	$entriesperpage=10;
	$checkrefs=1;
	$refchkaffects=0;
	$autoapprove=0;
	$msendlimit=30;
	$newreqnotify=1;
	$newrefnotify=1;
	$emaildisplay=0;
	$admdelconfirm=0;
	$homepageurl="http://localhost";
	$homepagedesc="Localhost";
	$topfilter=0;
	$psysmailname="";
	$admstorefilter=0;
	$automscheck=0;
	$thumbs_maxx=64;
	$thumbs_maxy=48;
	$thumbs_numcols=3;
	$autogenthumbs=1;
}
else
{
	$new=0;
	$headingbg=$myrow["headingbg"];
	$bgcolor1=$myrow["bgcolor1"];
	$bgcolor2=$myrow["bgcolor2"];
	$bgcolor3=$myrow["bgcolor3"];
	$pagebg=$myrow["pagebg"];
	$tablewidth=$myrow["tablewidth"];
	$fontface=$myrow["fontface"];
	$fontsize1=$myrow["fontsize1"];
	$fontsize2=$myrow["fontsize2"];
	$fontsize3=$myrow["fontsize3"];
	$fontsize4=$myrow["fontsize4"];
	$fontsize5=$myrow["fontsize5"];
	$fontcolor=$myrow["fontcolor"];
	$headingfontcolor=$myrow["headingfontcolor"];
	$subheadingfontcolor=$myrow["subheadingfontcolor"];
	$linkcolor=$myrow["linkcolor"];
	$vlinkcolor=$myrow["vlinkcolor"];
	$alinkcolor=$myrow["alinkcolor"];
	$groupfontcolor=$myrow["groupfontcolor"];
	$tabledescfontcolor=$myrow["tabledescfontcolor"];
	$dateformat=$myrow["dateformat"];
	$dateformatlong=$myrow["dateformatlong"];
	$watchlogins=$myrow["watchlogins"];
	$urlautoencode=$myrow["urlautoencode"];
	$enablespcode=$myrow["enablespcode"];
	$nofreemailer=$myrow["nofreemailer"];
	$progsysmail=$myrow["progsysmail"];
	$enablefailednotify=$myrow["enablefailednotify"];
	$old_loginlimit=$myrow["loginlimit"];
	$timezone=$myrow["timezone"];
	$enablehostresolve=$myrow["enablehostresolve"];
	$usemenubar=$myrow["usemenubar"];
	$newbugnotify=$myrow["newbugnotify"];
	$mailsig=$myrow["mailsig"];
	$entriesperpage=$myrow["entriesperpage"];
	$checkrefs=$myrow["checkrefs"];
	$refchkaffects=$myrow["refchkaffects"];
	$autoapprove=$myrow["autoapprove"];
	$msendlimit=$myrow["msendlimit"];
	$newreqnotify=$myrow["newreqnotify"];
	$newrefnotify=$myrow["newrefnotify"];
	$emaildisplay=$myrow["emaildisplay"];
	$admdelconfirm=$myrow["admdelconfirm"];
	$homepageurl=$myrow["homepageurl"];
	$homepagedesc=$myrow["homepagedesc"];
	$topfilter=$myrow["topfilter"];
	$psysmailname=$myrow["psysmailname"];
	$admstorefilter=$myrow["admstorefilter"];
	$automscheck=$myrow["automscheck"];
	$thumbs_maxx=$myrow["thumbs_maxx"];
	$thumbs_maxy=$myrow["thumbs_maxy"];
	$thumbs_numcols=$myrow["thumbs_numcols"];
	$autogenthumbs=$myrow["autogenthumbs"];
}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<form name="settingsform" onsubmit="return checkform()" method="post" action="<?php echo $act_script_url?>">
<?php
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<input type="hidden" name="lang" value="<?php echo $lang?>">
<input type="hidden" name="new" value="<?php echo $new?>">
<tr class="listheading0"><td align="left" colspan="2"><b><?php echo $l_layout_settings?></b></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_global?></b></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_tablewidth?>:</td>
<td><input type="text" class="psysinput" name="tablewidth" value="<?php echo $tablewidth?>"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_pagebgcolor?>:</td>
<td><input type="text" class="psysinput" name="pagebg" value="<?php echo $pagebg?>"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_textcolor?>:</td>
<td><input type="text" class="psysinput" name="fontcolor" value="<?php echo $fontcolor?>"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_fontface?>:</td>
<td><input type="text" class="psysinput" name="fontface" size="50" value="<?php echo $fontface?>"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_fontsize1?>:</td>
<td><input type="text" class="psysinput" name="fontsize1" value="<?php echo $fontsize1?>"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_fontsize4?>:</td>
<td><input type="text" class="psysinput" name="fontsize4" value="<?php echo $fontsize4?>"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_linkcolor?>:</td>
<td><input type="text" class="psysinput" name="linkcolor" value="<?php echo $linkcolor?>"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_vlinkcolor?>:</td>
<td><input type="text" class="psysinput" name="vlinkcolor" value="<?php echo $vlinkcolor?>"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_alinkcolor?>:</td>
<td><input type="text" class="psysinput" name="alinkcolor" value="<?php echo $alinkcolor?>"></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_headings?></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_bgcolor?>:</td>
<td><input type="text" class="psysinput" name="headingbg" value="<?php echo $headingbg?>"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_fontcolor?>:</td>
<td><input type="text" class="psysinput" name="headingfontcolor" value="<?php echo $headingfontcolor?>"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_fontsize?>:</td>
<td><input type="text" class="psysinput" name="fontsize3" value="<?php echo $fontsize3?>"></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_subheading?></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_fontcolor?>:</td>
<td><input type="text" class="psysinput" name="subheadingfontcolor" value="<?php echo $subheadingfontcolor?>"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_fontsize?>:</td>
<td><input type="text" class="psysinput" name="fontsize2" value="<?php echo $fontsize2?>"></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_grouping?></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_bgcolor?>:</td>
<td><input type="text" class="psysinput" name="bgcolor3" value="<?php echo $bgcolor3?>"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_fontcolor?>:</td>
<td><input type="text" class="psysinput" name="groupfontcolor" value="<?php echo $groupfontcolor?>"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_fontsize?>:</td>
<td><input type="text" class="psysinput" name="fontsize5" value="<?php echo $fontsize5?>"></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_tableheading?></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_fontcolor?>:</td>
<td><input type="text" class="psysinput" name="tabledescfontcolor" value="<?php echo $tabledescfontcolor?>"></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_tablebg?></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_bgcolor?> 1:</td>
<td><input type="text" class="psysinput" name="bgcolor1" value="<?php echo $bgcolor1?>"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_bgcolor?> 2:</td>
<td><input type="text" class="psysinput" name="bgcolor2" value="<?php echo $bgcolor2?>"></td></tr>
<tr class="listheading0"><td align="left" colspan="2"><b><?php echo $l_misc_settings?></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_entriesperpage?>:</td>
<td><input type="text" class="psysinput" name="entriesperpage" value="<?php echo $entriesperpage?>"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_dateformat?>:</td>
<td><input type="text" class="psysinput" name="dateformat" value="<?php echo $dateformat?>"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_dateformatlong?>:</td>
<td><input type="text" class="psysinput" name="i_dateformatlong" value="<?php echo $dateformatlong?>"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_servertimezone?>:</td>
<td><?php echo tz_select($timezone,"timezone")?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_sysemailadr?>:</td>
<td><input type="text" class="psysinput" name="progsysmail" value="<?php echo $progsysmail?>" size="30" maxlength="140"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_sysemailname?>:</td>
<td><input type="text" class="psysinput" name="psysmailname" value="<?php echo $psysmailname?>" size="30" maxlength="140"></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_mailsig?>:</td>
<td><textarea class="psysinput" name="mailsig" rows="5" cols="40"?><?php echo $mailsig?></textarea></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_homepageurl?>:</td>
<td><input type="text" class="psysinput" name="new_homepageurl" size="40" maxlength="240" value="<?php echo $homepageurl?>"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_homepagedesc?>:</td>
<td><input type="text" class="psysinput" name="new_homepagedesc" size="40" maxlength="240" value="<?php echo $homepagedesc?>"></td></tr>
<tr class="listheading0"><td align="left" colspan="2"><b><?php echo $l_options?></b></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input name="bugnotify" value="1" type="checkbox"
<?php if($newbugnotify==1) echo " checked"?>> <?php echo $l_newbugnotify?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input name="reqnotify" value="1" type="checkbox"
<?php if($newreqnotify==1) echo " checked"?>> <?php echo $l_newreqnotify?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input name="refnotify" value="1" type="checkbox"
<?php if($newrefnotify==1) echo " checked"?>> <?php echo $l_newrefnotify?></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_msendlimit?>:</td><td align="left">
<input class="psysinput" name="input_msendlimit" value="<?php echo $msendlimit?>" size="4" maxlength="10" type="text"> <?php echo $l_seconds?><br>
<?php echo $l_msendlimit_remark?></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_hideposteremail?></b></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="bugtraqhideemail" value="1" <?php if(bittst($emaildisplay,BIT_1)) echo "checked"?>> bugtraq.php</td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_referer?></b></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input name="checkrefs" value="0" type="radio"
<?php if($checkrefs==0) echo " checked"?>> <?php echo $l_dontcheckreferers?><br>
<input name="checkrefs" value="1" type="radio" <?php if($checkrefs==1) echo " checked"?>> <?php echo $l_checkallowedreferers?><br>
<input name="checkrefs" value="2" type="radio" <?php if($checkrefs==2) echo " checked"?>> <?php echo $l_checkforbiddenreferers?>
</td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_chkreffor?>:</td><td align="left">
<input name="refdownload" value="1" type="checkbox" <?php if(bittst($refchkaffects,BIT_1)) echo "checked"?>> <?php echo $l_downloads?><br>
<input name="refchangelog" value="1" type="checkbox" <?php if(bittst($refchkaffects,BIT_2)) echo "checked"?>> <?php echo $l_changelog?><br>
<input name="refbugtraq" value="1" type="checkbox" <?php if(bittst($refchkaffects,BIT_3)) echo "checked"?>> <?php echo $l_bugtracking?><br>
<input name="refreferences" value="1" type="checkbox" <?php if(bittst($refchkaffects,BIT_4)) echo "checked"?>> <?php echo $l_references?><br>
<input name="refrequests" value="1" type="checkbox" <?php if(bittst($refchkaffects,BIT_5)) echo "checked"?>> <?php echo $l_featurerequests?><br>
<input name="refsubscribe" value="1" type="checkbox" <?php if(bittst($refchkaffects,BIT_6)) echo "checked"?>> <?php echo $l_subscriptions?><br>
<input name="reftodo" value="1" type="checkbox" <?php if(bittst($refchkaffects,BIT_7)) echo "checked"?>> <?php echo $l_todolist?><br>
</td></tr>
<tr class="listheading0"><td align="left" colspan="2"><b><?php echo $l_references?></b></td></tr>
<tr class="inputrow"><td align="right">&nbsp;</td><td>
<input type="checkbox" name="alwaysapproved" value="1" <?php if($autoapprove==1) echo "checked"?>> <?php echo $l_autoapprove?></td></tr>
<tr class="listheading0"><td align="left" colspan="2"><b><?php echo $l_screenshots?></b></td></tr>
<tr class="inputrow"><td>&nbsp</td><td><input type="checkbox" name="autothumbs" value="1" <?php if($autogenthumbs==1) echo "checked"?>> <?php echo $l_autogenthumbs?>
</td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_thumbs_numcols?>:</td><td>
<input type="text" name="thumbs_numcols" value="<?php echo $thumbs_numcols?>" class="psysinput" size="10" maxlength="10"></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_thumbsize?></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_maxx?>:</td><td>
<input type="text" name="thumbs_maxx" value="<?php echo $thumbs_maxx?>" class="psysinput" size="10" maxlength="10"> <?php echo $l_pixel?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_maxy?>:</td><td>
<input type="text" name="thumbs_maxy" value="<?php echo $thumbs_maxy?>" class="psysinput" size="10" maxlength="10"> <?php echo $l_pixel?></td></tr>
<tr class="listheading0"><td align="left" colspan="2"><b><?php echo $l_admininterface?></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_loginlimit?>:</td>
<td><input type="text" class="psysinput" name="new_loginlimit" value="<?php echo $old_loginlimit?>" size="5" maxlength="5"></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input name="dologincount" value="1" type="checkbox"
<?php if($watchlogins==1) echo " checked"?>> <?php echo $l_watchlogins?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input name="enableautourl" value="1" type="checkbox"
<?php if($urlautoencode==1) echo " checked"?>> <?php echo $l_urlautoencode?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input name="allowspcode" value="1" type="checkbox"
<?php if($enablespcode==1) echo " checked"?>> <?php echo $l_enablespcode?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input name="enablefreemailer" value="1" type="checkbox"
<?php if($nofreemailer==0) echo " checked"?>> <?php echo $l_allowfreemailer?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input name="enablemenubar" value="1" type="checkbox"
<?php if($usemenubar==1) echo " checked"?>> <?php echo $l_usemenubar?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input name="allowhostresolve" value="1" type="checkbox"
<?php if($enablehostresolve==1) echo " checked"?>> <?php echo $l_enablehostresolve?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="confirmdel" value="1" <?php if($admdelconfirm==1) echo "checked"?>>
<?php echo $l_delconfirm?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="filterattop" value="1" <?php if($topfilter==1) echo "checked"?>>
<?php echo $l_topfilter?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="storefilter" value="1" <?php if($admstorefilter==1) echo "checked"?>>
<?php echo $l_admstorefilter?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="autocheckms" value="1" <?php if($automscheck==1) echo "checked"?>>
<?php echo $l_autocheckms?></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_failednotify?>:<br><input name="dofailednotify" value="1" type="checkbox"
<?php if($enablefailednotify==1) echo " checked"?>><?php echo $l_enable?></td>
<td align="left" valign="top">
<?php
	$sql = "SELECT * FROM ".$tableprefix."_failed_notify fn, ".$tableprefix."_admins u where u.usernr=fn.usernr order by u.username";
	if(!$r = mysql_query($sql, $db))
	    die("Could not connect to the database.");
	if ($row = mysql_fetch_array($r))
	{
		 do {
		    echo $row["username"]." (<input type=\"checkbox\" name=\"rem_mods[]\" value=\"".$row["usernr"]."\"> $l_remove)<BR>";
		    $current_mods[] = $row["usernr"];
		 } while($row = mysql_fetch_array($r));
		 echo "<br>";
	}
	else
		echo "$l_noadmins<br><br>";
	$sql = "SELECT usernr, username FROM ".$tableprefix."_admins WHERE rights > 2 ";
	if(isset($current_mods))
	{
    	while(list($null, $currMod) = each($current_mods)) {
			$sql .= "AND usernr != $currMod ";
    	}
    }
    $sql .= "ORDER BY username";
    if(!$r = mysql_query($sql, $db))
		die("Could not connect to the database.");
    if($row = mysql_fetch_array($r)) {
		echo"<b>$l_add:</b><br>";
		echo"<SELECT NAME=\"mods[]\" size=\"5\" multiple>";
		do {
			echo "<OPTION VALUE=\"$row[usernr]\" >$row[username]</OPTION>\n";
		} while($row = mysql_fetch_array($r));
		echo"</select>";
	}
?>
</td></tr>
<tr class="actionrow"><td align="center" colspan="2">
<input class="psysbutton" type="submit" name="submit" value="<?php echo $l_submit?>"></td></tr>
</table></td></tr></table>
<?php
}
include('trailer.php');
?>