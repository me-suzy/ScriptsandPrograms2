<?
define('E_USER_ALL',    E_USER_NOTICE | E_USER_WARNING | E_USER_ERROR);
define('E_NOTICE_ALL',  E_NOTICE | E_USER_NOTICE);
define('E_WARNING_ALL', E_WARNING | E_USER_WARNING | E_CORE_WARNING | E_COMPILE_WARNING);
define('E_ERROR_ALL',   E_ERROR | E_PARSE | E_CORE_ERROR | E_COMPILE_ERROR | E_USER_ERROR);
define('E_NOTICE_NONE', E_ALL & ~E_NOTICE_ALL);
define('E_DEBUG',       0x10000000);
define('E_VERY_ALL',    E_ERROR_ALL | E_WARNING_ALL | E_NOTICE_ALL | E_DEBUG);

define('REPORT_UNDEFINED_INDEX', false);
define('REPORT_UNDEFINED_OFFSET', false);
define('REPORT_UNDEFINED_VARIABLE', false);
define('REPORT_UNINITIALIZED_STRING_OFFSET', false);
define('ERRORHANDLER_CONSOLE','
<script type="text/javascript" language="JavaScript"><!--
ErrorHandler = window.open("", "ErrorHandlerConsole","resizable=yes,scrollbars=yes,directories=no,location=no,menubar=no,status=no,toolbar=no");
ErrorHandler.document.open();
ErrorHandler.document.writeln("<html><head><title>ErrorHandler Console: %s<\\/title><\\/head><body style=\"FONT-SIZE: 11px; FONT-FAMILY:  Verdana, Arial, Helvetica, sans-serif\">");
%s
ErrorHandler.document.writeln("<\\/body><\\/html>");
ErrorHandler.document.close();
//--></script>
');
// timestamp format
define('ERRORHANDLER_DATE_FORMAT','');
$_SECTION   = array('CONTEXT', 'LOGGING', 'REPLACE', 'SOURCE');
$_LEVEL     = array('ALL' => 0, 'DEFAULT' => 0, 'CONTEXT' => 0, 'LOGGING' => 0, 'REPLACE' => 0, 'SOURCE' => 0);

$_TRAP      = array(); // error trap stack
$_TRAP_LEVEL= 0;    // error trap stack
$_CONSOLE   = '';   // CONSOLE window code
$_COLLECT   = '';   // COLLECT mail message
$SILENT			= false;
$_escchrs		= array("\t" => '\\t', "\n" => '\\n', "\r" => '\\r', '\\' => '&#092;', "'" => '&#39;', '</' => '<\\/');

function _init_error_handler($init, $level){
	if ($init==true) {
		ob_start('_console');
		ini_set('display_errors', !$SILENT);
		error_reporting($level); 
		set_error_handler("_handle_error");   	    
 	}
	else{
		error_reporting($level); 
	}
}

function _console( $output ){
	global $_CONSOLE, $SILENT;
	if ( empty($_CONSOLE) || $SILENT ){
		return $output;
	}
	$history = sprintf(ERRORHANDLER_CONSOLE, $_SERVER['SCRIPT_NAME'], $_CONSOLE);
	$return  = preg_replace('!(<head(?(?=\s)[^>]*)>)!i', '$1 '.$history, $output, 1);
	if ( strlen($return) > strlen($output) ){
		return $return;
	}
	else {
		return $history.$output;
	}
}

function _handle_error( $level, $err_str, $file_name, $line_no, $context ){
	global $_escchrs, $_CONSOLE;
	$source='';
	//$context='';


	// split error messages emitted by run-time generated codes
	// (e.g. create_function(), eval(), etc. )
	if ( preg_match('!^(.*)\((\d+)\) : (.*)$!U', $file_name, $match) ) {
		$file_name = $match[1];
		$line_no   = $match[2];
		$err_str   = $err_str.' in '.$match[3];
	}
	
	$message = _message($level, $file_name, $line_no, $err_str);
	//$source  = _source($level, $file_name, $line_no);
	//$context = _context($level, $context, $source);
	// send log to each destination
	//$this->_logall($level, $file_name, $message."\n(request: ".$HTTP_SERVER_VARS['SCRIPT_NAME'].")\n".$source.$context."\n");
	// if the actual error hits the REPLACE error level
	if ($message || $source) {
		$report = ini_get('error_prepend_string').nl2br($message).ini_get('error_append_string');
		if ( !empty($source) ){
			// opens a buffer to gather formatted $message, $source and $context
			ob_start();
				@highlight_string($source);
				$report .= ob_get_contents();
			ob_end_clean();
		}
		//if ( !empty($context) ){
		//	$report .= '<pre>'.print_r($context)."</pre>\r\n";
		//}
		// append reports to the CONSOLE
		$_CONSOLE .= sprintf("ErrorHandler.document.writeln('%s<hr \/>');\r\n", strtr($report, $_escchrs));
	}
}

function _message( $level, $file_name, $line_no, $err_str ){
	global $_LEVEL;
	$message = date(ERRORHANDLER_DATE_FORMAT);
	if ($level & ini_get('error_reporting')) {
		if ($level & E_ERROR_ALL) {
			$message .= ' <b>Error: </b> ';
		}
		elseif ($level & E_WARNING_ALL ) {
			$message .= ' <b>Warning: </b> ';
		}
		elseif ($level & E_NOTICE_ALL) {
			$notice=substr($err_str, 0, 11);
			if ($notice=='Undefined i' && REPORT_UNDEFINED_INDEX==false) {
				return false;
			}
			elseif($notice=='Undefined o' && REPORT_UNDEFINED_OFFSET==false){
				return false;
			}
			elseif($notice=='Undefined v' && REPORT_UNDEFINED_VARIABLE==false){
				return false;
			}
			elseif(substr($err_str, 14, 13)=='string offset' && REPORT_UNINITIALIZED_STRING_OFFSET==false){
				return false;
			}
			else{
				$message .= ' <b>Notice: </b> ';
			}
		}
		elseif ( $level & E_DEBUG ){
			$message .= ' <b>DEBUG information: </b> ';
		}
		else if ( $level & E_LOG ){
			$message .= ' <b>LOG message: </b> ';
		}
		
		// if SOURCE report is required, $file_name and $line_no not appended
		if ( $_LEVEL['SOURCE'] & $level ){
			$message .= sprintf("(0x%02x) %s\n", $level, $err_str);
		}
		else {
			$message .= sprintf("%s in <b>%s</b> on line <b>%s</b>\n\n", $err_str, $file_name, $line_no);
		}
	}

	return $message;
}
