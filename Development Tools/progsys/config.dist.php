<?php
// Edit this to fit your needs
// begin user editable part
	// hostname mysql running on
	$dbhost = "localhost";
	// name of database
	$dbname = "progsys";
	// username for database
	$dbuser = "root";
	// password for databaseuser
	$dbpasswd = "dbpassword";
	// prefix for tables, so you can have multiple instances of
	// ProgSys in one database (please set before calling install or update)
	$tableprefix= "progsys";
	// prefix for hostcache table, if you also use one of our other PHP scripts
	// you can set all hostcache tables to 1 prefix, so you only have 1 hostcache
	$hcprefix= "progsys";
	// prefix for ip banlist table, if you also use one of our other PHP scripts
	// you can set all banlist tables to 1 prefix, so you only have 1 banlist
	$banprefix= "progsys";
	// default language, you can create your own languagefile
	// for other languages in ./language
	$default_lang = "de";
	// language to use for admininterface
	$admin_lang = "de";
	// URL-Path for ProgSys instance. If you use http://www.myhost.com/progsys
	// this is /progsys
	$url_progsys = "/progsys";
	// complete path to directory containing scripts (without trailing slash)
	$path_progsys = getenv("DOCUMENT_ROOT")."/progsys";
	// This is the cookie name for storing persistent data for admin interface, you shouldn't have to change it
	// (for multiple instances use different names)
	$admcookiename = "psysadm";
	// It should be safe to leave this alone as well. But if you do change it
	// make sure you don't set it to a variable already in use (use a seperate
	// name for each instance of ProgSys)
	$cookiename = "progsys";
	// It should be safe to leave these alone as well.
	$cookiepath = $url_progsys;
	$cookiesecure = false;
	// This is the cookie name for the sessions cookie, you shouldn't have to change it
	// (for multiple instances use different names)
	$sesscookiename = "progsyssession";
	// This is the number of seconds that a session lasts for, 3600 == 1 hour.
	// The session will exprire if the user dosn't view a page on the admininterface
	// in this amount of time.
	$sesscookietime = 600;
	// Set this to true if you want to use sessionid passed throught get and put requests
	// rathern than by cookie
	$sessid_url=false;
	// Name of server, where ProgSys resided on
	$progsys_sitename="athlon.boesch.lan";
	// Please provide a short description of the site where ProgSys is installed on
	$progsys_sitedesc="Testsystem";
	// Full url for ProgSys with trailing /
	$progsys_fullurl = "http://".$progsys_sitename.$url_progsys."/";
	//set to true, if you want to have download statistics be compressed on month start
	$compress_download_stats = false;
	//set to true, if you want to call download.php with url=... and have new urls automatically
	//added as download file
	$auto_download_file=false;
	// are information for beta versions protected? (protection must be done externally by webserver)
	$protectbeta=false;
	// For sending emails through SMTP server instead of PHP mail function set this to true
	$use_smtpmail = false;
	// SMTP Server to use
	$smtpserver = "localhost";
	// SMTP Port
	$smtpport = 25;
	// SMTP Server needs authentication
	$smtpauth = false;
	// Authentication username
	$smtpuser = "";
	// Authentication password
	$smtppasswd = "";
	// Set to true if you want to try to get the real IP of the user.
	// Please note: This may not work for all HTTPDs.
	$try_real_ip=false;
	// Method for trying real IP (0=default or 1=alternate method)
	// if determining IP with default mode fails within your server environment, maybe
	// alternate method works
	$try_real_ip_mode=0;
	// set this to true, if you are using PHP 4.1.0 or greater (has to be set to true for
	// PHP 4.2.0 or greater)
	$new_global_handling=true;
	// Type of new line to be used (\r\n = CRLF, \r=CR, \n=LF)
	$crlf="\r\n";
	// Set this to the charset to be used as content encoding
	$contentcharset="iso-8859-1";
	// leaving this to false is the best.
	$testmode = true;
	// set to false if file upload is disabled in php.ini
	// uncomment next line, if you want to override autodetection for this setting
	$upload_avail=true;
	// set this to true, if server is using unix
	// set this to fals, if server is using windows...
	$unixserver = false;
	// set this to true, if the installation of PHP used provides support for libGD
	// uncomment next line, if you want to override autodetection for this setting
//	$gdavail=true;
	// If you want to override checking for register_globals=off uncomment the next line
	// $no_rgcheck=true;
	// If you want to have the script remove the vars registered on register_globals=ob uncomment the next line
	// $dosafephp=true;
	// Set this to true if you want to use authentication by htacces rather then the
	// internal version (for details reffer to readme.txt)
	$enable_htaccess=false;
// end user editable part
	ini_set("include_path", $path_progsys.'/includes/pear' . PATH_SEPARATOR . ini_get("include_path"));
	require_once($path_progsys.'/includes/global.inc');
?>
