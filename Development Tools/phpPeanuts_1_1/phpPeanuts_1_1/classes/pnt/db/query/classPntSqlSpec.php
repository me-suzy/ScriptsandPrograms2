<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

includeClass('PntObject', 'pnt');
includeClass('QueryHandler');

/** Abstract superclass for parts for navigational query specification.
* @see http://www.phppeanuts.org/site/index_php/Pagina/170* 
* @package pnt/db/query
*/
class PntSqlSpec extends PntObject {

	var $label;
	var $itemType;
	var $id;
	
	function PntSqlSpec($id=null)
	{
		$this->PntObject();
		$this->id = $id;
	}

	/** @static 
	* @return String the name of the database table the instances are stored in
	* @abstract - override for each subclass
	*/
	function initPropertyDescriptors() {
		parent::initPropertyDescriptors();
		
		$this->addFieldProp('id', 'string', false, null, null, 0, null);
		$this->addFieldProp('label', 'string', false, null, null, 0, null);
		$this->addFieldProp('itemType', 'string', false, null, null, 0, null);

		$this->addDerivedProp('sqlForJoin', 'string', true, null, null, 0, null);
		$this->addDerivedProp('sql', 'string', true, null, null, 0, null);

		//$this->addFieldProp($name, $type, $readOnly=false, $minValue=null, $maxValue=null, $minLength=0, $maxLength=null, $classDir=null, $persistent=true) 
		//$this->addDerivedProp/addMultiValueProp($name, $type, $readOnly=true, $minValue=null, $maxValue=null, $minLength=0, $maxLength=null, $classDir=null) 
	}
	
	function getId()
	{
		return $this->id;
	}

	function getLabel()
	{
		if ($this->label)
			return $this->label;
		else
			return $this->getId();
	}
	
	function &getQueryHandler()
	{
		if (!isSet($this->queryHandler)) {
			if ($this->getItemType()) {
				$clsDesc =& PntClassDescriptor::getInstance(
					$this->getItemType()
				);
				$this->queryHandler =& $clsDesc->getSelectQueryHandler();
			} else 
				$this->queryHandler =& new QueryHandler();
		}
		return $this->queryHandler;
	}

	function getDescription()
	{
		return $this->getSql();
	}

	/** Return a piece of SQL for extending the FROM clause with the tables to be joined
	*/
	function getSqlForJoin()
	{
		$joins = array();
		$this->addJoinTableAndConditionByTableAlias($joins);

		$result = '';
		while (list($alias) = each($joins)) {
			$tac =& $joins[$alias];
			$result .= " LEFT JOIN $tac[0] AS $alias ON $tac[1]" ;
		}
		return $result;
	}
	
}
?>