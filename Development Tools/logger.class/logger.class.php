<?php
/**
 * @package none
 * @copyright (C) 2005 Toon Goedhart
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @since v1
 * @tables none
 * @author Toon Goedhart
 *
 * @LastChanged $LastChangedDate: 2005-10-31 23:59:43 +0100 (ma, 31 okt 2005) $
 * @lastedited $LastChangedBy: Gebruiker $
 * @Revision $LastChangedRevision: 199 $
 *
 **/


// Error constants
define( "_lc_NOERROR", "No error" );
define( "_lc_LOGNOTFOUND", "Log file not found" );
define( "_lc_LOGNOTWRITABLE", "Log not writable" );
define( "_lc_CANTOPENLOG", "Can't open log" );
define( "_lc_CANTWRITETOLOG", "Can't write to log" );


/** 
 * This class facilitates simple logging to a text file.
 * Each log entry is written on a single line.
 *
 **/
class Logger {
	
	// Full path and name to the log file
	var $logFileName;
	// Format date/time of entry. See date() function for variables
	var $datetimeFormat;
	// Separator between field in an entry
	var $separator;
	// TRUE if entries are to be written
	var $loggingEnabled;
	
	
	/**
	 * Contructor.
	 * 
	 * @access public
	 * @param boolean $EnableLog Enable/disable logging on creation (optional)
	 * @return void
	 **/
	function Logger( $EnableLog=TRUE ) {
		$this->loggingEnabled = $EnableLog;
		$this->logFileName = "";
		$this->separator = chr(9);
		$this->datetimeFormat = "Y-m-d".$this->separator."H:i:s";
	}
	
	
	/**
	 * Writes an entry to the log file.
	 * $msg Can be either a string or an array.
	 * Multi-dimensional arrays are acceptable.
	 * 
	 * @access public
	 * @param mixed $msg Log-entry message
	 * @return boolean TRUE on success, FALSE on failure 
	 **/
	function writeLogEntry( $msg ) {
		$result = TRUE;
		
		if ( $this->loggingEnabled ) {
			if ( is_array( $msg )) {
			    $msg = $this->_compoundMsg( $msg );
			}
			
			if ( $fp = fopen( $this->logFileName, 'a' )) {
				$logEntry = date( $this->datetimeFormat ).$this->separator.$msg."\n";
				
				if ( fwrite( $fp, $logEntry ) === FALSE ) {
					fclose( $fp );
					$this->errorCode = _lc_CANTWRITETOLOG;
					return FALSE;
				}
				
			} else {
				$this->errorCode = _lc_CANTOPENLOG;
				$result =  FALSE;
			}
		}
		
		return $result;
	} // writeLogEntry
	
	
	/**
	 * Builds the message string from an array
	 * by recursively calling itself.
	 * 
	 * @access public
	 * @param array $msg Array containing log message
	 * @return string Message to log
	 **/
	function _compoundMsg( $msg ) {
		$msgString = "";
		
		while ( list( $key, $value ) = each( $msg )) {
			if ( is_array( $value )) {
			    $msgString .= (( $msgString == "" ) ? "" : $this->separator ).$this->_compoundMsg( $value );
				
			} else {
				$msgString .= (( $msgString == "" ) ? "" : $this->separator ).$value;
			}
		} // while
		
		return $msgString;
	} // _compoundMsg
	
	
	/**
	 * Enables or disables logging.
	 * 
	 * @access public
	 * @param boolean $enable TRUE to enable, FALSE to disable
	 * @return void 
	 **/
	function setLogging( $enable ) {
		$this->loggingEnabled = $enable;
	} // setLogging
	
	
	/**
	 * Returns the state of the logging variable
	 * 
	 * @access public
	 * @return boolean TRUE on enabled, FALSE on disabled
	 **/
	function getLogging() {
		return $this->loggingEnabled;
	} // getLogging
	
	
	/**
	 * Sets the name of the log file
	 * 
	 * @access public
	 * @param string $fileName Name of the log file
	 * @return void 
	 **/
	function setLogFileName( $fileName ) {
		$this->logFileName = $fileName;
	} // setLogFileName
	
	
	/**
	 * Returns the name of the log file
	 * 
	 * @access public
	 * @return string Name of the log file 
	 **/
	function getLogFileName() {
		return $this->logFileName;
	} // getLogFileName
	
	
	/**
	 * Sets the field separator.
	 * Modifies the date/time format to fit the new separator.
	 * 
	 * @access public
	 * @param string $sep Field separator
	 * @return void 
	 **/
	function setSeparator( $sep ) {
		$this->datetimeFormat = str_replace( $this->separator, $sep, $this->datetimeFormat );
		$this->separator = $sep;
	} // setSeparator
	
	
	/**
	 * Returns the separator currently in use
	 * 
	 * @access public
	 * @return string Separator 
	 **/
	function getSeparator() {
		return $this->separator;
	} // getSeparator
	
	
	/**
	 * Sets the format for the date/time.
	 * See the docs on date() for more information.
	 * 
	 * @access public
	 * @param string $fmt Date/time format
	 * @return void 
	 **/
	function setDateTimeFormat( $fmt ) {
		$this->datetimeFormat = $fmt;
	} // setDateTimeFormat
	
	
	/**
	 * Returns the format used for the date/time entries.
	 * 
	 * @access public
	 * @return string Date/time format 
	 **/
	function getDateTimeFormat() {
		return $this->datetimeFormat;
	} // getDateTimeFormat
}
?>