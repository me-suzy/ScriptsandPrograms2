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
$tables[1] = array (
	'adminlog' => 'CREATE TABLE adminlog (
		adminlogid int(10) unsigned NOT NULL auto_increment,
		dateline int(10) unsigned NOT NULL default "0",
		do varchar(200) NOT NULL default "",
		filename varchar(200) NOT NULL default "",
		userid int(10) unsigned NOT NULL default "0",
		id int(10) unsigned NOT NULL default "0",
		success tinyint(4) NOT NULL default "-1",
		notes text NOT NULL,
		ipaddress varchar(20) NOT NULL default "",
		PRIMARY KEY  (adminlogid),
		UNIQUE KEY adminlogid (adminlogid)
		) TYPE=MyISAM',
	'emailid' => 'CREATE TABLE emailid (
		userid int(10) unsigned NOT NULL default "0",
		emailid varchar(255) NOT NULL default "",
		INDEX (userid)
		) TYPE=MyISAM',
	'pop' => 'ALTER TABLE pop
		ADD folderid int(11) NOT NULL default "-1"',
	'sig' => 'CREATE TABLE sig (
		sigid int(10) unsigned NOT NULL auto_increment,
		userid int(10) unsigned NOT NULL default "0",
		name varchar(50) NOT NULL default "",
		signature text NOT NULL,
		isdefault tinyint(3) unsigned NOT NULL default "1",
		UNIQUE KEY sigid (sigid),
		KEY userid (userid)
		) TYPE=MyISAM',
	'sound' => 'CREATE TABLE sound (
		soundid int(10) unsigned NOT NULL auto_increment,
		userid int(10) unsigned NOT NULL default "0",
		filename varchar(100) NOT NULL default "",
		data mediumtext NOT NULL,
		UNIQUE KEY soundid (soundid),
		KEY userid (userid)
		) TYPE=MyISAM',
	'user' => 'ALTER TABLE user
		ADD notifyemail varchar(50) NOT NULL default "", ADD soundid int(10) unsigned NOT NULL default "0"',
	'usergroup' => 'ALTER TABLE usergroup
		ADD groupsig_html text NOT NULL, ADD maxattach float NOT NULL default "0", ADD maxsigs smallint(5) unsigned NOT NULL default "0", 
		CHANGE groupsig groupsig_text text NOT NULL',
);

// ############################################################################
// Records
$inserts[1] = array (
	'UPDATE setting SET title = "Domain names", description = "The domain name HiveMail&trade; is running on, with a preceding \'@\' character.<br />To specify multiple domain names, put each one on its own line.<br />The first domain name on the list is the primary one.<br /><br />For example:<br />@yourmail.com<br />@example.net", type = "area", display = 3 WHERE variable = "domainname"',
	'UPDATE setting SET description = "Enable the advanced menu for the control panel. Please note that this menu might not work on some browsers." WHERE variable = "cp_topmenu"',
	'UPDATE setting SET display = 5 WHERE variable = "maxperpage"',
	'UPDATE usergroup SET groupsig_html = REPLACE(groupsig_text, "'."\n".'", "<br />"), perms = perms + 1536',
	'INSERT INTO sig SELECT NULL AS sigid, userid, "Default" AS name, signature, 1 AS isdefault FROM user WHERE signature <> ""',
	'INSERT INTO settinggroup VALUES (8, "Safe Mode", "If PHP is running in Safe Mode you should modify these settings to make sure the program operates fine.", 7)',
	'INSERT INTO setting VALUES (NULL, 3, "Last time POP3 was checked", "Do not touch.", "pop3_lastrun", 1037544575, "text", 0)',
	'INSERT INTO setting VALUES (NULL, 4, "Check the account every:", "The frequency for checking the POP3 account for messages, in minutes.<br />Set this to 0 to check the account whenever a user visits the system (not advisable).", "pop3_runevery", 2, "text", 8)',
	'INSERT INTO setting VALUES (NULL, 4, "Using cron job script?", "If you are using the cron script, set this to Yes. If this is set to Yes, email will not be checked from within the program but only using the cron job script.<br />Note: You need to set up the script yourself, this is not automatic!", "pop3_cron", 0, "yesno", 3)',
	'INSERT INTO setting VALUES (NULL, 2, "Send new users a greeting message", "Turn this on to send a welcome email to all new users.<br />The message and subject of this email can be edited in the<br />signup_welcome_subject and signup_welcome_message templates.", "sendgreeting", 1, "yesno", 3)',
	'INSERT INTO setting VALUES (NULL, 2, "Reserved names", "Text in this setting (one entry per line) will not be allowed in usernames or real names. This can contain wildcards. Examples:<br />admin* - name beginning with admin<br />*hive* - name containing hive<br />*site - name ending in site<br />web*master - name beginning with web and ending in master (including webmaster)", "reservedtext", "admin*\n*master", "area", 6)',
	'INSERT INTO setting VALUES (NULL, 2, "Maximum size of sound files", "This is the maximum file size of custom sound files users can upload. (in bytes)", "maxsoundfile", 102400, "text", 7)',
	'INSERT INTO setting VALUES (NULL, 1, "Self-referenced attachments", "Sometimes messages contain attachments that are used inside the message itself (background images, sounds, etc.). This option allows you to enable this self-reference, but unfortunately this behavior is sometimes used by viruses. This is why we give you the option to disable this feature altogether.", "allowcid", 0, "yesno", 6)',
	'INSERT INTO setting VALUES (NULL, 2, "Notification of new users", "Whenever a new user registers, an email notification will be sent to these addresses.<br />Separate address with space or new line.<br />The message and subject of this email can be edited in the<br />signup_notify_subject and signup_notify_message templates.", "newuseremail", "", "area", 4)',
	'INSERT INTO setting VALUES (NULL, 4, "Maximum messages to process", "This is the maximum number of messages that are processed every time a connection is made to the mailbox.", "pop3_maxmsgs", 50, "text", 10)',
	'INSERT INTO setting VALUES (NULL, 5, "Enable enhanced template tree", "Whether or not the dynamic template tree will be used in the template editor. This feature may only work in certain browsers.", "cp_templatetree", 0, "yesno", 3)',
	'INSERT INTO setting VALUES (NULL, 4, "Leave messages on mailbox", "If this is set to yes, messages will be left on the server even after HiveMail&trade; downloads them. We do not recommended that enable this option!", "pop3_nodelete", 0, "yesno", 9)',
	'INSERT INTO setting VALUES (NULL, 3, "HiveMail&trade; Version", "Don\'t touch!", "versionnum", 1.1, "text", 0)',
	'INSERT INTO setting VALUES (NULL, 1, "Use redirection messages", "HiveMail&trade; displays redirection messages when users perform an action that requires processing. You can disable these messages here, and then your users will be redirected immediately, without any confirmation for their action.", "useredirect", 1, "yesno", 7)',
	'INSERT INTO setting VALUES (NULL, 8, "Upload attachments in safe mode", "If your server is running in Safe Mode or if open_basedir restrictions are in effect, enable this option and update the setting below.", "safeupload", "0", "yesno", 1)',
	'INSERT INTO setting VALUES (NULL, 8, "Temporary director for attachments", "If you enable uploads in Safe Mode, this must be set to the folder in which the attachments will be <i>temporarily</i> stored. PHP must have write permission in this folder, i.e CHMOD 0777.", "tmppath", "/tmp", "text", 2)',
	'UPDATE setting SET display = 4 WHERE variable = "pop3_server"',
	'UPDATE setting SET display = 5 WHERE variable = "pop3_port"',
	'UPDATE setting SET display = 6 WHERE variable = "pop3_username"',
	'UPDATE setting SET display = 7 WHERE variable = "pop3_password"',
	'UPDATE template SET title = "signup_activate_message" WHERE title = "signup_email"',
	'UPDATE template SET title = "signup_activate_subject" WHERE title = "signup_email_subject"',
	'INSERT INTO emailid SELECT userid, emailid FROM message WHERE emailid <> ""',
);

?>