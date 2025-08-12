<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require_once('../config.php');
require_once('./admchk.php');
if(!isset($$langvar) || !$$langvar)
	$act_lang=$admin_lang;
else
	$act_lang=$$langvar;
include_once('./language/lang_'.$act_lang.'.php');
require_once('./auth.php');
$page_title=$l_syssettings;
$page="settings";
require_once('./heading.php');
if($admin_rights < 3)
{
	die($l_functionotallowed);
}
$rndid=time();
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<form name="settingsform" method="post" action="<?php echo $act_script_url?>">
<?php
if(is_konqueror())
	echo "<tr><td></td></tr>";
if($sessid_url)
	echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
echo "<input type=\"hidden\" name=\"postid\" value=\"$rndid\">";
if(isset($mode))
{
	if(isset($dobwords))
		$usebwlist=1;
	else
		$usebwlist=0;
	if(isset($doevsearch))
		$enableevsearch=1;
	else
		$enableevsearch=0;
	if(isset($doadmaltprv))
		$admaltprv=1;
	else
		$admaltprv=0;
	if(isset($noproppwconfirm))
		$prop_nopwconfirm=1;
	else
		$prop_nopwconfirm=0;
  	if(isset($nrftprohibit))
		$prohibitnoregfiletypes=1;
	else
		$prohibitnoregfiletypes=0;
	if(isset($emaildie))
		$emailerrordie=1;
	else
		$emailerrordie=0;
	if(isset($rss_activ))
		$rss_enable=1;
	else
		$rss_enable=0;
	if(isset($wap_activ))
		$wap_enable=1;
	else
		$wap_enable=0;
	if(isset($comnotify))
		$newcomnotify=1;
	else
		$newcomnotify=0;
	if(isset($logsearches))
		$dosearchlog=1;
	else
		$dosearchlog=0;
	if(isset($progressautohide))
		$sendprogressautohide=1;
	else
		$sendprogressautohide=0;
	if(isset($sendprogressbox))
		$showsendprogress=1;
	else
		$showsendprogress=0;
	if($input_senddelayinterval<1)
		$input_senddelayinterval=1;
	if(($input_sendnewsdelay>0) && ($input_sendnewsdelay/1000>=$input_msendlimit))
	{
		if($input_msendlimit==0)
			$input_sendnewsdelay=0;
		else
			$input_sendnewsdelay=($input_msendlimit-1)*1000;
	}
	if(isset($dorating))
		$enablerating=1;
	else
		$enablerating=0;
	if(isset($leacherblocking))
		$blockleacher=1;
	else
		$blockleacher=0;
	if(isset($altlayout))
		$admaltlayout=1;
	else
		$admaltlayout=0;
	if(isset($dlcounts))
		$usedlcounts=1;
	else
		$usedlcounts=0;
	if(isset($viewcounts))
		$useviewcounts=1;
	else
		$useviewcounts=0;
	if(isset($mailnews))
		$emailnews=1;
	else
		$emailnews=0;
	if($maxuserupload>$maxfilesize)
		$maxuserupload=$maxfilesize;
	if(isset($subremnot))
		$subremovenotify=1;
	else
		$subremovenotify=0;
	if(isset($newsubnotify))
		$newsubscriptionnotify=1;
	else
		$newsubscriptionnotify=0;
	if(isset($storefilter))
		$admstorefilter=1;
	else
		$admstorefilter=0;
	if(isset($inlinethumbs) && $gdavail)
		$inline_genthumbs=1;
	else
		$inline_genthumbs=0;
	if(isset($attachinlinepix))
		$newsletterattachinlinepix=1;
	else
		$newsletterattachinlinepix=0;
	$secsettings=0;
	if($newscat0lvl==1)
		$secsettings=setbit($secsettings,BIT_1);
	if($eventcat0lvl==1)
		$secsettings=setbit($secsettings,BIT_2);
	if($annlvl==1)
		$secsettings=setbit($secsettings,BIT_3);
	if($anncat0lvl==1)
		$secsettings=setbit($secsettings,BIT_4);
	if($edsub==1)
		$secsettings=setbit($secsettings,BIT_5);
	if($edposter==1)
		$secsettings=setbit($secsettings,BIT_6);
	if($edcat==1)
		$secsettings=setbit($secsettings,BIT_7);
	if($purgenews==1)
		$secsettings=setbit($secsettings,BIT_8);
	if($purgeevents==1)
		$secsettings=setbit($secsettings,BIT_9);
	if($nlcat0==1)
		$secsettings=setbit($secsettings,BIT_10);
	if($newsproped==1)
		$secsettings=setbit($secsettings,BIT_11);
	if($evproped==1)
		$secsettings=setbit($secsettings,BIT_12);
	if($layouted==1)
		$secsettings=setbit($secsettings,BIT_13);
	if(isset($anhidenoed))
		$secsettings=setbit($secsettings,BIT_14);
	if(isset($limitnewsedcat))
		$secsettings=setbit($secsettings,BIT_15);
	if(isset($limiteventsedcat))
		$secsettings=setbit($secsettings,BIT_16);
	if(isset($limitanedcat))
		$secsettings=setbit($secsettings,BIT_17);
	if(isset($limitsnewsedcat))
		$secsettings=setbit($secsettings,BIT_18);
	if(isset($limitseventsedcat))
		$secsettings=setbit($secsettings,BIT_19);
	if($limitimport==1)
		$secsettings=setbit($secsettings,BIT_20);
	if(isset($limitsanedcat))
		$secsettings=setbit($secsettings,BIT_21);
	if(isset($edcatlimit))
		$secsettings=setbit($secsettings,BIT_22);
	if(isset($requestlinks))
		$secsettings=setbit($secsettings,BIT_23);
	if(isset($evnewsletter))
		$evnewsletterinclude=1;
	else
		$evnewsletterinclude=0;
	if(isset($enableasc))
		$asclist=1;
	else
		$asclist=0;
	$input_simpnewsmailname=trim($input_simpnewsmailname);
	$proposepermissions=0;
	if(isset($editproposals))
		$proposepermissions=setbit($proposepermissions,BIT_1);
	if($pdelmode==1)
		$proposepermissions=setbit($proposepermissions,BIT_2);
	if($pedmode==1)
		$proposepermissions=setbit($proposepermissions,BIT_3);
	if(isset($pednobbcode))
		$proposepermissions=setbit($proposepermissions,BIT_4);
	if(isset($newposter_nobbcode))
		$proposepermissions=setbit($proposepermissions,BIT_5);
	if(isset($propenablefileupload))
		$proposepermissions=setbit($proposepermissions,BIT_6);
	if(isset($newposter_nofileupload))
		$proposepermissions=setbit($proposepermissions,BIT_7);
	if(isset($admheadingsonly))
		$admonlyentryheadings=1;
	else
		$admonlyentryheadings=0;
	if(isset($pnotify))
		$proposenotify=1;
	else
		$proposenotify=0;
	if(isset($allowevpropose))
		$enableevpropose=1;
	else
		$enableevpropose=0;
	if(isset($allowpropose))
		$enablepropose=1;
	else
		$enablepropose=0;
	if(isset($nonewslettericons))
		$newsletternoicons=1;
	else
		$newsletternoicons=0;
	if(isset($visitcookie))
		$lastvisitcookie=1;
	else
		$lastvisitcookie=0;
	if(isset($eventcalnews))
		$newsineventcal=1;
	else
		$newsineventcal=0;
	if(isset($allowsearch))
		$enablesearch=1;
	else
		$enablesearch=0;
	if(isset($enablecomments))
		$allowcomments=1;
	else
		$allowcomments=0;
	if(isset($subfreemailer))
		$subscriptionfreemailer=1;
	else
		$subscriptionfreemailer=0;
	if(isset($newallowsubscriptions))
		$enablesubscriptions=1;
	else
		$enablesubscriptions=0;
	if(isset($nosubscriptionconfirm))
		$maxconfirmtime=0;
	if(isset($dowatchlogins))
		$watchlogins=1;
	else
		$watchlogins=0;
	if(isset($enablemenubar))
		$usemenubar=1;
	else
		$usemenubar=0;
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
	if(isset($restrictadm))
		$admrestrict=1;
	else
		$admrestrict=0;
	if(isset($sendattachbymail))
		$mailattach=1;
	else
		$mailattach=0;
	if($settingsnew==1)
	{
		$sql = "insert into ".$tableprefix."_settings (";
		$sql.= "watchlogins, usemenubar, nofreemailer, enablefailednotify, ";
		$sql.= "loginlimit, simpnewsmail, enablehostresolve, maxconfirmtime, enablesubscriptions, subject, ";
		$sql.= "subscriptionsendmode, subscriptionfreemailer, sitename, maxage, allowcomments, ";
		$sql.= "enablesearch, redirectdelay, newsineventcal, lastvisitcookie, ";
		$sql.= "servertimezone, displaytimezone, simpnewsmailname, admrestrict, ";
		$sql.= "newsletternoicons, maxpropose, enablepropose, enableevpropose, proposenotify, notifymode, ";
		$sql.= "admonlyentryheadings, admentrychars, proposepermissions, exporttype, asclist, admdelconfirm, ";
		$sql.= "mailattach, evnewsletterinclude, msendlimit, admepp, secsettings, bbcimgdefalign, ";
		$sql.= "newsletterattachinlinepix, icons_maxwidth, icons_maxheight, ";
		$sql.= "inline_thumbwidth, inline_thumbheight, inline_genthumbs, inline_maxwidth, inline_maxheight, ";
		$sql.= "admstorefilter, newsubscriptionnotify, subremovenotify, maxuserupload, emailnews, yearrange, ";
		$sql.= "useviewcounts, minviews, usedlcounts, admaltlayout, blockleacher, enablerating, sendnewsdelay, ";
		$sql.= "senddelayinterval, showsendprogress, sendprogressautohide, lastvisitdays, lastvisitsessiontime, ";
		$sql.= "dosearchlog, newcomnotify, rss_enable, wap_enable, emaillog, emailerrordie, prohibitnoregfiletypes, ";
		$sql.= "newsletterlinking, mailmaxlinelength, prop_nopwconfirm, admaltprv, enableevsearch, usebwlist";
		$sql.= ") values (";
		$sql.= "$watchlogins, $usemenubar, $nofreemailer, $enablefailednotify, $new_loginlimit, '$input_simpnewsmail', ";
		$sql.= "$enablehostresolve, $maxconfirmtime, $enablesubscriptions, '$new_subject', $input_subscriptionsendmode, ";
		$sql.= "subscriptionfreemailer, '$sitename', $maxage, $allowcomments, ";
		$sql.= "$enablesearch, $redirectdelay, $newsineventcal, $lastvisitcookie, ";
		$sql.= "$input_servertimezone, $input_displaytimezone, '$input_simpnewsmailname', ";
		$sql.= "$admrestrict, $newsletternoicons, $maxpropose, $enablepropose, $enableevpropose, $proposenotify, $notifymode, ";
		$sql.= "$admonlyentryheadings, $input_admentrychars, $proposepermissions, $exporttype, $asclist, $new_admdelconfirm, ";
		$sql.= "$mailattach, $evnewsletterinclude, $input_msendlimit, $input_admepp, $secsettings, '$input_bbcimgdefalign', ";
		$sql.= "$newsletterattachinlinepix, $input_icons_maxwidth, $input_icons_maxheight, ";
		$sql.= "$input_inline_thumbheight, $input_inline_thumbwidth, $inline_genthumbs, $input_inline_maxwidth, $input_inline_maxheight, ";
		$sql.= "$admstorefilter, $newsubscriptionnotify, $subremovenotify, $maxuserupload, $emailnews, $newyearrange, ";
		$sql.= "$useviewcounts, $newminviews, $usedlcounts, $admaltlayout, $blockleacher, $enablerating, $input_sendnewsdelay,";
		$sql.= "$input_senddelayinterval, $showsendprogress, $sendprogressautohide, $lastvisitdays, $lastvisitsessiontime, ";
		$sql.= "$dosearchlog, $newcomnotify, $rss_enable, $wap_enable, $new_emaillog, $emailerrordie, $prohibitnoregfiletypes, ";
		$sql.= "$new_newsletterlinking, $newmailmaxlinelength, $prop_nopwconfirm, $admaltprv, $enableevsearch, $usebwlist";
		$sql.= ")";
	}
	else
	{
		$sql = "update ".$tableprefix."_settings set watchlogins=$watchlogins, usemenubar=$usemenubar, ";
		$sql.= "nofreemailer=$nofreemailer, enablefailednotify=$enablefailednotify, loginlimit=$new_loginlimit, ";
		$sql.= "simpnewsmail='$input_simpnewsmail', enablehostresolve=$enablehostresolve, maxconfirmtime=$maxconfirmtime, ";
		$sql.= "enablesubscriptions=$enablesubscriptions, subject='$new_subject', subscriptionsendmode=$input_subscriptionsendmode, ";
		$sql.= "subscriptionfreemailer=$subscriptionfreemailer, sitename='$sitename', maxage=$maxage, ";
		$sql.= "allowcomments=$allowcomments, ";
		$sql.= "enablesearch=$enablesearch, redirectdelay=$redirectdelay, ";
		$sql.= "newsineventcal=$newsineventcal, lastvisitcookie=$lastvisitcookie, servertimezone=$input_servertimezone, ";
		$sql.= "displaytimezone=$input_displaytimezone, simpnewsmailname='$input_simpnewsmailname', ";
		$sql.= "admrestrict=$admrestrict, newsletternoicons=$newsletternoicons, ";
		$sql.= "maxpropose=$maxpropose, enablepropose=$enablepropose, enableevpropose=$enableevpropose, ";
		$sql.= "proposenotify=$proposenotify, notifymode=$notifymode, admonlyentryheadings=$admonlyentryheadings, ";
		$sql.= "admentrychars=$input_admentrychars, proposepermissions=$proposepermissions, exporttype=$exporttype, ";
		$sql.= "asclist=$asclist, admdelconfirm=$new_admdelconfirm, mailattach=$mailattach, evnewsletterinclude=$evnewsletterinclude, ";
		$sql.= "msendlimit=$input_msendlimit, admepp=$input_admepp, secsettings=$secsettings, bbcimgdefalign='$input_bbcimgdefalign', ";
		$sql.= "newsletterattachinlinepix=$newsletterattachinlinepix, ";
		$sql.= "icons_maxwidth=$input_icons_maxwidth, icons_maxheight=$input_icons_maxheight, ";
		$sql.= "inline_thumbheight=$input_inline_thumbheight, inline_thumbwidth=$input_inline_thumbwidth, inline_genthumbs=$inline_genthumbs, ";
		$sql.= "inline_maxwidth=$input_inline_maxwidth, inline_maxheight=$input_inline_maxheight, admstorefilter=$admstorefilter, ";
		$sql.= "newsubscriptionnotify=$newsubscriptionnotify, subremovenotify=$subremovenotify, maxuserupload=$maxuserupload, ";
		$sql.= "emailnews=$emailnews, yearrange=$newyearrange, useviewcounts=$useviewcounts, minviews=$newminviews, ";
		$sql.= "usedlcounts=$usedlcounts, admaltlayout=$admaltlayout, blockleacher=$blockleacher, enablerating=$enablerating, ";
		$sql.= "sendnewsdelay=$input_sendnewsdelay, senddelayinterval=$input_senddelayinterval, showsendprogress=$showsendprogress, ";
		$sql.= "sendprogressautohide=$sendprogressautohide, lastvisitdays=$lastvisitdays, lastvisitsessiontime=$lastvisitsessiontime, ";
		$sql.= "dosearchlog=$dosearchlog, newcomnotify=$newcomnotify, rss_enable=$rss_enable, wap_enable=$wap_enable, emaillog=$new_emaillog, ";
		$sql.= "emailerrordie=$emailerrordie, prohibitnoregfiletypes=$prohibitnoregfiletypes, newsletterlinking=$new_newsletterlinking, ";
		$sql.= "mailmaxlinelength=$newmailmaxlinelength, prop_nopwconfirm=$prop_nopwconfirm, admaltprv=$admaltprv, enableevsearch=$enableevsearch, ";
		$sql.= "usebwlist=$usebwlist ";
		$sql.= "where settingnr=1";
	}
	if(!$result = mysql_query($sql, $db))
	{
		if($dodebug)
			echo "<tr class=\"errorrow\"><td>SQL statement is: ".$sql."</td></tr>";
		die("<tr class=\"errorrow\"><td>Unable to connect to database. ".mysql_error());
	}
	if(isset($rem_hn6cats))
	{
   		while(list($null, $actcat) = each($_POST["rem_hn6cats"]))
   		{
			$tmpsql = "DELETE FROM ".$tableprefix."_hn6cats where catnr=$actcat";
   		   	if(!mysql_query($tmpsql, $db))
			    die("<tr class=\"errorrow\"><td>Unable to update the database.".mysql_error());
		}
	}
	if(isset($hn6cats))
	{
   		while(list($null, $actcat) = each($_POST["hn6cats"]))
   		{
			$tmpsql = "INSERT INTO ".$tableprefix."_hn6cats (catnr) VALUES ('$actcat')";
   		   	if(!mysql_query($tmpsql, $db))
			    die("<tr class=\"errorrow\"><td>Unable to update the database.".mysql_error());
		}
	}
	if(isset($mods))
	{
   		while(list($null, $mod) = each($_POST["mods"]))
   		{
			$mod_query = "INSERT INTO ".$tableprefix."_failed_notify (usernr) VALUES ('$mod')";
   		   	if(!mysql_query($mod_query, $db))
			    die("<tr class=\"errorrow\"><td>Unable to update the database.".mysql_error());
		}
	}
	if(isset($rem_mods))
	{
		while(list($null, $mod) = each($_POST["rem_mods"]))
		{
			$rem_query = "DELETE FROM ".$tableprefix."_failed_notify WHERE usernr = '$mod'";
   			if(!mysql_query($rem_query))
			    die("<tr class=\"errorrow\"><td>Unable to update the database.".mysql_error());
		}
	}
	if(isset($notifymods))
	{
   		while(list($null, $mod) = each($_POST["notifymods"]))
   		{
			$mod_query = "INSERT INTO ".$tableprefix."_notifylist (usernr) VALUES ('$mod')";
   		   	if(!mysql_query($mod_query, $db))
			    die("<tr class=\"errorrow\"><td>Unable to update the database.".mysql_error());
		}
	}
	if(isset($rem_notifymods))
	{
		while(list($null, $mod) = each($_POST["rem_notifymods"]))
		{
			$rem_query = "DELETE FROM ".$tableprefix."_notifylist WHERE usernr = '$mod'";
   			if(!mysql_query($rem_query))
			    die("<tr class=\"errorrow\"><td>Unable to update the database.".mysql_error());
		}
	}
	if(isset($comnotify_mods))
	{
   		while(list($null, $mod) = each($_POST["comnotify_mods"]))
   		{
			$mod_query = "INSERT INTO ".$tableprefix."_newcommentnotify (usernr) VALUES ('$mod')";
   		   	if(!mysql_query($mod_query, $db))
			    die("<tr class=\"errorrow\"><td>Unable to update the database.".mysql_error());
		}
	}
	if(isset($rem_comnotify_mods))
	{
		while(list($null, $mod) = each($_POST["rem_comnotify_mods"]))
		{
			$rem_query = "DELETE FROM ".$tableprefix."_newcommentnotify WHERE usernr = '$mod'";
   			if(!mysql_query($rem_query))
			    die("<tr class=\"errorrow\"><td>Unable to update the database.".mysql_error());
		}
	}
	if(isset($nsubmods))
	{
   		while(list($null, $mod) = each($_POST["nsubmods"]))
   		{
			$mod_query = "INSERT INTO ".$tableprefix."_newsubnotify (usernr) VALUES ('$mod')";
   		   	if(!mysql_query($mod_query, $db))
			    die("<tr class=\"errorrow\"><td>Unable to update the database.".mysql_error());
		}
	}
	if(isset($rem_nsubmods))
	{
		while(list($null, $mod) = each($_POST["rem_nsubmods"]))
		{
			$rem_query = "DELETE FROM ".$tableprefix."_newsubnotify WHERE usernr = '$mod'";
   			if(!mysql_query($rem_query))
			    die("<tr bgcolor=\"#cccccc\"><td>Unable to update the database.");
		}
	}
	if(isset($nlmods))
	{
   		while(list($null, $mod) = each($_POST["nlmods"]))
   		{
			$mod_query = "INSERT INTO ".$tableprefix."_newsletteradmins (usernr) VALUES ('$mod')";
   		   	if(!mysql_query($mod_query, $db))
			    die("<tr bgcolor=\"#cccccc\"><td>Unable to update the database.");
		}
	}
	if(isset($rem_nlmods))
	{
		while(list($null, $mod) = each($_POST["rem_nlmods"]))
		{
			$rem_query = "DELETE FROM ".$tableprefix."_newsletteradmins WHERE usernr = '$mod'";
   			if(!mysql_query($rem_query))
			    die("<tr bgcolor=\"#cccccc\"><td>Unable to update the database.");
		}
	}
	echo "<tr class=\"inforow\"><td align=\"center\" colspan=\"2\"><b>$l_settingsupdated</b></td></tr>";
}
$sql = "select * from ".$tableprefix."_settings where settingnr=1";
if(!$result = mysql_query($sql, $db))
    die("Unable to connect to database.".mysql_error());
if(!$myrow=mysql_fetch_array($result))
{
	$settingsnew=1;
	$watchlogins=0;
	$enablefailednotify=0;
	$simpnewsmail="simpnews@localhost";
	$old_loginlimit=0;
	$usemenubar=0;
	$nofreemailer=0;
	$enablehostresolve=0;
	$subscriptionfreemailer=1;
	$subject="";
	$enablesubscriptions=0;
	$subscriptionsendmode=1;
	$maxconfirmtime=2;
	$sitename="Sitename";
	$maxage=0;
	$allowcomments=0;
	$enablesearch=0;
	$redirectdelay=-1;
	$newsineventcal=0;
	$lastvisitcookie=1;
	$act_servertimezone=0;
	$act_displaytimezone=0;
	$simpnewsmailname="SimpNews";
	$admrestrict=0;
	$newsletternoicons=0;
	$maxpropose=0;
	$enablepropose=0;
	$enableevpropose=0;
	$proposenotify=0;
	$notifymode=0;
	$admonlyentryheadings=0;
	$admentrychars=20;
	$proposepermissions=0;
	$exporttype=0;
	$asclist=0;
	$admdelconfirm=0;
	$mailattach=0;
	$evnewsletterinclude=0;
	$msendlimit=30;
	$admepp=0;
	$secsettings=0;
	$bbcimgdefalign="left";
	$newsletterattachinlinepix=0;
	$icons_maxwidth=20;
	$icons_maxheight=20;
	$inline_thumbwidth=20;
	$inline_thumbheight=20;
	$inline_genthumbs=0;
	$inline_maxwidth=640;
	$inline_maxheight=480;
	$admstorefilter=1;
	$newsubscriptionnotify=0;
	$subremovenotify=0;
	$maxuserupload=100000;
	$emailnews=0;
	$yearrange=5;
	$useviewcounts=0;
	$minviews=0;
	$usedlcounts=0;
	$admaltlayout=0;
	$blockleacher=0;
	$enablerating=0;
	$sendnewsdelay=0;
	$senddelayinterval=1;
	$showsendprogress=1;
	$sendprogressautohide=1;
	$lastvisitdays=365;
	$lastvisitsessiontime=60;
	$dosearchlog=0;
	$newcomnotify=0;
	$rss_enable=0;
	$wap_enable=0;
	$prohibitnoregfiletypes=1;
	$newsletterlinking=0;
	$mailmaxlinelength=998;
	$prop_nopwconfirm=0;
	$admaltprv=0;
	$enableevsearch=0;
	$usebwlist=0;
}
else
{
	$settingsnew=0;
	$watchlogins=$myrow["watchlogins"];
	$enablefailednotify=$myrow["enablefailednotify"];
	$simpnewsmail=$myrow["simpnewsmail"];
	$old_loginlimit=$myrow["loginlimit"];
	$usemenubar=$myrow["usemenubar"];
	$nofreemailer=$myrow["nofreemailer"];
	$enablehostresolve=$myrow["enablehostresolve"];
	$subscriptionfreemailer=$myrow["subscriptionfreemailer"];
	$subject=$myrow["subject"];
	$enablesubscriptions=$myrow["enablesubscriptions"];
	$subscriptionsendmode=$myrow["subscriptionsendmode"];
	$maxconfirmtime=$myrow["maxconfirmtime"];
	$sitename=$myrow["sitename"];
	$maxage=$myrow["maxage"];
	$allowcomments=$myrow["allowcomments"];
	$enablesearch=$myrow["enablesearch"];
	$redirectdelay=$myrow["redirectdelay"];
	$newsineventcal=$myrow["newsineventcal"];
	$lastvisitcookie=$myrow["lastvisitcookie"];
	$act_servertimezone=$myrow["servertimezone"];
	$act_displaytimezone=$myrow["displaytimezone"];
	$simpnewsmailname=$myrow["simpnewsmailname"];
	$admrestrict=$myrow["admrestrict"];
	$newsletternoicons=$myrow["newsletternoicons"];
	$maxpropose=$myrow["maxpropose"];
	$enablepropose=$myrow["enablepropose"];
	$enableevpropose=$myrow["enableevpropose"];
	$proposenotify=$myrow["proposenotify"];
	$notifymode=$myrow["notifymode"];
	$admonlyentryheadings=$myrow["admonlyentryheadings"];
	$admentrychars=$myrow["admentrychars"];
	$proposepermissions=$myrow["proposepermissions"];
	$exporttype=$myrow["exporttype"];
	$asclist=$myrow["asclist"];
	$admdelconfirm=$myrow["admdelconfirm"];
	$mailattach=$myrow["mailattach"];
	$evnewsletterinclude=$myrow["evnewsletterinclude"];
	$msendlimit=$myrow["msendlimit"];
	$admepp=$myrow["admepp"];
	$secsettings=$myrow["secsettings"];
	$bbcimgdefalign=$myrow["bbcimgdefalign"];
	$newsletterattachinlinepix=$myrow["newsletterattachinlinepix"];
	$icons_maxwidth=$myrow["icons_maxwidth"];
	$icons_maxheight=$myrow["icons_maxheight"];
	$inline_thumbheight=$myrow["inline_thumbheight"];
	$inline_thumbwidth=$myrow["inline_thumbwidth"];
	$inline_genthumbs=$myrow["inline_genthumbs"];
	$inline_maxheight=$myrow["inline_maxheight"];
	$inline_maxwidth=$myrow["inline_maxwidth"];
	$admstorefilter=$myrow["admstorefilter"];
	$newsubscriptionnotify=$myrow["newsubscriptionnotify"];
	$subremovenotify=$myrow["subremovenotify"];
	$maxuserupload=$myrow["maxuserupload"];
	$emailnews=$myrow["emailnews"];
	$yearrange=$myrow["yearrange"];
	$useviewcounts=$myrow["useviewcounts"];
	$minviews=$myrow["minviews"];
	$usedlcounts=$myrow["usedlcounts"];
	$admaltlayout=$myrow["admaltlayout"];
	$blockleacher=$myrow["blockleacher"];
	$enablerating=$myrow["enablerating"];
	$sendnewsdelay=$myrow["sendnewsdelay"];
	$senddelayinterval=$myrow["senddelayinterval"];
	$showsendprogress=$myrow["showsendprogress"];
	$sendprogressautohide=$myrow["sendprogressautohide"];
	$lastvisitdays=$myrow["lastvisitdays"];
	$lastvisitsessiontime=$myrow["lastvisitsessiontime"];
	$dosearchlog=$myrow["dosearchlog"];
	$newcomnotify=$myrow["newcomnotify"];
	$rss_enable=$myrow["rss_enable"];
	$wap_enable=$myrow["wap_enable"];
	$prohibitnoregfiletypes=$myrow["prohibitnoregfiletypes"];
	$newsletterlinking=$myrow["newsletterlinking"];
	$mailmaxlinelength=$myrow["mailmaxlinelength"];
	$prop_nopwconfirm=$myrow["prop_nopwconfirm"];
	$admaltprv=$myrow["admaltprv"];
	$enableevsearch=$myrow["enableevsearch"];
	$usebwlist=$myrow["usebwlist"];
}
?>
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="mode" value="submit">
<input type="hidden" name="settingsnew" value="<?php echo $settingsnew?>">
<tr class="listheading0"><td align="left" colspan="2"><b><?php echo $l_admininterface?></b></td></tr>
<tr class="inputrow"><td align="right" width="30%">&nbsp;</td>
<td align="left"><input type="checkbox" name="dowatchlogins" value="1" <?php if($watchlogins==1) echo "checked"?>> <?php echo $l_watchlogins?></td></tr>
<tr class="inputrow"><td align="right" width="30%">&nbsp;</td>
<td align="left"><input type="checkbox" name="enablemenubar" value="1" <?php if($usemenubar==1) echo "checked"?>> <?php echo $l_usemenubar?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input name="allowhostresolve" value="1" type="checkbox"
<?php if($enablehostresolve==1) echo " checked"?>> <?php echo $l_enablehostresolve?></td></tr>
<tr class="inputrow"><td align="right" width="30%">&nbsp;</td>
<td align="left"><input type="checkbox" name="enablefreemailer" value="1" <?php if($nofreemailer==0) echo "checked"?>> <?php echo $l_enablefreemailer?></td></tr>
<tr class="inputrow"><td align="right" width="30%">&nbsp;</td>
<td align="left"><input type="checkbox" name="admheadingsonly" value="1" <?php if($admonlyentryheadings==1) echo "checked"?>> <?php echo $l_admonlyentryheadings?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_admlistmaxchars?>:</td>
<td align="left"><input class="sninput" type="text" name="input_admentrychars" value="<?php echo $admentrychars?>" size="4" maxlength="10"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_admepp?>:</td>
<td align="left"><input class="sninput" type="text" name="input_admepp" value="<?php echo $admepp?>" size="4" maxlength="10"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_loginlimit?>:</td>
<td align="left"><input class="sninput" type="text" name="new_loginlimit" value="<?php echo $old_loginlimit?>" size="2" maxlength="2"></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_failednotify?>:<br><input name="dofailednotify" value="1" type="checkbox"
<?php if($enablefailednotify==1) echo " checked"?>><?php echo $l_enable?></td>
<td align="left" valign="top">
<?php
	$sql = "SELECT * FROM ".$tableprefix."_failed_notify fn, ".$tableprefix."_users u where u.usernr=fn.usernr order by u.username asc";
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
	$sql = "SELECT usernr, username FROM ".$tableprefix."_users WHERE rights > 2 ";
	if(isset($current_mods))
	{
    	while(list($null, $currMod) = each($current_mods)) {
			$sql .= "AND usernr != $currMod ";
    	}
    }
    $sql .= "ORDER BY username asc";
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
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_confirmdelentries?>:</td><td align="left">
<input type="radio" name="new_admdelconfirm" value="0" <?php if($admdelconfirm==0) echo "checked"?>> <?php echo $l_none?><br>
<input type="radio" name="new_admdelconfirm" value="1" <?php if($admdelconfirm==1) echo "checked"?>> <?php echo $l_onnextpage?><br>
<input type="radio" name="new_admdelconfirm" value="2" <?php if($admdelconfirm==2) echo "checked"?>> <?php echo $l_usingjavascript?>
</td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input type="checkbox" name="storefilter" value="1" <?php if($admstorefilter==1) echo "checked"?>>
<?php echo $l_storefilter?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_yearrange?>:</td><td align="left">
+/- <input type="text" name="newyearrange" value="<?php echo $yearrange?>" size="4" maxlength="4" class="sninput"> <?php echo $l_years?></td></tr>
<tr class="inputrow"><td align="right" width="30%">&nbsp;</td>
<td align="left"><input type="checkbox" name="altlayout" value="1" <?php if($admaltlayout==1) echo "checked"?>> <?php echo $l_admaltlayout?></td></tr>
<tr class="inputrow"><td>&nbsp;</td>
<td align="left"><input type="checkbox" name="doadmaltprv" value="1" <?php if($admaltprv==1) echo "checked"?>> <?php echo $l_admaltprv?></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b>BBCodes</b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_bbcimgdefalign?>:</td><td>
<select name="input_bbcimgdefalign">
<?php
for($i=0;$i<count($l_alignments);$i++)
{
	echo "<option value=\"".$l_alignments[$i]."\"";
	if($l_alignments[$i]==$bbcimgdefalign)
		echo " selected";
	echo ">".$l_alignments[$i]."</option>";
}
?>
</select>
</td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_icons?></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_maxwidth?>:</td><td>
<input type="text" name="input_icons_maxwidth" value="<?php echo $icons_maxwidth?>" size="4" maxlength="10" class="sninput"> <?php echo $l_px?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_maxheight?>:</td><td>
<input type="text" name="input_icons_maxheight" value="<?php echo $icons_maxheight?>" size="4" maxlength="10" class="sninput"> <?php echo $l_px?></td></tr>
<?php
if($gdavail)
{
?>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_inlinegfx?></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_maxwidth?>:</td><td>
<input type="text" name="input_inline_maxwidth" value="<?php echo $inline_maxwidth?>" size="4" maxlength="10" class="sninput"> <?php echo $l_px?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_maxheight?>:</td><td>
<input type="text" name="input_inline_maxheight" value="<?php echo $inline_maxheight?>" size="4" maxlength="10" class="sninput"> <?php echo $l_px?></td></tr>
<tr class="listheading2"><td align="left" colspan="2"><b><?php echo $l_thumbs?></b></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" name="inlinethumbs" value="1" <?php if($inline_genthumbs==1) echo "checked"?>> <?php echo $l_genthumbs?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_width?>:</td><td>
<input type="text" name="input_inline_thumbwidth" value="<?php echo $inline_thumbwidth?>" size="4" maxlength="10" class="sninput"> <?php echo $l_px?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_height?>:</td><td>
<input type="text" name="input_inline_thumbheight" value="<?php echo $inline_thumbheight?>" size="4" maxlength="10" class="sninput"> <?php echo $l_px?></td></tr>
<?php
}
else
{
	echo "<input type=\"hidden\" name=\"input_inline_maxwidth\" value=\"0\">";
	echo "<input type=\"hidden\" name=\"input_inline_maxheight\" value=\"0\">";
	echo "<input type=\"hidden\" name=\"input_inline_thumbwidth\" value=\"0\">";
	echo "<input type=\"hidden\" name=\"input_inline_thumbheight\" value=\"0\">";
}
?>
<tr class="listheading0"><td align="left" colspan="2"><b><?php echo $l_proposeentries?></b></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input type="checkbox" name="allowpropose" value="1" <?php if($enablepropose==1) echo "checked"?>> <?php echo $l_proposenews?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input type="checkbox" name="allowevpropose" value="1" <?php if($enableevpropose==1) echo "checked"?>> <?php echo $l_proposeevents?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input type="checkbox" name="noproppwconfirm" value="1" <?php if($prop_nopwconfirm==1) echo "checked"?>> <?php echo $l_propnopwconfirm?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_maxpropose?>:</td><td align="left">
<input type="text" class="sninput" name="maxpropose" value="<?php echo $maxpropose?>" size="4" maxlength="10"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_maxuploadsize?>:</td><td align="left">
<input type="text" class="sninput" name="maxuserupload" value="<?php echo $maxuserupload?>" size="10" maxlength="10"> Bytes</td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input type="checkbox" name="propenablefileupload" value="1" <?php if(bittst($proposepermissions,BIT_6)) echo "checked"?>>
<?php echo $l_posterfileupload?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="nrftprohibit" value="1" <?php if($prohibitnoregfiletypes==1) echo "checked"?>> <?php echo $l_prohibitnoregfiletypes?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input type="checkbox" name="newposter_nofileupload" value="1" <?php if(bittst($proposepermissions,BIT_7)) echo "checked"?>>
<?php echo $l_newposternofileupload?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input type="checkbox" name="newposter_nobbcode" value="1" <?php if(bittst($proposepermissions,BIT_5)) echo "checked"?>>
<?php echo $l_newposternobbcode?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left">
<input type="checkbox" name="editproposals" value="1" <?php if(bittst($proposepermissions,BIT_1)) echo "checked"?>> <?php echo $l_editproposals?><hr>
<b><?php echo $l_deletion?>:</b><br>
<input type="radio" name="pdelmode" value="0" <?php if(!bittst($proposepermissions,BIT_2)) echo "checked"?>> <?php echo $l_pdel_onlynotify?><br>
<input type="radio" name="pdelmode" value="1" <?php if(bittst($proposepermissions,BIT_2)) echo "checked"?>> <?php echo $l_pdel_dodel?><hr>
<b><?php echo $l_editing?>:</b><br>
<input type="radio" name="pedmode" value="0" <?php if(!bittst($proposepermissions,BIT_3)) echo "checked"?>> <?php echo $l_ped_newproposal?><br>
<input type="radio" name="pedmode" value="1" <?php if(bittst($proposepermissions,BIT_3)) echo "checked"?>> <?php echo $l_ped_doed?><br>
<input type="checkbox" name="pednobbcode" value="1" <?php if(bittst($proposepermissions,BIT_4)) echo "checked"?>> <?php echo $l_disablebbcode?>
</td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input type="checkbox" name="pnotify" value="1" <?php if($proposenotify==1) echo "checked"?>> <?php echo $l_proposenotify?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left">
<input type="radio" name="notifymode" value="0" <?php if($notifymode==0) echo "checked"?>> <?php echo $l_notifyfromlist?><br>
<input type="radio" name="notifymode" value="1" <?php if($notifymode==1) echo "checked"?>> <?php echo $l_notifyfromcat?></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_notifylist?>:</td>
<td valign="top" align="left">
<?php
$tmpsql = "select u.* from ".$tableprefix."_notifylist nl, ".$tableprefix."_users u where u.usernr=nl.usernr order by u.username asc";
if(!$tmpresult = mysql_query($tmpsql, $db))
	die("Could not connect to the database.");
if($temprow=mysql_fetch_array($tmpresult))
{
	 do {
		echo $temprow["username"]." (<input type=\"checkbox\" name=\"rem_notifymods[]\" value=\"".$temprow["usernr"]."\"> $l_remove)<BR>";
		$current_notifymods[] = $temprow["usernr"];
	 } while($temprow = mysql_fetch_array($tmpresult));
	 echo "<br>";
}
else
	echo "$l_noadmins<br><br>";
$tmpsql = "SELECT usernr, username FROM ".$tableprefix."_users WHERE rights > 1 ";
if(isset($current_notifymods))
{
	while(list($null, $currMod) = each($current_notifymods)) {
		$tmpsql .= "AND usernr != $currMod ";
	}
}
$tmpsql .= "ORDER BY username asc";
if(!$tmpresult = mysql_query($tmpsql, $db))
	die("Could not connect to the database.");
if($temprow = mysql_fetch_array($tmpresult)) {
	echo"<b>$l_add:</b><br>";
	echo"<SELECT NAME=\"notifymods[]\" size=\"5\" multiple>";
	do {
		echo "<OPTION VALUE=\"$temprow[usernr]\" >$temprow[username]</OPTION>\n";
	} while($temprow = mysql_fetch_array($tmpresult));
	echo"</select>";
}
?>
</td></tr>
<tr class="listheading0"><td align="left" colspan="2"><b><?php echo $l_userinterface?></b></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input type="checkbox" name="allowsearch" value="1" <?php if($enablesearch==1) echo "checked"?>> <?php echo $l_enablesearch?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input type="checkbox" name="doevsearch" value="1" <?php if($enableevsearch==1) echo "checked"?>> <?php echo $l_enablesearch." (".$l_events.")"?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input type="checkbox" name="visitcookie" value="1" <?php if($lastvisitcookie==1) echo "checked"?>> <?php echo $l_lastvisitcookie?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input type="checkbox" name="mailnews" value="1" <?php if($emailnews==1) echo "checked"?>> <?php echo $l_newsbymail?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input type="checkbox" name="dlcounts" value="1" <?php if($usedlcounts==1) echo "checked"?>> <?php echo $l_usedlcounts?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input type="checkbox" name="viewcounts" value="1" <?php if($useviewcounts==1) echo "checked"?>> <?php echo $l_useviewcounts?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_minviews?>:</td>
<td align="left"><input type="text" class="sninput" size="4" maxlength="10" value="<?php echo $minviews?>" name="newminviews"></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_comments?></b></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input type="checkbox" name="enablecomments" value="1" <?php if($allowcomments==1) echo "checked"?>> <?php echo $l_allowcomments?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input type="checkbox" name="dobwords" value="1" <?php if($usebwlist==1) echo "checked"?>> <?php echo $l_enablebadwordlist?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input type="checkbox" name="comnotify" value="1" <?php if($newcomnotify==1) echo "checked"?>> <?php echo $l_newcomnotify?></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_notifylist?>:</td>
<td valign="top" align="left">
<?php
$tmpsql = "select u.* from ".$tableprefix."_newcommentnotify ncn, ".$tableprefix."_users u where u.usernr=ncn.usernr order by u.username asc";
if(!$tmpresult = mysql_query($tmpsql, $db))
	die("Could not connect to the database.");
if($temprow=mysql_fetch_array($tmpresult))
{
	 do {
		echo $temprow["username"]." (<input type=\"checkbox\" name=\"rem_comnotify_mods[]\" value=\"".$temprow["usernr"]."\"> $l_remove)<BR>";
		$current_comnotify_mods[] = $temprow["usernr"];
	 } while($temprow = mysql_fetch_array($tmpresult));
	 echo "<br>";
}
else
	echo "$l_noadmins<br><br>";
$tmpsql = "SELECT usernr, username FROM ".$tableprefix."_users WHERE rights > 1 ";
if(isset($current_comnotify_mods))
{
	while(list($null, $currMod) = each($current_comnotify_mods)) {
		$tmpsql .= "AND usernr != $currMod ";
	}
}
$tmpsql .= "ORDER BY username asc";
if(!$tmpresult = mysql_query($tmpsql, $db))
	die("Could not connect to the database.");
if($temprow = mysql_fetch_array($tmpresult)) {
	echo"<b>$l_add:</b><br>";
	echo"<SELECT NAME=\"comnotify_mods[]\" size=\"5\" multiple>";
	do {
		echo "<OPTION VALUE=\"$temprow[usernr]\" >$temprow[username]</OPTION>\n";
	} while($temprow = mysql_fetch_array($tmpresult));
	echo"</select>";
}
?>
</td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_lvisitcookie?></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_cookieexpiretime?>:</td>
<td><input type="text" name="lastvisitdays" value="<?php echo $lastvisitdays?>" size="4" maxlength="10" class="sninput"> <?php echo $l_days?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_cookiesessiontime?>:</td>
<td><input type="text" name="lastvisitsessiontime" value="<?php echo $lastvisitsessiontime?>" size="4" maxlength="10" class="sninput"> <?php echo $l_minutes?></td></tr>
<tr class="listheading0"><td align="left" colspan="2"><b>hotnews6/7/8/9, news6</b></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_catstouse?>:</td><td align="left">
<?php
$generalselected=false;
$tmpsql = "select * from ".$tableprefix."_hn6cats where catnr=0";
if(!$tmpresult = mysql_query($tmpsql, $db))
	die("Could not connect to the database.");
if(mysql_num_rows($tmpresult)>0)
{
	echo $l_general." (<input type=\"checkbox\" name=\"rem_hn6cats[]\" value=\"0\"> $l_remove)<BR>";
	$generalselected=true;
}
$tmpsql = "select cat.* from ".$tableprefix."_categories cat, ".$tableprefix."_hn6cats hn6 where cat.catnr=hn6.catnr";
if(!$tmpresult = mysql_query($tmpsql, $db))
	die("Could not connect to the database.");
if($temprow=mysql_fetch_array($tmpresult))
{
	 do {
		echo $temprow["catname"]." (<input type=\"checkbox\" name=\"rem_hn6cats[]\" value=\"".$temprow["catnr"]."\"> $l_remove)<BR>";
		$current_hn6cats[] = $temprow["catnr"];
	 } while($temprow = mysql_fetch_array($tmpresult));
	 echo "<br>";
}
$tmpsql = "SELECT * FROM ".$tableprefix."_categories ";
$firstentry=true;
if(isset($current_hn6cats))
{
	while(list($null, $currCat) = each($current_hn6cats)) {
		if($firstentry)
		{
			$tmpsql.="WHERE ";
			$firstentry=false;
		}
		else
			$tmpsql.="AND ";
		$tmpsql .= "catnr != $currCat ";
	}
}
if(!$tmpresult = mysql_query($tmpsql, $db))
	die("Could not connect to the database.");
if(($temprow = mysql_fetch_array($tmpresult)) || !$generalselected) {
	echo"<b>$l_add:</b><br>";
	echo"<SELECT NAME=\"hn6cats[]\" size=\"5\" multiple>";
	if(!$generalselected)
		echo "<OPTION VALUE=\"0\">$l_general</OPTION>\n";
	do {
		echo "<OPTION VALUE=\"".$temprow["catnr"]."\" >".$temprow["catname"]."</OPTION>\n";
	} while($temprow = mysql_fetch_array($tmpresult));
	echo"</select>";
}
?>
</td></tr>
<tr class="listheading0"><td align="left" colspan="2"><b>evlist2.php</b></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_csvexport?>:</td>
<td>
<input type="radio" name="exporttype" value="0" <?php if($exporttype==0) echo "checked"?>> <?php echo $l_disabled?><br>
<input type="radio" name="exporttype" value="1" <?php if($exporttype==1) echo "checked"?>> <?php echo $l_onlyownentries?><br>
<input type="radio" name="exporttype" value="2" <?php if($exporttype==2) echo "checked"?>> <?php echo $l_allentries?><br>
</td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="enableasc" value="1" <?php if($asclist==1) echo "checked"?>> <?php echo $l_enableasclist?></td></tr>
<tr class="listheading0"><td align="left" colspan="2"><b><?php echo $l_eventcal?></b></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input type="checkbox" name="eventcalnews" value="1" <?php if ($newsineventcal==1) echo "checked"?>> <?php echo $l_newsineventcal?></td></tr>
<tr class="listheading0"><td align="left" colspan="2"><b><?php echo $l_general?></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_maxnewsage?>:</td>
<td align="left"><input class="sninput" type="text" name="maxage" value="<?php echo $maxage?>" size="5" maxlength="5"> <?php echo $l_days?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_sitename?>:</td>
<td align="left"><input class="sninput" type="text" name="sitename" value="<?php echo $sitename?>" size="40" maxlength="80"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_simpnewsmail?>:</td>
<td align="left"><input class="sninput" type="text" name="input_simpnewsmail" value="<?php echo $simpnewsmail?>" size="40" maxlength="180"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_simpnewsmailname?>:</td>
<td align="left"><input class="sninput" type="text" name="input_simpnewsmailname" value="<?php echo $simpnewsmailname?>" size="40" maxlength="180"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_servertimezone?>:</td>
<td>
<?php echo tz_select($act_servertimezone,"input_servertimezone")?>
</td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_displaytimezone?>:</td>
<td>
<?php echo tz_select($act_displaytimezone,"input_displaytimezone")?>
</td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="leacherblocking" value="1" <?php if($blockleacher==1) echo "checked"?>> <?php echo $l_blockleacher?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="dorating" value="1" <?php if($enablerating==1) echo "checked"?>> <?php echo $l_enablerating?></td></tr>
<tr class="listheading0"><td align="left" colspan="2"><b><?php echo $l_search?></b></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="logsearches" value="1" <?php if($dosearchlog==1) echo "checked"?>> <?php echo $l_logsearches?>
</td></tr>
<tr class="listheading0"><td align="left" colspan="2"><b><?php echo $l_sendingmails?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_mailmaxlinelength?>:</td>
<td align="left"><input type="text" name="newmailmaxlinelength" size="3" maxlength="3" class="sninput" value="<?php echo $mailmaxlinelength?>"> <?php echo $l_chars?></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_msendlimit?>:</td><td align="left">
<input class="sninput" name="input_msendlimit" value="<?php echo $msendlimit?>" size="4" maxlength="10" type="text"> <?php echo $l_seconds?><br>
<?php echo $l_msendlimit_remark?></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_sendnewsdelay?>:</td><td align="left">
<input class="sninput" name="input_sendnewsdelay" value="<?php echo $sendnewsdelay?>" size="4" maxlength="10" type="text"><?php echo "ms $l_sendnewsdelay_remark"?><br>
<?php echo $l_senddelayinterval1?> <input type="text" class="sninput" size="4" maxlength="10" name="input_senddelayinterval" value="<?php echo $senddelayinterval?>"><?php echo $l_senddelayinterval2?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="sendprogressbox" value="1" <?php if($showsendprogress==1) echo "checked"?>> <?php echo $l_showsendprogress?><br>
<input type="checkbox" name="progressautohide" value="1" <?php if($sendprogressautohide==1) echo "checked"?>> <?php echo $l_sendprogressautohide?></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_emaillog?>:</td><td>
<input type="radio" name="new_emaillog" value="0" <?php if($emaillog==0) echo "checked"?>> <?php echo $l_disabled?><br>
<input type="radio" name="new_emaillog" value="1" <?php if($emaillog==1) echo "checked"?>> <?php echo $l_only_failed?><br>
<input type="radio" name="new_emaillog" value="2" <?php if($emaillog==2) echo "checked"?>> <?php echo $l_all_attempts?>
</td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="emaildie" value="1" <?php if($emailerrordie==1) echo "checked"?>> <?php echo $l_die_on_emailerrors?></td></tr>
<tr class="listheading0"><td align="left" colspan="2"><b><?php echo $l_subscriptions?></b></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input type="checkbox" name="newallowsubscriptions" value="1" <?php if($enablesubscriptions==1) echo "checked"?>> <?php echo $l_enable?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input type="checkbox" name="subfreemailer" value="1" <?php if($subscriptionfreemailer==1) echo "checked"?>> <?php echo $l_subscriptionfreemailer?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input type="radio" name="input_subscriptionsendmode" value="0" <?php if($subscriptionsendmode==0) echo "checked"?>> <?php echo $l_directsend?><br>
<input type="radio" name="input_subscriptionsendmode" value="1" <?php if($subscriptionsendmode==1) echo "checked"?>> <?php echo $l_masssend?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input type="radio" name="new_newsletterlinking" value="0" <?php if($newsletterlinking==0) echo "checked"?>> <?php echo $l_nonewsletterlinking?><br>
<input type="radio" name="new_newsletterlinking" value="1" <?php if($newsletterlinking==1) echo "checked"?>> <?php echo $l_newsletterlinkinghead?><br>
<input type="radio" name="new_newsletterlinking" value="2" <?php if($newsletterlinking==2) echo "checked"?>> <?php echo $l_newsletterlinkingmore?><br>
</td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_maxconfirmtime?>:</td><td align="left" valign="top">
<input type="checkbox" name="nosubscriptionconfirm" onClick="settings_maxconfirmtime()" value="1" <?php if ($maxconfirmtime==0) echo "checked"?>> <?php echo $l_noconfirm?><br>
<select name="maxconfirmtime" <?php if($maxconfirmtime==0) echo "disabled"?>>
<?php
for($i=1;$i<10;$i++)
{
	echo "<option value=\"$i\"";
	if($i==$maxconfirmtime)
		echo " selected";
	echo ">$i</option>";
}
?>
</select>&nbsp;<?php echo $l_days?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_subject?>:</td><td align="left">
<input class="sninput" type="text" name="new_subject" value="<?php echo $subject?>" size="40" maxlength="80"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_subscriptionredirectdelay?>:</td>
<td><input class="sninput" type="text" name="redirectdelay" value="<?php echo $redirectdelay?>" size="2" maxlength="2"> <?php echo $l_seconds?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="evnewsletter" value="1" <?php if($evnewsletterinclude==1) echo "checked"?>> <?php echo $l_evnewsletterinclude?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="nonewslettericons" value="1" <?php if($newsletternoicons==1) echo "checked"?>> <?php echo $l_newsletternoicons?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="attachinlinepix" value="1" <?php if($newsletterattachinlinepix==1) echo "checked"?>> <?php echo $l_attachinlinepix?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="sendattachbymail" value="1" <?php if($mailattach==1) echo "checked"?>> <?php echo $l_mailattach?></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_notification?></b></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input type="checkbox" name="newsubnotify" value="1" <?php if($newsubscriptionnotify==1) echo "checked"?>>
<?php echo $l_newsubnotify?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left"><input type="checkbox" name="subremnot" value="1" <?php if($subremovenotify==1) echo "checked"?>>
<?php echo $l_subremovenotify?></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_notifylist?>:</td>
<td valign="top" align="left">
<?php
$tmpsql = "select u.* from ".$tableprefix."_newsubnotify nsn, ".$tableprefix."_users u where u.usernr=nsn.usernr order by u.username asc";
if(!$tmpresult = mysql_query($tmpsql, $db))
	die("Could not connect to the database.");
if($temprow=mysql_fetch_array($tmpresult))
{
	 do {
		echo $temprow["username"]." (<input type=\"checkbox\" name=\"rem_nsubmods[]\" value=\"".$temprow["usernr"]."\"> $l_remove)<BR>";
		$current_nsubmods[] = $temprow["usernr"];
	 } while($temprow = mysql_fetch_array($tmpresult));
	 echo "<br>";
}
else
	echo "$l_noadmins<br><br>";
$tmpsql = "SELECT usernr, username FROM ".$tableprefix."_users WHERE rights > 1 ";
if(isset($current_nsubmods))
{
	while(list($null, $currMod) = each($current_nsubmods)) {
		$tmpsql .= "AND usernr != $currMod ";
	}
}
$tmpsql .= "ORDER BY username asc";
if(!$tmpresult = mysql_query($tmpsql, $db))
	die("Could not connect to the database.");
if($temprow = mysql_fetch_array($tmpresult)) {
	echo"<b>$l_add:</b><br>";
	echo"<SELECT NAME=\"nsubmods[]\" size=\"5\" multiple>";
	do {
		echo "<OPTION VALUE=\"$temprow[usernr]\" >$temprow[username]</OPTION>\n";
	} while($temprow = mysql_fetch_array($tmpresult));
	echo"</select>";
}
?>
</td></tr>
<tr class="listheading0"><td align="left" colspan="2"><b><?php echo $l_rss_newsfeed?></b></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="rss_activ" value="1" <?php if($rss_enable==1) echo "checked"?>><?php echo $l_enable?></td></tr>
<tr class="listheading0"><td align="left" colspan="2"><b><?php echo $l_wap_newsfeed?></b></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="wap_activ" value="1" <?php if($wap_enable==1) echo "checked"?>><?php echo $l_enable?></td></tr>
<tr class="listheading0"><td align="left" colspan="2"><b><?php echo $l_permissions?></b></td></tr>
<tr class="inputrow"><td align="right" width="30%">&nbsp;</td>
<td align="left"><input type="checkbox" name="restrictadm" value="1" <?php if($admrestrict==1) echo "checked"?>> <?php echo $l_admrestrict?></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_newscat0?>:</td><td>
<input type="radio" name="newscat0lvl" value="0" <?php if(!bittst($secsettings,BIT_1)) echo "checked"?>> <?php echo $l_onlysuperadmin?><br>
<input type="radio" name="newscat0lvl" value="1" <?php if(bittst($secsettings,BIT_1)) echo "checked"?>> <?php echo $l_alladmins?>
</td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_news?>:</td><td>
<input type="checkbox" name="limitnewsedcat" value="1" <?php if(bittst($secsettings,BIT_15)) echo "checked"?>> <?php echo $l_limitedcat?></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo "$l_search ($l_news)"?>:</td><td>
<input type="checkbox" name="limitsnewsedcat" value="1" <?php if(bittst($secsettings,BIT_18)) echo "checked"?>> <?php echo $l_limitedcat?></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_eventscat0?>:</td><td>
<input type="radio" name="eventcat0lvl" value="0" <?php if(!bittst($secsettings,BIT_2)) echo "checked"?>> <?php echo $l_onlysuperadmin?><br>
<input type="radio" name="eventcat0lvl" value="1" <?php if(bittst($secsettings,BIT_2)) echo "checked"?>> <?php echo $l_alladmins?>
</td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_events?>:</td><td>
<input type="checkbox" name="limiteventsedcat" value="1" <?php if(bittst($secsettings,BIT_16)) echo "checked"?>> <?php echo $l_limitedcat?></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo "$l_search ($l_events)"?>:</td><td>
<input type="checkbox" name="limitseventsedcat" value="1" <?php if(bittst($secsettings,BIT_19)) echo "checked"?>> <?php echo $l_limitedcat?></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_postann?>:</td><td>
<input type="radio" name="annlvl" value="0" <?php if(!bittst($secsettings,BIT_3)) echo "checked"?>> <?php echo $l_onlysuperadmin?><br>
<input type="radio" name="annlvl" value="1" <?php if(bittst($secsettings,BIT_3)) echo "checked"?>> <?php echo $l_alladmins?>
</td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_announcements?>:</td><td>
<input type="checkbox" name="anhidenoed" value="1" <?php if(bittst($secsettings,BIT_14)) echo "checked"?>> <?php echo $l_menuhidenoed?><br>
<input type="checkbox" name="limitanedcat" value="1" <?php if(bittst($secsettings,BIT_17)) echo "checked"?>> <?php echo $l_limitedcat?></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo "$l_search ($l_announcements)"?>:</td><td>
<input type="checkbox" name="limitsanedcat" value="1" <?php if(bittst($secsettings,BIT_21)) echo "checked"?>> <?php echo $l_limitedcat?></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_anncat0?>:</td><td>
<input type="radio" name="anncat0lvl" value="0" <?php if(!bittst($secsettings,BIT_4)) echo "checked"?>> <?php echo $l_onlysuperadmin?><br>
<input type="radio" name="anncat0lvl" value="1" <?php if(bittst($secsettings,BIT_4)) echo "checked"?>> <?php echo $l_alladmins?>
</td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_editsubscribers?>:</td><td>
<input type="radio" name="edsub" value="0" <?php if(!bittst($secsettings,BIT_5)) echo "checked"?>> <?php echo $l_onlysuperadmin?><br>
<input type="radio" name="edsub" value="1" <?php if(bittst($secsettings,BIT_5)) echo "checked"?>> <?php echo $l_alladmins?>
</td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_sendnewsletter?>:</td><td align="left" valign="top">
<?php
$tmpsql = "select u.* from ".$tableprefix."_newsletteradmins na, ".$tableprefix."_users u where u.usernr=na.usernr order by u.username asc";
if(!$tmpresult = mysql_query($tmpsql, $db))
	die("Could not connect to the database.");
if($temprow=mysql_fetch_array($tmpresult))
{
	 do {
		echo $temprow["username"]." (<input type=\"checkbox\" name=\"rem_nlmods[]\" value=\"".$temprow["usernr"]."\"> $l_remove)<BR>";
		$current_nlmods[] = $temprow["usernr"];
	 } while($temprow = mysql_fetch_array($tmpresult));
	 echo "<br>";
}
else
	echo "$l_noadmins<br><br>";
$tmpsql = "SELECT usernr, username FROM ".$tableprefix."_users WHERE rights > 1 ";
if(isset($current_nlmods))
{
	while(list($null, $currMod) = each($current_nlmods)) {
		$tmpsql .= "AND usernr != $currMod ";
	}
}
$tmpsql .= "ORDER BY username asc";
if(!$tmpresult = mysql_query($tmpsql, $db))
	die("Could not connect to the database.");
if($temprow = mysql_fetch_array($tmpresult)) {
	echo"<b>$l_add:</b><br>";
	echo"<SELECT NAME=\"nlmods[]\" size=\"5\" multiple>";
	do {
		echo "<OPTION VALUE=\"$temprow[usernr]\" >$temprow[username]</OPTION>\n";
	} while($temprow = mysql_fetch_array($tmpresult));
	echo"</select>";
}
?>
</td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_nlcat0?>:</td><td>
<input type="radio" name="nlcat0" value="0" <?php if(!bittst($secsettings,BIT_10)) echo "checked"?>> <?php echo $l_onlysuperadmin?><br>
<input type="radio" name="nlcat0" value="1" <?php if(bittst($secsettings,BIT_10)) echo "checked"?>> <?php echo $l_alladmins?>
</td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_edposter?>:</td><td>
<input type="radio" name="edposter" value="0" <?php if(!bittst($secsettings,BIT_6)) echo "checked"?>> <?php echo $l_onlysuperadmin?><br>
<input type="radio" name="edposter" value="1" <?php if(bittst($secsettings,BIT_6)) echo "checked"?>> <?php echo $l_alladmins?>
</td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_edcat?>:</td><td>
<input type="radio" name="edcat" value="0" <?php if(!bittst($secsettings,BIT_7)) echo "checked"?>> <?php echo $l_onlysuperadmin?><br>
<input type="radio" name="edcat" value="1" <?php if(bittst($secsettings,BIT_7)) echo "checked"?>> <?php echo $l_alladmins?><hr>
<input type="checkbox" name="edcatlimit" value="1" <?php if(bittst($secsettings,BIT_22)) echo "checked"?>> <?php echo $l_limitedcat?>
</td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_purgenews?>:</td><td>
<input type="radio" name="purgenews" value="0" <?php if(!bittst($secsettings,BIT_8)) echo "checked"?>> <?php echo $l_onlysuperadmin?><br>
<input type="radio" name="purgenews" value="1" <?php if(bittst($secsettings,BIT_8)) echo "checked"?>> <?php echo $l_alladmins?>
</td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_purgeevents?>:</td><td>
<input type="radio" name="purgeevents" value="0" <?php if(!bittst($secsettings,BIT_9)) echo "checked"?>> <?php echo $l_onlysuperadmin?><br>
<input type="radio" name="purgeevents" value="1" <?php if(bittst($secsettings,BIT_9)) echo "checked"?>> <?php echo $l_alladmins?>
</td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_edproposednews?>:</td><td>
<input type="radio" name="newsproped" value="0" <?php if(!bittst($secsettings,BIT_11)) echo "checked"?>> <?php echo $l_onlysuperadmin?><br>
<input type="radio" name="newsproped" value="1" <?php if(bittst($secsettings,BIT_11)) echo "checked"?>> <?php echo $l_alladmins?>
</td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_edproposedevents?>:</td><td>
<input type="radio" name="evproped" value="0" <?php if(!bittst($secsettings,BIT_12)) echo "checked"?>> <?php echo $l_onlysuperadmin?><br>
<input type="radio" name="evproped" value="1" <?php if(bittst($secsettings,BIT_12)) echo "checked"?>> <?php echo $l_alladmins?>
</td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_edlayout?>:</td><td>
<input type="radio" name="layouted" value="0" <?php if(!bittst($secsettings,BIT_13)) echo "checked"?>> <?php echo $l_onlysuperadmin?><br>
<input type="radio" name="layouted" value="1" <?php if(bittst($secsettings,BIT_13)) echo "checked"?>> <?php echo $l_alladmins?>
</td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_importnews?>:</td><td>
<input type="radio" name="limitimport" value="0" <?php if(!bittst($secsettings,BIT_20)) echo "checked"?>> <?php echo $l_onlysuperadmin?><br>
<input type="radio" name="limitimport" value="1" <?php if(bittst($secsettings,BIT_20)) echo "checked"?>> <?php echo $l_alladmins?>
</td></tr>
<tr class="inputrow"><td align="right" valign="top">&nbsp;</td><td>
<input type="checkbox" name="requestlinks" value="1" <?php if(bittst($secsettings,BIT_23)) echo "checked"?>> <?php echo $l_requestlinks?>
</td></tr>
<tr class="actionrow"><td align="center" colspan="2"><input class="snbutton" type="submit" value="<?php echo $l_submit?>" name="submit"></td></tr>
</form>
</table></td></tr></table>
<?php include('./trailer.php')?>
