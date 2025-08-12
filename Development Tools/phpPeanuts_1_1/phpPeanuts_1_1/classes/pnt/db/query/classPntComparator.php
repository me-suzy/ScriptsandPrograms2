<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

includeClass('PntIdentifiedOption', 'pnt');

/** Objects of this class describe a comparision.
* Used by FilterFormPart in the advanced search.
* part for navigational query specification, part of PntSqlFilter
* @see http://www.phppeanuts.org/site/index_php/Pagina/170
*
* Current version is MySQL specific. In future, all SQL generating methods should 
* delegate to PntQueryHandler to support other databases
*
* This abstract superclass provides behavior for the concrete
* subclass Comparator in the root classFolder or in the application classFolder. 
* To keep de application developers code (including localization overrides) 
* separated from the framework code override methods in the 
* concrete subclass rather then modify them here.
* @see http://www.phppeanuts.org/site/index_php/Menu/178
* @package pnt/db/query
*/
class PntComparator extends PntIdentifiedOption {

	function PntComparator($id=null, $label=null, $sqlOperator=null, $addition=null)
	{
		$this->PntIdentifiedOption($id, $label);
		$this->sqlOperator = $sqlOperator;
		$this->addition = $addition;
	}

	/** @static 
	* @return String the name of the database table the instances are stored in
	* @abstract - override for each subclass
	*/
	function initPropertyDescriptors() {
		// only to be called once

		parent::initPropertyDescriptors();

		$this->addFieldProp('id', 'string');
		$this->addFieldProp('sqlOperator', 'string');
		$this->addFieldProp('addition', 'string');
	}

	/** Returns the instances 
	* @static
	* @abstract
	* @return Array of instances
	*/
	function &getInstances()
	{
		$result['LIKE'] = new PntComparator('LIKE', '~', 'LIKE');
		$result['='] = new PntComparator('=');
		$result['>'] = new PntComparator('>');
		$result['>='] = new PntComparator('>=');
		$result['<'] = new PntComparator('<');
		$result['<='] = new PntComparator('<=');
		$result['!='] = new PntComparator('!=');
		$result['BETWEEN AND'] = new PntComparator('BETWEEN AND', '<= <=', 'BETWEEN', 'AND');
		$result['IS NULL'] = new PntComparator('IS NULL', null, 'IS');
		$result['NOT NULL'] = new PntComparator('NOT NULL', null, 'IS NOT');
		
		return $result;
	}
	
	// If there where subclasses for different comparators, this would be a polymorphism,
    // but  that would result in many extra subclasses to be parsed 
    // if this method is not heavily used, this case switch is probably more efficient  
	function evaluateValue_against($value, $value1, $value2)
	{
		if ($this->id == '=')
			return $value == $value1;
		if ($this->id == 'BETWEEN AND') 
			return $value >= $value1 && $value <= $value2;
		if ($this->id == 'IS NULL')
			return $value === null;  //SQL compatible null handling
		if ($this->id == 'NOT NULL')
			return $value !== null;  //SQL compatible null handling
		if ($this->id == 'LIKE')
			return trigger_error('PntComparator::evaluateValue_against Not yet implemented for LIKE', E_USER_ERROR);
			
		return eval("return \$value $this->id \$value1;");
	}

	function getSqlOperator()
	{
		if ($this->sqlOperator)
			return $this->sqlOperator;
			
		return $this->get('id');
	}
	
	function sqlFromValue($value)
	{
		if ($this->id == 'LIKE')
			return str_replace('*', '%', $value);
			
		if ($this->id == 'IS NULL' || $this->id == 'NOT NULL')
			return null;
			
		return $value;
	}

}
?>