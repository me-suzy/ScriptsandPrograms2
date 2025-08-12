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
require_once('../config.php');
if(!isset($lang) || !$lang)
	$lang=$admin_lang;
include_once('./language/lang_'.$lang.'.php');
require_once('./auth.php');
$page_title=$l_admin_title;
require_once('./heading.php');
$rowstyles=array("indexrow1","indexrow2");
$actstyle=0;
if($admin_rights>0)
{
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="indexsep1"><td align="center"><a name="#progs"><b><?php echo $l_progs?></b></a></td></tr>
<tr class="<?php echo $rowstyles[$actstyle%2]?>" align="center"><td><a href="<?php echo do_url_session("os.php?lang=$lang")?>"><?php echo $l_oslist?></a></td></tr>
<tr class="<?php echo $rowstyles[$actstyle%2]?>" align="center"><td><a href="<?php echo do_url_session("program.php?lang=$lang")?>"><?php echo $l_editprogs?></a></td></tr>
<tr class="<?php echo $rowstyles[$actstyle%2]?>" align="center"><td><a href="<?php echo do_url_session("changelog.php?lang=$lang")?>"><?php echo $l_changelog?></a></td></tr>
<tr class="<?php echo $rowstyles[$actstyle%2]?>" align="center"><td><a href="<?php echo do_url_session("bugtraq.php?lang=$lang")?>"><?php echo $l_bugtracking?></a></td></tr>
<tr class="<?php echo $rowstyles[$actstyle%2]?>" align="center"><td><a href="<?php echo do_url_session("todo.php?lang=$lang")?>"><?php echo $l_todo?></a></td></tr>
<tr class="<?php echo $rowstyles[$actstyle%2]?>" align="center"><td><a href="<?php echo do_url_session("todostats.php?lang=$lang")?>"><?php echo $l_todostats?></a></td></tr>
<tr class="<?php echo $rowstyles[$actstyle%2]?>" align="center"><td><a href="<?php echo do_url_session("requests.php?lang=$lang")?>"><?php echo $l_featurerequests?></a></td></tr>
<tr class="<?php echo $rowstyles[$actstyle%2]?>" align="center"><td><a href="<?php echo do_url_session("requeststats.php?lang=$lang")?>"><?php echo $l_requeststats?></a></td></tr>
<tr class="<?php echo $rowstyles[$actstyle%2]?>" align="center"><td><a href="<?php echo do_url_session("reference.php?lang=$lang")?>"><?php echo $l_references?></a></td></tr>
<?php
if($admin_rights>1)
{
?>
<tr class="<?php echo $rowstyles[$actstyle%2]?>" align="center"><td><a href="<?php echo do_url_session("mirrorserver.php?lang=$lang")?>"><?php echo $l_mirrorserver?></a></td></tr>
<tr class="<?php echo $rowstyles[$actstyle%2]?>" align="center"><td><a href="<?php echo do_url_session("download_files.php?lang=$lang")?>"><?php echo $l_downloadfiles?></a></td></tr>
<tr class="<?php echo $rowstyles[$actstyle%2]?>" align="center"><td><a href="<?php echo do_url_session("downloadstats.php?lang=$lang")?>"><?php echo $l_downloadstats?></a></td></tr>
<?php
	$actstyle++;
?>
<tr class="indexsep1"><td align="center"><a name="#news"><b><?php echo $l_newsletter?></b></a></td></tr>
<tr class="<?php echo $rowstyles[$actstyle%2]?>" align="center"><td><a href="<?php echo do_url_session("newsletter.php?lang=$lang")?>"><?php echo $l_sendnewsletter?></a></td></tr>
<tr class="<?php echo $rowstyles[$actstyle%2]?>" align="center"><td><a href="<?php echo do_url_session("subscribers.php?lang=$lang")?>"><?php echo $l_subscribers?></a></td></tr>
<?php
}
$actstyle++;
if($admin_rights>1)
{
?>
<tr class="indexsep1"><td align="center"><a name="#wparts"><b><?php echo $l_wparts?></b></a></td></tr>
<tr class="<?php echo $rowstyles[$actstyle%2]?>" align="center"><td><a href="<?php echo do_url_session("wparts.php?lang=$lang")?>"><?php echo $l_managewparts?></a></td></tr>
<?php
$actstyle++;
?>
<tr class="indexsep1"><td align="center"><a name="#partner"><b><?php echo $l_partnersites?></b></a></td></tr>
<tr class="<?php echo $rowstyles[$actstyle%2]?>" align="center"><td><a href="<?php echo do_url_session("partnersites.php?lang=$lang")?>"><?php echo $l_managepartnersites?></a></td></tr>
<tr class="<?php echo $rowstyles[$actstyle%2]?>" align="center"><td><a href="<?php echo do_url_session("partnerclicks.php?lang=$lang")?>"><?php echo $l_partnersiteclicks?></a></td></tr>
<?php
$actstyle++;
?>
<tr class="indexsep1"><td align="center"><a name="#screenshots"><b><?php echo $l_screenshots?></b></a></td></tr>
<tr class="<?php echo $rowstyles[$actstyle%2]?>" align="center"><td><a href="<?php echo do_url_session("screenshotdirs.php?lang=$lang")?>"><?php echo $l_directories?></a></td></tr>
<tr class="<?php echo $rowstyles[$actstyle%2]?>" align="center"><td><a href="<?php echo do_url_session("screenshots.php?lang=$lang")?>"><?php echo $l_managescreenshots?></a></td></tr>
<?php
}

?>
<tr class="indexsep1"><td align="center"><a name="#users"><b><?php echo $l_adminmanagement?></b></a></td></tr>
<tr class="<?php echo $rowstyles[$actstyle%2]?>" align="center"><td><a href="<?php echo do_url_session("users.php?lang=$lang")?>"><?php echo $l_editadmins?></a></td></tr>
<?php
if($admin_rights>2)
{
?>
<tr class="<?php echo $rowstyles[$actstyle%2]?>" align="center"><td><a href="<?php echo do_url_session("loginfailures.php?lang=$lang")?>"><?php echo $l_failed_logins?></a></td></tr>
<?php
}
if($admin_rights>1)
{
?>
<tr class="<?php echo $rowstyles[$actstyle%2]?>" align="center"><td><a href="<?php echo do_url_session("banlist.php?lang=$lang")?>"><?php echo $l_ipbanlist?></a></td></tr>
<tr class="<?php echo $rowstyles[$actstyle%2]?>" align="center"><td><a href="<?php echo do_url_session("freemailer.php?lang=$lang")?>"><?php echo $l_freemailerlist?></a></td></tr>
<?php
}
?>
<tr class="indexsep1"><td align="center"><a name="#layout"><b><?php echo $l_layout?></b></a></td></tr>
<?php
$actstyle++;
if($admin_rights>2)
{
?>
<tr class="<?php echo $rowstyles[$actstyle%2]?>" align="center"><td><a href="<?php echo do_url_session("settings.php?lang=$lang")?>"><?php echo $l_editsettings?></a></td></tr>
<?php
}
?>
<tr class="<?php echo $rowstyles[$actstyle%2]?>" align="center"><td><a href="<?php echo do_url_session("texts.php?lang=$lang")?>"><?php echo $l_texts?></a></td></tr>
<tr class="<?php echo $rowstyles[$actstyle%2]?>" align="center"><td><a href="<?php echo do_url_session("allowedrefs.php?lang=$lang")?>"><?php echo $l_allowedreferers?></a></td></tr>
<tr class="<?php echo $rowstyles[$actstyle%2]?>" align="center"><td><a href="<?php echo do_url_session("forbiddenrefs.php?lang=$lang")?>"><?php echo $l_forbiddenreferers?></a></td></tr>
<?php
if($admin_rights>2)
{
	$actstyle++;
?>
<tr class="indexsep1"><td align="center"><a name="#admin"><b><?php echo $l_administration?></b></a></td></tr>
<tr class="<?php echo $rowstyles[$actstyle%2]?>" align="center"><td><a href="<?php echo do_url_session("hostcache.php?lang=$lang")?>"><?php echo $l_hostcache?></a></td></tr>
<tr class="<?php echo $rowstyles[$actstyle%2]?>" align="center"><td><a href="<?php echo do_url_session("sessions.php?lang=$lang")?>"><?php echo $l_cleansession?></a></td></tr>
<tr class="<?php echo $rowstyles[$actstyle%2]?>" align="center"><td><a href="<?php echo do_url_session("shutdown.php?lang=$lang")?>"><?php echo $l_shutdownsys?></a></td></tr>
<tr class="<?php echo $rowstyles[$actstyle%2]?>" align="center"><td><a href="<?php echo do_url_session("backup.php?lang=$lang")?>"><?php echo $l_dbbackup?></a></td></tr>
<?php
}
?>
</table></td></tr></table>
<?php
}
else
{
	$sql="select * from ".$tableprefix."_texts where textid=\"acclck\" and lang=\"".$lang."\"";
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
include('trailer.php');
?>
