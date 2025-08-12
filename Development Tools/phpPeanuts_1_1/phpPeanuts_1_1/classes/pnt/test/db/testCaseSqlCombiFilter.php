<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

 includeClass('PntTestCase', 'pnt/unit');

class CaseSqlCombiFilter extends PntTestCase {
	
	var $dbObjectTest;
	var $clsDes;
	var $obj1;

	function setUp() {
		includeClass('TestDbSub', 'pnt/test/db');
		include_once('../classes/pnt/test/db/testCaseDbPolymorphic.php');
		includeClass('PntSqlCombiFilter', 'pnt/db/query');

		$this->dbObjectTest = new CaseDbObject();
		$this->clsDes =& PntClassDescriptor::getInstance('TestDbObject');
		$this->obj1 =& $this->clsDes->_getPeanutWithId('1');

		$this->filter1 =& PntSqlFilter::getInstance('TestDbObject', 'doubleField');
		$this->filter1->set('comparatorId', '=');
		$this->filter1->set('value1', 76543.21);
		$this->filter2 =& PntSqlFilter::getInstance('TestDbObject', 'stringField');
		$this->filter2->set('comparatorId', '>');
		$this->filter2->set('value1', 'zomaar');
		$this->combiFilter =& new PntSqlCombiFilter();
		$this->combiFilter->addPart($this->filter1);
		$this->combiFilter->addPart($this->filter2);
	}

	function testCreateTableAndDbObjects()
	{
		$this->dbObjectTest->test_CreateTables();
		$this->dbObjectTest->setUp();
		$this->dbObjectTest->test_insert_retrieve();
		$this->dbObjectTest->test_insertChild();
	}
	
	function testCombiFilterParts()
	{
		$this->assertEquals(
			"testdbobjects.doubleField = '76543.21'"
			, $this->filter1->getSql()
			);
		$this->assertSame(
			$this->filter1
			, $this->combiFilter->parts['doubleField']
			, 'part[doubleField] === $this->filter1');
		$this->assertEquals(
			"testdbobjects.stringField > 'zomaar'"
			, $this->filter2->getSql()
			);
		$this->assertSame(
			$this->filter2
			, $this->combiFilter->parts['stringField']
			, 'part[stringField] === $this->filter2');
	}

	function testEvaluate()
	{
		Assert::false($this->combiFilter->evaluate($this->obj1), "inital");
		$value = 1;
		$this->filter1->set('value1', $value);
		Assert::false($this->combiFilter->evaluate($this->obj1), "obj1->doubleField=$value && obj1>stringField > 'zomaar' "); 
		$value = 12345.67;
		$this->filter1->set('value1', $value);
//		Assert::true($this->filter1->evaluateValue($value), "evaluateValue $value");
		Assert::true($this->filter1->evaluate($this->obj1), "obj1->doubleField=$value && obj1>stringField > 'zomaar' "); 

		$this->combiFilter->set('combinator', 'OR');
		Assert::true($this->combiFilter->evaluate($this->obj1), "obj1->doubleField=$value || obj1>stringField > 'zomaar' "); 
		$value = 1;
		$this->filter1->set('value1', $value);
		Assert::true($this->combiFilter->evaluate($this->obj1), "obj1->doubleField=$value || obj1>stringField > 'zomaar' ");
		$this->filter2->set('comparatorId', '=');
		$this->filter2->comparator = null;
		Assert::false($this->combiFilter->evaluate($this->obj1), "obj1->doubleField=$value || obj1>stringField = 'zomaar' "); 
	}

	function testSqlAndCombinatorVariants()
	{
		$this->assertEquals(
			"(testdbobjects.doubleField = '76543.21') AND (testdbobjects.stringField > 'zomaar')"
			, $this->combiFilter->get('sql')
			, "AND");
		
		$this->combiFilter->set('combinator', 'OR');
		$this->assertEquals(
			"(testdbobjects.doubleField = '76543.21') OR (testdbobjects.stringField > 'zomaar')"
			, $this->combiFilter->get('sql')
			, "OR");		
	}

	//TO DO: test combination of JOIN conditions

	function test_dropTables()
	{
		$this->dbObjectTest->test_dropTables();
	}

}

?>
