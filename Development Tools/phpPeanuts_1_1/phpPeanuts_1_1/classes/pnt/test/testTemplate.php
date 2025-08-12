<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

includeClass('PntTestCase', 'pnt/unit');
includeClass('PntObject', 'pnt');

class Template extends PntTestCase {
	
	var $obj1;
	
	function setUp() {
		$this->obj1 =& new PntObject();
		
	}

	function testSomething()
	{
		$this->assertEquals(0, $this->obj1->get('id'), 'comment to recognize assertion from');
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

return 'Template';
?>
