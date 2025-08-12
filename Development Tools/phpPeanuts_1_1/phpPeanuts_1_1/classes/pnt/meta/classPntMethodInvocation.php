<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

	
includeClass('PntEvaluation', 'pnt/meta');

/** An object of this class represents method call. 
* it can make the call too.
* @package pnt/meta
*/
class PntMethodInvocation extends PntEvaluation{		
	var $receiver;	
	
	/** get an instance of the proper subclass for 
	* evaluation with the specified key
	*
	* @static
	* @param key String the name of the method 	
	* @param receiver Object the object who's method will be invocated
	* @return PntMethodInvocation
	*/   
	function &_getInstance($key, &$receiver) 
	{
		$obj =& new PntMethodInvocation();
		$obj->setKey($key);
		$obj->setReceiver($receiver);
		return $obj;
	}	

	function &getReceiver() {
		return $this->receiver;
	}	
	
	function setReceiver(&$value) {
		$this->receiver =& $value;		
	}	
	
	function getLabel() {
		return pntToString($this->getReceiver())."::".$this->getKey();
	}
	
	/** calls the method
	* @argument variant $item the parameter of the method	
	* @return variant whatever the evaluated method returns
	*/
	function &_evaluate(&$item)
	{		
		$obj =& $this->getReceiver();
		$func = $this->getKey();						
		return $obj->$func($item);
		
	}

	
}
?>