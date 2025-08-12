<html>
<head>
<title>FicHive Upgrade 0.9 -> 1.0</title>
<link rel='stylesheet' href='skins/1/style.css'>
</head>
<body><center>
<table border='0' width='700'>
<tr><td><img src='http://www.alter-idem.com/scripts/fichive/logo.gif'></td></tr>
<tr><td class='fframe'>FanHive Upgrade 0.9 -> 1.0</td></tr>
<tr><td class='chapter'>
<form method='post' action='upgrade0910.php'>
<?php

include( "config.inc.php" );

if( $_POST ) {

	require_once( "sources/db.class.php");

	$db = new db();
	$db->debug = FALSE;
	$db->connect() or die("Cannot connect to database");

	$fa = DBPRE;

	$sql[] = "CREATE TABLE `{$fa}news` (
	`nid` int(11) NOT NULL auto_increment,
	`nuid` int(11) NOT NULL default '0',
	`ndate` datetime NOT NULL default '0000-00-00 00:00:00',
	`nnews` text NOT NULL,
	`ncomment` text NOT NULL,
	PRIMARY KEY  (`nid`),
	KEY `nuid` (`nuid`)
	) TYPE=MyISAM;";

	$sql[] = "ALTER TABLE `{$fa}categories` ADD `cchars` TEXT NOT NULL;";
	$sql[] = "ALTER TABLE `{$fa}stories` ADD `sgenre1` TINYINT( 3 ) NOT NULL;";
	$sql[] = "ALTER TABLE `{$fa}stories` ADD `sgenre2` TINYINT( 3 ) NOT NULL;";
	$sql[] = "ALTER TABLE `{$fa}stories` ADD `scharacter` TINYINT( 3 ) NOT NULL;";

	foreach( $sql as $is )  $db->_query($is) or die($db->getError());

?>

	<table border='0' width='100%'>	
	<tr><td class='fframe' colspan='2'>Database Upgrade Complete</td></tr>
	<tr><td colspan='2' class='chapter'>Your database has been upgraded. You must now upload the following files:</td></tr>
	<tr><td colspan='2' class='frame'>

		<table border='0' width='100%'>
		<tr><td>index.php</td><td>&nbsp;</td></tr>
		<tr><td>js.js</td><td>&nbsp;</td></tr>
		<tr><td colspan='2' class='fframe'>&nbsp;</td></tr>
		<tr><td>language/1/adminpanel.lang.php</td><td>0777</td></tr>
		<tr><td>language/1/controlpanel.lang.php</td><td>0777</td></tr>
		<tr><td>language/1/fiction.lang.php</td><td>0777</td></tr>
		<tr><td>language/1/system.lang.php</td><td>0777</td></tr>
		<tr><td colspan='2' class='fframe'>&nbsp;</td></tr>
		<tr><td>language/1/mail/</td><td>0777</td></tr>
		<tr><td>language/1/mail/index.php</td><td>0777</td></tr>
		<tr><td>language/1/mail/registration.mail.php</td><td>0777</td></tr>
		<tr><td>language/1/mail/updatealert.mail.php</td><td>0777</td></tr>
		<tr><td>language/1/mail/reviewalert.mail.php</td><td>0777</td></tr>
		<tr><td>language/1/mail/forgottenpassword.mail.php</td><td>0777</td></tr>
		<tr><td colspan='2' class='fframe'>&nbsp;</td></tr>
		<tr><td>skins/1/layout.tmpl.php</td><td>0777</td></tr>
		<tr><td>skins/1/mainpage.tmpl.php</td><td>0777</td></tr>
		<tr><td>skins/1/mainpage_latest.tmpl.php</td><td>0777</td></tr>
		<tr><td>skins/1/mainpage_news.tmpl.php</td><td>0777</td></tr>
		<tr><td>skins/1/mainpage_stats.tmpl.php</td><td>0777</td></tr>
		<tr><td>skins/1/style.css</td><td>0777</td></tr>
		<tr><td colspan='2' class='fframe'>&nbsp;</td></tr>
		<tr><td>skins/1/admin/addcategory.tmpl.php</td><td>0777</td></tr>
		<tr><td>skins/1/admin/addlang.tmpl.php</td><td>0777</td></tr>
		<tr><td>skins/1/admin/addnews.tmpl.php</td><td>0777</td></tr>
		<tr><td>skins/1/admin/deletelang.tmpl.php</td><td>0777</td></tr>
		<tr><td>skins/1/admin/deletenews.tmpl.php</td><td>0777</td></tr>
		<tr><td>skins/1/admin/editcategory_2.tmpl.php</td><td>0777</td></tr>
		<tr><td>skins/1/admin/editlang_1.tmpl.php</td><td>0777</td></tr>
		<tr><td>skins/1/admin/editlang_2.tmpl.php</td><td>0777</td></tr>
		<tr><td>skins/1/admin/editlang_3.tmpl.php</td><td>0777</td></tr>
		<tr><td>skins/1/admin/editnews_1.tmpl.php</td><td>0777</td></tr>
		<tr><td>skins/1/admin/editnews_2.tmpl.php</td><td>0777</td></tr>
		<tr><td>skins/1/admin/generalsettings.tmpl.php</td><td>0777</td></tr>
		<tr><td>skins/1/admin/layout.tmpl.php</td><td>0777</td></tr>
		<tr><td colspan='2' class='fframe'>&nbsp;</td></tr>
		<tr><td>skins/1/controlpanel/addchapter.tmpl.php</td><td>0777</td></tr>
		<tr><td>skins/1/controlpanel/addstory.tmpl.php</td><td>0777</td></tr>
		<tr><td>skins/1/controlpanel/editchapter_3.tmpl.php</td><td>0777</td></tr>
		<tr><td>skins/1/controlpanel/editstory_2.tmpl.php</td><td>0777</td></tr>
		<tr><td>skins/1/controlpanel/login.tmpl.php</td><td>0777</td></tr>
		<tr><td colspan='2' class='fframe'>&nbsp;</td></tr>
		<tr><td>sources/adminpanel.class.php</td><td>&nbsp;</td></tr>
		<tr><td>sources/controlpanel.class.php</td><td>&nbsp;</td></tr>
		<tr><td>sources/dp.class.php</td><td>&nbsp;</td></tr>
		<tr><td>sources/fiction.class.php</td><td>&nbsp;</td></tr>
		</table>

	</td></tr>
	</table>
	

<?

} else {

?>

	<table border='0' width='100%'>	
	<tr><td class='fframe' colspan='2'>Part 1: Database</td></tr>
	<tr><td colspan='2' class='frame'><input type='submit' name='two' value='Update Database'></td></tr>
	</table>
<?
}
?>
</form>
</td></tr></table>
</center>
</body>
</html>
