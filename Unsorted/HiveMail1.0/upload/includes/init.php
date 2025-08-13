<?php
//
// +-------------------------------------------------------------+
// | HiveMail version 1.0
// | Copyright (C) 2002 Chen Avinadav
// | Supplied by  CyKuH [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | $RCSfile: init.php,v $
// | $Date: 2002/11/11 16:29:32 $
// | $Revision: 1.44 $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);

// ############################################################################
// Fix predefined variable names for older versions
if (!function_exists('version_compare')) {	// Ironic, huh
	$_GET = &$HTTP_GET_VARS;
	$_POST = &$HTTP_POST_VARS;
	$_COOKIE = &$HTTP_COOKIE_VARS;
	$_REQUEST = array_merge($_GET, $_POST, $_COOKIE);
	$_FILES = &$HTTP_POST_FILES;
	$_SERVER = &$HTTP_SERVER_VARS;
	$_ENV = &$HTTP_ENV_VARS;
	$_SESSION = &$HTTP_SESSION_VARS;
}

// ############################################################################
// God I hate magic_quotes...
if (get_magic_quotes_gpc()) {
	function stripslashesarray($array) {
		if (is_array($array)) {
			foreach($array as $key => $val) {
				if (is_array($val)) {
					$array["$key"] = stripslashesarray($val);
				} elseif (is_string($val)) {
					if (get_cfg_var('magic_quotes_sybase')) {
						$array["$key"] = str_replace("''", "'", $val);
					} else {
						$array["$key"] = stripslashes($val);
					}
				}
			}
		}

		return $array;
	}

	$_GET = stripslashesarray($_GET);
	$_POST = stripslashesarray($_POST);
	$_COOKIE = stripslashesarray($_COOKIE);
	$_REQUEST = stripslashesarray($_REQUEST);
}
set_magic_quotes_runtime(0);

// ############################################################################
// Register globals
// HiveMail v2 will have this turned off
if (!isset($PHP_SELF) or get_magic_quotes_gpc()) {
	if (get_magic_quotes_gpc()) {
		@extract($_GET);
		@extract($_SERVER);
		@extract($_COOKIE);
		@extract($_SESSION);
		@extract($_FILES);
		@extract($_POST);
		@extract($_ENV);
	} else {
		@extract($_GET, EXTR_SKIP);
		@extract($_SERVER, EXTR_SKIP);
		@extract($_COOKIE, EXTR_SKIP);
		@extract($_SESSION, EXTR_SKIP);
		@extract($_FILES, EXTR_SKIP);
		@extract($_POST, EXTR_SKIP);
		@extract($_ENV, EXTR_SKIP);
	}
}

// ############################################################################
// General constants
if ($ipaddress = getenv('HTTP_CLIENT_IP')) {
} elseif ($ipaddress = getenv('HTTP_X_FORWARDED_FOR')) {
} else {
	$ipaddress = getenv('REMOTE_ADDR');
}

define('TIMENOW', time());
define('IPADDRESS', $ipaddress);
define('HIVEVERSION', '1.0');
// Uncomment this line if you can't use shutdown functions
// define('NOSHUTDOWNFUNCS', true);

// ############################################################################
// Define the special folders
$_folders = array(
	'-1' => array(
		'name' => 'inbox',
		'title' => 'Inbox'
	),
	'-2' => array(
		'name' => 'sentitems',
		'title' => 'Sent Items'
	),
	'-3' => array(
		'name' => 'trashcan',
		'title' => 'Trash Can'
	)
);

// ############################################################################
// The newline character we use
define('CRLF', "\r\n");

// ############################################################################
// User options
define('USER_USEBGHIGH', 1);
define('USER_SHOWHTML', 2);
define('USER_WYSIWYG', 4);
define('USER_REQUESTREAD', 8);
define('USER_SAVECOPY', 16);
define('USER_ADDRECIPS', 32);
define('USER_INCLUDEORIG', 64);
define('USER_SHOWALLHEADERS', 128);
define('USER_SHOWFOLDERTAB', 256);
define('USER_AUTOADDSIG', 512);
define('USER_PLAYSOUND', 1024);
define('USER_DONTADDSIGONREPLY', 2048);
define('USER_SHOWTOPBOX', 4096);
define('USER_FIXDST', 8192);

// Their names
$_userbits = array(
	'read' => array(
		'USER_SHOWHTML' => 'Show HTML version of messages:',
		'USER_SHOWALLHEADERS' => 'Show advanced email headers:',
	),
	'compose' => array(
		'USER_INCLUDEORIG' => 'Include original message when replying:',
		'USER_WYSIWYG' => 'Use WYSIWYG editor:',
		'USER_REQUESTREAD' => 'Request read receipt by default:',
		'USER_SAVECOPY' => 'Save copy of outgoing messages by default:',
		'USER_ADDRECIPS' => 'Automatically add recipients to address book:',
	),
	'folder' => array(
		'USER_USEBGHIGH' => 'Use row background highlighting:',
		'USER_SHOWFOLDERTAB' => 'Show folder tab on left side of screen:',
		'USER_SHOWTOPBOX' => 'Show the statistics table:',
	),
	'general' => array(
		'USER_PLAYSOUND' => 'Play sound when new messages arrive:',
		'USER_FIXDST' => 'Automatically determine if Daylight Saving Time is in effect:',
	),
	'misc' => array(
	//	'USER_AUTOADDSIG' => 'xxxxxxxx:',
	//	'USER_DONTADDSIGONREPLY' => 'xxxxxx:',
	),
);

// Non-bitfield options
define('USER_SENDREADNO', 0);
define('USER_SENDREADASK', 1);
define('USER_SENDREADALWAYS', 2);
define('USER_EMPTYBINNO', -1);
define('USER_EMPTYBINONEXIT', -2);
define('USER_AUTOADDSIGOFF', 0);
define('USER_AUTOADDSIGONLY', 1);
define('USER_AUTOADDSIGON', 2);

// Default options for new users
define('USER_DEFAULTBITS', USER_FIXDST + USER_SHOWTOPBOX + USER_SHOWHTML + USER_WYSIWYG + USER_SAVECOPY + USER_INCLUDEORIG + USER_SHOWFOLDERTAB + USER_PLAYSOUND);

// ############################################################################
// Usergroup permissios
// Anything you define here will automagically appear in usergroup.php
define('GROUP_CANUSE', 1);
define('GROUP_CANFOLDER', 2);
define('GROUP_CANRULE', 4);
define('GROUP_CANPOP', 8);
define('GROUP_CANSENDHTML', 16);
define('GROUP_CANADMIN', 32);
define('GROUP_CANUSEOVERLIMIT', 64);
define('GROUP_CANSEARCH', 128);
define('GROUP_CANATTACH', 256);

// Their names
$_groupbits = array(
	'GROUP_CANUSE' => 'Can use the system:',
	'GROUP_CANFOLDER' => 'Can create custom folders:',
	'GROUP_CANRULE' => 'Can utilize message rules:',
	'GROUP_CANPOP' => 'Can receive emails from POP accounts:',
	'GROUP_CANSENDHTML' => 'Can send HTML emails and use the WYSIWYG editor:',
	'GROUP_CANADMIN' => 'Can log in to the admin panel:',
	'GROUP_CANUSEOVERLIMIT' => 'Can use the system even if it has reached the user limit:',
	'GROUP_CANSEARCH' => 'Can use the search engine:',
	'GROUP_CANATTACH' => 'Can add attachments to outgoing messages:',
);

// ############################################################################
// Options for mail messages
define('MAIL_REPLIED', 1);
define('MAIL_FORWARDED', 2);
define('MAIL_SENTRECEIPT', 4);
define('MAIL_READ', 8);
define('MAIL_FLAGGED', 16);

// ############################################################################
// Misc
define('SMTP_STATUS_NOT_CONNECTED', 1);
define('SMTP_STATUS_CONNECTED', 2);

// ############################################################################
// Mail rules
$_rules = array(
	'conds' => array(
		'emaileq' => 11,
		'emailcon' => 12,
		'emailnotcon' => 13,
		'emailstars' => 14,
		'emailends' => 15,
		'msgeq' => 21,
		'msgcon' => 22,
		'msgnotcon' => 23,
		'msgstars' => 24,
		'msgends' => 25,
		'recipseq' => 31,
		'recipscon' => 32,
		'recipsnotcon' => 33,
		'recipsstars' => 34,
		'recipsends' => 35,
		'subjecteq' => 41,
		'subjectcon' => 42,
		'subjectnotcon' => 43,
		'subjectstars' => 44,
		'subjectends' => 45,
	),
	'actions' => array(
		'read' => 1,
		'move' => 2,
		'copy' => 4,
		'delete' => 8,
		'flag' => 16
	)
);

// ############################################################################
// MIME types
$mimetypes = array (
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

// ############################################################################
// Country list
if (!defined('LOAD_COUNTRIES')) {
	return;
}

$_countries = array(
	'us' => 'United States of America',
	'af' => 'Afghanistan',
	'al' => 'Albania',
	'dz' => 'Algeria',
	'as' => 'American Samoa',
	'ad' => 'Andorra',
	'ao' => 'Angola',
	'ai' => 'Anguilla',
	'aq' => 'Antarctica',
	'ag' => 'Antigua & Barbuda',
	'ar' => 'Argentina',
	'am' => 'Armenia',
	'aw' => 'Aruba',
	'au' => 'Australia',
	'at' => 'Austria',
	'az' => 'Azerbaijan',
	'bs' => 'Bahamas',
	'bh' => 'Bahrain',
	'bd' => 'Bangladesh',
	'bb' => 'Barbados',
	'by' => 'Belarus',
	'be' => 'Belgium',
	'bz' => 'Belize',
	'bj' => 'Benin',
	'bm' => 'Bermuda',
	'bt' => 'Bhutan',
	'bo' => 'Bolivia',
	'ba' => 'Bosnia & Herzegovina',
	'bw' => 'Botswana',
	'bv' => 'Bouvet Island',
	'br' => 'Brazil',
	'io' => 'British Indian Ocean Territory',
	'vg' => 'British Virgin Islands',
	'bn' => 'Brunei',
	'bg' => 'Bulgaria',
	'bf' => 'Burkina Faso',
	'bi' => 'Burundi',
	'kh' => 'Cambodia',
	'cm' => 'Cameroon',
	'ca' => 'Canada',
	'cv' => 'Cape Verde',
	'ky' => 'Cayman Islands',
	'cf' => 'Central African Republic',
	'td' => 'Chad',
	'cl' => 'Chile',
	'cn' => 'China',
	'cx' => 'Christmas Island',
	'cc' => 'Cocos Islands',
	'co' => 'Colombia',
	'km' => 'Comoros',
	'cg' => 'Congo',
	'ck' => 'Cook Islands',
	'cr' => 'Costa Rica',
	'hr' => 'Croatia',
	'cu' => 'Cuba',
	'cy' => 'Cyprus',
	'cz' => 'Czech Republic',
	'dk' => 'Denmark',
	'dj' => 'Djibouti',
	'dm' => 'Dominica',
	'do' => 'Dominican Republic',
	'tp' => 'East Timor',
	'ec' => 'Ecuador',
	'eg' => 'Egypt',
	'sv' => 'El Salvador',
	'gq' => 'Equatorial Guinea',
	'er' => 'Eritrea',
	'ee' => 'Estonia',
	'et' => 'Ethiopia',
	'fk' => 'Falkland Islands',
	'fo' => 'Faroe Islands',
	'fj' => 'Fiji',
	'fi' => 'Finland',
	'fr' => 'France',
	'gf' => 'French Guiana',
	'pf' => 'French Polynesia',
	'tf' => 'French Southern Territories',
	'ga' => 'Gabon',
	'gm' => 'Gambia',
	'ge' => 'Georgia',
	'de' => 'Germany',
	'gh' => 'Ghana',
	'gi' => 'Gibraltar',
	'gr' => 'Greece',
	'gl' => 'Greenland',
	'gd' => 'Grenada',
	'gp' => 'Guadeloupe',
	'gu' => 'Guam',
	'gt' => 'Guatemala',
	'gn' => 'Guinea',
	'gw' => 'Guinea-Bissau',
	'gy' => 'Guyana',
	'ht' => 'Haiti',
	'hm' => 'Heard & McDonald Islands',
	'hn' => 'Honduras',
	'hk' => 'Hong Kong',
	'hu' => 'Hungary',
	'is' => 'Iceland',
	'in' => 'India',
	'id' => 'Indonesia',
	'ir' => 'Iran',
	'iq' => 'Iraq',
	'ie' => 'Ireland',
	'il' => 'Israel',
	'it' => 'Italy',
	'ci' => 'Ivory Coast',
	'jm' => 'Jamaica',
	'jp' => 'Japan',
	'jo' => 'Jordan',
	'kz' => 'Kazakhstan',
	'ke' => 'Kenya',
	'ki' => 'Kiribati',
	'kp' => 'Korea, North',
	'kr' => 'Korea, South',
	'kw' => 'Kuwait',
	'kg' => 'Kyrgyzstan',
	'la' => 'Laos',
	'lv' => 'Latvia',
	'lb' => 'Lebanon',
	'ls' => 'Lesotho',
	'lr' => 'Liberia',
	'ly' => 'Libya',
	'li' => 'Liechtenstein',
	'lt' => 'Lithuania',
	'lu' => 'Luxembourg',
	'mo' => 'Macau',
	'mk' => 'Macedonia',
	'mg' => 'Madagascar',
	'mw' => 'Malawi',
	'my' => 'Malaysia',
	'mv' => 'Maldives',
	'ml' => 'Mali',
	'mt' => 'Malta',
	'mh' => 'Marshall Islands',
	'mq' => 'Martinique',
	'mr' => 'Mauritania',
	'mu' => 'Mauritius',
	'yt' => 'Mayotte',
	'mx' => 'Mexico',
	'fm' => 'Micronesia',
	'md' => 'Moldova',
	'mc' => 'Monaco',
	'mn' => 'Mongolia',
	'ms' => 'Montserrat',
	'ma' => 'Morocco',
	'mz' => 'Mozambique',
	'mm' => 'Myanmar',
	'na' => 'Namibia',
	'nr' => 'Nauru',
	'np' => 'Nepal',
	'nl' => 'Netherlands',
	'an' => 'Netherlands Antilles',
	'nc' => 'New Caledonia',
	'nz' => 'New Zealand',
	'ni' => 'Nicaragua',
	'ne' => 'Niger',
	'ng' => 'Nigeria',
	'nu' => 'Niue',
	'nf' => 'Norfolk Island',
	'mp' => 'Northern Mariana Islands',
	'no' => 'Norway',
	'om' => 'Oman',
	'pk' => 'Pakistan',
	'pw' => 'Palau',
	'pa' => 'Panama',
	'pg' => 'Papua New Guinea',
	'py' => 'Paraguay',
	'pe' => 'Peru',
	'ph' => 'Philippines',
	'pn' => 'Pitcairn Island',
	'pl' => 'Poland',
	'pt' => 'Portugal',
	'pr' => 'Puerto Rico',
	'qa' => 'Qatar',
	're' => 'Reunion',
	'ro' => 'Romania',
	'ru' => 'Russia',
	'rw' => 'Rwanda',
	'gs' => 'S. Georgia & S. Sandwich Isls.',
	'kn' => 'Saint Kitts & Nevis',
	'lc' => 'Saint Lucia',
	'vc' => 'Saint Vincent & The Grenadines',
	'ws' => 'Samoa',
	'sm' => 'San Marino',
	'st' => 'Sao Tome & Principe',
	'sa' => 'Saudi Arabia',
	'sn' => 'Senegal',
	'sc' => 'Seychelles',
	'sl' => 'Sierra Leone',
	'sg' => 'Singapore',
	'sk' => 'Slovakia',
	'si' => 'Slovenia',
	'so' => 'Somalia',
	'za' => 'South Africa',
	'es' => 'Spain',
	'lk' => 'Sri Lanka',
	'sh' => 'St. Helena',
	'pm' => 'St. Pierre & Miquelon',
	'sd' => 'Sudan',
	'sr' => 'Suriname',
	'sj' => 'Svalbard & Jan Mayen Islands',
	'sz' => 'Swaziland',
	'se' => 'Sweden',
	'ch' => 'Switzerland',
	'sy' => 'Syria',
	'tw' => 'Taiwan',
	'tj' => 'Tajikistan',
	'tz' => 'Tanzania',
	'th' => 'Thailand',
	'tg' => 'Togo',
	'tk' => 'Tokelau',
	'to' => 'Tonga',
	'tt' => 'Trinidad & Tobago',
	'tn' => 'Tunisia',
	'tr' => 'Turkey',
	'tm' => 'Turkmenistan',
	'tc' => 'Turks & Caicos Islands',
	'tv' => 'Tuvalu',
	'um' => 'U.S. Minor Outlying Islands',
	'ug' => 'Uganda',
	'ua' => 'Ukraine',
	'ae' => 'United Arab Emirates',
	'uk' => 'United Kingdom',
	'uy' => 'Uruguay',
	'uz' => 'Uzbekistan',
	'vu' => 'Vanuatu',
	'va' => 'Vatican City',
	've' => 'Venezuela',
	'vn' => 'Vietnam',
	'vi' => 'Virgin Islands',
	'wf' => 'Wallis & Futuna Islands',
	'eh' => 'Western Sahara',
	'ye' => 'Yemen',
	'yu' => 'Yugoslavia (Former)',
	'zr' => 'Zaire',
	'zm' => 'Zambia',
	'zw' => 'Zimbabwe',
	'ot' => '(Other)',
);

// ############################################################################
// US States list (sorry Canada)
$_states = array(
	'ot' => '(Outside US)',
	'al' => 'Alabama',
	'ak' => 'Alaska',
	'az' => 'Arizona',
	'ar' => 'Arkansas',
	'ca' => 'California',
	'co' => 'Colorado',
	'ct' => 'Connecticut',
	'de' => 'Delaware',
	'dc' => 'District of Columbia',
	'fl' => 'Florida',
	'ga' => 'Georgia',
	'hi' => 'Hawaii',
	'id' => 'Idaho',
	'il' => 'Illinois',
	'in' => 'Indiana',
	'ia' => 'Iowa',
	'ks' => 'Kansas',
	'ky' => 'Kentucky',
	'la' => 'Louisiana',
	'me' => 'Maine',
	'md' => 'Maryland',
	'ma' => 'Massachusetts',
	'mi' => 'Michigan',
	'mn' => 'Minnesota',
	'ms' => 'Mississippi',
	'mo' => 'Missouri',
	'mt' => 'Montana',
	'nb' => 'Nebraska',
	'nv' => 'Nevada',
	'nh' => 'New Hampshire',
	'nj' => 'New Jersey',
	'nm' => 'New Mexico',
	'ny' => 'New York',
	'nc' => 'North Carolina',
	'nd' => 'North Dakota',
	'oh' => 'Ohio',
	'ok' => 'Oklahoma',
	'or' => 'Oregon',
	'pa' => 'Pennsylvania',
	'ri' => 'Rhode Island',
	'sc' => 'South Carolina',
	'sd' => 'South Dakota',
	'tn' => 'Tennessee',
	'tx' => 'Texas',
	'ut' => 'Utah',
	'vt' => 'Vermont',
	'va' => 'Virginia',
	'wa' => 'Washington',
	'wv' => 'West Virginia',
	'wi' => 'Wisconsin',
	'wy' => 'Wyoming',
);

?>