<?php
//
// +-------------------------------------------------------------+
// | HiveMail version 1.0
// | Copyright (C) 2002 Chen Avinadav
// | Supplied by  CyKuH [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+

// ############################################################################
// Table structure
$tables = array (
  'contact' => 'CREATE TABLE `contact` (
  `contactid` int(10) unsigned NOT NULL auto_increment,
  `userid` int(10) unsigned NOT NULL default \'0\',
  `email` varchar(50) NOT NULL default \'\',
  `name` varchar(50) NOT NULL default \'\',
  PRIMARY KEY  (`contactid`),
  KEY `userid` (`userid`)
) TYPE=MyISAM',
  'draft' => 'CREATE TABLE `draft` (
  `draftid` int(10) unsigned NOT NULL auto_increment,
  `userid` int(10) unsigned NOT NULL default \'0\',
  `dateline` int(10) unsigned NOT NULL default \'0\',
  `data` mediumtext NOT NULL,
  PRIMARY KEY  (`draftid`),
  KEY `dateline` (`dateline`)
) TYPE=MyISAM',
  'folder' => 'CREATE TABLE `folder` (
  `folderid` int(10) unsigned NOT NULL auto_increment,
  `userid` int(10) unsigned NOT NULL default \'0\',
  `title` varchar(15) NOT NULL default \'\',
  `msgcount` int(10) unsigned NOT NULL default \'0\',
  PRIMARY KEY  (`folderid`),
  UNIQUE KEY `folderid` (`folderid`),
  KEY `userid` (`userid`)
) TYPE=MyISAM',
  'message' => 'CREATE TABLE `message` (
  `messageid` int(10) unsigned NOT NULL auto_increment,
  `userid` int(10) unsigned NOT NULL default \'0\',
  `folderid` int(11) NOT NULL default \'0\',
  `dateline` int(10) unsigned NOT NULL default \'0\',
  `email` varchar(50) NOT NULL default \'\',
  `name` varchar(255) NOT NULL default \'\',
  `subject` varchar(255) NOT NULL default \'\',
  `message` longtext NOT NULL,
  `recipients` text NOT NULL,
  `attach` tinyint(4) NOT NULL default \'0\',
  `status` smallint(5) unsigned NOT NULL default \'0\',
  `emailid` varchar(255) NOT NULL default \'\',
  `source` longtext NOT NULL,
  `priority` tinyint(3) unsigned NOT NULL default \'3\',
  `size` int(10) unsigned NOT NULL default \'0\',
  PRIMARY KEY  (`messageid`),
  KEY `userfolder` (`userid`,`folderid`)
) TYPE=MyISAM',
  'pop' => 'CREATE TABLE `pop` (
  `popid` int(10) unsigned NOT NULL auto_increment,
  `userid` int(10) unsigned NOT NULL default \'0\',
  `server` varchar(75) NOT NULL default \'\',
  `username` varchar(50) NOT NULL default \'\',
  `password` text NOT NULL,
  `port` varchar(10) NOT NULL default \'\',
  `active` tinyint(3) unsigned NOT NULL default \'1\',
  `deletemails` tinyint(3) unsigned NOT NULL default \'1\',
  UNIQUE KEY `popid` (`popid`),
  KEY `userid` (`userid`)
) TYPE=MyISAM',
  'rule' => 'CREATE TABLE `rule` (
  `ruleid` int(10) unsigned NOT NULL auto_increment,
  `userid` int(10) unsigned NOT NULL default \'0\',
  `cond` varchar(255) NOT NULL default \'\',
  `action` varchar(255) NOT NULL default \'\',
  `active` tinyint(3) unsigned NOT NULL default \'1\',
  `display` smallint(5) unsigned NOT NULL default \'0\',
  UNIQUE KEY `ruleid` (`ruleid`),
  KEY `userid` (`userid`)
) TYPE=MyISAM',
  'search' => 'CREATE TABLE `search` (
  `searchid` int(10) unsigned NOT NULL auto_increment,
  `userid` int(10) unsigned NOT NULL default \'0\',
  `dateline` int(10) unsigned NOT NULL default \'0\',
  `data` mediumtext NOT NULL,
  UNIQUE KEY `searchid` (`searchid`)
) TYPE=MyISAM',
  'setting' => 'CREATE TABLE `setting` (
  `settingid` smallint(5) unsigned NOT NULL auto_increment,
  `settinggroupid` tinyint(3) unsigned NOT NULL default \'0\',
  `title` varchar(50) NOT NULL default \'\',
  `description` text NOT NULL,
  `variable` varchar(100) NOT NULL default \'\',
  `value` mediumtext NOT NULL,
  `type` varchar(250) NOT NULL default \'\',
  `display` tinyint(3) unsigned NOT NULL default \'0\',
  UNIQUE KEY `optionid` (`settingid`),
  KEY `optiongroupid` (`settinggroupid`)
) TYPE=MyISAM',
  'settinggroup' => 'CREATE TABLE `settinggroup` (
  `settinggroupid` tinyint(3) unsigned NOT NULL auto_increment,
  `title` varchar(50) NOT NULL default \'\',
  `description` text NOT NULL,
  `display` tinyint(3) unsigned NOT NULL default \'0\',
  UNIQUE KEY `optiongroupid` (`settinggroupid`)
) TYPE=MyISAM',
  'skin' => 'CREATE TABLE `skin` (
  `skinid` smallint(6) NOT NULL auto_increment,
  `title` varchar(25) NOT NULL default \'\',
  `templatesetid` smallint(5) unsigned NOT NULL default \'0\',
  `vars` longtext NOT NULL,
  UNIQUE KEY `skinid` (`skinid`)
) TYPE=MyISAM',
  'template' => 'CREATE TABLE `template` (
  `templateid` int(10) unsigned NOT NULL auto_increment,
  `title` varchar(50) NOT NULL default \'\',
  `templatesetid` smallint(6) NOT NULL default \'0\',
  `templategroupid` smallint(5) unsigned NOT NULL default \'0\',
  `user_data` mediumtext NOT NULL,
  `parsed_data` mediumtext NOT NULL,
  `backup_data` mediumtext NOT NULL,
  UNIQUE KEY `templateid` (`templateid`)
) TYPE=MyISAM',
  'templategroup' => 'CREATE TABLE `templategroup` (
  `templategroupid` smallint(5) unsigned NOT NULL auto_increment,
  `title` varchar(25) NOT NULL default \'\',
  `display` smallint(5) unsigned NOT NULL default \'0\',
  `description` text NOT NULL,
  UNIQUE KEY `templategroupid` (`templategroupid`)
) TYPE=MyISAM',
  'templateset' => 'CREATE TABLE `templateset` (
  `templatesetid` smallint(5) unsigned NOT NULL auto_increment,
  `title` varchar(25) NOT NULL default \'\',
  UNIQUE KEY `templatesetid` (`templatesetid`)
) TYPE=MyISAM',
  'user' => 'CREATE TABLE `user` (
  `userid` int(10) unsigned NOT NULL auto_increment,
  `username` varchar(35) NOT NULL default \'\',
  `password` varchar(32) NOT NULL default \'\',
  `altemail` varchar(200) NOT NULL default \'\',
  `usergroupid` smallint(5) unsigned NOT NULL default \'0\',
  `skinid` smallint(5) unsigned NOT NULL default \'0\',
  `realname` varchar(50) NOT NULL default \'\',
  `regdate` int(10) unsigned NOT NULL default \'0\',
  `lastvisit` int(10) unsigned NOT NULL default \'0\',
  `timezone` float NOT NULL default \'0\',
  `signature` mediumtext NOT NULL,
  `emptybin` smallint(6) NOT NULL default \'-1\',
  `lastbinempty` int(10) unsigned NOT NULL default \'0\',
  `blocked` text NOT NULL,
  `safe` text NOT NULL,
  `forward` varchar(50) NOT NULL default \'\',
  `autorefresh` smallint(5) unsigned NOT NULL default \'0\',
  `cols` varchar(255) NOT NULL default \'\',
  `birthday` date NOT NULL default \'0000-00-00\',
  `question` varchar(200) NOT NULL default \'\',
  `answer` varchar(32) NOT NULL default \'\',
  `country` char(2) NOT NULL default \'ot\',
  `state` char(2) NOT NULL default \'ot\',
  `zip` varchar(10) NOT NULL default \'\',
  `font` varchar(200) NOT NULL default \'\',
  `sendread` tinyint(4) NOT NULL default \'1\',
  `options` int(10) unsigned NOT NULL default \'0\',
  `haspop` tinyint(3) unsigned NOT NULL default \'0\',
  `perpage` smallint(5) unsigned NOT NULL default \'15\',
  `replyto` varchar(100) NOT NULL default \'\',
  `replychar` varchar(15) NOT NULL default \'\',
  `preview` varchar(10) NOT NULL default \'none\',
  UNIQUE KEY `userid` (`userid`)
) TYPE=MyISAM',
  'usergroup' => 'CREATE TABLE `usergroup` (
  `usergroupid` smallint(5) unsigned NOT NULL auto_increment,
  `title` varchar(25) NOT NULL default \'\',
  `maxmb` float NOT NULL default \'0\',
  `groupsig` text NOT NULL,
  `allowedskins` varchar(250) NOT NULL default \'\',
  `perms` smallint(5) unsigned NOT NULL default \'0\',
  UNIQUE KEY `usergroupid` (`usergroupid`)
) TYPE=MyISAM',
);

// ############################################################################
// Records
$inserts = array (
  0 => 'INSERT INTO setting VALUES (1, 1, \'Application name\', \'This is the name of the program that will be running. It will be viewable on every page.\', \'appname\', \'HiveMail\', \'text\', 1)',
  1 => 'INSERT INTO setting VALUES (2, 2, \'Open for registration\', \'Turn this off if you do not want to accept new users.\', \'regopen\', 1, \'yesno\', 1)',
  2 => 'INSERT INTO setting VALUES (3, 2, \'Require new user validation\', \'If this is turned on, users will not be able to use HiveMail until you activate your account. Otherwise, after registering the user\\\'s account will be activated immediately.\', \'moderate\', 0, \'yesno\', 2)',
  3 => 'INSERT INTO setting VALUES (4, 1, \'Default skin\', \'This is the default skin that will be used when a user is not logged in or when a user first registers.\', \'defaultskin\', 1, \'select_skin\', 4)',
  4 => 'INSERT INTO setting VALUES (5, 1, \'Domain name\', \'The domain name HiveMail is running on, with a preceding \\\'@\\\' character.<br />For example: @youdomain.com, @example.net, etc.\', \'domainname\', \'@youdomain.com\', \'text\', 3)',
  5 => 'INSERT INTO setting VALUES (6, 1, \'Threshold for storage gauge\', \'The space gauge will not show up for users until they hit this minimum percentage.<br />It is recommended to set this to a high value, such as 80. Do not include \\\'%\\\'.\', \'minpercentforgauge\', 80, \'text\', 5)',
  6 => 'INSERT INTO setting VALUES (7, 7, \'Date format\', \'The format for displaying date.<br />For example: \\"F j, Y\\" will display July 1, 2002, \\"m-d-Y\\" will display 07-01-2002.<br />For more possible combinations, please see the <a href=\\"http://www.php.net/manual/en/function.date.php\\" target=\\"_blank\\">PHP manual</a>.\', \'dateformat\', \'m-d-Y\', \'text\', 2)',
  7 => 'INSERT INTO setting VALUES (8, 7, \'Time format\', \'The format for displaying time.<br />For example: \\"H:i\\" will display 15:49, \\"h:i A\\" will display 03:49 PM.<br />For more possible combinations, please see the <a href=\\"http://www.php.net/manual/en/function.date.php\\" target=\\"_blank\\">PHP manual</a>.\', \'timeformat\', \'h:i A\', \'text\', 3)',
  8 => 'INSERT INTO setting VALUES (9, 5, \'Use enhanced top menu\', \'Enable the advanced menu for the control panel. Please note that this menu might now work on some browsers.\', \'cp_topmenu\', 0, \'yesno\', 1)',
  9 => 'INSERT INTO setting VALUES (10, 4, \'Enable POP3 gateway\', \'Enable or disable the POP3 gateway. If you have the Pipe gateway installed, you should leave this turned off. Otherwise, turn it on.\', \'pop3_use\', 0, \'yesno\', 2)',
  10 => 'INSERT INTO setting VALUES (11, 4, \'Mail server\', \'The server that holds the POP3 account. If you are not sure what the mail server is, contact your host and ask them\', \'pop3_server\', \'\', \'text\', 3)',
  11 => 'INSERT INTO setting VALUES (12, 4, \'Server port\', \'The port that\\\'s used to connect to the mail server above, usually this is 110.\', \'pop3_port\', 110, \'text\', 4)',
  12 => 'INSERT INTO setting VALUES (13, 4, \'Username\', \'The username that is used to log on to the mail server and connect to the POP3 account.\', \'pop3_username\', \'\', \'text\', 5)',
  13 => 'INSERT INTO setting VALUES (14, 4, \'Password\', \'The password that is used to log on to the mail server and connect to the POP3 account.\', \'pop3_password\', \'\', \'text\', 6)',
  14 => 'INSERT INTO setting VALUES (15, 4, \'note\', \'note\', \'note\', \'<b>Please see the installation guide for help regarding these settings.</b>\', \'note\', 1)',
  15 => 'INSERT INTO setting VALUES (16, 6, \'Sender Name\', \'The name that\\\'s sent as the \\\'From\\\' header when sending bounces or other messages through the system.<br />This can be an email address only (e.g mail@example.com) or a name with the email address enclosed in < and > (e.g Mail Delivery System <mail@example.com>).\', \'smtp_errorfrom\', \'Mail Delivery System <mail@youdomain.com>\', \'text\', 1)',
  16 => 'INSERT INTO setting VALUES (17, 5, \'Enable expand/collapse\', \'Turn on the [+] and [-] icons that expand and collapse tables in the control panel.\', \'cp_plus\', 0, \'yesno\', 2)',
  17 => 'INSERT INTO setting VALUES (18, 2, \'Maximum messages per page\', \'Set this to the maximum number of mail messages users can request to see per page.\', \'maxperpage\', 50, \'text\', 3)',
  18 => 'INSERT INTO setting VALUES (19, 1, \'Program location\', \'The complete URL (without any trailing slashes) of the program, e.g: http://www.example.com/webmail.\', \'appurl\', \'http://beta.youdomain.com\', \'text\', 2)',
  19 => 'INSERT INTO setting VALUES (20, 7, \'Time zone offset\', \'The time (in hours) this server is offset from GMT. Please select the most appropriate option.<br />For a complete list of time zones, please <a href=\\"http://greenwichmeantime.com/info/timezone.htm\\" target=\\"_blank\\">click here</a>.\', \'timeoffset\', 0, \'timezone\', 1)',
  20 => 'INSERT INTO settinggroup VALUES (1, \'General Options\', \'General options that don\\\'t fit into any other groups.\', 1)',
  21 => 'INSERT INTO settinggroup VALUES (2, \'User Options\', \'Options that deal with user settings.\', 2)',
  22 => 'INSERT INTO settinggroup VALUES (3, \'Internal Variables\', \'Do NOT edit.\', 0)',
  23 => 'INSERT INTO settinggroup VALUES (4, \'POP3 Gateway\', \'This group holds all information that is used to operate the POP3 gateway. If you are using the Pipe gateway, you do not need to touch any of these settings.\', 4)',
  24 => 'INSERT INTO settinggroup VALUES (5, \'Admin Control Panel\', \'Settings that only effect this control panel.\', 5)',
  25 => 'INSERT INTO settinggroup VALUES (6, \'Email Settings\', \'Configuration values for sending email from the software.\', 6)',
  26 => 'INSERT INTO settinggroup VALUES (7, \'Time and Date\', \'Set the time zone offset of the server and change display formats.\', 3)',
  27 => 'INSERT INTO skin VALUES (1, \'Original\', 1, \'array(\\n	\\\'doctype\\\' => \\\'<!DOCTYPE html PUBLIC \\"-//W3C//DTD XHTML 1.0 Transitional//EN\\" \\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\\">\\\',\\n	\\\'images\\\' => \\\'images\\\',\\n	\\\'body\\\' => \\\'topmargin=\\"0\\" leftmargin=\\"0\\" marginheight=\\"0\\" marginwidth=\\"0\\"\\\',\\n	\\\'fontface\\\' => \\\'Verdana, sans-serif\\\',\\n	\\\'normalsize\\\' => \\\'12px\\\',\\n	\\\'smallsize\\\' => \\\'10px\\\',\\n	\\\'tableheadfontcolor\\\' => \\\'#447892\\\',\\n	\\\'timecolor\\\' => \\\'#42517A\\\',\\n	\\\'linkhovercolor\\\' => \\\'#999999\\\',\\n	\\\'highcolor\\\' => \\\'red\\\',\\n	\\\'linkcolor\\\' => \\\'#000020\\\',\\n	\\\'border_normal_horizonal_width\\\' => \\\'1px\\\',\\n	\\\'border_normal_vertical_width\\\' => \\\'0px\\\',\\n	\\\'border_normal_edges_width\\\' => \\\'1px\\\',\\n	\\\'border_normal_color\\\' => \\\'#E1E1E1\\\',\\n	\\\'border_normal_style\\\' => \\\'solid\\\',\\n	\\\'border_header_horizonal_width\\\' => \\\'1px\\\',\\n	\\\'border_header_vertical_width\\\' => \\\'0px\\\',\\n	\\\'border_header_edges_width\\\' => \\\'1px\\\',\\n	\\\'border_header_color\\\' => \\\'#80B4DB\\\',\\n	\\\'border_header_style\\\' => \\\'solid\\\',\\n	\\\'tableheadbgcolor\\\' => \\\'#E9F3F8 url(\\\\\\\'images/tableheadbg.gif\\\\\\\')\\\',\\n	\\\'firstalt\\\' => \\\'#FFFFFF\\\',\\n	\\\'secondalt\\\' => \\\'#F4F4F4\\\',\\n	\\\'formbackground\\\' => \\\'#E1E4E5\\\',\\n	\\\'pagebgcolor\\\' => \\\'#FFFFFF\\\',\\n)\')',
  28 => 'INSERT INTO templategroup VALUES (3, \'Compose Screen\', 4, \'\')',
  29 => 'INSERT INTO templategroup VALUES (1, \'General\', 1, \'\')',
  30 => 'INSERT INTO templategroup VALUES (2, \'Folder View\', 2, \'\')',
  31 => 'INSERT INTO templategroup VALUES (4, \'Address Book\', 5, \'\')',
  32 => 'INSERT INTO templategroup VALUES (5, \'Searching\', 7, \'\')',
  33 => 'INSERT INTO templategroup VALUES (6, \'Email Reading\', 6, \'\')',
  34 => 'INSERT INTO templategroup VALUES (7, \'Error Messages\', 8, \'\')',
  35 => 'INSERT INTO templategroup VALUES (8, \'Redirect Messages\', 9, \'\')',
  36 => 'INSERT INTO templategroup VALUES (9, \'Folders Management\', 10, \'\')',
  37 => 'INSERT INTO templategroup VALUES (10, \'Message Rules\', 11, \'\')',
  38 => 'INSERT INTO templategroup VALUES (11, \'Page Navigation\', 12, \'\')',
  39 => 'INSERT INTO templategroup VALUES (12, \'Miscellaneous\', 0, \'\')',
  40 => 'INSERT INTO templategroup VALUES (13, \'Preferences\', 14, \'\')',
  41 => 'INSERT INTO templategroup VALUES (14, \'Signup Process\', 15, \'\')',
  42 => 'INSERT INTO templategroup VALUES (15, \'POP Accounts Management\', 13, \'\')',
  43 => 'INSERT INTO templategroup VALUES (16, \'Mail Display\', 3, \'\')',
  44 => 'INSERT INTO templateset VALUES (1, \'Default\')',
  45 => 'INSERT INTO usergroup VALUES (1, \'Administrators\', 5, \'\', 1, 511)',
  46 => 'INSERT INTO usergroup VALUES (2, \'Regular Users\', 5, \'\', 1, 479)',
  47 => 'INSERT INTO usergroup VALUES (3, \'Awaiting Validation\', 5, \'\', 1, 0)',
);

?>