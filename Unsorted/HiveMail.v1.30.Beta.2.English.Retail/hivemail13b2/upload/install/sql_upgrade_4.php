<?php
// +-------------------------------------------------------------+
// | HiveMail version 1.3 Beta 2 (English)
// | Copyright ©2002-2003 Chen Avinadav
// | Supplied by Scoons [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | HIVEMAIL IS NOT FREE SOFTWARE
// | If you have downloaded this software from a website other
// +-------------------------------------------------------------+
// +-------------------------------------------------------------+

// ############################################################################
// Table structure
$tables[4] = array (
	'hive_' => "RENAME TABLE
		adminlog TO hive_adminlog,
		contact TO hive_contact,
		cplink TO hive_cplink,
		draft TO hive_draft,
		emailid TO hive_emailid,
		eventlog TO hive_eventlog,
		field TO hive_field,
		fieldinfo TO hive_fieldinfo,
		folder TO hive_folder,
		message TO hive_message,
		messagefile TO hive_messagefile,
		pop TO hive_pop,
		response TO hive_response,
		rule TO hive_rule,
		search TO hive_search,
		setting TO hive_setting,
		settinggroup TO hive_settinggroup,
		sig TO hive_sig,
		skin TO hive_skin,
		sound TO hive_sound,
		template TO hive_template,
		templategroup TO hive_templategroup,
		templateset TO hive_templateset,
		user TO hive_user,
		usergroup TO hive_usergroup",
	'hive_alias' => "CREATE TABLE hive_alias (
		aliasid int(10) unsigned NOT NULL auto_increment,
		userid int(10) unsigned NOT NULL default '0',
		alias varchar(255) NOT NULL default '',
		PRIMARY KEY  (aliasid),
		UNIQUE KEY alias (alias),
		KEY userid (userid)
	) TYPE=MyISAM",
	'hive_event' => "CREATE TABLE hive_event (
		eventid int(10) unsigned NOT NULL auto_increment,
		userid int(10) unsigned NOT NULL default '0',
		title varchar(255) NOT NULL default '',
		message mediumtext NOT NULL,
		addresses mediumtext NOT NULL,
		from_date int(10) unsigned NOT NULL default '0',
		to_date int(10) unsigned NOT NULL default '0',
		to_date_real int(10) unsigned NOT NULL default '0',
		from_time time NOT NULL default '00:00:00',
		to_time time NOT NULL default '00:00:00',
		allday tinyint(1) unsigned NOT NULL default '0',
		type tinyint(3) unsigned NOT NULL default '0',
		options varchar(20) NOT NULL default '',
		maxtimes smallint(5) unsigned NOT NULL default '0',
		PRIMARY KEY  (eventid),
		KEY userdate (userid,to_date_real,from_date)
	) TYPE=MyISAM",
	'hive_iplog' => "CREATE TABLE hive_iplog (
		iplogid int(10) unsigned NOT NULL auto_increment,
		userid int(10) unsigned default '0',
		datefirstseen int(10) unsigned default '0',
		datelastseen int(10) unsigned default '0',
		ipaddr varchar(16) default '0',
		PRIMARY KEY  (iplogid),
		KEY userid (userid)
	) TYPE=MyISAM",
	'hive_draft' => "ALTER TABLE hive_draft
		DROP KEY dateline,
		ADD KEY dateline (dateline,userid)",
	'hive_field' => "ALTER TABLE hive_field
		CHANGE module module set('user','book') NOT NULL default 'user',
		CHANGE display display smallint(6) unsigned NOT NULL default '0',
		ADD KEY dispmod (display,module)",
	'hive_fieldinfo' => "ALTER TABLE hive_fieldinfo
		ADD KEY userid (userid)",
	'hive_folder' => "ALTER TABLE hive_folder
		ADD display smallint(5) unsigned NOT NULL default '0'",
	'hive_message' => "ALTER TABLE hive_message
		ADD popid int(10) unsigned NOT NULL default '0'",
	'hive_pop' => "ALTER TABLE hive_pop
		ADD accountname varchar(25) NOT NULL default '',
		ADD displayname varchar(50) NOT NULL default '',
		ADD displayemail varchar(100) NOT NULL default '',
		ADD color varchar(20) NOT NULL default 'none',
		ADD smtp_server varchar(75) NOT NULL default '',
		ADD smtp_port varchar(10) NOT NULL default '25',
		ADD smtp_username varchar(50) NOT NULL default '',
		ADD smtp_password text NOT NULL,
		CHANGE port port varchar(10) NOT NULL default '110',
		CHANGE active autopoll tinyint(3) unsigned NOT NULL default '1'",
	'hive_search' => "ALTER TABLE hive_search
		ADD KEY dateline (dateline)",
	'hive_template' => "ALTER TABLE hive_template
		ADD KEY titleset (title,templatesetid)",
	'hive_user' => "ALTER TABLE hive_user
		ADD lastactivity int(10) unsigned NOT NULL default '0',
		ADD markread tinyint(3) unsigned NOT NULL default '0',
		ADD fieldcache mediumtext NOT NULL,
		ADD aliases mediumtext NOT NULL,
		ADD weekstart tinyint(3) unsigned NOT NULL default '0',
		ADD lastsent int(10) unsigned NOT NULL default '0',
		ADD KEY username (username), ADD KEY lastactivity (lastactivity)",
	'hive_usergroup' => "ALTER TABLE hive_usergroup
		CHANGE perms perms int(10) unsigned NOT NULL default '0',
		ADD maxaliases smallint(5) unsigned NOT NULL default '0',
		ADD msgpersec varchar(255) NOT NULL default ''",
);

// ############################################################################
// Records
$inserts[4] = array (
	"UPDATE hive_settinggroup SET display = 18 WHERE display = 17",
	"UPDATE hive_setting SET description = 'If this is turned on, users will not be able to use HiveMail&trade; until you activate their account. Otherwise, after registering the user\'s account will be activated immediately.' WHERE variable = 'moderate'",
	"UPDATE hive_setting SET settinggroupid = 2 WHERE variable = 'minpercentforgauge'",
	"UPDATE hive_setting SET description = 'The name that\'s sent as the \'From\' header when sending bounces or other messages through the system.<br />This can be an email address only (e.g mail@example.com) or a name with the email address enclosed in < and > (e.g Mail Delivery System &lt;mail@example.com&gt;).' WHERE variable = 'smtp_errorfrom'",
	"UPDATE hive_setting SET description = 'If this is set to yes, messages will be left on the server even after HiveMail&trade; downloads them. We strongly recommended that you leave this option turned off.' WHERE variable = 'pop3_nodelete'",
	"UPDATE hive_setting SET display = 6 WHERE variable = 'maintain'",
	"UPDATE hive_usergroup SET perms = perms + 64 + 32768 WHERE usergroupid = 1",
	"UPDATE hive_usergroup SET perms = perms + 65536 + 131072 WHERE usergroupid <> 3",
	"UPDATE hive_usergroup SET maxaliases = 5 WHERE usergroupid NOT IN (1, 3)",
	"UPDATE hive_usergroup SET msgpersec = 'a:2:{s:5:\\\"every\\\";i:0;s:4:\\\"unit\\\";i:60;}' WHERE usergroupid IN (1, 3)",
	"UPDATE hive_usergroup SET msgpersec = 'a:2:{s:5:\\\"every\\\";s:2:\\\"30\\\";s:4:\\\"unit\\\";s:1:\\\"1\\\";}' WHERE usergroupid NOT IN (1, 3)",
	"UPDATE hive_user SET aliases = username, options = options + 4194304 + 67108864 + 268435456 + 536870912",
	"INSERT INTO hive_templategroup VALUES (NULL, 'Calendar', 17, '')",
	"INSERT INTO hive_setting VALUES (NULL, 1, 'Cookie domain', 'Domain for which cookies will be set. Leave blank to use automatic setting.<br /><br /><b>Note:</b> All domains must contain at least 2 periods (e.g. example.com should be written as .example.com)', 'cookiedomain', '', 'text', 12)",
	"INSERT INTO hive_setting VALUES (NULL, 1, 'Cookie Path', 'Path for which cookies will be set.', 'cookiepath', '/', 'text', 13)",
	"INSERT INTO hive_setting VALUES (NULL, 2, 'Maximum online users', 'Use this setting to control how many users can simultaneously use the system (concurrent users are determined from averages over 15 minute periods), set to 0 to disable this feature.<br /> You can set user groups to allow users to use the system regardless of this limit.', 'maxonline', 0, 'text', 14)",
	"INSERT INTO hive_setting VALUES (NULL, 6, 'Catch-all user', 'Messages that are sent to non-existent users will be forwarded to this user (please only enter the username, and not the full email address!). If you would like to bounce such messages back to the sender, please leave this setting empty.', 'catchalluser', '', 'text', 3)",
	"INSERT INTO hive_setting VALUES (NULL, 11, 'Ignore messages by address', 'If you would like to not process any messages that are sent to certain addresses, write down those addresses here. This can be a list of names only (i.e \'nobody\') or full email address (i.e \'nobody@domain.com\'). Separate addresses and names with space or a new line.', 'ignore_addys', 'nobody postmaster', 'area', 4)",
	"INSERT INTO hive_setting VALUES (NULL, 1, 'Inbox file name', 'If you change the name of the file index.php, please enter the new name here so HiveMail&trade; will know where it is located. Don\'t forget the extension, i.e home.php.', 'indexname', 'index.php', 'text', 14)",
	"INSERT INTO hive_alias SELECT NULL AS aliasid, userid, username FROM hive_user",
	"DELETE FROM hive_template WHERE title = 'pop_nopops'",
);

?>