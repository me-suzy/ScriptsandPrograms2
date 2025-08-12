<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0


/**
 * @package pnt/unit/notifications
 * @author  Henk Verhoeven, MetaClass <henk@phpPeanuts.org>
 */
class PntTestNotification {
	var $test;
	
	//category         E_USER_WARNING               failure
	//description      in file .... on line ...     assertEquals `label`
	//message          errormessage                 toCheck was tested against ref
	//judgement       Error, Warning, Failure, Notice, Pass

	function &getTestCase()
	{
		$test =& $this->getTest();
		if ($test)
			return $test->getCase();
		else
			return null;
	}

	function &getTest()
	{
		return $this->test;
	}
	
	function setTest($pntTest)
	{
		$this->test =& $pntTest;
	}
	
	function getMessage()
	{
		return '';
	}
	
	function getDescription()
	{
		return '';
	}
	
	function getJudgement()
	{
		$this->subclassResponsability();
	}
	
	function getCategory()
	{
		return $this->getJudgement();
	}
	
	function updateSuiteResult(&$testResult)
	{
		//$testResult->increment('failureCount');
		$this->subclassResponsability();
	}
	
	function defaultHandling(&$tesResult)
	{
		//$this->$tesResult->errorHandler->printDebugInfo(...);
		$this->subclassResponsability();
	}
}
?>