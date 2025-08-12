<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0


includeClass('PntObject', 'pnt');
includeClass('PntDbClassDescriptor', 'pnt/db');

/** Abstract superclass of persistent peanuts. 
* @see  http://www.phppeanuts.org/site/index_php/Menu/206
* @package pnt/db
*/
class PntDbObject extends PntObject {
	 	 
	var $id = 0;

	/** Constructor
	* Constuctors can not decide what class to instatiate.
	* If subclass has to instanciated depending on the data loaded,
	* implement it in a subclass of PntDbClassDescriptor 
	* Therefore this constructor should not be called from framework
	*/
	function PntDbObject($id=0) 
	{
		$this->PntObject();
		$this->id = 0;
		if ($id>0) {
			$this->loadData($id);	
		}

	}
	
	/** Answer wheather the instances can be loaded from and
	* stored in a database.
	* @static
	* @return boolean;
	*/
	function isPersistent() {
		return true;
	}

	//static - override if different kind of classDescriptor required
	function getClassDescriptorClass() {
		return 'PntDbClassDescriptor';
	}

	/** @static 
	* @return String the name of the database table the instances are stored in
	* @abstract - override for each subclass
	*/
	function getTableName() 
	{
		return null;
	}

	/** @static - override for special filters
	* @return Array of PntSqlFilter the filters by which instances can be searched for
	* @param $className name of the subclass - static will be inherited and will not know the name of the class it is called on :-(
	*/
	function &getFilters($className, $depth)
	{
		// default implementation is to get the defaults from the class descriptor
		$clsDes =& PntClassDescriptor::getInstance($className);
		$defaults =& $clsDes->getDefaultFilters($depth);
		
		return $defaults;
	}

	/** Default implementation - to be overridden by subclasses that override getLabel()
	* @static 
	* @param string $itemType itemType for the sort (may be the sort will be for a subclass)
	* @return PntSqlSort that specifies the sql for sorting the instance records by label
	*/
	function &getLabelSort($subclass)
	{
		includeClass('PntSqlSort', 'pnt/db/query');
		$sort =& new PntSqlSort('label', $subclass);
		$sort->addSortSpec('id');
		return $sort;
	}

	function &newQueryHandler() {
		//call whenever a queryhandler is needed to store/retrieve/delete objects of this class
		//override if specific queryHandler is required
		return new QueryHandler();
	}

	function initPropertyDescriptors() 
	{
		parent::initPropertyDescriptors();
		//                  name, type ,[readOnly,min,max,minSize,maxSize,persistent]
		$this->addFieldProp('id', 'number', false, 0, null, null, '6,0');
		$this->addDerivedProp('oid', 'string');

	}

	/** String representation for representation in UI 
	* @return String id
	*/
	function getLabel() 
	{
		// default implementation - should be overridden
		$id = $this->get('id');
		return "$id";
	}

	// returns a string that identifies the object (case insensitive)
	// the class name is in original case so it should be possible to include the class file 
	// using the class name from the oid.
	function getOid()
	{
		return $this->getClass().'*'.$this->get('id');
	}

	/** Initialize an existing object from an associative array retrieved from the datbase.
	* all fields mapped by the fieldMap are set, the ones for which assocArray
	* holds no key are set to null
	*
	* @param $assocArray Associative Array with the columnNames as keys and the values as values
	* @param $$fieldMap Associative Array with the fieldNames as keys and the corresponding columnNames as values
	*/
	function &initFromData(&$assocArray, &$fieldMap) 
	{
		$missingFieldsMap = array();
		reset($fieldMap);
		if (get_magic_quotes_runtime()) {
			while (list($field) = each($fieldMap))
				if (is_string($assocArray[$fieldMap[$field]]))
					$this->$field = stripSlashes($assocArray[$fieldMap[$field]]);
				else
					$this->$field = $assocArray[$fieldMap[$field]]; 
		} else {
			while (list($field) = each($fieldMap)) {
				if ( isSet( $assocArray[$fieldMap[$field]] ) )
					$this->$field = $assocArray[$fieldMap[$field]];
				else
					$missingFieldsMap[$field] = $fieldMap[$field];
			}
		}
		return $missingFieldsMap;
	}

	function loadData($id)
	{
		//print "<BR>loadData($id)";
		$clsDesc =& $this->getClassDescriptor();
		$qh =& $clsDesc->getSelectQueryHandler();
		$qh->where_equals('id', $id);
		$qh->runQuery(); //takes care of error handling
		
		if ($qh->getRowCount())
			$this->initFromData(
				mysql_fetch_assoc($qh->result)
				, $clsDesc->getFieldMap()
			);
		else 
			trigger_error(
				'er is geen '.$this->getClass()." met id=$id"
				, E_USER_WARNING
			);
		return $result;

	}

	function save() {
		
		$qh=& $this->newQueryHandler();
		$clsDesc =& $this->getClassDescriptor();
		$tableMap =& $clsDesc->getTableMap();
		$insert = $this->isNew();

		reset($tableMap);
		while (list($tableName) = each($tableMap)) {
			$qh->setQueryToSaveObject_table_fieldMap(
				$this 
				, $tableName
				, $clsDesc->getFieldMapForTable($tableName)
				, $insert
			);
			$qh->_runQuery($this->getClass()." opslaan is mislukt");
			if ($qh->getError())
				return trigger_error($qh->getError(), E_USER_ERROR);
	
			if ($this->isNew()) { 
				$this->id = $qh->getInsertId();
				$clsDesc->peanutInserted($this);  //adds this to the cache
			}
		}
	}
	
	function isNew()
	{
		return !$this->id ;
	}
	
	/** This method is called by ObjectSaveAction after the properties have been 
	* set  and before save() is called. If the result of this method is not empty,
    * save is not called and the error messages are set to the page forwarded to.
	* @return arrray of String with error messages, or empty array if none
	*/
	function &getSaveErrorMessages()
	{
		return array();
	}	
	
	function delete() 
	{
		$qh =& $this->newQueryHandler();
		$clsDesc =& $this->getClassDescriptor();
		$tableMap =& $clsDesc->getTableMap();

		reset($tableMap);
		while (list($tableName) = each($tableMap)) {
			$qh->setQueryToDeleteFrom_where_equals(
				$tableName
				, 'id'
				, $this->id
			);
			$qh->_runQuery($this->getClass()." verwijderen is mislukt");
			if ($qh->getError())
				trigger_error($qh->getError(), E_USER_ERROR);
		}		
		
		$clsDesc->peanutDeleted($this->id);
	}

	/** This method is called by ObjectDeleteAction before delete() is called. 
	* If the result of this method is not empty,
    * delete() is not called and the error messages are set to the page forwarded to.
	* @return arrray of String with error messages, or empty array if none
	*/
	function &getDeleteErrorMessages() {
		return array();
	}

}


?>