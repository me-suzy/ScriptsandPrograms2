<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

includeClass('PntSqlFilter', 'pnt/db/query');

/** Specifies the combination of mutliple PntSqlFilters by AND or OR. 
* Used by FilterFormPart in the simple search.
* part for navigational query specification, part of a PntSqlSpec
* @see http://www.phppeanuts.org/site/index_php/Pagina/170
*
* PntSqlFilters produce what comes after the WHERE clause to retrieve
* some objects as well as a JOIN clause to access related tables.
* Objects of this class combine the JOIN clauses from multiple PntSqlFilters
* from $this->parts and combine their WHERE expressions using their combinator field
* (by defauilt 'AND').
* @see http://www.phppeanuts.org/site/index_php/Pagina/170
*
* Current version is MySQL specific. In future, all SQL generating methods should 
* delegate to PntQueryHandler to support other databases
* @package pnt/db/query
*/
class PntSqlCombiFilter extends PntSqlFilter {

	var $parts;
	var $combinator = 'AND';

	function PntSqlCombiFilter()
	{
		$this->PntSqlFilter();
		$this->parts = array();
	}

	/** @static 
	* @return String the name of the database table the instances are stored in
	* @abstract - override for each subclass
	*/
	function initPropertyDescriptors() {
		parent::initPropertyDescriptors();

		$this->addFieldProp('combinator', 'string');
		$this->addMultiValueProp('parts', 'PntSqlFilter', false);
		
		//addFieldProp($name, $type, $readOnly=false, $minValue=null, $maxValue=null, $minLength=0, $maxLength=null, $classDir=null, $persistent=true) 
		//addDerivedProp/addMultiValueProp($name, $type, $readOnly=true, $minValue=null, $maxValue=null, $minLength=0, $maxLength=null, $classDir=null) 
	}
	
	function addPart(&$value)
	{
		$this->parts[$value->get('id')] =& $value;
	}

	/** Returns a recursive copy of $this 
	*/
	function &copy()
	{
		$copy =& objectCopy($this);
		$copy->parts = array();
		reset($this->parts);
		while(list($id) = each($this->parts))
			$copy->parts[$id] =& $this->parts[$id]->copy();
		return $copy;
	}

	function &getParts()
	{
		return $this->parts;
	}

	function setComparatorId($value)
	{
		$this->comparatorId = $value;
		reset($this->parts);
		while (list($key) = each($this->parts))
			$this->parts[$key]->setComparatorId($value);
	}

	function setValue1($value)
	{
		$this->value1 = $value;
		reset($this->parts);
		while (list($key) = each($this->parts))
			$this->parts[$key]->setValue1($value);
	}

	function setValue2($value)
	{
		$this->value2 = $value;
		reset($this->parts);
		while (list($key) = each($this->parts))
			$this->parts[$key]->setValue2($value);		
	}

	/* Returns what comes after the WHERE keyword to retrieve the objects' data
	* Implementation is to return the sqlTempate merged with value1 and value2 converted to SQL
	*/
	function getSql()
	{
		//get rid of repeated conditions
		$conditionsAsKeys = array();
		reset($this->parts);
		while (list($key) = each($this->parts)) {
			$conditionsAsKeys[$this->parts[$key]->getSql()] = $key;
		}
		$result = '';
		$combi = '';
		while (list($key) = each($conditionsAsKeys)) {
			$result .= $combi;
			$result .= '(';
			$result .= $key;
			$result .= ')';
			$combi = " $this->combinator ";
		}
		return $result;
	}

	function addJoinTableAndConditionByTableAlias(&$anArray)
	{
		reset($this->parts);
		while (list($key) = each($this->parts)) 
			$this->parts[$key]->addJoinTableAndConditionByTableAlias($anArray);
	}		
	
	function getDescription()
	{
		$result = '';
		reset($this->parts);
		$combi = '';
		while (list($key) = each($this->parts)) {
			$result .= $combi;
			$this->parts[$key]->getDescription();
			$combi = " $this->combinator ";
		}
		return $result;
	}

	// home-grown serialize als php source. unserialize did not work...
	function getPhpSource($i=1)
	{
		$this->notYetImplemented(); //causes fatal error
	}

	// array to serialize. unserialize did not work with objects
	function getPersistArray() {
		$this->notYetImplemented(); //causes fatal error
	}
	
	function canBeSortSpec()
	{
		return false;
	}

	/* Return the result of evaluating the supplied object against this. 
   */
	function evaluate(&$item)
	{
		$nParts = count($this->parts); 
		if ($nParts == 0)
			return null;
		$keys = array_keys($this->parts);
		$result = $this->parts[$keys[0] ]->evaluate($item);
		for ($i=1; $i<$nParts; $i++) 
			$result = $this->combine( $result, $this->parts[$keys[$i] ]->evaluate($item) );

		return $result;
	}
	
	function combine($bool1, $bool2)
	{
		if ($this->combinator == 'AND')
			return $bool1 && $bool2;
		else
			return $bool1 || $bool2;
	}
}
?>