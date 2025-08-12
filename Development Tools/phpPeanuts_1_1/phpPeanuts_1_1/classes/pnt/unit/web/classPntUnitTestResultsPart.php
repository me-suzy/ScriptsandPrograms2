<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0


/**
 * This part printf's the results using a settable format
 *
 * @package pnt/unit/web
 * @author  Henk Verhoeven, MetaClass <henk@phpPeanuts.org>
 */

class PntUnitTestResultsPart {
	
	/** page or part this part is a part of */
	var $whole;
	/** PntUnitTestNotifier $notifier Runs tests, routes events, builds results */
   	var $notifier; 
	/** string $format printf format for test event row */
	var $format;
	
	var $visibilities;
	
	var $couldNotRun = false;

	function PntUnitTestResultsPart(&$whole, $format)
	{
		$this->whole =& $whole;
		$this->format = $format;
	}
	
	
	function setVisibilities(&$visibilitiesByJudgement)
	{
		$this->visibilities =& $visibilitiesByJudgement;
	}
	
	function setCouldNotRun($value)
	{
		$this->couldNotRun = $value;
	}
	
	function printBody()
	{
		global $pntTestNotifier;
    	$this->notifier =& $pntTestNotifier;
	}

	function event(&$event)
	{
		$this->printNotificationRow($event);
    }

	function testResult(&$testResult)
	{
		if ($testResult->isPass())
			$this->printNotificationRow($testResult);
	}
	
	function suiteResult()
	{
		$this->printFooter();
	}

	function printNotificationRow(&$notification) 
	{
		if (is_a($notification, 'PntAssertionFailure')) {
	       	$message = $this->getFailureDescription($notification);
	    } else {
           	$message = $notification->getMessage();
        }

       	$test =& $notification->getTest();
		$invisible = !$this->visibilities[$notification->getJudgement()];
		
		//print table row
		if ($invisible) print '<!--';
		printf(
       	  $this->format,
       	  $notification->getJudgement(),
       	  $this->testCaseLink($notification->getTestCase()),
          $this->testLink($test),
          $notification->getCategory(),
          $notification->getDescription(),
          $message
        );
		if ($invisible) print '-->';
		
		print "\n";
		flush();
	}

	function getFailureDescription(&$failure)
	{
		$testCase =& $failure->getTestCase();
		$reference = $this->inspectorLink($testCase, $failure->getReferenceValue(), pntToString($failure->getReferenceValue()));
		$checked = $this->inspectorLink($testCase, $failure->getCheckedValue(), pntToString($failure->getCheckedValue()));
		$format = "%s was checked against %s %s %s";

		if ($failure->getPrecision() === null) {
			$precision = '';
			$precisionAnnouncement = '';
		} else {
			$precision = pntToString($failure->getPrecision());
			$precisionAnnouncement = $tesResult->precisionAnnouncement;
		}
		return sprintf(
			$format,
			$checked,
			$reference,
			$precisionAnnouncement,
			$precision);
	}
	
	function testCaseLink(&$testCase)
	{
		$label = pntToString($testCase);
		$classKey = $this->testCaseClassKey($testCase);
		if (!$classKey || !$this->isOnlineExamples()) return $label;

		return "<A TARGET=_blank HREF='../../site/index.php?pntType=HcodeClass&id=$classKey' title='browse class'>$label</A>";
	}

	function testCaseClassKey($testCase)
	{
		if (!$testCase ) return null;

		$className = $testCase->getClass();
		$dirAndFile = splitFilePath($testCase->getFilePath());
		
		if (subStr($dirAndFile[0], 0, 11) != '../classes/') return null;
		$package = str_replace('/', '.', subStr($dirAndFile[0], 11));
		return "$package.$className";
	}

	function testLink($test)
	{
		if (!$test) return '';
		$label = $test->getMethodName();
		if (!$label) return '';
		if (!$this->isOnlineExamples()) return $label;
		
		$case =& $test->getCase();
		if (!isSet($case->hcodeClass)) {
			$classKey = $this->testCaseClassKey($test->getCase());
			if (!$classKey ) return $label;
			
			includeClass('HcodeClass', 'beheer');
			$case->hcodeClass =& new HcodeClass($classKey);
		}	
		$hcodeMethod = $case->hcodeClass->getMethodNamed($label);
		if (!$hcodeMethod) return $label;
		
		$label = $hcodeMethod->getTitel();
		if ($this->isOnlineExamples()) {
			$methodKey = $hcodeMethod->getKey();
			return "<A TARGET=_blank HREF='../../site/index.php?pntType=HcodeMethod&id=$methodKey' title='browse test method'>$label</A>";
		} else {
			return $label;
		}	 
	}

	function inspectorLink(&$testCase, $value, $label) {
		
		$result = '<A HREF="Inspect?';
		$result .= 'Include='. $testCase->getFilePath();
		$result .= '&Object='.htmlentities(serialize($value));
		$result .= '" TARGET="_blank">';
		$result .= htmlentities($label);
		$result .= '</A>';
		return $result;
	}

	function printFooter()
	{
		include('skinFooter.php');
	}

	function printTestStatisticsRow() {
		
		$judgement = 'None'; 
		if ($this->notifier->getRunCount() > 0) {
			$suiteResult = $this->notifier->getSuiteResult();
			$judgement = $suiteResult->getJudgement();
		}
			
		print "<TR><TD class=pntUnit$judgement ALIGN='CENTER' ><BR>";
		$this->printTestStatisticsLine();
		print '<BR>&nbsp;</TD></TR>';
		
		//eventually print the script for showing whole peanut
		if (!$this->couldNotRun && in_array($judgement, array('None', 'Pass', 'Notice')) )
			print "<script> getElement('peanutImage').src='../images/pntUnit/peanut.gif';</script>";
	}
		
	function printTestStatisticsLine()
	{
		print $this->notifier->getRunCount();
		print ' tests run';
		if ($this->notifier->getRunCount() == 0) return;
		
		$suiteResult =& $this->notifier->getSuiteResult();
		$counters = $suiteResult->countJudgements($suiteResult->getTestResults());
		$passedCount = isSet($counters['Pass']) ? $counters['Pass']: 0;
		$passedS = $passedCount == 1 ? '' : 's';
		
		print ", $passedCount test$passedS passed, ";
		print $this->notifier->getEventCount('Error');
		print ' errors, ';
		print $this->notifier->getEventCount('Warning');
		print ' warnings, ';
		print $this->notifier->getEventCount('Notice');
		print ' notices, ';
		print $this->notifier->getEventCount('Failure');
		print ' assertion';
		if ($this->notifier->getEventCount('Failure') != 1)
			print 's';
		print ' failed';
	}

	/** For the on line examples hyperlinks to the code browsers are generated.
	* Currently the code browsers are not included in phpPeanuts, but in future
	* they will. But then hyperlinks will still be different for on line 
	*/
	function isOnlineExamples()
	{
		global $site;
		if (!$site) return false;
		
		$pat = 'www.phppeanuts.org/examples/';
		return subStr($site->getBaseUrl(), -strLen($pat)) == $pat;
	}


}

?>