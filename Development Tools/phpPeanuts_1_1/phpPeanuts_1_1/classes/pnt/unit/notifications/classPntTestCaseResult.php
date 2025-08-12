<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0


includeClass('PntUnitResultNotification', 'pnt/unit/notifications');

/**
 * @package pnt/unit/notifications
 * @author  Henk Verhoeven, MetaClass <henk@phpPeanuts.org>
 */
class PntTestCaseResult extends PntUnitResultNotification {
	
	
	function PntTestCaseResult(&$testCase)
	{
		$this->PntUnitResultNotification($testCase);
	}
	
	function &getTestCase()
	{
		return $this->getSubject();
	}

}
?>