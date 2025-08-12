<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0


/** Superclass for evaluation objects.
* Specifies and performs a call of a regular funcions (not a method).
* The advantage of evaluation objects is that they can be handed around, 
* manipulated and be part of a data structure, to be executed on demand
* @package pnt/meta
*/
class PntEvaluation {	
	var $key;	
	
	/** get an instance of the proper subclass for 
	* evaluation with the specified key
	*
	* @static
	* @param key String the name of the func tion 	
	* @return PntEvaluation
	*/ 
	  
	function &_getInstance($key) 
	{
		$evalObj =& new PntEvaluation();
		$evalObj->setKey($key);
		return $evalObj;
	}
	
	

	function getKey() {
		return $this->key;
	}
	
	function setKey($value) {
		$this->key = $value;		
	}	
	
	function getLabel() {
		return "::".$this->getKey();
	}
	 
	function getClass()
	{
		return getOriginalClassName(get_class($this));
	}

	/** calls the func tion
	* @argument variant $item the parameter of the func tion
	* @return variant whatever the evaluated func tion returns
	*/
	function &_evaluate(&$item)
	{
		return call_user_func ($this->getKey(), $item);
	}
	
	/* Return the type of the navigation result according to the metadata
	* If no metadata, return null
	* @result String @see PntPropertyDescriptor::getType
	*/
	function getResultType() {
		// no metadata..
		return null;
	}
	
	function getLastProp()
	{
		// no metadata..
		return null;
	}
	
	
}
?>