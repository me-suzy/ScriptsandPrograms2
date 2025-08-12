<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

includeClass('PntPropertyDescriptor', 'pnt/meta');

/** An object of this class describes a field property of a peanut 
* and supplies default property behavior.
* @see http://www.phppeanuts.org/site/index_php/Pagina/98
* @package pnt/meta
*/
class PntFieldPropertyDescriptor extends PntPropertyDescriptor 	{

	var $persistent = true;
	var $fieldProperties;
	var $tableName;

	function PntFieldPropertyDescriptor($name, $type, $readOnly, $minValue, $maxValue, $minLength, $maxLength, $classDir, $persistent=true) 
	{
		$this->PntPropertyDescriptor($name, $type, $readOnly, $minValue, $maxValue, $minLength, $maxLength, $classDir);
		$this->setPersistent($persistent);
	}

	function getPersistent() 
	{
		// answer wheather the receiver's values are persistent.
				
		return( $this->persistent );
	}
	
	function setPersistent($aBoolean) 
	{
		// see getter
		$this->persistent = $aBoolean;
	}

	function isFieldProperty() 
	{
		return(true);
	}

	/** Return the name of the databaseColumn mapped to this property
	* Current implementation is to return the property name.
	* @see PntDbClassDescriptor::getFieldMap()
	* @return String columnName 
	*/
	function getColumnName() 
	{
		return $this->getName();
	}

	/** Return the name of the database table holding the column mapped to this property
	* Is set at propertydescripter adding to the tableName of the overridden propertyDescriptor.
   * if none, is set to the tableName from the classDescriptor.
	* @see PntDbClassDescriptor::addPropertyDescriptor()
	* @return String tableName
	*/
	function getTableName()
	{
		return $this->tableName;
	}

	function setTableName($aString)
	{
		$this->tableName = $aString;
	}

	/** Return the property value for the object
	* Called if no getter method exists.
	* Returns the field value
	@param @obj PntObject The object whose property value to answer
	*/
	function &_deriveValueFor(&$obj) 
	{
		$name = $this->getName();
		return $obj->$name;
	}

	/** Set the property value for the object
	* Called if no setter method exists and the property is not readOnly.
	* Sets the field value
	@param @value varianr The value to set
	@param @obj PntObject The object whose property value to set
	*/
	function _propagateValue_for(&$value, &$obj) 
	{
		$name = $this->getName();
		return $obj->$name =& $value; //else set the field
	}

	function &getFieldProperties()	
	{
		// depricated support

		if (empty($this->fieldProperties)) {
			$this->fieldProperties["type"]=$this->getType();
			$this->fieldProperties["readOnly"]=$this->getReadOnly();			
			
			$temp = $this->getMinValue();
			$this->fieldProperties["minValue"]=($temp===null?'':$temp);

			$temp = $this->getMaxValue();
			$this->fieldProperties["maxValue"]=($temp===null?'':$temp);

			$temp = $this->getMinLength();
			$this->fieldProperties["minLength"]=($temp===null?'':$temp);

			$temp = $this->getMaxLength();
			$this->fieldProperties["maxLength"]=($temp===null?'':$temp);
		}

		return $this->fieldProperties;
	}
	
	function isIdProperty()
	{
		$name =& $this->getName();
		return strToLower(substr($name, -2)) == 'id';
	}
}
?>