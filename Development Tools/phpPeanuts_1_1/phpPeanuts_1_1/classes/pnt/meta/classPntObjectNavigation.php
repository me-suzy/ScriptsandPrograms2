<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

includeClass('PntEvaluation', 'pnt/meta');		
includeClass('PntObject', 'pnt');	
includeClass('PntNavigation', 'pnt/meta');

/** An object of this class represents a navigational step 
* starting from a logical instance of PntObject.  
* PntNavigations can be nested to create a navigational path.
* In many places in the user interface nopt only properties can be 
* specified, but also paths. This makes the user interface more flexible. 
* PntNavigations can execute the navigation, answering the value of the last property of the path.
* PntObjectNavigation also supports reasoning about navigations
* on a meta level, like getting the type of the results of navigating the entire path 
* @package pnt/meta
*/
class PntObjectNavigation extends PntNavigation {

	function &_setPath($path) 
	{
		$i = strpos($path, '.');	
		if ($i===false) 
			$this->setKey($path);
		else 
			$this->setKey(substr($path,0,$i));

		$clsDes =& PntClassDescriptor::getInstance($this->getItemType());
		$prop =& $clsDes->getPropertyDescriptor($this->getKey());
		if (!$prop )
			return new PntReflectionError(
				$this,
				'Property does not exist'
			);	
		$type = $prop->getType();
		if (!class_exists($type))
			tryIncludeClass($type, $prop->getClassDir());
			
		if ($i===false)
			return $this;
		
		$next =& PntNavigation::_getInstance(
			substr($path,$i+1,strlen($path) - $i)
			, $type
		);
		if (is_ofType($next, 'PntError'))
			return $next;
	
		$this->setNext($next);
		return $this;
	}
	
	/** Single value navigation from the argument using the key.
	* if the argument is null, return null
	* else, use propertyDescriptor to get next value.
	* @argument NntObject item from which to navigate
	* @return variant result of the navigation step
	*/
	function &_step(&$item)
	{
		if ($item === null)
			return null;
			
		if (!is_ofType($item, 'PntObject'))
			return new PntReflectionError(
				$this
				, " can not navigate from item of unsupported type: ". pntToString($item)
			);
		
		$clsDesc =& $item->getClassDescriptor();
		$prop =& $item->getPropertyDescriptor($this->getKey());
		if (!$prop)
			return new PntReflectionError(
				$this
				, " can not navigate because missing properyDescriptor for: ". $item->toString()
			);

		$nextItem =& $prop->_getValueFor($item);	
		if (is_ofType($nextItem, 'PntError')) 
			return new PntReflectionError(
				$this
				, " can not navigate from: ". $item->toString()
				, $nextItem
			);

		return $nextItem;
	}
	
	function &_getOptions(&$item)
	{
		if ($item === null)
			return null;

		$next =& $this->getNext();
		if (!$next) 
			return $this->_getOptionsStep($item);
		
		$nextItem =& $this->_step($item);
		if (is_ofType($nextItem, 'PntError'))
			return $nextItem;
		return $next->_getOptions($nextItem);
	}

	function &_getOptionsStep(&$item)
	{
		if (!is_ofType($item, 'PntObject'))
			return new PntReflectionError(
				$this
				, " can not get options from item of unsupported type: $item"
			);
			
		$clsDesc =& $item->getClassDescriptor();
		$prop =& $item->getPropertyDescriptor($this->getKey());
		if (!$prop)
			return new PntReflectionError(
				$this
				, " can not get options because missing properyDescriptor for: ". $item->toString()
			);

		$nextItem =& $prop->_getOptionsFor($item);
		if (is_ofType($nextItem, 'PntError')) 
			return new PntReflectionError(
				$this
				, " can not get options from: ". $item->toString()
				, $nextItem
			);
		
		return $nextItem;
	}
	

	/* Return the type of the navigation result according to the metadata
	* If no metadata, return null
	* @result String @see PntPropertyDescriptor::getType
	*/
	function getResultType() {
					
		$next =& $this->getNext();
		if ($next)
			return $next->getResultType();
		else
			return $this->getStepResultType();
	}

	/* Return the type of the result of navigating only this step
	*    according to the metadata. If no metadata, return null
	* @result String @see PntPropertyDescriptor::getType
	*/
	function getStepResultType()
	{
		$clsDes =& PntClassDescriptor::getInstance($this->getItemType());
		$prop =& $clsDes->getPropertyDescriptor($this->getKey());
		if (!$prop )
			return null;
		
		return $prop->getType();
	}

	function getFirstPropertyLabel() {
		
		$clsDes =& PntClassDescriptor::getInstance($this->getItemType());
		$prop =& $clsDes->getPropertyDescriptor($this->getKey());
		if (!$prop )
			return $this->getKey();
		
		return $prop->getLabel();
	}

	
	function getPathLabel()
	{
		$next =& $this->getNext();
		if ($next)
			return $this->getFirstPropertyLabel().'.'.$next->getPathLabel();
		else
			return $this->getFirstPropertyLabel();
	}


	/** Should return the path to the id property, but as the meaning of
	* the setted property is not yet clear, the name of the idProperty of this step is returned
	* @return String
	*/
	function getIdPath() 
	{//hack - meaning of the path of the setted property is not clear
		$clsDes =& PntClassDescriptor::getInstance($this->getItemType());
		$prop =& $clsDes->getPropertyDescriptor($this->getKey());
		$idProp =& $prop->getIdPropertyDescriptor();
		if ($idProp)
			return $idProp->getName();
		else 
			return null;
	}
	
	function getResultClassDir() 
	{   //hack - shoud be recursive over steps
		$clsDes =& PntClassDescriptor::getInstance($this->getItemType());
		$prop =& $clsDes->getPropertyDescriptor($this->getKey());
		return $prop->getClassDir();
	}
	
	function isSettedReadOnly() 
	{   //hack - meaning of the path of the setted property is not clear
		$clsDes =& PntClassDescriptor::getInstance($this->getItemType());
		$prop =& $clsDes->getPropertyDescriptor($this->getKey());
		return $prop->getReadOnly();
	}		
	
	function isSettedCompulsory()
	{   //hack - meaning of the path of the setted property is not clear
		$clsDes =& PntClassDescriptor::getInstance($this->getItemType());
		$prop =& $clsDes->getPropertyDescriptor($this->getKey());
		if ($prop->getMinLength() > 0)
			return true;
		$idProp =& $prop->getIdPropertyDescriptor();
		if ($idProp)
			return $idProp->getMinLength() > 0;
		else 
			return false;
	}

	function &getLastProp()
	{
		$next =& $this->getNext();
		if ($next)
			return $next->getLastProp();
		
		$clsDes =& PntClassDescriptor::getInstance($this->getItemType());
		return $clsDes->getPropertyDescriptor($this->getKey());
	}
	
}
?>
