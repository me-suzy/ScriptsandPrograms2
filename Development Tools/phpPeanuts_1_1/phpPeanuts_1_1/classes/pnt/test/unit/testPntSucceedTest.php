<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

   	
require_once('../classes/pnt/unit/classPntTestCase.php');
require_once('../classes/pnt/test/unit/classObjectToTest.php');

/**
 * @package pnt/test/unit
 * @author  Henk Verhoeven, MetaClass <henk@phpPeanuts.org>
 */
class PntSucceedTest extends PntTestCase {
	
	var $obj1;
	var $obj2;
	
	function setUp() {
		$this->obj1 =& new ObjectToTest();
    	$this->obj2 =& new ObjectToTest();
    	$this->obj2->var1 = 'value of obj2->var1 explicitly set by testSucceed';
	}

    function testSucceed() {

    	$this->assertEquals('123.0', 123, 'mixed');
    	$this->assertEquals(2, 2, 'with numbers');
    	$this->assertEquals('yes', 'yes', 'with Strings');
    	$this->assertEquals($this->obj1, $this->obj1, 'with objects');

		$this->assertNull(null, 'null');
		$this->assertNotNull(123, '123');

		$this->assertNotSame('12', 12, '12');
		$this->assertSame($this->obj1, $this->obj1, '$this->obj1');

     	$this->assertFalse(false, 'false');
		$this->assertTrue(true, 'true');

		$this->assertRegExp('~.php~', 'myFile.php', 'RegExp');
		
		Assert::ofType('integer', 123);
		Assert::ofType('string', '123');
		Assert::ofType('number', 123);
		Assert::ofType('number', '123');
		Assert::ofType('NULL', null);
		Assert::ofType('ObjectToTest', $this->obj1);
		
		Assert::ofAnyType(array('integer', 'boolean'), false);
		
    }
    
}

return 'PntSucceedTest';
?>
