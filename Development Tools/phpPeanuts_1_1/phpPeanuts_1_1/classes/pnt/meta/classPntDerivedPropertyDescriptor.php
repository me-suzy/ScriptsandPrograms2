<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

includeClass('PntPropertyDescriptor', 'pnt/meta');

/** An object of this class describes a derived property of a peanut 
* and supplies default property behavior.
* @see http://www.phppeanuts.org/site/index_php/Pagina/99
* @package pnt/meta
*/
class PntDerivedPropertyDescriptor extends PntPropertyDescriptor {

	function isDerived() {
		return(true);
	}

	function getPersistent() {
		// answer wheather the receiver's values are persistent.
		// for derived properties that are persistent the value(s)
		// will automatically be retrieved from persistent storage
		// otherwise the value may be derived from the propertyOptions

		$type = $this->getType();
		if (!class_exists($type))
			return false;
			
		$clsDesc =& $this->getOwner();
		return(
			eval("return $type::isPersistent();") 
				&& 	$this->getIdPropertyDescriptor()
			);
	}

	/** Returns the propertyDescriptor of the corresponding id-Property
	* this is the property named as this, extended with 'Id'
	* @return PntPropertyDescriptor
	*/
	function &getIdPropertyDescriptor()
	{
		$clsDesc =& $this->getOwner();
		return $clsDesc->getPropertyDescriptor($this->getName().'Id');
	}
	
	/** Return the property value for the object
	* Called if no getter method exists.
	* Delegate to the types classDescriptor
	* if unsucessfull, retrieve option with key = idProperty.value
	* Return PntReflectionError for primitive types, 
	* if no idProperty, if no options. Return null if option not found
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
				, "Unable to derive value: no getter and no id-property"
			);
		}			
		
		$id = $idProp->_getValueFor($obj);
		if (is_ofType($id, 'PntError')) 
			return new PntReflectionError(
				$this->toString().' Unable to derive value of id-property'
				, $id
			);		
			
		$clsDesc =& PntClassDescriptor::getInstance($className);
		$result =& $clsDesc->_getPeanutWithId($id); 
		if (!is_ofType($result, 'PntError')) 
			return $result;          //sucess!

		$options =& $this->_getOptionsFor($obj);
		if (is_ofType($options, 'PntError')) 
			return new PntReflectionError(
				$this
				, 'Unable to derive value: no getter or'
				, $options
			);
		
		if (empty($options))
			return new PntReflectionError(
				$this
				, "Unable to derive value: options empty"
			);
				
		if (isSet($options[$id]))
			return $options[$id];
		
		return new PntReflectionError(
			$this
			, "Unable to derive value: no option with id: $id"
		);
	}

	/** Set the property value for the object
	* Called if no setter method exists and the property is not readOnly.
	* Delegate to the types classDescriptor
	* Trigger warning for primitive types or if no id property
	@param @value varianr The value to set
	@param @obj PntObject The object whose property value to set
	*/
	function _propagateValue_for(&$value, &$obj) 
	{
		$className = $this->getType();
		if (class_exists($className)) {
			$idProp =& $this->getIdPropertyDescriptor();
			if ($idProp) {
				if ($value === null) {
					$id = null;
				} else {
					$valueClsDes =& $value->getClassDescriptor();
					$valueProp =& $valueClsDes->getPropertyDescriptor('id');
					if (!$valueProp)
						return new PntReflectionError(
							$this
							, "Unable to propagate value: no setter and value has no id-property"
						);				
	
					$id = $valueProp->_getValueFor($value);
					if (is_ofType($optionId, 'PntError'))
						return new PntReflectionError(
							$this
							, "Unable to propagate value: no setter or"
							, $id
						);				
				}				
				$idProp->_setValue_for($id, $obj);
				return true;
			} else
				return new PntReflectionError(
					$this
					, "Unable to propagate value: no setter and no id-property"
				);
		} else
			return new PntReflectionError(
				$this
				, 'unable to propagate value: no getter and type is not a class'
			);
	}

}
?>