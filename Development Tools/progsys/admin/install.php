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
if(isset($importfreemailer))
	require('fill_freemailer.php');
?>
<html><body>
<div align="center"><h3>ProgSys V<?php echo $version?> Install</h3></div>
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
// create table progsys_wparts
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_wparts;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_wparts");
$sql = "CREATE TABLE ".$tableprefix."_wparts (";
$sql.= "`id` int(10) unsigned NOT NULL auto_increment,";
$sql.= "`wpdesc` varchar(255) default NULL,";
$sql.= "`mainttxt` text,";
$sql.= "`maint` tinyint(1) unsigned NOT NULL default '0',";
$sql.= "PRIMARY KEY  (`id`));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_wparts".mysql_error());
// create table progsys_screenshots
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_screenshots;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_screenshots");
$sql = "CREATE TABLE ".$tableprefix."_screenshots (";
$sql.= "entrynr int(10) unsigned NOT NULL auto_increment,";
$sql.= "dir int(10) NOT NULL default '0',";
$sql.= "filename varchar(255) NOT NULL default '',";
$sql.= "longcomment text NOT NULL,";
$sql.= "shortcomment varchar(255) NOT NULL default '',";
$sql.= "displaypos int(10) NOT NULL default '0',";
$sql.= "thumbnailfile varchar(255) NOT NULL default '',";
$sql.= "PRIMARY KEY  (entrynr))";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_screenshots".mysql_error());
// create table progsys_screenshotdirs
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_screenshotdirs;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_screenshotdirs");
$sql = "CREATE TABLE ".$tableprefix."_screenshotdirs (";
$sql.= "entrynr int(10) unsigned NOT NULL auto_increment,";
$sql.= "program int(10) unsigned NOT NULL default '0',";
$sql.= "picdir varchar(255) NOT NULL default '',";
$sql.= "thumbdir varchar(255) NOT NULL default '',";
$sql.= "addheader text,";
$sql.= "picurl varchar(255) NOT NULL default '',";
$sql.= "thumburl varchar(255) NOT NULL default '',";
$sql.= "PRIMARY KEY  (entrynr))";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_screenshotdirs".mysql_error());
// create table progsys_partnersites
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_partnersites;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_partnersites");
$sql = "CREATE TABLE ".$tableprefix."_partnersites (";
$sql.= "sitenr int(10) unsigned NOT NULL auto_increment,";
$sql.= "name varchar(80) NOT NULL default '',";
$sql.= "siteurl varchar(255) NOT NULL default '',";
$sql.= "email varchar(255) NOT NULL default '',";
$sql.= "emaillang varchar(4) NOT NULL default '',";
$sql.= "disabled tinyint(1) unsigned NOT NULL default '0',";
$sql.= "logourl varchar(255) NOT NULL default '',";
$sql.= "linktarget varchar(80) NOT NULL default '',";
$sql.= "PRIMARY KEY  (sitenr))";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_partnersites".mysql_error());
// create table progsys_partnerclicks
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_partnerclicks;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_partnerclicks");
$sql = "CREATE TABLE ".$tableprefix."_partnerclicks (";
$sql.= "day date NOT NULL default '0000-00-00',";
$sql.= "sitenr int(10) NOT NULL default '0',";
$sql.= "clicks int(11) unsigned NOT NULL default '0')";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_partnerclicks".mysql_error());
// create table progsys_mirrorserver
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_mirrorserver;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_mirrorserver");
$sql = "CREATE TABLE ".$tableprefix."_mirrorserver (";
$sql.= "servernr int(10) unsigned NOT NULL auto_increment,";
$sql.= "servername varchar(80) NOT NULL default '',";
$sql.= "description varchar(255) NOT NULL default '',";
$sql.= "downurl varchar(255) NOT NULL default '',";
$sql.= "iconurl varchar(255) NOT NULL default '',";
$sql.= "PRIMARY KEY  (servernr))";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_mirrorserver".mysql_error());
// create table progsys_allowed_referers
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_allowed_referers;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_allowed_referers");
$sql = "CREATE TABLE ".$tableprefix."_allowed_referers (";
$sql.= "entrynr int(10) unsigned NOT NULL auto_increment,";
$sql.= "address varchar(255) NOT NULL default '',";
$sql.= "PRIMARY KEY  (entrynr))";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_allowed_referers".mysql_error());
// create table progsys_forbidden_referers
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_forbidden_referers;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_forbidden_referers");
$sql = "CREATE TABLE ".$tableprefix."_forbidden_referers (";
$sql.= "entrynr int(10) unsigned NOT NULL auto_increment,";
$sql.= "address varchar(255) NOT NULL default '',";
$sql.= "PRIMARY KEY  (entrynr))";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_forbidden_referers".mysql_error());
// create table progsys_compr_downloads
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_compr_downloads;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_compr_downloads");
$sql = "CREATE TABLE ".$tableprefix."_compr_downloads (";
$sql.= "month date NOT NULL DEFAULT '0000-00-00' ,";
$sql.= "filenr int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "raw bigint(30) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "uni bigint(30) unsigned NOT NULL DEFAULT '0');";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_compr_downloads".mysql_error());
// create table progsys_counts
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_counts;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_counts");
$sql = "CREATE TABLE ".$tableprefix."_counts (";
$sql.= "entrynr int(10) unsigned NOT NULL auto_increment,";
$sql.= "lastdownload date NOT NULL DEFAULT '0000-00-00' ,";
$sql.= "PRIMARY KEY (entrynr));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_counts".mysql_error());
// create table progsys_download_files
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_donwload_files;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_download_files");
$sql = "CREATE TABLE ".$tableprefix."_download_files (";
$sql.= "filenr int(10) unsigned NOT NULL auto_increment,";
$sql.= "url varchar(240) NOT NULL DEFAULT '' ,";
$sql.= "programm int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "description varchar(80) NOT NULL DEFAULT '' ,";
$sql.= "downloadenabled tinyint(1) unsigned NOT NULL default '1',";
$sql.= "mirrorserver int(10) unsigned NOT NULL default '0',";
$sql.= "betaversion tinyint(1) unsigned NOT NULL default '0',";
$sql.= "nofinfo tinyint(1) unsigned NOT NULL default '0',";
$sql.= "PRIMARY KEY (filenr));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_download_files".mysql_error());
// create table progsys_downloads
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_downloads;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_downloads");
$sql = "CREATE TABLE ".$tableprefix."_downloads (";
$sql.= "day date NOT NULL DEFAULT '0000-00-00' ,";
$sql.= "filenr int(10) NOT NULL DEFAULT '0' ,";
$sql.= "raw int(11) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "uni int(11) unsigned NOT NULL DEFAULT '0' );";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_downloads".mysql_error());
// create table progsys_download_ips
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_download_ips;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_download_ips");
$sql = "CREATE TABLE ".$tableprefix."_download_ips (";
$sql.= "day date NOT NULL DEFAULT '0000-00-00' ,";
$sql.= "filenr int(10) NOT NULL DEFAULT '0' ,";
$sql.= "ipadr varchar(15) NOT NULL DEFAULT '' ,";
$sql.= "time timestamp(14));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_download_ips".mysql_error());
// create table progsys_feature_requests
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_feature_requests;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_feature_requests");
$sql = "CREATE TABLE ".$tableprefix."_feature_requests (";
$sql.= "requestnr int(10) unsigned NOT NULL auto_increment,";
$sql.= "email varchar(120) NOT NULL DEFAULT '' ,";
$sql.= "request text NOT NULL DEFAULT '' ,";
$sql.= "publish tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "rating int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "ratingcount int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "ipadr varchar(16) NOT NULL DEFAULT '' ,";
$sql.= "enterdate date NOT NULL DEFAULT '0000-00-00' ,";
$sql.= "programm int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "releasestate tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "comment text NOT NULL DEFAULT '' ,";
$sql.= "PRIMARY KEY (requestnr));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_feature_requests".mysql_error());
// create table progsys_newsletter
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_newsletter;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_newsletter");
$sql = "CREATE TABLE ".$tableprefix."_newsletter (";
$sql.="entrynr int(10) unsigned NOT NULL auto_increment,";
$sql.="email varchar(240) NOT NULL DEFAULT '' ,";
$sql.="programm int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.="subscribeid int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.="unsubscribeid int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.="confirmed tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql.="enterdate datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ,";
$sql.="emailtype tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql.="listtype tinyint(1) unsigned NOT NULL default '0',";
$sql.="subscribername varchar(255) NOT NULL default '',";
$sql.="userip varchar(16) NOT NULL default '0.0.0.0',";
$sql.="mscheck tinyint(1) unsigned NOT NULL default '0',";
$sql.="PRIMARY KEY (entrynr));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_newsletter");
// create table progsys_references
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_references;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_references");
$sql = "CREATE TABLE ".$tableprefix."_references (";
$sql.= "id int(10) unsigned NOT NULL auto_increment,";
$sql.= "url varchar(255) NOT NULL DEFAULT '' ,";
$sql.= "publish tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "contactmail varchar(250) NOT NULL DEFAULT '' ,";
$sql.= "contactname varchar(250) NOT NULL DEFAULT '' ,";
$sql.= "sitename varchar(250) NOT NULL DEFAULT '' ,";
$sql.= "heardfrom varchar(240) NOT NULL DEFAULT '' ,";
$sql.= "enter_lang varchar(4) NOT NULL DEFAULT '' ,";
$sql.= "pin int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "programm int(10) NOT NULL DEFAULT '0' ,";
$sql.= "approved tinyint(1) unsigned NOT NULL default '0',";
$sql.= "prot varchar(6) NOT NULL default 'http',";
$sql.= "PRIMARY KEY (id));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_references");
// create table progsys_todo
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_todo;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_todo");
$sql = "CREATE TABLE ".$tableprefix."_todo (";
$sql.= "todonr int(10) unsigned NOT NULL auto_increment,";
$sql.= "programm int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "lastedited date NOT NULL DEFAULT '0000-00-00' ,";
$sql.= "editor int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "text text NOT NULL DEFAULT '' ,";
$sql.= "state tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "rating int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "ratingcount int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "PRIMARY KEY (todonr));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_todo");
// create table progsys_bugtraq
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_bugtraq;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_bugtraq");
$sql = "CREATE TABLE ".$tableprefix."_bugtraq (";
$sql .="bugnr int(10) unsigned NOT NULL auto_increment,";
$sql .="programm int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql .="custname varchar(120) NOT NULL DEFAULT '' ,";
$sql .="custmail varchar(120) NOT NULL DEFAULT '' ,";
$sql .="enterdate date NOT NULL DEFAULT '0000-00-00' ,";
$sql .="processor int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql .="state tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql .="fixversion varchar(10) NOT NULL DEFAULT '' ,";
$sql .="lastedited date NOT NULL DEFAULT '0000-00-00' ,";
$sql .="bugtext text NOT NULL DEFAULT '' ,";
$sql .="fixtext text NOT NULL DEFAULT '' ,";
$sql .="usedversion varchar(10) NOT NULL DEFAULT '' ,";
$sql .="enterip varchar(16) NOT NULL DEFAULT '0.0.0.0' ,";
$sql .="PRIMARY KEY (bugnr));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_bugtraq");
// create table progsys_changelog
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_changelog;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_changelog");
$sql = "CREATE TABLE ".$tableprefix."_changelog (";
$sql .="entrynr int(10) unsigned NOT NULL auto_increment,";
$sql .="version varchar(20) NOT NULL DEFAULT '' ,";
$sql .="programm int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql .="versiondate date NOT NULL DEFAULT '0000-00-00' ,";
$sql .="changes text NOT NULL DEFAULT '' ,";
$sql .="isbeta tinyint(1) unsigned NOT NULL default '0',";
$sql .="nlsenddate datetime NOT NULL default '0000-00-00 00:00:00',";
$sql .="PRIMARY KEY (entrynr));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_changelog");
// create table progsys_hostcache
if(!table_exists($hcprefix."_hostcache"))
{
	$sql = "CREATE TABLE /*!32300 IF NOT EXISTS*/ ".$hcprefix."_hostcache (";
	$sql .="ipadr varchar(16) NOT NULL DEFAULT '0' ,";
	$sql .="hostname varchar(240) NOT NULL DEFAULT '' ,";
	$sql .="UNIQUE ipadr (ipadr));";
	if(!$result = mysql_query($sql, $db))
		die("Unable to create table ".$hcprefix."_hostcache");
}
// create table progsys_texts
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_texts;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_texts");
$sql = "CREATE TABLE ".$tableprefix."_texts (";
$sql .="textnr int(10) unsigned NOT NULL auto_increment,";
$sql .="textid varchar(20) NOT NULL DEFAULT '' ,";
$sql .="lang varchar(4) NOT NULL DEFAULT '' ,";
$sql .="text text NOT NULL DEFAULT '' ,";
$sql .="PRIMARY KEY (textnr));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_texts");
// create table progsys_failed_notify
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_failed_notify;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_failed_notify");
$sql = "CREATE TABLE ".$tableprefix."_failed_notify (";
$sql .="usernr int(10) unsigned NOT NULL DEFAULT '0');";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_failed_notify");
// create table progsys_prog_os
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_prog_os;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_prog_os");
$sql = "CREATE TABLE ".$tableprefix."_prog_os (";
$sql .="osnr int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql .="prognr int(10) unsigned NOT NULL DEFAULT '0');";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_prog_os");
// create table progsys_os
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_os;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_os");
$sql = "CREATE TABLE ".$tableprefix."_os (";
$sql .="osnr int(10) unsigned NOT NULL auto_increment,";
$sql .="osname varchar(180) NOT NULL DEFAULT '' ,";
$sql .="PRIMARY KEY (osnr));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_os");
// create table progsys_failed_logins
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_failed_logins;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_failed_logins");
$sql = "CREATE TABLE ".$tableprefix."_failed_logins (";
$sql .="loginnr int(10) unsigned NOT NULL auto_increment,";
$sql .="username varchar(250) NOT NULL DEFAULT '0' ,";
$sql .="ipadr varchar(16) NOT NULL DEFAULT '' ,";
$sql .="logindate datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ,";
$sql .="usedpw varchar(240) NOT NULL DEFAULT '' ,";
$sql .="PRIMARY KEY (loginnr));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_failed_logins");
// create table progsys_freemailer
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_freemailer;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_freemailer");
$sql = "CREATE TABLE ".$tableprefix."_freemailer (";
$sql .="entrynr int(10) unsigned NOT NULL auto_increment,";
$sql .="address varchar(100) NOT NULL DEFAULT '' ,";
$sql .="PRIMARY KEY (entrynr));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_freemailer");
// create table progsys_banlist
if(!table_exists($banprefix."_banlist"))
{
	$sql = "CREATE TABLE /*!32300 IF NOT EXISTS*/  ".$banprefix."_banlist (";
	$sql .="bannr int(10) unsigned NOT NULL auto_increment,";
	$sql .="ipadr varchar(16) NOT NULL DEFAULT '0.0.0.0' ,";
	$sql .="subnetmask varchar(16) NOT NULL DEFAULT '0.0.0.0' ,";
	$sql .="reason text ,";
	$sql .="PRIMARY KEY (bannr));";
	if(!$result = mysql_query($sql, $db))
		die("Unable to create table ".$banprefix."_banlist");
}
// create table progsys_programm_admins
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_programm_admins;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_programm_admins");
$sql = "CREATE TABLE ".$tableprefix."_programm_admins (";
$sql .="prognr int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql .="usernr int(10) unsigned NOT NULL DEFAULT '0');";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_programm_admins");
// create table progsys_programm
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_programm;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_programm");
$sql = "CREATE TABLE ".$tableprefix."_programm (";
$sql.= "prognr int(10) unsigned NOT NULL auto_increment,";
$sql.= "programmname varchar(80) ,";
$sql.= "progid varchar(10) NOT NULL DEFAULT '' ,";
$sql.= "language varchar(5) NOT NULL DEFAULT 'de' ,";
$sql.= "stylesheet varchar(250) NOT NULL DEFAULT '' ,";
$sql.= "usecustomheader tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "usecustomfooter tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "headerfile varchar(240) NOT NULL DEFAULT '' ,";
$sql.= "footerfile varchar(240) NOT NULL DEFAULT '' ,";
$sql.= "pageheader text NOT NULL DEFAULT '' ,";
$sql.= "pagefooter text NOT NULL DEFAULT '' ,";
$sql.= "enablenewsletter tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "enabletodorating tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "enablebugentries tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "maxconfirmtime int(2) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "newsletterfreemailer tinyint(1) unsigned NOT NULL DEFAULT '1' ,";
$sql.= "newsletterremark text NOT NULL DEFAULT '' ,";
$sql.= "enablefeaturerequests tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "featurerequestspublic tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "ratefeaturerequests tinyint(1) unsigned NOT NULL DEFAULT '1' ,";
$sql.= "publishnewbugentries tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "requestratingspublic tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "hasbeta tinyint(1) unsigned NOT NULL default '0',";
$sql.= "emailname varchar(80) NOT NULL default '',";
$sql.= "downpath varchar(255) NOT NULL default '',";
$sql.= "betapath varchar(255) NOT NULL default '',";
$sql.= "disableref tinyint(1) unsigned NOT NULL default '0',";
$sql.= "PRIMARY KEY (prognr));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_programm");
// create table progsys_admins
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_admins;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_admins");
$sql = "CREATE TABLE ".$tableprefix."_admins (";
$sql .="usernr tinyint(3) unsigned NOT NULL auto_increment,";
$sql .="username varchar(80) NOT NULL DEFAULT '' ,";
$sql .="password varchar(40) binary NOT NULL DEFAULT '' ,";
$sql .="email varchar(80) ,";
$sql .="rights int(2) unsigned NOT NULL DEFAULT '0' ,";
$sql .="lastlogin datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ,";
$sql .="lockpw tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql .="realname varchar(240) NOT NULL DEFAULT '' ,";
$sql .="language varchar(4)  NOT NULL DEFAULT '' ,";
$sql .="lockentry tinyint(1) unsigned NOT NULL default '0',";
$sql .="PRIMARY KEY (usernr));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_admins");
// create table progsys_iplog
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_iplog;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_iplog");
$sql = "CREATE TABLE ".$tableprefix."_iplog (";
$sql .="lognr int(10) unsigned NOT NULL auto_increment,";
$sql .="usernr int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql .="logtime datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ,";
$sql .="ipadr varchar(16) NOT NULL DEFAULT '' ,";
$sql .="used_lang varchar(4) NOT NULL DEFAULT '' ,";
$sql .="PRIMARY KEY (lognr));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_iplog");
// create table progsys_layout
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_layout;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_layout");
$sql = "CREATE TABLE ".$tableprefix."_layout (";
$sql.= "layoutnr tinyint(3) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "headingbg varchar(8) ,";
$sql.= "bgcolor1 varchar(8) ,";
$sql.= "bgcolor2 varchar(8) ,";
$sql.= "pagebg varchar(8) ,";
$sql.= "tablewidth varchar(10) ,";
$sql.= "fontface varchar(80) ,";
$sql.= "fontsize1 varchar(10) ,";
$sql.= "fontsize2 varchar(10) ,";
$sql.= "fontsize3 varchar(10) ,";
$sql.= "fontcolor varchar(8) ,";
$sql.= "fontsize4 varchar(10) ,";
$sql.= "bgcolor3 varchar(8) ,";
$sql.= "headingfontcolor varchar(8) ,";
$sql.= "subheadingfontcolor varchar(8) ,";
$sql.= "linkcolor varchar(8) ,";
$sql.= "vlinkcolor varchar(8) ,";
$sql.= "alinkcolor varchar(8) ,";
$sql.= "groupfontcolor varchar(8) ,";
$sql.= "tabledescfontcolor varchar(8) ,";
$sql.= "fontsize5 varchar(10) ,";
$sql.= "dateformat varchar(10) ,";
$sql.= "watchlogins tinyint(1) unsigned DEFAULT '1' ,";
$sql.= "urlautoencode tinyint(1) unsigned NOT NULL DEFAULT '1' ,";
$sql.= "enablespcode tinyint(1) unsigned NOT NULL DEFAULT '1' ,";
$sql.= "nofreemailer tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "enablefailednotify tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "loginlimit int(5) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "timezone int(10) NOT NULL default '0',";
$sql.= "enablehostresolve tinyint(1) unsigned DEFAULT '1' ,";
$sql.= "usemenubar tinyint(1) unsigned NOT NULL DEFAULT '1' ,";
$sql.= "newbugnotify tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "progsysmail varchar(80) NOT NULL DEFAULT '' ,";
$sql.= "mailsig varchar(240) NOT NULL DEFAULT '' ,";
$sql.= "entriesperpage int(2) NOT NULL DEFAULT '0' ,";
$sql.= "checkrefs tinyint(1) unsigned NOT NULL default '1',";
$sql.= "refchkaffects int(10) unsigned NOT NULL default '0',";
$sql.= "autoapprove tinyint(1) unsigned NOT NULL default '0',";
$sql.= "msendlimit int(10) unsigned NOT NULL default '30',";
$sql.= "newreqnotify tinyint(1) unsigned NOT NULL default '1',";
$sql.= "newrefnotify tinyint(1) unsigned NOT NULL default '1',";
$sql.= "emaildisplay int(10) unsigned NOT NULL default '0',";
$sql.= "admdelconfirm tinyint(1) unsigned NOT NULL default '0',";
$sql.= "homepageurl varchar(240) NOT NULL default 'http://localhost',";
$sql.= "homepagedesc varchar(240) NOT NULL default 'Localhost',";
$sql.= "topfilter tinyint(1) unsigned NOT NULL default '0',";
$sql.= "psysmailname varchar(80) NOT NULL default '',";
$sql.= "admstorefilter tinyint(1) unsigned default '0',";
$sql.= "automscheck tinyint(1) unsigned NOT NULL default '0',";
$sql.= "thumbs_maxx int(10) NOT NULL default '0',";
$sql.= "thumbs_maxy int(10) NOT NULL default '0',";
$sql.= "thumbs_numcols int(10) NOT NULL default '0',";
$sql.= "autogenthumbs tinyint(1) NOT NULL default '0',";
$sql.= "dateformatlong varchar(20) NOT NULL default '',";
$sql.= "PRIMARY KEY (layoutnr));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_layout");
// create table progsys_session
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_session;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_session");
$sql = "CREATE TABLE ".$tableprefix."_session (";
$sql .="sessid int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql .="usernr int(10) NOT NULL DEFAULT '0' ,";
$sql .="starttime int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql .="remoteip varchar(15) NOT NULL DEFAULT '' ,";
$sql .="lastlogin datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ,";
$sql .="PRIMARY KEY (sessid),";
$sql .="INDEX sess_id (sessid),";
$sql .="INDEX start_time (starttime),";
$sql .="INDEX remote_ip (remoteip));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_session");
// create table progsys_misc
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_misc;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_misc");
$sql = "CREATE TABLE ".$tableprefix."_misc (";
$sql .="shutdown tinyint(3) unsigned NOT NULL DEFAULT '0' ,";
$sql .="shutdowntext text);";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_misc");
// insert adminuser
$admin_pw=md5($admin_pw1);
$admin_user=addslashes(strtolower($admin_user));
$sql = "INSERT INTO ".$tableprefix."_admins (";
$sql .="username, password, rights";
if(isset($admin_email))
	$sql .=", email";
$sql .=")";
$sql .="VALUES ('$admin_user', '$admin_pw', 3";
if(isset($admin_email))
	$sql .=", '$admin_email'";
$sql .=");";
if(!$result = mysql_query($sql, $db))
	die("Unable to create adminuser");
if(isset($importfreemailer))
	fill_freemailer($tableprefix,$db);
?>
<br><div align="center">Installation done.<br>Please remove install.php, upgrade*.php and fill_freemailer.php from server</div>
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
?>
<table align="center" width="80%">
<tr><td align="center" colspan="3"><b>Adminuser</b></td></tr>
<form action="<?php echo $PHP_SELF?>" method="post">
<tr><td align="right">Username:</td><td align="center" width="1%">*</td>
<td><input type="text" name="admin_user" size="40" maxlength="80" value="<?php echo $admin_user?>"></td></tr>
<tr><td align="right">E-Mail:</td><td align="center" width="1%">&nbsp;</td>
<td><input type="text" name="admin_email" size="40" maxlength="80" value="<?php echo $admin_email?>"></td></tr>
<tr><td align="right">Password:</td><td align="center" width="1%">*</td>
<td><input type="password" name="admin_pw1" size="40" maxlength="40"></td></tr>
<tr><td align="right">retype password:</td><td align="center" width="1%">*</td>
<td><input type="password" name="admin_pw2" size="40" maxlength="40"></td></tr>
<tr><td colspan="2">&nbsp;</td><td align="left"><input type="checkbox" name="importfreemailer" value="1"> import predefined freemailer</td></TR>
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
