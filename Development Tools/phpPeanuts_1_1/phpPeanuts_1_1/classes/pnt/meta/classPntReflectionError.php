<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0


includeClass('PntError', 'pnt');

/** PntError specificly returned by meta level code that retrieves peanuts
* @package pnt/meta
*/
class PntReflectionError extends PntError
{

	/** Constructor
	* @param String message The error message
	* @param PntError or String An earlier error that was answered
	*     by lower level code
	*/
	function PntReflectionError($origin, $message=null, $cause=null)
	{
		$this->PntError($origin, $message, $cause);
	}


}
?>