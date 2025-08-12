<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require('../config.php');
?>
<html><body>
<div align="center"><h3>FAQEngine V<?php echo $faqeversion?> Install</h3></div>
<br>
<?php
if(isset($mode))
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
// create table faq_settings
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_settings;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_settings");
$sql = "CREATE TABLE ".$tableprefix."_settings (";
$sql.="settingnr int(10) unsigned NOT NULL default '0',";
$sql.="showproglist tinyint(1) unsigned NOT NULL default '0',";
$sql.="watchlogins tinyint(1) unsigned NOT NULL default '1',";
$sql.="allowemail tinyint(1) unsigned NOT NULL default '1',";
$sql.="urlautoencode tinyint(1) unsigned NOT NULL default '1',";
$sql.="enablespcode tinyint(1) unsigned NOT NULL default '1',";
$sql.="nofreemailer tinyint(1) unsigned NOT NULL default '0',";
$sql.="allowquestions tinyint(1) unsigned NOT NULL default '1',";
$sql.="faqemail varchar(140) NOT NULL default '',";
$sql.="allowusercomments tinyint(1) unsigned NOT NULL default '1',";
$sql.="newcommentnotify tinyint(1) unsigned NOT NULL default '0',";
$sql.="enablefailednotify tinyint(1) unsigned NOT NULL default '0',";
$sql.="loginlimit int(5) unsigned NOT NULL default '0',";
$sql.="timezone int(10) unsigned NOT NULL default '0',";
$sql.="enablehostresolve tinyint(1) unsigned NOT NULL default '1',";
$sql.="ratecomments tinyint(1) unsigned NOT NULL default '1',";
$sql.="usemenubar tinyint(1) unsigned NOT NULL default '1',";
$sql.="admtextareasrows int(4) unsigned NOT NULL default '30',";
$sql.="admtextareascols int(4) unsigned NOT NULL default '10',";
$sql.="enablekbrating tinyint(1) unsigned NOT NULL default '0',";
$sql.="userquestionanswermode tinyint(1) unsigned NOT NULL default '0',";
$sql.="userquestionanswermail tinyint(1) unsigned NOT NULL default '0',";
$sql.="userquestionautopublish tinyint(1) unsigned NOT NULL default '0',";
$sql.="faqengine_hostname varchar(140) NOT NULL default 'localhost',";
$sql.="ratingcomment tinyint(1) unsigned NOT NULL default '0',";
$sql.="nosunotify tinyint(1) unsigned NOT NULL default '0',";
$sql.="blockoldbrowser tinyint(1) unsigned NOT NULL default '1',";
$sql.="bbccolorbar tinyint(1) unsigned NOT NULL default '1',";
$sql.="disablehtmlemail tinyint(1) unsigned NOT NULL default '0',";
$sql.="admstorefaqfilters tinyint(1) unsigned NOT NULL default '1',";
$sql.="admhideunassigned tinyint(1) unsigned NOT NULL default '0',";
$sql.="admdelconfirm tinyint(1) unsigned NOT NULL default '0',";
$sql.="zlibavail tinyint(1) unsigned NOT NULL default '0',";
$sql.="msendlimit int(10) unsigned NOT NULL default '30',";
$sql.="subscriptionavail tinyint(1) unsigned NOT NULL default '1',";
$sql.="admedoptions int(10) unsigned NOT NULL default '0',";
$sql.="allsendcompressed tinyint(1) unsigned NOT NULL default '0',";
$sql.="uq_allownoemail tinyint(1) unsigned NOT NULL default '0',";
$sql.="blockleacher tinyint(1) unsigned NOT NULL default '1',";
$sql.="defmailsig text NOT NULL,";
$sql.="faqlistshortcuts tinyint(1) unsigned NOT NULL default '0',";
$sql.="faqlimitrelated tinyint(1) unsigned NOT NULL default '0',";
$sql.="displayrating tinyint(1) unsigned NOT NULL default '0',";
$sql.="admdateformat varchar(20) NOT NULL default 'Y-m-d H:i:s',";
$sql.="showtimezone tinyint(1) unsigned NOT NULL default '1',";
$sql.="showcurrtime tinyint(1) unsigned NOT NULL default '1',";
$sql.="maxconfirmtime int(4) unsigned NOT NULL default '2',";
$sql.="dosearchlog tinyint(1) unsigned NOT NULL default '0',";
$sql.="logdateformat varchar(20) NOT NULL default 'Y-m-d H:i:s',";
$sql.="uqscmail tinyint(1) NOT NULL default '0',";
$sql.="extfailedlog tinyint(1) unsigned NOT NULL default '0',";
$sql.="usebwlist tinyint(1) unsigned NOT NULL default '0',";
$sql.="lhide tinyint(1) unsigned NOT NULL default '0',";
$sql.="UNIQUE KEY settingnr (settingnr));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_settings".mysql_error());
// create table faq_bad_words
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
// create table faq_leachers
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
// create table faq_subscriptions
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_subscriptions;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_subscriptions");
$sql = "CREATE TABLE ".$tableprefix."_subscriptions (";
$sql.= "subscriptionnr int(10) unsigned NOT NULL auto_increment,";
$sql.= "email varchar(240) NOT NULL default '',";
$sql.= "confirmed int(1) unsigned NOT NULL default '0',";
$sql.= "language varchar(4) NOT NULL default '',";
$sql.= "subscribeid int(10) unsigned NOT NULL default '0',";
$sql.= "unsubscribeid int(10) unsigned NOT NULL default '0',";
$sql.= "enterdate datetime NOT NULL default '0000-00-00 00:00:00',";
$sql.= "emailtype tinyint(1) unsigned NOT NULL default '1',";
$sql.= "progid varchar(10) NOT NULL default '',";
$sql.= "compression tinyint(1) unsigned NOT NULL default '0',";
$sql.= "PRIMARY KEY  (subscriptionnr));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_subscriptions".mysql_error());
// create table faq_filetypedescription
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_filetypedescription;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_filetypedescription");
$sql = "CREATE TABLE ".$tableprefix."_filetypedescription (";
$sql.= "mimetype int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "language varchar(10) NOT NULL DEFAULT '' ,";
$sql.= "description varchar(80) NOT NULL DEFAULT '' ,";
$sql.= "UNIQUE filetypedescription (mimetype,language));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_filetypedescription".mysql_error());
// create table faq_mimetypes
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_mimetypes;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_mimetypes");
$sql = "CREATE TABLE ".$tableprefix."_mimetypes (";
$sql.= "entrynr int(10) unsigned NOT NULL auto_increment,";
$sql.= "mimetype varchar(240) NOT NULL DEFAULT '' ,";
$sql.= "icon varchar(240) NOT NULL DEFAULT '' ,";
$sql.= "PRIMARY KEY (entrynr));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_mimetypes".mysql_error());
// create table faq_fileextensions
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_fileextensions;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_fileextensions");
$sql = "CREATE TABLE ".$tableprefix."_fileextensions (";
$sql.= "entrynr int(10) unsigned NOT NULL auto_increment,";
$sql.= "mimetype int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "extension varchar(20) NOT NULL DEFAULT '' ,";
$sql.= "PRIMARY KEY (entrynr));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_fileextensions".mysql_error());
// create table faq_files
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_files;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_files");
$sql = "CREATE TABLE ".$tableprefix."_files (";
$sql.= "entrynr int(10) unsigned NOT NULL auto_increment,";
$sql.= "bindata longblob ,";
$sql.= "filename varchar(240) NOT NULL DEFAULT '' ,";
$sql.= "mimetype varchar(240) NOT NULL DEFAULT '' ,";
$sql.= "filesize int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "fs_filename varchar(240) NOT NULL default '',";
$sql.= "downloads int(10) unsigned NOT NULL default '0',";
$sql.= "description varchar(255) NOT NULL default '',";
$sql.= "PRIMARY KEY (entrynr));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_files".mysql_error());
// create table faq_faq_attachs
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_faq_attachs;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_faq_attachs");
$sql = "CREATE TABLE ".$tableprefix."_faq_attachs (";
$sql.= "entrynr int(10) unsigned NOT NULL auto_increment,";
$sql.= "faqnr int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "attachnr int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "PRIMARY KEY (entrynr));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_faq_attachs".mysql_error());
// create table faq_kb_attachs
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_kb_attachs;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_kb_attachs");
$sql = "CREATE TABLE ".$tableprefix."_kb_attachs (";
$sql.= "entrynr int(10) unsigned NOT NULL auto_increment,";
$sql.= "articlenr int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "attachnr int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "PRIMARY KEY (entrynr));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_kb_attachs".mysql_error());
// create table faq_dir_access
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_dir_access;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_dir_access");
$sql = "CREATE TABLE ".$tableprefix."_dir_access (";
$sql.= "entrynr int(10) unsigned NOT NULL auto_increment,";
$sql.= "dirname varchar(240) NOT NULL DEFAULT '' ,";
$sql.= "PRIMARY KEY (entrynr));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_dir_access");
// create table faq_prog_dirs
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_prog_dirs;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_prog_dirs");
$sql = "CREATE TABLE ".$tableprefix."_prog_dirs (";
$sql.= "prognr int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "dirnr int(10) unsigned NOT NULL DEFAULT '0');";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_prog_dirs".mysql_error());
// create table faq_faq_os
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_faq_os;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_faq_os");
$sql = "CREATE TABLE ".$tableprefix."_faq_os (";
$sql.= "faqnr int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "osnr int(10) unsigned NOT NULL DEFAULT '0' );";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_faq_os".mysql_error());
// create table faq_kb_prog_version
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_kb_prog_version;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_kb_prog_version");
$sql = "CREATE TABLE ".$tableprefix."_kb_prog_version (";
$sql.= "articlenr int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "progversion int(10) unsigned NOT NULL DEFAULT '0' );";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_kb_prog_version".mysql_error());
// create table faq_faq_prog_version
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_faq_prog_version;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_faq_prog_version");
$sql = "CREATE TABLE ".$tableprefix."_faq_prog_version (";
$sql.= "faqnr int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "progversion int(10) unsigned NOT NULL DEFAULT '0' );";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_faq_prog_version".mysql_error());
// create table faq_kb_ratings
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_kb_ratings;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_kb_ratings");
$sql = "CREATE TABLE ".$tableprefix."_kb_ratings (";
$sql.= "entrynr int(10) unsigned NOT NULL auto_increment,";
$sql.= "rating int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "articlenr int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "comment tinytext NOT NULL DEFAULT '' ,";
$sql.= "PRIMARY KEY (entrynr));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_kb_ratings".mysql_error());
// create table faq_ratings
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_ratings;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_ratings");
$sql = "CREATE TABLE ".$tableprefix."_ratings (";
$sql.= "entrynr int(10) unsigned NOT NULL auto_increment,";
$sql.= "rating int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "faqnr int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "comment tinytext NOT NULL DEFAULT '' ,";
$sql.= "PRIMARY KEY (entrynr));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_ratings".mysql_error());
// create table faq_related_faq
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_related_faq;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_related_faq");
$sql = "CREATE TABLE ".$tableprefix."_related_faq (";
$sql.= "srcfaq int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "destfaq int(10) unsigned NOT NULL DEFAULT '0' );";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_related_faq".mysql_error());
// create table faq_related_subcat
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_related_subcat;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_related_subcat");
$sql = "CREATE TABLE ".$tableprefix."_related_subcat (";
$sql.= "srccat int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "destcat int(10) unsigned NOT NULL DEFAULT '0' );";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_related_subcat".mysql_error());
// create table faq_related_categories
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_related_categories;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_related_categories");
$sql = "CREATE TABLE ".$tableprefix."_related_categories (";
$sql.= "srccat int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "destcat int(10) unsigned NOT NULL DEFAULT '0' );";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_related_categories".mysql_error());
// create table faq_kb_subcat
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_kb_subcat;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_kb_subcat");
$sql = "CREATE TABLE ".$tableprefix."_kb_subcat (";
$sql.= "catnr int(10) unsigned NOT NULL auto_increment,";
$sql.= "catname varchar(240) NOT NULL DEFAULT '' ,";
$sql.= "heading varchar(250) NOT NULL DEFAULT '' ,";
$sql.= "category int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "displaypos int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "PRIMARY KEY (catnr));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_kb_subcat".mysql_error());
// create table faq_subcategory
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_subcategory;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_subcategory");
$sql = "CREATE TABLE ".$tableprefix."_subcategory (";
$sql.= "catnr int(10) unsigned NOT NULL auto_increment,";
$sql.= "categoryname varchar(240) NOT NULL DEFAULT '' ,";
$sql.= "category int(10) unsigned DEFAULT '0' ,";
$sql.= "displaypos int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "PRIMARY KEY (catnr));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_subcategory".mysql_error());
// create table faq_programm_version
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_programm_version;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_programm_version");
$sql = "CREATE TABLE ".$tableprefix."_programm_version (";
$sql.= "entrynr int(10) unsigned NOT NULL auto_increment,";
$sql.= "programm int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.= "version varchar(20) NOT NULL DEFAULT '' ,";
$sql.= "PRIMARY KEY (entrynr));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_programm_version".mysql_error());
// create table faq_faq_ref
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_faq_ref;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_faq_ref");
$sql = "CREATE TABLE ".$tableprefix."_faq_ref (";
$sql.="srcfaqnr int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.="language varchar(5) NOT NULL DEFAULT '' ,";
$sql.="destfaqnr int(10) unsigned NOT NULL DEFAULT '0');";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_faq_ref".mysql_error());
// create table faq_category_ref
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_category_ref;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_category_ref");
$sql = "CREATE TABLE ".$tableprefix."_category_ref (";
$sql.="srccatnr int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.="language varchar(5) NOT NULL DEFAULT '' ,";
$sql.="destcatnr int(10) unsigned NOT NULL DEFAULT '0');";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_category_ref".mysql_error());
// create table faq_keywords
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_keywords;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_keywords");
$sql = "CREATE TABLE ".$tableprefix."_keywords (";
$sql.="keywordnr int(10) unsigned NOT NULL auto_increment,";
$sql.="keyword varchar(250) NOT NULL DEFAULT '' ,";
$sql.="PRIMARY KEY (keywordnr));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_keywords".mysql_error());
// create table faq_kb_os
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_kb_os;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_kb_os");
$sql = "CREATE TABLE ".$tableprefix."_kb_os (";
$sql.="articlenr int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.="osnr int(10) unsigned NOT NULL DEFAULT '0');";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_kb_os".mysql_error());
// create table faq_kb_keywords
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_kb_keywords;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_kb_keywords");
$sql = "CREATE TABLE ".$tableprefix."_kb_keywords (";
$sql.="articlenr int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.="keywordnr int(10) unsigned NOT NULL DEFAULT '0');";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_kb_keywords".mysql_error());
// create table faq_kb_cat
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_kb_cat;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_kb_cat");
$sql = "CREATE TABLE ".$tableprefix."_kb_cat (";
$sql.="catnr int(10) unsigned NOT NULL auto_increment,";
$sql.="catname varchar(240) NOT NULL DEFAULT '' ,";
$sql.="heading varchar(250) NOT NULL DEFAULT '' ,";
$sql.="programm int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.="displaypos int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.="PRIMARY KEY (catnr));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_kb_cat".mysql_error());
// create table faq_kb_articles
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_kb_articles;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_kb_articles");
$sql = "CREATE TABLE ".$tableprefix."_kb_articles (";
$sql .="articlenr int(10) unsigned NOT NULL auto_increment,";
$sql .="programm int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql .="heading varchar(240) NOT NULL DEFAULT '' ,";
$sql .="article text NOT NULL DEFAULT '' ,";
$sql .="ratingcount int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql .="rating int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql .="editor varchar(80) NOT NULL DEFAULT '' ,";
$sql .="lastedited date NOT NULL DEFAULT '0000-00-00' ,";
$sql .="category int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql .="displaypos int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql .="subcategory int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql .="views int(10) unsigned NOT NULL default '0',";
$sql .="PRIMARY KEY (articlenr));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_kb_articles".mysql_error());
// create table faq_faq_keywords
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_faq_keywords;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_faq_keywords");
$sql = "CREATE TABLE ".$tableprefix."_faq_keywords (";
$sql.="faqnr int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql.="keywordnr int(10) unsigned NOT NULL DEFAULT '0');";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_faq_keywords".mysql_error());
// create table faq_hostcache
if(!table_exists($hcprefix."_hostcache"))
{
	$sql = "CREATE TABLE /*!32300 IF NOT EXISTS*/ ".$hcprefix."_hostcache (";
	$sql .="ipadr varchar(16) NOT NULL DEFAULT '0' ,";
	$sql .="hostname varchar(240) NOT NULL DEFAULT '' ,";
	$sql .="UNIQUE ipadr (ipadr));";
	if(!$result = mysql_query($sql, $db))
		die("Unable to create table ".$hcprefix."_hostcache".mysql_error());
}
// create table faq_texts
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
	die("Unable to create table ".$tableprefix."_texts".mysql_error());
// create table faq_failed_notify
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_failed_notify;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_failed_notify");
$sql = "CREATE TABLE ".$tableprefix."_failed_notify (";
$sql .="usernr int(10) unsigned DEFAULT '0');";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_failed_notify".mysql_error());
// create table faq_questions
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_questions;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_questions");
$sql = "CREATE TABLE ".$tableprefix."_questions (";
$sql .="questionnr int(10) unsigned NOT NULL auto_increment,";
$sql .="prognr int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql .="osname varchar(180) ,";
$sql .="versionnr varchar(10) ,";
$sql .="email varchar(140) NOT NULL DEFAULT '' ,";
$sql .="question text NOT NULL DEFAULT '' ,";
$sql .="enterdate datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ,";
$sql .="faqref int(10) unsigned DEFAULT '0' ,";
$sql .="posterip varchar(20) NOT NULL DEFAULT '' ,";
$sql .="answerdate datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ,";
$sql .="answerauthor int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql .="answer text NOT NULL DEFAULT '' ,";
$sql .="language varchar(5) NOT NULL DEFAULT 'de' ,";
$sql .="questionref int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql .="publish tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql .="state int(2) unsigned NOT NULL default '0',";
$sql .="PRIMARY KEY (questionnr));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_questions".mysql_error());
// create table faq_prog_os
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_prog_os;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_prog_os");
$sql = "CREATE TABLE ".$tableprefix."_prog_os (";
$sql .="osnr int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql .="prognr int(10) unsigned NOT NULL DEFAULT '0');";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_prog_os".mysql_error());
// create table faq_os
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_os;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_os");
$sql = "CREATE TABLE ".$tableprefix."_os (";
$sql .="osnr int(10) unsigned NOT NULL auto_increment,";
$sql .="osname varchar(180) NOT NULL DEFAULT '' ,";
$sql .="PRIMARY KEY (osnr));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_os".mysql_error());
// create table faq_failed_logins
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
	die("Unable to create table ".$tableprefix."_failed_logins".mysql_error());
// create table faq_freemailer
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_comments;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_comments");
$sql = "CREATE TABLE ".$tableprefix."_comments (";
$sql .="commentnr int(10) unsigned NOT NULL auto_increment,";
$sql .="faqnr int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql .="email varchar(140) NOT NULL DEFAULT '' ,";
$sql .="comment text NOT NULL DEFAULT '' ,";
$sql .="ipadr varchar(16) NOT NULL DEFAULT '0.0.0.0' ,";
$sql .="postdate datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ,";
$sql .="rating int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql .="ratingcount int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql .="views int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql .="PRIMARY KEY (commentnr));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_comments".mysql_error());
// create table faq_freemailer
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_freemailer;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_freemailer");
$sql = "CREATE TABLE ".$tableprefix."_freemailer (";
$sql .="entrynr int(10) unsigned NOT NULL auto_increment,";
$sql .="address varchar(100) NOT NULL DEFAULT '' ,";
$sql .="PRIMARY KEY (entrynr));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_freemailer".mysql_error());
// create table faq_banlist
if(!table_exists($banprefix."_banlist"))
{
	$sql = "CREATE TABLE /*!32300 IF NOT EXISTS*/ ".$banprefix."_banlist (";
	$sql .="bannr int(10) unsigned NOT NULL auto_increment,";
	$sql .="ipadr varchar(16) NOT NULL DEFAULT '0.0.0.0' ,";
	$sql .="subnetmask varchar(16) NOT NULL DEFAULT '0.0.0.0' ,";
	$sql .="reason text NOT NULL DEFAULT '' ,";
	$sql .="PRIMARY KEY (bannr));";
	if(!$result = mysql_query($sql, $db))
		die("Unable to create table ".$banprefix."_banlist".mysql_error());
}
// create table faq_category
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_category;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_category");
$sql = "CREATE TABLE ".$tableprefix."_category (";
$sql .="catnr int(10) unsigned NOT NULL auto_increment,";
$sql .="categoryname varchar(240) NOT NULL DEFAULT '' ,";
$sql .="numfaqs int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql .="programm int(10) unsigned DEFAULT '0' ,";
$sql .="displaypos int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql .="PRIMARY KEY (catnr));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_category".mysql_error());
// create table faq_programm_admins
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_programm_admins;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_programm_admins");
$sql = "CREATE TABLE ".$tableprefix."_programm_admins (";
$sql .="prognr int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql .="usernr int(10) unsigned NOT NULL DEFAULT '0');";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_programm_admins".mysql_error());
// create table faq_category_admins
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_category_admins;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_category_admins");
$sql = "CREATE TABLE ".$tableprefix."_category_admins (";
$sql .="catnr int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql .="usernr int(10) unsigned NOT NULL DEFAULT '0');";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_category_admins".mysql_error());
// create table faq_data
$sql ="DROP TABLE IF EXISTS ".$tableprefix."_data;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_data");
$sql ="CREATE TABLE ".$tableprefix."_data (";
$sql .="faqnr int(10) unsigned NOT NULL auto_increment,";
$sql .="heading varchar(240) NOT NULL default '' ,";
$sql .="category int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql .="questiontext text ,";
$sql .="editor varchar(80) NOT NULL DEFAULT 'unknown' ,";
$sql .="editdate date NOT NULL DEFAULT '0000-00-00' ,";
$sql .="answertext text ,";
$sql .="views int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql .="ratingcount int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql .="rating int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql .="displaypos int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql .="subcategory int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql .="linkedfaq int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql .="PRIMARY KEY (faqnr),";
$sql .="INDEX category (category));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_question".mysql_error());
// create table faq_programm
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_programm;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_programm");
$sql = "CREATE TABLE ".$tableprefix."_programm (";
$sql .="prognr int(10) unsigned NOT NULL auto_increment,";
$sql .="programmname varchar(240) NOT NULL DEFAULT '' ,";
$sql .="numcats int(10) unsigned DEFAULT '0' ,";
$sql .="progid varchar(10) NOT NULL DEFAULT '' ,";
$sql .="language varchar(5) NOT NULL DEFAULT 'de' ,";
$sql .="newsgroup varchar(250) NOT NULL DEFAULT '' ,";
$sql .="newssubject varchar(80) ,";
$sql .="nntpserver varchar(80) ,";
$sql .="newsdomain varchar(80) ,";
$sql .="description text NOT NULL DEFAULT '' ,";
$sql .="displaypos int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql .="lastmailed date NOT NULL default '0000-00-00',";
$sql .="htmlmailtype tinyint(1) unsigned NOT NULL default '0',";
$sql .="subscriptionavail tinyint(1) unsigned NOT NULL default '1',";
$sql .="PRIMARY KEY (prognr));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_programm".mysql_error());
// create table faq_admins
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_admins;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_admins");
$sql = "CREATE TABLE ".$tableprefix."_admins (";
$sql .="usernr tinyint(3) unsigned NOT NULL auto_increment,";
$sql .="username varchar(80) NOT NULL DEFAULT '' ,";
$sql .="password varchar(40) binary NOT NULL DEFAULT '' ,";
$sql .="email varchar(80) NOT NULL DEFAULT '' ,";
$sql .="rights int(2) unsigned NOT NULL DEFAULT '0' ,";
$sql .="lastlogin datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ,";
$sql .="lockpw tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql .="signature text ,";
$sql .="autopin int(10) unsigned NOT NULL DEFAULT '0' ,";
$sql .="language varchar(20) NOT NULL DEFAULT 'en' ,";
$sql .="hideemail tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql .="lockentry tinyint(1) unsigned NOT NULL DEFAULT '0' ,";
$sql .="PRIMARY KEY (usernr));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_admins".mysql_error());
// create table faq_iplog
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
	die("Unable to create table ".$tableprefix."_iplog".mysql_error());
// create table faq_layout
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_layout;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_layout");
$sql = "CREATE TABLE ".$tableprefix."_layout (";
$sql.="layoutnr tinyint(3) unsigned NOT NULL auto_increment,";
$sql.="headingbg varchar(8) NOT NULL default '',";
$sql.="bgcolor1 varchar(8) NOT NULL default '',";
$sql.="bgcolor2 varchar(8) NOT NULL default '',";
$sql.="pagebg varchar(8) NOT NULL default '',";
$sql.="tablewidth varchar(10) NOT NULL default '',";
$sql.="fontface varchar(80) NOT NULL default '',";
$sql.="fontsize1 varchar(10) NOT NULL default '',";
$sql.="fontsize2 varchar(10) NOT NULL default '',";
$sql.="fontsize3 varchar(10) NOT NULL default '',";
$sql.="fontcolor varchar(8) NOT NULL default '',";
$sql.="fontsize4 varchar(10) NOT NULL default '',";
$sql.="bgcolor3 varchar(8) NOT NULL default '',";
$sql.="stylesheet varchar(80) NOT NULL default '',";
$sql.="headingfontcolor varchar(8) NOT NULL default '',";
$sql.="subheadingfontcolor varchar(8) NOT NULL default '',";
$sql.="linkcolor varchar(8) NOT NULL default '',";
$sql.="vlinkcolor varchar(8) NOT NULL default '',";
$sql.="alinkcolor varchar(8) NOT NULL default '',";
$sql.="groupfontcolor varchar(8) NOT NULL default '',";
$sql.="tabledescfontcolor varchar(8) NOT NULL default '',";
$sql.="fontsize5 varchar(10) NOT NULL default '',";
$sql.="dateformat varchar(10) NOT NULL default '',";
$sql.="newtime int(4) unsigned NOT NULL default '0',";
$sql.="newpic varchar(80) NOT NULL default '',";
$sql.="searchpic varchar(80) NOT NULL default '',";
$sql.="printpic varchar(80) NOT NULL default '',";
$sql.="backpic varchar(80) NOT NULL default '',";
$sql.="listpic varchar(80) NOT NULL default '',";
$sql.="pageheader text NOT NULL,";
$sql.="pagefooter text NOT NULL,";
$sql.="usecustomheader tinyint(1) unsigned NOT NULL default '1',";
$sql.="usecustomfooter tinyint(1) unsigned NOT NULL default '1',";
$sql.="emailpic varchar(80) NOT NULL default '',";
$sql.="questionpic varchar(80) NOT NULL default '',";
$sql.="usercommentpic varchar(80) NOT NULL default '',";
$sql.="allowlists tinyint(1) unsigned NOT NULL default '1',";
$sql.="allowsearch tinyint(1) unsigned NOT NULL default '1',";
$sql.="searchcomments tinyint(1) unsigned NOT NULL default '1',";
$sql.="searchquestions tinyint(1) unsigned NOT NULL default '1',";
$sql.="showsummary tinyint(1) unsigned NOT NULL default '1',";
$sql.="summarylength tinyint(2) unsigned NOT NULL default '40',";
$sql.="progrestrict tinyint(1) unsigned NOT NULL default '1',";
$sql.="footerfile varchar(240) NOT NULL default '',";
$sql.="headerfile varchar(240) NOT NULL default '',";
$sql.="printheader tinyint(1) unsigned NOT NULL default '0',";
$sql.="printfooter tinyint(1) unsigned NOT NULL default '0',";
$sql.="mincommentlength int(3) unsigned NOT NULL default '0',";
$sql.="minquestionlength int(3) unsigned NOT NULL default '0',";
$sql.="proginfopic varchar(80) NOT NULL default '',";
$sql.="proginfowidth int(4) unsigned NOT NULL default '0',";
$sql.="proginfoheight int(4) unsigned NOT NULL default '0',";
$sql.="textareawidth int(4) unsigned NOT NULL default '0',";
$sql.="textareaheight int(4) unsigned NOT NULL default '0',";
$sql.="proginfoleft int(4) unsigned NOT NULL default '0',";
$sql.="proginfotop int(4) unsigned NOT NULL default '0',";
$sql.="helpwindowwidth int(4) unsigned NOT NULL default '0',";
$sql.="helpwindowheight int(4) unsigned NOT NULL default '0',";
$sql.="helpwindowleft int(4) unsigned NOT NULL default '0',";
$sql.="helpwindowtop int(4) unsigned NOT NULL default '0',";
$sql.="helppic varchar(80) NOT NULL default '',";
$sql.="closepic varchar(80) NOT NULL default '',";
$sql.="kbmode varchar(20) NOT NULL default 'wizard',";
$sql.="defsearchmethod tinyint(1) unsigned NOT NULL default '0',";
$sql.="enablekeywordsearch tinyint(1) unsigned NOT NULL default '1',";
$sql.="enablelanguageselector tinyint(1) unsigned NOT NULL default '0',";
$sql.="faqsortmethod tinyint(1) unsigned NOT NULL default '0',";
$sql.="kbsortmethod tinyint(1) unsigned NOT NULL default '0',";
$sql.="copyrightpos tinyint(1) unsigned NOT NULL default '0',";
$sql.="copyrightbgcolor varchar(8) NOT NULL default '',";
$sql.="ascheader text NOT NULL,";
$sql.="subheadingbgcolor varchar(8) NOT NULL default '',";
$sql.="actionbgcolor varchar(8) NOT NULL default '',";
$sql.="headerfilepos tinyint(1) unsigned NOT NULL default '0',";
$sql.="footerfilepos tinyint(1) unsigned NOT NULL default '0',";
$sql.="newinfobgcolor varchar(8) NOT NULL default '',";
$sql.="useascheader tinyint(1) unsigned NOT NULL default '0',";
$sql.="asclinelength int(4) unsigned NOT NULL default '0',";
$sql.="ascforcewrap tinyint(1) unsigned NOT NULL default '0',";
$sql.="addbodytags varchar(240) NOT NULL default '',";
$sql.="asclistmimetype tinyint(1) unsigned NOT NULL default '0',";
$sql.="asclistcharset varchar(80) NOT NULL default 'iso-8859-1',";
$sql.="keywordsearchmode tinyint(1) unsigned NOT NULL default '0',";
$sql.="questionrequireos tinyint(1) unsigned NOT NULL default '1',";
$sql.="questionrequireversion tinyint(1) unsigned NOT NULL default '0',";
$sql.="newfaqdisplaymethod tinyint(1) unsigned NOT NULL default '0',";
$sql.="enablefaqnewdisplay tinyint(1) unsigned NOT NULL default '0',";
$sql.="faqnewdisplaybgcolor varchar(8) NOT NULL default '',";
$sql.="faqnewdisplayfontcolor varchar(8) NOT NULL default '',";
$sql.="listallfaqmethod tinyint(1) unsigned NOT NULL default '0',";
$sql.="enableshortcutbar tinyint(1) unsigned NOT NULL default '0',";
$sql.="enablejumpboxes tinyint(1) unsigned NOT NULL default '0',";
$sql.="subcatbgcolor varchar(8) NOT NULL default '',";
$sql.="subcatfontcolor varchar(8) NOT NULL default '',";
$sql.="displayrelated tinyint(1) unsigned NOT NULL default '0',";
$sql.="htmllisttype int(2) unsigned NOT NULL default '0',";
$sql.="pagetoppic varchar(80) NOT NULL default 'gfx/top.gif',";
$sql.="attachpic varchar(80) NOT NULL default 'gfx/attach.gif',";
$sql.="summaryintotallist tinyint(1) unsigned NOT NULL default '0',";
$sql.="summarychars tinyint(2) unsigned NOT NULL default '40',";
$sql.="maxentries int(10) unsigned NOT NULL default '0',";
$sql.="activcellcolor varchar(8) NOT NULL default '#ffff72',";
$sql.="ratingspublic tinyint(1) unsigned NOT NULL default '0',";
$sql.="ratingcommentpublic tinyint(1) unsigned NOT NULL default '0',";
$sql.="hovercells tinyint(1) unsigned NOT NULL default '0',";
$sql.="qesautopub tinyint(1) unsigned NOT NULL default '0',";
$sql.="ns4style varchar(240) NOT NULL default 'faq_ns4.css',";
$sql.="ns6style varchar(240) NOT NULL default 'faq_ns6.css',";
$sql.="operastyle varchar(240) NOT NULL default 'faq_opera.css',";
$sql.="geckostyle varchar(240) NOT NULL default 'faq_gecko.css',";
$sql.="konquerorstyle varchar(240) NOT NULL default 'faq_konqueror.css',";
$sql.="ascheaderfile varchar(240) NOT NULL default '',";
$sql.="ascheaderfilepos tinyint(1) unsigned NOT NULL default '0',";
$sql.="numlatest tinyint(4) unsigned NOT NULL default '10',";
$sql.="colorscrollbars tinyint(1) unsigned NOT NULL default '1',";
$sql.="sbfacecolor varchar(7) NOT NULL default '#94AAD6',";
$sql.="sbhighlightcolor varchar(7) NOT NULL default '#AFEEEE',";
$sql.="sbshadowcolor varchar(7) NOT NULL default '#ADD8E6',";
$sql.="sb3dlightcolor varchar(7) NOT NULL default '#1E90FF',";
$sql.="sbarrowcolor varchar(7) NOT NULL default '#0000ff',";
$sql.="sbtrackcolor varchar(7) NOT NULL default '#E0FFFF',";
$sql.="sbdarkshadowcolor varchar(7) NOT NULL default '#4682B4',";
$sql.="pagebgpic varchar(240) NOT NULL default '',";
$sql.="tabledescfontsize varchar(20) NOT NULL default '10pt',";
$sql.="langselectfontsize varchar(20) NOT NULL default '10pt',";
$sql.="faqnewfontsize varchar(20) NOT NULL default '10pt',";
$sql.="jumpboxfontsize varchar(20) NOT NULL default '10pt',";
$sql.="actionlinefontsize varchar(20) NOT NULL default '9pt',";
$sql.="newinfofontsize varchar(20) NOT NULL default '9pt',";
$sql.="newinfofontcolor varchar(7) NOT NULL default '#000000',";
$sql.="shortbarfontsize varchar(20) NOT NULL default '9pt',";
$sql.="jumpboxsorting tinyint(4) unsigned NOT NULL default '0',";
$sql.="disableasclist tinyint(1) unsigned NOT NULL default '0',";
$sql.="disablehtmlemail tinyint(1) unsigned NOT NULL default '1',";
$sql.="contentcopy varchar(250) NOT NULL default '',";
$sql.="pagebgattach varchar(80) NOT NULL default 'scroll',";
$sql.="pagebgrepeat varchar(80) NOT NULL default 'repeat',";
$sql.="pagebgposition varchar(80) NOT NULL default 'top',";
$sql.="subcatfontsize varchar(10) NOT NULL default '12pt',";
$sql.="questionshorting int(10) unsigned NOT NULL default '20',";
$sql.="defmailsig text NOT NULL,";
$sql.="cc_font varchar(80) NOT NULL default '\"Times New Roman\", Times, serif',";
$sql.="cc_fontsize varchar(20) NOT NULL default '12pt',";
$sql.="cc_fontcolor varchar(7) NOT NULL default '',";
$sql.="subscriptionpic varchar(240) NOT NULL default 'gfx/subscribe.gif',";
$sql.="irow_bgcolor varchar(7) NOT NULL default '#ADD8E6',";
$sql.="irow_fontcolor varchar(7) NOT NULL default '#808100',";
$sql.="irow_fontsize varchar(20) NOT NULL default '12pt',";
$sql.="pagenavfontcolor varchar(7) NOT NULL default '#000000',";
$sql.="pagenavfontsize varchar(20) NOT NULL default '9pt',";
$sql.="subcatfontstyle tinyint(2) unsigned NOT NULL default '1',";
$sql.="linkoptions tinyint(4) unsigned NOT NULL default '0',";
$sql.="listoptions tinyint(4) unsigned NOT NULL default '0',";
$sql.="clist_linkcolor varchar(7) NOT NULL default '#000000',";
$sql.="clist_vlinkcolor varchar(7) NOT NULL default '#000000',";
$sql.="clist_alinkcolor varchar(7) NOT NULL default '#000000',";
$sql.="shownextprev tinyint(1) unsigned NOT NULL default '0',";
$sql.="nextprevmode tinyint(1) unsigned NOT NULL default '0',";
$sql.="kbsearchoptions int(10) unsigned NOT NULL default '0',";
$sql.="searchoptions int(4) unsigned NOT NULL default '0',";
$sql.="displayoptions int(4) unsigned NOT NULL default '0',";
$sql.="nextpagepic varchar(80) NOT NULL default 'gfx/fwd.gif',";
$sql.="prevpagepic varchar(80) NOT NULL default 'gfx/prev.gif',";
$sql.="firstpagepic varchar(80) NOT NULL default 'gfx/first.gif',";
$sql.="lastpagepic varchar(80) NOT NULL default 'gfx/last.gif',";
$sql.="usepagenavicons tinyint(1) unsigned NOT NULL default '1',";
$sql.="displayvotesinline tinyint(1) unsigned NOT NULL default '0',";
$sql.="tablealign int(4) unsigned NOT NULL default '2',";
$sql.="votesinlinedisplaymode tinyint(1) unsigned NOT NULL default '0',";
$sql.="navbarwidth int(10) unsigned NOT NULL default '250',";
$sql.="navsync tinyint(1) unsigned NOT NULL default '1',";
$sql.="navpic_progclosed varchar(80) NOT NULL default 'gfx/book_closed.gif',";
$sql.="navpic_progopen varchar(80) NOT NULL default 'gfx/book_open.gif',";
$sql.="navpic_proglocked varchar(80) NOT NULL default 'gfx/book_locked.gif',";
$sql.="navpic_faq varchar(80) NOT NULL default 'gfx/document.gif',";
$sql.="navpic_question varchar(80) NOT NULL default 'gfx/question2.gif',";
$sql.="navpic_catclosed varchar(80) NOT NULL default 'gfx/cat_closed.gif',";
$sql.="navpic_catopen varchar(80) NOT NULL default 'gfx/cat_open.gif',";
$sql.="navpic_catlocked varchar(80) NOT NULL default 'gfx/cat_locked.gif',";
$sql.="navpic_subcatopen varchar(80) NOT NULL default 'gfx/subcat_open.gif',";
$sql.="navpic_subcatclosed varchar(80) NOT NULL default 'gfx/subcat_closed.gif',";
$sql.="navpic_subcatlocked varchar(80) NOT NULL default 'gfx/subcat_locked.gif',";
$sql.="searchhighlightcolor varchar(7) NOT NULL default '#ff0000',";
$sql.="searchhighlight tinyint(1) unsigned NOT NULL default '0',";
$sql.="navpic_kbarticle varchar(80) NOT NULL default 'gfx/document.gif',";
$sql.="navpic_kbwizard varchar(80) NOT NULL default 'gfx/wizard.gif',";
$sql.="kbnavoptions int(10) unsigned NOT NULL default '0',";
$sql.="navpic_kbsearch varchar(80) NOT NULL default 'gfx/search.gif',";
$sql.="navtreepos tinyint(1) unsigned NOT NULL default '0',";
$sql.="search_inputfieldwidth int(4) unsigned NOT NULL default '60',";
$sql.="faqnavoptions int(10) unsigned NOT NULL default '0',";
$sql.="id varchar(10) NOT NULL default '',";
$sql.="deflayout tinyint(1) unsigned NOT NULL default '0',";
$sql.="tablespacing int(4) unsigned NOT NULL default '1',";
$sql.="tablepadding int(4) unsigned NOT NULL default '1',";
$sql.="extdateformat varchar(20) NOT NULL default 'Y-m-d H:i:s',";
$sql.="srchtoolpic varchar(80) NOT NULL default 'gfx/srchtool.gif',";
$sql.="donltrans tinyint(4) unsigned NOT NULL default '0',";
$sql.="displayattachinfo tinyint(1) unsigned NOT NULL default '1',";
$sql.="PRIMARY KEY  (layoutnr));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_layout".mysql_error());
// create table faq_session
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
$sql .="INDEX sessid (sessid),";
$sql .="INDEX starttime (starttime),";
$sql .="INDEX remoteip (remoteip));";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_session".mysql_error());
// create table faq_misc
$sql = "DROP TABLE IF EXISTS ".$tableprefix."_misc;";
if(!$result = mysql_query($sql, $db))
	die("Unable to drop existing table ".$tableprefix."_misc");
$sql = "CREATE TABLE ".$tableprefix."_misc (";
$sql .="shutdown tinyint(3) unsigned NOT NULL DEFAULT '0' ,";
$sql .="shutdowntext text);";
if(!$result = mysql_query($sql, $db))
	die("Unable to create table ".$tableprefix."_misc".mysql_error());
// insert adminuser
$admin_pw=md5($admin_pw1);
$admin_user=addslashes(strtolower($admin_user));
$sql = "INSERT INTO ".$tableprefix."_admins (";
$sql .="username, password, rights, language";
if(isset($admin_email))
	$sql .=", email";
$sql .=")";
$sql .="VALUES ('$admin_user', '$admin_pw', 4, '$inputlang'";
if(isset($admin_email))
	$sql .=", '$admin_email'";
$sql .=");";
if(!$result = mysql_query($sql, $db))
	die("Unable to create adminuser");
echo "Creating default settings...";
flush();
$sql="INSERT INTO ".$tableprefix."_layout (headingbg, bgcolor1, bgcolor2, pagebg, tablewidth, fontface, fontsize1, fontsize2, fontsize3, fontcolor, fontsize4, bgcolor3, stylesheet, headingfontcolor, subheadingfontcolor, linkcolor, vlinkcolor, alinkcolor, groupfontcolor, tabledescfontcolor, fontsize5, dateformat, newtime, newpic, searchpic, printpic, backpic, listpic, pageheader, pagefooter, usecustomheader, usecustomfooter, emailpic, questionpic, usercommentpic, allowlists, allowsearch, searchcomments, searchquestions, showsummary, summarylength, progrestrict, footerfile, headerfile, printheader, printfooter, mincommentlength, minquestionlength, proginfopic, proginfowidth, proginfoheight, proginfoleft, proginfotop, textareawidth, textareaheight, helpwindowwidth, helpwindowheight, helpwindowleft, helpwindowtop, helppic, closepic, kbmode, defsearchmethod, enablekeywordsearch, enablelanguageselector, faqsortmethod, kbsortmethod, copyrightpos, copyrightbgcolor, ascheader, subheadingbgcolor, actionbgcolor, headerfilepos, footerfilepos, newinfobgcolor, useascheader, asclinelength, ascforcewrap, addbodytags, asclistmimetype, asclistcharset, keywordsearchmode, questionrequireos, questionrequireversion, newfaqdisplaymethod, enablefaqnewdisplay, faqnewdisplaybgcolor, faqnewdisplayfontcolor, listallfaqmethod, enableshortcutbar, enablejumpboxes, subcatbgcolor, subcatfontcolor, displayrelated, htmllisttype, pagetoppic, attachpic, summaryintotallist, summarychars, maxentries, activcellcolor, ratingspublic, ratingcommentpublic, hovercells, qesautopub, ns4style, ns6style, operastyle, geckostyle, konquerorstyle, ascheaderfile, ascheaderfilepos, numlatest, tabledescfontsize, langselectfontsize, faqnewfontsize, jumpboxfontsize, actionlinefontsize, newinfofontsize, newinfofontcolor, shortbarfontsize, jumpboxsorting, disableasclist, disablehtmlemail, colorscrollbars, sbfacecolor, sbhighlightcolor, sbshadowcolor, sb3dlightcolor, sbarrowcolor, sbtrackcolor, sbdarkshadowcolor, pagebgpic, contentcopy, pagebgattach, pagebgrepeat, pagebgposition, subcatfontsize, questionshorting, defmailsig, cc_font, cc_fontsize, cc_fontcolor, subscriptionpic, irow_bgcolor, irow_fontcolor, irow_fontsize, pagenavfontcolor, pagenavfontsize, subcatfontstyle, linkoptions, listoptions, clist_linkcolor, clist_vlinkcolor, clist_alinkcolor, shownextprev, nextprevmode, kbsearchoptions, searchoptions, displayoptions, nextpagepic, prevpagepic, firstpagepic, lastpagepic, usepagenavicons, displayvotesinline, tablealign, votesinlinedisplaymode, navbarwidth, navsync, navpic_progclosed, navpic_progopen, navpic_proglocked, navpic_faq, navpic_question, navpic_catclosed, navpic_catopen, navpic_catlocked, navpic_subcatopen, navpic_subcatclosed, navpic_subcatlocked, searchhighlightcolor, searchhighlight, navpic_kbarticle, navpic_kbwizard, kbnavoptions, navpic_kbsearch, navtreepos, search_inputfieldwidth, faqnavoptions, id, deflayout, tablespacing, tablepadding, srchtoolpic, donltrans, displayattachinfo) ";
$sql.="VALUES ('#94AAD6', '#000000', '#CCCCCC', '#C0C0C0', '98%', 'Verdana, Geneva, Arial, Helvetica, sans-serif', '10pt', '12pt', '14pt', '#000000', '8pt', '#C0C0C0', 'faq.css', '#FFF0C0', '#F0F0F0', '#CC0000', '#CC0000', '#0000CC', '#2C2C2C', '#2C2C2C', '12pt', 'd.m.Y', 7, 'gfx/new.gif', 'gfx/search.gif', 'gfx/print.gif', 'gfx/back.gif', 'gfx/list.gif', '', '', 0, 0, 'gfx/email.gif', 'gfx/question.gif', 'gfx/comment.gif', 1, 1, 1, 1, 1, 40, 1, '', '', 0, 0, 0, 0, 'gfx/info.gif', 380, 420, 30, 6, 20, 20, 380, 420, 20, 20, 'gfx/help.gif', 'gfx/close.gif', 'wizard', 0, 1, 0, 0, 0, 0, '#C0C0C0', '', '#94AAD6', '#94AAD6', 0, 0, '#94AAD6', 0, 0, 0, '', 0, 'iso-8859-1', 0, 1, 1, 1, 0, '#94AAD6', '#000000', 0, 0, 0, '#e0e0e0', '#000000', 0, 1, 'gfx/top.gif', 'gfx/attach.gif', 0, 40, 0, '#ffff72', 1, 1, 1, 0, 'faq_ns4.css', 'faq_ns6.css', 'faq_opera.css', 'faq_gecko.css', 'faq_konqueror.css', '', 0, 10, '10pt', '10pt', '10pt', '10pt', '9pt', '9pt', '#000000', '9pt', 0, 0, 1, 1, '#94AAD6', '#AFEEEE', '#ADD8E6', '#1E90FF', '#0000ff', '#E0FFFF', '#4682B4', '', '', 'scroll', 'repeat', 'top', '12pt', 20, '', '\"Times New Roman\", Times, serif', '12pt', '', 'gfx/subscribe.gif', '#ADD8E6', '#808100', '12pt', '#000000', '9pt', 1, 0, 0, '#000000', '#000000', '#000000', 1, 1, 0, 0, 0, 'gfx/fwd.gif', 'gfx/prev.gif', 'gfx/first.gif', 'gfx/last.gif', 1, 0, 2, 0, 250, 1, 'gfx/book_closed.gif', 'gfx/book_open.gif', 'gfx/book_locked.gif', 'gfx/document.gif', 'gfx/question2.gif', 'gfx/cat_closed.gif', 'gfx/cat_open.gif', 'gfx/cat_locked.gif', 'gfx/subcat_open.gif', 'gfx/subcat_closed.gif', 'gfx/subcat_locked.gif', '#ff0000', 0, 'gfx/document.gif', 'gfx/wizard.gif', 0, 'gfx/search.gif', 0, 60, 0, 'def', 1, 1, 1, 'gfx/srchtool.gif', 0, 1);";
if(!$result = mysql_query($sql, $db))
	die("Unable to create layout".mysql_error());
$sql="INSERT INTO ".$tableprefix."_settings (settingnr, showproglist, watchlogins, allowemail, urlautoencode, enablespcode, nofreemailer, allowquestions, faqemail, allowusercomments, newcommentnotify, enablefailednotify, loginlimit, timezone, enablehostresolve, ratecomments, usemenubar, admtextareasrows, admtextareascols, enablekbrating, userquestionanswermode, userquestionanswermail, userquestionautopublish, faqengine_hostname, ratingcomment, nosunotify, blockoldbrowser, bbccolorbar, disablehtmlemail, admstorefaqfilters, admhideunassigned, admdelconfirm, zlibavail, msendlimit, subscriptionavail, admedoptions, allsendcompressed, uq_allownoemail, blockleacher, defmailsig, faqlistshortcuts, faqlimitrelated, displayrating, admdateformat, showtimezone, showcurrtime, maxconfirmtime, dosearchlog, logdateformat, uqscmail, extfailedlog, usebwlist) ";
$sql.="VALUES (1, 1, 1, 1, 1, 1, 0, 1, 'faqenine@foo.bar', 1, 1, 0, 0, 0, 1, 1, 1, 10, 50, 1, 0, 0, 0, 'localhost', 1, 0, 1, 1, 1, 1, 0, 0, 0, 30, 1, 0, 0, 0, 0, '', 0, 1, 1, 'Y-m-d H:i:s', 1, 1, 2, 0, 'Y-m-d H:i:s', 0, 0, 0);";
if(!$result = mysql_query($sql, $db))
	die("Unable to create settings".mysql_error());
require('./fill_mimetypes.php');
fill_mimetypes($tableprefix,$db);
if(isset($importfreemailer))
{
	require('./fill_freemailer.php');
	fill_freemailer($tableprefix,$db);
}
if(isset($importleacher))
{
	require('./fill_leacher.php');
	fill_freemailer($leacherprefix,$db);
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
?>
<table align="center" width="80%">
<tr><td align="center" colspan="3"><b>Adminuser</b></td></tr>
<form action="<?php echo $act_script_url?>" method="post">
<tr><td align="right">Username:</td><td align="center" width="1%">*</td>
<td><input type="text" name="admin_user" size="40" maxlength="80" value="<?php echo $admin_user?>"></td></tr>
<tr><td align="right">E-Mail:</td><td align="center" width="1%">&nbsp;</td>
<td><input type="text" name="admin_email" size="40" maxlength="80" value="<?php echo $admin_email?>"></td></tr>
<tr><td align="right">Password:</td><td align="center" width="1%">*</td>
<td><input type="password" name="admin_pw1" size="40" maxlength="40"></td></tr>
<tr><td align="right">retype password:</td><td align="center" width="1%">*</td>
<td><input type="password" name="admin_pw2" size="40" maxlength="40"></td></tr>
<tr><td align="right">Language:</td><td align="center" width="1%">*</td>
<td><?php echo language_select($admin_lang,"inputlang","./language/")?></td></tr>
<tr><td colspan="2">&nbsp;</td><td align="left"><input type="checkbox" name="importfreemailer" value="1"> import predefined freemailer</td></TR>
<tr><td colspan="2">&nbsp;</td><td align="left"><input type="checkbox" name="importleacher" value="1"> import predefined offline browser</td></TR>
<input type="hidden" name="mode" value="do">
<tr><td align="center" colspan="3"><input type="submit" name="submit" value="submit"></td></tr>
</form>
</table>
</body></html>
<?php
function language_select($default, $name="language", $dirname="language/")
{
	$dir = opendir($dirname);
	$lang_select = "<SELECT NAME=\"$name\">\n";
	while ($file = readdir($dir))
	{
		if (ereg("^lang_", $file))
		{
			$file = str_replace("lang_", "", $file);
			$file = str_replace(".php", "", $file);
			$file == $default ? $selected = " SELECTED" : $selected = "";
			$lang_select .= "  <OPTION value=\"$file\"$selected>$file\n";
		}
	}
	$lang_select .= "</SELECT>\n";
	closedir($dir);
	return $lang_select;
}
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