<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

includeClass('PntSqlFilter', 'pnt/db/query');

/** Specifies a navigation over a relationship, generates SQL.
* Used by FilterFormPart in the advanced search.
* part for navigational query specification, part of a PntSqlSpec
* @see http://www.phppeanuts.org/site/index_php/Pagina/170
*  
* PntSqlFilters produce what comes after the WHERE clause to retrieve
* some objects as well as a JOIN clause to access related tables.
* Objects of this class produce JOIN clauses.
* $this->next must be set to a PntSqlFilter that will provide
* the WHERE expression for searching the related table, 
* or to another PntSqlJoinFilter for another join.
*
* Current version is MySQL specific. In future, all SQL generating methods should 
* delegate to PntQueryHandler to support other databases
* Current implementation will AND the own where expression only if a sqlTemplate has been set.
* @package pnt/db/query
*/
class PntSqlJoinFilter extends PntSqlFilter {

	var $next = null;
	var $idColumnIsOnAlias = true; 
	var $joinCondition;

	function PntSqlJoinFilter()
	{
		$this->PntSqlFilter();
	}

	/** @static 
	* @return String the name of the database table the instances are stored in
	* @abstract - override for each subclass
	*/
	function initPropertyDescriptors() {
		parent::initPropertyDescriptors();

		$this->addFieldProp('key', 'string', false, null, null, 0, null);
		$this->addFieldProp('sqlForJoin', 'string', false, null, null, 0, null);
		$this->addFieldProp('next', 'PntSqlFilter', false, null, null, 0, null);
		
		//addFieldProp($name, $type, $readOnly=false, $minValue=null, $maxValue=null, $minLength=0, $maxLength=null, $classDir=null, $persistent=true) 
		//addDerivedProp/addMultiValueProp($name, $type, $readOnly=true, $minValue=null, $maxValue=null, $minLength=0, $maxLength=null, $classDir=null) 
	}

	/* Return the result of evaluating the supplied object against this.
	* for multivalue property returns true if the property contains an item that matches $this->next 
   */
	function &evaluate(&$item)
	{
		// this way of getting the value is inefficient and assumes pntObject 
		//shoud have a PntNavigation in a field
		$nextItem =& $item->get($this->key);
		if ($this->valueType != 'Array' && is_array($nextItem))
			//to be replaced by more efficient detect method
			return count($this->next->selectFrom($nextItem)) != 0;
		else
			return $this->next->evaluate($nextItem);
	}

	function getPath()
	{
		$result = $this->key;
		if ($this->next)
			$result .= '.'.$this->next->getId();
		return $result;
	}

	function getLabel()
	{
		if ($this->label)
			$result = $this->label;
		else
			$result = $this->key;
		
		if ($this->next)
			$result .= '.'.$this->next->getLabel();
		return $result;
	}
	
	/** true if column 'id' is on the aliased table  (relation is n to 1)
	* false if column 'id' is on the own table (relation is 1 to n)
	* The value of this property is calculated by getColumnName 
    * when it is called from addJoinTableAndConditionByTableAlias, unless columnName 
    * has been explicitly set. 
	*/
	function getIdColumnIsOnAlias()
	{
		return $this->idColumnIsOnAlias;
	}
	
	/** if columnName has been explicitly set, this property must be set too
    * (default is true).
    */
	function setIdColumnIsOnAlias($aBoolean)
	{
		$this->idColumnIsOnAlias = $aBoolean;
	}
	
	//field must be set if navigation is not a valid PntObjectNavigation
	function getValueType()
	{
		return $this->next->getValueType();
	}

	function &getNext()
	{
		return $this->next;
	}
	
	function setNext(&$value)
	{
		$this->next =& $value;
	}

	function setComparatorId($value)
	{
		$this->comparatorId = $value;
		$this->next->setComparatorId($value);
	}

	function setValue1($value)
	{
		$this->value1 = $value;
		$this->next->setValue1($value);
	}

	function setValue2($value)
	{
		$this->value2 = $value;
		$this->next->setValue2($value);
	}

	function getColumnName()
	{
		if ($this->columnName) 
			return $this->columnName;
		
		$idProp =& $this->getIdPropertyDescriptor();
		if ($this->idColumnIsOnAlias) //initialized by getPropertyDescriptor()
			$map =& $this->getFieldMapPrefixed();
		else 
			$map =& $this->next->getFieldMapPrefixed();
		return $map[$idProp->getName()];
	}

	function &getIdPropertyDescriptor()
	{
		$clsDes =& PntClassDescriptor::getInstance($this->itemType);
		$prop =& $clsDes->getPropertyDescriptor($this->key);
		$idProp =& $prop->getIdPropertyDescriptor();
		if (!$idProp) trigger_error("no id property found for ". $prop->getLabel(), E_USER_ERROR);
		
		$this->idColumnIsOnAlias = !$prop->isMultiValue(); // was: $idProp->ownerName == $this->itemType; maar dat is niet ok als recursieve relatie
		return $idProp;
	}

	/** The name of the table, used for creating join condition by previous JoinFilter.
  	*/
	function getTableName()
	{
		if ($this->tableName)
			return $this->tableName;

		$clsDes =& PntClassDescriptor::getInstance($this->itemType);
		$idProp =& $this->getIdPropertyDescriptor();
		if ($this->idColumnIsOnAlias) {
			return $this->tableName = $clsDes->getTableName();
		} else {
			$prop =& $clsDes->getPropertyDescriptor($this->key);
			return $this->tableName = $prop->getTableName();
		}
	}

	function getSqlTemplate()
	{
		return $this->sqlTemplate;
	}

	/* Returns what comes after the WHERE keyword to retrieve the objects' data
	* the columnName is used for the join, so we assume that the own WHERE expression
	* is only to be used if a sqlTempalte has been set.
	* PREREQUISITE: addJoinTableAndConditionByTableAlias() has been called
	* (getSqlForJoin() calls addJoinTableAndConditionByTableAlias())
	*/
	function getSql()
	{
		if ($this->sqlTemplate) {
			$result = '(';
			$result .= parent::getSql();
			$result .= ') AND (';
			$result .= $this->next->getSql();
			$result .= ')';
			return $result;
		} else {
			return $this->next->getSql();
		}
	}
	
 	function addJoinTableAndConditionByTableAlias(&$anArray)
	{
		$clsDesc =& PntClassDescriptor::getInstance($this->next->getItemType());
		$tableName = $this->next->getTableName();
		$tableAlias = $this->key.'ALIAS'.$tableName;
		$this->next->set('tableAlias', $tableAlias);
		
		$tableAndConditon = array($tableName, $this->getJoinCondition($tableAlias) );
		if (isSet($anArray[$tableAlias]) ) {
			$otherTac =& $anArray[$tableAlias]; 
			if ($otherTac != $tableAndConditon )
				trigger_error("join condition ". pntToString($otherTac) . " for $tableAlias different from: ". pntToString($tableAndConditon), E_USER_WARNING); 
		} else {
			$anArray[$tableAlias] = $tableAndConditon;
		}
		$this->next->addJoinTableAndConditionByTableAlias($anArray);
	}
	
	/** @private - do not call before addJoinTableAndConditionByTableAlias has been called 
	*/
	function getJoinCondition($tableAlias)
	{
		if ($this->joinCondition) return $this->joinCondition;
		
		$map =& $this->getFieldMapPrefixed();
		$columnName = $this->getColumnName(); //must be called BEFORE getIdColumnIsOnAlias
		$idColumnName = $this->getIdColumnIsOnAlias() ? "$tableAlias.id" : $map['id'];
		$this->joinCondition =  "$columnName = $idColumnName";
		return $this->joinCondition;
	}

	function setJoinCondition($value)
	{
		$this->joinCondition = $value;
	}
	
	function &getLast()
	{
		$next =& $this->getNext();
		return $next->getLast();
	}
	
	/** home-grown serialize as php source. 
	* @depricated
	*/
	function getPhpSource($i=1)
	{
		$source = "
\$filter$i =& new PntSqlFilter();
\$filter$i ->id = '$this->id';
\$filter$i ->key = '$this->key';
\$filter$i ->itemType = '$this->itemType';
\$filter$i ->label = '$this->label';
\$filter$i ->comparatorId = '$this->comparatorId';
\$filter$i ->value1 = '$this->value1';
\$filter$i ->value2 = '$this->value2';
\$filter$i ->valueType = '$this->valueType';
\$filter$i ->sqlTemplate = '$this->sqlTemplate';
\$filter$i ->sqlForJoin = '$this->sqlForJoin';
\$filter$i ->columnName = '$this->columnName';
\$filter$i ->tableAlias = '$this->tableAlias';
\$filter$i ->tableName = '$this->tableName';
";

		$nextI = $i+1;
		$source .= $this->next->getPhpSource($nextI);
		$source .= "\$filter$i ->next =& \$filter$nextI;
";

		if ($i==1)
			$source .= "
return \$filter1;";
		return $source;
	}

	//had problems with serialize, so let's only serialize the data 
	function &getPersistArray()
	{
		$result = parent::getPersistArray();
		$result['next'] =& $this->next->getPersistArray();
		return $result;
	}

	/** @static
	*/
	function &instanceFromPersistArray(&$array)
	{
		if ($array['clsId'] != 'PntSqlJoinFilter')
			trigger_error("filter class not supported: ". $array['clsId'], E_USER_ERROR);
		
		$result =& new PntSqlJoinFilter();
		$result->initFromPersistArray($array);
		return $result;
	}

	function initFromPersistArray(&$array) {
		parent::initFromPersistArray($array);
		$this->next =& PntSqlFilter::instanceFromPersistArray($array['next']); 
	}
}
?>