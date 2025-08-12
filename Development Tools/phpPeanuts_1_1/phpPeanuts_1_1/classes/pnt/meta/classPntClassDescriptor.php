<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

	
includeClass('PntDescriptor', 'pnt/meta');
includeClass('PntReflectionError', 'pnt/meta');

/** Class of  objects describing a class of peanuts. 
* closest thing to a metaclass.
* @see http://www.phppeanuts.org/site/index_php/Pagina/96
* @package pnt/meta
*/
class PntClassDescriptor extends PntDescriptor {

	var $propertyDescriptors; 
	var $name;

	function PntClassDescriptor($name) 
	{
		$this->propertyDescriptors = array();
		$this->setName($name);
	}

	function &getInstances() 
	{
		static $instances;
		return($instances);
	}

	/** Returns the PntClassDescriptor for the spefied class.
	* ClassDescriptors are cached in a static variable
	* PREREQUISITE: the class must be loaded and support the PntObject metaobjects protocol
	* Class of returned instance depends on what className the static method
	* getClassDescriptorClass on the specified class returns 
	* @static
	* @param name name of the class to describe
	* @return PntClassDescriptor the instance describing the specified class
	*/
	function &getInstance($name) 
	{
		$key = strToLower($name);
		$arr =& PntClassDescriptor::getInstances();
		$clsDesc =& $arr[$key];
		if (!isSet($clsDesc)) {
			if (!$name || !class_exists($name)) {
				trigger_error("class does not exist: $name", E_USER_WARNING);
				return null; //causes trouble in the sender, which is usually better debuggable
			}
			$descriptorClass = eval("return $name::getClassDescriptorClass();");
			
			$clsDesc = new $descriptorClass($name);
			$arr[$key] =& $clsDesc;
		}
			
		return $clsDesc ;
	}

	function isPropertyDescriptorSet($propertyName) {
		//answer wheater the propertyDescriptor with the given name has been added already
		$prop =& $this->propertyDescriptors[$propertyName];
		//if ($prop) print '<BR> set: '.$prop->toString();
		return( isSet($prop) );
	}

	function &getPropertyDescriptors() {
		//return copy with refs to the original propertyDescriptors
		
		$props =& $this->refPropertyDescriptors();
		reset($props);
		while (list($name) = each($props)) 
			$result[$name] =& $props[$name];
		
		return $result;
	}
	
	function &refPropertyDescriptors() 
	{
		if (empty($this->propertyDescriptors)) {
			$this->propertyDescriptors = array();
			$className = $this->getName();
			// print "<BR>initializing propertyDescriptors of $className";
			$anInstance = new $className(); //initializes propertyDescriptors
		}
		return $this->propertyDescriptors;
	}			

	function &getMultiValuePropertyDescriptors() 
	{
		$result = array(); // anwering reference to unset var may crash php
		$props =& $this->refPropertyDescriptors();
		reset($props);
		while (list($name) = each($props)) {
			$prop =& $props[$name];
			if ($prop->isMultiValue())
				$result[$name] =& $prop;
		}
		return( $result );
		
		//forEach no good with objects: it allways copies them
		//so does list($name, $prop)
	}

	function &getSingleValuePropertyDescriptors() 
	{
		$result = array(); // anwering reference to unset var may crash php
		$props =& $this->refPropertyDescriptors();
		reset($props);
		while (list($name) = each($props)) {
			$prop =& $props[$name];
			if (!$prop->isMultiValue())
				$result[$name] =& $prop;
		}
		return( $result );
	}

	function &getPropertyDescriptor($name) 
	{
		$props =& $this->refPropertyDescriptors();
		if (array_key_exists($name, $props))
			return $props[$name]; // gives trouble with references
		else {
//debug:	trigger_error($this->getName().' unknown property: '.$name, E_USER_WARNING);
			return null;
		}
	}

	/** Get the twin of a property whose name and type are...
	* If a property implements a role in a relationship, 
	* the property that implements the role on the other side is its twin.
	* @param String $propName The name of the property whose twin is requested
	* @param String $type The type of the property whose twin is requested
	* @return PntPropertyDescriptor the twin or null if not found. Currently only multi value properties will be returned.
	* PRECONDITION: both the properties type and the twins type must be loaded
	*/ 
	function &getTwinOf_type($propName, $type)
	{
		$props =& $this->getMultiValuePropertyDescriptors();
		while (list($key) = each($props)) {
			if ( $props[$key]->getTwinName() == $propName
					&&  is_subclassOr($type, $props[$key]->getType()) )
				return $props[$key];
		}
	}

	function addPropertyDescriptor(&$anPntPropertyDescriptor) 
	{
			
		$anPntPropertyDescriptor->setOwner($this);
		// print '<BR>'.$anPntPropertyDescriptor->toString();

		//do not use getPropertyDescriptors() here, that would cause infinite recursion
		$this->propertyDescriptors[$anPntPropertyDescriptor->getName()] =& $anPntPropertyDescriptor;
	}

	function hasPropertyDescriptor($name) 
	{
		$props = $this->refPropertyDescriptors();
		return( isSet($props[$name]) );
	}

	/** Returns the directory of the class file
	* @return String 
	*/
	function getClassDir() 
	{
		$clsName = $this->getName();
		return eval("return $clsName::getClassDir();");
	}

	function getLabel() {
		if (isSet($this->label))
			return $this->label;
			
		$clsName = $this->getName();
		$label = eval("return $clsName::getClassLabel();");
		if ($label) return $label;
		
		return $this->getName();
	}

	/** Returns the default user interface table column paths 
	* If the static method PntObject::getUiColumnPaths() has been overridden,
	* its result will be returned. Otherwise the names of
	* the result of getUiPropertyDescriptors will be returned
	* @return Array of String
	*/
	function &getUiColumnPaths() {
		$clsName = $this->getName();
		$paths = eval("return $clsName::getUiColumnPaths();");
		if ($paths!==null) 
			return $paths;
			
		$paths = array_keys($this->getUiPropertyDescriptors());
		
		if (empty($paths))
			return array('label');
		else
			return $paths;
	}
	
	/** Returns the default user interface field paths 
	* If the static method PntObject::getUiFieldPaths() has been overridden,
	* its result will be returned. Otherwise the names of
	* getUiPropertyDescriptors that are not readOnly will be returned
	* @return Array of String
	*/
	function &getUiFieldPaths() {
		$clsName = $this->getName();
		$paths = eval("return $clsName::getUiFieldPaths(\$this);");
		if ($paths!==null) {
			if (!is_array($paths))
				return explode(' ', $paths);
			else
				return $paths;
		}
		return array_keys($this->getUiPropertyDescriptors());
	}

	/** Returns the default user interface single value property descriptors.
	* These are the names of all 
	* sinlge value propertyDesctiptors except label, id or *Id
	* but if that would be empty, label
	* @return Array of PntPropertyDescriptor
	*/
	function &getUiPropertyDescriptors() 
	{
		$result = array();
		$props =& $this->getSingleValuePropertyDescriptors();
		while (list($name) = each($props)) {
			if ($name != 'label' && $name != 'id' && $name != 'oid'
					&&  substr($name, -2) != 'Id')
				$result[$name] = $props[$name];
		}
		return $result;
	}

	function &getParentclassDescriptor() 
	{
		$name = $this->getName();
		$parentName = getOriginalClassName(get_parent_class($name));
		return PntClassDescriptor::getInstance($parentName);
	}


//---------- reflective behavior --------------------------------------

	/** Returns the instance of the described class with 
	* the id to be equal to the specfied value, or null if none
	* @param variant id
	* @return PntObject, null 
	*/
	function &getPeanutWithId($id) 
	{
		$result =& $this->_getPeanutWithId($id);
		if (is_ofType($result, 'PntError')) {
			trigger_error($result->getLabel(), E_USER_WARNING);
			return null;
		}
		return $result;

	}

	/** Returns the instances of the described class
	* @return Array 
	*/
	function &getPeanuts()
	{
		$result =& $this->_getPeanuts();
		if (is_ofType($result, 'PntError')) {
			trigger_error($result->getLabel(), E_USER_WARNING);
			return array();
		} 
		return $result;
	}

	/** Returns the instances of the described class with 
	* the specfied property value to be equal to the specfied value
	* @param String propertyName 
	* @param variant value
	* @return Array 
	*/
	function &getPeanutsWith($propertyName, $value)
	{
		$result =& $this->_getPeanutsWith($propertyName, $value);
		if (is_ofType($result, 'PntError')) {
			trigger_error($result->getLabel(), E_USER_WARNING);
			return array();
		} 
		return $result;

	}
	/** Returns the instance of the described class with 
	* the id to be equal to the specfied value, or null if none
	* @param variant id
	* @return PntObject, null or PntReflectionEror
	*/
	function &_getPeanutWithId($id) 
	{
		// id like null, 0 or '' mean 'no value'
		if (!$id)
			return null;

		if (class_hasMethod($this->getName(), 'getInstance') )
			return call_user_func(array($this->getName(), 'getInstance'), $id);

		$arr =& $this->_getPeanutsWith('id', $id);
		if (is_ofType($arr, 'PntError')) 
			return $arr;
		
		if (count($arr)) {
			reset($arr);
			
			return $arr[key($arr)]; //current would have copied the object
		} else 
			return null;
	}

	/** Returns the instances of the described class
	* @return Array or PntReflectionEror
	*/
	function &_getPeanuts()
	{
		$clsName = $this->getName();
		if (class_hasMethod($clsName, 'getInstances'))
			return eval("return $clsName::getInstances();");
		
		return new PntReflectionError(
			
			$this
			, '_getPeanuts should have been overridden'
		);
	}

	/** Returns the instances of the described class with 
	* the specfied property value to be equal to the specfied value
	* @param String propertyName 
	* @param variant value
	* @return Array or PntReflectionEror
	*/
	function &_getPeanutsWith($propertyName, $value)
	{
		return new PntReflectionError(
			$this
			, '_getPeanutsWith should have been overridden'
		);
	}
}
?>