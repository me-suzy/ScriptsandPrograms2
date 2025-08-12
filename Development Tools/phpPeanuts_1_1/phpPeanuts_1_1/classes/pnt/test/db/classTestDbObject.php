<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

includeClass('PntDbObject', 'pnt/db');

class TestDbObject extends PntDbObject {

	var $obj1;
	var $clsDes;
	var $testDbObjectId = 0; // otherwise asserEquals will complain, though usually the behavior will be the same if no initial value is set
	var $anotherDbObjectId = 0;

	var $persistentFieldPropNames = 'id clsId stringField dateField timestampField doubleField memoField testDbObjectId anotherDbObjectId';
	var $singleValuePropNames = 'label id oid clsId stringField dateField timestampField doubleField memoField testDbObjectId testDbObject anotherDbObjectId anotherDbObject';
	var $multiValuePropNames = 'children';

	function TestDbObject($id=null)
	{
		$this->PntDbObject($id);
		$this->clsId = $this->getClass();
	}

	/** @static 
	* @return String the name of the database table the instances are stored in
	*/
	function getTableName() 
	{
		return 'testdbobjects';
	}

	/** Returns the classFolder
	* @static
	* @return String
	*/
	function getClassDir()
	{
		return 'pnt/test/db';
	}

	/** @static 
	* @param string $itemType itemType for the sort (may be the sort will be for a subclass)
	* @return PntSqlSort that specifies the sql for sorting the instance records by label
	*/
	function &getLabelSort($itemType)
	{
		includeClass('PntSqlSort', 'pnt/db/query');
		$sort =& new PntSqlSort('label', $itemType);
		$sort->addSortSpec('stringField');
		return $sort;
	}
	
	function initPropertyDescriptors() {
		//activate polymorphism support. Must be done befora any property descriptor is added 
 		$clsDes =& $this->getClassDescriptor();
		$clsDes->setPolymorphismPropName('clsId');

		parent::initPropertyDescriptors(); 		//adds 'inherited' propertydescriptors
		
		$this->addFieldProp('clsId', 'string');
		$this->addFieldProp('stringField', 'string');
		$this->addFieldProp('dateField', 'date');
		$this->addFieldProp('timestampField', 'timestamp');
		$this->addFieldProp('doubleField', 'number');
		$this->addFieldProp('memoField', 'string');
		
		$this->addFieldProp('testDbObjectId', 'string');
		$this->addDerivedProp('testDbObject', 'TestDbObject', false); //not readOnly
		$this->addFieldProp('anotherDbObjectId', 'number');
		$this->addDerivedProp('anotherDbObject', 'TestDbObject', false); 
		
		$this->addMultiValueProp('children', 'TestDbObject');
	}

	function getLabel()
	{
		return $this->stringField;
	}
	
}
?>