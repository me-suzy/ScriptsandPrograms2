<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0


includeClass('PntUnitErrorHandlingEvent', 'pnt/unit/notifications');

/**
 * @package pnt/unit
 * @author  Henk Verhoeven, MetaClass <henk@phpPeanuts.org>
 */
class PntUnitTestErrorHandler {

	var $oldHandler = null;
	var $reportingLevel;
	var $errorLevelMap;
	var $testResult;

	function PntUnitTestErrorHandler()
	{
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
	
	/** @static
	*/
	function getWarningMask()
	{
		return E_WARNING | E_USER_WARNING;
	}
	
	/** @static
	*/
	function getNoticeMask()
	{
		return E_NOTICE | E_USER_NOTICE;
	}
	
	function getErrorMask()
	{
		E_ALL 
			^PntUnitTestErrorHandler::getWarningMask()
			^PntUnitTestErrorHandler::getNoticeMask();
	}
	
	/** @static
	*/
	function getDefaultReportingLevel()
	{
		return E_ALL ^ PntUnitTestErrorHandler::getNoticeMask();
	}
	
	function &getEventNotifier()
	{
		// $this was copied by php's set_error_handler function.
		// a testResult set to a field would have been copied to.
		global $pntTestNotifier;
		return $pntTestNotifier;
	}
	
	function startHandling()
	{
		$GLOBALS['pntErrorHandler'] =& $this;

		$this->oldHandler = set_error_handler('pntUnitTestErrorHandleFunc');
	}
	
	function handleError($level, $message, $filePath, $lineNumber)
	{
		if ( $this->isPntUnitFile($filePath) ) 
			$this->handlePntUnitInternalError($level, $message, $filePath, $lineNumber);
		else
			//HACK (no longer needed): do not trigger event on framework notifications
			// if ( !$this->isPntFile($filePath) || ($level & error_reporting()) )
				$this->triggerErrorEvent($level, $message, $filePath, $lineNumber);
			
		if ($level == E_USER_ERROR || $level == E_ERROR)
			die();
	}
	
	function handlePntUnitInternalError($level, $message, $filePath, $lineNumber)
	{
		if ( $level & error_reporting() ) {
			$this->printDebugInfo($level, $message, $filePath, $lineNumber);
		} 
	}
	
	/** This method will not recognize all errors caused by pntUnit itself,
	* but at least it should be good enoug to prevent the errorhandler to loop endlessly
	*/
	function isPntUnitFile($filePath)
	{
		$fileDir = dirname($filePath);
		$scriptDir = realpath('../pntUnit');
		if ($fileDir == $scriptDir) return true;
		
		$classDir = realpath('../classes/pnt/unit');
		return strPos($filePath, $classDir) === 0;
	}

	function isPntFile($filePath)
	{
		$classDir = realpath('../classes/pnt');
		$testDir = realpath('../classes/pnt/test');
		return strPos($filePath, $classDir) === 0 
			&& strPos($filePath, $testDir) !== 0;
	}

	function printDebugInfo($level, $message, $filePath, $lineNumber)
	{
		$levelName = $this->mapErrorLevel($level);
		print "<br />\n<B>$levelName:</B> '$message' in <B>$filePath</B><BR>\nat line <B>$lineNumber</B>";
		if (!$this->hasHandledError) {
			print "<BR>Request params: <BR>\n";
			print implode(assocsToStrings($_REQUEST), ", <BR>\n");
		}
		print "<br />\n";
	}
	
	function triggerErrorEvent($level, $message, $filePath, $lineNumber)
	{
		$levelName = $this->mapErrorLevel($level);
		$event =& PntUnitErrorHandlingEvent::getInstance($level, $levelName, $message, $filePath, $lineNumber);
		
		$notifier =& $this->getEventNotifier();
		$notifier->event($event);
	}

} 

function pntUnitTestErrorHandleFunc($level, $message, $filePath, $lineNumber)
{
	$GLOBALS['pntErrorHandler']->handleError($level, $message, $filePath, $lineNumber);
}
?>