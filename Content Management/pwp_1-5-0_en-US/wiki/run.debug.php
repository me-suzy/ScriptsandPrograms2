<?php
/**
 * DEBUG VERSION. Request handler and state machine for
 * the net.assistant framework v2.0 modified for wiki
 * ( no user / no dictionary )
 * Requires PHP 4.2.x or higher, supports register_globals off.
 * Contains debugging and logging functions.
 *
 * @author Lars Ackermann
 * @status Part of PWP Wiki Processor, licensed under GPL.
 * $Id: $
 */

define('BASE_PATH', './');

//debugging and error handling stuff
$_gTimeStart = _getMicroTime();
error_reporting(E_ALL);
set_error_handler('_errorLog');
$_gIntrospections = array();
$_gLog = array();

//--- quick fix for Xitami-CGI
if (empty($_SERVER['PHP_SELF'])) {
	$_SERVER['PHP_SELF'] = $_SERVER['SCRIPT_NAME'];
}

//get a well defined user name
if (!isset($_SERVER['REMOTE_USER']) or ($_SERVER['REMOTE_USER'] == '-')) {
	$_SERVER['REMOTE_USER'] = '';
}

//include base classes
require_once( BASE_PATH.'conf/Config.inc' );
require_once( BASE_PATH.'core/Framework.inc' );

// Global objects which are still alife after a state change.
$gConfig  = new Config();
$gError   = new ErrorStorage();

//check for file existence, avoid upward path elements "."
if (!isset($_REQUEST['iRequest']) or (strpos($_REQUEST['iRequest'], '.') !== FALSE) or
    !file_exists(BASE_PATH."resp/{$_REQUEST['iRequest']}.inc")) {
	$gError->add('ErrorInvalidRequest');
	$_REQUEST['iRequest'] = 'etc/Error';
}

//helper vars
$_gResponse = NULL;		// a global response object.
$_gBuffer = '';			// output from the prevoius response
$_gRequest = NULL;		// holds class name separated from path
$_gArgs    = array();	// arguments passed between responses

//run the response
do {
	//iterate through states; state change is triggered by current response
	ob_start();

	include( BASE_PATH."resp/{$_REQUEST['iRequest']}.inc" );
	$_gRequest = substr( $_REQUEST['iRequest'], strpos( $_REQUEST['iRequest'], '/' ) + 1 );
	$_gResponse = new $_gRequest( $_gBuffer, $_gArgs ); 	//output and arguments passed from previous response
	$_REQUEST['iRequest']  = $_gResponse->getNewState(); 	//final response returns NULL

	$_gBuffer = ob_get_contents();
	ob_end_clean();

} while ($_REQUEST['iRequest'] != NULL);

//flush buffer include log and introspection
if ($gConfig->mProp['UseGzipHandler']) {
	ob_start("ob_gzhandler");
} else {
	ob_start();
}

$_gBuffer = str_replace( "</body>", '<p>', $_gBuffer);
$_gBuffer = str_replace( "</html>", '</p>', $_gBuffer);
//echo str_replace("\t", '', $_gBuffer);
echo $_gBuffer;
echo _printsIntrospections();
echo _printLog();
echo "</body>\n</html>";


//-- debugging / logging functions (globally available) ---

/** Records information about an object. */
function introspect($object, $message='---') {
	global $_gIntrospections;
	$_gIntrospections[] = array($message, $object);
}

/** Sends user log messages to error handler if not productive. */
function logMsg( $msg, $file = 'unkown file', $line = 0 ) {
	_errorLog( E_USER_NOTICE, $msg, $file, $line );
}

/** Prints recorded information about objects in HTML if not productive. */
function _printsIntrospections() {
	global $_gIntrospections;
	if (!empty($_gIntrospections)) {
		echo "<table cellpadding='3'>\n<tr bgcolor='#cccccc'><th>message</th><th>introspection</th><th>type</th></tr>\n";
		foreach($_gIntrospections as $introspection) {
			echo "<tr bgcolor='#ffffff'><td>$introspection[0]</td><td>";
			print_r($introspection[1]);
			echo "</td><td>";
			echo gettype($introspection[1]);
			echo "</td></tr>\n";
		}
		echo "</table>\n<br>\n";
	}
}

/** Prints user log and error messages if not productive. */
function _printLog() {
	global $gDB, $_gLog;
	echo "<table cellpadding='3'>\n<tr bgcolor='#cccccc'><th>log</th></tr>\n";
	foreach($_gLog as $entry) {
		echo "<tr bgcolor='#ffffff'><td>$entry</td></tr>\n";
	}
	$files = sizeof(get_included_files()) + 1; //run.php
	echo "<tr bgcolor='#ffffff'><td><b>Files loaded: $files</b></td></tr>\n";
	$time = _getTimerResult();
	echo "<tr bgcolor='#ffffff'><td><b>Time: $time</b></td></tr>\n";
	echo "</table>\n<br>\n";
}

/**
 * Stores messages in log array.
 * Use set error handler to apply this handler.
 */
function _errorLog($errno, $errstr, $errfile, $errline) {
	global $_gLog, $gConfig;
	$now = date('Y-m-d H:i:s', time());
	$_gLog[] = "$now, $errno, $errfile, $errline, $errstr\n";
}

/** Catch the time. */
function _getMicroTime(){
	list($usec, $sec) = explode(" ",microtime());
	return ((float)$usec + (float)$sec);
}

/** Returns the timer result in ms. */
function _getTimerResult() {
	global $_gTimeStart, $gConfig;
	if ($gConfig->mProp['IsProductive']) {
		return '';
	} else {
		return (round( _getMicroTime() - $_gTimeStart, 3 ) * 1000) . ' ms elapsed';
	}
}

?>
