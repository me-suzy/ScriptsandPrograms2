<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0


includeClass('PntTestNotification', 'pnt/unit/notifications');

/**
 * @package pnt/unit/notifications
 * @author  Henk Verhoeven, MetaClass <henk@phpPeanuts.org>
 */
class PntUnitErrorHandlingEvent extends PntTestNotification {
	
	var $category;
	var $message;
		
	function &getInstance($level, $levelName, $message, $filePath, $lineNumber)
	{
		$class = PntUnitErrorHandlingEvent::getSubclassName($level);
		includeClass($class, 'pnt/unit/notifications');
		$instance =& new $class();
		$instance->initFromErrorHandling($level, $levelName, $message, $filePath, $lineNumber);
		return $instance;
	}
	
	function getSubclassName($level)
	{
		if ($level & PntUnitTestErrorHandler::getNoticeMask())
			return 'PntUnitNoticeEvent';
		if ($level & PntUnitTestErrorHandler::getWarningMask())
			return 'PntUnitWarningEvent';
			
		return 'PntUnitErrorEvent';
	}
	
	function initFromErrorHandling($level, $levelName, $message, $filePath, $lineNumber)
	{
		$this->category = $levelName;
		$this->message = $message;
		
		$this->errorLevel = $level;
		$this->filePath = $filePath;
		$this->lineNumber = $lineNumber;
	}
	
	function getCategory()
	{
		return $this->category;
	}

	function getMessage()
	{
		return $this->message;
	}
	
	function getDescription()
	{
		global $pntTestNotifier;
		return sprintf(
			$pntTestNotifier->errorDescriptionFormat,
			$this->getFileName(),
			$this->getLineNumber() );
	}
	
	function getErrorLevel()
	{
		$this->errorLevel;
	}
	
	function getFilePath()
	{
		return $this->filePath;
	}
	
	function getFileName()
	{
		return basename($this->getFilePath());
	}
	
	function getRelativePath()
	{
		return subStr($this->getFilePath(), strLen($_SERVER['DOCUMENT_ROOT']) );
	}
	
	function getLineNumber()
	{
		return $this->lineNumber;
	}
		
}
?>