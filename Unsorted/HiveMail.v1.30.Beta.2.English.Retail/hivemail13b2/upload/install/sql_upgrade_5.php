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
$tables[5] = array(
	'hive_ban' => 'CREATE TABLE hive_ban (
		banid int(10) unsigned NOT NULL auto_increment,
		userid int(10) unsigned NOT NULL default \'0\',
		adminid int(10) unsigned NOT NULL default \'0\',
		dateline int(10) unsigned NOT NULL default \'0\',
		duration int(11) NOT NULL default \'0\',
		reason mediumtext NOT NULL,
		PRIMARY KEY	(banid),
		KEY userid (userid)
	) TYPE=MyISAM',
	'hive_cart' => 'CREATE TABLE hive_cart (
		cartid int(10) unsigned NOT NULL auto_increment,
		planid int(10) unsigned NOT NULL default \'0\',
		userid int(10) unsigned NOT NULL default \'0\',
		dateline int(10) unsigned NOT NULL default \'0\',
		PRIMARY KEY	(cartid),
		KEY dateline (dateline)
	) TYPE=MyISAM',
	'hive_contactgroup' => 'CREATE TABLE hive_contactgroup (
		contactgroupid int(10) unsigned NOT NULL auto_increment,
		userid int(10) unsigned NOT NULL default \'0\',
		title varchar(255) NOT NULL default \'\',
		contacts text NOT NULL,
		PRIMARY KEY	(contactgroupid),
		KEY userid (userid)
	) TYPE=MyISAM',
	'hive_distlist' => 'CREATE TABLE hive_distlist (
		distlistid smallint(5) unsigned NOT NULL auto_increment,
		toalias varchar(100) NOT NULL default \'\',
		recipients mediumtext NOT NULL,
		PRIMARY KEY	(distlistid)
	) TYPE=MyISAM',
	'hive_payment' => 'CREATE TABLE hive_payment (
		paymentid int(10) unsigned NOT NULL auto_increment,
		planid smallint(5) unsigned NOT NULL default \'0\',
		subscriptionid int(10) unsigned NOT NULL default \'0\',
		userid int(10) unsigned NOT NULL default \'0\',
		processor varchar(10) NOT NULL default \'\',
		dateline int(10) unsigned NOT NULL default \'0\',
		remoteid varchar(150) NOT NULL default \'\',
		amount float NOT NULL default \'0\',
		pseudo tinyint(3) unsigned NOT NULL default \'0\',
		refunddate int(10) unsigned NOT NULL default \'0\',
		debuginfo mediumtext NOT NULL,
		PRIMARY KEY	(paymentid),
		UNIQUE KEY remoteid (remoteid,processor),
		KEY subscriptionid (subscriptionid),
		KEY userid (userid)
	) TYPE=MyISAM',
	'hive_plan' => 'CREATE TABLE hive_plan (
		planid smallint(5) unsigned NOT NULL auto_increment,
		name varchar(50) NOT NULL default \'\',
		usergroupid smallint(5) unsigned NOT NULL default \'0\',
		processors varchar(255) NOT NULL default \'\',
		description mediumtext NOT NULL,
		cost float NOT NULL default \'0\',
		length tinyint(3) unsigned NOT NULL default \'0\',
		unit char(1) NOT NULL default \'m\',
		canpayadvance tinyint(3) unsigned NOT NULL default \'1\',
		reminder tinyint(3) unsigned NOT NULL default \'0\',
		PRIMARY KEY	(planid)
	) TYPE=MyISAM',
	'hive_report' => 'CREATE TABLE hive_report (
		reportid int(10) unsigned NOT NULL auto_increment,
		userid int(10) unsigned NOT NULL default \'0\',
		opendate int(10) unsigned NOT NULL default \'0\',
		closedate int(10) unsigned NOT NULL default \'0\',
		email varchar(255) NOT NULL default \'\',
		name varchar(255) NOT NULL default \'\',
		subject varchar(255) NOT NULL default \'\',
		source mediumtext NOT NULL,
		reason mediumtext NOT NULL,
		PRIMARY KEY	(reportid),
		KEY email (email)
	) TYPE=MyISAM',
	'hive_subscription' => 'CREATE TABLE hive_subscription (
		subscriptionid int(10) unsigned NOT NULL auto_increment,
		planid smallint(5) unsigned NOT NULL default \'0\',
		userid int(10) unsigned NOT NULL default \'0\',
		oldusergroup smallint(5) unsigned NOT NULL default \'0\',
		processor varchar(10) NOT NULL default \'\',
		active tinyint(3) unsigned NOT NULL default \'1\',
		payments tinyint(3) unsigned NOT NULL default \'0\',
		startdate int(10) unsigned NOT NULL default \'0\',
		lastpaydate int(10) unsigned NOT NULL default \'0\',
		expirydate int(11) NOT NULL default \'0\',
		PRIMARY KEY	(subscriptionid),
		KEY planid (planid),
		KEY expirydate (expirydate)
	) TYPE=MyISAM',
	'hive_subscriptionlog' => 'CREATE TABLE hive_subscriptionlog (
		subscriptionid int(10) unsigned NOT NULL default \'0\',
		action tinyint(3) unsigned NOT NULL default \'0\',
		dateline int(10) unsigned NOT NULL default \'0\',
		pseudo tinyint(3) unsigned NOT NULL default \'0\',
		KEY subscriptionid (subscriptionid)
	) TYPE=MyISAM',
	'hive_temp' => 'CREATE TABLE hive_temp (
		tempid int(10) unsigned NOT NULL auto_increment,
		tempdata text NOT NULL,
		PRIMARY KEY	(tempid)
	) TYPE=MyISAM',
	'hive_word' => 'CREATE TABLE hive_word (
		userid int(10) unsigned NOT NULL default \'0\',
		word char(50) NOT NULL default \'\',
		metaphone char(50) NOT NULL default \'\',
		PRIMARY KEY	(userid,word)
	) TYPE=MyISAM',
	'hive_contact' => 'ALTER TABLE hive_contact
		ADD emailinfo text NOT NULL,
		ADD nameinfo text NOT NULL,
		ADD birthday date NOT NULL default \'0000-00-00\',
		ADD timezone float NOT NULL default \'0\',
		ADD webpage varchar(200) NOT NULL default \'\',
		ADD notes text NOT NULL,
		ADD addressinfo mediumtext NOT NULL,
		ADD phoneinfo mediumtext NOT NULL,
		CHANGE email email varchar(100) NOT NULL default \'\',
		CHANGE name name varchar(255) NOT NULL default \'\'',
	'hive_emailid' => 'ALTER TABLE hive_emailid
		ADD KEY emailid (emailid)',
	'hive_event' => 'ALTER TABLE hive_event
		ADD eventtype int(10) NOT NULL default \'0\',
		ADD aliasids mediumtext NOT NULL,
		ADD shareoptions int(10) unsigned NOT NULL default \'0\',
		ADD notes mediumtext NOT NULL,
		CHANGE userid userid int(10) NOT NULL default \'0\'',
	'hive_folder' => 'ALTER TABLE hive_folder
		CHANGE title title varchar(50) NOT NULL default \'\'',
	'hive_message' => 'ALTER TABLE hive_message
		ADD flagged tinyint(3) unsigned NOT NULL default \'0\',
		ADD bgcolor varchar(15) NOT NULL default \'\',
		ADD uniquestr varchar(15) NOT NULL default \'\',
		ADD notes text NOT NULL',
	'hive_pop' => 'ALTER TABLE hive_pop
		ADD replyto varchar(100) NOT NULL default \'\',
		ADD usessl tinyint(3) unsigned NOT NULL default \'0\'',
	'hive_response' => 'ALTER TABLE hive_response
		ADD response_tagless text NOT NULL',
	'hive_setting' => 'ALTER TABLE hive_setting
		ADD UNIQUE KEY variable (variable)',
	'hive_sig' => 'ALTER TABLE hive_sig
		ADD signature_tagless text NOT NULL',
	'hive_template' => 'ALTER TABLE hive_template
		ADD upgraded tinyint(3) unsigned NOT NULL default \'0\'',
	'hive_user' => 'ALTER TABLE hive_user
		ADD regipaddr varchar(15) NOT NULL default \'\',
		ADD options2 int(10) unsigned NOT NULL default \'0\',
		ADD aliaslog mediumtext NOT NULL,
		ADD lastpassword int(10) unsigned NOT NULL default \'0\',
		ADD adminnotes mediumtext NOT NULL,
		ADD calreminder tinyint(3) unsigned NOT NULL default \'0\',
		ADD defcompose varchar(45) NOT NULL default \'\',
		ADD draftcache mediumtext NOT NULL,
		ADD foldercache mediumtext NOT NULL,
		ADD subexpiry int(10) unsigned NOT NULL default \'0\',
		ADD savetopop tinyint(3) unsigned NOT NULL default \'0\',
		ADD lastexpirynotice int(10) unsigned NOT NULL default \'0\',
		ADD KEY regdate (regdate)',
	'hive_usergroup' => 'ALTER TABLE hive_usergroup
		ADD changepass smallint(5) unsigned NOT NULL default \'0\',
		ADD PRIMARY KEY	(usergroupid)',
);

// ############################################################################
// Records
$inserts[5] = array(
	'DELETE FROM hive_setting WHERE variable IN (\'sendipaddress\', \'note\', \'pop3_use\')',
	'INSERT INTO hive_setting VALUES (92, 6, \'Enable message bounces\', \'If this is turned on, messages that were sent to non-existent users will be bounced back to the sender. Otherwise, the messages will not be processed and the sender will not be notified of the error.\', \'bounceback\', 1, \'yesno\', 2)',
	'INSERT INTO hive_setting VALUES (91, 11, \'Check for SpamAssassin flags\', \'If <a href=\\"http://www.spamassassin.org/\\" target=\\"_blank\\">SpamAssassin</a> is installed on the server and it is checking incoming messages, turn this on to filter messages that were marked as spam.\', \'checkassassin\', 0, \'yesno\', 6)',
	'INSERT INTO hive_setting VALUES (100, 4, \'Use IMAP functions\', \'If PHP on this server is compiled with the IMAP extension, HiveMail&trade; can use it to connect to outside POP3 accounts. Turning this option on will also allow your users to connect to POP3 servers that require SSL authorization, something that otherwise would not be possible.\', \'pop3_useimap\', 0, \'yesno\', 8)',
	'INSERT INTO hive_setting VALUES (93, 3, \'Last atomic clock check\', \'\', \'last_atomic_clock_check\', 0, \'text\', 0)',
	'INSERT INTO hive_setting VALUES (94, 3, \'Atomic clock time difference\', \'\', \'atomic_clock_time_diff\', 0, \'text\', 0)',
	'INSERT INTO hive_setting VALUES (95, 7, \'Automatically correct time\', \'HiveMail&trade; will contact this time server to check the accurate time every 24 hours. This will allow the program to automatically correct the time of the server HiveMail&trade; is running on, based on the time from that was received from the time server. Leave this field empty to disable this feature. A full list of available time servers is available <a href=\\"http://www.bldrdoc.gov/timefreq/service/time-servers.html\\" target=\\"_blank\\">here</a>.\', \'time_server\', \'\', \'text\', 4)',
	'INSERT INTO hive_setting VALUES (96, 3, \'Email gateway type\', \'Do not modify!\', \'gatewaytype\', \''.iif(getop('pop3_use'), 'pop3', 'pipe').'\', \'text\', 0)',
	'INSERT INTO hive_setting VALUES (98, 17, \'Format of the mailbox\', \'Choose the format of the mailbox HiveMail&trade; is going to access. If are you not sure which format is the correct one, please contact your host.\', \'fg_format\', \'mbox\', \'mboxselect\', 1)',
	'INSERT INTO hive_setting VALUES (99, 1, \'New-line character\', \'Possible values: \\\\r\\\\n (default), \\\\n.\', \'crlf\', \'\\\\r\\\\n\', \'text\', 15)',
	'INSERT INTO hive_setting VALUES (101, 9, \'Maximum IP Usage\', \'Set this to a whole number to limit the number\\nof times any single IP address can be used for registration. Set it to 0 to disable.\\n(<i>Remember</i>, this may not prevent users from registering multiple times if they are using a service which provides a dynamic IP address!\\nAdditionally, it may prevent innocent users from registering if they happen to use the same ISP as other users.)\', \'reg_maxipusage\', 0, \'text\', 10)',
	'INSERT INTO hive_setting VALUES (102, 2, \'Send Password to Secondary Email\', \'If you set this option to Yes, instead of allowing a user to reset his own password, the password will be sent to the secondary email address he provided on registration. (Therefore, if you do not require a secondary email address, this option will not work.)\', \'sendpwtoalt\', 0, \'yesno\', 5)',
	'INSERT INTO hive_setting VALUES (103, 11, \'Maximum message size\', \'Messages that are larger than this value (in megabytes) will not be processed by HiveMail&trade;, but instead sent back to the sender with a message saying that the email was too large. Set the value to 0 to disable this feature.\', \'maxprocsize\', \'\', \'text\', 1)',
	'INSERT INTO hive_setting VALUES (104, 5, \'Display announcements\', \'Turn this on to display announcements from YourMail.com on the control panel home page.\', \'cp_showannouncements\', 1, \'yesno\', 5)',
	'INSERT INTO hive_setting VALUES (105, 3, \'Last time announcements were updated\', \'Do not touch!\', \'cp_lastannouncementcheck\', 0, \'text\', 0)',
	'INSERT INTO hive_setting VALUES (106, 3, \'Announcements cache\', \'\', \'cp_announcementcache\', \'\', \'text\', 0)',
	'INSERT INTO hive_setting VALUES (107, 7, \'Use 24-hour format\', \'Turn this on to use 24-hour format for calendar events.\', \'cal_use24\', 0, \'yesno\', 5)',
	'INSERT INTO hive_setting VALUES (108, 3, \'Last time subscriptions were audited\', \'Do not modify!\', \'subs_lastcheck\', 0, \'text\', 0)',
	'INSERT INTO hive_setting VALUES (109, 19, \'Enable HivePOP3\', \'Enable the functions and features of the HivePOP3 server.\', \'hivepop_enabled\', 0, \'yesno\', 1)',
	'INSERT INTO hive_setting VALUES (110, 19, \'Server address\', \'The address that users need to connect to in order to read their mail via the POP3 protocl.\', \'hivepop_serveraddr\', \'\', \'text\', 2)',
	'INSERT INTO hive_setting VALUES (111, 19, \'Server port\', \'The port number that should be used to connect to the HivePOP3 server.\', \'hivepop_serverport\', \'\', \'text\', 3)',
	'INSERT INTO hive_setting VALUES (112, 19, \'Allow duplicate storing\', \'Allow users to store incoming messages in their default mailbox as well as in the HivePOP3 account?\', \'hivepop_allowdupe\', 1, \'yesno\', 4)',
	'INSERT INTO hive_setting VALUES (113, 19, \'Path to message files\', \'Enter the absolute path to the folder that contains the message and data files.\', \'hivepop_path\', \'\', \'text\', 5)',
	'INSERT INTO hive_setting VALUES (90, 3, \'Default user options\', \'Don\\\'t touch!\', \'defuseroptions\', \'a:2:{i:0;d:1953562450;i:1;d:49;}\', \'text\', 0)',
	'INSERT INTO hive_setting VALUES (97, 17, \'Path to the mail messages\', \'Please enter the path to where the mail is kept on your system. If you are accessing an mbox-formatted account, enter the complete path to the file in which messages are saved. In the case of MH or Maildir, only enter the directory in which the files are kept.\', \'fg_path\', \'\', \'text\', 2)',
	'INSERT INTO hive_settinggroup VALUES (16, \'Pipe Gateway\', \'pipe\', 0)',
	'INSERT INTO hive_settinggroup VALUES (17, \'File Gateway\', \'file\', 0)',
	'INSERT INTO hive_settinggroup VALUES (18, \'POP3 Gateway\', \'pop3\', 0)',
	'INSERT INTO hive_settinggroup VALUES (19, \'HivePOP3 Settings\', \'hivepop\', 6)',
	'INSERT INTO hive_templategroup VALUES (19, \'Subscriptions\', 18, \'\')',
	'UPDATE hive_setting SET display = 3, description = \'Addresses that you add here will never be blocked, for any user in the system, <i>even</i> if the user would like to block it. Useful, for example, if you don\\\'t want users to ignore emails from the webmaster.<br />Wildcards may be used. Specify one email address per line. Use only the domain name (e.g \\"domain.com\\") to block all messages from it.\' WHERE variable = \'globalsafe\'',
	'UPDATE hive_setting SET display = 2, description = \'Any addresses you enter here will be blocked for all users, <i>even</i> if they are defined as \\"safe\\" for a user. Please use with care!<br />Wildcards may be used. Specify one email address per line. Use only the domain name (e.g \\"domain.com\\") to block all messages from it.\' WHERE variable = \'globalblock\'',
	'UPDATE hive_setting SET display = 4 WHERE variable = \'dnsbls\'',
	'UPDATE hive_setting SET display = 2, settinggroupid = 18 WHERE variable = \'pop3_server\'',
	'UPDATE hive_setting SET display = 3, settinggroupid = 18 WHERE variable = \'pop3_port\'',
	'UPDATE hive_setting SET display = 4, settinggroupid = 18 WHERE variable = \'pop3_username\'',
	'UPDATE hive_setting SET display = 5, settinggroupid = 18 WHERE variable = \'pop3_password\'',
	'UPDATE hive_setting SET display = 6, settinggroupid = 18 WHERE variable = \'pop3_runevery\'',
	'UPDATE hive_setting SET display = 1, settinggroupid = 18 WHERE variable = \'pop3_cron\'',
	'UPDATE hive_setting SET display = 7, settinggroupid = 18 WHERE variable = \'pop3_nodelete\'',
	'UPDATE hive_setting SET display = 9, description = \'This is the maximum number of messages that are processed every time a connection is made to a POP3 account.\' WHERE variable = \'pop3_maxmsgs\'',
	'UPDATE hive_setting SET display = 10, description = \'The maximum accumulated size of all messages that are proccesed per session, in megabytes. Please note that if one message alone is larger than this limit, it will be processed alone after all other messages were cleared (unless it is bigger than maximum allowed size - see below). Set this to 0 to have messages processed in the order of their arrival without any size limits.\' WHERE variable = \'pop3_maxsize\'',
	'UPDATE hive_setting SET display = 5, description = \'If you would like to not process any messages that are sent to certain addresses, write down those addresses here. This can be a list of names only (i.e \\\'nobody\\\') or full email address (i.e \\\'nobody@domain.com\\\'). Wildcards may be used. Separate addresses and names with space or a new line.\' WHERE variable = \'ignore_addys\'',
	'UPDATE hive_settinggroup SET display = 14 WHERE settinggroupid = 5',
	'UPDATE hive_settinggroup SET display = 5, title = \'POP3 Options\', description = \'pop3\' WHERE settinggroupid = 4',
	'UPDATE hive_settinggroup SET display = 8 WHERE settinggroupid = 7',
	'UPDATE hive_settinggroup SET title = \'Registration\' WHERE settinggroupid = 9',
	'UPDATE hive_settinggroup SET title = \'Email Reading\' WHERE settinggroupid = 10',
	'UPDATE hive_settinggroup SET title = \'Email Processing\', display = 7 WHERE settinggroupid = 11',
	'UPDATE hive_settinggroup SET display = 9 WHERE settinggroupid = 14',
	'UPDATE hive_templategroup SET display = 19 WHERE templategroupid = 12',
	'UPDATE hive_user SET lastpassword = UNIX_TIMESTAMP(), defcompose = \'username\', options2 = 1 + 16 + 32',
	'UPDATE hive_user SET options2 = (options2 - 4) WHERE (options2 & 4)',
	'UPDATE hive_pop SET replyto = displayemail',
	'UPDATE hive_contact SET timezone = -13, emailinfo = \'a:0:{}\', nameinfo = \'a:0:{}\', addressinfo = \'a:0:{}\', phoneinfo = \'a:0:{}\'',
	'UPDATE hive_message SET flagged = (status & 16)',
	'UPDATE hive_usergroup SET perms = perms + 262144 + 1048576 + 524288 + 2097152 + 4194304 + 8388608 + 16777216 WHERE usergroupid <> 3',
);

?>