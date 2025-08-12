<?
if(isset($_POST['auser']) && isset($_POST['apass'])) {
	setcookie('cncook',"$_POST[auser],$_POST[apass]",time()+3600,'/');
}


$tpath = substr($_SERVER['SCRIPT_FILENAME'],0,strrpos($_SERVER['SCRIPT_FILENAME'], "/")) . "/";

if(file_exists($tpath . "cn_dbdefs.php")) {
	// Include MySQL Definitions
	include_once("cn_dbdefs.php");
	// Make the default database connection
	$link = @mysql_connect($dbhost, $dbuser, $dbpass); 
	@mysql_select_db($dbname, $link);
	$q[sets] = @mysql_query("SELECT * FROM cn_config", $link);
	$set = @mysql_fetch_array($q[sets], MYSQL_ASSOC);
} else {
	// First-time install
}
?>

<html>

<head>
<title>CzarNews Installation</title>
<script type="text/javascript">
function openBox(cid) {
	document.getElementById(cid).style.display=(document.getElementById(cid).style.display!="block")? "block" : "none"
}
</script>
<style type="text/css">
<!--
BODY, TEXT, TD {
font-family: Arial, Verdana;
font-size: 12px;
font-weight: normal;
text-decoration: none;
color: #000000;
}
td.head, tr.head, th.head {
background-color: #E1E1E1;
}
td.content, tr.content, th.content {
background-color: #F9F9F9;
}
td.title {
font-family: Verdana, Arial;
font-size: 12px;
font-weight: bold;
text-decoration: none;
color: #000000;
height: 30px;
}
td.name {
font-family: Verdana, Arial;
font-size: 12px;
font-weight: normal;
text-decoration: none;
color: #000000;
width: 30%;
}
td.value {
font-family: Arial, Verdana;
font-size: 12px;
font-weight: normal;
text-decoration: none;
color: #000000;
width: 70%;
}
-->
</style>
</head>

<body bgcolor="#ffffff" text="#000000" link="#0000ff" vlink="#800080" alink="#ff0000">
<div align="center">
<?
$cnver_new = "1.14";
?>
<table width="450" border="1" cellspacing="0" cellpadding="0">
<tr><td>
<table width="450" border="0" cellspacing="0" cellpadding="2">
<tr class="head">
<td class="title">
&nbsp;CzarNews Installation
</td></tr>
</table>
</td></tr><tr>
<td class="content"><br>
<table width="400" border="0" cellspacing="0" cellpadding="1" align="center">
<tr><td class="name">
Version:
</td><td class="value">
<?=$cnver_new?>
</td></tr>
<tr><td class="name">
Updates:
</td><td class="value">
<a href="http://www.czaries.net/scripts/" target="_blank">http://www.czaries.net/scripts/</a>
</td></tr>
<tr><td class="name">
Contact:
</td><td class="value">
<a href="mailto:czaries@czaries.net" target="_blank">czaries@czaries.net</a>
</td></tr>
</table>
<br></td><form action="install.php" method="post"></tr>

<?
if($_POST['install'] == "true") { ?>
	<tr><td>
	<table width="450" border="0" cellspacing="0" cellpadding="2">
	<tr class="head">
	<td class="title">
	&nbsp;Installation Results
	</td></tr>
	</table>
	</td></tr><tr>
	<td class="content"><br>
	<blockquote>
	<? if($_POST['mysql_file'] == "on") { ?>
		<b>MySQL Connection Test</b><br>
		<?
		// Make the default database connection
		$link = mysql_connect($_POST[dbhost], $_POST[dbuser], $_POST[dbpass]) or die ("Unable to connect to MySQL server:<br>" . mysql_error()); 
		mysql_select_db($_POST[dbname], $link) or die ("Could Not Connect to Selected Database:<br>" . mysql_error());
		?>
		<font color=#008000>Connected Successfully</font><br><br>
		
		<b>Database Definitions File</b><br>
		<?
		$thefile = "cn_dbdefs.php"; 
		if(file_exists($thefile)){ $delold = @unlink($thefile); }
		if($RF = @fopen($thefile,"w")) {

		$output = "<?php
##########################################
### 
### CzarNews MySQL Definitions
### Made by: Czaries  [czaries@czaries.net]
### http://www.czaries.net/scripts/
### for more scripts and updates.
###
### Generated On: " . date("D M d, Y @ H:i") . "
##########################################


### Database Definitions ###
\$dbhost = \"$_POST[dbhost]\";
\$dbname = \"$_POST[dbname]\";
\$dbuser = \"$_POST[dbuser]\";
\$dbpass = \"$_POST[dbpass]\";
?>";

			fwrite($RF, "$output");
			fclose($RF);
			print "<font color=#008000>File created successfully</font><br><br>";
		} else {
			print "<font color=#ff0000>Could not create database definitions file.  Please check to see that this directory's CHMOD settings are 777, and try again.</font><br><br>";
			exit;
		}
	}
	?>
	<? if($_POST['mysql_tbl'] == "on") { ?>
	<b>Creating MySQL Tables</b><br>
	<?
	if($_POST['cn_cats'] == "on") {
		$r[cats] = "
		CREATE TABLE cn_cats (
		  id int(10) unsigned NOT NULL auto_increment,
		  name varchar(255) NOT NULL default '',
		  date int(10) NOT NULL default '0',
		  PRIMARY KEY  (id)
		)";
		$q[delcats] = mysql_query("DROP TABLE IF EXISTS cn_cats", $link);
		$q[cats] = mysql_query($r[cats], $link) or die ("<font color=#ff0000>Unable to create 'cn_cats' table:</font><br>" . mysql_error());
		print "<font color=#008000>Table 'cn_cats' created</font><br>";
	}
	if($_POST['cn_coms'] == "on") {
		$r[coms] = "
		CREATE TABLE cn_comments (
		  id int(10) unsigned NOT NULL auto_increment,
		  news_id bigint(20) NOT NULL default '0',
		  name varchar(255) NOT NULL default '',
		  email varchar(255) NOT NULL default '',
		  comment text NOT NULL,
		  date int(10) unsigned NOT NULL default '0',
		  ip varchar(15) NOT NULL default '',
		  KEY id (id)
		)";
		$q[delcoms] = mysql_query("DROP TABLE IF EXISTS cn_comments", $link);
		$q[coms] = mysql_query($r[coms], $link) or die ("<font color=#ff0000>Unable to create 'cn_comments' table:</font><br>" . mysql_error());
		print "<font color=#008000>Table 'cn_comments' created</font><br>";
	}
	if($_POST['cn_config'] == "on") {
		### Define a few variables... ###
		$servtime = (date("Z")/3600);
		// Reverse search of strrchr.
		function strrrchr($haystack,$needle) {
			// Returns everything before $needle (inclusive).
			return substr($haystack,0,strrpos($haystack,$needle)+1);
		}
		
		$rootlen = strlen($_SERVER['DOCUMENT_ROOT']);
		$cnurl = "http://" . $_SERVER['HTTP_HOST'];
		$cnurl .= substr($_SERVER['SCRIPT_FILENAME'],$rootlen,strrpos($_SERVER['SCRIPT_FILENAME'],DIRECTORY_SEPARATOR)+1);
		
		// Set default news output code
		$defoutput = "<p><b>{subject}</b> Posted on {date} by {author}<br />{news}<br />{source}{comments}</p>";
		$setsource = "<small>Source: <a href=\"{surl}\" target=\"_blank\">{sname}</a></small><br /><br />";
		$setauthor = "<a href=\"mailto:{aemail}\">{aname}</a>";
		$setcoms = "View/Post Comment ({cnum})";
		### End ###
		
		$r[config] = "
		CREATE TABLE cn_config (
		  sitename varchar(255) NOT NULL default '',
		  siteurl varchar(255) NOT NULL default '',
		  scripturl varchar(255) NOT NULL default '',
		  newslimit int(3) NOT NULL default '0',
		  timezone int(5) NOT NULL default '0',
		  dateform varchar(255) NOT NULL default '',
		  output text NOT NULL,
		  source varchar(255) NOT NULL default '',
		  version varchar(255) NOT NULL default '',
		  words enum('on','off') NOT NULL default 'on',
		  comments enum('on','off') NOT NULL default 'on',
		  search enum('on','off') NOT NULL default 'on',
		  pages enum('on','off') NOT NULL default 'on',
		  catbox enum('on','off') NOT NULL default 'on',
		  images enum('on','off') NOT NULL default 'on',
		  img_thumbw varchar(255) NOT NULL default '150',
		  img_thumbh varchar(255) NOT NULL default '150',
		  img_dir varchar(255) NOT NULL default 'uploads/',
		  img_maxsize int(10) UNSIGNED NOT NULL default '512000',
		  author varchar(255) NOT NULL default '',
		  coms_text varchar(255) NOT NULL default ''
		)";
		$q[delconfig] = mysql_query("DROP TABLE IF EXISTS cn_config", $link);
		$q[config] = mysql_query($r[config], $link) or die ("<font color=#ff0000>Unable to create 'cn_config' table:</font><br>" . mysql_error());
		$q[prep] = mysql_query("INSERT INTO cn_config (sitename, siteurl, scripturl, newslimit, timezone, dateform, output, source, author, coms_text, version, words, comments, search, pages, catbox) VALUES ('', 'http://$_SERVER[HTTP_HOST]/', '$cnurl', '15', '$servtime', 'M d, Y', '$defoutput', '$setsource', '$setauthor', '$setcoms', '$cnver_new', 'on', 'on', 'on', 'on', 'on')", $link) or die ("Could not prepare configuration settings:<br>" . mysql_error());
		print "<font color=#008000>Table 'cn_config' created</font><br>";
	}
	if($_POST['cn_images'] == "on") {
		// Add table 'cn_images' 
		$r[imgs] = "
		 CREATE TABLE `cn_images` (
		`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`author` INT(10) UNSIGNED NOT NULL,
		`type` VARCHAR(255) NOT NULL,
		`name` VARCHAR(255) NOT NULL,
		`text` VARCHAR(255) NOT NULL,
		`filename` VARCHAR(255) NOT NULL,
		`thumbname` VARCHAR(255) NOT NULL,
		`date` INT(10) UNSIGNED NOT NULL
		)";
		$q[delimgs] = mysql_query("DROP TABLE IF EXISTS cn_images", $link);
		$q[imgs] = mysql_query($r[imgs], $link) or die ("<font color=#ff0000>Unable to create 'cn_images' table:</font><br>" . mysql_error());
		print "<font color=#008000>Table 'cn_images' created</font><br>";	
	}
	if($_POST['cn_news'] == "on") {
		$r[news] = "
		CREATE TABLE cn_news (
		  id int(10) unsigned NOT NULL auto_increment,
		  author int(10) unsigned NOT NULL default '0',
		  cat int(10) unsigned NOT NULL default '0',
		  subject varchar(255) NOT NULL default '',
		  content longtext NOT NULL,
		  content2 longtext NOT NULL,
		  sumstory enum('on','off') NOT NULL default 'off',
		  date int(10) unsigned NOT NULL default '0',
		  source varchar(255) NOT NULL default '',
		  sourceurl varchar(255) NOT NULL default '',
		  PRIMARY KEY  (id)
		)";
		$q[delnews] = mysql_query("DROP TABLE IF EXISTS cn_news", $link);
		$q[news] = mysql_query($r[news], $link) or die ("<font color=#ff0000>Unable to create 'cn_news' table:</font><br>" . mysql_error());
		print "<font color=#008000>Table 'cn_news' created</font><br>";
	}
	if($_POST['cn_users'] == "on") {
		$r[users] = "
		CREATE TABLE cn_users (
		  id int(10) unsigned NOT NULL auto_increment,
		  user varchar(255) NOT NULL default '',
		  pass varchar(255) NOT NULL default '',
		  email varchar(255) NOT NULL default '',
		  created int(10) unsigned NOT NULL default '0',
		  last_login int(10) unsigned NOT NULL default '0',
		  cookie tinyint(4) NOT NULL default '0',
		  categories varchar(255) NOT NULL default '0',
		  admin enum('on','off') NOT NULL default 'off',
		  news enum('on','off') NOT NULL default 'off',
		  users enum('on','off') NOT NULL default 'off',
		  cats enum('on','off') NOT NULL default 'off',
		  config enum('on','off') NOT NULL default 'off',
		  words enum('on','off') NOT NULL default 'off',
		  images enum('on','off') NOT NULL default 'off',
		  PRIMARY KEY  (id),
		  UNIQUE KEY user (user)
		)";
		$q[delusers] = mysql_query("DROP TABLE IF EXISTS cn_users", $link);
		$q[users] = mysql_query($r[users], $link) or die ("<font color=#ff0000>Unable to create 'cn_users' table:</font><br>" . mysql_error());
		print "<font color=#008000>Table 'cn_users' created</font><br>";
	}
	if($_POST['cn_words'] == "on") {
		$r[words] = "
		CREATE TABLE cn_words (
		  id int(10) unsigned NOT NULL auto_increment,
		  word varchar(255) NOT NULL default '',
		  type varchar(255) NOT NULL default '',
		  replaced longtext NOT NULL,
		  PRIMARY KEY  (id)
		)";
		$q[delwords] = mysql_query("DROP TABLE IF EXISTS cn_words", $link);
		$q[words] = mysql_query($r[words], $link) or die ("<font color=#ff0000>Unable to create 'cn_words' table:</font><br>" . mysql_error());
		print "<font color=#008000>Table 'cn_words' created</font><br>";
	}
	if($_POST['admin_user'] == "on") {
		?>
		<br><b>Creating Admin User</b><br>
		<?
		$time = strtotime("now");
		$q[adduser] = mysql_query ("INSERT INTO cn_users (id, user, pass, email, created, last_login, cookie, categories, admin, news, users, cats, config, words) VALUES ('', '$_POST[auser]', '$_POST[apass]', '$_POST[aemail]', '$time', '$time', '1', 'all', 'on', 'on', 'on', 'on', 'on', 'on')", $link) or die ("<font color=#ff0000>Could not create admin user:</font><br>" . mysql_error());
		print "<font color=#008000>Admin user '$_POST[auser]' created successfully</font><br><br>";
	}
	
	?>
	Everything has been setup correctly, and you may now access and post news.  Please delete the file 'install.php' from the server, and CHOMD this directory back to where it was, or at least down to 755 (you can do this through any FTP client).
	</blockquote>
	<br></td></tr>
	
	<tr><td>
	<table width="450" border="0" cellspacing="0" cellpadding="2">
	<tr class="head">
	<td class="title">
	&nbsp;Everything Okay?
	</td><td align="right">
	<input type="button" value="Open CzarNews" onClick="javascript:location.href='cn_webconfig.php'">
	</td></tr>
	</table>
	</td></tr>

<?
	}
} elseif($_POST['install'] == "full" || $_POST['install'] == "trouble") {
	?>
	
	<tr><td>
	<table width="450" border="0" cellspacing="0" cellpadding="2">
	<tr class="head">
	<td class="title">
	&nbsp;MySQL Configuration File
	</td></tr>
	</table>
	</td></tr><tr>
	<td class="content"><br>
	<?  if($_POST['install'] == "trouble") { ?>
		<blockquote>
		<input type="checkbox" name="mysql_file" id="mysql_file" onClick="openBox(1);"> <label for="mysql_file">Create MySQL configuration file</label>
		</blockquote>
	<? } else { ?>
		<input type="hidden" name="mysql_file" value="on">
	<? } ?>
	<div id="1" style="display: <? if($_POST['install'] == "trouble") { print "none"; } else { print "block"; } ?>">
	<blockquote>
	<font color=#ff0000>CHOMD THIS DIRECTORY 777 FOR INSTALLATION</font><br />
	<small>(You can do this through any FTP client)</small><br /><br />
	This will not create a user or database for you; these must already be made.  These variables are just so the script will know what to use when connecting to the database.<br><br>
	Don't forget to CONFIGURE your script correctly!
	</blockquote>
	<table width="400" border="0" cellspacing="0" cellpadding="1" align="center">
	<tr><td class="name">
	Hostname
	</td><td class="value">
	<input type="text" name="dbhost" size="25" class="input" value="localhost">
	</td></tr>
	<tr><td class="name">
	Username
	</td><td class="value">
	<input type="text" name="dbuser" size="25" class="input" value="">
	</td></tr>
	<tr><td class="name">
	Password
	</td><td class="value">
	<input type="text" name="dbpass" size="25" class="input" value="">
	</td></tr>
	<tr><td class="name">
	Database
	</td><td class="value">
	<input type="text" name="dbname" size="25" class="input" value="">
	</td></tr>
	</table>
	<br>
	</div>
	</td></tr>
	
	<tr><td>
	<table width="450" border="0" cellspacing="0" cellpadding="2">
	<tr class="head">
	<td class="title">
	&nbsp;MySQL Table Creation
	</td></tr>
	</table>
	</td></tr><tr>
	<td class="content"><br>
	<?  if($_POST['install'] == "trouble") { ?>
		<blockquote>
		<input type="checkbox" name="mysql_tbl" id="mysql_tbl" onClick="openBox(2);"> <label for="mysql_tbl">Create MySQL Tables</label>
		</blockquote>
	<? } else { ?>
		<input type="hidden" name="mysql_tbl" value="on">
	<? } ?>
	<div id="2" style="display: <? if($_POST['install'] == "trouble") { print "none"; } else { print "block"; } ?>">
	<blockquote>
	<font color=#ff0000>Leave ALL boxes checked for first-time installation</font><br><br>
	For troubleshooting, you can come back to this script, and re-create the tables individually if you ever have a problem in the future.
	</blockquote>
	<table width="400" border="0" cellspacing="0" cellpadding="1" align="center">
	<tr><td>
	<input type="checkbox" name="cn_cats" id="cats" CHECKED> <label for="cats">cn_cats - Categories Table</label><br>
	<input type="checkbox" name="cn_coms" id="coms" CHECKED> <label for="coms">cn_comments - Comments Table</label><br>
	<input type="checkbox" name="cn_config" id="config" CHECKED> <label for="config">cn_config - News Settings Table</label><br>
	<input type="checkbox" name="cn_images" id="images" CHECKED> <label for="images">cn_images - Images Table</label><br>
	<input type="checkbox" name="cn_news" id="news" CHECKED> <label for="news">cn_news - News Table</label><br>
	<input type="checkbox" name="cn_users" id="users" CHECKED> <label for="users">cn_users - Users Table</label><br>
	<input type="checkbox" name="cn_words" id="words" CHECKED> <label for="words">cn_words - Keywords Table</label><br>
	</td></tr>
	</table>
	<br>
	</div></td></tr>
	
	<tr><td>
	<table width="450" border="0" cellspacing="0" cellpadding="2">
	<tr class="head">
	<td class="title">
	&nbsp;CzarNews Admin User
	</td></tr>
	</table>
	</td></tr><tr>
	<td class="content"><br>
	<?  if($_POST['install'] == "trouble") { ?>
		<blockquote>
		<input type="checkbox" name="admin_user" id="admin_user" onClick="openBox(3);"> <label for="admin_user">Create CzarNews admin user</label>
		</blockquote>
	<? } else { ?>
		<input type="hidden" name="admin_user" value="on">
	<? } ?>
	<div id="3" style="display: <? if($_POST['install']== "trouble") { print "none"; } else { print "block"; } ?>">
	<blockquote>
	This user will have total control over everything in this script - this is intended to be the webmaster of the website you are installing it on.
	</blockquote>
	<table width="400" border="0" cellspacing="0" cellpadding="1" align="center">
	<tr><td class="name">
	Admin Username
	</td><td class="value">
	<input type="text" name="auser" size="25" class="input" value="">
	</td></tr>
	<tr><td class="name">
	Admin Password
	</td><td class="value">
	<input type="text" name="apass" size="25" class="input" value="">
	</td></tr>
	<tr><td class="name">
	Admin Email
	</td><td class="value">
	<input type="text" name="aemail" size="25" class="input" value="">
	</td></tr>
	</table>
	<br>
	</div>
	</td></tr>
	
	<tr><td>
	<table width="450" border="0" cellspacing="0" cellpadding="2">
	<tr class="head">
	<td class="title">
	&nbsp;Done?
	</td><td align="right">
	<input type="hidden" name="install" value="true">
	<input type="submit" value="Install CzarNews">
	</td></tr>
	</table>
	</td></tr>
	
	<?
} elseif($_POST['install'] == "upg") {
	if($_POST['go'] == "true") {
		?>
		<tr><td>
		<table width="450" border="0" cellspacing="0" cellpadding="2">
		<tr class="head">
		<td class="title">
		&nbsp;Upgrading...
		</td></tr>
		</table>
		</td></tr>
		
		<tr><td class="content">
		<blockquote><br>
		<?
		if(file_exists("cn_dbdefs.php")) {
			include("cn_dbdefs.php");
		} else {
			die ("<font color=#ff0000>Could not include required file 'cn_dbdefs.php'.  Please run troubleshooting install and create this file.</font>");
		}
		?>
		<b>MySQL Connection Test</b><br>
		<?
		// Make the default database connection
		$link = mysql_connect($dbhost, $dbuser, $dbpass) or die ("Unable to connect to MySQL server:<br>" . mysql_error()); 
		mysql_select_db($dbname, $link) or die ("Could Not Connect to Selected Database:<br>" . mysql_error());
		?>
		<font color=#008000>Connected Successfully</font><br><br>
		
		<?
		// Upgrades for v1.12
		if("1.12" > $set[version]) {
			?>
			<b>Updating Table Structures</b><br>
			<?
			// Add table 'cn_comments' 
			$r[coms] = "
			CREATE TABLE `cn_comments` (
			  `id` bigint(20) NOT NULL default '0',
			  `news_id` bigint(20) NOT NULL default '0',
			  `name` varchar(255) NOT NULL default '',
			  `email` varchar(255) NOT NULL default '',
			  `comment` text NOT NULL,
			  `date` varchar(10) NOT NULL default '',
			  `ip` varchar(15) NOT NULL default ''
			)";
				$q[delcoms] = mysql_query("DROP TABLE IF EXISTS cn_comments", $link);
				$q[coms] = mysql_query($r[coms], $link) or die ("<font color=#ff0000>Unable to create 'cn_comments' table:</font><br>" . mysql_error());
				print "<font color=#008000>Table 'cn_comments' created</font><br>";
			
			// Add table 'cn_words' 
			$r[words] = "
			CREATE TABLE `cn_words` (
			  `id` bigint(20) NOT NULL auto_increment,
			  `word` varchar(255) NOT NULL default '',
			  `type` varchar(255) NOT NULL default '',
			  `replaced` longtext NOT NULL,
			  PRIMARY KEY  (`id`)
			)";
				$q[delwords] = mysql_query("DROP TABLE IF EXISTS cn_words", $link);
				$q[words] = mysql_query($r[words], $link) or die ("<font color=#ff0000>Unable to create 'cn_words' table:</font><br>" . mysql_error());
				print "<font color=#008000>Table 'cn_words' created</font><br>";
			
			### Existing table alterations 
			// Users table changes 
			$ru[] = mysql_query("ALTER TABLE `cn_users` ADD `words` ENUM('on', 'off') NOT NULL", $link) or $errs .= "<p><font color=#ff0000>Unable to alter table:</font><br>" . mysql_error() . "</p>";
			$ru[] = mysql_query("ALTER TABLE `cn_users` ADD UNIQUE (`user`)", $link) or $errs .= "<font color=#ff0000>Unable to alter table:</font><br>" . mysql_error();
			// Config table changes 
			$ru[] = mysql_query("ALTER TABLE `cn_config` ADD `source` VARCHAR( 255 ) NOT NULL", $link) or $errs .= "<p><font color=#ff0000>Unable to alter table:</font><br>" . mysql_error() . "</p>";
			$ru[] = mysql_query("ALTER TABLE `cn_config` ADD `author` VARCHAR( 255 ) NOT NULL", $link) or $errs .= "<p><font color=#ff0000>Unable to alter table:</font><br>" . mysql_error() . "</p>";
			$ru[] = mysql_query("ALTER TABLE `cn_config` ADD `coms_text` VARCHAR( 255 ) NOT NULL", $link) or $errs .= "<p><font color=#ff0000>Unable to alter table:</font><br>" . mysql_error() . "</p>";
			$ru[] = mysql_query("ALTER TABLE `cn_config` ADD `version` VARCHAR( 255 ) NOT NULL", $link) or $errs .= "<p><font color=#ff0000>Unable to alter table:</font><br>" . mysql_error() . "</p>";
			$ru[] = mysql_query("ALTER TABLE `cn_config` ADD `words` ENUM( 'on', 'off' ) NOT NULL", $link) or $errs .= "<p><font color=#ff0000>Unable to alter table:</font><br>" . mysql_error() . "</p>";
			$ru[] = mysql_query("ALTER TABLE `cn_config` ADD `comments` ENUM( 'on', 'off' ) NOT NULL", $link) or $errs .= "<p><font color=#ff0000>Unable to alter table:</font><br>" . mysql_error() . "</p>";
			$ru[] = mysql_query("ALTER TABLE `cn_config` ADD `search` ENUM( 'on', 'off' ) NOT NULL", $link) or $errs .= "<p><font color=#ff0000>Unable to alter table:</font><br>" . mysql_error() . "</p>";
			$ru[] = mysql_query("ALTER TABLE `cn_config` ADD `pages` ENUM( 'on', 'off' ) NOT NULL", $link) or $errs .= "<p><font color=#ff0000>Unable to alter table:</font><br>" . mysql_error() . "</p>";
			$ru[] = mysql_query("ALTER TABLE `cn_config` ADD `catbox` ENUM( 'on', 'off' ) NOT NULL", $link) or $errs .= "<p><font color=#ff0000>Unable to alter table:</font><br>" . mysql_error() . "</p>";
			// News table changes 
			$ru[] = mysql_query("ALTER TABLE `cn_news` ADD `content2` LONGTEXT NOT NULL AFTER `content`", $link) or $errs .= "<p><font color=#ff0000>Unable to alter table:</font><br>" . mysql_error() . "</p>";
			$ru[] = mysql_query("ALTER TABLE `cn_news` ADD `sumstory` ENUM('on','off') DEFAULT 'off' NOT NULL AFTER `content2`", $link) or $errs .= "<p><font color=#ff0000>Unable to alter table:</font><br>" . mysql_error() . "</p>";
			
			// Add new variables in 
			$setsource = "<small>Source: <a href=\"{surl}\" target=\"_blank\">{sname}</a></small><br><br>";
			$setauthor = "<a href=\"mailto:{aemail}\">{aname}</a>";
			$setcoms = "View/Post Comment ({cnum})";
			$ru[] = mysql_query("UPDATE cn_config SET source='$setsource', author='$setauthor', coms_text='$setcoms', version='1.12'", $link) or $errs .= "<p><font color=#ff0000>Unable to update 'cn_config' table:</font><br>" . mysql_error() . "</p>";
		}
		
		// Upgrades for v1.13
		if("1.13" > $set[version]) {
			$ru[] = mysql_query("UPDATE cn_config SET version='1.13'", $link) or $errs .= "<p><font color=#ff0000>Unable to update 'cn_config' table:</font><br>" . mysql_error() . "</p>";
		}
		
		// Upgrades for current version
		if($cnver_new > $set[version]) {
			
			// Add table 'cn_images' 
			$r[imgs] = "
			 CREATE TABLE `cn_images` (
			`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
			`author` INT(10) UNSIGNED NOT NULL,
			`type` VARCHAR(255) NOT NULL,
			`name` VARCHAR(255) NOT NULL,
			`text` VARCHAR(255) NOT NULL,
			`filename` VARCHAR(255) NOT NULL,
			`thumbname` VARCHAR(255) NOT NULL,
			`date` INT(10) UNSIGNED NOT NULL
			)";
			$q[delimgs] = mysql_query("DROP TABLE IF EXISTS cn_images", $link);
			$q[imgs] = mysql_query($r[imgs], $link) or die ("<font color=#ff0000>Unable to create 'cn_images' table:</font><br>" . mysql_error());
			print "<font color=#008000>Table 'cn_images' created</font><br>";
			
			$ru[] = mysql_query("ALTER TABLE `cn_users` DROP PRIMARY KEY , ADD PRIMARY KEY ( `id` ) ", $link) or $errs .= "<p><font color=#ff0000>Unable to alter table:</font><br>" . mysql_error() . "</p>";
			$ru[] = mysql_query("ALTER TABLE `cn_users` CHANGE `id` `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT, CHANGE `created` `created` INT(10) UNSIGNED NOT NULL, CHANGE `last_login` `last_login` INT(10) UNSIGNED NOT NULL, ADD `images` ENUM('on','off') DEFAULT 'off' NOT NULL", $link) or $errs .= "<p><font color=#ff0000>Unable to alter table:</font><br>" . mysql_error() . "</p>";
			$ru[] = mysql_query("ALTER TABLE `cn_cats` CHANGE `id` `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT", $link) or $errs .= "<p><font color=#ff0000>Unable to alter table:</font><br>" . mysql_error() . "</p>";
			$ru[] = mysql_query("ALTER TABLE `cn_comments` CHANGE `id` `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT, CHANGE `date` `date` INT(10) UNSIGNED NOT NULL", $link) or $errs .= "<p><font color=#ff0000>Unable to alter table:</font><br>" . mysql_error() . "</p>";
			$ru[] = mysql_query("ALTER TABLE `cn_news` CHANGE `id` `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT, CHANGE `author` `author` INT(10) UNSIGNED NOT NULL, CHANGE `cat` `cat` INT(10) UNSIGNED NOT NULL, CHANGE `date` `date` INT(10) UNSIGNED NOT NULL", $link) or $errs .= "<p><font color=#ff0000>Unable to alter table:</font><br>" . mysql_error() . "</p>";
			$ru[] = mysql_query("ALTER TABLE `cn_words` CHANGE `id` `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT", $link) or $errs .= "<p><font color=#ff0000>Unable to alter table:</font><br>" . mysql_error() . "</p>";
			$ru[] = mysql_query("ALTER TABLE `cn_config` ADD `images` ENUM('on','off') DEFAULT 'on' NOT NULL AFTER `catbox`, ADD `img_thumbw` VARCHAR(255) DEFAULT '150' NOT NULL AFTER `images`, ADD `img_thumbh` VARCHAR(255) DEFAULT '150' NOT NULL AFTER `img_thumbw`, ADD `img_dir` VARCHAR(255) DEFAULT 'uploads/' NOT NULL AFTER `img_thumbh`, ADD `img_maxsize` INT( 10 ) UNSIGNED NOT NULL AFTER `img_dir`", $link) or $errs .= "<p><font color=#ff0000>Unable to alter table:</font><br>" . mysql_error() . "</p>";
			
			$ru[] = mysql_query("UPDATE cn_config SET version='$cnver_new'", $link) or $errs .= "<p><font color=#ff0000>Unable to update 'cn_config' table:</font><br>" . mysql_error() . "</p>";
		}
		
		// Print errors or success message
		if(empty($errs)) { print "<font color=#008000>Updates Applied</font>"; } else { print $errs; }
		?>
		<p>
		Your version of CzarNews has been updated to v<? print $cnver_new; ?>.  Please look over all
		the configuration options to ensure they are correct.  Some options may have changed or been
		added for the new version.  Thank you for choosing CzarNews.
		</p>
		</blockquote>
		</td></tr>
		
		<tr><td>
		<table width="450" border="0" cellspacing="0" cellpadding="2">
		<tr class="head">
		<td class="title">
		&nbsp;Everything Okay?
		</td><td align="right">
		<input type="button" value="Open CzarNews" onClick="javascript:location.href='cn_webconfig.php'">
		</td></tr>
		</table>
		</td></tr>
		<?
	} else {
		?>
		
		<tr><td>
		<table width="450" border="0" cellspacing="0" cellpadding="2">
		<tr class="head">
		<td class="title">
		&nbsp;CHANGELOG
		</td></tr>
		</table>
		</td></tr><tr>
		<td class="content" style="padding: 8px;"><br>
		
		<a href="javascript:openBox(1.14)">CzarNews v1.14 Changes</a><br>
		<div id="1.14" style="display: block">
		Released On: March 25, 2005<br>
		---------------------------------------------<br>
		<ul>
		<li>Added 'cn_images.php' page for adding/uploading images for news articles</li>
		<li>Added image upload, delete, and thumbnail functions</li>
		<li>Added config options for images, like width and height for thumbnails</li>
		<li>Added user permission to access and use images in user's news posts</li>
		<li>Added function to build query string so CzarNews will work within most portal systems</li>
		<li>Update: Valid XHTML 1.0 output for news items printed out by 'news.php' and 'headlines.php'</li>
		<li>Update: Changed all function names to start with a 'cn_' prefix to avoid conflictions with a user's predefined functions</li>
		<li>FIX: Remote file inclusion security hole if 'allow_url_fopen' and 'register_globals' are turned 'On'</li>
		<li>FIX: File 'fpass.php' would produce an error if register_globals was not on</li>
		<li>FIX: File 'news.php' would display news from all categories, even if a category ($c) was specified</li>
		<li>FIX: 'Hyperlink' keyword displayed full URL instead of the text as a link</li>
		<li>FIX: Minor issues with some user category permissions</li>
		</ul>
		</div>
		
		<a href="javascript:openBox(1.13)">CzarNews v1.13 Changes</a><br>
		<div id="1.13" style="display: none">
		Released On: October 2, 2004<br>
		---------------------------------------------<br>
		<ul>
		<li>Updated all variables to superglobals ($_POST, $_GET, etc...) for increased security and portability</li>
		<li>Updated existing functions for increased portability and added new function to list usernames</li>
		<li>Updated generator and install page to auto-detect correct current directory of CzarNews</li>
		<li>Added 'cn_update.php' file that checks <a href="http://www.czaries.net" target="_blank">czaries.net</a> for updates</li>
		<li>Fixed bug that allowed anonymous users to use HTML in comments</li>
		<li>Fixed bug to insert line breaks for content areas ONLY instead of after news was completely formatted (would break tables, etc)</li>
		<li>Fixed some cross-browser javascript compatibility issues for admin panel of CzarNews</li>
		<li>Fixed bug that would not allow users to edit their own news posts when 'News Admin' was unchecked</li>
		<li>Fixed 'Function redefined' errors that would appear if user included 'headlines.php' or 'news.php' more than once on the same page</li>
		<li>Security warnings are now only displayed for logged-in admin users</li>
		<li>Structured all PHP code to make it easier to read (at the request of CzarNews users)</li>
		</ul>
		</div>
		
		<a href="javascript:openBox(1.12)">CzarNews v1.12 Changes</a><br>
		<div id="1.12" style="display: none">
		Released On: March 7, 2004<br>
		---------------------------------------------<br>
		<ul>
		<li>Optimized several lines of code for faster execution speed</li>
		<li>Fixed bug with category display box (would not display if user was not logged in)</li>
		<li>Added 'All Categories' news display selection for category box</li>
		<li>Added news search feature with highlighting of searched word</li>
		<li>Added Comments with current username protection (someone cannot post a comment under a CzarNews user's name w/o their password)</li>
		<li>Fixed security bug that allowed users to delete news items outside their category permissions</li>
		<li>Added 'cn_info.php' file to explain a little about the script and myself</li>
		<li>Added 'cn_generate.php' file to generate PHP code that you can use to include the file 'news.php'</li>
		<li>Added ability to create news summaries and full stories</li>
		<li>Added ability to move and delete multiple news items at once</li>
		<li>Fixed news setting to allow user permission: 'allow user to edit/delete only posts that they themselves posted'</li>
		<li>Added warning for having 'install.php' on server after script is installed</li>
		</ul>
		</div>
		
		</td></tr>
		
		<tr><td>
		<table width="450" border="0" cellspacing="0" cellpadding="2">
		<tr class="head">
		<td class="title">
		&nbsp;Ready to Upgrade?
		</td><td align="right">
		<input type="hidden" name="install" value="upg">
		<input type="hidden" name="go" value="true">
		<input type="submit" value="Upgrade">
		</td></tr>
		</table>
		</td></tr>
		
		<?
	}
} else {
	?>
	
	<tr><td>
	<table width="450" border="0" cellspacing="0" cellpadding="2">
	<tr class="head">
	<td class="title">
	&nbsp;CzarNews Install Mode
	</td></tr>
	</table>
	</td></tr><tr>
	<td class="content"><br>
	<blockquote>
	Please choose your desired CzarNews installation mode from the choices below:
	</blockquote>
	<table width="400" border="0" cellspacing="0" cellpadding="1" align="center">
	<tr><td>
	<input type="radio" name="install" value="full" id="full"<? if(empty($set[version])) { print " checked"; } ?>> <label for="full">Full Install (first-time users) - Will delete all existing data</label><br>
	
	<input type="radio" name="install" value="upg" id="upg"<? if(!empty($set[version])) { print " checked"; } ?>> <label for="upg">Upgrade to v<? print $cnver_new; ?> (Your version: <? print $set[version]; ?>) - Keeps all existing data</label><br>
	<input type="hidden" name="oldver" value="<? print $set[version]; ?>">
	<input type="radio" name="install" value="trouble" id="trouble"> <label for="trouble">Troubleshooting - Restore certian aspects of CzarNews</label><br>
	</td></tr>
	</table>
	<br></td></tr>
	
	<tr><td>
	<table width="450" border="0" cellspacing="0" cellpadding="2">
	<tr class="head">
	<td class="title">
	&nbsp;Ready to go on?
	</td><td align="right">
	<input type="submit" value="Next Step &gt;&gt;">
	</td></tr>
	</table>
	</td></tr>

<? } ?>

</table>
</form>
</div>
</body>

</html>
