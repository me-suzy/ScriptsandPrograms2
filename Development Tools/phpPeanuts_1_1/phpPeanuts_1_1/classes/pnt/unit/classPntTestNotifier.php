<?php 
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

	
require_once('../classes/generalFunctions.php');
includeClass('PntTestSuiteResult', 'pnt/unit/notifications');
includeClass('PntTestCaseResult', 'pnt/unit/notifications');
includeClass('PntTestResult', 'pnt/unit/notifications');

class PntTestNotifier {
	
	var $failureMessageFormat = "<BR>\n<PRE><B>%s%s `%s` has failed.</B>
%s was checked against %s %s %s</PRE>";
	var $precisionAnnouncement = "with precision";
	var $errorDescriptionFormat = "in %s on line %s";
	var $handler_event;
	var $handler_testCaseResult;
	var $handler_testResult;
	var $errorHandler;
	var $suiteResult;
	var $severitiesByJudgement;
	var $runCount = 0;
	var $eventCounters;
	var $testCases;
	var $tests;
	var $currentTestCase;
	var $currentTest;
	var $currentTestResult;

	function PntTestNotifier()
	{
		$this->suiteResult =& new PntTestSuiteResult($null);
		$this->initSeveritiesByJudgement();
		$this->eventCounters = array();
	}
	
	function initSeveritiesByJudgement()
	{
		$this->severitiesByJudgement = array(
			'Pass' => 0,
			'Notice' => 1,
			'Failure' => 2,
			'Warning' => 3,
			'Error' => 4);
	}
	
	function &getSeveritiesByJudgement()
	{
		return $this->severitiesByJudgement;
	}
	
	function setHandler_event(&$handler)
	{
		$this->handler_event =& $handler;
	}
	
	function setHandler_testResult(&$handler)
	{
		$this->handler_testResult =& $handler;
	}
	
	function setHandler_testCaseResult(&$handler)
	{
		$this->handler_testCaseResult =& $handler;
	}

	function setHandler_suiteResult(&$handler)
	{
		$this->handler_suiteResult =& $handler;
	}

	function &getSuiteResult()
	{
		return $this->suiteResult;
	}
	
	function setSuiteResult(&$result)
	{
		$this->suiteResult =& $result;
	}

	function setTestCases(&$cases)
	{
		$this->testCases =& $cases;
	}

	function runRestOfCases()
	{
		while ($this->runNextCase()) {}
		
		$this->suiteResult($this->suiteResult);
	}

	function runNextCase()
	{
		list($key) = each($this->testCases);
		if ($key === null) {
			$testCase = null;
		} else {
			$testCase =& $this->testCases[$key];
		}

		return $this->runCase($testCase);
	}

	/** ! Must be called with $null after the last testCase has been executed !
	* This will happen automatically if you set the case classes 
	* and then call runRestOfCaseClasses()
	* @private
	*/
	function runCase(&$testCase)
	{
		if ($this->currentTestCase) {
			if ($this->currentTestCase->isIncremental())
				$this->currentTestCase->tearDown();
			$this->suiteResult->addChild($this->currentTestCaseResult);
			$this->testCaseResult($this->currentTestCase);
		}

		$this->currentTestCase =& $testCase;
		if (!$testCase) return false;
		
		$this->currentTestCaseResult =& new PntTestCaseResult($this->currentTestCase);
		$this->setTests($testCase->getTests());
		
		if ($testCase->isIncremental())
			$testCase->setUp();
			
		$this->runRestOfTests();
		return true;
	}

	function setTests(&$tests)
	{
		$this->tests =& $tests;
	}

	function runRestOfTests()
	{
		while ($this->runNextTest()) {}
	}
	
	function runNextTest()
	{
		list($key) = each($this->tests);
		if ($key === null) {
			$test = null;
		} else {
			$test =& $this->tests[$key];
		}

		return $this->runTest($test);
	}
	
	/** ! Must be called with $null after the last test has been executed
	* This will happen automatically if you set the tests and then run them
	* using runRestOfTests().
	* @private
	*/
	function runTest(&$test)
	{
		if ($this->currentTest) {
			$case =& $this->currentTest->getCase();
			if (!$case->isIncremental())
				$case->tearDown();
			
			$this->currentTestCaseResult->addChild($this->currentTestResult);
			$this->testResult($this->currentTestResult);
		}

		$this->currentTest =& $test;
		if (!$test) return false;
		
		$this->currentTestResult =& new PntTestResult($test);

		//not sure where the setup and teardown code should be: here or in test->execute
		$case =& $test->getCase();
		if (!$case->isIncremental())
			$case->setUp();

		$this->runCount++;
		$test->execute();

		return true;
	}
	
	function setErrorHandler($handler=null)
	{
		if ($handler)
			$this->errorHandler =& $handler;
		else
			$this->errorHandler =& $this->newErrorHandler();
			
		$this->errorHandler->startHandling();
	}
	
	/** This method returs the original errorhandler. The one that
	* is handling the errors is actually a copy.
	*/
	function &getErrorHandler()
	{
		return $this->errorHandler;
	}
	
	function &newErrorHandler()
	{
		includeClass('PntUnitTestErrorHandler', 'pnt/unit');
		return new PntUnitTestErrorHandler();
	}
	
	function event(&$event)
	{
		$event->setTest($this->currentTest);
		if ($this->currentTestResult)
			$this->currentTestResult->addChild($event);
		$judgement = $event->getJudgement();
		$this->incrementEventCount($judgement);

		if (isSet($this->handler_event))
			$this->handler_event->event($event);
		else
			$event->defaultHandling();
			
		if ($judgement != 'Error') return;
		
		//errorhandler will die() the current script if we return, so we continue from here
		$this->setErrorHandler($this->getErrorHandler()); //errorhandler seems to be reset during error handling

		if ($this->currentTestCase->isIncremental() && $this->currentTestCase->abortIncrementalOnError())
			return $this->runRestOfCases();
		
		$this->runRestOfTests();
		$this->runRestOfCases();
	}

	function testResult(&$testResult)
	{
		if (isSet($this->handler_testResult))
			$this->handler_testResult->testResult($testResult);
	}

	function testCaseResult(&$testCaseResult)
	{
		if (isSet($this->handler_testCaseResult))
			$this->handler_testCaseResult->testCaseResult($testCaseResult);
	}
	
	function suiteResult(&$suiteResult)
	{
		if (isSet($this->handler_suiteResult))
			$this->handler_suiteResult->suiteResult($suiteResult);
	}

	function getEventCount($judgement)
	{
		if ( isSet($this->eventCounters[$judgement]) )
			return $this->eventCounters[$judgement];
			
		return 0;
	}
	
	function incrementEventCount($judgement)
	{
		if ( isSet($this->eventCounters[$judgement]) )
			$this->eventCounters[$judgement]++;
		else
			$this->eventCounters[$judgement] = 1;
	}
	
	function getRunCount()
	{
		return $this->runCount;
	}


}