<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

   	
require_once('../classes/pnt/unit/classPntTestCase.php');

class SqlJoinFilterTest extends PntTestCase {
	
	var $dbObjectTest;
	var $clsDes;
	var $obj1;

	function setUp() {
		includeClass('TestDbSub', 'pnt/test/db');
		include_once('../classes/pnt/test/db/testCaseDbPolymorphic.php');
		includeClass('PntSqlJoinFilter', 'pnt/db/query');

		$this->dbObjectTest = new CaseDbObject();
		$this->clsDes =& PntClassDescriptor::getInstance('TestDbObject');
		$this->obj1 =& $this->clsDes->_getPeanutWithId('1');
		$this->filter1 =& new PntSqlJoinFilter();
		$this->filter2 =& new PntSqlFilter();
		$this->filter1->setNext($this->filter2);
	}

	function testCreateTableAndObjects()
	{
		$this->dbObjectTest->test_CreateTables();
		$this->dbObjectTest->setUp();
		$this->dbObjectTest->test_insert_retrieve();
	}
	
	function testWithKey()
	{
		$this->filter1->set('key', 'testDbObject');
		$this->filter1->set('itemType', 'TestDbObject');
		$this->filter2->set('key', 'doubleField');
		$this->filter2->set('itemType', 'TestDbObject');
		$this->filter2->set('valueType', 'number');

		$this->filter1->set('comparatorId', '=');
		$this->filter1->set('value1', 76543.21);
		
		$this->assertEquals(
			'TestDbObject'
			, $this->filter1->get('itemType')
			, "itemType");
		$this->assertEquals(
			'testDbObject.doubleField'
			, $this->filter1->get('label')
			, "label");
		$this->assertEquals(
			'number'
			, $this->filter1->get('valueType')
			, "valueType");
			
		$this->assertEquals(
			' LEFT JOIN testdbobjects AS testDbObjectALIAStestdbobjects ON testdbobjects.testDbObjectId = testDbObjectALIAStestdbobjects.id'
			, $this->filter1->getSqlForJoin()
			, 'sqlForJoin'
			);
		$this->assertEquals(
			"testDbObjectALIAStestdbobjects.doubleField = '76543.21'"
			, $this->filter1->get('sql')
			, "sql");
	}

	function test_getInstance()
	{
		$this->filter1 =& PntSqlFilter::getInstance('TestDbObject', 'testDbObject.doubleField');
		$this->assertNotNull($this->filter1, "getInstance('TestDbObject', 'testDbObject.doubleField')" );

		$this->filter2 =& $this->filter1->getNext();
		$this->assertNotNull($this->filter2, "filter1->getNext()" );

		
		$this->assertEquals(
			'testDbObject'
			, $this->filter1->get('key')
			, "key");
		$this->assertEquals(
			'TestDbObject'
			, $this->filter1->get('itemType')
			, "itemType");
		$this->assertEquals(
			'testDbObject.doubleField'
			, $this->filter1->get('label')
			, "label");
		$this->assertEquals(
			'number'
			, $this->filter1->get('valueType')
			, "valueType");

		$this->assertEquals(
			'doubleField'
			, $this->filter2->get('key')
			, "key");
		$this->assertEquals(
			'TestDbObject'
			, $this->filter2->get('itemType')
			, "itemType");

	}

	function testCreateTableAndDbSub()
	{
		$this->dbObjectTest->test_dropTables();
		unSet($this->clsDes->peanutsById[1]);
		
		$dbObjectTest =& new CaseDbPolymorphic();
		$dbObjectTest->test_CreateTables();
		$dbObjectTest->setUp();
		$dbObjectTest->test_insert_retrieve();
		$dbObjectTest->test_insertChild();
	}

	function test_SelectEvaluateSuperclassRelationSingleValue()
	{
		$this->filter1 =& PntSqlFilter::getInstance('TestDbSub', 'testDbObject.doubleField');
		$this->assertNotNull($this->filter1, "getInstance('TestDbSub', 'testDbObject.doubleField')" );

		$this->filter2 =& $this->filter1->getNext();
		$this->assertNotNull($this->filter2, "filter1->getNext()" );
		$this->filter2->set('comparatorId', '=');
		$value = 1;
		$this->filter2->set('value1', $value);
		$sub =& $this->clsDes->_getPeanutWithId(2);
		$super =& $sub->get('testDbObject');

		Assert::false($this->filter2->evaluate($super), "super->doubleField=$value"); 
		Assert::false($this->filter1->evaluate($sub), "sub->testDbObject.doubleField=$value"); 

		$value = 12345.67;
		$this->filter2->set('value1', $value);
		Assert::true($this->filter1->evaluate($sub), "sub->testDbObject.doubleField=$value"); 
	
	}

	function test_superclassRelationSingleValue()
	{
		$this->filter1 =& PntSqlFilter::getInstance('TestDbSub', 'testDbObject.doubleField');
		$this->assertNotNull($this->filter1, "getInstance('TestDbSub', 'testDbObject.doubleField')" );

		$this->filter2 =& $this->filter1->getNext();
		$this->assertNotNull($this->filter2, "filter1->getNext()" );

		
		$this->assertEquals(
			'testDbObject'
			, $this->filter1->get('key')
			, "key");
		$this->assertEquals(
			'TestDbSub'
			, $this->filter1->get('itemType')
			, "itemType");
		$this->assertEquals(
			'testDbObject.doubleField'
			, $this->filter1->get('label')
			, "label");
		$this->assertEquals(
			'number'
			, $this->filter1->get('valueType')
			, "valueType");

		$this->assertEquals(
			'doubleField'
			, $this->filter2->get('key')
			, "key");
		$this->assertEquals(
			'TestDbObject'
			, $this->filter2->get('itemType')
			, "itemType");

		$joinsData = array();
		$this->filter1->addJoinTableAndConditionByTableAlias(&$joinsData);
		$condition = 'testdbobjects.testDbObjectId = testDbObjectALIAStestdbobjects.id'; 
		$this->assertEquals(
			$condition
			, $this->filter1->getJoinCondition('testDbObjectALIAStestdbobjects')
			, 'joinCondition');
		$tableAndCondition = $joinsData['testDbObjectALIAStestdbobjects'];
		$this->assertNotNull( $tableAndCondition, 'tableAndCondition');
		$this->assertEquals('testdbobjects',  $tableAndCondition[0], 'joinData tableName');
		$this->assertEquals($condition,  $tableAndCondition[1], 'joinData condition');
	}

	function test_SelectEvaluateSuperclassRelationMultiValue()
	{
		$this->filter1 =& PntSqlFilter::getInstance('TestDbSub', 'children.doubleField');
		$this->assertNotNull($this->filter1, "getInstance('TestDbSub', 'children.doubleField')" );

		$this->filter2 =& $this->filter1->getNext();
		$this->assertNotNull($this->filter2, "filter1->getNext()" );
		$this->filter2->set('comparatorId', '=');
		$value = 1;
		$this->filter2->set('value1', $value);
		$super =& $this->clsDes->_getPeanutWithId(1);
		$subs =& $super->get('children');

		Assert::false($this->filter2->evaluate($subs[0]), "$subs[0]->doubleField=$value"); 
		Assert::false($this->filter1->evaluate($super), "super->children.doubleField=$value"); 

		$value = 9999.99;
		$this->filter2->set('value1', $value);
		Assert::true($this->filter2->evaluate($subs[0]), "$subs[0]->doubleField=$value"); 
		Assert::true($this->filter1->evaluate($super), "super->children.doubleField=$value"); 
	
	}

	function test_superclassRelationMultiValue()
	{
		$this->filter1 =& PntSqlFilter::getInstance('TestDbSub', 'children.doubleField');
		$this->assertNotNull($this->filter1, "getInstance('TestDbSub', 'children.doubleField')" );

		$this->filter2 =& $this->filter1->getNext();
		$this->assertNotNull($this->filter2, "filter1->getNext()" );

		$this->assertEquals(
			'children'
			, $this->filter1->get('key')
			, "key");
		$this->assertEquals(
			'TestDbSub'
			, $this->filter1->get('itemType')
			, "itemType");
		$this->assertEquals(
			'children.doubleField'
			, $this->filter1->get('label')
			, "label");
		$this->assertEquals(
			'number'
			, $this->filter1->get('valueType')
			, "valueType");

		$this->assertEquals(
			'doubleField'
			, $this->filter2->get('key')
			, "key");
		$this->assertEquals(
			'TestDbObject'
			, $this->filter2->get('itemType')
			, "itemType");

		$joinsData = array();
		$this->filter1->addJoinTableAndConditionByTableAlias(&$joinsData); 
		$condition = 'childrenALIAStestdbobjects.testDbObjectId = testdbsubs.id'; 
		$this->assertEquals(
			$condition
			, $this->filter1->getJoinCondition('childrenALIAStestdbobjects')
			, 'joinCondition');
		$tableAndCondition = $joinsData['childrenALIAStestdbobjects'];
		$this->assertNotNull( $tableAndCondition, 'tableAndCondition');
		$this->assertEquals('testdbobjects',  $tableAndCondition[0], 'joinData tableName');
		$this->assertEquals($condition,  $tableAndCondition[1], 'joinData condition');
	}

	function test_subclassRelation_superclassfield()
	{
		$this->filter1 =& PntSqlFilter::getInstance('TestDbSub', 'testDbSub.doubleField');
		$this->assertNotNull($this->filter1, "getInstance('TestDbSub', 'testDbSub.doubleField')" );

		$this->filter2 =& $this->filter1->getNext();
		$this->assertNotNull($this->filter2, "filter1->getNext()" );
		
		$this->assertEquals(
			'testDbSub'
			, $this->filter1->get('key')
			, "key");
		$this->assertEquals(
			'TestDbSub'
			, $this->filter1->get('itemType')
			, "itemType");
		$this->assertEquals(
			'testDbSub.doubleField'
			, $this->filter1->get('label')
			, "label");
		$this->assertEquals(
			'number'
			, $this->filter1->get('valueType')
			, "valueType");

		$this->assertEquals(
			'doubleField'
			, $this->filter2->get('key')
			, "key");
		$this->assertEquals(
			'TestDbSub'
			, $this->filter2->get('itemType')
			, "itemType");

		$joinsData = array();
		$this->filter1->addJoinTableAndConditionByTableAlias(&$joinsData);
		$condition = 'testdbsubs.testDbSubId = testDbSubALIAStestdbobjects.id'; 
		$this->assertEquals(
			$condition
			, $this->filter1->getJoinCondition('testDbSubALIAStestdbobjects')
			, 'joinCondition');
		$tableAndCondition = $joinsData['testDbSubALIAStestdbobjects'];
		$this->assertNotNull( $tableAndCondition, 'tableAndCondition');
		$this->assertEquals('testdbobjects',  $tableAndCondition[0], 'joinData tableName');  //veld zit in tabel van superklasse
		$this->assertEquals($condition,  $tableAndCondition[1], 'joinData condition');
	}

	function test_subclassRelation_subclassfield()
	{
		$this->filter1 =& PntSqlFilter::getInstance('TestDbSub', 'testDbSub.subOnlyStringField');
		$this->assertNotNull($this->filter1, "getInstance('TestDbSub', 'testDbSub.subOnlyStringField')" );

		$this->filter2 =& $this->filter1->getNext();
		$this->assertNotNull($this->filter2, "filter1->getNext()" );
		
		$this->assertEquals(
			'testDbSub'
			, $this->filter1->get('key')
			, "key");
		$this->assertEquals(
			'TestDbSub'
			, $this->filter1->get('itemType')
			, "itemType");
		$this->assertEquals(
			'testDbSub.subOnlyStringField'
			, $this->filter1->get('label')
			, "label");
		$this->assertEquals(
			'string'
			, $this->filter1->get('valueType')
			, "valueType");

		$this->assertEquals(
			'subOnlyStringField'
			, $this->filter2->get('key')
			, "key");
		$this->assertEquals(
			'TestDbSub'
			, $this->filter2->get('itemType')
			, "itemType");

		$joinsData = array();
		$this->filter1->addJoinTableAndConditionByTableAlias(&$joinsData);
		$condition = 'testdbsubs.testDbSubId = testDbSubALIAStestdbsubs.id'; 
		$this->assertEquals(
			$condition
			, $this->filter1->getJoinCondition('testDbSubALIAStestdbsubs')
			, 'joinCondition');
		$tableAndCondition = $joinsData['testDbSubALIAStestdbsubs'];
		$this->assertNotNull( $tableAndCondition, 'tableAndCondition');
		$this->assertEquals('testdbsubs',  $tableAndCondition[0], 'joinData tableName');
		$this->assertEquals($condition,  $tableAndCondition[1], 'joinData condition');
	}

	function test_getPersistArray_FilterWithPresetTemplate()
	{ 
		$this->filter1 =& PntSqlFilter::getInstance('TestDbObject', 'testDbObject.doubleField');
		$this->filter1->set('value1', 76543.21);
		$this->filter1->set('comparatorId', '=');
		$this->filter2 =& $this->filter1->getNext();
		$array =& $this->filter1->getPersistArray();

		$this->assertEquals('PntSqlJoinFilter', $array['clsId'], 'clsId');
		$this->assertEquals('TestDbObject', $array['itemType'], 'itemType');
		$this->assertEquals('testDbObject', $array['key'], 'key');
		$this->assertEquals('testDbObject', $array['label'], 'label');
		$this->assertEquals('=', $array['comparatorId'], 'comparatorId');
//		$this->assertEquals('string', $array['valueType'], 'valueType'); field not used
		$this->assertEquals(76543.21, $array['value1'], 'value1');
		$this->assertEquals(8, count($array), 'array count');
		
		$nextArray =& $array['next'];
		$this->assertEquals('PntSqlFilter', $nextArray['clsId'], 'next clsId');
		$this->assertEquals('TestDbObject', $nextArray['itemType'], 'next itemType');
		$this->assertEquals('doubleField', $nextArray['key'], 'next key');
		$this->assertEquals('doubleField', $nextArray['label'], 'label');
		$this->assertEquals('=', $nextArray['comparatorId'], 'next comparatorId');
		$this->assertEquals('number', $nextArray['valueType'], 'next valueType'); 
		$this->assertEquals(76543.21, $nextArray['value1'], 'next value1');
		$this->assertEquals(7, count($nextArray), 'next array count');
	}

	function test_instanceFromPersistArray()
	{
		$this->filter1 =& PntSqlFilter::getInstance('TestDbObject', 'testDbObject.doubleField');
		$this->filter1->set('comparatorId', '=');
		$this->filter1->set('value1', 76543.21);
		$this->filter2 =& $this->filter1->getNext();
		$array =& $this->filter1->getPersistArray();
		$filterFromArray =& PntSqlFilter::instanceFromPersistArray($array);
		Assert::propertiesEqual($this->filter1, $filterFromArray);
	}

	function test_dropTables()
	{
		$dbObjectTest =& new CaseDbPolymorphic();
		$dbObjectTest->test_dropTables();
	}

}

return 'SqlJoinFilterTest';
?>
