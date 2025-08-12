<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0


includeClass('PntClassDescriptor', 'pnt/meta');
includeClass('QueryHandler');
includeClass('PntNavigation', 'pnt/meta');

/** ClassDescriptor for persistent peanuts.
* Retrieves peanuts from the database.  Generates PntSqlFilters for searching.
* ClassDescriptor: @see http://www.phppeanuts.org/site/index_php/Pagina/96 
* Persistency: @see  http://www.phppeanuts.org/site/index_php/Menu/206
* Override or reimplement this class to adapt persistency or create your own,
* @see http://www.phppeanuts.org/site/index_php/Pagina/52
* @package pnt/db
*/
class PntDbClassDescriptor extends PntClassDescriptor {

	/** Name of property that holds class name for polymorhism support */
	var $polymorphismPropName = null;

	// just for caching
	var $tableName;
	var $fieldMap;
	var $peanutsById;

	function PntDbClassDescriptor($name)
	{
		$this->PntClassDescriptor($name);
		$this->peanutsById = array();
	}

	function addPropertyDescriptor(&$aPntPropertyDescriptor) 
	{
		if ($aPntPropertyDescriptor->isFieldProperty() ) {
			if ($this->polymorphismPropName) {
				$name = $aPntPropertyDescriptor->getName();
				$parentDesc =& $this->getParentclassDescriptor();
				$inherited =& $parentDesc->getPropertyDescriptor($name);
			
				if ($inherited && $inherited->isFieldProperty() && $inherited->getPersistent() && $name != 'id' && $inherited->getTableName() ) {
					$aPntPropertyDescriptor->setTableName($inherited->getTableName() );
				} else {//no polymorphism for this property
					$aPntPropertyDescriptor->setTableName($this->getTableName() );
				}
			} else { //no polymorphism for this classdescriptor
				$aPntPropertyDescriptor->setTableName($this->getTableName() );
			}
		} 
		parent::addPropertyDescriptor($aPntPropertyDescriptor);
	} 
			 
	/** Set which property used for polymorphic retrieval
    * If no value, retrieval is monomorphic (default)
	* @param String the name of the property
	*/
	function setPolymorphismPropName($value)
	{
		$this->polymorphismPropName = $value;
	}

	//used to save persistent object to disk
	function &getPersistentFieldPropertyDescriptors()
	{
		$result = array(); // anwsering reference to unset var may crash php
		$props =& $this->refPropertyDescriptors();
		if (empty($props))
			return $result;
		reset($props);
		while (list($name) = each($props)) {
			$prop =& $props[$name];
			if ($prop->isFieldProperty() && $prop->getPersistent())
				$result[$name] =& $prop;
		}

		return( $result );
	}

	// get the getPersistentFieldPropertyDescriptors except the idProperties
	function &getPersistentValuePropertyDescriptors()
	{
		$result = array(); // anwsering reference to unset var may crash php
		$props =& $this->getPersistentFieldPropertyDescriptors();
		while (list($name) = each($props)) {
			$prop =& $props[$name];
			if (!$prop->isIdProperty())
				$result[$name] =& $prop;
		}
		return $result;
	}


	function &getPersistentRelationPropertyDescriptors()
	{
		$result = array(); // anwsering reference to unset var may crash php
		$props =& $this->refPropertyDescriptors();
		if (empty($props))
			return $result;
		reset($props);
		while (list($name) = each($props)) {
			$prop =& $props[$name];
			if ($prop->isDerived() && ($idProp =& $prop->getIdPropertyDescriptor())
					&& $idProp->getPersistent() ) {
				$relatedType = $prop->getType();
				if (!class_exists($relatedType)) {
					$classDir = $prop->getClassDir();
					$used = tryIncludeClass($relatedType, $classDir);
					if (!$used)
						trigger_error($this->getName()."::getPersistentRelationPropertyDescriptors class file not found: $classDir/class$relatedType.php",E_USER_WARNING);
				}
				if (is_subclassOr($prop->getType(), 'PntDbObject'))
					$result[$name] =& $prop;
			}
		}
		return( $result );
	}

	function &getTableName()
	{
		if (!isSet($this->tableName)) {
			$clsName = $this->getName();
			$this->tableName = eval("return $clsName::getTableName();");
		}
		return $this->tableName;
	}

	/** Return an array with table names as keys and class names as values.
	* the first table will be used to obtain the id of new objects, with polymorphic 
	* retrieval this table should be the topmost persistent superclass, so that it
	* contains a record for each logical instance in the polymorphism, making the ids
	* unique for all objects within a polymorphism
	* @return Array the map
	*/
	function initTableMap(&$anArray)
	{
		if ($this->getTableName() === null) return;

		$this->refPropertyDescriptors(); //initializes the polymorphismPropName too 
		if ($this->polymorphismPropName) { //polymorphism, include parent tablenames first
			$parentDesc =& $this->getParentclassDescriptor();
			$parentDesc->initTableMap($anArray);
		}
		if ( !isSet($anArray[$this->tableName]) )
			$anArray[$this->tableName] = $this->name; //add own tablename only if it was not already added by parent
	}
	
	/** Return an array with the tablenames as keys, in parent first order
	*/
	function &getTableMap()
	{
		if (!isSet($this->tableMap)) {
			$this->tableMap = array();
			$this->initTableMap($this->tableMap);
		}
		return $this->tableMap;
	}

	/** Returns the field to column mapping for the described class.
	* Current implementations answers one to one mapping, so field name will be equal to columnName.
	*
	* ! Many of the other methods do not yet support column mapping
	* ! Returns reference to cached Array, allways reset before using forEach or while each
	* @return columnNameMapping Associative Array with field names as the keys and (unprefixed) column names as the values
	*/
	function &getFieldMap()
	{
		if ($this->fieldMap !== null)
			return $this->fieldMap;

		$this->fieldMap = array();
		$props =& $this->getPersistentFieldPropertyDescriptors();
		if (empty($props))
			return $this->fieldMap;

		reset($props);
		while (list($name) = each($props)) {
			$prop =& $props[$name];
			$this->fieldMap[$prop->getName()] = $prop->getColumnName();
		}
		return $this->fieldMap;
	}

	/** Returns the field to column mapping for the described class.
	* Current implementations answers one to one mapping, so field name will be equal to columnName.
	*
	* ! Many of the other methods do not yet support column mapping
	* ! Returns reference to cached Array, allways reset before using forEach or while each
	* @return columnNameMapping Associative Array with field names as the keys and (prefixed) column names as the values
	*/
	function &getFieldMapPrefixed()
	{
		if ( isSet($this->fieldMapPrefixed) )
			return $this->fieldMapPrefixed;

		$this->fieldMapPrefixed = array();
		$props =& $this->getPersistentFieldPropertyDescriptors();
		reset($props);
		while (list($name) = each($props)) {
			$prop =& $props[$name];
			$this->fieldMapPrefixed[$prop->getName()] = $prop->getTableName(). '.'. $prop->getColumnName();
		}
		return $this->fieldMapPrefixed;
	}

	function &getFieldMapForTable($tableName)
	{
		$fieldMap = array();
		$props =& $this->getPersistentFieldPropertyDescriptors();
		if (empty($props))
			return $fieldMap;

		reset($props);
		while (list($name) = each($props)) {
			$prop =& $props[$name];
			if ($prop->getTableName() == $tableName || $prop->getName() == 'id')
				$fieldMap[$prop->getName()] = $prop->getColumnName();
		}
		return $fieldMap;
	}

	function &getSelectQueryHandler()
	{
		return $this->getSelectQueryHandlerFor(
			$this->getTableName()
			, $this->getTableMap()
			, $this->getFieldMapPrefixed()
			);
	}

	function &getSelectQueryHandlerFor($tableName, &$tableMap, &$fieldMapPrefixed)
	{
		$clsName = $this->getName();
		$qh = eval("return $clsName::newQueryHandler();");
		
		$qh->select_from($fieldMapPrefixed, $tableName);
		if ($this->polymorphismPropName) 
			$qh->joinAllById($tableMap, $tableName);

		return $qh;
	}

	/** The filters are be produced by static getFilter() on the class.
	* The default for that static is to call back getDefaultFilters().
	* it should add a filter for the label if required.
	* Override the static to get different filters.
	* @return Array of PntSqlFilter the filters by which instances can be searched for
	*/
	function &getFilters($depth)
	{
		$clsName = $this->getName();
		return eval("return $clsName::getFilters('$clsName',$depth);"); //className argument necessary for callback
	}

	function getAllFieldsFilter(&$filters, $type)
	{
		includeClass('PntSqlCombiFilter', 'pnt/db/query');
		$filter =& new PntSqlCombiFilter();
		$filter->set('combinator', 'OR');
		$filter->set('key', "All $type".'fields');
		$filter->set('itemType', $this->getName());
		$filter->set('valueType', $type);
		while (list($key) = each($filters))
			if ($filters[$key]->getValueType() == $type)
				$filter->addPart($filters[$key]);

		return $filter;
	}

	/**
	* @return Array of PntSqlFilter filters derived from metadata
	*/
	function &getFieldFilters()
	{
		$result = array();
		includeClass('PntSqlFilter', 'pnt/db/query');

		$props =& $this->getPersistentValuePropertyDescriptors();
		while (list($name) = each($props)) {
			$prop =& $props[$name];

			$filter =& new PntSqlFilter();
			$filter->set('key', $name);
			$filter->set('itemType', $this->getName());
			$filter->set('label', $prop->getLabel());
			$filter->set('valueType', $prop->getType());
			$result[$name] =& $filter;
		}
		return $result;
	}

	/**
	* @return Array of PntSqlFilter filters derived from metadata
	*/
	function &getDefaultFilters($depth)
	{
		$result =& $this->getFieldFilters();

		if ($depth < 2) return $result;

		$props =& $this->getPersistentRelationPropertyDescriptors();
		while (list($name) = each($props)) {
			$prop =& $props[$name];
			if (!$prop->isMultiValue())
				$this->addReferenceFilters($result, $prop, $depth);
		}
		return $result;
	}

	function addReferenceFilters(&$result, &$prop, $depth)
	{
		includeClass('PntSqlJoinFilter', 'pnt/db/query');
		$filter =& new PntSqlJoinFilter();
		$filter->set('key', $prop->getName());
		$filter->set('itemType', $this->getName());
		$filter->set('label', $prop->getLabel());

		$relatedType = $prop->getType();
		$relatedClsDesc =& PntClassDescriptor::getInstance($relatedType);

		$relatedFilters =& $relatedClsDesc->getDefaultFilters($depth - 1);
		while (list($key) = each($relatedFilters)) {
			$copy = $filter;
			$copy->setNext($relatedFilters[$key]);
			$result[$copy->getId()] = $copy;
		}
	}

	function &getLabelSort()
	{
		$clsName = $this->getName();
		return eval("return $clsName::getLabelSort('$clsName');");
	}

//---------- meta behavior --------------------------------------

	/** Returns an instance of the described class
    * or if polymorphismPropName is set, from class according to data
	* initialized from the data in the supplied associative array.
	* @return PntDbObject
	*/
	function &_getDescribedClassInstanceForData(&$assocArray, &$delegator)
	{
		//polymorhism support: delegate to appropriate classDescriptor
		if ($this->polymorphismPropName && $assocArray[$this->polymorphismPropName] 
				&& $assocArray[$this->polymorphismPropName] != $this->getName() ) {
			includeClass($assocArray[$this->polymorphismPropName], $this->getClassDir() );
			$clsDes =& $this->getInstance($assocArray[$this->polymorphismPropName]);
			return $clsDes->_getDescribedClassInstanceForData($assocArray, $this);
		}

		//create instance of described class and initialize it
		$peanut =& $this->peanutsById[$assocArray['id']];
		if ($peanut === null) {
			$toInstantiate = $this->getName();
			$peanut =& new $toInstantiate();
			$missingFieldsMap = $peanut->initFromData($assocArray, $this->getFieldMap());
			if ($delegator && count($missingFieldsMap) != 0) {
				$result = $this->_loadMissingFields($peanut, $assocArray['id'], $missingFieldsMap, $delegator);
				if ($result) return $result; //query error
			}
			$this->peanutsById[$assocArray['id']] =& $peanut;
		}
		return $peanut;
	}

	/** Polymorphism support: $peanut has been initialized from data, but
	* some field values where not in the data. Probably the query the data 
	* was retrieved with, was created by a superclass' classDescriptor.
	* run another query to retrieve the missing data and initialize 
	* the peanut from it.
	* PRECONDITION: $delegator not null
	* @param PntDbObject The peanut that is being retrieved
	* @param integer $id The id of the object
	* @param Array  $missingFieldsMap With names of missing fields as keys and columnNames as values
	* @param PntClassDescriptor  $delegator The classDescriptor that issued the original query that resulted in delegation and missing fields
	*/
	function _loadMissingFields(&$peanut, $id, &$missingFieldsMap, &$delegator)
	{
		//collect maps for missing tables and fields 
		$delegatorTableMap =& $delegator->getTableMap();
		$ownTableMap =& $this->getTableMap();
		$missingTableMap = array();
		$fieldMapPrefixed = array();
		forEach($missingFieldsMap as $field => $columnName) {
			$prop =& $this->getPropertyDescriptor($field);
			$tableName = $prop->getTableName();
			if (!isSet($delegatorTableMap[$tableName]) ) {
				$missingTableMap[$tableName] = $ownTableMap[$tableName];
				$fieldMapPrefixed[$field] = $tableName. ".". $columnName;
			}
		}
		if (count($fieldMapPrefixed) == 0) return; //assume some of the fields that are normally retrieved by the delegator where left out deliberately

		//there still are fields to retrieve, build query
		$qh = $this->getSelectQueryHandlerFor($this->getTableName(), $missingTableMap, $fieldMapPrefixed);
		$qh->where_equals('id', $id);

		$qh->_runQuery();
		if ($qh->getError())
			return new PntReflectionError($this, $qh->getError());
		if ($qh->getRowCount() != 0) {
			$row=mysql_fetch_assoc($qh->result); 
			$peanut->initFromData($row, $missingFieldsMap);
		}
	}
	
	/** Register that a peanut has been deleted.
	* The peanut must be removed from the cache.
	* @param integer $id the id of the deleted peanut
	*/
	function peanutDeleted($id)
	{
		unSet($this->peanutsById[$id]);
	}
	
	/** Register that a peanut has been insetred.
	* The peanut must be addes to the cache.
	* @param PntDbObject $peanut The peanut that has been inserted
	*/
	function peanutInserted(&$peanut)
	{
		$this->peanutsById[$peanut->get('id')] =& $peanut;
	}

	/** Returns instances of the described class
	* initialized from the supplied PntQueryHandler
	* @return Array or PntReflectionError
	*/
	function &_getPeanutsRunQueryHandler(&$qh, $sortPath=null)
	{
		$qh->_runQuery();
		if ($qh->getError())
			return new PntReflectionError($this, $qh->getError());
		if ($qh->getRowCount())
			while ($row=mysql_fetch_assoc($qh->result)) {
				$instance =& $this->_getDescribedClassInstanceForData($row, $null);
				if (is_ofType($instance, 'PntError'))
					return $instance;
				$result[] =& $instance;
			}
		else
			$result = array();

		if ($sortPath === null)
			return $result;

		$nav =& PntNavigation::_getInstance($sortPath, $this->getName());
		if (is_ofType($nav, 'PntError'))
			return $nav;
		return PntNavigation::_nav1Sort($result, $nav);

	}

	/** Returns all the instances of the described class
	* @return Array or PntReflectionError
	*/
	function &_getPeanuts()
	{
		$qh =& $this->getSelectQueryHandler();

		$sort = $this->getLabelSort();
		$qh->query .= $sort->getSqlForJoin();
		$qh->query .= $sort->getSql();
//print $qh->query;
		return $this->_getPeanutsRunQueryHandler($qh);
	}

	/** Returns the instances of the described class with
	* the specfied property value to be equal to the specfied value
	* @param String propertyName
	* @param variant value
	* @return Array or PntReflectionError
	*/
	function &_getPeanutsWith($propertyName, $value)
	{
		$qh =& $this->getSelectQueryHandler();

		$map =& $this->getFieldMapPrefixed();
		if (!isSet($map[$propertyName]) )
			return new PntReflectionError($this, "Property not in fieldMap: $propertyName");  
			
		if ($propertyName == 'id') {
			$qh->where_equals($map[$propertyName], $value);
		} else {
			$sort =& $this->getLabelSort();
			$qh->query .= $sort->getSqlForJoin();

			$qh->where_equals($map[$propertyName], $value);

			$qh->query .= $sort->getSql();
		}
		return $this->_getPeanutsRunQueryHandler($qh);
	}

	/** Returns the instance of the described class with
	* the id to be equal to the specfied value, or null if none
	* @param integer id
	* @return PntObject, null or PntReflectionEror
	*/
	function &_getPeanutWithId($id)
	{
		$instance =& $this->peanutsById[$id];
		if ($instance !== null)
			return $instance;

		return parent::_getPeanutWithId($id);
	}

	function getPeanutsCount()
	{
		$clsName = $this->getName();
		$qh = eval("return $clsName::newQueryHandler();");
		$qh->select_from(
			array('count(*)')
			, $this->getTableName());
		return $qh->getSingleValue('', $error="Error retrieving number of rows");
	}
}
?>
