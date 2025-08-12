<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

   	
require_once('../classes/pnt/unit/classPntTestCase.php');

class SqlSortTest extends PntTestCase {
	
	var $dbObjectTest;
	var $clsDes;
	var $obj1;

	function setUp() {
		includeClass('TestDbSub', 'pnt/test/db');
		include_once('../classes/pnt/test/db/testCaseDbPolymorphic.php');
		includeClass('PntSqlJoinFilter', 'pnt/db/query');
		includeClass('PntSqlSort', 'pnt/db/query');

		$this->dbObjectTest = new CaseDbObject();
		$this->clsDes =& PntClassDescriptor::getInstance('TestDbObject');
		$this->obj1 =& $this->clsDes->_getPeanutWithId('1');
		
		$this->filter1 =& PntSqlFilter::getInstance('TestDbObject', 'testDbObject.doubleField');
		$this->filter2 =& PntSqlFilter::getInstance('TestDbObject', 'dateField');
		$this->filter3 =& PntSqlFilter::getInstance('TestDbObject', 'anotherDbObject.stringField');
		
		$this->sort1 =& new PntSqlSort('testsort1');
		$this->sort1->setFilter($this->filter1);
		$this->sort1->addSortSpecFilter($this->filter2);
		$this->sort1->addSortSpecFilter($this->filter3);
	}

	function testCreateTableAndObjects()
	{
		$this->dbObjectTest->test_CreateTables();
		$this->dbObjectTest->setUp();
		$this->dbObjectTest->test_insert_retrieve();
	}

	function test_getSqlForJoin()
	{
		$this->assertEquals(
			' LEFT JOIN testdbobjects AS testDbObjectALIAStestdbobjects ON testdbobjects.testDbObjectId = testDbObjectALIAStestdbobjects.id LEFT JOIN testdbobjects AS anotherDbObjectALIAStestdbobjects ON testdbobjects.anotherDbObjectId = anotherDbObjectALIAStestdbobjects.id'
			, $this->sort1->getSqlForJoin()
			, "getSqlForJoin");
	}

	function test_getOrderBySql()
	{
		$this->sort1->getSqlForJoin();
		$this->assertEquals(
			'testdbobjects.dateField ASC, anotherDbObjectALIAStestdbobjects.stringField ASC'
			, $this->sort1->getOrderBySql()
			, "sql ascending");
		$this->filter2->comparatorId = '>';
		$this->assertEquals(
			'testdbobjects.dateField DESC, anotherDbObjectALIAStestdbobjects.stringField ASC'
			, $this->sort1->getOrderBySql()
			, "sql filter2 descending");
		$this->filter3->comparatorId = '>';
		$this->assertEquals(
			'testdbobjects.dateField DESC, anotherDbObjectALIAStestdbobjects.stringField DESC'
			, $this->sort1->getOrderBySql()
			, "sql filter3 descending");
	}

	function test_addSortSpec()
	{
		$this->sort1 =& new PntSqlSort('test_addSortSpec', 'TestDbObject');
		$this->sort1->addSortSpec('dateField');
		$this->sort1->addSortSpec('anotherDbObject.stringField', 'DESC');
		$sortSpecFilters =& $this->sort1->sortSpecFilters;

		$ssf =& $sortSpecFilters['dateField'];
		$this->assertEquals(
				'dateField'
				, $ssf->get('label')
				, "addSortSpec 0 label");
		$this->assertEquals(
				'TestDbObject'
				, $ssf->get('itemType')
				, "addSortSpec 0 itemType");
		
		$ssf =& $sortSpecFilters['anotherDbObject.stringField'];
		$this->assertEquals(
				'anotherDbObject.stringField'
				, $ssf->get('label')
				, "addSortSpec 1 label");
		$this->assertEquals(
				'TestDbObject'
				, $ssf->get('itemType')
				, "addSortSpec 1 itemType");
	}
	
	function testLabelSort()
	{
		$sort =& $this->obj1->getLabelSort('TestDbObject');
		$sortSpecFilters =& $sort->sortSpecFilters;
		$ssf =& $sortSpecFilters['stringField'];
		$this->assertEquals(
				'stringField'
				, $ssf->get('label')
				, "spec label");
		$this->assertEquals(
				'TestDbObject'
				, $ssf->get('itemType')
				, "spec itemType");
	}

	function testCreateTableAndDbSub()
	{
		$this->dbObjectTest->test_dropTables();
		unSet($this->clsDes->peanutsById[1]);
		
		$dbObjectTest =& new CaseDbPolymorphic();
		$dbObjectTest->test_CreateTables();
		$dbObjectTest->setUp();
		$dbObjectTest->test_insert_retrieve();
	}

	function test_dbsub()
	{
		$this->sort1 =& new PntSqlSort('test_dbsub', 'TestDbSub');
		$this->sort1->addSortSpec('dateField');
		$this->sort1->addSortSpec('anotherDbObject.stringField', 'DESC');
		$this->sort1->addSortSpec('subOnlyStringField');
		$this->sort1->addSortSpec('testDbSub.doubleField', 'ASC');
		$sortSpecFilters =& $this->sort1->sortSpecFilters;

		$ssf =& $sortSpecFilters['dateField'];
		$this->assertEquals(
				'dateField'
				, $ssf->get('label')
				, "addSortSpec 0 label");
		$this->assertEquals(
				'TestDbSub'
				, $ssf->get('itemType')
				, "addSortSpec 0 itemType");
		
		$ssf =& $sortSpecFilters['anotherDbObject.stringField'];
		$this->assertEquals(
				'anotherDbObject.stringField'
				, $ssf->get('label')
				, "addSortSpec 1 label");
		$this->assertEquals(
				'TestDbSub'
				, $ssf->get('itemType')
				, "addSortSpec 1 itemType");

		$ssf =& $sortSpecFilters['subOnlyStringField'];
		$this->assertEquals(
				'subOnlyStringField'
				, $ssf->get('label')
				, "addSortSpec 2 label");
		$this->assertEquals(
				'TestDbSub'
				, $ssf->get('itemType')
				, "addSortSpec 2 itemType");

		$ssf =& $sortSpecFilters['testDbSub.doubleField'];
		$this->assertEquals(
				'testDbSub.doubleField'
				, $ssf->get('label')
				, "addSortSpec 3 label");
		$this->assertEquals(
				'TestDbSub'
				, $ssf->get('itemType')
				, "addSortSpec 3 itemType");
	}

	function test_dropTables()
	{
		$dbObjectTest =& new CaseDbPolymorphic();
		$dbObjectTest->test_dropTables();
	}

}

return 'SqlSortTest';
?>
