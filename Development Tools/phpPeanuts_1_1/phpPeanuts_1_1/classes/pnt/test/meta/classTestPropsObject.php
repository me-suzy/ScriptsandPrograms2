<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

includeClass('PntObject', 'pnt');

class TestPropsObject extends PntObject {

	var $field1;
	
	var $singleValuePropNames = 'label derived1 derived2 derived3 field1 field2 derived3Id id';
	var $multiValuePropNames = 'multi1 multi2';
	var $persistentFieldPropNames = 'field1 derived3Id id';
	var $uiColumnPaths = 'derived1 derived2 derived3 field1 field2';

	function TestPropsObject($id=null)
	{
		$this->PntObject();
		$this->id = $id;
	}

	/** Returns the directory of the class file
	* @static
	* @return String 
	*/
	function getClassDir() 
	{
		return 'pnt/test/meta';
	}

	function initPropertyDescriptors() {
		// only to be called once

		parent::initPropertyDescriptors();
		
		$this->addDerivedProp('derived1', 'email');
		$this->addDerivedProp('derived2', 'PntObject', false, 'lowest', 'highest', 1, 2, 'pnt');
		$this->addDerivedProp('derived3', 'TestPropsObject', false, 'lowest', 'highest', 1, 2);
		
		$this->addFieldProp('field1', 'number');
		$this->addFieldProp('field2', 'TestPropsObject', false, 'lowest', 'highest', 1, 2, null, false);
		$this->addFieldProp('derived3Id', 'number');
		$this->addFieldProp('id', 'number');
		
		$this->addMultiValueProp('multi1', 'string');
		$this->addMultiValueProp('multi2', 'PntObject', false);
		
	}

	function getField1Options()
	{
		return array(1, 2, 4, 8, 16);
	}
	
	function getDerived3Options()
	{
		// keys must be equal to ids
		return array(
			1 => new TestPropsObject(1)
			, 2 => new TestPropsObject(2)
			, 3 => new TestPropsObject(3)
			);
	}
	
}
?>