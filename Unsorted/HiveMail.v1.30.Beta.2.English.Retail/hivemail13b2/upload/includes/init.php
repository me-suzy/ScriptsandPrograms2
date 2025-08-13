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
// | $RCSfile: init.php,v $ - $Revision: 1.106 $
// | $Date: 2003/12/27 21:29:38 $ - $Author: chen $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);

// ############################################################################
// HivePOP server
define('HIVEPOP_RUNNING', false);
// ############################################################################

// ############################################################################
// General constants
$ipaddress = getenv('HTTP_CLIENT_IP') or $ipaddress = getenv('HTTP_X_FORWARDED_FOR') or $ipaddress = getenv('REMOTE_ADDR');
$ipaddress = trim(preg_replace('#^([^,]+)(,.*)?#', '$1', $ipaddress));
define('IPADDRESS', $ipaddress);
define('TIMENOW', time() + iif(getop('time_server'), getop('atomic_clock_time_diff'), 0));
define('HIVEVERSION', '1.3');
define('HIVEFULLVERSION', '1.3 Beta 2');
define('HIVE_DEV', false);
define('DEBUG', false);
define('ONLINE_TIMESPAN', 15);
define('SESSION_VARNAME', 'hivesession');
eval('define("CRLF", "'.getop('crlf').'");');
// Set the constant below to true if you would like
// to Base64-encode all parts of outgoing messages
// (Please note that this may affect performance)
define('SEND_WITH_BASE64', false);
// Uncomment this line if you can't use shutdown functions
// define('NOSHUTDOWNFUNCS', true);

// ############################################################################
// Get all processors information
$_processor_info = array();
$incfiles = scandir(iif(INADMIN, '.').'./subscriptions');
foreach ($incfiles as $incfile) {
	if (substr($incfile, 0, 8) == 'gateway.') {
		require_once(iif(INADMIN, '.')."./subscriptions/$incfile");
	}
}

// ############################################################################
// Number of messages to keep per-directory
// We do not recommend setting this to a large number, or changing this at all
define('MAX_FLAT_FILES', 1000);
umask(0);

// ############################################################################
// The regular expressions that are used to detect email address and IP's
define('REGEX_EMAIL_USER', '[-a-zA-Z0-9!\#$%&*+\/=?^_`{|}~.]+');
define('REGEX_EMAIL_DOMAIN', '[a-zA-Z0-9]{1}[-.a-zA-Z0-9_]*\.[a-zA-Z]{2,6}');
define('REGEX_IP_ADDR', '[\d]{1,3}\.[\d]{1,3}\.[\d]{1,3}\.[\d]{1,3}(?:\.[\d]{1,3})?');

// ############################################################################
// Define the special folders
// NOTE: If you add another folder here make sure you also add it to the skinning system
$_folders = array(
	'-1' => array(
		'name' => 'inbox',
		'title' => 'Inbox',
	),
	'-2' => array(
		'name' => 'sentitems',
		'title' => 'Sent Items',
	),
	'-3' => array(
		'name' => 'trashcan',
		'title' => 'Trash Can',
	),
	'-4' => array(
		'name' => 'junkmail',
		'title' => 'Junk Mail',
	),
);

// ############################################################################
// User options
define('USER_USEBGHIGH',			1);
define('USER_SHOWHTML',				2);
define('USER_WYSIWYG',				4);
define('USER_REQUESTREAD',			8);
define('USER_SAVECOPY',				16);
define('USER_ADDRECIPS',			32);
define('USER_INCLUDEORIG',			64);
define('USER_SHOWALLHEADERS',		128);
define('USER_SHOWFOLDERTAB',		256);
define('USER_AUTOADDSIG',			512);
define('USER_PLAYSOUND',			1024);
define('USER_DONTADDSIGONREPLY',	2048);
define('USER_SHOWTOPBOX',			4096);
define('USER_FIXDST',				8192);
define('USER_RETURNSENT',			16384);
define('USER_PROTECTBOOK',			32768);
define('USER_SENDERLINK',			65536);
define('USER_COMPOSEREPLYTO',		131072);
define('USER_DELETEFORWARDS',		262144);
define('USER_AUTORESPOND',			524288);
define('USER_NOCOOKIES',			1048576);
define('USER_SHOWINLINE',			2097152);
define('USER_NOTIFYALL',			4194304);
define('USER_USERANDOMSIG',			8388608);
define('USER_ALIASMULTIMAILS',		16777216);
define('USER_ATTACHWIN',			33554432);
define('USER_SHOWIMGINMSG',			67108864);
define('USER_HASNEWMSGS',			134217728);
define('USER_CALONINBOX',			268435456);
define('USER_CALYEAR3ON4',			536870912);
define('USER_AUTOSPELL',			1073741824);
define('USER_CALSPANINBOX',			1);
define('USER_SHOWPASSNOTICE',		2);
define('USER_ISBANNED',				4);
define('USER_SYNCHIVEPOP',			8);
define('USER_CALSHARESOK',			16);
define('USER_CALSHOWMEONLIST',		32);
define('USER_POPUPNOTICES',			64);

// List of options2 bits
$_userextrabits = array(
	'USER_CALSPANINBOX',
	'USER_SHOWPASSNOTICE',
	'USER_ISBANNED',
	'USER_SYNCHIVEPOP',
	'USER_CALSHARESOK',
	'USER_CALSHOWMEONLIST',
	'USER_POPUPNOTICES',
);

// Their names
$_userbits = array(
	'read' => array(
		'USER_SHOWHTML' => 'Show HTML version of messages:',
		'USER_SHOWALLHEADERS' => 'Show advanced email headers:',
		'USER_SHOWINLINE' => 'Show inline attachments:',
		'USER_ATTACHWIN' => 'Attachments open in new window:',
		'USER_SHOWIMGINMSG' => 'Display attached images below the message:',
	),
	'compose' => array(
		'USER_INCLUDEORIG' => 'Include original message when replying:',
		'USER_WYSIWYG' => 'Use WYSIWYG editor:',
		'USER_REQUESTREAD' => 'Request read receipt by default:',
		'USER_SAVECOPY' => 'Save copy of outgoing messages by default:',
		'USER_ADDRECIPS' => 'Automatically add recipients to address book:',
		'USER_RETURNSENT' => 'Return to Sent Items after sending an email:',
		'USER_COMPOSEREPLYTO' => 'Display Reply-To address when composing:',
		'USER_USERANDOMSIG' => 'Select a random default signature:',
		'USER_AUTOSPELL' => 'Automatically spell check before sending emails:',
	),
	'folder' => array(
		'USER_USEBGHIGH' => 'Use row background highlighting:',
		'USER_SHOWFOLDERTAB' => 'Show folder tab on left side of screen:',
		'USER_SHOWTOPBOX' => 'Show the statistics table:',
		'USER_SENDERLINK' => 'Clicking a sender name creates a new message to the sender:',
	),
	'calendar' => array(
		'USER_CALONINBOX' => 'Display current month on inbox:',
		'USER_CALYEAR3ON4' => 'Use 3x4 layout in yearly view:',
		'USER_CALSPANINBOX' => 'Show next or previous months on inbox if necessary:',
		'USER_CALSHARESOK' => 'Allow other users to share events with user and vice-versa:',
		'USER_CALSHOWMEONLIST' => 'Show user\'s name on the userlist of calendar events that have been shared with user:',
	),
	'general' => array(
		'USER_PLAYSOUND' => 'Play sound when new messages arrive:',
		'USER_FIXDST' => 'Automatically determine if Daylight Saving Time is in effect:',
		'USER_NOCOOKIES' => 'Do not use cookies:',
		'USER_AUTORESPOND' => 'Automatically respond with default response:',
		'USER_DELETEFORWARDS' => 'Delete automatically forwarded messages:',
		'USER_PROTECTBOOK' => 'Exempt address book contacts from message rules:',
		'USER_AUTORESPOND' => 'Automatically respond with default response:',
		'USER_NOTIFYALL' => 'Send notification of all incoming messages:',
		'USER_ALIASMULTIMAILS' => 'Show messages multiple times if they were sent to more than one alias:',
		'USER_POPUPNOTICES' => 'Show system notices as JavaScript pop-ups:',
	),
);

// Non-bitfield options
define('USER_SENDREADNO',			0);
define('USER_SENDREADASK',			1);
define('USER_SENDREADALWAYS',		2);
define('USER_EMPTYBINNO',			-1);
define('USER_EMPTYBINONEXIT',		-2);
define('USER_AUTOADDSIGOFF',		0);
define('USER_AUTOADDSIGONLY',		1);
define('USER_AUTOADDSIGON',			2);
define('USER_HIVEPOP_NOSAVE',		0);
define('USER_HIVEPOP_SAVEONLY',		1);
define('USER_HIVEPOP_SAVEBOTH',		2);

// Default options for new users
define('USER_DEFAULTCOLS', serialize(array('priority', 'attach', 'from', 'subject', 'datetime', 'size')));

// ############################################################################
// Usergroup permissions
define('GROUP_CANUSE',				1);
define('GROUP_CANFOLDER',			2);
define('GROUP_CANRULE',				4);
define('GROUP_CANPOP',				8);
define('GROUP_CANSENDHTML',			16);
define('GROUP_CANADMIN',			32);
define('GROUP_CANUSEOVERLIMIT',		64);
define('GROUP_CANSEARCH',			128);
define('GROUP_CANATTACH',			256);
define('GROUP_CANSEND',				512);
define('GROUP_CANSOUND',			1024);
define('GROUP_CANFORWARD',			2048);
define('GROUP_NOTIFY_EMPTY',		4096);
define('GROUP_NOTIFY_REMOVE',		8192);
define('GROUP_ALLOWDYNAMICIP',		16384);
define('GROUP_CANALIAS',			32768);
define('GROUP_CANREPORTSPAM',		65536);
define('GROUP_CANCALENDAR',			131072);
define('GROUP_SENDIP',				262144);
define('GROUP_CANSPELL',			524288);
define('GROUP_CANHIVEPOP',			1048576);
define('GROUP_CANCHANGEPASS',		2097152);
define('GROUP_CANSHAREDEVENTS',		4194304);
define('GROUP_CANGLOBALEVENTS',		8388608);
define('GROUP_CANUSEMONITOR',		16777216);

// Default options for new groups
define('GROUP_DEFAULTBITS', GROUP_CANUSE + GROUP_CANFOLDER + GROUP_CANRULE + GROUP_CANPOP + GROUP_CANSENDHTML + GROUP_CANSEARCH + GROUP_CANATTACH + GROUP_CANSEND + GROUP_CANSOUND + GROUP_CANFORWARD + GROUP_NOTIFY_EMPTY + GROUP_NOTIFY_REMOVE + GROUP_CANREPORTSPAM + GROUP_CANCALENDAR + GROUP_SENDIP + GROUP_CANSPELL + GROUP_CANCHANGEPASS + GROUP_CANGLOBALEVENTS + GROUP_CANUSEMONITOR);

// Titles of usergroup permissions
// Anything you define here will automagically appear in usergroup.php
$_groupbits = array(
	'GROUP_CANUSE' => 'Can use the system:',
	'GROUP_CANSEND' => 'Can send messages:',
	'GROUP_CANFOLDER' => 'Can create custom folders:',
	'GROUP_CANRULE' => 'Can utilize message rules:',
	'GROUP_CANPOP' => 'Can receive emails from POP accounts:',
	'GROUP_CANSENDHTML' => 'Can send HTML emails and use the WYSIWYG editor:',
	'GROUP_CANADMIN' => 'Can log in to the admin panel:',
	'GROUP_CANUSEOVERLIMIT' => 'Can use the system regardless of the online user limit:',
	'GROUP_CANSEARCH' => 'Can use the search engine:',
	'GROUP_CANATTACH' => 'Can add attachments to outgoing messages:',
	'GROUP_CANSOUND' => 'Can choose different mail sounds and upload custom ones:',
	'GROUP_CANFORWARD' => 'Can auto-forward messages:',
	'GROUP_ALLOWDYNAMICIP' => 'Allow IP to change during session without forcing logout:',
	'GROUP_CANALIAS' => 'Can use account aliases:',
	'GROUP_CANREPORTSPAM' => 'Can send spam reports:',
	'GROUP_CANCALENDAR' => 'Can use the calendar:',
	'GROUP_SENDIP' => 'Send user\'s IP with outbound messages:',
	'GROUP_CANSPELL' => 'Can use the spell checker:',
	'GROUP_CANCHANGEPASS' => 'Can change account password:',
	'GROUP_CANSHAREDEVENTS' => 'Can share calendar events with other users:',
	'GROUP_CANGLOBALEVENTS' => 'Can view global calendar events set by admin:',
	'GROUP_CANUSEMONITOR' => 'Can use HiveMonitor:',
);
if (HIVEPOP_RUNNING) {
	$_groupbits['GROUP_CANHIVEPOP'] = 'Can use the HivePOP server:';
}

// ############################################################################
// Options for mail messages
define('MAIL_REPLIED',				1);
define('MAIL_FORWARDED',			2);
define('MAIL_SENTRECEIPT',			4);
define('MAIL_READ',					8);
//define('MAIL_FLAGGED',			16); # Not used anymore
define('MAIL_OUTGOING',				32);
define('MAIL_BOUNCED',				64);
define('MAIL_REPORTED',				128);
define('MAIL_SYSMAIL',				256);
define('MAIL_COLORED',				512);

// ############################################################################
// SMTP constants
define('SMTP_STATUS_NOT_CONNECTED',	1);
define('SMTP_STATUS_CONNECTED',		2);

// ############################################################################
// POP3 accounts
define('POP3_DELETE_NEVER',			0);
define('POP3_DELETE_RIGHTAWAY',		1);
define('POP3_DELETE_SYNC',			2);

// ############################################################################
// Calendar events
define('REALLY_FAR_DATE',			1410040800); // Sometime in 2014, hope we live long enough to change this
define('RECUR_NONE',				0);
define('RECUR_DAILY',				1);
define('RECUR_WEEKDAY',				2);
define('RECUR_WEEKLY',				3);
define('RECUR_MONTHLY',				4);
define('RECUR_YEARLY',				5);
define('RECUR_END_NEVER',			0);
define('RECUR_END_COUNT',			1);
define('RECUR_END_BYDATE',			2);

// ############################################################################
// Contact information
define('CONTACT_PHONE_HOME',		1);
define('CONTACT_PHONE_VOICE',		2);
define('CONTACT_PHONE_WORK',		4);
define('CONTACT_PHONE_FAX',			8);
define('CONTACT_PHONE_CELL',		16);
define('CONTACT_PHONE_PAGER',		32);
$_phonetypes = array(
	CONTACT_PHONE_HOME => 'home',
	CONTACT_PHONE_VOICE => 'voice',
	CONTACT_PHONE_WORK => 'work',
	CONTACT_PHONE_FAX => 'fax',
	CONTACT_PHONE_CELL => 'cell',
	CONTACT_PHONE_PAGER => 'pager',
);

// ############################################################################
// Calendar shared event permissions
define('CAL_SHARE_CANEDIT',			1);
define('CAL_SHARE_CANLIST',			2);
define('CAL_SHARE_CANFWD',			4);

// ############################################################################
// Logging constants
define('EVENT_NOTICE',				1);
define('EVENT_WARNING',				2);
define('EVENT_CRITICAL',			3);

$_events = array(
	'levels' => array(
		'1' => 'Notice',
		'2' => 'Warning',
		'3' => 'Critical',
	),
	'modules' => array(
		'1' => 'POP',
		'2' => 'MIME',
		'3' => 'Templates',
		'4' => 'User',
		'5' => 'SMTP',
		'6' => 'Database',
	),
);

// ############################################################################
// ZIP class constants
define('ZIP_READ_BLOCK_SIZE',		2048);
define('ZIP_OPT_PATH',				77001);
define('ZIP_OPT_ADD_PATH',			77002);
define('ZIP_OPT_REMOVE_PATH',		77003);
define('ZIP_OPT_REMOVE_ALL_PATH',	77004);
define('ZIP_OPT_SET_CHMOD',			77005);
define('ZIP_CB_PRE_EXTRACT',		78001);
define('ZIP_CB_POST_EXTRACT',		78002);
define('ZIP_CB_PRE_ADD',			78003);
define('ZIP_CB_POST_ADD',			78004);

// ############################################################################
// Payment gateway constants
define('PAY_ERROR_DEMO_MODE',		-7);
define('PAY_ERROR_BAD_REQUEST',		-6);
define('PAY_ERROR_CC_NOTPROCESSED',	-5);
define('PAY_ERROR_INVALID_CART',	-4);
define('PAY_ERROR_NEVER_MIND',		-3);
define('PAY_ERROR_NOT_ENOUGH',		-2);
define('PAY_ERROR_ANOTHER_SUB',		-1);
define('PAY_STATUS_CREATED',		1);
define('PAY_STATUS_EXTENDED',		2);
define('PAY_STATUS_SHORTENED',		3);
define('PAY_STATUS_CANCELLED',		4);

// ############################################################################
// Mail rules
$_rules = array(
	'conds' => array(
		'emaileq' =>				11,
		'emailcon' =>				12,
		'emailnotcon' =>			13,
		'emailstars' =>				14,
		'emailends' =>				15,
		'msgeq' =>					21,
		'msgcon' =>					22,
		'msgnotcon' =>				23,
		'msgstars' =>				24,
		'msgends' =>				25,
		'recipseq' =>				31,
		'recipscon' =>				32,
		'recipsnotcon' =>			33,
		'recipsstars' =>			34,
		'recipsends' =>				35,
		'subjecteq' =>				41,
		'subjectcon' =>				42,
		'subjectnotcon' =>			43,
		'subjectstars' =>			44,
		'subjectends' =>			45,
		'isfrompop3' =>				51
	),
	'actions' => array(
		'read' =>					1,
		'move' =>					2,
		'copy' =>					4,
		'delete' =>					8,
		'flag' =>					16,
		'respond' =>				32,
		'notify' =>					64,
		'color' =>					128
	)
);

// ############################################################################
// These are the secret keys that are used to encrypt the POP3 passwords
$_secret_keys[0] = rand(1, 100);
$_secret_keys[1] = 'S3AsLIqap2us';
$_secret_keys[2] = 'Nu2oSpislut9';
$_secret_keys[3] = 'cr7sleCHagiP';
$_secret_keys[4] = 'fe9ASwaw4Ota';
$_secret_keys[5] = 'suFEr2T39Fob';
$_secret_keys[6] = 'e4d18283a02123c11a135d16fa628c21';

// ############################################################################
// MIME types
$mimetypes = array(
	'dot'   => 'application/msword',
	'doc'   => 'application/msword',
	'exe'   => 'application/octet-stream',
	'bin'   => 'application/octet-stream',
	'pdf'   => 'application/pdf',
	'ai'    => 'application/postscript',
	'ps'    => 'application/postscript',
	'eps'   => 'application/postscript',
	'rtf'   => 'application/rtf',
	'csh'   => 'application/x-csh',
	'gtar'  => 'application/x-gtar',
	'gz'    => 'application/x-gzip',
	'class' => 'application/x-java-vm',
	'ser'   => 'application/x-java-serialized-object',
	'jar'   => 'application/x-java-archive',
	'sh'    => 'application/x-sh',
	'tar'   => 'application/x-tar',
	'zip'   => 'application/zip',
	'ua'    => 'audio/basic',
	'wav'   => 'audio/x-wav',
	'mid'   => 'audio/x-midi',
	'gif'   => 'image/gif',
	'jpg'   => 'image/jpeg',
	'jpe'   => 'image/jpeg',
	'jpeg'  => 'image/jpeg',
	'tif'   => 'image/tiff',
	'tiff'  => 'image/tiff',
	'xbm'   => 'image/x-xbitmap',
	'htm'   => 'text/html',
	'html'  => 'text/html',
	'txt'   => 'text/plain',
	'rtx'   => 'text/richtext',
	'rtf'   => 'text/richtext',
	'mpeg'  => 'video/mpeg',
	'mpe'   => 'video/mpeg',
	'mpg'   => 'video/mpeg',
	'qt'    => 'video/quicktime',
	'mov'   => 'video/quicktime',
	'avi'   => 'video/x-msvideo',
	'movie' => 'video/x-sgi-movie'
);

?>