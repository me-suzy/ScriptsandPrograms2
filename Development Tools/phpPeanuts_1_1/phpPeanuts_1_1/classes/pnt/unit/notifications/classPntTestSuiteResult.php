<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0


includeClass('PntUnitResultNotification', 'pnt/unit/notifications');

/**
 * @package pnt/unit/notifications
 * @author  Henk Verhoeven, MetaClass <henk@phpPeanuts.org>
 */
class PntTestSuiteResult extends PntUnitResultNotification {
	
	function PntTestSuiteResult(&$suite)
	{
		$this->PntUnitResultNotification($suite);
	}
	
	function &getTestSuite()
	{
		return $this->getSubject();
	}
	
	function getTestResults()
	{
		$result = array();
		$testCases =& $this->getChildren();
		reset($testCases);
		while ( list($key) = each($testCases) ) 
			array_addAll($result, $testCases[$key]->getChildren());
		return $result;
	}
	
	

}
?>