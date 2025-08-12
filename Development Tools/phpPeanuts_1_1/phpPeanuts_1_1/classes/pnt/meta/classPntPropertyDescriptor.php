<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

includeClass('PntClassDescriptor', 'pnt/meta');

/** An object of this class describes a property of a peanut and supplies default property behavior.
* @see http://www.phppeanuts.org/site/index_php/Menu/212
* @package pnt/meta
*/
class PntPropertyDescriptor extends PntDescriptor
{
	var $name;
	var $type;
	var $minValue;
	var $maxValue;
	var $minLength = 0;
	var $maxLength;
	var $readOnly;
	var $ownerName;
	var $classDir;
	var $isTypePrimitive;

	function PntPropertyDescriptor($name, $type, $readOnly, $minValue, $maxValue, $minLength, $maxLength, $classDir)
	{
		$this->setName($name);
		$this->setType($type);
		$this->isTypePrimitive = in_array($type, $this->primitiveTypes());
		$this->setMinValue($minValue);
		$this->setMaxValue($maxValue);
		$this->setMinLength($minLength);
		$this->setMaxLength($maxLength);
		$this->setReadOnly($readOnly);
		$this->setClassDir($classDir);
	}

	//static
	function &primitiveTypes()
	{
		return array('number', 'string', 'date', 'timestamp', 'time', 'boolean', 'currency', 'email');
	}

	function getName()
	{

		return( $this->name );
	}

	function setName($aString)
	{

		$this->name = $aString;
	}

	function isLegalType($aString)
	{
		return (!isFalseOrNull( array_search($aString, $this->primitiveTypes()) ))
			|| class_exists($aString);
	}

	// answer 'number', 'date', 'string', 'timestamp', 'email' or the name of the class
	// that defines the interface of the objects held by the property.
	function getType()
	{
		return $this->type ;
	}

	function setType($aString)
	{
		$this->type = $aString;
	}

	/** answer wheather the property value may be modified explicitely
	* if false, calling a setter or changing a field value or other
	* explicit property change action will be undefined
	*/
	function getReadOnly()
	{
		return( $this->readOnly );
	}

	function setReadOnly($aValue) {
		// see getter
		$this->readOnly = $aValue;
	}

	function getMinValue()
	{
		// answer tho lowest value that is allowed for this property
		// if used with objects the objects must implement the comparable interface

		return( $this->minValue );
	}

	function setMinValue($aValue)
	{
		// see getter
		$this->minValue = $aValue;
	}

	function getMaxValue()
	{
		// answer tho highest value that is allowed for this property
		// if used with objects the objects must implement the Comparable interface

		return( $this->maxValue );
	}

	function setMaxValue($aValue)
	{
		// see getter
		$this->maxValue = $aValue;
	}

	function getMinLength()
	{
		// answer minimal length that is allowed for this property
		// if used with non-strings, the value will be converted to a string
		// if used with objects the objects must have the label propery

		return( $this->minLength );
	}

	function setMinLength($aValue)
	{
		// see getter
		$this->minLength = $aValue;
	}

	function getMaxLength()
	{
		// answer maximal length that is allowed for this property
		// if used with non-strings, the length will be used
		//   of the value converted to a String
		// if used with objects the lenght of the value of the
		//   label property will be used

		return( $this->maxLength );
	}

	function setMaxLength($aValue)
	{
		// see getter
		$this->maxLength = $aValue;
	}

	function getPersistent()
	{
		// answer wheather the receiver's values are persistent.
		// for derived properties that are persistent the value(s)
		// will automatically be retrieved from persistent storage
		// otherwise the value may be derived from the propertyOptions

		return( false );
	}

	/** Returns the directory of the class file of the type of the property
	* As the type class can not be loaded without knowing its classDir,
	* the classDir must be specified ont the property if it is not default.
	* Default is the classDir of the owner.
	* @return String
	*/
	function getClassDir()
	{
		if ($this->classDir !== null)
			return $this->classDir;

		$owner =& $this->getOwner();
		return $owner->getClassDir();
	}

	/** See Getter */
	function setClassDir($value)
	{
		$this->classDir =& $value;
	}

	function &getOwner()
	{
		return PntClassDescriptor::getInstance($this->ownerName);
	}

	function setOwner(&$anPntClassDescriptor)
	{
		$this->ownerName = $anPntClassDescriptor->getName();
	}

	/** Returns the ValueValidator used for validating property values
	* !Do not call this method directly, use getValueValidator($propertyName)
	* on the object whose property values have to be validated, or better,
	* let the object do the validation using validateGetErrorString($propertyName, $value)
	* @return ValueValidator
	*/
	function &_getValueValidator()
	{
		$validator =& new ValueValidator();
		$validator->initFromProp($this);
		return $validator;
	}

	function isDerived()
	{
		return(false);
	}

	function isFieldProperty()
	{
		return(false);
	}

	function isMultiValue()
	{
		return false;
	}

	function getIdPropertyDescriptor() {
		return null;
	}

	function toString()
	{
		if (!$this->ownerName)
			return parent::toString();

		return $this->ownerName.'>>'.$this->getName();
	}

	//------------------ Meta Behavior -------------------------

	/** Answer the property value for the object
	* If a getter method exists, answer the method result.
	* else derive value through default behavior
	*/
	function &_getValueFor(&$obj)
	{
		//use getter method if there
		$name = $this->getName();
		$mth = "get$name";
		if (method_exists($obj, $mth)) {
			if ($this->isTypePrimitive ) { 
				$value = $obj->$mth();  //FINAL (?) workaround for reference anomalies
				return $value;
			} else 
				return $obj->$mth();  
		}
		return $this->_deriveValueFor($obj);
	}


	/** Set the property value for the object
	* If a setter method exists, use the method and answer result.
	* else set field value
	*/
	function _setValue_for(&$value, &$obj)
	{
		if ($this->getReadOnly())
			return new PntReflectionError(
				$this
				, 'attempt to set value on readOnly property'
			);
		$name = $this->getName();
		$mth = "set$name";
		if (method_exists($obj, $mth))
			return $obj->$mth($value); //use setter method if there
		else
			return $this->_propagateValue_for($value, $obj);
		}

	/** Return the property options for the object
	* If an options method exists, answer the method result.
	* otherwise delegate to the types ClassDescriptor
	/ If the type is a class name and the class has a method 'getInstances'
	/ assume it is a static method, call it and return the result.
	* if ClassDescriptor returns null, or type is not a class
	* return PntReflectionError
	*/
	function &_getOptionsFor(&$obj)
	{
		$name = $this->getName();
		$mth = "get$name".'Options';

		if (method_exists($obj, $mth))
			return $obj->$mth(); //use getter method if there

		$className = $this->getType();
		if (!class_exists($className))
			tryIncludeClass($className, $this->getClassDir());

		if (class_exists($className)) {
			$clsDesc =& PntClassDescriptor::getInstance($className);
			$result =& $clsDesc->_getPeanuts();
			if (is_ofType($result, 'PntError'))
				return new PntReflectionError(
					$this
					, "can not get options: no getter or"
					, $result);
			else
				return $result;
		} else
			return new PntReflectionError(
				$this
				, 'no options getter, and type is not a class'
			);
	}

	function hasOptionsGetter(&$obj)
	{
		$name = $this->getName();
		$mth = "get$name".'Options';

		return method_exists($obj, $mth);
	}

	/** Return the property value for the object
	* Called if no getter method exists.
	* Should be implemented by subClass.
	@param @obj PntObject The object whose property value to answer
	*/
	function &_deriveValueFor(&$obj)
	{
		return new PntReflectionError(
			$this
			, 'Should have been implemented by subclass: _getValueFor'
		);
	}

	/** Set the property value for the object
	* Called if no setter method exists and the property is not readOnly.
	* Should be implemented by subClass.
	@param @value varianr The value to set
	@param @obj PntObject The object whose property value to set
	*/
	function _propagateValue_for(&$value, &$obj)
	{
		return new PntReflectionError(
			$this
			, 'Should have been implemented by subclass: _setValue_for'
		);
	}
}
?>