<?php
/*
+---------------------------------------------------------------+
|       Download Sentinel++ 
|        /ds_config.php
|
|	Version: >>v2.1.0<<
|
|        ©Kevin Lynn 2005
|        URL: http://scripts.ihostwebservices.com
|        EMAIL: scripts at ihostwebservices dot com
|
|        Released under the terms and conditions of the
|        GNU General Public License (http://gnu.org).
+---------------------------------------------------------------+
*/
error_reporting(0); // always have error reporting (to the browser) off on your server, if it isn't, then this line assures it is for this script. Use (E_ALL ^E_NOTICE) for debugging.

/* change to suit your site */
define('DS_URL', 'http://www.example.com'); // Where people should be directed after a bandwidth theft attempt.
define('DS_EMAIL', 'webmaster@example.com'); // Email address to send alerts to.
define('DS_DATADIR', '/home/example/ds_files/data/'); // base path to the data files (should be outside visible web) 
define('DS_FILEPATH', '/home/example/ds_files/files'); // Path to the your downloadable files. (should be outside visible web)
define('DS_DLLOG', DS_DATADIR.'ds_dllog.txt'); // Download log file
define('DS_DLLOGARC', DS_DATADIR.'ds_dllogarchive.txt'); // Archived Download log file
define('DS_RPTLOGARC', DS_DATADIR.'ds_rptlogarchive.txt'); // Archived Error report log file
define('DS_COUNTLOG', DS_DATADIR.'ds_count.php'); //Counter file, records quantity of file downloads in a text file.
define('DS_BWLOG', DS_DATADIR.'ds_bwlog.php'); //bandwidth recorder file
define('DS_RPTLOG', DS_DATADIR.'ds_rptlog.txt'); // text file for error log if option turned on below.
define('DS_TRLOG', DS_DATADIR.'ds_trlog.txt'); // text file for recording the token used and the http referer so they can be crossreferenced.
define('DS_TOKENS', DS_DATADIR.'ds_tokens.php'); // repository for unique tokens... future use.
define('DS_TRON', 1); // Set to 1 to have token / http_referer logging turned on. 0 to shut off. (a good way to catch a site stealing bandwidth)
define('DS_COUNTON', 1); // Set to 1 to record downloads in a txt file, 0 to shut off logging.
define('DS_DLON', 1); // Set to 1 to record download details. 0 to shut off logging.
define('DS_BWON', 1); // Set to 1 to turn on bandwidth checking, 0 to shut off.
define('DS_TFAIL', 1); // Set to 1 to have it report token failures. Set to 0 to shut off.
define('DS_BWALERT', 1); // Set to 1 to have it report BW alerts (when the bandwidth limit is reached). Set to 0 to shut off.
define('DS_REPORTON', 1); // Set to 1 to have it record errors. Set to 0 to shut off.
define('DS_RPTBYEMAIL', 0); // Set to 1 to have it send reports out via email. 0 to shut off.
define('DS_RPTBYFILE', 1); // Set to 1 to have a text file log of errors, 0 to shut off.
define('DS_TOKENON', 1); // Set to 1 to have the token check active. Set to 0 to shut off token checks. NOTE, Tokens help ensure downloads are only from approved sites.
define('DS_CTOKENON',1); // Set to 1 to enable cookie tokens which limits downloads to the same server and the browser that initiated it. Can be used to stop link sharing between browsers.
define('DS_STOKENON',1); // Set to 1 to enable session tokens which limits downloads to the same server. Use only if you do not wish to have ANY offsite downloads, even mirrors.
define('DS_UNQTKN', 0); // Set to 1 to have unique tokens for each file, this will use more resources so it could slow down script depending on the number of files.
define('DS_DLLOGSIZE', 1000000); // Size the download log file is allowed to get before archiving. Default is 1MB
define('DS_DLLOGARCSIZE', 3000000); // Size the archive file is allowed to get before being erased. A warning will be in the report file or email when it is reaching max.
define('DS_DLLOGARCWARN', 0.8); // The percentage the archive file can get before the warning is issued. 0.8 = 80%
define('DS_RPTLOGSIZE', 1000000); // Size the error log file is allowed to get before archiving. Default is 1MB
define('DS_RPTLOGARCSIZE', 3000000); // Size the error archive file is allowed to get before being erased. A warning will be in the report file or email when it is reaching max.
define('DS_ABLIMIT', 18000000000); //The absolute limit on bandwidth for the absolute limit of time. ie 18GB in a Month. Default setting is 18 GB.
define('DS_ATLIMIT', 2592000); // The absolute limit of time in seconds. Default is 1 month. A day would be 86400.
define('DS_INTLGTH', 10800); // The bandwidth interval length in seconds. Default is 10800 (3 hours) Which allows for 75 meg per interval with defaults. This spreads out the downloads over time. So that everyone has a chance to download a popular file.
define('DS_RATIO', 0.9); // the ratio of download size to actual bandwidth usage. Normally somewhat less than 1 normally due to people cancelling downloads after they are started. For example, use 0.5 if it's half.
define('DS_FTOKEN', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8'); // Your secret word. (5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8) Anyone with this word (and the right code) can download directly from your site. Change it to any alphanuneric sequence.
define('DS_ACTIVEDL', 30); // number of seconds the download token is good for.
define('DS_DLQTY', 3); // number of times someone can click on a download before a message pops up telling them to stop. This is automatically reset when the bandwidth limit is reached.

/* List of Tokens for sites that are allowed to link to your downloads. Disable token and that site cannot download. */
$list[] = DS_FTOKEN; // your sites secret word. Change above with the define BWFTOKEN, no need to repeat yourself.

/* Commented out by default remove comment block to enable other sites and enter their secret word between the single quotes
$list[] = 'some_other_sites_secret_word'; // A mirror's secret word. Record here the name of the site using it so you can check it in the token/referer log.
$list[] = 'another_sites_secret_word'; // Another mirror. etc etc
*/

/* Defined list. Change List array above, do not change this define. */
define('DS_TOKENLIST', serialize($list));

/* Database info */
define('DB_ON', 0); // use database for logging downloads? 1 for on, 0 for off.
define ('DB_HOST', 'localhost'); // for most people this will be localhost, but it could be a sub-domain or IP
define ('DB_NAME', 'youraccount_dsplus'); // The name of your database.. for people on shared hosts, don't forget your account name in front ie "accountname_mydbname"
define ('DB_USER', 'youraccount_dbusername'); // User name for that database, also with a shared host the account is usually prepended. ie "accountname_user"
define ('DB_PASS', 'dbpassword'); // password for that user
define ('DB_TABLE', 'ds_filedata'); // change to the name of the table you are using (assuming you are logging with a database.
define ('DB_INCFIELD', 'downloads'); // change to the name of the field you are incrementing
define ('DB_CRITERIAFIELD', 'filename'); // change to the name of the field you are using to specify which field to increment.

/* Messages issued by the script to the browser */
define('DS_BMESS1', 'Invalid File Name!');
define('DS_BMESS2', 'Invalid Token!');
define('DS_BMESS3', 'That file was not found on the server.');
/*bandwidth reached message */
define('DS_BWMESS', 'The download limit for this time period has been reached (wait '.DS_INTLGTH.' seconds and try again). Sorry for the inconvenience.<br />'); // no need to change unless you wish to.
define('DS_BWMESSFULL', 'The total bandwidth limit for this time period has been reached (wait '.DS_ATLIMIT.' seconds and try again). Sorry for the inconvenience.<br />'); // no need to change unless you wish to.
/* Token failure message */
define('DS_TFAILMESS', "<p>Your session has failed, please go back, reload the page, and try the link again.</p><p>Possible reasons for failure are: </p><p>1. Your session may have timed out.</p><p>2. You may need cookies enabled.</p><p>3. You may not be downloading from a valid download mirror.</p><p>Download from here instead <a href='".DS_URL."'>".DS_URL."</a></p>");
define('DS_DLMESS', 'Stop Clicking me!'); // the message they would get for clicking too many times.


/* Messages issued by the script to the report file or email */
define('DS_EMESS1', 'File missing');
define('DS_EMESS2', 'Bandwidth Limit Reached');
define('DS_EMESS3', 'Token Failure');
define('DS_EMESS4', 'file was missing or has had permissions changed. Please check its status.');
define('DS_EMESS5', ' (download archive) is reaching the maximum size. Download it now if you wish to save the data. Once maximum size is reached it will be deleted');
define('DS_EMESS6', 'Automated error message'); // Email Subject
define('DS_EMESS7', 'An error has occured, message is - '); // Email message start.
define('DS_EMESS8', 'From: Download Sentinel++'); // Email From header
define('DS_EMESS9', 'Database failed to connect');
define('DS_EMESSA', 'Cannot find database');
define('DS_EMESSB', 'Invalid query');
/*
+
|  Security notes:
|  This file should be located outside the visible web (where most people will not be able to see your sensitive info).
|  People on the same server can see this file if the host has not disabled several commands from being used by php or perl, etc.
|  They will not be able to see this file if the server is using CGI PHP and it is properly configured and/or safemode is on and properly configured.
|  On a shared host that does not have safemode on, nor using CGI PHP (phpsuexec), nor disabled exec, backtick operator, etc, you may be out of luck. Get hosted elsewhere.
| 
|  Do NOT use the same password for your database as that used for your main account with your host. Always try to use a different password.
|  More than 10 lines are added at the begining of this script and at the end as a security measure. Some hosts forget to disable "top" and "tail" which will show the first and last 10 lines of any file.
| 
+
*/
?>