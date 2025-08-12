<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0


/**
 * @package pnt/unit/web
 * @author  Henk Verhoeven, MetaClass <henk@phpPeanuts.org>
 */
class PntUnitTestRunPart {

	/** page or part this part is a part of */
	var $whole;
	/** PntUnitTestNotifier $notifier Runs tests, routes events, builds results */
   	var $notifier; 
	/** array $classAndFileNames contains arrays, each with classname and filename
	* of testcases to be run */
	var $classAndFileNames;
	/** string $format printf format for test event row */
	var $format;
	/** array $colorsByJudgement lookupTable, @see PntUnitPage::initialize */
	var $colorsByJudgement;
	
	var $notificationsVisibleFrom = 1;
	
	var $defaultVisibilities;

	function PntUnitTestRunPart(&$whole, $format)
	{
		$this->whole =& $whole;
		$this->format = $format;
	}
	
	function setClassAndFileNames($classAndFileNames)
	{
		$this->classAndFileNames = $classAndFileNames;
	}
	
	/** PREREQUISITE: classAndFileNames must be set
	*/
	function printBody() {
		global $pntTestNotifier;
    	$this->notifier =& $pntTestNotifier;

		include('skinTestRunPart.php');
	}
	
	function &getTestResultsPart()
	{
		if (!isSet($this->testResultsPart)) {
			includeClass('PntUnitTestResultsPart', 'pnt/unit/web');
			$this->testResultsPart = new PntUnitTestResultsPart(
				$this,
				$this->format);
		}			
		return $this->testResultsPart;
	}
	
	function printTestResultsPart()
	{
		$this->getTestResultsPart();
		$this->testResultsPart->printBody();
		if ($this->classAndFileNames)
			$this->runTests();
		else 
			$this->testResultsPart->printFooter();
		
	}

	function runTests() 
	{
		$this->testResultsPart->setVisibilities($this->getVisibilities());
		$this->notifier->setHandler_event($this->testResultsPart);
		$this->notifier->setHandler_testResult($this->testResultsPart);
		$this->notifier->setHandler_suiteResult($this->testResultsPart);
		//$his->listener will print test results as they occur
		$this->notifier->setErrorHandler();

		$this->notifier->setTestCases($this->getTestCases());
		$this->notifier->runRestOfCases();
	}
	
	function &getTestCases()
	{
		$cases = array();
		
		while (list($key) = each($this->classAndFileNames)) {
			$className = $this->classAndFileNames[$key][0];
			$filePath = $this->classAndFileNames[$key][1];
			if ($this->checkTestCaseClass($className, $filePath)) {
				$case =& new $className();
				$case->setClass($className);
				$case->setFilePath($filePath);
				$cases[] =& $case;
			}
		}
		return $cases;
	}
	
	function checkTestCaseClass($aClassName, $filePath) 
	{
		$ok = is_subclassOr($aClassName, 'PntTestCase');
		if (!$ok) {
			printf(
				$this->format
				, 'Fatal'
				, $aClassName
				, ''
				, 'Can not run'
				, dirname($filePath). '/ '. basename($filePath)
				, (class_exists($aClassName)
					?'Class is not a subclass of PntTestCase'
					:'Class does not exist after include')
				);
			print "\n";
			$part =& $this->getTestResultsPart();
			$part->setCouldNotRun(true);
		}
		return $ok;
	}

	function printVisibilityChecked($judgement)
	{
		$visibilities =& $this->getVisibilities();
		if ($visibilities[$judgement])
			print "CHECKED";
	}

	function &getVisibilities()
	{
		if (!isSet($_REQUEST["visibilities"]))
			return $this->getDefaultVisibilites();
		
		//get visibilities from request
		$visibilities = array();
		$severities =& $this->notifier->getSeveritiesByJudgement();
		reset($severities);
		while ( list($judgement) = each($severities) ) {
			$param = "show$judgement";
			$visibilities[$judgement] = isSet($_REQUEST[$param]) ? $_REQUEST[$param] : null;
		}
		return $visibilities;	
	}
	
	function &getDefaultVisibilites()
	{
		if (!$this->defaultVisibilities) {
			$this->defaultVisibilities = array();
			$severities =& $this->notifier->getSeveritiesByJudgement();
			reset($severities);
			while ( list($judgement, $severity) = each($severities) )
				$this->defaultVisibilities[$judgement] = 
					$severity >= $this->notificationsVisibleFrom;
		}
		return $this->defaultVisibilities;
	}

}
?>