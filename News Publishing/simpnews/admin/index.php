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
$page_title=$l_admin_title;
require_once('./heading.php');
if($admin_rights > 0)
{
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="indexsep"><td align="center"><a name="general"><b><?php echo $l_general?></b></a></td></tr>
<?php
if($admin_rights > 2)
{
?>
<tr class="indexrow1"><td align="center"><a href="<?php echo do_url_session("emoticons.php?$langvar=$act_lang")?>"><?php echo $l_emoticons?></a></td></tr>
<tr class="indexrow1"><td align="center"><a href="<?php echo do_url_session("icons.php?$langvar=$act_lang")?>"><?php echo $l_icons?></a></td></tr>
<?php
}
?>
<tr class="indexsep2"><td align="center"><a name="cats"><b><?php echo $l_categories?></b></a></td></tr>
<tr class="indexrow1"><td align="center"><a href="<?php echo do_url_session("categories.php?$langvar=$act_lang")?>"><?php echo $l_categories?></a></td></tr>
<?php
if($admin_rights > 2)
{
?>
<tr class="indexrow1"><td align="center"><a href="<?php echo do_url_session("wap_catlist.php?$langvar=$act_lang")?>"><?php echo $l_wap_catlist?></a></td></tr>
<tr class="indexrow1"><td align="center"><a href="<?php echo do_url_session("rss_catlist.php?$langvar=$act_lang")?>"><?php echo $l_rss_catlist?></a></td></tr>
<?php
}
if($admin_rights >= $attachlevel)
{
?>
<tr class="indexsep2"><td align="center"><a name="files"><b><?php echo $l_files?></b></a></td></tr>
<tr class="indexrow1" align="center"><td><a href="<?php echo do_url_session("attachs.php?$langvar=$act_lang")?>"><?php echo $l_adminfiles?></a></td></tr>
<?php
}
if($admin_rights > 2)
{
?>
<tr class="indexrow1" align="center"><td><a href="<?php echo do_url_session("files_cleanup.php?$langvar=$act_lang")?>"><?php echo $l_files_cleanup?></a></td></tr>
<tr class="indexrow1" align="center"><td><a href="<?php echo do_url_session("mimetypes.php?$langvar=$act_lang")?>"><?php echo $l_filetypes?></a></td></tr>
<?php
}
if($admin_rights >= $anlevel)
{
?>
<tr class="indexsep"><td align="center"><a name="announce"><b><?php echo $l_announcements?></b></a></td></tr>
<tr class="indexrow2"><td align="center"><a href="<?php echo do_url_session("announce.php?$langvar=$act_lang")?>"><?php echo $l_editannouncements?></a></td></tr>
<tr class="indexrow2"><td align="center"><a href="<?php echo do_url_session("anviews.php?$langvar=$act_lang")?>"><?php echo $l_viewcounts?></a></td></tr>
<tr class="indexsep2"><td align="center"><a name="ansearch"><b><?php echo $l_searching?></b></a></td></tr>
<tr class="indexrow2"><td align="center"><a href="<?php echo do_url_session("ansearch.php?$langvar=$act_lang")?>"><?php echo $l_search?></a></td></tr>
<tr class="indexrow2"><td align="center"><a href="<?php echo do_url_session("rebuildansearch.php?$langvar=$act_lang")?>"><?php echo $l_rebuildsearch?></a></td></tr>
<?php
}
?>
<tr class="indexsep"><td align="center"><a name="news"><b><?php echo $l_news?></b></a></td></tr>
<tr class="indexrow1"><td align="center"><a href="<?php echo do_url_session("news.php?$langvar=$act_lang")?>"><?php echo $l_editnews?></a></td></tr>
<?php
if($admin_rights >= $importlevel)
{
?>
<tr class="indexrow1"><td align="center"><a href="<?php echo do_url_session("import_news.php?$langvar=$act_lang")?>"><?php echo $l_importnews?></a></td></tr>
<?php
}
if($admin_rights > 1)
{
?>
<tr class="indexrow1"><td align="center"><a href="<?php echo do_url_session("reordernews.php?$langvar=$act_lang")?>"><?php echo $l_reordernews?></a></td></tr>
<tr class="indexrow1"><td align="center"><a href="<?php echo do_url_session("nviews.php?$langvar=$act_lang")?>"><?php echo $l_viewcounts?></a></td></tr>
<tr class="indexrow1"><td align="center"><a href="<?php echo do_url_session("ntop.php?$langvar=$act_lang")?>"><?php echo $l_topratings?></a></td></tr>
<?php
if($admin_rights >= $nproplevel)
{
?>
<tr class="indexrow1"><td align="center"><a href="<?php echo do_url_session("proposes.php?$langvar=$act_lang")?>"><?php echo $l_proposednews?></a></td></tr>
<?php
}
if($admin_rights > 1)
{
?>
<tr class="indexrow1"><td align="center"><a href="<?php echo do_url_session("clist.php?$langvar=$act_lang")?>"><?php echo $l_listofcomments?></a></td></tr>
<?php
}
?>
<tr class="indexsep2"><td align="center"><a name="newssearch"><b><?php echo $l_searching?></b></a></td></tr>
<tr class="indexrow1"><td align="center"><a href="<?php echo do_url_session("search.php?$langvar=$act_lang")?>"><?php echo $l_search?></a></td></tr>
<tr class="indexrow1"><td align="center"><a href="<?php echo do_url_session("rebuildsearch.php?$langvar=$act_lang")?>"><?php echo $l_rebuildsearch?></a></td></tr>
<tr class="indexsep2"><td align="center"><a name="newsutils"><b><?php echo $l_utils?></b></a></td></tr>
<?php
}
if($admin_rights >= $pnlevel)
{
?>
<tr class="indexrow1"><td align="center"><a href="<?php echo do_url_session("purgenews.php?$langvar=$act_lang")?>"><?php echo $l_purgenews?></a></td></tr>
<?php
}
if($admin_rights > 1)
{
?>
<tr class="indexrow1"><td align="center"><a href="<?php echo do_url_session("syncnewsld.php?$langvar=$act_lang")?>"><?php echo $l_synclinkdates?></a></td></tr>
<?php
}
?>
<tr class="indexsep"><td align="center"><a name="events"><b><?php echo $l_events?></b></a></td></tr>
<tr class="indexrow2"><td align="center"><a href="<?php echo do_url_session("events.php?$langvar=$act_lang")?>"><?php echo $l_editevents?></a></td></tr>
<?php
if($admin_rights >= $importlevel)
{
?>
<tr class="indexrow2"><td align="center"><a href="<?php echo do_url_session("import_events.php?$langvar=$act_lang")?>"><?php echo $l_importevents?></a></td></tr>
<?php
}
if($admin_rights > 1)
{
?>
<tr class="indexrow2"><td align="center"><a href="<?php echo do_url_session("evviews.php?$langvar=$act_lang")?>"><?php echo $l_viewcounts?></a></td></tr>
<?php
if($admin_rights >= $evproplevel)
{
?>
<tr class="indexrow2"><td align="center"><a href="<?php echo do_url_session("evproposes.php?$langvar=$act_lang")?>"><?php echo $l_proposedevents?></a></td></tr>
<?php
}
?>
<tr class="indexsep2"><td align="center"><a name="evsearch"><b><?php echo $l_searching?></b></a></td></tr>
<tr class="indexrow2"><td align="center"><a href="<?php echo do_url_session("evsearch.php?$langvar=$act_lang")?>"><?php echo $l_search?></a></td></tr>
<tr class="indexrow2"><td align="center"><a href="<?php echo do_url_session("rebuildevsearch.php?$langvar=$act_lang")?>"><?php echo $l_rebuildsearch?></a></td></tr>
<tr class="indexsep2"><td align="center"><a name="evutils"><b><?php echo $l_utils?></b></a></td></tr>
<?php
}
if($admin_rights >= $pevlevel)
{
?>
<tr class="indexrow2"><td align="center"><a href="<?php echo do_url_session("purgeevents.php?$langvar=$act_lang")?>"><?php echo $l_purgeevents?></a></td></tr>
<?php
}
if($admin_rights > 1)
{
?>
<tr class="indexrow2"><td align="center"><a href="<?php echo do_url_session("syncevld.php?$langvar=$act_lang")?>"><?php echo $l_synclinkdates?></a></td></tr>
<?php
}
if($admin_rights >=$layoutlevel)
{
?>
<tr class="indexsep"><td align="center"><a name="set"><b><?php echo $l_settings?></b></a></td></tr>
<tr class="indexrow1"><td align="center"><a href="<?php echo do_url_session("$layoutlink")?>"><?php echo $l_layout?></a></td></tr>
<?php
}
if($admin_rights > 2)
{
?>
<tr class="indexrow1"><td align="center"><a href="<?php echo do_url_session("settings.php?$langvar=$act_lang")?>"><?php echo $l_syssettings?></a></td></tr>
<tr class="indexrow1"><td align="center"><a href="<?php echo do_url_session("texts.php?$langvar=$act_lang")?>"><?php echo $l_texts?></a></td></tr>
<tr class="indexrow1"><td align="center"><a href="<?php echo do_url_session("ratings.php?$langvar=$act_lang")?>"><?php echo $l_defratings?></a></td></tr>
<tr class="indexsep2"><td align="center"><a name="lists"><b><?php echo $l_lists?></b></a></td></tr>
<tr class="indexrow1"><td align="center"><a href="<?php echo do_url_session("freemailer.php?$langvar=$act_lang")?>"><?php echo $l_freemailerlist?></a></td></tr>
<tr class="indexrow1"><td align="center"><a href="<?php echo do_url_session("leacher.php?$langvar=$act_lang")?>"><?php echo $l_leacherlist?></a></td></tr>
<tr class="indexrow1"><td align="center"><a href="<?php echo do_url_session("badwords.php?$langvar=$act_lang")?>"><?php echo $l_badwordlist?></a></td></tr>
<?php
}
if($admin_rights >= $sublevel)
{
?>
<tr class="indexsep"><td align="center"><a name="subscription"><b><?php echo $l_subscriptions?></b></a></td></tr>
<tr class="indexrow2"><td align="center"><a href="<?php echo do_url_session("subscribers.php?$langvar=$act_lang")?>"><?php echo $l_subscribers?></a></td></tr>
<tr class="indexrow2"><td align="center"><a href="<?php echo do_url_session("sendoldnews.php?$langvar=$act_lang")?>"><?php echo $l_emailoldnews?></a></td></tr>
<?php
if($admin_rights >= $nllevel)
{
?>
<tr class="indexrow2"><td align="center"><a href="<?php echo do_url_session("sendnews.php?$langvar=$act_lang")?>"><?php echo $l_emailnews?></a></td></tr>
<?php
}
}
?>
<tr class="indexsep"><td align="center"><a name="users"><b><?php echo $l_user?></b></a></td></tr>
<tr class="indexrow1"><td align="center"><a href="<?php echo do_url_session("users.php?$langvar=$act_lang")?>"><?php echo $l_editusers?></a></td></tr>
<?php
if($admin_rights>=$posterlevel)
{
?>
<tr class="indexrow1"><td align="center"><a href="<?php echo do_url_session("poster.php?$langvar=$act_lang")?>"><?php echo $l_pposter?></a></td></tr>
<?php
}
if($admin_rights>2)
{
?>
<tr class="indexsep2"><td align="center"><a name="userutils"><b><?php echo $l_utils?></b></a></td></tr>
<tr class="indexrow1"><td align="center"><a href="<?php echo do_url_session("loginfailures.php?$langvar=$act_lang")?>"><?php echo $l_failed_logins?></a></td></tr>
<tr class="indexrow1"><td align="center"><a href="<?php echo do_url_session("banlist.php?$langvar=$act_lang")?>"><?php echo $l_ipbanlist?></a></td></tr>
<tr class="indexsep"><td align="center"><a name="admin"><b><?php echo $l_administration?></b></a></td></tr>
<tr class="indexrow2"><td align="center"><a href="<?php echo do_url_session("admmsgs.php?$langvar=$act_lang")?>"><?php echo $l_admmsgs?></a></td></tr>
<?php
if($admin_rights>=$logaccess)
{
	echo "<tr class=\"indexsep2\"><td align=\"center\"><a name=\"logs\"><b>$l_logs</b></a></td></tr>";
	if($admin_rights>=$emaillogaccess)
		echo "<tr class=\"indexrow2\"><td align=\"center\"><a href=\"".do_url_session("emaillogs.php?$langvar=$act_lang")."\">$l_emaillogs</a></td></tr>";
	if($admin_rights>=$searchlogaccess)
		echo "<tr class=\"indexrow2\"><td align=\"center\"><a href=\"".do_url_session("searchlogs.php?$langvar=$act_lang")."\">$l_searchlogs</a></td></tr>";
}
?>
<tr class="indexsep2"><td align="center"><a name="adminutils"><b><?php echo $l_utils?></b></a></td></tr>
<tr class="indexrow2"><td align="center"><a href="<?php echo do_url_session("hostcache.php?$langvar=$act_lang")?>"><?php echo $l_hostcache?></a></td></tr>
<tr class="indexrow2"><td align="center"><a href="<?php echo do_url_session("sessions.php?$langvar=$act_lang")?>"><?php echo $l_cleansession?></a></td></tr>
<?php
if($admin_rights>3)
{
?>
<tr class="indexrow2"><td align="center"><a href="<?php echo do_url_session("shutdown.php?$langvar=$act_lang")?>"><?php echo $l_shutdownsys?></a></td></tr>
<tr class="indexrow2"><td align="center"><a href="<?php echo do_url_session("internalinfo.php?$langvar=$act_lang")?>"><?php echo $l_internalinfo?></a></td></tr>
<?php
}
?>
<tr class="indexrow2"><td align="center"><a href="<?php echo do_url_session("sys_stats.php?$langvar=$act_lang")?>"><?php echo $l_sys_stats?></a></td></tr>
<tr class="indexsep2"><td align="center"><a name="dbbackup"><b><?php echo $l_backup?></b></a></td></tr>
<tr class="indexrow2"><td align="center"><a href="<?php echo do_url_session("backup.php?$langvar=$act_lang")?>"><?php echo $l_dbbackup?></a></td></tr>
<tr class="indexrow2"><td align="center"><a href="<?php echo do_url_session("restore.php?$langvar=$act_lang")?>"><?php echo $l_dbrestore?></a></td></tr>
<?php
if($admin_rights>3)
{
?>
<tr class="indexsep2"><td align="center"><a name="dbutils"><b><?php echo $l_dbutils?></b></a></td></tr>
<tr class="indexrow2"><td align="center"><a href="<?php echo do_url_session("tblrepair.php?$langvar=$act_lang")?>"><?php echo $l_repairtables?></a></td></tr>
<tr class="indexrow2"><td align="center"><a href="<?php echo do_url_session("tblpack.php?$langvar=$act_lang")?>"><?php echo $l_optimizetables?></a></td></tr>
<?php
}
?>
<tr class="indexsep2"><td align="center"><a name="codegens"><b><?php echo $l_codegens?></b></a></td></tr>
<tr class="indexrow2"><td align="center"><a href="<?php echo do_url_session("inc_gen.php?$langvar=$act_lang")?>"><?php echo $l_include_generator?></a></td></tr>
<tr class="indexrow2"><td align="center"><a href="<?php echo do_url_session("inc_sub.php?$langvar=$act_lang")?>"><?php echo $l_subscription_generator?></a></td></tr>
<?php
}
echo "</table></td></tr></table>";
}
else
{
	$sql="select * from ".$tableprefix."_texts where textid=\"acclck\" and lang=\"".$act_lang."\"";
	if(!$result = mysql_query($sql, $db))
	    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
	echo "<table align=\"center\" width=\"80%\" CELLPADDING=\"1\" CELLSPACING=\"0\" border=\"0\" valign=\"top\">";
	echo "<tr><TD BGCOLOR=\"#000000\">";
	echo "<table align=\"center\" width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">";
	echo "<tr class=\"displayrow\"><td align=\"center\">";
	if(!$myrow=mysql_fetch_array($result))
		echo $l_acclocked;
	else
		echo stripslashes($myrow["text"]);
	echo "</td></tr></table></td></tr></table>";
}
include('./trailer.php');
?>
