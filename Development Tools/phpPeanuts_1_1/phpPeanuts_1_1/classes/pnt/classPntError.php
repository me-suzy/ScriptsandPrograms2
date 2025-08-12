<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0


includeClass('PntObject', 'pnt');

/** Objects of this class may be answered bay framework methods 
* whose name starts with an underscore to signal and describe
* an error.  PntErrors may be nested to describe errors that are
* cuased by other errors. 
* When php5 reaches the mainstream of applications PntError
* will either be replaced by or become exceptions.   
* @see http://www.phppeanuts.org/site/index_php/Pagina/92
* @package pnt
*/
class PntError extends PntObject 
{
	var $origin; //variant, where the error originates from
	var $message; //String
	var $cause; //PntError
	var $causeDescription; //String	

	/** Constructor
	* @param String message The error message
	* @param PntError or String An earlier error that was answered 
	*     by lower level code
	*/
	function PntError($origin=null, $message=null, $cause=null) 
	{
		$this->PntObject();
		$this->setOrigin($origin);
		$this->setMessage($message);
		if (is_string($cause))
			$this->setCauseDescription($cause);
		else
			$this->setCause($cause);
	}
	
	function getErrorTypeLabel() {
		$clsDesc =& $this->getClassDescriptor();
		return $clsDesc->getLabel();
	}
	
	function &getOrigin() {
		return $this->origin;
	}
	
	function setOrigin(&$value) {
		$this->origin =& $value;
	}
	
	function getMessage()
	{
		return empty($this->message)
			? $this->getErrorTypeLabel()
			: $this->message;
	}

	function setMessage($value)
	{
		$this->message = $value;
	}
	
	function &getCause()
	{
		return $this->cause;
	}

	function setCause(&$value)
	{
		$this->cause =& $value;
	}
	
	function getCauseDescription()
	{
		$cause =& $this->getCause();
		if ($this->causeDescription === null && $cause)
			return $cause->getLabel();
			
		return $this->causeDescription;
	}

	function setCauseDescription($value)
	{
		$this->causeDescription = $value;
	}

	function getLabel() 
	{
		if ($this->getOrigin() !== null)
			$result = pntToString($this->getOrigin()) .' ';
		$result .= $this->getMessage();
		$causeDescription = $this->getCauseDescription();
		if ($causeDescription) 
			$result .= " because: $causeDescription";
		return $result;
			
	}
	
}
?>