<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

   	
require_once('../classes/pnt/unit/classPntTestCase.php');

class PntErrorTest extends PntTestCase {
	
	var $obj1;
	var $obj1Message = 'message of original error';
	var $obj2;
	var $obj2Message = 'message of resulting error';
	
	
	function setUp() {
		includeClass('PntError', 'pnt');
		$this->obj1 =& new PntError($this, $this->obj1Message);
		$this->obj2 =& new PntError($this, $this->obj2Message, $this->obj1Message);
		$this->obj3 =& new PntError($this, $this->obj2Message, $this->obj1);
	}

	function toString()
	{
		return 'TestPntError';
	}

	function test_getMessage()
	{
		$this->assertTrue($this->obj1Message, 'obj1Message');
		$this->assertEquals(
			$this->obj1Message
			, $this->obj1->getMessage()
			, 'obj1->getMessage()'
		);
		$errorClass = 'PntError';
		$errorNoMessage = new $errorClass();
		$this->assertEquals(
			$errorClass
			, $errorNoMessage->getMessage()
			, 'errorNoMessage->getMessage()'
		);
	}
	
	function test_getCause()
	{
		$this->assertSame(
			$this->obj1
			, $this->obj3->getCause()
			, 'obj3->getCause()'
		);
	}

	function test_getCauseDescription()
	{
		$this->assertEquals(
			$this->obj1Message
			, $this->obj2->getCauseDescription()
			, 'obj2->getCauseDescription()'
		);
		$this->assertEquals(
			$this->toString(). ' '. $this->obj1Message
			, $this->obj3->getCauseDescription()
			, 'obj3->getCauseDescription()'
		);
	}

	function test_getLabel()
	{
		$this->assertEquals(
			$this->toString(). ' '. $this->obj2Message
				.' because: '
				. $this->toString(). ' '. $this->obj1Message
			, $this->obj3->getLabel()
			, 'obj3->getLabel()'
		);
	}

/*
    	$this->assertEquals('yes', 123, 'assertEquals');
		$this->assertNotNull(null, 'assertNotNull');
		$this->assertNull(123, 'assertNull');
		$this->assertSame('12', 12, 'assertSame');
		$this->assertNotSame($this->obj1, $this->obj1, 'assertNotSame');
     	$this->assertTrue(false, 'assertTrue');
		$this->assertFalse(true, 'assertFalse');
		$this->assertRegExp('~.php~', 'myFile.txt', 'assertRegExp');
 */   	
}

return 'PntErrorTest';
?>
