<?php
// ----------------------------------------------------------------------
// Khaled Content Management System
// Copyright (C) 2004 by Khaled Al-Shamaa.
// GSIBC.net stands behind the software with support, training, certification and consulting.
// http://www.al-shamaa.com/
// ----------------------------------------------------------------------
// LICENSE

// This program is open source product; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.

// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------
// Filename: install.php
// Original  Author(s): Khaled Al-Sham'aa khaled@al-shamaa.com
// Purpose:  Database installation
// ----------------------------------------------------------------------

header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache"); // HTTP/1.0
?>
<?php
include_once ("db.php");
$currentTime = time();

// create an object instance
// configure library for a MySQL connection
$db = NewADOConnection(DBTYPE);

// get resultset as associative array
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

if(!@$db->Connect(HOST, USER, PASS, DB)){
      echo '<br><br><br><font face=verdana><center><b><font color=red>';
      echo 'You have to set your database configuration ';
      echo 'in the "db.php" file</font></b></center></font>';
      exit;
}

?>
<?php
if (isset($_POST['install'])){
	$strsql = "DROP TABLE IF EXISTS `pages`";
	$db->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());

$strsql = <<<END
CREATE TABLE `pages` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `content` longtext NOT NULL,
  `views` int(11) NOT NULL default '0',
  `parent_id` int(11) NOT NULL default '1',
  `status` int(11) NOT NULL default '1',
  `order` int(11) NOT NULL default '0',
  `on` int(11) NOT NULL default '0',
  `off` int(11) NOT NULL default '0',
  `to_sell` int(11) NULL default '0',
  `price` float NULL default '0',
  `mode` int(11) NOT NULL default '0',
  `mode_ext` varchar(255) NOT NULL default '',
  `lang` char(2) NOT NULL default 'en',
  `privilege` int(11) NOT NULL default '0',
  `modified` int(11) NOT NULL default '0',
  `create` int(11) NOT NULL default '0',
  `user_id` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`id`,`lang`),
  KEY `parent_id` (`parent_id`),
  KEY `status` (`status`),
  KEY `to_sell` (`to_sell`),
  KEY `mode` (`mode`),
  FULLTEXT KEY `content` (`content`),
  FULLTEXT KEY `title` (`title`)
) TYPE=MyISAM AUTO_INCREMENT=2
END;

	$db->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());

	$db->Execute("INSERT INTO `pages` VALUES (1, 'Home page', '<P>Home page content</P>', 1, 1, 1, 1, 0, 0, 0, 0, 0, '', 'en', 0, $currentTime, $currentTime, 'admin')") or die("Error in query: $strsql. " . $db->ErrorMsg());
	$db->Execute("INSERT INTO `pages` VALUES (2, 'Sub page', '<P>Sub page content</P>', 1, 1, 1, 2, 0, 0, 0, 0, 0, '', 'en', 0, $currentTime, $currentTime, 'admin')") or die("Error in query: $strsql. " . $db->ErrorMsg());
	$db->Execute("INSERT INTO `pages` VALUES (1, 'ÇáÕÝÍÉ ÇáÑÆíÓíÉ', '<P>ãÍÊæì ÇáÕÝÍÉ ÇáÑÆíÓíÉ</P>', 1, 1, 1, 1, 0, 0, 0, 0, 0, '', 'ar', 0, $currentTime, $currentTime, 'admin')") or die("Error in query: $strsql. " . $db->ErrorMsg());
	$db->Execute("INSERT INTO `pages` VALUES (2, 'ÕÝÍÉ ÝÑÚíÉ', '<P>ãÍÊæì ÇáÕÝÍÉ ÇáÝÑÚíÉ</P>', 1, 1, 1, 2, 0, 0, 0, 0, 0, '', 'ar', 0, $currentTime, $currentTime, 'admin')") or die("Error in query: $strsql. " . $db->ErrorMsg());

	$db->Execute("DROP TABLE IF EXISTS `users`") or die("Error in query: $strsql. " . $db->ErrorMsg());

$strsql = <<<END
CREATE TABLE `users` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` varchar(50) NOT NULL default '',
  `user_pw` varchar(255) NOT NULL default '',
  `fullname` varchar(50) NOT NULL default '',
  `privilege` int(11) NOT NULL default '0',
  `rootpage` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=2
END;

	$db->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());

	$db->Execute("INSERT INTO `users` VALUES (1, 'admin', '" . addslashes('$1$dg/.iu1.$sBvhmZWxa.LDyDp7wmC9t/') . "', 'Khaled El-Sham''aa', 255, 0);") or die("Error in query: $strsql. " . $db->ErrorMsg());

	$db->Execute("DROP TABLE IF EXISTS `invoice`") or die("Error in query: $strsql. " . $db->ErrorMsg());

$strsql = <<<END
CREATE TABLE `invoice` (
  `id` int(11) NOT NULL auto_increment,
  `phone` varchar(20) NOT NULL default '',
  `name` varchar(30) NOT NULL default '',
  `address` text NOT NULL,
  `city` varchar(30) NOT NULL default '',
  `state` varchar(30) NOT NULL default '',
  `zip` varchar(30) NOT NULL default '',
  `country` varchar(30) NOT NULL default '',
  `session` varchar(100) NOT NULL default '',
  `date` int(11) NOT NULL default '0',
  `total` float NOT NULL default '0',
  `paid` char(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `country` (`country`,`paid`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;
END;

	$db->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());

	$db->Execute("DROP TABLE IF EXISTS `invoice_details`") or die("Error in query: $strsql. " . $db->ErrorMsg());

$strsql = <<<END
CREATE TABLE `invoice_details` (
  `id` int(11) NOT NULL auto_increment,
  `invoice_id` int(11) NOT NULL default '0',
  `item_id` int(11) NOT NULL default '0',
  `quantity` int(11) NOT NULL default '0',
  `price` float NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `invoice_id` (`invoice_id`,`item_id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;
END;

	$db->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());

	$db->Execute("DROP TABLE IF EXISTS `interactive`;") or die("Error in query: $strsql. " . $db->ErrorMsg());

$strsql = <<<END
CREATE TABLE `interactive` (
  `id` int(11) NOT NULL auto_increment,
  `page_id` int(11) NOT NULL default '0',
  `alias` varchar(30) NOT NULL default '',
  `email` varchar(50) NOT NULL default '',
  `content` text NOT NULL,
  `status` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `page_id` (`page_id`,`status`)
) TYPE=MyISAM AUTO_INCREMENT=1;
END;

	$db->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());
	$db->Close();

	header("Location: index.php");
}

if (isset($_POST['up02'])){
	$strsql = <<<END
ALTER TABLE `pages` ADD `order` INT DEFAULT '0' NOT NULL AFTER `status` ,
ADD `on` INT DEFAULT '0' NOT NULL AFTER `order` ,
ADD `off` INT DEFAULT '0' NOT NULL AFTER `on` ,
ADD `to_sell` INT DEFAULT '0' AFTER `off` ,
ADD `price` FLOAT DEFAULT '0' AFTER `to_sell` ,
ADD `mode` INT DEFAULT '0' AFTER `price` ;
END;

	$db->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());

	$strsql = "ALTER TABLE `pages` ADD INDEX ( `parent_id` )";
	$db->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());

	$strsql = "ALTER TABLE `pages` ADD INDEX ( `status` )";
	$db->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());

	$strsql = "ALTER TABLE `pages` ADD INDEX ( `mode` )";
	$db->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());

	$strsql = "ALTER TABLE `users` ADD `rootpage` INT NOT NULL ;";
	$db->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());

	$strsql = "UPDATE `users` SET `privilege` = '255' WHERE `id` =1;";
	$db->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());

	$db->Execute("DROP TABLE IF EXISTS `invoice`") or die("Error in query: $strsql. " . $db->ErrorMsg());

$strsql = <<<END
CREATE TABLE `invoice` (
  `id` int(11) NOT NULL auto_increment,
  `phone` varchar(20) NOT NULL default '',
  `name` varchar(30) NOT NULL default '',
  `address` text NOT NULL,
  `city` varchar(30) NOT NULL default '',
  `state` varchar(30) NOT NULL default '',
  `zip` varchar(30) NOT NULL default '',
  `country` varchar(30) NOT NULL default '',
  `session` varchar(100) NOT NULL default '',
  `date` int(11) NOT NULL default '0',
  `total` float NOT NULL default '0',
  `paid` char(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `country` (`country`,`paid`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;
END;

	$db->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());

	$db->Execute("DROP TABLE IF EXISTS `invoice_details`") or die("Error in query: $strsql. " . $db->ErrorMsg());

$strsql = <<<END
CREATE TABLE `invoice_details` (
  `id` int(11) NOT NULL auto_increment,
  `invoice_id` int(11) NOT NULL default '0',
  `item_id` int(11) NOT NULL default '0',
  `quantity` int(11) NOT NULL default '0',
  `price` float NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `invoice_id` (`invoice_id`,`item_id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;
END;

	$db->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());

	$db->Execute("DROP TABLE IF EXISTS `interactive`;") or die("Error in query: $strsql. " . $db->ErrorMsg());

$strsql = <<<END
CREATE TABLE `interactive` (
  `id` int(11) NOT NULL auto_increment,
  `page_id` int(11) NOT NULL default '0',
  `alias` varchar(30) NOT NULL default '',
  `email` varchar(50) NOT NULL default '',
  `content` text NOT NULL,
  `status` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `page_id` (`page_id`,`status`)
) TYPE=MyISAM AUTO_INCREMENT=1;
END;

	$db->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());

	$db->Close();

	header("Location: index.php");
}

if (isset($_POST['up03'])){
	$strsql = <<<END
ALTER TABLE `pages` ADD `to_sell` INT DEFAULT '0' AFTER `off` ,
ADD `price` FLOAT DEFAULT '0' AFTER `to_sell` ,
ADD `mode` INT DEFAULT '0' AFTER `price` ;
END;

	$db->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());

	$strsql = "ALTER TABLE `pages` ADD INDEX ( `to_sell` )";
	$db->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());

	$strsql = "ALTER TABLE `pages` ADD INDEX ( `mode` )";
	$db->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());

	$strsql = "ALTER TABLE `users` ADD `rootpage` INT NOT NULL ;";
	$db->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());

	$strsql = "UPDATE `users` SET `privilege` = '255' WHERE `id` =1;";
	$db->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());

	$db->Execute("DROP TABLE IF EXISTS `invoice`") or die("Error in query: $strsql. " . $db->ErrorMsg());

$strsql = <<<END
CREATE TABLE `invoice` (
  `id` int(11) NOT NULL auto_increment,
  `phone` varchar(20) NOT NULL default '',
  `name` varchar(30) NOT NULL default '',
  `address` text NOT NULL,
  `city` varchar(30) NOT NULL default '',
  `state` varchar(30) NOT NULL default '',
  `zip` varchar(30) NOT NULL default '',
  `country` varchar(30) NOT NULL default '',
  `session` varchar(100) NOT NULL default '',
  `date` int(11) NOT NULL default '0',
  `total` float NOT NULL default '0',
  `paid` char(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `country` (`country`,`paid`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;
END;

	$db->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());

	$db->Execute("DROP TABLE IF EXISTS `invoice_details`") or die("Error in query: $strsql. " . $db->ErrorMsg());

$strsql = <<<END
CREATE TABLE `invoice_details` (
  `id` int(11) NOT NULL auto_increment,
  `invoice_id` int(11) NOT NULL default '0',
  `item_id` int(11) NOT NULL default '0',
  `quantity` int(11) NOT NULL default '0',
  `price` float NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `invoice_id` (`invoice_id`,`item_id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;
END;

	$db->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());

	$db->Execute("DROP TABLE IF EXISTS `interactive`;") or die("Error in query: $strsql. " . $db->ErrorMsg());

$strsql = <<<END
CREATE TABLE `interactive` (
  `id` int(11) NOT NULL auto_increment,
  `page_id` int(11) NOT NULL default '0',
  `alias` varchar(30) NOT NULL default '',
  `email` varchar(50) NOT NULL default '',
  `content` text NOT NULL,
  `status` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `page_id` (`page_id`,`status`)
) TYPE=MyISAM AUTO_INCREMENT=1;
END;

	$db->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());

	$db->Close();

	header("Location: index.php");
}

if (isset($_POST['up04_5'])){
	$strsql = "ALTER TABLE `pages` ADD `mode` INT DEFAULT '0' AFTER `price`;";
	$db->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());

	$strsql = "ALTER TABLE `pages` ADD INDEX ( `mode` )";
	$db->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());

	$strsql = "ALTER TABLE `users` ADD `rootpage` INT NOT NULL ;";
	$db->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());

	$strsql = "UPDATE `users` SET `privilege` = '255' WHERE `id` =1;";
	$db->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());

	$db->Execute("DROP TABLE IF EXISTS `interactive`;") or die("Error in query: $strsql. " . $db->ErrorMsg());

$strsql = <<<END
CREATE TABLE `interactive` (
  `id` int(11) NOT NULL auto_increment,
  `page_id` int(11) NOT NULL default '0',
  `alias` varchar(30) NOT NULL default '',
  `email` varchar(50) NOT NULL default '',
  `content` text NOT NULL,
  `status` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `page_id` (`page_id`,`status`)
) TYPE=MyISAM AUTO_INCREMENT=1;
END;

	$db->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());
	$db->Close();

	header("Location: index.php");
}

if (isset($_POST['up06s'])){
	$strsql = "ALTER TABLE `pages` ADD `mode_ext` VARCHAR( 255 ) NOT NULL AFTER `mode` ;";
	$db->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());

	$strsql = "ALTER TABLE `users` ADD `rootpage` INT NOT NULL ;";
	$db->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());

	$strsql = "UPDATE `users` SET `privilege` = '255' WHERE `id` =1;";
	$db->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());

	$db->Close();

	header("Location: index.php");
}


if (isset($_POST['up063'])){
	$strsql = "ALTER TABLE `users` ADD `rootpage` INT NOT NULL ;";
	$db->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());

	$strsql = "UPDATE `users` SET `privilege` = '255' WHERE `id` =1;";
	$db->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());

	$db->Close();

	header("Location: index.php");
}

?>
<?php include_once("config.php"); ?>
<?php include_once("lang.php"); ?>
<?php ini_set('arg_separator.output', '&;amp;'); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<TITLE><?php echo SITE_TITLE; ?></TITLE>
<META http-equiv=Content-Type content="text/html; charset=<?php echo CHARSET; ?>">
<meta http-equiv="Page-Enter" content="blendtrans(duration=2.0)">
<?php include_once ("design/meta.html") ?>
<link href="khaled.css" rel="stylesheet" type="text/css">
</HEAD>
<BODY bgcolor="#FFFFFF" background="images/bg.gif" DIR=<?php echo DIRECTION; ?>>
<table width="100%" height="100%" border="0">
<tr><td align="center" valign="middle">
<img src="cmsimages/install.gif" width="48" height="46">
<form action="install.php" method="post">

<?php
if(!@$db->Execute("SELECT `id` FROM `pages`")){
?>
<input type="submit" name="install" value="   Install	">
<?php
}elseif(!@$db->Execute("SELECT `order` FROM `pages`")){
?>
<input type="submit" name="up02" value="   Upgrade v 0.2	">
<?php
}elseif(!@$db->Execute("SELECT `to_sell` FROM `pages`")){
?>
<input type="submit" name="up03" value="   Upgrade v 0.3	">
<?php
}elseif(!@$db->Execute("SELECT `page_id` FROM `interactive`")){
?>
<input type="submit" name="up04_5" value="   Upgrade v 0.4 or v 0.5 ">
<?php
}elseif(!@$db->Execute("SELECT `mode_ext` FROM `pages`")){
?>
<input type="submit" name="up06s" value="   Upgrade v 0.6 or v 0.61 or v 0.62 ">
<?php
}elseif(!@$db->Execute("SELECT `rootpage` FROM `users`")){
?>
<input type="submit" name="up063" value="   Upgrade v 0.63 ">
<?php
}else{
?>
<input type="submit" name="install" value="   Initiate	 ">
<?php
}
?>

</form>
</td></tr>
</table>
</BODY></HTML>
