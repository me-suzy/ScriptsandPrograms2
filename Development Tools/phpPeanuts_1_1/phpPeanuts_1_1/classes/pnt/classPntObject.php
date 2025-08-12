<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0


includeClass('PntDerivedPropertyDescriptor', 'pnt/meta');
includeClass('PntFieldPropertyDescriptor', 'pnt/meta');
includeClass('PntMultiValuePropertyDescriptor', 'pnt/meta');
// ValueValidator included by PntSite

/** General Peanut superclass. 
* @see http://www.phppeanuts.org/site/index_php/Pagina/90
* @abstract
* @package pnt
*/
class PntObject {

	/**
	* Constuctors can not decide what class to instatiate.
	* If subclass has to instanciated depending on a parameter,
	* implement it in a subclass of PntClassDescriptor
	* Therefore this constructor should not be called from framework
	*/
	function PntObject()
	{
		// call this constructor from subclasses,
		// except from those using depricated support only

		$clsDes =& $this->getClassDescriptor();
		if (empty($clsDes->propertyDescriptors) )
			$this->initPropertyDescriptors();
	}

	//static
	function isPersistent() {
		return false;
	}

	//static - override if different kind of classDescriptor required
	function getClassDescriptorClass() {
		return 'PntClassDescriptor';
	}

	/** Returns the directory of the class file
	* @static
	* @return String
	*/
	function getClassDir()
	{
		// default
		return 'beheer';
	}

	/** Returns the label of the class, or null if none.
	* To get a defaulted label use the PntClassDescriptor method
	* @static
	* @return String
	*/
	function getClassLabel()
	{
		return null;
	}

	//static - override if required
	// if not overridden and not specified on the classDescriptor,
	// the classDescriptor will make something up, see PntClassDescriptor
	function &getUiColumnPaths() {
		return null;
	}

	/** Returns the paths for columns to be used in reports
	* Default is null, the reporpage will use getUiColumnPaths
	* @static override if required.
	* @return Array of String or String
	*   For keys that are Strings, the keys will be used as column label
	*/
	function &getReportColumnPaths()
	{
		return null;
	}

	//static - override if required
	// if not overridden and not specified on the classDescriptor,
	// the classDescriptor will make something up, see PntClassDescriptor
	function &getUiFieldPaths() {
		return null;
	}

	function &copy()
	{
		return objectCopy($this);
	}

	function getClass()
	{
		return getOriginalClassName(get_class($this));
	}

	function &getClassDescriptor()
	{
		return PntClassDescriptor::getInstance($this->getClass()) ;
	}

	function initPropertyDescriptors()
	{
		$this->addDerivedProp('label', 'string');

		//addFieldProp($name, $type, $readOnly=false, $minValue=null, $maxValue=null, $minLength=0, $maxLength=null, $classDir=null, $persistent=true)
		//addDerivedProp/addMultiValueProp($name, $type, $readOnly=true, $minValue=null, $maxValue=null, $minLength=0, $maxLength=null, $classDir=null)
	}

	function &addFieldProp($name, $type, $readOnly=false, $minValue=null, $maxValue=null, $minLength=0, $maxLength=null, $classDir=null, $persistent=true)
	{
		if (strlen($name)==0) {
			trigger_error("addFieldProp without a name", E_USER_WARNING);
			return null;
		}

		$clsDes =& $this->getClassDescriptor();
		$prop =& new PntFieldPropertyDescriptor($name, $type, $readOnly, $minValue, $maxValue, $minLength, $maxLength, $classDir, $persistent);
		$clsDes->addPropertyDescriptor($prop);
		return $prop;

	}

	function &addDerivedProp($name, $type, $readOnly=true, $minValue=null, $maxValue=null, $minLength=0, $maxLength=null, $classDir=null)
	{
		if (strlen($name)==0) {
			trigger_error("addDerivedProp without a name", E_USER_WARNING);
			return null;
		}

		$clsDes =& $this->getClassDescriptor();
		$prop =& new PntDerivedPropertyDescriptor($name, $type, $readOnly, $minValue, $maxValue, $minLength, $maxLength, $classDir);
		$clsDes->addPropertyDescriptor($prop);
		return $prop;
	}

	function &addMultiValueProp($name, $type, $readOnly=true, $minValue=null, $maxValue=null, $minLength=0, $maxLength=null, $classDir=null)
	{
		if (strlen($name)==0) {
			trigger_error("addMultiValueProp without a name", E_USER_WARNING);
			return null;
		}

		$clsDes =& $this->getClassDescriptor();
		$prop =& new PntMultiValuePropertyDescriptor($name, $type, $readOnly, $minValue, $maxValue, $minLength, $maxLength, $classDir);
		$clsDes->addPropertyDescriptor($prop);
		return $prop;
	}

	function &getPropertyDescriptor($propertyName)
	{
		$clsDes =& $this->getClassDescriptor();
if (!$clsDes) trigger_error($this->toString().' no classdescriptor while getting propertydescriptor: '.$propertyName, E_USER_WARNING);
		return $clsDes->getPropertyDescriptor($propertyName);
/*		// code like in classdescriptor here to get better error message
		$props =& $clsDes->refPropertyDescriptors();
		if (array_key_exists($propertyName, $props))
			return $props[$propertyName]; // gives trouble with references
		else {
			trigger_error($this->toString().' unknown property: '.$propertyName, E_USER_WARNING);
			return null;
		}
*/	}

	//get the value of the property with the specified name
	function &get($propertyName)
	{
		$prop =& $this->getPropertyDescriptor($propertyName);
		if (!$prop) {
			trigger_error(
				$this->getClass()." property not found: $propertyName"
				, E_USER_WARNING);
			return null;
		}
		$result =& $prop->_getValueFor($this);
		if (is_ofType($result, 'PntError')) {
			trigger_error(
				$result->getLabel()
				, E_USER_WARNING);
			return null;
		}
		return $result;
	}

	//set the value of the property with the specified name
	// The value is pased by value, thus copied.
	// If you need to pass the value by reference, use
	// PntPropertyDescriptor::_setValue_for directly
	function set($propertyName, $value)
	{
		$prop =& $this->getPropertyDescriptor($propertyName);
		if (!$prop) {
			trigger_error(
				$this->getClass()." property not found: $propertyName"
				, E_USER_WARNING);
			return null;
		}
		$result =& $prop->_setValue_for($value, $this);
		if (is_ofType($result, 'PntError')) {
			trigger_error(
				$this->getClass().
					"::set($propertyName, ".get_class($value).') '.
					$result->getLabel()
				, E_USER_WARNING);
			return false;
		} else
			return true;
	}

	/** Answer the property options for the object
	* If an options method exists, answer the method result.
	* otherwise delegate to the types ClassDexcriptor
	* if ClassDescriptor answers null, , or type is not a class
	* trigger warning and answer empty array
	*/
	function &getOptions($name)
	{
		$clsDesc =& $this->getClassDescriptor();
		$prop =& $clsDesc->getPropertyDecriptor($name);
		$result =& $prop->_getOptionsFor($obj);
		if (is_ofType($result, 'PntError')) {
			trigger_error(
				$result->getLabel()
				, E_USER_WARNING);
			return array();
		} else
			return $result;
	}

	/** Returns the ValueValidator used for validating property values
	* !Do not call this method directly, use validateGetErrorString($propertyDescriptor, $value)
	* @param prop PntPropertyDescriptor
	* @return ValueValidator
	*/
	function &_getValueValidator(&$prop)
	{
		return $prop->_getValueValidator();
	}

	/** Validate the value for the property. Answer null if valid.
	* Answer the error String if invalid.
	* Override this method to modify the validation behavior.
	* Default is just to use the validator from _getValueValidator.
	* You may change ValueValidator to override the error messages
	* or the generic behavior inherited from PntValueValidator
	* @param prop PntPropertyDescriptor
	* @param value mixed value to be validated.
	* @return String the error string or null if the value is valid
	*/
	function validateGetErrorString(&$prop, $value)
	{
		$validator =& $this->_getValueValidator($prop);
		return $validator->validate($value);
	}

	function basicGetLabel()
	{
		// warning: overriding may change toString() behavior in an unexpected way
		return ('a '.$this->getClass() );
	}

	/** String representation for representation in UI */
	function getLabel()
	{
		// default implementation - should be overridden
		return $this->basicGetLabel();
	}

	/** String representation for debugging purposes */
	function toString()
	{
		$basicLabel = $this->basicGetLabel();
		$label = $this->getLabel();
		if ($label == $basicLabel)
			return $basicLabel; //the label is already showing the class name

		//combine class name and label
		return $this->getClass()."($label)";
	}

	/** Information for the user that is editing the object
	* Should be overridden
	* @return String Html
	*/
	function getEditInfo()
	{
		// default is no information
		return null;
	}
}


?>