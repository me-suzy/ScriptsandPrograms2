<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta name="author" content="Chris Warren" />
<meta name="copyright" content="2005 Chris Warren" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>DS Plugger Installer</title>
<style type="text/css">
body {
	background-color: #457698;
}
h1 {
	text-align: center;
	font-size: 16px;
	font-weight: bold;
}
h2 {
	text-align: center;
	font-size: 14px;
	font-weight: bold;
}
#container {
	background-color: #fff;
	color: #000;
	width: 780px;
	margin-left: auto;
	margin-right: auto;
	padding: 10px;
	text-align: center;
}
</style>
</head>
<body>
<div id="container">
<h1>DS Plugger Installer</h1>
<?php
require_once('mysql.class.php');
require('config.php');
$sql = &new database;
$sql->connect($db['host'],$db['database'],$db['user'],$db['password']);
if (!isset($_GET['action'])) {
	echo "<br /><br />\n";
	echo "<a href='install.php?action=install'>(Re)Install</a>";
	$result = $sql->dbQuery("SELECT * FROM dsp_settings WHERE object = 'version'");
	$version = $sql->dbFetchArray($result);
	if (isset($version['setting']) && $version['setting'] < 1.1) {
		echo "&nbsp;&nbsp;&nbsp;&nbsp;<a href='install.php?action=upgrade'>Upgrade</a>";
	}
	echo "<br />\n";
	echo "</div>\n</body>\n</html>";
	exit();
} elseif ($_GET['action'] == "upgrade") {
	$sql->dbSave("dsp_settings","object,setting","'version','1.1'");
	$query = "CREATE TABLE `dsp_image` (
		`size` varchar(12) NOT NULL default '',
		`image` blob NOT NULL,
		PRIMARY KEY  (`size`)
		) TYPE=MyISAM";
	$result = $sql->dbQuery($query);
	if ($result) {
		echo "Table 'dsp_image' created successfully<br />\n";
		$sql->dbSave("dsp_image","size,image","'1', 0x89504e470d0a1a0a0000000d4948445200000001000000010802000000907753de000000097048597300000b1300000b1301009a9c180000000774494d4507d50a05160e1c616061ac0000001d74455874436f6d6d656e7400437265617465642077697468205468652047494d50ef64256e0000000c4944415408d763f8ffff3f0005fe02fedccc59e70000000049454e44ae426082");
		echo "Table 'dsp_image' populated<br />\n";
	} else {
		echo "Problem creating table 'dsp_image'<br />\n";
		echo "<h2>Installation Failed</h2>\n";
		echo "Contact <a href=\"mailto:chris@dawgiestyle.com\">chris@dawgiestyle.com</a> with problems\n";
		exit();
	} ?>
	<br />
	<h2>Upgrade Complete!</h2>
	Please delete install.php and login to <a href="admin.php">admin.php</a>.
<?php
} elseif ($_GET['action'] == "install") {
	$query = "DROP TABLE IF EXISTS `dsp_banned`";
	$sql->dbQuery($query);

	$query = "CREATE TABLE `dsp_banned` (
		`ip` varchar(20) NOT NULL default '',
		`comment` varchar(255) default NULL,
		PRIMARY KEY  (`ip`)
		) TYPE=MyISAM";
	$result = $sql->dbQuery($query);
	if ($result) {
		echo "Table 'dsp_banned' created successfully<br />\n";
	} else {
		echo "Problem creating table 'dsp_banned'<br />\n";
		echo "<h2>Installation Failed</h2>\n";
		echo "Contact <a href=\"mailto:chris@dawgiestyle.com\">chris@dawgiestyle.com</a> with problems\n";
		exit();
	}

	$query = "DROP TABLE IF EXISTS `dsp_plugs`";
	$sql->dbQuery($query);

	$query = "CREATE TABLE `dsp_plugs` (
		`id` int(5) NOT NULL auto_increment,
		`url` varchar(100) NOT NULL default '',
		`image` varchar(100) NOT NULL default '',
		`ip` varchar(20) NOT NULL default '',
		PRIMARY KEY  (`id`)
		) TYPE=MyISAM";
	$result = $sql->dbQuery($query);
	if ($result) {
		echo "Table 'dsp_plugs' created successfully<br />\n";
		$sql->dbSave("dsp_plugs","ip,url,image","'127.0.0.1','http://www.dawgiestyle.com','http://www.dawgiestyle.com/images/dsplugger-logo.png'");
	} else {
		echo "Problem creating table 'dsp_plugs'<br />\n";
		echo "<h2>Installation Failed</h2>\n";
		echo "Contact <a href=\"mailto:chris@dawgiestyle.com\">chris@dawgiestyle.com</a> with problems\n";
		exit();
	}
	
	$query = "DROP TABLE IF EXISTS `dsp_settings`";
	$sql->dbQuery($query);

	$query = "CREATE TABLE `dsp_settings` (
		`object` varchar(30) NOT NULL default '',
		`setting` varchar(30) default NULL,
		PRIMARY KEY  (`object`)
		) TYPE=MyISAM";
	$result = $sql->dbQuery($query);
	if ($result) {
		echo "Table 'dsp_settings' created successfully<br />\n";
		$sql->dbSave("dsp_settings","object,setting","'width','88'");
		$sql->dbSave("dsp_settings","object,setting","'height','31'");
		$sql->dbSave("dsp_settings","object,setting","'columns','3'");
		$sql->dbSave("dsp_settings","object,setting","'rows','4'");
		$sql->dbSave("dsp_settings","object,setting","'target','_blank'");
		$sql->dbSave("dsp_settings","object,setting","'version','1.1'");
		echo "Table 'dsp_settings' populated<br /><br />\n";
	} else {
		echo "Problem creating table 'dsp_settings'<br />\n";
		echo "<h2>Installation Failed</h2>\n";
		echo "Contact <a href=\"mailto:chris@dawgiestyle.com\">chris@dawgiestyle.com</a> with problems\n";
		exit();
	}
	
	$query = "DROP TABLE IF EXISTS `dsp_style_body`";
	$sql->dbQuery($query);

	$query = "CREATE TABLE `dsp_style_body` (
		`object` varchar(50) NOT NULL default '',
		`setting` varchar(50) default NULL,
		PRIMARY KEY  (`object`)
		) TYPE=MyISAM";
	$result = $sql->dbQuery($query);
	if ($result) {
		echo "Table 'dsp_style_body' created successfully<br />\n";
		$sql->dbSave("dsp_style_body","object,setting","'border-color','#000'");
		$sql->dbSave("dsp_style_body","object,setting","'background-color','#fff'");
		$sql->dbSave("dsp_style_body","object,setting","'font-family','arial'");
		$sql->dbSave("dsp_style_body","object,setting","'color','#000'");
		$sql->dbSave("dsp_style_body","object,setting","'font-size','11px'");
		echo "Table 'dsp_style_body' populated<br /><br />\n";
	} else {
		echo "Problem creating table 'dsp_style_body'<br />\n";
		echo "<h2>Installation Failed</h2>\n";
		echo "Contact <a href=\"mailto:chris@dawgiestyle.com\">chris@dawgiestyle.com</a> with problems\n";
		exit();
	}
	
	$query = "DROP TABLE IF EXISTS `dsp_style_form`";
	$sql->dbQuery($query);

	$query = "CREATE TABLE `dsp_style_form` (
		`object` varchar(50) NOT NULL default '',
		`setting` varchar(50) default NULL,
		PRIMARY KEY  (`object`)
		) TYPE=MyISAM";
	$result = $sql->dbQuery($query);
	if ($result) {
		echo "Table 'dsp_style_form' created successfully<br />\n";
		$sql->dbSave("dsp_style_form","object,setting","'background-color','#eee'");
		$sql->dbSave("dsp_style_form","object,setting","'font-family','arial'");
		$sql->dbSave("dsp_style_form","object,setting","'color','#000'");
		$sql->dbSave("dsp_style_form","object,setting","'font-size','11px'");
		echo "Table 'dsp_style_form' populated<br />\n";
	} else {
		echo "Problem creating table 'dsp_style_form'<br />\n";
		echo "<h2>Installation Failed</h2>\n";
		echo "Contact <a href=\"mailto:chris@dawgiestyle.com\">chris@dawgiestyle.com</a> with problems\n";
		exit();
	}
	
	$query = "DROP TABLE IF EXISTS `dsp_image`";
	$sql->dbQuery($query);
	
	$query = "CREATE TABLE `dsp_image` (
		`size` varchar(12) NOT NULL default '',
		`image` blob NOT NULL,
		PRIMARY KEY  (`size`)
		) TYPE=MyISAM";
	$result = $sql->dbQuery($query);
	if ($result) {
		echo "Table 'dsp_image' created successfully<br />\n";
		$sql->dbSave("dsp_image","size,image","'1', 0x89504e470d0a1a0a0000000d4948445200000001000000010802000000907753de000000097048597300000b1300000b1301009a9c180000000774494d4507d50a05160e1c616061ac0000001d74455874436f6d6d656e7400437265617465642077697468205468652047494d50ef64256e0000000c4944415408d763f8ffff3f0005fe02fedccc59e70000000049454e44ae426082");
		echo "Table 'dsp_image' populated<br />\n";
	} else {
		echo "Problem creating table 'dsp_image'<br />\n";
		echo "<h2>Installation Failed</h2>\n";
		echo "Contact <a href=\"mailto:chris@dawgiestyle.com\">chris@dawgiestyle.com</a> with problems\n";
		exit();
	} ?>
	<br />
	<h2>Installation Complete!</h2>
	Please delete install.php and login to <a href="admin.php">admin.php</a>.
<?php } ?>
</div>
</body>
</html>