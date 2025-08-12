<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
// Edit this to fit your needs
// Begin edit
	// hostname mysql running on
	$dbhost = "localhost";
	// name of database
	$dbname = "faq";
	// username for database
	$dbuser = "root";
	// password for databaseuser
	$dbpasswd = "dbpassword";
	// prefix for tables, so you can have multiple instances of
	// FAQEngine in one database (please set before calling install or update)
	// Note for all prefix entries: only use a-z and 0-9, no special characters
	// (especially -, +, /, \)
	$tableprefix= "faq";
	// prefix for hostcache table, if you also use one of our other PHP scripts
	// you can set all hostcache tables to 1 prefix, so you only have 1 hostcache
	$hcprefix= "faq";
	// prefix for ip banlist table, if you also use one of our other PHP scripts
	// you can set all banlist tables to 1 prefix, so you only have 1 banlist
	$banprefix= "faq";
	// prefix for website leacher table, if you also use one of our other PHP scripts
	// you can set all leacher tables to 1 prefix, so you only have 1 leacherlist
	$leacherprefix= "faq";
	// prefix for badword table, if you also use one of our other PHP scripts
	// you can set all badword tables to 1 prefix, so you only have 1 badwordlist
	$badwordprefix= "faq";
	// default language, you can create your own languagefile
	// for other languages in ./language
	$default_lang = "de";
	// language to use for admininterface
	$admin_lang = "de";
	// URL-Path for FAQEngine instance (no trailing slash !). If you use http://www.myhost.com/faq
	// this is /faq
	$url_faqengine = "/faq";
	// URL-Path for graphics to include in FAQs (no trailing slash !)
	$url_gfx = "/faq/gfx/inline";
	// complete path to directory containing FAQEngine (without trailing slash)
	$path_faqe = getenv("DOCUMENT_ROOT")."/faq";
	// complete path to directory containing graphics to include in FAQs (without trailing slash)
	$path_gfx = $path_faqe."/gfx/inline";
	// complete path to directory containing log files (without trailing slash)
	$path_logfiles = $path_faqe."/logs";
	// Set this to true, if last visitdate of user should be stored on his
	// computer and only faqs newer than this date should be displayed.
	$usevisitcookie = true;
	// It should be safe to leave this alone as well. But if you do change it
	// make sure you don't set it to a variable already in use (use a seperate
	// name for each instance of FAQEngine)
	$cookiename = "faqengine";
	// It should be safe to leave these alone as well.
	$cookiepath = $url_faqengine;
	$cookiesecure = false;
	// This is the cookie name for the sessions cookie, you shouldn't have to change it
	// (for multiple instances use different names)
	$sesscookiename = "faqenginesession";
	// This is the cookie name for storing persistent data for admin interface, you shouldn't have to change it
	// (for multiple instances use different names)
	$admcookiename = "faqengineadm";
	// This is the number of seconds that a session lasts for, 3600 == 1 hour.
	// The session will exprire if the user doesn't view a page on the admininterface
	// in this amount of time.
	$sesscookietime = 600;
	// If there is a conflict with the HTTP variable "lang", because e.g. your CMS needs it
	// set this to some other value. The calling URLs then not will be URL?lang=..., but
	// URL?<setvalue>=...
	$langvar="lang";
	// Set this to true if you want to use authentication by htacces rather then the
	// internal version (for details reffer to readme.txt)
	$enable_htaccess=false;
	// Set this to true if you want to use sessionid passed throught get and put requests
	// rathern than by cookie (for details reffer to readme.txt)
	$sessid_url=false;
	// Please set this to the hostname, where your instance of FAQEngine is installed
	$faqsitename="athlon.boesch.lan";
	// Please provide a short description of the site where FAEngine is installed on
	$faqsitedesc="Testsystem";
	// Protocol used to access FAQEngine
	$faqe_prot="http";
	// full url (incl. protocol) to main directory of FAQEngine
	$faqe_fullurl=$faqe_prot."://".$faqsitename.$url_faqengine;
	// Set to true if you want to have password recovery for admin interface enabled
	$enablerecoverpw=true;
	// maximal filesize for attachements by admin
	$maxfilesize=20000000;
	// Set this to true, if you want to have attachements stored in file system instead of DB
	// WARNING: directory with attachements must be world writeable (chmod 777) on most servers
	// to allow webserver to write to it. Also mention:
	// * if you delete the attachement only in filesystem,
	//   the reference in the DB still will be present and point to nothing.
	// * Don't switch between true and false, while there are attachements in database
	//   migrate them in admin interface first and then switch here
	$attach_in_fs=true;
	// Set this to true, if you have permission problems with uploaded attachements
	$attach_do_chmod=false;
	// Set this to the permission mask to use, if $attach_do_chmod is enabled
	$attach_fmode=0644;
	// complete path to directory, where attachements should be stored
	$path_attach = getenv("DOCUMENT_ROOT")."/faq/attachements";
	// url to directory with attachements
	$url_attach = "/faq/attachements";
	// please enter all fileextension your server uses for PHP scripts here
	$php_fileext=array("php","php3","phtml");
	// Set this to the charset to be used as content encoding
	$contentcharset="iso-8859-1";
	// Set this to the charset to be used for encoding. For available charsets see "htmlentities" in PHP manual
	$encodecharset="ISO-8859-1";
	// minimal fontsize to use for dropdown BBcode button
	$minfontsize=-10;
	// maximal fontsize to use for dropdown BBcode button
	$maxfontsize=10;
	// set DB engine to be used:
	// 1 = mySQL
	$dbengine=1;
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
	// set to false if file upload is disabled in php.ini
	// uncomment next line, if you want to override autodetection for this setting
//	$upload_avail=true;
	// set this to true, if dropdown menu doesn't work in your browser (switching may
	// resolve the problem on some browsers)
	$alt_admmenu=false;
	// set this to true, if you are using PHP 4.1.0 or greater (has to be set to true for
	// PHP 4.2.0 or greater)
	// uncomment next line, if you want to override autodetection for this setting
//	$new_global_handling=true;
	// set this to true, if you are using PHP 4.2.0 or greater
	// uncomment next line, if you want to override autodetection for this setting
//	$has_file_errors=true;
	// Set this to true, if you get cached pages in admin interface
	$admoldhdr=false;
	// set this to true, if you are using PHP with MS IIS on Windows
	$iis_workaround=false;
	// set this to true, if you want to disable the security check (config.php writeable...)
//	$noseccheck=true;
	// leaving this to false is the best.
	$testmode = false;
	// Type of new line to be used (\r\n = CRLF, \r=CR, \n=LF)
	$crlf="\r\n";
	// set this to true, if passwords for failed logins shouldn't be tracked
	$failednopw=true;
	// Set this to the time in sec., you'll allow for log running scripts until they should timeout
	$longrunner=1800;
	// Set this to true, if you're trying to access admin interface through a proxy cluster
	// (so the acessing IP changes within session)
	$try_real_ip=false;
	// Method for trying real IP (0=default or 1=alternate method)
	// if determining IP with default mode fails within your server environment, maybe
	// alternate method works
	$try_real_ip_mode=0;
	// If you want to have the script remove the vars registered on register_globals=ob uncomment the next line
	// $dosafephp=true;
	// If you want to override checking for register_globals=off uncomment the next line
	// $no_rgcheck=true;
// end edit
// you are not allowed to edit beyond this point
	require_once($path_faqe."/includes/global.inc");
?>