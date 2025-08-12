<?php
/**
 *	(c)2005 http://Lauri.Kasvandik.com
 */

// these settings are for development machine... if you want to test this script
// on your own box then add here your development machine IP addr (for example 127.0.0.1)
if($_SERVER['SERVER_ADDR']=='192.168.0.1')
{
	error_reporting(E_ALL);
	define('SQL_PATH',			'mysql://root@localhost/xlquiz');
	define('TABLE_RESULTS',		'results2');
}

// here are production server settings
else
{
	define('SQL_PATH',			'mysql://SQLuser:SQLpass@localhost/databaseName');
	// database, where are stored tests results
	define('TABLE_RESULTS',		'results');

	error_reporting(0);
}

// i thinked little bit longer percpective, that maybe someone 
// whishes to translate this script ;) strings are located in "lang" 
// directory, and they are in ini-format
define('LANG',					'en');

// this is the time, after which user can't added into scores database (on same test)
define('RESULTS_EXPIRY_TIME', 1800);

?>