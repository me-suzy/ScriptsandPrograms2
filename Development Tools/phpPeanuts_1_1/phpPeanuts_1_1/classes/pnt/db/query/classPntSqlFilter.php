<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

includeClass('PntDbObject', 'pnt/db');
includeClass('PntSqlSpec', 'pnt/db/query');
includeClass('Comparator');

/** * PntSqlFilters specify (and produce) what comes after
* the WHERE clause to retrieve some objects
*
* Used by FilterFormPart in the advanced search.
* part for navigational query specification, part of PntSqlFilter
* @see http://www.phppeanuts.org/site/index_php/Pagina/170
* for navigation instances of a subclass also produce JOIN clauses to access related tables.
* Objects of this class produce an empty JOIN clause.
* Also used by other types of SqlFilter to produce
* more complicated WHERE expressions, JOIN and ORDER BY clauses
*
* Current version is MySQL specific. In future, all SQL generating methods should
* delegate to PntQueryHandler to support other databases
* @package pnt/db/query
*/
class PntSqlFilter extends PntSqlSpec {

	var $comparatorId = 0;
	var $valueType = 'string';
	var $tableAlias;
	var $tableName;
	var $key;
	var $value1; 
	var $value2;
	var $columnName;
	var $sqlTemplate;
	
	function PntSqlFilter()
	{
		$this->PntObject();
	}

	/** @static
	*/
	function &getInstance($itemType, $path)
	{
		$nav =& PntNavigation::_getInstance($path, $itemType);
		if (is_ofType($nav, 'PntError')) {
			trigger_error($nav->getLabel(), E_USER_WARNING);
			return null;
		}
		return PntSqlFilter::getInstanceForNav($nav);
	}

	/** @static
	* @param PntObjectNavigation $nav
	*/
	function &getInstanceForNav(&$nav)
	{
		$prevFilter = null;
		while ($nav) {
			if ($nav->getNext()) {
				$newFilter =& new PntSqlJoinFilter();
			} else {
				$newFilter =& new PntSqlFilter();
				$newFilter->set('valueType', $nav->getResultType());
			}
			$newFilter->set('key', $nav->getKey());
			$newFilter->set('itemType', $nav->getItemType());
			$newFilter->set('label', $nav->getFirstPropertyLabel());

			if (!isSet($result))
				$result =& $newFilter;
			if ($prevFilter)
				$prevFilter->setNext($newFilter);
			$prevFilter =& $newFilter;
			$nav =& $nav->getNext();
		}
		return $result;
	}

	/** @static
	* @return String the name of the database table the instances are stored in
	* @abstract - override for each subclass
	*/
	function initPropertyDescriptors() {
		parent::initPropertyDescriptors();

		$this->addFieldProp('tableAlias', 'string', false, null, null, 0, null);
		$this->addFieldProp('tableName', 'string', false, null, null, 0, null);

		$this->addFieldProp('key', 'string', false, null, null, 0, null);

		$this->addFieldProp('comparatorId', 'string', false, null, null, 0, null);
		$this->addDerivedProp('comparator', 'Comparator', false, null, null, 0, null);

		$this->addFieldProp('value1', 'string', false, null, null, 0, null);
		$this->addFieldProp('value2', 'string', false, null, null, 0, null);
		$this->addFieldProp('valueType', 'string', false, null, null, 0, null);

		$this->addFieldProp('columnName', 'string', false, null, null, 0, null);
		$this->addFieldProp('sqlTemplate', 'string', false, null, null, 0, null);

		//addFieldProp($name, $type, $readOnly=false, $minValue=null, $maxValue=null, $minLength=0, $maxLength=null, $classDir=null, $persistent=true)
		//addDerivedProp/addMultiValueProp($name, $type, $readOnly=true, $minValue=null, $maxValue=null, $minLength=0, $maxLength=null, $classDir=null)
	}

	function getId()
	{
		if (isSet($this->id)) return $this->id;
		//workaround for reference anomalies
		return $this->getPath();
	}

	function getPath()
	{
		return $this->key;
	}

	function getItemType()
	{
		return $this->itemType;
	}

	/** the alias or tablename to be used as prefix with the columnName.
	* Set by previous JoinFilter */
	function getTableAlias()
	{
		//if no alias, use tableName
		//important: do not cache the tableAlias, otherwise getFieldMapPrefixed will no longer support polymorphism
		if ($this->tableAlias)
			return $this->tableAlias;
		else
			return $this->getTableName();
	}

	/** The name of the table the column is stored in.  Used for creating join condition.
	*	if the key and itemtype do not identify a persistent fieldProperty, this must be set explicitly
  	*/
	function getTableName()
	{
		if ($this->tableName)
			return $this->tableName;
	
		$clsDesc =& PntClassDescriptor::getInstance($this->getItemType());
		$prop =& $clsDesc->getPropertyDescriptor($this->key);
		return $prop->getTableName();
	}
	
	function getValueType()
	{
		return $this->valueType;
	}

	function setComparatorId($value)
	{
		$this->comparatorId = $value;
	}

	function setValue1($value)
	{
		$this->value1 = $value;
	}

	function setValue2($value)
	{
		$this->value2 = $value;
	}

	function getFieldMapPrefixed()
	{
		if (!$this->getItemType())
			trigger_error($this->toString(). ' No itemtype', E_USER_ERROR);	
		$clsDesc =& PntClassDescriptor::getInstance(
			$this->getItemType()
		);
		if ($this->tableAlias) {
			$qh =& $this->getQueryHandler();
			return $qh->prefixColumnNames(
				$clsDesc->getFieldMap(),
				$this->getTableAlias()
			);
		} else 
			//becuase of polymorhic retrieval the classdescriptor must provide the prefixes  
			return $clsDesc->getFieldMapPrefixed();
	}

	// if field not set, builds template from sqlForPath and comparator(Id)
	function getSqlTemplate()
	{
		if ($this->sqlTemplate)
			return $this->sqlTemplate;

		$comp =& $this->get('comparator');
		$sqlOperator = $comp->getSqlOperator();
		$template = "\$columnName $sqlOperator \$value1";

		if ($comp && ($comparatorAddition = $comp->get('addition')) )
			$template .= " $comparatorAddition \$value2";

		return $template;
	}

	/* The prefixed column name */
	function getColumnName()
	{
		if ($this->columnName || ! $this->key)
			return $this->columnName;

		$map =& $this->getFieldMapPrefixed();

		return $map[$this->key];
	}

	/* Returns what comes after the WHERE keyword to retrieve the objects' data
	* Implementation is to return the sqlTempate merged with value1 and value2 converted to SQL
	*/
	function getSql()
	{
		if (isSet($this->next))
			return $this->next->getSql();

		$sql = $this->getSqlTemplate();
		$qh =& $this->getQueryHandler();
		$comparator =& $this->get('comparator');
		$columnName = $this->getColumnName();
		$sql = str_replace('$columnName', $columnName, $sql);
		$sql = str_replace(
			'$value1',
			$qh->convertConditionArgumentToSql($comparator
				? $comparator->sqlFromValue($this->value1)
				: $this->value1),
			$sql);
		$sql = str_replace(
			'$value2',
			$qh->convertConditionArgumentToSql($comparator
				? $comparator->sqlFromValue($this->value2)
				: $this->value2),
			$sql);
//print $sql;
		return $sql;
	}

	function getDescription($conv)
	{
		$this->initConverter($conv);
		$value1String = $conv->toLabel($this->get('value1'), $conv->type);
		$value2String = $conv->toLabel($this->get('value2'), $conv->type);
		if ($this->get('comparatorId') == 'BETWEEN AND')
			return $value1String.' <= '
				. $this->getLabel().' <= '
				. $value2String;

		else
			return $this->getLabel().' '
				. $this->get('comparatorId').' '
				. $value1String.' '
				. $value2String;
	}

	/** Initialize the converter
	* @param $conv PntStringConverter
	*/
	function initConverter(&$conv)
	{
		$conv->type = $this->getValueType(); //decimalprecision not necessary (?)
	}

	/** home-grown serialize as php source. 
	* @depricated
	*/
	function getPhpSource($i=1)
	{
		$source = "
PntClassDescriptor::getInstance('PntSqlFilter');
\$filter$i =& new PntSqlFilter();
\$filter$i ->key = '$this->key';
\$filter$i ->itemType = '$this->itemType';
\$filter$i ->label = '$this->label';
\$filter$i ->comparatorId = '$this->comparatorId';
\$filter$i ->value1 = '$this->value1';
\$filter$i ->value2 = '$this->value2';
\$filter$i ->valueType = '$this->valueType';
\$filter$i ->sqlTemplate = '$this->sqlTemplate';
\$filter$i ->columnName = '$this->columnName';
\$filter$i ->tableAlias = '$this->tableAlias';
\$filter$i ->tableName = '$this->tableName';
";
		if (isSet($this->id))
			$source .= "\$filter$i ->id = '$this->id';
";
		if ($i==1)
			$source .= "
return \$filter1;";
		return $source;
	}

	//had problems with serialize, so let's only serialize the data 
	function &getPersistArray()
	{
		$result = array();
		$clsDes =& $this->getClassDescriptor();
		$props =& $clsDes->getSingleValuePropertyDescriptors();
		while (list($propName) = each($props))
			if ($props[$propName]->isFieldProperty() && $props[$propName]->getPersistent() 
					&& isSet($this->$propName))
				$result[$propName] = $this->$propName;
		$result['clsId'] = $clsDes->getName();
		return $result;
	}
	
	/** @static
	*/
	function &instanceFromPersistArray(&$array)
	{
		if ($array['clsId'] == 'PntSqlJoinFilter')
			return PntSqlJoinFilter::instanceFromPersistArray($array);
		if ($array['clsId'] != 'PntSqlFilter')
			trigger_error("filter class not supported: ". $array['clsId'], E_USER_ERROR);
		
		$result =& new PntSqlFilter();
		$result->initFromPersistArray($array);
		return $result;
	}

	function initFromPersistArray(&$array) {
		
		$clsDes =& $this->getClassDescriptor();
		$props =& $clsDes->getSingleValuePropertyDescriptors();
		while (list($propName) = each($props))
			if (isSet($array[$propName]) )
				$this->$propName = $array[$propName];
	}

	function addJoinTableAndConditionByTableAlias(&$anArray)
	{
		//ignore
	}

	function &getLast()
	{
		return $this;
	}

	function canBeSortSpec()
	{
		return true;
	}

	/* Return the result of evaluating the supplied object against this. 
   */
	function evaluate(&$item)
	{
		// this way of getting the value is inefficient and assumes pntObject 
		//shoud have a PntNavigation in a field 
		return $this->evaluateValue($item->get($this->key));
	}

	/** Return the result of comparing the supplied value to the vaules of this, using the comparator
	*/ 
	function evaluateValue($value)
	{
		if (!isSet($this->comparator) )
			$this->comparator =& $this->get('comparator');
		return $this->comparator->evaluateValue_against($value, $this->value1, $this->value2);
	}
	
	/** Select objects from $array that match $this, leaving keys intact 
	*/
	function &assocSelectFrom(&$array)
	{
			$result = array();
			forEach(array_keys($array) as $eachKey) {
				if ($this->evaluate($array[$eachKey]))
					$result[$eachKey] =& $array[$eachKey];
			}
			return $result;
	}		

	/** Select objects from $array that match $this, renumbering the keys 
	*/
	function &selectFrom(&$array)
	{
			$result = array();
			forEach(array_keys($array) as $eachKey) {
				if ($this->evaluate($array[$eachKey]))
					$result[] =& $array[$eachKey];
			}
			return $result;
	}
		
}
?>