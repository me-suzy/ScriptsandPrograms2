<?php
/**
* Root Object, any Class in the system extends this class.
*
* implements error handling...
*
* @author Niels Hoffmann <niels.hoffmann@freenet.de>
* @version 1.0.0; 2002/21/08; 10:00:00
*/
class systemObject {
	/**
	* path to logs
	* @var	sttring
	*/
	var $strLogPath = "./logs/";
	/**
	* definition of the logging codes
	* @var	array
	*/
	var $arrLoggingCodes = array (
		'errors' => array (
			'0' => "general Error",
		),
		'warnings' => array (
			'0' => "general Warning",
		),
		'notices' => array (
			'0' => "general Notice",
		),
		'debug' => array (
			'0' => "general Debug"
		)
	);
	/**
	* array of formfield errors
	* @var	array
	*/
	var $a_gs_formfield_error_text = array();
	/**
	* defines if the debug log is on or not
	* @var	boolean
	*/
	var $debug_on = false;
	/**
	* defines the starttime of the script
	* @var	integer
	*/
	var $script_start_time = 0;
	/**
	* defines the stoptime of the script
	* @var	integer
	*/
	var $script_stop_time = 0;
	
	
	/**
	* initializes the object
	* @access		public
	*/
	function systemObject() {
	} // end func systemObject
	
	/**
	* Writes an error line into "logs/error.log"
	* @access		protected
	* @param	string	$log_key_nr Code nr. of the error in "configuration/logging_codes.php".
	* @param	string	$extra_comment A free string of extra comment.
	* @param	string	$method Name of the method produced the error.
	* @param	string	$classname Name of the Class produced the Error, if no classname is given the actual classname is used.
	*/
	function writeErrorLog($log_key_nr, $extra_comment = "", $method = "", $classname = "") {
		if (!file_exists($this->strLogPath) OR !is_dir($this->strLogPath) ) {
			@mkdir($this->strLogPath, 0777);
			@chmod($this->strLogPath, 0777);
		}
		$logfile = $this->strLogPath . "/error.log";
		$logfile = str_replace("//", "/", $logfile);
		if ($method != "") $method = $method."()";
		if ($classname == "") $classname = get_class($this);
		if (!isset($this->arrLoggingCodes['errors'][$log_key_nr])) $log_key_nr = 0;
		$log_str = $log_key_nr . " | ".date("d-m-Y H:i:s")." | ".$classname."->" . $method . ": " . $this->arrLoggingCodes['errors'][$log_key_nr] . " | " . $extra_comment . "\n"; 
		$fp = fopen($logfile, "a+");
		fwrite($fp, $log_str);
		fclose($fp);
		//chmod ($logfile, 0777);
	} // end func writeErrorLog
	/**
	* Writes a warning line into "logs/warnings.log"
	* @access		protected
	* @param	string	$log_key_nr	Code nr. of the warning in <configuration/logging_codes.php>.
	* @param	string	$extra_comment	A free string of extra comment.
	* @param	string	$method	Name of the method produced the error.
	* @param	string	$classname	Name of the Class produced the Error, if no classname is given the actual classname is used.
	*/
	function writeWarningsLog($log_key_nr, $extra_comment = "", $method = "", $classname = "") {
		if (!file_exists($this->strLogPath) OR !is_dir($this->strLogPath) ) {
			@mkdir($this->strLogPath, 0777);
			@chmod($this->strLogPath, 0777);
		}
		$logfile = $this->strLogPath  . "/warnings.log";
		$logfile = str_replace("//", "/", $logfile);
		if ($method != "") $method = $method."()";
		if ($classname == "") $classname = get_class($this);
		if (!isset($this->arrLoggingCodes['warnings'][$log_key_nr])) $log_key_nr = 0;
		$log_str = $log_key_nr . " | ".date("d-m-Y H:i:s")." | ".$classname."->" . $method . ": " . $this->arrLoggingCodes['warnings'][$log_key_nr] . " | " . $extra_comment . "\n"; 
		$fp = fopen($logfile, "a+");
		fwrite($fp, $log_str);
		fclose($fp);
		//chmod ($logfile, 0777);
	} // end func writeWarningsLog

	/**
	* Writes a notice line into "logs/notices.log"
	* @access		protected
	* @param	string	$log_key_nr Code nr. of the notice in <configuration/logging_codes.php>.
	* @param	string	$extra_comment A free string of extra comment.
	* @param	string	$method Name of the method produced the error.
	* @param	string	$classname Name of the Class produced the Error, if no classname is given the actual classname is used.
	*/
	function writeNoticesLog($log_key_nr, $extra_comment = "", $method = "", $classname = "") {
		if (!file_exists($this->strLogPath) OR !is_dir($this->strLogPath) ) {
			@mkdir($this->strLogPath, 0777);
			@chmod($this->strLogPath, 0777);
		}
		$logfile = $this->strLogPath  . "/notices.log";
		$logfile = str_replace("//", "/", $logfile);
		if ($method != "") $method = $method."()";
		if ($classname == "") $classname = get_class($this);
		if (!isset($this->arrLoggingCodes['notices'][$log_key_nr])) $log_key_nr = 0;
		$log_str = $log_key_nr . " | ".date("d-m-Y H:i:s")." | ".$classname."->" . $method . ": " . $this->arrLoggingCodes['notices'][$log_key_nr] . " | " . $extra_comment . "\n"; 
		$fp = fopen($logfile, "a+");
		fwrite($fp, $log_str);
		fclose($fp);
		//chmod ($logfile, 0777);
	} // end func writeNoticesLog

	/**
	* Writes a debug line into "logs/debug.log"
	* @access		protected
	* @param	string	$log_key_nr Code nr. of the debug in <configuration/logging_codes.php>.
	* @param	string	$extra_comment A free string of extra comment.
	* @param	string	$method Name of the method produced the error.
	* @param	string	$classname Name of the Class produced the Error, if no classname is given the actual classname is used.
	*/
	function writeDebugLog($log_key_nr, $extra_comment = "", $method = "", $classname = "") {
		if (!file_exists($this->strLogPath) OR !is_dir($this->strLogPath) ) {
			@mkdir($this->strLogPath, 0777);
			@chmod($this->strLogPath, 0777);
		}
		$logfile = $this->strLogPath  . "/debug.log";
		$logfile = str_replace("//", "/", $logfile);
		if ($method != "") $method = $method."()";
		if ($classname == "") $classname = get_class($this);
		if (!isset($this->arrLoggingCodes['debug'][$log_key_nr])) $log_key_nr = 0;
		$log_str = $log_key_nr . " | ".date("d-m-Y H:i:s")." | ".$classname."->" . $method . ": " . $this->arrLoggingCodes['debug'][$log_key_nr] . " | " . $extra_comment . "\n"; 
		$fp = fopen($logfile, "a+");
		fwrite($fp, $log_str);
		fclose($fp);
		//chmod ($logfile, 0777);
	} // end func writeDebugLog

	/**
	* sets the starttime for the internal runtime counter
	* @access		protected
	*/
	function startTimer() { 
	    $microtime=explode(" ", microtime()); 
    	$this->script_start_time=$microtime[1]+$microtime[0]; 
    }  // end func start_timer
	
	
	/**
	* sets the stoptime for the internal runtime counter
	* @access		protected
	* @return	real	runtime	runtime of the script between start and stop of the timer.
	*/
	function stopTimer($stellen = 5) { 
	    $microtime=explode(" ", microtime()); 
    	$this->script_stop_time=$microtime[1]+$microtime[0]; 
	    return number_format(($this->script_stop_time-$this->script_start_time),$stellen); 
    }// end func stop_timer
	
	
	/**
	* Adds an error text for a wrong form field input
	* @access		protected
	* @param	string	$text	description of the input error
	*/
	function addFormFieldErrorText($text) {
		$this->a_gs_formfield_error_text[] = $text;
	} // end func addFormFieldErrorText

	/**
	* Returns an array of error descriptions in the form fields
	* @access		protected
	* @return	array	$array
	*/
	function getFormFieldErrorText() {
		return $this->a_gs_formfield_error_text;
	} // end func getFormFieldErrorText

	/**
	* Empties the array of error descriptions for form fields
	* @access		protected
	*/
	function resetFormFieldErrorText() {
		$this->a_gs_formfield_error_text = array();
	} // end func resetFormFieldErrorText
}// end class systemObject

?>