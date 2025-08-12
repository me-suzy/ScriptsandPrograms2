<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0


includeClass('PntUnitResultNotification', 'pnt/unit/notifications');

/**
 * @package pnt/unit/notifications
 * @author  Henk Verhoeven, MetaClass <henk@phpPeanuts.org>
 */
class PntTestResult extends PntUnitResultNotification {
	
	
	function PntTestResult(&$test)
	{
		$this->PntUnitResultNotification($test);
	}
	
	function &getTest()
	{
		return $this->getSubject();
	}
	
}
?>