<?php
/***************************************************************************
 * (c)2002-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
// Edit this to fit your needs
// begin user editable part
	// hostname mysql running on
	$dbhost = "localhost";
	// name of database
	$dbname = "news";
	// username for database
	$dbuser = "root";
	// password for databaseuser
	$dbpasswd = "dbpassword";
	// prefix for tables, so you can have multiple instances of
	// SimpNews in one database (please set before calling install or update)
	$tableprefix= "simpnews";
	// prefix for hostcache table, if you also use one of our other PHP scripts
	// you can set all hostcache tables to 1 prefix, so you only have 1 hostcache
	$hcprefix= "simpnews";
	// prefix for ip banlist table, if you also use one of our other PHP scripts
	// you can set all banlist tables to 1 prefix, so you only have 1 banlist
	$banprefix= "simpnews";
	// prefix for website leacher table, if you also use one of our other PHP scripts
	// you can set all leacher tables to 1 prefix, so you only have 1 leacherlist
	$leacherprefix= "simpnews";
	// prefix for badword table, if you also use one of our other PHP scripts
	// you can set all badword tables to 1 prefix, so you only have 1 badwordlist
	$badwordprefix= "simpnews";
	// default language, you can create your own languagefile
	// for other languages in ./language
	$default_lang = "de";
	// language to use for admininterface
	$admin_lang = "de";
	// URL-Path for SimpNews instance. If you use http://www.myhost.com/simpnews
	// this is /simpnews
	$url_simpnews = "/simpnews";
	// URL-Path for graphics (no trailing slash)
	$url_gfx=$url_simpnews."/gfx";
	// URL-Path for emoticons (no trailing slash)
	$url_emoticons=$url_gfx."/emoticons";
	// URL-Path for icons (no trailing slash)
	$url_icons=$url_gfx."/icons";
	// URL-Path for inline graphics (no trailing slash)
	$url_inline_gfx=$url_gfx."/inline";
	// complete path to directory containing scripts (without trailing slash)
	$path_simpnews = getenv("DOCUMENT_ROOT")."/simpnews";
	// complete path to directory containing graphics (without trailing slash)
	$path_gfx = $path_simpnews."/gfx";
	// complete path to directory containing emoticons (without trailing slash)
	$path_emoticons = $path_gfx."/emoticons";
	// complete path to directory containing icons (without trailing slash)
	$path_icons = $path_gfx."/icons";
	// complete path to directory containing inline graphics (without trailing slash)
	$path_inline_gfx = $path_gfx."/inline";
	// complete path to directory containing log files (without trailing slash)
	$path_logfiles = $path_simpnews."/logs";
	// It should be safe to leave this alone. But if you do change it
	// make sure you don't set it to a variable allready in use (use a seperate
	// name for each instance of SimpNews)
	$cookiename = "simpnews";
	// It should be safe to leave these alone as well.
	$cookiepath = $url_simpnews;
	$cookiesecure = false;
	// This is the cookie name for storing persistent data for admin interface, you shouldn't have to change it
	// (for multiple instances use different names)
	$admcookiename = "simpnewsadm";
	// This is the cookie name for the sessions cookie, you shouldn't have to change it
	// (for multiple instances use different names)
	// Must not be same as $cookiename !!!
	$sesscookiename = "simpnewssession";
	// This is the number of seconds that a session lasts for, 3600 == 1 hour.
	// The session will exprire if the user dosn't view a page on the admininterface
	// in this amount of time.
	$sesscookietime = 600;
	// Set this to true if you want to use sessionid passed throught get and put requests
	// rathern than by cookie (for details reffer to readme.txt)
	$sessid_url=false;
	// maximal filesize for attachements by admin
	$maxfilesize=1000000;
	// Set to true if you want to have password recovery for admin interface enabled
	$enablerecoverpw=true;
	// Set this to the charset to be used as content encoding
	$contentcharset="iso-8859-1";
	// Set this to the charset to be used for encoding. For available charsets see "htmlentities" in PHP manual
	$encodecharset="ISO-8859-1";
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
	$path_attach = $path_simpnews."/attachements";
	// complete path to directory, where uploaded files should temporary be copied to
	// (PHP has to have write permissions on this directory)
	// Needed if open_basedir restriction is in effect. If your PHP instance can
	// access the default upload directory, you don't need to set it
	// $path_tempdir = $path_simpnews."/admin/temp";
	// url to directory with attachements
	$url_attach = $url_simpnews."/attachements";
	// Set this to true if you want to use authentication by htacces rather then the
	// internal version (for details reffer to readme.txt)
	$enable_htaccess=false;
	// Please set this to the hostname, where your instance of SimpNews is installed
	$simpnewssitename="athlon.boesch.lan";
	// Please provide a short description of the site where SimpNews is installed on
	$simpnewssitedesc="Testsystem";
	// Set to true if you want to try to get the real IP of the user.
	// Please note: This may not work for all HTTPDs.
	$try_real_ip=false;
	// Method for trying real IP (0=default or 1=alternate method)
	// if determining IP with default mode fails within your server environment, maybe
	// alternate method works
	$try_real_ip_mode=0;
	// please enter all fileextension your server uses for PHP scripts here
	$php_fileext=array("php","php3","phtml","php4");
	// Here the association between used language names and system locales are defined.
	// If you add an additional language, also provide the appropriate locale here
	// Windows systems
	$def_locales=array("de"=>"german","en"=>"english");
	// Unix systems
	// $def_locales=array("de"=>"de_DE","en"=>"en_EN");
	// Full url for SimpNews with trailing slash
	$simpnews_fullurl = "http://".$simpnewssitename.$url_simpnews."/";
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
	// minimal fontsize to use for dropdown BBcode button
	$minfontsize=-10;
	// maximal fontsize to use for dropdown BBcode button
	$maxfontsize=10;
	// set to false if file upload is disabled in php.ini
	// uncomment next line, if you want to override autodetection for this setting
//	$upload_avail=true;
	// set this to true, if you are using PHP 4.1.0 or greater (has to be set to true for
	// PHP 4.2.0 or greater)
	// uncomment next line, if you want to override autodetection for this setting
//	$new_global_handling=true;
	// set this to true, if you are using PHP 4.2.0 or greater
	// uncomment next line, if you want to override autodetection for this setting
//	$has_file_errors=true;
	// set this to true, if you are using PHP with MS IIS on Windows
	$iis_workaround=false;
	// set this to true, if the installation of PHP used provides support for libGD
	// uncomment next line, if you want to override autodetection for this setting
//	$gdavail=true;
	// subdirname used for storing thumbnails for inline gfx
	$thumbdir="thumbs";
	// If there is a conflict with the HTTP variable "lang", because e.g. your CMS needs it
	// set this to some other value. The calling URLs then not will be URL?lang=..., but
	// URL?<setvalue>=...
	$langvar="lang";
	// Type of new line to be used (\r\n = CRLF, \r=CR, \n=LF)
	$crlf="\r\n";
	// Set this to the time in sec., you'll allow for log running scripts until they should timeout
	$longrunner=1800;
	// Set this to true, if userview should send HTTP headers to prevent caching of pages
	$nocaching=false;
	// If you have register_globals set to on in php.ini and experience problems with POST/GET data
	// not getting to script, uncomment this. Note: globally registered vars will be overwritten
	// by POS/GET data
	// $ovwglobals=true;
	// Set this to true, if you get cached pages in admin interface
	$admoldhdr=false;
	// set this to true, if you want to disable the security check (config.php writeable...)
//	$noseccheck=true;
	// leaving this to false is the best.
	$testmode = false;
	// To enable debugging, uncomment the next line
//	$dodebug=true;
	// If you want to override checking for register_globals=off uncomment the next line
	// $no_rgcheck=true;
	// If you want to have the script remove the vars registered on register_globals=ob uncomment the next line
	// $dosafephp=true;
// end user editable part
	$fid="b073e76b30344b970271303a892b1d91";
// you are not allowed to edit beyond this point
	require_once($path_simpnews.'/includes/global.inc');
?>