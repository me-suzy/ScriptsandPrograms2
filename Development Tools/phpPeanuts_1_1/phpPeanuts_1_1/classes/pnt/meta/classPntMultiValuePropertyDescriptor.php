<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

includeClass('PntDerivedPropertyDescriptor', 'pnt/meta');

/** An object of this class describes a multi value property of a peanut 
* and supplies default property behavior.
* @see http://www.phppeanuts.org/site/index_php/Pagina/100
* @package pnt/meta
*/
class PntMultiValuePropertyDescriptor extends PntDerivedPropertyDescriptor 
{
	var $twinName;

	function isMultiValue() 
	{
		return true;
	}

	/** Returns the propertyDescriptor of the corresponding id-Property
	* this is the property of the type which is named as the ownerName, extended with 'Id'
	* @return PntPropertyDescriptor
	*/
	function &getIdPropertyDescriptor()
	{
		$className = $this->getType();
		if (!class_exists($className))
			tryIncludeClass($className, $this->getClassDir());

		if ( !class_exists($className) )
			return null;
			
		$idPropName = $this->getTwinName().'Id';
		$typeClsDesc =& PntClassDescriptor::getInstance($className);
		$result = $typeClsDesc->getPropertyDescriptor($idPropName);
		if ($result) return $result;
		
		return null;
	}

	/** Return the property value for the object
	* Called if no getter method exists.
	* Delegate to the types classDescriptor
	* if unsucessfull, search options
	* Return PntReflectionError for primitive types, 
	* if no idProperty or if the types classDescriptor can not get values
	* 
	* @param @obj PntObject The object whose property value to answer
	*/
	function &_deriveValueFor(&$obj) 
	{
		$className = $this->getType();
		if (!class_exists($className))
			tryIncludeClass($className, $this->getClassDir());

		if (!class_exists($className)) {
			return new PntReflectionError(
				$this
				, 'unable to derive value: no getter and type is not a class'
			);
		}

		$idProp =& $this->getIdPropertyDescriptor();
		if (!$idProp) {
			return new PntReflectionError(
				$this
				, "Unable to derive value: no getter and no id-property: ".$this->getTwinName().'Id'
			);
		}			

		$clsDesc =& $this->getOwner();
		$ownIdProp =& $clsDesc->getPropertyDescriptor('id');
		$id = $ownIdProp->_getValueFor($obj);
		if (is_ofType($id, 'PntError')) 
			return new PntReflectionError(
				$this
				, 'Unable to derive value of idProperty'
				, $id
			);
		
		$typeClsDesc =& PntClassDescriptor::getInstance($this->getType());

		$result =& $typeClsDesc->_getPeanutsWith($idProp->getName(), $id); 
		if (is_ofType($result, 'PntError')) 
			return new PntReflectionError(
				$this
				, 'Unable to derive value: no getter or'
				, $result
			);
		
		return $result;
	}

	/** If a property implements a role in a relationship, 
	* the property that implements the role on the other side is its twin.
	* If a properties type is not a primitive type, the default 
	* twin name is the owners name with the first letter in lower case.
	* @return The name of the twin property in the relationship
	*/ 
	function getTwinName()
	{
		if (isSet($this->twinName))
			return $this->twinName;
		
		if ($this->isTypePrimitive) return null;

		return lcFirst($this->ownerName);
	}

	/** @see getTwinName() 
	* Setting null will override the default
	* @param $value String the twin name
	*/
	function setTwinName($value)
	{
		$this->twinName = $value;
	}
}
?>