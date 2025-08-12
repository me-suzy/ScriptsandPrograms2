<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

includeClass('TestDbObject', 'pnt/test/db');
/** Class for testing inheritance and polymorhic retrieval 
*/
class TestDbSub extends TestDbObject {

	var $testDbSubId = 0;
	var $subOnlyStringField;

	var $persistentFieldPropNames = 'id clsId stringField dateField timestampField doubleField memoField testDbObjectId anotherDbObjectId subOnlyStringField testDbSubId';
	var $singleValuePropNames = 'label id oid clsId stringField dateField timestampField doubleField memoField testDbObjectId testDbObject anotherDbObjectId anotherDbObject subOnlyStringField testDbSubId testDbSub';
	var $multiValuePropNames = 'children';

	function TestDbSub($id=null)
	{
		$this->TestDbObject($id);
	}

	/** @static for testing polymorphic retrieval
	* @return String the name of the database table the instances are stored in
	*/
	function getTableName() 
	{
		return 'testdbsubs';
	}
	
	function initPropertyDescriptors() {
		parent::initPropertyDescriptors();
		
		$this->addFieldProp('subOnlyStringField', 'string');
		$this->addFieldProp('testDbSubId', 'number');
		$this->addDerivedProp('testDbSub', 'TestDbSub', false); 

		$prop =& $this->getPropertyDescriptor('children');
		$prop->setTwinName('testDbObject'); //necessary because of polymorphism
	}

	
}
?>