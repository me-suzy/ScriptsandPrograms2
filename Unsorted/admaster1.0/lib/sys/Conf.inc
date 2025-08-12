<?
// debug params
$DEBUG_ON 		= false;
$DEBUG_LOG_PATH = "/user/local/name/logs.txt";
$DEBUG_STYLE    = "SYS";             // set it to "WEB" if you are going to view debug messages from browser 
									 // set it to "CMD" if you are going to view debug messages from command line
									 // else you can view debug message in file $ERROR_LOG_PATH

// testing params
$TEST_LOG_PATH = "./test_log.txt";
$TEST_STYLE    = "WEB";

// list user
$ITEMS_PER_PAGE  = 10;

// db parameters
$DB_HOST = "localhost";
$DB_NAME = "xsql";
$DB_USER = "xsql";
$DB_PSWD = "xsql";

// secret word
$SECRET_WORD = "opsrsdfnbmxbcvhtuytywoetr";

// path and e-mail address
$USER_PAGE_PATH = "http://localhost/admaster/";
$SENDER_EMAIL = "posmaster@thissite.com";

// upload info
$UPLOAD_PATH = "/user/local/name/images/";
$UPLOAD_NUM  = 3;

//sys include 
include_once "sys/debug/Debug.inc";
include_once "sys/test/Tester.inc";
include_once "sys/db/DB.inc";
?>