<?php
// Copyright 2001 - 2004 SQLFusion LLC           info@sqlfusion.com

  /**
   * Base Object 
   * @see BaseObject
   * @package PASClass
   */
  /**
   * Base Object Class
   *
   * Its the original object of all.
   * It handle basic attributes and methods common to all objects.
   * Basic and simple error handling
   * This class will need to be fully rewriten soon.
   *
   * @package PASClass
   * @author Philippe Lewicki  <phil@sqlfusion.com>
   * @copyright  SQLFusion LLC 2001-2004   
   * @version 3.0.0
   * @access public
   *
   */

    class BaseObject {
        var $objErrorId = 0;
        var $objErrorDesc  = "";
        var $objErrorFile = "pas_errors.log" ;
        var $objLogRunFile = "pas_run.log";
        var $objLogFilesPath = "./";
        var $objLogErrors = true ;
        var $objLogRun = true;
        var $objDisplayErrors = true ;
        var $objDisplayRunLog = false ;

		/** 
		 *  Return the last error message from PAS or PHP
		 **/
		
        function getErrorMessage() {
            if (strlen($this->objErrorDesc) >0) {
                return $this->objErrorDesc ;
            } 
	    //else {
            //    global $php_errormsg ;
            //    if ($this->objLogErrors && strlen($php_errormsg) > 0) {
            //      error_log($php_errormsg, 3, $this->objLogFilesPath.$this->objErrorFile) ;
            //    }
            //    return $php_errormsg ;
            //}
        }
		
		/**
		 *  Return the last error message from PHP only
		 **/
		
        function getPHPError() {
            global $php_errormsg ;   
            if (strlen($php_errormsg)> 0) {
                return $php_errormsg ;
            } else {
                return 0;
            }
        }
		
		/**
		 *  Check is there is an error already set
		 **/
		
        function isError() {
         //   global $php_errormsg ;
          //  if (strlen($php_errormsg)> 0 || $this->objErrorId) {
	   if (!empty($this->objErrorDesc)) {
                return true ;
            } else {
                return false ;
            }
        }
		
		/**
		 *  Set an Error to be logged in and eventualy displayed.
		 *  The error message when logged will be formated with the date, time, uri and referer
		 *  The error will be written in the default pas_error.log file.
		 *  A PAS error will be thrown.
		 **/
		
        function setError($message, $id=0) {
            global $HTTP_SERVER_VARS, $php_errormsg ;
            $requesturi = $HTTP_SERVER_VARS["REQUEST_URI"] ;
            $referer = $HTTP_SERVER_VARS["REFERER"] ;
            $this->objErrorDesc = $message ;
            $this->objErrorId = $id ;
            if ($this->objLogFilesPath.$this->objLogErrors) {
                if (!file_exists($this->objLogFilesPath.$this->objErrorFile)) {
                    $fp = @fopen($this->objLogFilesPath.$this->objErrorFile, "w") ;
                    @fwrite($fp, "#PAS error logs \n") ;
                    @fclose($fp) ;
                }
                if(is_writable($this->objLogFilesPath.$this->objErrorFile)) {
                    $logm = "\n".date("Y/m/d - H:m:i") ;
                    $logm.= " - (".get_class($this) ;
                    $logm.= ") - ".$message." uri : ".$requesturi." referrer : ".$referer."\n" ;
                    //error_log($logm, 3, $this->objLogFilesPath.$this->objErrorFile) ;
                    $this->writeLog($logm, $this->objLogFilesPath.$this->objErrorFile);
                }
            }
            if ($this->objDisplayErrors) {
                echo "<font color=red>(".get_class($this).") : ".$message."</font>" ;
            }
            $php_errormsg = "" ; // will that clean the global one, anyway its not currently used
        }
        
		/**
		 * Set a message in the run log.
		 * Messaged are not formated so you need to add your hown \n (carriage return)
		 *
		 **/
		
        function setLog($message) {
            if ($this->objLogRun) {
                if (!file_exists($this->objLogFilesPath.$this->objLogRunFile)) {
                    $fp = fopen($this->objLogFilesPath.$this->objLogRunFile, "w") ;
                    fwrite($fp, "#PAS Run logs \n") ;
                    fclose($fp) ;
                }
              //  echo $this->objLogFilesPath.$this->objLogRunFile;
                if(is_writable($this->objLogFilesPath.$this->objLogRunFile)) {
                    //$logm = date("Y/m/d - H:m:i") ;
                    $logm = $message ;
                    //error_log($logm, 3, $this->objLogFilesPath.$this->objLogRunFile) ;

                    $this->writeLog($logm, $this->objLogFilesPath.$this->objLogRunFile);
                }
            }
            if ($this->objDisplayRunLog) {
                echo nl2br($message) ;
            }
        }
		
		/**
		 * Private class to write to the log file.
		 * @private
		 **/
		
        function writeLog($message, $file) {
            $fp = fopen($file, "a") ;
            fwrite($fp, $message) ;
            fclose($fp) ;
        }
		
		/** 
		 * Set path for the error and log files
		 **/
        function setLogFilesPath($path) {
            $this->objLogFilesPath = $path ;
        }
		
		/**
		 * Set the file name for the error log
		 **/
        function setLogErrorFile($filename) {
            $this->objErrorFile = $filename ;
        }
		
		/**
		 * Set the file name for the run log file
		 **/
        function setLogRunFile($filename) {
            $this->objLogRunFile = $filename ;
        }
		
		/**
		 * Set the loggin of errors on
		 * setLogErrors(true) will turn error loggin on
		 */
        function setLogErrors($bool) {
            $this->objLogErrors = $bool ;
        }
		
		/** 
		 * Set the run log on
		 * setLogRun(true) will turn on the writting 
		 * of logs in the runlog file.
		 * if its "false" all the setLog() will be ignored
		 **/
        function setLogRun($bool) {
            $this->objLogRun = $bool ;
        }
		
		/**
		 * Set the display of errors
		 * setDisplayErrors(true) will turn on the display of error messages
		 **/
        function setDisplayErrors($bool) {
            $this->objDisplayErrors = $bool ;
        }
		
		/**
		 * Set the display of logs in web pages.
		 * setDisplayRunLog(true) will display all the logs when set.
		 **/
        function setDisplayRunLog($bool) {
            $this->objDisplayRunLog = $bool ;
        }
    }
?>
