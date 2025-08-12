<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

   	
require_once('../classes/pnt/unit/classPntTestCase.php');

class ClassDesriptorTest extends PntTestCase {
	
	var $desc1Name;
	var $desc1;
	
	function setUp() {
		includeClass('TestPropsObject', 'pnt/test/meta');
	
		$this->desc1Name = 'TestPropsObject';
		$this->desc1 =& PntClassDescriptor::getInstance($this->desc1Name);
		
	}

	function test_getInstance() {
		
		$arr =& PntClassDescriptor::getInstances();
		$this->assertFalse(
			empty($arr)
			,'instances');
		
		$desc2 =& PntClassDescriptor::getInstance($this->desc1Name);
		$this->desc1->temp1 = 'aValue';
		$this->assertNotNull($this->desc1->temp1, 'same var'); 
		$this->assertSame($this->desc1, $desc2, 'same instance');
		
		$desc3 =& PntClassDescriptor::getInstance('PntObject');
		$this->assertNotSame($this->desc1, $desc3, 'PntObject');
		
	}

	function test_name() {
		$this->assertEquals($this->desc1Name, $this->desc1->name, 'field');
		$this->assertEquals($this->desc1Name, $this->desc1->getName(), 'getter');
	}

	function test_label() {
		$this->assertNull($this->desc1->label, 'field');
		$this->assertEquals($this->desc1Name, $this->desc1->getLabel(), 'getter');
		
		$this->desc1->setLabel('label of One');
		$this->assertEquals('label of One', $this->desc1->getLabel(), 'setter');
		
		$this->desc1->setLabel(null);
		$this->assertEquals($this->desc1Name, $this->desc1->getLabel(), 'set to null');
	}

	function test_getClassDir() {
		$this->assertEquals('pnt/test/meta', $this->desc1->getClassDir(), 'getter');
	}

	function test_getPeanutWithId() 
	{
		$error =& $this->desc1->_getPeanutWithId(0);
		$this->assertNull(
			$error
			, 'with id 0'
		);
		
		$error =& $this->desc1->_getPeanutWithId(1);
		$this->assertEquals(
			'PntClassDescriptor(TestPropsObject) _getPeanutsWith should have been overridden'
			, $error->getLabel()
			, 'with id 1'
		);
	}

	/** Returns the instances of the described class
	* @return Array 
	*/
	function test_getPeanuts()
	{
		$error =& $this->desc1->_getPeanuts();
		$this->assertEquals(
			'PntClassDescriptor(TestPropsObject) _getPeanuts should have been overridden'
			, $error->getLabel()
		);
	}

	/** Returns the instances of the described class with 
	* the specfied property value to be equal to the specfied value
	* @param String propertyName 
	* @param variant value
	* @return Array 
	*/
	function test_getPeanutsWith()
	{
		$error =& $this->desc1->_getPeanutsWith('label', 'xxx');
		$this->assertEquals(
			'PntClassDescriptor(TestPropsObject) _getPeanutsWith should have been overridden'
			, $error->getLabel()
		);
	}


/*
    	$this->assertEquals('yes', 123, 'assertEquals');
		$this->assertNotNull(null, 'assertNotNull');
		$this->assertNull(123, 'assertNull');
		$this->assertSame('12', 12, 'assertSame');
		$this->assertNotSame($this->obj1, $this->obj1, 'assertNotSame');
     	$this->assertTrue(false, 'assertTrue');
		$this->assertFalse(true, 'assertTrue');
		$this->assertRegExp('~.php~', 'myFile.txt', 'assertRegExp');
*/   
}

return 'ClassDesriptorTest';
?>
