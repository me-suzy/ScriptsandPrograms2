<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

	
includeClass('PntObject', 'pnt');

/** This file serves as a template for creating new subclasses
* of PntObject. 
*/
class PntObjectTemplate extends PntObject {

	function PntObjectTemplate($id=null)
	{
		$this->PntObject($id);
	}

	/** Returns the classFolder
	* @static
	* @return String 
	*/
	function getClassDir() 
	{
		return 'pnt';
	}

	/** @static 
	* @return String the name of the database table the instances are stored in
	* @abstract - override for each subclass
	*/
	function initPropertyDescriptors() {
		// only to be called once

		parent::initPropertyDescriptors();

		//$this->addFieldProp($name, $type, $readOnly=false, $minValue=null, $maxValue=null, $minLength=0, $maxLength=null, $classDir=null, $persistent=true) 
		//$this->addDerivedProp/addMultiValueProp($name, $type, $readOnly=true, $minValue=null, $maxValue=null, $minLength=0, $maxLength=null, $classDir=null) 

	}

	
}
?>