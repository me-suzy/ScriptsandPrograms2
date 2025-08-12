<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require_once('../config.php');
if(!isset($$langvar) || !$$langvar)
	$act_lang=$admin_lang;
else
	$act_lang=$$langvar;
include_once('./language/lang_'.$act_lang.'.php');
require_once('./auth.php');
$page_title=$l_admin_title;
require_once('./heading.php');
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="indexsep1"><td align="center"><a name="general"><b><?php echo $l_general?></b></a></td></tr>
<tr class="indexrow2" align="center"><td><a href="<?php echo do_url_session("os.php?$langvar=$act_lang")?>"><?php echo $l_oslist?></a></td></tr>
<tr class="indexrow2" align="center"><td><a href="<?php echo do_url_session("program.php?$langvar=$act_lang")?>"><?php echo $l_editprogs?></a></td></tr>
<tr class="indexrow2" align="center"><td><a href="<?php echo do_url_session("reorder_prog.php?$langvar=$act_lang")?>"><?php echo $l_reorder_prog?></a></td></tr>
<tr class="indexsep1"><td align="center"><a name="faq"><b><?php echo $l_faq?></b></a></td></tr>
<tr class="indexrow1" align="center"><td><a href="<?php echo do_url_session("categories.php?$langvar=$act_lang")?>"><?php echo $l_editcats?></a></td></tr>
<tr class="indexrow1" align="center"><td><a href="<?php echo do_url_session("subcategories.php?$langvar=$act_lang")?>"><?php echo $l_editsubcats?></a></td></tr>
<tr class="indexrow1" align="center"><td><a href="<?php echo do_url_session("faq.php?$langvar=$act_lang")?>"><?php echo $l_editfaq?></a></td></tr>
<?php
if($admin_rights>2)
{
?>
<tr class="indexrow1" align="center"><td><a href="<?php echo do_url_session("faqsearch.php?$langvar=$act_lang")?>"><?php echo $l_faqsearch?></a></td></tr>
<?php
}
if($admin_rights>2)
{
?>
<tr class="indexsep2"><td align="center"><a name="offline"><b><?php echo $l_offlinefunctions?></b></a></td></tr>
<tr class="indexrow1" align="center"><td><a href="<?php echo do_url_session("offlinelists.php?$langvar=$act_lang")?>"><?php echo $l_offlinelists?></a></td></tr>
<?php
if($upload_avail)
	echo "<tr class=\"indexrow1\" align=\"center\"><td><a href=\"".do_url_session("faq_upload.php?$langvar=$act_lang")."\">$l_faqupload</a></td></tr>";
?>
<tr class="indexrow1" align="center"><td><a href="<?php echo do_url_session("faq_download.php?$langvar=$act_lang")?>"><?php echo $l_faqdownload?></a></td></tr>
<?php
}
if($admin_rights>1)
{
?>
<tr class="indexsep2"><td align="center"><a name="reorder1"><b><?php echo $l_rearrange?></b></a></td></tr>
<tr class="indexrow1" align="center"><td><a href="<?php echo do_url_session("reorder_cat.php?$langvar=$act_lang")?>"><?php echo $l_reorder_cat?></a></td></tr>
<tr class="indexrow1" align="center"><td><a href="<?php echo do_url_session("reorder_subcat.php?$langvar=$act_lang")?>"><?php echo $l_reorder_subcat?></a></td></tr>
<tr class="indexrow1" align="center"><td><a href="<?php echo do_url_session("reorder_faq.php?$langvar=$act_lang")?>"><?php echo $l_reorder_faq?></a></td></tr>
<tr class="indexsep2"><td align="center"><a name="stats1"><b><?php echo $l_tools?></b></a></td></tr>
<tr class="indexrow1" align="center"><td><a href="<?php echo do_url_session("treeview.php?$langvar=$act_lang")?>"><?php echo $l_treeview?></a></td></tr>
<tr class="indexrow1" align="center"><td><a href="<?php echo do_url_session("stats.php?$langvar=$act_lang")?>"><?php echo $l_statistics?></a></td></tr>
<?php
}
if($admin_rights>=$nllevel)
{
?>
<tr class="indexsep2"><td align="center"><a name="subs"><b><?php echo $l_subscriptions?></b></a></td></tr>
<tr class="indexrow1"><td align="center"><a href="<?php echo do_url_session("subscribers.php?$langvar=$act_lang")?>"><?php echo $l_subscribers?></a></td></tr>
<tr class="indexrow1"><td align="center"><a href="<?php echo do_url_session("faqmail.php?$langvar=$act_lang")?>"><?php echo $l_emailfaq?></a></td></tr>
<?php
}
?>
<tr class="indexsep1"><td align="center"><a name="userposts"><b><?php echo $l_userposts?></b></a></td></tr>
<tr class="indexrow2" align="center"><td><a href="<?php echo do_url_session("userquestions.php?$langvar=$act_lang")?>"><?php echo $l_userquestions?></a></td></tr>
<tr class="indexrow2" align="center"><td><a href="<?php echo do_url_session("usercomments.php?$langvar=$act_lang")?>"><?php echo $l_usercomments?></a></td></tr>
<tr class="indexsep1"><td align="center"><a name="kb"><b><?php echo $l_kb?></b></a></td></tr>
<tr class="indexrow1" align="center"><td><a href="<?php echo do_url_session("kb_cats.php?$langvar=$act_lang")?>"><?php echo $l_editcats?></a></td></tr>
<tr class="indexrow1" align="center"><td><a href="<?php echo do_url_session("kb_subcats.php?$langvar=$act_lang")?>"><?php echo $l_editsubcats?></a></td></tr>
<tr class="indexrow1" align="center"><td><a href="<?php echo do_url_session("kb.php?$langvar=$act_lang")?>"><?php echo $l_editarticles?></a></td></tr>
<?php
if($admin_rights>2)
{
?>
<tr class="indexrow1" align="center"><td><a href="<?php echo do_url_session("kbsearch.php?$langvar=$act_lang")?>"><?php echo $l_kbsearch?></a></td></tr>
<?php
}
if($admin_rights>2)
{
?>
<tr class="indexsep2"><td align="center"><a name="offline"><b><?php echo $l_offlinefunctions?></b></a></td></tr>
<tr class="indexrow1" align="center"><td><a href="<?php echo do_url_session("offlinelists_kb.php?$langvar=$act_lang")?>"><?php echo $l_offlinelists?></a></td></tr>
<?php
if($upload_avail)
	echo "<tr class=\"indexrow1\" align=\"center\"><td><a href=\"".do_url_session("kb_upload.php?$langvar=$act_lang")."\">$l_kbupload</a></td></tr>";
?>
<tr class="indexrow1" align="center"><td><a href="<?php echo do_url_session("kb_download.php?$langvar=$act_lang")?>"><?php echo $l_kbdownload?></a></td></tr>
<?php
}
if($admin_rights>1)
{
?>
<tr class="indexsep2"><td align="center"><a name="reorder2"><b><?php echo $l_rearrange?></b></a></td></tr>
<tr class="indexrow1" align="center"><td><a href="<?php echo do_url_session("reorder_kb_cats.php?$langvar=$act_lang")?>"><?php echo $l_reorder_cat?></a></td></tr>
<tr class="indexrow1" align="center"><td><a href="<?php echo do_url_session("reorder_kb_subcats.php?$langvar=$act_lang")?>"><?php echo $l_reorder_subcat?></a></td></tr>
<tr class="indexrow1" align="center"><td><a href="<?php echo do_url_session("reorder_kb.php?$langvar=$act_lang")?>"><?php echo $l_reorder_kb?></a></td></tr>
<?php
}
?>
<tr class="indexsep2"><td align="center"><a name="stats2"><b><?php echo $l_tools?></b></a></td></tr>
<tr class="indexrow1" align="center"><td><a href="<?php echo do_url_session("kb_treeview.php?$langvar=$act_lang")?>"><?php echo $l_treeview?></a></td></tr>
<tr class="indexrow1" align="center"><td><a href="<?php echo do_url_session("kb_stats.php?$langvar=$act_lang")?>"><?php echo $l_statistics?></a></td></tr>
<?php
if($admin_rights>1)
{
?>
<tr class="indexsep1"><td align="center"><a name="files"><b><?php echo $l_files?></b></a></td></tr>
<tr class="indexrow2" align="center"><td><a href="<?php echo do_url_session("attachs.php?$langvar=$act_lang")?>"><?php echo $l_adminfiles?></a></td></tr>
<tr class="indexrow2" align="center"><td><a href="<?php echo do_url_session("admin_gfx.php?$langvar=$act_lang")?>"><?php echo $l_admingfx?></a></td></tr>
<?php
}
if($admin_rights>2)
{
?>
<tr class="indexrow2" align="center"><td><a href="<?php echo do_url_session("files_cleanup.php?$langvar=$act_lang")?>"><?php echo $l_files_cleanup?></a></td></tr>
<tr class="indexrow2" align="center"><td><a href="<?php echo do_url_session("mimetypes.php?$langvar=$act_lang")?>"><?php echo $l_filetypes?></a></td></tr>
<?php
}
?>
<tr class="indexsep1"><td align="center"><a name="users"><b><?php echo $l_adminmanagement?></b></a></td></tr>
<tr class="indexrow1" align="center"><td><a href="<?php echo do_url_session("users.php?$langvar=$act_lang")?>"><?php echo $l_editadmins?></a></td></tr>
<?php
if($admin_rights>2)
{
?>
<tr class="indexrow1" align="center"><td><a href="<?php echo do_url_session("loginfailures.php?$langvar=$act_lang")?>"><?php echo $l_failed_logins?></a></td></tr>
<?php
}
?>
<tr class="indexrow1" align="center"><td><a href="<?php echo do_url_session("banlist.php?$langvar=$act_lang")?>"><?php echo $l_ipbanlist?></a></td></tr>
<tr class="indexrow1" align="center"><td><a href="<?php echo do_url_session("freemailer.php?$langvar=$act_lang")?>"><?php echo $l_freemailerlist?></a></td></tr>
<?php
if($admin_rights>2)
{
?>
<tr class="indexsep1"><td align="center"><a name="settings"><b><?php echo $l_settings?></b></a></td></tr>
<tr class="indexrow2" align="center"><td><a href="<?php echo do_url_session("layout.php?$langvar=$act_lang")?>"><?php echo $l_editlayout?></a></td></tr>
<tr class="indexrow2" align="center"><td><a href="<?php echo do_url_session("settings.php?$langvar=$act_lang")?>"><?php echo $l_editsettings?></a></td></tr>
<tr class="indexrow2" align="center"><td><a href="<?php echo do_url_session("texts.php?$langvar=$act_lang")?>"><?php echo $l_texts?></a></td></tr>
<tr class="indexrow2" align="center"><td><a href="<?php echo do_url_session("badwords.php?$langvar=$act_lang")?>"><?php echo $l_badwordlist?></a></td></tr>
<tr class="indexsep1"><td align="center"><a name="admin"><b><?php echo $l_administration?></b></a></td></tr>
<tr class="indexrow1" align="center"><td><a href="<?php echo do_url_session("diraccess.php?$langvar=$act_lang")?>"><?php echo $l_diraccess?></a></td></tr>
<tr class="indexrow1" align="center"><td><a href="<?php echo do_url_session("hostcache.php?$langvar=$act_lang")?>"><?php echo $l_hostcache?></a></td></tr>
<tr class="indexrow1" align="center"><td><a href="<?php echo do_url_session("leacher.php?$langvar=$act_lang")?>"><?php echo $l_leacherlist?></a></td></tr>
<tr class="indexrow1" align="center"><td><a href="<?php echo do_url_session("sessions.php?$langvar=$act_lang")?>"><?php echo $l_cleansession?></a></td></tr>
<?php
if($admin_rights>=4)
{
?>
<tr class="indexrow1" align="center"><td><a href="<?php echo do_url_session("shutdown.php?$langvar=$act_lang")?>"><?php echo $l_shutdownsys?></a></td></tr>
<tr class="indexrow1"><td align="center"><a href="<?php echo do_url_session("internalinfo.php?$langvar=$act_lang")?>"><?php echo $l_internalinfo?></a></td></tr>
<?php
}
if($admin_rights>=$searchlogaccess)
	echo "<tr class=\"indexrow1\"><td align=\"center\"><a href=\"".do_url_session("searchlogs.php?$langvar=$act_lang")."\">$l_searchlogs</a></td></tr>";
?>
<tr class="indexsep2"><td align="center"><a name="database"><b><?php echo $l_database?></b></a></td></tr>
<tr class="indexrow1" align="center"><td><a href="<?php echo do_url_session("backup.php?$langvar=$act_lang")?>"><?php echo $l_dbbackup?></a></td></tr>
<tr class="indexrow1" align="center"><td><a href="<?php echo do_url_session("restore.php?$langvar=$act_lang")?>"><?php echo $l_dbrestore?></a></td></tr>
<tr class="indexrow1"><td align="center"><a href="<?php echo do_url_session("tblrepair.php?$langvar=$act_lang")?>"><?php echo $l_repairtables?></a></td></tr>
<tr class="indexrow1"><td align="center"><a href="<?php echo do_url_session("tblpack.php?$langvar=$act_lang")?>"><?php echo $l_optimizetables?></a></td></tr>
<?php
}
?>
</table></td></tr></table>
<?php include_once('./trailer.php')?>