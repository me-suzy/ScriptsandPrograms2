<?php
error_reporting (E_ERROR|E_WARNING|E_PARSE);

include "./global.php";
$site['title'] = "Setup";

if ($_GET['do'] == 'install')
{
	$udb->query('DROP TABLE IF EXISTS '.$database['smilies']);
	$udb->query('CREATE TABLE '.$database['smilies']." (
	  `id` smallint(11) NOT NULL auto_increment,
	  `before` varchar(255) NOT NULL default '',
	  `click` tinyint(1) NOT NULL default '0',
	  `after` varchar(255) NOT NULL default '',
	  PRIMARY KEY  (`id`)
	) TYPE=MyISAM");


	$udb->query("INSERT INTO ".$database['smilies']." VALUES (1, ':)', 1, '{smiley_path}smile.gif')");
	$udb->query("INSERT INTO ".$database['smilies']." VALUES (2, ':(', 1, '{smiley_path}sad.gif')");
	$udb->query("INSERT INTO ".$database['smilies']." VALUES (3, ';)', 1, '{smiley_path}wink.gif')");
	$udb->query("INSERT INTO ".$database['smilies']." VALUES (4, ':D', 1, '{smiley_path}biggrin.gif')");
	$udb->query("INSERT INTO ".$database['smilies']." VALUES (5, ';;)', 0, '{smiley_path}eyes.gif')");
	$udb->query("INSERT INTO ".$database['smilies']." VALUES (6, ':X', 1, '{smiley_path}love.gif')");
	$udb->query("INSERT INTO ".$database['smilies']." VALUES (7, ':\">', 1, '{smiley_path}blush.gif')");
	$udb->query("INSERT INTO ".$database['smilies']." VALUES (8, ':*', 0, '{smiley_path}kiss.gif')");
	$udb->query("INSERT INTO ".$database['smilies']." VALUES (9, ':p', 1, '{smiley_path}tongue.gif')");
	$udb->query("INSERT INTO ".$database['smilies']." VALUES (10, ':o', 1, '{smiley_path}shock.gif')");
	$udb->query("INSERT INTO ".$database['smilies']." VALUES (11, 'B)', 1, '{smiley_path}cool.gif')");
	$udb->query("INSERT INTO ".$database['smilies']." VALUES (12, '>:)', 0, '{smiley_path}devilish.gif')");
	$udb->query("INSERT INTO ".$database['smilies']." VALUES (13, ':((', 1, '{smiley_path}cry.gif')");
	$udb->query("INSERT INTO ".$database['smilies']." VALUES (14, ':))', 1, '{smiley_path}laugh.gif')");
	$udb->query("INSERT INTO ".$database['smilies']." VALUES (15, '[-(', 0, '{smiley_path}majok.gif')");
	$udb->query("INSERT INTO ".$database['smilies']." VALUES (16, ':-?', 0, '{smiley_path}thinking.gif')");
	$udb->query("INSERT INTO ".$database['smilies']." VALUES (17, ':rolleyes:', 1, '{smiley_path}eyes.gif')");
		

	$udb->query('DROP TABLE IF EXISTS '.$database['comments'].';');
	$udb->query('CREATE TABLE '.$database['comments']." (
	  `id` smallint(11) NOT NULL auto_increment,
	  `entry_id` smallint(11) default NULL,
	  `name` varchar(255) NOT NULL default '',
	  `comment` mediumtext NOT NULL,
	  `ip` varchar(50) NOT NULL default '',
	  `email` varchar(100) NOT NULL default '',
	  PRIMARY KEY  (`id`)
	) TYPE=MyISAM");

	$udb->query('DROP TABLE IF EXISTS '.$database['entry'].';');
	$udb->query("CREATE TABLE ".$database['entry']." (
	  `id` int(11) NOT NULL auto_increment,
	  `name` varchar(255) NOT NULL default '',
	  `email` varchar(255) NOT NULL default '',
	  `www` varchar(255) NOT NULL default '',
	  `ym` varchar(255) NOT NULL default '',
	  `icq` varchar(255) NOT NULL default '',
	  `ip` varchar(255) NOT NULL default '',
	  `agent` varchar(255) NOT NULL default '',
	  `entry` longtext NOT NULL,
	  `date` int(50) NOT NULL default '0',
	  `gender` varchar(10) NOT NULL default '',
	  `totalcomments` int(11) NOT NULL default '0',
	  `country` varchar(100) NOT NULL default '',
	  PRIMARY KEY  (`id`)
	) TYPE=MyISAM");

	$udb->query("DROP TABLE IF EXISTS ".$database['settings'].";");
	$udb->query("CREATE TABLE ".$database['settings']." (
	  `id` int(11) NOT NULL auto_increment,
	  `oid` int(11) NOT NULL default '0',
	  `varname` varchar(100) NOT NULL default '',
	  `value` text NOT NULL,
	  `defvalue` text NOT NULL,
	  `name` varchar(255) NOT NULL default '',
	  `description` varchar(255) NOT NULL default '',
	  `type` varchar(100) NOT NULL default '',
	  `orders` int(11) NOT NULL default '0',
	  PRIMARY KEY  (`id`)
	) TYPE=MyISAM");


	$udb->query("DROP TABLE IF EXISTS ".$database['sgroup'].";");
	$udb->query("CREATE TABLE ".$database['sgroup']." (
	  `sid` smallint(11) NOT NULL auto_increment,
	  `name` varchar(100) NOT NULL default '',
	  `description` varchar(255) NOT NULL default '',
	  `orders` smallint(11) default '0',
	  PRIMARY KEY  (`sid`)
	) TYPE=MyISAM");

	$udb->query("INSERT INTO ".$database['sgroup']." VALUES (1, 'main', 'main settings\r\n', 0)");
	$udb->query("INSERT INTO ".$database['sgroup']." VALUES (2, 'posting', 'posting settings', 0)");


	$udb->query("INSERT INTO ".$database['settings']." VALUES (1, 1, 'name', 'evobook', '', 'Guestbook Name', 'Name of this guestbook', 'text', 0)");
	$udb->query("INSERT INTO ".$database['settings']." VALUES (2, 1, 'url', 'http://localhost/evobook', '', 'Guestbook URL', 'url of this guestbook <br />\r\ni.e : http://domain.com/guestbook<br />\r\nno trailing slash', 'text', 0)");
	$udb->query("INSERT INTO ".$database['settings']." VALUES (3, 1, 'dateformat', 'j F , Y, g:i a', 'd m y', 'Date format', 'date format. <a href=\"http://www.php.net/date\">here</a> for more info', 'text', 0)");
	$udb->query("INSERT INTO ".$database['settings']." VALUES (4, 1, 'deflang', 'lang_english', '', 'Default Language', 'Default language used on system', 'select|||lang', 0)");
	$udb->query("INSERT INTO ".$database['settings']." VALUES (5, 1, 'email', 'amunzir@tm.net.my', 'admin@yeeha.com', 'Techical Email', 'Your Email', 'text', 0)");
	$udb->query("INSERT INTO ".$database['settings']." VALUES (6, 1, 'imgfolder', 'img/', '', 'Image Folder', 'Guestbook Image Folder<br />\r\nwith trailing slash <br />\r\ni.e : http://domain/gbook/img<b>/</b>', 'text', 0)");
	$udb->query("INSERT INTO ".$database['settings']." VALUES (7, 1, 'img_smilies', 'img/smilies/', '', 'Smilies Folder', 'path to smilies folder<br />\r\nend with trailing slash <br/ >\r\ni.e: path<b>/</b>', 'text', 0)");
	$udb->query("INSERT INTO ".$database['settings']." VALUES (8, 1, 'perpage', '5', '', 'Results Per Page', 'Total LATEST Entries shown on each page', 'text', 0)");
	$udb->query("INSERT INTO ".$database['settings']." VALUES (9, 2, 'allow_html', '1', '', 'Allow HTML', 'Allow HTML in entries?', 'yesno', 0)");
	$udb->query("INSERT INTO ".$database['settings']." VALUES (10, 2, 'filter', 'shit\r\nfuck\r\ncrap\r\nfuckers\r\nasshole\r\nbullshit\r\nass', '', 'Words Filter', 'Filter unwanted words in comments. <br />Saparated by line breaks like so:<br />\r\nshit <br />\r\ncrap', 'textarea', 0)");
	$udb->query("INSERT INTO ".$database['settings']." VALUES (11, 2, 'floodcheck', '60', '', 'Flood Check', 'Allow new entry to be added atleast <b>x</b> seconds. Enter \'x\' value in text field.', 'text', 0)");
	$udb->query("INSERT INTO ".$database['settings']." VALUES (12, 1, 'mode', 'gb', '', 'Script Mode', 'Choose Mode', 'select|||mode', 10)");
	$udb->query("INSERT INTO ".$database['settings']." VALUES (13, 1, 'output', '1', '', 'Shoutbox : Output type?', 'Do you wish to output just shoutbox table?', 'yesno', 0)");
	$udb->query("INSERT INTO ".$database['settings']." VALUES (14, 2, 'autobr', '1', '', 'Enable Auto-BR?', 'This will automatically add a <br> on new line', 'yesno', 0)");

		

	$content = "YAY, finished installing. You can remove this file now (setup.php), failure to do so will allow someone to delete your guestbook by reinstalling it";
}
else
{
	$content .= 'CHMOD <b>admin/config_site.php</b> to <b>666</b>, failure to do so will cause error when changing a settings after installation.<br /><br />';
	$content .= $admin->link_button('Install',$_SERVER['PHP_SELF'].'?do=install');
}
$page->generate();
?>