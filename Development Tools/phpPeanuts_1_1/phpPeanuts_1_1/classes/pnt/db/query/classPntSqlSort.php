<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

includeClass('PntSqlSpec', 'pnt/db/query');
includeClass('PntSqlJoinFilter', 'pnt/db/query');

/** PntSqlSort specifies (and produces) what comes after the ORDER BY keywords
* Used by FilterFormPart and PntDbClassDescriptor.
* part for navigational query specification, part of a PntSqlSpec
* @see http://www.phppeanuts.org/site/index_php/Pagina/170
* @see http://www.phppeanuts.org/site/index_php/Pagina/70
* @package pnt/db/query
*/
class PntSqlSort extends PntSqlSpec {

	function PntSqlSort($id=null, $itemType=null)
	{
		$this->PntSqlSpec($id);
		$this->itemType = $itemType;
		$this->sortSpecFilters = array();
	}

	/** @static 
	* @return String the name of the database table the instances are stored in
	* @abstract - override for each subclass
	*/
	function initPropertyDescriptors() {
		// only to be called once

		parent::initPropertyDescriptors();

		$this->addFieldProp('filter', 'PntSqlFilter', false, null, null, 0, null);
		$this->addMultiValueProp('sortSpecFilters', 'PntSqlFilter', false, null, null, 0, null);

		//$this->addFieldProp($name, $type, $readOnly=false, $minValue=null, $maxValue=null, $minLength=0, $maxLength=null, $classDir=null, $persistent=true) 
		//$this->addDerivedProp/addMultiValueProp($name, $type, $readOnly=true, $minValue=null, $maxValue=null, $minLength=0, $maxLength=null, $classDir=null) 
	}

	function &getFilter()
	{ 
		return $this->filter;
	}
	
	function setFilter(&$filter)
	{
		return $this->filter =& $filter;
	}
	
	/** @see getSortDirection()
	*/
	function addSortSpecFilter(&$aPntSqlFilter)
	{
		$this->sortSpecFilters[$aPntSqlFilter->getId()] =& $aPntSqlFilter;
	}
	
	//the array_merge will, instead of putting the new value in front,
	// move the old value there. 
	function addSortSpecFilterFirstOrMoveExistingFirst(&$aPntSqlFilter)
	{
		$elementArr[$aPntSqlFilter->getId()] =& $aPntSqlFilter;
		$this->sortSpecFilters=& array_merge($elementArr,$this->sortSpecFilters);
	}

	function addSortSpec($path, $direction='ASC')
	{
		$filter =& PntSqlFilter::getInstance($this->itemType, $path);
		$filter->comparatorId = ($direction == 'DESC' ? '>' : '<');
		$this->addSortSpecFilter($filter);
	}

	function addJoinTableAndConditionByTableAlias(&$anArray)
	{
		if (isSet($this->filter))
			$this->filter->addJoinTableAndConditionByTableAlias($anArray);
		
		reset($this->sortSpecFilters);
		while (list($key) = each($this->sortSpecFilters)) 
			$this->sortSpecFilters[$key]->addJoinTableAndConditionByTableAlias($anArray);
	}		
	
	/* Returns what comes after the WHERE clause and includes the OPRDER BY clauese 
	* PREREQUISITE: addJoinTableAndConditionByTableAlias() has been called
	* (getSqlForJoin() calls addJoinTableAndConditionByTableAlias())
	*/
	function getSql()
	{
		$result = '';
		if (isSet($this->filter))
			$result = $this->filter->getSql();
		
		$result .= ' ORDER BY ';
		$result .= $this->getOrderBySql();
		return $result;
	}
	
	/* Returns what comes after the ORDER BY keyword 
	* PREREQUISITE: addJoinTableAndConditionByTableAlias() has been called
	* (getSqlForJoin() calls addJoinTableAndConditionByTableAlias())
	*/
	function getOrderBySql()
	{
		//get rid of repeated conditions
		$result = '';
		reset($this->sortSpecFilters);
		$comma = '';
		while (list($key) = each($this->sortSpecFilters)) {
			$spec =& $this->sortSpecFilters[$key];
			$specLastFilter =& $spec->getLast();
			
			$result .= $comma;
			$result .= $specLastFilter->getColumnName();
			$result .= ' ';
			$result .= $this->getSortDirection($spec);
			$comma = ', ';
		}
		return $result;
	}
	
	/** Actually we abuse PntSqlFilters as sort specifiers. 
	* For the sort direction we use the comparatorId > as DESC, otherwise ASC
	*/
	function getSortDirection($sortSpecFilter)
	{
//		print '<BR>';
//		print_r($sortSpec->get('label'));
//		print ': '. $sortSpec->get('comparatorId');
		if ($sortSpecFilter->get('comparatorId') === '>')
			return 'DESC';
		else 
			return 'ASC';		
	}
}
?>