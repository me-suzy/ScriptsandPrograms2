<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

// ValueValidator included by PntSite

/**  Objects of this class log and handle errors using php's set_error_handler function
* @see http://www.phppeanuts.org/site/index_php/Pagina/32
* 
* This abstract superclass provides behavior for the concrete
* subclass StringConverter in the root classFolder or in the application classFolder. 
* To keep de application developers code (including localization overrides) 
* separated from the framework code override methods in the 
* concrete subclass rather then modify them here.
* @see http://www.phppeanuts.org/site/index_php/Menu/178
* @package pnt
*/
class PntErrorHandler {

	var $logFilePath;
	var $oldHandler = null;
	var $reportingLevel;
	var $errorLevelMap;
	var $hasHandledError = false;
	var $stringConverter;
	var $developmentHost = 'development';
	var $emailAddress;

	function PntErrorHandler($logFilePath='../classes/pntErrorLog.txt', $emailAddress=null)
	{
		$this->logFilePath = $logFilePath;
		$this->emailAddress = $emailAddress;
		$this->reportingLevel = $this->getDefaultReportingLevel();
		$this->initErrorLevelMap();
	}
	
	function initErrorLevelMap()
	{
		$this->errorLevelMap = array(
			E_ERROR => 'E_ERROR' 
			, E_WARNING => 'E_WARNING' 
			, E_PARSE => 'E_PARSE' 
			, E_NOTICE => 'E_NOTICE' 
			,E_CORE_ERROR => 'E_CORE_ERROR' 
			,E_CORE_WARNING => 'E_CORE_WARNING' 
			,E_COMPILE_ERROR => 'E_COMPILE_ERROR' 
			,E_COMPILE_WARNING => 'E_COMPILE_WARNING' 
			,E_USER_ERROR => 'E_USER_ERROR' 
			,E_USER_WARNING => 'E_USER_WARNING' 
			,E_USER_NOTICE => 'E_USER_NOTICE' 
			,E_ALL => 'E_ALL'
			);
	}
	
	function mapErrorLevel($level)
	{
		$levelName = $this->errorLevelMap[$level];
		if ($levelName)
			return $levelName;
		else
			return $level;
	}
	
	function getDefaultReportingLevel()
	{
		return E_ALL ^ E_NOTICE ^ E_USER_NOTICE;
	}
	
	function getLoggingLevel()
	{ 
		return isSet($this->loggingLevel) ? $this->loggingLevel : $this->reportingLevel;
	}
	
	function &getStringConverter()
	{
		if ($this->stringConverter)
			return $this->stringConverter;
	
		includeClass('StringConverter');		
		return new StringConverter();
	}
	
	function startHandling()
	{
		$GLOBALS['pntErrorHandler'] =& $this;

		$this->oldHandler = set_error_handler('pntErrorHandleFunc');
	}
	
	function handleError($level, $message, $filePath, $lineNumber)
	{
		
		if ($level & $this->getLoggingLevel()) {
			$format = class_exists('ValueValidator')
				? ValueValidator::getInternalTimestampFormat()
				: 'Y-m-d H:i:s';
			$timeStamp = date($format, mktime());
			$this->logError($level, $message, $filePath, $lineNumber, $timeStamp);
		}
		if ($level & $this->reportingLevel) {
			if ($this->isDevelopment() || $this->errorInErrorPage() )
				$this->printDebugInfo($level, $message, $filePath, $lineNumber, $timeStamp);
			elseif (!$this->hasHandledError)
				$this->informUser($level, $message, $filePath, $lineNumber, $timeStamp);
			$this->hasHandledError = true;
			if ($level == E_USER_ERROR || $level == E_ERROR)
				die();
		}
	}
	
	function logError($level, $message, $filePath, $lineNumber, $timeStamp)
	{
		$someInfo['timeStamp'] = $timeStamp;
		$someInfo['level'] = $level;
		$someInfo['levelName'] = $this->mapErrorLevel($level);
		$someInfo['message'] = $message;
		$someInfo['filePath'] = $filePath;
		$someInfo['lineNumber'] = $lineNumber;
		$someInfo['host'] = $_SERVER['HTTP_HOST'];
		$someInfo['user'] = isSet($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : null;
		$someInfo['clientIp'] = $_SERVER["REMOTE_ADDR"];
		$someInfo['clientHost'] = gethostbyaddr($_SERVER["REMOTE_ADDR"]);
		$someInfo['script'] = $_SERVER["SCRIPT_FILENAME"];
		$someInfo['requestParams'] = implode(assocsToStrings($_REQUEST), ", ");
		$someInfo['referer'] = isSet($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'no referer';

		error_log(toCsvString($someInfo)."\r\n", 3, $this->logFilePath);
	}

	// If you override this method to redirect to your own error page or script, you need to
    //override errorInErrorPage() too
	function informUser($level, $message, $filePath, $lineNumber, $timeStamp)
	{
		$cnv =& $this->getStringConverter();
		$timeStampString = $cnv->toLabel($timeStamp, 'timestamp');
		
		$errorText = urlEncode($this->getUserErrorText($level, $message, $filePath, $lineNumber, $timeStampString));
		$info = urlEncode($this->getUserErrorInfo($level, $message, $filePath, $lineNumber, $timeStampString));
		$cause = urlEncode($this->getUserErrorCause($level, $message, $filePath, $lineNumber, $timeStampString));
		$url = $this->getErrorPageUrl($errorText, $info, $cause);
		print "
			<script>
				document.location.href='$url';
			</script>";
	}
	
	function getErrorPageUrl($errorText, $info, $cause)
	{
		return  "index.php?pntHandler=ErrorPage&errorMessage=$errorText&pntInfo=$info&errorCause=$cause";
	}
	
	function errorInErrorPage()
	{
		return isSet($_REQUEST['pntHandler']) && $_REQUEST['pntHandler'] == 'ErrorPage';
	}
		
	function getUserErrorText($level, $message, $filePath, $lineNumber, $timeStampString)
	{
		return "<H2>Software failure at $timeStampString<H2>";
	}
	
	function getUserErrorInfo($level, $message, $filePath, $lineNumber, $timeStampString)
	{
		return "The failure has been logged for debugging. If you inform the application administrator or webmaster about the the date and time as printed on this page, the problem may be solved sooner";
	}
	
	function getUserErrorCause($level, $message, $filePath, $lineNumber, $timeStampString)
	{
		return "Cause: ".$this->getErrorCause($level, $message, $filePath, $lineNumber, $timeStampString);
	}

	function getErrorCause($level, $message, $filePath, $lineNumber, $timeStampString)
	{
		$pieces = explode('/', $filePath);
		$fileName = $pieces[count($pieces)-1];
		return subStr($fileName, 0, strlen($fileName)-4).$lineNumber;
	}
	
	function printDebugInfo($level, $message, $filePath, $lineNumber, $timeStamp)
	{
		$levelName = $this->mapErrorLevel($level);
		print "<br />\n<B>$levelName:</B> '$message' in <B>$filePath</B><BR>\nat line <B>$lineNumber</B>";
		if (!$this->hasHandledError) {
			print "<BR>Request params: <BR>\n";
			print implode(assocsToStrings($_REQUEST), ", <BR>\n");
		}
		print "<br />\n";
	}
	
	function isDevelopment()
	{
		return $_SERVER['HTTP_HOST'] == $this->developmentHost;
	}

} 

function pntErrorHandleFunc($level, $message, $filePath, $lineNumber)
{
	$GLOBALS['pntErrorHandler']->handleError($level, $message, $filePath, $lineNumber);
}
?>