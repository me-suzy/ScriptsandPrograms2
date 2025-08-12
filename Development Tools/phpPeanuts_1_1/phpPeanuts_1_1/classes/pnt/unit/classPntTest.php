<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

	
/**
 * @package pnt/unit
 * @author  Henk Verhoeven, MetaClass <henk@phpPeanuts.org>
 */
class PntTest {
	
	var $tstCase;
	var $methodName;
	
	function PntTest(&$testCase, $methodName)
	{
		$this->tstCase =& $testCase;
		$this->methodName = $methodName;
	}
	
	function &getCase()
	{
		return $this->tstCase;
	}
	
	function getMethodName()
	{
		return $this->methodName;
	}
	
	function execute()
	{
		$mth = $this->methodName;
		$this->tstCase->$mth();
	}
}
?>