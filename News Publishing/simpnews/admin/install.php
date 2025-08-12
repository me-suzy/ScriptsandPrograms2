<?php
/***************************************************************************
 * (c)2002-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
// ini_set("display_errors","1");
require('../config.php');
require('./fill_freemailer.php');
require('./fill_emoticons.php');
require('./fill_icons.php');
require('./fill_leacher.php');
require('./fill_mimetypes.php');
?>
<html><body>
<div align="center"><h3>SimpNews V<?php echo $version?> Install</h3></div>
<br>
<?php
if(isset($submit))
{
if(!$admin_pw1 || !$admin_pw2 || !$admin_user)
	die("Needed fields not filled");
if($admin_pw1 != $admin_pw2)
{
	echo "<div align=\"center\">";
	echo "<font color=\"#ff2200\"><b>Error</b>: Passwords don't match</font>";
	echo "</div><br>";
}
else
{
echo "Creating tables...<br>";
flush();
// create table simpnews_catnames
$sql ="DROP TABLE IF EXISTS ".$tableprefix."_catnames;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_catnames");
$sql ="CREATE TABLE ".$tableprefix."_catnames (";
$sql.="catnr int(10) unsigned NOT NULL default '0',";
$sql.="lang varchar(4) NOT NULL default '',";
$sql.="catname varchar(40) NOT NULL default '',";
$sql.="headertext text NOT NULL,";
$sql.="UNIQUE KEY catlang (catnr,lang));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_catnames");
// create table simpnews_rss_catlist
$sql ="DROP TABLE IF EXISTS ".$tableprefix."_rss_catlist;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_rss_catlist");
$sql ="CREATE TABLE ".$tableprefix."_rss_catlist (";
$sql.="catnr int(10) unsigned NOT NULL default '0',";
$sql.="layoutid varchar(10) NOT NULL default '',";
$sql.="displaypos int(10) unsigned NOT NULL default '0',";
$sql.="UNIQUE KEY catlistkey (catnr,layoutid));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_rss_catlist");
// create table simpnews_wap_catlist
$sql ="DROP TABLE IF EXISTS ".$tableprefix."_wap_catlist;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_wap_catlist");
$sql ="CREATE TABLE ".$tableprefix."_wap_catlist (";
$sql.="catnr int(10) unsigned NOT NULL default '0',";
$sql.="layoutid varchar(10) NOT NULL default '',";
$sql.="modes int(10) unsigned NOT NULL default '0',";
$sql.="displaypos int(10) unsigned NOT NULL default '0',";
$sql.="UNIQUE KEY catlistkey (catnr,layoutid));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_wap_catlist".mysql_error());
// create table simpnews_newcommentnotify
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_newcommentnotify;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_newcommentnotify");
$sql = "CREATE TABLE ".$tableprefix."_newcommentnotify (";
$sql.= "usernr int(10) unsigned NOT NULL default '0',";
$sql.= "UNIQUE KEY usernr (usernr));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_newcommentnotify".mysql_error());
// create table simpnews_ratings
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_ratings;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_ratings");
$sql = "CREATE TABLE ".$tableprefix."_ratings (";
$sql.= "rating int(4) unsigned NOT NULL default '0',";
$sql.= "lang varchar(4) NOT NULL default '',";
$sql.= "text varchar(40) NOT NULL default '',";
$sql.= "UNIQUE KEY ratings_index (lang,rating))";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_ratings".mysql_error());
// create table simpnews_leachers
if(!table_exists($leacherprefix."_leachers"))
{
	$sql = "CREATE TABLE ".$leacherprefix."_leachers (";
	$sql.= "entrynr int(10) unsigned NOT NULL auto_increment,";
  	$sql.= "useragent varchar(80) NOT NULL default '',";
	$sql.= "description text,";
	$sql.= "PRIMARY KEY  (entrynr))";
	if(!$result = mysql_query($sql, $db))
		die("Unable to create table ".$leacherprefix."_leachers".mysql_error());
}
// create table simpnews_hn6cats
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_hn6cats;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_hn6cats");
$sql = "CREATE TABLE ".$tableprefix."_hn6cats (";
$sql.= "catnr int(10) NOT NULL default '0',";
$sql.= "UNIQUE KEY catnr (catnr));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_hn6cats".mysql_error());
// create table simpnews_announce_attachs
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_announce_attachs;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_announce_attachs");
$sql = "CREATE TABLE ".$tableprefix."_announce_attachs (";
$sql.= "entrynr int(10) unsigned NOT NULL auto_increment,";
$sql.= "announcenr int(10) unsigned NOT NULL default '0',";
$sql.= "attachnr int(10) unsigned NOT NULL default '0',";
$sql.= "PRIMARY KEY  (entrynr));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_announce_attachs".mysql_error());
// create table simpnews_ansearch
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_ansearch;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_ansearch");
$sql = "CREATE TABLE ".$tableprefix."_ansearch (";
$sql.= "annr int(10) unsigned NOT NULL default '0',";
$sql.= "text text NOT NULL)";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_ansearch".mysql_error());
// create table simpnews_tmpnews_attachs
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_tmpnews_attachs;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_tmpnews_attachs");
$sql = "CREATE TABLE ".$tableprefix."_tmpnews_attachs (";
$sql.= "entrynr int(10) unsigned NOT NULL auto_increment,";
$sql.= "newsnr int(10) unsigned NOT NULL default '0',";
$sql.= "attachnr int(10) unsigned NOT NULL default '0',";
$sql.= "PRIMARY KEY  (entrynr));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_tmpnews_attachs".mysql_error());
// create table simpnews_tmpevents_attachs
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_tmpevents_attachs;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_tmpevents_attachs");
$sql = "CREATE TABLE ".$tableprefix."_tmpevents_attachs (";
$sql.= "entrynr int(10) unsigned NOT NULL auto_increment,";
$sql.= "eventnr int(10) unsigned NOT NULL default '0',";
$sql.= "attachnr int(10) unsigned NOT NULL default '0',";
$sql.= "PRIMARY KEY  (entrynr));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_tmpevents_attachs".mysql_error());
// create table simpnews_newsletteradmins
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_newsletteradmins;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_newsletteradmins");
$sql = "CREATE TABLE ".$tableprefix."_newsletteradmins (";
$sql.= "usernr int(10) unsigned NOT NULL default '0',";
$sql.= "UNIQUE KEY usernr (usernr));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_newsletteradmins".mysql_error());
// create table simpnews_newsubnotify
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_newsubnotify;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_newsubnotify");
$sql = "CREATE TABLE ".$tableprefix."_newsubnotify (";
$sql.= "usernr int(10) unsigned NOT NULL default '0',";
$sql.= "UNIQUE KEY usernr (usernr));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_newsubnotify".mysql_error());
// create table simpnews_files
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_files;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_files");
$sql = "CREATE TABLE ".$tableprefix."_files (";
$sql.= "entrynr int(10) unsigned NOT NULL auto_increment,";
$sql.= "bindata longblob ,";
$sql.= "filename varchar(240) NOT NULL DEFAULT '' ,";
$sql.= "mimetype varchar(240) NOT NULL DEFAULT '' ,";
$sql.= "filesize int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "downloads int(10) unsigned NOT NULL default '0',";
$sql.= "fs_filename varchar(240) NOT NULL default '',";
$sql.= "description varchar(255) NOT NULL default '',";
$sql.= "PRIMARY KEY (entrynr));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_files".mysql_error());
// create table simpnews_news_attachs
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_news_attachs;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_news_attachs");
$sql = "CREATE TABLE ".$tableprefix."_news_attachs (";
$sql.= "entrynr int(10) unsigned NOT NULL auto_increment,";
$sql.= "newsnr int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "attachnr int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "PRIMARY KEY (entrynr));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_news_attachs".mysql_error());
// create table simpnews_events_attachs
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_events_attachs;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_events_attachs");
$sql = "CREATE TABLE ".$tableprefix."_events_attachs (";
$sql.= "entrynr int(10) unsigned NOT NULL auto_increment,";
$sql.= "eventnr int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "attachnr int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "PRIMARY KEY (entrynr));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_events_attachs".mysql_error());
// create table simpnews_filetypedescription
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_filetypedescription;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_filetypedescription");
$sql = "CREATE TABLE ".$tableprefix."_filetypedescription (";
$sql.= "mimetype int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "language varchar(10) NOT NULL DEFAULT '' ,";
$sql.= "description varchar(80) NOT NULL DEFAULT '' ,";
$sql.= "UNIQUE filetypedescription (mimetype,language));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_filetypedescription");
// create table simpnews_fileextensions
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_fileextensions;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_fileextensions");
$sql = "CREATE TABLE ".$tableprefix."_fileextensions (";
$sql.= "entrynr int(10) unsigned NOT NULL auto_increment,";
$sql.= "mimetype int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "extension varchar(20) NOT NULL DEFAULT '' ,";
$sql.= "PRIMARY KEY (entrynr));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_fileextensions");
// create table simpnews_mimetypes
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_mimetypes;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_mimetypes");
$sql = "CREATE TABLE ".$tableprefix."_mimetypes (";
$sql.= "entrynr int(10) unsigned NOT NULL auto_increment,";
$sql.= "mimetype varchar(240) NOT NULL DEFAULT '' ,";
$sql.= "icon varchar(240) NOT NULL DEFAULT '' ,";
$sql.= "noupload tinyint(1) unsigned NOT NULL default '0',";
$sql.= "PRIMARY KEY (entrynr));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_mimetypes");
// create table simpnews_globalmsg
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_globalmsg;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_globalmsg");
$sql = "CREATE TABLE ".$tableprefix."_globalmsg (";
$sql.= "entrynr int(10) unsigned NOT NULL auto_increment,";
$sql.= "added datetime NOT NULL default '0000-00-00 00:00:00',";
$sql.= "text text NOT NULL,";
$sql.= "lang varchar(4) NOT NULL default '',";
$sql.= "heading varchar(80) NOT NULL default '',";
$sql.= "PRIMARY KEY  (entrynr))";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_globalmsg");
// create table simpnews_evsearch
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_evsearch;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_evsearch");
$sql = "CREATE TABLE ".$tableprefix."_evsearch (";
$sql.= "eventnr int(10) unsigned NOT NULL default '0',";
$sql.= "text text NOT NULL);";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_evsearch");
// create table simpnews_session2
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_session2;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_session2");
$sql = "CREATE TABLE ".$tableprefix."_session2 (";
$sql.= "sessid int(10) unsigned NOT NULL default '0',";
$sql.= "usernr int(10) NOT NULL default '0',";
$sql.= "starttime int(10) unsigned NOT NULL default '0',";
$sql.= "remoteip varchar(15) NOT NULL default '',";
$sql.= "PRIMARY KEY  (sessid),";
$sql.= "KEY sess_id (sessid),";
$sql.= "KEY start_time (starttime),";
$sql.= "KEY remote_ip (remoteip));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_session2");
// create table simpnews_announce
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_announce;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_announce");
$sql = "CREATE TABLE ".$tableprefix."_announce (";
$sql.= "entrynr int(10) unsigned NOT NULL auto_increment,";
$sql.= "lang varchar(4) NOT NULL default '',";
$sql.= "date datetime NOT NULL default '0000-00-00 00:00:00',";
$sql.= "text text NOT NULL,";
$sql.= "heading varchar(80) NOT NULL default '',";
$sql.= "poster varchar(240) NOT NULL default '',";
$sql.= "category int(10) unsigned NOT NULL default '0',";
$sql.= "posterid int(10) unsigned NOT NULL default '0',";
$sql.= "expiredate int(14) unsigned default NULL,";
$sql.= "headingicon varchar(100) NOT NULL default '',";
$sql.= "firstdate int(14) unsigned NOT NULL default '0',";
$sql.= "views int(10) unsigned NOT NULL default '0',";
$sql.= "tickerurl varchar(240) NOT NULL default '',";
$sql.= "wap_nopublish tinyint(1) unsigned NOT NULL default '0',";
$sql.= "wap_short text NOT NULL,";
$sql.= "PRIMARY KEY  (entrynr));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_announce");
// create table simpnews_poster
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_poster;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_poster");
$sql = "CREATE TABLE ".$tableprefix."_poster (";
$sql.= "entrynr int(10) unsigned NOT NULL auto_increment,";
$sql.= "email varchar(240) NOT NULL DEFAULT '' ,";
$sql.= "name varchar(240) NOT NULL DEFAULT '' ,";
$sql.= "password varchar(40) binary NOT NULL default '',";
$sql.= "pid int(10) unsigned NOT NULL default '0',";
$sql.= "pwconfirmed tinyint(1) unsigned NOT NULL default '0',";
$sql.= "disablebbcode tinyint(1) unsigned NOT NULL default '0',";
$sql.= "disablefileupload tinyint(1) unsigned NOT NULL default '0',";
$sql.= "PRIMARY KEY (entrynr),";
$sql.= "UNIQUE FieldName (entrynr));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_poster");
// create table simpnews_notifylist
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_notifylist;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_notifylist");
$sql = "CREATE TABLE ".$tableprefix."_notifylist (";
$sql.= "usernr int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "UNIQUE usernr (usernr));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_notifylist");
// create table simpnews_texts
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_texts;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_texts");
$sql = "CREATE TABLE ".$tableprefix."_texts (";
$sql.= "textnr int(10) unsigned NOT NULL auto_increment,";
$sql.= "textid varchar(20) NOT NULL DEFAULT '' ,";
$sql.= "lang varchar(4) NOT NULL DEFAULT '' ,";
$sql.= "text text NOT NULL DEFAULT '' ,";
$sql.= "PRIMARY KEY (textnr));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_texts");
// create table simpnews_tmpevents
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_tmpevents;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_tmpevents");
$sql = "CREATE TABLE ".$tableprefix."_tmpevents (";
$sql.= "entrynr int(10) unsigned NOT NULL auto_increment,";
$sql.= "date date NOT NULL DEFAULT '0000-00-00' ,";
$sql.= "lang varchar(4) NOT NULL DEFAULT '' ,";
$sql.= "category int(10) NOT NULL DEFAULT '0' ,";
$sql.= "heading varchar(80) NOT NULL DEFAULT '' ,";
$sql.= "text text NOT NULL DEFAULT '' ,";
$sql.= "added datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ,";
$sql.= "posterip varchar(16) NOT NULL DEFAULT '' ,";
$sql.= "posterid int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "chgevent int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "postingid varchar(40) NOT NULL default '0',";
$sql.= "PRIMARY KEY (entrynr));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_tmpevents");
// create table simpnews_tmpdata
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_tmpdata;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_tmpdata");
$sql = "CREATE TABLE ".$tableprefix."_tmpdata (";
$sql.= "entrynr int(10) unsigned NOT NULL auto_increment,";
$sql.= "lang varchar(4) NOT NULL DEFAULT '' ,";
$sql.= "date datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ,";
$sql.= "text text NOT NULL DEFAULT '' ,";
$sql.= "heading varchar(80) NOT NULL DEFAULT '' ,";
$sql.= "category int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "posterip varchar(16) NOT NULL DEFAULT '' ,";
$sql.= "posterid int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "chgnews int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "postingid varchar(40) NOT NULL default '0',";
$sql.= "PRIMARY KEY (entrynr));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_tmpdata");
// create table simpnews_cat_adm
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_cat_adm;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_cat_adm");
$sql = "CREATE TABLE ".$tableprefix."_cat_adm (";
$sql.= "catnr int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "usernr int(10) unsigned NOT NULL DEFAULT '0' );";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_cat_adm");
// create table simpnews_events
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_events;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_events");
$sql = "CREATE TABLE ".$tableprefix."_events (";
$sql.= "eventnr int(10) unsigned NOT NULL auto_increment,";
$sql.= "date datetime NOT NULL default '0000-00-00 00:00:00',";
$sql.= "lang varchar(4) NOT NULL DEFAULT '' ,";
$sql.= "poster varchar(240) NOT NULL DEFAULT '' ,";
$sql.= "category int(10) NOT NULL DEFAULT '0' ,";
$sql.= "heading varchar(80) NOT NULL DEFAULT '' ,";
$sql.= "headingicon varchar(240) NOT NULL DEFAULT '' ,";
$sql.= "text text NOT NULL DEFAULT '' ,";
$sql.= "added datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ,";
$sql.= "posterid int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "exposter int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "dontemail tinyint(1) unsigned NOT NULL default '0',";
$sql.= "linkeventnr int(10) unsigned NOT NULL default '0',";
$sql.= "views int(10) unsigned NOT NULL default '0',";
$sql.= "wap_nopublish tinyint(1) unsigned NOT NULL default '0',";
$sql.= "wap_short text NOT NULL,";
$sql.= "PRIMARY KEY (eventnr));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_events");
// create table simpnews_search
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_search;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_search");
$sql = "CREATE TABLE ".$tableprefix."_search (";
$sql.= "newsnr int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "text text NOT NULL DEFAULT '' );";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_search");
// create table simpnews_comments
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_comments;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_comments");
$sql = "CREATE TABLE ".$tableprefix."_comments (";
$sql.= "commentnr int(10) unsigned NOT NULL auto_increment,";
$sql.= "poster varchar(80) NOT NULL DEFAULT '' ,";
$sql.= "email varchar(80) NOT NULL DEFAULT '' ,";
$sql.= "entryref int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "comment text NOT NULL DEFAULT '' ,";
$sql.= "enterdate datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ,";
$sql.= "postingid varchar(40) NOT NULL default '0',";
$sql.= "PRIMARY KEY (commentnr));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_comments");
// create table simpnews_categories
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_categories;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_categories");
$sql = "CREATE TABLE ".$tableprefix."_categories (";
$sql.= "catnr int(10) unsigned NOT NULL auto_increment,";
$sql.= "catname varchar(40) NOT NULL DEFAULT '' ,";
$sql.= "hideincatlist tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "enablepropose tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "customfooter text NOT NULL,";
$sql.= "footeroptions tinyint(4) unsigned NOT NULL default '0',";
$sql.= "newsframelayout varchar(10) NOT NULL default '',";
$sql.= "displaypos int(10) unsigned NOT NULL default '0',";
$sql.= "icon varchar(100) NOT NULL default '',";
$sql.= "iconoptions int(10) unsigned NOT NULL default '0',";
$sql.= "headertext text NOT NULL,";
$sql.= "hideintotallist tinyint(1) unsigned NOT NULL default '0',";
$sql.= "excludefromnewsletter tinyint(1) unsigned NOT NULL default '0',";
$sql.= "nlsenddate datetime NOT NULL default '0000-00-00 00:00:00',";
$sql.= "isarchiv tinyint(1) unsigned NOT NULL default '0',";
$sql.= "ignoreonsearch tinyint(1) unsigned NOT NULL default '0',";
$sql.= "rss_channel_title varchar(100) NOT NULL default '',";
$sql.= "rss_channel_description varchar(255) NOT NULL default '',";
$sql.= "rss_channel_link varchar(255) NOT NULL default '',";
$sql.= "rss_channel_copyright varchar(100) NOT NULL default '',";
$sql.= "rss_channel_editor varchar(100) NOT NULL default '',";
$sql.= "evmarkcolor varchar(7) NOT NULL default '#555555',";
$sql.= "enablerating tinyint(1) unsigned NOT NULL default '1',";
$sql.= "PRIMARY KEY (catnr));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_categories");
// create table simpnews_icons
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_icons;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_icons");
$sql = "CREATE TABLE ".$tableprefix."_icons (";
$sql.= "iconnr int(10) unsigned NOT NULL auto_increment,";
$sql.= "icon_url varchar(100) NOT NULL DEFAULT '' ,";
$sql.= "PRIMARY KEY (iconnr));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_icons");
// create table simpnews_emoticons
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_emoticons;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_emoticons");
$sql = "CREATE TABLE ".$tableprefix."_emoticons (";
$sql.= "iconnr int(10) unsigned NOT NULL auto_increment,";
$sql.= "code varchar(20) NOT NULL DEFAULT '' ,";
$sql.= "emoticon_url varchar(100) NOT NULL DEFAULT '' ,";
$sql.= "emotion varchar(80) NOT NULL DEFAULT '' ,";
$sql.= "PRIMARY KEY (iconnr));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_emoticons");
// create table simpnews_subscriptions
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_subscriptions;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_subscriptions");
$sql = "CREATE TABLE ".$tableprefix."_subscriptions (";
$sql.= "subscriptionnr int(10) unsigned NOT NULL auto_increment,";
$sql.= "email varchar(240) NOT NULL DEFAULT '' ,";
$sql.= "confirmed int(1) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "language varchar(4) NOT NULL DEFAULT '' ,";
$sql.= "subscribeid int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "unsubscribeid int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "enterdate datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ,";
$sql.= "lastsent datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ,";
$sql.= "emailtype tinyint(1) unsigned NOT NULL DEFAULT '1' ,";
$sql.= "lastmanual datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ,";
$sql.= "category int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "PRIMARY KEY (subscriptionnr));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_subscriptions");
// create table simpnews_users
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_users;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_users");
$sql = "CREATE TABLE ".$tableprefix."_users (";
$sql.= "usernr tinyint(3) unsigned NOT NULL auto_increment,";
$sql.= "username varchar(80) NOT NULL DEFAULT '' ,";
$sql.= "password varchar(40) binary NOT NULL DEFAULT '' ,";
$sql.= "email varchar(80) ,";
$sql.= "rights int(2) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "lastlogin datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ,";
$sql.= "lockpw tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "realname varchar(240) NOT NULL DEFAULT '' ,";
$sql.= "autopin int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "language varchar(10) NOT NULL DEFAULT 'de' ,";
$sql.= "lockentry tinyint(1) unsigned NOT NULL default '0',";
$sql.= "addoptions int(10) unsigned NOT NULL default '0',";
$sql.= "PRIMARY KEY (usernr));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_users");
// create table simpnews_settings
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_settings;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_settings");
$sql = "CREATE TABLE ".$tableprefix."_settings (";
$sql.= "settingnr int(10) unsigned NOT NULL auto_increment,";
$sql.= "watchlogins tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "enablefailednotify tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "simpnewsmail varchar(180) NOT NULL DEFAULT '' ,";
$sql.= "loginlimit int(2) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "usemenubar tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "nofreemailer tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "enablehostresolve tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "enablesubscriptions tinyint(1) NOT NULL DEFAULT '0' ,";
$sql.= "maxconfirmtime int(1) unsigned NOT NULL DEFAULT '2' ,";
$sql.= "subject varchar(80) NOT NULL DEFAULT '' ,";
$sql.= "subscriptionsendmode tinyint(1) unsigned NOT NULL DEFAULT '1' ,";
$sql.= "subscriptionfreemailer tinyint(1) unsigned NOT NULL DEFAULT '1' ,";
$sql.= "sitename varchar(80) NOT NULL DEFAULT '' ,";
$sql.= "maxage int(5) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "allowcomments tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "enablesearch tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "redirectdelay tinyint(2) NOT NULL DEFAULT '-1' ,";
$sql.= "newsineventcal tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "lastvisitcookie tinyint(1) unsigned NOT NULL DEFAULT '1' ,";
$sql.= "servertimezone int(10) NOT NULL DEFAULT '0' ,";
$sql.= "displaytimezone int(10) NOT NULL DEFAULT '0' ,";
$sql.= "simpnewsmailname varchar(180) NOT NULL DEFAULT '' ,";
$sql.= "admrestrict tinyint(3) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "newsletternoicons tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "maxpropose int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "enablepropose tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "enableevpropose tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "proposenotify tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "notifymode tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "admentrychars int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "admonlyentryheadings tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "proposepermissions tinyint(4) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "exporttype tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "asclist tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "admdelconfirm tinyint(4) unsigned NOT NULL default '0',";
$sql.= "mailattach tinyint(1) unsigned NOT NULL default '0',";
$sql.= "evnewsletterinclude tinyint(1) unsigned NOT NULL default '0',";
$sql.= "msendlimit int(10) unsigned NOT NULL default '30',";
$sql.= "admepp int(10) unsigned NOT NULL default '0',";
$sql.= "secsettings int(10) unsigned NOT NULL default '0',";
$sql.= "bbcimgdefalign varchar(20) NOT NULL default 'Center',";
$sql.= "newsletterattachinlinepix tinyint(1) unsigned NOT NULL default '0',";
$sql.= "icons_maxheight int(10) unsigned NOT NULL default '20',";
$sql.= "icons_maxwidth int(10) unsigned NOT NULL default '20',";
$sql.= "inline_thumbwidth int(10) unsigned NOT NULL default '50',";
$sql.= "inline_thumbheight int(10) unsigned NOT NULL default '50',";
$sql.= "inline_genthumbs tinyint(1) unsigned NOT NULL default '0',";
$sql.= "inline_maxwidth int(10) unsigned NOT NULL default '640',";
$sql.= "inline_maxheight int(10) unsigned NOT NULL default '480',";
$sql.= "admstorefilter tinyint(1) unsigned NOT NULL default '1',";
$sql.= "newsubscriptionnotify tinyint(1) unsigned NOT NULL default '0',";
$sql.= "subremovenotify tinyint(1) unsigned NOT NULL default '0',";
$sql.= "maxuserupload int(10) unsigned NOT NULL default '100000',";
$sql.= "emailnews tinyint(1) unsigned NOT NULL default '0',";
$sql.= "yearrange tinyint(2) unsigned NOT NULL default '5',";
$sql.= "useviewcounts tinyint(1) unsigned NOT NULL default '0',";
$sql.= "minviews int(10) unsigned NOT NULL default '0',";
$sql.= "usedlcounts tinyint(1) unsigned NOT NULL default '0',";
$sql.= "admaltlayout tinyint(1) unsigned NOT NULL default '0',";
$sql.= "enablerating tinyint(1) unsigned NOT NULL default '0',";
$sql.= "blockleacher tinyint(1) unsigned NOT NULL default '0',";
$sql.= "sendnewsdelay int(10) unsigned NOT NULL default '0',";
$sql.= "senddelayinterval int(10) unsigned NOT NULL default '1',";
$sql.= "showsendprogress tinyint(1) unsigned NOT NULL default '1',";
$sql.= "sendprogressautohide tinyint(1) unsigned NOT NULL default '1',";
$sql.= "lastvisitdays int(10) unsigned NOT NULL default '365',";
$sql.= "lastvisitsessiontime int(10) unsigned NOT NULL default '60',";
$sql.= "dosearchlog tinyint(1) unsigned NOT NULL default '0',";
$sql.= "newcomnotify tinyint(1) unsigned NOT NULL default '0',";
$sql.= "rss_enable tinyint(1) unsigned NOT NULL default '0',";
$sql.= "wap_enable tinyint(1) unsigned NOT NULL default '0',";
$sql.= "emaillog int(2) unsigned NOT NULL default '0',";
$sql.= "emailerrordie tinyint(1) unsigned NOT NULL default '1',";
$sql.= "prohibitnoregfiletypes tinyint(1) unsigned NOT NULL default '1',";
$sql.= "newsletterlinking tinyint(1) unsigned NOT NULL default '0',";
$sql.= "mailmaxlinelength int(10) unsigned NOT NULL default '998',";
$sql.= "prop_nopwconfirm tinyint(1) unsigned default '0',";
$sql.= "admaltprv tinyint(1) unsigned NOT NULL default '0',";
$sql.= "enableevsearch tinyint(1) unsigned NOT NULL default '0',";
$sql.= "usebwlist tinyint(1) unsigned NOT NULL default '0',";
$sql.= "PRIMARY KEY (settingnr));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_settings");
// create table simpnews_session
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_session;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_session");
$sql = "CREATE TABLE ".$tableprefix."_session (";
$sql.= "sessid int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "usernr int(10) NOT NULL DEFAULT '0' ,";
$sql.= "starttime int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "remoteip varchar(15) NOT NULL DEFAULT '' ,";
$sql.= "lastlogin datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ,";
$sql.= "PRIMARY KEY (sessid),";
$sql.= "INDEX sess_id (sessid),";
$sql.= "INDEX start_time (starttime),";
$sql.= "INDEX remote_ip (remoteip));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_session");
// create table simpnews_misc
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_misc;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_misc");
$sql = "CREATE TABLE ".$tableprefix."_misc (";
$sql.= "shutdown tinyint(3) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "shutdowntext text);";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_misc");
// create table simpnews_iplog
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_iplog;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_iplog");
$sql = "CREATE TABLE ".$tableprefix."_iplog (";
$sql.= "lognr int(10) unsigned NOT NULL auto_increment,";
$sql.= "usernr int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "logtime datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ,";
$sql.= "ipadr varchar(16) NOT NULL DEFAULT '' ,";
$sql.= "used_lang varchar(4) NOT NULL DEFAULT '' ,";
$sql.= "PRIMARY KEY (lognr));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_iplog");
// create table simpnews_bad_words
if(!table_exists($badwordprefix."_bad_words"))
{
	$sql = "CREATE TABLE ".$badwordprefix."_bad_words (";
	$sql.= "indexnr int(10) unsigned NOT NULL auto_increment,";
	$sql.= "word varchar(100) NOT NULL default '',";
	$sql.= "replacement varchar(100) NOT NULL default '',";
	$sql.= "PRIMARY KEY  (indexnr));";
	if(!$result = mysql_query($sql, $db))
		die("Unable to create table ".$badwordprefix."_bad_words");
}
// create table simpnews_hostcache
if(!table_exists($hcprefix."_hostcache"))
{
	$sql = "CREATE TABLE /*!32300 IF NOT EXISTS*/ ".$hcprefix."_hostcache (";
	$sql.= "ipadr varchar(16) NOT NULL DEFAULT '0' ,";
	$sql.= "hostname varchar(240) NOT NULL DEFAULT '' ,";
	$sql.= "UNIQUE ipadr (ipadr));";
	if(!$result = mysql_query($sql, $db))
		die("Unable to create table ".$hcprefix."_hostcache");
}
// create table simpnews_freemailer
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_freemailer;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_freemailer");
$sql = "CREATE TABLE ".$tableprefix."_freemailer (";
$sql.= "entrynr int(10) unsigned NOT NULL auto_increment,";
$sql.= "address varchar(100) NOT NULL DEFAULT '' ,";
$sql.= "PRIMARY KEY (entrynr));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_freemailer");
// create table simpnews_failed_notify
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_failed_notify;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_failed_notify");
$sql = "CREATE TABLE ".$tableprefix."_failed_notify (";
$sql.= "usernr int(10) unsigned NOT NULL DEFAULT '0');";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_failed_notify");
// create table simpnews_failed_logins
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_failed_logins;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_failed_logins");
$sql = "CREATE TABLE ".$tableprefix."_failed_logins (";
$sql.= "loginnr int(10) unsigned NOT NULL auto_increment,";
$sql.= "username varchar(250) NOT NULL DEFAULT '0' ,";
$sql.= "ipadr varchar(16) NOT NULL DEFAULT '' ,";
$sql.= "logindate datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ,";
$sql.= "usedpw varchar(240) NOT NULL DEFAULT '' ,";
$sql.= "PRIMARY KEY (loginnr));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_failed_logins");
// create table simpnews_banlist
if(!table_exists($banprefix."_banlist"))
{
	$sql = "CREATE TABLE /*!32300 IF NOT EXISTS*/ ".$banprefix."_banlist (";
	$sql.="bannr int(10) unsigned NOT NULL auto_increment,";
	$sql.="ipadr varchar(16) NOT NULL DEFAULT '0.0.0.0' ,";
	$sql.="subnetmask varchar(16) NOT NULL DEFAULT '0.0.0.0' ,";
	$sql.="reason text ,";
	$sql.="PRIMARY KEY (bannr));";
	if(!$result = mysql_query($sql, $db))
		die("Unable to create table ".$banprefix."_banlist");
}
// create table simpnews_data
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_data;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_data");
$sql = "CREATE TABLE ".$tableprefix."_data (";
$sql .="newsnr int(10) unsigned NOT NULL auto_increment,";
$sql .="lang varchar(4) NOT NULL DEFAULT '' ,";
$sql .="date datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ,";
$sql .="text text NOT NULL DEFAULT '' ,";
$sql .="heading varchar(80) NOT NULL DEFAULT '' ,";
$sql .="poster varchar(240) NOT NULL DEFAULT '' ,";
$sql.= "headingicon varchar(100) NOT NULL DEFAULT '' ,";
$sql.= "category int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "allowcomments tinyint(1) unsigned NOT NULL DEFAULT '1' ,";
$sql.= "tickerurl varchar(240) NOT NULL DEFAULT '' ,";
$sql.= "posterid int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "exposter int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "dontemail tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "linknewsnr int(10) unsigned NOT NULL default '0',";
$sql.= "added datetime NOT NULL default '0000-00-00 00:00:00',";
$sql.= "displaypos int(10) unsigned NOT NULL default '0',";
$sql.= "views int(10) unsigned NOT NULL default '0',";
$sql.= "ratings int(10) unsigned NOT NULL default '0',";
$sql.= "ratingcount int(10) unsigned NOT NULL default '0',";
$sql.= "rss_short text NOT NULL,";
$sql.= "rss_nopublish tinyint(1) unsigned NOT NULL default '0',";
$sql.= "wap_nopublish tinyint(1) unsigned NOT NULL default '0',";
$sql.= "dontpurge tinyint(1) unsigned default '0',";
$sql.= "norating tinyint(1) unsigned NOT NULL default '0',";
$sql .="PRIMARY KEY (newsnr));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_data");
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_layout;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_layout");
$sql = "CREATE TABLE ".$tableprefix."_layout (";
$sql.="lang varchar(4) NOT NULL DEFAULT '0' ,";
$sql.="heading varchar(80) NOT NULL DEFAULT '' ,";
$sql.="headingbgcolor varchar(7) NOT NULL DEFAULT '' ,";
$sql.="headingfontcolor varchar(7) NOT NULL DEFAULT '' ,";
$sql.="headingfont varchar(240) NOT NULL DEFAULT '' ,";
$sql.="headingfontsize varchar(4) NOT NULL DEFAULT '' ,";
$sql.="bordercolor varchar(7) NOT NULL DEFAULT '' ,";
$sql.="contentbgcolor varchar(7) NOT NULL DEFAULT '' ,";
$sql.="contentfontcolor varchar(7) NOT NULL DEFAULT '' ,";
$sql.="contentfont varchar(240) NOT NULL DEFAULT '' ,";
$sql.="contentfontsize varchar(4) NOT NULL DEFAULT '' ,";
$sql.="TableWidth varchar(10) NOT NULL DEFAULT '' ,";
$sql.="timestampfontcolor varchar(7) NOT NULL DEFAULT '' ,";
$sql.="timestampfontsize varchar(4) NOT NULL DEFAULT '' ,";
$sql.="timestampfont varchar(240) NOT NULL DEFAULT '' ,";
$sql.="dateformat varchar(20) NOT NULL DEFAULT '' ,";
$sql.="showcurrtime tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql.="customheader text NOT NULL DEFAULT '' ,";
$sql.="pagebgcolor varchar(7) NOT NULL DEFAULT '' ,";
$sql.="stylesheet varchar(80) NOT NULL DEFAULT '' ,";
$sql.="newsheadingbgcolor varchar(7) NOT NULL DEFAULT '' ,";
$sql.="newsheadingfontcolor varchar(7) NOT NULL DEFAULT '' ,";
$sql.="newsheadingstyle tinyint(1) NOT NULL DEFAULT '0' ,";
$sql.="posterbgcolor varchar(7) NOT NULL DEFAULT '' ,";
$sql.="posterfontcolor varchar(7) NOT NULL DEFAULT '' ,";
$sql.="posterstyle tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql.="displayposter tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql.="posterfont varchar(240) NOT NULL DEFAULT '' ,";
$sql.="posterfontsize varchar(4) NOT NULL DEFAULT '' ,";
$sql.="newsheadingfont varchar(240) NOT NULL DEFAULT '' ,";
$sql.="newsheadingfontsize varchar(4) NOT NULL DEFAULT '' ,";
$sql.="timestampbgcolor varchar(7) NOT NULL DEFAULT '' ,";
$sql.="timestampstyle tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql.="displaysubscriptionbox tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql.="defsignature varchar(240) NOT NULL DEFAULT '' ,";
$sql.="subscriptionbgcolor varchar(7) NOT NULL DEFAULT '' ,";
$sql.="subscriptionfontcolor varchar(7) NOT NULL DEFAULT '' ,";
$sql.="subscriptionfont varchar(240) NOT NULL DEFAULT '' ,";
$sql.="subscriptionfontsize varchar(4) NOT NULL DEFAULT '' ,";
$sql.="copyrightbgcolor varchar(7) NOT NULL DEFAULT '' ,";
$sql.="copyrightfontcolor varchar(7) NOT NULL DEFAULT '' ,";
$sql.="copyrightfont varchar(240) NOT NULL DEFAULT '' ,";
$sql.="copyrightfontsize varchar(4) NOT NULL DEFAULT '' ,";
$sql.="emailremark text NOT NULL DEFAULT '' ,";
$sql.="layoutnr int(10) NOT NULL auto_increment,";
$sql.="id varchar(10) NOT NULL DEFAULT '' ,";
$sql.="deflayout tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql.="customfooter text NOT NULL DEFAULT '' ,";
$sql.="searchpic varchar(240) NOT NULL DEFAULT 'search.gif' ,";
$sql.="backpic varchar(240) NOT NULL DEFAULT 'back.gif' ,";
$sql.="pagepic_back varchar(240) NOT NULL DEFAULT 'prev.gif' ,";
$sql.="pagepic_first varchar(240) NOT NULL DEFAULT 'first.gif' ,";
$sql.="pagepic_next varchar(240) NOT NULL DEFAULT 'next.gif' ,";
$sql.="pagepic_last varchar(240) NOT NULL DEFAULT 'last.gif' ,";
$sql.="pagetoppic varchar(240) NOT NULL DEFAULT 'pagetop.gif' ,";
$sql.="newssignal_on varchar(240) NOT NULL DEFAULT 'blink.gif' ,";
$sql.="newssignal_off varchar(240) NOT NULL DEFAULT 'off.gif' ,";
$sql.="helppic varchar(240) NOT NULL DEFAULT 'help.gif' ,";
$sql.="attachpic varchar(240) NOT NULL DEFAULT 'attach.gif' ,";
$sql.="prevpic varchar(240) NOT NULL DEFAULT 'prev_big.gif' ,";
$sql.="fwdpic varchar(240) NOT NULL DEFAULT 'next_big.gif' ,";
$sql.="eventheading varchar(80) NOT NULL DEFAULT '' ,";
$sql.="event_dateformat varchar(20) NOT NULL DEFAULT '' ,";
$sql.="newstickerbgcolor varchar(7) NOT NULL DEFAULT '#cccccc' ,";
$sql.="newstickerfontcolor varchar(7) NOT NULL DEFAULT '#000000' ,";
$sql.="newstickerfont varchar(240) NOT NULL DEFAULT 'Verdana' ,";
$sql.="newstickerfontsize tinyint(2) unsigned NOT NULL DEFAULT '12' ,";
$sql.="newstickerhighlightcolor varchar(7) NOT NULL DEFAULT '#0000ff' ,";
$sql.="newstickerheight int(10) unsigned NOT NULL DEFAULT '20' ,";
$sql.="newstickerwidth int(10) unsigned NOT NULL DEFAULT '300' ,";
$sql.="newstickerscrollspeed tinyint(2) unsigned NOT NULL DEFAULT '1' ,";
$sql.="newstickerscrolldelay int(10) unsigned NOT NULL DEFAULT '30' ,";
$sql.="newstickermaxdays int(10) NOT NULL DEFAULT '0' ,";
$sql.="newstickermaxentries int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.="newsscrollerbgcolor varchar(7) NOT NULL DEFAULT '#cccccc' ,";
$sql.="newsscrollerfontcolor varchar(7) NOT NULL DEFAULT '#000000' ,";
$sql.="newsscrollerfont varchar(240) NOT NULL DEFAULT 'Verdana' ,";
$sql.="newsscrollerfontsize tinyint(2) unsigned NOT NULL DEFAULT '12' ,";
$sql.="newsscrollerheight int(10) unsigned NOT NULL DEFAULT '300' ,";
$sql.="newsscrollerwidth int(10) unsigned NOT NULL DEFAULT '200' ,";
$sql.="newsscrollerscrollspeed tinyint(2) unsigned NOT NULL DEFAULT '1' ,";
$sql.="newsscrollerscrolldelay int(10) unsigned NOT NULL DEFAULT '100' ,";
$sql.="newsscrollerscrollpause int(10) unsigned NOT NULL DEFAULT '2000' ,";
$sql.="newsscrollermaxdays int(10) NOT NULL DEFAULT '0' ,";
$sql.="newsscrollermaxentries int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.="newsscrollertype tinyint(4) unsigned NOT NULL DEFAULT '4' ,";
$sql.="newsscrollerbgimage varchar(240) NOT NULL DEFAULT '' ,";
$sql.="newsscrollerfgimage varchar(240) NOT NULL DEFAULT '' ,";
$sql.="newsscrollermousestop tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql.="newsscrollermaxchars int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.="newstickertarget varchar(80) NOT NULL DEFAULT '_self' ,";
$sql.="newsscrollertarget varchar(80) NOT NULL DEFAULT '_self' ,";
$sql.="newsscrollerxoffset tinyint(4) unsigned NOT NULL DEFAULT '0' ,";
$sql.="newsscrolleryoffset tinyint(4) unsigned NOT NULL DEFAULT '0' ,";
$sql.="newsscrollerwordwrap tinyint(1) unsigned NOT NULL DEFAULT '1' ,";
$sql.="newsscrollerdisplaydate tinyint(1) unsigned NOT NULL DEFAULT '1' ,";
$sql.="newsscrollerdateformat varchar(20) NOT NULL DEFAULT 'Y-m-d' ,";
$sql.="newentrypic varchar(240) NOT NULL DEFAULT 'new.gif' ,";
$sql.="newstypermaxentries int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.="newstyperbgcolor varchar(7) NOT NULL DEFAULT '#cccccc' ,";
$sql.="newstyperfontcolor varchar(7) NOT NULL DEFAULT '#000000' ,";
$sql.="newstyperfont varchar(240) NOT NULL DEFAULT 'Verdana' ,";
$sql.="newstyperfontsize tinyint(2) unsigned NOT NULL DEFAULT '12' ,";
$sql.="newstyperfontstyle tinyint(2) unsigned NOT NULL DEFAULT '0' ,";
$sql.="newstyperdisplaydate tinyint(1) unsigned NOT NULL DEFAULT '1' ,";
$sql.="newstyperdateformat varchar(20) NOT NULL DEFAULT 'Y-m-d' ,";
$sql.="newstyperxoffset tinyint(4) unsigned NOT NULL DEFAULT '8' ,";
$sql.="newstyperyoffset tinyint(4) unsigned NOT NULL DEFAULT '8' ,";
$sql.="newstypermaxdays int(10) NOT NULL DEFAULT '0' ,";
$sql.="newstypermaxchars int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.="newstyperwidth int(10) unsigned NOT NULL DEFAULT '200' ,";
$sql.="newstyperheight int(10) unsigned NOT NULL DEFAULT '300' ,";
$sql.="newstyperbgimage varchar(240) NOT NULL DEFAULT '' ,";
$sql.="newstyperscroll tinyint(1) unsigned NOT NULL DEFAULT '1' ,";
$sql.="newsscrollernolinking tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql.="newstyper2maxentries int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.="newstyper2bgcolor varchar(7) NOT NULL DEFAULT '#cccccc' ,";
$sql.="newstyper2fontcolor varchar(7) NOT NULL DEFAULT '#000000' ,";
$sql.="newstyper2fontsize tinyint(2) unsigned NOT NULL DEFAULT '12' ,";
$sql.="newstyper2displaydate tinyint(1) unsigned NOT NULL DEFAULT '1' ,";
$sql.="newstyper2newscreen tinyint(1) unsigned NOT NULL DEFAULT '1' ,";
$sql.="newstyper2waitentry tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql.="newstyper2dateformat varchar(20) NOT NULL DEFAULT 'Y-m-d' ,";
$sql.="newstyper2indent tinyint(4) unsigned NOT NULL DEFAULT '8' ,";
$sql.="newstyper2linespace tinyint(4) unsigned NOT NULL DEFAULT '15' ,";
$sql.="newstyper2maxdays int(10) NOT NULL DEFAULT '-1' ,";
$sql.="newstyper2maxchars int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.="newstyper2width int(10) unsigned NOT NULL DEFAULT '300' ,";
$sql.="newstyper2height int(10) unsigned NOT NULL DEFAULT '200' ,";
$sql.="newstyper2bgimage varchar(240) NOT NULL DEFAULT '' ,";
$sql.="newstyper2sound varchar(240) NOT NULL DEFAULT 'sfx/tick.au' ,";
$sql.="newstyper2charpause int(10) unsigned NOT NULL DEFAULT '50' ,";
$sql.="newstyper2linepause int(10) unsigned NOT NULL DEFAULT '500' ,";
$sql.="newstyper2screenpause int(10) unsigned NOT NULL DEFAULT '5000' ,";
$sql.="eventscrolleractdate tinyint(1) unsigned NOT NULL DEFAULT '1' ,";
$sql.="separatebylang tinyint(1) unsigned NOT NULL DEFAULT '1' ,";
$sql.="headerfile varchar(250) NOT NULL DEFAULT '' ,";
$sql.="footerfile varchar(250) NOT NULL DEFAULT '' ,";
$sql.="headerfilepos tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql.="footerfilepos tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql.="usecustomheader tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql.="usecustomfooter tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql.="copyrightpos tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql.="categorybgcolor varchar(7) NOT NULL DEFAULT '#999999' ,";
$sql.="categoryfont varchar(240) NOT NULL DEFAULT 'Verdana, Geneva, Arial, Helvetica, sans-serif' ,";
$sql.="categoryfontsize varchar(4) NOT NULL DEFAULT '3' ,";
$sql.="categoryfontcolor varchar(7) NOT NULL DEFAULT '#EEEEEE' ,";
$sql.="categorystyle int(2) unsigned NOT NULL DEFAULT '0' ,";
$sql.="hotnews2target varchar(240) NOT NULL DEFAULT '_self' ,";
$sql.="news2target varchar(240) NOT NULL DEFAULT '_self' ,";
$sql.="newsscrollermaxlines int(2) unsigned NOT NULL DEFAULT '20' ,";
$sql.="linkcolor varchar(7) NOT NULL DEFAULT '#696969' ,";
$sql.="vlinkcolor varchar(7) NOT NULL DEFAULT '#696969' ,";
$sql.="alinkcolor varchar(7) NOT NULL DEFAULT '#696969' ,";
$sql.="morelinkcolor varchar(7) NOT NULL DEFAULT '#191970' ,";
$sql.="morevlinkcolor varchar(7) NOT NULL DEFAULT '#191970' ,";
$sql.="morealinkcolor varchar(7) NOT NULL DEFAULT '#191970' ,";
$sql.="catlinkcolor varchar(7) NOT NULL DEFAULT '#F0FFFF' ,";
$sql.="catvlinkcolor varchar(7) NOT NULL DEFAULT '#F0FFFF' ,";
$sql.="catalinkcolor varchar(7) NOT NULL DEFAULT '#F0FFFF' ,";
$sql.="commentlinkcolor varchar(7) NOT NULL DEFAULT '#191970' ,";
$sql.="commentvlinkcolor varchar(7) NOT NULL DEFAULT '#191970' ,";
$sql.="commentalinkcolor varchar(7) NOT NULL DEFAULT '#191970' ,";
$sql.="attachlinkcolor varchar(7) NOT NULL DEFAULT '#CD5C5C' ,";
$sql.="attachvlinkcolor varchar(7) NOT NULL DEFAULT '#CD5C5C' ,";
$sql.="attachalinkcolor varchar(7) NOT NULL DEFAULT '#CD5C5C' ,";
$sql.="pagenavlinkcolor varchar(7) NOT NULL DEFAULT '#FFF0C0' ,";
$sql.="pagenavvlinkcolor varchar(7) NOT NULL DEFAULT '#FFF0C0' ,";
$sql.="pagenavalinkcolor varchar(7) NOT NULL DEFAULT '#FFF0C0' ,";
$sql.="colorscrollbars tinyint(1) NOT NULL DEFAULT '1' ,";
$sql.="sbfacecolor varchar(7) NOT NULL DEFAULT '#94AAD6' ,";
$sql.="sbhighlightcolor varchar(7) NOT NULL DEFAULT '#AFEEEE' ,";
$sql.="sbshadowcolor varchar(7) NOT NULL DEFAULT '#ADD8E6' ,";
$sql.="sbdarkshadowcolor varchar(7) NOT NULL DEFAULT '#4682B4' ,";
$sql.="sb3dlightcolor varchar(7) NOT NULL DEFAULT '#1E90FF' ,";
$sql.="sbarrowcolor varchar(7) NOT NULL DEFAULT '#0000ff' ,";
$sql.="sbtrackcolor varchar(7) NOT NULL DEFAULT '#E0FFFF' ,";
$sql.="snsel_bgcolor varchar(7) NOT NULL DEFAULT '#DCDCDC' ,";
$sql.="snsel_fontcolor varchar(7) NOT NULL DEFAULT '#000000' ,";
$sql.="snsel_font varchar(240) NOT NULL DEFAULT 'Verdana, Geneva, Arial, Helvetica, sans-serif' ,";
$sql.="snsel_fontsize varchar(10) NOT NULL DEFAULT '10pt' ,";
$sql.="snsel_fontstyle varchar(20) NOT NULL DEFAULT 'normal' ,";
$sql.="snsel_fontweight varchar(20) NOT NULL DEFAULT 'normal' ,";
$sql.="snsel_borderstyle varchar(20) NOT NULL DEFAULT 'none' ,";
$sql.="snsel_bordercolor varchar(7) NOT NULL DEFAULT '' ,";
$sql.="snsel_borderwidth varchar(20) NOT NULL DEFAULT '' ,";
$sql.="morelinkfontsize varchar(20) NOT NULL DEFAULT '8pt' ,";
$sql.="sninput_bgcolor varchar(7) NOT NULL DEFAULT '#DCDCDC' ,";
$sql.="sninput_fontcolor varchar(7) NOT NULL DEFAULT '#000000' ,";
$sql.="sninput_font varchar(240) NOT NULL DEFAULT 'Verdana, Geneva, Arial, Helvetica, sans-serif' ,";
$sql.="sninput_fontsize varchar(20) NOT NULL DEFAULT '10pt' ,";
$sql.="sninput_fontstyle varchar(20) NOT NULL DEFAULT 'normal' ,";
$sql.="sninput_fontweight varchar(20) NOT NULL DEFAULT 'normal' ,";
$sql.="sninput_borderstyle varchar(20) NOT NULL DEFAULT 'solid' ,";
$sql.="sninput_borderwidth varchar(20) NOT NULL DEFAULT 'thin' ,";
$sql.="sninput_bordercolor varchar(7) NOT NULL DEFAULT '#696969' ,";
$sql.="snisb_facecolor varchar(7) NOT NULL DEFAULT '#708090' ,";
$sql.="snisb_highlightcolor varchar(7) NOT NULL DEFAULT '#A9A9A9' ,";
$sql.="snisb_shadowcolor varchar(7) NOT NULL DEFAULT '#191970' ,";
$sql.="snisb_darkshadowcolor varchar(7) NOT NULL DEFAULT '#000080' ,";
$sql.="snisb_3dlightcolor varchar(7) NOT NULL DEFAULT '#F5FFFA' ,";
$sql.="snisb_arrowcolor varchar(7) NOT NULL DEFAULT '#c0c0c0' ,";
$sql.="snisb_trackcolor varchar(7) NOT NULL DEFAULT '#b0b0b0' ,";
$sql.="snbutton_bgcolor varchar(7) NOT NULL DEFAULT '#94AAD6' ,";
$sql.="snbutton_fontcolor varchar(7) NOT NULL DEFAULT '#FFFAF0' ,";
$sql.="snbutton_font varchar(240) NOT NULL DEFAULT 'Verdana, Geneva, Arial, Helvetica, sans-serif' ,";
$sql.="snbutton_fontsize varchar(20) NOT NULL DEFAULT '7pt' ,";
$sql.="snbutton_fontstyle varchar(20) NOT NULL DEFAULT 'normal' ,";
$sql.="snbutton_fontweight varchar(20) NOT NULL DEFAULT 'normal' ,";
$sql.="snbutton_borderstyle varchar(20) NOT NULL DEFAULT 'ridge' ,";
$sql.="snbutton_borderwidth varchar(20) NOT NULL DEFAULT 'thin' ,";
$sql.="snbutton_bordercolor varchar(7) NOT NULL DEFAULT '#483D8B' ,";
$sql.="eventlinkcolor varchar(7) NOT NULL DEFAULT '#696969' ,";
$sql.="eventalinkcolor varchar(7) NOT NULL DEFAULT '#696969' ,";
$sql.="eventvlinkcolor varchar(7) NOT NULL DEFAULT '#696969' ,";
$sql.="eventlinkfontsize varchar(20) NOT NULL DEFAULT '9pt' ,";
$sql.="actionlinkcolor varchar(7) NOT NULL DEFAULT '#F0FFFF' ,";
$sql.="actionvlinkcolor varchar(7) NOT NULL DEFAULT '#F0FFFF' ,";
$sql.="actionalinkcolor varchar(7) NOT NULL DEFAULT '#F0FFFF' ,";
$sql.="pagebgpic varchar(240) NOT NULL DEFAULT 'pagebg.gif' ,";
$sql.="eventcalshortnews tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql.="eventcalshortlength int(10) unsigned NOT NULL DEFAULT '20' ,";
$sql.="eventcalshortnum int(10) unsigned NOT NULL DEFAULT '3' ,";
$sql.="eventcalshortonlyheadings tinyint(1) unsigned NOT NULL DEFAULT '1' ,";
$sql.="hotnewstarget varchar(80) NOT NULL DEFAULT '' ,";
$sql.="hotnewsdisplayposter tinyint(1) unsigned NOT NULL DEFAULT '1' ,";
$sql.="hotnewsnohtmlformatting tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql.="hotnewsicons tinyint(1) unsigned NOT NULL DEFAULT '1' ,";
$sql.="ns4style varchar(80) NOT NULL DEFAULT 'simpnews_ns4.css' ,";
$sql.="ns6style varchar(80) NOT NULL DEFAULT 'simpnews_ns6.css' ,";
$sql.="operastyle varchar(80) NOT NULL DEFAULT 'simpnews_opera.css' ,";
$sql.="geckostyle varchar(80) NOT NULL DEFAULT 'simpnews_gecko.css' ,";
$sql.="konquerorstyle varchar(80) NOT NULL DEFAULT 'simpnews_konqueror.css' ,";
$sql.="jsnf_maxdays tinyint(4) NOT NULL DEFAULT '-1' ,";
$sql.="jsnf_maxentries tinyint(4) unsigned NOT NULL DEFAULT '0' ,";
$sql.="jsnf_displaydate tinyint(1) unsigned NOT NULL DEFAULT '1' ,";
$sql.="jsnf_maxchars int(10) NOT NULL DEFAULT '-1' ,";
$sql.="jsnf_nolinking tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql.="jsnf_font varchar(240) NOT NULL DEFAULT 'Verdana, Geneva, Arial, Helvetica, sans-serif' ,";
$sql.="jsnf_fontsize varchar(10) NOT NULL DEFAULT '2' ,";
$sql.="jsnf_fontcolor varchar(7) NOT NULL DEFAULT '#000000' ,";
$sql.="jsnf_delay int(10) unsigned NOT NULL DEFAULT '3000' ,";
$sql.="jsnf_width int(10) unsigned NOT NULL DEFAULT '150' ,";
$sql.="jsnf_height int(10) unsigned NOT NULL DEFAULT '150' ,";
$sql.="jsnf_linktarget varchar(80) NOT NULL DEFAULT '_self' ,";
$sql.="jsnf_dateformat varchar(20) NOT NULL DEFAULT 'Y-m-d' ,";
$sql.="news4maxchars int(10) unsigned NOT NULL DEFAULT '30' ,";
$sql.="news4useddlink tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql.="news4linktarget varchar(240) NOT NULL DEFAULT '_self' ,";
$sql.="news4dateformat varchar(20) NOT NULL DEFAULT '%B %d, %Y' ,";
$sql.="news3dateformat varchar(20) NOT NULL DEFAULT '%B %d, %Y' ,";
$sql.="news3useddlink tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql.="news3linktarget varchar(240) NOT NULL DEFAULT '_self' ,";
$sql.="news3maxchars int(10) unsigned NOT NULL DEFAULT '30' ,";
$sql.="ss_font varchar(80) NOT NULL DEFAULT 'Verdana' ,";
$sql.="ss_fontsize int(10) unsigned NOT NULL DEFAULT '18' ,";
$sql.="ss_fontcolor varchar(7) NOT NULL DEFAULT '#ffffff' ,";
$sql.="ss_fontstyle tinyint(4) unsigned NOT NULL DEFAULT '0' ,";
$sql.="ss_stars tinyint(4) unsigned NOT NULL DEFAULT '1' ,";
$sql.="ss_speed tinyint(4) unsigned NOT NULL DEFAULT '1' ,";
$sql.="ss_dir tinyint(4) unsigned NOT NULL DEFAULT '0' ,";
$sql.="ss_shadow tinyint(1) unsigned NOT NULL DEFAULT '1' ,";
$sql.="ss_bgcolor varchar(7) NOT NULL DEFAULT '#000000' ,";
$sql.="ss_targetframe varchar(80) NOT NULL DEFAULT '_self' ,";
$sql.="ss_height int(10) unsigned NOT NULL DEFAULT '200' ,";
$sql.="ss_width int(10) unsigned NOT NULL DEFAULT '400' ,";
$sql.="ss_maxentries int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.="ss_maxdays int(10) NOT NULL DEFAULT '-1' ,";
$sql.="ss_nolinking tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql.="jsns_font varchar(240) NOT NULL DEFAULT 'Verdana, Geneva, Arial, Helvetica, sans-serif' ,";
$sql.="jsns_fontsize int(10) unsigned NOT NULL DEFAULT '12' ,";
$sql.="jsns_fontcolor varchar(7) NOT NULL DEFAULT '#000000' ,";
$sql.="jsns_nolinking tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql.="jsns_displaydate tinyint(1) unsigned NOT NULL DEFAULT '1' ,";
$sql.="jsns_linktarget varchar(80) NOT NULL DEFAULT '_self' ,";
$sql.="jsns_bgcolor varchar(7) NOT NULL DEFAULT '#eeeeee' ,";
$sql.="jsns_direction tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql.="jsns_maxchars int(10) NOT NULL DEFAULT '-1' ,";
$sql.="jsns_maxdays int(10) NOT NULL DEFAULT '-1' ,";
$sql.="jsns_maxentries int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.="jsns_dateformat varchar(20) NOT NULL DEFAULT 'Y-m-d' ,";
$sql.="jsns_height int(10) unsigned NOT NULL DEFAULT '150' ,";
$sql.="jsns_width int(10) unsigned NOT NULL DEFAULT '150' ,";
$sql.="jsns_speed int(10) unsigned NOT NULL DEFAULT '50' ,";
$sql.="jsns_step tinyint(4) unsigned NOT NULL DEFAULT '2' ,";
$sql.="tablealign tinyint(4) unsigned NOT NULL DEFAULT '2' ,";
$sql.="clheadingbgcolor varchar(7) NOT NULL DEFAULT '#eeeeee' ,";
$sql.="clheadingfontcolor varchar(7) NOT NULL DEFAULT '#333333' ,";
$sql.="clheadingfont varchar(240) NOT NULL DEFAULT 'Verdana, Geneva, Arial, Helvetica, sans-serif' ,";
$sql.="clheadingfontsize varchar(20) NOT NULL DEFAULT '2' ,";
$sql.="clwidth int(10) unsigned NOT NULL DEFAULT '260' ,";
$sql.="clcontentbgcolor varchar(7) NOT NULL DEFAULT '#ffffff' ,";
$sql.="clcontentfontcolor varchar(7) NOT NULL DEFAULT '#000000' ,";
$sql.="clcontentfont varchar(240) NOT NULL DEFAULT 'Verdana, Geneva, Arial, Helvetica, sans-serif' ,";
$sql.="clcontentfontsize varchar(20) NOT NULL DEFAULT '1' ,";
$sql.="enablecatlist tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql.="catframenewslist tinyint(4) unsigned NOT NULL DEFAULT '0' ,";
$sql.="clcontenthighlight varchar(7) NOT NULL DEFAULT '#ffffff' ,";
$sql.="clactdontlink tinyint(1) unsigned NOT NULL DEFAULT '1' ,";
$sql.="clleftwidth varchar(10) NOT NULL DEFAULT '30%' ,";
$sql.="clrightwidth varchar(10) NOT NULL DEFAULT '70%' ,";
$sql.="clnowrap tinyint(1) unsigned NOT NULL DEFAULT '1' ,";
$sql.="contentcopy varchar(250) NOT NULL DEFAULT '' ,";
$sql.="addbodytags varchar(250) NOT NULL DEFAULT '' ,";
$sql.="proposepic varchar(240) NOT NULL DEFAULT 'propose.gif' ,";
$sql.="proposereq tinyint(4) unsigned NOT NULL DEFAULT '0' ,";
$sql.="caltabpic varchar(204) NOT NULL DEFAULT 'caltab.gif' ,";
$sql.="caljumpbox tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql.="cjminyear int(10) unsigned NOT NULL DEFAULT '2' ,";
$sql.="cjmaxyear int(10) unsigned NOT NULL DEFAULT '2' ,";
$sql.="hotevmaxdays int(10) NOT NULL DEFAULT '14' ,";
$sql.="hotevmaxentries int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.="hotevtarget varchar(80) NOT NULL DEFAULT '_self' ,";
$sql.="displayevnum tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql.="hotevmaxchars int(10) NOT NULL DEFAULT '0' ,";
$sql.="hotevnohtmlformatting tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql.="hotevdisplayposter tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql.="hotevicons tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql.="hotscriptsnoheading tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql.="evproposemaxyears int(10) unsigned NOT NULL DEFAULT '3' ,";
$sql.="printpic varchar(240) NOT NULL DEFAULT '' ,";
$sql.="printheader tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql.="TableWidth2 varchar(10) NOT NULL DEFAULT '60%' ,";
$sql.="news5enddate date NOT NULL DEFAULT '0000-00-00' ,";
$sql.="news5monthbgcolor varchar(7) NOT NULL DEFAULT '#dddddd' ,";
$sql.="news5monthfontcolor varchar(7) NOT NULL DEFAULT '#000000' ,";
$sql.="news5monthfont varchar(240) NOT NULL DEFAULT 'Verdana, Geneva, Arial, Helvetica, sans-serif' ,";
$sql.="news5monthfontsize varchar(20) NOT NULL DEFAULT '1' ,";
$sql.="news5monthfontstyle tinyint(4) unsigned NOT NULL DEFAULT '0' ,";
$sql.="news5startdate date NOT NULL DEFAULT '0000-00-00' ,";
$sql.="news5yearbgcolor varchar(7) NOT NULL DEFAULT '#dddddd' ,";
$sql.="news5yearfontcolor varchar(7) NOT NULL DEFAULT '#000000' ,";
$sql.="news5yearfont varchar(240) NOT NULL DEFAULT 'Verdana, Geneva, Arial, Helvetica, sans-serif' ,";
$sql.="news5yearfontsize varchar(20) NOT NULL DEFAULT '2' ,";
$sql.="news5yearfontstyle tinyint(4) unsigned NOT NULL DEFAULT '0' ,";
$sql.="news5monthdisplayyear tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql.="news5dateformat varchar(20) NOT NULL DEFAULT '%B %d, %Y' ,";
$sql.="news5maxchars int(10) unsigned NOT NULL DEFAULT '30' ,";
$sql.="news5linktarget varchar(240) NOT NULL DEFAULT '_self' ,";
$sql.="news5useddlink tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql.="news5displayposter tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql.="news5displayicons tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql.="csvexportpic varchar(240) NOT NULL DEFAULT 'csvexport.gif' ,";
$sql.="csvexportdateformat varchar(20) NOT NULL DEFAULT '%d.%m.%Y' ,";
$sql.="csvexportfields int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.="asclistpic varchar(240) NOT NULL DEFAULT 'asclist.gif' ,";
$sql.="bbchelp_bgcolor varchar(7) NOT NULL default '#483D8B',";
$sql.="bbchelp_fontcolor varchar(7) NOT NULL default '#ffff00',";
$sql.="bbchelp_fontsize varchar(20) NOT NULL default '8pt',";
$sql.="bbchelp_font varchar(240) NOT NULL default '\"Courier New\", Courier, monospace',";
$sql.="el_font varchar(240) NOT NULL default '\"Courier New\", Courier, monospace',";
$sql.="el_fontweight varchar(20) NOT NULL default 'normal',";
$sql.="el_fontsize varchar(20) NOT NULL default '8pt',";
$sql.="el_hovercolor varchar(7) NOT NULL default '#B0C4DE',";
$sql.="bbchelp_fontweight varchar(20) NOT NULL default 'normal',";
$sql.="bbchelp_fontstyle varchar(20) NOT NULL default 'normal',";
$sql.="sb_bgcolor varchar(7) NOT NULL default '#EEE8AA',";
$sql.="sb_bordercolor varchar(7) NOT NULL default '#CD853F',";
$sql.="bbc_bgcolor varchar(7) NOT NULL default '#B0C4DE',";
$sql.="bbc_bordercolor varchar(7) NOT NULL default '#708090',";
$sql.="bbcsel_font varchar(240) NOT NULL default 'Verdana, Geneva, Arial, Helvetica, sans-serif',";
$sql.="bbcsel_fontsize varchar(20) NOT NULL default '10pt',";
$sql.="bbcsel_fontcolor varchar(7) NOT NULL default '#ff00ff',";
$sql.="bbcsel_bgcolor varchar(7) NOT NULL default '#333333',";
$sql.="bbcsel_borderstyle varchar(20) NOT NULL default 'none',";
$sql.="bbcsel_borderwidth varchar(20) NOT NULL default '',";
$sql.="bbcsel_bordercolor varchar(7) NOT NULL default '#000000',";
$sql.="bbcsel_fontstyle varchar(20) NOT NULL default 'normal',";
$sql.="bbcsel_fontweight varchar(20) NOT NULL default 'normal',";
$sql.="or_bgcolor varchar(7) NOT NULL default '#4682B4',";
$sql.="or_fontcolor varchar(7) NOT NULL default '#ffffff',";
$sql.="or_font varchar(240) NOT NULL default 'Verdana, Geneva, Arial, Helvetica, sans-serif',";
$sql.="or_fontsize varchar(20) NOT NULL default '10pt',";
$sql.="sep_char char(1) NOT NULL default '§',";
$sql.="el_fontstyle varchar(20) NOT NULL default 'normal',";
$sql.="hn_newsheadingfontsize varchar(20) NOT NULL default '3',";
$sql.="nbindent varchar(20) NOT NULL default '5pt',";
$sql.="evbindent varchar(20) NOT NULL default '5pt',";
$sql.="morepic varchar(240) NOT NULL default 'more.gif',";
$sql.="announcepic varchar(240) NOT NULL default 'announce.gif',";
$sql.="gannouncepic varchar(240) NOT NULL default 'gannounce.gif',";
$sql.="announceoptions int(10) unsigned NOT NULL default '0',";
$sql.="maxevcannounce tinyint(4) unsigned NOT NULL default '4',";
$sql.="noprinticon tinyint(1) unsigned NOT NULL default '0',";
$sql.="nogotopicon tinyint(1) unsigned NOT NULL default '0',";
$sql.="news5headingdateformat varchar(20) NOT NULL default '%B %d, %Y',";
$sql.="news5noglobalprint tinyint(1) unsigned NOT NULL default '0',";
$sql.="news4fontsize varchar(20) NOT NULL default '1',";
$sql.="news3fontsize varchar(20) NOT NULL default '1',";
$sql.="n5_newsheadingfontsize varchar(20) NOT NULL default '1',";
$sql.="n5_timestampfontsize varchar(20) NOT NULL default '1',";
$sql.="n5_timestampstyle tinyint(1) unsigned NOT NULL default '0',";
$sql.="ev2onlyheadings tinyint(1) unsigned NOT NULL default '0',";
$sql.="ev2_newsheadingfontsize varchar(20) NOT NULL default '1',";
$sql.="ev2_newsheadingstyle tinyint(1) unsigned NOT NULL default '0',";
$sql.="ev2_timestampfontsize varchar(20) NOT NULL default '1',";
$sql.="ev2_timestampstyle tinyint(1) unsigned NOT NULL default '0',";
$sql.="ev2_contentfontsize varchar(20) NOT NULL default '1',";
$sql.="ev2_posterfontsize varchar(20) NOT NULL default '1',";
$sql.="ev2_posterstyle tinyint(1) unsigned NOT NULL default '0',";
$sql.="newsscrollerheadingsep tinyint(1) unsigned NOT NULL default '0',";
$sql.="newsscrollerheadingsepchar char(1) NOT NULL default '-',";
$sql.="newsscrollernumsepchars int(10) unsigned NOT NULL default '40',";
$sql.="jsns_sepheading tinyint(1) unsigned NOT NULL default '0',";
$sql.="highlightmarker varchar(240) NOT NULL default 'highlight.gif',";
$sql.="catinfobgcolor varchar(7) NOT NULL default '#dddddd',";
$sql.="catinfofont varchar(240) NOT NULL default 'Verdana, Geneva, Arial, Helvetica, sans-serif',";
$sql.="catinfofontsize varchar(20) NOT NULL default '2',";
$sql.="catinfofontcolor varchar(7) NOT NULL default '#000000',";
$sql.="catinfoindent varchar(20) NOT NULL default '10px',";
$sql.="searchonlyheadings tinyint(1) unsigned NOT NULL default '0',";
$sql.="searchdetailtarget varchar(80) NOT NULL default '_self',";
$sql.="searchshortchars int(10) unsigned NOT NULL default '20',";
$sql.="sshort_timestampfontsize varchar(20) NOT NULL default '1',";
$sql.="sshort_headingfontsize varchar(20) NOT NULL default '1',";
$sql.="nofileinfo tinyint(1) unsigned NOT NULL default '0',";
$sql.="sn_hideallnewslink tinyint(1) unsigned NOT NULL default '0',";
$sql.="pagenavdetails tinyint(1) unsigned NOT NULL default '1',";
$sql.="newsletterbgcolor varchar(7) NOT NULL default '#ffffff',";
$sql.="newslettercustomheader text NOT NULL,";
$sql.="newslettercustomfooter text NOT NULL,";
$sql.="subredirecturl varchar(255) NOT NULL default '',";
$sql.="newsletteralign int(4) unsigned NOT NULL default '2',";
$sql.="linkposter tinyint(1) unsigned NOT NULL default '0',";
$sql.="weekstart tinyint(1) unsigned NOT NULL default '0',";
$sql.="evshowcalweek tinyint(1) unsigned NOT NULL default '0',";
$sql.="nlsend_heading varchar(80) NOT NULL default '',";
$sql.="nlsend_dateformat varchar(20) NOT NULL default '%d.%m.%Y %H:%M',";
$sql.="nlsend_bgcolor varchar(7) NOT NULL default '#ffeedd',";
$sql.="nlsend_font varchar(240) NOT NULL default 'Verdana, Geneva, Arial, Helvetica, sans-serif',";
$sql.="nlsend_fontsize varchar(10) NOT NULL default '2',";
$sql.="nlsend_fontcolor varchar(7) NOT NULL default '#000000',";
$sql.="nfheight int(10) unsigned NOT NULL default '100',";
$sql.="emailpic varchar(240) NOT NULL default 'email.gif',";
$sql.="sncatlink varchar(40) NOT NULL default 'news.php',";
$sql.="textareanoscroll tinyint(1) unsigned NOT NULL default '0',";
$sql.="emailcustomheader text,";
$sql.="emailcustomfooter text,";
$sql.="emailbgcolor varchar(7) NOT NULL default '#ffffff',";
$sql.="emailpageremark text,";
$sql.="hn6_numentries int(10) unsigned NOT NULL default '10',";
$sql.="anhn6numentries int(10) unsigned NOT NULL default '1',";
$sql.="hotnews6target varchar(240) NOT NULL default '_self',";
$sql.="hotnews7useddlink tinyint(1) unsigned NOT NULL default '0',";
$sql.="ratingdisplay int(4) unsigned NOT NULL default '1',";
$sql.="ratingprelude varchar(80) NOT NULL default '',";
$sql.="sns_tablewidth varchar(10) NOT NULL default '80%',";
$sql.="sns_options int(10) unsigned NOT NULL default '0',";
$sql.="hnlinkdest varchar(80) NOT NULL default '',";
$sql.="usehnlinkdest tinyint(1) unsigned NOT NULL default '0',";
$sql.="aninc_options int(10) unsigned NOT NULL default '0',";
$sql.="aninc_tablewidth varchar(10) NOT NULL default '80%',";
$sql.="hnlinkdestan varchar(80) NOT NULL default '',";
$sql.="evinc_tablewidth varchar(10) NOT NULL default '80%',";
$sql.="evinc_options int(10) unsigned NOT NULL default '0',";
$sql.="hnlinkdestev varchar(80) NOT NULL default '',";
$sql.="useappletlinkdest tinyint(1) unsigned NOT NULL default '0',";
$sql.="appletlinkdest varchar(80) NOT NULL default '',";
$sql.="appletlinkdestan varchar(80) NOT NULL default '',";
$sql.="appletlinkdestev varchar(80) NOT NULL default '',";
$sql.="usejslinkdest tinyint(1) unsigned NOT NULL default '0',";
$sql.="jslinkdest varchar(80) NOT NULL default '',";
$sql.="jslinkdestev varchar(80) NOT NULL default '',";
$sql.="jslinkdestan varchar(80) NOT NULL default '',";
$sql.="evscrollevcal2 tinyint(1) unsigned NOT NULL default '0',";
$sql.="evscrollcal2dest varchar(80) NOT NULL default '',";
$sql.="applet_ganmark varchar(20) NOT NULL default '',";
$sql.="applet_anmark varchar(20) NOT NULL default '',";
$sql.="attachpos tinyint(1) unsigned NOT NULL default '0',";
$sql.="searchmaxchars int(10) unsigned NOT NULL default '0',";
$sql.="searchhighlightcolor varchar(7) NOT NULL default '#ff0000',";
$sql.="searchhighlight tinyint(1) unsigned NOT NULL default '0',";
$sql.="activcellcolor varchar(7) NOT NULL default '#ffff00',";
$sql.="news4showcat tinyint(1) unsigned NOT NULL default '0',";
$sql.="showproposer tinyint(1) unsigned NOT NULL default '0',";
$sql.="event_dateformat2 varchar(20) NOT NULL default 'd.m.Y H:i',";
$sql.="commentsinline tinyint(1) unsigned NOT NULL default '0',";
$sql.="icdisplayemail tinyint(1) unsigned NOT NULL default '0',";
$sql.="ic_heading_bgcolor varchar(7) NOT NULL default '#999999',";
$sql.="ic_heading_fontcolor varchar(7) NOT NULL default '#000000',";
$sql.="ic_heading_font varchar(240) NOT NULL default 'Verdana, Geneva, Arial, Helvetica, sans-serif',";
$sql.="ic_heading_fontsize varchar(4) NOT NULL default '1',";
$sql.="ic_body_bgcolor varchar(7) NOT NULL default '#bbbbbb',";
$sql.="ic_body_font varchar(240) NOT NULL default 'Verdana, Geneva, Arial, Helvetica, sans-serif',";
$sql.="ic_body_fontsize int(4) unsigned NOT NULL default '2',";
$sql.="ic_body_fontcolor varchar(7) NOT NULL default '#000000',";
$sql.="ic_heading_style tinyint(1) unsigned NOT NULL default '0',";
$sql.="ic_body_style tinyint(1) unsigned NOT NULL default '0',";
$sql.="commentspic varchar(240) NOT NULL default 'comment_small.gif',";
$sql.="hotnewscommentslink tinyint(1) unsigned NOT NULL default '1',";
$sql.="writecommentpic varchar(240) NOT NULL default 'writecomment.gif',";
$sql.="ev3_dateformat varchar(40) NOT NULL default '%B %d, %Y',";
$sql.="masssuboptions int(10) unsigned NOT NULL default '0',";
$sql.="tablebgcolor varchar(7) NOT NULL default '#c0c0c0',";
$sql.="hn_catlinking int(10) unsigned NOT NULL default '0',";
$sql.="hn_linklayout varchar(20) NOT NULL default '',";
$sql.="rss_channel_title varchar(100) NOT NULL default '',";
$sql.="rss_channel_description varchar(255) NOT NULL default '',";
$sql.="rss_channel_link varchar(255) NOT NULL default '',";
$sql.="rss_auto_title int(10) unsigned NOT NULL default '80',";
$sql.="rss_auto_short int(10) unsigned NOT NULL default '200',";
$sql.="rss_channel_copyright varchar(100) NOT NULL default '',";
$sql.="rss_maxentries int(10) unsigned NOT NULL default '0',";
$sql.="rss_channel_editor varchar(100) NOT NULL default '',";
$sql.="rss_channel_webmaster varchar(100) NOT NULL default '',";
$sql.="wap_title varchar(100) NOT NULL default '',";
$sql.="wap_description varchar(255) NOT NULL default '',";
$sql.="wap_copyright varchar(100) NOT NULL default '',";
$sql.="wap_auto_short int(10) unsigned NOT NULL default '200',";
$sql.="wap_auto_title int(10) unsigned NOT NULL default '80',";
$sql.="wap_maxentries int(10) unsigned NOT NULL default '0',";
$sql.="wap_options int(10) unsigned NOT NULL default '0',";
$sql.="wap_ev_maxdays int(10) unsigned NOT NULL default '8',";
$sql.="wap_ev_title varchar(100) NOT NULL default '',";
$sql.="wap_ev_description varchar(255) NOT NULL default '',";
$sql.="wap_ev_maxentries int(10) unsigned NOT NULL default '0',";
$sql.="wap_an_maxdays int(10) unsigned NOT NULL default '8',";
$sql.="wap_an_maxentries int(10) unsigned NOT NULL default '0',";
$sql.="wap_an_title varchar(100) NOT NULL default '',";
$sql.="wap_an_description varchar(255) NOT NULL default '',";
$sql.="wap_evs_dayrange int(10) unsigned NOT NULL default '1',";
$sql.="wap_evs_maxldays int(10) unsigned NOT NULL default '5',";
$sql.="wap_catlist_epp int(10) unsigned NOT NULL default '10',";
$sql.="wap_evlist2_epp int(10) unsigned NOT NULL default '10',";
$sql.="printpic_small varchar(240) NOT NULL default 'print_small.gif',";
$sql.="expandpic varchar(240) NOT NULL default 'expand.gif',";
$sql.="collapsepic varchar(240) NOT NULL default 'collapse.gif',";
$sql.="wap_cl_title varchar(80) NOT NULL default '',";
$sql.="wap_cl_description varchar(255) NOT NULL default '',";
$sql.="wap_cl_logo varchar(240) NOT NULL default '',";
$sql.="newsnodate int(10) unsigned NOT NULL default '0',";
$sql.="rsspic varchar(240) NOT NULL default 'xml.gif',";
$sql.="eventcalonlymarkers tinyint(1) unsigned NOT NULL default '0',";
$sql.="evmarkcolgeneral varchar(7) NOT NULL default '#333333',";
$sql.="n4nodate tinyint(1) unsigned NOT NULL default '0',";
$sql.="n4tbmargin varchar(10) NOT NULL default '',";
$sql.="n4leftmargin varchar(10) NOT NULL default '',";
$sql.="srchnolimit tinyint(1) unsigned NOT NULL default '0',";
$sql.="newsnoicons tinyint(1) unsigned NOT NULL default '0',";
$sql.="snnodate tinyint(1) unsigned NOT NULL default '0',";
$sql.="hotnewsmaxchars int(10) NOT NULL default '-1',";
$sql.="entriesperpage int(2) unsigned NOT NULL default '20',";
$sql.="numhotnews tinyint(2) unsigned NOT NULL default '5',";
$sql.="newsnotifydays tinyint(2) unsigned NOT NULL default '0',";
$sql.="news2entries int(2) unsigned NOT NULL default '5',";
$sql.="news3entries int(2) unsigned NOT NULL default '5',";
$sql.="srchaddoptions int(10) unsigned NOT NULL default '0',";
$sql.="cheadnobr tinyint(1) unsigned default '0',";
$sql.="cfootnobr tinyint(1) unsigned default '0',";
$sql.="news4addoptions int(6) unsigned NOT NULL default '0',";
$sql.="subemailtype tinyint(1) unsigned NOT NULL default '0',";
$sql.="showfuturenews tinyint(1) unsigned NOT NULL default '0',";
$sql.="nonltrans tinyint(4) unsigned default '0',";
$sql.="hnnolinking tinyint(1) unsigned NOT NULL default '0',";
$sql.="PRIMARY KEY (layoutnr));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_layout");
// insert adminuser
$admin_pw=md5($admin_pw1);
$admin_user=addslashes(strtolower($admin_user));
$sql = "INSERT INTO ".$tableprefix."_users (";
$sql .="username, password, rights, realname";
if(isset($admin_email))
	$sql .=", email";
$sql .=")";
$sql .="VALUES ('$admin_user', '$admin_pw', 4, '$realname'";
if(isset($admin_email))
	$sql .=", '$admin_email'";
$sql .=");";
if(!$result = mysql_query($sql, $db))
	die("Unable to create adminuser");
echo "Adding default layout...<br>";
flush();
$sql = "INSERT INTO ".$tableprefix."_layout (lang, heading, headingbgcolor, headingfontcolor, headingfont, headingfontsize, bordercolor, contentbgcolor, contentfontcolor, contentfont, contentfontsize, TableWidth, timestampfontcolor, timestampfontsize, timestampfont, dateformat, showcurrtime, customheader, pagebgcolor, stylesheet, newsheadingbgcolor, newsheadingfontcolor, newsheadingstyle, posterbgcolor, posterfontcolor, posterstyle, displayposter, posterfont, posterfontsize, newsheadingfont, newsheadingfontsize, timestampbgcolor, timestampstyle, displaysubscriptionbox, defsignature, subscriptionbgcolor, subscriptionfontcolor, subscriptionfont, subscriptionfontsize, copyrightbgcolor, copyrightfontcolor, copyrightfont, copyrightfontsize, emailremark, id, deflayout, customfooter, searchpic, backpic, pagepic_back, pagepic_first, pagepic_next, pagepic_last, pagetoppic, newssignal_on, newssignal_off, helppic, attachpic, prevpic, fwdpic, eventheading, event_dateformat, newstickerbgcolor, newstickerfontcolor, newstickerfont, newstickerfontsize, newstickerhighlightcolor, newstickerheight, newstickerwidth, newstickerscrollspeed, newstickerscrolldelay, newstickermaxdays, newstickermaxentries, newsscrollerbgcolor, newsscrollerfontcolor, newsscrollerfont, newsscrollerfontsize, newsscrollerheight, newsscrollerwidth, newsscrollerscrollspeed, newsscrollerscrolldelay, newsscrollerscrollpause, newsscrollermaxdays, newsscrollermaxentries, newsscrollertype, newsscrollerbgimage, newsscrollerfgimage, newsscrollermousestop, newsscrollermaxchars, newstickertarget, newsscrollertarget, newsscrollerxoffset, newsscrolleryoffset, newsscrollerwordwrap, newsscrollerdisplaydate, newsscrollerdateformat, newentrypic, newstypermaxentries, newstyperbgcolor, newstyperfontcolor, newstyperfont, newstyperfontsize, newstyperfontstyle, newstyperdisplaydate, newstyperdateformat, newstyperxoffset, newstyperyoffset, newstypermaxdays, newstypermaxchars, newstyperwidth, newstyperheight, newstyperbgimage, newstyperscroll, newsscrollernolinking, newstyper2maxentries, newstyper2bgcolor, newstyper2fontcolor, newstyper2fontsize, newstyper2displaydate, newstyper2newscreen, newstyper2waitentry, newstyper2dateformat, newstyper2indent, newstyper2linespace, newstyper2maxdays, newstyper2maxchars, newstyper2width, newstyper2height, newstyper2bgimage, newstyper2sound, newstyper2charpause, newstyper2linepause, newstyper2screenpause, eventscrolleractdate, separatebylang, headerfile, footerfile, headerfilepos, footerfilepos, usecustomheader, usecustomfooter, copyrightpos, categorybgcolor, categoryfont, categoryfontsize, categoryfontcolor, categorystyle, hotnews2target, news2target, newsscrollermaxlines, linkcolor, vlinkcolor, alinkcolor, morelinkcolor, morevlinkcolor, morealinkcolor, catlinkcolor, catvlinkcolor, catalinkcolor, commentlinkcolor, commentvlinkcolor, commentalinkcolor, attachlinkcolor, attachvlinkcolor, attachalinkcolor, pagenavlinkcolor, pagenavvlinkcolor, pagenavalinkcolor, colorscrollbars, sbfacecolor, sbhighlightcolor, sbshadowcolor, sbdarkshadowcolor, sb3dlightcolor, sbarrowcolor, sbtrackcolor, snsel_bgcolor, snsel_fontcolor, snsel_font, snsel_fontsize, snsel_fontstyle, snsel_fontweight, snsel_borderstyle, snsel_bordercolor, snsel_borderwidth, morelinkfontsize, sninput_bgcolor, sninput_fontcolor, sninput_font, sninput_fontsize, sninput_fontstyle, sninput_fontweight, sninput_borderstyle, sninput_borderwidth, sninput_bordercolor, snisb_facecolor, snisb_highlightcolor, snisb_shadowcolor, snisb_darkshadowcolor, snisb_3dlightcolor, snisb_arrowcolor, snisb_trackcolor, snbutton_bgcolor, snbutton_fontcolor, snbutton_font, snbutton_fontsize, snbutton_fontstyle, snbutton_fontweight, snbutton_borderstyle, snbutton_borderwidth, snbutton_bordercolor, eventlinkcolor, eventalinkcolor, eventvlinkcolor, eventlinkfontsize, actionlinkcolor, actionvlinkcolor, actionalinkcolor, pagebgpic, eventcalshortnews, eventcalshortlength, eventcalshortnum, eventcalshortonlyheadings, hotnewstarget, hotnewsdisplayposter, hotnewsnohtmlformatting, hotnewsicons, ns4style, ns6style, operastyle, geckostyle, konquerorstyle, jsnf_maxdays, jsnf_maxentries, jsnf_displaydate, jsnf_maxchars, jsnf_nolinking, jsnf_font, jsnf_fontsize, jsnf_fontcolor, jsnf_delay, jsnf_width, jsnf_height, jsnf_linktarget, jsnf_dateformat, news4maxchars, news4useddlink, news4linktarget, news4dateformat, news3dateformat, news3useddlink, news3linktarget, news3maxchars, ss_font, ss_fontsize, ss_fontcolor, ss_fontstyle, ss_stars, ss_speed, ss_dir, ss_shadow, ss_bgcolor, ss_targetframe, ss_height, ss_width, ss_maxentries, ss_maxdays, ss_nolinking, jsns_font, jsns_fontsize, jsns_fontcolor, jsns_nolinking, jsns_displaydate, jsns_linktarget, jsns_bgcolor, jsns_direction, jsns_maxchars, jsns_maxdays, jsns_maxentries, jsns_dateformat, jsns_height, jsns_width, jsns_speed, jsns_step, tablealign, clheadingbgcolor, clheadingfontcolor, clheadingfont, clheadingfontsize, clwidth, clcontentbgcolor, clcontentfontcolor, clcontentfont, clcontentfontsize, enablecatlist, catframenewslist, clcontenthighlight, clactdontlink, clleftwidth, clrightwidth, clnowrap, contentcopy, addbodytags, proposepic, proposereq, caltabpic, caljumpbox, cjminyear, cjmaxyear, hotevmaxdays, hotevmaxentries, hotevtarget, displayevnum, hotevmaxchars, hotevnohtmlformatting, hotevdisplayposter, hotevicons, hotscriptsnoheading, evproposemaxyears, printpic, printheader, TableWidth2, news5enddate, news5monthbgcolor, news5monthfontcolor, news5monthfont, news5monthfontsize, news5monthfontstyle, news5startdate, news5yearbgcolor, news5yearfontcolor, news5yearfont, news5yearfontsize, news5yearfontstyle, news5monthdisplayyear, news5dateformat, news5maxchars, news5linktarget, news5useddlink, news5displayposter, news5displayicons, csvexportpic, csvexportdateformat, csvexportfields, asclistpic, bbchelp_bgcolor, bbchelp_fontcolor, bbchelp_fontsize, bbchelp_font, el_font, el_fontweight, el_fontsize, el_hovercolor, bbchelp_fontweight, bbchelp_fontstyle, sb_bgcolor, sb_bordercolor, bbc_bgcolor, bbc_bordercolor, bbcsel_font, bbcsel_fontsize, bbcsel_fontcolor, bbcsel_bgcolor, bbcsel_borderstyle, bbcsel_borderwidth, bbcsel_bordercolor, bbcsel_fontstyle, bbcsel_fontweight, or_bgcolor, or_fontcolor, or_font, or_fontsize, sep_char, el_fontstyle, hn_newsheadingfontsize, nbindent, evbindent, morepic, announcepic, gannouncepic, announceoptions, maxevcannounce, noprinticon, nogotopicon, news5headingdateformat, news5noglobalprint, news4fontsize, news3fontsize, n5_newsheadingfontsize, n5_timestampfontsize, n5_timestampstyle, ev2onlyheadings, ev2_newsheadingfontsize, ev2_newsheadingstyle, ev2_timestampfontsize, ev2_timestampstyle, ev2_contentfontsize, ev2_posterfontsize, ev2_posterstyle, newsscrollerheadingsep, newsscrollerheadingsepchar, newsscrollernumsepchars, jsns_sepheading, highlightmarker, catinfobgcolor, catinfofont, catinfofontsize, catinfofontcolor, catinfoindent, searchonlyheadings, searchdetailtarget, searchshortchars, sshort_timestampfontsize, sshort_headingfontsize, nofileinfo, sn_hideallnewslink, pagenavdetails, newsletterbgcolor, newslettercustomheader, newslettercustomfooter, subredirecturl, newsletteralign, linkposter, weekstart, evshowcalweek, nlsend_heading, nlsend_dateformat, nlsend_bgcolor, nlsend_font, nlsend_fontsize, nlsend_fontcolor, nfheight, emailpic, sncatlink, textareanoscroll, emailcustomheader, emailcustomfooter, emailbgcolor, emailpageremark, hn6_numentries, anhn6numentries, hotnews6target, hotnews7useddlink, ratingdisplay, ratingprelude, sns_tablewidth, sns_options, hnlinkdest, usehnlinkdest, aninc_options, aninc_tablewidth, hnlinkdestan, evinc_tablewidth, evinc_options, hnlinkdestev, useappletlinkdest, appletlinkdest, appletlinkdestan, appletlinkdestev, usejslinkdest, jslinkdest, jslinkdestev, jslinkdestan, evscrollevcal2, evscrollcal2dest, applet_ganmark, applet_anmark, attachpos, searchmaxchars, searchhighlightcolor, searchhighlight, activcellcolor, news4showcat, showproposer, event_dateformat2, commentsinline, icdisplayemail, ic_heading_bgcolor, ic_heading_fontcolor, ic_heading_font, ic_heading_fontsize, ic_body_bgcolor, ic_body_font, ic_body_fontsize, ic_body_fontcolor, ic_heading_style, ic_body_style, commentspic, hotnewscommentslink, writecommentpic, ev3_dateformat, masssuboptions, tablebgcolor, hn_catlinking, hn_linklayout, rss_channel_title, rss_channel_description, rss_channel_link, rss_auto_title, rss_auto_short, rss_channel_copyright, rss_maxentries, rss_channel_editor, rss_channel_webmaster, wap_title, wap_description, wap_copyright, wap_auto_short, wap_auto_title, wap_maxentries, wap_options, wap_ev_maxdays, wap_ev_title, wap_ev_description, wap_ev_maxentries, wap_an_maxdays, wap_an_maxentries, wap_an_title, wap_an_description, wap_evs_dayrange, wap_evs_maxldays, wap_catlist_epp, wap_evlist2_epp, printpic_small, expandpic, collapsepic, wap_cl_title, wap_cl_description, wap_cl_logo, newsnodate, rsspic, eventcalonlymarkers, evmarkcolgeneral, n4nodate, n4tbmargin, n4leftmargin, srchnolimit, newsnoicons, snnodate, hotnewsmaxchars, entriesperpage, numhotnews, newsnotifydays, news2entries, news3entries, srchaddoptions, cheadnobr, cfootnobr, news4addoptions, subemailtype, showfuturenews, nonltrans) ";
$sql.= "VALUES ('en', 'News', '#94AAD6', '#FFF0C0', 'Verdana, Geneva, Arial, Helvetica, sans-serif', '+2', '#000000', '#c0c0c0', '#000000', 'Verdana, Geneva, Arial, Helvetica, sans-serif', '2', '98%', '#000000', '1', 'Verdana, Geneva, Arial, Helvetica, sans-serif', 'd.m.Y H:i:s', 1, '', '#c0c0c0', 'simpnews.css', '#c0c0c0', '#222222', 0, '#c0c0c0', '#000000', 0, 1, 'Verdana, Geneva, Arial, Helvetica, sans-serif', '1', 'Verdana, Geneva, Arial, Helvetica, sans-serif', '3', '#c0c0c0', 0, 1, '', '#94AAD6', '#FFF0C0', 'Verdana, Geneva, Arial, Helvetica, sans-serif', '2', '#c0c0c0', '#000000', 'Verdana, Geneva, Arial, Helvetica, sans-serif', '1', '', 'def', 1, '', 'search.gif', 'back.gif', 'prev.gif', 'first.gif', 'next.gif', 'last.gif', 'pagetop.gif', 'blink.gif', 'off.gif', 'help.gif', 'attach.gif', 'prev_big.gif', 'next_big.gif', 'Events', 'd.m.Y', '#cccccc', '#000000', 'Verdana', 12, '#0000ff', 20, 300, 1, 30, -1, 0, '#cccccc', '#000000', 'Verdana', 12, 200, 300, 1, 30, 2000, 200, 0, 1, '', '', 0, 0, '_blank', '_self', 0, 0, 1, 1, 'd.m.Y', 'new.gif', 0, '#cccccc', '#000000', 'Verdana', 12, 0, 1, 'Y-m-d', 0, 0, 0, 0, 300, 200, '', 0, 0, 0, '#cccccc', '#000000', 10, 1, 1, 1, 'Y-m-d', 8, 15, -1, 0, 500, 100, '', '', 10, 100, 10, 1, 1, '', '', 0, 0, 0, 0, 0, '#999999', 'Verdana, Geneva, Arial, Helvetica, sans-serif', '3', '#EEEEEE', 1, '_self', '_self', 20, '#696969', '#696969', '#696969', '#191970', '#191970', '#191970', '#F0FFFF', '#F0FFFF', '#F0FFFF', '#191970', '#191970', '#191970', '#CD5C5C', '#CD5C5C', '#CD5C5C', '#FFF0C0', '#FFF0C0', '#FFF0C0', 1, '#94AAD6', '#AFEEEE', '#ADD8E6', '#4682B4', '#1E90FF', '#0000ff', '#E0FFFF', '#DCDCDC', '#000000', 'Verdana, Geneva, Arial, Helvetica, sans-serif', '10pt', 'fett', 'normal', 'none', '', '', '8pt', '#DCDCDC', '#000000', 'Verdana, Geneva, Arial, Helvetica, sans-serif', '10pt', 'normal', 'normal', 'solid', 'thin', '#696969', '#94AAD6', '#AFEEEE', '#ADD8E6', '#4682B4', '#1E90FF', '#0000ff', '#E0FFFF', '#94AAD6', '#FFFAF0', 'Verdana, Geneva, Arial, Helvetica, sans-serif', '9pt', 'normal', 'normal', 'ridge', 'thin', '#483D8B', '#696969', '#696969', '#696969', '9pt', '#F0FFFF', '#F0FFFF', '#F0FFFF', '', 1, 20, 3, 1, '_self', 0, 0, 0, 'simpnews_ns4.css', 'simpnews_ns6.css', 'simpnews_opera.css', 'simpnews_gecko.css', 'simpnews_konqueror.css', -1, 0, 1, -1, 0, 'Verdana, Geneva, Arial, Helvetica, sans-serif', '2', '#000000', 3000, 150, 150, '_self', 'Y-m-d', 30, 0, '_self', '%B %d, %Y', '%B %d, %Y', 0, '_self', 30, 'Verdana', 18, '#ffffff', 0, 1, 1, 0, 0, '#000000', '_self', 100, 400, 0, -1, 0, 'Verdana, Geneva, Arial, Helvetica, sans-serif', 12, '#000000', 0, 1, '_self', '#eeeeee', 0, -1, -1, 0, 'Y-m-d', 150, 150, 50, 2, 0, '#eeeeee', '#333333', 'Verdana, Geneva, Arial, Helvetica, sans-serif', '2', 260, '#ffffff', '#000000', 'Verdana, Geneva, Arial, Helvetica, sans-serif', '1', 0, 0, '#eeeeee', 1, '30%', '70%', 1, '', '', 'propose.gif', 0, 'caltab.gif', 0, 2, 2, -1, 0, '_self', 0, 0, 0, 0, 0, 1, 3, 'print.gif', 0, '60%', '2005-09-15', '#dddddd', '#000000', 'Verdana, Geneva, Arial, Helvetica, sans-serif', '1', 0, '2000-09-01', '#dddddd', '#000000', 'Verdana, Geneva, Arial, Helvetica, sans-serif', '2', 0, 0, '%B %d, %Y', 30, '_self', 1, 1, 1, 'csvexport.gif', '%d.%m.%Y', 0, 'asclist.gif', '#483D8B', '#ffff00', '8pt', '\"Courier New\", Courier, monospace', '\"Courier New\", Courier, monospace', 'normal', '8pt', '#B0C4DE', 'normal', 'fett', '#EEE8AA', '#CD853F', '#B0C4DE', '#708090', 'Verdana, Geneva, Arial, Helvetica, sans-serif', '10pt', '#ff00ff', '#333333', 'none', '', '#000000', 'fett', 'normal', '#4682B4', '#ffffff', 'Verdana, Geneva, Arial, Helvetica, sans-serif', '10pt', '§', 'fett', '1', '5 pt', '5 pt', 'more.gif', 'announce.gif', 'gannounce.gif', 2872, 4, 0, 0, '%d.%m.%Y', 0, '1', '1', '1', '1', 0, 0, '1', 0, '1', 0, '1', '1', 0, 1, '-', 40, 1, 'highlight.gif', '#dddddd', 'Verdana, Geneva, Arial, Helvetica, sans-serif', '2', '#000000', '10px', 1, '_self', 20, '1', '1', 0, 1, 1, '#ffd989', '', '', '', 0, 0, 1, 1, 'latest newsletter senddate', '%d.%m.%Y %H:%M', '#ffa022', 'Verdana, Geneva, Arial, Helvetica, sans-serif', '2', '#000000', 100, 'email.gif', 'source script', 1, '', '', '#ffffff', '', 20, 1, '_self', 1, 1, 'Rate this entry', '80%', 6624, 'snews.php', 0, 975, '80%', 'an_inc.php', '80%', 1014, 'ev_inc.php', 1, '', '', '', 1, '', '', '', 1, '', 'ga:', 'an:', 0, 20, '#ff0000', 1, '#cacc00', 0, 1, 'd.m.Y H:i', 0, 1, '#999999', '#000000', 'Verdana, Geneva, Arial, Helvetica, sans-serif', '1', '#bbbbbb', 'Verdana, Geneva, Arial, Helvetica, sans-serif', 2, '#000000', 3, 0, 'comment_small.gif', 1, 'writecomment.gif', '%B %d, %Y', 1, '#ffff4a', 7, 'test', 'Channel title', 'Channel description', 'http://localhost/simpnews', 100, 200, '', 0, 'simpnews@localhost', 'simpnews@localhost', '', '', '', 0, 80, 0, 3711, 8, '', '', 0, 8, 0, '', '', 2, 5, 3, 10, 'print_small.gif', 'expand.gif', 'collapse.gif', '', '', 'snoopy.wbmp', 1, 'xml.gif', 0, '#333333', 0, '10px', '20px', 0, 1, 1, 20, 20, 5, 0, 5, 5, 3, 0, 0, 0, 0, 0, 0);";
if(!$result = mysql_query($sql, $db))
	die("Unable to add layout (en) ".mysql_error());
$sql = "INSERT INTO ".$tableprefix."_layout (lang, heading, headingbgcolor, headingfontcolor, headingfont, headingfontsize, bordercolor, contentbgcolor, contentfontcolor, contentfont, contentfontsize, TableWidth, timestampfontcolor, timestampfontsize, timestampfont, dateformat, showcurrtime, customheader, pagebgcolor, stylesheet, newsheadingbgcolor, newsheadingfontcolor, newsheadingstyle, posterbgcolor, posterfontcolor, posterstyle, displayposter, posterfont, posterfontsize, newsheadingfont, newsheadingfontsize, timestampbgcolor, timestampstyle, displaysubscriptionbox, defsignature, subscriptionbgcolor, subscriptionfontcolor, subscriptionfont, subscriptionfontsize, copyrightbgcolor, copyrightfontcolor, copyrightfont, copyrightfontsize, emailremark, id, deflayout, customfooter, searchpic, backpic, pagepic_back, pagepic_first, pagepic_next, pagepic_last, pagetoppic, newssignal_on, newssignal_off, helppic, attachpic, prevpic, fwdpic, eventheading, event_dateformat, newstickerbgcolor, newstickerfontcolor, newstickerfont, newstickerfontsize, newstickerhighlightcolor, newstickerheight, newstickerwidth, newstickerscrollspeed, newstickerscrolldelay, newstickermaxdays, newstickermaxentries, newsscrollerbgcolor, newsscrollerfontcolor, newsscrollerfont, newsscrollerfontsize, newsscrollerheight, newsscrollerwidth, newsscrollerscrollspeed, newsscrollerscrolldelay, newsscrollerscrollpause, newsscrollermaxdays, newsscrollermaxentries, newsscrollertype, newsscrollerbgimage, newsscrollerfgimage, newsscrollermousestop, newsscrollermaxchars, newstickertarget, newsscrollertarget, newsscrollerxoffset, newsscrolleryoffset, newsscrollerwordwrap, newsscrollerdisplaydate, newsscrollerdateformat, newentrypic, newstypermaxentries, newstyperbgcolor, newstyperfontcolor, newstyperfont, newstyperfontsize, newstyperfontstyle, newstyperdisplaydate, newstyperdateformat, newstyperxoffset, newstyperyoffset, newstypermaxdays, newstypermaxchars, newstyperwidth, newstyperheight, newstyperbgimage, newstyperscroll, newsscrollernolinking, newstyper2maxentries, newstyper2bgcolor, newstyper2fontcolor, newstyper2fontsize, newstyper2displaydate, newstyper2newscreen, newstyper2waitentry, newstyper2dateformat, newstyper2indent, newstyper2linespace, newstyper2maxdays, newstyper2maxchars, newstyper2width, newstyper2height, newstyper2bgimage, newstyper2sound, newstyper2charpause, newstyper2linepause, newstyper2screenpause, eventscrolleractdate, separatebylang, headerfile, footerfile, headerfilepos, footerfilepos, usecustomheader, usecustomfooter, copyrightpos, categorybgcolor, categoryfont, categoryfontsize, categoryfontcolor, categorystyle, hotnews2target, news2target, newsscrollermaxlines, linkcolor, vlinkcolor, alinkcolor, morelinkcolor, morevlinkcolor, morealinkcolor, catlinkcolor, catvlinkcolor, catalinkcolor, commentlinkcolor, commentvlinkcolor, commentalinkcolor, attachlinkcolor, attachvlinkcolor, attachalinkcolor, pagenavlinkcolor, pagenavvlinkcolor, pagenavalinkcolor, colorscrollbars, sbfacecolor, sbhighlightcolor, sbshadowcolor, sbdarkshadowcolor, sb3dlightcolor, sbarrowcolor, sbtrackcolor, snsel_bgcolor, snsel_fontcolor, snsel_font, snsel_fontsize, snsel_fontstyle, snsel_fontweight, snsel_borderstyle, snsel_bordercolor, snsel_borderwidth, morelinkfontsize, sninput_bgcolor, sninput_fontcolor, sninput_font, sninput_fontsize, sninput_fontstyle, sninput_fontweight, sninput_borderstyle, sninput_borderwidth, sninput_bordercolor, snisb_facecolor, snisb_highlightcolor, snisb_shadowcolor, snisb_darkshadowcolor, snisb_3dlightcolor, snisb_arrowcolor, snisb_trackcolor, snbutton_bgcolor, snbutton_fontcolor, snbutton_font, snbutton_fontsize, snbutton_fontstyle, snbutton_fontweight, snbutton_borderstyle, snbutton_borderwidth, snbutton_bordercolor, eventlinkcolor, eventalinkcolor, eventvlinkcolor, eventlinkfontsize, actionlinkcolor, actionvlinkcolor, actionalinkcolor, pagebgpic, eventcalshortnews, eventcalshortlength, eventcalshortnum, eventcalshortonlyheadings, hotnewstarget, hotnewsdisplayposter, hotnewsnohtmlformatting, hotnewsicons, ns4style, ns6style, operastyle, geckostyle, konquerorstyle, jsnf_maxdays, jsnf_maxentries, jsnf_displaydate, jsnf_maxchars, jsnf_nolinking, jsnf_font, jsnf_fontsize, jsnf_fontcolor, jsnf_delay, jsnf_width, jsnf_height, jsnf_linktarget, jsnf_dateformat, news4maxchars, news4useddlink, news4linktarget, news4dateformat, news3dateformat, news3useddlink, news3linktarget, news3maxchars, ss_font, ss_fontsize, ss_fontcolor, ss_fontstyle, ss_stars, ss_speed, ss_dir, ss_shadow, ss_bgcolor, ss_targetframe, ss_height, ss_width, ss_maxentries, ss_maxdays, ss_nolinking, jsns_font, jsns_fontsize, jsns_fontcolor, jsns_nolinking, jsns_displaydate, jsns_linktarget, jsns_bgcolor, jsns_direction, jsns_maxchars, jsns_maxdays, jsns_maxentries, jsns_dateformat, jsns_height, jsns_width, jsns_speed, jsns_step, tablealign, clheadingbgcolor, clheadingfontcolor, clheadingfont, clheadingfontsize, clwidth, clcontentbgcolor, clcontentfontcolor, clcontentfont, clcontentfontsize, enablecatlist, catframenewslist, clcontenthighlight, clactdontlink, clleftwidth, clrightwidth, clnowrap, contentcopy, addbodytags, proposepic, proposereq, caltabpic, caljumpbox, cjminyear, cjmaxyear, hotevmaxdays, hotevmaxentries, hotevtarget, displayevnum, hotevmaxchars, hotevnohtmlformatting, hotevdisplayposter, hotevicons, hotscriptsnoheading, evproposemaxyears, printpic, printheader, TableWidth2, news5enddate, news5monthbgcolor, news5monthfontcolor, news5monthfont, news5monthfontsize, news5monthfontstyle, news5startdate, news5yearbgcolor, news5yearfontcolor, news5yearfont, news5yearfontsize, news5yearfontstyle, news5monthdisplayyear, news5dateformat, news5maxchars, news5linktarget, news5useddlink, news5displayposter, news5displayicons, csvexportpic, csvexportdateformat, csvexportfields, asclistpic, bbchelp_bgcolor, bbchelp_fontcolor, bbchelp_fontsize, bbchelp_font, el_font, el_fontweight, el_fontsize, el_hovercolor, bbchelp_fontweight, bbchelp_fontstyle, sb_bgcolor, sb_bordercolor, bbc_bgcolor, bbc_bordercolor, bbcsel_font, bbcsel_fontsize, bbcsel_fontcolor, bbcsel_bgcolor, bbcsel_borderstyle, bbcsel_borderwidth, bbcsel_bordercolor, bbcsel_fontstyle, bbcsel_fontweight, or_bgcolor, or_fontcolor, or_font, or_fontsize, sep_char, el_fontstyle, hn_newsheadingfontsize, nbindent, evbindent, morepic, announcepic, gannouncepic, announceoptions, maxevcannounce, noprinticon, nogotopicon, news5headingdateformat, news5noglobalprint, news4fontsize, news3fontsize, n5_newsheadingfontsize, n5_timestampfontsize, n5_timestampstyle, ev2onlyheadings, ev2_newsheadingfontsize, ev2_newsheadingstyle, ev2_timestampfontsize, ev2_timestampstyle, ev2_contentfontsize, ev2_posterfontsize, ev2_posterstyle, newsscrollerheadingsep, newsscrollerheadingsepchar, newsscrollernumsepchars, jsns_sepheading, highlightmarker, catinfobgcolor, catinfofont, catinfofontsize, catinfofontcolor, catinfoindent, searchonlyheadings, searchdetailtarget, searchshortchars, sshort_timestampfontsize, sshort_headingfontsize, nofileinfo, sn_hideallnewslink, pagenavdetails, newsletterbgcolor, newslettercustomheader, newslettercustomfooter, subredirecturl, newsletteralign, linkposter, weekstart, evshowcalweek, nlsend_heading, nlsend_dateformat, nlsend_bgcolor, nlsend_font, nlsend_fontsize, nlsend_fontcolor, nfheight, emailpic, sncatlink, textareanoscroll, emailcustomheader, emailcustomfooter, emailbgcolor, emailpageremark, hn6_numentries, anhn6numentries, hotnews6target, hotnews7useddlink, ratingdisplay, ratingprelude, sns_tablewidth, sns_options, hnlinkdest, usehnlinkdest, aninc_options, aninc_tablewidth, hnlinkdestan, evinc_tablewidth, evinc_options, hnlinkdestev, useappletlinkdest, appletlinkdest, appletlinkdestan, appletlinkdestev, usejslinkdest, jslinkdest, jslinkdestev, jslinkdestan, evscrollevcal2, evscrollcal2dest, applet_ganmark, applet_anmark, attachpos, searchmaxchars, searchhighlightcolor, searchhighlight, activcellcolor, news4showcat, showproposer, event_dateformat2, commentsinline, icdisplayemail, ic_heading_bgcolor, ic_heading_fontcolor, ic_heading_font, ic_heading_fontsize, ic_body_bgcolor, ic_body_font, ic_body_fontsize, ic_body_fontcolor, ic_heading_style, ic_body_style, commentspic, hotnewscommentslink, writecommentpic, ev3_dateformat, masssuboptions, tablebgcolor, hn_catlinking, hn_linklayout, rss_channel_title, rss_channel_description, rss_channel_link, rss_auto_title, rss_auto_short, rss_channel_copyright, rss_maxentries, rss_channel_editor, rss_channel_webmaster, wap_title, wap_description, wap_copyright, wap_auto_short, wap_auto_title, wap_maxentries, wap_options, wap_ev_maxdays, wap_ev_title, wap_ev_description, wap_ev_maxentries, wap_an_maxdays, wap_an_maxentries, wap_an_title, wap_an_description, wap_evs_dayrange, wap_evs_maxldays, wap_catlist_epp, wap_evlist2_epp, printpic_small, expandpic, collapsepic, wap_cl_title, wap_cl_description, wap_cl_logo, newsnodate, rsspic, eventcalonlymarkers, evmarkcolgeneral, n4nodate, n4tbmargin, n4leftmargin, srchnolimit, newsnoicons, snnodate, hotnewsmaxchars, entriesperpage, numhotnews, newsnotifydays, news2entries, news3entries, srchaddoptions, cheadnobr, cfootnobr, news4addoptions, subemailtype, showfuturenews, nonltrans) ";
$sql.= "VALUES ('de', 'News', '#94AAD6', '#FFF0C0', 'Verdana, Geneva, Arial, Helvetica, sans-serif', '+2', '#000000', '#c0c0c0', '#000000', 'Verdana, Geneva, Arial, Helvetica, sans-serif', '2', '98%', '#000000', '1', 'Verdana, Geneva, Arial, Helvetica, sans-serif', 'd.m.Y H:i:s', 1, '', '#c0c0c0', 'simpnews.css', '#c0c0c0', '#222222', 0, '#c0c0c0', '#000000', 0, 1, 'Verdana, Geneva, Arial, Helvetica, sans-serif', '1', 'Verdana, Geneva, Arial, Helvetica, sans-serif', '3', '#c0c0c0', 0, 1, '', '#94AAD6', '#FFF0C0', 'Verdana, Geneva, Arial, Helvetica, sans-serif', '2', '#c0c0c0', '#000000', 'Verdana, Geneva, Arial, Helvetica, sans-serif', '1', '', 'def', 1, '', 'search.gif', 'back.gif', 'prev.gif', 'first.gif', 'next.gif', 'last.gif', 'pagetop.gif', 'blink.gif', 'off.gif', 'help.gif', 'attach.gif', 'prev_big.gif', 'next_big.gif', 'Ereignisse', 'd.m.Y', '#cccccc', '#000000', 'Verdana', 12, '#0000ff', 20, 300, 1, 30, -1, 0, '#cccccc', '#000000', 'Verdana', 12, 200, 300, 1, 30, 2000, 200, 0, 1, '', '', 0, 0, '_blank', '_self', 0, 0, 1, 1, 'd.m.Y', 'new.gif', 0, '#cccccc', '#000000', 'Verdana', 12, 0, 1, 'Y-m-d', 0, 0, 0, 0, 300, 200, '', 0, 0, 0, '#cccccc', '#000000', 10, 1, 1, 1, 'Y-m-d', 8, 15, -1, 0, 500, 100, '', '', 10, 100, 10, 1, 1, '', '', 0, 0, 0, 0, 0, '#999999', 'Verdana, Geneva, Arial, Helvetica, sans-serif', '3', '#EEEEEE', 1, '_self', '_self', 20, '#696969', '#696969', '#696969', '#191970', '#191970', '#191970', '#F0FFFF', '#F0FFFF', '#F0FFFF', '#191970', '#191970', '#191970', '#CD5C5C', '#CD5C5C', '#CD5C5C', '#FFF0C0', '#FFF0C0', '#FFF0C0', 1, '#94AAD6', '#AFEEEE', '#ADD8E6', '#4682B4', '#1E90FF', '#0000ff', '#E0FFFF', '#DCDCDC', '#000000', 'Verdana, Geneva, Arial, Helvetica, sans-serif', '10pt', 'fett', 'normal', 'none', '', '', '8pt', '#DCDCDC', '#000000', 'Verdana, Geneva, Arial, Helvetica, sans-serif', '10pt', 'normal', 'normal', 'solid', 'thin', '#696969', '#94AAD6', '#AFEEEE', '#ADD8E6', '#4682B4', '#1E90FF', '#0000ff', '#E0FFFF', '#94AAD6', '#FFFAF0', 'Verdana, Geneva, Arial, Helvetica, sans-serif', '9pt', 'normal', 'normal', 'ridge', 'thin', '#483D8B', '#696969', '#696969', '#696969', '9pt', '#F0FFFF', '#F0FFFF', '#F0FFFF', '', 1, 20, 3, 1, '_self', 0, 0, 0, 'simpnews_ns4.css', 'simpnews_ns6.css', 'simpnews_opera.css', 'simpnews_gecko.css', 'simpnews_konqueror.css', -1, 0, 1, -1, 0, 'Verdana, Geneva, Arial, Helvetica, sans-serif', '2', '#000000', 3000, 150, 150, '_self', 'Y-m-d', 30, 0, '_self', '%B %d, %Y', '%B %d, %Y', 0, '_self', 30, 'Verdana', 18, '#ffffff', 0, 1, 1, 0, 0, '#000000', '_self', 100, 400, 0, -1, 0, 'Verdana, Geneva, Arial, Helvetica, sans-serif', 12, '#000000', 0, 1, '_self', '#eeeeee', 0, -1, -1, 0, 'Y-m-d', 150, 150, 50, 2, 0, '#eeeeee', '#333333', 'Verdana, Geneva, Arial, Helvetica, sans-serif', '2', 260, '#ffffff', '#000000', 'Verdana, Geneva, Arial, Helvetica, sans-serif', '1', 0, 0, '#eeeeee', 1, '30%', '70%', 1, '', '', 'propose.gif', 0, 'caltab.gif', 0, 2, 2, -1, 0, '_self', 0, 0, 0, 0, 0, 1, 3, 'print.gif', 0, '60%', '2005-09-15', '#dddddd', '#000000', 'Verdana, Geneva, Arial, Helvetica, sans-serif', '1', 0, '2000-09-01', '#dddddd', '#000000', 'Verdana, Geneva, Arial, Helvetica, sans-serif', '2', 0, 0, '%B %d, %Y', 30, '_self', 1, 1, 1, 'csvexport.gif', '%d.%m.%Y', 0, 'asclist.gif', '#483D8B', '#ffff00', '8pt', '\"Courier New\", Courier, monospace', '\"Courier New\", Courier, monospace', 'normal', '8pt', '#B0C4DE', 'normal', 'fett', '#EEE8AA', '#CD853F', '#B0C4DE', '#708090', 'Verdana, Geneva, Arial, Helvetica, sans-serif', '10pt', '#ff00ff', '#333333', 'none', '', '#000000', 'fett', 'normal', '#4682B4', '#ffffff', 'Verdana, Geneva, Arial, Helvetica, sans-serif', '10pt', '§', 'fett', '1', '5 pt', '5 pt', 'more.gif', 'announce.gif', 'gannounce.gif', 2872, 4, 0, 0, '%d.%m.%Y', 0, '1', '1', '1', '1', 0, 0, '1', 0, '1', 0, '1', '1', 0, 1, '-', 40, 1, 'highlight.gif', '#dddddd', 'Verdana, Geneva, Arial, Helvetica, sans-serif', '2', '#000000', '10px', 1, '_self', 20, '1', '1', 0, 1, 1, '#ffd989', '', '', '', 0, 0, 1, 1, 'latest newsletter senddate', '%d.%m.%Y %H:%M', '#ffa022', 'Verdana, Geneva, Arial, Helvetica, sans-serif', '2', '#000000', 100, 'email.gif', 'source script', 1, '', '', '#ffffff', '', 20, 1, '_self', 1, 1, 'Rate this entry', '80%', 6624, 'snews.php', 0, 975, '80%', 'an_inc.php', '80%', 1014, 'ev_inc.php', 1, '', '', '', 1, '', '', '', 1, '', 'ga:', 'an:', 0, 20, '#ff0000', 1, '#cacc00', 0, 1, 'd.m.Y H:i', 0, 1, '#999999', '#000000', 'Verdana, Geneva, Arial, Helvetica, sans-serif', '1', '#bbbbbb', 'Verdana, Geneva, Arial, Helvetica, sans-serif', 2, '#000000', 3, 0, 'comment_small.gif', 1, 'writecomment.gif', '%B %d, %Y', 1, '#ffff4a', 7, 'test', 'Channel title', 'Channel description', 'http://localhost/simpnews', 100, 200, '', 0, 'simpnews@localhost', 'simpnews@localhost', '', '', '', 0, 80, 0, 3711, 8, '', '', 0, 8, 0, '', '', 2, 5, 3, 10, 'print_small.gif', 'expand.gif', 'collapse.gif', '', '', 'snoopy.wbmp', 1, 'xml.gif', 0, '#333333', 0, '10px', '20px', 0, 1, 1, 20, 20, 5, 0, 5, 5, 3, 0, 0, 0, 0, 0, 0);";
if(!$result = mysql_query($sql, $db))
	die("Unable to add layout (de) ".mysql_error());
echo "Adding default settings...<br>";
flush();
$sql = "INSERT INTO ".$tableprefix."_settings VALUES (1, 1, 0, 'simpnews@localhost', 0, 1, 0, 1, 1, 1, 'SimpNews Subscription', 0, 1, 'Sitename', 0, 1, 1, -1, 1, 1, 1, 1, 'SimpNews', 1, 1, 0, 1, 1, 0, 1, 0, 0, 88, 2, 1, 2, 0, 1, 90, 0, 7856128, 'Center', 0, 32, 32, 50, 50, 0, 0, 0, 1, 0, 0, 100000, 1, 2, 1, 0, 1, 0, 1, 0, 100, 1, 1, 1, 365, 60, 0, 0, 1, 1, 0, 1, 1, 2, 998, 0, 0, 0, 0);";
if(!$result = mysql_query($sql, $db))
	die("Unable to add settings ".mysql_error());
fill_mimetypes($tableprefix,$db);
if(isset($importfreemailer))
{
	echo "Adding default freemailer...<br>";
	flush();
	fill_freemailer($tableprefix,$db);
}
if(isset($importemoticons))
{
	echo "Adding default smilies...<br>";
	flush();
	fill_emoticons($tableprefix,$db);
}
if(isset($importicons))
{
	echo "Adding default icons...<br>";
	flush();
	fill_icons($tableprefix,$db);
}
if(isset($importleacher))
{
	echo "Adding default offlinebrowser...<br>";
	flush();
	fill_leacher($leacherprefix,$db);
}
?>
<br><div align="center">Installation done.<br>Please remove install.php, upgrade*.php, mkconfig.php and fill_*.php from server</div>
<div align="center">Now you can login to the <a href="index.php">admininterface</a></div>
</body></html>
<?php
exit;
}
}
if(!isset($admin_user))
	$admin_user="";
if(!isset($admin_email))
	$admin_email="";
if(!isset($realname))
	$realname="";
?>
<table align="center" width="80%">
<tr><td align="center" colspan="3"><b>Adminuser</b></td></tr>
<form action="<?php echo $act_script_url?>" method="post">
<tr><td align="right">Username:</td><td align="center" width="1%">*</td>
<td><input type="text" name="admin_user" size="40" maxlength="80" value="<?php echo $admin_user?>"></td></tr>
<tr><td align="right">real name:</td><td align="center" width="1%">&nbsp;</td>
<td><input type="text" name="realname" size="40" maxlength="240" value="<?php echo $realname?>"></td></tr>
<tr><td align="right">E-Mail:</td><td align="center" width="1%">&nbsp;</td>
<td><input type="text" name="admin_email" size="40" maxlength="80" value="<?php echo $admin_email?>"></td></tr>
<tr><td align="right">Password:</td><td align="center" width="1%">*</td>
<td><input type="password" name="admin_pw1" size="40" maxlength="40"></td></tr>
<tr><td align="right">retype password:</td><td align="center" width="1%">*</td>
<td><input type="password" name="admin_pw2" size="40" maxlength="40"></td></tr>
<tr><td colspan="2">&nbsp;</td><td align="left"><input type="checkbox" name="importfreemailer" value="1"> import predefined freemailer</td></TR>
<tr><td colspan="2">&nbsp;</td><td align="left"><input type="checkbox" name="importemoticons" value="1"> import predefined smilies</td></TR>
<tr><td colspan="2">&nbsp;</td><td align="left"><input type="checkbox" name="importicons" value="1"> import predefined icons</td></TR>
<tr><td colspan="2">&nbsp;</td><td align="left"><input type="checkbox" name="importleacher" value="1"> import predefined offline browser</td></TR>
<tr><td align="center" colspan="3"><input type="submit" name="submit" value="submit"></td></tr>
</form>
</table>
</body></html>
<?php
function table_exists($searchedtable)
{
	global $dbname;

	$tables = mysql_list_tables($dbname);
	$numtables = @mysql_numrows($tables);
	for($i=0;$i<$numtables;$i++)
	{
		$tablename = mysql_tablename($tables,$i);
		if($tablename==$searchedtable)
			return true;
	}
	return false;
}
?>
