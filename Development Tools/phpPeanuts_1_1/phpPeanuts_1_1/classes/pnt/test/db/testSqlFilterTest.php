<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

   	
require_once('../classes/pnt/unit/classPntTestCase.php');

class SqlFilterTest extends PntTestCase {
	
	var $dbObjectTest;
	var $clsDes;
	var $obj1;

	function setUp() {
		includeClass('TestDbSub', 'pnt/test/db');
		include_once('../classes/pnt/test/db/testCaseDbPolymorphic.php');
		includeClass('PntSqlFilter', 'pnt/db/query');

		$this->dbObjectTest = new CaseDbObject();
		$this->clsDes =& PntClassDescriptor::getInstance('TestDbObject');
		$this->obj1 =& $this->clsDes->_getPeanutWithId('1');
		$this->filter1 =& new PntSqlFilter();
	}

	function testCreateTableAndDbObjects()
	{
		$this->dbObjectTest->test_CreateTables();
		$this->dbObjectTest->setUp();
		$this->dbObjectTest->test_insert_retrieve();
		$this->dbObjectTest->test_insertChild();
	}
	
	function initFilterWithPresetTemplate()
	{
		$this->filter1->set('itemType', 'TestDbObject');
		$this->filter1->set('sqlTemplate', 'testdbobjects.doubleField = $value1');
		$this->filter1->set('valueType', 'number');
		$this->filter1->set('value1', 76543.21);
		$this->filter1->set('tableName', 'testdbobjects');
	}		
	
	function testGetSqlFromPresetTemplate()
	{
		$this->initFilterWithPresetTemplate();
		$this->assertEquals(
			"testdbobjects.doubleField = '76543.21'"
			, $this->filter1->getSql()
			);
	}
	
	function testWithKey()
	{
		$this->filter1->set('key', 'doubleField');
		$this->filter1->set('itemType', 'TestDbObject');
		$this->filter1->set('valueType', 'number');
		$this->filter1->set('comparatorId', '=');
		$this->filter1->set('value1', 76543.21);
		
		$this->assertEquals(
			'TestDbObject'
			, $this->filter1->get('itemType')
			, "itemType");
		$this->assertEquals(
			'doubleField'
			, $this->filter1->get('label')
			, "label");
		$this->assertEquals(
			'number'
			, $this->filter1->get('valueType')
			, "valueType");
		$this->assertEquals(
			"testdbobjects.doubleField = '76543.21'"
			, $this->filter1->get('sql')
			, "sql");
	}

	function test_getInstance()
	{
		$this->filter1 =& PntSqlFilter::getInstance('TestDbObject', 'doubleField');
		$this->assertNotNull($this->filter1, "getInstance('TestDbObject', 'doubleField')" );
		$this->filter1->set('comparatorId', '=');
		$this->filter1->set('value1', 76543.21);
		
		$this->assertEquals(
			'TestDbObject'
			, $this->filter1->get('itemType')
			, "itemType");
		$this->assertEquals(
			'doubleField'
			, $this->filter1->get('label')
			, "label");
		$this->assertEquals(
			'number'
			, $this->filter1->get('valueType')
			, "valueType");
		$this->assertEquals(
			"testdbobjects.doubleField = '76543.21'"
			, $this->filter1->get('sql')
			, "sql");
	}

	function testComparator_evaluateValue_against()
	{
		$comparators =& PntComparator::getInstances();
		$value = 12345.67;
		Assert::true($comparators['=']->evaluateValue_against($value, $value, null) );
		Assert::false($comparators['!=']->evaluateValue_against($value, $value, null) );
		Assert::true($comparators['>=']->evaluateValue_against($value, $value, null) );
		Assert::false($comparators['>']->evaluateValue_against($value, $value, null) );	
		Assert::true($comparators['<=']->evaluateValue_against($value, $value, null) );
		Assert::false($comparators['<']->evaluateValue_against($value, $value, null) );	
		Assert::true($comparators['<']->evaluateValue_against($value, $value + 1, null) );
		Assert::true($comparators['>']->evaluateValue_against($value, $value - 1, null) );
		Assert::true($comparators['BETWEEN AND']->evaluateValue_against($value, $value - 1, $value + 1) );
		Assert::true($comparators['NOT NULL']->evaluateValue_against($value, $value, null) );
		Assert::false($comparators['IS NULL']->evaluateValue_against($value, $value, null) );

		//not yet implemented
		//Assert::true($comparators['LIKE']->evaluateValue_against('aap noot mies', '%noot%', null) );
		//Assert::false($comparators['LIKE']->evaluateValue_against('aap noot mies', '%wim%', null) );
	}

	function testSelectEvaluate()
	{
		$this->filter1 =& PntSqlFilter::getInstance('TestDbObject', 'doubleField');
		$this->assertNotNull($this->filter1, "getInstance('TestDbObject', 'doubleField')" );
		$this->filter1->set('comparatorId', '=');
		$value = 1;
		$this->filter1->set('value1', $value);
		Assert::false($this->filter1->evaluateValue(12345.67), "evaluateValue $value");
		Assert::false($this->filter1->evaluate($this->obj1), "obj1->doubleField=$value"); 

		$value = 12345.67;
		$this->filter1->set('value1', $value);
		Assert::true($this->filter1->evaluateValue($value), "evaluateValue $value");
		Assert::true($this->filter1->evaluate($this->obj1), "obj1->doubleField=$value"); 
	
		$instances =& $this->clsDes->_getPeanuts();
		$found =& $this->filter1->selectFrom($instances);
		$qh =& $this->clsDes->getSelectQueryHandler();
		$qh->addSqlFromSpec($this->filter1);
		$retrieved =&  $this->clsDes->_getPeanutsRunQueryHandler($qh);
		$this->assertEquals($retrieved, $found, "obj1->doubleField=$value selectFrom");

		$found =& $this->filter1->assocSelectFrom($instances);
		$this->assertEquals($retrieved[0], $found[1], "obj1->doubleField=$value assocSelectFrom");
	}

	function testComparatorVariants()
	{
		$this->filter1->set('key', 'stringField');
		$this->filter1->set('itemType', 'TestDbObject');		
		$this->filter1->set('valueType', 'string');
		$this->filter1->set('comparatorId', '=');
		$this->filter1->set('value1', '*Zomaar*String*');

		$this->assertEquals(
			"testdbobjects.stringField = '*Zomaar*String*'"
			, $this->filter1->get('sql')
			, "=");
		
		$this->filter1->set('comparatorId', '>');
		$this->assertEquals(
			"testdbobjects.stringField > '*Zomaar*String*'"
			, $this->filter1->get('sql')
			, ">");		
			
		$this->filter1->set('comparatorId', 'LIKE');
		$this->assertEquals(
			"testdbobjects.stringField LIKE '%Zomaar%String%'"
			, $this->filter1->get('sql')
			, "*=");
		
		$this->filter1->set('comparatorId', 'NOT NULL');
		$this->assertEquals(
			"testdbobjects.stringField IS NOT NULL"
			, $this->filter1->get('sql')
			, "NOT NULL");
			
		$this->filter1->set('comparatorId', 'IS NULL');
		$this->assertEquals(
			"testdbobjects.stringField IS NULL"
			, $this->filter1->get('sql')
			, "IS NULL");
			
		$this->filter1->set('comparatorId', 'BETWEEN AND');
		$this->filter1->set('value2', 'v2');

		$this->assertEquals(
			"testdbobjects.stringField BETWEEN '*Zomaar*String*' AND 'v2'"
			, $this->filter1->get('sql')
			, "><");
			
	}
	
	function test_getPersistArray_FilterWithPresetTemplate()
	{ 
		$this->initFilterWithPresetTemplate();
		$array =& $this->filter1->getPersistArray();

		$this->assertEquals('PntSqlFilter', $array['clsId'], 'clsId');
		$this->assertEquals('TestDbObject', $array['itemType'], 'itemType');
		$this->assertEquals('testdbobjects.doubleField = $value1', $array['sqlTemplate'], 'sqlTemplate');
		$this->assertEquals('number', $array['valueType'], 'valueType');
		$this->assertEquals(76543.21, $array['value1'], 'value1');
		$this->assertEquals('testdbobjects', $array['tableName'], 'tableName');
		$this->assertEquals(0, $array['comparatorId'], 'comparatorId');
		$this->assertEquals(7, count($array), 'array count');
	}

	function test_instanceFromPersistArray_FilterWithPresetTemplate()
	{
		$this->initFilterWithPresetTemplate();
		$array =& $this->filter1->getPersistArray();
		$filterFromArray =& PntSqlFilter::instanceFromPersistArray($array);
		Assert::propertiesEqual($this->filter1, $filterFromArray);
	}
	
	function initDateBetweenFilter() 
	{
		$this->filter1->set('itemType', 'TestDbObject');
		$this->filter1->set('key', 'dateField');
		$this->filter1->set('valueType', 'date');
		$this->filter1->set('comparatorId', 'BETWEEN AND');
		$this->filter1->set('value1', '1970-01-01');
		$this->filter1->set('value2', '2050-12-31');
		$this->assertEquals(
			"testdbobjects.dateField BETWEEN '1970-01-01' AND '2050-12-31'"
			, $this->filter1->get('sql')
			, "date between filter filter");
		
	}
	
	function test_getPersistArray_dateBetweenFilter()
	{
		$this->initDateBetweenFilter();
		$array =& $this->filter1->getPersistArray();

		$this->assertEquals('PntSqlFilter', $array['clsId'], 'clsId');
		$this->assertEquals('TestDbObject', $array['itemType'], 'itemType');
		$this->assertEquals('dateField', $array['key'], 'key');
		$this->assertEquals('date', $array['valueType'], 'valueType');
		$this->assertEquals('BETWEEN AND', $array['comparatorId'], 'comparatorId');
		$this->assertEquals('1970-01-01', $array['value1'], 'value1');
		$this->assertEquals('2050-12-31', $array['value2'], 'value2');
		$this->assertEquals(7, count($array), 'array count');
	}
	
	function test_instanceFromPersistArray_dateBetweenFilter()
	{
		$this->initDateBetweenFilter();
		$array =& $this->filter1->getPersistArray();
		$filterFromArray =& PntSqlFilter::instanceFromPersistArray($array);
		Assert::propertiesEqual($this->filter1, $filterFromArray);
	}

	function testSetGlobalFilter()
	{
		$this->initDateBetweenFilter();
		
		global $site;
		$filters = $site->getGlobalFilters();
		$this->assertNotNull($filters[0], "should fail first time, succeed second, fail third, etc");
		if ($filters[0])
			$this->assertEquals(
				$this->filter1->get('sql')
				, $filters[0]->get('sql')
				, "global filter from session");
		
		if ($filters[0]) {
			unSet($filters[0]);
			$site->setGlobalFilters($filters);
			print "cleared global filter";
		} else {
			print "Setting global filter, you should clear global filter by running this test again";
			$filters[0] =& $this->filter1;
			$site->setGlobalFilters($filters);
		}
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

	function test_subclassField()
	{
		$this->filter1 =& PntSqlFilter::getInstance('TestDbSub', 'subOnlyStringField');
		$this->assertNotNull($this->filter1, "getInstance('TestDbSub', 'subOnlyStringField')" );
		$this->filter1->set('comparatorId', '=');
		$this->filter1->set('value1', '1234567891123456789212345678931234567894');
		
		$this->assertEquals(
			'TestDbSub'
			, $this->filter1->get('itemType')
			, "itemType");
		$this->assertEquals(
			'subOnlyStringField'
			, $this->filter1->get('label')
			, "label");
		$this->assertEquals(
			'string'
			, $this->filter1->get('valueType')
			, "valueType");
		$this->assertEquals(
			"testdbsubs.subOnlyStringField = '1234567891123456789212345678931234567894'"
			, $this->filter1->get('sql')
			, "sql");
	}

	function test_dropTables()
	{
		$dbObjectTest =& new CaseDbPolymorphic();
		$dbObjectTest->test_dropTables();
	}

}

return 'SqlFilterTest';
?>
