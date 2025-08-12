<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

   	
require_once('../classes/pnt/unit/classPntTestCase.php');

class PntObjectTest extends PntTestCase {
	
	var $obj1;
	var $clsDes;
	
	function setUp() {
		//includeClass('PntDerivedPropertyDescriptor', 'pnt/meta');
		includeClass('PntObject', 'pnt');
		$this->obj1 = new PntObject;
		$this->clsDes =& $this->obj1->getClassDescriptor();
	}

	function test_getClassDescriptor() {
		$this->assertNotNull($this->clsDes);
		
		$this->clsDes->undefinedField = true;
		$this->assertSame(
			$this->clsDes
			, $this->obj1->getClassDescriptor()
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

return 'PntObjectTest';
?>
