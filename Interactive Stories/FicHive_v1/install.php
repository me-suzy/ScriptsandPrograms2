<html>
<head>
<title>FicHive Installation</title>
<link rel='stylesheet' href='skins/1/style.css'>
</head>
<body><center>
<table border='0' width='700'>
<tr><td><img src='http://www.alter-idem.com/scripts/fichive/logo.gif'></td></tr>
<tr><td class='fframe'>FanHive Installation</td></tr>
<tr><td class='chapter'>
<form method='post' action='install.php'>
<?php

if( $_POST['finish'] ) {

	include( "config.inc.php" );

	require_once( "sources/db.class.php");

	$db = new db();
	$db->debug = FALSE;
	$db->connect() or die("Cannot connect to database");

	$user['upass'] = crypt( $_POST['upass'], $conf['salt'] );
	$user['ustart'] = date( "Y-m-d H:i:s" );
	$user['uip'] = getenv("REMOTE_ADDR");

	$db->insert( "users" , array('uname'=>$_POST['uname'], 'ugroup'=>4, 'ulang'=>1, 'uskin'=>1, 'upass'=>$user['upass'], 
	'uemail'=>$_POST['uemail'], 'ustart'=>$user['ustart'], 'uip'=>$user['uip']) ) or die("Cannot create admin account: " .$db->getError());	

?>

	<table border='0' width='100%'>	
	<tr><td class='fframe' colspan='2'>Installation Successful</td></tr>
	<tr><td colspan='2' class='chapter'>Your FicHive installation can now be accessed <a href='index.php'>here</a>. 
	It is important that you delete this installation file at the earliest opportunity.</td></tr>
	</table>

<?

} elseif( $_POST['three'] ) {

	include( "config.inc.php" );

	require_once( "sources/db.class.php");

	$db = new db();
	$db->debug = FALSE;
	$db->connect() or die("Cannot connect to database");

	$fa = DBPRE;

	$sql[] = "CREATE TABLE `{$fa}categories` (
	`cid` int(11) NOT NULL auto_increment,
	`cname` varchar(255) NOT NULL default '',
	`cdesc` varchar(255) NOT NULL default '',
	`cparent` int(11) NOT NULL default '0',
	`cactive` tinyint(1) NOT NULL default '0',
	`corder` int(11) NOT NULL default '0',
	`cpost` varchar(255) NOT NULL default '',
	`cread` varchar(255) NOT NULL default '',
	`cmod` varchar(255) NOT NULL default '',
	`capp` tinyint(1) NOT NULL default '0',
	`cimg` varchar(255) NOT NULL default '',
	`cchars` text NOT NULL,
	PRIMARY KEY  (`cid`),
	KEY `cparent` (`cparent`),
	KEY `cid` (`cid`)
	) TYPE=MyISAM;";

	
	$sql[] = "CREATE TABLE `{$fa}chapters` (
	`chid` int(11) NOT NULL auto_increment,
	`chsid` int(11) NOT NULL default '0',
	`chtitle` varchar(255) NOT NULL default '',
	`chchapter` longtext NOT NULL,
	`chapp` tinyint(1) NOT NULL default '0',
	`chpdate` datetime NOT NULL default '0000-00-00 00:00:00',
	`chorder` int(11) NOT NULL default '0',
	`chwords` int(11) NOT NULL default '0',
	PRIMARY KEY  (`chid`),
	KEY `chsid` (`chsid`),
	KEY `chapp` (`chapp`),
	KEY `chorder` (`chorder`),
	FULLTEXT KEY `chchapter` (`chchapter`)
	) TYPE=MyISAM;";

	$sql[] = "CREATE TABLE `{$fa}favorites` (
	`fuid` int(11) NOT NULL default '0',
	`ffavs` varchar(255) NOT NULL default '',
	KEY `fuid` (`fuid`)
	) TYPE=MyISAM;";


	$sql[] = "CREATE TABLE `{$fa}groups` (
	`gid` int(11) NOT NULL auto_increment,
	`gname` varchar(255) NOT NULL default '',
	`gcolor` varchar(255) NOT NULL default '',
	PRIMARY KEY  (`gid`),
	KEY `gid` (`gid`)
	) TYPE=MyISAM;";


	$sql[] = "CREATE TABLE `{$fa}news` (
	`nid` int(11) NOT NULL auto_increment,
	`nuid` int(11) NOT NULL default '0',
	`ndate` datetime NOT NULL default '0000-00-00 00:00:00',
	`nnews` text NOT NULL,
	`ncomment` text NOT NULL,
	PRIMARY KEY  (`nid`),
	KEY `nuid` (`nuid`)
	) TYPE=MyISAM;";


	$sql[] = "CREATE TABLE `{$fa}reviews` (
	`rid` int(11) NOT NULL auto_increment,
	`rsid` int(11) NOT NULL default '0',
	`ruid` int(11) NOT NULL default '0',
	`rdate` datetime NOT NULL default '0000-00-00 00:00:00',
	`rreview` text NOT NULL,
	`rname` varchar(255) NOT NULL default '',
	PRIMARY KEY  (`rid`),
	KEY `rsid` (`rsid`),
	KEY `ruid` (`ruid`)
	) TYPE=MyISAM;";

	$sql[] = "CREATE TABLE `{$fa}stories` (
	`sid` int(11) NOT NULL auto_increment,
	`scid` int(11) NOT NULL default '0',
	`suid` int(11) NOT NULL default '0',
	`swip` tinyint(1) NOT NULL default '0',
	`stitle` varchar(255) NOT NULL default '',
	`sdesc` varchar(255) NOT NULL default '',
	`srating` tinyint(1) NOT NULL default '0',
	`shits` int(11) NOT NULL default '0',
	`sgenre1` tinyint(3) NOT NULL default '0',
	`sgenre2` tinyint(3) NOT NULL default '0',
	`scharacter` tinyint(3) NOT NULL default '0',
	PRIMARY KEY  (`sid`),
	KEY `sid` (`sid`),
	KEY `scid` (`scid`),
	KEY `suid` (`suid`),
	KEY `sgenre1` (`sgenre1`),
	KEY `sgenre2` (`sgenre2`),
	KEY `scharacter` (`scharacter`),
	FULLTEXT KEY `stitle` (`stitle`),
	FULLTEXT KEY `sdesc` (`sdesc`)
	) TYPE=MyISAM;";

	$sql[] = "CREATE TABLE `{$fa}users` (
	`uid` int(11) NOT NULL auto_increment,
	`uname` varchar(255) NOT NULL default '',
	`upass` varchar(255) NOT NULL default '',
	`uemail` varchar(255) NOT NULL default '',
	`ugroup` tinyint(3) NOT NULL default '0',
	`ulang` tinyint(3) NOT NULL default '0',
	`uskin` tinyint(3) NOT NULL default '0',
	`uavatar` varchar(255) NOT NULL default '',
	`ubio` text NOT NULL,
	`ustart` datetime NOT NULL default '0000-00-00 00:00:00',
	`uip` varchar(255) NOT NULL default '',
	`ufavorites` varchar(255) NOT NULL default '',
	PRIMARY KEY  (`uid`),
	KEY `uid` (`uid`),
	KEY `ugroup` (`ugroup`)
	) TYPE=MyISAM;";

	foreach( $sql as $is )  $db->_query($is) or die($db->getError());

	$db->insert( "groups" , array('gname'=>"Guest", 'gcolor'=>"#008000") );
	$db->insert( "groups" , array('gname'=>"Author", 'gcolor'=>"#000000") );
	$db->insert( "groups" , array('gname'=>"Moderator", 'gcolor'=>"#C00000") );
	$db->insert( "groups" , array('gname'=>"Admin", 'gcolor'=>"#808080") );
	$db->insert( "groups" , array('gname'=>"Banned", 'gcolor'=>"#C0C0C0") );

	$db->insert( "users" , array('uname'=>"Guest", 'ugroup'=>1, 'ulang'=>1, 'uskin'=>1) );	

?>

	<table border='0' width='100%'>	
	<tr><td class='fframe' colspan='2'>Part 4: Create Admin Account</td></tr>
	<tr><td colspan='2' class='chapter'>Tables created and populated. Please create your admin account.</td></tr>
	<tr><td class='fframe'>PenName</td><td><input type='text' name='uname'></td></tr>
	<tr><td class='fframe'>Password</td><td><input type='text' name='upass'></td></tr>
	<tr><td class='fframe'>Email</td><td><input type='text' name='uemail'></td></tr>
	<tr><td colspan='2' class='frame'><input type='submit' name='finish' value='Next'></td></tr>
	</table>
	

<?

} elseif( $_POST['two'] ) {

	define("DBSERVER" , $_POST['db']['dbserver'] );
	define("DBNAME" , $_POST['db']['dbname']);
	define("DBUSER" , $_POST['db']['dbuser']);
	define("DBPASS" , $_POST['db']['dbpass']);
	define("DBPRE" , $_POST['db']['dbpre']);

	require_once( "sources/db.class.php");

	$db = new db();
	$db->debug = FALSE;
	$db->connect() or die("Cannot connect to database, please press back and ensure your details are correct.");

	if( !is_writable( $_POST['con']['path']."config.inc.php" ) ) 

		die("Cannot write to config.inc.php file. Please ensure it is in the correct place and CHMOD 0777");

	$write = fopen( $_POST['con']['path']."config.inc.php" , "w" );

	$string.= "define(\"DBSERVER\" , \"{$_POST['db']['dbserver']}\");\r\n";
	$string.= "define(\"DBNAME\" , \"{$_POST['db']['dbname']}\");\r\n";
	$string.= "define(\"DBUSER\" , \"{$_POST['db']['dbuser']}\");\r\n";
	$string.= "define(\"DBPASS\" , \"{$_POST['db']['dbpass']}\");\r\n";
	$string.= "define(\"DBPRE\" , \"{$_POST['db']['dbpre']}\");\r\n";

	$string.= "$"."conf['title'] = \"{$_POST['con']['title']}\";\r\n";
	$string.= "$"."conf['path'] = \"{$_POST['con']['path']}\";\r\n";
	$string.= "$"."conf['url'] = \"{$_POST['con']['url']}\";\r\n";
	$string.= "$"."conf['email_bot'] = \"bot@donotreply.com\";\r\n";
	$string.= "$"."conf['open'] = \"1\";\r\n";
	$string.= "$"."conf['time'] = \"0\";\r\n";
	$string.= "$"."conf['time_format'] = \"jS F Y\";\r\n";
	$string.= "$"."conf['time_format_news'] = \"jS F Y H:i\";\r\n";
	$string.= "$"."conf['allowed_html'] = \"<b><i><u>\";\r\n";
	$string.= "$"."conf['salt'] = \"{$_POST['con']['salt']}\";\r\n";
	$string.= "$"."conf['mage'] = \"0\";\r\n";
	$string.= "$"."conf['penname_length'] = \"50\";\r\n";
	$string.= "$"."conf['password_length'] = \"50\";\r\n";
	$string.= "$"."conf['avatar_width'] = \"100\";\r\n";
	$string.= "$"."conf['avatar_height'] = \"100\";\r\n";
	$string.= "$"."conf['fiction_words'] = \"4000\";\r\n";
	$string.= "$"."conf['fiction_upload'] = \"1\";\r\n";
	$string.= "$"."conf['fiction_types'] = \"txt htm html\";\r\n";
	$string.= "$"."conf['default_skin'] = \"1\";\r\n";
	$string.= "$"."conf['default_lang'] = \"1\";\r\n";
	$string.= "$"."conf['default_group'] = \"2\";\r\n";
	$string.= "$"."conf['cookie'] = \"{$_POST['con']['cookie']}\";\r\n";
	$string.= "$"."conf['cookie_path'] = \"\";\r\n";
	$string.= "$"."conf['cookie_domain'] = \"\";\r\n";
	$string.= "$"."conf['sep_title'] = \" &rsaquo; \";\r\n";
	$string.= "$"."conf['sep_crumb'] = \" &rsaquo; \";\r\n";
	$string.= "$"."conf['sep_navig'] = \" &curren; \";\r\n";
	$string.= "$"."conf['sep_misc'] = \"&raquo;\";\r\n";
	$string.= "$"."conf['cols'] = \"3\";\r\n";
	$string.= "$"."conf['ppage'] = \"25\";\r\n";
	$string.= "$"."conf['latest_limit'] = \"50\";\r\n";
	$string.= "$"."conf['search_limit'] = \"50\";\r\n";
	$string.= "$"."conf['search_time'] = \"60\";\r\n";
	$string.= "$"."conf['mailer_updatealert'] = \"1\";\r\n";
	$string.= "$"."conf['mailer_reviewalert'] = \"1\";\r\n";
	$string = "<?php\r\n{$string}?>";
		
	fwrite($write, $string);

	fclose($write);

?>

	<table border='0' width='100%'>	
	<tr><td class='fframe' colspan='2'>Part 3: Database Population</td></tr>
	<tr><td colspan='2' class='chapter'>Configuration complete. Click next to create and populate the database tables.</td></tr>
	<tr><td colspan='2' class='frame'><input type='submit' name='three' value='Next'></td></tr>
	</table>
	

<?

} else {

	$path = $_SERVER['DOCUMENT_ROOT'] . str_replace( "install.php" , "",  $_SERVER['REQUEST_URI'] );
	
	$url = "http://{$_SERVER['SERVER_NAME']}" . str_replace( "install.php" , "",  $_SERVER['REQUEST_URI'] );
?>
	<table border='0' width='100%'>	
	<tr><td class='fframe' colspan='2'>Part 1: Database Configuration</td></tr>
	<tr><td class='fframe'>Server</td><td><input type='text' name='db[dbserver]' value='localhost'></td></tr>
	<tr><td class='fframe'>Name</td><td><input type='text' name='db[dbname]'></td></tr>
	<tr><td class='fframe'>Username</td><td><input type='text' name='db[dbuser]'></td></tr>
	<tr><td class='fframe'>Password</td><td><input type='text' name='db[dbpass]'></td></tr>
	<tr><td class='fframe'>Table Prefix</td><td><input type='text' name='db[dbpre]' value='fh_'></td></tr>
	<tr><td class='fframe' colspan='2'>Part 2: Site Configuration</td></tr>
	<tr><td class='fframe'>Site Title</td><td><input type='text' name='con[title]' value='FicHive'></td></tr>
	<tr><td class='fframe'>Url</td><td><input type='text' name='con[url]' value='<?=$url?>'></td></tr>
	<tr><td class='fframe'>Path</td><td><input type='text' name='con[path]' value='<?=$path?>'></td></tr>
	<tr><td class='fframe'>Password Salt</td><td><input type='text' name='con[salt]' value='anythingsecret'></td></tr>
	<tr><td class='fframe'>Cookie Prefix</td><td><input type='text' name='con[cookie]' value='fh_'></td></tr>
	<tr><td colspan='2' class='frame'><input type='submit' name='two' value='Next'></td></tr>
	</table>
<?
}
?>
</form>
</td></tr></table>
</center>
</body>
</html>
