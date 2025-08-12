<?
// This file contains the general configuration settings for the front-end and 
// back-end system. Please read all the provided comment so you don't forget 
// anything. Some third party application are semy integrated and need to be 
// configured separately. The application which need special attention are 
// described in this file.

// Error handling 
// Custom handling on or off [true|false]
// Error Level reporting

if (function_exists("_init_error_handler")) {
	//_init_error_handler(false, E_ERROR);
}
	
// Disable magic_quotes_runtime - don't change
set_magic_quotes_runtime(0);

// Cookie settings
ini_set("session.use_cookies", true);
// Extra security cookie settings,
// be carefull, this may cause login problems.
// ini_set("session.cookie_domain", $_SERVER["HTTP_HOST"]);

// Enabling this prevents php from adding session information to every url in the page
// HIGHLY RECOMMENDED to disable (remove comment from the line below), due to caching vulnerabilities with proxy
// ini_set("session.use_trans_sid", false);

// In conjunction with use_trans_sid, forces php to use cookies for session management
// HIGHLY RECOMMENDED.
// ini_set("session.use_only_cookies", true);

$database_type	= "mysql";		// Default = mysql
$PHP_SELF = array_pop(explode("/",$_SERVER["SCRIPT_NAME"]));

// SECTION I
// NOTICE
// Jetbox automatically detects if it is installed in a sub dir or not. If you 
// have problems with broken images or links this detection is probably not
// correct. 

// IF THE AUTOMATIC DETECTION FAILS
  // Uncomment $install_dir='/jetbox'; under section I.I and set your apropriate 
  // folder
  // Check SECTION V of this file.
  // It is possible to run Jetbox on any compatible system, but it might take
  // some manual tweaking. Please let me know if you into installation problems
  // and how your where able to solve them.

// Automatically get the installation dir.
if ( ereg( "(.*)/([^\/]+\.php)$", $_SERVER['SCRIPT_NAME'], $regs ) ){
	$install_dir = $regs[1];
}
elseif ( ereg( "(.*)/([^\/]+\.php)$", $_SERVER['PHP_SELF'], $regs ) ){
	$install_dir = $regs[1];
}
$pos=strpos($install_dir, "/admin");
if ($pos!==false) {
	$install_dir=substr($install_dir, 0, $pos);    
}
$pos=strpos($install_dir, "/includes");
if ($pos!==false) {
	$install_dir=substr($install_dir, 0, $pos);    
}

// SECTION I.I
// If the detection fails you should set it manually here. 
// Also check SECTION V of this file.
// Uncomment the line below and set the correct installation path, if Jetbox is not installed in a subfolder the install_dir should be /
// $install_dir='/jetbox';



// SECTION II
// SINGLE SERVER SETUP
$username = "";
$password = "";
$hostname = "localhost";
$database = "jetbox";
$table_prefix = "";

// SET to false to disable installation wizard
$install_jetbox=true;

// COMPATIBILITY
// Convert url to standard ? format
// Default false
$use_standard_url_method=false;


// USAGE STATISTICS
$phpOpenTracker_enabled=true;

// MULTIPLE SERVER SETUP
// YOU DON'T NEED TWO SERVERS TO RUN JETBOX
// Use this construction to switch between two or more server configurations
// It's only for easy development. (Your local test server and a remote live server)
/*
switch($_SERVER["HTTP_HOST"]){
	// Configure your host(s)
	// Specify all from where this website is available
	case "your.host.com":
	case "www.your.host.com":
		// Database configuration
		$username = "";
		$password = "";
		$hostname = "";
		$database = "";
		$table_prefix = "";
		$phpOpenTracker_enabled=true;
		// Convert url to standard ? format
		// Default false
		$use_standard_url_method=false;
	break;
	case "liveserver":
	case "www.liveserver":
		// Database configuration
		$username = "";
		$password = "";
		$hostname = "";
		$database = "";
		$phpOpenTracker_enabled=true;
		// Convert url to standard ? format
		// Default false
		$use_standard_url_method=false;
	break;
}
*/



// SECTION III

// SITE Settings
// Used as the front-page title
$sitename = "Jetbox CMS Site";
$jetstream_version = "v2.1";
//  Used as the admin section title
$site_title = "Jetbox ".$jetstream_version;

// P3P settings for the admin section
$_SETTINGS['ADMIN_P3P']="NOI NID OTPa STP COM STA";

// META INFORMATION
// Only used for the front-page.
// Site wide robot settings (ALL, NONE, INDEX, NOINDEX, FOLLOW, NOFOLLOW)
// For more information about robots check: http://www.robotstxt.org/wc/robots.html
$_SETTINGS["meta_robot"] = 'ALL';
// Keywords (comma separate all the keywords)
$_SETTINGS["meta_keywords"] = $sitename;
// Description
$_SETTINGS["meta_description"] = $sitename;

// SHOW current page in bread crum trail.
$show_current_page_in_bctr=false;
// EMAIL
// A general email address for you
$generic_email = "info@localhost.com";
// Administors email address (used to set the FROM-address when sending email)
$admin_email = "jetbox@localhost.com";
// Mail Headers for the reflection of the sender of the daily ad reports
$email_headers = "From: $admin_email \n";
// Administrators full name (used when sending emails)
$admin_name = "Jetbox";
$supplynewsrecipient = "jetbox@localhost.com";

// EMAIL SECURITY
// Specify the hosts the server may send email to
$_SETTINGS["allowed_email_hosts"] = array('localhost', $_SERVER["HTTP_HOST"], '', '', '', '', '', '', '');


// SECTION IV

// JPGRAPH PHP4 Graph Plotting library.
// This library is used in the statistics page to generate images
// Path to fonts
$_SETTINGS["ttf_folder"] = 'c:/windows/fonts/';
// $_SETTINGS[ttf_folder]='/usr/X11R6/lib/X11/fonts/truetype/';
// Cache folder for images
$_SETTINGS["jpgraph_cache_path"] = 'c:/windows/tmp/'; 
// $_SETTINGS[jpgraph_cache_path]='/tmp/';
// Use image cache (true|false)
$_SETTINGS["jpgraph_cache"] = false; 


// SECTION V

// TECHNICAL SITE SETTINGS

// The full url to the website
// With slash. // was withouth, changed in v2.0.10
$front_end_url = "http://".$_SERVER["HTTP_HOST"].$install_dir."/";
// The url to Jetstream CMS from the base of the webserver
// No traing slash.
$jetstream_url = $install_dir."/admin/cms";
// Full path to your front-end/htdocs folder
// No traing slash.

$front_end_path = getenv("DOCUMENT_ROOT").$install_dir;
// The directory of the website. default: /
// $absolutepath = $install_dir."/"; // changed in v2.0.10 no longer needed
// The file that processes all requests. dafault: $absolutepath."index.php/"  
//$absolutepathfull = $absolutepath."index.php/"; // changed in v2.0.10 

if(getenv("rewrite")!=1){
	$absolutepathfull = "index.php/";
}
else{
	$absolutepathfull = "";
}

// The absolute location to the base path for the file upload
$upload_base = $include_dir;

// In general do not change these values
// Locatie van uploaded files en images
$BASE_ROOT_FILES = $install_dir."/webfiles"; 
$BASE_ROOT_IMAGES = $install_dir."/webimages"; 




// SECTION VI

// TC - 04/10/05 Paging mod to allow for large recordsets in single table
// Uses the myPagina Class available at http://www.finalwebsites.com/
// SET for paging with tables with many records and will not apply unless row count exceeds the NUM_ROWS
define("NUM_ROWS", 20); // the number of records on each page
define("NUM_LINKS", 20); // the number of links inside the navigation for paging (the default value)

// AUTOMATIC LOGOUT POPUP
// Set time out in seconds for automatic logout popup to appear if a user 
// has been inactive in the admin for too long.
$autologoutpopup = 2400;

// POSTLISTER MAILINGLIST CONFIGURATION
// As a combined package Postlister is integrated in Jetstream CMS.
// Postlister isn't an official Jetbox project but it isnt' maintainded anymore by the project owner.
// See http://sf.net/projects/postlister/
// Header text for the mailinglist
// The text is generated in /admin/postlister/functions.php:generate_mail_txt();
$postlisterheaders = array('news'=>'News mailing',
									'events'=>'Upcoming events');


// ONLINE SUPPORT URL'S
// This url is used on the help page in the system.
// It provides an integrated interface to the inline faq system
$faq_url="http://jetbox.streamedge.com/faq.php";

// Url to the development site
$more_info_url="http://jetbox.streamedge.com/";


// RICH TEXT EDITOR
// For the RTE some extra configuration options can be found in config.inc.php in the 
// 'SCRIPT_URL' dir (see below) in general you don't have to change these
// Global replace from -> to, these values are replaced in *ALL* $_POST values that are 
// about to be inserted in the db.
// This is handy for the rich text editor which deforms all relative paths.
// The default location of the rich text editor is $_SERVER["HTTP_HOST"].$install_dir."/admin/cms/htmlarea/"
$replace_from = array("http://".$_SERVER["HTTP_HOST"].$install_dir."/admin/cms/tiny_mce/");
$replace_to = array("");
// Image dialog location
$_SETTINGS["img_dialog"] = 'imgpopup/insert_image.html';
// Host where the adminsystem is running, default: $_SERVER["HTTP_HOST"]
define("HOST", $_SERVER["HTTP_HOST"]);
// IMAGE_DIR and IMAGE_URL identify the Image directory "root" (MUST end in "/")
// Do NOT include "http://my.hostname.com" in IMAGE_URL; just the path from the
// DocumentRoot of your webserver.
define("IMAGE_DIR_RT", trim(getenv("DOCUMENT_ROOT").$install_dir."/webimages/"));
define("IMAGE_URL_RT", trim($install_dir."/webimages/"));
// SCRIPT_DIR and SCRIPT_URL identify where these scripts reside (MUST end in "/")
// Do NOT include "http://my.hostname.com" in SCRIPT_URL; just the path from the
// DocumentRoot of your webserver.
define("SCRIPT_DIR", trim(getenv("DOCUMENT_ROOT").$install_dir."/admin/cms/tiny_mce/plugins/advimage/"));
define("SCRIPT_URL", trim($install_dir."/admin/cms/tiny_mce/plugins/advimage/"));


// Tabs configuration of the cms
// Don't change these values unless you know what you are doing.
$auth_tabs = array (
			"1"		=>  array($jetstream_url."/index.php" => "Editorial overview"),
			"2"		=>  array($jetstream_url."/help.php?popup_help=1' target='_blank" => "Help"),
			"3"		=>  array($jetstream_url."/index.php" => "Editorial overview"),
			"4"		=>  array($PHP_SELF => $pagetitle),
			"5"		=>  array($jetstream_url."/index.php" => "Editorial overview"),
			"6"		=>  array($jetstream_url."/index.php" => $site_title . " Logged out"),
			"7"		=>  array($jetstream_url."/index.php" => $site_title . " CMS"),
			"8"		=>  array($jetstream_url."/../postlister/index.php" => "Mailinglist"),
			"9"		=>  array($jetstream_url."/index.php?task=logout" => "Logout"),
			"10"	=>  array($jetstream_url."/../postlister/index.php" => "Statistics"),
			"11"		=>  array($jetstream_url."/changes.php" => "Changes overview"),
			"12"		=>  array($jetstream_url."/user_prefs.php" => "Personal preferences"),
		);

$no_auth_tabs	 = array (
			"1"		=>  array($jetstream_url."/index.php" => $site_title . " Log in"),
			"2"		=>  array($jetstream_url."/help.php" => "Help"),
			"3"		=>  array($jetstream_url."/index.php" => $site_title . " CMS"),
			"4"		=>  array($jetstream_url."/index.php" => $pagetitle),
			"5"		=>  array($jetstream_url."/index.php" => "Editorial overview"),
			"6"		=>  array($jetstream_url."/index.php" => $site_title . " Logged out"),
			"7"		=>  array($jetstream_url."/index.php" => $site_title . " CMS"),
			"8"		=>  array($jetstream_url."/../postlister/index.php" => $pagetitle ),
		);

$dodefaultpage=true;
//if ($db_config_is_set==true) {
	# connect to our database

	$connect = @mysql_connect($hostname,$username,$password);
	$db_error = mysql_error();
	@mysql_select_db($database);
	$db_error2=mysql_error();
	if ($db_error<>'' || $db_error2<>'') {
		$error_message = "<h2>Database error</h2>";
		$db_error<>'' ? $error_message.="<b>".$db_error."</b><br>" : $error_message;
		$db_error2<>'' ? $error_message.="<b>".$db_error2."</b><br>" : $error_message;
		$error_message.= "Configure the database settings in includes/general_settings.inc.php<br>";
	}
//}

// FINAL CHECK: Configure:
// hosts,
// database settings,
// email settings,
// email security,
// JPGRAPH PHP4 Graph Plotting library.

// END OF CONFIG FILE

?>