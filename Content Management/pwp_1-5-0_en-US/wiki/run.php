<?php
/**
 * STANDARD VERSION. Request handler and state machine for
 * the net.assistant framework v2.0 modified for wiki
 * ( no user / no dictionary )
 * Requires PHP 4.2.x or higher, supports register_globals off.
 *
 * @author Lars Ackermann
 * @status Part of PWP Wiki Processor, licensed under GPL.
 * $Id: $
 */

define('BASE_PATH', './');
set_error_handler('_errorLog');

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

//flush buffer
if ($gConfig->mProp['UseGzipHandler']) {
	ob_start("ob_gzhandler");
} else {
	ob_start();
}
echo $_gBuffer;


//-- debugging / logging functions (globally available) ---

/** Records information about an object. Disabled. */
function introspect($object, $message='') {}

/** Sends user log messages to error handler. Disabled. */
function logMsg( $msg, $file = 'unkown file', $line = 0 ) {}

/**
 * Logs error messages into HTML comments.
 * Use set error handler to apply this handler.
 */
function _errorLog($errno, $errstr, $errfile, $errline) {
	$now = date('Y-m-d H:i:s', time());
	echo "<!-- Error: $now, $errno, $errfile, $errline, $errstr -->\n";
}

?>